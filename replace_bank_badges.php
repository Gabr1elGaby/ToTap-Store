<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

// 1. Replace BCA badge
$content = preg_replace(
    '/<div class="text-xs font-bold px-2 py-1 bg-blue-100 text-blue-700 rounded">BCA<\/div>/',
    '<img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" class="h-6 object-contain bg-white px-2 py-1 rounded shadow-sm">',
    $content
);

// 2. Replace BNI badge (checking what classes it had)
// We might not know exact classes, so I'll use regex to match the inner text 'BNI' inside a div in that section
$content = preg_replace(
    '/<div class="text-xs font-bold px-2 py-1 bg-[^>]*>BNI<\/div>/',
    '<img src="https://upload.wikimedia.org/wikipedia/id/5/55/BNI_logo.svg" alt="BNI" class="h-6 object-contain bg-white px-2 py-1 rounded shadow-sm">',
    $content
);

// 3. Replace BRI badge
$content = preg_replace(
    '/<div class="text-xs font-bold px-2 py-1 bg-[^>]*>BRI<\/div>/',
    '<img src="https://upload.wikimedia.org/wikipedia/commons/2/2e/BRI_2020.svg" alt="BRI" class="h-6 object-contain bg-white px-2 py-1 rounded shadow-sm">',
    $content
);

// 4. Replace Mandiri badge
$content = preg_replace(
    '/<div class="text-xs font-bold px-2 py-1 bg-[^>]*>MANDIRI<\/div>/',
    '<img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" alt="Mandiri" class="h-6 object-contain bg-white px-2 py-1 rounded shadow-sm">',
    $content
);

file_put_contents($file, $content);

echo "Replaced all bank badges with official SVG logos.\n";
