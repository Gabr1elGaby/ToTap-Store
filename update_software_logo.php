<?php
$welcomeFile = 'resources/views/welcome.blade.php';
$content = file_get_contents($welcomeFile);

// Replace totap-logo-circle.png with software-logo.png inside the software category card
$content = str_replace(
    '<img src="{{ asset(\'images/totap-logo-circle.png\') }}" alt="Software"',
    '<img src="{{ asset(\'images/software-logo.png\') }}" alt="Software"',
    $content
);

file_put_contents($welcomeFile, $content);
echo "Updated software category logo on homepage.\n";
