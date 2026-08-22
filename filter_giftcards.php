<?php
$file = 'app/Http/Controllers/Admin/GameProductController.php';
$content = file_get_contents($file);

// Expand the filter regex in GameProductController
$oldFilter = "if (preg_match('/\\\\b(global|brazil|br|my|malaysia|ph|philippines|skin|charisma|p\\\\.ace|champion|lightborn|epic|usd|eur|hkd)\\\\b/i', \$name) || str_contains(\$name, '$')) {";
$newFilter = "if (preg_match('/\\\\b(global|brazil|br|my|malaysia|ph|philippines|skin|charisma|p\\\\.ace|champion|lightborn|epic|usd|eur|hkd|gift card|voucher|riot cash)\\\\b/i', \$name) || str_contains(\$name, '$')) {";

$content = str_replace($oldFilter, $newFilter, $content);
file_put_contents($file, $content);
echo "Added Gift Card and Riot Cash to sync filter.\n";
