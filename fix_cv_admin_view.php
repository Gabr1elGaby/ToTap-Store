<?php
$editFile = 'resources/views/admin/cv-templates/edit.blade.php';

if (file_exists($editFile)) {
    $editContent = file_get_contents($editFile);
    if (strpos($editContent, 'name="price_normal"') === false) {
        $priceNormalHtml = <<<BLADE
        </div>
        <div class="mb-4">
            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="price_normal">Normal Price (Harga Coret, Opsional)</label>
            <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="price_normal" type="number" step="0.01" name="price_normal" value="{{ old('price_normal', \$template->price_normal) }}" />
        </div>
BLADE;
        $editContent = preg_replace('/(name="price"[^>]*><\/div>)/i', '$1' . "\n" . $priceNormalHtml, $editContent);
        file_put_contents($editFile, $editContent);
        echo "Updated cv-templates/edit.blade.php\n";
    } else {
        echo "Already updated.\n";
    }
}
