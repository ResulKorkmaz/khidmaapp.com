<?php
/**
 * Provider Purchase Service
 * 
 * Provider (usta) panelindeki satın alma işlemlerini yönetir.
 * Controller'dan bağımsız, tekrar kullanılabilir iş mantığı.
 * 
 * @package KhidmaApp
 * @since 1.0.0
 */

class ProviderPurchaseService
{
    private $pdo;
    
    public function __construct($pdo = null)
    {
        $this->pdo = $pdo ?? getDatabase();
    }
    
    /**
     * Provider'ın tüm satın alımlarını getir
     */
    public function getPurchases(int $providerId): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT pp.*, lp.name_ar, lp.name_tr, lp.description_ar, lp.description_tr
                FROM provider_purchases pp
                LEFT JOIN lead_packages lp ON pp.package_id = lp.id
                WHERE pp.provider_id = ?
                ORDER BY pp.purchased_at DESC
            ");
            $stmt->execute([$providerId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ProviderPurchaseService::getPurchases error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Aktif satın alımları getir (kalan lead'i olan)
     */
    public function getActivePurchases(int $providerId): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT pp.*, lp.name_ar, lp.name_tr, lp.lead_count as package_lead_count
                FROM provider_purchases pp
                LEFT JOIN lead_packages lp ON pp.package_id = lp.id
                WHERE pp.provider_id = ? 
                AND pp.remaining_leads > 0 
                AND pp.payment_status = 'completed'
                ORDER BY pp.purchased_at ASC
            ");
            $stmt->execute([$providerId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ProviderPurchaseService::getActivePurchases error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Toplam kalan lead sayısı
     */
    public function getTotalRemainingLeads(int $providerId): int
    {
        if (!$this->pdo) {
            return 0;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT COALESCE(SUM(remaining_leads), 0) 
                FROM provider_purchases 
                WHERE provider_id = ? 
                AND payment_status = 'completed'
            ");
            $stmt->execute([$providerId]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("ProviderPurchaseService::getTotalRemainingLeads error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Toplam kullanılan lead sayısı
     */
    public function getTotalUsedLeads(int $providerId): int
    {
        if (!$this->pdo) {
            return 0;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT COALESCE(SUM(leads_count - remaining_leads), 0) 
                FROM provider_purchases 
                WHERE provider_id = ? 
                AND payment_status = 'completed'
            ");
            $stmt->execute([$providerId]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("ProviderPurchaseService::getTotalUsedLeads error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Satın alma detayını getir
     */
    public function getPurchaseById(int $purchaseId, int $providerId): ?array
    {
        if (!$this->pdo) {
            return null;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT pp.*, lp.name_ar, lp.name_tr
                FROM provider_purchases pp
                LEFT JOIN lead_packages lp ON pp.package_id = lp.id
                WHERE pp.id = ? AND pp.provider_id = ?
            ");
            $stmt->execute([$purchaseId, $providerId]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("ProviderPurchaseService::getPurchaseById error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Mevcut paketleri getir (provider'ın service type'ına göre)
     */
    public function getAvailablePackages(int $providerId): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            // Provider'ın service type'ını al
            $stmt = $this->pdo->prepare("SELECT service_type FROM service_providers WHERE id = ?");
            $stmt->execute([$providerId]);
            $provider = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$provider) {
                return [];
            }
            
            // O service type için aktif paketleri getir
            $stmt = $this->pdo->prepare("
                SELECT * FROM lead_packages 
                WHERE service_type = ? 
                AND is_active = 1
                ORDER BY lead_count ASC
            ");
            $stmt->execute([$provider['service_type']]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ProviderPurchaseService::getAvailablePackages error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Satın alma istatistikleri
     */
    public function getStats(int $providerId): array
    {
        if (!$this->pdo) {
            return $this->getEmptyStats();
        }
        
        try {
            $stats = [];
            
            // Toplam satın alma
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM provider_purchases 
                WHERE provider_id = ? AND payment_status = 'completed'
            ");
            $stmt->execute([$providerId]);
            $stats['total_purchases'] = (int) $stmt->fetchColumn();
            
            // Toplam harcama
            $stmt = $this->pdo->prepare("
                SELECT COALESCE(SUM(price_paid), 0) FROM provider_purchases 
                WHERE provider_id = ? AND payment_status = 'completed'
            ");
            $stmt->execute([$providerId]);
            $stats['total_spent'] = (float) $stmt->fetchColumn();
            
            // Toplam satın alınan lead
            $stmt = $this->pdo->prepare("
                SELECT COALESCE(SUM(leads_count), 0) FROM provider_purchases 
                WHERE provider_id = ? AND payment_status = 'completed'
            ");
            $stmt->execute([$providerId]);
            $stats['total_leads_bought'] = (int) $stmt->fetchColumn();
            
            // Kalan lead
            $stats['remaining_leads'] = $this->getTotalRemainingLeads($providerId);
            
            // Kullanılan lead
            $stats['used_leads'] = $this->getTotalUsedLeads($providerId);
            
            // Aktif paket sayısı
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM provider_purchases 
                WHERE provider_id = ? 
                AND payment_status = 'completed' 
                AND remaining_leads > 0
            ");
            $stmt->execute([$providerId]);
            $stats['active_packages'] = (int) $stmt->fetchColumn();
            
            // Son satın alma tarihi
            $stmt = $this->pdo->prepare("
                SELECT purchased_at FROM provider_purchases 
                WHERE provider_id = ? AND payment_status = 'completed'
                ORDER BY purchased_at DESC LIMIT 1
            ");
            $stmt->execute([$providerId]);
            $stats['last_purchase'] = $stmt->fetchColumn() ?: null;
            
            return $stats;
        } catch (PDOException $e) {
            error_log("ProviderPurchaseService::getStats error: " . $e->getMessage());
            return $this->getEmptyStats();
        }
    }
    
    /**
     * Lead kullan (bir adet düş)
     */
    public function useOneLead(int $providerId): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            // En eski aktif paketten bir lead düş (FIFO)
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
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("ProviderPurchaseService::useOneLead error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lead iade et (bir adet ekle)
     */
    public function refundOneLead(int $providerId): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            // En yeni pakete bir lead ekle
            $stmt = $this->pdo->prepare("
                UPDATE provider_purchases 
                SET remaining_leads = remaining_leads + 1
                WHERE provider_id = ? 
                AND payment_status = 'completed'
                ORDER BY purchased_at DESC
                LIMIT 1
            ");
            $stmt->execute([$providerId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("ProviderPurchaseService::refundOneLead error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Aktif paketi var mı kontrol et
     */
    public function hasActivePackage(int $providerId): bool
    {
        return $this->getTotalRemainingLeads($providerId) > 0;
    }
    
    /**
     * Bekleyen ödeme var mı
     */
    public function hasPendingPayment(int $providerId): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM provider_purchases 
                WHERE provider_id = ? AND payment_status = 'pending'
            ");
            $stmt->execute([$providerId]);
            return (int) $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("ProviderPurchaseService::hasPendingPayment error: " . $e->getMessage());
            return false;
        }
    }
    
    private function getEmptyStats(): array
    {
        return [
            'total_purchases' => 0,
            'total_spent' => 0,
            'total_leads_bought' => 0,
            'remaining_leads' => 0,
            'used_leads' => 0,
            'active_packages' => 0,
            'last_purchase' => null
        ];
    }
}

