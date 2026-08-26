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

        $threshold = \App\Models\Setting::get('vip_balance_threshold');
        $vipBalance = ($threshold !== null && $threshold !== '') ? (float)$threshold : 0.0;
        
        try {
            $vipApi = app(VipResellerService::class);
            $profile = $vipApi->getProfile();
            if (isset($profile['result']) && $profile['result'] === true && isset($profile['data']['balance'])) {
                $liveBal = (float) $profile['data']['balance'];
                if ($liveBal > 0) {
                    $vipBalance = $liveBal;
                }
            }

            // AUTO-UPDATE STOK & HARGA MODAL OTOMATIS DARI VIP RESELLER (Cache 5 Menit)
            $cacheKey = 'vip_auto_sync_' . $game->slug;
            if (!Cache::has($cacheKey)) {
                $filterMap = [
                    'mobile-legend' => 'Mobile Legends',
                    'mobile-legends' => 'Mobile Legends',
                    'valorant' => 'Valorant',
                    'free-fire' => 'Free Fire',
                    'freefire' => 'Free Fire',
                    'roblox' => 'Roblox',
                    'pubg' => 'PUBG',
                    'pubg-mobile' => 'PUBG',
                ];
                $keyword = $filterMap[$game->slug] ?? $game->name;
                $apiRes = $vipApi->getGameProducts($keyword);
                if (isset($apiRes['result']) && $apiRes['result'] === true && !empty($apiRes['data'])) {
                    $apiItems = collect($apiRes['data'])->keyBy('code');
                    $localProds = $game->products()->get();
                    foreach ($localProds as $lp) {
                        if ($apiItems->has($lp->product_code)) {
                            $aItem = $apiItems->get($lp->product_code);
                            $aStatus = strtolower($aItem['status']) === 'available' ? 'available' : 'empty';
                            $latestModal = (float)($aItem['price']['special'] ?? ($aItem['price']['h2h'] ?? ($aItem['price']['premium'] ?? ($aItem['price']['basic'] ?? $lp->price_modal))));
                            
                            $margin = $lp->price_sell > $lp->price_modal ? ($lp->price_sell - $lp->price_modal) : ceil($latestModal * 0.05);
                            $newSell = $latestModal + $margin;
                            
                            if ($lp->price_modal != $latestModal || $lp->status !== $aStatus) {
                                $lp->update([
                                    'price_modal' => $latestModal,
                                    'price_sell' => ceil($newSell),
                                    'status' => $aStatus,
                                ]);
                            }
                        }
                    }
                }
                Cache::put($cacheKey, true, 300); // 5 minutes cache
            }
        } catch (\Exception $e) {
            // Fallback ke $vipBalance
        }

        $allProducts = $game->products()->where('price_modal', '>', 0)->orderBy('price_sell')->get();

        $uniqueProducts = collect();
        $seenKeys = [];

        foreach ($allProducts as $product) {
            // BUKA jika harga modal <= saldo modal, TUTUP jika modal > saldo
            $product->is_out_of_stock = ($vipBalance <= 0 || (float)$product->price_modal > $vipBalance);
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

        $stockMap = [];
        foreach ($uniqueProducts as $p) {
            $modal = (float) $p->price_modal;
            $stockMap[(string)$p->id] = ($vipBalance <= 0 || $modal > $vipBalance);
        }

        $response = response()->view('topup.show', [
            'game' => $game,
            'categories' => $finalCategories,
            'vipBalance' => $vipBalance,
            'stockMap' => $stockMap,
        ]);

        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        $response->headers->set('X-LiteSpeed-Cache-Control', 'no-cache');

        return $response;
    }

    public function stockStatus($slug)
    {
        $game = Game::where('slug', $slug)->firstOrFail();
        $threshold = \App\Models\Setting::get('vip_balance_threshold');
        $vipBalance = ($threshold !== null && $threshold !== '') ? (float)$threshold : 0.0;
        
        try {
            $vipApi = app(VipResellerService::class);
            $profile = $vipApi->getProfile();
            if (isset($profile['result']) && $profile['result'] === true && isset($profile['data']['balance'])) {
                $liveBal = (float) $profile['data']['balance'];
                if ($liveBal > 0) {
                    $vipBalance = $liveBal;
                }
            }

            // AUTO-UPDATE STOK & HARGA MODAL OTOMATIS DARI VIP RESELLER (Cache 5 Menit)
            $cacheKey = 'vip_auto_sync_' . $game->slug;
            if (!Cache::has($cacheKey)) {
                $filterMap = [
                    'mobile-legend' => 'Mobile Legends',
                    'mobile-legends' => 'Mobile Legends',
                    'valorant' => 'Valorant',
                    'free-fire' => 'Free Fire',
                    'freefire' => 'Free Fire',
                    'roblox' => 'Roblox',
                    'pubg' => 'PUBG',
                    'pubg-mobile' => 'PUBG',
                ];
                $keyword = $filterMap[$game->slug] ?? $game->name;
                $apiRes = $vipApi->getGameProducts($keyword);
                if (isset($apiRes['result']) && $apiRes['result'] === true && !empty($apiRes['data'])) {
                    $apiItems = collect($apiRes['data'])->keyBy('code');
                    $localProds = $game->products()->get();
                    foreach ($localProds as $lp) {
                        if ($apiItems->has($lp->product_code)) {
                            $aItem = $apiItems->get($lp->product_code);
                            $aStatus = strtolower($aItem['status']) === 'available' ? 'available' : 'empty';
                            $latestModal = (float)($aItem['price']['special'] ?? ($aItem['price']['h2h'] ?? ($aItem['price']['premium'] ?? ($aItem['price']['basic'] ?? $lp->price_modal))));
                            
                            $margin = $lp->price_sell > $lp->price_modal ? ($lp->price_sell - $lp->price_modal) : ceil($latestModal * 0.05);
                            $newSell = $latestModal + $margin;
                            
                            if ($lp->price_modal != $latestModal || $lp->status !== $aStatus) {
                                $lp->update([
                                    'price_modal' => $latestModal,
                                    'price_sell' => ceil($newSell),
                                    'status' => $aStatus,
                                ]);
                            }
                        }
                    }
                }
                Cache::put($cacheKey, true, 300); // 5 minutes cache
            }
        } catch (\Exception $e) {
            // Fallback ke $vipBalance
        }

        $products = $game->products()->select('id', 'price_modal')->get();
        $stockMap = [];
        foreach ($products as $p) {
            $modal = (float) $p->price_modal;
            $stockMap[(string)$p->id] = ($vipBalance <= 0 || $modal > $vipBalance);
        }

        return response()->json([
            'success' => true,
            'vip_balance' => $vipBalance,
            'stock_map' => $stockMap,
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function process(Request $request, $slug)
    {
        $game = Game::where('slug', $slug)->firstOrFail();
        $gameSlug = strtolower($game->slug);
        $playerId = trim($request->player_id);
        $zoneId = trim($request->zone_id ?? '');

        $rules = [
            'product_id' => 'required|exists:game_products,id',
            'player_id' => 'required|string|max:255',
        ];

        $isZoneRequired = $game->requires_zone_id || str_contains($gameSlug, 'magic-chess') || str_contains($gameSlug, 'mobile-legend');
        if ($isZoneRequired) {
            $rules['zone_id'] = 'required|string|max:255';
        }

        $request->validate($rules);

        // 1. Validasi Valorant: HANYA Valorant yang boleh dan wajib menyertakan '#'
        if ($gameSlug === 'valorant') {
            if (!str_contains($playerId, '#')) {
                return back()->with('error', 'Format Riot ID salah! Wajib menyertakan tanda pagar (#), contoh: Jett#1234 atau Username#TAG');
            }
        } else {
            // Semua game selain Valorant dilarang memakai '#'
            if (str_contains($playerId, '#') || str_contains($zoneId, '#')) {
                return back()->with('error', 'Format ID salah! Karakter "#" hanya digunakan untuk Riot ID Valorant. Untuk game ' . $game->name . ', masukkan User ID angka yang benar.');
            }
        }

        // 2. Validasi Angka untuk game dengan Zone ID (MLBB, Magic Chess, dll)
        if ($isZoneRequired) {
            if (!preg_match('/^[0-9]+$/', $playerId)) {
                return back()->with('error', 'User ID ' . $game->name . ' harus berupa angka (contoh: 12345678), tanpa huruf atau simbol.');
            }
            if (!preg_match('/^[0-9]+$/', $zoneId)) {
                return back()->with('error', 'Zone ID ' . $game->name . ' harus berupa angka (contoh: 1234), tanpa huruf atau simbol.');
            }
        }
        
        $product = GameProduct::findOrFail($request->product_id);
        
        $threshold = (float) \App\Models\Setting::get('vip_balance_threshold', 0);
        $vipBalance = Cache::get('vip_reseller_balance') ?? (float)\App\Models\Setting::get('vip_reseller_balance', 0);
        if ($threshold > 0 && $vipBalance > 0 && $product->price_modal > $vipBalance) {
            return back()->with('error', 'Mohon maaf, nominal ' . $product->name . ' sedang dalam pemeliharaan saldo provider. Silakan hubungi Admin.');
        }
        
        // Buat ID Transaksi & Nomor Invoice Unik (Format: INV/TOPUP/TTS/001/VIII/2026)
        $orderId = \App\Helpers\InvoiceHelper::generateTopUpInvoice();
        $paymentMethod = $request->payment_method === 'balance' ? 'balance' : 'qris';
        
        // JIKA MEMILIH PEMBAYARAN MENGGUNAKAN SALDO AKUN
        if ($paymentMethod === 'balance') {
            if (!auth()->check()) {
                return back()->with('error', 'Silakan login terlebih dahulu untuk menggunakan pembayaran Saldo Akun.');
            }
            $user = auth()->user();
            if ((float)$user->balance < (float)$product->price_sell) {
                return back()->with('error', 'Saldo Akun Anda (Rp' . number_format($user->balance, 0, ',', '.') . ') tidak mencukupi untuk pembayaran ini.');
            }

            // 1. Potong Saldo User
            $user->decrement('balance', $product->price_sell);

            // 2. Simpan Transaksi Berstatus Processing
            $transaction = \App\Models\Transaction::create([
                'id' => $orderId,
                'user_id' => $user->id,
                'game_id' => $game->id,
                'game_product_id' => $product->id,
                'target_field_1' => $playerId,
                'target_field_2' => $isZoneRequired ? $zoneId : null,
                'amount' => $product->price_sell,
                'payment_method' => 'balance',
                'status' => 'processing',
                'snap_token' => json_encode(['type' => 'balance', 'gateway' => 'wallet', 'amount' => (int) $product->price_sell]),
            ]);

            // 3. Coba kirim otomatis ke Provider (VIP Reseller)
            try {
                $vipService = app(\App\Services\VipResellerService::class);
                $orderRes = $vipService->order(
                    $product->product_code,
                    $playerId,
                    $isZoneRequired ? $zoneId : '',
                    $transaction->id
                );

                if (isset($orderRes['result']) && $orderRes['result'] === true) {
                    $transaction->update([
                        'status' => 'success',
                        'provider_trx_id' => $orderRes['data']['trxid'] ?? null,
                    ]);
                    return redirect()->route('topup.checkout.show', $transaction->id)->with('success', 'Pembayaran via Saldo Akun berhasil! Pesanan langsung terkirim.');
                } else {
                    // JIKA PROVIDER GAGAL/MENOLAK: OTOMATIS REFUND DANA KE SALDO AKUN USER!
                    $errMsg = $orderRes['message'] ?? 'Provider gagal memproses pesanan.';
                    $user->increment('balance', $product->price_sell);
                    $transaction->update([
                        'status' => 'refunded',
                    ]);
                    return redirect()->route('topup.checkout.show', $transaction->id)->with('warning', 'Pesanan gagal diproses: ' . $errMsg . '. Dana sebesar Rp' . number_format($product->price_sell, 0, ',', '.') . ' telah OTOMATIS dikembalikan ke Saldo Akun Anda.');
                }
            } catch (\Exception $e) {
                return redirect()->route('topup.checkout.show', $transaction->id)->with('info', 'Pembayaran via Saldo Akun berhasil diterima! Pesanan Anda sedang diproses.');
            }
        }

        // JIKA PEMBAYARAN MANUAL QRIS (DEFAULT)
        $transaction = \App\Models\Transaction::create([
            'id' => $orderId,
            'user_id' => auth()->id(),
            'game_id' => $game->id,
            'game_product_id' => $product->id,
            'target_field_1' => $playerId,
            'target_field_2' => $isZoneRequired ? $zoneId : null,
            'amount' => $product->price_sell,
            'payment_method' => 'qris',
            'status' => 'pending',
        ]);
        
        // Set snap_token for Manual QRIS & WhatsApp Flow
        $snapData = json_encode([
            'type'      => 'manual_qris',
            'gateway'   => 'manual',
            'method'    => 'qris',
            'amount'    => (int) $product->price_sell,
        ]);

        $transaction->update([
            'snap_token' => $snapData,
        ]);

        return redirect()->route('topup.checkout.show', $transaction->id);
    }

    public function checkNickname(Request $request, $slug)
    {
        try {
            $game = Game::where('slug', $slug)->firstOrFail();
            $api = app(\App\Services\VipResellerService::class);
            
            $target1 = trim($request->player_id);
            $target2 = trim($request->zone_id ?? '');
            $gameSlug = strtolower($game->slug);

            // 1. VALIDASI KHUSUS VALORANT: HANYA Valorant yang boleh dan wajib memakai tanda '#' (Riot ID: Username#TAG)
            if ($gameSlug === 'valorant') {
                if (!str_contains($target1, '#')) {
                    return response()->json([
                        'result' => false,
                        'message' => 'Format Riot ID salah! Wajib menyertakan tanda pagar (#), contoh: Jett#1234 atau Username#TAG',
                    ]);
                }
                $parts = explode('#', $target1, 2);
                $uName = trim($parts[0]);
                $tag = trim($parts[1]);
                if (strlen($uName) < 2 || strlen($tag) < 2) {
                    return response()->json([
                        'result' => false,
                        'message' => 'Riot ID dan Tagline tidak boleh kosong! Contoh: Jett#1234',
                    ]);
                }

                return response()->json([
                    'result' => true,
                    'is_checked' => true,
                    'nickname' => $uName . '#' . $tag,
                ]);
            }

            // 2. UNTUK SEMUA GAME SELAIN VALORANT: DILARANG MENGGUNAKAN SIMBOL '#'!
            if (str_contains($target1, '#') || str_contains($target2, '#')) {
                return response()->json([
                    'result' => false,
                    'message' => 'Format ID salah! Karakter "#" hanya untuk Riot ID Valorant. Untuk game ' . $game->name . ', masukkan User ID angka yang benar.',
                ]);
            }

            // 3. VALIDASI NUMERIK UNTUK GAME BERBASIS ANGKA & ZONE ID (MLBB, Magic Chess, dll)
            $isZoneRequired = $game->requires_zone_id || str_contains($gameSlug, 'magic-chess') || str_contains($gameSlug, 'mobile-legend');
            if ($isZoneRequired) {
                if (!preg_match('/^[0-9]+$/', $target1)) {
                    return response()->json([
                        'result' => false,
                        'message' => 'User ID ' . $game->name . ' harus berupa angka (contoh: 12345678), tanpa simbol atau huruf.',
                    ]);
                }
                if (!preg_match('/^[0-9]+$/', $target2)) {
                    return response()->json([
                        'result' => false,
                        'message' => 'Zone ID ' . $game->name . ' harus berupa angka (contoh: 1234).',
                    ]);
                }
            }

            // 4. VALIDASI KHUSUS ROBLOX: Cek ke API Resmi Roblox
            if ($gameSlug === 'roblox') {
                try {
                    $robloxRes = \Illuminate\Support\Facades\Http::timeout(6)->post('https://users.roblox.com/v1/usernames/users', [
                        'usernames' => [$target1],
                        'excludeBannedUsers' => false
                    ]);
                    $rJson = $robloxRes->json();
                    if (!empty($rJson['data']) && isset($rJson['data'][0]['name'])) {
                        $robloxUser = $rJson['data'][0];
                        return response()->json([
                            'result' => true,
                            'is_checked' => true,
                            'nickname' => $robloxUser['name'] . (isset($robloxUser['displayName']) && $robloxUser['displayName'] !== $robloxUser['name'] ? ' (' . $robloxUser['displayName'] . ')' : ''),
                        ]);
                    } else {
                        return response()->json([
                            'result' => false,
                            'message' => 'Username Roblox "' . $target1 . '" tidak ditemukan di server Roblox. Pastikan penulisan username benar!',
                        ]);
                    }
                } catch (\Exception $exRoblox) {
                    return response()->json([
                        'result' => false,
                        'message' => 'Gagal memverifikasi Username Roblox. Pastikan username sudah terdaftar.',
                    ]);
                }
            }

            // 5. CEK NICKNAME KE VIP RESELLER API JIKA TERSEDIA
            $gameCode = match($gameSlug) {
                'mobile-legend', 'mobile-legends', 'magic-chess-go-go' => 'mobile-legends',
                'free-fire', 'freefire' => 'free-fire',
                'pubg-mobile', 'pubg' => 'pubg-mobile',
                'genshin-impact' => 'genshin-impact',
                'honkai-star-rail' => 'honkai-star-rail',
                'call-of-duty-mobile', 'cod-mobile' => 'cod-mobile',
                'point-blank' => 'point-blank',
                'arena-of-valor', 'aov' => 'aov',
                default => null,
            };

            if (!$gameCode) {
                // Game non-API nickname: pastikan lolos validasi
                return response()->json([
                    'result' => true,
                    'is_checked' => false,
                    'message' => 'ID berhasil dimasukkan.',
                ]);
            }

            $res = $api->checkNickname($gameCode, $target1, $target2);

            if (isset($res['result']) && $res['result'] === true && isset($res['data'])) {
                return response()->json([
                    'result' => true,
                    'is_checked' => true,
                    'nickname' => $res['data'],
                ]);
            }

            // Jika API memberikan pesan error nickname tidak ditemukan
            $errMsg = $res['message'] ?? ('User ID ' . $target1 . ($target2 ? ' (' . $target2 . ')' : '') . ' tidak ditemukan di game ' . $game->name . '. Silakan periksa kembali.');
            return response()->json([
                'result' => false,
                'message' => $errMsg,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => 'Terjadi kesalahan saat memeriksa User ID. Silakan periksa kembali format ID Anda.',
            ]);
        }
    }
}