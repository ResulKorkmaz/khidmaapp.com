<?php

/**
 * KhidmaApp.com - Provider Controller (DEPRECATED)
 * 
 * ⚠️ BU DOSYA ARTIK KULLANILMIYOR!
 * 
 * Tüm provider işlevleri aşağıdaki modüler controller'lara taşındı:
 * 
 * - ProviderAuthController      : Giriş/çıkış/kayıt işlemleri
 * - ProviderDashboardController : Dashboard
 * - ProviderLeadController      : Lead yönetimi (görüntüleme, talep, gizleme)
 * - ProviderProfileController   : Profil ve ayar yönetimi
 * - ProviderPurchaseController  : Paket satın alma işlemleri
 * - ProviderMessageController   : Mesaj yönetimi
 * 
 * @deprecated Bu dosya geriye dönük uyumluluk için tutulmaktadır.
 *             Yeni geliştirmeler için src/Controllers/Provider/ klasörünü kullanın.
 */

// Yeni controller'ları yükle
require_once __DIR__ . '/Provider/ProviderControllerLoader.php';

/**
 * @deprecated Geriye dönük uyumluluk için - yeni ProviderControllerFactory kullanın
 */
class ProviderController 
{
    private $db;
    
    public function __construct() 
    {
        trigger_error(
            'ProviderController is deprecated. Use modular controllers in src/Controllers/Provider/ instead.',
            E_USER_DEPRECATED
        );
        
        require_once __DIR__ . '/../config/config.php';
        $this->db = getDatabase();
        startSession();
    }
    
    /**
     * Herhangi bir metod çağrıldığında uyarı ver
     */
    public function __call($method, $args)
    {
        error_log("⚠️ DEPRECATED: ProviderController::$method() called. Use modular controllers instead.");
        
        // Metod adına göre doğru controller'a yönlendir
        $controllerMap = [
            'login' => 'ProviderAuthController',
            'register' => 'ProviderAuthController',
            'logout' => 'ProviderAuthController',
            'dashboard' => 'ProviderDashboardController',
            'leads' => 'ProviderLeadController',
            'markLeadViewed' => 'ProviderLeadController',
            'requestLead' => 'ProviderLeadController',
            'hideLead' => 'ProviderLeadController',
            'hiddenLeads' => 'ProviderLeadController',
            'myRequests' => 'ProviderLeadController',
            'profile' => 'ProviderProfileController',
            'settings' => 'ProviderProfileController',
            'browsePackages' => 'ProviderPurchaseController',
            'purchasePackage' => 'ProviderPurchaseController',
            'createCheckoutSession' => 'ProviderPurchaseController',
            'purchaseSuccess' => 'ProviderPurchaseController',
            'purchaseCancel' => 'ProviderPurchaseController',
            'messages' => 'ProviderMessageController',
            'markMessageRead' => 'ProviderMessageController',
            'deleteMessage' => 'ProviderMessageController',
        ];
        
        if (isset($controllerMap[$method])) {
            error_log("Redirecting to {$controllerMap[$method]}::$method()");
        }
        
        throw new \BadMethodCallException(
            "Method $method() not found in ProviderController. " .
            "This controller is deprecated. Use modular controllers in src/Controllers/Provider/ instead."
        );
    }
}
