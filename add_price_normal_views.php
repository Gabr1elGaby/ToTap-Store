<?php
$createFile = 'resources/views/admin/plans/create.blade.php';
$editFile = 'resources/views/admin/plans/edit.blade.php';

$createContent = file_get_contents($createFile);
$editContent = file_get_contents($editFile);

$priceNormalCreate = <<<BLADE
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="price_normal">Normal Price (Harga Coret, Opsional)</label>
                            <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="price_normal" type="number" step="0.01" name="price_normal" />
                            <p class="text-xs text-gray-500 mt-1">Isi jika ingin menampilkan harga coret diskon. Kosongkan jika tidak ada diskon.</p>
BLADE;

$priceNormalEdit = <<<BLADE
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="price_normal">Normal Price (Harga Coret, Opsional)</label>
                            <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="price_normal" type="number" step="0.01" name="price_normal" value="{{ old('price_normal', \$plan->price_normal) }}" />
                            <p class="text-xs text-gray-500 mt-1">Isi jika ingin menampilkan harga coret diskon. Kosongkan jika tidak ada diskon.</p>
BLADE;

// In create.blade.php
if (strpos($createContent, 'name="price_normal"') === false) {
    $createContent = str_replace(
        'name="price" required />'."\n".'                        </div>',
        'name="price" required />'."\n".$priceNormalCreate,
        $createContent
    );
    file_put_contents($createFile, $createContent);
}

// In edit.blade.php
if (strpos($editContent, 'name="price_normal"') === false) {
    // Note: Edit probably has value="{{ ... }}"
    $editContent = preg_replace(
        '/(name="price" value="[^"]*" required \/>\s*<\/div>)/i',
        '$1' . "\n" . $priceNormalEdit,
        $editContent
    );
    file_put_contents($editFile, $editContent);
}

echo "Added price_normal to views.\n";
