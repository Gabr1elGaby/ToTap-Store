<?php
$welcomeFile = 'resources/views/welcome.blade.php';
$content = file_get_contents($welcomeFile);

// 1. Update the style block
$oldStyle = <<<CSS
    .nav-item-glow:hover {
        color: #60A5FA !important;
        border-bottom-color: #60A5FA !important;
    }
</style>
CSS;

$newStyle = <<<CSS
    .nav-item-glow:hover {
        color: #60A5FA !important;
        border-bottom-color: #60A5FA !important;
    }
    .category-card-glow {
        transition: all 0.3s ease;
    }
    .category-card-glow:hover {
        border-color: #3B82F6 !important;
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.3) !important;
    }
</style>
CSS;

$content = str_replace($oldStyle, $newStyle, $content);

// 2. Add class to the cards
$content = str_replace('group border border-gray-700"', 'group border border-gray-700 category-card-glow"', $content);

file_put_contents($welcomeFile, $content);
echo "Category cards hover outline added.\n";
