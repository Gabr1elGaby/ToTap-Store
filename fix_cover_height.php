<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

// Replace the tailwind height class with an explicit inline style height to bypass JIT compilation issues
$content = preg_replace('/class="w-full h-24 object-cover"/i', 'class="w-full object-cover" style="height: 120px;"', $content);
$content = preg_replace('/class="w-full h-40 object-cover"/i', 'class="w-full object-cover" style="height: 120px;"', $content);

file_put_contents($file, $content);
echo "Fixed cover image height with inline style.\n";
