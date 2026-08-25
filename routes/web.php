<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = \App\Models\Product::where('is_active', true)->with(['plans' => function($q) {
        $q->where('is_active', true)->orderBy('price');
    }])->get();

    // REAL-TIME STATS
    $totalUsers = \App\Models\User::count();
    
    // Sum of all paid transactions across all modules
    $totalTransactions = 0;
    
    // Enterprise POS Orders
    if (class_exists(\App\Models\Order::class)) {
        $totalTransactions += \App\Models\Order::whereIn('payment_status', ['paid', 'success', 'PAID', 'SUCCESS'])->count();
    }
    
    // Top Up Transactions
    if (class_exists(\App\Models\TopupTransaction::class)) {
        $totalTransactions += \App\Models\TopupTransaction::whereIn('payment_status', ['paid', 'success', 'PAID', 'SUCCESS'])->count();
    }
    
    // CV / General Transactions
    if (class_exists(\App\Models\Transaction::class)) {
        $totalTransactions += \App\Models\Transaction::whereIn('status', ['paid', 'success', 'PAID', 'SUCCESS'])->count();
    }

        // GET MAX DISCOUNT FOR TOP UP GAMES
    $maxGameDiscount = \Illuminate\Support\Facades\DB::table('game_products')
        ->where('is_promo', true)
        ->where('price_normal', '>', 0)
        ->whereColumn('price_normal', '>', 'price_sell')
        ->selectRaw('MAX(ROUND(((price_normal - price_sell) / price_normal) * 100)) as max_discount')
        ->value('max_discount') ?? 0;
        // GET MAX DISCOUNT FOR SOFTWARE
    $maxSoftwareDiscount = \Illuminate\Support\Facades\DB::table('plans')
        ->where('is_active', true)
        ->where('price_normal', '>', 0)
        ->whereColumn('price_normal', '>', 'price')
        ->selectRaw('MAX(ROUND(((price_normal - price) / price_normal) * 100)) as max_discount')
        ->value('max_discount') ?? 0;

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
Route::match(['get', 'post'], '/api/tripay/callback', [\App\Http\Controllers\Api\TripayCallbackController::class, 'handle'])->name('tripay.callback');
Route::match(['get', 'post'], '/api/duitku/callback', [\App\Http\Controllers\Api\DuitkuCallbackController::class, 'handle'])->name('duitku.callback');
Route::get('/api/cron/sync-all', [\App\Http\Controllers\Admin\GameProductController::class, 'cronSyncAll'])->name('cron.sync-all');
Route::get('/topup-status/{slug}', [\App\Http\Controllers\TopUpController::class, 'stockStatus'])->name('topup.stock-status');
Route::get('/topup', [\App\Http\Controllers\TopUpController::class, 'index'])->name('topup.index');
Route::get('/topup/{slug}', [\App\Http\Controllers\TopUpController::class, 'show'])->name('topup.show');
Route::post('/topup/{slug}/check-nickname', [\App\Http\Controllers\TopUpController::class, 'checkNickname'])->name('topup.check-nickname');
Route::post('/topup/{slug}/process', [\App\Http\Controllers\TopUpController::class, 'process'])->name('topup.process');
Route::get('/topup/checkout/{id}', [\App\Http\Controllers\TopUpPaymentController::class, 'show'])->name('topup.checkout.show');
Route::post('/topup/checkout/{id}/verify', [\App\Http\Controllers\TopUpPaymentController::class, 'verify'])->name('topup.checkout.verify');

// Rute Transaksi / Aksi CV & Software (Backend Auth Lock)
Route::middleware(['auth'])->group(function () {
    Route::post('/cv', [\App\Http\Controllers\CvController::class, 'store'])->name('cv.store');
    Route::get('/cv/download/{id}', [\App\Http\Controllers\CvController::class, 'download'])->name('cv.download');
    Route::post('/cv/preview/{slug}', [\App\Http\Controllers\CvController::class, 'preview'])->name('cv.preview');
    
    Route::get('/checkout/cv/{cv_id}', [\App\Http\Controllers\CvPaymentController::class, 'show'])->name('cv.checkout.show');
    Route::post('/payment/cv/simulate/{cv_id}', [\App\Http\Controllers\CvPaymentController::class, 'simulate'])->name('cv.payment.simulate');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout/{plan}', [\App\Http\Controllers\CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout/{plan}/process', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/payment/{order_number}', [\App\Http\Controllers\PaymentController::class, 'page'])->name('payment.page');
    Route::get('/payment/status/{order_number}', [\App\Http\Controllers\PaymentController::class, 'status'])->name('payment.status');
    Route::post('/payment/simulate/{order_number}', [\App\Http\Controllers\PaymentController::class, 'simulate'])->name('payment.simulate');
    Route::get('/payment/success/{order_number}', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/subscription/active/{product_id}', [\App\Http\Controllers\SubscriptionController::class, 'active'])->name('subscription.active');

    Route::get('/transactions', [\App\Http\Controllers\TransactionHistoryController::class, 'index'])->name('transactions.history');
    Route::get('/transactions/{id}/invoice', [\App\Http\Controllers\TransactionHistoryController::class, 'invoice'])->name('transactions.invoice');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('plans', \App\Http\Controllers\PlanController::class);
    Route::resource('cv-templates', \App\Http\Controllers\Admin\CvTemplateController::class)->only(['index', 'edit', 'update']);
    
    // Manual Customer Provisioning
    Route::get('/customers/create', [\App\Http\Controllers\AdminCustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [\App\Http\Controllers\AdminCustomerController::class, 'store'])->name('customers.store');
    Route::post('/customers/{subscription}/revoke', [\App\Http\Controllers\AdminCustomerController::class, 'revokeAccess'])->name('customers.revoke');

    // Game Top-Up Management
    Route::post('games/update-balance', [\App\Http\Controllers\Admin\GameController::class, 'updateBalance'])->name('games.update-balance');
    Route::resource('games', \App\Http\Controllers\Admin\GameController::class);
    Route::get('games/{game}/products/sync', [\App\Http\Controllers\Admin\GameProductController::class, 'syncForm'])->name('games.products.sync');
    Route::post('games/{game}/products/sync', [\App\Http\Controllers\Admin\GameProductController::class, 'syncProcess'])->name('games.products.sync.process');
    Route::resource('games.products', \App\Http\Controllers\Admin\GameProductController::class)->except(['create', 'store', 'show']);

    // Admin Transactions & Invoices Management
    Route::get('/transactions', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}/invoice', [\App\Http\Controllers\Admin\AdminTransactionController::class, 'invoice'])->name('transactions.invoice');

    // Customer Reviews & Feedback Management
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'destroy']);
});

require __DIR__.'/auth.php';


Route::get('/software', function () {
    $softwareProducts = \App\Models\Product::where('is_active', true)->with(['plans' => function($q) {
        $q->where('is_active', true)->orderBy('price');
    }])->get();
    return view('software.index', compact('softwareProducts'));
})->name('software.index');
