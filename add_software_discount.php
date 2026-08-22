<?php
$file = 'routes/web.php';
$content = file_get_contents($file);

$softwareDiscountQuery = <<<PHP
    // GET MAX DISCOUNT FOR SOFTWARE
    \$maxSoftwareDiscount = \Illuminate\Support\Facades\DB::table('plans')
        ->where('is_active', true)
        ->where('price_normal', '>', 0)
        ->whereColumn('price_normal', '>', 'price')
        ->selectRaw('MAX(ROUND(((price_normal - price) / price_normal) * 100)) as max_discount')
        ->value('max_discount') ?? 0;
PHP;

$content = str_replace(
    'return view(\'welcome\', compact(\'products\', \'totalUsers\', \'totalTransactions\', \'maxGameDiscount\'));',
    $softwareDiscountQuery . "\n    return view('welcome', compact('products', 'totalUsers', 'totalTransactions', 'maxGameDiscount', 'maxSoftwareDiscount'));",
    $content
);

file_put_contents($file, $content);
echo "Added maxSoftwareDiscount to routes.\n";
