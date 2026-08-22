<?php
$files = [
    'resources/views/welcome.blade.php',
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/navigation.blade.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // Update all possible old logo references to the new v2 logo
        $content = preg_replace('/images\/logo-totap\.png/i', 'images/logo-totap-v2.png', $content);
        $content = preg_replace('/images\/totap-logo\.png/i', 'images/logo-totap-v2.png', $content);
        $content = preg_replace('/images\/totap-logo-circle\.png/i', 'images/logo-totap-v2.png', $content);
        
        file_put_contents($file, $content);
    }
}
echo "Logo updated to v2 across all files, including favicons.\n";
