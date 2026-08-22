<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

$oldBniImg = '<img src="https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/320px-BNI_logo.svg.png" alt="BNI" class="h-5 object-contain bg-white px-2 py-1 rounded shadow-sm">';
$newBniBadge = '<div class="h-5 px-2 bg-white rounded shadow-sm flex items-center justify-center italic font-black text-sm tracking-tighter" style="line-height: 1;"><span style="color: #F05A28;">B</span><span style="color: #005E6A;">NI</span></div>';

$content = str_replace($oldBniImg, $newBniBadge, $content);
file_put_contents($file, $content);
echo "Replaced BNI with custom CSS logo.\n";
