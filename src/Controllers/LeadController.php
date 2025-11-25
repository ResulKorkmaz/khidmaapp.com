<?php

/**
 * KhidmaApp.com - Lead Controller
 * 
 * Müşteri taleplerini (lead) yönetir
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Services/NotificationService.php';

class LeadController 
{
    private $pdo;
    private $notificationService;
    
    public function __construct() 
    {
        $this->pdo = getDatabase();
        $this->notificationService = new NotificationService($this->pdo);
        
        // Veritabanı bağlantısı yoksa null olarak devam et
        // store() metodunda kontrol edilecek
    }
    
    /**
     * Yeni lead kaydet (Form submission)
     */
    public function store() 
    {
        // AJAX isteği için error output'u kapat
        ini_set('display_errors', 0);
        error_reporting(E_ALL);
        
        // Output buffering başlat (herhangi bir echo/warning'i yakala)
        ob_start();
        
        // JSON response için header ayarla
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            // Veritabanı bağlantısı kontrolü
            if ($this->pdo === null) {
                ob_clean();
                http_response_code(503);
                echo json_encode([
                    'success' => false,
                    'message' => 'خدمة قاعدة البيانات غير متاحة حالياً. يرجى المحاولة لاحقاً',
                    'error' => 'Database connection failed'
                ]);
                ob_end_flush();
                return;
            }
            
            // Honeypot SPAM protection check
            if (!empty($_POST['website'])) {
                // Bot detected (honeypot field filled)
                ob_clean();
                error_log("SPAM detected: Honeypot field filled | IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
                
                // Return success to not alert the bot
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'message' => 'تم إرسال طلبك بنجاح!',
                    'redirect' => '/thanks'
                ]);
                ob_end_flush();
                return;
            }
            
            // CSRF token kontrolü
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($csrfToken)) {
                ob_clean();
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'غير مصرح بهذا الطلب',
                    'error' => 'CSRF token invalid'
                ]);
                ob_end_flush();
                return;
            }
            
            // Input validation
            $validationResult = $this->validateLeadInput($_POST);
            if (!$validationResult['valid']) {
                ob_clean();
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => $validationResult['message'],
                    'errors' => $validationResult['errors']
                ]);
                ob_end_flush();
                return;
            }
            
            $data = $validationResult['data'];
            
            // Rate limiting kontrolü (aynı IP'den çok fazla istek)
            if (!$this->checkRateLimit()) {
                ob_clean();
                http_response_code(429);
                echo json_encode([
                    'success' => false,
                    'message' => 'تم إرسال طلبات كثيرة، يرجى المحاولة مرة أخرى لاحقاً',
                    'error' => 'Rate limit exceeded'
                ]);
                ob_end_flush();
                return;
            }
            
            // Duplicate kontrolü (aynı telefon numarası ve servis için son 1 saatte)
            if ($this->isDuplicateRequest($data['phone'], $data['service_type'])) {
                ob_clean();
                echo json_encode([
                    'success' => false,
                    'message' => 'لقد تم إرسال طلب مماثل مؤخراً، سنتواصل معك قريباً',
                    'error' => 'Duplicate request detected'
                ]);
                ob_end_flush();
                return;
            }
            
            // Veritabanına kaydet
            $leadId = $this->insertLead($data);
            
            // Buffer'daki gereksiz output'u temizle
            ob_clean();
            
            // Başarılı response (Redirect to thanks page)
            echo json_encode([
                'success' => true,
                'message' => 'تم إرسال طلبك بنجاح! سنتواصل معك خلال دقائق',
                'lead_id' => $leadId,
                'redirect' => '/thanks?ref=' . str_pad($leadId, 8, '0', STR_PAD_LEFT),
                'data' => [
                    'service_type' => $data['service_type'],
                    'city' => $data['city']
                ]
            ]);
            
            ob_end_flush();
            
            // Lead notification (WhatsApp, SMS vb. için hazırlık)
            $this->sendLeadNotification($leadId, $data);
            
        } catch (Exception $e) {
            // Hata yönetimi
            error_log("Lead Controller Error: " . $e->getMessage() . " | File: " . $e->getFile() . " | Line: " . $e->getLine());
            
            ob_clean();
            http_response_code(500);
            
            echo json_encode([
                'success' => false,
                'message' => 'حدث خطأ أثناء معالجة طلبك، يرجى المحاولة مرة أخرى'
            ]);
            ob_end_flush();
        }
    }
    
    /**
     * Lead listesi (Admin için)
     */
    public function index() 
    {
        // Admin authentication check (ileride implement edilecek)
        if (!$this->isAdminAuthenticated()) {
            header('Location: /admin/login');
            exit;
        }
        
        try {
            $filters = $this->getFilters();
            $leads = $this->getLeads($filters);
            
            // Admin view'ini yükle (ileride oluşturulacak)
            include __DIR__ . '/../Views/admin/leads.php';
            
        } catch (Exception $e) {
            error_log("Lead listing error: " . $e->getMessage());
            // Hata sayfası göster
        }
    }
    
    /**
     * Lead detayları
     */
    public function show($id) 
    {
        if (!$this->isAdminAuthenticated()) {
            header('Location: /admin/login');
            exit;
        }
        
        try {
            $lead = $this->getLeadById($id);
            
            if (!$lead) {
                // 404 sayfası
                http_response_code(404);
                return;
            }
            
            // Lead detail view'ini yükle
            include __DIR__ . '/../Views/admin/lead_detail.php';
            
        } catch (Exception $e) {
            error_log("Lead detail error: " . $e->getMessage());
        }
    }
    
    /**
     * Lead durumu güncelle
     */
    public function updateStatus() 
    {
        header('Content-Type: application/json; charset=utf-8');
        
        if (!$this->isAdminAuthenticated()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        try {
            $leadId = $_POST['lead_id'] ?? 0;
            $status = $_POST['status'] ?? '';
            
            $validStatuses = ['new', 'verified', 'sold', 'invalid'];
            
            if (!in_array($status, $validStatuses)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid status']);
                return;
            }
            
            $sql = "UPDATE leads SET status = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$status, $leadId]);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update status']);
            }
            
        } catch (Exception $e) {
            error_log("Status update error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Internal error']);
        }
    }
    
    /**
     * Input validation
     */
    private function validateLeadInput($input) 
    {
        $errors = [];
        $data = [];
        
        // Service type validation
        $serviceType = sanitizeInput($input['service_type'] ?? '');
        $validServices = array_keys(getServiceTypes());
        
        if (empty($serviceType)) {
            $errors['service_type'] = 'نوع الخدمة مطلوب';
        } elseif (!in_array($serviceType, $validServices)) {
            $errors['service_type'] = 'نوع الخدمة غير صحيح';
        } else {
            $data['service_type'] = $serviceType;
        }
        
        // City validation
        $city = sanitizeInput($input['city'] ?? '');
        $validCities = array_keys(getCities());
        
        if (empty($city)) {
            $errors['city'] = 'المدينة مطلوبة';
        } elseif (!in_array($city, $validCities)) {
            $errors['city'] = 'المدينة غير صحيحة';
        } else {
            $data['city'] = $city;
        }
        
        // Phone validation
        $phone = sanitizeInput($input['phone'] ?? '');
        $phone = preg_replace('/[^\d+]/', '', $phone); // Remove non-numeric characters except +
        $phoneConfirm = sanitizeInput($input['phone_confirm'] ?? '');
        $phoneConfirm = preg_replace('/[^\d+]/', '', $phoneConfirm);
        
        if (empty($phone)) {
            $errors['phone'] = 'رقم الهاتف مطلوب';
        } else {
            // Suudi telefon formatlarını kontrol et
            $validPatterns = [
                '/^05[0-9]{8}$/',        // 05xxxxxxxx (10 digits)
                '/^5[0-9]{8}$/',         // 5xxxxxxxx (9 digits) 
                '/^\+9665[0-9]{8}$/',    // +9665xxxxxxxx
                '/^009665[0-9]{8}$/'     // 009665xxxxxxxx
            ];
            
            $isValid = false;
            foreach ($validPatterns as $pattern) {
                if (preg_match($pattern, $phone)) {
                    $isValid = true;
                    break;
                }
            }
            
            if (!$isValid) {
                $errors['phone'] = 'رقم هاتف سعودي غير صحيح (مثال: 0501234567)';
            } elseif (empty($phoneConfirm)) {
                $errors['phone_confirm'] = 'تأكيد رقم الهاتف مطلوب';
            } elseif ($phone !== $phoneConfirm) {
                $errors['phone_confirm'] = 'رقم الهاتف غير متطابق';
            } else {
                // Normalize phone number to 05xxxxxxxx format
                if (strpos($phone, '+9665') === 0) {
                    $phone = '0' . substr($phone, 5);
                } elseif (strpos($phone, '009665') === 0) {
                    $phone = '0' . substr($phone, 6);
                } elseif (strpos($phone, '9665') === 0) {
                    $phone = '0' . substr($phone, 4);
                } elseif (strpos($phone, '5') === 0 && strlen($phone) === 9) {
                    $phone = '0' . $phone;
                }
                $data['phone'] = $phone;
            }
        }
        
        // Description (optional)
        $description = sanitizeInput($input['description'] ?? '');
        
        if (strlen($description) > 1000) {
            $errors['description'] = 'الوصف طويل جداً (الحد الأقصى 1000 حرف)';
        } else {
            $data['description'] = $description;
        }
        
        // Service Time validation (required)
        $serviceTimeType = sanitizeInput($input['service_time_type'] ?? '');
        $validTimeTypes = ['urgent', 'within_24h', 'scheduled'];
        
        if (empty($serviceTimeType)) {
            $errors['service_time_type'] = 'وقت الخدمة مطلوب';
        } elseif (!in_array($serviceTimeType, $validTimeTypes)) {
            $errors['service_time_type'] = 'وقت الخدمة غير صحيح';
        } else {
            $data['service_time_type'] = $serviceTimeType;
            
            // If scheduled, validate date
            if ($serviceTimeType === 'scheduled') {
                $scheduledDate = sanitizeInput($input['scheduled_date'] ?? '');
                
                if (empty($scheduledDate)) {
                    $errors['scheduled_date'] = 'التاريخ المحدد مطلوب';
                } else {
                    // Validate date format and future date
                    $dateObj = DateTime::createFromFormat('Y-m-d', $scheduledDate);
                    $today = new DateTime();
                    $today->setTime(0, 0, 0);
                    
                    if (!$dateObj || $dateObj->format('Y-m-d') !== $scheduledDate) {
                        $errors['scheduled_date'] = 'تاريخ غير صحيح';
                    } elseif ($dateObj < $today) {
                        $errors['scheduled_date'] = 'التاريخ يجب أن يكون في المستقبل';
                    } else {
                        $data['scheduled_date'] = $scheduledDate;
                    }
                }
            }
        }
        
        // Terms & Privacy acceptance (automatically accepted by form submission)
        
        // Additional data
        $data['source'] = 'website';
        $data['whatsapp_phone'] = $data['phone']; // Assume same as phone for now
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'data' => $data,
            'message' => empty($errors) ? '' : 'يوجد أخطاء في البيانات المدخلة'
        ];
    }
    
    /**
     * Insert lead to database
     */
    private function insertLead($data) 
    {
        $sql = "INSERT INTO leads (service_type, city, description, phone, whatsapp_phone, source, service_time_type, scheduled_date, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'new', NOW())";
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            $data['service_type'],
            $data['city'],
            $data['description'],
            $data['phone'],
            $data['whatsapp_phone'],
            $data['source'],
            $data['service_time_type'] ?? null,
            $data['scheduled_date'] ?? null
        ]);
        
        if (!$result) {
            throw new Exception('Database insert failed');
        }
        
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Rate limiting check
     */
    private function checkRateLimit() 
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $timeWindow = 300; // 5 minutes
        $maxRequests = 5; // Max 5 requests per 5 minutes
        
        try {
            $sql = "SELECT COUNT(*) FROM leads 
                    WHERE created_at > DATE_SUB(NOW(), INTERVAL ? SECOND) 
                    AND source = 'website'";
            
            // Bu basit bir implementasyon, production'da Redis veya Memcached kullanılabilir
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$timeWindow]);
            $requestCount = $stmt->fetchColumn();
            
            return $requestCount < $maxRequests;
            
        } catch (Exception $e) {
            // Rate limiting hatası durumunda işleme devam et
            error_log("Rate limit check error: " . $e->getMessage());
            return true;
        }
    }
    
    /**
     * Duplicate request check
     */
    private function isDuplicateRequest($phone, $serviceType) 
    {
        try {
            $sql = "SELECT COUNT(*) FROM leads 
                    WHERE phone = ? AND service_type = ? 
                    AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$phone, $serviceType]);
            $count = $stmt->fetchColumn();
            
            return $count > 0;
            
        } catch (Exception $e) {
            error_log("Duplicate check error: " . $e->getMessage());
            return false; // Hata durumunda işleme devam et
        }
    }
    
    /**
     * Send lead notification
     */
    private function sendLeadNotification($leadId, $data) 
    {
        try {
            // Send notification using NotificationService
            $this->notificationService->sendNewLeadNotification($leadId, $data);
            
            // Log for debugging (Türkçe - Admin için)
            $serviceTypes = getServiceTypes();
            $cities = getCities();
            
            $serviceName = $serviceTypes[$data['service_type']]['tr'] ?? $data['service_type'];
            $cityName = $cities[$data['city']]['tr'] ?? $data['city'];
            
            $logMessage = sprintf(
                "Yeni Lead #%d: %s - %s - Telefon: %s",
                $leadId,
                $serviceName,
                $cityName,
                $data['phone']
            );
            
            error_log("LEAD_NOTIFICATION: " . $logMessage);
            
        } catch (Exception $e) {
            error_log("Bildirim gönderilemedi: " . $e->getMessage());
        }
    }
    
    /**
     * Get leads with filters (for admin)
     */
    private function getLeads($filters = []) 
    {
        $sql = "SELECT * FROM leads WHERE 1=1";
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['service_type'])) {
            $sql .= " AND service_type = ?";
            $params[] = $filters['service_type'];
        }
        
        if (!empty($filters['city'])) {
            $sql .= " AND city = ?";
            $params[] = $filters['city'];
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(created_at) >= ?";
            $params[] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(created_at) <= ?";
            $params[] = $filters['date_to'];
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT 100";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get lead by ID
     */
    private function getLeadById($id) 
    {
        $sql = "SELECT * FROM leads WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        
        return $stmt->fetch();
    }
    
    /**
     * Get filters from request
     */
    private function getFilters() 
    {
        return [
            'status' => sanitizeInput($_GET['status'] ?? ''),
            'service_type' => sanitizeInput($_GET['service_type'] ?? ''),
            'city' => sanitizeInput($_GET['city'] ?? ''),
            'date_from' => sanitizeInput($_GET['date_from'] ?? ''),
            'date_to' => sanitizeInput($_GET['date_to'] ?? '')
        ];
    }
    
    /**
     * Check admin authentication (basit implementasyon)
     */
    private function isAdminAuthenticated() 
    {
        startSession();
        return isset($_SESSION['admin_authenticated']) && $_SESSION['admin_authenticated'] === true;
    }
}

/**
 * KhidmaApp.com - Lead Controller
 * 
 * Müşteri taleplerini (lead) yönetir
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Services/NotificationService.php';

class LeadController 
{
    private $pdo;
    private $notificationService;
    
    public function __construct() 
    {
        $this->pdo = getDatabase();
        $this->notificationService = new NotificationService($this->pdo);
        
        // Veritabanı bağlantısı yoksa null olarak devam et
        // store() metodunda kontrol edilecek
    }
    
    /**
     * Yeni lead kaydet (Form submission)
     */
    public function store() 
    {
        // AJAX isteği için error output'u kapat
        ini_set('display_errors', 0);
        error_reporting(E_ALL);
        
        // Output buffering başlat (herhangi bir echo/warning'i yakala)
        ob_start();
        
        // JSON response için header ayarla
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            // Veritabanı bağlantısı kontrolü
            if ($this->pdo === null) {
                ob_clean();
                http_response_code(503);
                echo json_encode([
                    'success' => false,
                    'message' => 'خدمة قاعدة البيانات غير متاحة حالياً. يرجى المحاولة لاحقاً',
                    'error' => 'Database connection failed'
                ]);
                ob_end_flush();
                return;
            }
            
            // Honeypot SPAM protection check
            if (!empty($_POST['website'])) {
                // Bot detected (honeypot field filled)
                ob_clean();
                error_log("SPAM detected: Honeypot field filled | IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
                
                // Return success to not alert the bot
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'message' => 'تم إرسال طلبك بنجاح!',
                    'redirect' => '/thanks'
                ]);
                ob_end_flush();
                return;
            }
            
            // CSRF token kontrolü
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($csrfToken)) {
                ob_clean();
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'غير مصرح بهذا الطلب',
                    'error' => 'CSRF token invalid'
                ]);
                ob_end_flush();
                return;
            }
            
            // Input validation
            $validationResult = $this->validateLeadInput($_POST);
            if (!$validationResult['valid']) {
                ob_clean();
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => $validationResult['message'],
                    'errors' => $validationResult['errors']
                ]);
                ob_end_flush();
                return;
            }
            
            $data = $validationResult['data'];
            
            // Rate limiting kontrolü (aynı IP'den çok fazla istek)
            if (!$this->checkRateLimit()) {
                ob_clean();
                http_response_code(429);
                echo json_encode([
                    'success' => false,
                    'message' => 'تم إرسال طلبات كثيرة، يرجى المحاولة مرة أخرى لاحقاً',
                    'error' => 'Rate limit exceeded'
                ]);
                ob_end_flush();
                return;
            }
            
            // Duplicate kontrolü (aynı telefon numarası ve servis için son 1 saatte)
            if ($this->isDuplicateRequest($data['phone'], $data['service_type'])) {
                ob_clean();
                echo json_encode([
                    'success' => false,
                    'message' => 'لقد تم إرسال طلب مماثل مؤخراً، سنتواصل معك قريباً',
                    'error' => 'Duplicate request detected'
                ]);
                ob_end_flush();
                return;
            }
            
            // Veritabanına kaydet
            $leadId = $this->insertLead($data);
            
            // Buffer'daki gereksiz output'u temizle
            ob_clean();
            
            // Başarılı response (Redirect to thanks page)
            echo json_encode([
                'success' => true,
                'message' => 'تم إرسال طلبك بنجاح! سنتواصل معك خلال دقائق',
                'lead_id' => $leadId,
                'redirect' => '/thanks?ref=' . str_pad($leadId, 8, '0', STR_PAD_LEFT),
                'data' => [
                    'service_type' => $data['service_type'],
                    'city' => $data['city']
                ]
            ]);
            
            ob_end_flush();
            
            // Lead notification (WhatsApp, SMS vb. için hazırlık)
            $this->sendLeadNotification($leadId, $data);
            
        } catch (Exception $e) {
            // Hata yönetimi
            error_log("Lead Controller Error: " . $e->getMessage() . " | File: " . $e->getFile() . " | Line: " . $e->getLine());
            
            ob_clean();
            http_response_code(500);
            
            echo json_encode([
                'success' => false,
                'message' => 'حدث خطأ أثناء معالجة طلبك، يرجى المحاولة مرة أخرى'
            ]);
            ob_end_flush();
        }
    }
    
    /**
     * Lead listesi (Admin için)
     */
    public function index() 
    {
        // Admin authentication check (ileride implement edilecek)
        if (!$this->isAdminAuthenticated()) {
            header('Location: /admin/login');
            exit;
        }
        
        try {
            $filters = $this->getFilters();
            $leads = $this->getLeads($filters);
            
            // Admin view'ini yükle (ileride oluşturulacak)
            include __DIR__ . '/../Views/admin/leads.php';
            
        } catch (Exception $e) {
            error_log("Lead listing error: " . $e->getMessage());
            // Hata sayfası göster
        }
    }
    
    /**
     * Lead detayları
     */
    public function show($id) 
    {
        if (!$this->isAdminAuthenticated()) {
            header('Location: /admin/login');
            exit;
        }
        
        try {
            $lead = $this->getLeadById($id);
            
            if (!$lead) {
                // 404 sayfası
                http_response_code(404);
                return;
            }
            
            // Lead detail view'ini yükle
            include __DIR__ . '/../Views/admin/lead_detail.php';
            
        } catch (Exception $e) {
            error_log("Lead detail error: " . $e->getMessage());
        }
    }
    
    /**
     * Lead durumu güncelle
     */
    public function updateStatus() 
    {
        header('Content-Type: application/json; charset=utf-8');
        
        if (!$this->isAdminAuthenticated()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        try {
            $leadId = $_POST['lead_id'] ?? 0;
            $status = $_POST['status'] ?? '';
            
            $validStatuses = ['new', 'verified', 'sold', 'invalid'];
            
            if (!in_array($status, $validStatuses)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid status']);
                return;
            }
            
            $sql = "UPDATE leads SET status = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$status, $leadId]);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update status']);
            }
            
        } catch (Exception $e) {
            error_log("Status update error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Internal error']);
        }
    }
    
    /**
     * Input validation
     */
    private function validateLeadInput($input) 
    {
        $errors = [];
        $data = [];
        
        // Service type validation
        $serviceType = sanitizeInput($input['service_type'] ?? '');
        $validServices = array_keys(getServiceTypes());
        
        if (empty($serviceType)) {
            $errors['service_type'] = 'نوع الخدمة مطلوب';
        } elseif (!in_array($serviceType, $validServices)) {
            $errors['service_type'] = 'نوع الخدمة غير صحيح';
        } else {
            $data['service_type'] = $serviceType;
        }
        
        // City validation
        $city = sanitizeInput($input['city'] ?? '');
        $validCities = array_keys(getCities());
        
        if (empty($city)) {
            $errors['city'] = 'المدينة مطلوبة';
        } elseif (!in_array($city, $validCities)) {
            $errors['city'] = 'المدينة غير صحيحة';
        } else {
            $data['city'] = $city;
        }
        
        // Phone validation
        $phone = sanitizeInput($input['phone'] ?? '');
        $phone = preg_replace('/[^\d+]/', '', $phone); // Remove non-numeric characters except +
        $phoneConfirm = sanitizeInput($input['phone_confirm'] ?? '');
        $phoneConfirm = preg_replace('/[^\d+]/', '', $phoneConfirm);
        
        if (empty($phone)) {
            $errors['phone'] = 'رقم الهاتف مطلوب';
        } else {
            // Suudi telefon formatlarını kontrol et
            $validPatterns = [
                '/^05[0-9]{8}$/',        // 05xxxxxxxx (10 digits)
                '/^5[0-9]{8}$/',         // 5xxxxxxxx (9 digits) 
                '/^\+9665[0-9]{8}$/',    // +9665xxxxxxxx
                '/^009665[0-9]{8}$/'     // 009665xxxxxxxx
            ];
            
            $isValid = false;
            foreach ($validPatterns as $pattern) {
                if (preg_match($pattern, $phone)) {
                    $isValid = true;
                    break;
                }
            }
            
            if (!$isValid) {
                $errors['phone'] = 'رقم هاتف سعودي غير صحيح (مثال: 0501234567)';
            } elseif (empty($phoneConfirm)) {
                $errors['phone_confirm'] = 'تأكيد رقم الهاتف مطلوب';
            } elseif ($phone !== $phoneConfirm) {
                $errors['phone_confirm'] = 'رقم الهاتف غير متطابق';
            } else {
                // Normalize phone number to 05xxxxxxxx format
                if (strpos($phone, '+9665') === 0) {
                    $phone = '0' . substr($phone, 5);
                } elseif (strpos($phone, '009665') === 0) {
                    $phone = '0' . substr($phone, 6);
                } elseif (strpos($phone, '9665') === 0) {
                    $phone = '0' . substr($phone, 4);
                } elseif (strpos($phone, '5') === 0 && strlen($phone) === 9) {
                    $phone = '0' . $phone;
                }
                $data['phone'] = $phone;
            }
        }
        
        // Description (optional)
        $description = sanitizeInput($input['description'] ?? '');
        
        if (strlen($description) > 1000) {
            $errors['description'] = 'الوصف طويل جداً (الحد الأقصى 1000 حرف)';
        } else {
            $data['description'] = $description;
        }
        
        // Service Time validation (required)
        $serviceTimeType = sanitizeInput($input['service_time_type'] ?? '');
        $validTimeTypes = ['urgent', 'within_24h', 'scheduled'];
        
        if (empty($serviceTimeType)) {
            $errors['service_time_type'] = 'وقت الخدمة مطلوب';
        } elseif (!in_array($serviceTimeType, $validTimeTypes)) {
            $errors['service_time_type'] = 'وقت الخدمة غير صحيح';
        } else {
            $data['service_time_type'] = $serviceTimeType;
            
            // If scheduled, validate date
            if ($serviceTimeType === 'scheduled') {
                $scheduledDate = sanitizeInput($input['scheduled_date'] ?? '');
                
                if (empty($scheduledDate)) {
                    $errors['scheduled_date'] = 'التاريخ المحدد مطلوب';
                } else {
                    // Validate date format and future date
                    $dateObj = DateTime::createFromFormat('Y-m-d', $scheduledDate);
                    $today = new DateTime();
                    $today->setTime(0, 0, 0);
                    
                    if (!$dateObj || $dateObj->format('Y-m-d') !== $scheduledDate) {
                        $errors['scheduled_date'] = 'تاريخ غير صحيح';
                    } elseif ($dateObj < $today) {
                        $errors['scheduled_date'] = 'التاريخ يجب أن يكون في المستقبل';
                    } else {
                        $data['scheduled_date'] = $scheduledDate;
                    }
                }
            }
        }
        
        // Terms & Privacy acceptance (automatically accepted by form submission)
        
        // Additional data
        $data['source'] = 'website';
        $data['whatsapp_phone'] = $data['phone']; // Assume same as phone for now
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'data' => $data,
            'message' => empty($errors) ? '' : 'يوجد أخطاء في البيانات المدخلة'
        ];
    }
    
    /**
     * Insert lead to database
     */
    private function insertLead($data) 
    {
        $sql = "INSERT INTO leads (service_type, city, description, phone, whatsapp_phone, source, service_time_type, scheduled_date, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'new', NOW())";
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            $data['service_type'],
            $data['city'],
            $data['description'],
            $data['phone'],
            $data['whatsapp_phone'],
            $data['source'],
            $data['service_time_type'] ?? null,
            $data['scheduled_date'] ?? null
        ]);
        
        if (!$result) {
            throw new Exception('Database insert failed');
        }
        
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Rate limiting check
     */
    private function checkRateLimit() 
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $timeWindow = 300; // 5 minutes
        $maxRequests = 5; // Max 5 requests per 5 minutes
        
        try {
            $sql = "SELECT COUNT(*) FROM leads 
                    WHERE created_at > DATE_SUB(NOW(), INTERVAL ? SECOND) 
                    AND source = 'website'";
            
            // Bu basit bir implementasyon, production'da Redis veya Memcached kullanılabilir
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$timeWindow]);
            $requestCount = $stmt->fetchColumn();
            
            return $requestCount < $maxRequests;
            
        } catch (Exception $e) {
            // Rate limiting hatası durumunda işleme devam et
            error_log("Rate limit check error: " . $e->getMessage());
            return true;
        }
    }
    
    /**
     * Duplicate request check
     */
    private function isDuplicateRequest($phone, $serviceType) 
    {
        try {
            $sql = "SELECT COUNT(*) FROM leads 
                    WHERE phone = ? AND service_type = ? 
                    AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$phone, $serviceType]);
            $count = $stmt->fetchColumn();
            
            return $count > 0;
            
        } catch (Exception $e) {
            error_log("Duplicate check error: " . $e->getMessage());
            return false; // Hata durumunda işleme devam et
        }
    }
    
    /**
     * Send lead notification
     */
    private function sendLeadNotification($leadId, $data) 
    {
        try {
            // Send notification using NotificationService
            $this->notificationService->sendNewLeadNotification($leadId, $data);
            
            // Log for debugging (Türkçe - Admin için)
            $serviceTypes = getServiceTypes();
            $cities = getCities();
            
            $serviceName = $serviceTypes[$data['service_type']]['tr'] ?? $data['service_type'];
            $cityName = $cities[$data['city']]['tr'] ?? $data['city'];
            
            $logMessage = sprintf(
                "Yeni Lead #%d: %s - %s - Telefon: %s",
                $leadId,
                $serviceName,
                $cityName,
                $data['phone']
            );
            
            error_log("LEAD_NOTIFICATION: " . $logMessage);
            
        } catch (Exception $e) {
            error_log("Bildirim gönderilemedi: " . $e->getMessage());
        }
    }
    
    /**
     * Get leads with filters (for admin)
     */
    private function getLeads($filters = []) 
    {
        $sql = "SELECT * FROM leads WHERE 1=1";
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['service_type'])) {
            $sql .= " AND service_type = ?";
            $params[] = $filters['service_type'];
        }
        
        if (!empty($filters['city'])) {
            $sql .= " AND city = ?";
            $params[] = $filters['city'];
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(created_at) >= ?";
            $params[] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(created_at) <= ?";
            $params[] = $filters['date_to'];
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT 100";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get lead by ID
     */
    private function getLeadById($id) 
    {
        $sql = "SELECT * FROM leads WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        
        return $stmt->fetch();
    }
    
    /**
     * Get filters from request
     */
    private function getFilters() 
    {
        return [
            'status' => sanitizeInput($_GET['status'] ?? ''),
            'service_type' => sanitizeInput($_GET['service_type'] ?? ''),
            'city' => sanitizeInput($_GET['city'] ?? ''),
            'date_from' => sanitizeInput($_GET['date_from'] ?? ''),
            'date_to' => sanitizeInput($_GET['date_to'] ?? '')
        ];
    }
    
    /**
     * Check admin authentication (basit implementasyon)
     */
    private function isAdminAuthenticated() 
    {
        startSession();
        return isset($_SESSION['admin_authenticated']) && $_SESSION['admin_authenticated'] === true;
    }
}


