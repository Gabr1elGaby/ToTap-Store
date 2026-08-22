<?php
$file = 'resources/views/products/show.blade.php';
$content = file_get_contents($file);

// Inject fonts if not present
if (strpos($content, 'Orbitron') === false) {
    $fonts = <<<HTML
        <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
HTML;
    $content = str_replace('</head>', $fonts . "\n    </head>", $content);
}

// 1. Main Title
$content = preg_replace(
    '/<h1 class="text-4xl font-extrabold text-white mb-4">/', 
    '<h1 class="text-4xl font-extrabold text-white mb-4" style="font-family: \'Orbitron\', sans-serif; letter-spacing: 1px; text-transform: uppercase;">', 
    $content
);

// 2. Pilih Paket Anda
$content = preg_replace(
    '/<h2 class="text-3xl font-bold text-center text-white mb-12">/', 
    '<h2 class="text-3xl font-bold text-center text-white mb-12" style="font-family: \'Orbitron\', sans-serif; letter-spacing: 1px; text-transform: uppercase;">', 
    $content
);

// 3. Package Names (BASIC, PRO)
$content = preg_replace(
    '/<h3 class="text-2xl font-bold text-white mb-4 text-center">/', 
    '<h3 class="text-2xl font-bold text-white mb-4 text-center" style="font-family: \'Orbitron\', sans-serif; letter-spacing: 2px;">', 
    $content
);

// 4. Price styling (make it pop a bit more with blue text for the Rp part)
$content = preg_replace(
    '/<span class="text-4xl font-bold text-white">Rp/',
    '<span class="text-4xl font-bold text-blue-400">Rp</span><span class="text-4xl font-bold text-white">',
    $content
);

// 5. Button
$content = preg_replace(
    '/<button(.*?)class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded hover:bg-indigo-700 transition"/',
    '<button$1class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded hover:bg-blue-500 transition shadow-lg shadow-blue-500/30" style="font-family: \'Orbitron\', sans-serif; text-transform: uppercase; font-size: 14px;"',
    $content
);

file_put_contents($file, $content);
echo "POS page fonts updated.\n";
