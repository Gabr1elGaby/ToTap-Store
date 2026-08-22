<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$oldBody = '<body class="antialiased text-gray-900 bg-gray-50" x-data="{ showLogin: false, showRegister: false }" :class="{ \'overflow-hidden\': showLogin || showRegister }">';
$newBody = '<body class="antialiased text-gray-900 bg-gray-50" x-data="{ showLogin: false, showRegister: false }" @open-login.window="showLogin = true" :class="{ \'overflow-hidden\': showLogin || showRegister }">';

$content = str_replace($oldBody, $newBody, $content);
file_put_contents($file, $content);
echo "Welcome body fixed.\n";
