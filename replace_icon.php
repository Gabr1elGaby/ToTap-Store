<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$oldIcon = <<<BLADE
                            <!-- Gamepad Icon -->
                            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
BLADE;

$newIcon = <<<BLADE
                            <img src="{{ asset('images/kategori-game.png') }}" alt="Top Up Game" class="w-16 h-16 object-contain">
BLADE;

$content = str_replace($oldIcon, $newIcon, $content);
file_put_contents($file, $content);
echo "Icon replaced.\n";
