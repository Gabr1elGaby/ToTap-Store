<?php
$file = 'resources/views/layouts/navigation.blade.php';
$content = file_get_contents($file);

$oldGuestLinks = <<<BLADE
                <div class="space-x-4">
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Login</a>
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Register</a>
                </div>
BLADE;

$newGuestLinks = <<<BLADE
                <div class="space-x-4">
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="font-semibold text-gray-500 hover:text-gray-900 dark:text-white dark:hover:text-blue-400 transition">Login</a>
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-register'))" class="font-semibold text-gray-500 hover:text-gray-900 dark:text-white dark:hover:text-blue-400 transition">Register</a>
                </div>
BLADE;

$content = str_replace($oldGuestLinks, $newGuestLinks, $content);

// Also check the user dropdown trigger just in case
$oldDropdownTrigger = <<<BLADE
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
BLADE;

$newDropdownTrigger = <<<BLADE
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-semibold rounded-md text-gray-500 dark:text-white bg-gray-800 hover:text-gray-700 dark:hover:text-blue-400 focus:outline-none transition ease-in-out duration-150">
BLADE;

$content = str_replace($oldDropdownTrigger, $newDropdownTrigger, $content);

file_put_contents($file, $content);
echo "Guest links and dropdown trigger made white.\n";
