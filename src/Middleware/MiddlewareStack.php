<?php

/**
 * KhidmaApp.com - Middleware Stack
 * 
 * Middleware'leri yönetmek için yardımcı sınıf
 */

require_once __DIR__ . '/AuthMiddleware.php';
require_once __DIR__ . '/RateLimitMiddleware.php';
require_once __DIR__ . '/CsrfMiddleware.php';

class MiddlewareStack
{
    /**
     * Middleware grupları
     */
    private static $groups = [
        'web' => ['csrf'],
        'api' => ['rate_limit:api'],
        'admin' => ['admin_auth', 'csrf'],
        'admin.auth' => ['admin_auth'],
        'provider' => ['provider_auth', 'csrf'],
        'provider.auth' => ['provider_auth'],
        'super_admin' => ['super_admin_auth', 'csrf'],
    ];
    
    /**
     * Middleware'leri çalıştır
     * 
     * @param array|string $middlewares Middleware listesi veya grup adı
     * @return bool
     */
    public static function run($middlewares): bool
    {
        // String ise grup olarak kabul et
        if (is_string($middlewares)) {
            $middlewares = self::$groups[$middlewares] ?? [$middlewares];
        }
        
        foreach ($middlewares as $middleware) {
            // Parametre kontrolü (middleware:param formatı)
            $params = [];
            if (strpos($middleware, ':') !== false) {
                [$middleware, $paramStr] = explode(':', $middleware, 2);
                $params = explode(',', $paramStr);
            }
            
            // Middleware'i çalıştır
            $result = self::execute($middleware, $params);
            
            if ($result === false) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Tek bir middleware'i çalıştır
     * 
     * @param string $middleware Middleware adı
     * @param array $params Parametreler
     * @return bool
     */
    private static function execute(string $middleware, array $params = []): bool
    {
        switch ($middleware) {
            case 'admin_auth':
                return AuthMiddleware::adminAuth();
                
            case 'provider_auth':
                return AuthMiddleware::providerAuth();
                
            case 'super_admin_auth':
                return AuthMiddleware::requireSuperAdmin();
                
            case 'require_admin':
                return AuthMiddleware::requireAdmin();
                
            case 'active_provider':
                return AuthMiddleware::requireActiveProvider();
                
            case 'csrf':
                return CsrfMiddleware::verify();
                
            case 'rate_limit':
                $type = $params[0] ?? 'default';
                return RateLimitMiddleware::check($type);
                
            case 'login_limit':
                $username = $params[0] ?? null;
                return RateLimitMiddleware::loginLimit($username);
                
            case 'register_limit':
                return RateLimitMiddleware::registerLimit();
                
            case 'lead_submit_limit':
                $phone = $params[0] ?? null;
                return RateLimitMiddleware::leadSubmitLimit($phone);
                
            default:
                error_log("Unknown middleware: {$middleware}");
                return true;
        }
    }
    
    /**
     * Middleware grubu tanımla
     * 
     * @param string $name Grup adı
     * @param array $middlewares Middleware listesi
     */
    public static function group(string $name, array $middlewares): void
    {
        self::$groups[$name] = $middlewares;
    }
    
    /**
     * Mevcut grupları al
     * 
     * @return array
     */
    public static function getGroups(): array
    {
        return self::$groups;
    }
}

