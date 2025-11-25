<?php

/**
 * KhidmaApp.com - Auth Middleware
 * 
 * Admin ve Provider kimlik doğrulama middleware'i
 */

require_once __DIR__ . '/../config/config.php';

class AuthMiddleware
{
    /**
     * Admin authentication kontrolü
     * 
     * @param string $redirectUrl Yönlendirilecek URL (default: /admin/login)
     * @return bool
     */
    public static function adminAuth(string $redirectUrl = '/admin/login'): bool
    {
        startSession();
        
        if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
            if (self::isAjaxRequest()) {
                self::jsonError('Oturum süresi doldu. Lütfen tekrar giriş yapın.', 401);
            }
            
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . $redirectUrl);
            exit;
        }
        
        // Session timeout kontrolü (2 saat)
        $sessionTimeout = 7200; // 2 saat
        if (isset($_SESSION['admin_last_activity'])) {
            if (time() - $_SESSION['admin_last_activity'] > $sessionTimeout) {
                self::adminLogout();
                
                if (self::isAjaxRequest()) {
                    self::jsonError('Oturum süresi doldu. Lütfen tekrar giriş yapın.', 401);
                }
                
                $_SESSION['error'] = 'Oturum süreniz doldu. Lütfen tekrar giriş yapın.';
                header('Location: ' . $redirectUrl);
                exit;
            }
        }
        
        // Son aktivite zamanını güncelle
        $_SESSION['admin_last_activity'] = time();
        
        return true;
    }
    
    /**
     * Provider authentication kontrolü
     * 
     * @param string $redirectUrl Yönlendirilecek URL (default: /)
     * @return bool
     */
    public static function providerAuth(string $redirectUrl = '/'): bool
    {
        startSession();
        
        if (!isset($_SESSION['provider_id']) || empty($_SESSION['provider_id'])) {
            if (self::isAjaxRequest()) {
                self::jsonError('يجب تسجيل الدخول أولاً', 401);
            }
            
            $_SESSION['error'] = 'يجب تسجيل الدخول أولاً';
            header('Location: ' . $redirectUrl);
            exit;
        }
        
        // Session timeout kontrolü (24 saat)
        $sessionTimeout = 86400; // 24 saat
        if (isset($_SESSION['provider_last_activity'])) {
            if (time() - $_SESSION['provider_last_activity'] > $sessionTimeout) {
                self::providerLogout();
                
                if (self::isAjaxRequest()) {
                    self::jsonError('انتهت صلاحية الجلسة. الرجاء تسجيل الدخول مرة أخرى.', 401);
                }
                
                $_SESSION['error'] = 'انتهت صلاحية الجلسة. الرجاء تسجيل الدخول مرة أخرى.';
                header('Location: ' . $redirectUrl);
                exit;
            }
        }
        
        // Son aktivite zamanını güncelle
        $_SESSION['provider_last_activity'] = time();
        
        return true;
    }
    
    /**
     * Super Admin rolü kontrolü
     * 
     * @return bool
     */
    public static function requireSuperAdmin(): bool
    {
        self::adminAuth();
        
        $pdo = getDatabase();
        if (!$pdo) {
            self::forbidden('Veritabanı bağlantısı kurulamadı.');
        }
        
        try {
            $stmt = $pdo->prepare("SELECT role FROM admins WHERE id = ?");
            $stmt->execute([$_SESSION['admin_id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result || $result['role'] !== 'super_admin') {
                self::forbidden('Bu işlem için süper admin yetkisi gereklidir.');
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Super admin check error: " . $e->getMessage());
            self::forbidden('Yetki kontrolü yapılamadı.');
        }
        
        return false;
    }
    
    /**
     * Admin veya Super Admin rolü kontrolü
     * 
     * @return bool
     */
    public static function requireAdmin(): bool
    {
        self::adminAuth();
        
        $pdo = getDatabase();
        if (!$pdo) {
            self::forbidden('Veritabanı bağlantısı kurulamadı.');
        }
        
        try {
            $stmt = $pdo->prepare("SELECT role FROM admins WHERE id = ?");
            $stmt->execute([$_SESSION['admin_id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result || !in_array($result['role'], ['super_admin', 'admin'])) {
                self::forbidden('Bu işlem için admin yetkisi gereklidir.');
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Admin check error: " . $e->getMessage());
            self::forbidden('Yetki kontrolü yapılamadı.');
        }
        
        return false;
    }
    
    /**
     * Provider aktif durumu kontrolü
     * 
     * @return bool
     */
    public static function requireActiveProvider(): bool
    {
        self::providerAuth();
        
        $pdo = getDatabase();
        if (!$pdo) {
            self::forbidden('Veritabanı bağlantısı kurulamadı.');
        }
        
        try {
            $stmt = $pdo->prepare("SELECT status FROM service_providers WHERE id = ?");
            $stmt->execute([$_SESSION['provider_id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result || $result['status'] !== 'active') {
                if (self::isAjaxRequest()) {
                    self::jsonError('حسابك غير نشط. يرجى الاتصال بالدعم.', 403);
                }
                
                $_SESSION['error'] = 'حسابك غير نشط. يرجى الاتصال بالدعم.';
                header('Location: /provider/dashboard');
                exit;
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Active provider check error: " . $e->getMessage());
            self::forbidden('حدث خطأ أثناء التحقق من حالة الحساب.');
        }
        
        return false;
    }
    
    /**
     * Admin logout
     */
    private static function adminLogout(): void
    {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_username']);
        unset($_SESSION['admin_last_activity']);
    }
    
    /**
     * Provider logout
     */
    private static function providerLogout(): void
    {
        unset($_SESSION['provider_id']);
        unset($_SESSION['provider_name']);
        unset($_SESSION['provider_last_activity']);
    }
    
    /**
     * AJAX request kontrolü
     * 
     * @return bool
     */
    private static function isAjaxRequest(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * JSON hata response
     * 
     * @param string $message Hata mesajı
     * @param int $statusCode HTTP status kodu
     */
    private static function jsonError(string $message, int $statusCode = 400): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => $message,
            'error' => $message
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Forbidden response
     * 
     * @param string $message Hata mesajı
     */
    private static function forbidden(string $message): void
    {
        if (self::isAjaxRequest()) {
            self::jsonError($message, 403);
        }
        
        http_response_code(403);
        echo "<h1>403 Forbidden</h1><p>" . htmlspecialchars($message) . "</p>";
        exit;
    }
}

