<?php
$file = 'app/Http/Controllers/CvController.php';
$content = file_get_contents($file);

$content = str_replace(
    "'end_date' => 'Sekarang',",
    "'end_date' => 'Sekarang', 'is_current' => true,",
    $content
);
$content = str_replace(
    "'end_date' => 'Des 2022',",
    "'end_date' => 'Des 2022', 'is_current' => false,",
    $content
);
$content = str_replace(
    "'end_date' => 'Agu 2021',",
    "'end_date' => 'Agu 2021', 'is_current' => false,",
    $content
);

file_put_contents($file, $content);
echo "Added is_current to mock data.\n";
