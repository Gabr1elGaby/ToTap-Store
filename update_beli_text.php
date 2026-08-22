<?php
$file = 'resources/views/topup/index.blade.php';
$content = file_get_contents($file);

$oldBeli = '<p class="text-xs text-indigo-600 font-semibold mt-1">Beli Sekarang</p>';
$newBeli = '<p class="text-xs text-blue-400 font-semibold mt-1 tracking-wide">BELI SEKARANG</p>';

$content = str_replace($oldBeli, $newBeli, $content);
file_put_contents($file, $content);
echo "Beli Sekarang updated.\n";
