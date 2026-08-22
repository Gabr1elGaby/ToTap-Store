<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

// Fix body classes
$content = str_replace(
    '<body class="bg-gray-100 text-gray-900 font-sans antialiased overflow-hidden h-screen flex flex-col"',
    '<body class="bg-gray-100 text-gray-900 font-sans antialiased md:overflow-hidden md:h-screen flex flex-col"',
    $content
);

// Fix main wrapper classes
$content = preg_replace(
    '/<div class="flex flex-1 overflow-hidden relative flex-col md:flex-row">/',
    '<div class="flex flex-1 md:overflow-hidden relative flex-col md:flex-row">',
    $content,
    1
);

// Fix form container classes
$content = preg_replace(
    '/<div class="flex-1 overflow-y-auto p-6 scroll-smooth" id="form-container">/',
    '<div class="flex-1 md:overflow-y-auto p-4 sm:p-6 scroll-smooth" id="form-container">',
    $content,
    1
);

file_put_contents($file, $content);
echo "Mobile layout fixed to use native scrolling.\n";
