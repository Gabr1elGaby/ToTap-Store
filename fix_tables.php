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
    // Remove existing table rules
    $content = preg_replace('/table\s*\{[^}]+\}/', "table { border-collapse: collapse; table-layout: fixed; width: 100%; }\n        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }", $content);
    file_put_contents($file, $content);
}
echo "Done fixing tables.\n";
