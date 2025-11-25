<?php

/**
 * KhidmaApp.com - Provider Lead Controller
 * 
 * Provider için lead görüntüleme ve yönetimi
 */

require_once __DIR__ . '/BaseProviderController.php';

class ProviderLeadController extends BaseProviderController 
{
    /**
     * Provider'a gönderilmiş lead'leri listele
     */
    public function index(): void
    {
        $this->requireAuth();
        
        $providerId = $this->getProviderId();
        $provider = $this->getProvider();
        
        if (!$provider) {
            $this->redirect('/');
        }
        
        // Filtreleme parametreleri
        $statusFilter = $this->sanitizedGet('status', 'all');
        $page = max(1, $this->intGet('page', 1));
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        
        try {
            // Provider'a gönderilmiş lead'leri getir
            $whereClause = "WHERE pld.provider_id = ?";
            $params = [$providerId];
            
            if ($statusFilter !== 'all') {
                $whereClause .= " AND l.status = ?";
                $params[] = $statusFilter;
            }
            
            // Toplam sayı
            $countSql = "
                SELECT COUNT(DISTINCT l.id) as count
                FROM leads l
                INNER JOIN provider_lead_deliveries pld ON l.id = pld.lead_id
                $whereClause
            ";
            $stmt = $this->db->prepare($countSql);
            $stmt->execute($params);
            $totalLeads = (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];
            $totalPages = ceil($totalLeads / $perPage);
            
            // Lead'leri getir
            $params[] = $perPage;
            $params[] = $offset;
            
            $sql = "
                SELECT l.*, pld.delivered_at, pld.viewed_at, pld.viewed_count, pld.delivery_method,
                       pp.id as purchase_id
                FROM leads l
                INNER JOIN provider_lead_deliveries pld ON l.id = pld.lead_id
                LEFT JOIN provider_purchases pp ON pld.purchase_id = pp.id
                $whereClause
                ORDER BY pld.delivered_at DESC
                LIMIT ? OFFSET ?
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // İstatistikler
            $stats = $this->getLeadStats($providerId);
            
            // Satın alınan paketler
            $purchases = $this->getProviderPurchases($providerId);
            
            $this->render('leads', [
                'leads' => $leads,
                'stats' => $stats,
                'purchases' => $purchases,
                'statusFilter' => $statusFilter,
                'page' => $page,
                'totalPages' => $totalPages,
                'totalLeads' => $totalLeads,
                'provider' => $provider
            ]);
        } catch (PDOException $e) {
            error_log("Provider leads error: " . $e->getMessage());
            $_SESSION['error'] = 'Lead\'ler yüklenirken hata oluştu';
            $this->redirect('/provider/dashboard');
        }
    }
    
    /**
     * Lead'i görüntülendi olarak işaretle
     */
    public function markViewed(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('Method not allowed', 405);
        }
        
        $leadId = $this->intPost('lead_id');
        $providerId = $this->getProviderId();
        
        if (!$leadId) {
            $this->errorResponse('Geçersiz lead ID', 400);
        }
        
        try {
            // Delivery kaydını güncelle
            $stmt = $this->db->prepare("
                UPDATE provider_lead_deliveries 
                SET viewed_at = COALESCE(viewed_at, NOW()), viewed_count = viewed_count + 1
                WHERE lead_id = ? AND provider_id = ?
            ");
            $stmt->execute([$leadId, $providerId]);
            
            $this->successResponse('Lead görüntülendi olarak işaretlendi');
        } catch (PDOException $e) {
            error_log("Mark lead viewed error: " . $e->getMessage());
            $this->errorResponse('İşlem başarısız', 500);
        }
    }
    
    /**
     * Satın alınan paketten lead talep et
     */
    public function request(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('Method not allowed', 405);
        }
        
        $purchaseId = $this->intPost('purchase_id');
        $providerId = $this->getProviderId();
        
        if (!$purchaseId) {
            $this->errorResponse('Geçersiz paket ID', 400);
        }
        
        try {
            // Provider aktif mi kontrol et
            $provider = $this->getProvider();
            if (!$provider || $provider['status'] !== 'active') {
                $this->errorResponse('حسابك غير مفعّل', 403);
            }
            
            // Satın alma kaydını kontrol et
            $stmt = $this->db->prepare("
                SELECT * FROM provider_purchases 
                WHERE id = ? AND provider_id = ? AND payment_status = 'completed'
            ");
            $stmt->execute([$purchaseId, $providerId]);
            $purchase = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$purchase) {
                $this->errorResponse('الحزمة غير موجودة', 404);
            }
            
            // Kalan lead var mı kontrol et
            if (($purchase['remaining_leads'] ?? 0) <= 0) {
                $this->errorResponse('لا توجد طلبات متبقية في هذه الحزمة', 400);
            }
            
            // Lead talebi oluştur (admin'in göndermesi için)
            $stmt = $this->db->prepare("
                INSERT INTO lead_requests (provider_id, purchase_id, status, created_at)
                VALUES (?, ?, 'pending', NOW())
            ");
            $stmt->execute([$providerId, $purchaseId]);
            
            error_log("✅ Provider #{$providerId} requested lead from purchase #{$purchaseId}");
            
            $this->successResponse('تم إرسال طلبك بنجاح! سيتم إرسال بيانات العميل قريباً.');
        } catch (PDOException $e) {
            error_log("Request lead error: " . $e->getMessage());
            $this->errorResponse('حدث خطأ أثناء إرسال الطلب', 500);
        }
    }
    
    /**
     * Lead'i gizle
     */
    public function hide(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('Method not allowed', 405);
        }
        
        if (!$this->verifyCsrf()) {
            $this->errorResponse('Geçersiz güvenlik belirteci', 403);
        }
        
        $leadId = $this->intPost('lead_id');
        $providerId = $this->getProviderId();
        
        if (!$leadId) {
            $this->errorResponse('Geçersiz lead ID', 400);
        }
        
        try {
            // Gizli lead kaydı oluştur
            $stmt = $this->db->prepare("
                INSERT INTO provider_hidden_leads (provider_id, lead_id, hidden_at)
                VALUES (?, ?, NOW())
                ON DUPLICATE KEY UPDATE hidden_at = NOW()
            ");
            $stmt->execute([$providerId, $leadId]);
            
            $this->successResponse('Lead gizlendi');
        } catch (PDOException $e) {
            error_log("Hide lead error: " . $e->getMessage());
            $this->errorResponse('İşlem başarısız', 500);
        }
    }
    
    /**
     * Gizlenmiş lead'leri listele
     */
    public function hidden(): void
    {
        $this->requireAuth();
        
        $providerId = $this->getProviderId();
        $provider = $this->getProvider();
        
        try {
            $stmt = $this->db->prepare("
                SELECT l.*, phl.hidden_at
                FROM leads l
                INNER JOIN provider_hidden_leads phl ON l.id = phl.lead_id
                WHERE phl.provider_id = ? AND l.deleted_at IS NULL
                ORDER BY phl.hidden_at DESC
            ");
            $stmt->execute([$providerId]);
            $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $this->render('hidden_leads', [
                'leads' => $leads,
                'provider' => $provider
            ]);
        } catch (PDOException $e) {
            error_log("Hidden leads error: " . $e->getMessage());
            $_SESSION['error'] = 'Gizli lead\'ler yüklenirken hata oluştu';
            $this->redirect('/provider/dashboard');
        }
    }
    
    /**
     * Taleplerim sayfası
     */
    public function myRequests(): void
    {
        $this->requireAuth();
        
        $providerId = $this->getProviderId();
        $provider = $this->getProvider();
        
        try {
            $stmt = $this->db->prepare("
                SELECT lr.*, l.service_type, l.city, l.description, l.created_at as lead_created_at
                FROM lead_requests lr
                INNER JOIN leads l ON lr.lead_id = l.id
                WHERE lr.provider_id = ?
                ORDER BY lr.created_at DESC
            ");
            $stmt->execute([$providerId]);
            $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $this->render('my_requests', [
                'requests' => $requests,
                'provider' => $provider
            ]);
        } catch (PDOException $e) {
            error_log("My requests error: " . $e->getMessage());
            $_SESSION['error'] = 'Talepler yüklenirken hata oluştu';
            $this->redirect('/provider/dashboard');
        }
    }
    
    // ==================== PRIVATE METHODS ====================
    
    /**
     * Provider'ın satın aldığı paketleri getir
     */
    private function getProviderPurchases(int $providerId): array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT pp.*, lp.name_ar as package_name, lp.lead_count as package_lead_count
                FROM provider_purchases pp
                LEFT JOIN lead_packages lp ON pp.package_id = lp.id
                WHERE pp.provider_id = ? AND pp.payment_status = 'completed'
                ORDER BY pp.purchased_at DESC
            ");
            $stmt->execute([$providerId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get provider purchases error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Provider için lead istatistikleri
     */
    private function getLeadStats(int $providerId): array
    {
        try {
            $stats = [];
            
            // Toplam gönderilen lead
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM provider_lead_deliveries WHERE provider_id = ?");
            $stmt->execute([$providerId]);
            $stats['total'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Görüntülenen
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM provider_lead_deliveries WHERE provider_id = ? AND viewed_at IS NOT NULL");
            $stmt->execute([$providerId]);
            $stats['viewed'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Görüntülenmemiş
            $stats['not_viewed'] = $stats['total'] - $stats['viewed'];
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Get lead stats error: " . $e->getMessage());
            return ['total' => 0, 'viewed' => 0, 'not_viewed' => 0];
        }
    }
}

