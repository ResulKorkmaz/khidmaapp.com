<?php

/**
 * KhidmaApp.com - Provider Auth Controller
 * 
 * Provider giriş/çıkış/kayıt işlemleri
 * 
 * Güvenlik Önlemleri:
 * - Rate limiting (IP bazlı)
 * - E-posta doğrulaması zorunlu
 * - Honeypot bot koruması
 */

require_once __DIR__ . '/BaseProviderController.php';
require_once __DIR__ . '/../../Helpers/EmailVerification.php';
require_once __DIR__ . '/../../Helpers/RateLimiter.php';

class ProviderAuthController extends BaseProviderController 
{
    /**
     * Provider Login
     * 
     * Güvenlik:
     * - Rate limiting: 15 dakikada max 5 deneme
     * - E-posta doğrulanmamış hesaplar giriş yapamaz
     */
    public function login(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/');
        }
        
        $this->requireCsrf();
        
        // 🔒 Rate Limiting
        $rateLimiter = new RateLimiter($this->db);
        if (!$rateLimiter->canAttempt('login')) {
            $_SESSION['error'] = $rateLimiter->getErrorMessage('login');
            $this->redirect('/');
            return;
        }
        
        $identifier = trim($this->postParam('identifier', ''));
        $password = $this->postParam('password', '');
        $remember = isset($_POST['remember']);
        
        if (empty($identifier) || empty($password)) {
            $_SESSION['error'] = 'الرجاء إدخال البريد الإلكتروني/رقم الهاتف وكلمة المرور';
            $this->redirect('/');
            return;
        }
        
        // Find provider by email or phone
        $stmt = $this->db->prepare("SELECT * FROM service_providers WHERE email = ? OR phone = ? LIMIT 1");
        $stmt->execute([$identifier, $identifier]);
        $provider = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$provider) {
            $rateLimiter->recordAttempt('login');
            $_SESSION['error'] = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
            $this->redirect('/');
            return;
        }
        
        if (!password_verify($password, $provider['password_hash'])) {
            $rateLimiter->recordAttempt('login');
            $_SESSION['error'] = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
            $this->redirect('/');
            return;
        }
        
        // 🔒 E-posta doğrulanmamışsa giriş yapılamaz
        if (!$provider['email_verified'] || $provider['status'] === 'unverified') {
            $_SESSION['pending_verification_email'] = $provider['email'];
            $_SESSION['pending_verification_provider_id'] = $provider['id'];
            $_SESSION['error'] = 'يرجى تأكيد بريدك الإلكتروني أولاً للمتابعة';
            $this->redirect('/provider/verify-pending');
            return;
        }
        
        if ($provider['status'] === 'suspended') {
            $_SESSION['error'] = 'حسابك معلق. يرجى الاتصال بالدعم';
            $this->redirect('/');
            return;
        }
        
        if ($provider['status'] === 'rejected') {
            $_SESSION['error'] = 'تم رفض حسابك';
            $this->redirect('/');
            return;
        }
        
        // 🔒 Başarılı giriş - rate limit sıfırla
        $rateLimiter->clearOnSuccess('login');
        
        // Set session
        $_SESSION['provider_id'] = $provider['id'];
        $_SESSION['provider_name'] = $provider['name'];
        $_SESSION['provider_email'] = $provider['email'];
        $_SESSION['provider_service_type'] = $provider['service_type'];
        $_SESSION['email_verified'] = true;
        
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
     * 
     * Güvenlik Önlemleri:
     * - Rate limiting: 60 dakikada max 3 kayıt
     * - Honeypot: Bot koruması
     * - E-posta doğrulaması zorunlu (doğrulanmadan giriş yapılamaz)
     */
    public function register(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/');
        }
        
        $this->requireCsrf();
        
        // 🔒 Rate Limiting - IP bazlı
        $rateLimiter = new RateLimiter($this->db);
        if (!$rateLimiter->canAttempt('registration')) {
            $_SESSION['error'] = $rateLimiter->getErrorMessage('registration');
            $this->redirect('/');
            return;
        }
        
        // 🔒 Honeypot - Bot koruması (gizli alan doldurulmuşsa bot)
        $honeypot = trim($this->postParam('website_url', '')); // Gizli alan
        if (!empty($honeypot)) {
            // Bot tespit edildi, sessizce yönlendir
            error_log("🤖 Bot detected from IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
            sleep(2); // Bot'u yavaşlat
            $this->redirect('/');
            return;
        }
        
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
        
        // Check for existing email (doğrulanmamış hesaplar da dahil)
        $stmt = $this->db->prepare("SELECT id, email_verified FROM service_providers WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $existingByEmail = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($existingByEmail) {
            if (!$existingByEmail['email_verified']) {
                // Doğrulanmamış hesap var - yeniden doğrulama linki gönder
                $_SESSION['pending_verification_email'] = $email;
                $_SESSION['pending_verification_provider_id'] = $existingByEmail['id'];
                $_SESSION['error'] = 'البريد الإلكتروني مسجل بالفعل ولكن غير مفعل. يرجى التحقق من بريدك الإلكتروني أو طلب رابط تأكيد جديد.';
                $this->redirect('/provider/verify-pending');
                return;
            } else {
                $errors[] = 'البريد الإلكتروني مسجل بالفعل';
            }
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
            return;
        }
        
        // 🔒 Rate limit kaydı (başarılı validasyon sonrası)
        $rateLimiter->recordAttempt('registration');
        
        // Hash password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        
        try {
            // Hesap oluştur - status: 'unverified' (e-posta doğrulanana kadar)
            $stmt = $this->db->prepare("
                INSERT INTO service_providers 
                (name, email, phone, city, password_hash, service_type, status, email_verified, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, 'unverified', 0, NOW())
            ");
            $stmt->execute([$name, $email, $phone, $city, $password_hash, $service_type]);
            
            $provider_id = $this->db->lastInsertId();
            
            // E-posta doğrulama e-postası gönder
            $emailVerification = new EmailVerification($this->db);
            $verificationResult = $emailVerification->sendVerificationEmail($provider_id);
            
            // ⚠️ GİRİŞ YAPMA - Doğrulama sayfasına yönlendir
            $_SESSION['pending_verification_email'] = $email;
            $_SESSION['pending_verification_provider_id'] = $provider_id;
            
            if ($verificationResult['success']) {
                $_SESSION['success'] = 'تم إنشاء الحساب! يرجى تأكيد بريدك الإلكتروني للمتابعة. ⚠️ تحقق أيضاً من مجلد الرسائل غير المرغوب فيها (Spam)';
            } else {
                $_SESSION['warning'] = 'تم إنشاء الحساب ولكن فشل إرسال رابط التأكيد. يرجى المحاولة مرة أخرى.';
            }
            
            $this->redirect('/provider/verify-pending');
            
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

