<?php
$file = 'app/Console/Commands/SyncVipProducts.php';
$content = file_get_contents($file);

$oldLogic = <<<PHP
                // Update or Create the Product
                \$product = GameProduct::updateOrCreate(
                    ['provider_product_id' => \$item['code']],
                    [
                        'game_id' => \$game->id,
                        'name' => \$item['name'],
                        'provider' => 'vip_reseller',
                        'provider_price' => \$item['price']['basic'] ?? \$item['price'],
                        'price' => ceil((\$item['price']['basic'] ?? \$item['price']) * 1.1), // 10% markup
                        'status' => \$item['status'], // 'available' or 'empty'
                        'is_active' => (\$item['status'] === 'available'),
                    ]
                );
PHP;

$newLogic = <<<PHP
                // Update or Create the Product
                \$product = GameProduct::updateOrCreate(
                    ['product_code' => \$item['code']],
                    [
                        'game_id' => \$game->id,
                        'name' => \$item['name'],
                        'provider' => 'vip_reseller',
                        'price_modal' => \$item['price']['basic'] ?? \$item['price'],
                        'price_sell' => ceil((\$item['price']['basic'] ?? \$item['price']) * 1.1), // 10% markup
                        'status' => \$item['status'], // 'available' or 'empty'
                    ]
                );
PHP;

$content = str_replace($oldLogic, $newLogic, $content);
file_put_contents($file, $content);
echo "Sync command fixed columns.\n";
