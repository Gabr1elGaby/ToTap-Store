<?php
$file = 'app/Http/Controllers/Admin/GameProductController.php';
$content = file_get_contents($file);

// Expand the filter regex
$oldFilter = "if (preg_match('/\\\\b(global|brazil|br|my|malaysia|ph|philippines|skin|charisma|p\\\\.ace|champion|lightborn|epic)\\\\b/i', \$name)) {";
$newFilter = "if (preg_match('/\\\\b(global|brazil|br|my|malaysia|ph|philippines|skin|charisma|p\\\\.ace|champion|lightborn|epic|usd|eur|hkd)\\\\b/i', \$name) || str_contains(\$name, '$')) {";

$content = str_replace($oldFilter, $newFilter, $content);
file_put_contents($file, $content);
echo "Added USD filter to sync.\n";
