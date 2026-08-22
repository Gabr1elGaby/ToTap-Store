<?php
$file = 'routes/web.php';
$content = file_get_contents($file);

// Kita kembalikan rute show/create ke public, tapi biarkan proses/bayar di auth!
$oldRoutes = <<<PHP
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

    Route::get('/topup/{slug}', [\App\Http\Controllers\TopUpController::class, 'show'])->name('topup.show');
    Route::post('/topup/{slug}/process', [\App\Http\Controllers\TopUpController::class, 'process'])->name('topup.process');
    Route::post('/topup/{slug}/check-nickname', [\App\Http\Controllers\TopUpController::class, 'checkNickname'])->name('topup.check-nickname');
    
    Route::get('/topup/checkout/{id}', [\App\Http\Controllers\TopUpPaymentController::class, 'show'])->name('topup.checkout.show');
    Route::post('/topup/checkout/{id}/verify', [\App\Http\Controllers\TopUpPaymentController::class, 'verify'])->name('topup.checkout.verify');
});
PHP;

$newRoutes = <<<PHP
// Rute Tampilan (Bisa Dilihat Publik, tapi transaksi ditahan via Pop-up)
Route::get('/produk/{slug}', [\App\Http\Controllers\PublicProductController::class, 'show'])->name('product.show');
Route::get('/cv', [\App\Http\Controllers\CvController::class, 'index'])->name('cv.index');
Route::get('/cv/create', [\App\Http\Controllers\CvController::class, 'create'])->name('cv.create');
Route::get('/cv/preview-example/{slug}', [\App\Http\Controllers\CvController::class, 'previewExample'])->name('cv.previewExample');
Route::get('/topup/{slug}', [\App\Http\Controllers\TopUpController::class, 'show'])->name('topup.show');

// Rute Transaksi / Aksi (Backend Auth Lock)
Route::middleware(['auth'])->group(function () {
    Route::post('/cv', [\App\Http\Controllers\CvController::class, 'store'])->name('cv.store');
    Route::get('/cv/download/{id}', [\App\Http\Controllers\CvController::class, 'download'])->name('cv.download');
    Route::post('/cv/preview/{slug}', [\App\Http\Controllers\CvController::class, 'preview'])->name('cv.preview');
    
    Route::get('/checkout/cv/{cv_id}', [\App\Http\Controllers\CvPaymentController::class, 'show'])->name('cv.checkout.show');
    Route::post('/payment/cv/simulate/{cv_id}', [\App\Http\Controllers\CvPaymentController::class, 'simulate'])->name('cv.payment.simulate');

    Route::post('/topup/{slug}/process', [\App\Http\Controllers\TopUpController::class, 'process'])->name('topup.process');
    Route::post('/topup/{slug}/check-nickname', [\App\Http\Controllers\TopUpController::class, 'checkNickname'])->name('topup.check-nickname');
    
    Route::get('/topup/checkout/{id}', [\App\Http\Controllers\TopUpPaymentController::class, 'show'])->name('topup.checkout.show');
    Route::post('/topup/checkout/{id}/verify', [\App\Http\Controllers\TopUpPaymentController::class, 'verify'])->name('topup.checkout.verify');
});
PHP;

$content = str_replace($oldRoutes, $newRoutes, $content);
file_put_contents($file, $content);
echo "Routes rearranged.\n";
