<?php

/**
 * KhidmaApp.com - Provider Message Controller
 * 
 * Provider mesaj yönetimi
 */

require_once __DIR__ . '/BaseProviderController.php';

class ProviderMessageController extends BaseProviderController 
{
    /**
     * Mesajlar sayfası
     */
    public function index(): void
    {
        $this->requireAuth();
        
        $providerId = $this->getProviderId();
        $provider = $this->getProvider();
        
        try {
            $stmt = $this->db->prepare("
                SELECT pm.*, 
                       CASE WHEN pm.sender_type = 'admin' THEN a.username ELSE 'النظام' END as admin_name
                FROM provider_messages pm
                LEFT JOIN admins a ON pm.sender_id = a.id AND pm.sender_type = 'admin'
                WHERE pm.provider_id = ? AND pm.deleted_at IS NULL
                ORDER BY pm.created_at DESC
            ");
            $stmt->execute([$providerId]);
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $this->render('messages', [
                'messages' => $messages,
                'provider' => $provider
            ]);
        } catch (PDOException $e) {
            error_log("Provider messages error: " . $e->getMessage());
            $_SESSION['error'] = 'Mesajlar yüklenirken hata oluştu';
            $this->redirect('/provider/dashboard');
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
        $providerId = $this->getProviderId();
        
        if (!$messageId) {
            $this->errorResponse('Geçersiz mesaj ID', 400);
        }
        
        try {
            $stmt = $this->db->prepare("
                UPDATE provider_messages 
                SET is_read = 1, read_at = NOW()
                WHERE id = ? AND provider_id = ?
            ");
            $stmt->execute([$messageId, $providerId]);
            
            $this->successResponse('Mesaj okundu olarak işaretlendi');
        } catch (PDOException $e) {
            error_log("Mark message read error: " . $e->getMessage());
            $this->errorResponse('İşlem başarısız', 500);
        }
    }
    
    /**
     * Tüm mesajları okundu olarak işaretle
     */
    public function markAllRead(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('Method not allowed', 405);
        }
        
        $providerId = $this->getProviderId();
        
        try {
            $stmt = $this->db->prepare("
                UPDATE provider_messages 
                SET is_read = 1, read_at = NOW()
                WHERE provider_id = ? AND is_read = 0 AND deleted_at IS NULL
            ");
            $stmt->execute([$providerId]);
            
            $this->successResponse('Tüm mesajlar okundu olarak işaretlendi');
        } catch (PDOException $e) {
            error_log("Mark all messages read error: " . $e->getMessage());
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
        
        if (!$this->verifyCsrf()) {
            $this->errorResponse('Geçersiz güvenlik belirteci', 403);
        }
        
        $messageId = $this->intPost('message_id');
        $providerId = $this->getProviderId();
        
        if (!$messageId) {
            $this->errorResponse('Geçersiz mesaj ID', 400);
        }
        
        try {
            // Soft delete
            $stmt = $this->db->prepare("
                UPDATE provider_messages 
                SET deleted_at = NOW()
                WHERE id = ? AND provider_id = ?
            ");
            $stmt->execute([$messageId, $providerId]);
            
            $this->successResponse('Mesaj silindi');
        } catch (PDOException $e) {
            error_log("Delete message error: " . $e->getMessage());
            $this->errorResponse('İşlem başarısız', 500);
        }
    }
}

