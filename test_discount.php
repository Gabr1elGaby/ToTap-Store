<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$game = \App\Models\Game::where('name', 'LIKE', '%Roblox%')->first();
$promoProduct = $game->products()->where('is_promo', true)->where('status', 'available')->where('price_normal', '>', 0)->orderByRaw('((price_normal - price_sell) / price_normal) DESC')->first();

if ($promoProduct) {
    echo "Product: " . $promoProduct->name . "\n";
    echo "Price Normal: " . $promoProduct->price_normal . "\n";
    echo "Price Sell: " . $promoProduct->price_sell . "\n";
    $maxDiscount = floor((($promoProduct->price_normal - $promoProduct->price_sell) / $promoProduct->price_normal) * 100);
    echo "Max Discount: " . $maxDiscount . "%\n";
} else {
    echo "No promo product found.\n";
}
