<?php
$file = 'routes/web.php';
$content = file_get_contents($file);

$content = str_replace(
    "Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {",
    "Route::middleware(['auth'])->group(function () {",
    $content
);

file_put_contents($file, $content);
echo "Updated checkout middleware in web.php.\n";
