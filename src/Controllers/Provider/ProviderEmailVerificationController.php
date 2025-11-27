<?php
/**
 * KhidmaApp.com - Provider Email Verification Controller
 * 
 * Usta e-posta doğrulama işlemleri
 */

require_once __DIR__ . '/BaseProviderController.php';
require_once __DIR__ . '/../../Helpers/EmailVerification.php';

class ProviderEmailVerificationController extends BaseProviderController 
{
    private EmailVerification $emailVerification;
    
    public function __construct()
    {
        parent::__construct();
        $this->emailVerification = new EmailVerification($this->db);
    }
    
    /**
     * Token ile e-posta doğrula
     */
    public function verify(): void
    {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            $_SESSION['error'] = 'رابط التأكيد غير صالح';
            $this->redirect('/provider/login');
            return;
        }
        
        $result = $this->emailVerification->verifyToken($token);
        
        if ($result['success']) {
            // Hoşgeldin e-postası gönder
            $this->emailVerification->sendWelcomeEmail($result['provider_id']);
            
            $_SESSION['success'] = $result['message'];
            
            // Eğer giriş yapmışsa dashboard'a, değilse login'e yönlendir
            if (isset($_SESSION['provider_id'])) {
                $this->redirect('/provider/dashboard');
            } else {
                $this->redirect('/provider/login');
            }
        } else {
            if (isset($result['expired']) && $result['expired']) {
                // Token süresi dolmuş, yeniden gönderim sayfasına yönlendir
                $_SESSION['verification_provider_id'] = $result['provider_id'];
                $_SESSION['error'] = $result['message'];
                $this->redirect('/provider/resend-verification');
            } else {
                $_SESSION['error'] = $result['message'];
                $this->redirect('/provider/login');
            }
        }
    }
    
    /**
     * Doğrulama e-postası yeniden gönder (giriş yapmış kullanıcı için)
     */
    public function resend(): void
    {
        $this->requireAuth();
        
        $providerId = $this->getProviderId();
        $result = $this->emailVerification->sendVerificationEmail($providerId);
        
        if ($this->isAjax()) {
            if ($result['success']) {
                $this->successResponse($result['message']);
            } else {
                $this->errorResponse($result['message'], 400);
            }
        } else {
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
            $this->redirect('/provider/dashboard');
        }
    }
    
    /**
     * Yeniden gönderim sayfası (giriş yapmamış, token süresi dolmuş kullanıcılar için)
     */
    public function resendPage(): void
    {
        $providerId = $_SESSION['verification_provider_id'] ?? null;
        
        if (!$providerId) {
            $this->redirect('/provider/login');
            return;
        }
        
        // POST isteği - yeniden gönder
        if ($this->isPost()) {
            $result = $this->emailVerification->sendVerificationEmail($providerId);
            
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
                unset($_SESSION['verification_provider_id']);
                $this->redirect('/provider/login');
            } else {
                $_SESSION['error'] = $result['message'];
                $this->redirect('/provider/resend-verification');
            }
            return;
        }
        
        // GET isteği - sayfa göster
        $this->render('email_verification/resend', [
            'providerId' => $providerId
        ]);
    }
    
    /**
     * Doğrulama durumu kontrol (AJAX)
     */
    public function status(): void
    {
        $this->requireAuth();
        
        $provider = $this->getProvider();
        
        $this->successResponse('Durum alındı', [
            'email_verified' => (bool)$provider['email_verified'],
            'email' => $provider['email']
        ]);
    }
    
    /**
     * AJAX isteği mi kontrol et
     */
    private function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}

