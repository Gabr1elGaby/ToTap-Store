<?php
$file = 'app/Http/Controllers/TopUpController.php';
$content = file_get_contents($file);

$oldArray = <<<PHP
        // 4. PENGELOMPOKAN KE KATEGORI (Universal untuk Semua Game)
        \$categories = [
            'Mata Uang Game' => collect(), // Diamonds, Robux, Points, Cash
            'Pass & Member' => collect(),  // Battle Pass, Starlight, Weekly, Member
            'Item & Lainnya' => collect(), // Name Change, Squad, dll
        ];
PHP;

$newArray = <<<PHP
        // 4. PENGELOMPOKAN KE KATEGORI (Universal untuk Semua Game)
        \$categories = [
            'Pass & Member' => collect(),  // Battle Pass, Starlight, Weekly, Member
            'Item & Lainnya' => collect(), // Name Change, Squad, dll
            'Mata Uang Game' => collect(), // Diamonds, Robux, Points, Cash
        ];
PHP;

$content = str_replace($oldArray, $newArray, $content);
file_put_contents($file, $content);
echo "Category order updated.\n";
