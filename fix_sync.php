<?php
$file = 'app/Console/Commands/SyncVipProducts.php';
$content = file_get_contents($file);

$content = str_replace("\$item['brand']", "(\$item['game'] ?? 'Unknown Game')", $content);

file_put_contents($file, $content);
echo "Sync command fixed.\n";
