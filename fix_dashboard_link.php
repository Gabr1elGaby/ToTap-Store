<?php
$welcomeFile = 'resources/views/welcome.blade.php';
$content = file_get_contents($welcomeFile);

// Change /dashboard to /profile and "Dashboard Saya" to "Profil Saya"
$content = preg_replace(
    '/<a href="\/dashboard" class="(.*?)">\s*Dashboard Saya\s*<\/a>/s',
    '<a href="/profile" class="$1">' . "\n" . '                                Profil Saya' . "\n" . '                            </a>',
    $content
);

file_put_contents($welcomeFile, $content);
echo "Updated logged-in button to point to /profile.\n";
