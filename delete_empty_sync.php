<?php
$file = 'app/Http/Controllers/Admin/GameProductController.php';
$content = file_get_contents($file);

$oldSyncBlock = <<<PHP
            // Only process if it matches the game exactly
            if (stripos(\$item['game'], \$request->filter_value) !== false) {
                \$jual = \$modal + \$request->markup_flat;

                GameProduct::updateOrCreate(
                    ['game_id' => \$game->id, 'product_code' => \$item['code']],
                    [
                        'name' => \$item['name'],
                        'price_modal' => \$modal,
                        'price_sell' => \$jual,
                        'status' => \$item['status']
                    ]
                );
                \$count++;
            }
PHP;

$newSyncBlock = <<<PHP
            // Only process if it matches the game exactly
            if (stripos(\$item['game'], \$request->filter_value) !== false) {
                
                // JIKA STATUS EMPTY/GANGGUAN DI PUSAT, KITA HAPUS SAJA DARI DATABASE KITA
                // AGAR TIDAK MENUH-MENUHIN HALAMAN ADMIN
                if (strtolower(\$item['status']) === 'empty' || strtolower(\$item['status']) === 'gangguan' || strtolower(\$item['status']) === 'error') {
                    GameProduct::where('game_id', \$game->id)->where('product_code', \$item['code'])->delete();
                    continue; // Skip creating it
                }

                \$jual = \$modal + \$request->markup_flat;

                GameProduct::updateOrCreate(
                    ['game_id' => \$game->id, 'product_code' => \$item['code']],
                    [
                        'name' => \$item['name'],
                        'price_modal' => \$modal,
                        'price_sell' => \$jual,
                        'status' => \$item['status']
                    ]
                );
                \$count++;
            }
PHP;

$content = str_replace($oldSyncBlock, $newSyncBlock, $content);
file_put_contents($file, $content);
echo "GameProductController updated to delete empty products.\n";
