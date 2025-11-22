-- ============================================
-- LEADS PACKAGES SYSTEM MIGRATION
-- ============================================
-- Purpose: Create tables for lead packages and purchases
-- Date: 2025-11-14
-- ============================================

USE khidmaapp;

-- Create leads_packages table (paket tanımları)
CREATE TABLE IF NOT EXISTS leads_packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_ar VARCHAR(100) NOT NULL COMMENT 'Package name in Arabic',
    name_tr VARCHAR(100) NOT NULL COMMENT 'Package name in Turkish',
    lead_count INT NOT NULL COMMENT 'Number of leads in package',
    price DECIMAL(10,2) NOT NULL COMMENT 'Price in SAR',
    description_ar TEXT NULL COMMENT 'Package description in Arabic',
    description_tr TEXT NULL COMMENT 'Package description in Turkish',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Is package available for purchase',
    display_order INT DEFAULT 0 COMMENT 'Display order (lower = first)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_is_active (is_active),
    INDEX idx_display_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Lead packages (1 lead, 3 leads, 5 leads)';

-- Create provider_purchases table (satın alımlar)
CREATE TABLE IF NOT EXISTS provider_purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider_id INT NOT NULL COMMENT 'Foreign key to service_providers',
    package_id INT NOT NULL COMMENT 'Foreign key to leads_packages',
    
    -- Package snapshot (satın alındığı andaki bilgiler)
    package_name VARCHAR(100) NOT NULL,
    leads_count INT NOT NULL COMMENT 'Total leads purchased',
    price_paid DECIMAL(10,2) NOT NULL COMMENT 'Price paid at purchase time',
    
    -- Usage tracking
    used_leads INT DEFAULT 0 COMMENT 'Number of leads already used',
    remaining_leads INT NOT NULL COMMENT 'Remaining leads',
    
    -- Payment info
    payment_method VARCHAR(50) NULL COMMENT 'Payment method used',
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    transaction_id VARCHAR(255) NULL COMMENT 'Payment gateway transaction ID',
    
    -- Dates
    purchased_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL COMMENT 'Package expiration date (if any)',
    
    INDEX idx_provider_id (provider_id),
    INDEX idx_package_id (package_id),
    INDEX idx_payment_status (payment_status),
    INDEX idx_purchased_at (purchased_at),
    
    FOREIGN KEY (provider_id) REFERENCES service_providers(id) ON DELETE CASCADE,
    FOREIGN KEY (package_id) REFERENCES leads_packages(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Provider lead package purchases';

-- Create provider_lead_views table (lead görüntüleme takibi)
CREATE TABLE IF NOT EXISTS provider_lead_views (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider_id INT NOT NULL,
    lead_id INT UNSIGNED NOT NULL,
    purchase_id INT NOT NULL COMMENT 'Which purchase was used for this view',
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_provider_id (provider_id),
    INDEX idx_lead_id (lead_id),
    INDEX idx_purchase_id (purchase_id),
    INDEX idx_viewed_at (viewed_at),
    
    FOREIGN KEY (provider_id) REFERENCES service_providers(id) ON DELETE CASCADE,
    FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE,
    FOREIGN KEY (purchase_id) REFERENCES provider_purchases(id) ON DELETE CASCADE,
    
    UNIQUE KEY unique_provider_lead (provider_id, lead_id) COMMENT 'Each provider can view a lead only once'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Track which leads each provider has viewed/purchased';

-- Insert default packages
INSERT INTO leads_packages (name_ar, name_tr, lead_count, price, description_ar, description_tr, display_order) VALUES
('حزمة طلب واحد', '1 Lead Paketi', 1, 50.00, 'احصل على طلب خدمة واحد فقط', 'Tek bir müşteri talebi', 1),
('حزمة 3 طلبات', '3 Lead Paketi', 3, 120.00, 'احصل على 3 طلبات خدمة - وفر 30 ريال', '3 müşteri talebi - 30 SAR tasarruf', 2),
('حزمة 5 طلبات', '5 Lead Paketi', 5, 180.00, 'احصل على 5 طلبات خدمة - وفر 70 ريال', '5 müşteri talebi - 70 SAR tasarruf', 3)
ON DUPLICATE KEY UPDATE id=id;

-- Success message
SELECT 'Leads packages system created successfully!' AS Status;
SELECT COUNT(*) AS Total_Packages FROM leads_packages;


-- ============================================
-- Purpose: Create tables for lead packages and purchases
-- Date: 2025-11-14
-- ============================================

USE khidmaapp;

-- Create leads_packages table (paket tanımları)
CREATE TABLE IF NOT EXISTS leads_packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_ar VARCHAR(100) NOT NULL COMMENT 'Package name in Arabic',
    name_tr VARCHAR(100) NOT NULL COMMENT 'Package name in Turkish',
    lead_count INT NOT NULL COMMENT 'Number of leads in package',
    price DECIMAL(10,2) NOT NULL COMMENT 'Price in SAR',
    description_ar TEXT NULL COMMENT 'Package description in Arabic',
    description_tr TEXT NULL COMMENT 'Package description in Turkish',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Is package available for purchase',
    display_order INT DEFAULT 0 COMMENT 'Display order (lower = first)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_is_active (is_active),
    INDEX idx_display_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Lead packages (1 lead, 3 leads, 5 leads)';

-- Create provider_purchases table (satın alımlar)
CREATE TABLE IF NOT EXISTS provider_purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider_id INT NOT NULL COMMENT 'Foreign key to service_providers',
    package_id INT NOT NULL COMMENT 'Foreign key to leads_packages',
    
    -- Package snapshot (satın alındığı andaki bilgiler)
    package_name VARCHAR(100) NOT NULL,
    leads_count INT NOT NULL COMMENT 'Total leads purchased',
    price_paid DECIMAL(10,2) NOT NULL COMMENT 'Price paid at purchase time',
    
    -- Usage tracking
    used_leads INT DEFAULT 0 COMMENT 'Number of leads already used',
    remaining_leads INT NOT NULL COMMENT 'Remaining leads',
    
    -- Payment info
    payment_method VARCHAR(50) NULL COMMENT 'Payment method used',
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    transaction_id VARCHAR(255) NULL COMMENT 'Payment gateway transaction ID',
    
    -- Dates
    purchased_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL COMMENT 'Package expiration date (if any)',
    
    INDEX idx_provider_id (provider_id),
    INDEX idx_package_id (package_id),
    INDEX idx_payment_status (payment_status),
    INDEX idx_purchased_at (purchased_at),
    
    FOREIGN KEY (provider_id) REFERENCES service_providers(id) ON DELETE CASCADE,
    FOREIGN KEY (package_id) REFERENCES leads_packages(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Provider lead package purchases';

-- Create provider_lead_views table (lead görüntüleme takibi)
CREATE TABLE IF NOT EXISTS provider_lead_views (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider_id INT NOT NULL,
    lead_id INT UNSIGNED NOT NULL,
    purchase_id INT NOT NULL COMMENT 'Which purchase was used for this view',
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_provider_id (provider_id),
    INDEX idx_lead_id (lead_id),
    INDEX idx_purchase_id (purchase_id),
    INDEX idx_viewed_at (viewed_at),
    
    FOREIGN KEY (provider_id) REFERENCES service_providers(id) ON DELETE CASCADE,
    FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE,
    FOREIGN KEY (purchase_id) REFERENCES provider_purchases(id) ON DELETE CASCADE,
    
    UNIQUE KEY unique_provider_lead (provider_id, lead_id) COMMENT 'Each provider can view a lead only once'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Track which leads each provider has viewed/purchased';

-- Insert default packages
INSERT INTO leads_packages (name_ar, name_tr, lead_count, price, description_ar, description_tr, display_order) VALUES
('حزمة طلب واحد', '1 Lead Paketi', 1, 50.00, 'احصل على طلب خدمة واحد فقط', 'Tek bir müşteri talebi', 1),
('حزمة 3 طلبات', '3 Lead Paketi', 3, 120.00, 'احصل على 3 طلبات خدمة - وفر 30 ريال', '3 müşteri talebi - 30 SAR tasarruf', 2),
('حزمة 5 طلبات', '5 Lead Paketi', 5, 180.00, 'احصل على 5 طلبات خدمة - وفر 70 ريال', '5 müşteri talebi - 70 SAR tasarruf', 3)
ON DUPLICATE KEY UPDATE id=id;

-- Success message
SELECT 'Leads packages system created successfully!' AS Status;
SELECT COUNT(*) AS Total_Packages FROM leads_packages;



