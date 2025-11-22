<?php
/**
 * KhidmaApp - Helper Functions
 * 
 * Tüm yardımcı fonksiyonlar bu dosyada toplanmıştır.
 * Güvenlik, validasyon, veri işleme ve diğer genel amaçlı fonksiyonlar.
 */

/**
 * Input Sanitization
 * XSS saldırılarına karşı koruma
 */
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * CSRF Token oluştur
 */
function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token']) || 
        !isset($_SESSION['csrf_token_time']) || 
        (time() - $_SESSION['csrf_token_time']) > CSRF_TOKEN_EXPIRE) {
        
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * CSRF Token doğrula
 */
function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && 
           hash_equals($_SESSION['csrf_token'], $token) &&
           isset($_SESSION['csrf_token_time']) &&
           (time() - $_SESSION['csrf_token_time']) <= CSRF_TOKEN_EXPIRE;
}

/**
 * Session başlat
 */
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        // Session ayarlarını başlatmadan önce yap
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_strict_mode', 1);
        session_start();
    }
}

/**
 * Hizmet türleri (Database'den - Cached)
 * Arapça-İngilizce-Türkçe
 */
function getServiceTypes($includeInactive = false) {
    static $cache = null;
    static $cacheWithInactive = null;
    
    // Cache varsa döndür
    if (!$includeInactive && $cache !== null) {
        return $cache;
    }
    if ($includeInactive && $cacheWithInactive !== null) {
        return $cacheWithInactive;
    }
    
    // Database'den oku
    try {
        $pdo = getDatabase();
        if (!$pdo) {
            // DB bağlantısı yoksa fallback
            return getFallbackServiceTypes();
        }
        
        $sql = "SELECT service_key, name_ar, name_tr, icon, is_active 
                FROM services ";
        
        if (!$includeInactive) {
            $sql .= "WHERE is_active = 1 ";
        }
        
        $sql .= "ORDER BY display_order ASC, name_tr ASC";
        
        $stmt = $pdo->query($sql);
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $result = [];
        foreach ($services as $service) {
            $result[$service['service_key']] = [
                'ar' => $service['name_ar'],
                'tr' => $service['name_tr'],
                'en' => $service['name_tr'], // EN kullanmıyoruz, TR'yi kopyala
                'icon' => $service['icon'] ?? '',
                'is_active' => (bool)$service['is_active']
            ];
        }
        
        // Cache'e kaydet
        if ($includeInactive) {
            $cacheWithInactive = $result;
        } else {
            $cache = $result;
        }
        
        return $result;
        
    } catch (Exception $e) {
        error_log("❌ getServiceTypes error: " . $e->getMessage());
        return getFallbackServiceTypes();
    }
}

/**
 * Fallback hizmet türleri (Database erişilemezse)
 */
function getFallbackServiceTypes() {
    return [
        'paint' => ['ar' => 'دهان', 'en' => 'Painting', 'tr' => 'Boya Badana', 'icon' => '🎨'],
        'renovation' => ['ar' => 'ترميم', 'en' => 'Renovation', 'tr' => 'Tadilat', 'icon' => '🔨'],
        'plumbing' => ['ar' => 'سباكة', 'en' => 'Plumbing', 'tr' => 'Sıhhi Tesisat', 'icon' => '🚰'],
        'electric' => ['ar' => 'كهرباء', 'en' => 'Electrical', 'tr' => 'Elektrik', 'icon' => '⚡'],
        'cleaning' => ['ar' => 'تنظيف', 'en' => 'Cleaning', 'tr' => 'Temizlik', 'icon' => '🧹'],
        'ac' => ['ar' => 'تكييف', 'en' => 'Air Conditioning', 'tr' => 'Klima', 'icon' => '❄️']
    ];
}

/**
 * Hizmet detay bilgileri (SEO ve içerik için)
 */
function getServiceDetails($serviceKey) {
    $details = [
        'paint' => [
            'title' => 'خدمات الدهانات في السعودية | دهانات داخلية وخارجية احترافية',
            'description' => 'خدمات دهانات شاملة في المملكة العربية السعودية. دهانات داخلية وخارجية بأحدث التقنيات والألوان. فريق محترف ومواد عالية الجودة.',
            'keywords' => 'دهانات، دهان، طلاء، دهانات داخلية، دهانات خارجية، السعودية، الرياض، جدة',
            'content' => [
                'intro' => 'نقدم خدمات دهانات شاملة ومتميزة في جميع أنحاء المملكة العربية السعودية. نستخدم أحدث التقنيات والمواد عالية الجودة لضمان نتائج مثالية تدوم طويلاً.',
                'features' => [
                    'دهانات داخلية بألوان عصرية ومتنوعة',
                    'دهانات خارجية مقاومة للعوامل الجوية',
                    'استخدام مواد صديقة للبيئة وآمنة',
                    'فريق محترف ذو خبرة واسعة',
                    'ضمان الجودة والرضا التام',
                    'أسعار تنافسية وشفافة'
                ],
                'process' => [
                    'التشاور والاستشارة المجانية',
                    'اختيار الألوان والمواد المناسبة',
                    'التحضير والتجهيز الكامل للأسطح',
                    'التطبيق الاحترافي للدهان',
                    'اللمسات الأخيرة والتسليم'
                ]
            ]
        ],
        'plumbing' => [
            'title' => 'خدمات السباكة في السعودية | إصلاح وصيانة أنظمة السباكة',
            'description' => 'خدمات سباكة شاملة في المملكة العربية السعودية. إصلاح وصيانة أنظمة السباكة، تركيب وصيانة الأدوات الصحية. خدمة سريعة وموثوقة.',
            'keywords' => 'سباكة، سباك، إصلاح سباكة، صيانة سباكة، السعودية، الرياض، جدة',
            'content' => [
                'intro' => 'نقدم خدمات سباكة شاملة ومحترفة في جميع أنحاء المملكة. فريق من السباكين المحترفين جاهز لحل جميع مشاكل السباكة بسرعة وكفاءة.',
                'features' => [
                    'إصلاح تسريبات المياه',
                    'صيانة أنظمة السباكة',
                    'تركيب الأدوات الصحية',
                    'تنظيف المجاري والأنابيب',
                    'استبدال الأنابيب القديمة',
                    'خدمة طوارئ 24/7'
                ],
                'process' => [
                    'فحص شامل للنظام',
                    'تشخيص المشكلة بدقة',
                    'تقديم الحلول والاقتراحات',
                    'تنفيذ الإصلاحات',
                    'اختبار النظام',
                    'ضمان العمل'
                ]
            ]
        ],
        // Diğer servisler için detaylar...
        // (Tamamını eklemeyi buraya bırakıyorum, gerekirse eklenebilir)
    ];
    
    return $details[$serviceKey] ?? null;
}

/**
 * Şehirler (Arapça-İngilizce-Türkçe)
 */
function getCities() {
    return [
        'riyadh' => ['ar' => 'الرياض', 'en' => 'Riyadh', 'tr' => 'Riyad'],
        'jeddah' => ['ar' => 'جدة', 'en' => 'Jeddah', 'tr' => 'Cidde'],
        'mecca' => ['ar' => 'مكة المكرمة', 'en' => 'Mecca', 'tr' => 'Mekke'],
        'medina' => ['ar' => 'المدينة المنورة', 'en' => 'Medina', 'tr' => 'Medine'],
        'dammam' => ['ar' => 'الدمام', 'en' => 'Dammam', 'tr' => 'Dammam'],
        'khobar' => ['ar' => 'الخبر', 'en' => 'Khobar', 'tr' => 'Khobar'],
        'tabuk' => ['ar' => 'تبوك', 'en' => 'Tabuk', 'tr' => 'Tebük'],
        'abha' => ['ar' => 'أبها', 'en' => 'Abha', 'tr' => 'Abha']
    ];
}

/**
 * Admin Authentication Functions
 */

/**
 * Admin giriş kontrolü
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
}

/**
 * Admin giriş yap
 */
function adminLogin($username, $password) {
    $pdo = getDatabase();
    if (!$pdo) {
        return false;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT id, username, password_hash, is_active FROM admins WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && $admin['is_active'] && password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            
            // Son giriş zamanını güncelle
            $updateStmt = $pdo->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?");
            $updateStmt->execute([$admin['id']]);
            
            return true;
        }
    } catch (PDOException $e) {
        error_log("Admin login error: " . $e->getMessage());
    }
    
    return false;
}

/**
 * Admin çıkış yap
 */
function adminLogout() {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    session_destroy();
}

/**
 * Admin sayfası koruması (giriş yapmamışsa login'e yönlendir)
 */
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        // Check if this is an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        
        // Also check if Accept header prefers JSON
        $acceptsJson = isset($_SERVER['HTTP_ACCEPT']) && 
                       strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
        
        if ($isAjax || $acceptsJson) {
            // Return JSON error for AJAX requests
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => 'unauthorized',
                'message' => 'Admin login required',
                'redirect' => '/admin/login'
            ]);
            exit;
        }
        
        // Normal redirect for regular requests
        header('Location: /admin/login');
        exit;
    }
}




