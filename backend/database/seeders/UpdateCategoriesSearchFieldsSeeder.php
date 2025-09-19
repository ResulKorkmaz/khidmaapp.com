<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class UpdateCategoriesSearchFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define keywords for each category
        $categoryKeywords = [
            'التنظيف' => [
                'keywords' => ['تنظيف', 'نظافة', 'تطهير', 'غسيل', 'تعقيم', 'نظف', 'cleaning', 'clean', 'wash'],
                'search_priority' => 10,
                'is_searchable' => true
            ],
            'السباكة' => [
                'keywords' => ['سباكة', 'تسريب', 'أنابيب', 'صرف', 'مياه', 'حنفية', 'plumbing', 'pipe', 'leak'],
                'search_priority' => 9,
                'is_searchable' => true
            ],
            'الكهرباء' => [
                'keywords' => ['كهرباء', 'كهربائي', 'إضاءة', 'أسلاك', 'كهرب', 'electrical', 'electric', 'wiring'],
                'search_priority' => 9,
                'is_searchable' => true
            ],
            'التكييف' => [
                'keywords' => ['تكييف', 'تبريد', 'مكيف', 'تهوية', 'مبرد', 'hvac', 'ac', 'cooling'],
                'search_priority' => 8,
                'is_searchable' => true
            ],
            'الصيانة العامة' => [
                'keywords' => ['صيانة', 'إصلاح', 'تصليح', 'ترميم', 'maintenance', 'repair', 'fix'],
                'search_priority' => 7,
                'is_searchable' => true
            ],
            'الدهانات' => [
                'keywords' => ['دهان', 'طلاء', 'ديكور', 'ألوان', 'painting', 'paint', 'color'],
                'search_priority' => 6,
                'is_searchable' => true
            ],
            'النجارة' => [
                'keywords' => ['نجارة', 'خشب', 'أثاث', 'نجار', 'carpentry', 'wood', 'furniture'],
                'search_priority' => 6,
                'is_searchable' => true
            ],
            'البستنة' => [
                'keywords' => ['بستنة', 'حديقة', 'نباتات', 'زراعة', 'gardening', 'garden', 'plants'],
                'search_priority' => 5,
                'is_searchable' => true
            ],
            'النقل' => [
                'keywords' => ['نقل', 'انتقال', 'شحن', 'توصيل', 'moving', 'transport', 'delivery'],
                'search_priority' => 5,
                'is_searchable' => true
            ],
            'التدريس' => [
                'keywords' => ['تدريس', 'تعليم', 'معلم', 'درس', 'tutoring', 'education', 'teaching'],
                'search_priority' => 4,
                'is_searchable' => true
            ],
            'مكافحة الحشرات' => [
                'keywords' => ['حشرات', 'رش', 'مبيدات', 'صراصير', 'pest', 'insects', 'spray'],
                'search_priority' => 5,
                'is_searchable' => true
            ],
            'النجارة والأثاث' => [
                'keywords' => ['نجارة', 'أثاث', 'خشب', 'كراسي', 'طاولات', 'furniture', 'carpentry'],
                'search_priority' => 6,
                'is_searchable' => true
            ],
            'صيانة الأجهزة المنزلية' => [
                'keywords' => ['أجهزة', 'ثلاجة', 'غسالة', 'فرن', 'appliances', 'repair'],
                'search_priority' => 7,
                'is_searchable' => true
            ],
            'المقاولات والترميم' => [
                'keywords' => ['مقاولات', 'ترميم', 'بناء', 'تشييد', 'construction', 'renovation'],
                'search_priority' => 6,
                'is_searchable' => true
            ],
            'خدمات السيارات المتنقلة' => [
                'keywords' => ['سيارات', 'غسيل', 'تنظيف', 'car', 'wash', 'mobile'],
                'search_priority' => 5,
                'is_searchable' => true
            ]
        ];

        foreach ($categoryKeywords as $nameAr => $data) {
            Category::where('name_ar', $nameAr)->update([
                'keywords' => json_encode($data['keywords']),
                'search_priority' => $data['search_priority'],
                'is_searchable' => $data['is_searchable']
            ]);
        }

        $this->command->info('Categories search fields updated successfully!');
    }
}
