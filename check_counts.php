<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$games = \App\Models\Game::withCount('products')->get();
foreach ($games as $g) {
    echo $g->name . ': ' . $g->products_count . "\n";
}
