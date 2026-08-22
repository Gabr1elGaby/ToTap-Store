<?php

$files = [
    'resources/views/welcome.blade.php',
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/guest.blade.php'
];

$faviconTag = '<link rel="icon" href="{{ asset(\'images/totap-logo.png\') }}" type="image/png">';

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // Remove existing favicon if any
        $content = preg_replace('/<link rel="icon"[^>]*>/i', '', $content);
        $content = preg_replace('/<link rel="shortcut icon"[^>]*>/i', '', $content);
        
        // Insert new favicon right before </head>
        $content = str_replace('</head>', "    $faviconTag\n</head>", $content);
        
        file_put_contents($file, $content);
    }
}
echo "Favicons updated.\n";
