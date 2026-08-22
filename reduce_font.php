<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$oldH3 = '<h3 class="text-gray-900 text-lg group-hover:text-blue-600 transition-colors" style="font-family: \'Orbitron\', sans-serif; font-weight: 900; letter-spacing: 1px;">TOP UP GAME</h3>';
$newH3 = '<h3 class="text-gray-900 group-hover:text-blue-600 transition-colors" style="font-family: \'Orbitron\', sans-serif; font-weight: 900; font-size: 14px; letter-spacing: 0.5px;">TOP UP GAME</h3>';

$content = str_replace($oldH3, $newH3, $content);
file_put_contents($file, $content);
echo "Font size reduced.\n";
