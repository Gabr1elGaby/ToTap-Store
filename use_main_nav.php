<?php
$files = [
    'resources/views/cv/index.blade.php',
    'resources/views/products/show.blade.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace("@include('partials.navbar')", "@include('layouts.navigation')", $content);
        file_put_contents($file, $content);
    }
}
echo "Replaced partials.navbar with layouts.navigation on CV and POS pages.\n";
