<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

// Fix the image aspect ratio by replacing "relative overflow-hidden bg-gray-200 aspect-square" with "w-full h-40 relative overflow-hidden bg-gray-200"
$content = preg_replace('/<div class="relative overflow-hidden bg-gray-200[^"]*">/i', '<div class="w-full h-48 relative overflow-hidden bg-gray-200">', $content);

// Add cache buster to the logos in welcome
$content = str_replace("asset('images/totap-logo.png')", "asset('images/totap-logo.png') . '?v=' . time()", $content);

file_put_contents($file, $content);
echo "Welcome page fixed.\n";

$logoFile = 'resources/views/components/application-logo.blade.php';
$logoContent = file_get_contents($logoFile);
$logoContent = str_replace("asset('images/totap-logo.png')", "asset('images/totap-logo.png') . '?v=' . time()", $logoContent);
file_put_contents($logoFile, $logoContent);
echo "Application logo component fixed.\n";
