<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{
    /**
     * Register a new user
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'whatsapp' => $request->whatsapp ?? $request->phone,
                'password' => Hash::make($request->password),
                'role' => $request->role ?? 'customer',
                'locale' => $request->locale ?? 'ar',
                'is_active' => true,
                'is_verified' => false,
            ]);

            $token = $user->createToken('auth_token', ['*'], now()->addDays(30))->plainTextToken;

            return $this->success([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                    'locale' => $user->locale,
                    'is_verified' => $user->is_verified,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_at' => now()->addDays(30)->toISOString(),
            ], 'Hesap başarıyla oluşturuldu', 201);
            
        } catch (\Exception $e) {
            \Log::error('User registration error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->except(['password', 'password_confirmation'])
            ]);
            return $this->error('Hesap oluşturulurken bir hata oluştu: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Login user
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $identifier = $request->input('email');
        $password = $request->input('password');
        
        // Determine if identifier is email or phone
        $credentials = [];
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            // It's an email
            $credentials = ['email' => $identifier, 'password' => $password];
        } else {
            // It's a phone number
            $credentials = ['phone' => $identifier, 'password' => $password];
        }

        if (!Auth::attempt($credentials)) {
            return $this->error('E-posta/telefon veya şifre hatalı', 401);
        }

        $user = Auth::user();

        // Check if user is active
        if (!$user->is_active) {
            Auth::logout();
            return $this->error('Hesabınız aktif değil', 403);
        }

        // Delete old tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('auth_token', ['*'], now()->addDays(30))->plainTextToken;

        return $this->success([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'locale' => $user->locale,
                'is_verified' => $user->is_verified,
                'rating_avg' => $user->rating_avg,
                'jobs_completed' => $user->jobs_completed,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => now()->addDays(30)->toISOString(),
        ], 'Giriş başarılı');
    }

    /**
     * Logout user
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Çıkış yapıldı');
    }

    /**
     * Logout from all devices
     */
    public function logoutAll(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return $this->success(null, 'Tüm cihazlardan çıkış yapıldı');
    }

    /**
     * Get current user profile
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'whatsapp' => $user->whatsapp,
            'role' => $user->role,
            'locale' => $user->locale,
            'is_verified' => $user->is_verified,
            'is_active' => $user->is_active,
            'rating_avg' => $user->rating_avg,
            'rating_count' => $user->rating_count,
            'jobs_completed' => $user->jobs_completed,
            'email_verified_at' => $user->email_verified_at,
            'phone_verified_at' => $user->phone_verified_at,
            'created_at' => $user->created_at->toISOString(),
        ], 'Profil bilgileri alındı');
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'whatsapp' => 'sometimes|string|max:20',
            'locale' => 'sometimes|in:ar,en',
        ]);

        $user->update($request->only(['name', 'phone', 'whatsapp', 'locale']));

        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'whatsapp' => $user->whatsapp,
            'role' => $user->role,
            'locale' => $user->locale,
            'is_verified' => $user->is_verified,
            'updated_at' => $user->updated_at->toISOString(),
        ], 'Profil güncellendi');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->error('Mevcut şifre hatalı', 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Logout from all devices except current
        $user->tokens()->where('id', '!=', $request->user()->currentAccessToken()->id)->delete();

        return $this->success(null, 'Şifre başarıyla değiştirildi');
    }

    /**
     * Send OTP for phone verification
     */
    public function sendPhoneOTP(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        // TODO: Implement actual SMS sending
        $otp = rand(100000, 999999);
        
        // Store OTP in cache for 5 minutes
        cache()->put('phone_otp_' . $request->phone, $otp, 300);

        // In production, send SMS here
        // For development, return OTP (remove this in production)
        return $this->success([
            'message' => 'OTP gönderildi',
            'otp' => config('app.debug') ? $otp : null, // Only show in debug mode
        ], 'Doğrulama kodu gönderildi');
    }

    /**
     * Verify phone OTP
     */
    public function verifyPhoneOTP(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'otp' => 'required|string|size:6',
        ]);

        $cachedOTP = cache()->get('phone_otp_' . $request->phone);

        if (!$cachedOTP || $cachedOTP != $request->otp) {
            return $this->error('Geçersiz veya süresi dolmuş kod', 400);
        }

        // Remove OTP from cache
        cache()->forget('phone_otp_' . $request->phone);

        // Update user phone verification
        if ($user = $request->user()) {
            $user->update([
                'phone' => $request->phone,
                'phone_verified_at' => now(),
            ]);
        }

        return $this->success(null, 'Telefon numarası doğrulandı');
    }

    /**
     * Refresh token
     */
    public function refreshToken(Request $request): JsonResponse
    {
        $user = $request->user();
        $currentToken = $request->user()->currentAccessToken();
        
        // Delete current token
        $currentToken->delete();
        
        // Create new token
        $token = $user->createToken('auth_token', ['*'], now()->addDays(30))->plainTextToken;

        return $this->success([
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => now()->addDays(30)->toISOString(),
        ], 'Token yenilendi');
    }
}