<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Service\ServiceIndexRequest;
use App\Http\Requests\Service\ServiceStoreRequest;
use App\Http\Resources\ServiceResource;
use App\Services\ServiceService;
use Illuminate\Http\JsonResponse;

/**
 * Servis işlemlerini yöneten API controller sınıfı
 * 
 * Bu controller servis CRUD işlemlerini ve arama/filtreleme
 * işlevlerini yönetir. Tüm response'lar JSON formatında döner.
 */
class ServiceController extends BaseController
{
    /**
     * Servis işlemlerini yürüten service class
     */
    private ServiceService $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    /**
     * Servisleri sayfalı olarak listeler
     * 
     * Şehir, kategori ve arama terimine göre filtreleme yapar.
     * Sonuçları sayfalı format halinde döndürür.
     * 
     * @param ServiceIndexRequest $request Filtreleme ve sayfalama parametreleri
     * @return JsonResponse Servis listesi ve pagination bilgisi
     */
    public function index(ServiceIndexRequest $request): JsonResponse
    {
        try {
            // Validate edilmiş parametreleri al
            $filters = $request->validated();
            
            // Sayfa başına gösterilecek kayıt sayısı (varsayılan: 15)
            $perPage = $filters['per_page'] ?? 15;
            
            // Servisleri filtrele ve sayfalı getir
            $services = $this->serviceService->getFilteredServices($filters, $perPage);
            
            // Başarılı response döndür
            return $this->success(
                data: [
                    'services' => ServiceResource::collection($services->items()),
                    'pagination' => [
                        'current_page' => $services->currentPage(),
                        'last_page' => $services->lastPage(),
                        'per_page' => $services->perPage(),
                        'total' => $services->total(),
                    ]
                ],
                message: 'Servisler başarıyla getirildi'
            );

        } catch (\Exception $e) {
            // Hata durumunda log'la ve error response döndür
            logger()->error('Servis listesi getirme hatası: ' . $e->getMessage(), [
                'filters' => $filters,
                'user_id' => auth()->id(),
            ]);

            return $this->error(
                message: 'Servisler getirilemedi',
                code: 500
            );
        }
    }

    /**
     * Tek bir servisi detayları ile getirir
     * 
     * @param string $id Servis UUID'si
     * @return JsonResponse Servis detay bilgileri
     */
    public function show(string $id): JsonResponse
    {
        try {
            // Servisi ID ile bul (bulunamazsa 404 fırlat)
            $service = $this->serviceService->findById($id);
            
            if (!$service) {
                return $this->error(
                    message: 'Servis bulunamadı',
                    code: 404
                );
            }

            // Servis görüntülenme sayısını artır
            $this->serviceService->incrementViewCount($service);

            return $this->success(
                data: new ServiceResource($service),
                message: 'Servis detayları getirildi'
            );

        } catch (\Exception $e) {
            logger()->error('Servis detay getirme hatası: ' . $e->getMessage(), [
                'service_id' => $id,
                'user_id' => auth()->id(),
            ]);

            return $this->error(
                message: 'Servis detayları getirilemedi',
                code: 500
            );
        }
    }

    /**
     * Yeni servis oluşturur
     * 
     * Sadece authenticated kullanıcılar servis oluşturabilir.
     * Oluşturulan servis moderation queue'ya alınır.
     * 
     * @param ServiceStoreRequest $request Servis verileri
     * @return JsonResponse Oluşturulan servis bilgileri
     */
    public function store(ServiceStoreRequest $request): JsonResponse
    {
        try {
            // Validate edilmiş verileri al
            $validatedData = $request->validated();
            
            // Current user'ı servise ata
            $validatedData['user_id'] = auth()->id();
            
            // Yeni servis oluştur
            $service = $this->serviceService->createService($validatedData);
            
            // Admin panel'e bildirim gönder (moderation için)
            // $this->notificationService->notifyModerators($service);

            return $this->success(
                data: new ServiceResource($service),
                message: 'Servis başarıyla oluşturuldu ve onay için gönderildi',
                code: 201
            );

        } catch (\Exception $e) {
            logger()->error('Servis oluşturma hatası: ' . $e->getMessage(), [
                'data' => $request->except(['photos']), // Foto verilerini log'lama
                'user_id' => auth()->id(),
            ]);

            return $this->error(
                message: 'Servis oluşturulamadı',
                code: 500
            );
        }
    }

    /**
     * Popüler servisleri getirir
     * 
     * Son 30 gün içinde en çok görüntülenen ve teklif alan
     * servisleri listeler. Ana sayfa ve kategori sayfalarında kullanılır.
     * 
     * @return JsonResponse Popüler servis listesi
     */
    public function popular(): JsonResponse
    {
        try {
            // Son 30 gün içindeki popüler servisleri getir
            $popularServices = $this->serviceService->getPopularServices(
                limit: 10,
                daysBack: 30
            );

            return $this->success(
                data: ServiceResource::collection($popularServices),
                message: 'Popüler servisler getirildi'
            );

        } catch (\Exception $e) {
            logger()->error('Popüler servisler getirme hatası: ' . $e->getMessage());

            return $this->error(
                message: 'Popüler servisler getirilemedi',
                code: 500
            );
        }
    }
}
