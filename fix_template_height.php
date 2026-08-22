<?php
$files = [
    'resources/views/cv/templates/creative.blade.php',
    'resources/views/cv/templates/fresh-graduate.blade.php',
    'resources/views/cv/templates/job-application.blade.php',
    'resources/views/cv/templates/student.blade.php',
    'resources/views/cv/templates/ats.blade.php'
];

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Check if height: 100% is missing
    $content = str_replace(
        '.main-table { border-collapse: collapse; table-layout: fixed; width: 100%; }',
        '.main-table { border-collapse: collapse; table-layout: fixed; width: 100%; height: 100%; min-height: 1123px; }',
        $content
    );
    
    // Add height 100% to body and html so it expands properly in standard HTML
    $content = str_replace(
        'body {',
        "html, body { height: 100%; margin: 0; padding: 0; }\n        body {",
        $content
    );
    
    file_put_contents($file, $content);
}
echo "Done adding height to tables.\n";
