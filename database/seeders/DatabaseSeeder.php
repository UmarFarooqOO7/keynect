<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // run ShieldSeeder
        $this->call(ShieldSeeder::class);

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@demo.com',
            'email_verified_at' => now(), // Mark as verified
        ]);
        $user->assignRole('user');

        // create super admin and assign all permissions
        $user = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@demo.com',
            'email_verified_at' => now(), // Mark as verified
        ]);
        $user->assignRole('super_admin');
    }
}
