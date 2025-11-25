<?php

/**
 * KhidmaApp.com - Base Provider Controller
 * 
 * Tüm provider controller'ların miras aldığı temel sınıf
 */

require_once __DIR__ . '/../../config/config.php';

abstract class BaseProviderController 
{
    protected $db;
    
    public function __construct() 
    {
        $this->db = getDatabase();
        startSession();
    }
    
    /**
     * Provider girişi zorunlu kıl
     */
    protected function requireAuth(): void
    {
        if (!isset($_SESSION['provider_id'])) {
            $_SESSION['error'] = 'يجب تسجيل الدخول أولاً';
            $this->redirect('/');
        }
    }
    
    /**
     * Mevcut provider ID'sini al
     */
    protected function getProviderId(): ?int
    {
        return $_SESSION['provider_id'] ?? null;
    }
    
    /**
     * Mevcut provider bilgilerini al
     */
    protected function getProvider(): ?array
    {
        $providerId = $this->getProviderId();
        if (!$providerId) return null;
        
        try {
            $stmt = $this->db->prepare("SELECT * FROM service_providers WHERE id = ?");
            $stmt->execute([$providerId]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Get provider error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Okunmamış mesaj sayısını al
     */
    protected function getUnreadMessagesCount(): int
    {
        $providerId = $this->getProviderId();
        if (!$providerId) return 0;
        
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as unread 
                FROM provider_messages 
                WHERE provider_id = ? AND is_read = 0 AND deleted_at IS NULL
            ");
            $stmt->execute([$providerId]);
            return (int)($stmt->fetch(PDO::FETCH_ASSOC)['unread'] ?? 0);
        } catch (Exception $e) {
            error_log("Get unread messages count error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * JSON response döndür
     */
    protected function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Başarılı JSON response
     */
    protected function successResponse(string $message, array $data = []): void
    {
        $this->jsonResponse(array_merge(['success' => true, 'message' => $message], $data));
    }
    
    /**
     * Hata JSON response
     */
    protected function errorResponse(string $message, int $statusCode = 400, array $data = []): void
    {
        $this->jsonResponse(array_merge(['success' => false, 'message' => $message], $data), $statusCode);
    }
    
    /**
     * View render et
     */
    protected function render(string $view, array $data = []): void
    {
        // Provider bilgilerini her view'a ekle
        $data['provider'] = $data['provider'] ?? $this->getProvider();
        $data['unreadMessages'] = $data['unreadMessages'] ?? $this->getUnreadMessagesCount();
        
        extract($data);
        $db = $this->db;
        include __DIR__ . '/../../Views/provider/' . $view . '.php';
    }
    
    /**
     * Redirect
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
    
    /**
     * POST isteği kontrolü
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * GET parametresi al
     */
    protected function getParam(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }
    
    /**
     * POST parametresi al
     */
    protected function postParam(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }
    
    /**
     * Sanitize edilmiş POST parametresi al
     */
    protected function sanitizedPost(string $key, $default = ''): string
    {
        return sanitizeInput($_POST[$key] ?? $default);
    }
    
    /**
     * Sanitize edilmiş GET parametresi al
     */
    protected function sanitizedGet(string $key, $default = ''): string
    {
        return sanitizeInput($_GET[$key] ?? $default);
    }
    
    /**
     * Integer POST parametresi al
     */
    protected function intPost(string $key, int $default = 0): int
    {
        return intval($_POST[$key] ?? $default);
    }
    
    /**
     * Integer GET parametresi al
     */
    protected function intGet(string $key, int $default = 0): int
    {
        return intval($_GET[$key] ?? $default);
    }
    
    /**
     * CSRF token doğrula
     */
    protected function verifyCsrf(): bool
    {
        $token = $this->postParam('csrf_token', '');
        return verifyCsrfToken($token);
    }
    
    /**
     * CSRF token doğrula ve hata döndür
     */
    protected function requireCsrf(): void
    {
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Geçersiz güvenlik belirteci';
            $this->redirect('/');
        }
    }
}

