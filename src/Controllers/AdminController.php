<?php

/**
 * KhidmaApp.com - Admin Controller (DEPRECATED)
 * 
 * ⚠️ BU DOSYA ARTIK KULLANILMIYOR!
 * 
 * Tüm admin işlevleri aşağıdaki modüler controller'lara taşındı:
 * 
 * - AdminAuthController       : Giriş/çıkış işlemleri
 * - AdminDashboardController  : Dashboard ve istatistikler
 * - AdminLeadController       : Lead (müşteri talepleri) yönetimi
 * - AdminProviderController   : Usta yönetimi
 * - AdminServiceController    : Hizmet yönetimi
 * - AdminPackageController    : Paket yönetimi
 * - AdminPurchaseController   : Satın alma ve lead atama işlemleri
 * - AdminMessageController    : Mesaj yönetimi
 * - AdminUserController       : Admin kullanıcı yönetimi
 * 
 * @deprecated Bu dosya geriye dönük uyumluluk için tutulmaktadır.
 *             Yeni geliştirmeler için src/Controllers/Admin/ klasörünü kullanın.
 */

// Yeni controller'ları yükle
require_once __DIR__ . '/Admin/AdminControllerLoader.php';

/**
 * @deprecated Geriye dönük uyumluluk için - yeni AdminControllerLoader kullanın
 */
class AdminController 
{
    private $pdo;
    
    public function __construct() 
    {
        trigger_error(
            'AdminController is deprecated. Use modular controllers in src/Controllers/Admin/ instead.',
            E_USER_DEPRECATED
        );
        
        require_once __DIR__ . '/../config/config.php';
        $this->pdo = getDatabase();
    }
    
    /**
     * Herhangi bir metod çağrıldığında uyarı ver
     */
    public function __call($method, $args)
    {
        error_log("⚠️ DEPRECATED: AdminController::$method() called. Use modular controllers instead.");
        
        // Metod adına göre doğru controller'a yönlendir
        $controllerMap = [
            'login' => 'AdminAuthController',
            'logout' => 'AdminAuthController',
            'index' => 'AdminDashboardController',
            'leads' => 'AdminLeadController',
            'leadDetail' => 'AdminLeadController',
            'updateLeadStatus' => 'AdminLeadController',
            'markAsSent' => 'AdminLeadController',
            'toggleSentToProvider' => 'AdminLeadController',
            'deleteLead' => 'AdminLeadController',
            'restoreLead' => 'AdminLeadController',
            'permanentlyDeleteLead' => 'AdminLeadController',
            'exportLeads' => 'AdminLeadController',
            'providers' => 'AdminProviderController',
            'providerDetail' => 'AdminProviderController',
            'approveProvider' => 'AdminProviderController',
            'changeProviderStatus' => 'AdminProviderController',
            'deleteProvider' => 'AdminProviderController',
            'searchProviders' => 'AdminProviderController',
            'manageServices' => 'AdminServiceController',
            'createService' => 'AdminServiceController',
            'updateService' => 'AdminServiceController',
            'toggleServiceStatus' => 'AdminServiceController',
            'deleteService' => 'AdminServiceController',
            'manageLeadPackages' => 'AdminPackageController',
            'createLeadPackage' => 'AdminPackageController',
            'updateLeadPackage' => 'AdminPackageController',
            'toggleLeadPackageStatus' => 'AdminPackageController',
            'deleteLeadPackage' => 'AdminPackageController',
            'purchases' => 'AdminPurchaseController',
            'leadRequests' => 'AdminPurchaseController',
            'sendLeadManually' => 'AdminPurchaseController',
            'getAvailableProviders' => 'AdminPurchaseController',
            'markLeadAsSentViaWhatsApp' => 'AdminPurchaseController',
            'assignLeadsToProvider' => 'AdminPurchaseController',
            'withdrawLeadFromProvider' => 'AdminPurchaseController',
            'providerMessages' => 'AdminMessageController',
            'sendMessage' => 'AdminMessageController',
            'getProviderMessageHistory' => 'AdminMessageController',
            'users' => 'AdminUserController',
            'createUser' => 'AdminUserController',
            'deleteUser' => 'AdminUserController',
            'toggleUserStatus' => 'AdminUserController',
        ];
        
        if (isset($controllerMap[$method])) {
            error_log("Redirecting to {$controllerMap[$method]}::$method()");
        }
        
        throw new \BadMethodCallException(
            "Method $method() not found in AdminController. " .
            "This controller is deprecated. Use modular controllers in src/Controllers/Admin/ instead."
        );
    }
}
