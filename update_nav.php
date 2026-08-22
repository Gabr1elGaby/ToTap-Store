<?php
$file = 'resources/views/layouts/navigation.blade.php';
$content = file_get_contents($file);

$oldCode1 = '<a href="{{ route(\'login\') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Login</a>';
$newCode1 = '<a href="#" @click.prevent="window.dispatchEvent(new CustomEvent(\'open-login\'))" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Login</a>';

$oldCode2 = '<a href="{{ route(\'register\') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Register</a>';
$newCode2 = '<a href="#" @click.prevent="window.dispatchEvent(new CustomEvent(\'open-login\'))" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Register</a>';

$oldCode3 = '<x-responsive-nav-link :href="route(\'login\')">Login</x-responsive-nav-link>';
$newCode3 = '<x-responsive-nav-link href="#" @click.prevent="window.dispatchEvent(new CustomEvent(\'open-login\'))">Login</x-responsive-nav-link>';

$oldCode4 = '<x-responsive-nav-link :href="route(\'register\')">Register</x-responsive-nav-link>';
$newCode4 = '<x-responsive-nav-link href="#" @click.prevent="window.dispatchEvent(new CustomEvent(\'open-login\'))">Register</x-responsive-nav-link>';

$content = str_replace($oldCode1, $newCode1, $content);
$content = str_replace($oldCode2, $newCode2, $content);
$content = str_replace($oldCode3, $newCode3, $content);
$content = str_replace($oldCode4, $newCode4, $content);

file_put_contents($file, $content);
echo "Navigation updated.\n";
