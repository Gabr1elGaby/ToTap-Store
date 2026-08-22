<?php
$welcomeFile = 'resources/views/welcome.blade.php';
$content = file_get_contents($welcomeFile);

$oldClass = 'class="grid grid-cols-1 md:grid-cols-2 gap-6 text-center divide-y md:divide-y-0 md:divide-x divide-gray-800"';
$newClass = 'class="grid grid-cols-1 md:grid-cols-2 gap-6 text-center"';

$content = str_replace($oldClass, $newClass, $content);
file_put_contents($welcomeFile, $content);

echo "Removed all dividing lines from the stats section.\n";
