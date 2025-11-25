<?php

/**
 * KhidmaApp.com - CSRF Middleware
 * 
 * Cross-Site Request Forgery koruması
 */

require_once __DIR__ . '/../config/config.php';

class CsrfMiddleware
{
    /**
     * CSRF koruması gerektirmeyen path'ler
     */
    private static $excludedPaths = [
        '/webhook/stripe',
        '/api/public/',
    ];
    
    /**
     * CSRF token doğrula
     * 
     * @param bool $throwException Hata durumunda exception fırlat
     * @return bool
     */
    public static function verify(bool $throwException = true): bool
    {
        // Excluded path kontrolü
        $currentPath = $_SERVER['REQUEST_URI'] ?? '/';
        foreach (self::$excludedPaths as $path) {
            if (strpos($currentPath, $path) === 0) {
                return true;
            }
        }
        
        // GET istekleri için CSRF kontrolü yapma
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return true;
        }
        
        // Token'ı al
        $token = self::getTokenFromRequest();
        
        // Token doğrula
        if (!verifyCsrfToken($token)) {
            if ($throwException) {
                self::handleInvalidToken();
            }
            return false;
        }
        
        return true;
    }
    
    /**
     * CSRF token oluştur
     * 
     * @return string
     */
    public static function generate(): string
    {
        return generateCsrfToken();
    }
    
    /**
     * CSRF token HTML input'u oluştur
     * 
     * @return string
     */
    public static function field(): string
    {
        $token = self::generate();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * CSRF token meta tag'i oluştur (AJAX için)
     * 
     * @return string
     */
    public static function meta(): string
    {
        $token = self::generate();
        return '<meta name="csrf-token" content="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * Request'ten token al
     * 
     * @return string
     */
    private static function getTokenFromRequest(): string
    {
        // 1. POST data
        if (!empty($_POST['csrf_token'])) {
            return $_POST['csrf_token'];
        }
        
        // 2. JSON body
        $jsonInput = file_get_contents('php://input');
        if ($jsonInput) {
            $data = json_decode($jsonInput, true);
            if (!empty($data['csrf_token'])) {
                return $data['csrf_token'];
            }
        }
        
        // 3. Header (X-CSRF-TOKEN)
        if (!empty($_SERVER['HTTP_X_CSRF_TOKEN'])) {
            return $_SERVER['HTTP_X_CSRF_TOKEN'];
        }
        
        // 4. Header (X-XSRF-TOKEN - Laravel style)
        if (!empty($_SERVER['HTTP_X_XSRF_TOKEN'])) {
            return $_SERVER['HTTP_X_XSRF_TOKEN'];
        }
        
        return '';
    }
    
    /**
     * Geçersiz token durumunu işle
     */
    private static function handleInvalidToken(): void
    {
        error_log("CSRF token validation failed: " . ($_SERVER['REQUEST_URI'] ?? 'unknown'));
        
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        
        http_response_code(403);
        
        if ($isAjax || strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'message' => 'Güvenlik doğrulaması başarısız. Lütfen sayfayı yenileyip tekrar deneyin.',
                'error' => 'csrf_token_invalid'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo "<h1>403 Forbidden</h1>";
            echo "<p>Güvenlik doğrulaması başarısız. Lütfen sayfayı yenileyip tekrar deneyin.</p>";
        }
        
        exit;
    }
}

