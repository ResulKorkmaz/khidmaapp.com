<?php
/**
 * KhidmaApp.com - Rate Limiter
 * 
 * IP bazlı istek sınırlama sistemi
 * Brute force ve spam saldırılarına karşı koruma
 */

class RateLimiter
{
    private PDO $db;
    private string $ip;
    
    // Varsayılan limitler
    const DEFAULT_MAX_ATTEMPTS = 5;
    const DEFAULT_DECAY_MINUTES = 15;
    
    // Aksiyon bazlı limitler
    const LIMITS = [
        'registration' => ['attempts' => 3, 'decay' => 60],      // 60 dakikada 3 kayıt
        'login' => ['attempts' => 5, 'decay' => 15],             // 15 dakikada 5 giriş
        'email_verification' => ['attempts' => 5, 'decay' => 10], // 10 dakikada 5 doğrulama
        'password_reset' => ['attempts' => 3, 'decay' => 30],    // 30 dakikada 3 reset
        'lead_submit' => ['attempts' => 10, 'decay' => 60],      // 60 dakikada 10 lead
    ];
    
    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? getDatabase();
        $this->ip = $this->getClientIp();
        $this->ensureTableExists();
    }
    
    /**
     * Tablo yoksa oluştur
     */
    private function ensureTableExists(): void
    {
        try {
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS rate_limits (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    ip_address VARCHAR(45) NOT NULL,
                    action_type VARCHAR(50) NOT NULL,
                    attempts INT DEFAULT 1,
                    first_attempt_at DATETIME NOT NULL,
                    last_attempt_at DATETIME NOT NULL,
                    blocked_until DATETIME NULL,
                    INDEX idx_ip_action (ip_address, action_type),
                    INDEX idx_blocked (blocked_until)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
        } catch (PDOException $e) {
            error_log("Rate limit table creation error: " . $e->getMessage());
        }
    }
    
    /**
     * İstek yapılabilir mi kontrol et
     */
    public function canAttempt(string $action): bool
    {
        $limits = self::LIMITS[$action] ?? [
            'attempts' => self::DEFAULT_MAX_ATTEMPTS,
            'decay' => self::DEFAULT_DECAY_MINUTES
        ];
        
        // Mevcut kayıt var mı?
        $stmt = $this->db->prepare("
            SELECT * FROM rate_limits 
            WHERE ip_address = ? AND action_type = ?
        ");
        $stmt->execute([$this->ip, $action]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$record) {
            return true; // İlk deneme
        }
        
        // Bloklanmış mı?
        if ($record['blocked_until'] && strtotime($record['blocked_until']) > time()) {
            return false;
        }
        
        // Decay süresi geçmiş mi?
        $decayTime = strtotime($record['first_attempt_at']) + ($limits['decay'] * 60);
        if (time() > $decayTime) {
            // Süresi dolmuş, sıfırla
            $this->resetAttempts($action);
            return true;
        }
        
        // Limit aşılmış mı?
        return $record['attempts'] < $limits['attempts'];
    }
    
    /**
     * Deneme kaydet
     */
    public function recordAttempt(string $action): void
    {
        $limits = self::LIMITS[$action] ?? [
            'attempts' => self::DEFAULT_MAX_ATTEMPTS,
            'decay' => self::DEFAULT_DECAY_MINUTES
        ];
        
        // Mevcut kayıt var mı?
        $stmt = $this->db->prepare("
            SELECT * FROM rate_limits 
            WHERE ip_address = ? AND action_type = ?
        ");
        $stmt->execute([$this->ip, $action]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$record) {
            // Yeni kayıt
            $stmt = $this->db->prepare("
                INSERT INTO rate_limits (ip_address, action_type, attempts, first_attempt_at, last_attempt_at)
                VALUES (?, ?, 1, NOW(), NOW())
            ");
            $stmt->execute([$this->ip, $action]);
        } else {
            // Decay süresi geçmiş mi kontrol et
            $decayTime = strtotime($record['first_attempt_at']) + ($limits['decay'] * 60);
            
            if (time() > $decayTime) {
                // Sıfırla ve yeni başlat
                $stmt = $this->db->prepare("
                    UPDATE rate_limits 
                    SET attempts = 1, first_attempt_at = NOW(), last_attempt_at = NOW(), blocked_until = NULL
                    WHERE ip_address = ? AND action_type = ?
                ");
                $stmt->execute([$this->ip, $action]);
            } else {
                // Artır
                $newAttempts = $record['attempts'] + 1;
                $blockedUntil = null;
                
                // Limit aşıldıysa blokla
                if ($newAttempts >= $limits['attempts']) {
                    $blockedUntil = date('Y-m-d H:i:s', time() + ($limits['decay'] * 60));
                }
                
                $stmt = $this->db->prepare("
                    UPDATE rate_limits 
                    SET attempts = ?, last_attempt_at = NOW(), blocked_until = ?
                    WHERE ip_address = ? AND action_type = ?
                ");
                $stmt->execute([$newAttempts, $blockedUntil, $this->ip, $action]);
            }
        }
    }
    
    /**
     * Kalan deneme sayısı
     */
    public function remainingAttempts(string $action): int
    {
        $limits = self::LIMITS[$action] ?? [
            'attempts' => self::DEFAULT_MAX_ATTEMPTS,
            'decay' => self::DEFAULT_DECAY_MINUTES
        ];
        
        $stmt = $this->db->prepare("
            SELECT attempts, first_attempt_at FROM rate_limits 
            WHERE ip_address = ? AND action_type = ?
        ");
        $stmt->execute([$this->ip, $action]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$record) {
            return $limits['attempts'];
        }
        
        // Decay süresi geçmiş mi?
        $decayTime = strtotime($record['first_attempt_at']) + ($limits['decay'] * 60);
        if (time() > $decayTime) {
            return $limits['attempts'];
        }
        
        return max(0, $limits['attempts'] - $record['attempts']);
    }
    
    /**
     * Bekleme süresi (saniye)
     */
    public function retryAfter(string $action): int
    {
        $limits = self::LIMITS[$action] ?? [
            'attempts' => self::DEFAULT_MAX_ATTEMPTS,
            'decay' => self::DEFAULT_DECAY_MINUTES
        ];
        
        $stmt = $this->db->prepare("
            SELECT blocked_until, first_attempt_at FROM rate_limits 
            WHERE ip_address = ? AND action_type = ?
        ");
        $stmt->execute([$this->ip, $action]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$record) {
            return 0;
        }
        
        if ($record['blocked_until']) {
            $blockedUntil = strtotime($record['blocked_until']);
            if ($blockedUntil > time()) {
                return $blockedUntil - time();
            }
        }
        
        return 0;
    }
    
    /**
     * Denemeleri sıfırla
     */
    public function resetAttempts(string $action): void
    {
        $stmt = $this->db->prepare("
            DELETE FROM rate_limits 
            WHERE ip_address = ? AND action_type = ?
        ");
        $stmt->execute([$this->ip, $action]);
    }
    
    /**
     * Başarılı işlem sonrası sıfırla
     */
    public function clearOnSuccess(string $action): void
    {
        $this->resetAttempts($action);
    }
    
    /**
     * Client IP adresini al
     */
    private function getClientIp(): string
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
                // Geçerli IP mi?
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        
        return '0.0.0.0';
    }
    
    /**
     * Eski kayıtları temizle (cron job için)
     */
    public function cleanup(int $olderThanDays = 7): int
    {
        $stmt = $this->db->prepare("
            DELETE FROM rate_limits 
            WHERE last_attempt_at < DATE_SUB(NOW(), INTERVAL ? DAY)
        ");
        $stmt->execute([$olderThanDays]);
        return $stmt->rowCount();
    }
    
    /**
     * Hata mesajı oluştur (Arapça)
     */
    public function getErrorMessage(string $action): string
    {
        $retryAfter = $this->retryAfter($action);
        $minutes = ceil($retryAfter / 60);
        
        $messages = [
            'registration' => "لقد تجاوزت الحد المسموح به لإنشاء الحسابات. يرجى المحاولة بعد {$minutes} دقيقة.",
            'login' => "لقد تجاوزت الحد المسموح به لمحاولات تسجيل الدخول. يرجى المحاولة بعد {$minutes} دقيقة.",
            'email_verification' => "لقد تجاوزت الحد المسموح به. يرجى المحاولة بعد {$minutes} دقيقة.",
            'password_reset' => "لقد تجاوزت الحد المسموح به لإعادة تعيين كلمة المرور. يرجى المحاولة بعد {$minutes} دقيقة.",
            'lead_submit' => "لقد تجاوزت الحد المسموح به لإرسال الطلبات. يرجى المحاولة بعد {$minutes} دقيقة.",
        ];
        
        return $messages[$action] ?? "لقد تجاوزت الحد المسموح به. يرجى المحاولة بعد {$minutes} دقيقة.";
    }
}

