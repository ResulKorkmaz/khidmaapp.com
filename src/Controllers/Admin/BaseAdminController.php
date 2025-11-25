<?php

/**
 * KhidmaApp.com - Base Admin Controller
 * 
 * Tüm admin controller'ların miras aldığı temel sınıf
 */

require_once __DIR__ . '/../../config/config.php';

abstract class BaseAdminController 
{
    protected $pdo;
    
    public function __construct() 
    {
        $this->pdo = getDatabase();
    }
    
    /**
     * Admin girişi zorunlu kıl
     */
    protected function requireAuth(): void
    {
        requireAdminLogin();
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
        extract($data);
        $pdo = $this->pdo; // View'larda kullanılabilir
        include __DIR__ . '/../../Views/admin/' . $view . '.php';
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
     * Mevcut kullanıcının rolünü al
     */
    protected function getCurrentUserRole(): string
    {
        if (!$this->pdo) return 'user';
        
        $adminId = $_SESSION['admin_id'] ?? null;
        if (!$adminId) return 'user';
        
        try {
            $stmt = $this->pdo->prepare("SELECT role FROM admins WHERE id = ?");
            $stmt->execute([$adminId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['role'] ?? 'user';
        } catch (PDOException $e) {
            error_log("getCurrentUserRole error: " . $e->getMessage());
            return 'user';
        }
    }
    
    /**
     * Süper admin mi kontrol et
     */
    protected function isSuperAdmin(): bool
    {
        return $this->getCurrentUserRole() === 'super_admin';
    }
    
    /**
     * Admin mi kontrol et (super_admin veya admin)
     */
    protected function isAdmin(): bool
    {
        $role = $this->getCurrentUserRole();
        return in_array($role, ['super_admin', 'admin']);
    }
}

