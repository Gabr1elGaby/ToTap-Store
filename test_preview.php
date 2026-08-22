<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$req = Illuminate\Http\Request::create('/cv/preview/ats', 'POST', ['cv' => ['name' => 'Test']]);
echo $app->handle($req)->getContent();
