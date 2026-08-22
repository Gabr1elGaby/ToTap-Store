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
    
    // Remove language extraction at top
    $content = preg_replace('/\$languages =.*?;/s', '', $content);
    
    // Remove language section rendering. It looks like:
    // @if(count($languages) > 0) ... @endif
    $content = preg_replace('/@if\(count\(\$languages\) > 0\).*?@endif/s', '', $content);
    
    file_put_contents($file, $content);
}
echo "Done removing languages from templates.\n";
