<?php
$file = 'resources/views/software/index.blade.php';
$content = file_get_contents($file);

// Add inline padding to ensure the buttons are spacious regardless of compiled Tailwind classes
$oldBeli = 'class="bg-gray-900 border border-gray-700 text-white hover:bg-gray-700 px-5 py-2 rounded font-semibold transition text-sm"';
$newBeli = 'class="bg-blue-600 hover:bg-blue-700 text-white rounded font-bold transition text-sm shadow-md" style="padding: 10px 24px; letter-spacing: 0.5px;"';

$content = str_replace($oldBeli, $newBeli, $content);
file_put_contents($file, $content);

echo "Button spacing fixed.\n";
