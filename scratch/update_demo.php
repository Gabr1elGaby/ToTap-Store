<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

DB::table('products')->where('slug', 'sistem-kasir')->update([
    'demo_url' => 'http://127.0.0.1:8001'
]);

echo "Updated demo URL to http://127.0.0.1:8001\n";
