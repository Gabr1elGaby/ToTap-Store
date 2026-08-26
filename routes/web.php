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
    $maxGameDiscount = \Illuminate\Support\Facades\DB::table('game_products')
        ->where('is_promo', true)
        ->where('price_normal', '>', 0)
        ->whereColumn('price_normal', '>', 'price_sell')
        ->selectRaw('MAX(ROUND(((price_normal - price_sell) / price_normal) * 100)) as max_discount')
        ->value('max_discount') ?? 0;
    // GET MAX DISCOUNT FOR SOFTWARE & CV SERVICES
    $maxPlanDiscount = (int) (\Illuminate\Support\Facades\DB::table('plans')
        ->where('is_active', true)
        ->where('price_normal', '>', 0)
        ->whereColumn('price_normal', '>', 'price')
        ->selectRaw('MAX(ROUND(((price_normal - price) / price_normal) * 100)) as max_discount')
        ->value('max_discount') ?? 0);

    $maxCvDiscount = 0;
    if (\Illuminate\Support\Facades\Schema::hasTable('cv_templates')) {
        $maxCvDiscount = (int) (\Illuminate\Support\Facades\DB::table('cv_templates')
            ->where('status', 'active')
            ->where('price_normal', '>', 0)
            ->whereColumn('price_normal', '>', 'price')
            ->selectRaw('MAX(ROUND(((price_normal - price) / price_normal) * 100)) as max_discount')
            ->value('max_discount') ?? 0);
    }

    $maxSoftwareDiscount = max($maxPlanDiscount, $maxCvDiscount);

    // CUSTOMER REVIEWS STATS (100% REAL DATA ONLY)
    $totalReviews = \App\Models\CustomerReview::count();
    $avgRating = $totalReviews > 0 ? round((float) \App\Models\CustomerReview::avg('rating'), 1) : 0.0;

    return view('welcome', compact('products', 'totalUsers', 'totalTransactions', 'maxGameDiscount', 'maxSoftwareDiscount', 'avgRating', 'totalReviews'));
});

// SUBMIT CUSTOMER REVIEW
Route::post('/api/reviews', [\App\Http\Controllers\CustomerReviewController::class, 'store'])->name('reviews.store');






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
    Route::get('/payment/{order_number}', [\App\Http\Controllers\PaymentController::class, 'page'])->name('payment.page');
    Route::get('/payment/status/{order_number}', [\App\Http\Controllers\PaymentController::class, 'status'])->name('payment.status');
    Route::post('/payment/simulate/{order_number}', [\App\Http\Controllers\PaymentController::class, 'simulate'])->name('payment.simulate');
    Route::get('/payment/success/{order_number}', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
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

    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('plans', \App\Http\Controllers\PlanController::class);
    Route::post('cv-templates/update-all', [\App\Http\Controllers\Admin\CvTemplateController::class, 'updateAll'])->name('cv-templates.update-all');
    Route::resource('cv-templates', \App\Http\Controllers\Admin\CvTemplateController::class)->only(['index', 'edit', 'update']);
    
    // Manual Customer Provisioning
    Route::get('/customers/create', [\App\Http\Controllers\AdminCustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [\App\Http\Controllers\AdminCustomerController::class, 'store'])->name('customers.store');
    Route::post('/customers/{subscription}/revoke', [\App\Http\Controllers\AdminCustomerController::class, 'revokeAccess'])->name('customers.revoke');

    // Game Top-Up Management
    Route::post('games/sync-balance', [\App\Http\Controllers\Admin\GameController::class, 'syncBalance'])->name('games.sync-balance');
    Route::post('games/update-balance', [\App\Http\Controllers\Admin\GameController::class, 'updateBalance'])->name('games.update-balance');
    Route::resource('games', \App\Http\Controllers\Admin\GameController::class);
    Route::get('games/{game}/products/sync', [\App\Http\Controllers\Admin\GameProductController::class, 'syncForm'])->name('games.products.sync');
    Route::post('games/{game}/products/sync', [\App\Http\Controllers\Admin\GameProductController::class, 'syncProcess'])->name('games.products.sync.process');
    Route::resource('games.products', \App\Http\Controllers\Admin\GameProductController::class)->except(['create', 'store', 'show']);

    // Admin Actions (Placed before wildcard routes)
    Route::post('/transactions/clear-all', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'clearAll'])->name('transactions.clear-all');
    Route::match(['GET', 'POST'], '/transactions/cv/{id}/approve', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'approveCv'])->name('transactions.cv.approve');
    Route::match(['GET', 'POST'], '/transactions/cv/{id}/reject', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'rejectCv'])->name('transactions.cv.reject');
    Route::match(['GET', 'POST', 'DELETE'], '/transactions/cv/{id}', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'destroyCv'])->name('transactions.cv.destroy');

    // Admin Transactions & Invoices Management
    Route::get('/transactions', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}/invoice', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'invoice'])->name('transactions.invoice')->where('id', '.*');
    Route::post('/transactions/{id}/approve', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'approve'])->name('transactions.approve')->where('id', '.*');
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
