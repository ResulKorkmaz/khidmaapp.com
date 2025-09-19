<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Yeni servis oluşturma için validation kuralları
 * 
 * Bu request class'ı yeni servis oluşturma endpoint'i için
 * gerekli alanları ve validation kurallarını tanımlar.
 */
class ServiceStoreRequest extends FormRequest
{
    /**
     * Kullanıcının bu request'i yapma yetkisi var mı?
     */
    public function authorize(): bool
    {
        // Sadece authenticated kullanıcılar servis oluşturabilir
        return auth()->check();
    }

    /**
     * Validation kuralları
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Başlık alanları (en az birisi zorunlu)
            'title_ar' => ['required_without:title_en', 'nullable', 'string', 'max:255'],
            'title_en' => ['required_without:title_ar', 'nullable', 'string', 'max:255'],
            
            // Açıklama alanları (en az birisi zorunlu)
            'description_ar' => ['required_without:description_en', 'nullable', 'string', 'max:5000'],
            'description_en' => ['required_without:description_ar', 'nullable', 'string', 'max:5000'],
            
            // İlişkili veriler
            'category_id' => ['required', 'uuid', 'exists:categories,id'],
            'city_id' => ['required', 'uuid', 'exists:cities,id'],
            
            // Bütçe bilgileri (isteğe bağlı)
            'budget_min' => ['nullable', 'numeric', 'min:0', 'max:999999999'],
            'budget_max' => ['nullable', 'numeric', 'min:0', 'max:999999999', 'gte:budget_min'],
            
            // Fotoğraflar (maksimum 5 adet)
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'], // 2MB max
            
            // Özel alanlar (kategori özelinde)
            'custom_fields' => ['nullable', 'array'],
            'custom_fields.*' => ['string', 'max:1000'],
            
            // Servis tercihleri
            'is_urgent' => ['nullable', 'boolean'],
            'is_online' => ['nullable', 'boolean'],
            'preferred_time' => ['nullable', 'string', 'in:morning,afternoon,evening,anytime'],
            
            // İletişim tercihleri
            'contact_phone' => ['nullable', 'boolean'],
            'contact_whatsapp' => ['nullable', 'boolean'],
            'contact_chat' => ['nullable', 'boolean'],
            
            // Konum bilgisi (isteğe bağlı)
            'address' => ['nullable', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }

    /**
     * Hata mesajları (Türkçe)
     * 
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Başlık validation
            'title_ar.required_without' => 'Arapça veya İngilizce başlık zorunludur.',
            'title_en.required_without' => 'İngilizce veya Arapça başlık zorunludur.',
            'title_ar.max' => 'Arapça başlık en fazla 255 karakter olabilir.',
            'title_en.max' => 'İngilizce başlık en fazla 255 karakter olabilir.',
            
            // Açıklama validation
            'description_ar.required_without' => 'Arapça veya İngilizce açıklama zorunludur.',
            'description_en.required_without' => 'İngilizce veya Arapça açıklama zorunludur.',
            'description_ar.max' => 'Arapça açıklama en fazla 5000 karakter olabilir.',
            'description_en.max' => 'İngilizce açıklama en fazla 5000 karakter olabilir.',
            
            // İlişkili veriler
            'category_id.required' => 'Kategori seçimi zorunludur.',
            'category_id.uuid' => 'Geçersiz kategori ID formatı.',
            'category_id.exists' => 'Seçilen kategori bulunamadı.',
            'city_id.required' => 'Şehir seçimi zorunludur.',
            'city_id.uuid' => 'Geçersiz şehir ID formatı.',
            'city_id.exists' => 'Seçilen şehir bulunamadı.',
            
            // Bütçe validation
            'budget_min.numeric' => 'Minimum bütçe sayısal bir değer olmalıdır.',
            'budget_min.min' => 'Minimum bütçe 0\'dan küçük olamaz.',
            'budget_min.max' => 'Minimum bütçe çok yüksek.',
            'budget_max.numeric' => 'Maksimum bütçe sayısal bir değer olmalıdır.',
            'budget_max.min' => 'Maksimum bütçe 0\'dan küçük olamaz.',
            'budget_max.max' => 'Maksimum bütçe çok yüksek.',
            'budget_max.gte' => 'Maksimum bütçe minimum bütçeden küçük olamaz.',
            
            // Fotoğraf validation
            'photos.array' => 'Fotoğraflar array formatında olmalıdır.',
            'photos.max' => 'En fazla 5 fotoğraf yükleyebilirsiniz.',
            'photos.*.image' => 'Yüklenen dosya resim formatında olmalıdır.',
            'photos.*.mimes' => 'Sadece JPEG, PNG, JPG ve WebP formatları kabul edilir.',
            'photos.*.max' => 'Her fotoğraf en fazla 2MB olabilir.',
            
            // Diğer alanlar
            'custom_fields.array' => 'Özel alanlar array formatında olmalıdır.',
            'custom_fields.*.max' => 'Özel alan değeri en fazla 1000 karakter olabilir.',
            'preferred_time.in' => 'Tercih edilen zaman geçersiz.',
            'address.max' => 'Adres en fazla 500 karakter olabilir.',
            'latitude.between' => 'Enlem -90 ile 90 arasında olmalıdır.',
            'longitude.between' => 'Boylam -180 ile 180 arasında olmalıdır.',
        ];
    }

    /**
     * Attribute adlarını Türkçeleştir
     * 
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title_ar' => 'Arapça başlık',
            'title_en' => 'İngilizce başlık',
            'description_ar' => 'Arapça açıklama',
            'description_en' => 'İngilizce açıklama',
            'category_id' => 'kategori',
            'city_id' => 'şehir',
            'budget_min' => 'minimum bütçe',
            'budget_max' => 'maksimum bütçe',
            'photos' => 'fotoğraflar',
            'custom_fields' => 'özel alanlar',
            'is_urgent' => 'acil durum',
            'is_online' => 'online servis',
            'preferred_time' => 'tercih edilen zaman',
            'contact_phone' => 'telefon iletişimi',
            'contact_whatsapp' => 'WhatsApp iletişimi',
            'contact_chat' => 'chat iletişimi',
            'address' => 'adres',
            'latitude' => 'enlem',
            'longitude' => 'boylam',
        ];
    }

    /**
     * Request verilerini işlenmek üzere hazırla
     */
    protected function prepareForValidation(): void
    {
        // Boolean değerleri normalize et
        if ($this->has('is_urgent')) {
            $this->merge(['is_urgent' => filter_var($this->is_urgent, FILTER_VALIDATE_BOOLEAN)]);
        }
        
        if ($this->has('is_online')) {
            $this->merge(['is_online' => filter_var($this->is_online, FILTER_VALIDATE_BOOLEAN)]);
        }
        
        // Contact preference'ları normalize et
        foreach (['contact_phone', 'contact_whatsapp', 'contact_chat'] as $field) {
            if ($this->has($field)) {
                $this->merge([$field => filter_var($this->$field, FILTER_VALIDATE_BOOLEAN)]);
            }
        }
    }
}
