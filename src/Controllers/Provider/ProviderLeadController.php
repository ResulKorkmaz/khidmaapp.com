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
            // Provider'a gönderilmiş lead'leri getir (gizlenmiş olanlar hariç)
            $whereClause = "WHERE pld.provider_id = ? AND phl.id IS NULL";
            $params = [$providerId];
            
            if ($statusFilter !== 'all') {
                $whereClause .= " AND l.status = ?";
                $params[] = $statusFilter;
            }
            
            // Toplam sayı (gizlenmiş olanlar hariç)
            $countSql = "
                SELECT COUNT(DISTINCT l.id) as count
                FROM leads l
                INNER JOIN provider_lead_deliveries pld ON l.id = pld.lead_id
                LEFT JOIN provider_hidden_leads phl ON l.id = phl.lead_id AND phl.provider_id = pld.provider_id
                $whereClause
            ";
            $stmt = $this->db->prepare($countSql);
            $stmt->execute($params);
            $totalLeads = (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];
            $totalPages = ceil($totalLeads / $perPage);
            
            // Lead'leri getir (gizlenmiş olanlar hariç)
            $params[] = $perPage;
            $params[] = $offset;
            
            $sql = "
                SELECT l.*, pld.delivered_at, pld.viewed_at, pld.viewed_count, pld.delivery_method,
                       pp.id as purchase_id
                FROM leads l
                INNER JOIN provider_lead_deliveries pld ON l.id = pld.lead_id
                LEFT JOIN provider_purchases pp ON pld.purchase_id = pp.id
                LEFT JOIN provider_hidden_leads phl ON l.id = phl.lead_id AND phl.provider_id = pld.provider_id
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
            
            // Son talep zamanı ve bekleme durumu
            $lastRequestInfo = $this->getLastRequestInfo($providerId);
            
            $this->render('leads', [
                'leads' => $leads,
                'stats' => $stats,
                'purchases' => $purchases,
                'lastRequestInfo' => $lastRequestInfo,
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
            
            // 🔥 Akıllı bekleme kontrolü
            // - Lead teslim edilmişse: 90 dakika
            // - Lead teslim edilmemişse (pending): 48 saat
            $stmt = $this->db->prepare("
                SELECT lr.*, 
                       CASE WHEN lr.lead_id IS NOT NULL THEN 'delivered' ELSE 'pending' END as delivery_status
                FROM lead_requests lr
                WHERE lr.provider_id = ? 
                ORDER BY lr.requested_at DESC 
                LIMIT 1
            ");
            $stmt->execute([$providerId]);
            $lastRequest = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($lastRequest) {
                $lastRequestTime = strtotime($lastRequest['requested_at']);
                $isDelivered = ($lastRequest['delivery_status'] === 'delivered') || ($lastRequest['request_status'] === 'completed');
                
                // Bekleme süresi
                $cooldownMinutes = $isDelivered ? 90 : (48 * 60); // 90 dk veya 48 saat
                $cooldownSeconds = $cooldownMinutes * 60;
                $timePassed = time() - $lastRequestTime;
                
                if ($timePassed < $cooldownSeconds) {
                    $remainingMinutes = ceil(($cooldownSeconds - $timePassed) / 60);
                    
                    if ($isDelivered) {
                        $this->errorResponse("يرجى الانتظار {$remainingMinutes} دقيقة قبل طلب عميل جديد", 429);
                    } else {
                        $remainingHours = floor($remainingMinutes / 60);
                        $remainingMins = $remainingMinutes % 60;
                        $this->errorResponse("طلبك السابق قيد الانتظار. يرجى الانتظار {$remainingHours} ساعة و {$remainingMins} دقيقة", 429);
                    }
                }
            }
            
            // Lead talebi oluştur (admin'in göndermesi için)
            $stmt = $this->db->prepare("
                INSERT INTO lead_requests (provider_id, purchase_id, request_status, requested_at)
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
     * 180 gün sonra lead'ler tamamen gizlenir (görünmez)
     */
    public function hidden(): void
    {
        $this->requireAuth();
        
        $providerId = $this->getProviderId();
        $provider = $this->getProvider();
        
        // Sayfalama
        $page = max(1, $this->intGet('page', 1));
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        
        // 180 gün = lead'ler bu süreden sonra görünmez
        $retentionDays = 180;
        
        try {
            // Toplam silinen lead sayısı (tüm zamanlar - istatistik için)
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as total_deleted
                FROM provider_hidden_leads phl
                WHERE phl.provider_id = ?
            ");
            $stmt->execute([$providerId]);
            $totalDeletedAllTime = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total_deleted'];
            
            // 180 gün içindeki lead'ler (görünür olanlar)
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count
                FROM leads l
                INNER JOIN provider_hidden_leads phl ON l.id = phl.lead_id
                WHERE phl.provider_id = ? 
                AND l.deleted_at IS NULL
                AND phl.hidden_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
            ");
            $stmt->execute([$providerId, $retentionDays]);
            $totalVisibleLeads = (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];
            $totalPages = ceil($totalVisibleLeads / $perPage);
            
            // 180 günden eski lead'ler (tamamen gizli)
            $expiredLeads = $totalDeletedAllTime - $totalVisibleLeads;
            
            // Görünür lead'leri getir (180 gün içinde silinmiş)
            $stmt = $this->db->prepare("
                SELECT l.*, phl.hidden_at,
                       DATEDIFF(DATE_ADD(phl.hidden_at, INTERVAL ? DAY), NOW()) as days_remaining
                FROM leads l
                INNER JOIN provider_hidden_leads phl ON l.id = phl.lead_id
                WHERE phl.provider_id = ? 
                AND l.deleted_at IS NULL
                AND phl.hidden_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
                ORDER BY phl.hidden_at DESC
                LIMIT ? OFFSET ?
            ");
            $stmt->execute([$retentionDays, $providerId, $retentionDays, $perPage, $offset]);
            $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $this->render('hidden_leads', [
                'leads' => $leads,
                'provider' => $provider,
                'page' => $page,
                'totalPages' => $totalPages,
                'totalVisibleLeads' => $totalVisibleLeads,
                'totalDeletedAllTime' => $totalDeletedAllTime,
                'expiredLeads' => $expiredLeads,
                'retentionDays' => $retentionDays
            ]);
        } catch (PDOException $e) {
            error_log("Hidden leads error: " . $e->getMessage());
            $_SESSION['error'] = 'Gizli lead\'ler yüklenirken hata oluştu';
            $this->redirect('/provider/dashboard');
        }
    }
    
    /**
     * Lead'i geri yükle (çöp kutusundan çıkar)
     */
    public function restore(): void
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
            // Gizli lead kaydını sil
            $stmt = $this->db->prepare("
                DELETE FROM provider_hidden_leads 
                WHERE provider_id = ? AND lead_id = ?
            ");
            $stmt->execute([$providerId, $leadId]);
            
            if ($stmt->rowCount() > 0) {
                $this->successResponse('تم استعادة الطلب بنجاح');
            } else {
                $this->errorResponse('الطلب غير موجود في سلة المحذوفات', 404);
            }
        } catch (PDOException $e) {
            error_log("Restore lead error: " . $e->getMessage());
            $this->errorResponse('حدث خطأ أثناء الاستعادة', 500);
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
                SELECT pp.*, lp.name_ar as lp_name, lp.lead_count as package_lead_count
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
     * Son lead talep bilgisi - Akıllı bekleme süresi
     * - Lead teslim edilmişse: 90 dakika sonra yeni talep
     * - Lead teslim edilmemişse (pending): 48 saat sonra tekrar talep
     */
    private function getLastRequestInfo(int $providerId): array
    {
        try {
            // Son talebi ve durumunu getir
            $stmt = $this->db->prepare("
                SELECT lr.*, 
                       CASE WHEN lr.lead_id IS NOT NULL THEN 'delivered' ELSE 'pending' END as delivery_status
                FROM lead_requests lr
                WHERE lr.provider_id = ? 
                ORDER BY lr.requested_at DESC 
                LIMIT 1
            ");
            $stmt->execute([$providerId]);
            $lastRequest = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$lastRequest) {
                return [
                    'canRequest' => true, 
                    'remainingMinutes' => 0, 
                    'lastRequestTime' => null,
                    'waitReason' => null
                ];
            }
            
            $lastRequestTime = strtotime($lastRequest['requested_at']);
            $isDelivered = ($lastRequest['delivery_status'] === 'delivered') || ($lastRequest['request_status'] === 'completed');
            
            // Bekleme süresi: Teslim edildiyse 90 dk, edilmediyse 48 saat
            if ($isDelivered) {
                $cooldownMinutes = 90; // 90 dakika
                $waitReason = 'delivered';
            } else {
                $cooldownMinutes = 48 * 60; // 48 saat = 2880 dakika
                $waitReason = 'pending';
            }
            
            $cooldownSeconds = $cooldownMinutes * 60;
            $timePassed = time() - $lastRequestTime;
            
            if ($timePassed >= $cooldownSeconds) {
                return [
                    'canRequest' => true, 
                    'remainingMinutes' => 0, 
                    'lastRequestTime' => $lastRequest['requested_at'],
                    'waitReason' => null
                ];
            }
            
            $remainingMinutes = ceil(($cooldownSeconds - $timePassed) / 60);
            $remainingHours = floor($remainingMinutes / 60);
            
            return [
                'canRequest' => false, 
                'remainingMinutes' => $remainingMinutes,
                'remainingHours' => $remainingHours,
                'lastRequestTime' => $lastRequest['requested_at'],
                'waitReason' => $waitReason,
                'isDelivered' => $isDelivered
            ];
        } catch (PDOException $e) {
            error_log("Get last request info error: " . $e->getMessage());
            return ['canRequest' => true, 'remainingMinutes' => 0, 'lastRequestTime' => null, 'waitReason' => null];
        }
    }
    
    /**
     * Provider için lead istatistikleri
     */
    private function getLeadStats(int $providerId): array
    {
        try {
            $stats = [];
            
            // Toplam satın alınan lead hakkı (tüm paketlerden)
            $stmt = $this->db->prepare("
                SELECT COALESCE(SUM(leads_count), 0) as total_rights
                FROM provider_purchases 
                WHERE provider_id = ? AND payment_status = 'completed' AND status = 'active'
            ");
            $stmt->execute([$providerId]);
            $stats['total_rights'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total_rights'];
            
            // Teslim edilen lead sayısı
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM provider_lead_deliveries WHERE provider_id = ?");
            $stmt->execute([$providerId]);
            $stats['delivered'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Kalan lead hakkı
            $stmt = $this->db->prepare("
                SELECT COALESCE(SUM(remaining_leads), 0) as remaining
                FROM provider_purchases 
                WHERE provider_id = ? AND payment_status = 'completed' AND status = 'active'
            ");
            $stmt->execute([$providerId]);
            $stats['remaining'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['remaining'];
            
            // Görüntülenen (tamamlanan) lead sayısı
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM provider_lead_deliveries WHERE provider_id = ? AND viewed_at IS NOT NULL");
            $stmt->execute([$providerId]);
            $stats['viewed'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Görüntülenmemiş lead sayısı
            $stats['not_viewed'] = $stats['delivered'] - $stats['viewed'];
            
            // Eski uyumluluk için
            $stats['total'] = $stats['delivered'];
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Get lead stats error: " . $e->getMessage());
            return ['total_rights' => 0, 'delivered' => 0, 'remaining' => 0, 'viewed' => 0, 'not_viewed' => 0, 'total' => 0];
        }
    }
}

