<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

// 1. Fix the hover state which might also be light
$content = str_replace(
    'hover:bg-gray-50 dark:hover:bg-gray-700/50',
    'hover:bg-gray-900',
    $content
);

// 2. Fix the selected state
$content = str_replace(
    "'border-indigo-600 bg-indigo-50 dark:bg-indigo-900/30' : 'border-gray-200 dark:border-gray-700'",
    "'border-blue-500 bg-gray-900' : 'border-gray-700 bg-gray-800'",
    $content
);

file_put_contents($file, $content);

echo "Fixed payment method background colors.\n";
