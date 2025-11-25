<?php

/**
 * KhidmaApp.com - Admin User Controller
 * 
 * Admin kullanıcıları yönetimi (Super Admin)
 */

require_once __DIR__ . '/BaseAdminController.php';

class AdminUserController extends BaseAdminController 
{
    /**
     * Kullanıcılar listesi
     */
    public function index(): void
    {
        $this->requireAuth();
        
        // Super admin kontrolü
        if (!$this->isSuperAdmin()) {
            $this->redirect('/admin');
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, username, email, role, is_active, created_at, last_login 
                FROM admins 
                WHERE username != ?
                ORDER BY 
                    CASE role 
                        WHEN 'super_admin' THEN 1 
                        WHEN 'admin' THEN 2 
                        WHEN 'user' THEN 3 
                    END,
                    created_at DESC
            ");
            $stmt->execute([$_SESSION['admin_username']]);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $this->render('users/index', ['users' => $users]);
        } catch (PDOException $e) {
            error_log("Get users error: " . $e->getMessage());
            $_SESSION['error'] = 'Kullanıcılar yüklenirken hata oluştu';
            $this->redirect('/admin');
        }
    }
    
    /**
     * Kullanıcı oluşturma formu
     */
    public function createForm(): void
    {
        $this->requireAuth();
        
        $currentRole = $this->getCurrentUserRole();
        
        if ($currentRole !== 'super_admin' && $currentRole !== 'admin') {
            $this->redirect('/admin');
        }
        
        $this->render('users/create', ['currentRole' => $currentRole]);
    }
    
    /**
     * Yeni kullanıcı oluştur
     */
    public function create(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->redirect('/admin/users/create');
        }
        
        $currentRole = $this->getCurrentUserRole();
        
        if ($currentRole !== 'super_admin' && $currentRole !== 'admin') {
            $this->redirect('/admin');
        }
        
        $username = $this->sanitizedPost('username');
        $email = $this->sanitizedPost('email');
        $password = $this->postParam('password', '');
        $role = $this->sanitizedPost('role', 'user');
        
        // Admin kullanıcısı sadece user oluşturabilir
        if ($currentRole === 'admin' && $role !== 'user') {
            $_SESSION['error'] = 'Admin kullanıcısı sadece "User" rolü atayabilir';
            $this->redirect('/admin/users/create');
        }
        
        // Super admin rolü sadece super_admin tarafından verilebilir
        if ($role === 'super_admin' && $currentRole !== 'super_admin') {
            $_SESSION['error'] = 'Super Admin rolü atanamaz';
            $this->redirect('/admin/users/create');
        }
        
        // Validasyon
        $errors = [];
        if (empty($username)) $errors[] = 'Kullanıcı adı gerekli';
        if (empty($email)) $errors[] = 'E-posta gerekli';
        if (empty($password)) $errors[] = 'Şifre gerekli';
        if (strlen($password) < 6) $errors[] = 'Şifre en az 6 karakter olmalı';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Geçerli bir e-posta adresi girin';
        if (!in_array($role, ['super_admin', 'admin', 'user'])) $errors[] = 'Geçersiz rol';
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            $this->redirect('/admin/users/create');
        }
        
        try {
            // Kullanıcı adı kontrolü
            $stmt = $this->pdo->prepare("SELECT id FROM admins WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'Bu kullanıcı adı veya e-posta zaten kullanımda';
                $this->redirect('/admin/users/create');
            }
            
            // Kullanıcı oluştur
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare("
                INSERT INTO admins (username, email, password, role, is_active, created_at)
                VALUES (?, ?, ?, ?, 1, NOW())
            ");
            $stmt->execute([$username, $email, $passwordHash, $role]);
            
            $_SESSION['success'] = 'Kullanıcı başarıyla oluşturuldu';
            $this->redirect('/admin/users');
        } catch (PDOException $e) {
            error_log("Create user error: " . $e->getMessage());
            $_SESSION['error'] = 'Kullanıcı oluşturulurken hata oluştu';
            $this->redirect('/admin/users/create');
        }
    }
    
    /**
     * Kullanıcı sil
     */
    public function delete(): void
    {
        $this->requireAuth();
        
        if (!$this->isSuperAdmin()) {
            $this->errorResponse('Yetkiniz yok', 403);
        }
        
        if (!$this->isPost()) {
            $this->errorResponse('Method not allowed', 405);
        }
        
        if (!verifyCsrfToken($this->postParam('csrf_token', ''))) {
            $this->errorResponse('Invalid CSRF token', 403);
        }
        
        $userId = $this->intPost('user_id');
        
        try {
            // Kendi hesabını silemez
            $stmt = $this->pdo->prepare("SELECT username FROM admins WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && $user['username'] === $_SESSION['admin_username']) {
                $this->errorResponse('Kendi hesabınızı silemezsiniz', 400);
            }
            
            $stmt = $this->pdo->prepare("DELETE FROM admins WHERE id = ? AND username != ?");
            $stmt->execute([$userId, $_SESSION['admin_username']]);
            
            $this->successResponse('Kullanıcı silindi');
        } catch (PDOException $e) {
            error_log("Delete user error: " . $e->getMessage());
            $this->errorResponse('Kullanıcı silinirken hata oluştu', 500);
        }
    }
    
    /**
     * Kullanıcı durumunu değiştir
     */
    public function toggleStatus(): void
    {
        $this->requireAuth();
        
        if (!$this->isSuperAdmin()) {
            $this->errorResponse('Yetkiniz yok', 403);
        }
        
        if (!$this->isPost()) {
            $this->errorResponse('Method not allowed', 405);
        }
        
        if (!verifyCsrfToken($this->postParam('csrf_token', ''))) {
            $this->errorResponse('Invalid CSRF token', 403);
        }
        
        $userId = $this->intPost('user_id');
        
        try {
            // Kendi hesabını pasif yapamaz
            $stmt = $this->pdo->prepare("SELECT username, is_active FROM admins WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && $user['username'] === $_SESSION['admin_username']) {
                $this->errorResponse('Kendi hesabınızı pasif yapamazsınız', 400);
            }
            
            $newStatus = $user['is_active'] ? 0 : 1;
            $stmt = $this->pdo->prepare("UPDATE admins SET is_active = ? WHERE id = ?");
            $stmt->execute([$newStatus, $userId]);
            
            $this->successResponse('Kullanıcı durumu güncellendi', ['is_active' => $newStatus]);
        } catch (PDOException $e) {
            error_log("Toggle user status error: " . $e->getMessage());
            $this->errorResponse('İşlem başarısız', 500);
        }
    }
}

