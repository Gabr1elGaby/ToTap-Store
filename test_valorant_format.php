<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$api = app(\App\Services\VipResellerService::class);

echo "Test 2: Combined (target=4Some1#21104, additional=)\n";
$res2 = $api->checkNickname('valorant', '4Some1#21104', '');
print_r($res2);

sleep(6);

echo "Test 3: Combined no hash (target=4Some121104, additional=)\n";
$res3 = $api->checkNickname('valorant', '4Some121104', '');
print_r($res3);
