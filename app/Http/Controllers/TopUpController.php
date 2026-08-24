<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameProduct;
use App\Services\VipResellerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TopUpController extends Controller
{
    public function index()
    {
        return view('topup.index');
    }

    public function show($slug)
    {
        $game = Game::where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        $vipBalance = Cache::remember('vip_reseller_balance', 60, function () {
            try {
                $vipApi = app(VipResellerService::class);
                $profile = $vipApi->getProfile();
                if (isset($profile['result']) && $profile['result'] === true && isset($profile['data']['balance'])) {
                    $liveBal = (float) $profile['data']['balance'];
                    \App\Models\Setting::set('vip_reseller_balance', (string)$liveBal);
                    return $liveBal;
                }
            } catch (\Exception $e) {
                Log::warning("Gagal cek saldo VIP Reseller API: " . $e->getMessage());
            }
            
            $dbSetting = \App\Models\Setting::get('vip_reseller_balance', 0);
            return (float) $dbSetting;
        });

        $allProducts = $game->products()->where('price_modal', '>', 0)->orderBy('price_sell')->get();

        $uniqueProducts = collect();
        $seenKeys = [];

        foreach ($allProducts as $product) {
            $threshold = (float) \App\Models\Setting::get('vip_balance_threshold', 0);
            $product->is_out_of_stock = (
                strtolower($product->status) !== 'available' || 
                ($threshold > 0 && $vipBalance > 0 && $product->price_modal > $vipBalance)
            );
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
                str_contains($name, 'br') ||
                str_contains($name, 'my') ||
                str_contains($name, 'malaysia') ||
                str_contains($name, 'ph') ||
                str_contains($name, 'philippines')
            ) {
                if (preg_match('/\b(global|brazil|br|my|malaysia|ph|philippines|skin|charisma|p\.ace|champion|lightborn|epic)\b/i', $name)) {
                    continue;
                }
            }
            
            // Hilangkan titik (.) yang digunakan sebagai pemisah ribuan agar 1.446 terbaca 1446
            $nameForMath = str_replace('.', '', $name);

            // 2. CEK KATEGORI PASS / MEMBER / BUNDLE
            $isPass = (str_contains($name, 'pass') || str_contains($name, 'weekly') || str_contains($name, 'starlight') || str_contains($name, 'twilight') || str_contains($name, 'member') || str_contains($name, 'bundle'));
            
            $uniqueKey = $name;
            $qty = 0;

            if ($isPass) {
                $uniqueKey = preg_replace('/[^a-z0-9]/', '', $name);
                if (preg_match('/^(\d+)/', $nameForMath, $m)) {
                    $qty = (int)$m[1];
                }
            } else {
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
            
            // Buat nama pendek yang rapi
            $shortName = $product->name;
            $shortName = preg_replace('/\(.*?\)/', '', $shortName);
            $shortName = str_ireplace('Diamonds', 'DM', $shortName);
            $shortName = str_ireplace('Diamond', 'DM', $shortName);
            $shortName = str_ireplace('Bonus', '', $shortName);
            $shortName = str_ireplace('First Top Up', '', $shortName);
            $shortName = preg_replace('/\s+\+\s+/', ' ', $shortName);
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

        // 4. PENGELOMPOKAN KE KATEGORI
        $categories = [
            'Pass & Member' => collect(),
            'Mata Uang Game' => collect(),
            'Item & Lainnya' => collect(),
        ];

        foreach ($uniqueProducts as $product) {
            $name = strtolower($product->name);
            
            if (
                str_contains($name, 'weekly') || 
                str_contains($name, 'monthly') || 
                str_contains($name, 'bundle') || 
                str_contains($name, 'pass') || 
                str_contains($name, 'twilight') || 
                str_contains($name, 'starlight') || 
                str_contains($name, 'member') || 
                str_contains($name, 'battle') || 
                str_contains($name, 'subscription')
            ) {
                $categories['Pass & Member']->push($product);
            } elseif (str_contains($name, 'name') || str_contains($name, 'nama') || str_contains($name, 'squad') || str_contains($name, 'crystal') || str_contains($name, 'ticket') || str_contains($name, 'token') || str_contains($name, 'gift card')) {
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
                })->values();
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
        
        // Pengecekan Keamanan Saldo & Status VIP Reseller
        $vipBalance = Cache::get('vip_reseller_balance') ?? (float)\App\Models\Setting::get('vip_reseller_balance', 0);
        if ($product->status !== 'available' || ($vipBalance !== null && $product->price_modal > $vipBalance)) {
            return back()->with('error', 'Mohon maaf, nominal ' . $product->name . ' sedang habis atau dalam pemeliharaan. Silakan pilih nominal lainnya.');
        }
        
        // Buat ID Transaksi Unik (Order ID)
        $orderId = 'TRX-' . time() . '-' . rand(100, 999);
        
        // Simpan ke Database
        $transaction = \App\Models\Transaction::create([
            'id' => $orderId,
            'user_id' => auth()->id(),
            'game_id' => $game->id,
            'game_product_id' => $product->id,
            'target_field_1' => $request->player_id,
            'target_field_2' => $request->zone_id,
            'amount' => $product->price_sell,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
        ]);
        
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY', 'Mid-server-ckZHwiXrG6K0f-NXv3ykujHi'));
        \Midtrans\Config::$clientKey = config('services.midtrans.client_key', env('MIDTRANS_CLIENT_KEY', 'Mid-client-j5_lQIPsu4FpDtlk'));
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
        
        // MATIKAN SSL VERIFICATION AGAR JALAN DI LOCALHOST WINDOWS
        \Midtrans\Config::$curlOptions = [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => [],
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
                'email' => 'customer@totap.com',
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
                // Fallback to snap if needed
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                $transaction->update(['snap_token' => $snapToken]);
            }
            
            // Arahkan ke halaman checkout
            return redirect()->route('topup.checkout.show', $transaction->id);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Midtrans TopUp Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function checkNickname(Request $request, $slug)
    {
        try {
            $game = Game::where('slug', $slug)->firstOrFail();
            $api = app(\App\Services\VipResellerService::class);
            
            $target1 = trim($request->player_id);
            $target2 = trim($request->zone_id ?? '');
            
            $gameCode = match(strtolower($game->slug)) {
                'mobile-legend', 'mobile-legends' => 'mobile-legends',
                'free-fire', 'freefire' => 'free-fire',
                'valorant' => 'valorant',
                'pubg-mobile', 'pubg' => 'pubg-mobile',
                'roblox' => 'roblox',
                'genshin-impact' => 'genshin-impact',
                'point-blank' => 'point-blank',
                default => $game->slug,
            };

            // Format khusus untuk Valorant (Digabungkan dengan #)
            if ($gameCode === 'valorant') {
                $target2 = ltrim($target2, '#');
                if (!str_contains($target1, '#') && !empty($target2)) {
                    $target1 = $target1 . '#' . $target2;
                }
                $target2 = '';
            }
            
            $response = $api->checkNickname($gameCode, $target1, $target2);
            
            // 1. Jika berhasil diverifikasi oleh API VIP Reseller
            if (isset($response['result']) && $response['result'] === true && isset($response['data'])) {
                if (is_string($response['data'])) {
                    $response['data'] = urldecode($response['data']);
                }
                return response()->json($response);
            }
            
            // 2. Jika gagal atau ID salah, tolak secara tegas (tanpa bypass)
            return response()->json([
                'result' => false,
                'message' => $response['message'] ?? 'Player ID atau Tagline tidak valid atau tidak ditemukan.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => 'Gangguan koneksi server: ' . $e->getMessage()
            ]);
        }
    }
}