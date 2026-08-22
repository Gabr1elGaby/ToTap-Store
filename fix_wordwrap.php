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
    $content = preg_replace('/body\s*\{/', "body {\n            word-wrap: break-word;\n            word-break: break-word;\n            overflow-wrap: break-word;", $content);
    file_put_contents($file, $content);
}
echo "Done fixing word wrap.\n";
