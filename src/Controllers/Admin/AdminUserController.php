<?php

/**
 * KhidmaApp.com - Admin User Controller
 * 
 * Admin kullanıcıları yönetimi
 * Rol bazlı görünürlük:
 * - Super Admin: Herkesi görür
 * - Admin: Sadece User'ları görür
 * - User: Sadece diğer User'ları görür
 */

require_once __DIR__ . '/BaseAdminController.php';

class AdminUserController extends BaseAdminController 
{
    private const SUPER_ADMIN_USERNAME = 'rslkrkmz';
    
    /**
     * Kullanıcılar listesi
     */
    public function index(): void
    {
        $this->requireAuth();
        
        $currentRole = $this->getCurrentUserRole();
        
        try {
            // Rol bazlı filtreleme
            $roleFilter = '';
            $params = [$_SESSION['admin_username']]; // Kendini hariç tut
            
            if ($currentRole === 'super_admin') {
                // Super admin herkesi görür
                $roleFilter = '';
            } elseif ($currentRole === 'admin') {
                // Admin sadece user'ları görür
                $roleFilter = "AND role = 'user'";
            } else {
                // User sadece diğer user'ları görür
                $roleFilter = "AND role = 'user'";
            }
            
            $stmt = $this->pdo->prepare("
                SELECT id, username, email, role, is_active, created_at, last_login 
                FROM admins 
                WHERE username != ? {$roleFilter}
                ORDER BY 
                    CASE role 
                        WHEN 'super_admin' THEN 1 
                        WHEN 'admin' THEN 2 
                        WHEN 'user' THEN 3 
                    END,
                    created_at DESC
            ");
            $stmt->execute($params);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $this->render('users/index', [
                'users' => $users,
                'currentRole' => $currentRole,
                'superAdminUsername' => self::SUPER_ADMIN_USERNAME
            ]);
        } catch (PDOException $e) {
            error_log("Get users error: " . $e->getMessage());
            $_SESSION['error'] = 'Kullanıcılar yüklenirken hata oluştu';
            $this->redirect('/admin');
        }
    }
    
    /**
     * Kendi profil sayfası (herkes için)
     */
    public function profile(): void
    {
        $this->requireAuth();
        
        $userId = $_SESSION['admin_id'];
        $currentRole = $this->getCurrentUserRole();
        
        try {
            $stmt = $this->pdo->prepare("SELECT id, username, email, role, created_at, last_login FROM admins WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                $_SESSION['error'] = 'Kullanıcı bulunamadı';
                $this->redirect('/admin');
            }
            
            $this->render('users/profile', [
                'user' => $user,
                'currentRole' => $currentRole,
                'superAdminUsername' => self::SUPER_ADMIN_USERNAME
            ]);
        } catch (PDOException $e) {
            error_log("Get profile error: " . $e->getMessage());
            $_SESSION['error'] = 'Profil bilgileri alınamadı';
            $this->redirect('/admin');
        }
    }
    
    /**
     * Kendi profilini güncelle
     */
    public function updateProfile(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->redirect('/admin/profile');
        }
        
        $userId = $_SESSION['admin_id'];
        $username = $this->sanitizedPost('username');
        $email = $this->sanitizedPost('email');
        $currentPassword = $this->postParam('current_password', '');
        $newPassword = $this->postParam('new_password', '');
        
        try {
            // Mevcut kullanıcıyı al
            $stmt = $this->pdo->prepare("SELECT id, username, password_hash, role FROM admins WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                $_SESSION['error'] = 'Kullanıcı bulunamadı';
                $this->redirect('/admin/profile');
            }
            
            // Super admin kullanıcı adı değiştirilemez
            if ($user['username'] === self::SUPER_ADMIN_USERNAME && $username !== self::SUPER_ADMIN_USERNAME) {
                $_SESSION['error'] = 'Super Admin kullanıcı adı değiştirilemez';
                $this->redirect('/admin/profile');
            }
            
            // Validasyon
            $errors = [];
            if (empty($username)) $errors[] = 'Kullanıcı adı gerekli';
            if (empty($email)) $errors[] = 'E-posta gerekli';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Geçerli bir e-posta adresi girin';
            
            // Şifre değişikliği için mevcut şifre kontrolü
            if (!empty($newPassword)) {
                if (empty($currentPassword)) {
                    $errors[] = 'Şifre değiştirmek için mevcut şifrenizi girin';
                } elseif (!password_verify($currentPassword, $user['password_hash'])) {
                    $errors[] = 'Mevcut şifre yanlış';
                } elseif (strlen($newPassword) < 6) {
                    $errors[] = 'Yeni şifre en az 6 karakter olmalı';
                }
            }
            
            if (!empty($errors)) {
                $_SESSION['error'] = implode(', ', $errors);
                $this->redirect('/admin/profile');
            }
            
            // Kullanıcı adı/email benzersizlik kontrolü (kendisi hariç)
            $stmt = $this->pdo->prepare("SELECT id FROM admins WHERE (username = ? OR email = ?) AND id != ?");
            $stmt->execute([$username, $email, $userId]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'Bu kullanıcı adı veya e-posta zaten kullanımda';
                $this->redirect('/admin/profile');
            }
            
            // Güncelle
            if (!empty($newPassword)) {
                $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);
                $stmt = $this->pdo->prepare("UPDATE admins SET username = ?, email = ?, password_hash = ? WHERE id = ?");
                $stmt->execute([$username, $email, $passwordHash, $userId]);
            } else {
                $stmt = $this->pdo->prepare("UPDATE admins SET username = ?, email = ? WHERE id = ?");
                $stmt->execute([$username, $email, $userId]);
            }
            
            // Session'ı güncelle
            $_SESSION['admin_username'] = $username;
            
            $_SESSION['success'] = 'Profil başarıyla güncellendi';
            $this->redirect('/admin/profile');
        } catch (PDOException $e) {
            error_log("Update profile error: " . $e->getMessage());
            $_SESSION['error'] = 'Profil güncellenirken hata oluştu';
            $this->redirect('/admin/profile');
        }
    }
    
    /**
     * Kullanıcı düzenleme formu
     */
    public function editForm(): void
    {
        $this->requireAuth();
        
        $userId = $this->intGet('id');
        if (!$userId) {
            $_SESSION['error'] = 'Geçersiz kullanıcı ID';
            $this->redirect('/admin/users');
        }
        
        $currentRole = $this->getCurrentUserRole();
        
        try {
            $stmt = $this->pdo->prepare("SELECT id, username, email, role FROM admins WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                $_SESSION['error'] = 'Kullanıcı bulunamadı';
                $this->redirect('/admin/users');
            }
            
            // Yetki kontrolü
            if (!$this->canEditUser($user, $currentRole)) {
                $_SESSION['error'] = 'Bu kullanıcıyı düzenleme yetkiniz yok';
                $this->redirect('/admin/users');
            }
            
            $this->render('users/edit', [
                'user' => $user,
                'currentRole' => $currentRole,
                'superAdminUsername' => self::SUPER_ADMIN_USERNAME
            ]);
        } catch (PDOException $e) {
            error_log("Get user for edit error: " . $e->getMessage());
            $_SESSION['error'] = 'Kullanıcı bilgileri alınamadı';
            $this->redirect('/admin/users');
        }
    }
    
    /**
     * Kullanıcı güncelle
     */
    public function update(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->redirect('/admin/users');
        }
        
        $userId = $this->intPost('user_id');
        $username = $this->sanitizedPost('username');
        $email = $this->sanitizedPost('email');
        $password = $this->postParam('password', '');
        
        $currentRole = $this->getCurrentUserRole();
        
        try {
            // Mevcut kullanıcıyı al
            $stmt = $this->pdo->prepare("SELECT id, username, email, role FROM admins WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                $_SESSION['error'] = 'Kullanıcı bulunamadı';
                $this->redirect('/admin/users');
            }
            
            // Yetki kontrolü
            if (!$this->canEditUser($user, $currentRole)) {
                $_SESSION['error'] = 'Bu kullanıcıyı düzenleme yetkiniz yok';
                $this->redirect('/admin/users');
            }
            
            // Super admin kullanıcı adı değiştirilemez
            if ($user['username'] === self::SUPER_ADMIN_USERNAME && $username !== self::SUPER_ADMIN_USERNAME) {
                $_SESSION['error'] = 'Super Admin kullanıcı adı değiştirilemez';
                $this->redirect('/admin/users/edit?id=' . $userId);
            }
            
            // Validasyon
            $errors = [];
            if (empty($username)) $errors[] = 'Kullanıcı adı gerekli';
            if (empty($email)) $errors[] = 'E-posta gerekli';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Geçerli bir e-posta adresi girin';
            if (!empty($password) && strlen($password) < 6) $errors[] = 'Şifre en az 6 karakter olmalı';
            
            if (!empty($errors)) {
                $_SESSION['error'] = implode(', ', $errors);
                $this->redirect('/admin/users/edit?id=' . $userId);
            }
            
            // Kullanıcı adı/email benzersizlik kontrolü (kendisi hariç)
            $stmt = $this->pdo->prepare("SELECT id FROM admins WHERE (username = ? OR email = ?) AND id != ?");
            $stmt->execute([$username, $email, $userId]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'Bu kullanıcı adı veya e-posta zaten kullanımda';
                $this->redirect('/admin/users/edit?id=' . $userId);
            }
            
            // Güncelle
            if (!empty($password)) {
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $this->pdo->prepare("UPDATE admins SET username = ?, email = ?, password_hash = ? WHERE id = ?");
                $stmt->execute([$username, $email, $passwordHash, $userId]);
            } else {
                $stmt = $this->pdo->prepare("UPDATE admins SET username = ?, email = ? WHERE id = ?");
                $stmt->execute([$username, $email, $userId]);
            }
            
            // Eğer kendi bilgilerini güncelliyorsa session'ı güncelle
            if ($userId == $_SESSION['admin_id']) {
                $_SESSION['admin_username'] = $username;
            }
            
            $_SESSION['success'] = 'Kullanıcı başarıyla güncellendi';
            $this->redirect('/admin/users');
        } catch (PDOException $e) {
            error_log("Update user error: " . $e->getMessage());
            $_SESSION['error'] = 'Kullanıcı güncellenirken hata oluştu';
            $this->redirect('/admin/users/edit?id=' . $userId);
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
                INSERT INTO admins (username, email, password_hash, role, is_active, created_at)
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
        
        if (!$this->isPost()) {
            $this->errorResponse('Method not allowed', 405);
        }
        
        if (!verifyCsrfToken($this->postParam('csrf_token', ''))) {
            $this->errorResponse('Invalid CSRF token', 403);
        }
        
        $userId = $this->intPost('user_id');
        $currentRole = $this->getCurrentUserRole();
        
        try {
            // Silinecek kullanıcıyı al
            $stmt = $this->pdo->prepare("SELECT username, role FROM admins WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                $this->errorResponse('Kullanıcı bulunamadı', 404);
            }
            
            // Kendi hesabını silemez
            if ($user['username'] === $_SESSION['admin_username']) {
                $this->errorResponse('Kendi hesabınızı silemezsiniz', 400);
            }
            
            // Super admin silinemez
            if ($user['username'] === self::SUPER_ADMIN_USERNAME) {
                $this->errorResponse('Super Admin silinemez', 400);
            }
            
            // Yetki kontrolü
            if (!$this->canDeleteUser($user, $currentRole)) {
                $this->errorResponse('Bu kullanıcıyı silme yetkiniz yok', 403);
            }
            
            $stmt = $this->pdo->prepare("DELETE FROM admins WHERE id = ?");
            $stmt->execute([$userId]);
            
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
        
        if (!$this->isPost()) {
            $this->errorResponse('Method not allowed', 405);
        }
        
        if (!verifyCsrfToken($this->postParam('csrf_token', ''))) {
            $this->errorResponse('Invalid CSRF token', 403);
        }
        
        $userId = $this->intPost('user_id');
        $currentRole = $this->getCurrentUserRole();
        
        try {
            $stmt = $this->pdo->prepare("SELECT username, role, is_active FROM admins WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                $this->errorResponse('Kullanıcı bulunamadı', 404);
            }
            
            // Kendi hesabını pasif yapamaz
            if ($user['username'] === $_SESSION['admin_username']) {
                $this->errorResponse('Kendi hesabınızı pasif yapamazsınız', 400);
            }
            
            // Super admin pasif yapılamaz
            if ($user['username'] === self::SUPER_ADMIN_USERNAME) {
                $this->errorResponse('Super Admin pasif yapılamaz', 400);
            }
            
            // Yetki kontrolü
            if (!$this->canEditUser($user, $currentRole)) {
                $this->errorResponse('Bu kullanıcının durumunu değiştirme yetkiniz yok', 403);
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
    
    /**
     * Kullanıcıyı düzenleme yetkisi var mı?
     */
    private function canEditUser(array $user, string $currentRole): bool
    {
        // Kendi hesabını düzenleyebilir
        if ($user['username'] === $_SESSION['admin_username']) {
            return true;
        }
        
        if ($currentRole === 'super_admin') {
            return true; // Super admin herkesi düzenleyebilir
        }
        
        if ($currentRole === 'admin') {
            return $user['role'] === 'user'; // Admin sadece user'ları düzenleyebilir
        }
        
        // User başka user'ları düzenleyemez (sadece kendini)
        return false;
    }
    
    /**
     * Kullanıcıyı silme yetkisi var mı?
     */
    private function canDeleteUser(array $user, string $currentRole): bool
    {
        if ($currentRole === 'super_admin') {
            return true; // Super admin herkesi silebilir (kendisi ve ana super admin hariç)
        }
        
        if ($currentRole === 'admin') {
            return $user['role'] === 'user'; // Admin sadece user'ları silebilir
        }
        
        return false; // User kimseyi silemez
    }
}
