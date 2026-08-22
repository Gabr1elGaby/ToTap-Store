<?php
$file = 'resources/views/layouts/app.blade.php';
$content = file_get_contents($file);

// Add 'dark' class to html element
if (strpos($content, '<html lang="{{ str_replace(\'_\', \'-\', app()->getLocale()) }}" class="dark">') === false) {
    $content = str_replace(
        '<html lang="{{ str_replace(\'_\', \'-\', app()->getLocale()) }}">',
        '<html lang="{{ str_replace(\'_\', \'-\', app()->getLocale()) }}" class="dark" style="background-color: #0F172A;">',
        $content
    );
}

// Ensure body has dark text color
$content = str_replace('class="font-sans antialiased"', 'class="font-sans antialiased text-white bg-slate-900"', $content);

// Ensure wrapper has dark bg
$content = str_replace('bg-gray-100 dark:bg-gray-900', 'bg-slate-900', $content);
$content = str_replace('bg-white dark:bg-gray-800', 'bg-slate-800', $content);

// Also change the favicon to the new logo while we're here
$content = preg_replace('/href="\{\{ asset\(\'images\/totap-logo-circle\.png\'\).*?>/i', 'href="{{ asset(\'images/logo-totap.png\') }}" type="image/png">', $content);

file_put_contents($file, $content);
echo "Layout app set to dark mode.\n";

// Update navigation layout
$navFile = 'resources/views/layouts/navigation.blade.php';
$navContent = file_get_contents($navFile);
$navContent = str_replace('bg-white dark:bg-gray-800', 'bg-slate-800', $navContent);
$navContent = str_replace('border-gray-100 dark:border-gray-700', 'border-slate-700', $navContent);
// Swap logo in navigation
$navContent = preg_replace('/<x-application-logo.*?\/>/i', '<img src="{{ asset(\'images/logo-totap.png\') }}" class="h-10 w-auto object-contain">', $navContent);
file_put_contents($navFile, $navContent);
echo "Navigation layout set to dark mode.\n";
