-- =====================================================
-- BASIT PAKET SİSTEMİ
-- Sadece 2 paket: 1 Lead ve 3 Lead
-- Tüm hizmet türleri için aynı fiyat
-- =====================================================

-- Mevcut tabloyu temizle ve yeniden oluştur
DROP TABLE IF EXISTS lead_packages;

CREATE TABLE lead_packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lead_count INT NOT NULL UNIQUE,
    price_sar DECIMAL(10,2) NOT NULL,
    price_per_lead DECIMAL(10,2) NOT NULL,
    name_ar VARCHAR(100) NOT NULL,
    name_tr VARCHAR(100) NOT NULL,
    description_ar TEXT NULL,
    description_tr TEXT NULL,
    discount_percentage DECIMAL(5,2) DEFAULT 0,
    stripe_product_id VARCHAR(100) NULL,
    stripe_price_id VARCHAR(100) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_active (is_active),
    INDEX idx_display_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sadece 2 paket ekle
INSERT INTO lead_packages (lead_count, price_sar, price_per_lead, name_ar, name_tr, description_ar, description_tr, discount_percentage, display_order, is_active) VALUES
-- 1 Lead Paketi
(1, 150.00, 150.00, 
 'حزمة طلب واحد', 
 '1 Lead Paketi',
 'احصل على طلب عميل واحد مع معلومات الاتصال الكاملة. مثالي للتجربة الأولى.',
 'Tam iletişim bilgileri ile 1 müşteri talebi alın. İlk deneme için ideal.',
 0, 1, 1),

-- 3 Lead Paketi (indirimli)
(3, 400.00, 133.33, 
 'حزمة 3 طلبات', 
 '3 Lead Paketi',
 'احصل على 3 طلبات عملاء بسعر مخفض. وفر 10% مقارنة بالشراء الفردي!',
 '3 müşteri talebini indirimli fiyata alın. Tekli satın almaya göre %10 tasarruf edin!',
 10, 2, 1);

-- =====================================================
-- NOT: Bu migration'ı çalıştırmadan önce:
-- 1. Mevcut provider_purchases tablosundaki package_id referansları bozulabilir
-- 2. Önce yedek alın
-- 3. Test ortamında deneyin
-- =====================================================

