-- ================================================
-- PURCHASE MANAGEMENT SYSTEM
-- Satın alma yönetimi için gerekli alanlar
-- ================================================

-- 1. Order ID (Benzersiz Sipariş Numarası) ekle
ALTER TABLE provider_purchases 
ADD COLUMN order_id VARCHAR(50) UNIQUE NULL AFTER id;

-- 2. Status (Durum) ekle
ALTER TABLE provider_purchases 
ADD COLUMN status ENUM('active', 'cancelled', 'refunded') DEFAULT 'active' AFTER payment_status;

-- 3. Cancel (İptal) alanları ekle
ALTER TABLE provider_purchases 
ADD COLUMN cancel_reason ENUM('customer_request', 'admin_decision', 'technical_issue', 'fraud', 'other') NULL AFTER status;

ALTER TABLE provider_purchases 
ADD COLUMN cancel_notes TEXT NULL AFTER cancel_reason;

ALTER TABLE provider_purchases 
ADD COLUMN cancelled_at TIMESTAMP NULL AFTER cancel_notes;

ALTER TABLE provider_purchases 
ADD COLUMN cancelled_by INT UNSIGNED NULL AFTER cancelled_at;

-- 4. Refund (İade) alanları ekle
ALTER TABLE provider_purchases 
ADD COLUMN refund_status ENUM('not_requested', 'pending', 'document_uploaded', 'completed') DEFAULT 'not_requested' AFTER cancelled_by;

ALTER TABLE provider_purchases 
ADD COLUMN refund_document VARCHAR(255) NULL AFTER refund_status;

ALTER TABLE provider_purchases 
ADD COLUMN refund_notes TEXT NULL AFTER refund_document;

ALTER TABLE provider_purchases 
ADD COLUMN refund_completed_at TIMESTAMP NULL AFTER refund_notes;

ALTER TABLE provider_purchases 
ADD COLUMN refund_completed_by INT UNSIGNED NULL AFTER refund_completed_at;

-- 5. Foreign keys ekle
ALTER TABLE provider_purchases 
ADD CONSTRAINT fk_cancelled_by 
FOREIGN KEY (cancelled_by) REFERENCES admins(id) ON DELETE SET NULL;

ALTER TABLE provider_purchases 
ADD CONSTRAINT fk_refund_completed_by 
FOREIGN KEY (refund_completed_by) REFERENCES admins(id) ON DELETE SET NULL;

-- 6. Indexes ekle
CREATE INDEX idx_order_id ON provider_purchases(order_id);
CREATE INDEX idx_status ON provider_purchases(status);
CREATE INDEX idx_refund_status ON provider_purchases(refund_status);
CREATE INDEX idx_cancelled_at ON provider_purchases(cancelled_at);

-- 7. Mevcut kayıtlara order_id oluştur
UPDATE provider_purchases 
SET order_id = CONCAT('ORD-', DATE_FORMAT(purchased_at, '%Y%m%d'), '-', LPAD(id, 6, '0'))
WHERE order_id IS NULL;

-- 8. order_id'yi NOT NULL yap
ALTER TABLE provider_purchases 
MODIFY COLUMN order_id VARCHAR(50) NOT NULL UNIQUE;

-- ================================================
-- AÇIKLAMALAR
-- ================================================

-- order_id: Benzersiz sipariş numarası (örn: ORD-20231117-000001)
-- status: active (aktif), cancelled (iptal edildi), refunded (iade tamamlandı)
-- cancel_reason: İptal sebebi
--   - customer_request: Müşteri talebi
--   - admin_decision: Admin kararı
--   - technical_issue: Teknik sorun
--   - fraud: Dolandırıcılık
--   - other: Diğer
-- cancel_notes: İptal notu (opsiyonel açıklama)
-- cancelled_at: İptal tarihi
-- cancelled_by: İptal eden admin ID
-- refund_status: İade durumu
--   - not_requested: İade talep edilmedi
--   - pending: İade bekleniyor
--   - document_uploaded: İade belgesi yüklendi
--   - completed: İade tamamlandı
-- refund_document: İade belgesi dosya yolu
-- refund_notes: İade notu
-- refund_completed_at: İade tamamlanma tarihi
-- refund_completed_by: İadeyi tamamlayan admin ID

-- ================================================
-- ROLLBACK (Geri alma)
-- ================================================

-- Migration'ı geri almak için:
/*
ALTER TABLE provider_purchases DROP FOREIGN KEY fk_cancelled_by;
ALTER TABLE provider_purchases DROP FOREIGN KEY fk_refund_completed_by;
ALTER TABLE provider_purchases DROP INDEX idx_order_id;
ALTER TABLE provider_purchases DROP INDEX idx_status;
ALTER TABLE provider_purchases DROP INDEX idx_refund_status;
ALTER TABLE provider_purchases DROP INDEX idx_cancelled_at;
ALTER TABLE provider_purchases 
DROP COLUMN refund_completed_by,
DROP COLUMN refund_completed_at,
DROP COLUMN refund_notes,
DROP COLUMN refund_document,
DROP COLUMN refund_status,
DROP COLUMN cancelled_by,
DROP COLUMN cancelled_at,
DROP COLUMN cancel_notes,
DROP COLUMN cancel_reason,
DROP COLUMN status,
DROP COLUMN order_id;
*/


-- Satın alma yönetimi için gerekli alanlar
-- ================================================

-- 1. Order ID (Benzersiz Sipariş Numarası) ekle
ALTER TABLE provider_purchases 
ADD COLUMN order_id VARCHAR(50) UNIQUE NULL AFTER id;

-- 2. Status (Durum) ekle
ALTER TABLE provider_purchases 
ADD COLUMN status ENUM('active', 'cancelled', 'refunded') DEFAULT 'active' AFTER payment_status;

-- 3. Cancel (İptal) alanları ekle
ALTER TABLE provider_purchases 
ADD COLUMN cancel_reason ENUM('customer_request', 'admin_decision', 'technical_issue', 'fraud', 'other') NULL AFTER status;

ALTER TABLE provider_purchases 
ADD COLUMN cancel_notes TEXT NULL AFTER cancel_reason;

ALTER TABLE provider_purchases 
ADD COLUMN cancelled_at TIMESTAMP NULL AFTER cancel_notes;

ALTER TABLE provider_purchases 
ADD COLUMN cancelled_by INT UNSIGNED NULL AFTER cancelled_at;

-- 4. Refund (İade) alanları ekle
ALTER TABLE provider_purchases 
ADD COLUMN refund_status ENUM('not_requested', 'pending', 'document_uploaded', 'completed') DEFAULT 'not_requested' AFTER cancelled_by;

ALTER TABLE provider_purchases 
ADD COLUMN refund_document VARCHAR(255) NULL AFTER refund_status;

ALTER TABLE provider_purchases 
ADD COLUMN refund_notes TEXT NULL AFTER refund_document;

ALTER TABLE provider_purchases 
ADD COLUMN refund_completed_at TIMESTAMP NULL AFTER refund_notes;

ALTER TABLE provider_purchases 
ADD COLUMN refund_completed_by INT UNSIGNED NULL AFTER refund_completed_at;

-- 5. Foreign keys ekle
ALTER TABLE provider_purchases 
ADD CONSTRAINT fk_cancelled_by 
FOREIGN KEY (cancelled_by) REFERENCES admins(id) ON DELETE SET NULL;

ALTER TABLE provider_purchases 
ADD CONSTRAINT fk_refund_completed_by 
FOREIGN KEY (refund_completed_by) REFERENCES admins(id) ON DELETE SET NULL;

-- 6. Indexes ekle
CREATE INDEX idx_order_id ON provider_purchases(order_id);
CREATE INDEX idx_status ON provider_purchases(status);
CREATE INDEX idx_refund_status ON provider_purchases(refund_status);
CREATE INDEX idx_cancelled_at ON provider_purchases(cancelled_at);

-- 7. Mevcut kayıtlara order_id oluştur
UPDATE provider_purchases 
SET order_id = CONCAT('ORD-', DATE_FORMAT(purchased_at, '%Y%m%d'), '-', LPAD(id, 6, '0'))
WHERE order_id IS NULL;

-- 8. order_id'yi NOT NULL yap
ALTER TABLE provider_purchases 
MODIFY COLUMN order_id VARCHAR(50) NOT NULL UNIQUE;

-- ================================================
-- AÇIKLAMALAR
-- ================================================

-- order_id: Benzersiz sipariş numarası (örn: ORD-20231117-000001)
-- status: active (aktif), cancelled (iptal edildi), refunded (iade tamamlandı)
-- cancel_reason: İptal sebebi
--   - customer_request: Müşteri talebi
--   - admin_decision: Admin kararı
--   - technical_issue: Teknik sorun
--   - fraud: Dolandırıcılık
--   - other: Diğer
-- cancel_notes: İptal notu (opsiyonel açıklama)
-- cancelled_at: İptal tarihi
-- cancelled_by: İptal eden admin ID
-- refund_status: İade durumu
--   - not_requested: İade talep edilmedi
--   - pending: İade bekleniyor
--   - document_uploaded: İade belgesi yüklendi
--   - completed: İade tamamlandı
-- refund_document: İade belgesi dosya yolu
-- refund_notes: İade notu
-- refund_completed_at: İade tamamlanma tarihi
-- refund_completed_by: İadeyi tamamlayan admin ID

-- ================================================
-- ROLLBACK (Geri alma)
-- ================================================

-- Migration'ı geri almak için:
/*
ALTER TABLE provider_purchases DROP FOREIGN KEY fk_cancelled_by;
ALTER TABLE provider_purchases DROP FOREIGN KEY fk_refund_completed_by;
ALTER TABLE provider_purchases DROP INDEX idx_order_id;
ALTER TABLE provider_purchases DROP INDEX idx_status;
ALTER TABLE provider_purchases DROP INDEX idx_refund_status;
ALTER TABLE provider_purchases DROP INDEX idx_cancelled_at;
ALTER TABLE provider_purchases 
DROP COLUMN refund_completed_by,
DROP COLUMN refund_completed_at,
DROP COLUMN refund_notes,
DROP COLUMN refund_document,
DROP COLUMN refund_status,
DROP COLUMN cancelled_by,
DROP COLUMN cancelled_at,
DROP COLUMN cancel_notes,
DROP COLUMN cancel_reason,
DROP COLUMN status,
DROP COLUMN order_id;
*/



