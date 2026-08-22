<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan Super Admin selalu ada di Database Railway / Production
        $admin = User::firstOrNew(['email' => 'admin@gabrielsystems.com']);
        $admin->name = 'Super Admin';
        $admin->role = 'superadmin';
        if (!$admin->exists || empty($admin->password)) {
            $admin->password = Hash::make('password');
        }
        if (empty($admin->phone_number)) {
            $admin->phone_number = '081234567890';
        }
        $admin->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
