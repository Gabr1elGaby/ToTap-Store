<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

// 1. Fix BNI logo
$oldBni = 'https://upload.wikimedia.org/wikipedia/id/5/55/BNI_logo.svg';
$newBni = 'https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/800px-BNI_logo.svg.png';
$content = str_replace($oldBni, $newBni, $content);

// 2. Make the right column sticky
$oldColumn = '<div class="w-full xl:w-5/12 space-y-6">';
$newColumn = '<div class="w-full xl:w-5/12 space-y-6 lg:sticky lg:top-24">';

// 3. Ensure the parent flex container allows sticky (needs items-start)
// I'll just blindly replace 'gap-8' with 'gap-8 items-start' assuming they have a flex/gap container.
// Actually, I can just use a regex for the wrapper:
$content = preg_replace(
    '/<div class="flex flex-col xl:flex-row gap-6">/i',
    '<div class="flex flex-col xl:flex-row gap-6 items-start">',
    $content
);
// just in case they used lg:
$content = preg_replace(
    '/<div class="flex flex-col lg:flex-row gap-6">/i',
    '<div class="flex flex-col lg:flex-row gap-6 items-start">',
    $content
);
$content = preg_replace(
    '/<div class="flex flex-col lg:flex-row gap-8">/i',
    '<div class="flex flex-col lg:flex-row gap-8 items-start">',
    $content
);
$content = preg_replace(
    '/<div class="flex flex-col xl:flex-row gap-8">/i',
    '<div class="flex flex-col xl:flex-row gap-8 items-start">',
    $content
);


$content = str_replace($oldColumn, $newColumn, $content);

file_put_contents($file, $content);
echo "Fixed BNI logo and made right column sticky.\n";
