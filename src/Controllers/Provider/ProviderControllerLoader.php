<?php

/**
 * KhidmaApp.com - Provider Controller Loader
 * 
 * Tüm provider controller'ları yükler
 */

// Base controller
require_once __DIR__ . '/BaseProviderController.php';

// Provider controllers
require_once __DIR__ . '/ProviderAuthController.php';
require_once __DIR__ . '/ProviderDashboardController.php';
require_once __DIR__ . '/ProviderLeadController.php';
require_once __DIR__ . '/ProviderProfileController.php';
require_once __DIR__ . '/ProviderPurchaseController.php';
require_once __DIR__ . '/ProviderMessageController.php';
require_once __DIR__ . '/ProviderEmailVerificationController.php';

/**
 * Provider controller factory
 */
class ProviderControllerFactory
{
    private static $controllers = [];
    
    /**
     * Controller instance'ı al (singleton pattern)
     */
    public static function get(string $controllerName)
    {
        if (!isset(self::$controllers[$controllerName])) {
            $className = 'Provider' . ucfirst($controllerName) . 'Controller';
            
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
    public static function auth(): ProviderAuthController
    {
        return self::get('Auth');
    }
    
    /**
     * Dashboard controller
     */
    public static function dashboard(): ProviderDashboardController
    {
        return self::get('Dashboard');
    }
    
    /**
     * Lead controller
     */
    public static function lead(): ProviderLeadController
    {
        return self::get('Lead');
    }
    
    /**
     * Profile controller
     */
    public static function profile(): ProviderProfileController
    {
        return self::get('Profile');
    }
    
    /**
     * Purchase controller
     */
    public static function purchase(): ProviderPurchaseController
    {
        return self::get('Purchase');
    }
    
    /**
     * Message controller
     */
    public static function message(): ProviderMessageController
    {
        return self::get('Message');
    }
    
    /**
     * Email Verification controller
     */
    public static function emailVerification(): ProviderEmailVerificationController
    {
        return self::get('EmailVerification');
    }
}

// Helper function
if (!function_exists('providerController')) {
    function providerController(string $name) {
        return ProviderControllerFactory::get($name);
    }
}

