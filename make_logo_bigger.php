<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

// Make it even bigger! h-16 -> h-20 or h-24
$content = str_replace('class="h-16 w-auto object-contain"', 'class="h-24 w-auto object-contain transform scale-125"', $content);
$content = str_replace('class="h-14 w-auto object-contain grayscale', 'class="h-20 w-auto object-contain grayscale', $content);

file_put_contents($file, $content);
echo "Welcome logo size increased.\n";

$logoFile = 'resources/views/components/application-logo.blade.php';
$logoContent = file_get_contents($logoFile);
$logoContent = str_replace('class="h-14 w-auto object-contain"', 'class="h-20 w-auto object-contain transform scale-110 origin-left"', $logoContent);
file_put_contents($logoFile, $logoContent);
echo "Application logo component increased.\n";
