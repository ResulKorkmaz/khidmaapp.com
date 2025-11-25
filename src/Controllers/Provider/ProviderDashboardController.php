<?php

/**
 * KhidmaApp.com - Provider Dashboard Controller
 * 
 * Provider ana sayfa ve istatistikler
 */

require_once __DIR__ . '/BaseProviderController.php';

class ProviderDashboardController extends BaseProviderController 
{
    /**
     * Dashboard ana sayfası
     */
    public function index(): void
    {
        $this->requireAuth();
        
        $provider = $this->getProvider();
        
        if (!$provider) {
            session_destroy();
            $this->redirect('/');
        }
        
        // İstatistikleri hesapla
        $stats = $this->getProviderStats($provider['id'], $provider['service_type'], $provider['city']);
        
        // Son lead'leri getir
        $recentLeads = $this->getRecentDeliveredLeads($provider['id'], 5);
        
        // Aktif paketler
        $activePackages = $this->getActivePackages($provider['id']);
        
        $this->render('dashboard', [
            'provider' => $provider,
            'stats' => $stats,
            'recentLeads' => $recentLeads,
            'activePackages' => $activePackages
        ]);
    }
    
    /**
     * Provider istatistiklerini getir
     */
    private function getProviderStats(int $providerId, string $serviceType, string $city): array
    {
        $stats = [
            'total_leads_received' => 0,
            'viewed_leads' => 0,
            'unviewed_leads' => 0,
            'available_leads' => 0,
            'total_purchases' => 0,
            'remaining_leads' => 0
        ];
        
        try {
            // Teslim edilen lead sayısı
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count FROM provider_lead_deliveries WHERE provider_id = ?
            ");
            $stmt->execute([$providerId]);
            $stats['total_leads_received'] = (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
            
            // Görüntülenen lead sayısı
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count FROM provider_lead_deliveries WHERE provider_id = ? AND viewed_at IS NOT NULL
            ");
            $stmt->execute([$providerId]);
            $stats['viewed_leads'] = (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
            
            $stats['unviewed_leads'] = $stats['total_leads_received'] - $stats['viewed_leads'];
            
            // Mevcut lead sayısı (aynı service_type ve city)
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count FROM leads 
                WHERE service_type = ? AND city = ? 
                AND status IN ('new', 'verified', 'pending')
                AND (is_sent_to_provider = 0 OR is_sent_to_provider IS NULL)
                AND deleted_at IS NULL
            ");
            $stmt->execute([$serviceType, $city]);
            $stats['available_leads'] = (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
            
            // Toplam satın alma
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count, COALESCE(SUM(leads_count - used_leads), 0) as remaining
                FROM provider_purchases WHERE provider_id = ? AND status = 'completed'
            ");
            $stmt->execute([$providerId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['total_purchases'] = (int)($result['count'] ?? 0);
            $stats['remaining_leads'] = (int)($result['remaining'] ?? 0);
            
        } catch (PDOException $e) {
            error_log("Provider stats error: " . $e->getMessage());
        }
        
        return $stats;
    }
    
    /**
     * Son teslim edilen lead'leri getir
     */
    private function getRecentDeliveredLeads(int $providerId, int $limit = 5): array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT l.*, pld.delivered_at, pld.viewed_at
                FROM provider_lead_deliveries pld
                JOIN leads l ON pld.lead_id = l.id
                WHERE pld.provider_id = ?
                ORDER BY pld.delivered_at DESC
                LIMIT ?
            ");
            $stmt->execute([$providerId, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Recent delivered leads error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Aktif paketleri getir
     */
    private function getActivePackages(int $providerId): array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT pp.*, lp.name_ar, lp.name_tr,
                       (pp.leads_count - pp.used_leads) as remaining_leads
                FROM provider_purchases pp
                LEFT JOIN lead_packages lp ON pp.package_id = lp.id
                WHERE pp.provider_id = ? AND pp.status = 'completed' AND pp.used_leads < pp.leads_count
                ORDER BY pp.purchased_at DESC
            ");
            $stmt->execute([$providerId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Active packages error: " . $e->getMessage());
            return [];
        }
    }
}

