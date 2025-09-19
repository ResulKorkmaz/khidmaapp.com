<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class City extends Model
{
    use HasFactory, HasUuids, HasSlug;

    protected $fillable = [
        'name_ar',
        'name_en',
        'slug_ar',
        'slug_en',
        'region_code',
        'latitude',
        'longitude',
        'is_active',
        'priority',
        'services_count',
        'meta',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
        'meta' => 'array',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name_ar')
            ->saveSlugsTo('slug_ar');
    }

    // Relationships
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)
            ->withPivot(['services_count', 'is_active'])
            ->withTimestamps();
    }

    public function providers(): HasMany
    {
        return $this->hasMany(User::class)->providers();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'desc')->orderBy('name_ar');
    }

    public function scopeWithServices($query)
    {
        return $query->where('services_count', '>', 0);
    }

    public function scopeByRegion($query, string $regionCode)
    {
        return $query->where('region_code', $regionCode);
    }

    public function scopeNearby($query, float $lat, float $lng, int $radiusKm = 50)
    {
        return $query->selectRaw("*,
                    ( 6371 * acos( cos( radians(?) ) *
                      cos( radians( latitude ) )
                      * cos( radians( longitude ) - radians(?)
                      ) + sin( radians(?) ) *
                      sin( radians( latitude ) ) )
                    ) AS distance", [$lat, $lng, $lat])
            ->having("distance", "<", $radiusKm)
            ->orderBy("distance");
    }

    // Accessors
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"name_{$locale}"} ?? $this->name_ar;
    }

    public function getSlugAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"slug_{$locale}"} ?? $this->slug_ar;
    }

    /**
     * Admin paneli için Arapça - Türkçe format
     * Sadece admin panelinde kullanılacak
     */
    public function getAdminDisplayNameAttribute(): string
    {
        // Hardcoded Türkçe çeviriler - Admin paneli için
        $translations = [
            // Büyük şehirler
            'الرياض' => 'Riyad',
            'جدة' => 'Cidde',
            'الدمام' => 'Dammam',
            'مكة المكرمة' => 'Mekke',
            'المدينة المنورة' => 'Medine',
            'الطائف' => 'Taif',
            'تبوك' => 'Tebuk',
            'بريدة' => 'Buraydah',
            'الخبر' => 'Khobar',
            'خميس مشيط' => 'Khamis Mushait',
            'الهفوف' => 'Hofuf',
            'المبرز' => 'Mubarraz',
            'حائل' => 'Hail',
            'نجران' => 'Najran',
            'الجبيل' => 'Jubail',
            'ينبع' => 'Yanbu',
            'أبها' => 'Abha',
            'عرعر' => 'Arar',
            'سكاكا' => 'Sakaka',
            'جيزان' => 'Jizan',
            'القطيف' => 'Qatif',
            'الباحة' => 'Al Bahah',
            'رفحاء' => 'Rafha',
            'تيماء' => 'Tayma',
            'ضباء' => 'Duba',
        ];

        $turkish = $translations[$this->name_ar] ?? '';
        
        if ($turkish) {
            return $this->name_ar . ' - ' . $turkish;
        }
        
        return $this->name_ar;
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name;
    }

    public function getCoordinatesAttribute(): array
    {
        return [
            'lat' => $this->latitude,
            'lng' => $this->longitude,
        ];
    }

    public function getRegionNameAttribute(): string
    {
        $regions = [
            'RI' => 'الرياض', // Riyadh
            'MA' => 'مكة المكرمة', // Makkah
            'EP' => 'المنطقة الشرقية', // Eastern Province
            'AS' => 'عسير', // Asir
            'MD' => 'المدينة المنورة', // Madinah
            'QS' => 'القصيم', // Qassim
            'TB' => 'تبوك', // Tabuk
            'HA' => 'حائل', // Hail
            'NB' => 'الحدود الشمالية', // Northern Borders
            'JZ' => 'جازان', // Jazan
            'NJ' => 'نجران', // Najran
            'BH' => 'الباحة', // Al Bahah
            'JF' => 'الجوف', // Al Jawf
        ];

        return $regions[$this->region_code] ?? $this->region_code;
    }

    // Helper Methods
    public function updateServicesCount(): void
    {
        $this->update([
            'services_count' => $this->services()->where('status', 'active')->count(),
        ]);
    }

    public function getTopCategories(int $limit = 6): array
    {
        return $this->categories()
            ->withPivot('services_count')
            ->orderBy('category_city.services_count', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'icon' => $category->icon_url,
                    'services_count' => $category->pivot->services_count,
                ];
            })
            ->toArray();
    }

    public function distanceTo(float $lat, float $lng): float
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat - $this->latitude);
        $dLng = deg2rad($lng - $this->longitude);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($this->latitude)) * cos(deg2rad($lat)) *
             sin($dLng/2) * sin($dLng/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }

    public function getMetaValue(string $key, $default = null)
    {
        $meta = $this->meta ?? [];
        return $meta[$key] ?? $default;
    }

    public function setMetaValue(string $key, $value): void
    {
        $meta = $this->meta ?? [];
        $meta[$key] = $value;
        $this->update(['meta' => $meta]);
    }
}