<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

// Replace the right column wrapper to FORCE sticky with inline CSS and ensure it's xl:sticky since flex-row is xl:flex-row
$old = '<div class="w-full xl:w-5/12 space-y-6 lg:sticky lg:top-24">';
$new = '<div class="w-full xl:w-5/12 space-y-6" style="position: sticky; top: 6rem; align-self: flex-start;">';

if (strpos($content, $old) !== false) {
    $content = str_replace($old, $new, $content);
} else {
    // maybe it got malformed? Let's use regex
    $content = preg_replace(
        '/<div class="w-full xl:w-5\/12 space-y-6[^>]*">/',
        '<div class="w-full xl:w-5/12 space-y-6" style="position: sticky; top: 6rem; align-self: flex-start;">',
        $content
    );
}

// Ensure the form doesn't mess with height
// Ensure parent container has items-start (which we did earlier)
// Let's just double check the regex replacement
file_put_contents($file, $content);
echo "Forced sticky using inline CSS and align-self.\n";
