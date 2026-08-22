<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = DB::connection('kasir')->table('users')->where('email', 'ameliacs5758@gmail.com')->first();
print_r($user);
