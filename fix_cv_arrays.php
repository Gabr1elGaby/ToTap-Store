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
    
    $content = preg_replace_callback('/\$([a-z]+)\s*=\s*isset\(\$userData\[\'([a-z]+)\'\]\).+?\;/s', function($m) {
        $varName = $m[1];
        if ($varName === 'data') return $m[0]; // skip data
        return "\$$varName = isset(\$userData['cv']['$varName']) && is_array(\$userData['cv']['$varName']) ? collect(\$userData['cv']['$varName'])->map(fn(\$i) => (object)\$i) : [];";
    }, $content);
    
    // Also handle tools if present
    $content = preg_replace_callback('/\$tools\s*=\s*isset\(\$userData\[\'tools\'\]\).+?\;/s', function($m) {
        return "\$tools = isset(\$userData['cv']['tools']) && is_array(\$userData['cv']['tools']) ? collect(\$userData['cv']['tools'])->map(fn(\$i) => (object)\$i) : [];";
    }, $content);
    
    file_put_contents($file, $content);
}
echo "Done fixing arrays.\n";
