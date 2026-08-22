<?php
$file = 'app/Http/Controllers/Admin/GameController.php';
$content = file_get_contents($file);

$oldValidation = <<<PHP
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
PHP;

$newValidation = <<<PHP
            'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif,webp,svg,avif|max:5120',
            'cover_image' => 'nullable|mimes:jpeg,png,jpg,gif,webp,svg,avif|max:5120',
PHP;

$content = str_replace($oldValidation, $newValidation, $content);
file_put_contents($file, $content);
echo "GameController updated to allow SVG and AVIF.\n";
