<?php
$c = file_get_contents('resources/views/cv/create.blade.php');
echo substr($c, strpos($c, '<!-- Close Button -->'), 500);
