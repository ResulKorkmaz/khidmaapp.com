<?php

/**
 * KhidmaApp.com - Admin Controller
 * 
 * Admin paneli için controller
 */

require_once __DIR__ . '/../config/config.php';

class AdminController 
{
    private $pdo;
    
    public function __construct() 
    {
        $this->pdo = getDatabase();
    }
    
    /**
     * Admin login sayfası
     */
    public function login() 
    {
        // Zaten giriş yapmışsa dashboard'a yönlendir
        if (isAdminLoggedIn()) {
            header('Location: /admin');
            exit;
        }
        
        $error = '';
        
        // POST isteği (giriş denemesi)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitizeInput($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($username) || empty($password)) {
                $error = 'Lütfen kullanıcı adı ve şifre girin';
            } elseif (adminLogin($username, $password)) {
                header('Location: /admin');
                exit;
            } else {
                $error = 'Kullanıcı adı veya şifre hatalı';
            }
        }
        
        include __DIR__ . '/../Views/admin/login.php';
    }
    
    /**
     * Admin dashboard
     */
    public function index() 
    {
        requireAdminLogin();
        
        // Otomatik olarak 48 saatten eski lead'leri "beklemede" yap
        $this->autoUpdatePendingLeads();
        
        // İstatistikler
        $stats = $this->getStats();
        
        // Son leads
        $recentLeads = $this->getRecentLeads(10);
        
        // PDO'yu view'a gönder (ek istatistikler için)
        $pdo = $this->pdo;
        
        include __DIR__ . '/../Views/admin/dashboard.php';
    }
    
    /**
     * Leads listesi
     */
    public function leads() 
    {
        requireAdminLogin();
        
        // Otomatik olarak 48 saatten eski lead'leri "beklemede" yap
        $this->autoUpdatePendingLeads();
        
        // Otomatik olarak 24 saatten eski geçersiz lead'leri sil
        $this->autoDeleteInvalidLeads();
        
        // Filtreleme parametreleri
        $statusFilter = $_GET['status'] ?? 'all';
        $serviceType = $_GET['service_type'] ?? 'all';
        $city = $_GET['city'] ?? 'all';
        $sentFilter = $_GET['sent_filter'] ?? 'all';
        $serviceTimeType = $_GET['service_time_type'] ?? 'all';
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        // Status bazlı istatistikler al (tab'lar için)
        $stats = $this->getLeadsCountByStatus();
        
        // Leads getir
        $leads = $this->getLeads($statusFilter, $serviceType, $city, $offset, $perPage, $sentFilter, $dateFrom, $dateTo, $serviceTimeType);
        $totalLeads = $this->getLeadsCount($statusFilter, $serviceType, $city, $sentFilter, $dateFrom, $dateTo, $serviceTimeType);
        $totalPages = ceil($totalLeads / $perPage);
        
        include __DIR__ . '/../Views/admin/leads.php';
    }
    
    /**
     * Lead durumu güncelle
     */
    public function updateLeadStatus() 
    {
        requireAdminLogin();
        
        header('Content-Type: application/json; charset=utf-8');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'İzin verilmeyen metod']);
            return;
        }
        
        $leadId = intval($_POST['lead_id'] ?? 0);
        $status = sanitizeInput($_POST['status'] ?? '');
        
        $validStatuses = ['new', 'verified', 'pending', 'sold', 'invalid'];
        if (!in_array($status, $validStatuses)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Geçersiz durum']);
            return;
        }
        
        try {
            // Eğer status 'invalid' ise, invalid_at timestamp'ini de set et
            if ($status === 'invalid') {
                $stmt = $this->pdo->prepare("UPDATE leads SET status = ?, invalid_at = NOW() WHERE id = ?");
                $stmt->execute([$status, $leadId]);
                error_log("⚠️ Lead #{$leadId} geçersiz olarak işaretlendi. 24 saat sonra otomatik silinecek.");
            } else {
                // Diğer status'lar için invalid_at'i temizle
                $stmt = $this->pdo->prepare("UPDATE leads SET status = ?, invalid_at = NULL WHERE id = ?");
                $stmt->execute([$status, $leadId]);
            }
            
            echo json_encode(['success' => true, 'message' => 'Durum başarıyla güncellendi']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Güncelleme sırasında bir hata oluştu']);
        }
    }
    
    /**
     * Lead detayları
     */
    public function leadDetail() 
    {
        requireAdminLogin();
        
        $leadId = $_GET['id'] ?? 0;
        
        if (!$leadId) {
            header('Location: /admin/leads');
            exit;
        }
        
        include __DIR__ . '/../Views/admin/lead_detail.php';
    }
    
    /**
     * Mark lead as sent to provider (copied)
     */
    public function markAsSent() 
    {
        requireAdminLogin();
        
        header('Content-Type: application/json; charset=utf-8');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'İzin verilmeyen metod']);
            return;
        }
        
        $leadId = intval($_POST['lead_id'] ?? 0);
        
        if (!$leadId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Geçersiz lead ID']);
            return;
        }
        
        if (!$this->pdo) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database bağlantısı yok']);
            return;
        }
        
        try {
            // Önce mevcut durumu previous_status'a kaydet, sonra gönderildi olarak işaretle ve durumu "satılan" yap
            $stmt = $this->pdo->prepare("UPDATE leads SET previous_status = status, is_sent_to_provider = 1, sent_at = NOW(), status = 'sold' WHERE id = ?");
            $stmt->execute([$leadId]);
            
            $rowCount = $stmt->rowCount();
            error_log("Lead #$leadId marked as sent and sold. Previous status saved. Rows affected: $rowCount");
            
            echo json_encode([
                'success' => true, 
                'message' => 'Lead ustaya gönderildi ve satıldı olarak işaretlendi',
                'lead_id' => $leadId,
                'rows_affected' => $rowCount
            ]);
        } catch (PDOException $e) {
            error_log("markAsSent error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'İşlem başarısız: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Toggle sent to provider status
     */
    public function toggleSentToProvider() 
    {
        requireAdminLogin();
        
        header('Content-Type: application/json; charset=utf-8');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'İzin verilmeyen metod']);
            return;
        }
        
        $leadId = intval($_POST['lead_id'] ?? 0);
        
        if (!$leadId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Geçersiz lead ID']);
            return;
        }
        
        if (!$this->pdo) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database bağlantısı yok']);
            return;
        }
        
        try {
            // Get current status and previous_status
            $stmt = $this->pdo->prepare("SELECT is_sent_to_provider, status, previous_status FROM leads WHERE id = ?");
            $stmt->execute([$leadId]);
            $lead = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$lead) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Lead bulunamadı']);
                return;
            }
            
            // Toggle status
            $newStatus = $lead['is_sent_to_provider'] ? 0 : 1;
            
            // If marking as sent, save current status and set to 'sold'
            if ($newStatus == 1) {
                $stmt = $this->pdo->prepare("UPDATE leads SET previous_status = status, is_sent_to_provider = ?, sent_at = NOW(), status = 'sold' WHERE id = ?");
                $stmt->execute([$newStatus, $leadId]);
            } else {
                // If marking as not sent, restore previous status
                $previousStatus = $lead['previous_status'] ?? 'new';
                $stmt = $this->pdo->prepare("UPDATE leads SET is_sent_to_provider = ?, sent_at = NULL, status = ?, previous_status = NULL WHERE id = ?");
                $stmt->execute([$newStatus, $previousStatus, $leadId]);
            }
            
            $rowCount = $stmt->rowCount();
            error_log("Lead #$leadId toggled. New is_sent status: $newStatus. Rows affected: $rowCount");
            
            echo json_encode([
                'success' => true, 
                'message' => $newStatus ? 'Gönderildi olarak işaretlendi' : 'Gönderilmedi olarak işaretlendi',
                'is_sent' => (bool)$newStatus
            ]);
        } catch (PDOException $e) {
            error_log("toggleSentToProvider error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'İşlem başarısız: ' . $e->getMessage()]);
        }
    }
    
    /**
     * 48 saatten eski "new" lead'leri otomatik "pending" yap
     */
    private function autoUpdatePendingLeads() 
    {
        if (!$this->pdo) {
            return;
        }
        
        try {
            // 48 saat = 2 gün
            // "new" durumunda olan ve 48 saatten eski olan lead'leri "pending" yap
            $sql = "UPDATE leads 
                    SET status = 'pending' 
                    WHERE status = 'new' 
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
    
    /**
     * Admin çıkış
     */
    public function logout() 
    {
        adminLogout();
        header('Location: /admin/login');
        exit;
    }
    
    /**
     * İstatistikleri getir
     */
    private function getStats() 
    {
        if (!$this->pdo) {
            return [
                'total_leads' => 0,
                'new_leads' => 0,
                'verified_leads' => 0,
                'sold_leads' => 0,
                'today_leads' => 0
            ];
        }
        
        try {
            $stats = [];
            
            // Toplam leads
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads");
            $stats['total_leads'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Yeni leads
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'new'");
            $stats['new_leads'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Doğrulanmış leads
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'verified'");
            $stats['verified_leads'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Satılan leads
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'sold'");
            $stats['sold_leads'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Bugünkü leads
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE DATE(created_at) = CURDATE()");
            $stats['today_leads'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Stats error: " . $e->getMessage());
            return [
                'total_leads' => 0,
                'new_leads' => 0,
                'verified_leads' => 0,
                'sold_leads' => 0,
                'today_leads' => 0
            ];
        }
    }
    
    /**
     * Son leads'i getir
     */
    private function getRecentLeads($limit = 10) 
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM leads ORDER BY created_at DESC LIMIT ?");
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Recent leads error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Leads getir (filtreli)
     */
    private function getLeads($status, $serviceType, $city, $offset, $limit, $sentFilter = 'all', $dateFrom = '', $dateTo = '', $serviceTimeType = 'all') 
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $where = [];
            $params = [];
            
            // JOIN olduğu için tablo prefix'i kullanmalıyız
            $tablePrefix = ($status === 'sold' || $status === 'deleted') ? 'l.' : '';
            
            // Silme durumu filtresi (soft delete)
            if ($status === 'deleted') {
                // Sadece silinen lead'leri göster
                $where[] = "{$tablePrefix}deleted_at IS NOT NULL";
            } else {
                // Silinmemiş lead'leri göster
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
            
            // Gönderilme durumu filtresi
            if ($sentFilter === 'sent') {
                $where[] = "{$tablePrefix}is_sent_to_provider = 1";
            } elseif ($sentFilter === 'not_sent') {
                $where[] = "{$tablePrefix}is_sent_to_provider = 0";
            }
            
            // Hizmet zamanı filtresi
            if ($serviceTimeType !== 'all') {
                $where[] = "{$tablePrefix}service_time_type = ?";
                $params[] = $serviceTimeType;
            }
            
            // Tarih filtresi
            if (!empty($dateFrom)) {
                $where[] = "DATE({$tablePrefix}created_at) >= ?";
                $params[] = $dateFrom;
            }
            if (!empty($dateTo)) {
                $where[] = "DATE({$tablePrefix}created_at) <= ?";
                $params[] = $dateTo;
            }
            
            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
            
            // Sıralama stratejisi:
            // - Eğer "deleted" tab'ındaysa: En son silinen en üstte (deleted_at DESC)
            // - Eğer "sold" tab'ındaysa: En son satılan en üstte (sent_at DESC) + Provider bilgisi
            // - Diğer tab'larda: Önce gönderilmeyenler, sonra en yeni tarih
            if ($status === 'deleted') {
                // Silinmiş tab'ında (çöp kutusu)
                $sql = "SELECT l.*, 
                               DATEDIFF(DATE_ADD(l.deleted_at, INTERVAL 30 DAY), NOW()) as days_left
                        FROM leads l
                        $whereClause 
                        ORDER BY l.deleted_at DESC 
                        LIMIT ? OFFSET ?";
            } elseif ($status === 'sold') {
                // Satılanlar tab'ında: Provider bilgisiyle birlikte + viewed durumu
                $sql = "SELECT l.*, 
                               sp.id as provider_id,
                               sp.name as provider_name, 
                               sp.phone as provider_phone,
                               pld.delivery_method,
                               pld.delivered_at,
                               pld.viewed_at,
                               pld.viewed_count
                        FROM leads l
                        LEFT JOIN provider_lead_deliveries pld ON l.id = pld.lead_id
                        LEFT JOIN service_providers sp ON pld.provider_id = sp.id
                        $whereClause 
                        ORDER BY COALESCE(l.sent_at, l.updated_at, l.created_at) DESC 
                        LIMIT ? OFFSET ?";
            } else {
                // Diğer tab'larda: Önce gönderilmeyenler (yeni lead'ler), sonra gönderilenler
                $sql = "SELECT * FROM leads $whereClause 
                        ORDER BY is_sent_to_provider ASC, 
                                 COALESCE(sent_at, created_at) DESC 
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
    private function getLeadsCount($status, $serviceType, $city, $sentFilter = 'all', $dateFrom = '', $dateTo = '', $serviceTimeType = 'all') 
    {
        if (!$this->pdo) {
            return 0;
        }
        
        try {
            $where = [];
            $params = [];
            
            // Silme durumu filtresi (soft delete)
            if ($status === 'deleted') {
                // Sadece silinen lead'leri say
                $where[] = "deleted_at IS NOT NULL";
            } else {
                // Silinmemiş lead'leri say
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
            
            // Gönderilme durumu filtresi
            if ($sentFilter === 'sent') {
                $where[] = "is_sent_to_provider = 1";
            } elseif ($sentFilter === 'not_sent') {
                $where[] = "is_sent_to_provider = 0";
            }
            
            // Hizmet zamanı filtresi
            if ($serviceTimeType !== 'all') {
                $where[] = "service_time_type = ?";
                $params[] = $serviceTimeType;
            }
            
            // Tarih filtresi
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
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
        } catch (PDOException $e) {
            error_log("Get leads count error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Export leads in various formats (PDF, Excel, CSV, DOCX)
     * Content is in Arabic format
     */
    public function exportLeads()
    {
        requireAdminLogin();
        
        // Get format parameter
        $format = sanitizeInput($_GET['format'] ?? 'pdf');
        
        // Get filter parameters (same as leads page)
        $status = $_GET['status'] ?? 'all';
        $serviceType = $_GET['service_type'] ?? 'all';
        $city = $_GET['city'] ?? 'all';
        $sentFilter = $_GET['sent_filter'] ?? 'all';
        $serviceTimeType = $_GET['service_time_type'] ?? 'all';
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';
        
        // Get ALL leads matching filters (no pagination for export)
        $leads = $this->getLeads($status, $serviceType, $city, 0, 10000, $sentFilter, $dateFrom, $dateTo, $serviceTimeType);
        
        if (empty($leads)) {
            http_response_code(404);
            echo '<h1>Hata</h1><p>Export edilecek lead bulunamadı.</p>';
            return;
        }
        
        // Load export service
        require_once __DIR__ . '/../Services/LeadExportService.php';
        
        // Log export action
        error_log("Admin '" . ($_SESSION['admin_username'] ?? 'unknown') . "' exported " . count($leads) . " leads as $format");
        
        try {
            // Export based on format
            switch ($format) {
                case 'excel':
                case 'xlsx':
                    LeadExportService::exportExcel($leads);
                    break;
                    
                case 'csv':
                    LeadExportService::exportCSV($leads);
                    break;
                    
                case 'docx':
                case 'word':
                    LeadExportService::exportDOCX($leads);
                    break;
                    
                case 'pdf':
                default:
                    LeadExportService::exportPDF($leads);
                    break;
            }
        } catch (Exception $e) {
            error_log("Export error: " . $e->getMessage());
            http_response_code(500);
            echo '<h1>Export Hatası</h1><p>' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
    
    /**
     * Providers (Ustalar) listesi
     */
    public function providers()
    {
        requireAdminLogin();
        
        // Filtreleme parametreleri
        $searchQuery = $_GET['search'] ?? '';
        $serviceTypeFilter = $_GET['service_type'] ?? '';
        $statusFilter = $_GET['status'] ?? '';
        $cityFilter = $_GET['city'] ?? '';
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        // Toplam usta sayısı
        $totalProviders = $this->getProvidersCount($searchQuery, $serviceTypeFilter, $statusFilter, $cityFilter);
        
        // Ustaları getir
        $providers = $this->getProviders($searchQuery, $serviceTypeFilter, $statusFilter, $cityFilter, $perPage, $offset);
        
        // Pagination hesapla
        $totalPages = ceil($totalProviders / $perPage);
        
        // Status istatistikleri
        $stats = [
            'total' => $totalProviders,
            'active' => $this->getProvidersCountByStatus('active'),
            'pending' => $this->getProvidersCountByStatus('pending'),
            'suspended' => $this->getProvidersCountByStatus('suspended'),
            'rejected' => $this->getProvidersCountByStatus('rejected')
        ];
        
        // View'a data gönder
        $currentPage = 'providers';
        $pageTitle = 'Ustalar Yönetimi';
        
        require __DIR__ . '/../Views/admin/providers.php';
    }
    
    /**
     * Usta sayısını getir (filtrelenmiş)
     */
    private function getProvidersCount($search = '', $serviceType = '', $status = '', $city = '')
    {
        $sql = "SELECT COUNT(*) as count FROM service_providers WHERE 1=1";
        $params = [];
        
        // Arama
        if (!empty($search)) {
            $sql .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)";
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        // Hizmet türü filtresi
        if (!empty($serviceType)) {
            $sql .= " AND service_type = ?";
            $params[] = $serviceType;
        }
        
        // Durum filtresi
        if (!empty($status)) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }
        
        // Şehir filtresi
        if (!empty($city)) {
            $sql .= " AND city = ?";
            $params[] = $city;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
    
    /**
     * Ustaları getir (filtrelenmiş, sayfalanmış)
     */
    private function getProviders($search = '', $serviceType = '', $status = '', $city = '', $limit = 20, $offset = 0)
    {
        $sql = "SELECT * FROM service_providers WHERE 1=1";
        $params = [];
        
        // Arama
        if (!empty($search)) {
            $sql .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)";
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        // Hizmet türü filtresi
        if (!empty($serviceType)) {
            $sql .= " AND service_type = ?";
            $params[] = $serviceType;
        }
        
        // Durum filtresi
        if (!empty($status)) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }
        
        // Şehir filtresi
        if (!empty($city)) {
            $sql .= " AND city = ?";
            $params[] = $city;
        }
        
        // Sıralama ve sayfalama
        $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Status'a göre usta sayısı
     */
    private function getProvidersCountByStatus($status)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM service_providers WHERE status = ?");
        $stmt->execute([$status]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
    
    /**
     * Ustayı onayla (WhatsApp kanal kontrolünden sonra)
     */
    public function approveProvider()
    {
        requireAdminLogin();
        
        // JSON input al (index.php tarafından okunmuş ve global değişkene kaydedilmiş)
        $data = $GLOBALS['_JSON_INPUT'] ?? [];
        
        // Eğer global değişken yoksa (fallback), tekrar okumaya çalış
        if (empty($data)) {
            $jsonInput = file_get_contents('php://input');
            $data = json_decode($jsonInput, true) ?? [];
        }
        
        // CSRF token kontrolü index.php'de yapıldı, burada gerek yok
        
        $providerId = intval($data['provider_id'] ?? 0);
        
        if ($providerId <= 0) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Invalid provider ID']);
            exit;
        }
        
        try {
            // Ustayı onayla: status = active, is_verified = true
            $stmt = $this->pdo->prepare("
                UPDATE service_providers 
                SET status = 'active', 
                    is_verified = TRUE,
                    updated_at = NOW()
                WHERE id = ?
            ");
            
            $success = $stmt->execute([$providerId]);
            
            if ($success && $stmt->rowCount() > 0) {
                // Log kaydı
                error_log("Admin approved provider ID: $providerId");
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true, 
                    'message' => 'Usta başarıyla onaylandı'
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false, 
                    'error' => 'Usta bulunamadı veya zaten onaylı'
                ]);
            }
        } catch (PDOException $e) {
            error_log("Approve provider error: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false, 
                'error' => 'Veritabanı hatası'
            ]);
        }
    }
    
    /**
     * Provider detail page
     */
    public function providerDetail()
    {
        requireAdminLogin();
        
        // Get provider ID from URL
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $parts = explode('/', $uri);
        $providerId = isset($parts[2]) ? intval($parts[2]) : 0;
        
        if ($providerId <= 0) {
            $_SESSION['error'] = 'Geçersiz usta ID';
            header('Location: /admin/providers');
            exit;
        }
        
        try {
            // Get provider details
            $stmt = $this->pdo->prepare("
                SELECT * FROM service_providers 
                WHERE id = ?
            ");
            $stmt->execute([$providerId]);
            $provider = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$provider) {
                $_SESSION['error'] = 'Usta bulunamadı';
                header('Location: /admin/providers');
                exit;
            }
            
            // Load view
            require __DIR__ . '/../Views/admin/provider_detail.php';
            
        } catch (PDOException $e) {
            error_log("Provider detail error: " . $e->getMessage());
            $_SESSION['error'] = 'Veritabanı hatası';
            header('Location: /admin/providers');
            exit;
        }
    }
    
    /**
     * Change provider status (AJAX)
     */
    public function changeProviderStatus()
    {
        requireAdminLogin();
        
        // JSON input al (index.php tarafından okunmuş ve global değişkene kaydedilmiş)
        $data = $GLOBALS['_JSON_INPUT'] ?? [];
        
        // Eğer global değişken yoksa (fallback), tekrar okumaya çalış
        if (empty($data)) {
            $jsonInput = file_get_contents('php://input');
            $data = json_decode($jsonInput, true) ?? [];
        }
        
        // CSRF token kontrolü index.php'de yapıldı, burada gerek yok
        
        $providerId = intval($data['provider_id'] ?? 0);
        $newStatus = $data['new_status'] ?? '';
        
        // Validate inputs
        if ($providerId <= 0) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Geçersiz usta ID']);
            exit;
        }
        
        $validStatuses = ['active', 'pending', 'suspended', 'rejected'];
        if (!in_array($newStatus, $validStatuses)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Geçersiz durum']);
            exit;
        }
        
        try {
            // Update status
            $stmt = $this->pdo->prepare("
                UPDATE service_providers 
                SET status = ?,
                    is_verified = ?,
                    updated_at = NOW()
                WHERE id = ?
            ");
            
            // If status is active, set is_verified to TRUE
            $isVerified = ($newStatus === 'active') ? 1 : 0;
            
            $success = $stmt->execute([$newStatus, $isVerified, $providerId]);
            
            if ($success && $stmt->rowCount() > 0) {
                // Log kaydı
                error_log("Admin changed provider ID $providerId status to: $newStatus");
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true, 
                    'message' => 'Usta durumu güncellendi',
                    'new_status' => $newStatus
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false, 
                    'error' => 'Usta bulunamadı veya durum zaten aynı'
                ]);
            }
        } catch (PDOException $e) {
            error_log("Change provider status error: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false, 
                'error' => 'Veritabanı hatası'
            ]);
        }
    }
    
    /**
     * Send lead to provider (via system message)
     */
    public function sendLeadToProvider()
    {
        requireAdminLogin();
        
        // JSON input al
        $data = $GLOBALS['_JSON_INPUT'] ?? [];
        
        if (empty($data)) {
            $jsonInput = file_get_contents('php://input');
            $data = json_decode($jsonInput, true) ?? [];
        }
        
        $leadId = intval($data['lead_id'] ?? 0);
        $providerId = intval($data['provider_id'] ?? 0);
        $purchaseId = intval($data['purchase_id'] ?? 0);
        
        // Validate inputs
        if ($leadId <= 0 || $providerId <= 0 || $purchaseId <= 0) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Geçersiz parametreler']);
            exit;
        }
        
        try {
            // Check if purchase exists and has remaining leads
            $stmt = $this->pdo->prepare("
                SELECT pp.*, 
                       COUNT(DISTINCT pld.id) as delivered_count
                FROM provider_purchases pp
                LEFT JOIN provider_lead_deliveries pld ON pp.id = pld.purchase_id
                WHERE pp.id = ? AND pp.provider_id = ?
                GROUP BY pp.id
            ");
            $stmt->execute([$purchaseId, $providerId]);
            $purchase = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$purchase) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Satın alma bulunamadı']);
                exit;
            }
            
            $deliveredCount = intval($purchase['delivered_count']);
            $totalLeads = intval($purchase['leads_count']);
            
            if ($deliveredCount >= $totalLeads) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Tüm lead\'ler teslim edildi']);
                exit;
            }
            
            // Check if lead already delivered to this purchase
            $stmt = $this->pdo->prepare("
                SELECT id FROM provider_lead_deliveries 
                WHERE purchase_id = ? AND lead_id = ?
            ");
            $stmt->execute([$purchaseId, $leadId]);
            if ($stmt->fetch()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Bu lead zaten gönderilmiş']);
                exit;
            }
            
            // Insert delivery record (system method)
            $stmt = $this->pdo->prepare("
                INSERT INTO provider_lead_deliveries 
                (purchase_id, provider_id, lead_id, delivery_method, delivered_by, delivered_at)
                VALUES (?, ?, ?, 'system', ?, NOW())
            ");
            
            $adminId = $_SESSION['admin_id'] ?? null;
            $success = $stmt->execute([$purchaseId, $providerId, $leadId, $adminId]);
            
            if ($success) {
                // Update purchase used_leads count
                $stmt = $this->pdo->prepare("
                    UPDATE provider_purchases 
                    SET used_leads = used_leads + 1,
                        remaining_leads = remaining_leads - 1
                    WHERE id = ?
                ");
                $stmt->execute([$purchaseId]);
                
                // Mark lead as sent to provider and sold
                $stmt = $this->pdo->prepare("
                    UPDATE leads 
                    SET previous_status = status,
                        is_sent_to_provider = 1,
                        sent_at = NOW(),
                        status = 'sold'
                    WHERE id = ?
                ");
                $stmt->execute([$leadId]);
                
                // Log
                error_log("✅ Admin sent lead $leadId to provider $providerId via system. Status: sold");
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true, 
                    'message' => 'Lead başarıyla gönderildi ve satıldı olarak işaretlendi'
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Gönderim başarısız']);
            }
            
        } catch (PDOException $e) {
            error_log("Send lead error: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası']);
        }
    }
    
    /**
     * Mark lead as sent via WhatsApp (manual)
     */
    public function markLeadAsSentViaWhatsApp()
    {
        requireAdminLogin();
        
        // JSON input al
        $data = $GLOBALS['_JSON_INPUT'] ?? [];
        
        if (empty($data)) {
            $jsonInput = file_get_contents('php://input');
            $data = json_decode($jsonInput, true) ?? [];
        }
        
        $leadId = intval($data['lead_id'] ?? 0);
        $providerId = intval($data['provider_id'] ?? 0);
        $purchaseId = intval($data['purchase_id'] ?? 0);
        
        // Validate inputs
        if ($leadId <= 0 || $providerId <= 0 || $purchaseId <= 0) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Geçersiz parametreler']);
            exit;
        }
        
        try {
            // Check if purchase exists and has remaining leads
            $stmt = $this->pdo->prepare("
                SELECT pp.*, 
                       COUNT(DISTINCT pld.id) as delivered_count
                FROM provider_purchases pp
                LEFT JOIN provider_lead_deliveries pld ON pp.id = pld.purchase_id
                WHERE pp.id = ? AND pp.provider_id = ?
                GROUP BY pp.id
            ");
            $stmt->execute([$purchaseId, $providerId]);
            $purchase = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$purchase) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Satın alma bulunamadı']);
                exit;
            }
            
            $deliveredCount = intval($purchase['delivered_count']);
            $totalLeads = intval($purchase['leads_count']);
            
            if ($deliveredCount >= $totalLeads) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Tüm lead\'ler teslim edildi']);
                exit;
            }
            
            // Check if lead already delivered to this purchase
            $stmt = $this->pdo->prepare("
                SELECT id FROM provider_lead_deliveries 
                WHERE purchase_id = ? AND lead_id = ?
            ");
            $stmt->execute([$purchaseId, $leadId]);
            if ($stmt->fetch()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Bu lead zaten gönderilmiş']);
                exit;
            }
            
            // Insert delivery record (whatsapp method)
            $stmt = $this->pdo->prepare("
                INSERT INTO provider_lead_deliveries 
                (purchase_id, provider_id, lead_id, delivery_method, delivered_by, delivered_at)
                VALUES (?, ?, ?, 'whatsapp', ?, NOW())
            ");
            
            $adminId = $_SESSION['admin_id'] ?? null;
            $success = $stmt->execute([$purchaseId, $providerId, $leadId, $adminId]);
            
            if ($success) {
                // Update purchase used_leads count
                $stmt = $this->pdo->prepare("
                    UPDATE provider_purchases 
                    SET used_leads = used_leads + 1,
                        remaining_leads = remaining_leads - 1
                    WHERE id = ?
                ");
                $stmt->execute([$purchaseId]);
                
                // Mark lead as sent to provider and sold
                $stmt = $this->pdo->prepare("
                    UPDATE leads 
                    SET previous_status = status,
                        is_sent_to_provider = 1,
                        sent_at = NOW(),
                        status = 'sold'
                    WHERE id = ?
                ");
                $stmt->execute([$leadId]);
                
                // Log
                error_log("✅ Admin marked lead $leadId as sent to provider $providerId via WhatsApp. Status: sold");
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true, 
                    'message' => 'Lead WhatsApp üzerinden gönderildi ve satıldı olarak işaretlendi'
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'İşaretleme başarısız']);
            }
            
        } catch (PDOException $e) {
            error_log("Mark lead as sent via WhatsApp error: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası']);
        }
    }
    
    /**
     * Admin purchases page
     */
    public function purchases()
    {
        requireAdminLogin();
        
        $currentPage = 'purchases';
        
        // Get all purchases with provider info
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
            LEFT JOIN leads_packages lp ON pp.package_id = lp.id
            LEFT JOIN provider_lead_deliveries pld ON pp.id = pld.purchase_id
            GROUP BY pp.id
            ORDER BY pp.purchased_at DESC
        ");
        $stmt->execute();
        $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get service types for filtering
        $serviceTypes = getServiceTypes();
        
        // Load view
        require __DIR__ . '/../Views/admin/purchases.php';
    }
    
    /**
     * Get active providers with available leads for a specific service
     */
    public function getAvailableProviders()
    {
        requireAdminLogin();
        
        $serviceType = $_GET['service_type'] ?? '';
        $city = $_GET['city'] ?? '';
        
        error_log("🔍 GET AVAILABLE LEADS - Service Type: '" . $serviceType . "' - City: '" . $city . "'");
        
        if (empty($serviceType)) {
            error_log("❌ Service type is empty!");
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Service type required']);
            exit;
        }
        
        try {
            // Build query parameters
            $params = [$serviceType];
            $cityCondition = "";
            
            if (!empty($city)) {
                $cityCondition = "AND city = ?";
                $params[] = $city;
            }
            
            $query = "
                SELECT 
                    id,
                    service_type,
                    city,
                    phone,
                    whatsapp_phone,
                    description,
                    budget_min,
                    budget_max,
                    service_time_type,
                    scheduled_date,
                    status,
                    created_at,
                    is_sent_to_provider
                FROM leads
                WHERE service_type = ?
                $cityCondition
                AND status IN ('new', 'verified', 'pending')
                AND (is_sent_to_provider = 0 OR is_sent_to_provider IS NULL)
                ORDER BY created_at DESC
                LIMIT 50
            ";
            
            error_log("📝 Query: " . $query);
            error_log("📝 Params: " . json_encode($params));
            
            // Get new/verified leads for this service type that haven't been sent yet
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("✅ Found " . count($leads) . " leads for service type: '" . $serviceType . "' and city: '" . $city . "'");
            
            // Debug: Let's see what we got
            foreach ($leads as $lead) {
                error_log("  📌 Lead #" . $lead['id'] . ": service='" . $lead['service_type'] . "', city='" . $lead['city'] . "', status='" . $lead['status'] . "', sent=" . $lead['is_sent_to_provider']);
            }
            
            // Also check total leads in database for this service type
            $stmt2 = $this->pdo->prepare("SELECT COUNT(*) as total FROM leads WHERE service_type = ?");
            $stmt2->execute([$serviceType]);
            $total = $stmt2->fetch(PDO::FETCH_ASSOC)['total'];
            error_log("📊 Total leads in DB for $serviceType: $total");
            
            // Check how many are already sent
            $stmt3 = $this->pdo->prepare("SELECT COUNT(*) as sent FROM leads WHERE service_type = ? AND is_sent_to_provider = 1");
            $stmt3->execute([$serviceType]);
            $sent = $stmt3->fetch(PDO::FETCH_ASSOC)['sent'];
            error_log("📤 Already sent for $serviceType: $sent");
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'leads' => $leads, 'debug' => [
                'total_in_db' => $total,
                'already_sent' => $sent,
                'available' => count($leads)
            ]]);
            
        } catch (PDOException $e) {
            error_log("❌ Get available providers error: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası', 'details' => $e->getMessage()]);
        }
    }
    
    /**
     * Her status için lead sayısını getir (tab'lar için)
     */
    private function getLeadsCountByStatus() 
    {
        if (!$this->pdo) {
            return [
                'all' => 0,
                'new' => 0,
                'pending' => 0,
                'verified' => 0,
                'sold' => 0,
                'invalid' => 0,
                'deleted' => 0
            ];
        }
        
        try {
            $stats = [];
            
            // Toplam (sadece silinmemiş lead'ler)
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE deleted_at IS NULL");
            $stats['all'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Yeni
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'new' AND deleted_at IS NULL");
            $stats['new'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Beklemede
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'pending' AND deleted_at IS NULL");
            $stats['pending'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Doğrulanmış
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'verified' AND deleted_at IS NULL");
            $stats['verified'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Satılan
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'sold' AND deleted_at IS NULL");
            $stats['sold'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Geçersiz
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'invalid' AND deleted_at IS NULL");
            $stats['invalid'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            // Silinmiş (Çöp Kutusu)
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM leads WHERE deleted_at IS NOT NULL");
            $stats['deleted'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            return $stats;
            
        } catch (PDOException $e) {
            error_log("❌ Get leads count by status error: " . $e->getMessage());
            return [
                'all' => 0,
                'new' => 0,
                'pending' => 0,
                'verified' => 0,
                'sold' => 0,
                'invalid' => 0,
                'deleted' => 0
            ];
        }
    }
    
    /**
     * 24 saatten eski geçersiz lead'leri otomatik sil
     */
    private function autoDeleteInvalidLeads() 
    {
        if (!$this->pdo) {
            return;
        }
        
        try {
            // Geçersiz olarak işaretlenip 24 saat geçmiş lead'leri sil
            $stmt = $this->pdo->prepare("
                DELETE FROM leads 
                WHERE status = 'invalid' 
                AND invalid_at IS NOT NULL 
                AND invalid_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)
            ");
            $stmt->execute();
            
            $deletedCount = $stmt->rowCount();
            
            if ($deletedCount > 0) {
                error_log("🗑️ Otomatik silme: {$deletedCount} geçersiz lead silindi (24 saat geçmiş)");
            }
            
        } catch (PDOException $e) {
            error_log("❌ Auto delete invalid leads error: " . $e->getMessage());
        }
    }
    
    /**
     * Provider arama (telefon veya ID ile)
     */
    public function searchProviders()
    {
        requireAdminLogin();
        
        header('Content-Type: application/json');
        
        $query = sanitizeInput($_GET['q'] ?? '');
        
        if (strlen($query) < 2) {
            echo json_encode(['success' => false, 'error' => 'En az 2 karakter gerekli']);
            exit;
        }
        
        try {
            // Telefon veya ID ile ara
            $stmt = $this->pdo->prepare("
                SELECT 
                    sp.*,
                    CASE 
                        WHEN sp.service_type = 'paint' THEN 'Boya Badana'
                        WHEN sp.service_type = 'renovation' THEN 'Tadilat'
                        WHEN sp.service_type = 'cleaning' THEN 'Temizlik'
                        WHEN sp.service_type = 'ac' THEN 'Klima'
                        WHEN sp.service_type = 'plumbing' THEN 'Sıhhi Tesisat'
                        WHEN sp.service_type = 'electric' THEN 'Elektrik'
                        WHEN sp.service_type = 'carpentry' THEN 'Marangoz'
                        WHEN sp.service_type = 'security' THEN 'Güvenlik'
                        WHEN sp.service_type = 'satellite' THEN 'Uydu'
                        ELSE sp.service_type
                    END as service_type_tr
                FROM service_providers sp
                WHERE (
                    sp.phone LIKE ? OR 
                    sp.id = ? OR
                    sp.name LIKE ?
                )
                AND sp.status IN ('active', 'pending')
                ORDER BY sp.status = 'active' DESC, sp.created_at DESC
                LIMIT 10
            ");
            
            $searchTerm = "%{$query}%";
            $idSearch = is_numeric($query) ? intval($query) : 0;
            
            $stmt->execute([$searchTerm, $idSearch, $searchTerm]);
            $providers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'providers' => $providers,
                'count' => count($providers)
            ]);
            
        } catch (PDOException $e) {
            error_log("❌ Search providers error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası']);
        }
    }
    
    /**
     * Lead'i ustadan geri çek
     */
    public function withdrawLeadFromProvider()
    {
        requireAdminLogin();
        
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            exit;
        }
        
        // JSON input al
        $data = $GLOBALS['_JSON_INPUT'] ?? [];
        
        if (empty($data)) {
            $jsonInput = file_get_contents('php://input');
            $data = json_decode($jsonInput, true) ?? [];
        }
        
        $leadId = intval($data['lead_id'] ?? 0);
        
        if ($leadId <= 0) {
            echo json_encode(['success' => false, 'error' => 'Geçersiz lead ID']);
            exit;
        }
        
        try {
            // Lead'i kontrol et
            $stmt = $this->pdo->prepare("
                SELECT id, status, previous_status, is_sent_to_provider 
                FROM leads 
                WHERE id = ?
            ");
            $stmt->execute([$leadId]);
            $lead = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$lead) {
                echo json_encode(['success' => false, 'error' => 'Lead bulunamadı']);
                exit;
            }
            
            if ($lead['status'] !== 'sold' || !$lead['is_sent_to_provider']) {
                echo json_encode(['success' => false, 'error' => 'Bu lead ustaya gönderilmemiş']);
                exit;
            }
            
            // Delivery kaydını bul
            $stmt = $this->pdo->prepare("
                SELECT pld.*, pp.id as purchase_id
                FROM provider_lead_deliveries pld
                LEFT JOIN provider_purchases pp ON pld.purchase_id = pp.id
                WHERE pld.lead_id = ?
                LIMIT 1
            ");
            $stmt->execute([$leadId]);
            $delivery = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Transaction başlat
            $this->pdo->beginTransaction();
            
            try {
                // 1. Delivery kaydını sil
                if ($delivery) {
                    $stmt = $this->pdo->prepare("DELETE FROM provider_lead_deliveries WHERE lead_id = ?");
                    $stmt->execute([$leadId]);
                    
                    // 2. Purchase'ı güncelle (lead'i geri ver)
                    if ($delivery['purchase_id']) {
                        $stmt = $this->pdo->prepare("
                            UPDATE provider_purchases 
                            SET used_leads = used_leads - 1,
                                remaining_leads = remaining_leads + 1
                            WHERE id = ?
                        ");
                        $stmt->execute([$delivery['purchase_id']]);
                    }
                }
                
                // 3. Lead'i geri al - WITHDRAWN status'e al
                // ⚠️ ÖNEMLİ: Geri çekilen lead'ler "withdrawn" status'ünde olmalı
                // Admin bu lead'i tekrar satarken potansiyel sorun olduğunu bilmeli
                $stmt = $this->pdo->prepare("
                    UPDATE leads 
                    SET status = 'withdrawn',
                        is_sent_to_provider = 0,
                        sent_at = NULL
                    WHERE id = ?
                ");
                $stmt->execute([$leadId]);
                
                $this->pdo->commit();
                
                error_log("✅ Admin withdrew lead #{$leadId} from provider. Status: withdrawn");
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Lead başarıyla geri çekildi ve "Geri Çekildi" durumuna alındı',
                    'new_status' => 'withdrawn'
                ]);
                
            } catch (Exception $e) {
                $this->pdo->rollBack();
                throw $e;
            }
            
        } catch (PDOException $e) {
            error_log("❌ Withdraw lead error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Seçili lead'leri ustaya ata
     */
    public function assignLeadsToProvider()
    {
        requireAdminLogin();
        
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            exit;
        }
        
        // JSON input al
        $data = $GLOBALS['_JSON_INPUT'] ?? [];
        
        if (empty($data)) {
            $jsonInput = file_get_contents('php://input');
            $data = json_decode($jsonInput, true) ?? [];
        }
        
        $providerId = intval($data['provider_id'] ?? 0);
        $leadIds = $data['lead_ids'] ?? [];
        
        if ($providerId <= 0 || empty($leadIds) || !is_array($leadIds)) {
            echo json_encode(['success' => false, 'error' => 'Geçersiz parametreler']);
            exit;
        }
        
        try {
            // Transaction başlat
            $this->pdo->beginTransaction();
            
            // Provider kontrolü
            $stmt = $this->pdo->prepare("SELECT id, name, status FROM service_providers WHERE id = ?");
            $stmt->execute([$providerId]);
            $provider = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$provider) {
                $this->pdo->rollBack();
                echo json_encode(['success' => false, 'error' => 'Usta bulunamadı']);
                exit;
            }
            
            if ($provider['status'] !== 'active') {
                $this->pdo->rollBack();
                echo json_encode(['success' => false, 'error' => 'Usta aktif değil']);
                exit;
            }
            
            // Provider'ın aktif purchase'larını bul (remaining > 0)
            $stmt = $this->pdo->prepare("
                SELECT id, leads_count, used_leads, remaining_leads
                FROM provider_purchases 
                WHERE provider_id = ? 
                AND remaining_leads > 0
                AND payment_status = 'completed'
                ORDER BY purchased_at ASC
                LIMIT 1
            ");
            $stmt->execute([$providerId]);
            $purchase = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $assignedCount = 0;
            $errors = [];
            $adminId = $_SESSION['admin_id'] ?? null;
            
            // Her lead'i ata
            foreach ($leadIds as $leadId) {
                $leadId = intval($leadId);
                if ($leadId <= 0) continue;
                
                // Lead'in mevcut olup olmadığını kontrol et
                $stmt = $this->pdo->prepare("SELECT id, service_type, status FROM leads WHERE id = ? AND deleted_at IS NULL");
                $stmt->execute([$leadId]);
                $lead = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$lead) {
                    $errors[] = "Lead #{$leadId} bulunamadı";
                    continue;
                }
                
                // ⚠️ Eğer lead daha önce geri çekilmişse uyarı ekle (ama gönderimi engelleme)
                if ($lead['status'] === 'withdrawn') {
                    $errors[] = "⚠️ Lead #{$leadId} daha önce GERİ ÇEKİLMİŞ! Potansiyel sorun olabilir.";
                }
                
                // ÖNCELİKLE lead'in status'unu sold yap
                // Bu, lead'in sold tab'ına düşmesini garantiler
                $stmt = $this->pdo->prepare("
                    UPDATE leads 
                    SET previous_status = status,
                        status = 'sold',
                        is_sent_to_provider = 1,
                        sent_at = NOW()
                    WHERE id = ?
                ");
                $result = $stmt->execute([$leadId]);
                
                if (!$result) {
                    error_log("❌ Failed to update lead #{$leadId} status to sold");
                    $errors[] = "Lead #{$leadId} status güncellenemedi";
                    continue;
                }
                
                error_log("✅ Lead #{$leadId} status updated to 'sold'");
                
                // Eğer purchase varsa, delivery kaydı oluştur
                if ($purchase && $purchase['remaining_leads'] > 0) {
                    // Delivery kaydı ekle
                    $stmt = $this->pdo->prepare("
                        INSERT INTO provider_lead_deliveries 
                        (purchase_id, provider_id, lead_id, delivery_method, delivered_by, delivered_at)
                        VALUES (?, ?, ?, 'system', ?, NOW())
                    ");
                    
                    try {
                        $stmt->execute([$purchase['id'], $providerId, $leadId, $adminId]);
                        
                        // Purchase'ın remaining_leads'ini azalt
                        $stmt = $this->pdo->prepare("
                            UPDATE provider_purchases 
                            SET used_leads = used_leads + 1,
                                remaining_leads = remaining_leads - 1
                            WHERE id = ?
                        ");
                        $stmt->execute([$purchase['id']]);
                        
                        // Remaining'i güncelle (bir sonraki lead için)
                        $purchase['remaining_leads']--;
                        
                        error_log("✅ Lead #{$leadId} delivered to provider #{$providerId} via purchase #{$purchase['id']}");
                        
                    } catch (PDOException $e) {
                        // Duplicate delivery - skip
                        if ($e->getCode() == 23000) {
                            error_log("⚠️ Lead #{$leadId} already delivered to this purchase");
                        } else {
                            throw $e;
                        }
                    }
                } else {
                    error_log("⚠️ Provider #{$providerId} has no active purchase with remaining leads");
                }
                
                $assignedCount++;
                error_log("✅ Admin assigned lead #{$leadId} to provider #{$providerId} ({$provider['name']})");
            }
            
            // Transaction'ı commit et
            $this->pdo->commit();
            
            echo json_encode([
                'success' => true,
                'assigned_count' => $assignedCount,
                'errors' => $errors,
                'provider_name' => $provider['name']
            ]);
            
        } catch (PDOException $e) {
            // Transaction'ı rollback et
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("❌ Assign leads error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
    }

    /**
     * Lead'i soft delete yap (çöp kutusuna taşı)
     */
    public function deleteLead()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Geçersiz istek']);
            return;
        }

        // JSON body'yi oku
        $input = json_decode(file_get_contents('php://input'), true);
        
        // CSRF koruması
        if (!isset($input['csrf_token']) || $input['csrf_token'] !== $_SESSION['csrf_token']) {
            echo json_encode(['success' => false, 'error' => 'Geçersiz CSRF token']);
            return;
        }

        $leadId = $input['lead_id'] ?? null;
        
        if (!$leadId) {
            echo json_encode(['success' => false, 'error' => 'Lead ID gerekli']);
            return;
        }

        try {
            // Lead'i soft delete yap
            $stmt = $this->pdo->prepare("
                UPDATE leads 
                SET deleted_at = NOW()
                WHERE id = ? AND deleted_at IS NULL
            ");
            $stmt->execute([$leadId]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Lead çöp kutusuna taşındı. 30 gün içinde geri yükleyebilirsiniz.'
                ]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Lead bulunamadı veya zaten silinmiş']);
            }
        } catch (PDOException $e) {
            error_log("Delete lead error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası']);
        }
    }

    /**
     * Lead'i çöp kutusundan geri yükle
     */
    public function restoreLead()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Geçersiz istek']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        // CSRF koruması
        if (!isset($input['csrf_token']) || $input['csrf_token'] !== $_SESSION['csrf_token']) {
            echo json_encode(['success' => false, 'error' => 'Geçersiz CSRF token']);
            return;
        }

        $leadId = $input['lead_id'] ?? null;
        
        if (!$leadId) {
            echo json_encode(['success' => false, 'error' => 'Lead ID gerekli']);
            return;
        }

        try {
            // Lead'in mevcut durumunu al
            $stmt = $this->pdo->prepare("SELECT status FROM leads WHERE id = ? AND deleted_at IS NOT NULL");
            $stmt->execute([$leadId]);
            $lead = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$lead) {
                echo json_encode(['success' => false, 'error' => 'Lead bulunamadı']);
                return;
            }

            // Lead'i geri yükle
            $stmt = $this->pdo->prepare("
                UPDATE leads 
                SET deleted_at = NULL
                WHERE id = ?
            ");
            $stmt->execute([$leadId]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Lead başarıyla geri yüklendi',
                'restored_status' => $lead['status']
            ]);
        } catch (PDOException $e) {
            error_log("Restore lead error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası']);
        }
    }

    /**
     * 30 günden eski silinen lead'leri kalıcı olarak sil
     * Bu fonksiyon cron job ile günlük çalıştırılmalı
     */
    public function autoDeleteOldTrash()
    {
        if (!$this->pdo) {
            return;
        }

        try {
            // 30 gün geçmiş silinen lead'leri kalıcı sil
            $stmt = $this->pdo->prepare("
                DELETE FROM leads 
                WHERE deleted_at IS NOT NULL 
                AND deleted_at < DATE_SUB(NOW(), INTERVAL 30 DAY)
            ");
            $stmt->execute();
            $deletedCount = $stmt->rowCount();
            
            if ($deletedCount > 0) {
                error_log("✅ Çöp kutusundan {$deletedCount} adet eski lead kalıcı olarak silindi");
            }
            
            return $deletedCount;
        } catch (PDOException $e) {
            error_log("Auto delete old trash error: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Lead'i kalıcı olarak sil (çöp kutusundan)
     */
    public function permanentlyDeleteLead()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Geçersiz istek']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        // CSRF koruması
        if (!isset($input['csrf_token']) || $input['csrf_token'] !== $_SESSION['csrf_token']) {
            echo json_encode(['success' => false, 'error' => 'Geçersiz CSRF token']);
            return;
        }

        $leadId = $input['lead_id'] ?? null;
        
        if (!$leadId) {
            echo json_encode(['success' => false, 'error' => 'Lead ID gerekli']);
            return;
        }

        try {
            // Sadece çöp kutusundaki lead'leri kalıcı sil
            $stmt = $this->pdo->prepare("
                DELETE FROM leads 
                WHERE id = ? AND deleted_at IS NOT NULL
            ");
            $stmt->execute([$leadId]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Lead kalıcı olarak silindi'
                ]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Lead bulunamadı veya çöp kutusunda değil']);
            }
        } catch (PDOException $e) {
            error_log("Permanently delete lead error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası']);
        }
    }

    /**
     * Provider'ı kalıcı olarak sil
     */
    public function deleteProvider()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Geçersiz istek']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        // CSRF koruması
        if (!isset($input['csrf_token']) || $input['csrf_token'] !== $_SESSION['csrf_token']) {
            echo json_encode(['success' => false, 'error' => 'Geçersiz CSRF token']);
            return;
        }

        $providerId = $input['provider_id'] ?? null;
        $confirmation = $input['confirmation'] ?? '';
        
        if (!$providerId) {
            echo json_encode(['success' => false, 'error' => 'Provider ID gerekli']);
            return;
        }

        // "SIL" yazma kontrolü
        if ($confirmation !== 'SIL') {
            echo json_encode(['success' => false, 'error' => 'Onay için "SIL" yazmalısınız']);
            return;
        }

        try {
            $this->pdo->beginTransaction();
            
            // Provider'ın bilgilerini al
            $stmt = $this->pdo->prepare("SELECT name, email, phone FROM service_providers WHERE id = ?");
            $stmt->execute([$providerId]);
            $provider = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$provider) {
                $this->pdo->rollBack();
                echo json_encode(['success' => false, 'error' => 'Provider bulunamadı']);
                return;
            }

            // Provider'a bağlı lead delivery kayıtlarını sil
            $stmt = $this->pdo->prepare("DELETE FROM provider_lead_deliveries WHERE provider_id = ?");
            $stmt->execute([$providerId]);
            
            // Provider'a bağlı satın alma kayıtlarını sil
            $stmt = $this->pdo->prepare("DELETE FROM provider_purchases WHERE provider_id = ?");
            $stmt->execute([$providerId]);
            
            // Provider'ı sil
            $stmt = $this->pdo->prepare("DELETE FROM service_providers WHERE id = ?");
            $stmt->execute([$providerId]);
            
            $this->pdo->commit();
            
            echo json_encode([
                'success' => true,
                'message' => 'Provider başarıyla silindi',
                'deleted_provider' => $provider['name']
            ]);
            
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Delete provider error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
    }
    
    /**
     * ============================================
     * SERVICE MANAGEMENT METHODS
     * ============================================
     */
    
    /**
     * Display services management page
     */
    public function manageServices()
    {
        // Check if admin is logged in
        requireAdminLogin();
        
        try {
            // Fetch all services ordered by display_order
            $stmt = $this->pdo->prepare("
                SELECT * FROM services 
                ORDER BY display_order ASC, id ASC
            ");
            $stmt->execute();
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Count active and inactive services
            $activeCount = count(array_filter($services, fn($s) => $s['is_active'] == 1));
            $inactiveCount = count($services) - $activeCount;
            
            // Prepare page data
            $pageData = [
                'services' => $services,
                'totalServices' => count($services),
                'activeCount' => $activeCount,
                'inactiveCount' => $inactiveCount,
                'pageTitle' => 'Hizmet Yönetimi'
            ];
            
            require __DIR__ . '/../Views/admin/services.php';
            
        } catch (PDOException $e) {
            error_log("Manage services error: " . $e->getMessage());
            $_SESSION['error'] = 'Hizmetler yüklenirken hata oluştu';
            header('Location: /admin');
            exit;
        }
    }
    
    /**
     * Create a new service (AJAX)
     */
    public function createService()
    {
        header('Content-Type: application/json');
        
        // Check if admin is logged in
        if (!isAdminLoggedIn()) {
            echo json_encode(['success' => false, 'error' => 'Yetkisiz erişim']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Geçersiz istek']);
            exit;
        }
        
        // CSRF token validation
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            echo json_encode(['success' => false, 'error' => 'CSRF token doğrulaması başarısız']);
            exit;
        }
        
        try {
            // Get form data
            $serviceKey = trim($_POST['service_key'] ?? '');
            $nameAr = trim($_POST['name_ar'] ?? '');
            $nameTr = trim($_POST['name_tr'] ?? '');
            $icon = trim($_POST['icon'] ?? '');
            $isActive = intval($_POST['is_active'] ?? 1);
            
            // Validate required fields
            if (empty($serviceKey) || empty($nameAr) || empty($nameTr)) {
                echo json_encode(['success' => false, 'error' => 'Tüm alanlar zorunludur']);
                exit;
            }
            
            // Validate service_key format (lowercase, alphanumeric + underscore)
            if (!preg_match('/^[a-z0-9_]+$/', $serviceKey)) {
                echo json_encode(['success' => false, 'error' => 'Service key sadece küçük harf, rakam ve alt çizgi içerebilir']);
                exit;
            }
            
            // Check if service_key already exists
            $stmt = $this->pdo->prepare("SELECT id FROM services WHERE service_key = ?");
            $stmt->execute([$serviceKey]);
            if ($stmt->fetch()) {
                echo json_encode(['success' => false, 'error' => 'Bu service key zaten kullanılıyor']);
                exit;
            }
            
            // Get max display_order
            $stmt = $this->pdo->prepare("SELECT MAX(display_order) as max_order FROM services");
            $stmt->execute();
            $maxOrder = $stmt->fetch(PDO::FETCH_ASSOC)['max_order'] ?? 0;
            $displayOrder = $maxOrder + 1;
            
            // Insert new service
            $stmt = $this->pdo->prepare("
                INSERT INTO services (service_key, name_ar, name_tr, icon, is_active, display_order)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$serviceKey, $nameAr, $nameTr, $icon, $isActive, $displayOrder]);
            
            // Clear service cache
            $this->clearServiceCache();
            
            error_log("✅ New service created: {$serviceKey} - {$nameTr}");
            
            echo json_encode([
                'success' => true,
                'message' => 'Hizmet başarıyla eklendi',
                'service_id' => $this->pdo->lastInsertId()
            ]);
            
        } catch (PDOException $e) {
            error_log("Create service error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Update an existing service (AJAX)
     */
    public function updateService()
    {
        header('Content-Type: application/json');
        
        // Check if admin is logged in
        if (!isAdminLoggedIn()) {
            echo json_encode(['success' => false, 'error' => 'Yetkisiz erişim']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Geçersiz istek']);
            exit;
        }
        
        // CSRF token validation
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            echo json_encode(['success' => false, 'error' => 'CSRF token doğrulaması başarısız']);
            exit;
        }
        
        try {
            // Get form data
            $serviceId = intval($_POST['service_id'] ?? 0);
            $nameAr = trim($_POST['name_ar'] ?? '');
            $nameTr = trim($_POST['name_tr'] ?? '');
            $icon = trim($_POST['icon'] ?? '');
            $isActive = intval($_POST['is_active'] ?? 1);
            $displayOrder = intval($_POST['display_order'] ?? 0);
            
            // Validate
            if ($serviceId <= 0) {
                echo json_encode(['success' => false, 'error' => 'Geçersiz hizmet ID']);
                exit;
            }
            
            if (empty($nameAr) || empty($nameTr)) {
                echo json_encode(['success' => false, 'error' => 'Hizmet adları zorunludur']);
                exit;
            }
            
            // Check if service exists
            $stmt = $this->pdo->prepare("SELECT service_key FROM services WHERE id = ?");
            $stmt->execute([$serviceId]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$service) {
                echo json_encode(['success' => false, 'error' => 'Hizmet bulunamadı']);
                exit;
            }
            
            // Update service
            $stmt = $this->pdo->prepare("
                UPDATE services 
                SET name_ar = ?, name_tr = ?, icon = ?, is_active = ?, display_order = ?
                WHERE id = ?
            ");
            $stmt->execute([$nameAr, $nameTr, $icon, $isActive, $displayOrder, $serviceId]);
            
            // Clear service cache
            $this->clearServiceCache();
            
            error_log("✅ Service updated: {$service['service_key']} - {$nameTr}");
            
            echo json_encode([
                'success' => true,
                'message' => 'Hizmet başarıyla güncellendi'
            ]);
            
        } catch (PDOException $e) {
            error_log("Update service error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Toggle service active status (AJAX)
     */
    public function toggleServiceStatus()
    {
        header('Content-Type: application/json');
        
        // Check if admin is logged in
        if (!isAdminLoggedIn()) {
            echo json_encode(['success' => false, 'error' => 'Yetkisiz erişim']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Geçersiz istek']);
            exit;
        }
        
        // CSRF token validation
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            echo json_encode(['success' => false, 'error' => 'CSRF token doğrulaması başarısız']);
            exit;
        }
        
        try {
            $serviceId = intval($_POST['service_id'] ?? 0);
            
            if ($serviceId <= 0) {
                echo json_encode(['success' => false, 'error' => 'Geçersiz hizmet ID']);
                exit;
            }
            
            // Get current status
            $stmt = $this->pdo->prepare("SELECT service_key, is_active FROM services WHERE id = ?");
            $stmt->execute([$serviceId]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$service) {
                echo json_encode(['success' => false, 'error' => 'Hizmet bulunamadı']);
                exit;
            }
            
            // Toggle status
            $newStatus = $service['is_active'] ? 0 : 1;
            
            $stmt = $this->pdo->prepare("UPDATE services SET is_active = ? WHERE id = ?");
            $stmt->execute([$newStatus, $serviceId]);
            
            // Clear service cache
            $this->clearServiceCache();
            
            $statusText = $newStatus ? 'aktif' : 'pasif';
            error_log("✅ Service status toggled: {$service['service_key']} -> {$statusText}");
            
            echo json_encode([
                'success' => true,
                'message' => 'Hizmet durumu güncellendi',
                'new_status' => $newStatus
            ]);
            
        } catch (PDOException $e) {
            error_log("Toggle service status error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Delete a service (AJAX) - Soft delete or permanent
     */
    public function deleteService()
    {
        header('Content-Type: application/json');
        
        // Check if admin is logged in
        if (!isAdminLoggedIn()) {
            echo json_encode(['success' => false, 'error' => 'Yetkisiz erişim']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Geçersiz istek']);
            exit;
        }
        
        // CSRF token validation
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            echo json_encode(['success' => false, 'error' => 'CSRF token doğrulaması başarısız']);
            exit;
        }
        
        try {
            $serviceId = intval($_POST['service_id'] ?? 0);
            
            if ($serviceId <= 0) {
                echo json_encode(['success' => false, 'error' => 'Geçersiz hizmet ID']);
                exit;
            }
            
            // Check if service exists
            $stmt = $this->pdo->prepare("SELECT service_key FROM services WHERE id = ?");
            $stmt->execute([$serviceId]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$service) {
                echo json_encode(['success' => false, 'error' => 'Hizmet bulunamadı']);
                exit;
            }
            
            // Check if service is used by providers or leads
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM service_providers WHERE service_type = ?");
            $stmt->execute([$service['service_key']]);
            $providerCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM leads WHERE service_type = ?");
            $stmt->execute([$service['service_key']]);
            $leadCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            if ($providerCount > 0 || $leadCount > 0) {
                echo json_encode([
                    'success' => false,
                    'error' => "Bu hizmet {$providerCount} usta ve {$leadCount} lead tarafından kullanılıyor. Silmek yerine pasif yapın."
                ]);
                exit;
            }
            
            // Safe to delete
            $stmt = $this->pdo->prepare("DELETE FROM services WHERE id = ?");
            $stmt->execute([$serviceId]);
            
            // Clear service cache
            $this->clearServiceCache();
            
            error_log("✅ Service deleted: {$service['service_key']}");
            
            echo json_encode([
                'success' => true,
                'message' => 'Hizmet başarıyla silindi'
            ]);
            
        } catch (PDOException $e) {
            error_log("Delete service error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Clear service cache (invalidate getServiceTypes cache)
     */
    private function clearServiceCache()
    {
        // Clear APC/APCu cache if available
        if (function_exists('apcu_clear_cache')) {
            apcu_clear_cache();
            error_log("🗑️ APCu cache cleared");
        }
        
        // Clear static cache variable by forcing reload
        // getServiceTypes() will reload from DB on next call
        error_log("🗑️ Service cache invalidated");
    }
    
    /**
     * ============================================
     * LEAD PACKAGES MANAGEMENT METHODS
     * ============================================
     */
    
    /**
     * Display lead packages management page
     */
    public function manageLeadPackages()
    {
        requireAdminLogin();
        
        try {
            // Fetch all packages ordered by service_type and display_order
            $stmt = $this->pdo->prepare("
                SELECT * FROM lead_packages 
                ORDER BY service_type, display_order, lead_count
            ");
            $stmt->execute();
            $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Group by service type
            $packagesByService = [];
            foreach ($packages as $package) {
                $serviceType = $package['service_type'];
                if (!isset($packagesByService[$serviceType])) {
                    $packagesByService[$serviceType] = [];
                }
                $packagesByService[$serviceType][] = $package;
            }
            
            // Count active and inactive packages
            $activeCount = count(array_filter($packages, fn($p) => $p['is_active'] == 1));
            $inactiveCount = count($packages) - $activeCount;
            
            // Prepare page data
            $pageData = [
                'packages' => $packages,
                'packagesByService' => $packagesByService,
                'totalPackages' => count($packages),
                'activeCount' => $activeCount,
                'inactiveCount' => $inactiveCount,
                'services' => getServiceTypes(),
                'pageTitle' => 'Lead Paket Yönetimi'
            ];
            
            require __DIR__ . '/../Views/admin/lead_packages.php';
            
        } catch (PDOException $e) {
            error_log("Manage lead packages error: " . $e->getMessage());
            $_SESSION['error'] = 'Paketler yüklenirken hata oluştu';
            header('Location: /admin');
            exit;
        }
    }
    
    /**
     * Create a new lead package (AJAX)
     */
    public function createLeadPackage()
    {
        header('Content-Type: application/json');
        
        if (!isAdminLoggedIn()) {
            echo json_encode(['success' => false, 'error' => 'Yetkisiz erişim']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Geçersiz istek']);
            exit;
        }
        
        // CSRF token validation
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            echo json_encode(['success' => false, 'error' => 'CSRF token doğrulaması başarısız']);
            exit;
        }
        
        try {
            // Get form data
            $serviceType = trim($_POST['service_type'] ?? '');
            $leadCount = intval($_POST['lead_count'] ?? 0);
            $priceSar = floatval($_POST['price_sar'] ?? 0);
            $nameAr = trim($_POST['name_ar'] ?? '');
            $nameTr = trim($_POST['name_tr'] ?? '');
            $descriptionAr = trim($_POST['description_ar'] ?? '');
            $descriptionTr = trim($_POST['description_tr'] ?? '');
            $isActive = intval($_POST['is_active'] ?? 1);
            
            // Validate required fields
            if (empty($serviceType) || $leadCount <= 0 || $priceSar <= 0) {
                echo json_encode(['success' => false, 'error' => 'Tüm zorunlu alanları doldurun']);
                exit;
            }
            
            // Calculate price per lead
            $pricePerLead = $priceSar / $leadCount;
            
            // Calculate discount if applicable
            $discountPercentage = 0;
            if ($leadCount >= 3) {
                $singlePackagePrice = $pricePerLead * $leadCount;
                if ($priceSar < $singlePackagePrice) {
                    $discountPercentage = (($singlePackagePrice - $priceSar) / $singlePackagePrice) * 100;
                }
            }
            
            // Create Stripe Product & Price
            require_once __DIR__ . '/../config/stripe.php';
            initStripe();
            
            $stripeProduct = \Stripe\Product::create([
                'name' => $nameTr,
                'description' => $descriptionTr,
                'metadata' => [
                    'service_type' => $serviceType,
                    'lead_count' => $leadCount,
                    'name_ar' => $nameAr,
                ]
            ]);
            
            $stripePrice = \Stripe\Price::create([
                'product' => $stripeProduct->id,
                'unit_amount' => intval($priceSar * 100), // Convert to cents
                'currency' => 'sar',
                'metadata' => [
                    'service_type' => $serviceType,
                    'lead_count' => $leadCount,
                ]
            ]);
            
            // Insert into database
            $stmt = $this->pdo->prepare("
                INSERT INTO lead_packages (
                    service_type, lead_count, price_sar, price_per_lead, 
                    name_ar, name_tr, description_ar, description_tr,
                    stripe_product_id, stripe_price_id, discount_percentage, is_active
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $serviceType, $leadCount, $priceSar, $pricePerLead,
                $nameAr, $nameTr, $descriptionAr, $descriptionTr,
                $stripeProduct->id, $stripePrice->id, $discountPercentage, $isActive
            ]);
            
            error_log("✅ New lead package created: {$serviceType} - {$leadCount} leads - {$priceSar} SAR");
            
            echo json_encode([
                'success' => true,
                'message' => 'Paket başarıyla oluşturuldu',
                'package_id' => $this->pdo->lastInsertId()
            ]);
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            error_log("Stripe API error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Stripe hatası: ' . $e->getMessage()]);
        } catch (PDOException $e) {
            error_log("Create lead package error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası']);
        }
    }
    
    /**
     * Update an existing lead package (AJAX)
     */
    public function updateLeadPackage()
    {
        header('Content-Type: application/json');
        
        if (!isAdminLoggedIn()) {
            echo json_encode(['success' => false, 'error' => 'Yetkisiz erişim']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Geçersiz istek']);
            exit;
        }
        
        // CSRF token validation
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            echo json_encode(['success' => false, 'error' => 'CSRF token doğrulaması başarısız']);
            exit;
        }
        
        try {
            $packageId = intval($_POST['package_id'] ?? 0);
            $priceSar = floatval($_POST['price_sar'] ?? 0);
            $nameAr = trim($_POST['name_ar'] ?? '');
            $nameTr = trim($_POST['name_tr'] ?? '');
            $descriptionAr = trim($_POST['description_ar'] ?? '');
            $descriptionTr = trim($_POST['description_tr'] ?? '');
            $isActive = intval($_POST['is_active'] ?? 1);
            
            if ($packageId <= 0 || $priceSar <= 0) {
                echo json_encode(['success' => false, 'error' => 'Geçersiz paket ID veya fiyat']);
                exit;
            }
            
            // Get existing package
            $stmt = $this->pdo->prepare("SELECT * FROM lead_packages WHERE id = ?");
            $stmt->execute([$packageId]);
            $package = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$package) {
                echo json_encode(['success' => false, 'error' => 'Paket bulunamadı']);
                exit;
            }
            
            // Calculate new price per lead
            $pricePerLead = $priceSar / $package['lead_count'];
            
            // Update Stripe Price (create new price, old one will be archived)
            require_once __DIR__ . '/../config/stripe.php';
            initStripe();
            
            if ($package['stripe_product_id']) {
                // Update Product metadata
                \Stripe\Product::update($package['stripe_product_id'], [
                    'name' => $nameTr,
                    'description' => $descriptionTr,
                    'metadata' => ['name_ar' => $nameAr]
                ]);
                
                // Create new Price (Stripe doesn't allow price updates, must create new)
                $stripePrice = \Stripe\Price::create([
                    'product' => $package['stripe_product_id'],
                    'unit_amount' => intval($priceSar * 100),
                    'currency' => 'sar',
                    'metadata' => [
                        'service_type' => $package['service_type'],
                        'lead_count' => $package['lead_count'],
                    ]
                ]);
                
                $newStripePriceId = $stripePrice->id;
            } else {
                $newStripePriceId = null;
            }
            
            // Update database
            $stmt = $this->pdo->prepare("
                UPDATE lead_packages 
                SET price_sar = ?, price_per_lead = ?, 
                    name_ar = ?, name_tr = ?, description_ar = ?, description_tr = ?,
                    stripe_price_id = ?, is_active = ?
                WHERE id = ?
            ");
            
            $stmt->execute([
                $priceSar, $pricePerLead,
                $nameAr, $nameTr, $descriptionAr, $descriptionTr,
                $newStripePriceId, $isActive, $packageId
            ]);
            
            error_log("✅ Lead package updated: ID {$packageId}");
            
            echo json_encode(['success' => true, 'message' => 'Paket başarıyla güncellendi']);
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            error_log("Stripe API error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Stripe hatası: ' . $e->getMessage()]);
        } catch (PDOException $e) {
            error_log("Update lead package error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası']);
        }
    }
    
    /**
     * Toggle package active status (AJAX)
     */
    public function toggleLeadPackageStatus()
    {
        header('Content-Type: application/json');
        
        if (!isAdminLoggedIn()) {
            echo json_encode(['success' => false, 'error' => 'Yetkisiz erişim']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Geçersiz istek']);
            exit;
        }
        
        // CSRF token validation
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            echo json_encode(['success' => false, 'error' => 'CSRF token doğrulaması başarısız']);
            exit;
        }
        
        try {
            $packageId = intval($_POST['package_id'] ?? 0);
            
            if ($packageId <= 0) {
                echo json_encode(['success' => false, 'error' => 'Geçersiz paket ID']);
                exit;
            }
            
            // Get current status
            $stmt = $this->pdo->prepare("SELECT is_active FROM lead_packages WHERE id = ?");
            $stmt->execute([$packageId]);
            $package = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$package) {
                echo json_encode(['success' => false, 'error' => 'Paket bulunamadı']);
                exit;
            }
            
            // Toggle status
            $newStatus = $package['is_active'] ? 0 : 1;
            
            $stmt = $this->pdo->prepare("UPDATE lead_packages SET is_active = ? WHERE id = ?");
            $stmt->execute([$newStatus, $packageId]);
            
            error_log("✅ Lead package status toggled: ID {$packageId} -> " . ($newStatus ? 'active' : 'inactive'));
            
            echo json_encode([
                'success' => true,
                'message' => 'Paket durumu güncellendi',
                'new_status' => $newStatus
            ]);
            
        } catch (PDOException $e) {
            error_log("Toggle lead package status error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası']);
        }
    }
    
    /**
     * Delete a lead package (AJAX)
     */
    public function deleteLeadPackage()
    {
        header('Content-Type: application/json');
        
        if (!isAdminLoggedIn()) {
            echo json_encode(['success' => false, 'error' => 'Yetkisiz erişim']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Geçersiz istek']);
            exit;
        }
        
        // CSRF token validation
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            echo json_encode(['success' => false, 'error' => 'CSRF token doğrulaması başarısız']);
            exit;
        }
        
        try {
            $packageId = intval($_POST['package_id'] ?? 0);
            
            if ($packageId <= 0) {
                echo json_encode(['success' => false, 'error' => 'Geçersiz paket ID']);
                exit;
            }
            
            // Get package
            $stmt = $this->pdo->prepare("SELECT * FROM lead_packages WHERE id = ?");
            $stmt->execute([$packageId]);
            $package = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$package) {
                echo json_encode(['success' => false, 'error' => 'Paket bulunamadı']);
                exit;
            }
            
            // Check if package is used in purchases
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM provider_purchases WHERE package_id = ?");
            $stmt->execute([$packageId]);
            $purchaseCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            if ($purchaseCount > 0) {
                echo json_encode([
                    'success' => false,
                    'error' => "Bu paket {$purchaseCount} satın almada kullanılıyor. Silmek yerine pasif yapın."
                ]);
                exit;
            }
            
            // Archive Stripe Product
            if ($package['stripe_product_id']) {
                require_once __DIR__ . '/../config/stripe.php';
                initStripe();
                
                try {
                    \Stripe\Product::update($package['stripe_product_id'], [
                        'active' => false
                    ]);
                } catch (\Stripe\Exception\ApiErrorException $e) {
                    error_log("Stripe product archive error: " . $e->getMessage());
                }
            }
            
            // Delete from database
            $stmt = $this->pdo->prepare("DELETE FROM lead_packages WHERE id = ?");
            $stmt->execute([$packageId]);
            
            error_log("✅ Lead package deleted: ID {$packageId}");
            
            echo json_encode(['success' => true, 'message' => 'Paket başarıyla silindi']);
            
        } catch (PDOException $e) {
            error_log("Delete lead package error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası']);
        }
    }
    
    /**
     * Lead İstekleri Sayfası
     * Admin bekleyen lead isteklerini görür ve manuel gönderir
     */
    public function leadRequests()
    {
        requireAdminLogin();
        
        // Bekleyen istekleri getir
        $stmt = $this->pdo->prepare("
            SELECT lr.*,
                   sp.name as provider_name,
                   sp.email as provider_email,
                   sp.phone as provider_phone,
                   sp.city as provider_city,
                   sp.service_type,
                   pp.package_name,
                   pp.leads_count,
                   pp.remaining_leads,
                   pp.purchased_at
            FROM lead_requests lr
            INNER JOIN service_providers sp ON lr.provider_id = sp.id
            INNER JOIN provider_purchases pp ON lr.purchase_id = pp.id
            WHERE lr.request_status = 'pending'
            ORDER BY lr.requested_at ASC
        ");
        $stmt->execute();
        $pendingRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Tamamlanmış istekleri getir (son 50)
        $stmt = $this->pdo->prepare("
            SELECT lr.*,
                   sp.name as provider_name,
                   pp.package_name,
                   l.id as lead_id,
                   l.phone as lead_phone,
                   l.city as lead_city,
                   a.username as admin_name
            FROM lead_requests lr
            INNER JOIN service_providers sp ON lr.provider_id = sp.id
            INNER JOIN provider_purchases pp ON lr.purchase_id = pp.id
            LEFT JOIN leads l ON lr.lead_id = l.id
            LEFT JOIN admins a ON lr.completed_by = a.id
            WHERE lr.request_status = 'completed'
            ORDER BY lr.completed_at DESC
            LIMIT 50
        ");
        $stmt->execute();
        $completedRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Mevcut lead'leri getir (gönderilmeye hazır)
        $stmt = $this->pdo->prepare("
            SELECT l.*,
                   COUNT(pld.id) as delivery_count
            FROM leads l
            LEFT JOIN provider_lead_deliveries pld ON l.id = pld.lead_id
            WHERE l.status = 'new'
            GROUP BY l.id
            ORDER BY l.created_at DESC
            LIMIT 100
        ");
        $stmt->execute();
        $availableLeads = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // View'ı render et
        $pageTitle = 'Lead İstekleri';
        $pageData = [
            'pendingRequests' => $pendingRequests,
            'completedRequests' => $completedRequests,
            'availableLeads' => $availableLeads
        ];
        
        require __DIR__ . '/../Views/admin/lead_requests.php';
    }
    
    /**
     * Manuel Lead Gönderimi
     * Admin, lead isteğine manuel olarak lead atar (AJAX)
     */
    public function sendLeadManually()
    {
        header('Content-Type: application/json');
        requireAdminLogin();
        
        // Sadece POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }
        
        // CSRF token kontrolü
        if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
            exit;
        }
        
        $requestId = intval($_POST['request_id'] ?? 0);
        $leadId = intval($_POST['lead_id'] ?? 0);
        
        if (!$requestId || !$leadId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Request ID ve Lead ID gerekli']);
            exit;
        }
        
        try {
            $this->pdo->beginTransaction();
            
            // Lead request'i getir
            $stmt = $this->pdo->prepare("
                SELECT lr.*, pp.provider_id, pp.remaining_leads, sp.service_type, sp.city
                FROM lead_requests lr
                INNER JOIN provider_purchases pp ON lr.purchase_id = pp.id
                INNER JOIN service_providers sp ON lr.provider_id = sp.id
                WHERE lr.id = ? AND lr.request_status = 'pending'
            ");
            $stmt->execute([$requestId]);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$request) {
                throw new Exception('İstek bulunamadı veya zaten tamamlanmış');
            }
            
            // Kalan lead var mı kontrol et
            if ($request['remaining_leads'] <= 0) {
                throw new Exception('Bu pakette kalan lead yok');
            }
            
            // Lead'i getir ve kontrol et
            $stmt = $this->pdo->prepare("SELECT * FROM leads WHERE id = ? AND status = 'new'");
            $stmt->execute([$leadId]);
            $lead = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$lead) {
                throw new Exception('Lead bulunamadı veya zaten gönderilmiş');
            }
            
            // Lead uygun mu kontrol et (aynı şehir ve hizmet türü) - Case-insensitive
            if (strcasecmp($lead['service_type'], $request['service_type']) !== 0) {
                error_log("❌ Service type mismatch: Lead='{$lead['service_type']}' vs Request='{$request['service_type']}'");
                throw new Exception('Lead hizmet türü uyuşmuyor');
            }
            
            if (strcasecmp($lead['city'], $request['city']) !== 0) {
                error_log("❌ City mismatch: Lead='{$lead['city']}' vs Request='{$request['city']}'");
                throw new Exception('Lead şehri uyuşmuyor');
            }
            
            error_log("✅ Lead validation passed: Service={$lead['service_type']}, City={$lead['city']}");
            
            // Lead'i gönder (delivery_method: 'system' - admin tarafından manuel)
            $stmt = $this->pdo->prepare("
                INSERT INTO provider_lead_deliveries 
                (purchase_id, provider_id, lead_id, delivery_method, delivered_at, delivered_by, request_id)
                VALUES (?, ?, ?, 'system', NOW(), ?, ?)
            ");
            $stmt->execute([
                $request['purchase_id'],
                $request['provider_id'],
                $leadId,
                $_SESSION['admin_id'],
                $requestId
            ]);
            
            // Lead durumunu güncelle
            $stmt = $this->pdo->prepare("UPDATE leads SET status = 'sold' WHERE id = ?");
            $stmt->execute([$leadId]);
            
            // Purchase'daki remaining_leads'i azalt
            $stmt = $this->pdo->prepare("
                UPDATE provider_purchases 
                SET remaining_leads = remaining_leads - 1 
                WHERE id = ?
            ");
            $stmt->execute([$request['purchase_id']]);
            
            // Request'i tamamla
            $stmt = $this->pdo->prepare("
                UPDATE lead_requests 
                SET request_status = 'completed',
                    completed_at = NOW(),
                    completed_by = ?,
                    lead_id = ?
                WHERE id = ?
            ");
            $stmt->execute([$_SESSION['admin_id'], $leadId, $requestId]);
            $affectedRows = $stmt->rowCount();
            
            error_log("🔄 Lead request update: Request={$requestId}, AffectedRows={$affectedRows}");
            
            // Transaction commit ET!
            $this->pdo->commit();
            
            error_log("✅ TRANSACTION COMMITTED! Lead manually sent: Request={$requestId}, Lead={$leadId}, Admin={$_SESSION['admin_id']}");
            
            // Son durumu kontrol et
            $stmt = $this->pdo->prepare("SELECT request_status FROM lead_requests WHERE id = ?");
            $stmt->execute([$requestId]);
            $finalStatus = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("🔍 Final status check: Request={$requestId}, Status={$finalStatus['request_status']}");
            
            // Başarılı yanıt
            echo json_encode([
                'success' => true,
                'message' => 'Lead başarıyla gönderildi'
            ]);
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Manual lead send error: " . $e->getMessage());
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Manual lead send DB error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Veritabanı hatası'
            ]);
        }
    }
    
    /**
     * Bekleyen İstekleri Getir (AJAX)
     * Dashboard widget için
     */
    public function getPendingRequestsCount()
    {
        header('Content-Type: application/json');
        requireAdminLogin();
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as count 
                FROM lead_requests 
                WHERE request_status = 'pending'
            ");
            $stmt->execute();
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            echo json_encode([
                'success' => true,
                'count' => $count
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Veritabanı hatası'
            ]);
        }
    }
    
    /**
     * Provider Messages - List all providers and send messages
     */
    public function providerMessages()
    {
        requireAdminLogin();
        
        try {
            // Pagination parameters
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $perPage = 12; // 12 providers per page
            $offset = ($page - 1) * $perPage;
            
            // Get total providers count
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as total
                FROM service_providers sp
                WHERE sp.status IN ('active', 'pending')
            ");
            $stmt->execute();
            $totalProviders = $stmt->fetchColumn();
            $totalPages = ceil($totalProviders / $perPage);
            
            // Get providers with pagination
            $stmt = $this->pdo->prepare("
                SELECT 
                    sp.id,
                    sp.name,
                    sp.email,
                    sp.phone,
                    sp.service_type,
                    sp.city,
                    sp.status,
                    (SELECT COUNT(*) FROM provider_messages WHERE provider_id = sp.id AND deleted_at IS NULL) as message_count,
                    (SELECT COUNT(*) FROM provider_messages WHERE provider_id = sp.id AND is_read = 0 AND deleted_at IS NULL) as unread_count
                FROM service_providers sp
                WHERE sp.status IN ('active', 'pending')
                ORDER BY sp.created_at DESC
                LIMIT ? OFFSET ?
            ");
            $stmt->execute([$perPage, $offset]);
            $providers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get message statistics
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
            
        } catch (PDOException $e) {
            error_log("Provider Messages Error: " . $e->getMessage());
            
            // If provider_messages table doesn't exist, provide empty data
            if (strpos($e->getMessage(), "doesn't exist") !== false || 
                strpos($e->getMessage(), "Table") !== false) {
                
                // Get providers without message counts (with pagination)
                try {
                    // Get total count
                    $stmt = $this->pdo->prepare("
                        SELECT COUNT(*) as total
                        FROM service_providers sp
                        WHERE sp.status IN ('active', 'pending')
                    ");
                    $stmt->execute();
                    $totalProviders = $stmt->fetchColumn();
                    $totalPages = ceil($totalProviders / $perPage);
                    
                    $stmt = $this->pdo->prepare("
                        SELECT 
                            sp.id,
                            sp.name,
                            sp.email,
                            sp.phone,
                            sp.service_type,
                            sp.city,
                            sp.status,
                            0 as message_count,
                            0 as unread_count
                        FROM service_providers sp
                        WHERE sp.status IN ('active', 'pending')
                        ORDER BY sp.created_at DESC
                        LIMIT ? OFFSET ?
                    ");
                    $stmt->execute([$perPage, $offset]);
                    $providers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e2) {
                    $providers = [];
                }
                
                $stats = [
                    'total_messages' => 0,
                    'unread_messages' => 0,
                    'providers_with_messages' => 0
                ];
                
                // Show warning
                $_SESSION['warning_message'] = 'provider_messages tablosu bulunamadı. Migration çalıştırın.';
            } else {
                throw $e;
            }
        }
        
        // Set default values if not already set (for error handling)
        if (!isset($page)) $page = 1;
        if (!isset($totalPages)) $totalPages = 1;
        if (!isset($totalProviders)) $totalProviders = count($providers ?? []);
        
        // Render view
        require __DIR__ . '/../Views/admin/provider_messages.php';
    }
    
    /**
     * Send Message to Provider (AJAX)
     */
    public function sendMessage()
    {
        requireAdminLogin();
        
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Geçersiz CSRF token']);
            return;
        }
        
        $providerId = $_POST['provider_id'] ?? null;
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $messageType = $_POST['message_type'] ?? 'info';
        $priority = $_POST['priority'] ?? 'normal';
        
        // Validation
        if (!$providerId || empty($subject) || empty($message)) {
            echo json_encode(['success' => false, 'message' => 'Tüm alanlar zorunludur']);
            return;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO provider_messages (
                    provider_id,
                    sender_type,
                    sender_id,
                    subject,
                    message,
                    message_type,
                    priority,
                    created_at
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
            
            echo json_encode([
                'success' => true,
                'message' => 'Mesaj başarıyla gönderildi'
            ]);
        } catch (PDOException $e) {
            error_log("Send message error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Mesaj gönderilirken bir hata oluştu'
            ]);
        }
    }
    
    /**
     * Get Provider Message History (AJAX)
     */
    public function getProviderMessageHistory()
    {
        requireAdminLogin();
        
        header('Content-Type: application/json');
        
        $providerId = $_GET['provider_id'] ?? null;
        
        if (!$providerId) {
            echo json_encode(['success' => false, 'message' => 'Provider ID gerekli']);
            return;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    pm.*,
                    a.username as sender_name,
                    sp.name as provider_name
                FROM provider_messages pm
                LEFT JOIN admins a ON pm.sender_id = a.id
                LEFT JOIN service_providers sp ON pm.provider_id = sp.id
                WHERE pm.provider_id = ? AND pm.deleted_at IS NULL
                ORDER BY pm.created_at DESC
                LIMIT 50
            ");
            $stmt->execute([$providerId]);
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'messages' => $messages
            ]);
        } catch (PDOException $e) {
            error_log("Get message history error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Mesaj geçmişi alınamadı'
            ]);
        }
    }
}
