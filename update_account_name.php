<?php
$file = 'resources/views/checkout/payment.blade.php';
$content = file_get_contents($file);

$content = str_replace('<strong>a.n. ToTap Store (Amelia)</strong>', '<strong>a.n. Gabriel</strong>', $content);

file_put_contents($file, $content);
echo "Updated account name to Gabriel.\n";
