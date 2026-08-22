<?php
$file = 'resources/views/admin/games/products/edit.blade.php';
$content = file_get_contents($file);

$newInputs = <<<HTML
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-bold">Harga Jual (Di Website Anda)</label>
                        <p class="text-xs text-gray-500 mb-1">Jika promo aktif, ini adalah harga Diskon (Harga Akhir yang dibayar).</p>
                        <input type="number" name="price_sell" value="{{ \$product->price_sell }}" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg mb-4">
                        <div class="mb-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_promo" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ \$product->is_promo ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700 dark:text-gray-300 font-bold">Aktifkan Tanda Promo / Diskon Coret?</span>
                            </label>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 font-bold">Harga Normal (Harga Sebelum Diskon)</label>
                            <p class="text-xs text-gray-500 mb-1">Angka ini akan dicoret warna abu-abu (Contoh: <del>Rp 20.000</del>). Buat angka ini lebih mahal dari Harga Jual agar terlihat seperti diskon besar.</p>
                            <input type="number" name="price_normal" value="{{ \$product->price_normal }}" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
HTML;

$content = preg_replace('/<div class="mb-4">\s*<label.*?Harga Jual.*?<\/div>/is', $newInputs, $content);
file_put_contents($file, $content);
echo "Edit view updated.\n";
