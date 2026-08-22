<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$deleted = \App\Models\GameProduct::where('name', 'LIKE', '%Riot Cash%')
    ->orWhere('name', 'LIKE', '%Gift Card%')
    ->orWhere('name', 'LIKE', '%Voucher%')
    ->orWhere('name', 'LIKE', '%EUR%')
    ->orWhere('name', 'LIKE', '%USD%')
    ->delete();
    
echo "Deleted " . $deleted . " products.\n";
