<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

$oldQRIS = '<div class="text-xs font-bold px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-gray-600 dark:text-gray-300">QRIS</div>';

// Replacing the ugly gray text box with the official QRIS SVG logo from Wikimedia
$newQRIS = '<img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS" class="h-6 object-contain bg-white px-2 py-1 rounded shadow-sm">';

$content = str_replace($oldQRIS, $newQRIS, $content);
file_put_contents($file, $content);

echo "Replaced QRIS text badge with official logo image.\n";
