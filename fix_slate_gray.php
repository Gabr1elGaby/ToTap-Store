<?php
$files = [
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/navigation.blade.php',
    'resources/views/topup/index.blade.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace('slate-900', 'gray-900', $content);
        $content = str_replace('slate-800', 'gray-800', $content);
        $content = str_replace('slate-700', 'gray-700', $content);
        $content = str_replace('slate-400', 'gray-400', $content);
        
        // Fix inline style in app.blade.php
        $content = str_replace('background-color: #0F172A;', 'background-color: #111827;', $content);
        
        file_put_contents($file, $content);
    }
}
echo "Replaced slate with gray to ensure standard tailwind classes are used.\n";
