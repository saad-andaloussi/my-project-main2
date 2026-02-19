<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $userRole = Role::where('name', 'user')->first();
        $guestRole = Role::where('name', 'guest')->first();

        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@datacenter.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
                'is_active' => true,
                'department' => 'IT',
            ]
        );

        // Create manager users
        User::firstOrCreate(
            ['email' => 'manager@datacenter.com'],
            [
                'name' => 'Manager Technical',
                'password' => bcrypt('password'),
                'role_id' => $managerRole->id,
                'email_verified_at' => now(),
                'is_active' => true,
                'department' => 'Operations',
            ]
        );

        // Create regular users
        User::factory(5)->create([
            'role_id' => $userRole->id,
            'is_active' => true,
        ]);

        // Create a guest user
        User::firstOrCreate(
            ['email' => 'guest@datacenter.com'],
            [
                'name' => 'Guest User',
                'password' => bcrypt('password'),
                'role_id' => $guestRole->id,
                'email_verified_at' => now(),
                'is_active' => true,
                'department' => 'External',
            ]
        );
    }
}
