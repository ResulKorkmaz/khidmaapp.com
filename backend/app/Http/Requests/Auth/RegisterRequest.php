<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'whatsapp' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|in:customer,individual_provider,company_provider,admin,editor',
            'locale' => 'nullable|in:ar,en',
            'terms_accepted' => 'required|accepted',
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Ad soyad gereklidir',
            'name.max' => 'Ad soyad en fazla 255 karakter olabilir',
            
            'email.required' => 'E-posta adresi gereklidir',
            'email.email' => 'Geçerli bir e-posta adresi giriniz',
            'email.unique' => 'Bu e-posta adresi zaten kullanılıyor',
            'email.max' => 'E-posta adresi en fazla 255 karakter olabilir',
            
            'phone.required' => 'Telefon numarası gereklidir',
            'phone.unique' => 'Bu telefon numarası zaten kullanılıyor',
            'phone.max' => 'Telefon numarası en fazla 20 karakter olabilir',
            
            'whatsapp.max' => 'WhatsApp numarası en fazla 20 karakter olabilir',
            
            'password.required' => 'Şifre gereklidir',
            'password.min' => 'Şifre en az 8 karakter olmalıdır',
            'password.confirmed' => 'Şifre onayı eşleşmiyor',
            
            'role.in' => 'Geçersiz rol seçimi',
            'locale.in' => 'Geçersiz dil seçimi',
            
            'terms_accepted.required' => 'Kullanım şartlarını kabul etmelisiniz',
            'terms_accepted.accepted' => 'Kullanım şartlarını kabul etmelisiniz',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // If whatsapp is not provided, use phone number
        if (!$this->has('whatsapp') || empty($this->whatsapp)) {
            $this->merge([
                'whatsapp' => $this->phone,
            ]);
        }

        // Set default role if not provided
        if (!$this->has('role') || empty($this->role)) {
            $this->merge([
                'role' => 'customer',
            ]);
        }

        // Set default locale if not provided
        if (!$this->has('locale') || empty($this->locale)) {
            $this->merge([
                'locale' => 'ar',
            ]);
        }
    }
}