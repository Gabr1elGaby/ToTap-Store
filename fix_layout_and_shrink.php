<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

// 1. Fix the main container so the left column can stretch!
$content = str_replace(
    '<div class="flex flex-col lg:flex-row gap-6 items-start">',
    '<div class="flex flex-col lg:flex-row gap-6">',
    $content
);

// 2. Remove the scrollbar stuff from the right column inline style
$oldStyle = 'style="position: sticky; top: 6rem; align-self: flex-start; max-height: 85vh; overflow-y: auto; padding-right: 5px;"';
$newStyle = 'style="position: sticky; top: 6rem; align-self: flex-start;"';
$content = str_replace($oldStyle, $newStyle, $content);
$content = str_replace('hide-scroll', '', $content); // remove the hide-scroll class

// 3. Shrink the right column UI
// Reduce padding of the main box
$content = str_replace(
    '<div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6">',
    '<div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-4">',
    $content
);

// Reduce gap between payment methods
// The payment methods are inside <div class="space-y-3">
// Need to be careful not to replace ALL space-y-3 if there are others, but it's probably fine
$content = preg_replace(
    '/<div class="space-y-3">/',
    '<div class="space-y-2">',
    $content,
    1 // only the first one which is inside the payment methods box
);

// Reduce padding inside the payment methods
$content = str_replace(
    'p-4 border-2 rounded-xl',
    'px-3 py-2 border-2 rounded-lg',
    $content
);

// Reduce Beli Sekarang button padding
$content = str_replace(
    '<button type="submit" class="w-full py-4 rounded-xl',
    '<button type="submit" class="w-full py-3 rounded-lg',
    $content
);

// 4. Fix BNI logo once and for all. It's STILL broken in their image.
// Wikimedia often blocks hotlinking to some files or it fails to load.
// Let's use a base64 encoded BNI logo or a very reliable image link.
// Since BNI is text "BNI" in orange and teal, let's just use a highly reliable url.
$reliableBni = 'https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/320px-BNI_logo.svg.png';
$content = preg_replace(
    '/<img src="[^"]*BNI_logo[^"]*"[^>]*>/i',
    '<img src="' . $reliableBni . '" alt="BNI" class="h-5 object-contain bg-white px-2 py-1 rounded shadow-sm">',
    $content
);

// Shrink all logos slightly to save space
$content = str_replace('class="h-6 object-contain', 'class="h-5 object-contain', $content);

file_put_contents($file, $content);
echo "Layout fixed and payment UI shrunk.\n";
