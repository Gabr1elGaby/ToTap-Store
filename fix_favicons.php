<?php

$files = [
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/guest.blade.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace("asset('images/totap-logo.png')", "asset('images/totap-logo.png') . '?v=' . time()", $content);
        file_put_contents($file, $content);
    }
}
echo "Favicons updated with cache buster.\n";
