<?php
$file = 'routes/web.php';
$content = file_get_contents($file);

// 1. Tangkap semua rute CV & Produk yang masih public
$content = preg_replace('/Route::get\(\'\/produk\/.*?\'product\.show\'\);\n/ms', '', $content);
$content = preg_replace('/^\/\/ CV Payment Gateway Routes \(Public\)\n.*?\'cv\.payment\.simulate\'\);\n/ms', '', $content);
$content = preg_replace('/^\/\/ CV Maker Routes\n.*?\'cv\.previewExample\'\);\n/ms', '', $content);

// 2. Tambahkan ke dalam grup auth
$authGroupStart = "Route::middleware(['auth'])->group(function () {";
$newRoutes = <<<PHP
Route::middleware(['auth'])->group(function () {
    // Tampilan Detail Produk (Subscription/Voucher)
    Route::get('/produk/{slug}', [\App\Http\Controllers\PublicProductController::class, 'show'])->name('product.show');
    
    // Fitur CV Maker (Wajib Login)
    Route::get('/cv', [\App\Http\Controllers\CvController::class, 'index'])->name('cv.index');
    Route::get('/cv/create', [\App\Http\Controllers\CvController::class, 'create'])->name('cv.create');
    Route::post('/cv', [\App\Http\Controllers\CvController::class, 'store'])->name('cv.store');
    Route::get('/cv/download/{id}', [\App\Http\Controllers\CvController::class, 'download'])->name('cv.download');
    Route::post('/cv/preview/{slug}', [\App\Http\Controllers\CvController::class, 'preview'])->name('cv.preview');
    Route::get('/cv/preview-example/{slug}', [\App\Http\Controllers\CvController::class, 'previewExample'])->name('cv.previewExample');
    
    // CV Payment / Checkout (Wajib Login)
    Route::get('/checkout/cv/{cv_id}', [\App\Http\Controllers\CvPaymentController::class, 'show'])->name('cv.checkout.show');
    Route::post('/payment/cv/simulate/{cv_id}', [\App\Http\Controllers\CvPaymentController::class, 'simulate'])->name('cv.payment.simulate');
PHP;

$content = str_replace($authGroupStart, $newRoutes, $content);
file_put_contents($file, $content);
echo "All features locked down.\n";
