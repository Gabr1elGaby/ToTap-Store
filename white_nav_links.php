<?php
$file = 'resources/views/components/nav-link.blade.php';
$content = file_get_contents($file);

$oldClasses = <<<BLADE
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 dark:border-indigo-600 text-sm font-medium leading-5 text-gray-900 dark:text-gray-100 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out';
BLADE;

$newClasses = <<<BLADE
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-blue-400 dark:border-blue-500 text-sm font-bold leading-5 text-gray-900 dark:text-white focus:outline-none focus:border-blue-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-semibold leading-5 text-gray-600 dark:text-white hover:text-gray-900 dark:hover:text-blue-400 hover:border-gray-300 dark:hover:border-blue-500 focus:outline-none focus:text-gray-900 dark:focus:text-white focus:border-gray-300 transition duration-150 ease-in-out';
BLADE;

$content = str_replace($oldClasses, $newClasses, $content);
file_put_contents($file, $content);
echo "Nav link colors updated to white.\n";
