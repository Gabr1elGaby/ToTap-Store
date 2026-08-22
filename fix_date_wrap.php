<?php
$files = [
    'resources/views/cv/templates/ats.blade.php',
    'resources/views/cv/templates/creative.blade.php',
    'resources/views/cv/templates/fresh-graduate.blade.php',
    'resources/views/cv/templates/job-application.blade.php',
    'resources/views/cv/templates/student.blade.php'
];

foreach ($files as $file) {
    $content = file_get_contents($file);
    $content = preg_replace('/\.item-date\s*\{([^\}]+)\}/', ".item-date {\$1 white-space: nowrap; }", $content);
    file_put_contents($file, $content);
}
echo "Done fixing date wrap.\n";
