<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$pattern = '/<!-- TOP UP GAME HUB CARD -->.*?<\/div>\s*<\/div>\s*<\/div>/s';
$content = preg_replace($pattern, '</div></div></div>', $content);

file_put_contents($file, $content);
echo "Old Gaming Center card removed.\n";
