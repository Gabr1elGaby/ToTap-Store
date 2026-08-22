<?php
$file = 'routes/web.php';
$content = file_get_contents($file);

// Find the admin routes group
$replacement = <<<PHP
    Route::resource('plans', App\Http\Controllers\Admin\PlanController::class)->except(['show']);
    Route::resource('cv-templates', App\Http\Controllers\Admin\CvTemplateController::class)->only(['index', 'edit', 'update']);
PHP;

$content = str_replace(
    "Route::resource('plans', App\Http\Controllers\Admin\PlanController::class)->except(['show']);",
    $replacement,
    $content
);

file_put_contents($file, $content);
echo "Added cv-templates routes.\n";
