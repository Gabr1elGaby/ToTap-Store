<?php
$file = 'app/Http/Controllers/Admin/GameProductController.php';
$content = file_get_contents($file);

$oldMath = <<<PHP
            if (\$isPromo) {
                \$fakeMarkup = \$jual * (\$request->mass_promo_percent / 100);
                \$priceNormal = ceil(\$jual + \$fakeMarkup);
                // Agar harganya terlihat cantik (berakhiran 00)
                \$priceNormal = round(\$priceNormal / 100) * 100;
            }
PHP;

$newMath = <<<PHP
            if (\$isPromo) {
                // Rumus Diskon Terbalik: priceNormal = priceSell / (1 - (discount / 100))
                \$discountDec = \$request->mass_promo_percent / 100;
                if (\$discountDec >= 1) \$discountDec = 0.99; // Cegah error bagi 0
                \$priceNormal = ceil(\$jual / (1 - \$discountDec));
                
                // Agar harganya terlihat cantik (berakhiran 00)
                \$priceNormal = round(\$priceNormal / 100) * 100;
            }
PHP;

$content = str_replace($oldMath, $newMath, $content);
file_put_contents($file, $content);

// Database fix
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$valGame = \App\Models\Game::where('name', 'LIKE', '%Valorant%')->first();
if ($valGame) {
    $products = $valGame->products()->where('is_promo', true)->get();
    foreach ($products as $p) {
        $p->price_normal = round(ceil($p->price_sell / (1 - 0.40)) / 100) * 100;
        $p->save();
    }
}

$robGame = \App\Models\Game::where('name', 'LIKE', '%Roblox%')->first();
if ($robGame) {
    $products = $robGame->products()->where('is_promo', true)->get();
    foreach ($products as $p) {
        $p->price_normal = round(ceil($p->price_sell / (1 - 0.10)) / 100) * 100;
        $p->save();
    }
}

$mlGame = \App\Models\Game::where('name', 'LIKE', '%Mobile%')->first();
if ($mlGame) {
    $products = $mlGame->products()->where('is_promo', true)->get();
    foreach ($products as $p) {
        $p->price_normal = round(ceil($p->price_sell / (1 - 0.10)) / 100) * 100;
        $p->save();
    }
}

echo "Math fixed.\n";
