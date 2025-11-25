<?php
/**
 * Cache Service
 * 
 * File-based cache sistemi.
 * Sık kullanılan sorguları cache'leyerek performansı artırır.
 * 
 * @package KhidmaApp
 * @since 1.0.0
 */

class CacheService
{
    private static $instance = null;
    private $cacheDir;
    private $defaultTtl = 3600; // 1 saat
    private $enabled = true;
    
    // Cache key prefixes
    const PREFIX_STATS = 'stats_';
    const PREFIX_SERVICE = 'service_';
    const PREFIX_CITY = 'city_';
    const PREFIX_PACKAGE = 'package_';
    const PREFIX_PROVIDER = 'provider_';
    const PREFIX_LEAD = 'lead_';
    
    /**
     * Constructor
     */
    public function __construct(?string $cacheDir = null)
    {
        $this->cacheDir = $cacheDir ?? __DIR__ . '/../../storage/cache';
        $this->ensureCacheDirectory();
        
        // Development modunda cache'i devre dışı bırakabilirsiniz
        $this->enabled = env('CACHE_ENABLED', 'true') !== 'false';
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
     * Cache klasörünü oluştur
     */
    private function ensureCacheDirectory(): void
    {
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
            
            // .gitignore ekle
            file_put_contents($this->cacheDir . '/.gitignore', "*\n!.gitignore\n");
        }
    }
    
    /**
     * Cache dosya yolunu al
     */
    private function getCacheFilePath(string $key): string
    {
        $hash = md5($key);
        $subDir = substr($hash, 0, 2);
        
        $dir = $this->cacheDir . '/' . $subDir;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        return $dir . '/' . $hash . '.cache';
    }
    
    /**
     * Cache'e değer kaydet
     */
    public function set(string $key, $value, ?int $ttl = null): bool
    {
        if (!$this->enabled) {
            return false;
        }
        
        $ttl = $ttl ?? $this->defaultTtl;
        $expiry = time() + $ttl;
        
        $data = [
            'key' => $key,
            'value' => $value,
            'expiry' => $expiry,
            'created_at' => time()
        ];
        
        $filePath = $this->getCacheFilePath($key);
        
        try {
            $content = serialize($data);
            return file_put_contents($filePath, $content, LOCK_EX) !== false;
        } catch (Exception $e) {
            error_log("CacheService::set error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cache'den değer al
     */
    public function get(string $key, $default = null)
    {
        if (!$this->enabled) {
            return $default;
        }
        
        $filePath = $this->getCacheFilePath($key);
        
        if (!file_exists($filePath)) {
            return $default;
        }
        
        try {
            $content = file_get_contents($filePath);
            $data = unserialize($content);
            
            // Süresi dolmuş mu?
            if ($data['expiry'] < time()) {
                $this->delete($key);
                return $default;
            }
            
            return $data['value'];
        } catch (Exception $e) {
            error_log("CacheService::get error: " . $e->getMessage());
            return $default;
        }
    }
    
    /**
     * Cache'de var mı?
     */
    public function has(string $key): bool
    {
        return $this->get($key) !== null;
    }
    
    /**
     * Cache'den sil
     */
    public function delete(string $key): bool
    {
        $filePath = $this->getCacheFilePath($key);
        
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        
        return true;
    }
    
    /**
     * Prefix ile başlayan tüm cache'leri sil
     */
    public function deleteByPrefix(string $prefix): int
    {
        $deleted = 0;
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->cacheDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'cache') {
                try {
                    $content = file_get_contents($file->getPathname());
                    $data = unserialize($content);
                    
                    if (isset($data['key']) && strpos($data['key'], $prefix) === 0) {
                        unlink($file->getPathname());
                        $deleted++;
                    }
                } catch (Exception $e) {
                    // Bozuk cache dosyasını sil
                    unlink($file->getPathname());
                    $deleted++;
                }
            }
        }
        
        return $deleted;
    }
    
    /**
     * Tüm cache'i temizle
     */
    public function flush(): bool
    {
        try {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($this->cacheDir, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'cache') {
                    unlink($file->getPathname());
                }
            }
            
            return true;
        } catch (Exception $e) {
            error_log("CacheService::flush error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Süresi dolmuş cache'leri temizle
     */
    public function gc(): int
    {
        $cleaned = 0;
        
        try {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($this->cacheDir, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'cache') {
                    try {
                        $content = file_get_contents($file->getPathname());
                        $data = unserialize($content);
                        
                        if (!isset($data['expiry']) || $data['expiry'] < time()) {
                            unlink($file->getPathname());
                            $cleaned++;
                        }
                    } catch (Exception $e) {
                        // Bozuk dosyayı sil
                        unlink($file->getPathname());
                        $cleaned++;
                    }
                }
            }
        } catch (Exception $e) {
            error_log("CacheService::gc error: " . $e->getMessage());
        }
        
        return $cleaned;
    }
    
    /**
     * Remember pattern - yoksa hesapla ve cache'le
     */
    public function remember(string $key, int $ttl, callable $callback)
    {
        $value = $this->get($key);
        
        if ($value !== null) {
            return $value;
        }
        
        $value = $callback();
        $this->set($key, $value, $ttl);
        
        return $value;
    }
    
    /**
     * Cache istatistikleri
     */
    public function getStats(): array
    {
        $stats = [
            'total_files' => 0,
            'total_size' => 0,
            'expired' => 0,
            'valid' => 0
        ];
        
        try {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($this->cacheDir, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'cache') {
                    $stats['total_files']++;
                    $stats['total_size'] += $file->getSize();
                    
                    try {
                        $content = file_get_contents($file->getPathname());
                        $data = unserialize($content);
                        
                        if (isset($data['expiry']) && $data['expiry'] < time()) {
                            $stats['expired']++;
                        } else {
                            $stats['valid']++;
                        }
                    } catch (Exception $e) {
                        $stats['expired']++;
                    }
                }
            }
        } catch (Exception $e) {
            error_log("CacheService::getStats error: " . $e->getMessage());
        }
        
        $stats['total_size_formatted'] = $this->formatBytes($stats['total_size']);
        
        return $stats;
    }
    
    /**
     * Byte'ları okunabilir formata çevir
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
    
    /**
     * Cache'i etkinleştir/devre dışı bırak
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
    
    /**
     * Cache etkin mi?
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}

// ==========================================
// GLOBAL HELPER FUNCTIONS
// ==========================================

/**
 * Cache instance'ını al
 */
function cache(): CacheService {
    return CacheService::getInstance();
}

/**
 * Cache'e değer kaydet
 */
function cache_set(string $key, $value, ?int $ttl = null): bool {
    return CacheService::getInstance()->set($key, $value, $ttl);
}

/**
 * Cache'den değer al
 */
function cache_get(string $key, $default = null) {
    return CacheService::getInstance()->get($key, $default);
}

/**
 * Cache'den sil
 */
function cache_delete(string $key): bool {
    return CacheService::getInstance()->delete($key);
}

/**
 * Remember pattern
 */
function cache_remember(string $key, int $ttl, callable $callback) {
    return CacheService::getInstance()->remember($key, $ttl, $callback);
}

/**
 * Tüm cache'i temizle
 */
function cache_flush(): bool {
    return CacheService::getInstance()->flush();
}

