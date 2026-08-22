<?php
$file = 'routes/web.php';
$content = file_get_contents($file);

$oldRoute = "Route::post('/topup/{slug}/process', [\App\Http\Controllers\TopUpController::class, 'process'])->name('topup.process');";
$newRoute = $oldRoute . "\nRoute::post('/topup/{slug}/check-nickname', [\App\Http\Controllers\TopUpController::class, 'checkNickname'])->name('topup.check-nickname');";

$content = str_replace($oldRoute, $newRoute, $content);
file_put_contents($file, $content);
echo "Route added.\n";

$controller = 'app/Http/Controllers/TopUpController.php';
$ctrlContent = file_get_contents($controller);

$method = <<<PHP
    public function checkNickname(Request \$request, \$slug)
    {
        \$game = Game::where('slug', \$slug)->firstOrFail();
        
        \$api = app(\App\Services\VipResellerService::class);
        \$response = \$api->checkNickname(\$game->slug, \$request->player_id, \$request->zone_id ?? '');
        
        return response()->json(\$response);
    }
}
PHP;

$ctrlContent = preg_replace('/\}\s*$/', "\n" . $method, $ctrlContent);
file_put_contents($controller, $ctrlContent);
echo "Controller updated.\n";
