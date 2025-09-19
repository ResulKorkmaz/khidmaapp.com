<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'id' => Str::uuid(),
            'name' => 'Admin User',
            'email' => 'admin@khidmaapp.com',
            'phone' => '+966500000000',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'locale' => 'ar',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
        ]);

        $this->command->info('Admin user created successfully!');
    }
}

