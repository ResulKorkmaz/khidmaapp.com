<?php
/**
 * Provider Lead Service
 * 
 * Provider (usta) panelindeki lead işlemlerini yönetir.
 * Controller'dan bağımsız, tekrar kullanılabilir iş mantığı.
 * 
 * @package KhidmaApp
 * @since 1.0.0
 */

class ProviderLeadService
{
    private $pdo;
    
    public function __construct($pdo = null)
    {
        $this->pdo = $pdo ?? getDatabase();
    }
    
    /**
     * Provider'a teslim edilen lead'leri getir
     */
    public function getDeliveredLeads(int $providerId, array $filters = [], int $limit = 50, int $offset = 0): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $where = ["pld.provider_id = ?"];
            $params = [$providerId];
            
            // Service type filter
            if (!empty($filters['service_type'])) {
                $where[] = "l.service_type = ?";
                $params[] = $filters['service_type'];
            }
            
            // City filter
            if (!empty($filters['city'])) {
                $where[] = "l.city = ?";
                $params[] = $filters['city'];
            }
            
            // Viewed filter
            if (isset($filters['viewed'])) {
                if ($filters['viewed']) {
                    $where[] = "pld.viewed_at IS NOT NULL";
                } else {
                    $where[] = "pld.viewed_at IS NULL";
                }
            }
            
            $whereClause = "WHERE " . implode(" AND ", $where);
            $params[] = $limit;
            $params[] = $offset;
            
            $stmt = $this->pdo->prepare("
                SELECT l.*, 
                       pld.delivered_at, 
                       pld.delivery_method, 
                       pld.viewed_at, 
                       pld.viewed_count
                FROM leads l
                INNER JOIN provider_lead_deliveries pld ON l.id = pld.lead_id
                $whereClause
                ORDER BY pld.delivered_at DESC
                LIMIT ? OFFSET ?
            ");
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ProviderLeadService::getDeliveredLeads error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Provider'a teslim edilen lead sayısı
     */
    public function getDeliveredLeadsCount(int $providerId): int
    {
        if (!$this->pdo) {
            return 0;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM provider_lead_deliveries WHERE provider_id = ?
            ");
            $stmt->execute([$providerId]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("ProviderLeadService::getDeliveredLeadsCount error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Lead'i görüntülendi olarak işaretle
     */
    public function markAsViewed(int $leadId, int $providerId): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            // Önce delivery kaydını kontrol et
            $stmt = $this->pdo->prepare("
                SELECT id, viewed_count FROM provider_lead_deliveries 
                WHERE lead_id = ? AND provider_id = ?
            ");
            $stmt->execute([$leadId, $providerId]);
            $delivery = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$delivery) {
                return false;
            }
            
            // İlk görüntüleme mi?
            $isFirstView = ($delivery['viewed_count'] == 0);
            
            $stmt = $this->pdo->prepare("
                UPDATE provider_lead_deliveries 
                SET viewed_at = COALESCE(viewed_at, NOW()),
                    viewed_count = viewed_count + 1
                WHERE lead_id = ? AND provider_id = ?
            ");
            $stmt->execute([$leadId, $providerId]);
            
            return true;
        } catch (PDOException $e) {
            error_log("ProviderLeadService::markAsViewed error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lead detayını getir (provider için)
     */
    public function getLeadDetail(int $leadId, int $providerId): ?array
    {
        if (!$this->pdo) {
            return null;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT l.*, pld.delivered_at, pld.viewed_at, pld.viewed_count
                FROM leads l
                INNER JOIN provider_lead_deliveries pld ON l.id = pld.lead_id
                WHERE l.id = ? AND pld.provider_id = ?
            ");
            $stmt->execute([$leadId, $providerId]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("ProviderLeadService::getLeadDetail error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Lead talep et
     */
    public function requestLead(int $providerId, ?string $notes = null): ?int
    {
        if (!$this->pdo) {
            return null;
        }
        
        try {
            // Provider bilgisini al
            $stmt = $this->pdo->prepare("SELECT service_type, city FROM service_providers WHERE id = ?");
            $stmt->execute([$providerId]);
            $provider = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$provider) {
                return null;
            }
            
            // Aktif paketi kontrol et
            $stmt = $this->pdo->prepare("
                SELECT id, remaining_leads FROM provider_purchases 
                WHERE provider_id = ? 
                AND payment_status = 'completed' 
                AND remaining_leads > 0
                LIMIT 1
            ");
            $stmt->execute([$providerId]);
            $purchase = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$purchase) {
                return null; // Aktif paket yok
            }
            
            // Talep oluştur
            $stmt = $this->pdo->prepare("
                INSERT INTO lead_requests 
                (provider_id, service_type, city, notes, status, created_at)
                VALUES (?, ?, ?, ?, 'pending', NOW())
            ");
            $stmt->execute([
                $providerId,
                $provider['service_type'],
                $provider['city'],
                $notes
            ]);
            
            return (int) $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("ProviderLeadService::requestLead error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Provider'ın lead taleplerini getir
     */
    public function getMyRequests(int $providerId, int $limit = 20): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT lr.*, l.customer_name, l.phone, l.service_type as lead_service_type
                FROM lead_requests lr
                LEFT JOIN leads l ON lr.lead_id = l.id
                WHERE lr.provider_id = ?
                ORDER BY lr.created_at DESC
                LIMIT ?
            ");
            $stmt->execute([$providerId, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ProviderLeadService::getMyRequests error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Bekleyen talep sayısı
     */
    public function getPendingRequestsCount(int $providerId): int
    {
        if (!$this->pdo) {
            return 0;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM lead_requests 
                WHERE provider_id = ? AND status = 'pending'
            ");
            $stmt->execute([$providerId]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("ProviderLeadService::getPendingRequestsCount error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Lead'i gizle
     */
    public function hideLead(int $leadId, int $providerId): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO provider_hidden_leads (provider_id, lead_id, hidden_at)
                VALUES (?, ?, NOW())
                ON DUPLICATE KEY UPDATE hidden_at = NOW()
            ");
            return $stmt->execute([$providerId, $leadId]);
        } catch (PDOException $e) {
            error_log("ProviderLeadService::hideLead error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Gizli lead'leri getir
     */
    public function getHiddenLeads(int $providerId, int $limit = 50): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT l.*, phl.hidden_at
                FROM leads l
                INNER JOIN provider_hidden_leads phl ON l.id = phl.lead_id
                WHERE phl.provider_id = ?
                ORDER BY phl.hidden_at DESC
                LIMIT ?
            ");
            $stmt->execute([$providerId, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ProviderLeadService::getHiddenLeads error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lead gizliliğini kaldır
     */
    public function unhideLead(int $leadId, int $providerId): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                DELETE FROM provider_hidden_leads 
                WHERE lead_id = ? AND provider_id = ?
            ");
            return $stmt->execute([$leadId, $providerId]);
        } catch (PDOException $e) {
            error_log("ProviderLeadService::unhideLead error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Provider için lead istatistikleri
     */
    public function getStats(int $providerId): array
    {
        if (!$this->pdo) {
            return $this->getEmptyStats();
        }
        
        try {
            $stats = [];
            
            // Toplam teslim edilen
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM provider_lead_deliveries WHERE provider_id = ?");
            $stmt->execute([$providerId]);
            $stats['total_delivered'] = (int) $stmt->fetchColumn();
            
            // Görüntülenen
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM provider_lead_deliveries WHERE provider_id = ? AND viewed_at IS NOT NULL");
            $stmt->execute([$providerId]);
            $stats['viewed'] = (int) $stmt->fetchColumn();
            
            // Görüntülenmemiş
            $stats['not_viewed'] = $stats['total_delivered'] - $stats['viewed'];
            
            // Bu ay teslim edilen
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM provider_lead_deliveries 
                WHERE provider_id = ? AND delivered_at > DATE_SUB(NOW(), INTERVAL 30 DAY)
            ");
            $stmt->execute([$providerId]);
            $stats['this_month'] = (int) $stmt->fetchColumn();
            
            // Bekleyen talepler
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM lead_requests WHERE provider_id = ? AND status = 'pending'");
            $stmt->execute([$providerId]);
            $stats['pending_requests'] = (int) $stmt->fetchColumn();
            
            return $stats;
        } catch (PDOException $e) {
            error_log("ProviderLeadService::getStats error: " . $e->getMessage());
            return $this->getEmptyStats();
        }
    }
    
    private function getEmptyStats(): array
    {
        return [
            'total_delivered' => 0,
            'viewed' => 0,
            'not_viewed' => 0,
            'this_month' => 0,
            'pending_requests' => 0
        ];
    }
}

