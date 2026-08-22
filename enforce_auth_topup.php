<?php
$file = 'routes/web.php';
$content = file_get_contents($file);

// 1. Remove the old topup routes
$content = preg_replace('/^\/\/ Top-Up Frontend\nRoute::get\(\'\/topup.*?checkNickname\'\]\)->name\(\'topup\.check-nickname\'\);\n/ms', '', $content);
$content = preg_replace('/^Route::get\(\'\/topup\/checkout.*?topup\.checkout\.verify\'\);\n/ms', '', $content);

// 2. Add them inside an auth middleware
$authRoutes = <<<PHP

// Fitur Top Up Game (Wajib Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/topup/{slug}', [\App\Http\Controllers\TopUpController::class, 'show'])->name('topup.show');
    Route::post('/topup/{slug}/process', [\App\Http\Controllers\TopUpController::class, 'process'])->name('topup.process');
    Route::post('/topup/{slug}/check-nickname', [\App\Http\Controllers\TopUpController::class, 'checkNickname'])->name('topup.check-nickname');
    
    Route::get('/topup/checkout/{id}', [\App\Http\Controllers\TopUpPaymentController::class, 'show'])->name('topup.checkout.show');
    Route::post('/topup/checkout/{id}/verify', [\App\Http\Controllers\TopUpPaymentController::class, 'verify'])->name('topup.checkout.verify');
});

PHP;

// Insert them before the auth, verified, role:customer group
$content = str_replace("Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {", $authRoutes . "Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {", $content);

file_put_contents($file, $content);
echo "Routes updated.\n";
