<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$trx = \App\Models\Transaction::latest()->first();
echo "ID: " . $trx->id . "\n";
echo "SNAP TOKEN: " . $trx->snap_token . "\n";
