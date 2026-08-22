<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

// Change xl:w-2/3 to xl:w-3/5
$content = preg_replace('/class="w-full xl:w-2\/3 space-y-6"/', 'class="w-full xl:w-7/12 space-y-6"', $content);

// Change xl:w-1/3 to xl:w-2/5
$content = preg_replace('/class="w-full xl:w-1\/3 space-y-6"/', 'class="w-full xl:w-5/12 space-y-6"', $content);

file_put_contents($file, $content);
echo "Adjusted column widths.\n";
