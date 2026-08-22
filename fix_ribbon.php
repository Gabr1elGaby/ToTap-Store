<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$content = str_replace('DISKON {$maxDiscount}%', 'DISKON {{ $maxDiscount }}%', $content);

file_put_contents($file, $content);
echo "Ribbon syntax fixed.\n";
