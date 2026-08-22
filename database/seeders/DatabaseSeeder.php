<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@gabrielsystems.com',
            'role' => 'superadmin',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Budi Customer',
            'email' => 'budi@example.com',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);
    }
}
