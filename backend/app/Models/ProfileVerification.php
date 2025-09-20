<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ProfileVerification extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'status',
        'verification_type',
        'price',
        'verified_at',
        'expires_at',
        'trial_expires_at',
        'is_trial',
        'is_active',
        'documents',
        'admin_notes',
        'payment_reference',
        'payment_received_at',
    ];

    protected $casts = [
        'documents' => 'array',
        'verified_at' => 'datetime',
        'expires_at' => 'datetime',
        'trial_expires_at' => 'datetime',
        'payment_received_at' => 'datetime',
        'is_trial' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now())
                     ->orWhere(function($q) {
                         $q->where('is_trial', true)
                           ->where('trial_expires_at', '<', now());
                     });
    }

    public function scopeExpiringInDays($query, $days = 30)
    {
        $date = now()->addDays($days);
        return $query->where(function($q) use ($date) {
            $q->where('expires_at', '<=', $date)
              ->orWhere(function($subQ) use ($date) {
                  $subQ->where('is_trial', true)
                       ->where('trial_expires_at', '<=', $date);
              });
        });
    }

    // Accessors & Mutators
    public function getIsExpiredAttribute(): bool
    {
        if ($this->is_trial) {
            return $this->trial_expires_at && $this->trial_expires_at->isPast();
        }
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getDaysUntilExpiryAttribute(): int
    {
        if ($this->is_trial && $this->trial_expires_at) {
            return max(0, now()->diffInDays($this->trial_expires_at, false));
        }
        if ($this->expires_at) {
            return max(0, now()->diffInDays($this->expires_at, false));
        }
        return 0;
    }

    public function getIsVerifiedAttribute(): bool
    {
        return $this->status === 'approved' && 
               $this->is_active && 
               !$this->is_expired;
    }

    // Methods
    public static function createTrialVerification(User $user): self
    {
        return self::create([
            'user_id' => $user->id,
            'status' => 'approved',
            'verification_type' => 'basic',
            'trial_expires_at' => now()->addMonths(6), // 6 months free
            'is_trial' => true,
            'is_active' => true,
            'verified_at' => now(),
        ]);
    }

    public function extendVerification(int $months = 12): self
    {
        if ($this->is_trial) {
            // Convert from trial to paid
            $this->is_trial = false;
            $this->expires_at = now()->addMonths($months);
            $this->trial_expires_at = null;
        } else {
            // Extend existing verification
            $currentExpiry = $this->expires_at ?: now();
            $this->expires_at = $currentExpiry->addMonths($months);
        }

        $this->is_active = true;
        $this->status = 'approved';
        $this->payment_received_at = now();
        $this->save();

        return $this;
    }

    public function approve(string $adminNotes = null): self
    {
        $this->status = 'approved';
        $this->is_active = true;
        $this->verified_at = now();
        
        if (!$this->is_trial && !$this->expires_at) {
            $this->expires_at = now()->addYear();
        }
        
        if ($adminNotes) {
            $this->admin_notes = $adminNotes;
        }
        
        $this->save();
        return $this;
    }

    public function reject(string $reason): self
    {
        $this->status = 'rejected';
        $this->is_active = false;
        $this->admin_notes = $reason;
        $this->save();
        return $this;
    }

    public function expire(): self
    {
        $this->status = 'expired';
        $this->is_active = false;
        $this->save();
        return $this;
    }
}

