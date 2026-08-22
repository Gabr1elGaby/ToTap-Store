<?php
$file = 'app/Http/Controllers/Admin/GameProductController.php';
$content = file_get_contents($file);

$oldFilter = <<<PHP
            // 3. FILTER NON-IDN
            if (preg_match('/\\b(global|brazil|br|my|malaysia|ph|philippines|skin|charisma|p\\.ace|champion|lightborn|epic)\\b/i', \$name)) {
                continue;
            }
PHP;

$newFilter = <<<PHP
            // 3. FILTER NON-IDN DAN VOUCHER
            if (preg_match('/\\b(global|brazil|br|my|malaysia|ph|philippines|skin|charisma|p\\.ace|champion|lightborn|epic|voucher|gift card|eur|usd|hkd|riot cash|card)\\b/i', \$name)) {
                continue;
            }
PHP;

$content = str_replace($oldFilter, $newFilter, $content);
file_put_contents($file, $content);
echo "Filter updated.\n";
