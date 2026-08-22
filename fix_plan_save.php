<?php
$modelFile = 'app/Models/Plan.php';
$modelContent = file_get_contents($modelFile);

if (strpos($modelContent, "'price_normal'") === false) {
    $modelContent = str_replace(
        "'price',",
        "'price', 'price_normal',",
        $modelContent
    );
    file_put_contents($modelFile, $modelContent);
    echo "Added price_normal to fillable.\n";
}

$controllerFile = 'app/Http/Controllers/PlanController.php';
$controllerContent = file_get_contents($controllerFile);

$controllerContent = str_replace(
    "'duration_days' => 'required|integer|min:1',",
    "'duration_days' => 'required|integer|min:0',",
    $controllerContent
);
file_put_contents($controllerFile, $controllerContent);
echo "Updated duration_days validation to allow 0.\n";
