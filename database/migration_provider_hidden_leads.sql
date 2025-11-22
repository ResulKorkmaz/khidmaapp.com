-- Migration: Provider Hidden Leads
-- Add column to track which leads are hidden by providers (soft delete for UI)
-- Leads are NEVER actually deleted, only hidden from provider's view

-- Check if columns exist before adding
SET @dbname = DATABASE();
SET @tablename = "provider_lead_deliveries";
SET @columnname1 = "hidden_by_provider";
SET @columnname2 = "hidden_at";

SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
   WHERE (table_name = @tablename)
     AND (table_schema = @dbname)
     AND (column_name = @columnname1)) > 0,
  "SELECT 1",
  "ALTER TABLE provider_lead_deliveries ADD COLUMN hidden_by_provider TINYINT(1) DEFAULT 0 AFTER delivered_at"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
   WHERE (table_name = @tablename)
     AND (table_schema = @dbname)
     AND (column_name = @columnname2)) > 0,
  "SELECT 1",
  "ALTER TABLE provider_lead_deliveries ADD COLUMN hidden_at TIMESTAMP NULL AFTER hidden_by_provider"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Index for filtering hidden leads (check if exists first)
SET @indexExists = (SELECT COUNT(1) 
    FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE table_schema = DATABASE() 
    AND table_name = 'provider_lead_deliveries' 
    AND index_name = 'idx_hidden_by_provider');

SET @preparedStatement = IF(@indexExists = 0,
    'CREATE INDEX idx_hidden_by_provider ON provider_lead_deliveries(provider_id, hidden_by_provider)',
    'SELECT 1');
    
PREPARE createIndexIfNotExists FROM @preparedStatement;
EXECUTE createIndexIfNotExists;
DEALLOCATE PREPARE createIndexIfNotExists;

-- Note: This is a SOFT DELETE for UI purposes only
-- The lead data is NEVER actually deleted from the database
-- Provider thinks they deleted it, but admin can still see everything


-- Leads are NEVER actually deleted, only hidden from provider's view

-- Check if columns exist before adding
SET @dbname = DATABASE();
SET @tablename = "provider_lead_deliveries";
SET @columnname1 = "hidden_by_provider";
SET @columnname2 = "hidden_at";

SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
   WHERE (table_name = @tablename)
     AND (table_schema = @dbname)
     AND (column_name = @columnname1)) > 0,
  "SELECT 1",
  "ALTER TABLE provider_lead_deliveries ADD COLUMN hidden_by_provider TINYINT(1) DEFAULT 0 AFTER delivered_at"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
   WHERE (table_name = @tablename)
     AND (table_schema = @dbname)
     AND (column_name = @columnname2)) > 0,
  "SELECT 1",
  "ALTER TABLE provider_lead_deliveries ADD COLUMN hidden_at TIMESTAMP NULL AFTER hidden_by_provider"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Index for filtering hidden leads (check if exists first)
SET @indexExists = (SELECT COUNT(1) 
    FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE table_schema = DATABASE() 
    AND table_name = 'provider_lead_deliveries' 
    AND index_name = 'idx_hidden_by_provider');

SET @preparedStatement = IF(@indexExists = 0,
    'CREATE INDEX idx_hidden_by_provider ON provider_lead_deliveries(provider_id, hidden_by_provider)',
    'SELECT 1');
    
PREPARE createIndexIfNotExists FROM @preparedStatement;
EXECUTE createIndexIfNotExists;
DEALLOCATE PREPARE createIndexIfNotExists;

-- Note: This is a SOFT DELETE for UI purposes only
-- The lead data is NEVER actually deleted from the database
-- Provider thinks they deleted it, but admin can still see everything



