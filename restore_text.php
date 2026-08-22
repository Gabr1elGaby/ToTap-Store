<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$oldLogo = '<img src="{{ asset(\'images/logo-totap.png\') }}" alt="ToTap Store" class="h-16 w-auto object-contain drop-shadow-[0_0_15px_rgba(59,130,246,0.5)]">';
$newLogo = '<img src="{{ asset(\'images/logo-totap.png\') }}" alt="ToTap Store" class="h-16 w-auto object-contain drop-shadow-md">' . "\n                        " . '<span class="-ml-3 text-2xl text-white tracking-widest whitespace-nowrap" style="font-family: \'Righteous\', cursive; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">TOTAP STORE</span>';

$content = str_replace($oldLogo, $newLogo, $content);
file_put_contents($file, $content);
echo "Added TOTAP STORE text back to navbar.\n";
