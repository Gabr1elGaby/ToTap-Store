<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

// 1. Fix the main container so the left column can stretch and allow sticky to work
$content = str_replace(
    '<div class="flex flex-col lg:flex-row gap-8 items-start">',
    '<div class="flex flex-col lg:flex-row gap-8">',
    $content
);

// 2. Fix the right column so it scrolls if it's too tall (Beli Sekarang cut off)
$oldStyle = 'style="position: sticky; top: 6rem; align-self: flex-start;"';
$newStyle = 'style="position: sticky; top: 6rem; align-self: flex-start; max-height: 85vh; overflow-y: auto; padding-right: 5px;"';

$content = str_replace($oldStyle, $newStyle, $content);

// also hide the scrollbar for webkit to make it look clean
$customScrollbar = <<<BLADE
<style>
    .hide-scroll::-webkit-scrollbar {
        width: 4px;
    }
    .hide-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .hide-scroll::-webkit-scrollbar-thumb {
        background: #4b5563;
        border-radius: 4px;
    }
</style>
BLADE;

// Inject style into the right column
$content = str_replace(
    '<div class="w-full xl:w-5/12 space-y-6" style="',
    '<div class="w-full xl:w-5/12 space-y-6 hide-scroll" style="',
    $content
);

// Put the <style> at the top of the file
if (strpos($content, '.hide-scroll') === false) {
    $content = $customScrollbar . "\n" . $content;
}

file_put_contents($file, $content);
echo "Fixed layout issues and added scrollable sticky column.\n";
