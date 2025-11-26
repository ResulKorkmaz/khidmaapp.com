<?php

/**
 * KhidmaApp.com - Provider Profile Controller
 * 
 * Provider profil ve ayar yönetimi
 */

require_once __DIR__ . '/BaseProviderController.php';

class ProviderProfileController extends BaseProviderController 
{
    /**
     * Profil sayfası
     */
    public function index(): void
    {
        $this->requireAuth();
        
        $provider = $this->getProvider();
        
        if (!$provider) {
            $this->redirect('/');
        }
        
        // POST isteği - profil güncelleme
        if ($this->isPost()) {
            $this->updateProfile($provider);
            return;
        }
        
        // Hizmet türlerini al
        $serviceTypes = getServiceTypes();
        
        $this->render('profile', [
            'provider' => $provider,
            'serviceTypes' => $serviceTypes
        ]);
    }
    
    /**
     * Ayarlar sayfası
     */
    public function settings(): void
    {
        $this->requireAuth();
        
        $provider = $this->getProvider();
        
        if (!$provider) {
            $this->redirect('/');
        }
        
        // POST isteği - ayar güncelleme
        if ($this->isPost()) {
            $this->updateSettings($provider);
            return;
        }
        
        $this->render('settings', [
            'provider' => $provider
        ]);
    }
    
    /**
     * Profil güncelle
     */
    private function updateProfile(array $provider): void
    {
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'رمز الأمان غير صالح';
            $this->redirect('/provider/profile');
        }
        
        $action = $this->postParam('action');
        
        switch ($action) {
            case 'update_profile':
                $this->updatePersonalInfo($provider);
                break;
            case 'update_service':
                $this->updateServiceInfo($provider);
                break;
            case 'change_password':
                $this->changePasswordFromProfile($provider);
                break;
            default:
                $_SESSION['error'] = 'إجراء غير صالح';
                $this->redirect('/provider/profile');
        }
    }
    
    /**
     * Kişisel bilgileri güncelle
     */
    private function updatePersonalInfo(array $provider): void
    {
        $name = $this->sanitizedPost('name');
        $email = $this->sanitizedPost('email');
        $phone = $this->sanitizedPost('phone');
        
        // Validasyon
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'الاسم مطلوب';
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'البريد الإلكتروني غير صالح';
        }
        
        if (empty($phone)) {
            $errors[] = 'رقم الهاتف مطلوب';
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $this->redirect('/provider/profile');
        }
        
        try {
            // Email benzersizliği kontrol et
            $stmt = $this->db->prepare("SELECT id FROM service_providers WHERE email = ? AND id != ?");
            $stmt->execute([$email, $provider['id']]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'هذا البريد الإلكتروني مستخدم بالفعل';
                $this->redirect('/provider/profile');
            }
            
            // Güncelle
            $stmt = $this->db->prepare("
                UPDATE service_providers 
                SET name = ?, email = ?, phone = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$name, $email, $phone, $provider['id']]);
            
            // Session'daki ismi güncelle
            $_SESSION['provider_name'] = $name;
            
            $_SESSION['success'] = 'تم تحديث المعلومات الشخصية بنجاح';
            $this->redirect('/provider/profile');
            
        } catch (PDOException $e) {
            error_log("Update personal info error: " . $e->getMessage());
            $_SESSION['error'] = 'حدث خطأ أثناء تحديث المعلومات';
            $this->redirect('/provider/profile');
        }
    }
    
    /**
     * Hizmet bilgilerini güncelle
     */
    private function updateServiceInfo(array $provider): void
    {
        $serviceType = $this->sanitizedPost('service_type');
        $city = $this->sanitizedPost('city');
        $bio = $this->sanitizedPost('bio');
        
        try {
            $stmt = $this->db->prepare("
                UPDATE service_providers 
                SET service_type = ?, city = ?, bio = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$serviceType, $city, $bio, $provider['id']]);
            
            $_SESSION['success'] = 'تم تحديث معلومات الخدمة بنجاح';
            $this->redirect('/provider/profile');
            
        } catch (PDOException $e) {
            error_log("Update service info error: " . $e->getMessage());
            $_SESSION['error'] = 'حدث خطأ أثناء تحديث معلومات الخدمة';
            $this->redirect('/provider/profile');
        }
    }
    
    /**
     * Profil sayfasından şifre değiştir
     */
    private function changePasswordFromProfile(array $provider): void
    {
        $currentPassword = $this->postParam('current_password');
        $newPassword = $this->postParam('new_password');
        $confirmPassword = $this->postParam('confirm_password');
        
        // Validasyon
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['error'] = 'جميع حقول كلمة المرور مطلوبة';
            $this->redirect('/provider/profile');
        }
        
        if (!password_verify($currentPassword, $provider['password_hash'])) {
            $_SESSION['error'] = 'كلمة المرور الحالية غير صحيحة';
            $this->redirect('/provider/profile');
        }
        
        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'كلمات المرور الجديدة غير متطابقة';
            $this->redirect('/provider/profile');
        }
        
        if (strlen($newPassword) < 6) {
            $_SESSION['error'] = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
            $this->redirect('/provider/profile');
        }
        
        try {
            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $stmt = $this->db->prepare("UPDATE service_providers SET password_hash = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$passwordHash, $provider['id']]);
            
            $_SESSION['success'] = 'تم تغيير كلمة المرور بنجاح';
            $this->redirect('/provider/profile');
            
        } catch (PDOException $e) {
            error_log("Change password error: " . $e->getMessage());
            $_SESSION['error'] = 'حدث خطأ أثناء تغيير كلمة المرور';
            $this->redirect('/provider/profile');
        }
    }
    
    /**
     * Ayarları güncelle (şifre değiştirme vb.)
     */
    private function updateSettings(array $provider): void
    {
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Geçersiz güvenlik belirteci';
            $this->redirect('/provider/settings');
        }
        
        $action = $this->postParam('action');
        
        switch ($action) {
            case 'change_password':
                $this->changePassword($provider);
                break;
            case 'update_notifications':
                $this->updateNotifications($provider);
                break;
            default:
                $_SESSION['error'] = 'Geçersiz işlem';
                $this->redirect('/provider/settings');
        }
    }
    
    /**
     * Şifre değiştir
     */
    private function changePassword(array $provider): void
    {
        $currentPassword = $this->postParam('current_password');
        $newPassword = $this->postParam('new_password');
        $confirmPassword = $this->postParam('confirm_password');
        
        // Validasyon
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['error'] = 'جميع الحقول مطلوبة';
            $this->redirect('/provider/settings');
        }
        
        if (!password_verify($currentPassword, $provider['password_hash'])) {
            $_SESSION['error'] = 'كلمة المرور الحالية غير صحيحة';
            $this->redirect('/provider/settings');
        }
        
        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'كلمات المرور الجديدة غير متطابقة';
            $this->redirect('/provider/settings');
        }
        
        if (strlen($newPassword) < 6) {
            $_SESSION['error'] = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
            $this->redirect('/provider/settings');
        }
        
        try {
            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $stmt = $this->db->prepare("UPDATE service_providers SET password_hash = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$passwordHash, $provider['id']]);
            
            $_SESSION['success'] = 'تم تغيير كلمة المرور بنجاح';
            $this->redirect('/provider/settings');
            
        } catch (PDOException $e) {
            error_log("Change password error: " . $e->getMessage());
            $_SESSION['error'] = 'حدث خطأ أثناء تغيير كلمة المرور';
            $this->redirect('/provider/settings');
        }
    }
    
    /**
     * Bildirim ayarlarını güncelle
     */
    private function updateNotifications(array $provider): void
    {
        $emailNotifications = $this->postParam('email_notifications') ? 1 : 0;
        $smsNotifications = $this->postParam('sms_notifications') ? 1 : 0;
        
        try {
            $stmt = $this->db->prepare("
                UPDATE service_providers 
                SET email_notifications = ?, sms_notifications = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$emailNotifications, $smsNotifications, $provider['id']]);
            
            $_SESSION['success'] = 'تم تحديث إعدادات الإشعارات';
            $this->redirect('/provider/settings');
            
        } catch (PDOException $e) {
            error_log("Update notifications error: " . $e->getMessage());
            $_SESSION['error'] = 'حدث خطأ أثناء تحديث الإعدادات';
            $this->redirect('/provider/settings');
        }
    }
}

