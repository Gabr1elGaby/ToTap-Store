<?php
$file = 'app/Http/Controllers/TopUpController.php';
$content = file_get_contents($file);

$oldCategories = <<<PHP
        // 4. PENGELOMPOKAN KE KATEGORI
        \$categories = [
            'Membership / Weekly Pass' => collect(),
            'Starlight / Battle Pass' => collect(),
            'Diamonds' => collect(),
            'Item & Lainnya' => collect(),
        ];

        foreach (\$uniqueProducts as \$product) {
            \$name = strtolower(\$product->name);
            
            if (str_contains(\$name, 'weekly') || (str_contains(\$name, 'pass') && !str_contains(\$name, 'twilight') && !str_contains(\$name, 'starlight'))) {
                \$categories['Membership / Weekly Pass']->push(\$product);
            } elseif (str_contains(\$name, 'starlight') || str_contains(\$name, 'battle')) {
                \$categories['Starlight / Battle Pass']->push(\$product);
            } elseif (str_contains(\$name, 'name') || str_contains(\$name, 'nama') || str_contains(\$name, 'squad') || str_contains(\$name, 'twilight') || str_contains(\$name, 'crystal') || str_contains(\$name, 'ticket') || str_contains(\$name, 'token')) {
                \$categories['Item & Lainnya']->push(\$product);
            } elseif (str_contains(\$name, 'bundle')) {
                \$categories['Starlight / Battle Pass']->push(\$product);
            } else {
                \$categories['Diamonds']->push(\$product);
            }
        }
PHP;

$newCategories = <<<PHP
        // 4. PENGELOMPOKAN KE KATEGORI (Universal untuk Semua Game)
        \$categories = [
            'Mata Uang Game' => collect(), // Diamonds, Robux, Points, Cash
            'Pass & Member' => collect(),  // Battle Pass, Starlight, Weekly, Member
            'Item & Lainnya' => collect(), // Name Change, Squad, dll
        ];

        foreach (\$uniqueProducts as \$product) {
            \$name = strtolower(\$product->name);
            
            if (str_contains(\$name, 'weekly') || str_contains(\$name, 'pass') || str_contains(\$name, 'starlight') || str_contains(\$name, 'member') || str_contains(\$name, 'battle') || str_contains(\$name, 'subscription')) {
                \$categories['Pass & Member']->push(\$product);
            } elseif (str_contains(\$name, 'name') || str_contains(\$name, 'nama') || str_contains(\$name, 'squad') || str_contains(\$name, 'twilight') || str_contains(\$name, 'crystal') || str_contains(\$name, 'ticket') || str_contains(\$name, 'token') || str_contains(\$name, 'gift card')) {
                \$categories['Item & Lainnya']->push(\$product);
            } else {
                \$categories['Mata Uang Game']->push(\$product);
            }
        }
PHP;

$content = str_replace($oldCategories, $newCategories, $content);
file_put_contents($file, $content);
echo "Categories updated.\n";
