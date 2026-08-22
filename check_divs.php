<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

$open = preg_match_all('/<div\b[^>]*>/', $content);
$close = preg_match_all('/<\/div>/', $content);

echo "Open divs: $open\n";
echo "Close divs: $close\n";
