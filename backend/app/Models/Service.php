<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Service extends Model
{
    use HasFactory, HasUuids, HasSlug, LogsActivity;

    protected $fillable = [
        'user_id',
        'category_id',
        'city_id',
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'slug_ar',
        'slug_en',
        'images',
        'budget_min',
        'budget_max',
        'budget_currency',
        'urgency',
        'preferred_date',
        'preferred_time',
        'latitude',
        'longitude',
        'address_ar',
        'address_en',
        'status',
        'expires_at',
        'completed_at',
        'views_count',
        'bids_count',
        'is_featured',
        'custom_fields',
        'seo_meta',
    ];

    protected $casts = [
        'images' => 'array',
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'preferred_date' => 'date',
        'preferred_time' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'expires_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_featured' => 'boolean',
        'custom_fields' => 'array',
        'seo_meta' => 'array',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['title_ar', 'title_en'])
            ->saveSlugsTo(['slug_ar', 'slug_en'])
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'title_ar', 'budget_min', 'budget_max'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function bids(): HasMany
    {
        return $this->hasMany(ServiceBid::class);
    }

    public function acceptedBid(): HasOne
    {
        return $this->hasOne(ServiceBid::class)->where('status', 'accepted');
    }

    public function review(): HasOne
    {
        return $this->hasOne(ServiceReview::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInCity($query, $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeWithBudget($query, $minBudget = null, $maxBudget = null)
    {
        if ($minBudget) {
            $query->where('budget_max', '>=', $minBudget);
        }
        if ($maxBudget) {
            $query->where('budget_min', '<=', $maxBudget);
        }
        return $query;
    }

    public function scopeByUrgency($query, string $urgency)
    {
        return $query->where('urgency', $urgency);
    }

    public function scopeNearby($query, float $lat, float $lng, int $radiusKm = 25)
    {
        $query->selectRaw("
            *,
            (6371 * acos(cos(radians(?)) 
            * cos(radians(latitude)) 
            * cos(radians(longitude) - radians(?)) 
            + sin(radians(?)) 
            * sin(radians(latitude)))) AS distance_km
        ", [$lat, $lng, $lat])
        ->having('distance_km', '<=', $radiusKm)
        ->orderBy('distance_km');

        return $query;
    }

    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeExpiring($query, int $days = 3)
    {
        return $query->where('expires_at', '<=', now()->addDays($days))
                    ->where('expires_at', '>', now());
    }

    // Accessors
    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"title_{$locale}"} ?? $this->title_ar;
    }

    public function getDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"description_{$locale}"} ?? $this->description_ar;
    }

    public function getSlugAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"slug_{$locale}"} ?? $this->slug_ar;
    }

    public function getAddressAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $this->{"address_{$locale}"} ?? $this->address_ar;
    }

    public function getBudgetRangeAttribute(): string
    {
        if (!$this->budget_min && !$this->budget_max) {
            return __('Budget: To be discussed');
        }

        $currency = $this->budget_currency === 'SAR' ? 'ر.س' : $this->budget_currency;

        if ($this->budget_min && $this->budget_max) {
            return "{$this->budget_min} - {$this->budget_max} {$currency}";
        }

        if ($this->budget_min) {
            return __('From') . " {$this->budget_min} {$currency}";
        }

        return __('Up to') . " {$this->budget_max} {$currency}";
    }

    public function getUrgencyLabelAttribute(): string
    {
        $labels = [
            'low' => __('Low Priority'),
            'medium' => __('Medium Priority'),
            'high' => __('High Priority'),
            'urgent' => __('Urgent'),
        ];

        return $labels[$this->urgency] ?? $this->urgency;
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'draft' => __('Draft'),
            'active' => __('Active'),
            'closed' => __('Closed'),
            'completed' => __('Completed'),
            'cancelled' => __('Cancelled'),
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getImagesUrlsAttribute(): array
    {
        if (!$this->images) {
            return [];
        }

        return array_map(function ($image) {
            return asset('storage/services/' . $image);
        }, $this->images);
    }

    public function getFeaturedImageAttribute(): ?string
    {
        $images = $this->images_urls;
        return $images[0] ?? null;
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getIsCompletedAttribute(): bool
    {
        return in_array($this->status, ['completed', 'closed']);
    }

    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->expires_at) {
            return null;
        }

        return max(0, now()->diffInDays($this->expires_at, false));
    }

    // Helper Methods
    public function incrementViewsCount(): void
    {
        $this->increment('views_count');
    }

    public function updateBidsCount(): void
    {
        $this->update([
            'bids_count' => $this->bids()->count(),
        ]);
    }

    public function canReceiveBids(): bool
    {
        return $this->status === 'active' && !$this->is_expired;
    }

    public function hasAcceptedBid(): bool
    {
        return $this->acceptedBid()->exists();
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Update customer's jobs completed count
        $this->customer->incrementJobsCompleted();
    }

    public function getCoordinates(): ?array
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        return [
            'lat' => $this->latitude,
            'lng' => $this->longitude,
        ];
    }

    public function distanceTo(float $lat, float $lng): float
    {
        if (!$this->latitude || !$this->longitude) {
            return 0;
        }

        $earthRadius = 6371; // km

        $dLat = deg2rad($lat - $this->latitude);
        $dLng = deg2rad($lng - $this->longitude);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($this->latitude)) * cos(deg2rad($lat)) *
             sin($dLng/2) * sin($dLng/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }
}
