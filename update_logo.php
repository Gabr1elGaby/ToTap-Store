<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

// Remove the old 'G' logo
$gLogoPattern = '/<div class="h-10 w-10 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-2xl mr-3 shadow-lg shadow-indigo-600\/30">\s*G\s*<\/div>/is';
$content = preg_replace($gLogoPattern, '<img src="{{ asset(\'images/totap-logo.png\') }}" alt="ToTap Store" class="h-12 w-auto mr-3 object-contain drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]">', $content);

// Remove the text logo next to it if we already have the image
$textLogoPattern = '/<a href="#beranda" class="text-xl font-bold text-white tracking-tight uppercase">ToTap Store<\/a>/is';
$content = preg_replace($textLogoPattern, '', $content);

// Update footer
$footerPattern = '/<div class="text-white font-bold text-lg mb-4 md:mb-0">ToTap Store<\/div>/is';
$content = preg_replace($footerPattern, '<img src="{{ asset(\'images/totap-logo.png\') }}" alt="ToTap Store" class="h-10 w-auto mb-4 md:mb-0 object-contain grayscale hover:grayscale-0 transition-all">', $content);

file_put_contents($file, $content);
echo "Welcome page updated with new logo.\n";
