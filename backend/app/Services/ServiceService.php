<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Servis işlemlerini yöneten business logic sınıfı
 * 
 * Bu sınıf servis CRUD, arama, filtreleme ve istatistik
 * işlemlerini handle eder. Controller'dan çağırılır.
 */
class ServiceService
{
    /**
     * Filtreleme parametrelerine göre servisleri getirir
     * 
     * @param array $filters Filtreleme parametreleri (city, category, search vb.)
     * @param int $perPage Sayfa başına kayıt sayısı
     * @return LengthAwarePaginator Sayfalı servis listesi
     */
    public function getFilteredServices(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Service::query()
            ->with(['user', 'category', 'city']) // İlişkili verileri de getir
            ->where('status', 'active')         // Sadece aktif servisleri
            ->where('is_featured', true);       // Öne çıkarılmış servisleri önce göster

        // Şehir filtresi
        if (!empty($filters['city'])) {
            $query->whereHas('city', function ($q) use ($filters) {
                $q->where('slug_ar', $filters['city'])
                  ->orWhere('slug_en', $filters['city']);
            });
        }

        // Kategori filtresi  
        if (!empty($filters['category'])) {
            $query->whereHas('category', function ($q) use ($filters) {
                $q->where('slug_ar', $filters['category'])
                  ->orWhere('slug_en', $filters['category']);
            });
        }

        // Arama terimi filtresi (title ve description'da ara)
        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title_ar', 'like', "%{$searchTerm}%")
                  ->orWhere('title_en', 'like', "%{$searchTerm}%")
                  ->orWhere('description_ar', 'like', "%{$searchTerm}%")
                  ->orWhere('description_en', 'like', "%{$searchTerm}%");
            });
        }

        // Bütçe aralığı filtresi
        if (!empty($filters['min_budget'])) {
            $query->where('budget_min', '>=', $filters['min_budget']);
        }
        if (!empty($filters['max_budget'])) {
            $query->where('budget_max', '<=', $filters['max_budget']);
        }

        // Sıralama (varsayılan: created_at desc)
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        
        // Güvenlik için sadece belirli fieldlara göre sıralamaya izin ver
        $allowedSortFields = ['created_at', 'budget_min', 'budget_max', 'views_count'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        return $query->paginate($perPage);
    }

    /**
     * ID'ye göre servis bulur
     * 
     * @param string $id Servis UUID'si
     * @return Service|null Bulunan servis veya null
     */
    public function findById(string $id): ?Service
    {
        return Service::with(['user', 'category', 'city', 'bids.user'])
            ->where('id', $id)
            ->where('status', 'active')
            ->first();
    }

    /**
     * Yeni servis oluşturur
     * 
     * @param array $data Servis verileri
     * @return Service Oluşturulan servis
     */
    public function createService(array $data): Service
    {
        // Servis durumunu 'pending' olarak ayarla (moderation için)
        $data['status'] = 'pending';
        
        // Slug'ları otomatik oluştur
        $data['slug_ar'] = $this->generateSlug($data['title_ar'] ?? $data['title_en']);
        $data['slug_en'] = $this->generateSlug($data['title_en'] ?? $data['title_ar']);
        
        return Service::create($data);
    }

    /**
     * Servis görüntülenme sayısını artırır
     * 
     * @param Service $service
     * @return void
     */
    public function incrementViewCount(Service $service): void
    {
        // Atomic increment (race condition'ı önlemek için)
        $service->increment('views_count');
        
        // Son görüntülenme zamanını güncelle
        $service->update(['last_viewed_at' => now()]);
    }

    /**
     * Popüler servisleri getirir
     * 
     * Son X gün içinde en çok görüntülenen ve teklif alan servisleri döndürür
     * 
     * @param int $limit Maksimum kayıt sayısı
     * @param int $daysBack Kaç gün geriye dönük bakılacak
     * @return Collection Popüler servisler
     */
    public function getPopularServices(int $limit = 10, int $daysBack = 30): Collection
    {
        $dateFrom = now()->subDays($daysBack);

        return Service::with(['user', 'category', 'city'])
            ->where('status', 'active')
            ->where('created_at', '>=', $dateFrom)
            ->orderByDesc('views_count')
            ->orderByDesc('bids_count') // Teklif sayısına göre de sırala
            ->limit($limit)
            ->get();
    }

    /**
     * Öne çıkarılmış servisleri getirir
     * 
     * @param int $limit Maksimum kayıt sayısı
     * @return Collection Öne çıkarılmış servisler
     */
    public function getFeaturedServices(int $limit = 6): Collection
    {
        return Service::with(['user', 'category', 'city'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Metin'den slug oluşturur
     * 
     * @param string $text
     * @return string
     */
    private function generateSlug(string $text): string
    {
        // Arapça karakterleri transliterate et ve slug'a çevir
        $slug = str_replace([' ', 'أ', 'إ', 'آ'], ['-', 'ا', 'ا', 'ا'], $text);
        $slug = preg_replace('/[^\p{L}\p{N}\-]/u', '', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        return strtolower($slug);
    }
}
