<?php
$file = 'resources/views/products/show.blade.php';
$content = file_get_contents($file);

// 1. Add Favicon
$headOld = '<title>{{ $product->name }} - ToTap Store</title>';
$headNew = "<title>{{ \$product->name }} - ToTap Store</title>\n        <link rel=\"icon\" href=\"{{ asset('images/logo-totap-v2.png') }}\" type=\"image/png\">";
$content = str_replace($headOld, $headNew, $content);

// 2. Fix Grid Alignment (grid-cols-3 -> flex wrap center, or max-w-4xl grid-cols-2)
$gridOld = '<div class="grid grid-cols-1 md:grid-cols-3 gap-8 justify-center">';
$gridNew = '<div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto justify-center">';
$content = str_replace($gridOld, $gridNew, $content);

file_put_contents($file, $content);
echo "Updated products/show.blade.php with favicon and center alignment.\n";
