<?php
/**
 * Logger Service
 * 
 * Simple logging service for the application
 * Production-ready logging with different levels and file rotation
 */

class Logger
{
    private $logPath;
    private $logLevel;
    private $maxFileSize = 10485760; // 10MB
    
    const DEBUG = 'DEBUG';
    const INFO = 'INFO';
    const WARNING = 'WARNING';
    const ERROR = 'ERROR';
    const CRITICAL = 'CRITICAL';
    
    private $levels = [
        self::DEBUG => 0,
        self::INFO => 1,
        self::WARNING => 2,
        self::ERROR => 3,
        self::CRITICAL => 4,
    ];
    
    public function __construct(string $logPath = null, string $logLevel = self::INFO)
    {
        $this->logPath = $logPath ?? __DIR__ . '/../../logs/app.log';
        $this->logLevel = $logLevel;
        
        // Create logs directory if it doesn't exist
        $logDir = dirname($this->logPath);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }
    
    /**
     * Log debug message
     */
    public function debug(string $message, array $context = []): void
    {
        $this->log(self::DEBUG, $message, $context);
    }
    
    /**
     * Log info message
     */
    public function info(string $message, array $context = []): void
    {
        $this->log(self::INFO, $message, $context);
    }
    
    /**
     * Log warning message
     */
    public function warning(string $message, array $context = []): void
    {
        $this->log(self::WARNING, $message, $context);
    }
    
    /**
     * Log error message
     */
    public function error(string $message, array $context = []): void
    {
        $this->log(self::ERROR, $message, $context);
    }
    
    /**
     * Log critical message
     */
    public function critical(string $message, array $context = []): void
    {
        $this->log(self::CRITICAL, $message, $context);
    }
    
    /**
     * Main logging method
     */
    private function log(string $level, string $message, array $context = []): void
    {
        // Check if this level should be logged
        if ($this->levels[$level] < $this->levels[$this->logLevel]) {
            return;
        }
        
        // Rotate log file if too large
        $this->rotateLogIfNeeded();
        
        // Format log entry
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' ' . json_encode($context) : '';
        $logEntry = "[{$timestamp}] {$level}: {$message}{$contextStr}" . PHP_EOL;
        
        // Write to log file
        file_put_contents($this->logPath, $logEntry, FILE_APPEND | LOCK_EX);
        
        // Also write to PHP error log in production
        if (APP_ENV === 'production' && $level >= self::ERROR) {
            error_log("[KhidmaApp] {$level}: {$message}");
        }
    }
    
    /**
     * Rotate log file if it exceeds max size
     */
    private function rotateLogIfNeeded(): void
    {
        if (!file_exists($this->logPath)) {
            return;
        }
        
        if (filesize($this->logPath) > $this->maxFileSize) {
            $rotatedPath = $this->logPath . '.' . date('Y-m-d_His');
            rename($this->logPath, $rotatedPath);
            
            // Keep only last 10 rotated logs
            $this->cleanOldLogs();
        }
    }
    
    /**
     * Clean old log files (keep last 10)
     */
    private function cleanOldLogs(): void
    {
        $logDir = dirname($this->logPath);
        $logFiles = glob($logDir . '/*.log.*');
        
        // Sort by modification time (oldest first)
        usort($logFiles, function($a, $b) {
            return filemtime($a) - filemtime($b);
        });
        
        // Delete old files (keep last 10)
        $filesToDelete = array_slice($logFiles, 0, -10);
        foreach ($filesToDelete as $file) {
            unlink($file);
        }
    }
    
    /**
     * Get log level name
     */
    public function getLogLevel(): string
    {
        return $this->logLevel;
    }
    
    /**
     * Set log level
     */
    public function setLogLevel(string $level): void
    {
        if (isset($this->levels[$level])) {
            $this->logLevel = $level;
        }
    }
}

/**
 * Global logger instance
 * 
 * @return Logger
 */
function logger(): Logger
{
    static $logger = null;
    
    if ($logger === null) {
        $logLevel = APP_DEBUG ? Logger::DEBUG : Logger::INFO;
        $logger = new Logger(null, $logLevel);
    }
    
    return $logger;
}



