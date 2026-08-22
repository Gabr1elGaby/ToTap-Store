<?php
$file = 'routes/web.php';
$content = file_get_contents($file);

if (strpos($content, 'topup.checkout.show') === false) {
    $content .= "\n";
    $content .= "Route::get('/topup/checkout/{id}', [\\App\\Http\\Controllers\\TopUpPaymentController::class, 'show'])->name('topup.checkout.show');\n";
    $content .= "Route::post('/topup/checkout/{id}/verify', [\\App\\Http\\Controllers\\TopUpPaymentController::class, 'verify'])->name('topup.checkout.verify');\n";
    file_put_contents($file, $content);
    echo "Routes appended.\n";
} else {
    echo "Routes already exist.\n";
}
