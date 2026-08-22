<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$gabrielUser = DB::table('users')->where('email', 'ameliacs5758@gmail.com')->first();
if ($gabrielUser) {
    DB::connection('kasir')->table('users')
        ->where('email', 'ameliacs5758@gmail.com')
        ->update(['password' => $gabrielUser->password, 'role' => 'admin']);
    echo "Password and role synced successfully.";
} else {
    echo "User not found in Gabriel Systems.";
}
