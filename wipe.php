<?php
DB::statement('SET FOREIGN_KEY_CHECKS=0;');

DB::table('orders')->truncate();
DB::table('payments')->truncate();
DB::table('subscriptions')->truncate();
DB::table('transactions')->truncate(); // Top Up Transactions
if (Schema::hasTable('topup_transactions')) {
    DB::table('topup_transactions')->truncate();
}

if (Schema::hasTable('cvs')) {
    DB::table('cvs')->truncate();
    DB::table('educations')->truncate();
    DB::table('experiences')->truncate();
    DB::table('skills')->truncate();
    DB::table('tools')->truncate();
}

\App\Models\User::where('role', '!=', 'superadmin')->delete();

DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "All dummy data wiped except Super Admin!\n";
