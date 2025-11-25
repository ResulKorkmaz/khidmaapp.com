<?php

/**
 * KhidmaApp.com - Admin Provider Controller
 * 
 * Hizmet sağlayıcı (usta) yönetimi
 */

require_once __DIR__ . '/BaseAdminController.php';

class AdminProviderController extends BaseAdminController 
{
    /**
     * Providers listesi
     */
    public function index(): void
    {
        $this->requireAuth();
        
        // Filtreleme parametreleri
        $searchQuery = $this->sanitizedGet('search');
        $serviceTypeFilter = $this->sanitizedGet('service_type');
        $statusFilter = $this->sanitizedGet('status');
        $cityFilter = $this->sanitizedGet('city');
        $page = max(1, $this->intGet('page', 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        // Toplam usta sayısı
        $totalProviders = $this->getProvidersCount($searchQuery, $serviceTypeFilter, $statusFilter, $cityFilter);
        
        // Ustaları getir
        $providers = $this->getProviders($searchQuery, $serviceTypeFilter, $statusFilter, $cityFilter, $perPage, $offset);
        
        // Pagination hesapla
        $totalPages = ceil($totalProviders / $perPage);
        
        // Status istatistikleri
        $stats = [
            'total' => $totalProviders,
            'active' => $this->getProvidersCountByStatus('active'),
            'pending' => $this->getProvidersCountByStatus('pending'),
            'suspended' => $this->getProvidersCountByStatus('suspended'),
            'rejected' => $this->getProvidersCountByStatus('rejected')
        ];
        
        $this->render('providers', [
            'providers' => $providers,
            'stats' => $stats,
            'searchQuery' => $searchQuery,
            'serviceTypeFilter' => $serviceTypeFilter,
            'statusFilter' => $statusFilter,
            'cityFilter' => $cityFilter,
            'page' => $page,
            'perPage' => $perPage,
            'totalProviders' => $totalProviders,
            'totalPages' => $totalPages,
            'currentPage' => 'providers',
            'pageTitle' => 'Ustalar Yönetimi'
        ]);
    }
    
    /**
     * Provider detayları
     */
    public function detail(): void
    {
        $this->requireAuth();
        
        // URL'den provider ID al
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $parts = explode('/', $uri);
        $providerId = isset($parts[2]) ? intval($parts[2]) : 0;
        
        if ($providerId <= 0) {
            $_SESSION['error'] = 'Geçersiz usta ID';
            $this->redirect('/admin/providers');
        }
        
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM service_providers WHERE id = ?");
            $stmt->execute([$providerId]);
            $provider = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$provider) {
                $_SESSION['error'] = 'Usta bulunamadı';
                $this->redirect('/admin/providers');
            }
            
            // Provider'ın satın almaları
            $purchases = $this->getProviderPurchases($providerId);
            
            // Provider'a gönderilen lead'ler
            $deliveredLeads = $this->getProviderDeliveredLeads($providerId);
            
            $this->render('provider_detail', [
                'provider' => $provider,
                'purchases' => $purchases,
                'deliveredLeads' => $deliveredLeads,
                'pageTitle' => 'Usta Detayı - ' . $provider['name'],
                'currentPage' => 'providers'
            ]);
            
        } catch (PDOException $e) {
            error_log("Provider detail error: " . $e->getMessage());
            $_SESSION['error'] = 'Veritabanı hatası';
            $this->redirect('/admin/providers');
        }
    }
    
    /**
     * Provider'ı onayla
     */
    public function approve(): void
    {
        $this->requireAuth();
        
        $data = $this->getJsonInput();
        $providerId = intval($data['provider_id'] ?? 0);
        
        if ($providerId <= 0) {
            $this->errorResponse('Geçersiz usta ID', 400);
        }
        
        try {
            $stmt = $this->pdo->prepare("
                UPDATE service_providers 
                SET status = 'active', is_verified = TRUE, updated_at = NOW()
                WHERE id = ?
            ");
            
            $success = $stmt->execute([$providerId]);
            
            if ($success && $stmt->rowCount() > 0) {
                error_log("Admin approved provider ID: $providerId");
                $this->successResponse('Usta başarıyla onaylandı');
            } else {
                $this->errorResponse('Usta bulunamadı veya zaten onaylı', 404);
            }
        } catch (PDOException $e) {
            error_log("Approve provider error: " . $e->getMessage());
            $this->errorResponse('Veritabanı hatası', 500);
        }
    }
    
    /**
     * Provider durumunu değiştir
     */
    public function changeStatus(): void
    {
        $this->requireAuth();
        
        $data = $this->getJsonInput();
        $providerId = intval($data['provider_id'] ?? 0);
        $newStatus = $data['new_status'] ?? '';
        
        if ($providerId <= 0) {
            $this->errorResponse('Geçersiz usta ID', 400);
        }
        
        $validStatuses = ['active', 'pending', 'suspended', 'rejected'];
        if (!in_array($newStatus, $validStatuses)) {
            $this->errorResponse('Geçersiz durum', 400);
        }
        
        try {
            $isVerified = ($newStatus === 'active') ? 1 : 0;
            
            $stmt = $this->pdo->prepare("
                UPDATE service_providers 
                SET status = ?, is_verified = ?, updated_at = NOW()
                WHERE id = ?
            ");
            
            $success = $stmt->execute([$newStatus, $isVerified, $providerId]);
            
            if ($success && $stmt->rowCount() > 0) {
                error_log("Admin changed provider ID $providerId status to: $newStatus");
                $this->successResponse('Usta durumu güncellendi', ['new_status' => $newStatus]);
            } else {
                $this->errorResponse('Usta bulunamadı veya durum zaten aynı', 404);
            }
        } catch (PDOException $e) {
            error_log("Change provider status error: " . $e->getMessage());
            $this->errorResponse('Veritabanı hatası', 500);
        }
    }
    
    /**
     * Provider'ı sil
     */
    public function delete(): void
    {
        $this->requireAuth();
        
        $data = $this->getJsonInput();
        $providerId = intval($data['provider_id'] ?? 0);
        
        if ($providerId <= 0) {
            $this->errorResponse('Geçersiz usta ID', 400);
        }
        
        try {
            // Önce ilişkili kayıtları kontrol et
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM provider_purchases WHERE provider_id = ?");
            $stmt->execute([$providerId]);
            $purchaseCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            if ($purchaseCount > 0) {
                $this->errorResponse('Bu ustanın satın almaları var, silinemez', 400);
            }
            
            // Provider'ı sil
            $stmt = $this->pdo->prepare("DELETE FROM service_providers WHERE id = ?");
            $success = $stmt->execute([$providerId]);
            
            if ($success && $stmt->rowCount() > 0) {
                error_log("Admin deleted provider ID: $providerId");
                $this->successResponse('Usta başarıyla silindi');
            } else {
                $this->errorResponse('Usta bulunamadı', 404);
            }
        } catch (PDOException $e) {
            error_log("Delete provider error: " . $e->getMessage());
            $this->errorResponse('Veritabanı hatası', 500);
        }
    }
    
    /**
     * Provider arama (AJAX)
     */
    public function search(): void
    {
        $this->requireAuth();
        
        $query = $this->sanitizedGet('q');
        
        if (strlen($query) < 2) {
            $this->errorResponse('En az 2 karakter gerekli', 400);
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT sp.*,
                       CASE 
                           WHEN sp.service_type = 'paint' THEN 'Boya Badana'
                           WHEN sp.service_type = 'renovation' THEN 'Tadilat'
                           WHEN sp.service_type = 'cleaning' THEN 'Temizlik'
                           WHEN sp.service_type = 'ac' THEN 'Klima'
                           WHEN sp.service_type = 'plumbing' THEN 'Sıhhi Tesisat'
                           WHEN sp.service_type = 'electric' THEN 'Elektrik'
                           ELSE sp.service_type
                       END as service_type_tr
                FROM service_providers sp
                WHERE (sp.phone LIKE ? OR sp.id = ? OR sp.name LIKE ?)
                AND sp.status IN ('active', 'pending')
                ORDER BY sp.status = 'active' DESC, sp.created_at DESC
                LIMIT 10
            ");
            
            $searchTerm = "%{$query}%";
            $idSearch = is_numeric($query) ? intval($query) : 0;
            
            $stmt->execute([$searchTerm, $idSearch, $searchTerm]);
            $providers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $this->successResponse('Arama sonuçları', ['providers' => $providers]);
        } catch (PDOException $e) {
            error_log("Search providers error: " . $e->getMessage());
            $this->errorResponse('Arama sırasında hata oluştu', 500);
        }
    }
    
    // ==================== PRIVATE METHODS ====================
    
    /**
     * JSON input al
     */
    private function getJsonInput(): array
    {
        $data = $GLOBALS['_JSON_INPUT'] ?? [];
        
        if (empty($data)) {
            $jsonInput = file_get_contents('php://input');
            $data = json_decode($jsonInput, true) ?? [];
        }
        
        return $data;
    }
    
    /**
     * Usta sayısını getir
     */
    private function getProvidersCount(string $search = '', string $serviceType = '', string $status = '', string $city = ''): int
    {
        $sql = "SELECT COUNT(*) as count FROM service_providers WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)";
            $searchTerm = '%' . $search . '%';
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
        }
        
        if (!empty($serviceType)) {
            $sql .= " AND service_type = ?";
            $params[] = $serviceType;
        }
        
        if (!empty($status)) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }
        
        if (!empty($city)) {
            $sql .= " AND city = ?";
            $params[] = $city;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
    }
    
    /**
     * Ustaları getir
     */
    private function getProviders(string $search = '', string $serviceType = '', string $status = '', string $city = '', int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT * FROM service_providers WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)";
            $searchTerm = '%' . $search . '%';
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
        }
        
        if (!empty($serviceType)) {
            $sql .= " AND service_type = ?";
            $params[] = $serviceType;
        }
        
        if (!empty($status)) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }
        
        if (!empty($city)) {
            $sql .= " AND city = ?";
            $params[] = $city;
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Status'a göre usta sayısı
     */
    private function getProvidersCountByStatus(string $status): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM service_providers WHERE status = ?");
        $stmt->execute([$status]);
        return (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
    }
    
    /**
     * Provider'ın satın almalarını getir
     */
    private function getProviderPurchases(int $providerId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT pp.*, lp.name as package_name,
                   (SELECT COUNT(*) FROM provider_lead_deliveries WHERE purchase_id = pp.id) as delivered_count
            FROM provider_purchases pp
            LEFT JOIN lead_packages lp ON pp.package_id = lp.id
            WHERE pp.provider_id = ?
            ORDER BY pp.created_at DESC
        ");
        $stmt->execute([$providerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Provider'a gönderilen lead'leri getir
     */
    private function getProviderDeliveredLeads(int $providerId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT l.*, pld.delivered_at, pld.viewed_at, pld.delivery_method
            FROM provider_lead_deliveries pld
            JOIN leads l ON pld.lead_id = l.id
            WHERE pld.provider_id = ?
            ORDER BY pld.delivered_at DESC
            LIMIT 50
        ");
        $stmt->execute([$providerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

