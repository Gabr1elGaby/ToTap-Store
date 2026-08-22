<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = DB::connection('kasir')->table('users')->where('email', 'ameliacs5758@gmail.com')->first();
if ($user) {
    echo "User found: Store ID " . $user->store_id . "\n";
    $sub = DB::connection('kasir')->table('subscriptions')->where('store_id', $user->store_id)->first();
    print_r($sub);
} else {
    echo "User not found in Kasir.\n";
}
