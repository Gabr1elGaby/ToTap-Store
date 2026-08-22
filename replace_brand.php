<?php
function replaceInFile($filePath, $search, $replace) {
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        if (strpos($content, $search) !== false) {
            $content = str_replace($search, $replace, $content);
            file_put_contents($filePath, $content);
            echo "Replaced in $filePath\n";
        }
    }
}

$files = [
    'resources/views/welcome.blade.php',
    'resources/views/checkout/cv.blade.php',
    'resources/views/components/application-logo.blade.php',
    'resources/views/cv/create.blade.php',
    'resources/views/cv/index.blade.php',
    'resources/views/cv/pending.blade.php',
    'resources/views/products/show.blade.php'
];

foreach ($files as $file) {
    replaceInFile($file, 'Gabriel Systems', 'Wiforge Store');
    replaceInFile($file, 'Gabriel Systems - Enterprise Software Solutions', 'Wiforge Store - Digital & System Hub');
}

// Update .env
$envPath = '.env';
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    $envContent = preg_replace('/APP_NAME=.*/', 'APP_NAME="Wiforge Store"', $envContent);
    file_put_contents($envPath, $envContent);
    echo "Replaced APP_NAME in .env\n";
}

echo "All replacements done!\n";
