<?php

/**
 * KhidmaApp.com - Rate Limit Middleware
 * 
 * IP bazlı rate limiting middleware'i
 */

require_once __DIR__ . '/../config/config.php';

class RateLimitMiddleware
{
    /**
     * Rate limit konfigürasyonları
     */
    private static $limits = [
        'default' => ['requests' => 60, 'window' => 60],      // 60 istek/dakika
        'api' => ['requests' => 30, 'window' => 60],          // 30 istek/dakika
        'login' => ['requests' => 5, 'window' => 300],        // 5 deneme/5 dakika
        'register' => ['requests' => 3, 'window' => 3600],    // 3 kayıt/saat
        'lead_submit' => ['requests' => 10, 'window' => 3600], // 10 lead/saat
        'strict' => ['requests' => 10, 'window' => 60],       // 10 istek/dakika
    ];
    
    /**
     * Rate limit kontrolü yap
     * 
     * @param string $type Limit tipi (default, api, login, register, lead_submit, strict)
     * @param string|null $identifier Özel tanımlayıcı (null ise IP kullanılır)
     * @return bool
     */
    public static function check(string $type = 'default', ?string $identifier = null): bool
    {
        $config = self::$limits[$type] ?? self::$limits['default'];
        $key = self::getKey($type, $identifier);
        
        // Rate limiter servisini kullan
        if (function_exists('isRateLimited')) {
            if (isRateLimited($key, $config['requests'], $config['window'])) {
                self::handleLimitExceeded($type, $config);
                return false;
            }
        } else {
            // Fallback: Basit file-based rate limiting
            if (self::isLimitedFallback($key, $config['requests'], $config['window'])) {
                self::handleLimitExceeded($type, $config);
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Rate limit kontrolü (exception fırlatmadan)
     * 
     * @param string $type Limit tipi
     * @param string|null $identifier Özel tanımlayıcı
     * @return array ['allowed' => bool, 'remaining' => int, 'reset' => int]
     */
    public static function checkSilent(string $type = 'default', ?string $identifier = null): array
    {
        $config = self::$limits[$type] ?? self::$limits['default'];
        $key = self::getKey($type, $identifier);
        
        if (function_exists('isRateLimited')) {
            $limited = isRateLimited($key, $config['requests'], $config['window']);
            $remaining = $limited ? 0 : $config['requests'];
            $reset = time() + $config['window'];
            
            return [
                'allowed' => !$limited,
                'remaining' => $remaining,
                'reset' => $reset,
                'limit' => $config['requests'],
                'window' => $config['window']
            ];
        }
        
        return [
            'allowed' => true,
            'remaining' => $config['requests'],
            'reset' => time() + $config['window'],
            'limit' => $config['requests'],
            'window' => $config['window']
        ];
    }
    
    /**
     * Rate limit'i temizle
     * 
     * @param string $type Limit tipi
     * @param string|null $identifier Özel tanımlayıcı
     */
    public static function clear(string $type = 'default', ?string $identifier = null): void
    {
        $key = self::getKey($type, $identifier);
        
        if (function_exists('clearRateLimit')) {
            clearRateLimit($key);
        }
    }
    
    /**
     * API endpoint'leri için rate limit middleware
     * 
     * @return bool
     */
    public static function apiLimit(): bool
    {
        return self::check('api');
    }
    
    /**
     * Login için rate limit middleware
     * 
     * @param string|null $username Kullanıcı adı (opsiyonel, IP + username kombinasyonu için)
     * @return bool
     */
    public static function loginLimit(?string $username = null): bool
    {
        $identifier = $username ? self::getClientIP() . ':' . $username : null;
        return self::check('login', $identifier);
    }
    
    /**
     * Kayıt için rate limit middleware
     * 
     * @return bool
     */
    public static function registerLimit(): bool
    {
        return self::check('register');
    }
    
    /**
     * Lead gönderimi için rate limit middleware
     * 
     * @param string|null $phone Telefon numarası (opsiyonel)
     * @return bool
     */
    public static function leadSubmitLimit(?string $phone = null): bool
    {
        $identifier = $phone ? self::getClientIP() . ':' . $phone : null;
        return self::check('lead_submit', $identifier);
    }
    
    /**
     * Rate limit key oluştur
     * 
     * @param string $type Limit tipi
     * @param string|null $identifier Özel tanımlayıcı
     * @return string
     */
    private static function getKey(string $type, ?string $identifier = null): string
    {
        $ip = self::getClientIP();
        $base = "rate_limit:{$type}:{$ip}";
        
        if ($identifier) {
            $base .= ':' . md5($identifier);
        }
        
        return $base;
    }
    
    /**
     * Client IP adresini al
     * 
     * @return string
     */
    private static function getClientIP(): string
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_X_FORWARDED_FOR',      // Proxy
            'HTTP_X_REAL_IP',            // Nginx
            'REMOTE_ADDR'                // Standart
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                // X-Forwarded-For birden fazla IP içerebilir
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        
        return '0.0.0.0';
    }
    
    /**
     * Fallback rate limiting (file-based)
     * 
     * @param string $key Rate limit key
     * @param int $maxRequests Maksimum istek sayısı
     * @param int $window Zaman penceresi (saniye)
     * @return bool
     */
    private static function isLimitedFallback(string $key, int $maxRequests, int $window): bool
    {
        $storageDir = __DIR__ . '/../../storage/rate_limits';
        if (!is_dir($storageDir)) {
            @mkdir($storageDir, 0755, true);
        }
        
        $file = $storageDir . '/' . md5($key) . '.json';
        $now = time();
        $data = ['count' => 0, 'reset' => $now + $window];
        
        if (file_exists($file)) {
            $content = @file_get_contents($file);
            if ($content) {
                $data = json_decode($content, true) ?: $data;
            }
        }
        
        // Reset zamanı geçmişse sıfırla
        if ($now >= $data['reset']) {
            $data = ['count' => 0, 'reset' => $now + $window];
        }
        
        // Sayacı artır
        $data['count']++;
        
        // Dosyaya yaz
        @file_put_contents($file, json_encode($data), LOCK_EX);
        
        return $data['count'] > $maxRequests;
    }
    
    /**
     * Limit aşıldığında işlem yap
     * 
     * @param string $type Limit tipi
     * @param array $config Limit konfigürasyonu
     */
    private static function handleLimitExceeded(string $type, array $config): void
    {
        $retryAfter = $config['window'];
        
        // Log
        error_log("Rate limit exceeded: type={$type}, ip=" . self::getClientIP());
        
        // Headers
        header('Retry-After: ' . $retryAfter);
        header('X-RateLimit-Limit: ' . $config['requests']);
        header('X-RateLimit-Remaining: 0');
        header('X-RateLimit-Reset: ' . (time() + $retryAfter));
        
        // AJAX request kontrolü
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        
        http_response_code(429);
        
        if ($isAjax || strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'message' => 'Çok fazla istek gönderdiniz. Lütfen ' . ceil($retryAfter / 60) . ' dakika sonra tekrar deneyin.',
                'error' => 'rate_limit_exceeded',
                'retry_after' => $retryAfter
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo "<h1>429 Too Many Requests</h1>";
            echo "<p>Çok fazla istek gönderdiniz. Lütfen " . ceil($retryAfter / 60) . " dakika sonra tekrar deneyin.</p>";
        }
        
        exit;
    }
}

