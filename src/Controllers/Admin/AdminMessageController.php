<?php

/**
 * KhidmaApp.com - Admin Message Controller
 * 
 * Provider mesajları yönetimi
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
            
            // Toplam provider sayısı
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as total
                FROM service_providers sp
                WHERE sp.status IN ('active', 'pending')
            ");
            $stmt->execute();
            $totalProviders = $stmt->fetchColumn();
            $totalPages = ceil($totalProviders / $perPage);
            
            // Provider'ları getir
            $stmt = $this->pdo->prepare("
                SELECT 
                    sp.id, sp.name, sp.email, sp.phone, sp.service_type, sp.city, sp.status,
                    (SELECT COUNT(*) FROM provider_messages WHERE provider_id = sp.id AND deleted_at IS NULL) as message_count,
                    (SELECT COUNT(*) FROM provider_messages WHERE provider_id = sp.id AND is_read = 0 AND deleted_at IS NULL) as unread_count
                FROM service_providers sp
                WHERE sp.status IN ('active', 'pending')
                ORDER BY sp.created_at DESC
                LIMIT ? OFFSET ?
            ");
            $stmt->execute([$perPage, $offset]);
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
            
        } catch (PDOException $e) {
            error_log("Provider Messages Error: " . $e->getMessage());
            
            // Tablo yoksa varsayılan değerler
            $providers = [];
            $stats = ['total_messages' => 0, 'unread_messages' => 0, 'providers_with_messages' => 0];
            $totalPages = 1;
            $totalProviders = 0;
            
            if (strpos($e->getMessage(), "doesn't exist") !== false) {
                $_SESSION['warning_message'] = 'provider_messages tablosu bulunamadı. Migration çalıştırın.';
            }
        }
        
        $this->render('provider_messages', [
            'providers' => $providers,
            'stats' => $stats,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalProviders' => $totalProviders
        ]);
    }
    
    /**
     * Mesaj gönder
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

