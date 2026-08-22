<?php
$welcomeFile = 'resources/views/welcome.blade.php';
$content = file_get_contents($welcomeFile);

$oldBlue = '<h2 class="text-blue-600 font-bold uppercase tracking-widest text-xs mb-2">Keunggulan Sistem</h2>';
$newBlue = '<h2 class="text-blue-600 font-bold uppercase tracking-widest text-xs mb-2">MENGAPA MEMILIH KAMI?</h2>';

$oldWhite = '<h3 class="text-3xl font-extrabold text-white">Arsitektur yang Handal & Aman</h3>';
$newWhite = '<h3 class="text-3xl font-extrabold text-white">Mengapa Harus di ToTap Store?</h3>';

$content = str_replace($oldBlue, $newBlue, $content);
$content = str_replace($oldWhite, $newWhite, $content);

file_put_contents($welcomeFile, $content);

echo "Updated Keunggulan text.\n";
