<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$api = app(\App\Services\VipResellerService::class);
$res1 = $api->checkNickname('valorant', '4Some1', '021105');
$res2 = $api->checkNickname('mobile-legends', '12345678', '1234');

print_r($res1);
print_r($res2);
