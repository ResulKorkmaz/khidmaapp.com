<?php

/**
 * KhidmaApp.com - Admin Controller Loader
 * 
 * Tüm admin controller'ları yükler
 */

// Base controller
require_once __DIR__ . '/BaseAdminController.php';

// Admin controllers
require_once __DIR__ . '/AdminAuthController.php';
require_once __DIR__ . '/AdminDashboardController.php';
require_once __DIR__ . '/AdminLeadController.php';
require_once __DIR__ . '/AdminProviderController.php';
require_once __DIR__ . '/AdminServiceController.php';
require_once __DIR__ . '/AdminPackageController.php';
require_once __DIR__ . '/AdminMessageController.php';
require_once __DIR__ . '/AdminUserController.php';
require_once __DIR__ . '/AdminPurchaseController.php';

/**
 * Admin controller factory
 * 
 * Controller adına göre ilgili controller instance'ını döndürür
 */
class AdminControllerFactory
{
    private static $controllers = [];
    
    /**
     * Controller instance'ı al (singleton pattern)
     */
    public static function get(string $controllerName)
    {
        if (!isset(self::$controllers[$controllerName])) {
            $className = 'Admin' . ucfirst($controllerName) . 'Controller';
            
            if (!class_exists($className)) {
                throw new Exception("Controller not found: {$className}");
            }
            
            self::$controllers[$controllerName] = new $className();
        }
        
        return self::$controllers[$controllerName];
    }
    
    /**
     * Auth controller
     */
    public static function auth(): AdminAuthController
    {
        return self::get('Auth');
    }
    
    /**
     * Dashboard controller
     */
    public static function dashboard(): AdminDashboardController
    {
        return self::get('Dashboard');
    }
    
    /**
     * Lead controller
     */
    public static function lead(): AdminLeadController
    {
        return self::get('Lead');
    }
    
    /**
     * Provider controller
     */
    public static function provider(): AdminProviderController
    {
        return self::get('Provider');
    }
    
    /**
     * Service controller
     */
    public static function service(): AdminServiceController
    {
        return self::get('Service');
    }
    
    /**
     * Package controller
     */
    public static function package(): AdminPackageController
    {
        return self::get('Package');
    }
    
    /**
     * Message controller
     */
    public static function message(): AdminMessageController
    {
        return self::get('Message');
    }
    
    /**
     * User controller
     */
    public static function user(): AdminUserController
    {
        return self::get('User');
    }
    
    /**
     * Purchase controller
     */
    public static function purchase(): AdminPurchaseController
    {
        return self::get('Purchase');
    }
}

// Helper function for quick access
if (!function_exists('adminController')) {
    function adminController(string $name) {
        return AdminControllerFactory::get($name);
    }
}

