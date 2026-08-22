<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

// Revert the gigantic logo
$content = str_replace('class="h-24 w-auto object-contain transform scale-125"', 'class="h-16 w-auto object-contain"', $content);

// Make the navbar taller to accommodate a bigger logo!
// Find <div class="flex justify-between h-16"> and change to h-20
$content = str_replace('<div class="flex justify-between h-16">', '<div class="flex justify-between h-20">', $content);

// Also fix the text next to it so it doesn't wrap weirdly (TOTAP STORE was wrapping)
// Ensure flex items-center and maybe whitespace-nowrap
$content = str_replace('<span class="text-xl font-bold text-white tracking-tight uppercase" style="letter-spacing: 1px;">TOTAP STORE</span>', '<span class="text-xl font-bold text-white tracking-tight uppercase whitespace-nowrap" style="letter-spacing: 1px;">TOTAP STORE</span>', $content);

file_put_contents($file, $content);
echo "Fixed welcome logo overflow.\n";

// Fix application-logo
$logoFile = 'resources/views/components/application-logo.blade.php';
$logoContent = file_get_contents($logoFile);
$logoContent = str_replace('class="h-20 w-auto object-contain transform scale-110 origin-left"', 'class="h-12 w-auto object-contain"', $logoContent);
$logoContent = str_replace('<span class="text-xl font-bold text-gray-800 dark:text-white tracking-tight uppercase"', '<span class="text-xl font-bold text-gray-800 dark:text-white tracking-tight uppercase whitespace-nowrap"', $logoContent);
file_put_contents($logoFile, $logoContent);
echo "Fixed application-logo overflow.\n";
