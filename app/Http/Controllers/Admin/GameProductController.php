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
        // Hanya tampilkan produk yang available dan memiliki harga modal valid (tidak termasuk first top up & joki/gacha skin)
        $products = $game->products()
            ->where('status', 'available')
            ->where('price_modal', '>', 0)
            ->where('name', 'not like', '%first top%')
            ->where('name', 'not like', '%first-top%')
            ->where('name', 'not like', '%first topup%')
            ->where('name', 'not like', '%skin%')
            ->where('product_code', 'not like', '%skin%')
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
        // Sanitize price inputs if formatted with dots/commas
        $input = $request->all();
        if (isset($input['price_sell'])) {
            $input['price_sell'] = str_replace(',', '.', str_replace('.', '', (string)$input['price_sell']));
        }
        if (isset($input['price_normal']) && $input['price_normal'] !== '') {
            $input['price_normal'] = str_replace(',', '.', str_replace('.', '', (string)$input['price_normal']));
        }
        $request->merge($input);

        $validated = $request->validate([
            'price_sell' => 'required|numeric|min:0',
            'price_normal' => 'nullable|numeric|min:0',
            'is_promo' => 'nullable',
            'status' => 'required|string|in:available,empty,error',
            'name' => 'nullable|string'
        ]);

        $updateData = [
            'price_sell' => (float)$validated['price_sell'],
            'price_normal' => !empty($validated['price_normal']) ? (float)$validated['price_normal'] : null,
            'is_promo' => $request->has('is_promo') && $request->is_promo == '1' ? 1 : 0,
            'status' => $validated['status'],
        ];

        if (!empty($validated['name'])) {
            $updateData['name'] = $validated['name'];
        }

        $product->update($updateData);

        if ($validated['status'] !== 'available') {
            $product->delete();
        }

        return redirect()->route('admin.games.products.index', $game)->with('success', "Harga produk '{$product->name}' berhasil diperbarui menjadi Rp " . number_format($product->price_sell, 0, ',', '.'));
    }

    public function destroy(Game $game, GameProduct $product)
    {
        $product->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }

    public function cleanupNonIdr(Game $game)
    {
        $deleted = 0;
        $all = $game->products()->get();
        foreach ($all as $p) {
            $name = $p->name;
            $code = $p->product_code;
            if (preg_match('/\b(php|myr|inr|thb|sgd|usd|eur|brl|vnd|twd|sar|hkd|brazil|malaysia|philippines|thailand|singapore|vietnam|taiwan)\b/i', $name)
                || preg_match('/(?:^|[-_])(php|myr|inr|thb|sgd|usd|eur|brl|vnd|twd|sar|hkd)(?:$|[-_\d])/i', $code)) {
                $p->delete();
                $deleted++;
            }
        }

        return back()->with('success', "Berhasil membersihkan {$deleted} produk non-IDR (mata uang asing)!");
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

        // Cek apakah ada exact match nama game di data VIP Reseller (contoh: "Valorant" vs "Voucher Valorant")
        $targetFilter = trim($request->filter_value);
        $hasExactGameMatch = false;
        foreach ($items as $chkItem) {
            if (strcasecmp(trim($chkItem['game'] ?? ''), $targetFilter) === 0) {
                $hasExactGameMatch = true;
                break;
            }
        }

        foreach ($items as $item) {
            $name = trim($item['name']);
            $nameUpper = strtoupper($name);
            $nameLower = strtolower($name);
            $codeUpper = strtoupper($item['code'] ?? '');
            $itemGame = trim($item['game'] ?? '');
            $modal = VipResellerService::getAccountPrice($item['price'] ?? 0);

            // 1. FILTER SAMPAH & HARGA MODAL NOL
            if ($modal <= 0) continue; 
            if (str_contains($nameUpper, 'OPEN') || str_contains($nameUpper, 'CLOSE') || str_contains($nameUpper, 'INFO') || str_contains($nameUpper, 'RATE') || str_contains($nameUpper, 'TESTING') || str_contains($nameUpper, 'DUMMY')) continue;
            
            // 2. FILTER STATUS EMPTY & GANGGUAN (HANYA AMBIL YANG STATUS AVAILABLE)
            if (strtolower($item['status']) !== 'available') {
                continue; 
            }

            // 3. FILTER MATA UANG ASING (HANYA AMBIL IDR / INDONESIA, DENGAN BATAS KATA AGAR TIDAK SALAH DETEKSI SEPERTI CANVAEDU)
            if (preg_match('/\b(php|myr|inr|thb|sgd|usd|eur|brl|vnd|twd|sar|hkd|brazil|malaysia|philippines|thailand|singapore|vietnam|taiwan)\b/i', $name) 
                || preg_match('/(?:^|[-_])(php|myr|inr|thb|sgd|usd|eur|brl|vnd|twd|sar|hkd)(?:$|[-_\d])/i', $codeUpper)) {
                continue;
            }

            // 4. FILTER NAMA GAME (JANGAN CAMPUR GAME LANGSUNG DENGAN VOUCHER)
            if ($hasExactGameMatch) {
                // Jika ada "Valorant", jangan ambil "Voucher Valorant"
                if (strcasecmp($itemGame, $targetFilter) !== 0) {
                    continue;
                }
            } else {
                if (stripos($itemGame, $targetFilter) === false) {
                    continue;
                }
                // Jika pencarian BUKAN mencari voucher, buang kategori yang berawalan "Voucher "
                if (!str_contains(strtolower($targetFilter), 'voucher') && str_contains(strtolower($itemGame), 'voucher')) {
                    continue;
                }
            }

            $isAppOrVoucher = in_array($game->category, ['Aplikasi Premium', 'Voucher', 'App & Entertainment']) 
                || str_contains(strtolower($game->category ?? ''), 'app') 
                || str_contains(strtolower($game->category ?? ''), 'aplikasi')
                || str_contains(strtolower($game->category ?? ''), 'streaming')
                || str_contains(strtolower($game->category ?? ''), 'voucher')
                || str_contains(strtolower($game->name), 'premium')
                || str_contains(strtolower($game->name), 'voucher');

            $isPass = (str_contains($nameLower, 'pass') || str_contains($nameLower, 'weekly') || str_contains($nameLower, 'starlight') || str_contains($nameLower, 'twilight') || str_contains($nameLower, 'member') || str_contains($nameLower, 'bundle') || str_contains($nameLower, 'gsuite') || str_contains($nameLower, 'invite') || str_contains($nameLower, 'individu') || str_contains($nameLower, 'family') || str_contains($nameLower, 'private') || str_contains($nameLower, 'shared') || str_contains($nameLower, 'garansi') || str_contains($nameLower, 'bulan') || str_contains($nameLower, 'hari') || str_contains($nameLower, 'tahun'));
            
            $nameForMath = str_replace('.', '', $nameLower);
            $qty = 0;

            if ($isAppOrVoucher || $isPass) {
                // Untuk Aplikasi Premium, Streaming, Voucher, atau Variasi Paket:
                // Setiap varian adalah produk unik
                $uniqueKey = preg_replace('/[^a-z0-9]/', '', $nameLower);
            } else {
                if (preg_match('/^(\d+)\s*\+\s*(\d+)\s*(?:diamond|points|point|vp|uc|cp|dm)/i', $nameForMath, $m)) {
                    $qty = (int)$m[1] + (int)$m[2]; 
                } elseif (preg_match('/^(\d+)\s*(?:diamond|points|point|vp|uc|cp|dm|gems|tokens|coin|coins)/i', $nameForMath, $m)) {
                    $qty = (int)$m[1]; 
                } elseif (preg_match('/(\d+)\s*(?:diamond|points|point|vp|uc|cp|dm|gems|tokens|coin|coins)/i', $nameForMath, $m)) {
                    $qty = (int)$m[1];
                } elseif (preg_match('/^(\d+)\s*\+\s*(\d+)/', $nameForMath, $m)) {
                    $qty = (int)$m[1] + (int)$m[2];
                } elseif (preg_match('/^(\d+)$/', trim($nameForMath), $m)) {
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

    public function cronSyncAll(VipResellerService $api)
    {
        $games = Game::where('is_active', true)->get();
        $totalUpdated = 0;

        $filterMap = [
            'mobile-legend' => 'Mobile Legends',
            'mobile-legends' => 'Mobile Legends',
            'valorant' => 'Valorant',
            'free-fire' => 'Free Fire',
            'freefire' => 'Free Fire',
            'roblox' => 'Roblox',
            'pubg' => 'PUBG',
            'pubg-mobile' => 'PUBG',
            'marvel-rivals' => 'Marvel Rivals',
        ];

        foreach ($games as $game) {
            try {
                $kw = $filterMap[$game->slug] ?? $game->name;
                $res = $api->getGameProducts($kw);
                if (isset($res['result']) && $res['result'] === true && !empty($res['data'])) {
                    $apiItems = collect($res['data'])->keyBy('code');
                    $localProds = $game->products()->get();
                    foreach ($localProds as $lp) {
                        if ($apiItems->has($lp->product_code)) {
                            $aItem = $apiItems->get($lp->product_code);
                            $aStatus = strtolower($aItem['status']) === 'available' ? 'available' : 'empty';
                            $latestModal = VipResellerService::getAccountPrice($aItem['price'] ?? $lp->price_modal);
                            
                            $margin = $lp->price_sell > $lp->price_modal ? ($lp->price_sell - $lp->price_modal) : ceil($latestModal * 0.05);
                            $newSell = $latestModal + $margin;
                            
                            if ($lp->price_modal != $latestModal || $lp->status !== $aStatus) {
                                $lp->update([
                                    'price_modal' => $latestModal,
                                    'price_sell' => ceil($newSell),
                                    'status' => $aStatus,
                                ]);
                                $totalUpdated++;
                            }
                        }
                    }
                }
            } catch (\Exception $e) {}
        }

        // Ambil saldo modal terbaru
        try {
            $prof = $api->getProfile();
            if (isset($prof['data']['balance'])) {
                \App\Models\Setting::set('vip_balance_threshold', (string)$prof['data']['balance']);
                \App\Models\Setting::set('vip_reseller_balance', (string)$prof['data']['balance']);
            }
        } catch (\Exception $e) {}

        // AUTO-EXPIRE SOFTWARE SUBSCRIPTIONS (Otomatis Expire Langganan Software yang Lewat Batas)
        $expiredSubs = 0;
        try {
            if (class_exists(\App\Models\Subscription::class)) {
                $expiredSubs = \App\Models\Subscription::where('status', 'ACTIVE')
                    ->whereNotNull('end_date')
                    ->where('end_date', '<', now()->toDateString())
                    ->update(['status' => 'EXPIRED']);
            }
        } catch (\Exception $e) {}

        return response()->json([
            'success' => true,
            'message' => "Auto Sync Selesai! {$totalUpdated} game produk, saldo modal, dan {$expiredSubs} status software berhasil diperbarui otomatis.",
        ]);
    }
}
