<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles first (other seeders depend on this)
        $this->call(RoleSeeder::class);
        
        // Seed users
        $this->call(UserSeeder::class);
        
        // Seed resource categories
        $this->call(ResourceCategorySeeder::class);
        
        // Seed resources
        $this->call(ResourceSeeder::class);
    }
}
