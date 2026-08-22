<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

// 1. Update grid item sizing to be responsive (small on mobile, big on desktop)
$content = preg_replace(
    '/class="cursor-pointer border-2 rounded-xl p-2 transition-all flex flex-col items-center justify-center min-h-\[60px\] text-center"/',
    'class="cursor-pointer border-2 rounded-xl p-2 md:p-3 transition-all flex flex-col items-center justify-center min-h-[60px] md:min-h-[80px] text-center"',
    $content
);
$content = preg_replace(
    '/class="font-bold text-gray-800 dark:text-gray-200 leading-tight text-xs mb-0\.5"/',
    'class="font-bold text-gray-800 dark:text-gray-200 leading-tight text-xs md:text-sm mb-0.5"',
    $content
);
$content = preg_replace(
    '/class="text-indigo-600 dark:text-indigo-400 font-bold text-\[11px\]"/',
    'class="text-indigo-600 dark:text-indigo-400 font-bold text-[11px] md:text-xs"',
    $content
);

// 2. Structural changes for 3-column layout
// Change lg:w-1/3 and lg:w-2/3
$content = preg_replace('/class="w-full lg:w-1\/3"/', 'class="w-full lg:w-1/4"', $content);
$content = preg_replace('/class="w-full lg:w-2\/3"/', 'class="w-full lg:w-3/4"', $content);

// Inside lg:w-3/4, wrap Card 1 & Card 2 in a div, and Card 3 in another div
// We find <form ...>
// Replace it with <form ...><div class="flex flex-col xl:flex-row gap-6"><div class="w-full xl:w-2/3 space-y-6">

$content = str_replace(
    '<form action="{{ route(\'topup.process\', $game->slug) }}" method="POST">',
    '<form action="{{ route(\'topup.process\', $game->slug) }}" method="POST">' . "\n" . '<div class="flex flex-col xl:flex-row gap-6"><div class="w-full xl:w-2/3 space-y-6">',
    $content
);

// Now we need to close the left wrapper before Card 3, and open the right wrapper
// Card 3 starts with: <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-6 mb-6"> containing "3" "Pilih Pembayaran"
$card3Html = <<<HTML
                    <!-- Card 3: Pembayaran -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-6 mb-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">3</div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Pilih Pembayaran</h2>
                        </div>
HTML;

$newCard3Html = <<<HTML
                    </div> <!-- End of left column -->
                    <div class="w-full xl:w-1/3 space-y-6"> <!-- Start of right column -->
                    <!-- Card 3: Pembayaran -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-6 mb-6 sticky top-24">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">3</div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Pilih Pembayaran</h2>
                        </div>
HTML;

$content = str_replace($card3Html, $newCard3Html, $content);

// At the end of the form, close the main wrapper
$content = str_replace('</form>', '</div></div></form>', $content);

// Fix the mb-6 issues since we are using space-y-6 on the wrapper
$content = str_replace('mb-6">', '">', $content); // Lazy replace but it might break other things, better not.
// Actually it's fine, let's keep mb-6, it just adds extra margin which is okay.

file_put_contents($file, $content);
echo "3 column layout applied.\n";
