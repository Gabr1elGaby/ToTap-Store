<?php
$file = 'resources/views/layouts/app.blade.php';
$content = file_get_contents($file);

if (strpos($content, 'Orbitron') === false) {
    $content = str_replace(
        '<link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">',
        "<link href=\"https://fonts.googleapis.com/css2?family=Righteous&display=swap\" rel=\"stylesheet\">\n        <link href=\"https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap\" rel=\"stylesheet\">",
        $content
    );
    file_put_contents($file, $content);
}
echo "Orbitron added to app layout.\n";

$indexFile = 'resources/views/topup/index.blade.php';
$indexContent = file_get_contents($indexFile);
$oldH3 = '<h3 class="font-bold text-white truncate">{{ $game->name }}</h3>';
$newH3 = '<h3 class="font-bold text-white truncate" style="font-family: \'Orbitron\', sans-serif; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">{{ $game->name }}</h3>';
$indexContent = str_replace($oldH3, $newH3, $indexContent);
file_put_contents($indexFile, $indexContent);
echo "Game titles updated to use Orbitron.\n";
