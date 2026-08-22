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
    // Give item-title td 75% and item-date td 25%
    $content = preg_replace('/<td class="item-title">/', '<td class="item-title" style="width: 75%;">', $content);
    $content = preg_replace('/<td class="item-date">/', '<td class="item-date" style="width: 25%;">', $content);
    file_put_contents($file, $content);
}
echo "Done fixing td widths.\n";
