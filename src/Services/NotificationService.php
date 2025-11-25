<?php
/**
 * KhidmaApp.com - Notification Service
 * 
 * Email, SMS ve WhatsApp bildirimleri için merkezi servis.
 * PHPMailer ile SMTP email gönderimi destekler.
 * 
 * @package KhidmaApp
 * @since 1.0.0
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class NotificationService 
{
    private $pdo;
    private $mailer;
    private $emailEnabled = false;
    
    // Notification types
    const TYPE_EMAIL = 'email';
    const TYPE_SMS = 'sms';
    const TYPE_WHATSAPP = 'whatsapp';
    
    // Notification templates
    const TEMPLATE_NEW_LEAD = 'new_lead';
    const TEMPLATE_LEAD_STATUS = 'lead_status';
    const TEMPLATE_WELCOME = 'welcome';
    const TEMPLATE_PROVIDER_APPROVED = 'provider_approved';
    const TEMPLATE_PROVIDER_REJECTED = 'provider_rejected';
    const TEMPLATE_NEW_PURCHASE = 'new_purchase';
    const TEMPLATE_PASSWORD_RESET = 'password_reset';
    const TEMPLATE_LEAD_DELIVERED = 'lead_delivered';
    
    public function __construct($pdo = null) 
    {
        $this->pdo = $pdo ?? getDatabase();
        $this->initMailer();
    }
    
    /**
     * PHPMailer'ı başlat
     */
    private function initMailer(): void
    {
        // Autoload kontrolü
        $autoloadPath = __DIR__ . '/../../vendor/autoload.php';
        if (!file_exists($autoloadPath)) {
            error_log("NotificationService: vendor/autoload.php not found");
            return;
        }
        
        require_once $autoloadPath;
        
        // Email ayarları kontrolü
        $mailHost = env('MAIL_HOST');
        $mailUsername = env('MAIL_USERNAME');
        $mailPassword = env('MAIL_PASSWORD');
        
        if (empty($mailHost) || empty($mailUsername) || empty($mailPassword)) {
            error_log("NotificationService: Email settings not configured in .env");
            return;
        }
        
        try {
            $this->mailer = new PHPMailer(true);
            
            // SMTP ayarları
            $this->mailer->isSMTP();
            $this->mailer->Host = $mailHost;
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $mailUsername;
            $this->mailer->Password = $mailPassword;
            $this->mailer->SMTPSecure = env('MAIL_ENCRYPTION', 'tls') === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = (int) env('MAIL_PORT', 587);
            
            // Karakter kodlaması
            $this->mailer->CharSet = 'UTF-8';
            $this->mailer->Encoding = 'base64';
            
            // Gönderen bilgileri
            $this->mailer->setFrom(
                env('MAIL_FROM_ADDRESS', 'noreply@khidmaapp.com'),
                env('MAIL_FROM_NAME', 'KhidmaApp')
            );
            
            // Debug modu (development için)
            if (env('APP_DEBUG') === 'true' && env('MAIL_DEBUG') === 'true') {
                $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
            }
            
            $this->emailEnabled = true;
            
        } catch (Exception $e) {
            error_log("NotificationService: Mailer init error: " . $e->getMessage());
            $this->emailEnabled = false;
        }
    }
    
    /**
     * Email gönderebiliyor mu?
     */
    public function isEmailEnabled(): bool
    {
        return $this->emailEnabled;
    }
    
    // ==========================================
    // PUBLIC NOTIFICATION METHODS
    // ==========================================
    
    /**
     * Yeni lead bildirimi gönder (Admin'e)
     */
    public function sendNewLeadNotification(int $leadId, array $leadData): bool
    {
        $adminEmail = env('ADMIN_EMAIL');
        if (empty($adminEmail)) {
            error_log("NotificationService: ADMIN_EMAIL not set");
            return false;
        }
        
        $services = getServiceTypes();
        $cities = getCities();
        
        $serviceName = $services[$leadData['service_type']]['ar'] ?? $leadData['service_type'];
        $cityName = $cities[$leadData['city']]['ar'] ?? $leadData['city'];
        
        $subject = "🔔 طلب جديد - {$serviceName} في {$cityName}";
        
        $body = $this->renderTemplate('new_lead', [
            'lead_id' => $leadId,
            'service_name' => $serviceName,
            'city_name' => $cityName,
            'customer_name' => $leadData['customer_name'] ?? 'غير محدد',
            'phone' => $leadData['phone'] ?? '',
            'description' => $leadData['description'] ?? '',
            'created_at' => date('Y-m-d H:i'),
            'admin_url' => env('SITE_URL', 'http://localhost:8000') . '/admin/leads'
        ]);
        
        $result = $this->sendEmail($adminEmail, $subject, $body);
        
        // Log notification
        $this->logNotification(
            self::TYPE_EMAIL,
            self::TEMPLATE_NEW_LEAD,
            $leadId,
            $subject,
            $adminEmail,
            $result ? 'sent' : 'failed'
        );
        
        return $result;
    }
    
    /**
     * Provider onay bildirimi
     */
    public function sendProviderApprovedNotification(array $provider): bool
    {
        if (empty($provider['email'])) {
            return false;
        }
        
        $subject = "✅ تم قبول طلبك - KhidmaApp";
        
        $body = $this->renderTemplate('provider_approved', [
            'name' => $provider['name'],
            'email' => $provider['email'],
            'service_type' => $provider['service_type'],
            'login_url' => env('SITE_URL', 'http://localhost:8000') . '/provider/login',
            'dashboard_url' => env('SITE_URL', 'http://localhost:8000') . '/provider/dashboard'
        ]);
        
        $result = $this->sendEmail($provider['email'], $subject, $body);
        
        $this->logNotification(
            self::TYPE_EMAIL,
            self::TEMPLATE_PROVIDER_APPROVED,
            null,
            $subject,
            $provider['email'],
            $result ? 'sent' : 'failed'
        );
        
        return $result;
    }
    
    /**
     * Provider red bildirimi
     */
    public function sendProviderRejectedNotification(array $provider, ?string $reason = null): bool
    {
        if (empty($provider['email'])) {
            return false;
        }
        
        $subject = "❌ تم رفض طلبك - KhidmaApp";
        
        $body = $this->renderTemplate('provider_rejected', [
            'name' => $provider['name'],
            'reason' => $reason ?? 'لم يتم استيفاء الشروط المطلوبة',
            'contact_email' => env('CONTACT_EMAIL', 'info@khidmaapp.com')
        ]);
        
        $result = $this->sendEmail($provider['email'], $subject, $body);
        
        $this->logNotification(
            self::TYPE_EMAIL,
            self::TEMPLATE_PROVIDER_REJECTED,
            null,
            $subject,
            $provider['email'],
            $result ? 'sent' : 'failed'
        );
        
        return $result;
    }
    
    /**
     * Yeni satın alma bildirimi (Admin'e)
     */
    public function sendNewPurchaseNotification(array $purchase, array $provider): bool
    {
        $adminEmail = env('ADMIN_EMAIL');
        if (empty($adminEmail)) {
            return false;
        }
        
        $subject = "💰 عملية شراء جديدة - {$provider['name']}";
        
        $body = $this->renderTemplate('new_purchase', [
            'provider_name' => $provider['name'],
            'provider_phone' => $provider['phone'],
            'package_name' => $purchase['package_name'] ?? 'غير محدد',
            'leads_count' => $purchase['leads_count'] ?? 0,
            'price' => $purchase['price_paid'] ?? 0,
            'currency' => 'SAR',
            'purchased_at' => date('Y-m-d H:i'),
            'admin_url' => env('SITE_URL', 'http://localhost:8000') . '/admin/purchases'
        ]);
        
        $result = $this->sendEmail($adminEmail, $subject, $body);
        
        $this->logNotification(
            self::TYPE_EMAIL,
            self::TEMPLATE_NEW_PURCHASE,
            null,
            $subject,
            $adminEmail,
            $result ? 'sent' : 'failed'
        );
        
        return $result;
    }
    
    /**
     * Lead teslim bildirimi (Provider'a)
     */
    public function sendLeadDeliveredNotification(array $lead, array $provider): bool
    {
        if (empty($provider['email'])) {
            return false;
        }
        
        $services = getServiceTypes();
        $cities = getCities();
        
        $serviceName = $services[$lead['service_type']]['ar'] ?? $lead['service_type'];
        $cityName = $cities[$lead['city']]['ar'] ?? $lead['city'];
        
        $subject = "📋 طلب جديد لك - {$serviceName}";
        
        $body = $this->renderTemplate('lead_delivered', [
            'provider_name' => $provider['name'],
            'service_name' => $serviceName,
            'city_name' => $cityName,
            'customer_name' => $lead['customer_name'] ?? 'العميل',
            'dashboard_url' => env('SITE_URL', 'http://localhost:8000') . '/provider/leads'
        ]);
        
        $result = $this->sendEmail($provider['email'], $subject, $body);
        
        $this->logNotification(
            self::TYPE_EMAIL,
            self::TEMPLATE_LEAD_DELIVERED,
            $lead['id'] ?? null,
            $subject,
            $provider['email'],
            $result ? 'sent' : 'failed'
        );
        
        return $result;
    }
    
    /**
     * Şifre sıfırlama emaili
     */
    public function sendPasswordResetEmail(string $email, string $resetToken, string $userType = 'provider'): bool
    {
        $resetUrl = env('SITE_URL', 'http://localhost:8000') . "/{$userType}/reset-password?token={$resetToken}";
        
        $subject = "🔐 إعادة تعيين كلمة المرور - KhidmaApp";
        
        $body = $this->renderTemplate('password_reset', [
            'reset_url' => $resetUrl,
            'expire_hours' => 24
        ]);
        
        $result = $this->sendEmail($email, $subject, $body);
        
        $this->logNotification(
            self::TYPE_EMAIL,
            self::TEMPLATE_PASSWORD_RESET,
            null,
            $subject,
            $email,
            $result ? 'sent' : 'failed'
        );
        
        return $result;
    }
    
    /**
     * Hoş geldin emaili (yeni provider kaydı)
     */
    public function sendWelcomeEmail(array $provider): bool
    {
        if (empty($provider['email'])) {
            return false;
        }
        
        $subject = "🎉 مرحباً بك في KhidmaApp";
        
        $body = $this->renderTemplate('welcome', [
            'name' => $provider['name'],
            'email' => $provider['email'],
            'whatsapp_channel' => env('WHATSAPP_CHANNEL_URL', '#')
        ]);
        
        $result = $this->sendEmail($provider['email'], $subject, $body);
        
        $this->logNotification(
            self::TYPE_EMAIL,
            self::TEMPLATE_WELCOME,
            null,
            $subject,
            $provider['email'],
            $result ? 'sent' : 'failed'
        );
        
        return $result;
    }
    
    // ==========================================
    // EMAIL SENDING
    // ==========================================
    
    /**
     * Email gönder
     */
    public function sendEmail(string $to, string $subject, string $body, bool $isHtml = true): bool
    {
        if (!$this->emailEnabled || !$this->mailer) {
            error_log("NotificationService: Email not enabled, logging only - To: {$to}, Subject: {$subject}");
            return false;
        }
        
        try {
            // Her gönderimde temizle
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            
            $this->mailer->addAddress($to);
            $this->mailer->isHTML($isHtml);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            
            // Plain text alternatif
            if ($isHtml) {
                $this->mailer->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $body));
            }
            
            $this->mailer->send();
            
            error_log("NotificationService: Email sent successfully to {$to}");
            return true;
            
        } catch (Exception $e) {
            error_log("NotificationService: Email send error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Toplu email gönder
     */
    public function sendBulkEmail(array $recipients, string $subject, string $body): array
    {
        $results = [];
        
        foreach ($recipients as $email) {
            $results[$email] = $this->sendEmail($email, $subject, $body);
        }
        
        return $results;
    }
    
    // ==========================================
    // EMAIL TEMPLATES
    // ==========================================
    
    /**
     * Email template'ini render et
     */
    private function renderTemplate(string $template, array $data = []): string
    {
        $templatePath = __DIR__ . '/../Views/emails/' . $template . '.php';
        
        // Template dosyası varsa kullan
        if (file_exists($templatePath)) {
            ob_start();
            extract($data);
            include $templatePath;
            return ob_get_clean();
        }
        
        // Yoksa inline template kullan
        return $this->getInlineTemplate($template, $data);
    }
    
    /**
     * Inline email template'leri
     */
    private function getInlineTemplate(string $template, array $data): string
    {
        $siteName = 'KhidmaApp';
        $siteUrl = env('SITE_URL', 'http://localhost:8000');
        $primaryColor = '#3B9DD9';
        
        $header = "
        <div style='background: linear-gradient(135deg, #1E5A8A 0%, #3B9DD9 100%); padding: 30px; text-align: center;'>
            <h1 style='color: white; margin: 0; font-size: 28px;'>{$siteName}</h1>
            <p style='color: rgba(255,255,255,0.9); margin: 10px 0 0 0; font-size: 14px;'>منصة ربط مقدمي الخدمات</p>
        </div>";
        
        $footer = "
        <div style='background: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #e9ecef;'>
            <p style='color: #6c757d; margin: 0; font-size: 12px;'>
                © " . date('Y') . " {$siteName}. جميع الحقوق محفوظة.
            </p>
            <p style='color: #6c757d; margin: 10px 0 0 0; font-size: 12px;'>
                <a href='{$siteUrl}' style='color: {$primaryColor};'>{$siteUrl}</a>
            </p>
        </div>";
        
        $wrapper = function($content) use ($header, $footer) {
            return "
            <!DOCTYPE html>
            <html dir='rtl' lang='ar'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            </head>
            <body style='margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;'>
                <div style='max-width: 600px; margin: 20px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);'>
                    {$header}
                    <div style='padding: 30px;'>
                        {$content}
                    </div>
                    {$footer}
                </div>
            </body>
            </html>";
        };
        
        switch ($template) {
            case 'new_lead':
                $content = "
                <h2 style='color: #1E5A8A; margin-top: 0;'>🔔 طلب جديد!</h2>
                <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                    <p style='margin: 5px 0;'><strong>الخدمة:</strong> {$data['service_name']}</p>
                    <p style='margin: 5px 0;'><strong>المدينة:</strong> {$data['city_name']}</p>
                    <p style='margin: 5px 0;'><strong>العميل:</strong> {$data['customer_name']}</p>
                    <p style='margin: 5px 0;'><strong>الهاتف:</strong> {$data['phone']}</p>
                    " . (!empty($data['description']) ? "<p style='margin: 5px 0;'><strong>الوصف:</strong> {$data['description']}</p>" : "") . "
                </div>
                <a href='{$data['admin_url']}' style='display: inline-block; background: {$primaryColor}; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold;'>عرض في لوحة التحكم</a>";
                break;
                
            case 'provider_approved':
                $content = "
                <h2 style='color: #28a745; margin-top: 0;'>✅ تهانينا {$data['name']}!</h2>
                <p style='font-size: 16px; line-height: 1.6;'>تم قبول طلب انضمامك إلى منصة KhidmaApp. يمكنك الآن تسجيل الدخول والبدء في استقبال طلبات العملاء.</p>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$data['dashboard_url']}' style='display: inline-block; background: {$primaryColor}; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px;'>الذهاب إلى لوحة التحكم</a>
                </div>
                <p style='color: #6c757d; font-size: 14px;'>إذا كان لديك أي استفسار، لا تتردد في التواصل معنا.</p>";
                break;
                
            case 'provider_rejected':
                $content = "
                <h2 style='color: #dc3545; margin-top: 0;'>عذراً {$data['name']}</h2>
                <p style='font-size: 16px; line-height: 1.6;'>نأسف لإبلاغك بأنه تم رفض طلب انضمامك إلى منصة KhidmaApp.</p>
                <div style='background: #fff3cd; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                    <p style='margin: 0; color: #856404;'><strong>السبب:</strong> {$data['reason']}</p>
                </div>
                <p style='font-size: 14px;'>للمزيد من المعلومات، يمكنك التواصل معنا على: <a href='mailto:{$data['contact_email']}' style='color: {$primaryColor};'>{$data['contact_email']}</a></p>";
                break;
                
            case 'new_purchase':
                $content = "
                <h2 style='color: #28a745; margin-top: 0;'>💰 عملية شراء جديدة!</h2>
                <div style='background: #d4edda; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                    <p style='margin: 5px 0;'><strong>مقدم الخدمة:</strong> {$data['provider_name']}</p>
                    <p style='margin: 5px 0;'><strong>الهاتف:</strong> {$data['provider_phone']}</p>
                    <p style='margin: 5px 0;'><strong>الباقة:</strong> {$data['package_name']}</p>
                    <p style='margin: 5px 0;'><strong>عدد الطلبات:</strong> {$data['leads_count']}</p>
                    <p style='margin: 5px 0; font-size: 18px;'><strong>المبلغ:</strong> {$data['price']} {$data['currency']}</p>
                </div>
                <a href='{$data['admin_url']}' style='display: inline-block; background: {$primaryColor}; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold;'>عرض التفاصيل</a>";
                break;
                
            case 'lead_delivered':
                $content = "
                <h2 style='color: #1E5A8A; margin-top: 0;'>📋 مرحباً {$data['provider_name']}!</h2>
                <p style='font-size: 16px; line-height: 1.6;'>لديك طلب جديد من عميل يبحث عن خدمة <strong>{$data['service_name']}</strong> في <strong>{$data['city_name']}</strong>.</p>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$data['dashboard_url']}' style='display: inline-block; background: {$primaryColor}; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold;'>عرض تفاصيل الطلب</a>
                </div>
                <p style='color: #6c757d; font-size: 14px;'>سارع بالتواصل مع العميل للحصول على العمل!</p>";
                break;
                
            case 'password_reset':
                $content = "
                <h2 style='color: #1E5A8A; margin-top: 0;'>🔐 إعادة تعيين كلمة المرور</h2>
                <p style='font-size: 16px; line-height: 1.6;'>لقد طلبت إعادة تعيين كلمة المرور الخاصة بك. انقر على الزر أدناه لإنشاء كلمة مرور جديدة.</p>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$data['reset_url']}' style='display: inline-block; background: {$primaryColor}; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold;'>إعادة تعيين كلمة المرور</a>
                </div>
                <p style='color: #dc3545; font-size: 14px;'>⚠️ هذا الرابط صالح لمدة {$data['expire_hours']} ساعة فقط.</p>
                <p style='color: #6c757d; font-size: 14px;'>إذا لم تطلب إعادة تعيين كلمة المرور، يمكنك تجاهل هذا البريد.</p>";
                break;
                
            case 'welcome':
                $content = "
                <h2 style='color: #1E5A8A; margin-top: 0;'>🎉 مرحباً بك {$data['name']}!</h2>
                <p style='font-size: 16px; line-height: 1.6;'>شكراً لتسجيلك في منصة KhidmaApp. طلبك قيد المراجعة وسنقوم بإعلامك فور الموافقة عليه.</p>
                <div style='background: #d1ecf1; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                    <p style='margin: 0; color: #0c5460;'><strong>📱 مهم:</strong> لا تنسَ الانضمام إلى قناتنا على WhatsApp لتلقي الطلبات!</p>
                    <a href='{$data['whatsapp_channel']}' style='display: inline-block; background: #25D366; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 10px;'>انضم للقناة</a>
                </div>";
                break;
                
            default:
                $content = "<p>Notification content</p>";
        }
        
        return $wrapper($content);
    }
    
    // ==========================================
    // LOGGING & HISTORY
    // ==========================================
    
    /**
     * Bildirimi logla
     */
    private function logNotification(string $type, string $template, ?int $leadId, string $message, ?string $recipient, string $status = 'pending'): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            // Tablo yoksa oluştur
            $this->ensureNotificationsTable();
            
            $stmt = $this->pdo->prepare("
                INSERT INTO notifications (type, template, lead_id, recipient, message, status, sent_at, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            return $stmt->execute([
                $type,
                $template,
                $leadId,
                $recipient,
                $message,
                $status,
                $status === 'sent' ? date('Y-m-d H:i:s') : null
            ]);
            
        } catch (Exception $e) {
            error_log("NotificationService: Log error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Notifications tablosunu oluştur
     */
    private function ensureNotificationsTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS notifications (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            type VARCHAR(20) NOT NULL,
            template VARCHAR(50) NOT NULL,
            lead_id INT UNSIGNED NULL,
            recipient VARCHAR(100) NULL,
            message TEXT NOT NULL,
            status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
            sent_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_lead_id (lead_id),
            INDEX idx_status (status),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->pdo->exec($sql);
    }
    
    /**
     * Bildirim geçmişini getir
     */
    public function getNotificationHistory(?int $leadId = null, int $limit = 50): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $sql = "SELECT * FROM notifications";
            $params = [];
            
            if ($leadId) {
                $sql .= " WHERE lead_id = ?";
                $params[] = $leadId;
            }
            
            $sql .= " ORDER BY created_at DESC LIMIT ?";
            $params[] = $limit;
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("NotificationService: History error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Bekleyen bildirimleri getir
     */
    public function getPendingNotifications(int $limit = 100): array
    {
        if (!$this->pdo) {
            return [];
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM notifications 
                WHERE status = 'pending' 
                ORDER BY created_at ASC 
                LIMIT ?
            ");
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("NotificationService: Pending error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Bildirimi gönderildi olarak işaretle
     */
    public function markAsSent(int $notificationId): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                UPDATE notifications 
                SET status = 'sent', sent_at = NOW() 
                WHERE id = ?
            ");
            return $stmt->execute([$notificationId]);
            
        } catch (Exception $e) {
            error_log("NotificationService: Mark sent error: " . $e->getMessage());
            return false;
        }
    }
}
