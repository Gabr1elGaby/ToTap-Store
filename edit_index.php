<?php
$file = 'resources/views/cv/index.blade.php';
$content = file_get_contents($file);

$regex = '/<div class="aspect-w-3 aspect-h-4 bg-gray-100 flex items-center justify-center p-6 border-b border-gray-100">.*?<\/div>\s*<\/div>\s*<\/div>\s*<\/div>/s';
// Wait, I need to be careful with closing divs.
// I will just use regex to match from `<div class="aspect-w-3` down to `<!-- Template Preview Thumbnail -->` up to the `@endif` and its closing divs.
