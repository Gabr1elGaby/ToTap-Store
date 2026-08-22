<?php
$file = 'resources/views/components/nav-link.blade.php';
$content = file_get_contents($file);

$oldClasses = <<<BLADE
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-blue-500 text-sm font-bold leading-5 text-white focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-semibold leading-5 text-white hover:text-blue-400 focus:outline-none transition duration-150 ease-in-out';
BLADE;

$newClasses = <<<BLADE
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-blue-500 text-sm font-bold leading-5 text-white focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-semibold leading-5 text-white hover:text-blue-400 hover:border-blue-400 focus:outline-none transition duration-150 ease-in-out';
BLADE;

$content = str_replace($oldClasses, $newClasses, $content);
file_put_contents($file, $content);
echo "Added hover border to nav links.\n";
