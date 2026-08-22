<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// We need to know which email was used for this order.
$order = DB::table('orders')->where('order_number', 'ORD-20260819-EHXSKF')->first();
if ($order) {
    $user = DB::table('users')->where('id', $order->user_id)->first();
    echo "Email used: " . $user->email . "\n";
    
    $kasirUser = DB::connection('kasir')->table('users')->where('email', $user->email)->first();
    if ($kasirUser) {
        echo "Found in Kasir DB: \n";
        print_r($kasirUser);
    } else {
        echo "NOT FOUND in Kasir DB.\n";
    }
} else {
    echo "Order not found.\n";
}
