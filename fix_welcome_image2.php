<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

// Let's use preg_replace to add style="padding-top:100%;" to the div
$content = preg_replace('/<div class="relative[^>]*overflow-hidden bg-gray-200">/', '<div class="relative overflow-hidden bg-gray-200" style="padding-top:100%;">', $content);

file_put_contents($file, $content);
echo "Added padding-top:100% style directly.\n";
