<?php
$welcomeFile = 'resources/views/welcome.blade.php';
$content = file_get_contents($welcomeFile);

$glowCSS = <<<CSS
    .category-card-glow {
        transition: all 0.3s ease;
    }
    .category-card-glow:hover {
        border-color: #3B82F6 !important;
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.4) !important;
    }
CSS;

if (strpos($content, '.category-card-glow {') === false) {
    // Inject it just before </head>
    $content = str_replace('</head>', "<style>\n" . $glowCSS . "\n</style>\n</head>", $content);
    file_put_contents($welcomeFile, $content);
    echo "Injected category-card-glow CSS.\n";
} else {
    echo "CSS already exists.\n";
}
