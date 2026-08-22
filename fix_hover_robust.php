<?php
$files = [
    'resources/views/welcome.blade.php',
    'resources/views/layouts/app.blade.php'
];

$styleBlock = <<<CSS
    <style>
        .nav-item-glow {
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .nav-item-glow:hover {
            color: #60A5FA !important;
            border-bottom-color: #60A5FA !important;
        }
    </style>
CSS;

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, '.nav-item-glow') === false) {
            $content = str_replace('</head>', $styleBlock . "\n</head>", $content);
            file_put_contents($file, $content);
        }
    }
}
echo "Style block injected.\n";

$navLinkFile = 'resources/views/components/nav-link.blade.php';
$navLinkContent = file_get_contents($navLinkFile);
$navLinkContent = str_replace('text-white hover:text-blue-400 hover:border-blue-400', 'text-white nav-item-glow', $navLinkContent);
$navLinkContent = str_replace('text-white hover:text-blue-400', 'text-white nav-item-glow', $navLinkContent); // fallback
file_put_contents($navLinkFile, $navLinkContent);

$navFile = 'resources/views/layouts/navigation.blade.php';
$navContent = file_get_contents($navFile);
$navContent = str_replace('hover:text-blue-400 border-b-2 border-transparent hover:border-blue-400 transition', 'nav-item-glow', $navContent);
$navContent = str_replace('hover:text-blue-400 transition', 'nav-item-glow', $navContent);
file_put_contents($navFile, $navContent);

echo "Classes updated to use custom robust CSS.\n";
