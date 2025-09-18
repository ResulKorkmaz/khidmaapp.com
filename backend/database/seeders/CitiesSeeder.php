<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            // Riyadh Region
            [
                'name_ar' => 'الرياض',
                'name_en' => 'Riyadh',
                'region_code' => 'RI',
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'priority' => 100,
                'services_count' => 250,
            ],
            [
                'name_ar' => 'الخرج',
                'name_en' => 'Al Kharj',
                'region_code' => 'RI',
                'latitude' => 24.1552,
                'longitude' => 47.3348,
                'priority' => 60,
                'services_count' => 45,
            ],
            
            // Makkah Region
            [
                'name_ar' => 'جدة',
                'name_en' => 'Jeddah',
                'region_code' => 'MA',
                'latitude' => 21.3099,
                'longitude' => 39.8208,
                'priority' => 95,
                'services_count' => 180,
            ],
            [
                'name_ar' => 'مكة المكرمة',
                'name_en' => 'Makkah',
                'region_code' => 'MA',
                'latitude' => 21.3891,
                'longitude' => 39.8579,
                'priority' => 85,
                'services_count' => 120,
            ],
            [
                'name_ar' => 'الطائف',
                'name_en' => 'Taif',
                'region_code' => 'MA',
                'latitude' => 21.2703,
                'longitude' => 40.4158,
                'priority' => 70,
                'services_count' => 75,
            ],
            
            // Eastern Province
            [
                'name_ar' => 'الدمام',
                'name_en' => 'Dammam',
                'region_code' => 'EP',
                'latitude' => 26.3927,
                'longitude' => 49.9777,
                'priority' => 90,
                'services_count' => 150,
            ],
            [
                'name_ar' => 'الخبر',
                'name_en' => 'Khobar',
                'region_code' => 'EP',
                'latitude' => 26.2172,
                'longitude' => 50.1971,
                'priority' => 80,
                'services_count' => 95,
            ],
            [
                'name_ar' => 'الظهران',
                'name_en' => 'Dhahran',
                'region_code' => 'EP',
                'latitude' => 26.2361,
                'longitude' => 50.1572,
                'priority' => 75,
                'services_count' => 85,
            ],
            [
                'name_ar' => 'الجبيل',
                'name_en' => 'Jubail',
                'region_code' => 'EP',
                'latitude' => 27.0174,
                'longitude' => 49.6251,
                'priority' => 65,
                'services_count' => 55,
            ],
            [
                'name_ar' => 'الأحساء',
                'name_en' => 'Al Ahsa',
                'region_code' => 'EP',
                'latitude' => 25.2868,
                'longitude' => 49.5857,
                'priority' => 60,
                'services_count' => 40,
            ],
            
            // Madinah Region
            [
                'name_ar' => 'المدينة المنورة',
                'name_en' => 'Madinah',
                'region_code' => 'MD',
                'latitude' => 24.5247,
                'longitude' => 39.5692,
                'priority' => 85,
                'services_count' => 110,
            ],
            [
                'name_ar' => 'ينبع',
                'name_en' => 'Yanbu',
                'region_code' => 'MD',
                'latitude' => 24.0894,
                'longitude' => 38.0618,
                'priority' => 65,
                'services_count' => 50,
            ],
            
            // Qassim Region
            [
                'name_ar' => 'بريدة',
                'name_en' => 'Buraydah',
                'region_code' => 'QS',
                'latitude' => 26.3260,
                'longitude' => 43.9750,
                'priority' => 75,
                'services_count' => 80,
            ],
            [
                'name_ar' => 'عنيزة',
                'name_en' => 'Unaizah',
                'region_code' => 'QS',
                'latitude' => 26.0876,
                'longitude' => 43.9936,
                'priority' => 60,
                'services_count' => 45,
            ],
            
            // Asir Region
            [
                'name_ar' => 'أبها',
                'name_en' => 'Abha',
                'region_code' => 'AS',
                'latitude' => 18.2164,
                'longitude' => 42.5048,
                'priority' => 70,
                'services_count' => 65,
            ],
            [
                'name_ar' => 'خميس مشيط',
                'name_en' => 'Khamis Mushait',
                'region_code' => 'AS',
                'latitude' => 18.3061,
                'longitude' => 42.7322,
                'priority' => 65,
                'services_count' => 50,
            ],
            
            // Tabuk Region
            [
                'name_ar' => 'تبوك',
                'name_en' => 'Tabuk',
                'region_code' => 'TB',
                'latitude' => 28.3998,
                'longitude' => 36.5700,
                'priority' => 60,
                'services_count' => 45,
            ],
            
            // Hail Region
            [
                'name_ar' => 'حائل',
                'name_en' => 'Hail',
                'region_code' => 'HA',
                'latitude' => 27.5114,
                'longitude' => 41.6900,
                'priority' => 55,
                'services_count' => 35,
            ],
            
            // Jazan Region
            [
                'name_ar' => 'جازان',
                'name_en' => 'Jazan',
                'region_code' => 'JZ',
                'latitude' => 16.8892,
                'longitude' => 42.5511,
                'priority' => 55,
                'services_count' => 35,
            ],
            
            // Najran Region
            [
                'name_ar' => 'نجران',
                'name_en' => 'Najran',
                'region_code' => 'NJ',
                'latitude' => 17.4924,
                'longitude' => 44.1277,
                'priority' => 50,
                'services_count' => 30,
            ],
            
            // Al Bahah Region
            [
                'name_ar' => 'الباحة',
                'name_en' => 'Al Bahah',
                'region_code' => 'BH',
                'latitude' => 20.0129,
                'longitude' => 41.4677,
                'priority' => 45,
                'services_count' => 25,
            ],
            
            // Northern Borders Region
            [
                'name_ar' => 'عرعر',
                'name_en' => 'Arar',
                'region_code' => 'NB',
                'latitude' => 30.9753,
                'longitude' => 41.0381,
                'priority' => 40,
                'services_count' => 20,
            ],
            
            // Al Jawf Region
            [
                'name_ar' => 'سكاكا',
                'name_en' => 'Sakaka',
                'region_code' => 'JF',
                'latitude' => 29.9697,
                'longitude' => 40.2064,
                'priority' => 40,
                'services_count' => 20,
            ],
        ];

        foreach ($cities as $cityData) {
            City::create([
                'id' => Str::uuid(),
                'name_ar' => $cityData['name_ar'],
                'name_en' => $cityData['name_en'],
                'slug_ar' => Str::slug($cityData['name_ar']),
                'slug_en' => Str::slug($cityData['name_en']),
                'region_code' => $cityData['region_code'],
                'latitude' => $cityData['latitude'],
                'longitude' => $cityData['longitude'],
                'is_active' => true,
                'priority' => $cityData['priority'],
                'services_count' => $cityData['services_count'],
                'meta' => [
                    'population' => $this->getPopulation($cityData['name_en']),
                    'timezone' => 'Asia/Riyadh',
                    'calling_code' => '+966',
                ],
            ]);
        }

        $this->command->info('Cities seeded successfully!');
    }

    private function getPopulation(string $cityName): int
    {
        $populations = [
            'Riyadh' => 7676654,
            'Jeddah' => 4697000,
            'Makkah' => 2385509,
            'Madinah' => 1512724,
            'Dammam' => 1252523,
            'Khobar' => 409549,
            'Dhahran' => 240742,
            'Buraydah' => 614093,
            'Tabuk' => 569797,
            'Abha' => 578261,
            'Hail' => 605930,
            'Taif' => 688693,
            'Jubail' => 474679,
            'Al Ahsa' => 1063112,
            'Yanbu' => 222360,
            'Unaizah' => 163729,
            'Khamis Mushait' => 515599,
            'Jazan' => 173919,
            'Najran' => 505652,
            'Al Bahah' => 88419,
            'Arar' => 191893,
            'Sakaka' => 115291,
            'Al Kharj' => 425300,
        ];

        return $populations[$cityName] ?? 100000;
    }
}
