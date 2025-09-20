<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, HasRoles, LogsActivity;

    protected static function boot()
    {
        parent::boot();
        
        // Prevent deletion of critical admin users
        static::deleting(function ($user) {
            // Prevent deletion of main admin user
            if ($user->email === 'admin@khidmaapp.com') {
                throw new \Exception('Main admin user (admin@khidmaapp.com) cannot be deleted for security reasons.');
            }
            
            // Prevent deletion of the last admin user
            $adminCount = static::where('role', 'admin')->where('id', '!=', $user->id)->count();
            if ($user->role === 'admin' && $adminCount < 1) {
                throw new \Exception('Cannot delete the last admin user. At least one admin user must exist.');
            }
        });
    }

    protected $fillable = [
        'name',
        'email',
        'phone',
        'whatsapp',
        'password',
        'role',
        'locale',
        'rating_avg',
        'rating_count',
        'jobs_completed',
        'is_active',
        'is_verified',
        'preferred_cities',
        'notification_preferences',
        'email_verified_at',
        'phone_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
        'preferred_cities' => 'array',
        'notification_preferences' => 'array',
        'rating_avg' => 'decimal:2',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'role', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships (UserProfile will be added later)
    // public function profile(): HasOne
    // {
    //     return $this->hasOne(UserProfile::class);
    // }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function bids(): HasMany
    {
        return $this->hasMany(ServiceBid::class, 'provider_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ServiceReview::class, 'provider_id');
    }

    public function givenReviews(): HasMany
    {
        return $this->hasMany(ServiceReview::class, 'customer_id');
    }

    public function verification(): HasOne
    {
        return $this->hasOne(ProfileVerification::class);
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(ProfileVerification::class);
    }

    // Scopes
    public function scopeProviders($query)
    {
        return $query->whereIn('role', ['individual_provider', 'company_provider']);
    }

    public function scopeIndividualProviders($query)
    {
        return $query->where('role', 'individual_provider');
    }

    public function scopeCompanyProviders($query)
    {
        return $query->where('role', 'company_provider');
    }

    public function scopeCustomers($query)
    {
        return $query->where('role', 'customer');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeEditors($query)
    {
        return $query->where('role', 'editor');
    }

    public function scopeBackendUsers($query)
    {
        return $query->whereIn('role', ['admin', 'editor']);
    }

    public function scopeFrontendUsers($query)
    {
        return $query->whereIn('role', ['customer', 'individual_provider', 'company_provider']);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeInCity($query, $cityId)
    {
        return $query->whereJsonContains('preferred_cities', $cityId);
    }

    // Accessors & Mutators
    public function getIsProviderAttribute(): bool
    {
        return in_array($this->role, ['individual_provider', 'company_provider']);
    }

    public function getIsIndividualProviderAttribute(): bool
    {
        return $this->role === 'individual_provider';
    }

    public function getIsCompanyProviderAttribute(): bool
    {
        return $this->role === 'company_provider';
    }

    public function getIsCustomerAttribute(): bool
    {
        return $this->role === 'customer';
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->role === 'admin';
    }

    public function getIsEditorAttribute(): bool
    {
        return $this->role === 'editor';
    }

    public function getIsBackendUserAttribute(): bool
    {
        return in_array($this->role, ['admin', 'editor']);
    }

    public function getIsFrontendUserAttribute(): bool
    {
        return in_array($this->role, ['customer', 'individual_provider', 'company_provider']);
    }

    public function getRoleDisplayNameAttribute(): string
    {
        return match($this->role) {
            'customer' => 'عميل',
            'individual_provider' => 'مقدم خدمة فردي',
            'company_provider' => 'مقدم خدمة شركة',
            'admin' => 'مدير النظام',
            'editor' => 'محرر',
            default => 'غير محدد'
        };
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name; // Will be enhanced when UserProfile is added
    }

    public function getAvatarUrlAttribute(): string
    {
        // For now, always use generated avatar
        // Will be enhanced when UserProfile is added
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=3B82F6&color=fff&size=200";
    }

    // Helper Methods
    public function updateRating(): void
    {
        $reviews = $this->reviews()->where('is_verified', true);
        
        $this->update([
            'rating_avg' => $reviews->avg('rating') ?? 0,
            'rating_count' => $reviews->count(),
        ]);
    }

    public function incrementJobsCompleted(): void
    {
        $this->increment('jobs_completed');
    }

    public function canReceiveNotifications(string $type): bool
    {
        $preferences = $this->notification_preferences ?? [];
        return $preferences[$type] ?? true;
    }

    public function getPreferredLanguage(): string
    {
        return $this->locale ?? 'ar';
    }

    // Verification Helper Methods
    public function getIsVerifiedProviderAttribute(): bool
    {
        if (!$this->isProvider()) {
            return false;
        }

        $verification = $this->verification;
        return $verification && $verification->is_verified;
    }

    public function getVerificationStatusAttribute(): string
    {
        $verification = $this->verification;
        
        if (!$verification) {
            return 'none';
        }
        
        if ($verification->is_expired) {
            return 'expired';
        }
        
        return $verification->status;
    }

    public function getVerificationExpiryAttribute(): ?string
    {
        $verification = $this->verification;
        
        if (!$verification) {
            return null;
        }
        
        if ($verification->is_trial && $verification->trial_expires_at) {
            return $verification->trial_expires_at->format('Y-m-d');
        }
        
        if ($verification->expires_at) {
            return $verification->expires_at->format('Y-m-d');
        }
        
        return null;
    }

    public function isProvider(): bool
    {
        return in_array($this->role, ['individual_provider', 'company_provider']);
    }

    public function isCompanyProvider(): bool
    {
        return $this->role === 'company_provider';
    }

    public function canRequestVerification(): bool
    {
        return $this->isProvider() && !$this->verification;
    }

    public function createTrialVerification(): ?ProfileVerification
    {
        if (!$this->canRequestVerification()) {
            return null;
        }

        return ProfileVerification::createTrialVerification($this);
    }
}
