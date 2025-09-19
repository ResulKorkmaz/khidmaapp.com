<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Servis listesi için validation kuralları
 * 
 * Bu request class'ı servisleri listeleme endpoint'i için
 * filtreleme ve sayfalama parametrelerini validate eder.
 */
class ServiceIndexRequest extends FormRequest
{
    /**
     * Kullanıcının bu request'i yapma yetkisi var mı?
     */
    public function authorize(): bool
    {
        // Servis listesini herkes görebilir (public endpoint)
        return true;
    }

    /**
     * Validation kuralları
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Arama parametreleri
            'search' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            
            // Bütçe filtreleri
            'min_budget' => ['nullable', 'numeric', 'min:0'],
            'max_budget' => ['nullable', 'numeric', 'min:0', 'gte:min_budget'],
            
            // Sıralama parametreleri
            'sort_by' => ['nullable', 'string', 'in:created_at,budget_min,budget_max,views_count'],
            'sort_order' => ['nullable', 'string', 'in:asc,desc'],
            
            // Sayfalama
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:50'],
            
            // Filtreleme seçenekleri
            'featured_only' => ['nullable', 'boolean'],
            'has_budget' => ['nullable', 'boolean'],
            
            // Tarih filtreleri
            'created_after' => ['nullable', 'date'],
            'created_before' => ['nullable', 'date', 'after:created_after'],
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
            'search.max' => 'Arama terimi en fazla 255 karakter olabilir.',
            'city.max' => 'Şehir adı en fazla 100 karakter olabilir.',
            'category.max' => 'Kategori adı en fazla 100 karakter olabilir.',
            
            'min_budget.numeric' => 'Minimum bütçe sayısal bir değer olmalıdır.',
            'min_budget.min' => 'Minimum bütçe 0\'dan küçük olamaz.',
            'max_budget.numeric' => 'Maksimum bütçe sayısal bir değer olmalıdır.',
            'max_budget.min' => 'Maksimum bütçe 0\'dan küçük olamaz.',
            'max_budget.gte' => 'Maksimum bütçe minimum bütçeden küçük olamaz.',
            
            'sort_by.in' => 'Sıralama kriteri geçersiz.',
            'sort_order.in' => 'Sıralama yönü asc veya desc olmalıdır.',
            
            'page.integer' => 'Sayfa numarası tam sayı olmalıdır.',
            'page.min' => 'Sayfa numarası en az 1 olmalıdır.',
            'per_page.integer' => 'Sayfa başına kayıt sayısı tam sayı olmalıdır.',
            'per_page.min' => 'Sayfa başına en az 5 kayıt gösterilmelidir.',
            'per_page.max' => 'Sayfa başına en fazla 50 kayıt gösterilebilir.',
            
            'featured_only.boolean' => 'Öne çıkarılmış filtresi true/false olmalıdır.',
            'has_budget.boolean' => 'Bütçe filtresi true/false olmalıdır.',
            
            'created_after.date' => 'Başlangıç tarihi geçerli bir tarih olmalıdır.',
            'created_before.date' => 'Bitiş tarihi geçerli bir tarih olmalıdır.',
            'created_before.after' => 'Bitiş tarihi başlangıç tarihinden sonra olmalıdır.',
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
            'search' => 'arama terimi',
            'city' => 'şehir',
            'category' => 'kategori',
            'min_budget' => 'minimum bütçe',
            'max_budget' => 'maksimum bütçe',
            'sort_by' => 'sıralama kriteri',
            'sort_order' => 'sıralama yönü',
            'page' => 'sayfa numarası',
            'per_page' => 'sayfa başına kayıt',
            'featured_only' => 'öne çıkarılmış',
            'has_budget' => 'bütçe durumu',
            'created_after' => 'başlangıç tarihi',
            'created_before' => 'bitiş tarihi',
        ];
    }
}
