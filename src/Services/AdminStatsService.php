<?php
/**
 * Admin Stats Service
 * 
 * Admin dashboard istatistiklerini yönetir.
 * Tüm istatistik sorgularını merkezi bir yerde toplar.
 * 
 * @package KhidmaApp
 * @since 1.0.0
 */

class AdminStatsService
{
    private $pdo;
    
    public function __construct($pdo = null)
    {
        $this->pdo = $pdo ?? getDatabase();
    }
    
    /**
     * Dashboard için tüm istatistikleri getir
     */
    public function getDashboardStats(): array
    {
        return [
            'leads' => $this->getLeadStats(),
            'providers' => $this->getProviderStats(),
            'purchases' => $this->getPurchaseStats(),
            'revenue' => $this->getRevenueStats()
        ];
    }
    
    /**
     * Lead istatistikleri
     */
    public function getLeadStats(): array
    {
        if (!$this->pdo) {
            return $this->getEmptyLeadStats();
        }
        
        try {
            $stats = [];
            
            // Toplam (silinmemiş)
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM leads WHERE deleted_at IS NULL");
            $stats['total'] = (int) $stmt->fetchColumn();
            
            // Yeni (son 24 saat)
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM leads WHERE created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR) AND deleted_at IS NULL");
            $stats['new_24h'] = (int) $stmt->fetchColumn();
            
            // Bu hafta
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM leads WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY) AND deleted_at IS NULL");
            $stats['new_week'] = (int) $stmt->fetchColumn();
            
            // Bu ay
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM leads WHERE created_at > DATE_SUB(NOW(), INTERVAL 30 DAY) AND deleted_at IS NULL");
            $stats['new_month'] = (int) $stmt->fetchColumn();
            
            // Status'a göre
            $stmt = $this->pdo->query("
                SELECT status, COUNT(*) as count 
                FROM leads 
                WHERE deleted_at IS NULL 
                GROUP BY status
            ");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $stats['by_status'][$row['status']] = (int) $row['count'];
            }
            
            // Gönderilmemiş
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM leads WHERE is_sent_to_provider = 0 AND deleted_at IS NULL AND status != 'invalid'");
            $stats['unsent'] = (int) $stmt->fetchColumn();
            
            // Silinmiş (çöp kutusu)
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM leads WHERE deleted_at IS NOT NULL");
            $stats['deleted'] = (int) $stmt->fetchColumn();
            
            return $stats;
        } catch (PDOException $e) {
            error_log("AdminStatsService::getLeadStats error: " . $e->getMessage());
            return $this->getEmptyLeadStats();
        }
    }
    
    /**
     * Provider istatistikleri
     */
    public function getProviderStats(): array
    {
        if (!$this->pdo) {
            return $this->getEmptyProviderStats();
        }
        
        try {
            $stats = [];
            
            // Toplam
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM service_providers");
            $stats['total'] = (int) $stmt->fetchColumn();
            
            // Status'a göre
            $stmt = $this->pdo->query("
                SELECT status, COUNT(*) as count 
                FROM service_providers 
                GROUP BY status
            ");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $stats['by_status'][$row['status']] = (int) $row['count'];
            }
            
            // Aktif (son 30 gün giriş)
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM service_providers WHERE last_login > DATE_SUB(NOW(), INTERVAL 30 DAY)");
            $stats['active_30d'] = (int) $stmt->fetchColumn();
            
            // Yeni kayıtlar (bu hafta)
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM service_providers WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)");
            $stats['new_week'] = (int) $stmt->fetchColumn();
            
            // Service type'a göre
            $stmt = $this->pdo->query("
                SELECT service_type, COUNT(*) as count 
                FROM service_providers 
                WHERE status = 'approved'
                GROUP BY service_type
            ");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $stats['by_service'][$row['service_type']] = (int) $row['count'];
            }
            
            return $stats;
        } catch (PDOException $e) {
            error_log("AdminStatsService::getProviderStats error: " . $e->getMessage());
            return $this->getEmptyProviderStats();
        }
    }
    
    /**
     * Satın alma istatistikleri
     */
    public function getPurchaseStats(): array
    {
        if (!$this->pdo) {
            return $this->getEmptyPurchaseStats();
        }
        
        try {
            $stats = [];
            
            // Toplam satın alma
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM provider_purchases WHERE payment_status = 'completed'");
            $stats['total'] = (int) $stmt->fetchColumn();
            
            // Bu ay
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM provider_purchases WHERE payment_status = 'completed' AND purchased_at > DATE_SUB(NOW(), INTERVAL 30 DAY)");
            $stats['this_month'] = (int) $stmt->fetchColumn();
            
            // Bekleyen ödemeler
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM provider_purchases WHERE payment_status = 'pending'");
            $stats['pending'] = (int) $stmt->fetchColumn();
            
            // Toplam satılan lead
            $stmt = $this->pdo->query("SELECT COALESCE(SUM(leads_count), 0) FROM provider_purchases WHERE payment_status = 'completed'");
            $stats['total_leads_sold'] = (int) $stmt->fetchColumn();
            
            // Kullanılan lead'ler
            $stmt = $this->pdo->query("SELECT COALESCE(SUM(leads_count - remaining_leads), 0) FROM provider_purchases WHERE payment_status = 'completed'");
            $stats['leads_used'] = (int) $stmt->fetchColumn();
            
            // Kalan lead'ler
            $stmt = $this->pdo->query("SELECT COALESCE(SUM(remaining_leads), 0) FROM provider_purchases WHERE payment_status = 'completed'");
            $stats['leads_remaining'] = (int) $stmt->fetchColumn();
            
            return $stats;
        } catch (PDOException $e) {
            error_log("AdminStatsService::getPurchaseStats error: " . $e->getMessage());
            return $this->getEmptyPurchaseStats();
        }
    }
    
    /**
     * Gelir istatistikleri
     */
    public function getRevenueStats(): array
    {
        if (!$this->pdo) {
            return $this->getEmptyRevenueStats();
        }
        
        try {
            $stats = [];
            
            // Toplam gelir
            $stmt = $this->pdo->query("SELECT COALESCE(SUM(price_paid), 0) FROM provider_purchases WHERE payment_status = 'completed'");
            $stats['total'] = (float) $stmt->fetchColumn();
            
            // Bu ay
            $stmt = $this->pdo->query("SELECT COALESCE(SUM(price_paid), 0) FROM provider_purchases WHERE payment_status = 'completed' AND purchased_at > DATE_SUB(NOW(), INTERVAL 30 DAY)");
            $stats['this_month'] = (float) $stmt->fetchColumn();
            
            // Geçen ay
            $stmt = $this->pdo->query("
                SELECT COALESCE(SUM(price_paid), 0) FROM provider_purchases 
                WHERE payment_status = 'completed' 
                AND purchased_at BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND DATE_SUB(NOW(), INTERVAL 30 DAY)
            ");
            $stats['last_month'] = (float) $stmt->fetchColumn();
            
            // Bu hafta
            $stmt = $this->pdo->query("SELECT COALESCE(SUM(price_paid), 0) FROM provider_purchases WHERE payment_status = 'completed' AND purchased_at > DATE_SUB(NOW(), INTERVAL 7 DAY)");
            $stats['this_week'] = (float) $stmt->fetchColumn();
            
            // Bugün
            $stmt = $this->pdo->query("SELECT COALESCE(SUM(price_paid), 0) FROM provider_purchases WHERE payment_status = 'completed' AND DATE(purchased_at) = CURDATE()");
            $stats['today'] = (float) $stmt->fetchColumn();
            
            // Ortalama sipariş değeri
            $stmt = $this->pdo->query("SELECT COALESCE(AVG(price_paid), 0) FROM provider_purchases WHERE payment_status = 'completed'");
            $stats['avg_order'] = round((float) $stmt->fetchColumn(), 2);
            
            // Para birimi
            $stats['currency'] = 'SAR';
            
            return $stats;
        } catch (PDOException $e) {
            error_log("AdminStatsService::getRevenueStats error: " . $e->getMessage());
            return $this->getEmptyRevenueStats();
        }
    }
    
    /**
     * Lead talepleri istatistikleri
     */
    public function getLeadRequestStats(): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $stats = [];
            
            // Bekleyen talepler
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM lead_requests WHERE status = 'pending'");
            $stats['pending'] = (int) $stmt->fetchColumn();
            
            // Onaylanan
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM lead_requests WHERE status = 'approved'");
            $stats['approved'] = (int) $stmt->fetchColumn();
            
            // Reddedilen
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM lead_requests WHERE status = 'rejected'");
            $stats['rejected'] = (int) $stmt->fetchColumn();
            
            // Bugünkü talepler
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM lead_requests WHERE DATE(created_at) = CURDATE()");
            $stats['today'] = (int) $stmt->fetchColumn();
            
            return $stats;
        } catch (PDOException $e) {
            error_log("AdminStatsService::getLeadRequestStats error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Günlük lead grafiği verisi (son 30 gün)
     */
    public function getDailyLeadChart(int $days = 30): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT DATE(created_at) as date, COUNT(*) as count
                FROM leads
                WHERE created_at > DATE_SUB(NOW(), INTERVAL ? DAY)
                AND deleted_at IS NULL
                GROUP BY DATE(created_at)
                ORDER BY date
            ");
            $stmt->execute([$days]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("AdminStatsService::getDailyLeadChart error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Service type'a göre lead dağılımı
     */
    public function getLeadsByServiceType(): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $stmt = $this->pdo->query("
                SELECT service_type, COUNT(*) as count
                FROM leads
                WHERE deleted_at IS NULL
                GROUP BY service_type
                ORDER BY count DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("AdminStatsService::getLeadsByServiceType error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Şehre göre lead dağılımı
     */
    public function getLeadsByCity(): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $stmt = $this->pdo->query("
                SELECT city, COUNT(*) as count
                FROM leads
                WHERE deleted_at IS NULL
                GROUP BY city
                ORDER BY count DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("AdminStatsService::getLeadsByCity error: " . $e->getMessage());
            return [];
        }
    }
    
    // Empty stats helpers
    private function getEmptyLeadStats(): array
    {
        return ['total' => 0, 'new_24h' => 0, 'new_week' => 0, 'new_month' => 0, 'unsent' => 0, 'deleted' => 0, 'by_status' => []];
    }
    
    private function getEmptyProviderStats(): array
    {
        return ['total' => 0, 'active_30d' => 0, 'new_week' => 0, 'by_status' => [], 'by_service' => []];
    }
    
    private function getEmptyPurchaseStats(): array
    {
        return ['total' => 0, 'this_month' => 0, 'pending' => 0, 'total_leads_sold' => 0, 'leads_used' => 0, 'leads_remaining' => 0];
    }
    
    private function getEmptyRevenueStats(): array
    {
        return ['total' => 0, 'this_month' => 0, 'last_month' => 0, 'this_week' => 0, 'today' => 0, 'avg_order' => 0, 'currency' => 'SAR'];
    }
}

