<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$features = "Laporan Keuangan Real-time\nSistem Manajemen Stok & Inventaris\nAkses Multi-User (Admin & Kasir)";
\App\Models\Product::whereNull('features')->update(['features' => $features]);
echo "Updated features for existing products.\n";
