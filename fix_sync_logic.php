<?php
$file = 'app/Console/Commands/SyncVipProducts.php';
$content = file_get_contents($file);

$oldLogic = <<<PHP
                // Find or Create the Game based on brand
                \$gameSlug = Str::slug(\$item['game'] ?? 'Unknown Game');
                \$game = Game::firstOrCreate(
                    ['slug' => \$gameSlug],
                    [
                        'name' => \$item['game'] ?? 'Unknown Game',
                        'is_active' => true,
                        // Defaults
                        'requires_zone_id' => in_array(\$item['game'] ?? 'Unknown Game', ['Mobile Legends', 'Free Fire']),
                        'description' => 'Top Up ' . (\$item['game'] ?? 'Unknown Game') . ' via VIP Reseller',
                    ]
                );

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

$newLogic = <<<PHP
                // Cek apakah game ini sudah ada di database kita
                // Kita akan mencocokkan nama game dari VIP dengan yang ada di DB
                // Bisa pakai mapping khusus jika nama dari VIP beda dengan nama di DB
                \$vipGameName = \$item['game'] ?? 'Unknown Game';
                
                // Mapping manual untuk mengatasi perbedaan nama
                \$mapping = [
                    'Mobile Legends' => 'Mobile Legend', // VIP -> DB kita
                ];
                
                \$searchName = \$mapping[\$vipGameName] ?? \$vipGameName;

                \$game = Game::where('name', 'LIKE', '%' . \$searchName . '%')->first();

                // Jika game tidak ada di database kita, LEWATI (jangan ditambahkan)
                if (!\$game) {
                    continue; 
                }

                // Jika game ada, barulah kita update atau masukkan produknya
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
echo "Sync command fixed.\n";
