<?php
$file = 'routes/web.php';
$content = file_get_contents($file);

$route_regex = '/Route::post\(\'\/cv\/preview\/\{slug\}\', \[\\\App\\\Http\\\Controllers\\\CvController::class, \'preview\'\]\)->name\(\'cv\.preview\'\);/';

$new_route = <<<PHP
Route::post('/cv/preview/{slug}', [\App\Http\Controllers\CvController::class, 'preview'])->name('cv.preview');
Route::get('/cv/preview-example/{slug}', [\App\Http\Controllers\CvController::class, 'previewExample'])->name('cv.previewExample');
PHP;

$content = preg_replace($route_regex, $new_route, $content);

file_put_contents($file, $content);
echo "Added previewExample route.\n";
