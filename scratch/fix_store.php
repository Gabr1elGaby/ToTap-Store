<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

DB::connection('kasir')->table('stores')->where('id', 1)->update([
    'subscription_ends_at' => '2026-09-17 00:00:00'
]);
echo "Store subscription_ends_at updated.";
