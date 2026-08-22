<?php
$file = 'app/Http/Controllers/Admin/CvTemplateController.php';
$content = file_get_contents($file);

$content = str_replace(
    "'price' => \$request->price,",
    "'price' => \$request->price,\n            'price_normal' => \$request->price_normal,",
    $content
);

file_put_contents($file, $content);
echo "Updated CvTemplateController to save price_normal.\n";
