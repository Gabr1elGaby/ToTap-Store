<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

// Add pb-12 to the footer buttons container
$content = str_replace(
    '<div class="mt-8 pt-6 border-t border-gray-200 flex justify-between">',
    '<div class="mt-8 pt-6 pb-12 border-t border-gray-200 flex justify-between">',
    $content
);

file_put_contents($file, $content);
echo "Added pb-12 to footer.\n";
