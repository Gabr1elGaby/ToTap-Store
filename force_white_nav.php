<?php
$file = 'resources/views/components/nav-link.blade.php';
$content = file_get_contents($file);

$oldClasses = <<<BLADE
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-blue-400 dark:border-blue-500 text-sm font-bold leading-5 text-gray-900 dark:text-white focus:outline-none focus:border-blue-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-semibold leading-5 text-gray-600 dark:text-white hover:text-gray-900 dark:hover:text-blue-400 hover:border-gray-300 dark:hover:border-blue-500 focus:outline-none focus:text-gray-900 dark:focus:text-white focus:border-gray-300 transition duration-150 ease-in-out';
BLADE;

$newClasses = <<<BLADE
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-blue-500 text-sm font-bold leading-5 text-white focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-semibold leading-5 text-white hover:text-blue-400 focus:outline-none transition duration-150 ease-in-out';
BLADE;

$content = str_replace($oldClasses, $newClasses, $content);
file_put_contents($file, $content);
echo "Nav link made unconditionally white.\n";

$navFile = 'resources/views/layouts/navigation.blade.php';
$navContent = file_get_contents($navFile);
// Force Amelia Caroline text to be white
$navContent = str_replace('text-gray-500 dark:text-white bg-gray-800', 'text-white bg-gray-800', $navContent);
// Force guest links to be white
$navContent = preg_replace('/class="font-semibold text-gray-500 hover:text-gray-900 dark:text-white dark:hover:text-blue-400 transition"/', 'class="font-semibold text-white hover:text-blue-400 transition"', $navContent);

file_put_contents($navFile, $navContent);
echo "Navigation bar items made unconditionally white.\n";
