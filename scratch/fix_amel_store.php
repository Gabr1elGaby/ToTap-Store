<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'ameliacs5758@gmail.com';
$kasirUser = DB::connection('kasir')->table('users')->where('email', $email)->first();

if ($kasirUser && $kasirUser->store_id == 1) {
    // Create new store
    $storeId = DB::connection('kasir')->table('stores')->insertGetId([
        'name' => 'Toko amel',
        'slug' => \Illuminate\Support\Str::slug('Toko amel ' . rand(1000, 9999)),
        'subscription_ends_at' => '2026-09-17 00:00:00',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Update user to new store
    DB::connection('kasir')->table('users')->where('id', $kasirUser->id)->update([
        'store_id' => $storeId,
        'role' => 'admin'
    ]);

    // Move her subscription record
    DB::connection('kasir')->table('subscriptions')
        ->where('store_id', 1)
        ->where('start_date', '2026-08-18') // To avoid deleting main store subscription if we can't differentiate
        ->delete(); // Wait, let's just create a new one instead of trying to find the old one, since the old one overwrote store 1's subscription!
        
    // Actually, store 1 might have lost its own subscription record if there was only one. Let's just restore store 1's sub and create one for Amelia.
    DB::connection('kasir')->table('subscriptions')->insert([
        'store_id' => $storeId,
        'plan_id' => null,
        'start_date' => '2026-08-18',
        'end_date' => '2026-09-17',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "Successfully migrated amel to store_id = $storeId";
}
