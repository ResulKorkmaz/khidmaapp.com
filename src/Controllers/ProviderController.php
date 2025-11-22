<?php
/**
 * Provider Controller
 * 
 * Handles service provider (Usta) authentication and dashboard
 */

class ProviderController
{
    private $db;
    
    public function __construct()
    {
        $this->db = getDatabase();
        
        // Start session
        startSession();
    }
    
    /**
     * Get unread messages count for provider
     * Helper function to show badge in navigation
     */
    private function getUnreadMessagesCount($providerId)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as unread 
                FROM provider_messages 
                WHERE provider_id = ? AND is_read = 0 AND deleted_at IS NULL
            ");
            $stmt->execute([$providerId]);
            return $stmt->fetch(PDO::FETCH_ASSOC)['unread'] ?? 0;
        } catch (Exception $e) {
            error_log("Get unread messages count error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Provider Login
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
            $_SESSION['error'] = 'Geçersiz güvenlik belirteci. Lütfen tekrar deneyin.';
            header('Location: /');
            exit;
        }
        
        $identifier = trim($_POST['identifier'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        // Validate input
        if (empty($identifier) || empty($password)) {
            $_SESSION['error'] = 'الرجاء إدخال البريد الإلكتروني/رقم الهاتف وكلمة المرور';
            header('Location: /');
            exit;
        }
        
        // Find provider by email or phone
        $stmt = $this->db->prepare("
            SELECT * FROM service_providers 
            WHERE email = ? OR phone = ? 
            LIMIT 1
        ");
        $stmt->execute([$identifier, $identifier]);
        $provider = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if provider exists
        if (!$provider) {
            $_SESSION['error'] = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
            header('Location: /');
            exit;
        }
        
        // Verify password
        if (!password_verify($password, $provider['password_hash'])) {
            $_SESSION['error'] = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
            header('Location: /');
            exit;
        }
        
        // Check account status
        if ($provider['status'] === 'suspended') {
            $_SESSION['error'] = 'حسابك معلق. يرجى الاتصال بالدعم';
            header('Location: /');
            exit;
        }
        
        if ($provider['status'] === 'rejected') {
            $_SESSION['error'] = 'تم رفض حسابك';
            header('Location: /');
            exit;
        }
        
        // Set session
        $_SESSION['provider_id'] = $provider['id'];
        $_SESSION['provider_name'] = $provider['name'];
        $_SESSION['provider_email'] = $provider['email'];
        $_SESSION['provider_service_type'] = $provider['service_type'];
        
        // Update last login
        $stmt = $this->db->prepare("UPDATE service_providers SET last_login_at = NOW() WHERE id = ?");
        $stmt->execute([$provider['id']]);
        
        // Handle remember me
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $stmt = $this->db->prepare("UPDATE service_providers SET remember_token = ? WHERE id = ?");
            $stmt->execute([$token, $provider['id']]);
            
            // Set cookie for 30 days
            setcookie('provider_remember', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true);
        }
        
        $_SESSION['success'] = 'تم تسجيل الدخول بنجاح!';
        header('Location: /provider/dashboard');
        exit;
    }
    
    /**
     * Provider Registration
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
            $_SESSION['error'] = 'Invalid CSRF token';
            header('Location: /');
            exit;
        }
        
        // Get and sanitize input
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $service_type = trim($_POST['service_type'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $terms = isset($_POST['terms']);
        
        // Validation
        $errors = [];
        
        if (empty($name) || strlen($name) < 3) {
            $errors[] = 'الاسم يجب أن يكون 3 أحرف على الأقل';
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'البريد الإلكتروني غير صحيح';
        }
        
        if (empty($phone) || !preg_match('/^05[0-9]{8}$/', $phone)) {
            $errors[] = 'رقم الهاتف غير صحيح (يجب أن يبدأ بـ 05 ويحتوي على 10 أرقام)';
        }
        
        // Şehir validasyonu (sadece belirtilen şehirler) - KEY kullanıyoruz (küçük harf)
        $allowedCities = ['riyadh', 'jeddah', 'dammam'];
        if (empty($city) || !in_array($city, $allowedCities)) {
            $errors[] = 'يرجى اختيار مدينة صالحة';
        }
        
        if (empty($service_type) || !array_key_exists($service_type, getServiceTypes())) {
            $errors[] = 'نوع الخدمة غير صحيح';
        }
        
        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
        }
        
        if ($password !== $password_confirm) {
            $errors[] = 'كلمتا المرور غير متطابقتين';
        }
        
        if (!$terms) {
            $errors[] = 'يجب الموافقة على شروط الاستخدام';
        }
        
        // WhatsApp kanal onayı kontrolü
        $channelJoined = isset($_POST['channel_joined']);
        if (!$channelJoined) {
            $errors[] = 'يجب الانضمام إلى قناة WhatsApp وتأكيد العضوية';
        }
        
        // Check for existing email
        $stmt = $this->db->prepare("SELECT id FROM service_providers WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'البريد الإلكتروني مسجل بالفعل';
        }
        
        // Check for existing phone
        $stmt = $this->db->prepare("SELECT id FROM service_providers WHERE phone = ? LIMIT 1");
        $stmt->execute([$phone]);
        if ($stmt->fetch()) {
            $errors[] = 'رقم الهاتف مسجل بالفعل';
        }
        
        // If errors, redirect back
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: /');
            exit;
        }
        
        // Hash password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        
        // Generate verification token
        $verification_token = bin2hex(random_bytes(32));
        
        // Insert provider
        $stmt = $this->db->prepare("
            INSERT INTO service_providers 
            (name, email, phone, city, password_hash, service_type, status, verification_token, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, 'pending', ?, NOW())
        ");
        
        try {
            $stmt->execute([$name, $email, $phone, $city, $password_hash, $service_type, $verification_token]);
            
            // Get inserted ID
            $provider_id = $this->db->lastInsertId();
            
            // Auto-login
            $_SESSION['provider_id'] = $provider_id;
            $_SESSION['provider_name'] = $name;
            $_SESSION['provider_email'] = $email;
            $_SESSION['provider_service_type'] = $service_type;
            
            // TODO: Send verification email
            
            $_SESSION['success'] = 'تم إنشاء الحساب بنجاح! مرحباً بك';
            header('Location: /provider/dashboard');
            exit;
            
        } catch (PDOException $e) {
            error_log("Provider registration error: " . $e->getMessage());
            $_SESSION['error'] = 'حدث خطأ أثناء إنشاء الحساب. يرجى المحاولة مرة أخرى';
            header('Location: /');
            exit;
        }
    }
    
    /**
     * Dashboard ana sayfası
     * Usta paneli - istatistikler ve özet bilgiler
     */
    public function dashboard()
    {
        // Usta girişi kontrolü
        if (!isset($_SESSION['provider_id'])) {
            header('Location: /');
            exit;
        }
        
        $provider_id = $_SESSION['provider_id'];
        
        // Usta bilgilerini getir
        $stmt = $this->db->prepare("SELECT * FROM service_providers WHERE id = ? LIMIT 1");
        $stmt->execute([$provider_id]);
        $provider = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$provider) {
            session_destroy();
            header('Location: /');
            exit;
        }
        
        // Ustaya teslim edilmiş lead'leri getir
        $stmt = $this->db->prepare("
            SELECT 
                l.*,
                pld.delivered_at,
                pld.delivery_method,
                pp.package_name as purchase_package
            FROM provider_lead_deliveries pld
            INNER JOIN leads l ON pld.lead_id = l.id
            LEFT JOIN provider_purchases pp ON pld.purchase_id = pp.id
            WHERE pld.provider_id = ?
            ORDER BY pld.delivered_at DESC
            LIMIT 100
        ");
        $stmt->execute([$provider_id]);
        $deliveredLeads = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Ustanın satın aldığı paketleri ve teslim istatistiklerini getir
        $stmt = $this->db->prepare("
            SELECT 
                pp.*,
                lp.name_ar as package_name,
                lp.lead_count as total_leads,
                COUNT(DISTINCT pld.id) as delivered_count,
                (pp.leads_count - COUNT(DISTINCT pld.id)) as pending_count
            FROM provider_purchases pp
            LEFT JOIN lead_packages lp ON pp.package_id = lp.id
            LEFT JOIN provider_lead_deliveries pld ON pp.id = pld.purchase_id
            WHERE pp.provider_id = ?
            GROUP BY pp.id
            ORDER BY pp.purchased_at DESC
        ");
        $stmt->execute([$provider_id]);
        $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Her purchase için bekleyen lead request sayısını ekle
        foreach ($purchases as &$purchase) {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as pending_requests
                FROM lead_requests
                WHERE purchase_id = ? AND request_status = 'pending'
            ");
            $stmt->execute([$purchase['id']]);
            $purchase['pending_requests'] = $stmt->fetch(PDO::FETCH_ASSOC)['pending_requests'];
        }
        unset($purchase); // referansı temizle
        
        // İstatistikleri hesapla
        $stats = [
            'total_leads' => count($deliveredLeads),
            'today_leads' => 0,
            'purchased_leads' => 0,
            'available_leads' => 0,
            'total_spent' => 0,
            'total_purchases' => count($purchases)
        ];
        
        // Bugünkü lead sayısını hesapla
        $today = date('Y-m-d');
        foreach ($deliveredLeads as $lead) {
            if (date('Y-m-d', strtotime($lead['delivered_at'])) === $today) {
                $stats['today_leads']++;
            }
        }
        
        // Satın alınan ve kalan lead sayılarını hesapla
        foreach ($purchases as $purchase) {
            if ($purchase['payment_status'] === 'completed') {
                $stats['purchased_leads'] += $purchase['leads_count'];
                $stats['available_leads'] += ($purchase['leads_count'] - $purchase['delivered_count']);
                $stats['total_spent'] += $purchase['price_paid']; // Alan adı price_paid
            }
        }
        
        // View'a veri gönder
        $pageData = [
            'provider' => $provider,
            'deliveredLeads' => $deliveredLeads,
            'purchases' => $purchases,
            'stats' => $stats
        ];
        
        // Dashboard view'ı yükle
        require_once __DIR__ . '/../Views/provider/dashboard.php';
    }
    
    /**
     * Purchase Package Page
     */
    public function purchasePackage()
    {
        // Check if provider is logged in
        if (!isset($_SESSION['provider_id'])) {
            header('Location: /');
            exit;
        }
        
        // Get package ID from URL
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $parts = explode('/', $uri);
        $packageId = isset($parts[2]) ? intval($parts[2]) : 0;
        
        if ($packageId <= 0) {
            $_SESSION['error'] = 'حزمة غير صالحة';
            header('Location: /provider/dashboard');
            exit;
        }
        
        // Get package details
        $stmt = $this->db->prepare("SELECT * FROM leads_packages WHERE id = ? AND is_active = TRUE");
        $stmt->execute([$packageId]);
        $package = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$package) {
            $_SESSION['error'] = 'الحزمة غير موجودة';
            header('Location: /provider/dashboard');
            exit;
        }
        
        // Get provider details
        $providerId = $_SESSION['provider_id'];
        $stmt = $this->db->prepare("SELECT * FROM service_providers WHERE id = ?");
        $stmt->execute([$providerId]);
        $provider = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Handle POST request (complete purchase)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate CSRF token
            if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
                $_SESSION['error'] = 'Invalid CSRF token';
                header('Location: /provider/dashboard');
                exit;
            }
            
            try {
                // Insert purchase record
                $stmt = $this->db->prepare("
                    INSERT INTO provider_purchases (
                        provider_id, 
                        package_id, 
                        package_name, 
                        leads_count, 
                        price_paid,
                        used_leads,
                        remaining_leads,
                        payment_status,
                        purchased_at
                    ) VALUES (?, ?, ?, ?, ?, 0, ?, 'completed', NOW())
                ");
                
                $success = $stmt->execute([
                    $providerId,
                    $package['id'],
                    $package['name_ar'],
                    $package['lead_count'],
                    $package['price'],
                    $package['lead_count'] // remaining = total initially
                ]);
                
                if ($success) {
                    $_SESSION['success'] = 'تم شراء الحزمة بنجاح! 🎉';
                    header('Location: /provider/dashboard');
                    exit;
                } else {
                    $_SESSION['error'] = 'فشل الشراء. يرجى المحاولة مرة أخرى';
                    header('Location: /provider/dashboard');
                    exit;
                }
                
            } catch (PDOException $e) {
                error_log("Purchase error: " . $e->getMessage());
                $_SESSION['error'] = 'حدث خطأ في الشراء';
                header('Location: /provider/dashboard');
                exit;
            }
        }
        
        // Show purchase confirmation page
        $pageData = [
            'provider' => $provider,
            'package' => $package
        ];
        
        require_once __DIR__ . '/../Views/provider/purchase.php';
    }
    
    /**
     * Provider Logout
     */
    public function logout()
    {
        // Clear remember cookie
        if (isset($_COOKIE['provider_remember'])) {
            setcookie('provider_remember', '', time() - 3600, '/', '', true, true);
        }
        
        // Clear provider session data
        unset($_SESSION['provider_id']);
        unset($_SESSION['provider_name']);
        unset($_SESSION['provider_email']);
        unset($_SESSION['provider_service_type']);
        
        // Set success message before destroying session
        $_SESSION['success'] = 'تم تسجيل الخروج بنجاح';
        
        header('Location: /');
        exit;
    }
    
    /**
     * Lead paketleri görüntüleme sayfası
     * Usta için mevcut lead paketlerini listeler (Stripe entegrasyonu)
     */
    public function browsePackages()
    {
        // Usta girişi kontrolü
        if (!isset($_SESSION['provider_id'])) {
            header('Location: /');
            exit;
        }
        
        $providerId = $_SESSION['provider_id'];
        
        // Usta bilgilerini getir
        $stmt = $this->db->prepare("SELECT * FROM service_providers WHERE id = ?");
        $stmt->execute([$providerId]);
        $provider = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$provider) {
            header('Location: /');
            exit;
        }
        
        // Stripe yapılandırmasını yükle
        require_once __DIR__ . '/../config/stripe.php';
        
        // Ustanın hizmet türüne göre paketleri getir
        $packages = getLeadPackages($this->db, $provider['service_type'], true);
        
        // Sayfa verilerini hazırla
        $pageData = [
            'provider' => $provider,
            'packages' => $packages,
            'pageTitle' => 'شراء حزمة'
        ];
        
        // View'ı render et
        require __DIR__ . '/../Views/provider/browse_packages.php';
    }
    
    /**
     * Stripe Checkout oturumu oluşturma
     * Lead paketi satın alma için ödeme sayfası oluşturur
     */
    public function createCheckoutSession()
    {
        header('Content-Type: application/json');
        
        // Usta girişi kontrolü
        if (!isset($_SESSION['provider_id'])) {
            echo json_encode(['success' => false, 'error' => 'غير مصرح']);
            exit;
        }
        
        // CSRF token doğrulama
        if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
            echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
            exit;
        }
        
        $packageId = intval($_POST['package_id'] ?? 0);
        $providerId = $_SESSION['provider_id'];
        
        if ($packageId <= 0) {
            echo json_encode(['success' => false, 'error' => 'حزمة غير صالحة']);
            exit;
        }
        
        try {
            // Load Stripe config
            require_once __DIR__ . '/../config/stripe.php';
            
            // Create checkout session
            $session = createStripeCheckoutSession($packageId, $providerId, $this->db);
            
            echo json_encode([
                'success' => true,
                'session_id' => $session->id,
                'checkout_url' => $session->url
            ]);
        } catch (Exception $e) {
            error_log('Stripe checkout error: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Purchase Success Page
     */
    public function purchaseSuccess()
    {
        // Check if provider is logged in
        if (!isset($_SESSION['provider_id'])) {
            header('Location: /');
            exit;
        }
        
        $sessionId = $_GET['session_id'] ?? '';
        
        if (empty($sessionId)) {
            $_SESSION['error'] = 'جلسة غير صالحة';
            header('Location: /provider/dashboard');
            exit;
        }
        
        // Load Stripe config
        require_once __DIR__ . '/../config/stripe.php';
        initStripe();
        
        try {
            // Retrieve session from Stripe
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            
            // Get purchase details
            $stmt = $this->db->prepare("
                SELECT pp.*, lp.lead_count, lp.service_type
                FROM provider_purchases pp
                LEFT JOIN lead_packages lp ON pp.package_id = lp.id
                WHERE pp.stripe_session_id = ?
            ");
            $stmt->execute([$sessionId]);
            $purchase = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Eğer ödeme başarılı ve henüz pending ise, otomatik olarak completed yap
            // (Test modunda webhook çalışmadığı için)
            if ($purchase && $purchase['payment_status'] === 'pending' && $session->payment_status === 'paid') {
                $updateStmt = $this->db->prepare("
                    UPDATE provider_purchases 
                    SET payment_status = 'completed',
                        stripe_payment_intent = ?,
                        currency = ?
                    WHERE id = ?
                ");
                $updateStmt->execute([
                    $session->payment_intent,
                    strtoupper($session->currency ?? 'SAR'),
                    $purchase['id']
                ]);
                
                // Purchase'ı tekrar çek (güncellenmiş hali)
                $stmt->execute([$sessionId]);
                $purchase = $stmt->fetch(PDO::FETCH_ASSOC);
                
                error_log("Auto-completed purchase #{$purchase['id']} for provider #{$_SESSION['provider_id']}");
            }
            
            $pageData = [
                'session' => $session,
                'purchase' => $purchase,
                'pageTitle' => 'عملية شراء ناجحة'
            ];
            
            require __DIR__ . '/../Views/provider/purchase_success.php';
        } catch (Exception $e) {
            error_log('Purchase success error: ' . $e->getMessage());
            $_SESSION['error'] = 'حدث خطأ أثناء تحميل التفاصيل';
            header('Location: /provider/dashboard');
            exit;
        }
    }
    
    /**
     * Purchase Cancel Page
     */
    public function purchaseCancel()
    {
        // Check if provider is logged in
        if (!isset($_SESSION['provider_id'])) {
            header('Location: /');
            exit;
        }
        
        $pageData = [
            'pageTitle' => 'تم إلغاء الشراء'
        ];
        
        require __DIR__ . '/../Views/provider/purchase_cancel.php';
    }
    
    /**
     * Mark Lead as Viewed
     */
    public function markLeadViewed()
    {
        header('Content-Type: application/json');
        
        // Check if provider is logged in
        if (!isset($_SESSION['provider_id'])) {
            echo json_encode(['success' => false, 'error' => 'غير مصرح']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            exit;
        }
        
        $leadId = intval($_POST['lead_id'] ?? 0);
        $providerId = $_SESSION['provider_id'];
        
        if ($leadId <= 0) {
            echo json_encode(['success' => false, 'error' => 'Lead ID geçersiz']);
            exit;
        }
        
        try {
            // Check if this lead is delivered to this provider
            $stmt = $this->db->prepare("
                SELECT id, viewed_at, viewed_count 
                FROM provider_lead_deliveries 
                WHERE lead_id = ? AND provider_id = ?
            ");
            $stmt->execute([$leadId, $providerId]);
            $delivery = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$delivery) {
                echo json_encode(['success' => false, 'error' => 'Bu lead size ait değil']);
                exit;
            }
            
            // Update viewed_at (only first time) and increment viewed_count
            if (empty($delivery['viewed_at'])) {
                // İlk kez görüntüleniyor
                $stmt = $this->db->prepare("
                    UPDATE provider_lead_deliveries 
                    SET viewed_at = NOW(), viewed_count = 1
                    WHERE id = ?
                ");
                $stmt->execute([$delivery['id']]);
                
                error_log("✅ Lead #{$leadId} FIRST TIME viewed by provider #{$providerId}");
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Lead görüntülendi',
                    'first_view' => true
                ]);
            } else {
                // Daha önce görüntülenmiş, sadece count artır
                $stmt = $this->db->prepare("
                    UPDATE provider_lead_deliveries 
                    SET viewed_count = viewed_count + 1
                    WHERE id = ?
                ");
                $stmt->execute([$delivery['id']]);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Lead tekrar görüntülendi',
                    'first_view' => false
                ]);
            }
            
        } catch (PDOException $e) {
            error_log("Mark lead viewed error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası']);
        }
    }
    
    /**
     * Provider Leads Page
     * Ustanın aldığı lead'leri göster
     */
    public function leads()
    {
        // Usta girişi kontrolü
        if (!isset($_SESSION['provider_id'])) {
            header('Location: /');
            exit;
        }
        
        $providerId = $_SESSION['provider_id'];
        
        // Provider bilgilerini al
        $stmt = $this->db->prepare("SELECT * FROM service_providers WHERE id = ?");
        $stmt->execute([$providerId]);
        $provider = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$provider) {
            header('Location: /');
            exit;
        }
        
        // Paket istatistiklerini al
        $stmt = $this->db->prepare("
            SELECT 
                SUM(leads_count) as total_purchased,
                SUM(remaining_leads) as total_remaining,
                SUM(leads_count - remaining_leads) as total_delivered
            FROM provider_purchases
            WHERE provider_id = ? AND payment_status = 'completed'
        ");
        $stmt->execute([$providerId]);
        $packageStats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Teslim edilen lead'leri al (gizlenmiş olanları hariç tut)
        $stmt = $this->db->prepare("
            SELECT pld.*, l.phone, l.whatsapp_phone, l.service_type, l.city, l.description, 
                   l.budget_min, l.budget_max, l.service_time_type, l.scheduled_date,
                   l.created_at as lead_created_at
            FROM provider_lead_deliveries pld
            INNER JOIN leads l ON pld.lead_id = l.id
            WHERE pld.provider_id = ? 
            AND pld.deleted_at IS NULL
            AND (pld.hidden_by_provider IS NULL OR pld.hidden_by_provider = 0)
            ORDER BY pld.delivered_at DESC
        ");
        $stmt->execute([$providerId]);
        $deliveredLeads = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // View'ı render et
        $pageTitle = 'الطلبات المستلمة';
        $currentPage = 'leads';
        ob_start();
        ?>
        
        <div class="mb-6">
            <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">الطلبات المستلمة</h1>
                    <p class="text-gray-600 mt-1">جميع الطلبات التي حصلت عليها من الحزم المشتراة</p>
                </div>
                <a href="/provider/browse-packages" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    شراء حزمة جديدة
                </a>
            </div>
            
            <!-- İstatistik Kartları -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Toplam Satın Alınan -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold mb-1"><?= number_format($packageStats['total_purchased'] ?? 0) ?></div>
                    <div class="text-blue-100 text-sm font-medium">إجمالي المشتريات</div>
                </div>
                
                <!-- Teslim Edilen -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold mb-1"><?= number_format($packageStats['total_delivered'] ?? 0) ?></div>
                    <div class="text-green-100 text-sm font-medium">الطلبات المستلمة</div>
                </div>
                
                <!-- Bekleyen -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold mb-1"><?= number_format($packageStats['total_remaining'] ?? 0) ?></div>
                    <div class="text-orange-100 text-sm font-medium">طلبات قادمة</div>
                </div>
            </div>
        </div>
        
        <?php if (empty($deliveredLeads)): ?>
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">لا توجد طلبات بعد</h3>
                <p class="text-gray-600 mb-6">قم بشراء حزمة للحصول على طلبات جديدة</p>
                <a href="/provider/browse-packages" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-colors">
                    شراء حزمة
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            </div>
        <?php else: ?>
            <style>
                @media (min-width: 768px) {
                    .desktop-table-view { display: block !important; }
                    .mobile-card-view { display: none !important; }
                }
                @media (max-width: 767px) {
                    .desktop-table-view { display: none !important; }
                    .mobile-card-view { display: block !important; }
                }
            </style>
            
            <!-- Desktop: Tablo Görünümü (md ve üzeri) -->
            <div class="desktop-table-view bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">رقم الطلب</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">المدينة</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">تاريخ التسليم</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الحالة</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php 
                        $cities = getCities();
                        foreach ($deliveredLeads as $index => $lead): 
                            $cityNameAr = $cities[strtolower($lead['city'])]['ar'] ?? $lead['city'];
                        ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-extrabold rounded-lg shadow-md" style="background-color: #f97316 !important; color: #ffffff !important;">
                                        🆔 #<?= str_pad($lead['lead_id'], 6, '0', STR_PAD_LEFT) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        <span class="text-sm text-gray-700"><?= htmlspecialchars($cityNameAr) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-700"><?= date('d/m/Y H:i', strtotime($lead['delivered_at'])) ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        تم التسليم
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="openLeadDetail(<?= $index ?>)" class="inline-flex items-center gap-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        التفاصيل
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Mobile: Card Görünümü (md altı) -->
            <div class="mobile-card-view space-y-3">
                <?php foreach ($deliveredLeads as $index => $lead): 
                    $cityNameAr = $cities[strtolower($lead['city'])]['ar'] ?? $lead['city'];
                ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                        <!-- Lead ID Badge -->
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-extrabold rounded-lg shadow-md" style="background-color: #f97316 !important; color: #ffffff !important;">
                                🆔 #<?= str_pad($lead['lead_id'], 6, '0', STR_PAD_LEFT) ?>
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                تم التسليم
                            </span>
                        </div>
                        
                        <!-- City & Date -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center gap-2 text-sm text-gray-700">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <span><?= htmlspecialchars($cityNameAr) ?></span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span><?= date('d/m/Y H:i', strtotime($lead['delivered_at'])) ?></span>
                            </div>
                        </div>
                        
                        <!-- Detail Button - Full Width -->
                        <button onclick="openLeadDetail(<?= $index ?>)" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-base font-bold rounded-lg transition-colors shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span>عرض التفاصيل</span>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Modal için lead verileri -->
            <script>
                const leadsData = <?= json_encode($deliveredLeads, JSON_UNESCAPED_UNICODE) ?>;
                const citiesData = <?= json_encode($cities, JSON_UNESCAPED_UNICODE) ?>;
                let currentLeadId = null;
                
                function openLeadDetail(index) {
                    const lead = leadsData[index];
                    const modal = document.getElementById('leadDetailModal');
                    currentLeadId = lead.lead_id; // Lead ID'yi sakla
                    
                    // Lead'i görüntülendi olarak işaretle (ilk tıklamada)
                    markLeadAsViewed(lead.lead_id);
                    
                    // Lead ID'yi modal header'da göster
                    const leadIdPadded = String(lead.lead_id).padStart(6, '0');
                    document.getElementById('modalLeadId').innerHTML = `🆔 #${leadIdPadded}`;
                    
                    // Modal içeriğini doldur
                    const phoneElement = document.getElementById('modalPhone');
                    const phoneLinkElement = document.getElementById('modalPhoneLink');
                    phoneElement.textContent = lead.phone;
                    phoneLinkElement.href = `tel:${lead.phone}`;
                    
                    // Şehir ismini Arapça göster
                    const cityKey = lead.city.toLowerCase();
                    const cityNameAr = citiesData[cityKey]?.ar || lead.city;
                    document.getElementById('modalCity').textContent = cityNameAr;
                    document.getElementById('modalDescription').textContent = lead.description || 'لا توجد تفاصيل';
                    document.getElementById('modalDeliveredAt').textContent = new Date(lead.delivered_at).toLocaleString('ar-SA', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    
                    // WhatsApp - Modern Button
                    const whatsappContainer = document.getElementById('modalWhatsapp');
                    if (lead.whatsapp_phone) {
                        const cleanPhone = lead.whatsapp_phone.replace(/[^0-9]/g, '');
                        whatsappContainer.innerHTML = `
                            <a href="https://wa.me/${cleanPhone}" target="_blank" class="bg-white border-2 border-green-500 rounded-lg p-3 flex items-center justify-between hover:bg-green-50 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                        </svg>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-600">واتساب</p>
                                        <p class="text-sm font-bold text-green-600 group-hover:text-green-700" dir="ltr">${lead.whatsapp_phone}</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-green-500 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        `;
                    } else {
                        whatsappContainer.innerHTML = '';
                    }
                    
                    // Modal'ı göster
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
                
                function closeLeadDetail() {
                    const modal = document.getElementById('leadDetailModal');
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
                
                // ESC tuşu ile kapat
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeLeadDetail();
                    }
                });
                
                // Lead'i görüntülendi olarak işaretle
                async function markLeadAsViewed(leadId) {
                    if (!leadId) {
                        console.error('markLeadAsViewed: Lead ID bulunamadı');
                        return;
                    }
                    
                    try {
                        const formData = new FormData();
                        formData.append('lead_id', leadId);
                        formData.append('csrf_token', getCsrfToken());
                        
                        const response = await fetch('/provider/mark-lead-viewed', {
                            method: 'POST',
                            body: formData
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            console.log('✅ Lead #' + leadId + ' görüntülendi olarak işaretlendi');
                            
                            // Badge'i güncelle (sadece ilk görüntülemede)
                            if (result.first_view) {
                                console.log('🔔 İlk görüntüleme - badge güncellenecek');
                                
                                // Notification badge'i güncelle
                                const badge = document.getElementById('unviewed-leads-badge');
                                if (badge) {
                                    const currentCount = parseInt(badge.textContent) || 0;
                                    const newCount = Math.max(0, currentCount - 1);
                                    
                                    if (newCount > 0) {
                                        badge.textContent = newCount;
                                    } else {
                                        // Badge'i kaldır
                                        badge.remove();
                                    }
                                }
                            }
                        } else {
                            console.error('Lead görüntüleme işaretlenemedi:', result.error);
                        }
                    } catch (error) {
                        console.error('markLeadAsViewed error:', error);
                    }
                }
                
                // Lead'i sil (gizle - soft delete)
                async function deleteLead() {
                    if (!currentLeadId) {
                        alert('خطأ: معرف الطلب غير صحيح');
                        return;
                    }
                    
                    console.log('Delete lead - currentLeadId:', currentLeadId);
                    console.log('CSRF token:', getCsrfToken());
                    
                    if (!confirm('هل تريد حذف هذا الطلب من قائمتك؟\n\nملاحظة: سيتم نقله إلى سلة المهملات')) {
                        return;
                    }
                    
                    const deleteBtn = document.getElementById('deleteLeadBtn');
                    const originalHTML = deleteBtn.innerHTML;
                    deleteBtn.disabled = true;
                    deleteBtn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                    
                    try {
                        const formData = new FormData();
                        formData.append('lead_id', currentLeadId);
                        formData.append('csrf_token', getCsrfToken());
                        
                        const response = await fetch('/provider/hide-lead', {
                            method: 'POST',
                            body: formData
                        });
                        
                        const result = await response.json();
                        console.log('Hide lead response:', result);
                        
                        if (result.success) {
                            showToast('success', result.message || 'تم حذف الطلب بنجاح');
                            closeLeadDetail();
                            setTimeout(() => { window.location.reload(); }, 1500);
                        } else {
                            console.error('Hide lead failed:', result);
                            showToast('error', result.message || 'حدث خطأ أثناء حذف الطلب');
                            deleteBtn.disabled = false;
                            deleteBtn.innerHTML = originalHTML;
                        }
                    } catch (error) {
                        console.error('Delete error:', error);
                        showToast('error', 'حدث خطأ أثناء حذف الطلب: ' + error.message);
                        deleteBtn.disabled = false;
                        deleteBtn.innerHTML = originalHTML;
                    }
                }
                
                // Toast Bildirimi
                function showToast(type, message) {
                    const toast = document.createElement('div');
                    toast.className = `fixed top-4 left-1/2 transform -translate-x-1/2 z-50 px-6 py-4 rounded-xl shadow-2xl border-2 transition-all ${
                        type === 'success' ? 'bg-green-50 border-green-500 text-green-800' : 'bg-red-50 border-red-500 text-red-800'
                    }`;
                    toast.innerHTML = `<div class="flex items-center gap-3">
                        ${type === 'success' 
                            ? '<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                            : '<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                        }
                        <span class="font-semibold">${message}</span>
                    </div>`;
                    document.body.appendChild(toast);
                    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 5000);
                }
                
                // CSRF Token Helper
                function getCsrfToken() {
                    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                    if (tokenMeta) {
                        return tokenMeta.getAttribute('content');
                    }
                    
                    // Fallback: PHP session'dan alalım
                    return '<?= $_SESSION['csrf_token'] ?? '' ?>';
                }
            </script>
            
            <!-- Lead Detay Modal - Modern & Mobile Optimized -->
            <div id="leadDetailModal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 p-3 sm:p-4 overflow-y-auto" onclick="if(event.target === this) closeLeadDetail()">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg my-auto mx-auto relative">
                    <!-- Header - Solid Blue -->
                    <div class="bg-blue-600 text-white p-4 sm:p-5 rounded-t-2xl">
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-lg sm:text-xl font-bold">تفاصيل الطلب</h2>
                            <button onclick="closeLeadDetail()" class="text-white hover:bg-blue-700 p-2 rounded-lg transition-colors flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <div id="modalLeadId" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-extrabold rounded-lg shadow-md" style="background-color: #ffffff !important; color: #f97316 !important;">
                            🆔 #000000
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-4 sm:p-5 space-y-4">
                        <!-- معلومات الاتصال -->
                        <div class="border-2 border-blue-200 rounded-xl p-3 sm:p-4 bg-blue-50">
                            <h3 class="text-base sm:text-lg font-bold text-blue-900 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                معلومات الاتصال
                            </h3>
                            <div class="space-y-3">
                                <div class="bg-white rounded-lg p-3 border border-blue-200">
                                    <p class="text-xs text-blue-600 mb-1">رقم الهاتف</p>
                                    <a href="#" id="modalPhoneLink" class="text-base sm:text-lg font-bold text-blue-900 hover:text-blue-600 transition-colors" dir="ltr">
                                        <span id="modalPhone"></span>
                                    </a>
                                </div>
                                
                                <div class="bg-white rounded-lg p-3 border border-blue-200">
                                    <p class="text-xs text-blue-600 mb-1">المدينة</p>
                                    <p class="text-base sm:text-lg font-bold text-blue-900" id="modalCity"></p>
                                </div>
                                
                                <div id="modalWhatsapp"></div>
                            </div>
                        </div>
                        
                        <!-- تفاصيل الطلب -->
                        <div class="border-2 border-gray-200 rounded-xl p-3 sm:p-4 bg-gray-50">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                تفاصيل الطلب
                            </h3>
                            <p class="text-sm sm:text-base text-gray-700 leading-relaxed" id="modalDescription"></p>
                        </div>
                        
                        <!-- تاريخ التسليم -->
                        <div class="border-2 border-green-200 rounded-xl p-3 sm:p-4 bg-green-50">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-xs sm:text-sm text-green-700 font-semibold">تاريخ التسليم</p>
                            </div>
                            <p class="text-sm sm:text-base font-bold text-green-900" id="modalDeliveredAt"></p>
                        </div>
                    </div>
                    
                    <!-- Footer - Modern Buttons -->
                    <div class="p-4 sm:p-5 bg-gray-50 border-t-2 border-gray-200 sticky bottom-0">
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                            <button 
                                id="deleteLeadBtn"
                                onclick="deleteLead()" 
                                class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-colors flex items-center justify-center gap-2 order-2 sm:order-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                حذف الطلب
                            </button>
                            <button onclick="closeLeadDetail()" class="flex-1 px-4 sm:px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl transition-colors order-1 sm:order-2">
                                إغلاق
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <?php
        $content = ob_get_clean();
        $unreadMessagesCount = $this->getUnreadMessagesCount($providerId);
        require __DIR__ . '/../Views/provider/layout.php';
    }
    
    /**
     * Provider Profile Page
     * Profil bilgilerini görüntüle
     */
    public function profile()
    {
        // Usta girişi kontrolü
        if (!isset($_SESSION['provider_id'])) {
            header('Location: /');
            exit;
        }
        
        $providerId = $_SESSION['provider_id'];
        
        // Provider bilgilerini al
        $stmt = $this->db->prepare("SELECT * FROM service_providers WHERE id = ?");
        $stmt->execute([$providerId]);
        $provider = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$provider) {
            header('Location: /');
            exit;
        }
        
        // Tüm satın alma geçmişini al (completed payments)
        $stmt = $this->db->prepare("
            SELECT 
                pp.id,
                pp.package_name,
                pp.leads_count,
                pp.price_paid,
                pp.currency,
                pp.payment_status,
                pp.purchased_at,
                pp.stripe_payment_intent,
                COUNT(DISTINCT pld.lead_id) as delivered_count,
                (pp.leads_count - COUNT(DISTINCT pld.lead_id)) as remaining_leads
            FROM provider_purchases pp
            LEFT JOIN provider_lead_deliveries pld ON pp.id = pld.purchase_id
            WHERE pp.provider_id = ?
            AND pp.payment_status = 'completed'
            GROUP BY pp.id
            ORDER BY pp.purchased_at DESC
        ");
        $stmt->execute([$providerId]);
        $purchaseHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // View'ı render et
        $pageTitle = 'الملف الشخصي';
        $currentPage = 'profile';
        ob_start();
        ?>
        
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">الملف الشخصي</h1>
            <p class="text-gray-600 mt-1">معلوماتك الشخصية</p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="max-w-2xl mx-auto">
                <div class="flex items-center gap-6 mb-8 pb-8 border-b border-gray-200">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($provider['name']) ?></h2>
                        <p class="text-gray-600"><?= htmlspecialchars($provider['email']) ?></p>
                        <span class="inline-block mt-2 px-3 py-1 bg-green-100 text-green-700 text-sm font-semibold rounded-full">
                            <?= $provider['status'] === 'active' ? 'نشط' : 'معلق' ?>
                        </span>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">رقم الهاتف</label>
                        <p class="text-gray-900"><?= htmlspecialchars($provider['phone']) ?></p>
                    </div>
                    
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">المدينة</label>
                        <p class="text-gray-900"><?= htmlspecialchars($provider['city']) ?></p>
                    </div>
                    
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">نوع الخدمة</label>
                        <p class="text-gray-900"><?= htmlspecialchars($provider['service_type']) ?></p>
                    </div>
                    
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">تاريخ التسجيل</label>
                        <p class="text-gray-900"><?= date('d/m/Y', strtotime($provider['created_at'])) ?></p>
                    </div>
                </div>
                
                <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <p class="text-sm text-blue-800">
                        <strong>ملاحظة:</strong> لتحديث معلومات الملف الشخصي، يرجى الاتصال بالدعم.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- سجل المشتريات - Purchase History -->
        <div class="mt-8 bg-white rounded-2xl shadow-lg p-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    سجل المشتريات
                </h2>
                <p class="text-gray-600 mt-1">جميع الحزم التي قمت بشرائها</p>
            </div>
            
            <?php if (empty($purchaseHistory)): ?>
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-lg font-medium">لا توجد مشتريات بعد</p>
                    <p class="text-gray-400 text-sm mt-2">لم تقم بشراء أي حزمة حتى الآن</p>
                    <a href="/provider/browse-packages" class="inline-flex items-center gap-2 mt-4 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        تصفح الحزم
                    </a>
                </div>
            <?php else: ?>
                <!-- Purchase History Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="text-right py-4 px-4 text-sm font-bold text-gray-700">اسم الحزمة</th>
                                <th class="text-center py-4 px-4 text-sm font-bold text-gray-700">العدد</th>
                                <th class="text-center py-4 px-4 text-sm font-bold text-gray-700">المبلغ</th>
                                <th class="text-center py-4 px-4 text-sm font-bold text-gray-700">الحالة</th>
                                <th class="text-center py-4 px-4 text-sm font-bold text-gray-700">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php 
                            $totalSpent = 0;
                            $totalLeadsPurchased = 0;
                            $totalLeadsDelivered = 0;
                            
                            foreach ($purchaseHistory as $purchase): 
                                $isCompleted = $purchase['remaining_leads'] == 0;
                                $totalSpent += $purchase['price_paid'];
                                $totalLeadsPurchased += $purchase['leads_count'];
                                $totalLeadsDelivered += $purchase['delivered_count'];
                            ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <!-- Package Name -->
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 <?= $isCompleted ? 'bg-gray-200' : 'bg-green-100' ?> rounded-lg flex items-center justify-center flex-shrink-0">
                                                <span class="text-lg"><?= $isCompleted ? '✅' : '📦' ?></span>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900"><?= htmlspecialchars($purchase['package_name']) ?></p>
                                                <p class="text-xs text-gray-500">ID: #<?= str_pad($purchase['id'], 6, '0', STR_PAD_LEFT) ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Leads Count -->
                                    <td class="py-4 px-4 text-center">
                                        <div>
                                            <p class="font-bold text-gray-900"><?= $purchase['leads_count'] ?> طلب</p>
                                            <div class="flex items-center justify-center gap-2 mt-1">
                                                <span class="text-xs text-green-600 font-semibold">
                                                    <?= $purchase['delivered_count'] ?> تم
                                                </span>
                                                <?php if ($purchase['remaining_leads'] > 0): ?>
                                                    <span class="text-xs text-orange-600 font-semibold">
                                                        | <?= $purchase['remaining_leads'] ?> متبقي
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Amount -->
                                    <td class="py-4 px-4 text-center">
                                        <p class="font-bold text-gray-900">
                                            <?= number_format($purchase['price_paid'], 2) ?> 
                                            <span class="text-sm text-gray-600"><?= strtoupper($purchase['currency']) ?></span>
                                        </p>
                                    </td>
                                    
                                    <!-- Status -->
                                    <td class="py-4 px-4 text-center">
                                        <?php if ($isCompleted): ?>
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                مكتملة
                                            </span>
                                        <?php elseif ($purchase['remaining_leads'] > 0): ?>
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                نشطة
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <!-- Date -->
                                    <td class="py-4 px-4 text-center">
                                        <p class="text-sm text-gray-900"><?= date('d/m/Y', strtotime($purchase['purchased_at'])) ?></p>
                                        <p class="text-xs text-gray-500"><?= date('H:i', strtotime($purchase['purchased_at'])) ?></p>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Summary Stats -->
                <div class="mt-6 pt-6 border-t-2 border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 text-center">
                            <p class="text-sm text-indigo-600 font-semibold mb-1">إجمالي الإنفاق</p>
                            <p class="text-2xl font-bold text-indigo-900"><?= number_format($totalSpent, 2) ?> SAR</p>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
                            <p class="text-sm text-blue-600 font-semibold mb-1">إجمالي الطلبات المشتراة</p>
                            <p class="text-2xl font-bold text-blue-900"><?= $totalLeadsPurchased ?></p>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
                            <p class="text-sm text-green-600 font-semibold mb-1">الطلبات المستلمة</p>
                            <p class="text-2xl font-bold text-green-900"><?= $totalLeadsDelivered ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <?php
        $content = ob_get_clean();
        $unreadMessagesCount = $this->getUnreadMessagesCount($providerId);
        require __DIR__ . '/../Views/provider/layout.php';
    }
    
    /**
     * Provider Settings Page
     * إعدادات الحساب
     */
    public function settings()
    {
        // Usta girişi kontrolü
        if (!isset($_SESSION['provider_id'])) {
            header('Location: /');
            exit;
        }
        
        $providerId = $_SESSION['provider_id'];
        
        // Provider bilgilerini al
        $stmt = $this->db->prepare("SELECT * FROM service_providers WHERE id = ?");
        $stmt->execute([$providerId]);
        $provider = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$provider) {
            header('Location: /');
            exit;
        }
        
        // View'ı render et
        $pageTitle = 'الإعدادات';
        $currentPage = 'settings';
        ob_start();
        ?>
        
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">⚙️ الإعدادات</h1>
            <p class="text-gray-600 mt-1">إدارة حسابك وتفضيلاتك وإعدادات الأمان</p>
        </div>
        
        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="mb-6 bg-green-50 border-2 border-green-200 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-green-800 font-medium"><?= htmlspecialchars($_SESSION['success']) ?></p>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-6 bg-red-50 border-2 border-red-200 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-red-800 font-medium"><?= htmlspecialchars($_SESSION['error']) ?></p>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-4 sticky top-4">
                    <nav class="space-y-1">
                        <button onclick="showSection('account')" class="settings-nav-btn active w-full text-right px-4 py-3 rounded-xl font-semibold transition-all flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>معلومات الحساب</span>
                        </button>
                        
                        <button onclick="showSection('notifications')" class="settings-nav-btn w-full text-right px-4 py-3 rounded-xl font-semibold transition-all flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span>الإشعارات</span>
                        </button>
                        
                        <button onclick="showSection('security')" class="settings-nav-btn w-full text-right px-4 py-3 rounded-xl font-semibold transition-all flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>الأمان</span>
                        </button>
                        
                        <button onclick="showSection('preferences')" class="settings-nav-btn w-full text-right px-4 py-3 rounded-xl font-semibold transition-all flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>التفضيلات</span>
                        </button>
                        
                        <button onclick="showSection('danger')" class="settings-nav-btn w-full text-right px-4 py-3 rounded-xl font-semibold transition-all flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>منطقة الخطر</span>
                        </button>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Account Information Section -->
                <div id="section-account" class="settings-section">
                    <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-6 pb-6 border-b-2 border-gray-100">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">معلومات الحساب</h2>
                                <p class="text-sm text-gray-600">معلوماتك الشخصية والمهنية</p>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <!-- Name -->
                            <div class="p-5 bg-gray-50 rounded-xl border-2 border-gray-200 hover:border-blue-300 transition-colors">
                                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    الاسم الكامل
                                </label>
                                <p class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($provider['name']) ?></p>
                            </div>
                            
                            <!-- Email -->
                            <div class="p-5 bg-gray-50 rounded-xl border-2 border-gray-200 hover:border-blue-300 transition-colors">
                                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    البريد الإلكتروني
                                </label>
                                <p class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($provider['email']) ?></p>
                            </div>
                            
                            <!-- Phone -->
                            <div class="p-5 bg-gray-50 rounded-xl border-2 border-gray-200 hover:border-blue-300 transition-colors">
                                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    رقم الهاتف
                                </label>
                                <p class="text-lg font-semibold text-gray-900" dir="ltr"><?= htmlspecialchars($provider['phone']) ?></p>
                            </div>
                            
                            <!-- City -->
                            <div class="p-5 bg-gray-50 rounded-xl border-2 border-gray-200 hover:border-blue-300 transition-colors">
                                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    المدينة
                                </label>
                                <p class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($provider['city']) ?></p>
                            </div>
                            
                            <!-- Service Type -->
                            <div class="p-5 bg-gray-50 rounded-xl border-2 border-gray-200 hover:border-blue-300 transition-colors">
                                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    نوع الخدمة
                                </label>
                                <p class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($provider['service_type']) ?></p>
                            </div>
                            
                            <!-- Status Badge -->
                            <div class="p-5 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border-2 border-green-200">
                                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    حالة الحساب
                                </label>
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white font-bold rounded-lg">
                                    <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                    <?= $provider['status'] === 'active' ? 'نشط' : 'معلق' ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="mt-8 p-5 bg-blue-50 border-2 border-blue-200 rounded-xl">
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="font-bold text-blue-900 mb-1">ملاحظة هامة</p>
                                    <p class="text-sm text-blue-800">لتحديث معلومات حسابك، يرجى التواصل مع فريق الدعم عبر الواتساب أو البريد الإلكتروني.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Notifications Section -->
                <div id="section-notifications" class="settings-section hidden">
                    <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-6 pb-6 border-b-2 border-gray-100">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">الإشعارات</h2>
                                <p class="text-sm text-gray-600">إدارة تفضيلات الإشعارات</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- New Leads Notification -->
                            <div class="p-5 bg-gray-50 rounded-xl border-2 border-gray-200 hover:border-purple-300 transition-all">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p class="font-bold text-gray-900">إشعارات الطلبات الجديدة</p>
                                        </div>
                                        <p class="text-sm text-gray-600">احصل على إشعار فوري عند وصول طلب جديد إليك</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                        <input type="checkbox" class="sr-only peer" checked>
                                        <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-600"></div>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Email Notifications -->
                            <div class="p-5 bg-gray-50 rounded-xl border-2 border-gray-200 hover:border-purple-300 transition-all">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            <p class="font-bold text-gray-900">إشعارات البريد الإلكتروني</p>
                                        </div>
                                        <p class="text-sm text-gray-600">تلقي التحديثات والإشعارات عبر البريد الإلكتروني</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                        <input type="checkbox" class="sr-only peer" checked>
                                        <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-600"></div>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- SMS Notifications -->
                            <div class="p-5 bg-gray-50 rounded-xl border-2 border-gray-200 hover:border-purple-300 transition-all">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                            </svg>
                                            <p class="font-bold text-gray-900">إشعارات الرسائل النصية</p>
                                        </div>
                                        <p class="text-sm text-gray-600">تلقي تنبيهات عبر الرسائل النصية SMS</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                        <input type="checkbox" class="sr-only peer">
                                        <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-600"></div>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Marketing Notifications -->
                            <div class="p-5 bg-gray-50 rounded-xl border-2 border-gray-200 hover:border-purple-300 transition-all">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                            </svg>
                                            <p class="font-bold text-gray-900">العروض والتحديثات</p>
                                        </div>
                                        <p class="text-sm text-gray-600">تلقي أخبار العروض الخاصة والتحديثات الجديدة</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                        <input type="checkbox" class="sr-only peer" checked>
                                        <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button class="w-full sm:w-auto px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl">
                                حفظ التغييرات
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Security Section -->
                <div id="section-security" class="settings-section hidden">
                    <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-6 pb-6 border-b-2 border-gray-100">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">الأمان</h2>
                                <p class="text-sm text-gray-600">إدارة كلمة المرور والأمان</p>
                            </div>
                        </div>
                        
                        <!-- Change Password -->
                        <div class="p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border-2 border-green-200 mb-6">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">تغيير كلمة المرور</h3>
                                    <p class="text-sm text-gray-700 mb-4">قم بتحديث كلمة المرور الخاصة بك بانتظام للحفاظ على أمان حسابك</p>
                                    <button onclick="alert('سيتم إضافة هذه الميزة قريباً')" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-all shadow-md hover:shadow-lg">
                                        تغيير كلمة المرور
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Security Tips -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                نصائح الأمان
                            </h3>
                            
                            <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm text-blue-900">استخدم كلمة مرور قوية تحتوي على أحرف كبيرة وصغيرة وأرقام ورموز</p>
                                </div>
                            </div>
                            
                            <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm text-blue-900">لا تشارك كلمة المرور الخاصة بك مع أي شخص</p>
                                </div>
                            </div>
                            
                            <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm text-blue-900">قم بتسجيل الخروج من حسابك عند استخدام أجهزة مشتركة</p>
                                </div>
                            </div>
                            
                            <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm text-blue-900">راجع نشاط حسابك بانتظام للتأكد من عدم وجود نشاط غير مصرح به</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Preferences Section -->
                <div id="section-preferences" class="settings-section hidden">
                    <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-6 pb-6 border-b-2 border-gray-100">
                            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">التفضيلات</h2>
                                <p class="text-sm text-gray-600">تخصيص تجربتك</p>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <!-- Language -->
                            <div class="p-5 bg-gray-50 rounded-xl border-2 border-gray-200">
                                <label class="block text-sm font-bold text-gray-700 mb-3">اللغة</label>
                                <select class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl font-semibold text-gray-900 focus:outline-none focus:ring-4 focus:ring-indigo-300 focus:border-indigo-500 transition-all">
                                    <option value="ar" selected>العربية</option>
                                    <option value="en">English</option>
                                </select>
                            </div>
                            
                            <!-- Theme -->
                            <div class="p-5 bg-gray-50 rounded-xl border-2 border-gray-200">
                                <label class="block text-sm font-bold text-gray-700 mb-3">المظهر</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <button class="p-4 bg-white border-2 border-indigo-500 rounded-xl font-semibold text-gray-900 hover:bg-indigo-50 transition-all">
                                        <svg class="w-6 h-6 mx-auto mb-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        فاتح
                                    </button>
                                    <button class="p-4 bg-white border-2 border-gray-300 rounded-xl font-semibold text-gray-900 hover:bg-gray-50 transition-all">
                                        <svg class="w-6 h-6 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                        </svg>
                                        داكن
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Date Format -->
                            <div class="p-5 bg-gray-50 rounded-xl border-2 border-gray-200">
                                <label class="block text-sm font-bold text-gray-700 mb-3">تنسيق التاريخ</label>
                                <select class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl font-semibold text-gray-900 focus:outline-none focus:ring-4 focus:ring-indigo-300 focus:border-indigo-500 transition-all">
                                    <option value="dd/mm/yyyy" selected>DD/MM/YYYY</option>
                                    <option value="mm/dd/yyyy">MM/DD/YYYY</option>
                                    <option value="yyyy-mm-dd">YYYY-MM-DD</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button class="w-full sm:w-auto px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl">
                                حفظ التفضيلات
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Danger Zone Section -->
                <div id="section-danger" class="settings-section hidden">
                    <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 border-2 border-red-200">
                        <div class="flex items-center gap-3 mb-6 pb-6 border-b-2 border-red-100">
                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-red-600">منطقة الخطر</h2>
                                <p class="text-sm text-red-700">إجراءات لا يمكن التراجع عنها</p>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <!-- Deactivate Account -->
                            <div class="p-6 bg-orange-50 rounded-xl border-2 border-orange-200">
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 mb-2">تعطيل الحساب مؤقتاً</h3>
                                        <p class="text-sm text-gray-700 mb-4">يمكنك تعطيل حسابك مؤقتاً. سيتم إخفاء ملفك الشخصي ولن تتلقى أي طلبات جديدة. يمكنك إعادة تفعيل حسابك في أي وقت.</p>
                                        <button onclick="alert('سيتم إضافة هذه الميزة قريباً')" class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl transition-all">
                                            تعطيل الحساب
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Delete Account -->
                            <div class="p-6 bg-red-50 rounded-xl border-2 border-red-300">
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-red-600 mb-2">حذف الحساب نهائياً</h3>
                                        <div class="mb-4 space-y-2">
                                            <p class="text-sm text-red-900 font-semibold">⚠️ تحذير: هذا الإجراء لا يمكن التراجع عنه!</p>
                                            <p class="text-sm text-gray-700">عند حذف حسابك:</p>
                                            <ul class="text-sm text-gray-700 list-disc list-inside space-y-1 mr-4">
                                                <li>سيتم حذف جميع بياناتك الشخصية</li>
                                                <li>سيتم إلغاء جميع الحزم النشطة</li>
                                                <li>لن تتمكن من الوصول إلى الطلبات السابقة</li>
                                                <li>لن يمكنك استعادة حسابك</li>
                                            </ul>
                                        </div>
                                        <button onclick="confirmDeleteAccount()" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-all shadow-md hover:shadow-lg">
                                            حذف الحساب نهائياً
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 p-5 bg-yellow-50 border-2 border-yellow-300 rounded-xl">
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div>
                                    <p class="font-bold text-yellow-900 mb-1">هل تواجه مشكلة؟</p>
                                    <p class="text-sm text-yellow-800">قبل حذف حسابك، يرجى التواصل مع فريق الدعم. قد نتمكن من مساعدتك في حل أي مشكلة تواجهها.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            // Section Navigation
            function showSection(sectionName) {
                // Hide all sections
                document.querySelectorAll('.settings-section').forEach(section => {
                    section.classList.add('hidden');
                });
                
                // Remove active class from all nav buttons
                document.querySelectorAll('.settings-nav-btn').forEach(btn => {
                    btn.classList.remove('active', 'bg-blue-600', 'text-white');
                    btn.classList.add('text-gray-700', 'hover:bg-gray-100');
                });
                
                // Show selected section
                document.getElementById('section-' + sectionName).classList.remove('hidden');
                
                // Add active class to clicked button
                event.target.closest('.settings-nav-btn').classList.add('active', 'bg-blue-600', 'text-white');
                event.target.closest('.settings-nav-btn').classList.remove('text-gray-700', 'hover:bg-gray-100');
            }
            
            // Delete Account Confirmation
            function confirmDeleteAccount() {
                if (confirm('⚠️ هل أنت متأكد من رغبتك في حذف حسابك نهائياً؟\n\nهذا الإجراء لا يمكن التراجع عنه وسيتم حذف جميع بياناتك.')) {
                    if (confirm('⚠️ تأكيد نهائي: هل أنت متأكد 100% من رغبتك في حذف حسابك؟')) {
                        alert('سيتم إضافة هذه الميزة قريباً. يرجى التواصل مع الدعم لحذف حسابك.');
                    }
                }
            }
            
            // Initialize - Show account section by default
            document.addEventListener('DOMContentLoaded', function() {
                // Set initial active state
                const firstBtn = document.querySelector('.settings-nav-btn');
                if (firstBtn) {
                    firstBtn.classList.add('bg-blue-600', 'text-white');
                    firstBtn.classList.remove('text-gray-700', 'hover:bg-gray-100');
                }
            });
        </script>
        
        <style>
            .settings-nav-btn {
                @apply text-gray-700 hover:bg-gray-100;
            }
            .settings-nav-btn.active {
                @apply bg-blue-600 text-white;
            }
        </style>
        
        <?php
        $content = ob_get_clean();
        require __DIR__ . '/../Views/provider/layout.php';
    }
    
    /**
     * Provider Messages - Inbox
     * Admin'den gelen mesajları göster
     */
    public function messages()
    {
        // Usta girişi kontrolü
        if (!isset($_SESSION['provider_id'])) {
            header('Location: /');
            exit;
        }
        
        $providerId = $_SESSION['provider_id'];
        
        // Provider bilgilerini al
        $stmt = $this->db->prepare("SELECT * FROM service_providers WHERE id = ?");
        $stmt->execute([$providerId]);
        $provider = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$provider) {
            header('Location: /');
            exit;
        }
        
        // Tüm mesajları al (okunmamışlar önce)
        $stmt = $this->db->prepare("
            SELECT 
                pm.*,
                a.username as sender_name
            FROM provider_messages pm
            LEFT JOIN admins a ON pm.sender_id = a.id
            WHERE pm.provider_id = ? AND pm.deleted_at IS NULL
            ORDER BY pm.is_read ASC, pm.created_at DESC
        ");
        $stmt->execute([$providerId]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Okunmamış mesaj sayısı
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as unread_count 
            FROM provider_messages 
            WHERE provider_id = ? AND is_read = 0 AND deleted_at IS NULL
        ");
        $stmt->execute([$providerId]);
        $unreadCount = $stmt->fetch(PDO::FETCH_ASSOC)['unread_count'];
        
        // View'ı render et
        $pageTitle = 'الرسائل';
        $currentPage = 'messages';
        ob_start();
        require __DIR__ . '/../Views/provider/messages.php';
        $content = ob_get_clean();
        $unreadMessagesCount = $this->getUnreadMessagesCount($providerId);
        require __DIR__ . '/../Views/provider/layout.php';
    }
    
    /**
     * Mark Message as Read
     * AJAX endpoint
     */
    public function markMessageRead()
    {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['provider_id'])) {
            echo json_encode(['success' => false, 'message' => 'غير مصرح']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'طريقة غير صحيحة']);
            exit;
        }
        
        // CSRF token kontrolü
        if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
            echo json_encode(['success' => false, 'message' => 'خطأ في التحقق من الأمان']);
            exit;
        }
        
        $providerId = $_SESSION['provider_id'];
        $messageId = isset($_POST['message_id']) ? intval($_POST['message_id']) : 0;
        
        if ($messageId <= 0) {
            echo json_encode(['success' => false, 'message' => 'معرف الرسالة غير صحيح']);
            exit;
        }
        
        try {
            // Mesajın provider'a ait olduğunu kontrol et
            $stmt = $this->db->prepare("
                SELECT id FROM provider_messages 
                WHERE id = ? AND provider_id = ? AND deleted_at IS NULL
            ");
            $stmt->execute([$messageId, $providerId]);
            
            if (!$stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'الرسالة غير موجودة']);
                exit;
            }
            
            // Mesajı okundu olarak işaretle
            $stmt = $this->db->prepare("
                UPDATE provider_messages 
                SET is_read = 1, read_at = NOW()
                WHERE id = ? AND provider_id = ?
            ");
            $stmt->execute([$messageId, $providerId]);
            
            echo json_encode(['success' => true, 'message' => 'تم وضع علامة مقروء']);
            
        } catch (Exception $e) {
            error_log("Mark message read error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'حدث خطأ']);
        }
        
        exit;
    }
    
    /**
     * Delete Message
     * AJAX endpoint (Soft delete)
     */
    public function deleteMessage()
    {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['provider_id'])) {
            echo json_encode(['success' => false, 'message' => 'غير مصرح']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'طريقة غير صحيحة']);
            exit;
        }
        
        // CSRF token kontrolü
        if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
            echo json_encode(['success' => false, 'message' => 'خطأ في التحقق من الأمان']);
            exit;
        }
        
        $providerId = $_SESSION['provider_id'];
        $messageId = isset($_POST['message_id']) ? intval($_POST['message_id']) : 0;
        
        if ($messageId <= 0) {
            echo json_encode(['success' => false, 'message' => 'معرف الرسالة غير صحيح']);
            exit;
        }
        
        try {
            // Mesajın provider'a ait olduğunu kontrol et
            $stmt = $this->db->prepare("
                SELECT id FROM provider_messages 
                WHERE id = ? AND provider_id = ? AND deleted_at IS NULL
            ");
            $stmt->execute([$messageId, $providerId]);
            
            if (!$stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'الرسالة غير موجودة']);
                exit;
            }
            
            // Mesajı sil (soft delete)
            $stmt = $this->db->prepare("
                UPDATE provider_messages 
                SET deleted_at = NOW()
                WHERE id = ? AND provider_id = ?
            ");
            $stmt->execute([$messageId, $providerId]);
            
            echo json_encode(['success' => true, 'message' => 'تم حذف الرسالة']);
            
        } catch (Exception $e) {
            error_log("Delete message error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'حدث خطأ']);
        }
        
        exit;
    }
    
    /**
     * Lead İste - Usta lead talep eder
     * AJAX endpoint
     */
    public function requestLead()
    {
        header('Content-Type: application/json');
        
        // Provider login kontrolü
        if (!isset($_SESSION['provider_id'])) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'يجب تسجيل الدخول أولاً'
            ]);
            exit;
        }
        
        // Sadece POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
            exit;
        }
        
        // CSRF token kontrolü
        if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'Invalid CSRF token'
            ]);
            exit;
        }
        
        $providerId = $_SESSION['provider_id'];
        $purchaseId = intval($_POST['purchase_id'] ?? 0);
        
        if (!$purchaseId) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'معرف الحزمة مطلوب'
            ]);
            exit;
        }
        
        try {
            // Purchase var mı ve bu provider'a ait mi kontrol et
            $stmt = $this->db->prepare("
                SELECT * FROM provider_purchases 
                WHERE id = ? AND provider_id = ? AND payment_status = 'completed'
            ");
            $stmt->execute([$purchaseId, $providerId]);
            $purchase = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$purchase) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'الحزمة غير موجودة أو غير نشطة'
                ]);
                exit;
            }
            
            // Kalan lead var mı kontrol et
            if ($purchase['remaining_leads'] <= 0) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'لا توجد طلبات متبقية في هذه الحزمة'
                ]);
                exit;
            }
            
            // Bugün kaç istek yapıldı kontrol et (spam önleme)
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM lead_requests 
                WHERE provider_id = ? 
                AND purchase_id = ?
                AND DATE(requested_at) = CURDATE()
            ");
            $stmt->execute([$providerId, $purchaseId]);
            $todayRequests = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Kalan lead sayısından fazla istek yapılmasın
            if ($todayRequests >= $purchase['remaining_leads']) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'لقد طلبت جميع الطلبات المتبقية بالفعل. يرجى الانتظار حتى يرسل المسؤول الطلبات.'
                ]);
                exit;
            }
            
            // 60 dakika içinde aynı istek yapıldı mı kontrol et (rate limiting - adil dağılım)
            $stmt = $this->db->prepare("
                SELECT requested_at, 
                       TIMESTAMPDIFF(MINUTE, requested_at, NOW()) as minutes_passed
                FROM lead_requests 
                WHERE provider_id = ? 
                AND requested_at > DATE_SUB(NOW(), INTERVAL 60 MINUTE)
                ORDER BY requested_at DESC
                LIMIT 1
            ");
            $stmt->execute([$providerId]);
            $lastRequest = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($lastRequest) {
                $minutesLeft = 60 - $lastRequest['minutes_passed'];
                http_response_code(429);
                echo json_encode([
                    'success' => false,
                    'message' => "يرجى الانتظار {$minutesLeft} دقيقة قبل طلب عميل جديد. نريد توزيع الطلبات بشكل عادل على جميع الأساتذة."
                ]);
                exit;
            }
            
            // Lead isteği oluştur
            $stmt = $this->db->prepare("
                INSERT INTO lead_requests (provider_id, purchase_id, request_status, requested_at)
                VALUES (?, ?, 'pending', NOW())
            ");
            $stmt->execute([$providerId, $purchaseId]);
            $requestId = $this->db->lastInsertId();
            
            // Bekleyen isteklerin sırasını hesapla
            $stmt = $this->db->prepare("
                SELECT COUNT(*) + 1 as queue_position
                FROM lead_requests
                WHERE id < ? AND request_status = 'pending'
            ");
            $stmt->execute([$requestId]);
            $queuePosition = $stmt->fetch(PDO::FETCH_ASSOC)['queue_position'];
            
            // Log kaydı
            error_log("Lead request created: ID={$requestId}, Provider={$providerId}, Purchase={$purchaseId}, Queue={$queuePosition}");
            
            // Başarılı yanıt
            echo json_encode([
                'success' => true,
                'message' => 'تم إرسال الطلب بنجاح. ستتلقى إشعاراً عندما يرسل المسؤول الطلب.',
                'request_id' => $requestId,
                'queue_position' => $queuePosition,
                'remaining_requests' => $purchase['remaining_leads'] - $todayRequests - 1
            ]);
            
        } catch (PDOException $e) {
            error_log("Lead request error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'حدث خطأ أثناء معالجة الطلب'
            ]);
        }
    }
    
    /**
     * Bekleyen İstekler - Usta kendi bekleyen isteklerini görür
     */
    public function myRequests()
    {
        // Provider login kontrolü
        if (!isset($_SESSION['provider_id'])) {
            header('Location: /');
            exit;
        }
        
        $providerId = $_SESSION['provider_id'];
        
        // Bekleyen istekleri getir
        $stmt = $this->db->prepare("
            SELECT lr.*, pp.package_name, pp.leads_count, pp.remaining_leads,
                   sp.name as provider_name
            FROM lead_requests lr
            INNER JOIN provider_purchases pp ON lr.purchase_id = pp.id
            INNER JOIN service_providers sp ON lr.provider_id = sp.id
            WHERE lr.provider_id = ?
            ORDER BY lr.requested_at DESC
            LIMIT 50
        ");
        $stmt->execute([$providerId]);
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // View'a gönder
        $pageTitle = 'طلباتي';
        $currentPage = 'requests';
        $pageData = [
            'requests' => $requests
        ];
        
        ob_start();
        ?>
        
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">طلبات العملاء</h1>
            <p class="text-gray-600 mt-1">جميع طلباتك للحصول على عملاء جدد</p>
        </div>
        
        <?php if (empty($requests)): ?>
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">لا توجد طلبات</h3>
                <p class="text-gray-600">لم تقم بطلب أي عملاء بعد</p>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">رقم الطلب</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الحزمة</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">تاريخ الطلب</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($requests as $request): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-900">#<?= $request['id'] ?></td>
                                <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($request['package_name']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-700"><?= date('d/m/Y H:i', strtotime($request['requested_at'])) ?></td>
                                <td class="px-6 py-4">
                                    <?php if ($request['request_status'] === 'pending'): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                            قيد الانتظار
                                        </span>
                                    <?php elseif ($request['request_status'] === 'completed'): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            تم الإرسال
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            ملغي
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
        <?php
        $content = ob_get_clean();
        $unreadMessagesCount = $this->getUnreadMessagesCount($providerId);
        require __DIR__ . '/../Views/provider/layout.php';
    }
    
    /**
     * Hide Lead (Soft Delete for Provider UI)
     * Lead görünümden gizlenir ama veritabanından SİLİNMEZ!
     */
    public function hideLead()
    {
        header('Content-Type: application/json');
        
        // Usta girişi kontrolü
        if (!isset($_SESSION['provider_id'])) {
            echo json_encode(['success' => false, 'message' => 'غير مصرح']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'طريقة غير صحيحة']);
            exit;
        }
        
        // CSRF token kontrolü
        if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
            echo json_encode(['success' => false, 'message' => 'خطأ في التحقق من الأمان']);
            exit;
        }
        
        $providerId = $_SESSION['provider_id'];
        $leadId = isset($_POST['lead_id']) ? intval($_POST['lead_id']) : 0;
        
        if ($leadId <= 0) {
            echo json_encode(['success' => false, 'message' => 'معرف الطلب غير صحيح']);
            exit;
        }
        
        try {
            // Lead'in provider'a ait olduğunu kontrol et
            $stmt = $this->db->prepare("
                SELECT id FROM provider_lead_deliveries 
                WHERE provider_id = ? AND lead_id = ? AND deleted_at IS NULL
            ");
            $stmt->execute([$providerId, $leadId]);
            $delivery = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$delivery) {
                error_log("Hide lead: Lead not found - Provider: $providerId, Lead: $leadId");
                echo json_encode(['success' => false, 'message' => 'الطلب غير موجود']);
                exit;
            }
            
            // Lead'i gizle (SOFT DELETE - sadece UI için)
            $stmt = $this->db->prepare("
                UPDATE provider_lead_deliveries 
                SET hidden_by_provider = 1, hidden_at = NOW()
                WHERE provider_id = ? AND lead_id = ?
            ");
            $result = $stmt->execute([$providerId, $leadId]);
            
            if (!$result) {
                error_log("Hide lead: UPDATE failed - Provider: $providerId, Lead: $leadId");
                echo json_encode(['success' => false, 'message' => 'فشل تحديث الطلب']);
                exit;
            }
            
            error_log("Hide lead: SUCCESS - Provider: $providerId, Lead: $leadId");
            echo json_encode([
                'success' => true, 
                'message' => 'تم حذف الطلب بنجاح'
            ]);
            
        } catch (Exception $e) {
            error_log("Hide lead error: " . $e->getMessage() . " | Provider: $providerId, Lead: $leadId");
            echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء حذف الطلب: ' . $e->getMessage()]);
        }
        
        exit;
    }
    
    /**
     * Hidden Leads (Trash/Archive)
     * Gizlenmiş lead'leri göster - çöp kutusu sayfası
     */
    public function hiddenLeads()
    {
        // Usta girişi kontrolü
        if (!isset($_SESSION['provider_id'])) {
            header('Location: /');
            exit;
        }
        
        $providerId = $_SESSION['provider_id'];
        
        // Provider bilgilerini al
        $stmt = $this->db->prepare("SELECT * FROM service_providers WHERE id = ?");
        $stmt->execute([$providerId]);
        $provider = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$provider) {
            header('Location: /');
            exit;
        }
        
        // Pagination parametreleri
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = 10; // Sayfa başına lead sayısı
        $offset = ($page - 1) * $perPage;
        
        // Toplam lead sayısını al
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total
            FROM provider_lead_deliveries pld
            WHERE pld.provider_id = ? 
            AND pld.deleted_at IS NULL
            AND pld.hidden_by_provider = 1
        ");
        $stmt->execute([$providerId]);
        $totalLeads = $stmt->fetchColumn();
        $totalPages = ceil($totalLeads / $perPage);
        
        // Gizlenmiş lead'leri al (pagination ile)
        $stmt = $this->db->prepare("
            SELECT pld.*, l.phone, l.whatsapp_phone, l.service_type, l.city, l.description, 
                   l.budget_min, l.budget_max, l.service_time_type, l.scheduled_date,
                   l.created_at as lead_created_at
            FROM provider_lead_deliveries pld
            INNER JOIN leads l ON pld.lead_id = l.id
            WHERE pld.provider_id = ? 
            AND pld.deleted_at IS NULL
            AND pld.hidden_by_provider = 1
            ORDER BY pld.hidden_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$providerId, $perPage, $offset]);
        $hiddenLeads = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // View'ı render et
        $pageTitle = 'سلة المهملات';
        $currentPage = 'hidden-leads';
        ob_start();
        ?>
        
        <div class="mb-6">
            <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        سلة المهملات
                    </h1>
                    <p class="text-gray-600 mt-1">الطلبات التي قمت بحذفها من قائمتك</p>
                </div>
                <a href="/provider/leads" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    العودة إلى الطلبات
                </a>
            </div>
            
            <?php if (empty($hiddenLeads)): ?>
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-xl font-medium">سلة المهملات فارغة</p>
                    <p class="text-gray-400 text-sm mt-2">لا توجد طلبات محذوفة</p>
                </div>
            <?php else: ?>
                <!-- Toplam Sonuç Göstergesi -->
                <div class="mb-4 flex items-center justify-between flex-wrap gap-3">
                    <div class="text-sm text-gray-600">
                        الإجمالي: <span class="font-bold text-gray-900"><?= $totalLeads ?></span> طلب محذوف
                        <?php if ($totalPages > 1): ?>
                            | الصفحة <span class="font-bold text-blue-600"><?= $page ?></span> من <span class="font-bold"><?= $totalPages ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Hidden Leads List -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-200">
                        <p class="text-sm text-gray-600">
                            <strong>ملاحظة:</strong> هذه الطلبات مخفية من قائمتك فقط. لم يتم حذفها نهائياً.
                        </p>
                    </div>
                    
                    <div class="divide-y divide-gray-200">
                        <?php 
                        $serviceTypes = getServiceTypes();
                        $cities = getCities();
                        
                        foreach ($hiddenLeads as $lead): 
                            $serviceName = $serviceTypes[$lead['service_type']]['ar'] ?? $lead['service_type'];
                            $cityName = $cities[strtolower($lead['city'])]['ar'] ?? $lead['city'];
                        ?>
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 bg-gray-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-2">
                                            <h3 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($serviceName) ?></h3>
                                            <span class="text-sm text-gray-500">•</span>
                                            <span class="text-sm text-gray-600"><?= htmlspecialchars($cityName) ?></span>
                                        </div>
                                        
                                        <p class="text-gray-700 mb-3 line-clamp-2"><?= htmlspecialchars($lead['description']) ?></p>
                                        
                                        <div class="flex items-center gap-4 flex-wrap text-sm text-gray-600">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                                <?= htmlspecialchars($lead['phone']) ?>
                                            </span>
                                            
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                تم الحذف: <?= date('d/m/Y H:i', strtotime($lead['hidden_at'])) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="mt-6 flex items-center justify-center gap-2">
                        <?php if ($page > 1): ?>
                            <a href="?page=1" class="inline-flex items-center gap-1 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                                </svg>
                                الأولى
                            </a>
                            <a href="?page=<?= $page - 1 ?>" class="inline-flex items-center gap-1 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                السابقة
                            </a>
                        <?php endif; ?>

                        <!-- Sayfa Numaraları -->
                        <?php
                        $startPage = max(1, $page - 2);
                        $endPage = min($totalPages, $page + 2);
                        
                        for ($i = $startPage; $i <= $endPage; $i++):
                        ?>
                            <a href="?page=<?= $i ?>" class="inline-flex items-center justify-center w-10 h-10 <?= $i === $page ? 'bg-blue-600 text-white font-bold' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' ?> rounded-lg transition-colors">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?>" class="inline-flex items-center gap-1 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                التالية
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            <a href="?page=<?= $totalPages ?>" class="inline-flex items-center gap-1 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                الأخيرة
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Mobil için Basit Pagination -->
                    <div class="mt-4 flex justify-center gap-2 sm:hidden">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>" class="flex-1 text-center px-4 py-3 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                                ← السابقة
                            </a>
                        <?php endif; ?>
                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?>" class="flex-1 text-center px-4 py-3 bg-blue-600 border-2 border-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                                التالية →
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <?php
        $content = ob_get_clean();
        $unreadMessagesCount = $this->getUnreadMessagesCount($providerId);
        require __DIR__ . '/../Views/provider/layout.php';
    }
}


