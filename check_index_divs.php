<?php
$file = 'resources/views/cv/index.blade.php';
$content = file_get_contents($file);
$open = substr_count($content, '<div');
$close = substr_count($content, '</div');
echo "Open: $open, Close: $close\n";
