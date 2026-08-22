<?php
$file = 'resources/views/products/show.blade.php';
$content = file_get_contents($file);

// Replace remaining bg-gray-50 with bg-gray-900
$content = str_replace('bg-gray-50', 'bg-gray-900', $content);

file_put_contents($file, $content);
echo "Fixed white background on pricing section.\n";
