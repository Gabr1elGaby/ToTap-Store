<?php
$file = 'routes/web.php';
$content = file_get_contents($file);

// Add max discount query
$discountQuery = <<<PHP
    // GET MAX DISCOUNT FOR TOP UP GAMES
    \$maxGameDiscount = \Illuminate\Support\Facades\DB::table('game_products')
        ->where('is_promo', true)
        ->where('price_normal', '>', 0)
        ->whereColumn('price_normal', '>', 'price_sell')
        ->selectRaw('MAX(ROUND(((price_normal - price_sell) / price_normal) * 100)) as max_discount')
        ->value('max_discount') ?? 0;
PHP;

// Insert it before the view return
$content = str_replace(
    'return view(\'welcome\', compact(\'products\', \'totalUsers\', \'totalTransactions\'));',
    $discountQuery . "\n    return view('welcome', compact('products', 'totalUsers', 'totalTransactions', 'maxGameDiscount'));",
    $content
);

file_put_contents($file, $content);
echo "Added maxGameDiscount to routes.\n";
