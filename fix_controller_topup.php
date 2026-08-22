<?php
$file = 'app/Http/Controllers/TopUpController.php';
$content = file_get_contents($file);

$oldFunc = "public function show(\$slug)";
$newFunc = "public function index()\n    {\n        return view('topup.index');\n    }\n\n    public function show(\$slug)";

$content = str_replace($oldFunc, $newFunc, $content);
file_put_contents($file, $content);
echo "Controller updated.\n";
