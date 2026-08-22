<?php
$file = 'resources/views/topup/index.blade.php';
$content = file_get_contents($file);

$content = str_replace('bg-gray-50', 'bg-slate-900', $content);
$content = str_replace('text-gray-900', 'text-white', $content);
$content = str_replace('text-gray-600', 'text-slate-400', $content);
$content = str_replace('bg-white', 'bg-slate-800 border border-slate-700', $content);
$content = str_replace('bg-gray-200', 'bg-slate-700', $content);
$content = str_replace('border-gray-100', 'border-slate-700', $content);

// Text styling for GAMING CENTER title
$content = str_replace('from-blue-600 to-indigo-600', 'from-blue-400 to-indigo-400', $content);

file_put_contents($file, $content);
echo "Topup index converted to dark theme.\n";
