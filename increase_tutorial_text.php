<?php
$welcomeFile = 'resources/views/welcome.blade.php';
$content = file_get_contents($welcomeFile);

// Change title size from text-lg to text-xl
$content = str_replace(
    'class="text-lg font-bold text-white mb-2"',
    'class="text-xl font-bold text-white mb-3"',
    $content
);

// Specifically target the tutorial section descriptions to increase size from text-sm to text-base
// I'll just replace all 'text-gray-400 text-sm' with 'text-gray-400 text-base' within the tutorial section bounds
$tutorialStart = strpos($content, '<section id="tutorial"');
$tutorialEnd = strpos($content, '</section>', $tutorialStart);

if ($tutorialStart !== false && $tutorialEnd !== false) {
    $tutorialSection = substr($content, $tutorialStart, $tutorialEnd - $tutorialStart);
    
    // Increase description font size
    $tutorialSection = str_replace(
        'class="text-gray-400 text-sm leading-relaxed"',
        'class="text-gray-400 text-base leading-relaxed"',
        $tutorialSection
    );
    
    $content = substr_replace($content, $tutorialSection, $tutorialStart, $tutorialEnd - $tutorialStart);
    file_put_contents($welcomeFile, $content);
    echo "Text size increased in tutorial section.\n";
} else {
    echo "Could not find tutorial section.\n";
}
