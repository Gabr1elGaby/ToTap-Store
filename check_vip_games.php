<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$api = app(\App\Services\VipResellerService::class);
$response = \Illuminate\Support\Facades\Http::asForm()->post("https://vip-reseller.co.id/api/game-feature", [
    'key' => env('VIP_RESELLER_API_KEY'),
    'sign' => md5(env('VIP_RESELLER_API_ID') . env('VIP_RESELLER_API_KEY')),
    'type' => 'services',
    'filter_type' => 'game'
]);

$data = $response->json();
if(isset($data['data']) && is_array($data['data'])) {
    print_r(\);
        if (stripos($game['name'], 'valorant') !== false || stripos($game['code'], 'valorant') !== false) {
            echo "MATCH: " . $game['name'] . " -> " . $game['code'] . "\n";
        }
        if (stripos($game['name'], 'mobile legend') !== false) {
            echo "MATCH ML: " . $game['name'] . " -> " . $game['code'] . "\n";
        }
    }
} else {
    print_r($data);
}
