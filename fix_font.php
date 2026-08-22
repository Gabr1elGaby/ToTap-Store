<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$fontImport = "<link href=\"https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap\" rel=\"stylesheet\">\n";

$oldH3 = '<h3 class="text-gray-900 font-bold text-base group-hover:text-blue-600 transition-colors">Top Up Game</h3>';
$newH3 = '<h3 class="text-gray-900 text-lg group-hover:text-blue-600 transition-colors" style="font-family: \'Orbitron\', sans-serif; font-weight: 900; letter-spacing: 1px;">TOP UP GAME</h3>';

// Cek apakah Orbitron sudah ada, jika belum, tambahkan di awal file
if (strpos($content, 'Orbitron') === false) {
    $content = preg_replace('/<head>/i', "<head>\n    " . $fontImport, $content);
}

$content = str_replace($oldH3, $newH3, $content);
file_put_contents($file, $content);
echo "Font changed to Orbitron.\n";
