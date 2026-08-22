<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

// Make grid 3 columns on mobile
$content = preg_replace('/class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3"/i', 'class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-2"', $content);

// Make padding smaller and minimum height smaller
$content = preg_replace('/class="cursor-pointer border-2 rounded-xl p-3 transition-all flex flex-col justify-center min-h-\[80px\]"/i', 'class="cursor-pointer border-2 rounded-xl p-2 transition-all flex flex-col items-center justify-center min-h-[60px] text-center"', $content);

// Change name to short_name
$content = str_replace('{{ $product->name }}', '{{ $product->_short_name ?? $product->name }}', $content);

// Make font smaller for the name
$content = preg_replace('/class="font-bold text-gray-800 dark:text-gray-200 mb-1 leading-tight text-sm"/i', 'class="font-bold text-gray-800 dark:text-gray-200 leading-tight text-xs mb-0.5"', $content);

// Make price smaller
$content = preg_replace('/class="text-indigo-600 dark:text-indigo-400 font-bold text-sm"/i', 'class="text-indigo-600 dark:text-indigo-400 font-bold text-[11px]"', $content);

file_put_contents($file, $content);
echo "Show blade ultra compact applied.\n";
