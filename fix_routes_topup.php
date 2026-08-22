<?php
$file = 'routes/web.php';
$content = file_get_contents($file);

$oldRoute = "Route::get('/topup/{slug}', [\App\Http\Controllers\TopUpController::class, 'show'])->name('topup.show');";
$newRoute = "Route::get('/topup', [\App\Http\Controllers\TopUpController::class, 'index'])->name('topup.index');\nRoute::get('/topup/{slug}', [\App\Http\Controllers\TopUpController::class, 'show'])->name('topup.show');";

$content = str_replace($oldRoute, $newRoute, $content);
file_put_contents($file, $content);
echo "Routes updated.\n";
