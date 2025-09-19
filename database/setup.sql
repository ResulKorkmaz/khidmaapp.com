-- Khidmaapp Database Setup Script
-- Local Development PostgreSQL Configuration

-- Create khidmaapp user with password
CREATE USER khidmaapp_user WITH PASSWORD 'khidmaapp_password';

-- Grant privileges to khidmaapp_user
ALTER USER khidmaapp_user CREATEDB;
GRANT ALL PRIVILEGES ON DATABASE khidmaapp TO khidmaapp_user;
ALTER DATABASE khidmaapp OWNER TO khidmaapp_user;

-- Connect to khidmaapp database and enable extensions
\c khidmaapp

-- Enable required PostgreSQL extensions
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pg_trgm";  -- For full-text search
CREATE EXTENSION IF NOT EXISTS "unaccent"; -- For Arabic text processing

-- Grant schema privileges
GRANT ALL ON SCHEMA public TO khidmaapp_user;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO khidmaapp_user;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO khidmaapp_user;

-- Set default privileges for future tables
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO khidmaapp_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON SEQUENCES TO khidmaapp_user;

-- Display connection info
\conninfo

-- List extensions
\dx

-- List databases
\l

-- Display success message
SELECT 
    'Khidmaapp database setup completed successfully!' as status,
    'Database: khidmaapp' as database,
    'User: khidmaapp_user' as user,
    'Extensions: uuid-ossp, pg_trgm, unaccent' as extensions;
