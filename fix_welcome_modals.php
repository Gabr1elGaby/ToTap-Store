<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$content = preg_replace('/<!-- Auth Modals -->.*?(?=<\/body>)/ms', "<x-auth-modals />\n    ", $content);

file_put_contents($file, $content);
echo "Welcome view updated.\n";
