-- Lead İstekleri Tablosu Migration
-- Usta "Lead İste" butonuna bastığında kayıt oluşturulur
-- Admin bu istekleri görür ve manuel olarak lead gönderir

CREATE TABLE IF NOT EXISTS lead_requests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    provider_id INT NOT NULL,
    purchase_id INT NOT NULL,
    request_status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    completed_by INT UNSIGNED NULL,
    lead_id INT UNSIGNED NULL,
    notes TEXT NULL,
    INDEX idx_status (request_status),
    INDEX idx_provider (provider_id),
    INDEX idx_purchase (purchase_id),
    INDEX idx_requested_at (requested_at),
    FOREIGN KEY (provider_id) REFERENCES service_providers(id) ON DELETE CASCADE,
    FOREIGN KEY (purchase_id) REFERENCES provider_purchases(id) ON DELETE CASCADE,
    FOREIGN KEY (completed_by) REFERENCES admins(id) ON DELETE SET NULL,
    FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Provider lead deliveries tablosuna kolonlar ekle (hata varsa devam et)
SET @query = IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = 'khidmaapp' 
     AND TABLE_NAME = 'provider_lead_deliveries' 
     AND COLUMN_NAME = 'request_id') = 0,
    'ALTER TABLE provider_lead_deliveries ADD COLUMN request_id INT UNSIGNED NULL AFTER lead_id',
    'SELECT "request_id already exists" AS info'
);
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @query = IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = 'khidmaapp' 
     AND TABLE_NAME = 'provider_lead_deliveries' 
     AND COLUMN_NAME = 'delivered_by') = 0,
    'ALTER TABLE provider_lead_deliveries ADD COLUMN delivered_by INT UNSIGNED NULL AFTER delivered_at',
    'SELECT "delivered_by already exists" AS info'
);
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Test verileri için yorum satırları
-- INSERT INTO lead_requests (provider_id, purchase_id, request_status, requested_at)
-- VALUES (3, 1, 'pending', NOW());

-- Son kontrol
SELECT 'lead_requests tablosu oluşturuldu' AS status;
SELECT COUNT(*) AS total_requests FROM lead_requests;


-- Admin bu istekleri görür ve manuel olarak lead gönderir

CREATE TABLE IF NOT EXISTS lead_requests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    provider_id INT NOT NULL,
    purchase_id INT NOT NULL,
    request_status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    completed_by INT UNSIGNED NULL,
    lead_id INT UNSIGNED NULL,
    notes TEXT NULL,
    INDEX idx_status (request_status),
    INDEX idx_provider (provider_id),
    INDEX idx_purchase (purchase_id),
    INDEX idx_requested_at (requested_at),
    FOREIGN KEY (provider_id) REFERENCES service_providers(id) ON DELETE CASCADE,
    FOREIGN KEY (purchase_id) REFERENCES provider_purchases(id) ON DELETE CASCADE,
    FOREIGN KEY (completed_by) REFERENCES admins(id) ON DELETE SET NULL,
    FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Provider lead deliveries tablosuna kolonlar ekle (hata varsa devam et)
SET @query = IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = 'khidmaapp' 
     AND TABLE_NAME = 'provider_lead_deliveries' 
     AND COLUMN_NAME = 'request_id') = 0,
    'ALTER TABLE provider_lead_deliveries ADD COLUMN request_id INT UNSIGNED NULL AFTER lead_id',
    'SELECT "request_id already exists" AS info'
);
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @query = IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = 'khidmaapp' 
     AND TABLE_NAME = 'provider_lead_deliveries' 
     AND COLUMN_NAME = 'delivered_by') = 0,
    'ALTER TABLE provider_lead_deliveries ADD COLUMN delivered_by INT UNSIGNED NULL AFTER delivered_at',
    'SELECT "delivered_by already exists" AS info'
);
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Test verileri için yorum satırları
-- INSERT INTO lead_requests (provider_id, purchase_id, request_status, requested_at)
-- VALUES (3, 1, 'pending', NOW());

-- Son kontrol
SELECT 'lead_requests tablosu oluşturuldu' AS status;
SELECT COUNT(*) AS total_requests FROM lead_requests;



