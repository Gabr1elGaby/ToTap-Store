<?php
$c = file_get_contents('resources/views/cv/create.blade.php');
$pos = strpos($c, 'Selanjutnya');
echo substr($c, $pos - 1000, 2000);
