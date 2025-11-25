<?php

/**
 * KhidmaApp.com - Provider Auth Controller
 * 
 * Provider giriş/çıkış/kayıt işlemleri
 */

require_once __DIR__ . '/BaseProviderController.php';

class ProviderAuthController extends BaseProviderController 
{
    /**
     * Provider Login
     */
    public function login(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/');
        }
        
        $this->requireCsrf();
        
        $identifier = trim($this->postParam('identifier', ''));
        $password = $this->postParam('password', '');
        $remember = isset($_POST['remember']);
        
        if (empty($identifier) || empty($password)) {
            $_SESSION['error'] = 'الرجاء إدخال البريد الإلكتروني/رقم الهاتف وكلمة المرور';
            $this->redirect('/');
        }
        
        // Find provider by email or phone
        $stmt = $this->db->prepare("SELECT * FROM service_providers WHERE email = ? OR phone = ? LIMIT 1");
        $stmt->execute([$identifier, $identifier]);
        $provider = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$provider) {
            $_SESSION['error'] = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
            $this->redirect('/');
        }
        
        if (!password_verify($password, $provider['password_hash'])) {
            $_SESSION['error'] = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
            $this->redirect('/');
        }
        
        if ($provider['status'] === 'suspended') {
            $_SESSION['error'] = 'حسابك معلق. يرجى الاتصال بالدعم';
            $this->redirect('/');
        }
        
        if ($provider['status'] === 'rejected') {
            $_SESSION['error'] = 'تم رفض حسابك';
            $this->redirect('/');
        }
        
        // Set session
        $_SESSION['provider_id'] = $provider['id'];
        $_SESSION['provider_name'] = $provider['name'];
        $_SESSION['provider_email'] = $provider['email'];
        $_SESSION['provider_service_type'] = $provider['service_type'];
        
        // Update last login
        $stmt = $this->db->prepare("UPDATE service_providers SET last_login_at = NOW() WHERE id = ?");
        $stmt->execute([$provider['id']]);
        
        // Handle remember me
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $stmt = $this->db->prepare("UPDATE service_providers SET remember_token = ? WHERE id = ?");
            $stmt->execute([$token, $provider['id']]);
            setcookie('provider_remember', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true);
        }
        
        $_SESSION['success'] = 'تم تسجيل الدخول بنجاح!';
        $this->redirect('/provider/dashboard');
    }
    
    /**
     * Provider Registration
     */
    public function register(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/');
        }
        
        $this->requireCsrf();
        
        // Get and sanitize input
        $name = trim($this->postParam('name', ''));
        $email = trim($this->postParam('email', ''));
        $phone = trim($this->postParam('phone', ''));
        $city = trim($this->postParam('city', ''));
        $service_type = trim($this->postParam('service_type', ''));
        $password = $this->postParam('password', '');
        $password_confirm = $this->postParam('password_confirm', '');
        $terms = isset($_POST['terms']);
        $channelJoined = isset($_POST['channel_joined']);
        
        // Validation
        $errors = [];
        
        if (empty($name) || strlen($name) < 3) {
            $errors[] = 'الاسم يجب أن يكون 3 أحرف على الأقل';
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'البريد الإلكتروني غير صحيح';
        }
        
        if (empty($phone) || !preg_match('/^05[0-9]{8}$/', $phone)) {
            $errors[] = 'رقم الهاتف غير صحيح (يجب أن يبدأ بـ 05 ويحتوي على 10 أرقام)';
        }
        
        $allowedCities = ['riyadh', 'jeddah', 'dammam'];
        if (empty($city) || !in_array($city, $allowedCities)) {
            $errors[] = 'يرجى اختيار مدينة صالحة';
        }
        
        if (empty($service_type) || !array_key_exists($service_type, getServiceTypes())) {
            $errors[] = 'نوع الخدمة غير صحيح';
        }
        
        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
        }
        
        if ($password !== $password_confirm) {
            $errors[] = 'كلمتا المرور غير متطابقتين';
        }
        
        if (!$terms) {
            $errors[] = 'يجب الموافقة على شروط الاستخدام';
        }
        
        if (!$channelJoined) {
            $errors[] = 'يجب الانضمام إلى قناة WhatsApp وتأكيد العضوية';
        }
        
        // Check for existing email
        $stmt = $this->db->prepare("SELECT id FROM service_providers WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'البريد الإلكتروني مسجل بالفعل';
        }
        
        // Check for existing phone
        $stmt = $this->db->prepare("SELECT id FROM service_providers WHERE phone = ? LIMIT 1");
        $stmt->execute([$phone]);
        if ($stmt->fetch()) {
            $errors[] = 'رقم الهاتف مسجل بالفعل';
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $this->redirect('/');
        }
        
        // Hash password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $verification_token = bin2hex(random_bytes(32));
        
        try {
            $stmt = $this->db->prepare("
                INSERT INTO service_providers 
                (name, email, phone, city, password_hash, service_type, status, verification_token, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, 'pending', ?, NOW())
            ");
            $stmt->execute([$name, $email, $phone, $city, $password_hash, $service_type, $verification_token]);
            
            $provider_id = $this->db->lastInsertId();
            
            // Auto-login
            $_SESSION['provider_id'] = $provider_id;
            $_SESSION['provider_name'] = $name;
            $_SESSION['provider_email'] = $email;
            $_SESSION['provider_service_type'] = $service_type;
            
            $_SESSION['success'] = 'تم إنشاء الحساب بنجاح! مرحباً بك';
            $this->redirect('/provider/dashboard');
            
        } catch (PDOException $e) {
            error_log("Provider registration error: " . $e->getMessage());
            $_SESSION['error'] = 'حدث خطأ أثناء إنشاء الحساب. يرجى المحاولة مرة أخرى';
            $this->redirect('/');
        }
    }
    
    /**
     * Provider Logout
     */
    public function logout(): void
    {
        // Clear remember token if exists
        if (isset($_SESSION['provider_id'])) {
            $stmt = $this->db->prepare("UPDATE service_providers SET remember_token = NULL WHERE id = ?");
            $stmt->execute([$_SESSION['provider_id']]);
        }
        
        // Delete remember cookie
        if (isset($_COOKIE['provider_remember'])) {
            setcookie('provider_remember', '', time() - 3600, '/', '', true, true);
        }
        
        // Clear session
        unset($_SESSION['provider_id']);
        unset($_SESSION['provider_name']);
        unset($_SESSION['provider_email']);
        unset($_SESSION['provider_service_type']);
        
        $_SESSION['success'] = 'تم تسجيل الخروج بنجاح';
        $this->redirect('/');
    }
}

