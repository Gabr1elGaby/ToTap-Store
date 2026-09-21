<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // REAL-TIME AUTO MAINTENANCE: Expire overdue software subscriptions
    try {
        if (class_exists(\App\Models\Subscription::class)) {
            \App\Models\Subscription::where('status', 'ACTIVE')
                ->whereNotNull('end_date')
                ->where('end_date', '<', now()->toDateString())
                ->update(['status' => 'EXPIRED']);
        }
    } catch (\Exception $e) {}

    $products = \App\Models\Product::where('is_active', true)->with(['plans' => function($q) {
        $q->where('is_active', true)->orderBy('price');
    }])->get();

    // REAL-TIME STATS (100% SYNCHRONIZED ACROSS DASHBOARD & HOMEPAGE)
    $totalUsers = \Illuminate\Support\Facades\DB::table('users')->count();
    
    // 1. CV Builder Transactions
    $paidCvCount = 0;
    if (\Illuminate\Support\Facades\Schema::hasTable('cvs')) {
        $paidCvCount = \Illuminate\Support\Facades\DB::table('cvs')->whereIn('status', ['PAID', 'paid', 'SUCCESS', 'success'])->count();
    }

    // 2. Top Up Game Transactions
    $topupTrxCount = 0;
    if (\Illuminate\Support\Facades\Schema::hasTable('transactions')) {
        $topupTrxCount = \Illuminate\Support\Facades\DB::table('transactions')->whereIn('status', ['PAID', 'paid', 'SUCCESS', 'success'])->count();
    }
    
    // 3. Enterprise POS Orders
    $softwareOrdersCount = 0;
    if (\Illuminate\Support\Facades\Schema::hasTable('orders')) {
        $softwareOrdersCount = \Illuminate\Support\Facades\DB::table('orders')->whereIn('payment_status', ['PAID', 'paid', 'SUCCESS', 'success'])->count();
    }

    $totalTransactions = $paidCvCount + $topupTrxCount + $softwareOrdersCount;

    // GET MAX DISCOUNT FOR TOP UP GAMES
    $maxGameDiscount = (int) (\Illuminate\Support\Facades\DB::table('game_products')
        ->join('games', 'game_products.game_id', '=', 'games.id')
        ->where('game_products.is_promo', true)
        ->where('game_products.price_normal', '>', 0)
        ->whereColumn('game_products.price_normal', '>', 'game_products.price_sell')
        ->where(function($q) {
            $q->whereNull('games.category')
              ->orWhere('games.category', 'Top Up Game')
              ->orWhere('games.category', 'NOT LIKE', '%aplikasi%');
        })
        ->selectRaw('MAX(ROUND(((game_products.price_normal - game_products.price_sell) / game_products.price_normal) * 100)) as max_discount')
        ->value('max_discount') ?? 0);

    // GET MAX DISCOUNT FOR APLIKASI PREMIUM
    $maxAppDiscount = (int) (\Illuminate\Support\Facades\DB::table('game_products')
        ->join('games', 'game_products.game_id', '=', 'games.id')
        ->where('game_products.is_promo', true)
        ->where('game_products.price_normal', '>', 0)
        ->whereColumn('game_products.price_normal', '>', 'game_products.price_sell')
        ->where(function($q) {
            $q->where('games.category', 'Aplikasi Premium')
              ->orWhere('games.category', 'App & Entertainment')
              ->orWhere('games.category', 'LIKE', '%aplikasi%')
              ->orWhere('games.category', 'LIKE', '%streaming%');
        })
        ->selectRaw('MAX(ROUND(((game_products.price_normal - game_products.price_sell) / game_products.price_normal) * 100)) as max_discount')
        ->value('max_discount') ?? 0);

    // GET MAX DISCOUNT FOR SOFTWARE
    $maxPlanDiscount = (int) (\Illuminate\Support\Facades\DB::table('plans')
        ->where('is_active', true)
        ->where('price_normal', '>', 0)
        ->whereColumn('price_normal', '>', 'price')
        ->selectRaw('MAX(ROUND(((price_normal - price) / price_normal) * 100)) as max_discount')
        ->value('max_discount') ?? 0);

    // Check active Super Admin Promo settings
    $promoSettings = \App\Helpers\PromoHelper::getSettings();
    $dayCheck = \App\Helpers\PromoHelper::isDayPromoActiveToday();

    $gamePromoPct = 0;
    $appPromoPct = 0;
    $softwarePromoPct = 0;

    // 1. Promo Pengguna Baru
    if (!empty($promoSettings['first_user_active']) && $promoSettings['first_user_type'] === 'percent') {
        $val = (int) $promoSettings['first_user_value'];
        $cats = $promoSettings['first_user_categories'] ?? ['all'];
        if (in_array('all', $cats) || in_array('games', $cats)) $gamePromoPct = max($gamePromoPct, $val);
        if (in_array('all', $cats) || in_array('apps', $cats)) $appPromoPct = max($appPromoPct, $val);
        if (in_array('all', $cats) || in_array('software', $cats)) $softwarePromoPct = max($softwarePromoPct, $val);
    }

    // 2. Promo Hari Spesial (Misal Hari Minggu)
    if (!empty($promoSettings['day_promo_active']) && !empty($dayCheck['active']) && $promoSettings['day_promo_type'] === 'percent') {
        $val = (int) $promoSettings['day_promo_value'];
        $cats = $promoSettings['day_promo_categories'] ?? ['all'];
        if (in_array('all', $cats) || in_array('games', $cats)) $gamePromoPct = max($gamePromoPct, $val);
        if (in_array('all', $cats) || in_array('apps', $cats)) $appPromoPct = max($appPromoPct, $val);
        if (in_array('all', $cats) || in_array('software', $cats)) $softwarePromoPct = max($softwarePromoPct, $val);
    }

    $maxGameDiscount = max($maxGameDiscount, $gamePromoPct);
    $maxAppDiscount = max($maxAppDiscount, $appPromoPct);
    $maxSoftwareDiscount = max($maxPlanDiscount, $softwarePromoPct);

    // CUSTOMER REVIEWS STATS (100% REAL DATA ONLY)
    $totalReviews = \App\Models\CustomerReview::count();
    $avgRating = $totalReviews > 0 ? round((float) \App\Models\CustomerReview::avg('rating'), 1) : 0.0;

    return view('welcome', compact('products', 'totalUsers', 'totalTransactions', 'maxGameDiscount', 'maxAppDiscount', 'maxSoftwareDiscount', 'avgRating', 'totalReviews'));
});

// SUBMIT CUSTOMER REVIEW
Route::post('/api/reviews', [\App\Http\Controllers\CustomerReviewController::class, 'store'])->name('reviews.store');
Route::post('/api/customer-reviews', [\App\Http\Controllers\CustomerReviewController::class, 'store'])->name('customer-reviews.store');






// Rute Tampilan (Bisa Dilihat Publik, tapi transaksi ditahan via Pop-up)
Route::get('/produk/{slug}', [\App\Http\Controllers\PublicProductController::class, 'show'])->name('product.show');
Route::get('/cv', [\App\Http\Controllers\CvController::class, 'index'])->name('cv.index');
Route::get('/cv/create', [\App\Http\Controllers\CvController::class, 'create'])->name('cv.create');
Route::get('/cv/preview-example/{slug}', [\App\Http\Controllers\CvController::class, 'previewExample'])->name('cv.previewExample');
Route::post('/cv/preview/{slug}', [\App\Http\Controllers\CvController::class, 'preview'])->name('cv.preview');
Route::get('/api/system-status', function () {
    $isMaintenance = false;
    $message = '';
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
            $mRow = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'maintenance_mode')->first();
            $isMaintenance = ($mRow && $mRow->value == '1');
            $msgRow = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'maintenance_message')->first();
            $message = $msgRow->value ?? '';
        }
    } catch (\Throwable $e) {}

    $isAdmin = false;
    if (auth()->check()) {
        $user = auth()->user();
        $isAdmin = in_array(strtolower($user->role ?? ''), ['admin', 'superadmin', 'owner']) || !empty($user->is_admin);
    }

    return response()->json([
        'maintenance' => $isMaintenance,
        'message' => $message,
        'is_admin' => $isAdmin,
        'server_time' => now()->toDateTimeString(),
    ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
})->name('api.system-status');

Route::match(['get', 'post'], '/api/tripay/callback', [\App\Http\Controllers\Api\TripayCallbackController::class, 'handle'])->name('tripay.callback');
Route::match(['get', 'post'], '/api/duitku/callback', [\App\Http\Controllers\Api\DuitkuCallbackController::class, 'handle'])->name('duitku.callback');
Route::get('/api/cron/sync-all', [\App\Http\Controllers\Admin\GameProductController::class, 'cronSyncAll'])->name('cron.sync-all');
Route::get('/topup-status/{slug}', [\App\Http\Controllers\TopUpController::class, 'stockStatus'])->name('topup.stock-status');
Route::get('/topup', [\App\Http\Controllers\TopUpController::class, 'index'])->name('topup.index');
Route::get('/topup/{slug}', [\App\Http\Controllers\TopUpController::class, 'show'])->name('topup.show');
Route::post('/topup/{slug}/check-nickname', [\App\Http\Controllers\TopUpController::class, 'checkNickname'])->name('topup.check-nickname');
Route::post('/topup/{slug}/process', [\App\Http\Controllers\TopUpController::class, 'process'])->name('topup.process');
Route::get('/topup/checkout/{id}', [\App\Http\Controllers\TopUpPaymentController::class, 'show'])->name('topup.checkout.show')->where('id', '.*');
Route::post('/topup/checkout/{id}/verify', [\App\Http\Controllers\TopUpPaymentController::class, 'verify'])->name('topup.checkout.verify')->where('id', '.*');
Route::post('/topup/verify/{id}', [\App\Http\Controllers\TopUpPaymentController::class, 'verify'])->name('topup.verify')->where('id', '.*');

// Rute Transaksi / Aksi CV & Software (Backend Auth Lock)
Route::middleware(['auth'])->group(function () {
    Route::post('/cv', [\App\Http\Controllers\CvController::class, 'store'])->name('cv.store');
    Route::get('/cv/download/{token}', [\App\Http\Controllers\CvController::class, 'download'])->name('cv.download');
    Route::get('/api/cv/{token}/status', [\App\Http\Controllers\CvPaymentController::class, 'statusApi'])->name('cv.status.api');
    
    Route::get('/checkout/cv/{token}', [\App\Http\Controllers\CvPaymentController::class, 'show'])->name('cv.checkout.show');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout/{plan}', [\App\Http\Controllers\CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout/{plan}/process', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/payment/{order_number}', [\App\Http\Controllers\PaymentController::class, 'page'])->name('payment.page')->where('order_number', '.*');
    Route::get('/payment/status/{order_number}', [\App\Http\Controllers\PaymentController::class, 'status'])->name('payment.status')->where('order_number', '.*');
    Route::post('/payment/simulate/{order_number}', [\App\Http\Controllers\PaymentController::class, 'simulate'])->name('payment.simulate')->where('order_number', '.*');
    Route::get('/payment/success/{order_number}', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success')->where('order_number', '.*');
    Route::get('/subscription/active/{product_id}', [\App\Http\Controllers\SubscriptionController::class, 'active'])->name('subscription.active');

    // Wallet Deposit Routes (Isi Saldo Akun)
    Route::get('/deposit', [\App\Http\Controllers\DepositController::class, 'index'])->name('deposit.index');
    Route::post('/deposit/process', [\App\Http\Controllers\DepositController::class, 'process'])->name('deposit.process');
    Route::get('/deposit/{id}', [\App\Http\Controllers\DepositController::class, 'show'])->name('deposit.show')->where('id', '.*');
    Route::get('/api/deposit/{id}/status', [\App\Http\Controllers\DepositController::class, 'statusApi'])->name('deposit.status.api')->where('id', '.*');

    Route::get('/transactions', [\App\Http\Controllers\TransactionHistoryController::class, 'index'])->name('transactions.history');
    Route::get('/transactions/{id}/invoice', [\App\Http\Controllers\TransactionHistoryController::class, 'invoice'])->name('transactions.invoice')->where('id', '.*');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/phone/send-otp', [ProfileController::class, 'sendPhoneOtp'])->name('profile.phone.send-otp');
    Route::post('/profile/phone/verify-otp', [ProfileController::class, 'verifyPhoneOtp'])->name('profile.phone.verify-otp');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Debug: lihat webhook GoPay terakhir & pending transactions
    Route::get('/debug/webhook-info', function () {
        $info = \Illuminate\Support\Facades\Cache::get('latest_gopay_webhook_info', 'BELUM ADA DATA (belum ada webhook masuk)');
        $pending = \App\Models\Transaction::whereIn('status', ['pending', 'waiting', 'unpaid'])
            ->where('payment_method', 'qris')
            ->where('created_at', '>=', now()->subHours(24))
            ->get(['id', 'amount', 'status', 'created_at', 'snap_token'])
            ->map(fn($t) => [
                'id' => $t->id,
                'amount_db' => (int) $t->amount,
                'snap' => json_decode($t->snap_token, true),
                'created_at' => $t->created_at,
            ]);
        return response()->json([
            'latest_webhook' => $info,
            'pending_qris_24h' => $pending,
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    })->name('debug.webhook-info');

    // Debug: test Digiflazz API
    Route::get('/debug/digiflazz', function () {
        $payload = [
            'username'       => 'tuwumiWXAdqg',
            'buyer_sku_code' => 'test',
            'customer_no'    => '087800001233',
            'ref_id'         => 'some1d',
            'sign'           => 'a47659b5af3b52fb57d4b8a3c069b11b',
        ];

        $start = microtime(true);
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(10)
                ->post('https://api.digiflazz.com/v1/transaction', $payload);
            $result   = $response->json();
            $httpCode = $response->status();
            $error    = null;
        } catch (\Throwable $e) {
            $result   = null;
            $httpCode = 0;
            $error    = $e->getMessage();
        }
        $duration = round((microtime(true) - $start) * 1000, 1);

        $status  = $result['data']['status'] ?? '-';
        $message = $result['data']['message'] ?? ($error ?? 'Tidak ada respon');
        $rc      = $result['data']['rc'] ?? '-';
        $sn      = $result['data']['sn'] ?? '';
        $isOk    = $status === 'Sukses';

        $badgeColor = $isOk ? '#22c55e' : '#ef4444';
        $json       = json_encode($result ?? ['error' => $error], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $reqJson    = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $snDisplay  = $sn ?: '—';

        $html = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Debug Digiflazz</title>
<style>
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:'Segoe UI',sans-serif;background:#0f172a;color:#e2e8f0;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
  .card{background:#1e293b;border-radius:16px;padding:32px;width:100%;max-width:680px;box-shadow:0 25px 60px rgba(0,0,0,.5)}
  h2{font-size:1.4rem;margin-bottom:6px;color:#f8fafc}
  .sub{font-size:.85rem;color:#64748b;margin-bottom:24px}
  .badge{display:inline-block;padding:4px 14px;border-radius:99px;font-weight:700;font-size:.85rem;color:#fff;background:{$badgeColor};margin-bottom:20px}
  .row{display:flex;gap:12px;margin-bottom:12px;flex-wrap:wrap}
  .box{flex:1;min-width:140px;background:#0f172a;border-radius:10px;padding:14px 16px}
  .box .label{font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;color:#64748b;margin-bottom:4px}
  .box .val{font-size:1rem;font-weight:600;color:#f1f5f9;word-break:break-all}
  pre{background:#0f172a;border-radius:10px;padding:16px;font-size:.8rem;color:#94a3b8;overflow-x:auto;line-height:1.6;white-space:pre-wrap;word-break:break-all}
  .lbl{font-size:.75rem;color:#64748b;margin:20px 0 8px;text-transform:uppercase;letter-spacing:.08em}
  .btn{display:inline-block;margin-top:20px;padding:10px 22px;background:#3b82f6;color:#fff;border-radius:8px;text-decoration:none;font-size:.85rem;font-weight:600}
  .btn:hover{background:#2563eb}
</style>
</head>
<body>
<div class="card">
  <h2>🔌 Digiflazz API — Debug</h2>
  <div class="sub">POST https://api.digiflazz.com/v1/transaction &nbsp;·&nbsp; {$duration}ms &nbsp;·&nbsp; HTTP {$httpCode}</div>
  <span class="badge">{$status}</span>
  <div class="row">
    <div class="box"><div class="label">RC</div><div class="val">{$rc}</div></div>
    <div class="box"><div class="label">SN</div><div class="val">{$snDisplay}</div></div>
    <div class="box"><div class="label">Pesan</div><div class="val">{$message}</div></div>
  </div>
  <div class="lbl">Request yang dikirim</div>
  <pre>{$reqJson}</pre>
  <div class="lbl">Response dari Digiflazz</div>
  <pre>{$json}</pre>
  <a class="btn" href="javascript:location.reload()">🔄 Coba Lagi</a>
</div>
</body>
</html>
HTML;
        return response($html, 200, ['Content-Type' => 'text/html; charset=utf-8']);
    })->name('debug.digiflazz');

    // AJAX: jumlah pengunjung online sekarang
    Route::get('/debug/online-count', function () {
        return response()->json([
            'count'   => \App\Http\Middleware\TrackOnlineVisitors::countOnline(),
            'details' => \App\Http\Middleware\TrackOnlineVisitors::getOnlineDetails(),
        ]);
    })->name('debug.online-count');

    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('plans', \App\Http\Controllers\PlanController::class);
    Route::post('cv-templates/update-all', [\App\Http\Controllers\Admin\CvTemplateController::class, 'updateAll'])->name('cv-templates.update-all');
    Route::resource('cv-templates', \App\Http\Controllers\Admin\CvTemplateController::class)->only(['index', 'edit', 'update']);
    
    // Manual Customer Provisioning
    Route::get('/customers/create', [\App\Http\Controllers\AdminCustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [\App\Http\Controllers\AdminCustomerController::class, 'store'])->name('customers.store');
    Route::post('/customers/{subscription}/revoke', [\App\Http\Controllers\AdminCustomerController::class, 'revokeAccess'])->name('customers.revoke');

    // Game Top-Up Management
    Route::match(['GET', 'POST'], 'games/sync-balance', [\App\Http\Controllers\Admin\GameController::class, 'syncBalance'])->name('games.sync-balance');
    Route::match(['GET', 'POST'], 'games/sync-product-status', [\App\Http\Controllers\Admin\GameController::class, 'syncProductStatus'])->name('games.sync-product-status');
    Route::match(['GET', 'POST'], 'games/{game}/sync-product-status', [\App\Http\Controllers\Admin\GameController::class, 'syncProductStatusForGame'])->name('games.sync-single-status');
    Route::post('games/update-balance', [\App\Http\Controllers\Admin\GameController::class, 'updateBalance'])->name('games.update-balance');
    Route::resource('games', \App\Http\Controllers\Admin\GameController::class);
    Route::get('games/{game}/products/sync', [\App\Http\Controllers\Admin\GameProductController::class, 'syncForm'])->name('games.products.sync');
    Route::post('games/{game}/products/sync', [\App\Http\Controllers\Admin\GameProductController::class, 'syncProcess'])->name('games.products.sync.process');
    Route::post('games/{game}/products/cleanup-non-idr', [\App\Http\Controllers\Admin\GameProductController::class, 'cleanupNonIdr'])->name('games.products.cleanup-non-idr');
    Route::resource('games.products', \App\Http\Controllers\Admin\GameProductController::class)->except(['create', 'store', 'show']);

    // Admin Actions (Placed before wildcard routes)
    Route::post('/transactions/clear-all', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'clearAll'])->name('transactions.clear-all');
    Route::match(['GET', 'POST'], '/transactions/cv/{id}/approve', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'approveCv'])->name('transactions.cv.approve');
    Route::match(['GET', 'POST'], '/transactions/cv/{id}/reject', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'rejectCv'])->name('transactions.cv.reject');
    Route::match(['GET', 'POST', 'DELETE'], '/transactions/cv/{id}', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'destroyCv'])->name('transactions.cv.destroy');

    // Admin Software & Kasir Order Actions
    Route::match(['GET', 'POST'], '/transactions/order/{id}/approve', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'approveOrder'])->name('transactions.order.approve')->where('id', '.*');
    Route::match(['GET', 'POST'], '/transactions/order/{id}/reject', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'rejectOrder'])->name('transactions.order.reject')->where('id', '.*');
    Route::match(['GET', 'POST', 'DELETE'], '/transactions/order/{id}', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'destroyOrder'])->name('transactions.order.destroy')->where('id', '.*');

    // Admin Transactions & Invoices Management
    Route::get('/transactions', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}/invoice', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'invoice'])->name('transactions.invoice')->where('id', '.*');
    Route::post('/transactions/{id}/update-sn', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'updateSn'])->name('transactions.update-sn')->where('id', '.*');
    Route::post('/transactions/{id}/approve', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'approve'])->name('transactions.approve')->where('id', '.*');
    Route::post('/transactions/{id}/sync-status', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'syncProviderStatus'])->name('transactions.sync-status')->where('id', '.*');
    Route::post('/transactions/{id}/refund', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'refundToBalance'])->name('transactions.refund')->where('id', '.*');
    Route::post('/transactions/{id}/manual-success', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'manualSuccess'])->name('transactions.manual-success')->where('id', '.*');
    Route::post('/transactions/{id}/reject', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'reject'])->name('transactions.reject')->where('id', '.*');
    Route::delete('/transactions/{id}', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'destroy'])->name('transactions.destroy')->where('id', '.*');

    // Admin User Deposit Management
    Route::post('/deposits/{id}/approve', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'approveDeposit'])->name('deposits.approve')->where('id', '.*');
    Route::post('/deposits/{id}/cancel', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'cancelDeposit'])->name('deposits.cancel')->where('id', '.*');
    Route::delete('/deposits/{id}', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'destroyDeposit'])->name('deposits.destroy')->where('id', '.*');

    // Customer Reviews & Feedback Management
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'destroy']);

    // Discount & Promo Settings
    Route::get('/promos', [\App\Http\Controllers\Admin\PromoSettingController::class, 'index'])->name('promos.index');
    Route::post('/promos', [\App\Http\Controllers\Admin\PromoSettingController::class, 'update'])->name('promos.update');
    Route::post('/promos/simulate', [\App\Http\Controllers\Admin\PromoSettingController::class, 'simulate'])->name('promos.simulate');

    // Maintenance Mode Toggle
    Route::post('/maintenance/toggle', function (\Illuminate\Http\Request $request) {
        $enabled = $request->input('enabled') == '1' ? '1' : '0';
        $message = trim($request->input('message') ?? '');
        if (empty($message)) {
            $message = 'Sistem ToTap Store sedang dalam peningkatan performa dan pemeliharaan berkala. Kami akan segera kembali!';
        }

        \Illuminate\Support\Facades\DB::table('settings')->updateOrInsert(
            ['key' => 'maintenance_mode'],
            ['value' => $enabled, 'updated_at' => now()]
        );
        \Illuminate\Support\Facades\DB::table('settings')->updateOrInsert(
            ['key' => 'maintenance_message'],
            ['value' => $message, 'updated_at' => now()]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'is_maintenance' => $enabled === '1',
                'message' => $enabled === '1' ? 'Mode Maintenance berhasil DIAKTIFKAN.' : 'Mode Maintenance berhasil DIMATIKAN (Website Online).',
            ]);
        }

        return back()->with('success', $enabled === '1' ? 'Mode Maintenance berhasil DIAKTIFKAN untuk seluruh pengunjung.' : 'Mode Maintenance berhasil DIMATIKAN. Website telah kembali online!');
    })->name('maintenance.toggle');
});

require __DIR__.'/auth.php';


Route::get('/software', function () {
    try {
        if (class_exists(\App\Models\Subscription::class)) {
            \App\Models\Subscription::where('status', 'ACTIVE')
                ->whereNotNull('end_date')
                ->where('end_date', '<', now()->toDateString())
                ->update(['status' => 'EXPIRED']);
        }
    } catch (\Exception $e) {}

    $softwareProducts = \App\Models\Product::where('is_active', true)->with(['plans' => function($q) {
        $q->where('is_active', true)->orderBy('price');
    }])->get();
    return view('software.index', compact('softwareProducts'));
})->name('software.index');

Route::get('/aplikasi-premium', function () {
    $apps = \App\Models\Game::where('is_active', true)
        ->where(function($q) {
            $q->where('category', 'Aplikasi Premium')
              ->orWhere('category', 'App & Entertainment')
              ->orWhere('category', 'LIKE', '%aplikasi%')
              ->orWhere('category', 'LIKE', '%streaming%');
        })
        ->get();
    return view('aplikasi-premium.index', compact('apps'));
})->name('aplikasi-premium.index');

// Dynamic XML Sitemap for Google Search Console
Route::get('/sitemap.xml', function () {
    $baseUrl = url('/');
    $games = \Illuminate\Support\Facades\Schema::hasTable('games')
        ? \App\Models\Game::where('is_active', true)->get(['slug', 'updated_at'])
        : collect();

    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    
    // Homepage
    $xml .= '<url><loc>' . $baseUrl . '</loc><changefreq>daily</changefreq><priority>1.0</priority></url>';
    $xml .= '<url><loc>' . $baseUrl . '/aplikasi-premium</loc><changefreq>daily</changefreq><priority>0.9</priority></url>';
    $xml .= '<url><loc>' . $baseUrl . '/software</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>';

    // Game & App Pages
    foreach ($games as $g) {
        $xml .= '<url><loc>' . $baseUrl . '/topup/' . $g->slug . '</loc><changefreq>daily</changefreq><priority>0.9</priority></url>';
    }

    $xml .= '</urlset>';

    return response($xml, 200, ['Content-Type' => 'application/xml']);
})->name('sitemap');
