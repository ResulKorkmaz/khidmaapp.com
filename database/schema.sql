-- KhidmaApp.com Database Schema
-- Lead satış platformu için MySQL veritabanı tabloları

-- Veritabanını oluştur (manuel olarak çalıştırılacak)
-- CREATE DATABASE khidmaapp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE khidmaapp;

-- 1. Leads tablosu - Müşteri talepleri
CREATE TABLE leads (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_type VARCHAR(50) NOT NULL COMMENT 'Hizmet türü: paint, renovation, cleaning, ac, plumbing, electric',
    city VARCHAR(100) NOT NULL COMMENT 'Şehir: Riyadh, Jeddah, Mecca, vb.',
    description TEXT COMMENT 'İş açıklaması',
    phone VARCHAR(30) NOT NULL COMMENT 'Telefon numarası (zorunlu)',
    whatsapp_phone VARCHAR(30) NULL COMMENT 'WhatsApp numarası (opsiyonel)',
    budget_min INT NULL COMMENT 'Minimum bütçe (SAR)',
    budget_max INT NULL COMMENT 'Maksimum bütçe (SAR)',
    service_time_type ENUM('urgent', 'within_24h', 'scheduled') NULL COMMENT 'Hizmet zamanı tipi: urgent=acil, within_24h=24 saat içinde, scheduled=planlı',
    scheduled_date DATE NULL COMMENT 'Planlı hizmet tarihi',
    source VARCHAR(50) DEFAULT 'website' COMMENT 'Lead kaynağı: website, whatsapp, form',
    status ENUM('new', 'verified', 'pending', 'sold', 'invalid') DEFAULT 'new' COMMENT 'Lead durumu',
    previous_status ENUM('new', 'verified', 'pending', 'sold', 'invalid') NULL COMMENT 'Ustaya göndermeden önceki durum',
    invalid_at TIMESTAMP NULL COMMENT 'Geçersiz olarak işaretlenme tarihi (24 saat sonra otomatik silinir)',
    is_sent_to_provider BOOLEAN DEFAULT FALSE COMMENT 'Ustaya gönderildi mi? (kopyalandı mı?)',
    sent_at TIMESTAMP NULL COMMENT 'Ustaya gönderilme tarihi',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    
    -- İndeksler
    INDEX idx_service_type (service_type),
    INDEX idx_city (city),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    INDEX idx_invalid_status_date (status, invalid_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Müşteri talepleri (leads)';

-- 2. Admins tablosu - Yönetici hesapları
CREATE TABLE admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE COMMENT 'Kullanıcı adı',
    password_hash VARCHAR(255) NOT NULL COMMENT 'Şifrelenmiş parola',
    email VARCHAR(100) NULL COMMENT 'E-posta adresi',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Aktif durum',
    last_login TIMESTAMP NULL COMMENT 'Son giriş zamanı',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    
    -- İndeksler
    INDEX idx_username (username),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Admin kullanıcıları';

-- Varsayılan admin hesabı oluştur
-- Parola: admin123 (değiştirilmesi zorunlu)
INSERT INTO admins (username, password_hash, email, is_active) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@khidmaapp.com', TRUE);

-- Test lead verisi (opsiyonel)
INSERT INTO leads (service_type, city, description, phone, source) VALUES 
('paint', 'Riyadh', 'منزل بحاجة إلى طلاء داخلي وخارجي', '+966501234567', 'website'),
('cleaning', 'Jeddah', 'تنظيف شقة بعد الانتقال', '+966509876543', 'website'),
('ac', 'Mecca', 'صيانة وتنظيف أجهزة التكييف', '+966551122334', 'website');



-- Veritabanını oluştur (manuel olarak çalıştırılacak)
-- CREATE DATABASE khidmaapp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE khidmaapp;

-- 1. Leads tablosu - Müşteri talepleri
CREATE TABLE leads (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_type VARCHAR(50) NOT NULL COMMENT 'Hizmet türü: paint, renovation, cleaning, ac, plumbing, electric',
    city VARCHAR(100) NOT NULL COMMENT 'Şehir: Riyadh, Jeddah, Mecca, vb.',
    description TEXT COMMENT 'İş açıklaması',
    phone VARCHAR(30) NOT NULL COMMENT 'Telefon numarası (zorunlu)',
    whatsapp_phone VARCHAR(30) NULL COMMENT 'WhatsApp numarası (opsiyonel)',
    budget_min INT NULL COMMENT 'Minimum bütçe (SAR)',
    budget_max INT NULL COMMENT 'Maksimum bütçe (SAR)',
    service_time_type ENUM('urgent', 'within_24h', 'scheduled') NULL COMMENT 'Hizmet zamanı tipi: urgent=acil, within_24h=24 saat içinde, scheduled=planlı',
    scheduled_date DATE NULL COMMENT 'Planlı hizmet tarihi',
    source VARCHAR(50) DEFAULT 'website' COMMENT 'Lead kaynağı: website, whatsapp, form',
    status ENUM('new', 'verified', 'pending', 'sold', 'invalid') DEFAULT 'new' COMMENT 'Lead durumu',
    previous_status ENUM('new', 'verified', 'pending', 'sold', 'invalid') NULL COMMENT 'Ustaya göndermeden önceki durum',
    invalid_at TIMESTAMP NULL COMMENT 'Geçersiz olarak işaretlenme tarihi (24 saat sonra otomatik silinir)',
    is_sent_to_provider BOOLEAN DEFAULT FALSE COMMENT 'Ustaya gönderildi mi? (kopyalandı mı?)',
    sent_at TIMESTAMP NULL COMMENT 'Ustaya gönderilme tarihi',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    
    -- İndeksler
    INDEX idx_service_type (service_type),
    INDEX idx_city (city),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    INDEX idx_invalid_status_date (status, invalid_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Müşteri talepleri (leads)';

-- 2. Admins tablosu - Yönetici hesapları
CREATE TABLE admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE COMMENT 'Kullanıcı adı',
    password_hash VARCHAR(255) NOT NULL COMMENT 'Şifrelenmiş parola',
    email VARCHAR(100) NULL COMMENT 'E-posta adresi',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Aktif durum',
    last_login TIMESTAMP NULL COMMENT 'Son giriş zamanı',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    
    -- İndeksler
    INDEX idx_username (username),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Admin kullanıcıları';

-- Varsayılan admin hesabı oluştur
-- Parola: admin123 (değiştirilmesi zorunlu)
INSERT INTO admins (username, password_hash, email, is_active) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@khidmaapp.com', TRUE);

-- Test lead verisi (opsiyonel)
INSERT INTO leads (service_type, city, description, phone, source) VALUES 
('paint', 'Riyadh', 'منزل بحاجة إلى طلاء داخلي وخارجي', '+966501234567', 'website'),
('cleaning', 'Jeddah', 'تنظيف شقة بعد الانتقال', '+966509876543', 'website'),
('ac', 'Mecca', 'صيانة وتنظيف أجهزة التكييف', '+966551122334', 'website');



