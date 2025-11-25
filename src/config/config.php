<?php
/**
 * KhidmaApp.com - Temel Konfigürasyon Dosyası
 * 
 * Bu dosya uygulamanın tüm temel ayarlarını içerir.
 * Veritabanı bağlantısı, güvenlik ayarları ve genel konfigürasyonlar burada tanımlanır.
 */

// Hata raporlama ayarları - Environment'a göre ayarla
error_reporting(E_ALL);

// Production'da hataları gösterme, sadece logla
if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'production') {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../../logs/php_errors.log');
} else {
ini_set('display_errors', 1);
}

// Karakter kodlaması
ini_set('default_charset', 'UTF-8');

/**
 * Basit .env dosyası okuyucu
 * .env dosyası yoksa varsayılan değerleri kullan
 */
function loadEnv($filePath = '.env') {
    $envFile = __DIR__ . '/../../' . $filePath;
    
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) continue; // Yorum satırları
            
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
            }
        }
    }
}

// .env dosyasını yükle
loadEnv();

/**
 * Çevre değişkeni oku (varsayılan değer ile)
 */
function env($key, $default = null) {
    return $_ENV[$key] ?? $default;
}

// =====================================================
// UYGULAMA AYARLARI
// =====================================================

// Temel Uygulama Ayarları
define('APP_ENV', env('APP_ENV', 'local'));
define('APP_DEBUG', env('APP_DEBUG', true));
define('APP_NAME', env('APP_NAME', 'KhidmaApp.com'));

// Veritabanı Ayarları
define('DB_HOST', env('DB_HOST', '127.0.0.1'));
define('DB_PORT', env('DB_PORT', '3306'));
define('DB_NAME', env('DB_NAME', 'khidmaapp'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));  // ⚠️ Set in .env file!

// URL Ayarları
define('BASE_URL', env('BASE_URL', 'http://localhost:8000'));
define('SITE_URL', env('SITE_URL', 'http://localhost:8000'));

// WhatsApp Entegrasyonu
define('WHATSAPP_CHANNEL_URL', env('WHATSAPP_CHANNEL_URL', 'https://whatsapp.com/channel/0029VbCCqZoI1rcjIn9IWV2l'));
define('WHATSAPP_BUSINESS_NUMBER', env('WHATSAPP_BUSINESS_NUMBER', '+966501234567'));

// Site Bilgileri
define('SITE_TITLE_AR', env('SITE_TITLE_AR', 'خدمة | منصة طلب الخدمات'));
define('SITE_TITLE_EN', env('SITE_TITLE_EN', 'KhidmaApp | Service Request Platform'));
define('CONTACT_PHONE', env('CONTACT_PHONE', '+966501234567'));
define('CONTACT_EMAIL', env('CONTACT_EMAIL', 'info@khidmaapp.com'));

// Güvenlik Ayarları
define('SESSION_LIFETIME', env('SESSION_LIFETIME', 120) * 60); // dakika * 60
define('CSRF_TOKEN_EXPIRE', env('CSRF_TOKEN_EXPIRE', 3600));

// Stripe Ayarları
// NOTE: Stripe constant'ları src/config/stripe.php dosyasında tanımlanıyor
// O dosyayı include ettiğinde otomatik yüklenecekler

// =====================================================
// VERİTABANI BAĞLANTISI
// =====================================================

/**
 * Veritabanı Bağlantısı (PDO)
 * Güvenli prepared statement kullanımı için
 */
function getDatabase() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Log hatayı ama output üretme (JSON response'u bozmamak için)
            error_log("Database connection failed: " . $e->getMessage());
            
            if (APP_DEBUG) {
                // Debug modunda bile output üretme, sadece log'a yaz
                error_log("⚠️ WARNING: Database connection failed but app continues without DB");
            }
            
            return null; // Return null, throw exception yok
        }
    }
    
    return $pdo;
}

// =====================================================
// HELPER FUNCTIONS
// =====================================================

// Tüm helper fonksiyonları ayrı dosyadan yükle
require_once __DIR__ . '/helpers.php';

// Otomatik session başlat
startSession();
