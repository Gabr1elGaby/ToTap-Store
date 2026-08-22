<?php
$file = 'app/Http\Controllers\PlanController.php';
$content = file_get_contents($file);

$content = str_replace(
    "'price' => 'required|numeric|min:0',",
    "'price' => 'required|numeric|min:0',\n            'price_normal' => 'nullable|numeric|min:0',",
    $content
);

file_put_contents($file, $content);
echo "Updated PlanController.\n";
