<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

// Update grid layout to pack items denser
$content = preg_replace('/class="grid grid-cols-2 md:grid-cols-3 gap-4"/i', 'class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3"', $content);

// Update card styling to make them more compact
$content = preg_replace('/class="cursor-pointer border-2 rounded-xl p-4 transition-all"/i', 'class="cursor-pointer border-2 rounded-xl p-3 transition-all flex flex-col justify-center min-h-[80px]"', $content);

// Make product name smaller and truncate if too long (optional, maybe leading-tight is enough)
$content = preg_replace('/class="font-bold text-gray-800 dark:text-gray-200 mb-1 leading-tight"/i', 'class="font-bold text-gray-800 dark:text-gray-200 mb-1 leading-tight text-sm"', $content);

// Make price smaller
// $content = preg_replace('/class="text-indigo-600 dark:text-indigo-400 font-bold text-sm"/i', 'class="text-indigo-600 dark:text-indigo-400 font-bold text-xs"', $content);

file_put_contents($file, $content);
echo "Top up view compact layout applied.\n";
