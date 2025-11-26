<?php

/**
 * KhidmaApp.com - Admin Dashboard Controller
 */

require_once __DIR__ . '/BaseAdminController.php';

class AdminDashboardController extends BaseAdminController 
{
    public function index(): void
    {
        $this->requireAuth();
        $this->autoUpdatePendingLeads();
        
        $stats = $this->getStats();
        $recentLeads = $this->getRecentLeads(5);
        $pendingRequests = $this->getPendingRequests(5);
        
        $this->render('dashboard', [
            'stats' => $stats,
            'recentLeads' => $recentLeads,
            'pendingRequests' => $pendingRequests
        ]);
    }
    
    private function getStats(): array
    {
        if (!$this->pdo) return $this->getEmptyStats();
        
        try {
            $stats = [];
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE deleted_at IS NULL");
            $stats['total_leads'] = (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'new' AND deleted_at IS NULL");
            $stats['new_leads'] = (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'verified' AND deleted_at IS NULL");
            $stats['verified_leads'] = (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'sold' AND deleted_at IS NULL");
            $stats['sold_leads'] = (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'pending' AND deleted_at IS NULL");
            $stats['pending_leads'] = (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE DATE(created_at) = CURDATE() AND deleted_at IS NULL");
            $stats['today_leads'] = (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1) AND deleted_at IS NULL");
            $stats['week_leads'] = (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM service_providers");
            $stats['total_providers'] = (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM service_providers WHERE status = 'active'");
            $stats['active_providers'] = (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM service_providers WHERE status = 'pending'");
            $stats['pending_providers'] = (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM provider_purchases WHERE payment_status = 'completed'");
            $stats['total_purchases'] = (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
            
            $stmt = $this->pdo->query("SELECT COALESCE(SUM(price_paid), 0) as total FROM provider_purchases WHERE payment_status = 'completed'");
            $stats['total_revenue'] = (float)($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM lead_requests WHERE request_status = 'pending'");
            $stats['pending_requests'] = (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM provider_lead_deliveries");
            $stats['delivered_leads'] = (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Dashboard stats error: " . $e->getMessage());
            return $this->getEmptyStats();
        }
    }
    
    private function getEmptyStats(): array
    {
        return [
            'total_leads' => 0, 'new_leads' => 0, 'verified_leads' => 0, 'sold_leads' => 0,
            'pending_leads' => 0, 'today_leads' => 0, 'week_leads' => 0, 'total_providers' => 0,
            'active_providers' => 0, 'pending_providers' => 0, 'total_purchases' => 0,
            'total_revenue' => 0, 'pending_requests' => 0, 'delivered_leads' => 0
        ];
    }
    
    private function getRecentLeads(int $limit = 5): array
    {
        if (!$this->pdo) return [];
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM leads WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT ?");
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    
    private function getPendingRequests(int $limit = 5): array
    {
        if (!$this->pdo) return [];
        try {
            $stmt = $this->pdo->prepare("
                SELECT lr.*, sp.business_name, sp.phone as provider_phone, pp.package_name, pp.remaining_leads
                FROM lead_requests lr
                JOIN service_providers sp ON lr.provider_id = sp.id
                LEFT JOIN provider_purchases pp ON lr.purchase_id = pp.id
                WHERE lr.request_status = 'pending'
                ORDER BY lr.requested_at ASC LIMIT ?
            ");
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    
    private function autoUpdatePendingLeads(): void
    {
        if (!$this->pdo) return;
        try {
            $this->pdo->exec("UPDATE leads SET status = 'pending' WHERE status = 'new' AND deleted_at IS NULL AND TIMESTAMPDIFF(HOUR, created_at, NOW()) >= 48");
        } catch (PDOException $e) {}
    }
}
