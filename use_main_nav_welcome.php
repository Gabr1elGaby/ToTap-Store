<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);
$content = str_replace("@include('partials.navbar')", "@include('layouts.navigation')", $content);
file_put_contents($file, $content);
echo "Welcome page now uses main navigation.\n";
