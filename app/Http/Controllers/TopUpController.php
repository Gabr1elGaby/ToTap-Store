<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameProduct;
use Illuminate\Http\Request;

class TopUpController extends Controller
{
    public function index()
    {
        return view('topup.index');
    }

    public function show($slug)
    {
        $game = Game::where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        $allProducts = $game->products()->where('status', 'available')->orderBy('price_sell')->get();

        $uniqueProducts = collect();
        $seenKeys = [];

        foreach ($allProducts as $product) {
            $name = strtolower(trim($product->name));
            
            // 1. FILTERING STRICT: Hapus produk Skin, Charisma, dan NON-IDN (Global/Luar Negeri)
            if (
                str_contains($name, 'skin') || 
                str_contains($name, 'charisma') || 
                str_contains($name, 'p.ace') || 
                str_contains($name, 'champion') || 
                str_contains($name, 'lightborn') || 
                str_contains($name, 'epic') ||
                str_contains($name, 'global') ||
                str_contains($name, 'brazil') ||
                str_contains($name, 'br') || // hati-hati br bisa match "bronze", but usually they use (BR)
                str_contains($name, 'my') ||
                str_contains($name, 'malaysia') ||
                str_contains($name, 'ph') ||
                str_contains($name, 'philippines')
            ) {
                // Jangan buang kalau "bundle" atau kata lain yang ngandung br/my secara kebetulan
                // Lebih aman kita cek dengan spasi atau kurung
                if (preg_match('/\b(global|brazil|br|my|malaysia|ph|philippines|skin|charisma|p\.ace|champion|lightborn|epic)\b/i', $name)) {
                    continue;
                }
            }
            
            // Hilangkan titik (.) yang digunakan sebagai pemisah ribuan agar 1.446 terbaca 1446
            $nameForMath = str_replace('.', '', $name);

            // 2. CEK KATEGORI PASS / MEMBER / BUNDLE
            $isPass = (str_contains($name, 'pass') || str_contains($name, 'weekly') || str_contains($name, 'starlight') || str_contains($name, 'twilight') || str_contains($name, 'member') || str_contains($name, 'bundle'));
            
            $uniqueKey = $name;
            $qty = 0; // Inisialisasi variabel jumlah

            if ($isPass) {
                $uniqueKey = preg_replace('/[^a-z0-9]/', '', $name);
                // Untuk pass, coba cari angka jika ada
                if (preg_match('/^(\d+)/', $nameForMath, $m)) {
                    $qty = (int)$m[1];
                }
            } else {
                // UNTUK DIAMOND: Ekstrak jumlah total diamond dengan pintar
                if (preg_match('/^(\d+)\s*\+\s*(\d+)\s*diamond/i', $nameForMath, $m)) {
                    $qty = (int)$m[1] + (int)$m[2]; 
                } 
                elseif (preg_match('/^(\d+)\s*diamond/i', $nameForMath, $m)) {
                    $qty = (int)$m[1]; 
                }
                elseif (preg_match('/(\d+)\s*\+\s*(\d+)/', $nameForMath, $m)) {
                    $qty = (int)$m[1] + (int)$m[2];
                }
                elseif (preg_match('/(\d+)/', $nameForMath, $m)) {
                    $qty = (int)$m[1];
                }

                if ($qty > 0) {
                    $uniqueKey = 'qty_' . $qty; 
                } else {
                    $uniqueKey = preg_replace('/[^a-z0-9]/', '', $name);
                }
            }
            
            $product->_qty = $qty;
            
            // Buat nama pendek yang rapi (Hapus tulisan bonus dalam kurung agar rapi di HP)
            $shortName = $product->name;
            $shortName = preg_replace('/\(.*?\)/', '', $shortName); // Hapus semua dalam kurung
            $shortName = str_ireplace('Diamonds', 'DM', $shortName); // Ubah Diamonds jadi DM biar makin pendek
            $shortName = str_ireplace('Diamond', 'DM', $shortName);
            $shortName = str_ireplace('Bonus', '', $shortName);
            $shortName = str_ireplace('First Top Up', '', $shortName);
            $shortName = preg_replace('/\s+\+\s+/', ' ', $shortName); // Hapus spasi + spasi
            // Jika nama hasil regex kosong, pakai qty saja
            if (trim($shortName) == '' || trim($shortName) == 'DM' || trim($shortName) == '+') {
                $shortName = $qty . ' DM';
            }
            $product->_short_name = trim($shortName);

            // 3. DEDUPLIKASI: Simpan hanya versi termurah
            if (!isset($seenKeys[$uniqueKey])) {
                $seenKeys[$uniqueKey] = true;
                $uniqueProducts->push($product);
            }
        }

        // 4. PENGELOMPOKAN KE KATEGORI (Universal untuk Semua Game)
        $categories = [
            'Pass & Member' => collect(),  // Battle Pass, Starlight, Weekly, Member
            'Item & Lainnya' => collect(), // Name Change, Squad, dll
            'Mata Uang Game' => collect(), // Diamonds, Robux, Points, Cash
        ];

        foreach ($uniqueProducts as $product) {
            $name = strtolower($product->name);
            
            if (str_contains($name, 'weekly') || str_contains($name, 'pass') || str_contains($name, 'starlight') || str_contains($name, 'member') || str_contains($name, 'battle') || str_contains($name, 'subscription')) {
                $categories['Pass & Member']->push($product);
            } elseif (str_contains($name, 'name') || str_contains($name, 'nama') || str_contains($name, 'squad') || str_contains($name, 'twilight') || str_contains($name, 'crystal') || str_contains($name, 'ticket') || str_contains($name, 'token') || str_contains($name, 'gift card')) {
                $categories['Item & Lainnya']->push($product);
            } else {
                $categories['Mata Uang Game']->push($product);
            }
        }

        // Urutkan produk di dalam kategori berdasarkan jumlah (_qty) terkecil ke terbesar!
        $finalCategories = [];
        foreach ($categories as $catName => $items) {
            if ($items->isNotEmpty()) {
                $finalCategories[$catName] = $items->sort(function ($a, $b) {
                    if ($a->_qty == $b->_qty) {
                        return $a->price_sell <=> $b->price_sell;
                    }
                    return $a->_qty <=> $b->_qty;
                })->values(); // Reset index
            }
        }

        return view('topup.show', [
            'game' => $game,
            'categories' => $finalCategories
        ]);
    }

    public function process(Request $request, $slug)
    {
        $game = Game::where('slug', $slug)->firstOrFail();

        $rules = [
            'product_id' => 'required|exists:game_products,id',
            'player_id' => 'required|string|max:255',
        ];

        if ($game->requires_zone_id) {
            $rules['zone_id'] = 'required|string|max:255';
        }

        $request->validate($rules);
        
        $product = GameProduct::findOrFail($request->product_id);
        
        // Buat ID Transaksi Unik (Order ID)
        $orderId = 'TRX-' . time() . '-' . rand(100, 999);
        
        // Simpan ke Database
        $transaction = \App\Models\Transaction::create([
            'id' => $orderId,
            'game_id' => $game->id,
            'game_product_id' => $product->id,
            'target_field_1' => $request->player_id,
            'target_field_2' => $request->zone_id,
            'amount' => $product->price_sell,
            'status' => 'pending',
        ]);
        
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
        
        // MATIKAN SSL VERIFICATION AGAR JALAN DI LOCALHOST WINDOWS
        \Midtrans\Config::$curlOptions = [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => [], // Fix Midtrans SDK Bug
        ];
        
        // Buat Parameter Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $product->price_sell,
            ],
            'item_details' => [
                [
                    'id' => $product->product_code,
                    'price' => (int) $product->price_sell,
                    'quantity' => 1,
                    'name' => substr($product->name, 0, 50),
                ]
            ],
            'customer_details' => [
                'first_name' => $request->player_id,
                'email' => 'customer@totap.com', // Opsional, bisa minta user email nanti
            ]
        ];
        
                try {
            // GUNAKAN CORE API (Native QRIS / VA)
            if ($request->payment_method === 'qris') {
                $coreParams = [
                    'payment_type' => 'gopay',
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => (int) $product->price_sell,
                    ]
                ];
                $response = \Midtrans\CoreApi::charge($coreParams);
                
                $qrUrl = '';
                if (isset($response->actions)) {
                    foreach ($response->actions as $action) {
                        if ($action->name === 'generate-qr-code') {
                            $qrUrl = $action->url;
                        }
                    }
                }
                $snapData = json_encode(['type' => 'qris', 'qr_url' => $qrUrl]);
                $transaction->update(['snap_token' => $snapData]);
                
            } elseif (in_array($request->payment_method, ['bca_va', 'bni_va', 'bri_va', 'mandiri_va', 'permata_va'])) {
                $bank = str_replace('_va', '', $request->payment_method);
                
                $coreParams = [
                    'payment_type' => ($bank === 'mandiri') ? 'echannel' : 'bank_transfer',
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => (int) $product->price_sell,
                    ]
                ];
                
                if ($bank === 'mandiri') {
                    $coreParams['echannel'] = [
                        'bill_info1' => 'Payment For',
                        'bill_info2' => 'Top Up Game'
                    ];
                } else {
                    $coreParams['bank_transfer'] = [
                        'bank' => $bank
                    ];
                }
                
                $response = \Midtrans\CoreApi::charge($coreParams);
                
                $vaNumber = '';
                if ($bank === 'mandiri' && isset($response->bill_key)) {
                    $vaNumber = $response->biller_code . ' - ' . $response->bill_key;
                } elseif (isset($response->va_numbers[0])) {
                    $vaNumber = $response->va_numbers[0]->va_number;
                }
                
                $snapData = json_encode(['type' => 'va', 'bank' => strtoupper($bank), 'va_number' => $vaNumber]);
                $transaction->update(['snap_token' => $snapData]);
            } else {
                // Fallback to snap if needed, but we don't need it.
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                $transaction->update(['snap_token' => $snapToken]);
            }
            
            // Arahkan ke halaman checkout
            return redirect()->route('topup.checkout.show', $transaction->id);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function checkNickname(Request $request, $slug)
    {
        try {
            $game = Game::where('slug', $slug)->firstOrFail();
            $api = app(\App\Services\VipResellerService::class);
            
            $target1 = $request->player_id;
            $target2 = $request->zone_id ?? '';
            
            // Format khusus untuk Valorant (Digabungkan dengan #)
            if (strtolower($game->slug) === 'valorant') {
                // Hapus # jika user terlanjur ngetik
                $target2 = ltrim($target2, '#');
                $target1 = $target1 . '#' . $target2;
                $target2 = '';
            }
            
            $response = $api->checkNickname($game->slug, $target1, $target2);
            
            // Decode URL Encoding dari VIP Reseller (e.g. 4Some1%20%2321104 -> 4Some1 #21104)
            if (isset($response['result']) && $response['result'] === true && isset($response['data'])) {
                if (is_string($response['data'])) {
                    $response['data'] = urldecode($response['data']);
                }
            }
            
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => 'Gangguan Server: ' . $e->getMessage()
            ]);
        }
    }
}