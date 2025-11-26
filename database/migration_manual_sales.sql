-- ============================================
-- MANUEL SATIŞ PAKETLERİ MIGRATION
-- ============================================
-- Purpose: Add currency and payment_link support for manual WhatsApp sales
-- Date: 2025-11-26
-- ============================================

-- lead_packages tablosuna yeni alanlar ekle
ALTER TABLE lead_packages
ADD COLUMN IF NOT EXISTS currency VARCHAR(10) DEFAULT 'SAR' COMMENT 'Currency: SAR, USD, EUR' AFTER price_sar,
ADD COLUMN IF NOT EXISTS stripe_payment_link VARCHAR(255) NULL COMMENT 'Stripe Payment Link URL for WhatsApp sharing' AFTER stripe_price_id,
ADD COLUMN IF NOT EXISTS is_manual_sale BOOLEAN DEFAULT FALSE COMMENT 'Is this a manual sale package (WhatsApp)' AFTER is_active;

-- Manuel Satış paketleri ekle (USD)
INSERT INTO lead_packages (
    service_type, lead_count, price_sar, currency, price_per_lead, 
    name_ar, name_tr, description_ar, description_tr,
    discount_percentage, display_order, is_active, is_manual_sale
) VALUES
-- 49 USD Paketi
('manual_sale', 1, 49.00, 'USD', 49.00, 
 'حزمة 49 دولار', 'Manuel Satış - $49', 
 'حزمة مخصصة للمبيعات المباشرة', 'WhatsApp üzerinden manuel satış için',
 0, 100, 1, 1),

-- 99 USD Paketi  
('manual_sale', 2, 99.00, 'USD', 49.50,
 'حزمة 99 دولار', 'Manuel Satış - $99',
 'حزمة مخصصة للمبيعات المباشرة', 'WhatsApp üzerinden manuel satış için',
 0, 101, 1, 1),

-- 149 USD Paketi
('manual_sale', 3, 149.00, 'USD', 49.67,
 'حزمة 149 دولار', 'Manuel Satış - $149',
 'حزمة مخصصة للمبيعات المباشرة', 'WhatsApp üzerinden manuel satış için',
 0, 102, 1, 1);

-- Success message
SELECT 'Manual sales packages created successfully!' AS Status;
SELECT * FROM lead_packages WHERE service_type = 'manual_sale';

