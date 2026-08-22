<?php
$c = file_get_contents('resources/views/welcome.blade.php');
preg_match('/<!-- Auth Modals -->.*?(?=<\/body>)/ms', $c, $m);
if (isset($m[0])) {
    file_put_contents('resources/views/components/auth-modals.blade.php', $m[0]);
    echo "Auth modals extracted.\n";
} else {
    echo "NOT FOUND.\n";
}
