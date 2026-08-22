<?php
$file = 'resources/views/layouts/navigation.blade.php';
$content = file_get_contents($file);

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

if (strpos($content, '.nav-item-glow') === false) {
    // Put it right at the top before <nav>
    $content = $styleBlock . "\n" . $content;
    file_put_contents($file, $content);
}
echo "Style block injected directly into navigation layout.\n";
