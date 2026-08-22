<?php
$file = 'resources/views/layouts/navigation.blade.php';
$content = file_get_contents($file);

$oldGuest = 'class="font-semibold text-white hover:text-blue-400 transition"';
$newGuest = 'class="font-semibold text-white hover:text-blue-400 border-b-2 border-transparent hover:border-blue-400 transition"';

$content = str_replace($oldGuest, $newGuest, $content);
file_put_contents($file, $content);
echo "Added hover border to guest links.\n";
