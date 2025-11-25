<?php

/**
 * KhidmaApp.com - Admin Purchase Controller
 * 
 * Satın alma ve lead talep yönetimi
 */

require_once __DIR__ . '/BaseAdminController.php';

class AdminPurchaseController extends BaseAdminController 
{
    /**
     * Satın almalar listesi
     */
    public function index(): void
    {
        $this->requireAuth();
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    pp.*,
                    sp.name as provider_name,
                    sp.phone as provider_phone,
                    sp.email as provider_email,
                    sp.service_type,
                    sp.city,
                    lp.name_tr as package_name_tr,
                    COUNT(DISTINCT pld.id) as delivered_count
                FROM provider_purchases pp
                INNER JOIN service_providers sp ON pp.provider_id = sp.id
                LEFT JOIN lead_packages lp ON pp.package_id = lp.id
                LEFT JOIN provider_lead_deliveries pld ON pp.id = pld.purchase_id
                GROUP BY pp.id
                ORDER BY pp.purchased_at DESC
            ");
            $stmt->execute();
            $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $serviceTypes = getServiceTypes();
            
            $this->render('purchases', [
                'purchases' => $purchases,
                'serviceTypes' => $serviceTypes,
                'currentPage' => 'purchases'
            ]);
        } catch (PDOException $e) {
            error_log("Purchases list error: " . $e->getMessage());
            $_SESSION['error'] = 'Satın almalar yüklenirken hata oluştu';
            $this->redirect('/admin');
        }
    }
    
    /**
     * Lead talepleri listesi
     */
    public function leadRequests(): void
    {
        $this->requireAuth();
        
        try {
            $page = max(1, $this->intGet('page', 1));
            $perPage = 20;
            $offset = ($page - 1) * $perPage;
            $statusFilter = $this->sanitizedGet('status', 'all');
            
            // Filtreleme
            $whereClause = "WHERE 1=1";
            $params = [];
            
            if ($statusFilter !== 'all') {
                $whereClause .= " AND lr.status = ?";
                $params[] = $statusFilter;
            }
            
            // Toplam sayı
            $countStmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM lead_requests lr $whereClause
            ");
            $countStmt->execute($params);
            $totalRequests = $countStmt->fetchColumn();
            $totalPages = ceil($totalRequests / $perPage);
            
            // Talepleri getir
            $params[] = $perPage;
            $params[] = $offset;
            
            $stmt = $this->pdo->prepare("
                SELECT 
                    lr.*,
                    l.service_type, l.city, l.phone as lead_phone, l.description as lead_description,
                    sp.name as provider_name, sp.phone as provider_phone, sp.email as provider_email
                FROM lead_requests lr
                JOIN leads l ON lr.lead_id = l.id
                JOIN service_providers sp ON lr.provider_id = sp.id
                $whereClause
                ORDER BY lr.created_at DESC
                LIMIT ? OFFSET ?
            ");
            $stmt->execute($params);
            $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // İstatistikler
            $stats = [
                'pending' => $this->getRequestCountByStatus('pending'),
                'approved' => $this->getRequestCountByStatus('approved'),
                'rejected' => $this->getRequestCountByStatus('rejected'),
                'total' => $totalRequests
            ];
            
            $this->render('lead_requests', [
                'requests' => $requests,
                'stats' => $stats,
                'statusFilter' => $statusFilter,
                'page' => $page,
                'totalPages' => $totalPages,
                'totalRequests' => $totalRequests
            ]);
        } catch (PDOException $e) {
            error_log("Lead requests error: " . $e->getMessage());
            $_SESSION['error'] = 'Lead talepleri yüklenirken hata oluştu';
            $this->redirect('/admin');
        }
    }
    
    /**
     * Bekleyen talep sayısını getir
     */
    public function pendingRequestsCount(): void
    {
        $this->requireAuth();
        
        try {
            $count = $this->getRequestCountByStatus('pending');
            $this->successResponse('Bekleyen talep sayısı', ['count' => $count]);
        } catch (PDOException $e) {
            error_log("Pending requests count error: " . $e->getMessage());
            $this->errorResponse('Sayı alınamadı', 500);
        }
    }
    
    /**
     * Lead'i manuel gönder
     */
    public function sendLeadManually(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('Method not allowed', 405);
        }
        
        $data = $this->getJsonInput();
        $leadId = intval($data['lead_id'] ?? 0);
        $providerId = intval($data['provider_id'] ?? 0);
        $purchaseId = intval($data['purchase_id'] ?? 0);
        
        if ($leadId <= 0 || $providerId <= 0 || $purchaseId <= 0) {
            $this->errorResponse('Geçersiz parametreler', 400);
        }
        
        try {
            $this->pdo->beginTransaction();
            
            // Satın alma kontrolü
            $stmt = $this->pdo->prepare("
                SELECT pp.*, COUNT(DISTINCT pld.id) as delivered_count
                FROM provider_purchases pp
                LEFT JOIN provider_lead_deliveries pld ON pp.id = pld.purchase_id
                WHERE pp.id = ? AND pp.provider_id = ?
                GROUP BY pp.id
            ");
            $stmt->execute([$purchaseId, $providerId]);
            $purchase = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$purchase) {
                $this->pdo->rollBack();
                $this->errorResponse('Satın alma bulunamadı', 404);
            }
            
            $deliveredCount = intval($purchase['delivered_count']);
            $totalLeads = intval($purchase['leads_count']);
            
            if ($deliveredCount >= $totalLeads) {
                $this->pdo->rollBack();
                $this->errorResponse('Tüm lead\'ler teslim edildi', 400);
            }
            
            // Daha önce gönderilmiş mi kontrol et
            $stmt = $this->pdo->prepare("SELECT id FROM provider_lead_deliveries WHERE purchase_id = ? AND lead_id = ?");
            $stmt->execute([$purchaseId, $leadId]);
            if ($stmt->fetch()) {
                $this->pdo->rollBack();
                $this->errorResponse('Bu lead zaten gönderilmiş', 400);
            }
            
            // Delivery kaydı oluştur
            $stmt = $this->pdo->prepare("
                INSERT INTO provider_lead_deliveries (purchase_id, provider_id, lead_id, delivery_method, delivered_by, delivered_at)
                VALUES (?, ?, ?, 'system', ?, NOW())
            ");
            $stmt->execute([$purchaseId, $providerId, $leadId, $_SESSION['admin_id']]);
            
            // Lead'i güncelle
            $stmt = $this->pdo->prepare("
                UPDATE leads SET previous_status = status, status = 'sold', is_sent_to_provider = 1, sent_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$leadId]);
            
            // Satın alma used_leads güncelle
            $stmt = $this->pdo->prepare("UPDATE provider_purchases SET used_leads = used_leads + 1 WHERE id = ?");
            $stmt->execute([$purchaseId]);
            
            $this->pdo->commit();
            
            $this->successResponse('Lead başarıyla gönderildi');
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Send lead manually error: " . $e->getMessage());
            $this->errorResponse('Gönderme sırasında hata oluştu', 500);
        }
    }
    
    /**
     * Mevcut lead'leri getir (provider için)
     */
    public function availableLeads(): void
    {
        $this->requireAuth();
        
        $serviceType = $this->sanitizedGet('service_type');
        $city = $this->sanitizedGet('city');
        
        if (empty($serviceType)) {
            $this->errorResponse('Service type required', 400);
        }
        
        try {
            $params = [$serviceType];
            $cityCondition = "";
            
            if (!empty($city)) {
                $cityCondition = "AND city = ?";
                $params[] = $city;
            }
            
            $stmt = $this->pdo->prepare("
                SELECT id, service_type, city, phone, description, service_time_type, 
                       scheduled_date, status, created_at, is_sent_to_provider
                FROM leads
                WHERE service_type = ?
                $cityCondition
                AND status IN ('new', 'verified', 'pending')
                AND (is_sent_to_provider = 0 OR is_sent_to_provider IS NULL)
                AND deleted_at IS NULL
                ORDER BY created_at DESC
                LIMIT 50
            ");
            $stmt->execute($params);
            $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $this->successResponse('Mevcut lead\'ler', ['leads' => $leads, 'count' => count($leads)]);
        } catch (PDOException $e) {
            error_log("Available leads error: " . $e->getMessage());
            $this->errorResponse('Lead\'ler alınamadı', 500);
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
     * Status'a göre talep sayısı
     */
    private function getRequestCountByStatus(string $status): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM lead_requests WHERE status = ?");
        $stmt->execute([$status]);
        return (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
    }
}

