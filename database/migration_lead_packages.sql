-- Lead Paketleri Tablosu
CREATE TABLE IF NOT EXISTS lead_packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_type VARCHAR(50) NOT NULL,
    lead_count INT NOT NULL,
    price_sar DECIMAL(10,2) NOT NULL,
    price_per_lead DECIMAL(10,2) NOT NULL,
    stripe_price_id VARCHAR(100) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_package (service_type, lead_count),
    INDEX idx_service_type (service_type),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Mevcut paketleri ekle
INSERT INTO lead_packages (service_type, lead_count, price_sar, price_per_lead) VALUES
-- Elektrik (كهرباء)
('electrician', 1, 120.00, 120.00),
('electrician', 3, 324.00, 108.00),

-- Su Tesisat (سباكة)
('plumbing', 1, 150.00, 150.00),
('plumbing', 3, 405.00, 135.00),

-- Boya (دهانات)
('painting', 1, 200.00, 200.00),
('painting', 3, 540.00, 180.00),

-- Tadilat (ترميم)
('renovation', 1, 350.00, 350.00),
('renovation', 3, 945.00, 315.00),

-- Klima (مكيفات)
('ac_repair', 1, 120.00, 120.00),
('ac_repair', 3, 324.00, 108.00),

-- Temizlik (تنظيف)
('cleaning', 1, 70.00, 70.00),
('cleaning', 3, 189.00, 63.00);

-- provider_purchases tablosuna stripe bilgileri ekle (sadece eksik olanlar)
ALTER TABLE provider_purchases
ADD COLUMN stripe_session_id VARCHAR(100) NULL AFTER package_id,
ADD COLUMN stripe_payment_intent VARCHAR(100) NULL AFTER stripe_session_id,
ADD COLUMN currency VARCHAR(10) DEFAULT 'SAR' AFTER transaction_id,
ADD INDEX idx_stripe_session (stripe_session_id);


    id INT AUTO_INCREMENT PRIMARY KEY,
    service_type VARCHAR(50) NOT NULL,
    lead_count INT NOT NULL,
    price_sar DECIMAL(10,2) NOT NULL,
    price_per_lead DECIMAL(10,2) NOT NULL,
    stripe_price_id VARCHAR(100) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_package (service_type, lead_count),
    INDEX idx_service_type (service_type),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Mevcut paketleri ekle
INSERT INTO lead_packages (service_type, lead_count, price_sar, price_per_lead) VALUES
-- Elektrik (كهرباء)
('electrician', 1, 120.00, 120.00),
('electrician', 3, 324.00, 108.00),

-- Su Tesisat (سباكة)
('plumbing', 1, 150.00, 150.00),
('plumbing', 3, 405.00, 135.00),

-- Boya (دهانات)
('painting', 1, 200.00, 200.00),
('painting', 3, 540.00, 180.00),

-- Tadilat (ترميم)
('renovation', 1, 350.00, 350.00),
('renovation', 3, 945.00, 315.00),

-- Klima (مكيفات)
('ac_repair', 1, 120.00, 120.00),
('ac_repair', 3, 324.00, 108.00),

-- Temizlik (تنظيف)
('cleaning', 1, 70.00, 70.00),
('cleaning', 3, 189.00, 63.00);

-- provider_purchases tablosuna stripe bilgileri ekle (sadece eksik olanlar)
ALTER TABLE provider_purchases
ADD COLUMN stripe_session_id VARCHAR(100) NULL AFTER package_id,
ADD COLUMN stripe_payment_intent VARCHAR(100) NULL AFTER stripe_session_id,
ADD COLUMN currency VARCHAR(10) DEFAULT 'SAR' AFTER transaction_id,
ADD INDEX idx_stripe_session (stripe_session_id);



