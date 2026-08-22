<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$content = str_replace('<span class="-ml-3 text-2xl', '<span class="ml-3 text-2xl', $content);

file_put_contents($file, $content);
echo "Adjusted margin between logo and text.\n";
