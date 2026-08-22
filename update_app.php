<?php
$file = 'resources/views/layouts/app.blade.php';
$content = file_get_contents($file);

$oldBody = '<body class="font-sans antialiased">';
$newBody = '<body class="font-sans antialiased" x-data="{ showLogin: false, showRegister: false }" @open-login.window="showLogin = true" :class="{ \'overflow-hidden\': showLogin || showRegister }">';
$content = str_replace($oldBody, $newBody, $content);

$oldEndBody = '</body>';
$newEndBody = "    <x-auth-modals />\n</body>";
$content = str_replace($oldEndBody, $newEndBody, $content);

file_put_contents($file, $content);
echo "app.blade.php updated.\n";
