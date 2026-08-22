<?php
$fileSync = 'resources/views/admin/games/products/sync.blade.php';
$contentSync = file_get_contents($fileSync);

$newInput = <<<HTML
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Markup Persentase (%)</label>
                        <p class="text-sm text-gray-500 mb-2">Ambil untung berdasarkan persentase (cocok agar barang murah tidak kemahalan, dan barang mahal tetap untung besar). Misal isi 2 untuk untung 2%.</p>
                        <input type="number" step="0.1" name="markup_percent" value="2" min="0" class="w-full rounded dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Markup Tambahan (Rupiah Flat)</label>
                        <p class="text-sm text-gray-500 mb-2">Tambahan keuntungan rupiah flat. Total Harga = Modal + (Modal * Persentase) + Markup Rupiah. Isi 0 jika hanya ingin pakai persentase.</p>
                        <input type="number" name="markup_flat" value="300" min="0" class="w-full rounded dark:bg-gray-700 dark:text-white" required>
                    </div>
HTML;

$contentSync = preg_replace('/<div class="mb-4">\s*<label.*?Markup Keuntungan.*?<\/div>/is', $newInput, $contentSync);
file_put_contents($fileSync, $contentSync);

$fileController = 'app/Http/Controllers/Admin/GameProductController.php';
$contentController = file_get_contents($fileController);

// Update validation
$contentController = str_replace(
    "'markup_flat' => 'required|numeric|min:0'",
    "'markup_flat' => 'required|numeric|min:0',\n            'markup_percent' => 'required|numeric|min:0'",
    $contentController
);

// Update price calculation
$oldPriceCalc = <<<PHP
        \$count = 0;
        foreach (\$cheapestItems as \$uniqueKey => \$cItem) {
            \$jual = \$cItem['modal'] + \$request->markup_flat;
PHP;

$newPriceCalc = <<<PHP
        \$count = 0;
        foreach (\$cheapestItems as \$uniqueKey => \$cItem) {
            \$percentProfit = \$cItem['modal'] * (\$request->markup_percent / 100);
            \$jual = \$cItem['modal'] + \$percentProfit + \$request->markup_flat;
            // Bulatkan harga jual (hilangkan desimal)
            \$jual = ceil(\$jual);
PHP;

$contentController = str_replace($oldPriceCalc, $newPriceCalc, $contentController);
file_put_contents($fileController, $contentController);

echo "Smart Markup implemented.\n";
