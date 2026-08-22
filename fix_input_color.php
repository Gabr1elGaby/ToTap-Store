<?php
$file = 'resources/views/components/auth-modals.blade.php';
$content = file_get_contents($file);

$content = str_replace(
    'class="w-full px-3 py-2 bg-gray-50 border',
    'class="w-full px-3 py-2 bg-gray-50 text-gray-900 border',
    $content
);

file_put_contents($file, $content);
echo "Added text-gray-900 to auth modal inputs.\n";
