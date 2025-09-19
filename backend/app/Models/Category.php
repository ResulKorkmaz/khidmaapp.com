<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, HasUuids, HasSlug, HasTranslations;

    protected $fillable = [
        'parent_id',
        'name_ar',
        'name_en',
        'slug_ar',
        'slug_en',
        'description_ar',
        'description_en',
        'icon',
        'color',
        'sort_order',
        'is_active',
        'services_count',
        'custom_fields',
        'seo_meta',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'custom_fields' => 'array',
        'seo_meta' => 'array',
    ];

    public $translatable = ['name', 'description', 'slug'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name_ar')
            ->saveSlugsTo('slug_ar');
    }

    // Relationships
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class)
            ->withPivot(['services_count', 'is_active'])
            ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRootCategories($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeSubCategories($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name_ar');
    }

    public function scopeWithServices($query)
    {
        return $query->where('services_count', '>', 0);
    }

    // Accessors
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"name_{$locale}"} ?? $this->name_ar;
    }

    public function getDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $this->{"description_{$locale}"} ?? $this->description_ar;
    }

    public function getSlugAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"slug_{$locale}"} ?? $this->slug_ar;
    }

    public function getIconUrlAttribute(): ?string
    {
        if (!$this->icon) {
            return null;
        }

        return asset('storage/icons/' . $this->icon);
    }

    public function getFullNameAttribute(): string
    {
        if ($this->parent) {
            return $this->parent->name . ' > ' . $this->name;
        }

        return $this->name;
    }

    public function getBreadcrumbAttribute(): array
    {
        $breadcrumb = [];
        $category = $this;

        while ($category) {
            array_unshift($breadcrumb, [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ]);
            $category = $category->parent;
        }

        return $breadcrumb;
    }

    // Helper Methods
    public function updateServicesCount(): void
    {
        $this->update([
            'services_count' => $this->services()->where('status', 'active')->count(),
        ]);
    }

    public function getAllDescendants(): array
    {
        $descendants = [];
        
        foreach ($this->children as $child) {
            $descendants[] = $child->id;
            $descendants = array_merge($descendants, $child->getAllDescendants());
        }

        return $descendants;
    }

    public function hasCustomField(string $field): bool
    {
        $fields = $this->custom_fields ?? [];
        return isset($fields[$field]);
    }

    public function getCustomField(string $field): ?array
    {
        $fields = $this->custom_fields ?? [];
        return $fields[$field] ?? null;
    }

    /**
     * Admin paneli için Arapça - Türkçe format
     * Sadece admin panelinde kullanılacak
     */
    public function getAdminDisplayNameAttribute(): string
    {
        // Hardcoded Türkçe çeviriler - Admin paneli için
        $translations = [
            // Database'deki gerçek kategoriler
            'التنظيف' => 'Temizlik & Hijyen',
            'الصيانة' => 'Genel Bakım & Onarım', 
            'السباكة' => 'Tesisat & Su Kaçakları',
            'الكهرباء' => 'Elektrik & Aydınlatma',
            'النجارة' => 'Marangoz & Mobilya',
            'الدهانات' => 'Boya & Dekorasyon',
            'التكييف' => 'Klima Servisi',
            'النقل' => 'Taşınma & Nakliye',
            'البستنة' => 'Peyzaj & Bahçe',
            'الطبخ' => 'Yemek Hizmetleri',
            'التصوير' => 'Foto & Video Çekimi',
            'التدريس' => 'Eğitim & Öğretmenlik',
            'التجميل' => 'Güzellik & Bakım',
            'الطباعة' => 'Baskı & Matbaa',
            'التوصيل' => 'Teslimat & Kurye',
            
            // Uzun kategori isimleri
            'صيانة التكييف' => 'Klima Servisi',
            'مكافحة الحشرات' => 'Haşere Kontrolü',
            'الدهان والديكور' => 'Boya & Dekorasyon',
            'النجارة والأثاث' => 'Marangoz & Mobilya',
            'صيانة الأجهزة المنزلية' => 'Beyaz Eşya & Cihaz Servisi',
            'النقل والانتقال' => 'Taşınma & Nakliye',
            'المقاولات والترميم' => 'İnşaat & Tadilat',
            'خدمات السيارات المتنقلة' => 'Otomotiv Yerinde Hizmet',
            'تقنية، ساتلايت وكاميرات' => 'IT, Uydu & CCTV',
            'البيوت الذكية والأمن' => 'Akıllı Ev & Güvenlik',
            'تنسيق الحدائق' => 'Peyzaj & Bahçe',
            'الغسيل والتنظيف الجاف' => 'Çamaşır & Kuru Temizleme',
            'خدمات إنجاز المعاملات' => 'PRO & Resmi İşlemler',
            'الفعاليات والتصوير' => 'Etkinlik & Foto/Video',
            'المساعدة المنزلية بالساعة' => 'Ev Yardımı Saatlik',
            
            // Ana kategoriler (خدمات ile başlayanlar)
            'خدمات التنظيف' => 'Temizlik Hizmetleri',
            'خدمات السباكة' => 'Tesisatçı Hizmetleri', 
            'خدمات الكهرباء' => 'Elektrikçi Hizmetleri',
            'خدمات التكييف' => 'Klima ve Soğutma',
            'خدمات الصيانة' => 'Bakım ve Onarım',
            'خدمات النقل' => 'Nakliye ve Taşıma',
            'خدمات التصميم' => 'Tasarım Hizmetleri',
            'خدمات التدريس' => 'Öğretmenlik ve Eğitim',
            'خدمات الطبخ' => 'Yemek ve Catering',
            'خدمات التجميل' => 'Güzellik ve Bakım',
            'خدمات الحدائق' => 'Bahçıvanlık',
            'خدمات السيارات' => 'Otomobil Hizmetleri',
            'خدمات المنزل' => 'Ev Hizmetleri',
            'خدمات التقنية' => 'Teknoloji Hizmetleri',
            'خدمات الأمن' => 'Güvenlik Hizmetleri',
            
            // Alt kategoriler - Temizlik
            'تنظيف المنازل' => 'Ev Temizliği',
            'تنظيف المكاتب' => 'Ofis Temizliği',
            'تنظيف السجاد' => 'Halı Yıkama',
            'تنظيف النوافذ' => 'Cam Temizliği',
            'تنظيف بعد البناء' => 'İnşaat Sonrası Temizlik',
            'تنظيف المسابح' => 'Havuz Temizliği',
            
            // Alt kategoriler - Tesisatçı
            'إصلاح الأنابيب' => 'Boru Onarımı',
            'تركيب أدوات صحية' => 'Sıhhi Tesisat Montajı',
            'حل مشاكل التسرب' => 'Su Kaçağı Tamiri',
            'تنظيف المجاري' => 'Kanal Temizliği',
            'تركيب الفلاتر' => 'Su Filtresi Montajı',
            
            // Alt kategoriler - Elektrikçi
            'إصلاح الكهرباء' => 'Elektrik Tamiri',
            'تركيب إضاءة' => 'Aydınlatma Montajı',
            'تركيب مراوح' => 'Vantilatör Montajı',
            'توصيل أجهزة' => 'Cihaz Bağlantısı',
            'فحص كهربائي' => 'Elektrik Kontrolü',
            
            // Alt kategoriler - Taşıma
            'نقل الأثاث' => 'Eşya Taşıma',
            'نقل المكاتب' => 'Ofis Taşıma',
            'نقل البضائع' => 'Kargo Taşıma',
            'خدمات التخزين' => 'Depolama Hizmetleri',
        ];

        $turkish = $translations[$this->name_ar] ?? '';
        
        if ($turkish) {
            return $this->name_ar . ' - ' . $turkish;
        }
        
        return $this->name_ar;
    }
}
