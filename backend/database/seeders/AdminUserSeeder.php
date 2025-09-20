<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Check if admin user already exists
        $adminUser = User::where('email', 'admin@khidmaapp.com')->first();
        
        if (!$adminUser) {
            $adminUser = User::create([
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
        } else {
            // Update existing admin user to ensure it has correct values
            $adminUser->update([
                'name' => 'Admin User',
                'role' => 'admin',
                'is_active' => true,
                'is_verified' => true,
                'email_verified_at' => $adminUser->email_verified_at ?? now(),
                'phone_verified_at' => $adminUser->phone_verified_at ?? now(),
            ]);
            $this->command->info('Admin user already exists - updated with correct values!');
        }
        
        // Ensure admin user has admin role in spatie/permission
        if (!\Spatie\Permission\Models\Role::where('name', 'admin')->exists()) {
            \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        }
        
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
            $this->command->info('Admin role assigned to admin user!');
        }
    }
}

