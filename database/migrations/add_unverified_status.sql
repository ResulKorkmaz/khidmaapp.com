-- ============================================
-- Add 'unverified' status for email verification
-- KhidmaApp.com
-- ============================================

-- status kolonuna 'unverified' değerini ekle
ALTER TABLE service_providers 
MODIFY COLUMN status ENUM('unverified', 'pending', 'active', 'suspended', 'rejected') 
DEFAULT 'unverified';

-- Açıklama:
-- unverified: E-posta doğrulanmamış (yeni kayıt)
-- pending: E-posta doğrulanmış, admin onayı bekliyor
-- active: Aktif hesap
-- suspended: Askıya alınmış
-- rejected: Reddedilmiş

