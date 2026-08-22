<?php
$files = [
    'resources/views/welcome.blade.php',
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/guest.blade.php'
];

$fontLink = '<link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">';

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // Add font link if not exists
        if (strpos($content, 'Righteous') === false) {
            $content = str_replace('</title>', "</title>\n        " . $fontLink, $content);
        }
        file_put_contents($file, $content);
    }
}

// Update welcome.blade.php
$welcome = file_get_contents('resources/views/welcome.blade.php');
// Navbar text
$welcome = preg_replace(
    '/<span class="text-xl font-bold text-white tracking-tight uppercase whitespace-nowrap" style="letter-spacing: 1px;">TOTAP STORE<\/span>/i',
    '<span class="-ml-3 text-2xl text-white tracking-widest whitespace-nowrap" style="font-family: \'Righteous\', cursive; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">TOTAP STORE</span>',
    $welcome
);
// Footer text
$welcome = preg_replace(
    '/<span class="text-white font-bold text-lg uppercase tracking-wider">TOTAP STORE<\/span>/i',
    '<span class="-ml-2 text-xl text-white tracking-widest whitespace-nowrap" style="font-family: \'Righteous\', cursive;">TOTAP STORE</span>',
    $welcome
);
file_put_contents('resources/views/welcome.blade.php', $welcome);


// Update application-logo.blade.php
$logo = file_get_contents('resources/views/components/application-logo.blade.php');
$logo = preg_replace(
    '/<span class="text-xl font-bold text-gray-800 dark:text-white tracking-tight uppercase whitespace-nowrap" style="letter-spacing: 1px;">TOTAP STORE<\/span>/i',
    '<span class="-ml-2 text-2xl text-indigo-600 dark:text-indigo-400 tracking-widest whitespace-nowrap" style="font-family: \'Righteous\', cursive;">TOTAP STORE</span>',
    $logo
);
file_put_contents('resources/views/components/application-logo.blade.php', $logo);

echo "Font changed and gap reduced.\n";
