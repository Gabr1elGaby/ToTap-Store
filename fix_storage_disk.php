<?php
$file = 'app/Http/Controllers/Admin/GameController.php';
$content = file_get_contents($file);
$content = str_replace("store('public/games')", "store('games', 'public')", $content);
file_put_contents($file, $content);
echo "GameController updated to use public disk.\n";
