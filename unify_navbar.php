<?php

// 1. Update Welcome
$welcomeFile = 'resources/views/welcome.blade.php';
$content = file_get_contents($welcomeFile);
$content = preg_replace('/<nav class="bg-gray-900.*?<\/nav>/s', "@include('partials.navbar')", $content);
file_put_contents($welcomeFile, $content);

// 2. Update Product Show (POS)
$productFile = 'resources/views/products/show.blade.php';
if (file_exists($productFile)) {
    $content = file_get_contents($productFile);
    // Replace navbar
    $content = preg_replace('/<nav class="bg-white shadow-sm.*?<\/nav>/s', "@include('partials.navbar')", $content);
    // Dark mode
    $content = str_replace('text-gray-900 bg-gray-50', 'text-white bg-gray-900', $content);
    $content = str_replace('bg-white', 'bg-gray-800 border-gray-700', $content);
    $content = str_replace('text-gray-900', 'text-white', $content);
    $content = str_replace('text-gray-800', 'text-gray-200', $content);
    $content = str_replace('text-gray-600', 'text-gray-400', $content);
    $content = str_replace('text-gray-500', 'text-gray-400', $content);
    file_put_contents($productFile, $content);
}

// 3. Update CV Index
$cvFile = 'resources/views/cv/index.blade.php';
if (file_exists($cvFile)) {
    $content = file_get_contents($cvFile);
    // Replace navbar
    $content = preg_replace('/<nav class="bg-gray-900.*?<\/nav>/s', "@include('partials.navbar')", $content);
    // Dark mode
    $content = str_replace('bg-gray-50 text-gray-900', 'bg-gray-900 text-white', $content);
    $content = str_replace('bg-white', 'bg-gray-800 border-gray-700', $content);
    $content = str_replace('text-gray-900', 'text-white', $content);
    $content = str_replace('text-gray-800', 'text-gray-200', $content);
    $content = str_replace('text-gray-600', 'text-gray-400', $content);
    $content = str_replace('text-gray-500', 'text-gray-400', $content);
    // Also include Righteous font in head if not there
    if (strpos($content, 'Righteous') === false) {
        $content = str_replace('</head>', '<link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet"></head>', $content);
    }
    file_put_contents($cvFile, $content);
}

// Also ensure Product show has Righteous font for the navbar
$productFile = 'resources/views/products/show.blade.php';
if (file_exists($productFile)) {
    $content = file_get_contents($productFile);
    if (strpos($content, 'Righteous') === false) {
        $content = str_replace('</head>', '<link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet"></head>', $content);
    }
    file_put_contents($productFile, $content);
}

echo "Unified navbar and dark mode applied to POS and CV pages.\n";
