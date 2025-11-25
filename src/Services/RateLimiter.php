<?php
/**
 * Rate Limiter Service
 * 
 * API ve form isteklerini sınırlandırır.
 * Brute force ve DDoS saldırılarına karşı koruma sağlar.
 * 
 * @package KhidmaApp
 * @since 1.0.0
 */

class RateLimiter
{
    private static $instance = null;
    private $storageDir;
    private $pdo;
    
    // Varsayılan limitler
    const DEFAULT_MAX_ATTEMPTS = 60;      // Dakikada max istek
    const DEFAULT_DECAY_MINUTES = 1;      // Süre (dakika)
    
    // Özel limitler
    const LOGIN_MAX_ATTEMPTS = 5;         // 5 deneme
    const LOGIN_DECAY_MINUTES = 15;       // 15 dakika
    
    const API_MAX_ATTEMPTS = 100;         // 100 istek
    const API_DECAY_MINUTES = 1;          // 1 dakika
    
    const FORM_MAX_ATTEMPTS = 10;         // 10 form gönderimi
    const FORM_DECAY_MINUTES = 1;         // 1 dakika
    
    const REGISTER_MAX_ATTEMPTS = 3;      // 3 kayıt denemesi
    const REGISTER_DECAY_MINUTES = 60;    // 1 saat
    
    /**
     * Constructor
     */
    public function __construct(?string $storageDir = null, $pdo = null)
    {
        $this->storageDir = $storageDir ?? __DIR__ . '/../../storage/rate_limits';
        $this->pdo = $pdo ?? getDatabase();
        $this->ensureStorageDirectory();
    }
    
    /**
     * Singleton instance
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Storage klasörünü oluştur
     */
    private function ensureStorageDirectory(): void
    {
        if (!is_dir($this->storageDir)) {
            mkdir($this->storageDir, 0755, true);
            file_put_contents($this->storageDir . '/.gitignore', "*\n!.gitignore\n");
        }
    }
    
    /**
     * Rate limit dosya yolunu al
     */
    private function getFilePath(string $key): string
    {
        $hash = md5($key);
        return $this->storageDir . '/' . $hash . '.limit';
    }
    
    /**
     * İstemci IP'sini al
     */
    public function getClientIp(): string
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
                // Birden fazla IP varsa ilkini al
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                // Geçerli IP mi kontrol et
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        
        return '0.0.0.0';
    }
    
    /**
     * Rate limit key oluştur
     */
    private function resolveKey(string $key): string
    {
        return $key . ':' . $this->getClientIp();
    }
    
    /**
     * Deneme sayısını artır
     */
    public function hit(string $key, int $decayMinutes = self::DEFAULT_DECAY_MINUTES): int
    {
        $key = $this->resolveKey($key);
        $filePath = $this->getFilePath($key);
        $now = time();
        $decaySeconds = $decayMinutes * 60;
        
        $data = $this->getData($filePath);
        
        // Süre dolmuşsa sıfırla
        if ($data['expires_at'] < $now) {
            $data = [
                'attempts' => 0,
                'expires_at' => $now + $decaySeconds
            ];
        }
        
        $data['attempts']++;
        
        $this->saveData($filePath, $data);
        
        return $data['attempts'];
    }
    
    /**
     * Mevcut deneme sayısını al
     */
    public function attempts(string $key): int
    {
        $key = $this->resolveKey($key);
        $filePath = $this->getFilePath($key);
        
        $data = $this->getData($filePath);
        
        if ($data['expires_at'] < time()) {
            return 0;
        }
        
        return $data['attempts'];
    }
    
    /**
     * Kalan deneme hakkı
     */
    public function remaining(string $key, int $maxAttempts = self::DEFAULT_MAX_ATTEMPTS): int
    {
        return max(0, $maxAttempts - $this->attempts($key));
    }
    
    /**
     * Çok fazla deneme yapıldı mı?
     */
    public function tooManyAttempts(string $key, int $maxAttempts = self::DEFAULT_MAX_ATTEMPTS): bool
    {
        return $this->attempts($key) >= $maxAttempts;
    }
    
    /**
     * Deneme sayısını sıfırla
     */
    public function clear(string $key): bool
    {
        $key = $this->resolveKey($key);
        $filePath = $this->getFilePath($key);
        
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        
        return true;
    }
    
    /**
     * Ne zaman tekrar deneyebilir (saniye)
     */
    public function availableIn(string $key): int
    {
        $key = $this->resolveKey($key);
        $filePath = $this->getFilePath($key);
        
        $data = $this->getData($filePath);
        
        $remaining = $data['expires_at'] - time();
        
        return max(0, $remaining);
    }
    
    /**
     * Rate limit kontrol et ve gerekirse engelle
     */
    public function check(string $key, int $maxAttempts = self::DEFAULT_MAX_ATTEMPTS, int $decayMinutes = self::DEFAULT_DECAY_MINUTES): bool
    {
        if ($this->tooManyAttempts($key, $maxAttempts)) {
            return false;
        }
        
        $this->hit($key, $decayMinutes);
        return true;
    }
    
    /**
     * Rate limit aşıldıysa JSON response döndür
     */
    public function throttleResponse(string $key): void
    {
        $retryAfter = $this->availableIn($key);
        
        http_response_code(429);
        header('Content-Type: application/json');
        header('Retry-After: ' . $retryAfter);
        
        echo json_encode([
            'success' => false,
            'error' => 'Too many requests',
            'error_ar' => 'طلبات كثيرة جداً، يرجى المحاولة لاحقاً',
            'retry_after' => $retryAfter,
            'retry_after_formatted' => $this->formatSeconds($retryAfter)
        ]);
        
        exit;
    }
    
    /**
     * Login rate limit kontrolü
     */
    public function checkLogin(string $identifier = ''): bool
    {
        $key = 'login:' . ($identifier ?: $this->getClientIp());
        
        if ($this->tooManyAttempts($key, self::LOGIN_MAX_ATTEMPTS)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Login denemesi kaydet
     */
    public function hitLogin(string $identifier = ''): void
    {
        $key = 'login:' . ($identifier ?: $this->getClientIp());
        $this->hit($key, self::LOGIN_DECAY_MINUTES);
    }
    
    /**
     * Başarılı login sonrası temizle
     */
    public function clearLogin(string $identifier = ''): void
    {
        $key = 'login:' . ($identifier ?: $this->getClientIp());
        $this->clear($key);
    }
    
    /**
     * API rate limit kontrolü
     */
    public function checkApi(string $endpoint = ''): bool
    {
        $key = 'api:' . ($endpoint ?: 'general');
        
        if ($this->tooManyAttempts($key, self::API_MAX_ATTEMPTS)) {
            return false;
        }
        
        $this->hit($key, self::API_DECAY_MINUTES);
        return true;
    }
    
    /**
     * Form gönderim rate limit kontrolü
     */
    public function checkForm(string $formName = ''): bool
    {
        $key = 'form:' . ($formName ?: 'general');
        
        if ($this->tooManyAttempts($key, self::FORM_MAX_ATTEMPTS)) {
            return false;
        }
        
        $this->hit($key, self::FORM_DECAY_MINUTES);
        return true;
    }
    
    /**
     * Kayıt rate limit kontrolü
     */
    public function checkRegister(): bool
    {
        $key = 'register';
        
        if ($this->tooManyAttempts($key, self::REGISTER_MAX_ATTEMPTS)) {
            return false;
        }
        
        $this->hit($key, self::REGISTER_DECAY_MINUTES);
        return true;
    }
    
    /**
     * Dosyadan veri oku
     */
    private function getData(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return ['attempts' => 0, 'expires_at' => 0];
        }
        
        try {
            $content = file_get_contents($filePath);
            $data = json_decode($content, true);
            
            if (!is_array($data)) {
                return ['attempts' => 0, 'expires_at' => 0];
            }
            
            return $data;
        } catch (Exception $e) {
            return ['attempts' => 0, 'expires_at' => 0];
        }
    }
    
    /**
     * Dosyaya veri yaz
     */
    private function saveData(string $filePath, array $data): bool
    {
        try {
            return file_put_contents($filePath, json_encode($data), LOCK_EX) !== false;
        } catch (Exception $e) {
            error_log("RateLimiter::saveData error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Saniyeleri okunabilir formata çevir
     */
    private function formatSeconds(int $seconds): string
    {
        if ($seconds < 60) {
            return $seconds . ' ثانية';
        }
        
        $minutes = ceil($seconds / 60);
        return $minutes . ' دقيقة';
    }
    
    /**
     * Eski rate limit dosyalarını temizle
     */
    public function gc(): int
    {
        $cleaned = 0;
        $now = time();
        
        try {
            $files = glob($this->storageDir . '/*.limit');
            
            foreach ($files as $file) {
                $data = $this->getData($file);
                
                // 24 saatten eski veya süresi dolmuş dosyaları sil
                if ($data['expires_at'] < $now || filemtime($file) < ($now - 86400)) {
                    unlink($file);
                    $cleaned++;
                }
            }
        } catch (Exception $e) {
            error_log("RateLimiter::gc error: " . $e->getMessage());
        }
        
        return $cleaned;
    }
    
    /**
     * Rate limit istatistikleri
     */
    public function getStats(): array
    {
        $stats = [
            'total_files' => 0,
            'active' => 0,
            'expired' => 0
        ];
        
        try {
            $files = glob($this->storageDir . '/*.limit');
            $now = time();
            
            foreach ($files as $file) {
                $stats['total_files']++;
                $data = $this->getData($file);
                
                if ($data['expires_at'] >= $now) {
                    $stats['active']++;
                } else {
                    $stats['expired']++;
                }
            }
        } catch (Exception $e) {
            error_log("RateLimiter::getStats error: " . $e->getMessage());
        }
        
        return $stats;
    }
    
    /**
     * IP'yi engelle (kalıcı)
     */
    public function blockIp(string $ip, int $duration = 86400): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            // Blocked IPs tablosunu oluştur
            $this->ensureBlockedIpsTable();
            
            $stmt = $this->pdo->prepare("
                INSERT INTO blocked_ips (ip_address, blocked_until, reason, created_at)
                VALUES (?, DATE_ADD(NOW(), INTERVAL ? SECOND), 'rate_limit', NOW())
                ON DUPLICATE KEY UPDATE 
                    blocked_until = DATE_ADD(NOW(), INTERVAL ? SECOND)
            ");
            
            return $stmt->execute([$ip, $duration, $duration]);
        } catch (Exception $e) {
            error_log("RateLimiter::blockIp error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * IP engelini kaldır
     */
    public function unblockIp(string $ip): bool
    {
        if (!$this->pdo) {
            return false;
        }
        
        try {
            $stmt = $this->pdo->prepare("DELETE FROM blocked_ips WHERE ip_address = ?");
            return $stmt->execute([$ip]);
        } catch (Exception $e) {
            error_log("RateLimiter::unblockIp error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * IP engelli mi?
     */
    public function isIpBlocked(?string $ip = null): bool
    {
        $ip = $ip ?? $this->getClientIp();
        
        if (!$this->pdo) {
            return false;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT 1 FROM blocked_ips 
                WHERE ip_address = ? AND blocked_until > NOW()
            ");
            $stmt->execute([$ip]);
            return $stmt->fetch() !== false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Blocked IPs tablosunu oluştur
     */
    private function ensureBlockedIpsTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS blocked_ips (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ip_address VARCHAR(45) NOT NULL UNIQUE,
            blocked_until DATETIME NOT NULL,
            reason VARCHAR(100) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_ip (ip_address),
            INDEX idx_blocked_until (blocked_until)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->pdo->exec($sql);
    }
}

// ==========================================
// GLOBAL HELPER FUNCTIONS
// ==========================================

/**
 * Rate limiter instance'ını al
 */
function rateLimiter(): RateLimiter {
    return RateLimiter::getInstance();
}

/**
 * Rate limit kontrol et
 */
function rate_limit_check(string $key, int $maxAttempts = 60, int $decayMinutes = 1): bool {
    return RateLimiter::getInstance()->check($key, $maxAttempts, $decayMinutes);
}

/**
 * Login rate limit kontrolü
 */
function rate_limit_login(string $identifier = ''): bool {
    return RateLimiter::getInstance()->checkLogin($identifier);
}

/**
 * API rate limit kontrolü
 */
function rate_limit_api(string $endpoint = ''): bool {
    return RateLimiter::getInstance()->checkApi($endpoint);
}

/**
 * Form rate limit kontrolü
 */
function rate_limit_form(string $formName = ''): bool {
    return RateLimiter::getInstance()->checkForm($formName);
}

