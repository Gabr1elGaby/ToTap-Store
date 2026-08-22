<?php
$file = 'app/Http/Controllers/Admin/GameProductController.php';
$content = file_get_contents($file);

$oldSyncBlock = <<<PHP
        foreach (\$items as \$item) {
            // Only process if it matches the game exactly, or sometimes we just take all returned
            if (stripos(\$item['game'], \$request->filter_value) !== false) {
                \$modal = \$item['price']['basic'] ?? 0;
PHP;

$newSyncBlock = <<<PHP
        foreach (\$items as \$item) {
            // Filter strict untuk membuang produk sampah "INFO/STOK/OPEN" dari VIP Reseller
            \$nameUpper = strtoupper(\$item['name']);
            \$modal = \$item['price']['basic'] ?? 0;

            if (\$modal <= 0) continue; // Harga modal 0 atau minus = sampah
            if (str_contains(\$nameUpper, 'OPEN')) continue;
            if (str_contains(\$nameUpper, 'CLOSE')) continue;
            if (str_contains(\$nameUpper, 'INFO')) continue;
            if (str_contains(\$nameUpper, 'RATE')) continue;
            if (str_contains(\$nameUpper, 'TESTING')) continue;
            if (str_contains(\$nameUpper, 'DUMMY')) continue;

            // Only process if it matches the game exactly
            if (stripos(\$item['game'], \$request->filter_value) !== false) {
PHP;

$content = str_replace($oldSyncBlock, $newSyncBlock, $content);
file_put_contents($file, $content);
echo "GameProductController sync method updated.\n";
