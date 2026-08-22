<?php
$file = 'resources/views/software/index.blade.php';
$content = file_get_contents($file);

$oldCode = <<<BLADE
    @if (Route::has('login'))
        @auth
            <!-- Logged in, no modal needed -->
        @else
            @include('auth.login-modal')
            @include('auth.register-modal')
        @endauth
    @endif
BLADE;

$newCode = "    <x-auth-modals />";

$content = str_replace($oldCode, $newCode, $content);
file_put_contents($file, $content);
echo "Fixed missing auth-modal includes in software page.\n";
