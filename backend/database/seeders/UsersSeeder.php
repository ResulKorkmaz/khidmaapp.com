<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample providers
        $providers = [
            [
                'name' => 'أحمد محمد',
                'email' => 'ahmed@example.com',
                'phone' => '+966501234567',
                'role' => 'provider',
                'rating_avg' => 4.8,
                'rating_count' => 124,
                'jobs_completed' => 98,
                'is_verified' => true,
            ],
            [
                'name' => 'فاطمة علي',
                'email' => 'fatima@example.com',
                'phone' => '+966502345678',
                'role' => 'provider',
                'rating_avg' => 4.9,
                'rating_count' => 87,
                'jobs_completed' => 76,
                'is_verified' => true,
            ],
            [
                'name' => 'محمد السعيد',
                'email' => 'mohammed@example.com',
                'phone' => '+966503456789',
                'role' => 'provider',
                'rating_avg' => 4.7,
                'rating_count' => 156,
                'jobs_completed' => 142,
                'is_verified' => true,
            ],
            [
                'name' => 'نورا أحمد',
                'email' => 'nora@example.com',
                'phone' => '+966504567890',
                'role' => 'provider',
                'rating_avg' => 4.6,
                'rating_count' => 98,
                'jobs_completed' => 89,
                'is_verified' => true,
            ],
            [
                'name' => 'خالد المطيري',
                'email' => 'khalid@example.com',
                'phone' => '+966505678901',
                'role' => 'provider',
                'rating_avg' => 4.8,
                'rating_count' => 203,
                'jobs_completed' => 187,
                'is_verified' => true,
            ],
        ];

        // Create sample customers
        $customers = [
            [
                'name' => 'سارة الأحمد',
                'email' => 'sara@example.com',
                'phone' => '+966506789012',
                'role' => 'customer',
            ],
            [
                'name' => 'عبدالله الحارثي',
                'email' => 'abdullah@example.com',
                'phone' => '+966507890123',
                'role' => 'customer',
            ],
            [
                'name' => 'ريم القحطاني',
                'email' => 'reem@example.com',
                'phone' => '+966508901234',
                'role' => 'customer',
            ],
        ];

        foreach ($providers as $providerData) {
            User::create([
                'id' => Str::uuid(),
                'name' => $providerData['name'],
                'email' => $providerData['email'],
                'phone' => $providerData['phone'],
                'whatsapp' => $providerData['phone'],
                'password' => bcrypt('password'),
                'role' => $providerData['role'],
                'locale' => 'ar',
                'rating_avg' => $providerData['rating_avg'],
                'rating_count' => $providerData['rating_count'],
                'jobs_completed' => $providerData['jobs_completed'],
                'is_active' => true,
                'is_verified' => $providerData['is_verified'],
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'preferred_cities' => [],
                'notification_preferences' => [
                    'email' => true,
                    'push' => true,
                    'sms' => true,
                ],
            ]);
        }

        foreach ($customers as $customerData) {
            User::create([
                'id' => Str::uuid(),
                'name' => $customerData['name'],
                'email' => $customerData['email'],
                'phone' => $customerData['phone'],
                'whatsapp' => $customerData['phone'],
                'password' => bcrypt('password'),
                'role' => $customerData['role'],
                'locale' => 'ar',
                'is_active' => true,
                'is_verified' => false,
                'email_verified_at' => now(),
                'notification_preferences' => [
                    'email' => true,
                    'push' => true,
                    'sms' => false,
                ],
            ]);
        }

        $this->command->info('Users seeded successfully!');
    }
}

