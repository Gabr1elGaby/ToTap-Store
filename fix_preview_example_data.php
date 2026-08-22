<?php
$file = 'app/Http/Controllers/CvController.php';
$content = file_get_contents($file);

$regex = '/return view\(\'cv\.templates\.\' \. \$template->slug, compact\(\'userData\', \'template\'\)\);/';
$replacement = <<<PHP
\$data = isset(\$userData['cv']) ? (object)\$userData['cv'] : (object)[];
        return view('cv.templates.' . \$template->slug, compact('userData', 'template', 'data'));
PHP;

$content = preg_replace($regex, $replacement, $content);

file_put_contents($file, $content);
echo "Added \$data to previewExample.\n";
