<?php
$c = file_get_contents('resources/views/welcome.blade.php');
file_put_contents('welcome_dump.txt', $c);
