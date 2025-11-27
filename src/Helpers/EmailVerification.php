<?php
/**
 * KhidmaApp.com - Email Verification System
 * 
 * E-posta doğrulama token yönetimi
 */

require_once __DIR__ . '/EmailService.php';

class EmailVerification
{
    private PDO $db;
    private EmailService $emailService;
    
    // Token geçerlilik süresi (24 saat)
    const TOKEN_EXPIRY_HOURS = 24;
    
    // Yeniden gönderim bekleme süresi (dakika)
    const RESEND_COOLDOWN_MINUTES = 2;
    
    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->emailService = new EmailService();
    }
    
    /**
     * Güvenli doğrulama token'ı oluştur
     */
    public function generateToken(): string
    {
        return bin2hex(random_bytes(32)); // 64 karakter hex
    }
    
    /**
     * Doğrulama e-postası gönder
     */
    public function sendVerificationEmail(int $providerId): array
    {
        try {
            // Provider bilgilerini al
            $stmt = $this->db->prepare("
                SELECT id, name, email, email_verified, verification_token, verification_sent_at 
                FROM service_providers 
                WHERE id = ?
            ");
            $stmt->execute([$providerId]);
            $provider = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$provider) {
                return ['success' => false, 'message' => 'مقدم الخدمة غير موجود'];
            }
            
            if ($provider['email_verified']) {
                return ['success' => false, 'message' => 'البريد الإلكتروني مفعل بالفعل'];
            }
            
            // Cooldown kontrolü
            if ($provider['verification_sent_at']) {
                $sentAt = strtotime($provider['verification_sent_at']);
                $cooldownEnd = $sentAt + (self::RESEND_COOLDOWN_MINUTES * 60);
                
                if (time() < $cooldownEnd) {
                    $remainingSeconds = $cooldownEnd - time();
                    $remainingMinutes = ceil($remainingSeconds / 60);
                    return [
                        'success' => false, 
                        'message' => "يرجى الانتظار {$remainingMinutes} دقيقة قبل إعادة الإرسال",
                        'cooldown' => $remainingSeconds
                    ];
                }
            }
            
            // Yeni token oluştur
            $token = $this->generateToken();
            $expiresAt = date('Y-m-d H:i:s', strtotime('+' . self::TOKEN_EXPIRY_HOURS . ' hours'));
            
            // Token'ı kaydet
            $stmt = $this->db->prepare("
                UPDATE service_providers 
                SET verification_token = ?, 
                    verification_token_expires = ?,
                    verification_sent_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$token, $expiresAt, $providerId]);
            
            // Doğrulama URL'i oluştur
            $verificationUrl = APP_URL . '/provider/verify-email?token=' . $token;
            
            // E-posta gönder
            $htmlBody = $this->getVerificationEmailTemplate($provider['name'], $verificationUrl);
            $subject = 'تأكيد البريد الإلكتروني - KhidmaApp';
            
            $sent = $this->emailService->send($provider['email'], $subject, $htmlBody);
            
            if ($sent) {
                return [
                    'success' => true, 
                    'message' => 'تم إرسال رابط التأكيد إلى بريدك الإلكتروني'
                ];
            } else {
                return [
                    'success' => false, 
                    'message' => 'فشل إرسال البريد الإلكتروني. يرجى المحاولة لاحقاً'
                ];
            }
            
        } catch (Exception $e) {
            error_log("Email verification error: " . $e->getMessage());
            return ['success' => false, 'message' => 'حدث خطأ. يرجى المحاولة لاحقاً'];
        }
    }
    
    /**
     * Token'ı doğrula
     */
    public function verifyToken(string $token): array
    {
        try {
            // Token'ı bul
            $stmt = $this->db->prepare("
                SELECT id, name, email, email_verified, verification_token_expires 
                FROM service_providers 
                WHERE verification_token = ?
            ");
            $stmt->execute([$token]);
            $provider = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$provider) {
                return ['success' => false, 'message' => 'رابط التأكيد غير صالح'];
            }
            
            if ($provider['email_verified']) {
                return ['success' => false, 'message' => 'البريد الإلكتروني مفعل بالفعل'];
            }
            
            // Token süresi dolmuş mu?
            if (strtotime($provider['verification_token_expires']) < time()) {
                return [
                    'success' => false, 
                    'message' => 'انتهت صلاحية رابط التأكيد. يرجى طلب رابط جديد',
                    'expired' => true,
                    'provider_id' => $provider['id']
                ];
            }
            
            // E-postayı doğrula ve hesabı aktif et
            $stmt = $this->db->prepare("
                UPDATE service_providers 
                SET email_verified = 1, 
                    email_verified_at = NOW(),
                    verification_token = NULL,
                    verification_token_expires = NULL,
                    status = CASE WHEN status = 'unverified' THEN 'pending' ELSE status END
                WHERE id = ?
            ");
            $stmt->execute([$provider['id']]);
            
            return [
                'success' => true, 
                'message' => 'تم تأكيد بريدك الإلكتروني بنجاح!',
                'provider_id' => $provider['id'],
                'provider_name' => $provider['name']
            ];
            
        } catch (Exception $e) {
            error_log("Token verification error: " . $e->getMessage());
            return ['success' => false, 'message' => 'حدث خطأ. يرجى المحاولة لاحقاً'];
        }
    }
    
    /**
     * E-posta değişikliğinde yeniden doğrulama
     */
    public function handleEmailChange(int $providerId, string $newEmail): array
    {
        try {
            // E-posta benzersiz mi?
            $stmt = $this->db->prepare("SELECT id FROM service_providers WHERE email = ? AND id != ?");
            $stmt->execute([$newEmail, $providerId]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'هذا البريد الإلكتروني مستخدم بالفعل'];
            }
            
            // E-postayı güncelle ve doğrulamayı sıfırla
            $stmt = $this->db->prepare("
                UPDATE service_providers 
                SET email = ?, 
                    email_verified = 0,
                    email_verified_at = NULL,
                    verification_token = NULL,
                    verification_token_expires = NULL,
                    verification_sent_at = NULL
                WHERE id = ?
            ");
            $stmt->execute([$newEmail, $providerId]);
            
            // Yeni doğrulama e-postası gönder
            return $this->sendVerificationEmail($providerId);
            
        } catch (Exception $e) {
            error_log("Email change error: " . $e->getMessage());
            return ['success' => false, 'message' => 'حدث خطأ. يرجى المحاولة لاحقاً'];
        }
    }
    
    /**
     * Doğrulama e-postası HTML şablonu
     */
    private function getVerificationEmailTemplate(string $name, string $verificationUrl): string
    {
        $expiryHours = self::TOKEN_EXPIRY_HOURS;
        
        return <<<HTML
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد البريد الإلكتروني</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa; direction: rtl;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f7fa; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: bold;">KhidmaApp</h1>
                            <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0; font-size: 14px;">منصة الخدمات الموثوقة</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #1f2937; margin: 0 0 20px; font-size: 24px; text-align: center;">
                                مرحباً {$name}! 👋
                            </h2>
                            
                            <p style="color: #4b5563; font-size: 16px; line-height: 1.8; margin: 0 0 25px; text-align: center;">
                                شكراً لتسجيلك في KhidmaApp. يرجى تأكيد بريدك الإلكتروني للبدء في استقبال طلبات العملاء.
                            </p>
                            
                            <!-- Button -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 20px 0;">
                                        <a href="{$verificationUrl}" 
                                           style="display: inline-block; background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 12px; font-size: 18px; font-weight: bold; box-shadow: 0 4px 14px rgba(5, 150, 105, 0.4);">
                                            ✓ تأكيد البريد الإلكتروني
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Alternative Link -->
                            <p style="color: #6b7280; font-size: 13px; line-height: 1.6; margin: 25px 0 0; text-align: center;">
                                إذا لم يعمل الزر أعلاه، انسخ الرابط التالي والصقه في المتصفح:
                            </p>
                            <p style="background-color: #f3f4f6; padding: 12px; border-radius: 8px; word-break: break-all; font-size: 12px; color: #059669; margin: 10px 0 0; text-align: center;">
                                {$verificationUrl}
                            </p>
                            
                            <!-- Expiry Notice -->
                            <div style="background-color: #fef3c7; border-right: 4px solid #f59e0b; padding: 15px; border-radius: 8px; margin-top: 25px;">
                                <p style="color: #92400e; font-size: 14px; margin: 0;">
                                    ⏰ <strong>تنبيه:</strong> صلاحية هذا الرابط {$expiryHours} ساعة فقط.
                                </p>
                            </div>
                            
                            <!-- Spam Notice -->
                            <div style="background-color: #f3f4f6; border-right: 4px solid #6b7280; padding: 15px; border-radius: 8px; margin-top: 15px;">
                                <p style="color: #4b5563; font-size: 13px; margin: 0;">
                                    📧 <strong>ملاحظة:</strong> إذا لم تجد هذه الرسالة في صندوق الوارد، يرجى التحقق من مجلد الرسائل غير المرغوب فيها (Spam).
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 25px 30px; border-top: 1px solid #e5e7eb;">
                            <p style="color: #9ca3af; font-size: 12px; margin: 0; text-align: center;">
                                إذا لم تقم بإنشاء حساب في KhidmaApp، يمكنك تجاهل هذا البريد.
                            </p>
                            <p style="color: #9ca3af; font-size: 12px; margin: 10px 0 0; text-align: center;">
                                © 2024 KhidmaApp. جميع الحقوق محفوظة.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
    }
    
    /**
     * Hoşgeldin e-postası gönder (doğrulama sonrası)
     */
    public function sendWelcomeEmail(int $providerId): bool
    {
        try {
            $stmt = $this->db->prepare("SELECT name, email FROM service_providers WHERE id = ?");
            $stmt->execute([$providerId]);
            $provider = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$provider) return false;
            
            $htmlBody = $this->getWelcomeEmailTemplate($provider['name']);
            $subject = 'مرحباً بك في KhidmaApp! 🎉';
            
            return $this->emailService->send($provider['email'], $subject, $htmlBody);
            
        } catch (Exception $e) {
            error_log("Welcome email error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Hoşgeldin e-postası şablonu
     */
    private function getWelcomeEmailTemplate(string $name): string
    {
        $dashboardUrl = APP_URL . '/provider/dashboard';
        $packagesUrl = APP_URL . '/provider/browse-packages';
        
        return <<<HTML
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مرحباً بك في KhidmaApp</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa; direction: rtl;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f7fa; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); padding: 40px 30px; text-align: center;">
                            <div style="font-size: 48px; margin-bottom: 15px;">🎉</div>
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: bold;">مرحباً بك في KhidmaApp!</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #1f2937; margin: 0 0 20px; font-size: 22px;">
                                أهلاً {$name}! 👋
                            </h2>
                            
                            <p style="color: #4b5563; font-size: 16px; line-height: 1.8; margin: 0 0 25px;">
                                تم تفعيل حسابك بنجاح! أنت الآن جاهز لاستقبال طلبات العملاء وتنمية أعمالك.
                            </p>
                            
                            <!-- Steps -->
                            <div style="background-color: #f0fdf4; border-radius: 12px; padding: 25px; margin-bottom: 25px;">
                                <h3 style="color: #166534; margin: 0 0 15px; font-size: 18px;">الخطوات التالية:</h3>
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="padding: 10px 0; border-bottom: 1px solid #dcfce7;">
                                            <span style="background-color: #059669; color: white; width: 24px; height: 24px; border-radius: 50%; display: inline-block; text-align: center; line-height: 24px; font-size: 14px; margin-left: 10px;">1</span>
                                            <span style="color: #166534; font-size: 15px;">أكمل ملفك الشخصي</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 0; border-bottom: 1px solid #dcfce7;">
                                            <span style="background-color: #059669; color: white; width: 24px; height: 24px; border-radius: 50%; display: inline-block; text-align: center; line-height: 24px; font-size: 14px; margin-left: 10px;">2</span>
                                            <span style="color: #166534; font-size: 15px;">اشترِ حزمة طلبات</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 0;">
                                            <span style="background-color: #059669; color: white; width: 24px; height: 24px; border-radius: 50%; display: inline-block; text-align: center; line-height: 24px; font-size: 14px; margin-left: 10px;">3</span>
                                            <span style="color: #166534; font-size: 15px;">ابدأ باستقبال الطلبات!</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            <!-- Buttons -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 10px;">
                                        <a href="{$dashboardUrl}" 
                                           style="display: inline-block; background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: #ffffff; text-decoration: none; padding: 14px 30px; border-radius: 10px; font-size: 16px; font-weight: bold;">
                                            لوحة التحكم
                                        </a>
                                    </td>
                                    <td align="center" style="padding: 10px;">
                                        <a href="{$packagesUrl}" 
                                           style="display: inline-block; background-color: #f3f4f6; color: #374151; text-decoration: none; padding: 14px 30px; border-radius: 10px; font-size: 16px; font-weight: bold; border: 2px solid #e5e7eb;">
                                            شراء حزمة
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 25px 30px; border-top: 1px solid #e5e7eb;">
                            <p style="color: #6b7280; font-size: 13px; margin: 0; text-align: center;">
                                هل لديك أسئلة؟ تواصل معنا عبر البريد الإلكتروني
                            </p>
                            <p style="color: #9ca3af; font-size: 12px; margin: 10px 0 0; text-align: center;">
                                © 2024 KhidmaApp. جميع الحقوق محفوظة.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
    }
}

