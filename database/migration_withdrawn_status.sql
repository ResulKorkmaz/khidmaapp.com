-- Lead status ve previous_status'e "withdrawn" durumu ekle
ALTER TABLE leads 
MODIFY COLUMN status ENUM('new', 'pending', 'verified', 'sold', 'invalid', 'withdrawn') DEFAULT 'new';

ALTER TABLE leads
MODIFY COLUMN previous_status ENUM('new', 'pending', 'verified', 'sold', 'invalid', 'withdrawn') DEFAULT NULL;

-- Açıklama:
-- withdrawn: Lead bir ustaya verilmiş, ama sonra geri çekilmiş
-- Admin bu lead'i tekrar satarken dikkatli olmalı (potansiyel sorun var)


MODIFY COLUMN status ENUM('new', 'pending', 'verified', 'sold', 'invalid', 'withdrawn') DEFAULT 'new';

ALTER TABLE leads
MODIFY COLUMN previous_status ENUM('new', 'pending', 'verified', 'sold', 'invalid', 'withdrawn') DEFAULT NULL;

-- Açıklama:
-- withdrawn: Lead bir ustaya verilmiş, ama sonra geri çekilmiş
-- Admin bu lead'i tekrar satarken dikkatli olmalı (potansiyel sorun var)



