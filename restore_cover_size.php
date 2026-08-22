<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

// Remove the inline style height I added in panic
$content = preg_replace('/class="w-full object-cover" style="height: 120px;"/i', 'class="w-full h-64 lg:h-48 object-cover"', $content);

file_put_contents($file, $content);
echo "Restored correct cover image class.\n";
