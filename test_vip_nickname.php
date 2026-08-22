<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = env('VIP_RESELLER_API_KEY');
$apiId = env('VIP_RESELLER_API_ID');
$sign = md5($apiId . $apiKey);

$response = Http::asForm()->post('https://vip-reseller.co.id/api/game-feature', [
    'key' => $apiKey,
    'sign' => $sign,
    'type' => 'get-nickname',
    'code' => 'valorant',
    'target' => '4Some1',
    'additional_target' => '021105'
]);

echo "Valorant Nickname:\n";
echo $response->body() . "\n\n";

$response2 = Http::asForm()->post('https://vip-reseller.co.id/api/game-feature', [
    'key' => $apiKey,
    'sign' => $sign,
    'type' => 'get-nickname',
    'code' => 'mobile-legends', // maybe mobile-legends?
    'target' => '12345678',
    'additional_target' => '1234'
]);

echo "MLBB Nickname:\n";
echo $response2->body() . "\n";
