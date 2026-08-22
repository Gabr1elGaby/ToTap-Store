<?php
// 1. MIGRATION
$migrationFile = 'database/migrations/2026_08_21_074057_add_price_normal_to_cv_templates_table.php';
$migrationContent = <<<PHP
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('cv_templates', function (Blueprint \$table) {
            \$table->decimal('price_normal', 15, 2)->nullable()->after('price');
        });
    }
    public function down(): void {
        Schema::table('cv_templates', function (Blueprint \$table) {
            \$table->dropColumn('price_normal');
        });
    }
};
PHP;
file_put_contents($migrationFile, $migrationContent);

// 2. MODEL
$modelFile = 'app/Models/CvTemplate.php';
$modelContent = file_get_contents($modelFile);
if (strpos($modelContent, "'price_normal'") === false) {
    $modelContent = str_replace("'price',", "'price', 'price_normal',", $modelContent);
    file_put_contents($modelFile, $modelContent);
}

// 3. CONTROLLER
$controllerFile = 'app/Http/Controllers/Admin/CvTemplateController.php';
$controllerContent = file_get_contents($controllerFile);
if (strpos($controllerContent, "'price_normal'") === false) {
    $controllerContent = str_replace(
        "'price' => 'required|numeric|min:0',",
        "'price' => 'required|numeric|min:0',\n            'price_normal' => 'nullable|numeric|min:0',",
        $controllerContent
    );
    file_put_contents($controllerFile, $controllerContent);
}

// 4. ADMIN VIEWS
$createFile = 'resources/views/admin/cv_templates/create.blade.php';
if (file_exists($createFile)) {
    $createContent = file_get_contents($createFile);
    if (strpos($createContent, 'name="price_normal"') === false) {
        $priceNormalHtml = <<<BLADE
        </div>
        <div class="mb-4">
            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="price_normal">Normal Price (Harga Coret, Opsional)</label>
            <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="price_normal" type="number" step="0.01" name="price_normal" />
        </div>
BLADE;
        $createContent = preg_replace('/(name="price"[^>]*><\/div>)/i', '$1' . "\n" . $priceNormalHtml, $createContent);
        file_put_contents($createFile, $createContent);
    }
}

$editFile = 'resources/views/admin/cv_templates/edit.blade.php';
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
    }
}

// 5. FRONTEND VIEW (CV INDEX)
$frontFile = 'resources/views/cv/index.blade.php';
$frontContent = file_get_contents($frontFile);
$oldPriceHtml = '<span class="text-blue-600 font-bold text-lg">Rp{{ number_format($template->price, 0, \',\', \'.\') }}</span>';
$newPriceHtml = <<<BLADE
                        <div class="flex flex-col">
                            @if(\$template->price_normal > 0 && \$template->price_normal > \$template->price)
                                <span class="text-xs text-gray-500 line-through">Rp{{ number_format(\$template->price_normal, 0, ',', '.') }}</span>
                            @endif
                            <span class="text-blue-600 font-bold text-lg">Rp{{ number_format(\$template->price, 0, ',', '.') }}</span>
                        </div>
BLADE;
$frontContent = str_replace($oldPriceHtml, $newPriceHtml, $frontContent);

// Add ribbon to the top of the card
$oldCardHeader = '<div class="bg-gray-800 rounded-lg shadow-md border border-gray-700 overflow-hidden hover:border-blue-500 transition duration-300 flex flex-col h-full">';
$newCardHeader = <<<BLADE
<div class="bg-gray-800 rounded-lg shadow-md border border-gray-700 overflow-hidden hover:border-blue-500 transition duration-300 flex flex-col h-full relative">
    @if(\$template->price_normal > 0 && \$template->price_normal > \$template->price)
    @php
        \$discountPercent = round(((\$template->price_normal - \$template->price) / \$template->price_normal) * 100);
    @endphp
    <div class="absolute top-0 left-0 bg-red-600 text-white font-black text-[10px] px-3 py-1 uppercase tracking-wider z-20" style="border-bottom-right-radius: 12px; box-shadow: 2px 2px 5px rgba(0,0,0,0.3);">
        Diskon {{ \$discountPercent }}%
    </div>
    @endif
BLADE;
$frontContent = str_replace($oldCardHeader, $newCardHeader, $frontContent);

file_put_contents($frontFile, $frontContent);

echo "All CV template discount changes applied.\n";
