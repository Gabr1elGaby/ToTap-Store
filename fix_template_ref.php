<?php
$file = 'app/Http/Controllers/CvController.php';
$content = file_get_contents($file);

$content = str_replace(
    'Template::where(\'slug\', $slug)->firstOrFail();',
    '\App\Models\Template::where(\'slug\', $slug)->firstOrFail();',
    $content
);

file_put_contents($file, $content);
echo "Fixed Template class reference.\n";
