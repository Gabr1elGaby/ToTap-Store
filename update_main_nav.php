<?php
$file = 'resources/views/layouts/navigation.blade.php';
$content = file_get_contents($file);

$oldLogo = '<img src="{{ asset(\'images/logo-totap-v2.png\') }}" class="h-10 w-auto object-contain">';
$newLogo = '<div class="flex items-center gap-2"><img src="{{ asset(\'images/logo-totap-v2.png\') }}" class="h-10 w-auto object-contain drop-shadow-md"><span class="text-xl text-white tracking-widest whitespace-nowrap" style="font-family: \'Righteous\', cursive; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">TOTAP STORE</span></div>';

$content = str_replace($oldLogo, $newLogo, $content);
file_put_contents($file, $content);
echo "Added TOTAP STORE text to the main navigation layout.\n";
