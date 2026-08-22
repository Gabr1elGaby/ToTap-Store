<?php
$file = 'resources/views/cv/index.blade.php';
$content = file_get_contents($file);

// Find the template preview thumbnail area
// Original:
// <div class="h-64 bg-gray-100 p-4 border-b border-gray-200 flex justify-center overflow-hidden">
//     <div class="w-40 h-full bg-white shadow-md relative overflow-hidden flex flex-col p-3 transition-transform hover:scale-105 duration-300 rounded-sm">
//         @if($template->slug === 'ats') ... @endif
//     </div>
// </div>
//
// We will replace this completely.

$regex = '/<div class="aspect-w-3 aspect-h-4 bg-gray-100 flex items-center justify-center p-6 border-b border-gray-100">.*?<\/div>\s*<\/div>/s';
// Wait, my initial substring showed:
// <div class="aspect-w-3 aspect-h-4 bg-gray-100 flex items-center justify-center p-6 border-b border-gray-100">
//   <!-- Template Preview Thumbnail -->
// But my next substring showed:
// <div class="h-64 bg-gray-100 p-4 border-b border-gray-200 flex justify-center overflow-hidden">
// Wait! Did I rewrite it from aspect-ratio to h-64? Let's check exactly what's in index.blade.php!
