<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string',
            'password' => 'required|string|min:6',
        ];
    }
    
    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $email = $this->input('email');
            
            // Check if it's an email format
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // It's an email, check if exists
                if (!\App\Models\User::where('email', $email)->exists()) {
                    $validator->errors()->add('email', 'Bu e-posta adresi ile kayıtlı hesap bulunamadı');
                }
            } else {
                // It's probably a phone number, check if exists
                if (!\App\Models\User::where('phone', $email)->exists()) {
                    $validator->errors()->add('email', 'Bu telefon numarası ile kayıtlı hesap bulunamadı');
                }
            }
        });
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'email.required' => 'E-posta adresi gereklidir',
            'email.email' => 'Geçerli bir e-posta adresi giriniz',
            'email.exists' => 'Bu e-posta adresi ile kayıtlı hesap bulunamadı',
            'password.required' => 'Şifre gereklidir',
            'password.min' => 'Şifre en az 6 karakter olmalıdır',
        ];
    }
}