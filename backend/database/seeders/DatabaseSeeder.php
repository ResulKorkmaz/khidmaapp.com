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
        $this->call([
            // Core data first
            CitiesSeeder::class,
            CategoriesSeeder::class,
            
            // Users and profiles
            UsersSeeder::class,
            
            // Services and related data
            ServicesSeeder::class,
            ServiceBidsSeeder::class,
            ServiceReviewsSeeder::class,
            
            // Admin user (must be last)
            AdminUserSeeder::class,
        ]);
    }
}
