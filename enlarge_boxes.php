<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

// 1. Change grid columns on desktop from 4 to 3
$content = preg_replace('/class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-2"/i', 'class="grid grid-cols-3 lg:grid-cols-3 gap-3"', $content);

// 2. Increase padding, min-height, and text sizes for desktop
$content = preg_replace('/class="cursor-pointer border-2 rounded-xl p-2 md:p-3 transition-all flex flex-col items-center justify-center min-h-\[60px\] md:min-h-\[80px\] text-center"/i', 'class="cursor-pointer border-2 rounded-xl p-2 md:p-4 transition-all flex flex-col items-center justify-center min-h-[60px] md:min-h-[90px] text-center"', $content);

$content = preg_replace('/class="font-bold text-gray-800 dark:text-gray-200 leading-tight text-xs md:text-sm mb-0\.5"/i', 'class="font-bold text-gray-800 dark:text-gray-200 leading-tight text-xs md:text-base mb-1"', $content);

$content = preg_replace('/class="text-indigo-600 dark:text-indigo-400 font-bold text-\[11px\] md:text-xs"/i', 'class="text-indigo-600 dark:text-indigo-400 font-bold text-[11px] md:text-sm"', $content);

file_put_contents($file, $content);
echo "Boxes enlarged.\n";
