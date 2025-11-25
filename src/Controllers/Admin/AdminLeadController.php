<?php

/**
 * KhidmaApp.com - Admin Lead Controller
 * 
 * Lead (müşteri talepleri) yönetimi
 */

require_once __DIR__ . '/BaseAdminController.php';

class AdminLeadController extends BaseAdminController 
{
    /**
     * Leads listesi
     */
    public function index(): void
    {
        $this->requireAuth();
        
        // Otomatik bakım işlemleri
        $this->autoUpdatePendingLeads();
        $this->autoDeleteInvalidLeads();
        
        // Filtreleme parametreleri
        $statusFilter = $this->sanitizedGet('status', 'all');
        $serviceType = $this->sanitizedGet('service_type', 'all');
        $city = $this->sanitizedGet('city', 'all');
        $sentFilter = $this->sanitizedGet('sent_filter', 'all');
        $serviceTimeType = $this->sanitizedGet('service_time_type', 'all');
        $dateFrom = $this->sanitizedGet('date_from');
        $dateTo = $this->sanitizedGet('date_to');
        $page = max(1, $this->intGet('page', 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        // Status bazlı istatistikler (tab'lar için)
        $stats = $this->getLeadsCountByStatus();
        
        // Leads getir
        $leads = $this->getLeads($statusFilter, $serviceType, $city, $offset, $perPage, $sentFilter, $dateFrom, $dateTo, $serviceTimeType);
        $totalLeads = $this->getLeadsCount($statusFilter, $serviceType, $city, $sentFilter, $dateFrom, $dateTo, $serviceTimeType);
        $totalPages = ceil($totalLeads / $perPage);
        
        $this->render('leads', [
            'leads' => $leads,
            'stats' => $stats,
            'statusFilter' => $statusFilter,
            'serviceType' => $serviceType,
            'city' => $city,
            'sentFilter' => $sentFilter,
            'serviceTimeType' => $serviceTimeType,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'page' => $page,
            'perPage' => $perPage,
            'totalLeads' => $totalLeads,
            'totalPages' => $totalPages
        ]);
    }
    
    /**
     * Lead detayları
     */
    public function detail(): void
    {
        $this->requireAuth();
        
        $leadId = $this->intGet('id');
        
        if (!$leadId) {
            $this->redirect('/admin/leads');
        }
        
        $lead = $this->getLeadById($leadId);
        
        if (!$lead) {
            $this->redirect('/admin/leads');
        }
        
        $this->render('lead_detail', ['lead' => $lead, 'leadId' => $leadId]);
    }
    
    /**
     * Lead durumu güncelle
     */
    public function updateStatus(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('İzin verilmeyen metod', 405);
        }
        
        $leadId = $this->intPost('lead_id');
        $status = $this->sanitizedPost('status');
        
        $validStatuses = ['new', 'verified', 'pending', 'sold', 'invalid'];
        if (!in_array($status, $validStatuses)) {
            $this->errorResponse('Geçersiz durum', 400);
        }
        
        try {
            if ($status === 'invalid') {
                $stmt = $this->pdo->prepare("UPDATE leads SET status = ?, invalid_at = NOW() WHERE id = ?");
                $stmt->execute([$status, $leadId]);
            } else {
                $stmt = $this->pdo->prepare("UPDATE leads SET status = ?, invalid_at = NULL WHERE id = ?");
                $stmt->execute([$status, $leadId]);
            }
            
            $this->successResponse('Durum başarıyla güncellendi');
        } catch (PDOException $e) {
            error_log("Update lead status error: " . $e->getMessage());
            $this->errorResponse('Güncelleme sırasında bir hata oluştu', 500);
        }
    }
    
    /**
     * Lead'i gönderildi olarak işaretle
     */
    public function markAsSent(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('İzin verilmeyen metod', 405);
        }
        
        $leadId = $this->intPost('lead_id');
        
        if (!$leadId) {
            $this->errorResponse('Geçersiz lead ID', 400);
        }
        
        try {
            $stmt = $this->pdo->prepare("
                UPDATE leads 
                SET previous_status = status, is_sent_to_provider = 1, sent_at = NOW(), status = 'sold' 
                WHERE id = ?
            ");
            $stmt->execute([$leadId]);
            
            $this->successResponse('Lead ustaya gönderildi ve satıldı olarak işaretlendi', [
                'lead_id' => $leadId,
                'rows_affected' => $stmt->rowCount()
            ]);
        } catch (PDOException $e) {
            error_log("markAsSent error: " . $e->getMessage());
            $this->errorResponse('İşlem başarısız: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Gönderildi durumunu değiştir
     */
    public function toggleSent(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('İzin verilmeyen metod', 405);
        }
        
        $leadId = $this->intPost('lead_id');
        
        if (!$leadId) {
            $this->errorResponse('Geçersiz lead ID', 400);
        }
        
        try {
            $stmt = $this->pdo->prepare("SELECT is_sent_to_provider, status, previous_status FROM leads WHERE id = ?");
            $stmt->execute([$leadId]);
            $lead = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$lead) {
                $this->errorResponse('Lead bulunamadı', 404);
            }
            
            $newStatus = $lead['is_sent_to_provider'] ? 0 : 1;
            
            if ($newStatus == 1) {
                $stmt = $this->pdo->prepare("UPDATE leads SET previous_status = status, is_sent_to_provider = ?, sent_at = NOW(), status = 'sold' WHERE id = ?");
                $stmt->execute([$newStatus, $leadId]);
            } else {
                $previousStatus = $lead['previous_status'] ?? 'new';
                $stmt = $this->pdo->prepare("UPDATE leads SET is_sent_to_provider = ?, sent_at = NULL, status = ?, previous_status = NULL WHERE id = ?");
                $stmt->execute([$newStatus, $previousStatus, $leadId]);
            }
            
            $this->successResponse(
                $newStatus ? 'Gönderildi olarak işaretlendi' : 'Gönderilmedi olarak işaretlendi',
                ['is_sent' => (bool)$newStatus]
            );
        } catch (PDOException $e) {
            error_log("toggleSent error: " . $e->getMessage());
            $this->errorResponse('İşlem başarısız: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Lead'i sil (soft delete)
     */
    public function delete(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('İzin verilmeyen metod', 405);
        }
        
        $leadId = $this->intPost('lead_id');
        
        if (!$leadId) {
            $this->errorResponse('Geçersiz lead ID', 400);
        }
        
        try {
            $stmt = $this->pdo->prepare("UPDATE leads SET deleted_at = NOW() WHERE id = ? AND deleted_at IS NULL");
            $stmt->execute([$leadId]);
            
            if ($stmt->rowCount() > 0) {
                $this->successResponse('Lead çöp kutusuna taşındı');
            } else {
                $this->errorResponse('Lead bulunamadı veya zaten silinmiş', 404);
            }
        } catch (PDOException $e) {
            error_log("Delete lead error: " . $e->getMessage());
            $this->errorResponse('Silme sırasında bir hata oluştu', 500);
        }
    }
    
    /**
     * Lead'i geri yükle
     */
    public function restore(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('İzin verilmeyen metod', 405);
        }
        
        $leadId = $this->intPost('lead_id');
        
        if (!$leadId) {
            $this->errorResponse('Geçersiz lead ID', 400);
        }
        
        try {
            $stmt = $this->pdo->prepare("UPDATE leads SET deleted_at = NULL WHERE id = ? AND deleted_at IS NOT NULL");
            $stmt->execute([$leadId]);
            
            if ($stmt->rowCount() > 0) {
                $this->successResponse('Lead başarıyla geri yüklendi');
            } else {
                $this->errorResponse('Lead bulunamadı veya zaten aktif', 404);
            }
        } catch (PDOException $e) {
            error_log("Restore lead error: " . $e->getMessage());
            $this->errorResponse('Geri yükleme sırasında bir hata oluştu', 500);
        }
    }
    
    /**
     * Lead'i kalıcı olarak sil
     */
    public function permanentDelete(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('İzin verilmeyen metod', 405);
        }
        
        $leadId = $this->intPost('lead_id');
        
        if (!$leadId) {
            $this->errorResponse('Geçersiz lead ID', 400);
        }
        
        try {
            // Sadece zaten soft-delete edilmiş lead'ler kalıcı silinebilir
            $stmt = $this->pdo->prepare("DELETE FROM leads WHERE id = ? AND deleted_at IS NOT NULL");
            $stmt->execute([$leadId]);
            
            if ($stmt->rowCount() > 0) {
                $this->successResponse('Lead kalıcı olarak silindi');
            } else {
                $this->errorResponse('Lead bulunamadı veya önce çöp kutusuna taşınmalı', 404);
            }
        } catch (PDOException $e) {
            error_log("Permanent delete lead error: " . $e->getMessage());
            $this->errorResponse('Kalıcı silme sırasında bir hata oluştu', 500);
        }
    }
    
    /**
     * Lead'i provider'a gönder
     */
    public function sendToProvider(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('İzin verilmeyen metod', 405);
        }
        
        $leadId = $this->intPost('lead_id');
        $providerId = $this->intPost('provider_id');
        $deliveryMethod = $this->sanitizedPost('delivery_method', 'whatsapp');
        
        if (!$leadId || !$providerId) {
            $this->errorResponse('Geçersiz parametreler', 400);
        }
        
        try {
            $this->pdo->beginTransaction();
            
            // Lead'i satıldı olarak işaretle
            $stmt = $this->pdo->prepare("
                UPDATE leads 
                SET previous_status = status, status = 'sold', is_sent_to_provider = 1, sent_at = NOW() 
                WHERE id = ?
            ");
            $stmt->execute([$leadId]);
            
            // Delivery kaydı oluştur
            $stmt = $this->pdo->prepare("
                INSERT INTO provider_lead_deliveries (lead_id, provider_id, delivery_method, delivered_at) 
                VALUES (?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE delivery_method = ?, delivered_at = NOW()
            ");
            $stmt->execute([$leadId, $providerId, $deliveryMethod, $deliveryMethod]);
            
            $this->pdo->commit();
            
            $this->successResponse('Lead provider\'a başarıyla gönderildi');
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Send lead to provider error: " . $e->getMessage());
            $this->errorResponse('Gönderme sırasında bir hata oluştu', 500);
        }
    }
    
    /**
     * Lead'i provider'dan geri çek
     */
    public function withdrawFromProvider(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('İzin verilmeyen metod', 405);
        }
        
        $leadId = $this->intPost('lead_id');
        $providerId = $this->intPost('provider_id');
        
        if (!$leadId) {
            $this->errorResponse('Geçersiz lead ID', 400);
        }
        
        try {
            $this->pdo->beginTransaction();
            
            // Lead'in önceki durumunu geri yükle
            $stmt = $this->pdo->prepare("SELECT previous_status FROM leads WHERE id = ?");
            $stmt->execute([$leadId]);
            $lead = $stmt->fetch(PDO::FETCH_ASSOC);
            $previousStatus = $lead['previous_status'] ?? 'verified';
            
            // Lead'i güncelle
            $stmt = $this->pdo->prepare("
                UPDATE leads 
                SET status = ?, is_sent_to_provider = 0, sent_at = NULL, previous_status = NULL 
                WHERE id = ?
            ");
            $stmt->execute([$previousStatus, $leadId]);
            
            // Delivery kaydını sil
            if ($providerId) {
                $stmt = $this->pdo->prepare("DELETE FROM provider_lead_deliveries WHERE lead_id = ? AND provider_id = ?");
                $stmt->execute([$leadId, $providerId]);
            } else {
                $stmt = $this->pdo->prepare("DELETE FROM provider_lead_deliveries WHERE lead_id = ?");
                $stmt->execute([$leadId]);
            }
            
            $this->pdo->commit();
            
            $this->successResponse('Lead provider\'dan geri çekildi');
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Withdraw lead error: " . $e->getMessage());
            $this->errorResponse('Geri çekme sırasında bir hata oluştu', 500);
        }
    }
    
    // ==================== PRIVATE METHODS ====================
    
    /**
     * Lead'i ID ile getir
     */
    private function getLeadById(int $id): ?array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM leads WHERE id = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("Get lead by ID error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Leads getir (filtreli)
     */
    private function getLeads(string $status, string $serviceType, string $city, int $offset, int $limit, string $sentFilter = 'all', string $dateFrom = '', string $dateTo = '', string $serviceTimeType = 'all'): array
    {
        if (!$this->pdo) return [];
        
        try {
            $where = [];
            $params = [];
            
            $tablePrefix = ($status === 'sold' || $status === 'deleted') ? 'l.' : '';
            
            // Soft delete filtresi
            if ($status === 'deleted') {
                $where[] = "{$tablePrefix}deleted_at IS NOT NULL";
            } else {
                $where[] = "{$tablePrefix}deleted_at IS NULL";
            }
            
            if ($status !== 'all' && $status !== 'deleted') {
                $where[] = "{$tablePrefix}status = ?";
                $params[] = $status;
            }
            
            if ($serviceType !== 'all') {
                $where[] = "{$tablePrefix}service_type = ?";
                $params[] = $serviceType;
            }
            
            if ($city !== 'all') {
                $where[] = "{$tablePrefix}city = ?";
                $params[] = $city;
            }
            
            if ($sentFilter === 'sent') {
                $where[] = "{$tablePrefix}is_sent_to_provider = 1";
            } elseif ($sentFilter === 'not_sent') {
                $where[] = "{$tablePrefix}is_sent_to_provider = 0";
            }
            
            if ($serviceTimeType !== 'all') {
                $where[] = "{$tablePrefix}service_time_type = ?";
                $params[] = $serviceTimeType;
            }
            
            if (!empty($dateFrom)) {
                $where[] = "DATE({$tablePrefix}created_at) >= ?";
                $params[] = $dateFrom;
            }
            if (!empty($dateTo)) {
                $where[] = "DATE({$tablePrefix}created_at) <= ?";
                $params[] = $dateTo;
            }
            
            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
            
            if ($status === 'deleted') {
                $sql = "SELECT l.*, DATEDIFF(DATE_ADD(l.deleted_at, INTERVAL 30 DAY), NOW()) as days_left
                        FROM leads l $whereClause 
                        ORDER BY l.deleted_at DESC 
                        LIMIT ? OFFSET ?";
            } elseif ($status === 'sold') {
                $sql = "SELECT l.*, sp.id as provider_id, sp.name as provider_name, sp.phone as provider_phone,
                               pld.delivery_method, pld.delivered_at, pld.viewed_at, pld.viewed_count
                        FROM leads l
                        LEFT JOIN provider_lead_deliveries pld ON l.id = pld.lead_id
                        LEFT JOIN service_providers sp ON pld.provider_id = sp.id
                        $whereClause 
                        ORDER BY COALESCE(l.sent_at, l.updated_at, l.created_at) DESC 
                        LIMIT ? OFFSET ?";
            } else {
                $sql = "SELECT * FROM leads $whereClause 
                        ORDER BY is_sent_to_provider ASC, COALESCE(sent_at, created_at) DESC 
                        LIMIT ? OFFSET ?";
            }
            
            $params[] = $limit;
            $params[] = $offset;
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get leads error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Leads sayısını getir
     */
    private function getLeadsCount(string $status, string $serviceType, string $city, string $sentFilter = 'all', string $dateFrom = '', string $dateTo = '', string $serviceTimeType = 'all'): int
    {
        if (!$this->pdo) return 0;
        
        try {
            $where = [];
            $params = [];
            
            if ($status === 'deleted') {
                $where[] = "deleted_at IS NOT NULL";
            } else {
                $where[] = "deleted_at IS NULL";
            }
            
            if ($status !== 'all' && $status !== 'deleted') {
                $where[] = "status = ?";
                $params[] = $status;
            }
            
            if ($serviceType !== 'all') {
                $where[] = "service_type = ?";
                $params[] = $serviceType;
            }
            
            if ($city !== 'all') {
                $where[] = "city = ?";
                $params[] = $city;
            }
            
            if ($sentFilter === 'sent') {
                $where[] = "is_sent_to_provider = 1";
            } elseif ($sentFilter === 'not_sent') {
                $where[] = "is_sent_to_provider = 0";
            }
            
            if ($serviceTimeType !== 'all') {
                $where[] = "service_time_type = ?";
                $params[] = $serviceTimeType;
            }
            
            if (!empty($dateFrom)) {
                $where[] = "DATE(created_at) >= ?";
                $params[] = $dateFrom;
            }
            if (!empty($dateTo)) {
                $where[] = "DATE(created_at) <= ?";
                $params[] = $dateTo;
            }
            
            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
            
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM leads $whereClause");
            $stmt->execute($params);
            return (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
        } catch (PDOException $e) {
            error_log("Get leads count error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Status bazlı lead sayıları
     */
    private function getLeadsCountByStatus(): array
    {
        if (!$this->pdo) {
            return ['all' => 0, 'new' => 0, 'pending' => 0, 'verified' => 0, 'sold' => 0, 'invalid' => 0, 'deleted' => 0];
        }
        
        try {
            $stats = [];
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE deleted_at IS NULL");
            $stats['all'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'new' AND deleted_at IS NULL");
            $stats['new'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'pending' AND deleted_at IS NULL");
            $stats['pending'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'verified' AND deleted_at IS NULL");
            $stats['verified'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'sold' AND deleted_at IS NULL");
            $stats['sold'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'invalid' AND deleted_at IS NULL");
            $stats['invalid'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE deleted_at IS NOT NULL");
            $stats['deleted'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Get leads count by status error: " . $e->getMessage());
            return ['all' => 0, 'new' => 0, 'pending' => 0, 'verified' => 0, 'sold' => 0, 'invalid' => 0, 'deleted' => 0];
        }
    }
    
    /**
     * 48 saatten eski "new" lead'leri otomatik "pending" yap
     */
    private function autoUpdatePendingLeads(): void
    {
        if (!$this->pdo) return;
        
        try {
            $stmt = $this->pdo->prepare("
                UPDATE leads SET status = 'pending' 
                WHERE status = 'new' AND deleted_at IS NULL
                AND TIMESTAMPDIFF(HOUR, created_at, NOW()) >= 48
            ");
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                error_log("Auto-updated {$stmt->rowCount()} leads to pending status");
            }
        } catch (PDOException $e) {
            error_log("Auto update pending leads error: " . $e->getMessage());
        }
    }
    
    /**
     * 24 saatten eski geçersiz lead'leri otomatik sil
     */
    private function autoDeleteInvalidLeads(): void
    {
        if (!$this->pdo) return;
        
        try {
            $stmt = $this->pdo->prepare("
                DELETE FROM leads 
                WHERE status = 'invalid' AND invalid_at IS NOT NULL 
                AND invalid_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)
            ");
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                error_log("Auto-deleted {$stmt->rowCount()} invalid leads");
            }
        } catch (PDOException $e) {
            error_log("Auto delete invalid leads error: " . $e->getMessage());
        }
    }
}

