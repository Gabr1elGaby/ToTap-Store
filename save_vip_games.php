<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$response = \Illuminate\Support\Facades\Http::asForm()->post("https://vip-reseller.co.id/api/game-feature", [
    'key' => env('VIP_RESELLER_API_KEY'),
    'sign' => md5(env('VIP_RESELLER_API_ID') . env('VIP_RESELLER_API_KEY')),
    'type' => 'services',
    'filter_type' => 'game'
]);

$data = $response->json();
file_put_contents('vip_games.json', json_encode($data, JSON_PRETTY_PRINT));
echo "Saved to vip_games.json\n";
