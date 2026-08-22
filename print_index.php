<?php
$c = file_get_contents('resources/views/cv/index.blade.php');
$s = strpos($c, '@foreach($templates as $template)');
echo substr($c, $s, 1000);
