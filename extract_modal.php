<?php
$c = file_get_contents('resources/views/welcome.blade.php');
preg_match('/<!-- Login Modal -->.*?(?=<!-- Register Modal -->)/ms', $c, $m);
file_put_contents('modal.txt', $m[0] ?? 'NOT_FOUND');
