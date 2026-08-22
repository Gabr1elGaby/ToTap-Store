<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);
$content = str_replace('pt-[100%]', '', $content);
$content = str_replace('class="relative  overflow-hidden bg-gray-200"', 'class="relative overflow-hidden bg-gray-200 aspect-square"', $content);
file_put_contents($file, $content);
echo "Fixed JIT CSS issue in welcome.blade.php\n";
