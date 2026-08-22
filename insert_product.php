<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$productId = DB::table('products')->where('slug', 'cv-maker')->value('id');
if (!$productId) {
    echo "Product not found. Exiting.\n";
    exit;
}

DB::table('plans')->insert([
    'product_id' => $productId,
    'name' => 'Pembuatan CV Profesional',
    'price' => 5000,
    'duration_days' => 0, // Lifetime/One-time
    'user_limit' => 1,
    'transaction_limit' => null,
    'is_active' => true,
    'created_at' => now(),
    'updated_at' => now()
]);
echo "Success\n";
