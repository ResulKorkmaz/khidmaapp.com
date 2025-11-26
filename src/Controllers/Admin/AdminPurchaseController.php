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
                $whereClause .= " AND lr.request_status = ?";
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
                    sp.name as provider_name, sp.phone as provider_phone, sp.email as provider_email, sp.service_type as provider_service_type, sp.city as provider_city,
                    pp.package_name, pp.leads_count, pp.remaining_leads,
                    l.service_type as lead_service_type, l.city as lead_city, l.phone as lead_phone, l.description as lead_description
                FROM lead_requests lr
                JOIN service_providers sp ON lr.provider_id = sp.id
                LEFT JOIN provider_purchases pp ON lr.purchase_id = pp.id
                LEFT JOIN leads l ON lr.lead_id = l.id
                $whereClause
                ORDER BY lr.requested_at DESC
                LIMIT ? OFFSET ?
            ");
            $stmt->execute($params);
            $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // İstatistikler
            $stats = [
                'pending' => $this->getRequestCountByStatus('pending'),
                'completed' => $this->getRequestCountByStatus('completed'),
                'cancelled' => $this->getRequestCountByStatus('cancelled'),
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
        
        // FormData veya JSON kabul et
        $leadId = intval($_POST['lead_id'] ?? 0);
        $requestId = intval($_POST['request_id'] ?? 0);
        $purchaseId = intval($_POST['purchase_id'] ?? 0);
        
        // JSON input da kontrol et
        if ($leadId <= 0 || $requestId <= 0) {
            $data = $this->getJsonInput();
            $leadId = $leadId ?: intval($data['lead_id'] ?? 0);
            $requestId = $requestId ?: intval($data['request_id'] ?? 0);
            $purchaseId = $purchaseId ?: intval($data['purchase_id'] ?? 0);
        }
        
        if ($leadId <= 0 || $requestId <= 0) {
            $this->errorResponse('Geçersiz parametreler', 400);
        }
        
        try {
            $this->pdo->beginTransaction();
            
            // Request'ten provider ve purchase bilgisini al
            $stmt = $this->pdo->prepare("
                SELECT lr.provider_id, lr.purchase_id, sp.name as provider_name
                FROM lead_requests lr
                JOIN service_providers sp ON lr.provider_id = sp.id
                WHERE lr.id = ? AND lr.request_status = 'pending'
            ");
            $stmt->execute([$requestId]);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$request) {
                $this->pdo->rollBack();
                $this->errorResponse('Lead talebi bulunamadı veya zaten işlenmiş', 404);
            }
            
            $providerId = intval($request['provider_id']);
            $purchaseId = $purchaseId ?: intval($request['purchase_id']);
            
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
            
            // Satın alma used_leads ve remaining_leads güncelle
            $stmt = $this->pdo->prepare("UPDATE provider_purchases SET used_leads = used_leads + 1, remaining_leads = remaining_leads - 1 WHERE id = ?");
            $stmt->execute([$purchaseId]);
            
            // Lead request'i completed olarak işaretle
            $stmt = $this->pdo->prepare("UPDATE lead_requests SET request_status = 'completed', lead_id = ?, completed_at = NOW() WHERE id = ?");
            $stmt->execute([$leadId, $requestId]);
            
            $this->pdo->commit();
            
            $this->successResponse('Lead başarıyla gönderildi', ['provider_name' => $request['provider_name'] ?? '']);
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Send lead manually error: " . $e->getMessage());
            $this->errorResponse('Gönderme sırasında hata oluştu', 500);
        }
    }
    
    /**
     * Mevcut lead'leri getir (provider için)
     * En eskiden en yeniye sıralı - aynı şehir ve hizmet türü filtrelemeli
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
            
            // En eskiden en yeniye sıralama (ASC) - adil dağıtım için
            $stmt = $this->pdo->prepare("
                SELECT id, service_type, city, phone, description, service_time_type, 
                       scheduled_date, status, created_at, is_sent_to_provider
                FROM leads
                WHERE service_type = ?
                $cityCondition
                AND status IN ('new', 'verified', 'pending')
                AND (is_sent_to_provider = 0 OR is_sent_to_provider IS NULL)
                AND deleted_at IS NULL
                ORDER BY created_at ASC
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
    
    /**
     * WhatsApp üzerinden gönderildi olarak işaretle
     */
    public function markAsSentViaWhatsApp(): void
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
                $this->errorResponse('Satın alma bulunamadı', 404);
            }
            
            $deliveredCount = intval($purchase['delivered_count']);
            $totalLeads = intval($purchase['leads_count']);
            
            if ($deliveredCount >= $totalLeads) {
                $this->errorResponse('Tüm lead\'ler teslim edildi', 400);
            }
            
            // Daha önce gönderilmiş mi kontrol et
            $stmt = $this->pdo->prepare("SELECT id FROM provider_lead_deliveries WHERE purchase_id = ? AND lead_id = ?");
            $stmt->execute([$purchaseId, $leadId]);
            if ($stmt->fetch()) {
                $this->errorResponse('Bu lead zaten gönderilmiş', 400);
            }
            
            $this->pdo->beginTransaction();
            
            // Delivery kaydı oluştur (whatsapp method)
            $stmt = $this->pdo->prepare("
                INSERT INTO provider_lead_deliveries 
                (purchase_id, provider_id, lead_id, delivery_method, delivered_by, delivered_at)
                VALUES (?, ?, ?, 'whatsapp', ?, NOW())
            ");
            $stmt->execute([$purchaseId, $providerId, $leadId, $_SESSION['admin_id'] ?? null]);
            
            // Purchase used_leads güncelle
            $stmt = $this->pdo->prepare("
                UPDATE provider_purchases 
                SET used_leads = used_leads + 1, remaining_leads = remaining_leads - 1
                WHERE id = ?
            ");
            $stmt->execute([$purchaseId]);
            
            // Lead'i satıldı olarak işaretle
            $stmt = $this->pdo->prepare("
                UPDATE leads 
                SET previous_status = status, is_sent_to_provider = 1, sent_at = NOW(), status = 'sold'
                WHERE id = ?
            ");
            $stmt->execute([$leadId]);
            
            $this->pdo->commit();
            
            error_log("✅ Admin marked lead $leadId as sent to provider $providerId via WhatsApp. Status: sold");
            
            $this->successResponse('Lead WhatsApp üzerinden gönderildi ve satıldı olarak işaretlendi');
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Mark lead as sent via WhatsApp error: " . $e->getMessage());
            $this->errorResponse('Veritabanı hatası', 500);
        }
    }
    
    /**
     * Müsait provider'ları (lead'leri) getir
     */
    public function getAvailableProviders(): void
    {
        $this->requireAuth();
        
        $serviceType = $this->sanitizedGet('service_type');
        $city = $this->sanitizedGet('city');
        
        error_log("🔍 GET AVAILABLE LEADS - Service Type: '$serviceType' - City: '$city'");
        
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
            
            // En eskiden en yeniye sıralama (ASC) - adil dağıtım için
            $stmt = $this->pdo->prepare("
                SELECT 
                    id, service_type, city, phone, whatsapp_phone, description,
                    budget_min, budget_max, service_time_type, scheduled_date,
                    status, created_at, is_sent_to_provider
                FROM leads
                WHERE service_type = ?
                $cityCondition
                AND status IN ('new', 'verified', 'pending')
                AND (is_sent_to_provider = 0 OR is_sent_to_provider IS NULL)
                AND deleted_at IS NULL
                ORDER BY created_at ASC
                LIMIT 50
            ");
            $stmt->execute($params);
            $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("✅ Found " . count($leads) . " leads for service type: '$serviceType' and city: '$city'");
            
            // Debug bilgileri
            $stmt2 = $this->pdo->prepare("SELECT COUNT(*) as total FROM leads WHERE service_type = ?");
            $stmt2->execute([$serviceType]);
            $total = $stmt2->fetch(PDO::FETCH_ASSOC)['total'];
            
            $stmt3 = $this->pdo->prepare("SELECT COUNT(*) as sent FROM leads WHERE service_type = ? AND is_sent_to_provider = 1");
            $stmt3->execute([$serviceType]);
            $sent = $stmt3->fetch(PDO::FETCH_ASSOC)['sent'];
            
            $this->successResponse('Mevcut lead\'ler', [
                'leads' => $leads,
                'debug' => [
                    'total_in_db' => $total,
                    'already_sent' => $sent,
                    'available' => count($leads)
                ]
            ]);
        } catch (PDOException $e) {
            error_log("❌ Get available providers error: " . $e->getMessage());
            $this->errorResponse('Veritabanı hatası', 500);
        }
    }
    
    /**
     * Seçili lead'leri provider'a ata
     */
    public function assignLeadsToProvider(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('Method not allowed', 405);
        }
        
        $data = $this->getJsonInput();
        $providerId = intval($data['provider_id'] ?? 0);
        $leadIds = $data['lead_ids'] ?? [];
        
        if ($providerId <= 0 || empty($leadIds) || !is_array($leadIds)) {
            $this->errorResponse('Geçersiz parametreler', 400);
        }
        
        try {
            $this->pdo->beginTransaction();
            
            // Provider kontrolü
            $stmt = $this->pdo->prepare("SELECT id, name, status FROM service_providers WHERE id = ?");
            $stmt->execute([$providerId]);
            $provider = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$provider) {
                $this->pdo->rollBack();
                $this->errorResponse('Usta bulunamadı', 404);
            }
            
            if ($provider['status'] !== 'active') {
                $this->pdo->rollBack();
                $this->errorResponse('Usta aktif değil', 400);
            }
            
            // Provider'ın aktif purchase'ını bul
            $stmt = $this->pdo->prepare("
                SELECT id, leads_count, used_leads, remaining_leads
                FROM provider_purchases 
                WHERE provider_id = ? AND remaining_leads > 0 AND payment_status = 'completed'
                ORDER BY purchased_at ASC
                LIMIT 1
            ");
            $stmt->execute([$providerId]);
            $purchase = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $assignedCount = 0;
            $errors = [];
            $adminId = $_SESSION['admin_id'] ?? null;
            
            foreach ($leadIds as $leadId) {
                $leadId = intval($leadId);
                if ($leadId <= 0) continue;
                
                // Lead kontrolü
                $stmt = $this->pdo->prepare("SELECT id, service_type, status FROM leads WHERE id = ? AND deleted_at IS NULL");
                $stmt->execute([$leadId]);
                $lead = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$lead) {
                    $errors[] = "Lead #{$leadId} bulunamadı";
                    continue;
                }
                
                if ($lead['status'] === 'withdrawn') {
                    $errors[] = "⚠️ Lead #{$leadId} daha önce GERİ ÇEKİLMİŞ!";
                }
                
                // Lead'i sold yap
                $stmt = $this->pdo->prepare("
                    UPDATE leads 
                    SET previous_status = status, status = 'sold', is_sent_to_provider = 1, sent_at = NOW()
                    WHERE id = ?
                ");
                $result = $stmt->execute([$leadId]);
                
                if (!$result) {
                    $errors[] = "Lead #{$leadId} status güncellenemedi";
                    continue;
                }
                
                // Delivery kaydı oluştur (purchase varsa)
                if ($purchase && $purchase['remaining_leads'] > 0) {
                    try {
                        $stmt = $this->pdo->prepare("
                            INSERT INTO provider_lead_deliveries 
                            (purchase_id, provider_id, lead_id, delivery_method, delivered_by, delivered_at)
                            VALUES (?, ?, ?, 'system', ?, NOW())
                        ");
                        $stmt->execute([$purchase['id'], $providerId, $leadId, $adminId]);
                        
                        $stmt = $this->pdo->prepare("
                            UPDATE provider_purchases 
                            SET used_leads = used_leads + 1, remaining_leads = remaining_leads - 1
                            WHERE id = ?
                        ");
                        $stmt->execute([$purchase['id']]);
                        
                        $purchase['remaining_leads']--;
                        
                    } catch (PDOException $e) {
                        if ($e->getCode() == 23000) {
                            error_log("⚠️ Lead #{$leadId} already delivered");
                        } else {
                            throw $e;
                        }
                    }
                }
                
                $assignedCount++;
                error_log("✅ Admin assigned lead #{$leadId} to provider #{$providerId} ({$provider['name']})");
            }
            
            $this->pdo->commit();
            
            $this->successResponse('Lead\'ler atandı', [
                'assigned_count' => $assignedCount,
                'errors' => $errors,
                'provider_name' => $provider['name']
            ]);
            
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("❌ Assign leads error: " . $e->getMessage());
            $this->errorResponse('Veritabanı hatası', 500);
        }
    }
    
    /**
     * Lead'i provider'dan geri çek
     */
    public function withdrawLead(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('Method not allowed', 405);
        }
        
        $data = $this->getJsonInput();
        $leadId = intval($data['lead_id'] ?? 0);
        $providerId = intval($data['provider_id'] ?? 0);
        
        if ($leadId <= 0) {
            $this->errorResponse('Geçersiz lead ID', 400);
        }
        
        try {
            $this->pdo->beginTransaction();
            
            // Lead'in önceki durumunu al
            $stmt = $this->pdo->prepare("SELECT previous_status FROM leads WHERE id = ?");
            $stmt->execute([$leadId]);
            $lead = $stmt->fetch(PDO::FETCH_ASSOC);
            $previousStatus = $lead['previous_status'] ?? 'verified';
            
            // Lead'i güncelle
            $stmt = $this->pdo->prepare("
                UPDATE leads 
                SET status = 'withdrawn', is_sent_to_provider = 0, sent_at = NULL
                WHERE id = ?
            ");
            $stmt->execute([$leadId]);
            
            // Delivery kaydını sil
            if ($providerId > 0) {
                $stmt = $this->pdo->prepare("DELETE FROM provider_lead_deliveries WHERE lead_id = ? AND provider_id = ?");
                $stmt->execute([$leadId, $providerId]);
            } else {
                $stmt = $this->pdo->prepare("DELETE FROM provider_lead_deliveries WHERE lead_id = ?");
                $stmt->execute([$leadId]);
            }
            
            $this->pdo->commit();
            
            error_log("✅ Lead #{$leadId} withdrawn from provider #{$providerId}");
            
            $this->successResponse('Lead provider\'dan geri çekildi');
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Withdraw lead error: " . $e->getMessage());
            $this->errorResponse('Geri çekme sırasında hata oluştu', 500);
        }
    }
    
    // ==================== REFUND METHODS ====================
    
    /**
     * İade listesi sayfası
     */
    public function refunds(): void
    {
        $this->requireAuth();
        
        try {
            $stmt = $this->pdo->query("
                SELECT 
                    r.*,
                    pp.package_name,
                    pp.price_paid,
                    sp.name as provider_name,
                    sp.email as provider_email,
                    sp.phone as provider_phone,
                    a.username as refunded_by_name
                FROM refunds r
                INNER JOIN provider_purchases pp ON r.purchase_id = pp.id
                INNER JOIN service_providers sp ON r.provider_id = sp.id
                LEFT JOIN admins a ON r.refunded_by = a.id
                ORDER BY r.requested_at DESC
            ");
            $refunds = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // İstatistikler
            $stats = $this->getRefundStats();
            
            $this->render('refunds', [
                'refunds' => $refunds,
                'stats' => $stats,
                'currentPage' => 'refunds'
            ]);
        } catch (PDOException $e) {
            error_log("Refunds list error: " . $e->getMessage());
            $_SESSION['error'] = 'İadeler yüklenirken hata oluştu';
            $this->redirect('/admin');
        }
    }
    
    /**
     * İade oluştur (Stripe ile)
     */
    public function createRefund(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('Method not allowed', 405);
        }
        
        $purchaseId = intval($_POST['purchase_id'] ?? 0);
        $refundType = $_POST['refund_type'] ?? 'full'; // full veya partial
        $refundAmount = floatval($_POST['refund_amount'] ?? 0);
        $reason = $_POST['reason'] ?? 'customer_request';
        $reasonDetails = trim($_POST['reason_details'] ?? '');
        $notes = trim($_POST['notes'] ?? '');
        
        if ($purchaseId <= 0) {
            $this->errorResponse('Geçersiz satın alma ID', 400);
        }
        
        try {
            // Satın alma bilgilerini al
            $stmt = $this->pdo->prepare("
                SELECT pp.*, sp.name as provider_name, sp.email as provider_email
                FROM provider_purchases pp
                INNER JOIN service_providers sp ON pp.provider_id = sp.id
                WHERE pp.id = ?
            ");
            $stmt->execute([$purchaseId]);
            $purchase = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$purchase) {
                $this->errorResponse('Satın alma bulunamadı', 404);
            }
            
            if ($purchase['payment_status'] !== 'completed') {
                $this->errorResponse('Bu satın alma henüz tamamlanmamış', 400);
            }
            
            if ($purchase['refund_status'] === 'full') {
                $this->errorResponse('Bu satın alma zaten tamamen iade edilmiş', 400);
            }
            
            // İade miktarını hesapla
            $paidAmount = floatval($purchase['price_paid']);
            $alreadyRefunded = floatval($purchase['refunded_amount'] ?? 0);
            $maxRefundable = $paidAmount - $alreadyRefunded;
            
            if ($refundType === 'full') {
                $refundAmount = $maxRefundable;
            } else {
                if ($refundAmount <= 0 || $refundAmount > $maxRefundable) {
                    $this->errorResponse("İade miktarı 0 ile {$maxRefundable} SAR arasında olmalı", 400);
                }
            }
            
            $this->pdo->beginTransaction();
            
            // Stripe ile iade yap
            $stripeRefundId = null;
            $stripeError = null;
            
            if (!empty($purchase['stripe_payment_intent_id'])) {
                try {
                    require_once __DIR__ . '/../../../vendor/autoload.php';
                    require_once __DIR__ . '/../../config/stripe.php';
                    
                    $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);
                    
                    // Stripe'da iade oluştur
                    $refund = $stripe->refunds->create([
                        'payment_intent' => $purchase['stripe_payment_intent_id'],
                        'amount' => intval($refundAmount * 100), // Kuruş cinsinden
                        'reason' => $this->mapReasonToStripe($reason),
                        'metadata' => [
                            'purchase_id' => $purchaseId,
                            'provider_id' => $purchase['provider_id'],
                            'admin_id' => $_SESSION['admin_id'],
                            'reason' => $reason
                        ]
                    ]);
                    
                    $stripeRefundId = $refund->id;
                    $refundStatus = 'completed';
                    
                } catch (\Stripe\Exception\ApiErrorException $e) {
                    $stripeError = $e->getMessage();
                    error_log("Stripe refund error: " . $stripeError);
                    $refundStatus = 'failed';
                }
            } else {
                // Stripe payment intent yoksa manuel iade olarak işaretle
                $refundStatus = 'completed';
                $stripeRefundId = 'MANUAL_' . time();
            }
            
            // Refund kaydı oluştur
            $stmt = $this->pdo->prepare("
                INSERT INTO refunds (
                    purchase_id, provider_id, stripe_payment_intent_id, stripe_refund_id,
                    amount, currency, reason, reason_details, refund_type, status,
                    refunded_by, requested_at, processed_at, notes
                ) VALUES (?, ?, ?, ?, ?, 'SAR', ?, ?, ?, ?, ?, NOW(), NOW(), ?)
            ");
            $stmt->execute([
                $purchaseId,
                $purchase['provider_id'],
                $purchase['stripe_payment_intent_id'],
                $stripeRefundId,
                $refundAmount,
                $reason,
                $reasonDetails,
                $refundType,
                $refundStatus,
                $_SESSION['admin_id'],
                $notes
            ]);
            
            // Satın alma kaydını güncelle
            $newRefundedAmount = $alreadyRefunded + $refundAmount;
            $newRefundStatus = ($newRefundedAmount >= $paidAmount) ? 'full' : 'partial';
            
            $stmt = $this->pdo->prepare("
                UPDATE provider_purchases 
                SET refund_status = ?, refunded_amount = ?
                WHERE id = ?
            ");
            $stmt->execute([$newRefundStatus, $newRefundedAmount, $purchaseId]);
            
            $this->pdo->commit();
            
            if ($refundStatus === 'completed') {
                error_log("✅ Refund created: {$refundAmount} SAR for purchase #{$purchaseId}");
                $this->successResponse("İade başarıyla oluşturuldu: {$refundAmount} SAR", [
                    'refund_id' => $stripeRefundId,
                    'amount' => $refundAmount
                ]);
            } else {
                $this->errorResponse("Stripe iade hatası: {$stripeError}", 500);
            }
            
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Create refund error: " . $e->getMessage());
            $this->errorResponse('İade oluşturulurken hata oluştu', 500);
        }
    }
    
    /**
     * Satın alma detayı (iade için)
     */
    public function purchaseDetail(): void
    {
        $this->requireAuth();
        
        $id = $this->intGet('id');
        
        if (!$id) {
            $_SESSION['error'] = 'Geçersiz satın alma ID';
            $this->redirect('/admin/purchases');
        }
        
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
                    lp.lead_count as package_lead_count
                FROM provider_purchases pp
                INNER JOIN service_providers sp ON pp.provider_id = sp.id
                LEFT JOIN lead_packages lp ON pp.package_id = lp.id
                WHERE pp.id = ?
            ");
            $stmt->execute([$id]);
            $purchase = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$purchase) {
                $_SESSION['error'] = 'Satın alma bulunamadı';
                $this->redirect('/admin/purchases');
            }
            
            // Teslim edilen lead'ler
            $stmt = $this->pdo->prepare("
                SELECT pld.*, l.service_type, l.city, l.phone, l.status as lead_status
                FROM provider_lead_deliveries pld
                INNER JOIN leads l ON pld.lead_id = l.id
                WHERE pld.purchase_id = ?
                ORDER BY pld.delivered_at DESC
            ");
            $stmt->execute([$id]);
            $deliveries = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // İade geçmişi
            $stmt = $this->pdo->prepare("
                SELECT r.*, a.username as refunded_by_name
                FROM refunds r
                LEFT JOIN admins a ON r.refunded_by = a.id
                WHERE r.purchase_id = ?
                ORDER BY r.requested_at DESC
            ");
            $stmt->execute([$id]);
            $refundHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $this->render('purchase_detail', [
                'purchase' => $purchase,
                'deliveries' => $deliveries,
                'refundHistory' => $refundHistory,
                'currentPage' => 'purchases'
            ]);
            
        } catch (PDOException $e) {
            error_log("Purchase detail error: " . $e->getMessage());
            $_SESSION['error'] = 'Satın alma detayı yüklenirken hata oluştu';
            $this->redirect('/admin/purchases');
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
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM lead_requests WHERE request_status = ?");
        $stmt->execute([$status]);
        return (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
    }
    
    /**
     * İade istatistikleri
     */
    private function getRefundStats(): array
    {
        $stats = [];
        
        // Toplam iade sayısı
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM refunds");
        $stats['total_refunds'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Toplam iade tutarı
        $stmt = $this->pdo->query("SELECT COALESCE(SUM(amount), 0) as total FROM refunds WHERE status = 'completed'");
        $stats['total_refunded_amount'] = floatval($stmt->fetch(PDO::FETCH_ASSOC)['total']);
        
        // Bekleyen iadeler
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM refunds WHERE status = 'pending'");
        $stats['pending_refunds'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Bu ay iade
        $stmt = $this->pdo->query("
            SELECT COALESCE(SUM(amount), 0) as total 
            FROM refunds 
            WHERE status = 'completed' AND MONTH(processed_at) = MONTH(CURDATE()) AND YEAR(processed_at) = YEAR(CURDATE())
        ");
        $stats['this_month_refunds'] = floatval($stmt->fetch(PDO::FETCH_ASSOC)['total']);
        
        return $stats;
    }
    
    /**
     * Sebep kodunu Stripe formatına çevir
     */
    private function mapReasonToStripe(string $reason): string
    {
        $map = [
            'customer_request' => 'requested_by_customer',
            'invalid_lead' => 'requested_by_customer',
            'duplicate' => 'duplicate',
            'service_issue' => 'requested_by_customer',
            'other' => 'requested_by_customer'
        ];
        
        return $map[$reason] ?? 'requested_by_customer';
    }
}

