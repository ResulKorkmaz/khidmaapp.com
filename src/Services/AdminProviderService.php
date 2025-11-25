<?php
/**
 * Admin Provider Service
 * 
 * Admin panelindeki provider (usta) işlemlerini yönetir.
 * Controller'dan bağımsız, tekrar kullanılabilir iş mantığı.
 * 
 * @package KhidmaApp
 * @since 1.0.0
 */

class AdminProviderService
{
    private $pdo;
    
    public function __construct($pdo = null)
    {
        $this->pdo = $pdo ?? getDatabase();
    }
    
    /**
     * Provider istatistiklerini getir
     */
    public function getStats(): array
    {
        if (!$this->pdo) {
            return $this->getEmptyStats();
        }
        
        try {
            // Toplam provider
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM service_providers");
            $total = $stmt->fetchColumn();
            
            // Onay bekleyen
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM service_providers WHERE status = 'pending'");
            $pending = $stmt->fetchColumn();
            
            // Onaylı
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM service_providers WHERE status = 'approved'");
            $approved = $stmt->fetchColumn();
            
            // Reddedilmiş
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM service_providers WHERE status = 'rejected'");
            $rejected = $stmt->fetchColumn();
            
            // Aktif (son 30 gün giriş yapmış)
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM service_providers WHERE last_login > DATE_SUB(NOW(), INTERVAL 30 DAY)");
            $active = $stmt->fetchColumn();
            
            return [
                'total' => (int) $total,
                'pending' => (int) $pending,
                'approved' => (int) $approved,
                'rejected' => (int) $rejected,
                'active' => (int) $active
            ];
        } catch (PDOException $e) {
            error_log("AdminProviderService::getStats error: " . $e->getMessage());
            return $this->getEmptyStats();
        }
    }
    
    /**
     * Boş istatistik array'i
     */
    private function getEmptyStats(): array
    {
        return [
            'total' => 0,
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'active' => 0
        ];
    }
    
    /**
     * Provider'ları filtreli getir
     */
    public function getProviders(array $filters = [], int $limit = 20, int $offset = 0): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $where = [];
            $params = [];
            
            // Arama
            if (!empty($filters['search'])) {
                $search = '%' . $filters['search'] . '%';
                $where[] = "(name LIKE ? OR email LIKE ? OR phone LIKE ?)";
                $params[] = $search;
                $params[] = $search;
                $params[] = $search;
            }
            
            // Service type
            if (!empty($filters['service_type'])) {
                $where[] = "service_type = ?";
                $params[] = $filters['service_type'];
            }
            
            // Status
            if (!empty($filters['status'])) {
                $where[] = "status = ?";
                $params[] = $filters['status'];
            }
            
            // City
            if (!empty($filters['city'])) {
                $where[] = "city = ?";
                $params[] = $filters['city'];
            }
            
            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
            
            $sql = "SELECT * FROM service_providers 
                    $whereClause 
                    ORDER BY created_at DESC 
                    LIMIT ? OFFSET ?";
            
            $params[] = $limit;
            $params[] = $offset;
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("AdminProviderService::getProviders error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Provider sayısını getir (filtreli)
     */
    public function getProvidersCount(array $filters = []): int
    {
        if (!$this->pdo) {
            return 0;
        }
        
        try {
            $where = [];
            $params = [];
            
            if (!empty($filters['search'])) {
                $search = '%' . $filters['search'] . '%';
                $where[] = "(name LIKE ? OR email LIKE ? OR phone LIKE ?)";
                $params[] = $search;
                $params[] = $search;
                $params[] = $search;
            }
            
            if (!empty($filters['service_type'])) {
                $where[] = "service_type = ?";
                $params[] = $filters['service_type'];
            }
            
            if (!empty($filters['status'])) {
                $where[] = "status = ?";
                $params[] = $filters['status'];
            }
            
            if (!empty($filters['city'])) {
                $where[] = "city = ?";
                $params[] = $filters['city'];
            }
            
            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
            
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM service_providers $whereClause");
            $stmt->execute($params);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("AdminProviderService::getProvidersCount error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Provider detayını getir
     */
    public function getProviderById(int $id): ?array
    {
        if (!$this->pdo) {
            return null;
        }
        
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM service_providers WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("AdminProviderService::getProviderById error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Provider'ı onayla
     */
    public function approve(int $id): bool
    {
        return $this->updateStatus($id, 'approved', true);
    }
    
    /**
     * Provider'ı reddet
     */
    public function reject(int $id): bool
    {
        return $this->updateStatus($id, 'rejected', false);
    }
    
    /**
     * Provider durumunu güncelle
     */
    public function updateStatus(int $id, string $status, bool $isActive = null): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            $validStatuses = ['pending', 'approved', 'rejected', 'suspended'];
            if (!in_array($status, $validStatuses)) {
                return false;
            }
            
            if ($isActive !== null) {
                $stmt = $this->pdo->prepare("
                    UPDATE service_providers 
                    SET status = ?, is_active = ?
                    WHERE id = ?
                ");
                return $stmt->execute([$status, $isActive ? 1 : 0, $id]);
            } else {
                $stmt = $this->pdo->prepare("
                    UPDATE service_providers 
                    SET status = ?
                    WHERE id = ?
                ");
                return $stmt->execute([$status, $id]);
            }
        } catch (PDOException $e) {
            error_log("AdminProviderService::updateStatus error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Provider'ı sil
     */
    public function delete(int $id): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            $this->pdo->beginTransaction();
            
            // İlişkili kayıtları sil
            $this->pdo->prepare("DELETE FROM provider_lead_deliveries WHERE provider_id = ?")->execute([$id]);
            $this->pdo->prepare("DELETE FROM provider_purchases WHERE provider_id = ?")->execute([$id]);
            $this->pdo->prepare("DELETE FROM provider_messages WHERE provider_id = ?")->execute([$id]);
            $this->pdo->prepare("DELETE FROM lead_requests WHERE provider_id = ?")->execute([$id]);
            $this->pdo->prepare("DELETE FROM provider_hidden_leads WHERE provider_id = ?")->execute([$id]);
            
            // Provider'ı sil
            $stmt = $this->pdo->prepare("DELETE FROM service_providers WHERE id = ?");
            $stmt->execute([$id]);
            
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("AdminProviderService::delete error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Provider'ın satın alımlarını getir
     */
    public function getProviderPurchases(int $providerId): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT pp.*, lp.name_ar, lp.name_tr
                FROM provider_purchases pp
                LEFT JOIN lead_packages lp ON pp.package_id = lp.id
                WHERE pp.provider_id = ?
                ORDER BY pp.purchased_at DESC
            ");
            $stmt->execute([$providerId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("AdminProviderService::getProviderPurchases error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Provider'a teslim edilen lead'leri getir
     */
    public function getProviderDeliveredLeads(int $providerId): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT l.*, pld.delivered_at, pld.delivery_method, pld.viewed_at, pld.viewed_count
                FROM leads l
                INNER JOIN provider_lead_deliveries pld ON l.id = pld.lead_id
                WHERE pld.provider_id = ?
                ORDER BY pld.delivered_at DESC
            ");
            $stmt->execute([$providerId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("AdminProviderService::getProviderDeliveredLeads error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lead için uygun provider'ları getir
     */
    public function getAvailableProvidersForLead(int $leadId): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            // Lead bilgisini al
            $stmt = $this->pdo->prepare("SELECT service_type, city FROM leads WHERE id = ?");
            $stmt->execute([$leadId]);
            $lead = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$lead) {
                return [];
            }
            
            // Uygun provider'ları getir
            $stmt = $this->pdo->prepare("
                SELECT sp.*, 
                       COALESCE(SUM(pp.remaining_leads), 0) as total_remaining_leads
                FROM service_providers sp
                LEFT JOIN provider_purchases pp ON sp.id = pp.provider_id 
                    AND pp.payment_status = 'completed' 
                    AND pp.remaining_leads > 0
                WHERE sp.service_type = ?
                AND sp.status = 'approved'
                AND sp.is_active = 1
                GROUP BY sp.id
                HAVING total_remaining_leads > 0
                ORDER BY total_remaining_leads DESC
            ");
            $stmt->execute([$lead['service_type']]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("AdminProviderService::getAvailableProvidersForLead error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Provider ara (autocomplete için)
     */
    public function searchProviders(string $query, ?string $serviceType = null, int $limit = 10): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $where = ["(name LIKE ? OR phone LIKE ? OR email LIKE ?)"];
            $params = ['%' . $query . '%', '%' . $query . '%', '%' . $query . '%'];
            
            $where[] = "status = 'approved'";
            $where[] = "is_active = 1";
            
            if ($serviceType) {
                $where[] = "service_type = ?";
                $params[] = $serviceType;
            }
            
            $whereClause = "WHERE " . implode(" AND ", $where);
            $params[] = $limit;
            
            $stmt = $this->pdo->prepare("
                SELECT id, name, phone, email, service_type, city 
                FROM service_providers 
                $whereClause 
                LIMIT ?
            ");
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("AdminProviderService::searchProviders error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Provider'a mesaj gönder
     */
    public function sendMessage(int $providerId, string $subject, string $message, int $adminId): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO provider_messages 
                (provider_id, admin_id, subject, message, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ");
            return $stmt->execute([$providerId, $adminId, $subject, $message]);
        } catch (PDOException $e) {
            error_log("AdminProviderService::sendMessage error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Provider mesaj geçmişini getir
     */
    public function getMessageHistory(int $providerId): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT pm.*, a.username as admin_name
                FROM provider_messages pm
                LEFT JOIN admins a ON pm.admin_id = a.id
                WHERE pm.provider_id = ?
                ORDER BY pm.created_at DESC
            ");
            $stmt->execute([$providerId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("AdminProviderService::getMessageHistory error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Status'a göre provider sayılarını getir
     */
    public function getCountsByStatus(): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $stmt = $this->pdo->query("
                SELECT status, COUNT(*) as count 
                FROM service_providers 
                GROUP BY status
            ");
            
            $counts = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $counts[$row['status']] = (int) $row['count'];
            }
            
            return $counts;
        } catch (PDOException $e) {
            error_log("AdminProviderService::getCountsByStatus error: " . $e->getMessage());
            return [];
        }
    }
}

