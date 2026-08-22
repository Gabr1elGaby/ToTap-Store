<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);
$content = str_replace('Top Up {{ $game->name', 'Top Up {{ $game->name }}', $content);
file_put_contents($file, $content);
echo "Syntax fixed.\n";
