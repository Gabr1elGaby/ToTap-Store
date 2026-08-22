<?php
$file = 'resources/views/checkout/show.blade.php';
$content = file_get_contents($file);

$oldH4 = '<h4 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">Informasi Akun Kasir</h4>';
$newH4 = '<h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Informasi Akun Kasir</h4>';

$content = str_replace($oldH4, $newH4, $content);
file_put_contents($file, $content);
echo "Updated checkout/show.blade.php\n";
