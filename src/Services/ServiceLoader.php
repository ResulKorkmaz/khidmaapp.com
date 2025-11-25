<?php
/**
 * Service Loader
 * 
 * Tüm service sınıflarını merkezi olarak yükler.
 * Controller'lar bu dosyayı include ederek service'lere erişir.
 * 
 * Kullanım:
 *   require_once __DIR__ . '/../Services/ServiceLoader.php';
 *   $leadService = ServiceLoader::getAdminLeadService();
 * 
 * @package KhidmaApp
 * @since 1.0.0
 */

class ServiceLoader
{
    private static $instances = [];
    
    /**
     * Service dosyalarını yükle
     */
    private static function loadServiceFiles(): void
    {
        static $loaded = false;
        
        if ($loaded) {
            return;
        }
        
        $servicesDir = __DIR__;
        
        // Service dosyalarını yükle
        $serviceFiles = [
            'AdminLeadService.php',
            'AdminProviderService.php',
            'AdminStatsService.php',
            'ProviderLeadService.php',
            'ProviderPurchaseService.php',
            'NotificationService.php',
            'LeadExportService.php',
            'Logger.php'
        ];
        
        foreach ($serviceFiles as $file) {
            $path = $servicesDir . '/' . $file;
            if (file_exists($path)) {
                require_once $path;
            }
        }
        
        $loaded = true;
    }
    
    /**
     * Service instance'ı al (singleton pattern)
     */
    private static function getInstance(string $className, $pdo = null)
    {
        self::loadServiceFiles();
        
        if (!isset(self::$instances[$className])) {
            if (class_exists($className)) {
                self::$instances[$className] = new $className($pdo);
            } else {
                error_log("ServiceLoader: Class not found: $className");
                return null;
            }
        }
        
        return self::$instances[$className];
    }
    
    /**
     * Instance'ı sıfırla (test için)
     */
    public static function resetInstance(string $className): void
    {
        if (isset(self::$instances[$className])) {
            unset(self::$instances[$className]);
        }
    }
    
    /**
     * Tüm instance'ları sıfırla
     */
    public static function resetAll(): void
    {
        self::$instances = [];
    }
    
    // ==========================================
    // ADMIN SERVICES
    // ==========================================
    
    /**
     * Admin Lead Service
     */
    public static function getAdminLeadService($pdo = null): ?AdminLeadService
    {
        return self::getInstance('AdminLeadService', $pdo);
    }
    
    /**
     * Admin Provider Service
     */
    public static function getAdminProviderService($pdo = null): ?AdminProviderService
    {
        return self::getInstance('AdminProviderService', $pdo);
    }
    
    /**
     * Admin Stats Service
     */
    public static function getAdminStatsService($pdo = null): ?AdminStatsService
    {
        return self::getInstance('AdminStatsService', $pdo);
    }
    
    // ==========================================
    // PROVIDER SERVICES
    // ==========================================
    
    /**
     * Provider Lead Service
     */
    public static function getProviderLeadService($pdo = null): ?ProviderLeadService
    {
        return self::getInstance('ProviderLeadService', $pdo);
    }
    
    /**
     * Provider Purchase Service
     */
    public static function getProviderPurchaseService($pdo = null): ?ProviderPurchaseService
    {
        return self::getInstance('ProviderPurchaseService', $pdo);
    }
    
    // ==========================================
    // COMMON SERVICES
    // ==========================================
    
    /**
     * Notification Service
     */
    public static function getNotificationService($pdo = null): ?NotificationService
    {
        return self::getInstance('NotificationService', $pdo);
    }
    
    /**
     * Lead Export Service
     */
    public static function getLeadExportService($pdo = null): ?LeadExportService
    {
        return self::getInstance('LeadExportService', $pdo);
    }
    
    /**
     * Logger Service
     */
    public static function getLogger(): ?Logger
    {
        return self::getInstance('Logger');
    }
}

// Kısa yollar (opsiyonel)
if (!function_exists('adminLeadService')) {
    function adminLeadService($pdo = null) {
        return ServiceLoader::getAdminLeadService($pdo);
    }
}

if (!function_exists('adminProviderService')) {
    function adminProviderService($pdo = null) {
        return ServiceLoader::getAdminProviderService($pdo);
    }
}

if (!function_exists('adminStatsService')) {
    function adminStatsService($pdo = null) {
        return ServiceLoader::getAdminStatsService($pdo);
    }
}

if (!function_exists('providerLeadService')) {
    function providerLeadService($pdo = null) {
        return ServiceLoader::getProviderLeadService($pdo);
    }
}

if (!function_exists('providerPurchaseService')) {
    function providerPurchaseService($pdo = null) {
        return ServiceLoader::getProviderPurchaseService($pdo);
    }
}

