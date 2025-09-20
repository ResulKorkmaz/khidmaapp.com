<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ProfileVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VerificationController extends BaseController
{
    /**
     * Get current user's verification status
     */
    public function getStatus(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user->isProvider()) {
                return $this->error('Only service providers can have verification status', 403);
            }

            $verification = $user->verification;
            
            if (!$verification) {
                return $this->success([
                    'status' => 'none',
                    'can_request' => true,
                    'trial_available' => true
                ]);
            }

            return $this->success([
                'status' => $verification->status,
                'is_active' => $verification->is_active,
                'is_trial' => $verification->is_trial,
                'verified_at' => $verification->verified_at?->format('Y-m-d H:i:s'),
                'expires_at' => $verification->expires_at?->format('Y-m-d H:i:s'),
                'trial_expires_at' => $verification->trial_expires_at?->format('Y-m-d H:i:s'),
                'days_until_expiry' => $verification->days_until_expiry,
                'is_expired' => $verification->is_expired,
                'is_verified' => $verification->is_verified,
                'can_request' => false,
                'trial_available' => false
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting verification status: ' . $e->getMessage());
            return $this->error('Failed to get verification status');
        }
    }

    /**
     * Request verification (starts with 6-month free trial)
     */
    public function requestVerification(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user->canRequestVerification()) {
                return $this->error('You cannot request verification at this time', 400);
            }

            $verification = ProfileVerification::createTrialVerification($user);

            return $this->success([
                'message' => 'تم بدء التجربة المجانية لمدة 6 أشهر بنجاح',
                'verification' => [
                    'status' => $verification->status,
                    'is_active' => $verification->is_active,
                    'is_trial' => $verification->is_trial,
                    'trial_expires_at' => $verification->trial_expires_at->format('Y-m-d H:i:s'),
                    'days_until_expiry' => $verification->days_until_expiry
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error requesting verification: ' . $e->getMessage());
            return $this->error('Failed to request verification');
        }
    }

    /**
     * Upgrade verification (convert from trial to paid)
     */
    public function upgradeVerification(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $verification = $user->verification;

            if (!$verification) {
                return $this->error('No verification found', 404);
            }

            // For now, we'll just extend the verification without payment processing
            // In the future, this would integrate with payment gateways
            $months = $request->input('months', 12);
            $verification->extendVerification($months);

            return $this->success([
                'message' => 'تم تجديد التوثيق بنجاح',
                'verification' => [
                    'status' => $verification->status,
                    'is_active' => $verification->is_active,
                    'is_trial' => $verification->is_trial,
                    'expires_at' => $verification->expires_at->format('Y-m-d H:i:s'),
                    'days_until_expiry' => $verification->days_until_expiry
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error upgrading verification: ' . $e->getMessage());
            return $this->error('Failed to upgrade verification');
        }
    }

    /**
     * Get verification pricing and benefits
     */
    public function getPricing(): JsonResponse
    {
        return $this->success([
            'plans' => [
                'basic' => [
                    'name' => 'التوثيق الأساسي',
                    'price' => 50.00,
                    'currency' => 'USD',
                    'billing_cycle' => 'yearly',
                    'trial_period' => 6, // months
                    'benefits' => [
                        'علامة التوثيق المميزة',
                        'أولوية في نتائج البحث',
                        'ثقة أكبر من العملاء',
                        'إحصائيات متقدمة',
                        'دعم فني مخصص'
                    ]
                ]
            ],
            'trial' => [
                'duration' => 6, // months
                'price' => 0,
                'description' => 'تجربة مجانية لمدة 6 أشهر لجميع المزايا'
            ]
        ]);
    }

    /**
     * Get verification statistics (for admins)
     */
    public function getStatistics(): JsonResponse
    {
        try {
            // This method should be protected by admin middleware
            
            $stats = [
                'total_verifications' => ProfileVerification::count(),
                'active_verifications' => ProfileVerification::active()->count(),
                'trial_verifications' => ProfileVerification::where('is_trial', true)->count(),
                'paid_verifications' => ProfileVerification::where('is_trial', false)->count(),
                'expired_verifications' => ProfileVerification::expired()->count(),
                'expiring_soon' => ProfileVerification::expiringInDays(30)->count(),
                'revenue_potential' => ProfileVerification::where('is_trial', true)
                    ->where('is_active', true)
                    ->count() * 50, // $50 per conversion
            ];

            return $this->success($stats);

        } catch (\Exception $e) {
            Log::error('Error getting verification statistics: ' . $e->getMessage());
            return $this->error('Failed to get statistics');
        }
    }
}

