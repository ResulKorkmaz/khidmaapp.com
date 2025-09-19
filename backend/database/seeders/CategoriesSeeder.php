<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Main Categories
            [
                'name_ar' => 'التنظيف',
                'name_en' => 'Cleaning',
                'description_ar' => 'خدمات التنظيف المنزلي والتجاري',
                'description_en' => 'Home and commercial cleaning services',
                'icon' => 'cleaning.svg',
                'color' => '#10B981',
                'sort_order' => 1,
                'services_count' => 85,
            ],
            [
                'name_ar' => 'الصيانة',
                'name_en' => 'Maintenance',
                'description_ar' => 'صيانة الأجهزة المنزلية والكهربائية',
                'description_en' => 'Home appliances and electrical maintenance',
                'icon' => 'maintenance.svg',
                'color' => '#3B82F6',
                'sort_order' => 2,
                'services_count' => 120,
            ],
            [
                'name_ar' => 'السباكة',
                'name_en' => 'Plumbing',
                'description_ar' => 'إصلاح وتركيب أنظمة السباكة',
                'description_en' => 'Plumbing repair and installation',
                'icon' => 'plumbing.svg',
                'color' => '#06B6D4',
                'sort_order' => 3,
                'services_count' => 95,
            ],
            [
                'name_ar' => 'الكهرباء',
                'name_en' => 'Electrical',
                'description_ar' => 'تركيب وإصلاح الأنظمة الكهربائية',
                'description_en' => 'Electrical installation and repair',
                'icon' => 'electrical.svg',
                'color' => '#F59E0B',
                'sort_order' => 4,
                'services_count' => 78,
            ],
            [
                'name_ar' => 'النجارة',
                'name_en' => 'Carpentry',
                'description_ar' => 'أعمال النجارة والأثاث المنزلي',
                'description_en' => 'Carpentry and furniture work',
                'icon' => 'carpentry.svg',
                'color' => '#8B5CF6',
                'sort_order' => 5,
                'services_count' => 65,
            ],
            [
                'name_ar' => 'الدهانات',
                'name_en' => 'Painting',
                'description_ar' => 'دهان الجدران والديكورات',
                'description_en' => 'Wall painting and decoration',
                'icon' => 'painting.svg',
                'color' => '#EF4444',
                'sort_order' => 6,
                'services_count' => 72,
            ],
            [
                'name_ar' => 'التكييف',
                'name_en' => 'Air Conditioning',
                'description_ar' => 'تركيب وصيانة أجهزة التكييف',
                'description_en' => 'AC installation and maintenance',
                'icon' => 'ac.svg',
                'color' => '#14B8A6',
                'sort_order' => 7,
                'services_count' => 88,
            ],
            [
                'name_ar' => 'النقل',
                'name_en' => 'Moving',
                'description_ar' => 'خدمات النقل والترحيل',
                'description_en' => 'Moving and relocation services',
                'icon' => 'moving.svg',
                'color' => '#F97316',
                'sort_order' => 8,
                'services_count' => 45,
            ],
            [
                'name_ar' => 'البستنة',
                'name_en' => 'Gardening',
                'description_ar' => 'تنسيق وصيانة الحدائق',
                'description_en' => 'Garden design and maintenance',
                'icon' => 'gardening.svg',
                'color' => '#22C55E',
                'sort_order' => 9,
                'services_count' => 38,
            ],
            [
                'name_ar' => 'الطبخ',
                'name_en' => 'Cooking',
                'description_ar' => 'خدمات الطبخ والولائم',
                'description_en' => 'Cooking and catering services',
                'icon' => 'cooking.svg',
                'color' => '#DC2626',
                'sort_order' => 10,
                'services_count' => 42,
            ],
            [
                'name_ar' => 'التصوير',
                'name_en' => 'Photography',
                'description_ar' => 'التصوير الفوتوغرافي والفيديو',
                'description_en' => 'Photography and videography',
                'icon' => 'photography.svg',
                'color' => '#9333EA',
                'sort_order' => 11,
                'services_count' => 35,
            ],
            [
                'name_ar' => 'التدريس',
                'name_en' => 'Tutoring',
                'description_ar' => 'دروس خصوصية وتدريس',
                'description_en' => 'Private tutoring and teaching',
                'icon' => 'tutoring.svg',
                'color' => '#059669',
                'sort_order' => 12,
                'services_count' => 55,
            ],
            [
                'name_ar' => 'التجميل',
                'name_en' => 'Beauty',
                'description_ar' => 'خدمات التجميل والعناية',
                'description_en' => 'Beauty and care services',
                'icon' => 'beauty.svg',
                'color' => '#EC4899',
                'sort_order' => 13,
                'services_count' => 48,
            ],
            [
                'name_ar' => 'الطباعة',
                'name_en' => 'Printing',
                'description_ar' => 'خدمات الطباعة والتصميم',
                'description_en' => 'Printing and design services',
                'icon' => 'printing.svg',
                'color' => '#7C3AED',
                'sort_order' => 14,
                'services_count' => 28,
            ],
            [
                'name_ar' => 'التوصيل',
                'name_en' => 'Delivery',
                'description_ar' => 'خدمات التوصيل والشحن',
                'description_en' => 'Delivery and shipping services',
                'icon' => 'delivery.svg',
                'color' => '#0EA5E9',
                'sort_order' => 15,
                'services_count' => 62,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create([
                'id' => Str::uuid(),
                'parent_id' => null,
                'name_ar' => $categoryData['name_ar'],
                'name_en' => $categoryData['name_en'],
                'slug_ar' => Str::slug($categoryData['name_ar']),
                'slug_en' => Str::slug($categoryData['name_en']),
                'description_ar' => $categoryData['description_ar'],
                'description_en' => $categoryData['description_en'],
                'icon' => $categoryData['icon'],
                'color' => $categoryData['color'],
                'sort_order' => $categoryData['sort_order'],
                'is_active' => true,
                'services_count' => $categoryData['services_count'],
                'custom_fields' => [],
                'seo_meta' => [
                    'title_ar' => $categoryData['name_ar'] . ' في السعودية',
                    'title_en' => $categoryData['name_en'] . ' in Saudi Arabia',
                    'description_ar' => 'احصل على أفضل خدمات ' . $categoryData['name_ar'] . ' في السعودية',
                    'description_en' => 'Get the best ' . $categoryData['name_en'] . ' services in Saudi Arabia',
                ],
            ]);
        }

        $this->command->info('Categories seeded successfully!');
    }
}

