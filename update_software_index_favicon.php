<?php
$file = 'resources/views/software/index.blade.php';
$content = file_get_contents($file);

$headOld = '<title>Software Enterprise - ToTap Store</title>';
$headNew = "<title>Software Enterprise - ToTap Store</title>\n    <link rel=\"icon\" href=\"{{ asset('images/logo-totap-v2.png') }}\" type=\"image/png\">";
$content = str_replace($headOld, $headNew, $content);

file_put_contents($file, $content);
echo "Updated software/index.blade.php with favicon.\n";
