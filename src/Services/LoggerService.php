<?php

/**
 * KhidmaApp.com - Logger Service
 * 
 * Merkezi loglama servisi
 */

class LoggerService
{
    /**
     * Log seviyeleri
     */
    const LEVEL_DEBUG = 'DEBUG';
    const LEVEL_INFO = 'INFO';
    const LEVEL_WARNING = 'WARNING';
    const LEVEL_ERROR = 'ERROR';
    const LEVEL_CRITICAL = 'CRITICAL';
    
    /**
     * Log dizini
     */
    private static string $logDir = '';
    
    /**
     * Minimum log seviyesi
     */
    private static string $minLevel = self::LEVEL_DEBUG;
    
    /**
     * Log seviyesi öncelikleri
     */
    private static array $levelPriority = [
        self::LEVEL_DEBUG => 0,
        self::LEVEL_INFO => 1,
        self::LEVEL_WARNING => 2,
        self::LEVEL_ERROR => 3,
        self::LEVEL_CRITICAL => 4,
    ];
    
    /**
     * Logger'ı başlat
     * 
     * @param string $logDir Log dizini
     * @param string $minLevel Minimum log seviyesi
     */
    public static function init(string $logDir = '', string $minLevel = self::LEVEL_DEBUG): void
    {
        self::$logDir = $logDir ?: __DIR__ . '/../../logs';
        self::$minLevel = $minLevel;
        
        // Log dizinini oluştur
        if (!is_dir(self::$logDir)) {
            @mkdir(self::$logDir, 0755, true);
        }
    }
    
    /**
     * Debug log
     */
    public static function debug(string $message, array $context = []): void
    {
        self::log(self::LEVEL_DEBUG, $message, $context);
    }
    
    /**
     * Info log
     */
    public static function info(string $message, array $context = []): void
    {
        self::log(self::LEVEL_INFO, $message, $context);
    }
    
    /**
     * Warning log
     */
    public static function warning(string $message, array $context = []): void
    {
        self::log(self::LEVEL_WARNING, $message, $context);
    }
    
    /**
     * Error log
     */
    public static function error(string $message, array $context = []): void
    {
        self::log(self::LEVEL_ERROR, $message, $context);
    }
    
    /**
     * Critical log
     */
    public static function critical(string $message, array $context = []): void
    {
        self::log(self::LEVEL_CRITICAL, $message, $context);
    }
    
    /**
     * Exception log
     * 
     * @param Throwable $exception
     * @param array $context Ek context
     */
    public static function exception(Throwable $exception, array $context = []): void
    {
        $context = array_merge($context, [
            'exception_class' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => self::formatTrace($exception->getTraceAsString()),
        ]);
        
        // AppException ise ek bilgileri al
        if ($exception instanceof AppException) {
            $context = array_merge($context, $exception->getContext());
        }
        
        self::error($exception->getMessage(), $context);
    }
    
    /**
     * HTTP request log
     */
    public static function request(string $message = '', array $context = []): void
    {
        $context = array_merge([
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN',
            'uri' => $_SERVER['REQUEST_URI'] ?? '/',
            'ip' => self::getClientIP(),
            'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 200),
        ], $context);
        
        self::info($message ?: 'HTTP Request', $context);
    }
    
    /**
     * Database query log
     */
    public static function query(string $sql, array $params = [], float $duration = 0): void
    {
        if (!defined('APP_DEBUG') || !APP_DEBUG) {
            return; // Production'da query log yapma
        }
        
        self::debug('Database Query', [
            'sql' => substr($sql, 0, 500),
            'params' => $params,
            'duration_ms' => round($duration * 1000, 2),
        ]);
    }
    
    /**
     * Security log (login, logout, failed attempts)
     */
    public static function security(string $event, array $context = []): void
    {
        $context = array_merge([
            'ip' => self::getClientIP(),
            'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 200),
        ], $context);
        
        self::log(self::LEVEL_WARNING, "SECURITY: {$event}", $context, 'security');
    }
    
    /**
     * Ana log fonksiyonu
     * 
     * @param string $level Log seviyesi
     * @param string $message Mesaj
     * @param array $context Ek veri
     * @param string $channel Log kanalı (dosya adı)
     */
    public static function log(string $level, string $message, array $context = [], string $channel = 'app'): void
    {
        // Seviye kontrolü
        if (self::$levelPriority[$level] < self::$levelPriority[self::$minLevel]) {
            return;
        }
        
        // Log dizini kontrolü
        if (empty(self::$logDir)) {
            self::init();
        }
        
        // Log formatı
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' ' . json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '';
        $logLine = "[{$timestamp}] [{$level}] {$message}{$contextStr}" . PHP_EOL;
        
        // Dosyaya yaz
        $logFile = self::$logDir . "/{$channel}-" . date('Y-m-d') . '.log';
        @file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
        
        // Critical hatalar için ayrıca error_log'a da yaz
        if ($level === self::LEVEL_CRITICAL || $level === self::LEVEL_ERROR) {
            error_log("[{$level}] {$message}");
        }
    }
    
    /**
     * Stack trace formatla
     */
    private static function formatTrace(string $trace): string
    {
        // Trace'i kısalt
        $lines = explode("\n", $trace);
        $formatted = array_slice($lines, 0, 10);
        return implode("\n", $formatted);
    }
    
    /**
     * Client IP al
     */
    private static function getClientIP(): string
    {
        $headers = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
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
     * Eski log dosyalarını temizle
     * 
     * @param int $days Kaç günden eski dosyalar silinecek
     */
    public static function cleanup(int $days = 30): int
    {
        if (empty(self::$logDir) || !is_dir(self::$logDir)) {
            return 0;
        }
        
        $deleted = 0;
        $threshold = time() - ($days * 86400);
        
        foreach (glob(self::$logDir . '/*.log') as $file) {
            if (filemtime($file) < $threshold) {
                if (@unlink($file)) {
                    $deleted++;
                }
            }
        }
        
        return $deleted;
    }
}

