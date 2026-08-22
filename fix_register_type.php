<?php
$file = 'app/Http/Controllers/Auth/RegisteredUserController.php';
$content = file_get_contents($file);

$oldFunc = 'public function store(Request $request): RedirectResponse';
$newFunc = 'public function store(Request $request)';

$content = str_replace($oldFunc, $newFunc, $content);
file_put_contents($file, $content);
echo "RegisteredUserController updated.\n";
