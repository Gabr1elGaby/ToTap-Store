<?php
$file = 'resources/views/products/show.blade.php';
$content = file_get_contents($file);

$content = preg_replace('/<!-- Auth Modals -->.*?(?=<\/body>)/ms', "<x-auth-modals />\n    ", $content);

file_put_contents($file, $content);
echo "Products show view updated.\n";
