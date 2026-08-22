<?php
$file = 'resources/views/admin/games/products/sync.blade.php';
$content = file_get_contents($file);

$promoSection = <<<HTML
                    <!-- SECTION DISKON MASSAL -->
                    <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg mb-6">
                        <div class="mb-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="mass_promo_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-gray-700 dark:text-gray-300 font-bold">Aktifkan Trik Diskon Coret Masal? (Flash Sale)</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1 ml-6">Jika dicentang, seluruh produk yang ditarik akan langsung dilabeli "PROMO" dengan harga normal palsu yang dicoret, seolah-olah Anda sedang memberikan diskon besar-besaran!</p>
                        </div>
                        
                        <div class="ml-6">
                            <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Tinggikan Harga Normal Sebesar (%)</label>
                            <p class="text-sm text-gray-500 mb-2">Misal isi 10. Maka Harga Normal (Coret) akan dibuat 10% lebih mahal dari Harga Jual asli Anda.</p>
                            <input type="number" step="0.1" name="mass_promo_percent" value="10" min="0" class="w-full rounded dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
HTML;

$content = str_replace('<button type="submit"', $promoSection . "\n                    <button type=\"submit\"", $content);
file_put_contents($file, $content);

// Now update the Controller
$fileController = 'app/Http/Controllers/Admin/GameProductController.php';
$contentController = file_get_contents($fileController);

// Update validation
$oldVal = <<<PHP
            'markup_flat' => 'required|numeric|min:0',
            'markup_percent' => 'required|numeric|min:0'
        ]);
PHP;
$newVal = <<<PHP
            'markup_flat' => 'required|numeric|min:0',
            'markup_percent' => 'required|numeric|min:0',
            'mass_promo_percent' => 'nullable|numeric|min:0'
        ]);
PHP;
$contentController = str_replace($oldVal, $newVal, $contentController);

// Update logic inside the loop
$oldLogic = <<<PHP
            // Bulatkan harga jual (hilangkan desimal)
            \$jual = ceil(\$jual);

            GameProduct::create([
                'game_id' => \$game->id, 
                'product_code' => \$cItem['code'],
                'name' => \$cItem['name'],
                'price_modal' => \$cItem['modal'],
                'price_sell' => \$jual,
                'status' => \$cItem['status']
            ]);
PHP;

$newLogic = <<<PHP
            // Bulatkan harga jual (hilangkan desimal)
            \$jual = ceil(\$jual);

            // Trik Diskon Masal
            \$isPromo = \$request->has('mass_promo_active');
            \$priceNormal = null;
            if (\$isPromo) {
                \$fakeMarkup = \$jual * (\$request->mass_promo_percent / 100);
                \$priceNormal = ceil(\$jual + \$fakeMarkup);
                // Agar harganya terlihat cantik (berakhiran 00)
                \$priceNormal = round(\$priceNormal / 100) * 100;
            }

            GameProduct::create([
                'game_id' => \$game->id, 
                'product_code' => \$cItem['code'],
                'name' => \$cItem['name'],
                'price_modal' => \$cItem['modal'],
                'price_sell' => \$jual,
                'status' => \$cItem['status'],
                'is_promo' => \$isPromo,
                'price_normal' => \$priceNormal
            ]);
PHP;

$contentController = str_replace($oldLogic, $newLogic, $contentController);
file_put_contents($fileController, $contentController);

echo "Mass promo implemented.\n";
