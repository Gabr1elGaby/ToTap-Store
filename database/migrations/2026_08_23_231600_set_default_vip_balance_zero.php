<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'vip_reseller_balance'],
            ['value' => '0', 'updated_at' => now()]
        );
    }

    public function down(): void
    {
    }
};
