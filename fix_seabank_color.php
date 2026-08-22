<?php
$file = 'resources/views/checkout/payment.blade.php';
$content = file_get_contents($file);

$content = str_replace('text-[#FF6600]', 'text-orange-600" style="color: #FF6600;', $content);

// Let's also make sure the text-gray-500 for the 'a.n.' is readable. It's probably fine, but let's make it text-gray-800.
$content = str_replace('a.n. ToTap Store (Amelia)', '<strong>a.n. ToTap Store (Amelia)</strong>', $content);
$content = str_replace('text-xs text-gray-500 mt-2 text-center', 'text-sm text-gray-800 mt-2 text-center', $content);

file_put_contents($file, $content);
echo "Fixed text color using inline styles.\n";
