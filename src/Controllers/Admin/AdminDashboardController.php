<?php

/**
 * KhidmaApp.com - Admin Dashboard Controller
 * 
 * Admin ana sayfa ve istatistikler
 */

require_once __DIR__ . '/BaseAdminController.php';

class AdminDashboardController extends BaseAdminController 
{
    /**
     * Admin dashboard
     */
    public function index(): void
    {
        $this->requireAuth();
        
        // Otomatik olarak 48 saatten eski lead'leri "beklemede" yap
        $this->autoUpdatePendingLeads();
        
        // İstatistikler
        $stats = $this->getStats();
        
        // Son leads
        $recentLeads = $this->getRecentLeads(10);
        
        $this->render('dashboard', [
            'stats' => $stats,
            'recentLeads' => $recentLeads
        ]);
    }
    
    /**
     * İstatistikleri getir
     */
    private function getStats(): array
    {
        if (!$this->pdo) {
            return $this->getEmptyStats();
        }
        
        try {
            $stats = [];
            
            // Toplam leads
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE deleted_at IS NULL");
            $stats['total_leads'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Yeni leads
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'new' AND deleted_at IS NULL");
            $stats['new_leads'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Doğrulanmış leads
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'verified' AND deleted_at IS NULL");
            $stats['verified_leads'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Satılan leads
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'sold' AND deleted_at IS NULL");
            $stats['sold_leads'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Bekleyen leads
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'pending' AND deleted_at IS NULL");
            $stats['pending_leads'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Bugünkü leads
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE DATE(created_at) = CURDATE() AND deleted_at IS NULL");
            $stats['today_leads'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Toplam provider'lar
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM service_providers");
            $stats['total_providers'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Aktif provider'lar
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM service_providers WHERE status = 'approved'");
            $stats['active_providers'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Onay bekleyen provider'lar
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM service_providers WHERE status = 'pending'");
            $stats['pending_providers'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Toplam satın almalar
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM purchases WHERE status = 'completed'");
            $stats['total_purchases'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Toplam gelir
            $stmt = $this->pdo->query("SELECT COALESCE(SUM(amount), 0) as total FROM purchases WHERE status = 'completed'");
            $stats['total_revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Dashboard stats error: " . $e->getMessage());
            return $this->getEmptyStats();
        }
    }
    
    /**
     * Boş istatistikler
     */
    private function getEmptyStats(): array
    {
        return [
            'total_leads' => 0,
            'new_leads' => 0,
            'verified_leads' => 0,
            'sold_leads' => 0,
            'pending_leads' => 0,
            'today_leads' => 0,
            'total_providers' => 0,
            'active_providers' => 0,
            'pending_providers' => 0,
            'total_purchases' => 0,
            'total_revenue' => 0
        ];
    }
    
    /**
     * Son leads'i getir
     */
    private function getRecentLeads(int $limit = 10): array
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
            error_log("Recent leads error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * 48 saatten eski "new" lead'leri otomatik "pending" yap
     */
    private function autoUpdatePendingLeads(): void
    {
        if (!$this->pdo) {
            return;
        }
        
        try {
            $sql = "UPDATE leads 
                    SET status = 'pending' 
                    WHERE status = 'new' 
                    AND deleted_at IS NULL
                    AND TIMESTAMPDIFF(HOUR, created_at, NOW()) >= 48";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            
            $affectedRows = $stmt->rowCount();
            if ($affectedRows > 0) {
                error_log("Auto-updated $affectedRows leads to pending status (older than 48 hours)");
            }
        } catch (PDOException $e) {
            error_log("Auto update pending leads error: " . $e->getMessage());
        }
    }
}

