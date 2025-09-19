-- PostgreSQL Extensions for Khidmaapp
-- This script runs automatically when PostgreSQL container starts

-- Connect to khidmaapp database
\c khidmaapp;

-- Enable required PostgreSQL extensions
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pg_trgm";  -- For full-text search
CREATE EXTENSION IF NOT EXISTS "unaccent"; -- For Arabic text processing

-- Create additional indexes for Arabic text search
-- These will be used after migrations run

-- Display success message
SELECT 
    'Khidmaapp PostgreSQL extensions setup completed!' as status,
    'Database: khidmaapp' as database,
    'Extensions: uuid-ossp, pg_trgm, unaccent' as extensions;
