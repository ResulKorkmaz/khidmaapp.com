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
        return $this->success(null, 'Çıkış başarılı');
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
     * Get authenticated user
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
            'is_active' => $user->is_active,
            'is_verified' => $user->is_verified,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'whatsapp' => 'sometimes|nullable|string|max:20',
            'locale' => 'sometimes|in:ar,en,tr',
        ]);

        $user->update($validatedData);

        return $this->success([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'whatsapp' => $user->whatsapp,
                'role' => $user->role,
                'locale' => $user->locale,
                'is_verified' => $user->is_verified,
            ],
        ], 'Profil güncellendi');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->error('Mevcut şifre hatalı', 400);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Logout from all other devices
        $user->tokens()->where('id', '!=', $user->currentAccessToken()->id)->delete();

        return $this->success(null, 'Şifre başarıyla değiştirildi');
    }

    /**
     * Refresh token
     */
    public function refreshToken(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Delete current token
        $request->user()->currentAccessToken()->delete();
        
        // Create new token
        $token = $user->createToken('auth_token', ['*'], now()->addDays(30))->plainTextToken;

        return $this->success([
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => now()->addDays(30)->toISOString(),
        ], 'Token yenilendi');
    }

    /**
     * Send phone OTP (placeholder)
     */
    public function sendPhoneOTP(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        // TODO: Implement SMS OTP sending
        return $this->success(['otp_sent' => true], 'OTP gönderildi');
    }

    /**
     * Verify phone OTP (placeholder)
     */
    public function verifyPhoneOTP(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'otp' => 'required|string|size:6',
        ]);

        // TODO: Implement SMS OTP verification
        return $this->success(['verified' => true], 'Telefon doğrulandı');
    }
}