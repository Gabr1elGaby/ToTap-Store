<?php
// 1. Revert application-logo.blade.php
$file = 'resources/views/components/application-logo.blade.php';
$content = file_get_contents($file);
$content = preg_replace('/class="h-12 w-12 rounded-full object-cover shadow-sm ring-2 ring-white\/50"/i', 'class="h-12 w-auto object-contain"', $content);
file_put_contents($file, $content);

// 2. Revert welcome.blade.php
$file2 = 'resources/views/welcome.blade.php';
$content2 = file_get_contents($file2);
$content2 = preg_replace('/class="h-16 w-16 rounded-full object-cover shadow-md ring-2 ring-white\/50"/i', 'class="h-16 w-auto object-contain"', $content2);
$content2 = preg_replace('/class="h-20 w-20 rounded-full object-cover grayscale hover:grayscale-0 transition-all shadow-md ring-2 ring-gray-600\/50"/i', 'class="h-20 w-auto object-contain grayscale hover:grayscale-0 transition-all"', $content2);

// Revert to original logo (not the circle one) just in case, but wait, the favicon is using circle, the images here are still using totap-logo.png, let's make sure.
// Yes, earlier I only changed href="..." for favicons. The src="..." is still totap-logo.png.
file_put_contents($file2, $content2);

echo "Logos reverted.\n";
