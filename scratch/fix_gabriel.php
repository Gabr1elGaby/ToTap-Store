<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = DB::table('users')->where('email', 'gabriel.gaby1277@gmail.com')->first();
$sub = DB::table('subscriptions')->where('user_id', $user->id)->first();

if ($user && $sub) {
    $kasirUser = DB::connection('kasir')->table('users')->where('email', $user->email)->first();
    
    if (!$kasirUser || $kasirUser->role !== 'admin') {
        $storeId = DB::connection('kasir')->table('stores')->insertGetId([
            'name' => 'Toko ' . $user->name,
            'slug' => \Illuminate\Support\Str::slug('Toko ' . $user->name . ' ' . rand(1000, 9999)),
            'subscription_ends_at' => $sub->end_date,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (!$kasirUser) {
            DB::connection('kasir')->table('users')->insert([
                'store_id' => $storeId,
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::connection('kasir')->table('users')
                ->where('id', $kasirUser->id)
                ->update([
                    'store_id' => $storeId,
                    'role' => 'admin',
                    'password' => $user->password,
                    'updated_at' => now()
                ]);
        }

        DB::connection('kasir')->table('subscriptions')->insert([
            'store_id' => $storeId,
            'plan_id' => null,
            'start_date' => $sub->start_date,
            'end_date' => $sub->end_date,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "Successfully provisioned gabriel in kasir!";
    } else {
        echo "Already provisioned as admin.";
    }
} else {
    echo "User or sub not found.";
}
