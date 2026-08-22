<?php
$file = 'app/Http/Controllers/CvController.php';
$content = file_get_contents($file);

$content = str_replace(
    '\App\Models\Template::where(\'slug\', $slug)->firstOrFail();',
    '$template = DB::table(\'cv_templates\')->where(\'slug\', $slug)->first(); if (!$template) abort(404);',
    $content
);
$content = str_replace(
    'Template::where(\'slug\', $slug)->firstOrFail();',
    '$template = DB::table(\'cv_templates\')->where(\'slug\', $slug)->first(); if (!$template) abort(404);',
    $content
);

// I should also check the `preview` method!
$content = str_replace(
    'Template::where(\'slug\', $slug)->firstOrFail();',
    '$template = DB::table(\'cv_templates\')->where(\'slug\', $slug)->first(); if (!$template) abort(404);',
    $content
);
// I might have written `Template::` in the original `preview` method!
file_put_contents($file, $content);
echo "Fixed DB table query.\n";
