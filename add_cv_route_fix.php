<?php
$file = 'routes/web.php';
$content = file_get_contents($file);

$content = str_replace(
    "Route::resource('plans', \App\Http\Controllers\PlanController::class);",
    "Route::resource('plans', \App\Http\Controllers\PlanController::class);\n    Route::resource('cv-templates', \App\Http\Controllers\Admin\CvTemplateController::class)->only(['index', 'edit', 'update']);",
    $content
);

file_put_contents($file, $content);
echo "Added cv-templates routes successfully.\n";
