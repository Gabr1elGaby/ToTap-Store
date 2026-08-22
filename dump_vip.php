<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$vip = app(App\Services\VipResellerService::class);
$res = $vip->getGameProducts();
print_r($res['data'][0] ?? 'No data');
