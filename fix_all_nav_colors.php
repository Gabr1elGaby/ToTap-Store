<?php
$file = 'resources/views/components/responsive-nav-link.blade.php';
$content = file_get_contents($file);

$oldClasses = <<<BLADE
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-400 dark:border-indigo-600 text-start text-base font-medium text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/50 focus:outline-none focus:text-indigo-800 dark:focus:text-indigo-200 focus:bg-indigo-100 dark:focus:bg-indigo-900 focus:border-indigo-700 dark:focus:border-indigo-300 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-50 dark:focus:bg-gray-700 focus:border-gray-300 dark:focus:border-gray-600 transition duration-150 ease-in-out';
BLADE;

$newClasses = <<<BLADE
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-blue-400 dark:border-blue-500 text-start text-base font-bold text-gray-900 dark:text-white bg-blue-50 dark:bg-blue-900/50 focus:outline-none transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-semibold text-gray-600 dark:text-white hover:text-gray-900 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none transition duration-150 ease-in-out';
BLADE;

$content = str_replace($oldClasses, $newClasses, $content);
file_put_contents($file, $content);
echo "Responsive nav links made white.\n";

$dropdownFile = 'resources/views/components/dropdown-link.blade.php';
if (file_exists($dropdownFile)) {
    $dContent = file_get_contents($dropdownFile);
    $dContent = str_replace('text-gray-700 dark:text-gray-300', 'text-gray-900 dark:text-white', $dContent);
    $dContent = str_replace('hover:bg-gray-100 dark:hover:bg-gray-800', 'hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-400', $dContent);
    file_put_contents($dropdownFile, $dContent);
    echo "Dropdown links made white.\n";
}

$navFile = 'resources/views/layouts/navigation.blade.php';
$navContent = file_get_contents($navFile);
$navContent = str_replace('text-gray-800 dark:text-gray-200', 'text-white', $navContent);
$navContent = str_replace('text-gray-500', 'text-gray-300', $navContent); // for emails
file_put_contents($navFile, $navContent);
echo "Mobile user info made white.\n";

