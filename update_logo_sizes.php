<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

// Navbar in welcome
$content = preg_replace('/<img src="{{ asset\(\'images\/totap-logo\.png\'\) }}" alt="TTS" class="h-10 w-auto object-contain">/i', '<img src="{{ asset(\'images/totap-logo.png\') }}" alt="TTS" class="h-16 w-auto object-contain">', $content);

// The text next to it and the gap
// Currently: <div class="flex items-center gap-3"> ... 
// Let's change gap-3 to gap-1
$content = preg_replace('/<div class="flex items-center gap-3">(\s*<img src="{{ asset\(\'images\/totap-logo\.png\'\) }}" alt="TTS" class="h-16)/i', '<div class="flex items-center gap-1">$1', $content);

// Footer in welcome
$content = preg_replace('/<img src="{{ asset\(\'images\/totap-logo\.png\'\) }}" alt="ToTap Store" class="h-10 w-auto object-contain grayscale hover:grayscale-0 transition-all">/i', '<img src="{{ asset(\'images/totap-logo.png\') }}" alt="ToTap Store" class="h-14 w-auto object-contain grayscale hover:grayscale-0 transition-all">', $content);

// The text in footer gap
$content = preg_replace('/<div class="flex items-center gap-3 mb-4 md:mb-0">(\s*<img src="{{ asset\(\'images\/totap-logo\.png\'\) }}" alt="ToTap Store")/i', '<div class="flex items-center gap-1 mb-4 md:mb-0">$1', $content);

file_put_contents($file, $content);
echo "Welcome page updated.\n";

$logoFile = 'resources/views/components/application-logo.blade.php';
$logoContent = '<div class="flex items-center gap-1">
    <img src="{{ asset(\'images/totap-logo.png\') }}" alt="ToTap Store" class="h-14 w-auto object-contain">
    <span class="text-xl font-bold text-gray-800 dark:text-white tracking-tight uppercase" style="letter-spacing: 1px;">TOTAP STORE</span>
</div>';
file_put_contents($logoFile, $logoContent);
echo "Application logo component updated.\n";
