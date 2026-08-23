<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameProduct;
use App\Services\VipResellerService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GameProductController extends Controller
{
    public function index(Game $game)
    {
        // Hanya tampilkan produk yang available dan memiliki harga modal valid
        $products = $game->products()
            ->where('status', 'available')
            ->where('price_modal', '>', 0)
            ->orderBy('price_modal')
            ->get();
            
        return view('admin.games.products.index', compact('game', 'products'));
    }

    public function edit(Game $game, GameProduct $product)
    {
        return view('admin.games.products.edit', compact('game', 'product'));
    }

    public function update(Request $request, Game $game, GameProduct $product)
    {
        $validated = $request->validate([
            'price_sell' => 'required|numeric|min:0',
            'status' => 'required|string|in:available,empty,error',
            'name' => 'required|string'
        ]);

        $product->update($validated);

        if ($validated['status'] !== 'available') {
            $product->delete();
        }

        return redirect()->route('admin.games.products.index', $game)->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Game $game, GameProduct $product)
    {
        $product->delete();
        return redirect()->route('admin.games.products.index', $game)->with('success', 'Produk berhasil dihapus.');
    }

    public function syncForm(Game $game)
    {
        return view('admin.games.products.sync', compact('game'));
    }

    public function syncProcess(Request $request, Game $game, VipResellerService $api)
    {
        // Cegah PHP membunuh proses sebelum selesai (Beri waktu 5 menit)
        set_time_limit(300);
        ini_set('max_execution_time', '300');
        $request->validate([
            'filter_value' => 'required|string', 
            'markup_flat' => 'required|numeric|min:0',
            'markup_percent' => 'required|numeric|min:0',
            'mass_promo_percent' => 'nullable|numeric|min:0'
        ]);

        try {
            $response = $api->getGameProducts($request->filter_value);

            if (!isset($response['result']) || !$response['result']) {
                return back()->with('error', 'Gagal menarik data dari VIP Reseller. ' . ($response['message'] ?? ''));
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Koneksi ke VIP Reseller terputus atau time out. Detail: ' . $e->getMessage());
        }

        $items = $response['data'];
        $cheapestItems = [];

        foreach ($items as $item) {
            $name = trim($item['name']);
            $nameUpper = strtoupper($name);
            $nameLower = strtolower($name);
            $modal = $item['price']['special'] ?? ($item['price']['h2h'] ?? ($item['price']['premium'] ?? ($item['price']['basic'] ?? 0)));

            // 1. FILTER SAMPAH & HARGA MODAL NOL
            if ($modal <= 0) continue; 
            if (str_contains($nameUpper, 'OPEN') || str_contains($nameUpper, 'CLOSE') || str_contains($nameUpper, 'INFO') || str_contains($nameUpper, 'RATE') || str_contains($nameUpper, 'TESTING') || str_contains($nameUpper, 'DUMMY')) continue;
            
            // 2. FILTER STATUS EMPTY & GANGGUAN (HANYA AMBIL YANG STATUS AVAILABLE)
            if (strtolower($item['status']) !== 'available') {
                continue; 
            }

            // 3. FILTER NON-IDN DAN VOUCHER
            if (preg_match('/\b(global|brazil|br|my|malaysia|ph|philippines|skin|charisma|p\.ace|champion|lightborn|epic|voucher|gift card|eur|usd|hkd|riot cash)\b/i', $name)) {
                continue;
            }

            if (stripos($item['game'], $request->filter_value) !== false) {
                $isPass = (str_contains($nameLower, 'pass') || str_contains($nameLower, 'weekly') || str_contains($nameLower, 'starlight') || str_contains($nameLower, 'twilight') || str_contains($nameLower, 'member') || str_contains($nameLower, 'bundle'));
                
                $nameForMath = str_replace('.', '', $nameLower);
                $qty = 0;

                if ($isPass) {
                    $uniqueKey = preg_replace('/[^a-z0-9]/', '', $nameLower);
                } else {
                    if (preg_match('/^(\d+)\s*\+\s*(\d+)\s*diamond/i', $nameForMath, $m)) {
                        $qty = (int)$m[1] + (int)$m[2]; 
                    } elseif (preg_match('/^(\d+)\s*diamond/i', $nameForMath, $m)) {
                        $qty = (int)$m[1]; 
                    } elseif (preg_match('/(\d+)\s*\+\s*(\d+)/', $nameForMath, $m)) {
                        $qty = (int)$m[1] + (int)$m[2];
                    } elseif (preg_match('/(\d+)/', $nameForMath, $m)) {
                        $qty = (int)$m[1];
                    }
                    if ($qty > 0) {
                        $uniqueKey = 'qty_' . $qty; 
                    } else {
                        $uniqueKey = preg_replace('/[^a-z0-9]/', '', $nameLower);
                    }
                }

                // SIMPAN YANG PALING MURAH SAJA!
                if (!isset($cheapestItems[$uniqueKey]) || $modal < $cheapestItems[$uniqueKey]['modal']) {
                    $cheapestItems[$uniqueKey] = [
                        'code' => $item['code'],
                        'name' => $item['name'],
                        'modal' => $modal,
                        'status' => 'available',
                        'unique_key' => $uniqueKey,
                    ];
                }
            }
        }

        try {
            $count = 0;
            $syncedCodes = [];

            foreach ($cheapestItems as $uniqueKey => $cItem) {
                $percentProfit = $cItem['modal'] * ($request->markup_percent / 100);
                $jual = $cItem['modal'] + $percentProfit + $request->markup_flat;
                $jual = ceil($jual);

                // Trik Diskon Masal
                $isPromo = $request->has('mass_promo_active');
                $priceNormal = null;
                if ($isPromo) {
                    $discountDec = $request->mass_promo_percent / 100;
                    if ($discountDec >= 1) $discountDec = 0.99;
                    $priceNormal = ceil($jual / (1 - $discountDec));
                    $priceNormal = round($priceNormal / 100) * 100;
                }

                GameProduct::updateOrCreate(
                    [
                        'game_id' => $game->id, 
                        'product_code' => $cItem['code'],
                    ],
                    [
                        'name' => $cItem['name'],
                        'price_modal' => $cItem['modal'],
                        'price_sell' => $jual,
                        'status' => 'available',
                        'is_promo' => $isPromo,
                        'price_normal' => $priceNormal
                    ]
                );
                $syncedCodes[] = $cItem['code'];
                $count++;
            }

            // Hapus permanen produk lama yang statusnya empty atau tidak ada di API
            if (!empty($syncedCodes)) {
                GameProduct::where('game_id', $game->id)
                    ->whereNotIn('product_code', $syncedCodes)
                    ->delete();
            }
            
            GameProduct::where('game_id', $game->id)
                ->where('status', '!=', 'available')
                ->delete();

            return redirect()->route('admin.games.products.index', $game)->with('success', "Sinkronisasi Berhasil! {$count} item aktif dan valid berhasil diperbarui.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses data: ' . $e->getMessage());
        }
    }
}
