<?php
/**
 * Admin Lead Service
 * 
 * Admin panelindeki lead işlemlerini yönetir.
 * Controller'dan bağımsız, tekrar kullanılabilir iş mantığı.
 * 
 * @package KhidmaApp
 * @since 1.0.0
 */

class AdminLeadService
{
    private $pdo;
    
    public function __construct($pdo = null)
    {
        $this->pdo = $pdo ?? getDatabase();
    }
    
    /**
     * Lead istatistiklerini getir
     */
    public function getStats(): array
    {
        if (!$this->pdo) {
            return $this->getEmptyStats();
        }
        
        try {
            // Toplam lead sayısı (silinmemiş)
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM leads WHERE deleted_at IS NULL");
            $totalLeads = $stmt->fetchColumn();
            
            // Yeni lead'ler (son 24 saat)
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM leads WHERE created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR) AND deleted_at IS NULL");
            $newLeads = $stmt->fetchColumn();
            
            // Bekleyen lead'ler
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM leads WHERE status = 'pending' AND deleted_at IS NULL");
            $pendingLeads = $stmt->fetchColumn();
            
            // Satılan lead'ler
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM leads WHERE status = 'sold' AND deleted_at IS NULL");
            $soldLeads = $stmt->fetchColumn();
            
            // Gönderilmemiş lead'ler
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM leads WHERE is_sent_to_provider = 0 AND deleted_at IS NULL AND status != 'invalid'");
            $unsentLeads = $stmt->fetchColumn();
            
            // Geçersiz lead'ler
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM leads WHERE status = 'invalid' AND deleted_at IS NULL");
            $invalidLeads = $stmt->fetchColumn();
            
            // Silinmiş lead'ler (çöp kutusu)
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM leads WHERE deleted_at IS NOT NULL");
            $deletedLeads = $stmt->fetchColumn();
            
            return [
                'total_leads' => (int) $totalLeads,
                'new_leads' => (int) $newLeads,
                'pending_leads' => (int) $pendingLeads,
                'sold_leads' => (int) $soldLeads,
                'unsent_leads' => (int) $unsentLeads,
                'invalid_leads' => (int) $invalidLeads,
                'deleted_leads' => (int) $deletedLeads
            ];
        } catch (PDOException $e) {
            error_log("AdminLeadService::getStats error: " . $e->getMessage());
            return $this->getEmptyStats();
        }
    }
    
    /**
     * Boş istatistik array'i
     */
    private function getEmptyStats(): array
    {
        return [
            'total_leads' => 0,
            'new_leads' => 0,
            'pending_leads' => 0,
            'sold_leads' => 0,
            'unsent_leads' => 0,
            'invalid_leads' => 0,
            'deleted_leads' => 0
        ];
    }
    
    /**
     * Son lead'leri getir
     */
    public function getRecentLeads(int $limit = 10): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM leads 
                WHERE deleted_at IS NULL 
                ORDER BY created_at DESC 
                LIMIT ?
            ");
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("AdminLeadService::getRecentLeads error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lead'leri filtreli getir
     */
    public function getLeads(array $filters = [], int $limit = 20, int $offset = 0): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $where = [];
            $params = [];
            
            // Status filtresi
            $status = $filters['status'] ?? 'all';
            $isDeleted = ($status === 'deleted');
            
            // Silme durumu
            if ($isDeleted) {
                $where[] = "l.deleted_at IS NOT NULL";
            } else {
                $where[] = "l.deleted_at IS NULL";
            }
            
            // Status (deleted hariç)
            if ($status !== 'all' && !$isDeleted) {
                $where[] = "l.status = ?";
                $params[] = $status;
            }
            
            // Service type
            if (!empty($filters['service_type']) && $filters['service_type'] !== 'all') {
                $where[] = "l.service_type = ?";
                $params[] = $filters['service_type'];
            }
            
            // City
            if (!empty($filters['city']) && $filters['city'] !== 'all') {
                $where[] = "l.city = ?";
                $params[] = $filters['city'];
            }
            
            // Sent filter
            if (!empty($filters['sent_filter'])) {
                if ($filters['sent_filter'] === 'sent') {
                    $where[] = "l.is_sent_to_provider = 1";
                } elseif ($filters['sent_filter'] === 'unsent') {
                    $where[] = "l.is_sent_to_provider = 0";
                }
            }
            
            // Date range
            if (!empty($filters['date_from'])) {
                $where[] = "DATE(l.created_at) >= ?";
                $params[] = $filters['date_from'];
            }
            if (!empty($filters['date_to'])) {
                $where[] = "DATE(l.created_at) <= ?";
                $params[] = $filters['date_to'];
            }
            
            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
            
            // Query oluştur
            if ($isDeleted) {
                $sql = "SELECT l.*, 
                               DATEDIFF(DATE_ADD(l.deleted_at, INTERVAL 30 DAY), NOW()) as days_left
                        FROM leads l
                        $whereClause 
                        ORDER BY l.deleted_at DESC 
                        LIMIT ? OFFSET ?";
            } elseif ($status === 'sold') {
                $sql = "SELECT l.*, 
                               sp.id as provider_id,
                               sp.name as provider_name, 
                               sp.phone as provider_phone,
                               pld.delivery_method,
                               pld.delivered_at,
                               pld.viewed_at,
                               pld.viewed_count
                        FROM leads l
                        LEFT JOIN provider_lead_deliveries pld ON l.id = pld.lead_id
                        LEFT JOIN service_providers sp ON pld.provider_id = sp.id
                        $whereClause 
                        ORDER BY COALESCE(l.sent_at, l.updated_at, l.created_at) DESC 
                        LIMIT ? OFFSET ?";
            } else {
                $sql = "SELECT l.* FROM leads l 
                        $whereClause 
                        ORDER BY l.is_sent_to_provider ASC, 
                                 COALESCE(l.sent_at, l.created_at) DESC 
                        LIMIT ? OFFSET ?";
            }
            
            $params[] = $limit;
            $params[] = $offset;
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("AdminLeadService::getLeads error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lead sayısını getir (filtreli)
     */
    public function getLeadsCount(array $filters = []): int
    {
        if (!$this->pdo) {
            return 0;
        }
        
        try {
            $where = [];
            $params = [];
            
            $status = $filters['status'] ?? 'all';
            $isDeleted = ($status === 'deleted');
            
            if ($isDeleted) {
                $where[] = "deleted_at IS NOT NULL";
            } else {
                $where[] = "deleted_at IS NULL";
            }
            
            if ($status !== 'all' && !$isDeleted) {
                $where[] = "status = ?";
                $params[] = $status;
            }
            
            if (!empty($filters['service_type']) && $filters['service_type'] !== 'all') {
                $where[] = "service_type = ?";
                $params[] = $filters['service_type'];
            }
            
            if (!empty($filters['city']) && $filters['city'] !== 'all') {
                $where[] = "city = ?";
                $params[] = $filters['city'];
            }
            
            if (!empty($filters['sent_filter'])) {
                if ($filters['sent_filter'] === 'sent') {
                    $where[] = "is_sent_to_provider = 1";
                } elseif ($filters['sent_filter'] === 'unsent') {
                    $where[] = "is_sent_to_provider = 0";
                }
            }
            
            if (!empty($filters['date_from'])) {
                $where[] = "DATE(created_at) >= ?";
                $params[] = $filters['date_from'];
            }
            if (!empty($filters['date_to'])) {
                $where[] = "DATE(created_at) <= ?";
                $params[] = $filters['date_to'];
            }
            
            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
            
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM leads $whereClause");
            $stmt->execute($params);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("AdminLeadService::getLeadsCount error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Lead detayını getir
     */
    public function getLeadById(int $id): ?array
    {
        if (!$this->pdo) {
            return null;
        }
        
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM leads WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("AdminLeadService::getLeadById error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Lead durumunu güncelle
     */
    public function updateStatus(int $id, string $status, ?string $previousStatus = null): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            $validStatuses = ['pending', 'contacted', 'sold', 'invalid', 'withdrawn'];
            if (!in_array($status, $validStatuses)) {
                return false;
            }
            
            // Önceki durumu kaydet
            if ($previousStatus === null) {
                $lead = $this->getLeadById($id);
                $previousStatus = $lead['status'] ?? null;
            }
            
            $stmt = $this->pdo->prepare("
                UPDATE leads 
                SET status = ?, 
                    previous_status = ?,
                    updated_at = NOW()
                WHERE id = ?
            ");
            return $stmt->execute([$status, $previousStatus, $id]);
        } catch (PDOException $e) {
            error_log("AdminLeadService::updateStatus error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lead'i gönderildi olarak işaretle
     */
    public function markAsSent(int $id): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                UPDATE leads 
                SET is_sent_to_provider = 1, 
                    sent_at = NOW(),
                    updated_at = NOW()
                WHERE id = ?
            ");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("AdminLeadService::markAsSent error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lead'i sil (soft delete)
     */
    public function softDelete(int $id): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                UPDATE leads 
                SET deleted_at = NOW(),
                    updated_at = NOW()
                WHERE id = ?
            ");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("AdminLeadService::softDelete error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lead'i geri yükle
     */
    public function restore(int $id): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                UPDATE leads 
                SET deleted_at = NULL,
                    updated_at = NOW()
                WHERE id = ?
            ");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("AdminLeadService::restore error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lead'i kalıcı olarak sil
     */
    public function permanentDelete(int $id): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            // Önce ilişkili kayıtları sil
            $this->pdo->prepare("DELETE FROM provider_lead_deliveries WHERE lead_id = ?")->execute([$id]);
            $this->pdo->prepare("DELETE FROM lead_requests WHERE lead_id = ?")->execute([$id]);
            
            // Sonra lead'i sil
            $stmt = $this->pdo->prepare("DELETE FROM leads WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("AdminLeadService::permanentDelete error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Eski çöp kutusunu temizle (30 günden eski)
     */
    public function cleanOldTrash(int $daysOld = 30): int
    {
        if (!$this->pdo) {
            return 0;
        }
        
        try {
            // 30 günden eski silinmiş lead'leri bul
            $stmt = $this->pdo->prepare("
                SELECT id FROM leads 
                WHERE deleted_at IS NOT NULL 
                AND deleted_at < DATE_SUB(NOW(), INTERVAL ? DAY)
            ");
            $stmt->execute([$daysOld]);
            $leads = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $deleted = 0;
            foreach ($leads as $leadId) {
                if ($this->permanentDelete($leadId)) {
                    $deleted++;
                }
            }
            
            return $deleted;
        } catch (PDOException $e) {
            error_log("AdminLeadService::cleanOldTrash error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * 48 saatten eski yeni lead'leri beklemede yap
     */
    public function autoUpdatePendingLeads(): int
    {
        if (!$this->pdo) {
            return 0;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                UPDATE leads 
                SET status = 'pending',
                    updated_at = NOW()
                WHERE status = 'new' 
                AND created_at < DATE_SUB(NOW(), INTERVAL 48 HOUR)
                AND deleted_at IS NULL
            ");
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("AdminLeadService::autoUpdatePendingLeads error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Lead'i provider'a gönder
     */
    public function sendToProvider(int $leadId, int $providerId, string $deliveryMethod = 'manual'): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            $this->pdo->beginTransaction();
            
            // Lead'i güncelle
            $stmt = $this->pdo->prepare("
                UPDATE leads 
                SET status = 'sold',
                    is_sent_to_provider = 1,
                    sent_at = NOW(),
                    updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$leadId]);
            
            // Delivery kaydı oluştur
            $stmt = $this->pdo->prepare("
                INSERT INTO provider_lead_deliveries 
                (lead_id, provider_id, delivery_method, delivered_at)
                VALUES (?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE 
                    delivery_method = VALUES(delivery_method),
                    delivered_at = NOW()
            ");
            $stmt->execute([$leadId, $providerId, $deliveryMethod]);
            
            // Provider'ın kalan lead sayısını azalt
            $stmt = $this->pdo->prepare("
                UPDATE provider_purchases 
                SET remaining_leads = remaining_leads - 1
                WHERE provider_id = ? 
                AND remaining_leads > 0 
                AND payment_status = 'completed'
                ORDER BY purchased_at ASC
                LIMIT 1
            ");
            $stmt->execute([$providerId]);
            
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("AdminLeadService::sendToProvider error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lead'i provider'dan geri çek
     */
    public function withdrawFromProvider(int $leadId): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            $this->pdo->beginTransaction();
            
            // Delivery kaydını bul
            $stmt = $this->pdo->prepare("
                SELECT provider_id FROM provider_lead_deliveries WHERE lead_id = ?
            ");
            $stmt->execute([$leadId]);
            $delivery = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($delivery) {
                // Provider'ın lead sayısını geri ver
                $stmt = $this->pdo->prepare("
                    UPDATE provider_purchases 
                    SET remaining_leads = remaining_leads + 1
                    WHERE provider_id = ? 
                    AND payment_status = 'completed'
                    ORDER BY purchased_at DESC
                    LIMIT 1
                ");
                $stmt->execute([$delivery['provider_id']]);
                
                // Delivery kaydını sil
                $stmt = $this->pdo->prepare("DELETE FROM provider_lead_deliveries WHERE lead_id = ?");
                $stmt->execute([$leadId]);
            }
            
            // Lead durumunu güncelle
            $stmt = $this->pdo->prepare("
                UPDATE leads 
                SET status = 'withdrawn',
                    is_sent_to_provider = 0,
                    updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$leadId]);
            
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("AdminLeadService::withdrawFromProvider error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Status'a göre lead sayılarını getir (dashboard için)
     */
    public function getCountsByStatus(): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $stmt = $this->pdo->query("
                SELECT status, COUNT(*) as count 
                FROM leads 
                WHERE deleted_at IS NULL 
                GROUP BY status
            ");
            
            $counts = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $counts[$row['status']] = (int) $row['count'];
            }
            
            return $counts;
        } catch (PDOException $e) {
            error_log("AdminLeadService::getCountsByStatus error: " . $e->getMessage());
            return [];
        }
    }
}

