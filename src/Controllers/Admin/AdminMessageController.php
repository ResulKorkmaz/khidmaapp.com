<?php

/**
 * KhidmaApp.com - Admin Message Controller
 * 
 * Provider mesajları yönetimi - Toplu ve filtrelenmiş mesaj gönderme
 */

require_once __DIR__ . '/BaseAdminController.php';

class AdminMessageController extends BaseAdminController 
{
    /**
     * Mesajlar listesi
     */
    public function index(): void
    {
        $this->requireAuth();
        
        try {
            $page = max(1, $this->intGet('page', 1));
            $perPage = 12;
            $offset = ($page - 1) * $perPage;
            
            // Filtreler
            $filterCity = $this->getParam('city', '');
            $filterService = $this->getParam('service', '');
            $filterStatus = $this->getParam('status', '');
            $search = trim($this->getParam('search', ''));
            
            // WHERE koşulları oluştur
            $where = "sp.status IN ('active', 'pending')";
            $params = [];
            
            if ($filterCity) {
                $where .= " AND sp.city = ?";
                $params[] = $filterCity;
            }
            if ($filterService) {
                $where .= " AND sp.service_type = ?";
                $params[] = $filterService;
            }
            if ($filterStatus) {
                $where .= " AND sp.status = ?";
                $params[] = $filterStatus;
            }
            if ($search) {
                $where .= " AND (sp.name LIKE ? OR sp.email LIKE ? OR sp.phone LIKE ?)";
                $searchTerm = "%{$search}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            // Toplam provider sayısı
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as total
                FROM service_providers sp
                WHERE {$where}
            ");
            $stmt->execute($params);
            $totalProviders = $stmt->fetchColumn();
            $totalPages = max(1, ceil($totalProviders / $perPage));
            
            // Provider'ları getir
            $params[] = $perPage;
            $params[] = $offset;
            $stmt = $this->pdo->prepare("
                SELECT 
                    sp.id, sp.name, sp.email, sp.phone, sp.service_type, sp.city, sp.status,
                    (SELECT COUNT(*) FROM provider_messages WHERE provider_id = sp.id AND deleted_at IS NULL) as message_count,
                    (SELECT COUNT(*) FROM provider_messages WHERE provider_id = sp.id AND is_read = 0 AND deleted_at IS NULL) as unread_count
                FROM service_providers sp
                WHERE {$where}
                ORDER BY sp.created_at DESC
                LIMIT ? OFFSET ?
            ");
            $stmt->execute($params);
            $providers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // İstatistikler
            $stmt = $this->pdo->prepare("
                SELECT 
                    COUNT(*) as total_messages,
                    SUM(CASE WHEN is_read = 0 THEN 1 ELSE 0 END) as unread_messages,
                    COUNT(DISTINCT provider_id) as providers_with_messages
                FROM provider_messages
                WHERE deleted_at IS NULL
            ");
            $stmt->execute();
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Şehir ve hizmet bazlı istatistikler
            $stmt = $this->pdo->query("
                SELECT city, COUNT(*) as count 
                FROM service_providers 
                WHERE status IN ('active', 'pending') 
                GROUP BY city 
                ORDER BY count DESC
            ");
            $cityCounts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            $stmt = $this->pdo->query("
                SELECT service_type, COUNT(*) as count 
                FROM service_providers 
                WHERE status IN ('active', 'pending') 
                GROUP BY service_type 
                ORDER BY count DESC
            ");
            $serviceCounts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            // Aktif provider sayısı (tüm filtreler olmadan)
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM service_providers WHERE status = 'active'");
            $activeProviderCount = $stmt->fetchColumn();
            
            // Uygun lead'leri getir (tek kişiye gönderim için)
            $stmt = $this->pdo->query("
                SELECT id, service_type, city, phone, description, created_at 
                FROM leads 
                WHERE status = 'new' AND (is_sent_to_provider = 0 OR is_sent_to_provider IS NULL)
                ORDER BY created_at ASC
                LIMIT 50
            ");
            $availableLeads = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Provider Messages Error: " . $e->getMessage());
            
            // Tablo yoksa varsayılan değerler
            $providers = [];
            $stats = ['total_messages' => 0, 'unread_messages' => 0, 'providers_with_messages' => 0];
            $totalPages = 1;
            $totalProviders = 0;
            $cityCounts = [];
            $serviceCounts = [];
            $activeProviderCount = 0;
            $availableLeads = [];
            
            if (strpos($e->getMessage(), "doesn't exist") !== false) {
                $_SESSION['warning_message'] = 'provider_messages tablosu bulunamadı. Migration çalıştırın.';
            }
        }
        
        $this->render('provider_messages', [
            'providers' => $providers,
            'stats' => $stats,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalProviders' => $totalProviders,
            'cityCounts' => $cityCounts,
            'serviceCounts' => $serviceCounts,
            'activeProviderCount' => $activeProviderCount,
            'availableLeads' => $availableLeads,
            'filters' => [
                'city' => $filterCity,
                'service' => $filterService,
                'status' => $filterStatus,
                'search' => $search
            ]
        ]);
    }
    
    /**
     * Tek kişiye mesaj gönder
     */
    public function send(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('Method not allowed', 405);
        }
        
        if (!verifyCsrfToken($this->postParam('csrf_token', ''))) {
            $this->errorResponse('Geçersiz CSRF token', 403);
        }
        
        $providerId = $this->intPost('provider_id');
        $subject = trim($this->postParam('subject', ''));
        $message = trim($this->postParam('message', ''));
        $messageType = $this->sanitizedPost('message_type', 'info');
        $priority = $this->sanitizedPost('priority', 'normal');
        
        if (!$providerId || empty($subject) || empty($message)) {
            $this->errorResponse('Tüm alanlar zorunludur', 400);
        }
        
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO provider_messages (
                    provider_id, sender_type, sender_id, subject, message, message_type, priority, created_at
                ) VALUES (?, 'admin', ?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $providerId,
                $_SESSION['admin_id'],
                $subject,
                $message,
                $messageType,
                $priority
            ]);
            
            $this->successResponse('Mesaj başarıyla gönderildi');
        } catch (PDOException $e) {
            error_log("Send message error: " . $e->getMessage());
            $this->errorResponse('Mesaj gönderilirken bir hata oluştu', 500);
        }
    }
    
    /**
     * Toplu mesaj gönder (tüm, şehir veya sektör bazlı)
     */
    public function sendBulk(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('Method not allowed', 405);
        }
        
        if (!verifyCsrfToken($this->postParam('csrf_token', ''))) {
            $this->errorResponse('Geçersiz CSRF token', 403);
        }
        
        $targetType = $this->sanitizedPost('target_type', 'all'); // all, city, service
        $targetValue = trim($this->postParam('target_value', ''));
        $subject = trim($this->postParam('subject', ''));
        $message = trim($this->postParam('message', ''));
        $messageType = $this->sanitizedPost('message_type', 'announcement');
        $priority = $this->sanitizedPost('priority', 'normal');
        
        if (empty($subject) || empty($message)) {
            $this->errorResponse('Konu ve mesaj zorunludur', 400);
        }
        
        if ($targetType !== 'all' && empty($targetValue)) {
            $this->errorResponse('Hedef değer zorunludur', 400);
        }
        
        try {
            // Hedef provider'ları bul
            $where = "status IN ('active', 'pending')";
            $params = [];
            
            if ($targetType === 'city') {
                $where .= " AND city = ?";
                $params[] = $targetValue;
            } elseif ($targetType === 'service') {
                $where .= " AND service_type = ?";
                $params[] = $targetValue;
            }
            
            $stmt = $this->pdo->prepare("SELECT id FROM service_providers WHERE {$where}");
            $stmt->execute($params);
            $providerIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (empty($providerIds)) {
                $this->errorResponse('Hedef kriterlere uygun provider bulunamadı', 400);
            }
            
            // Toplu mesaj gönder
            $insertStmt = $this->pdo->prepare("
                INSERT INTO provider_messages (
                    provider_id, sender_type, sender_id, subject, message, message_type, priority, created_at
                ) VALUES (?, 'admin', ?, ?, ?, ?, ?, NOW())
            ");
            
            $successCount = 0;
            foreach ($providerIds as $pid) {
                try {
                    $insertStmt->execute([
                        $pid,
                        $_SESSION['admin_id'],
                        $subject,
                        $message,
                        $messageType,
                        $priority
                    ]);
                    $successCount++;
                } catch (PDOException $e) {
                    error_log("Bulk message error for provider {$pid}: " . $e->getMessage());
                }
            }
            
            $this->successResponse("{$successCount} kişiye mesaj başarıyla gönderildi", [
                'sent_count' => $successCount,
                'total_targets' => count($providerIds)
            ]);
        } catch (PDOException $e) {
            error_log("Send bulk message error: " . $e->getMessage());
            $this->errorResponse('Toplu mesaj gönderilirken bir hata oluştu', 500);
        }
    }
    
    /**
     * Tek kişiye lead gönder (mesaj olarak)
     */
    public function sendLead(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('Method not allowed', 405);
        }
        
        if (!verifyCsrfToken($this->postParam('csrf_token', ''))) {
            $this->errorResponse('Geçersiz CSRF token', 403);
        }
        
        $providerId = $this->intPost('provider_id');
        $leadId = $this->intPost('lead_id');
        
        if (!$providerId || !$leadId) {
            $this->errorResponse('Provider ve Lead ID zorunludur', 400);
        }
        
        try {
            // Lead bilgilerini al
            $stmt = $this->pdo->prepare("SELECT * FROM leads WHERE id = ?");
            $stmt->execute([$leadId]);
            $lead = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$lead) {
                $this->errorResponse('Lead bulunamadı', 404);
            }
            
            // Provider bilgilerini al
            $stmt = $this->pdo->prepare("SELECT * FROM service_providers WHERE id = ?");
            $stmt->execute([$providerId]);
            $provider = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$provider) {
                $this->errorResponse('Provider bulunamadı', 404);
            }
            
            $this->pdo->beginTransaction();
            
            // Lead mesajı oluştur
            $serviceTypes = getServiceTypes();
            $cities = getCities();
            $serviceLabel = $serviceTypes[$lead['service_type']]['ar'] ?? $lead['service_type'];
            $cityLabel = $cities[$lead['city']]['ar'] ?? $lead['city'];
            
            $subject = "🎯 طلب خدمة جديد - {$serviceLabel}";
            $message = "📋 تفاصيل الطلب:\n\n";
            $message .= "🔧 نوع الخدمة: {$serviceLabel}\n";
            $message .= "📍 المدينة: {$cityLabel}\n";
            $message .= "📱 رقم الهاتف: {$lead['phone']}\n";
            if (!empty($lead['description'])) {
                $message .= "📝 الوصف: {$lead['description']}\n";
            }
            $message .= "\n⏰ تاريخ الطلب: " . date('Y-m-d H:i', strtotime($lead['created_at']));
            $message .= "\n\n✅ يرجى التواصل مع العميل في أقرب وقت ممكن.";
            
            // Mesaj gönder
            $stmt = $this->pdo->prepare("
                INSERT INTO provider_messages (
                    provider_id, sender_type, sender_id, subject, message, message_type, priority, lead_id, created_at
                ) VALUES (?, 'admin', ?, ?, ?, 'lead', 'high', ?, NOW())
            ");
            $stmt->execute([
                $providerId,
                $_SESSION['admin_id'],
                $subject,
                $message,
                $leadId
            ]);
            
            // Lead'i güncelle
            $stmt = $this->pdo->prepare("
                UPDATE leads 
                SET status = 'sent', is_sent_to_provider = 1, sent_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$leadId]);
            
            // provider_lead_deliveries tablosuna kayıt
            $stmt = $this->pdo->prepare("
                INSERT INTO provider_lead_deliveries (lead_id, provider_id, delivery_method, delivered_at)
                VALUES (?, ?, 'message', NOW())
                ON DUPLICATE KEY UPDATE delivery_method = 'message', delivered_at = NOW()
            ");
            $stmt->execute([$leadId, $providerId]);
            
            $this->pdo->commit();
            
            $this->successResponse('Lead başarıyla gönderildi', [
                'lead_id' => $leadId,
                'provider_id' => $providerId
            ]);
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Send lead error: " . $e->getMessage());
            $this->errorResponse('Lead gönderilirken bir hata oluştu', 500);
        }
    }
    
    /**
     * Provider mesaj geçmişini getir
     */
    public function history(): void
    {
        $this->requireAuth();
        
        $providerId = $this->intGet('provider_id');
        
        if (!$providerId) {
            $this->errorResponse('Geçersiz provider ID', 400);
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT pm.*, a.username as admin_name
                FROM provider_messages pm
                LEFT JOIN admins a ON pm.sender_id = a.id AND pm.sender_type = 'admin'
                WHERE pm.provider_id = ? AND pm.deleted_at IS NULL
                ORDER BY pm.created_at DESC
                LIMIT 50
            ");
            $stmt->execute([$providerId]);
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $this->successResponse('Mesaj geçmişi', ['messages' => $messages]);
        } catch (PDOException $e) {
            error_log("Get message history error: " . $e->getMessage());
            $this->errorResponse('Mesaj geçmişi alınamadı', 500);
        }
    }
    
    /**
     * Provider'ları filtrele (AJAX)
     */
    public function filterProviders(): void
    {
        $this->requireAuth();
        
        $city = $this->getParam('city', '');
        $service = $this->getParam('service', '');
        
        try {
            $where = "status IN ('active', 'pending')";
            $params = [];
            
            if ($city) {
                $where .= " AND city = ?";
                $params[] = $city;
            }
            if ($service) {
                $where .= " AND service_type = ?";
                $params[] = $service;
            }
            
            $stmt = $this->pdo->prepare("
                SELECT id, name, email, phone, service_type, city 
                FROM service_providers 
                WHERE {$where}
                ORDER BY name ASC
                LIMIT 100
            ");
            $stmt->execute($params);
            $providers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $this->successResponse('Providers', ['providers' => $providers, 'count' => count($providers)]);
        } catch (PDOException $e) {
            error_log("Filter providers error: " . $e->getMessage());
            $this->errorResponse('Filtre hatası', 500);
        }
    }
    
    /**
     * Mesajı okundu olarak işaretle
     */
    public function markRead(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('Method not allowed', 405);
        }
        
        $messageId = $this->intPost('message_id');
        
        if (!$messageId) {
            $this->errorResponse('Geçersiz mesaj ID', 400);
        }
        
        try {
            $stmt = $this->pdo->prepare("UPDATE provider_messages SET is_read = 1, read_at = NOW() WHERE id = ?");
            $stmt->execute([$messageId]);
            
            $this->successResponse('Mesaj okundu olarak işaretlendi');
        } catch (PDOException $e) {
            error_log("Mark message read error: " . $e->getMessage());
            $this->errorResponse('İşlem başarısız', 500);
        }
    }
    
    /**
     * Mesajı sil
     */
    public function delete(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('Method not allowed', 405);
        }
        
        $messageId = $this->intPost('message_id');
        
        if (!$messageId) {
            $this->errorResponse('Geçersiz mesaj ID', 400);
        }
        
        try {
            $stmt = $this->pdo->prepare("UPDATE provider_messages SET deleted_at = NOW() WHERE id = ?");
            $stmt->execute([$messageId]);
            
            $this->successResponse('Mesaj silindi');
        } catch (PDOException $e) {
            error_log("Delete message error: " . $e->getMessage());
            $this->errorResponse('İşlem başarısız', 500);
        }
    }
}
