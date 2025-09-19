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
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'whatsapp' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|in:customer,individual_provider,company_provider,admin,editor',
            'locale' => 'nullable|in:ar,en,tr',
            'terms_accepted' => 'required|boolean|accepted',
            
            // Company specific fields
            'company_name' => 'nullable|string|max:255',
            'authorized_person_name' => 'nullable|string|max:255',
            'authorized_person_surname' => 'nullable|string|max:255',
            'service_cities' => 'nullable|array',
            'services' => 'nullable|array',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Ad soyad gereklidir',
            'name.max' => 'Ad soyad en fazla 255 karakter olabilir',
            'email.required' => 'E-posta adresi gereklidir',
            'email.email' => 'Geçerli bir e-posta adresi giriniz',
            'email.unique' => 'Bu e-posta adresi zaten kullanılıyor',
            'phone.required' => 'Telefon numarası gereklidir',
            'phone.unique' => 'Bu telefon numarası zaten kullanılıyor',
            'password.required' => 'Şifre gereklidir',
            'password.min' => 'Şifre en az 8 karakter olmalıdır',
            'password.confirmed' => 'Şifre onayı eşleşmiyor',
            'terms_accepted.accepted' => 'Kullanım şartlarını kabul etmelisiniz',
            'role.in' => 'Geçersiz kullanıcı rolü',
            'locale.in' => 'Geçersiz dil seçimi',
        ];
    }
}