<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$content = str_replace('images/kategori-game-3.png', 'images/kategori-game-4.png', $content);

file_put_contents($file, $content);
echo "Image updated to the transparent neon version.\n";
