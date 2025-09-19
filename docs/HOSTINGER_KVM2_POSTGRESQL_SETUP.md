# 🖥️ Hostinger KVM 2 - PostgreSQL Optimize Kurulum

## 📊 Hostinger KVM 2 Özellikleri
- **CPU**: 2 vCPU (AMD EPYC)
- **RAM**: 8 GB 
- **Storage**: 100 GB NVMe SSD
- **Network**: 1 Gbps, 8 TB bandwidth
- **Backup**: Haftalık otomatik backup dahil
- **Performance**: Production-ready! 🔥

## 🚀 Hızlı Kurulum (Optimize)

### 1. İlk Sistem Hazırlığı
```bash
# Sistemi güncelle
sudo apt update && sudo apt upgrade -y

# Gerekli paketler
sudo apt install -y wget ca-certificates curl gnupg lsb-release htop iotop

# Sistem bilgileri
echo "=== Hostinger KVM 2 Sistem Bilgileri ==="
echo "CPU Cores: $(nproc)"
echo "RAM: $(free -h | grep Mem | awk '{print $2}')"
echo "Disk: $(df -h / | tail -1 | awk '{print $2}')"
echo "OS: $(lsb_release -d | cut -f2)"
```

### 2. PostgreSQL 15 Kurulumu (Latest Stable)
```bash
# PostgreSQL oficial repository
curl -fsSL https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo gpg --dearmor -o /etc/apt/trusted.gpg.d/postgresql.gpg
echo "deb http://apt.postgresql.org/pub/repos/apt/ $(lsb_release -cs)-pgdg main" | sudo tee /etc/apt/sources.list.d/pgdg.list

# Kurulum
sudo apt update
sudo apt install -y postgresql-15 postgresql-contrib-15 postgresql-client-15

# Servis başlat
sudo systemctl start postgresql
sudo systemctl enable postgresql
sudo systemctl status postgresql
```

### 3. Khidmaapp Database Setup
```bash
# PostgreSQL kullanıcısına geç
sudo -u postgres psql

-- Database ve user oluştur
CREATE DATABASE khidmaapp;
CREATE USER khidmaapp_user WITH PASSWORD 'Khidma2024!Strong#Pass';
GRANT ALL PRIVILEGES ON DATABASE khidmaapp TO khidmaapp_user;
ALTER USER khidmaapp_user CREATEDB;
ALTER USER khidmaapp_user WITH SUPERUSER;  -- Laravel migrations için

-- UTF-8 ve Arabic collation support
\c khidmaapp
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pg_trgm";
CREATE EXTENSION IF NOT EXISTS "unaccent";

-- Test connection
\l
\q
```

## ⚡ Performance Tuning (8GB RAM İçin Optimize)

### Optimal PostgreSQL Ayarları:
```bash
sudo nano /etc/postgresql/15/main/postgresql.conf
```

**8GB RAM için optimize edilmiş ayarlar:**
```ini
# Memory Settings (8GB RAM için)
shared_buffers = 2GB                    # RAM'in 25%'i
effective_cache_size = 6GB              # RAM'in 75%'i  
maintenance_work_mem = 512MB            # Bakım işlemleri için
work_mem = 32MB                         # Query işlemleri için

# Connection Settings
max_connections = 200                   # Yeterli connection pool
superuser_reserved_connections = 3

# Checkpoint Settings (NVMe SSD için optimize)
checkpoint_completion_target = 0.9
checkpoint_timeout = 15min
max_wal_size = 4GB
min_wal_size = 1GB
wal_buffers = 64MB

# Query Planner Settings
random_page_cost = 1.1                 # NVMe SSD için düşük
effective_io_concurrency = 300         # NVMe SSD için yüksek
default_statistics_target = 100

# Logging Settings
log_destination = 'stderr'
logging_collector = on
log_directory = '/var/log/postgresql'
log_filename = 'postgresql-%Y-%m-%d_%H%M%S.log'
log_rotation_age = 1d
log_rotation_size = 100MB
log_min_duration_statement = 1000      # 1 saniyeden uzun queryler log'la

# Connection Security
listen_addresses = 'localhost'          # Başlangıçta sadece local
port = 5432
ssl = on                               # SSL zorunlu
```

### Ayarları Uygula:
```bash
# Konfigürasyon test
sudo -u postgres /usr/lib/postgresql/15/bin/postgres --config-file=/etc/postgresql/15/main/postgresql.conf --check

# PostgreSQL restart
sudo systemctl restart postgresql

# Ayarları kontrol et
sudo -u postgres psql -c "SELECT name, setting, unit FROM pg_settings WHERE name IN ('shared_buffers', 'effective_cache_size', 'work_mem', 'maintenance_work_mem');"
```

## 🔒 Güvenlik Konfigürasyonu

### 1. PostgreSQL Authentication Setup
```bash
sudo nano /etc/postgresql/15/main/pg_hba.conf
```

**Güvenlik ayarları:**
```ini
# TYPE  DATABASE        USER            ADDRESS                 METHOD

# Local connections
local   all             postgres                                peer
local   khidmaapp       khidmaapp_user                         md5

# IPv4 local connections
host    khidmaapp       khidmaapp_user  127.0.0.1/32           md5
host    khidmaapp       khidmaapp_user  HOSTINGER_VPS_IP/32    md5

# Deny all other connections
host    all             all             0.0.0.0/0              reject
```

### 2. Firewall Konfigürasyonu (UFW)
```bash
# UFW kurulumu ve konfigürasyonu
sudo ufw --force reset
sudo ufw default deny incoming
sudo ufw default allow outgoing

# SSH access (Hostinger default port: 22)
sudo ufw allow ssh

# PostgreSQL sadece localhost ve uygulama serverından
sudo ufw allow from 127.0.0.1 to any port 5432
# sudo ufw allow from APP_SERVER_IP to any port 5432  # Uygulama server IP'si geldiğinde

# HTTP/HTTPS (eğer aynı server'da web de çalışacaksa)
sudo ufw allow 80
sudo ufw allow 443

# Activate firewall
sudo ufw enable
sudo ufw status verbose
```

### 3. PostgreSQL User Security
```bash
# postgres kullanıcısı için güçlü şifre
sudo -u postgres psql -c "ALTER USER postgres PASSWORD 'VeryStrongPostgresPassword2024!';"

# Sadece gerekli yetkiler
sudo -u postgres psql -c "REVOKE ALL ON SCHEMA public FROM public;"
sudo -u postgres psql -c "GRANT ALL ON SCHEMA public TO khidmaapp_user;"
```

## 💾 Backup Stratejisi (Hostinger Backup + Custom)

### 1. Automated Daily Backup Script
```bash
sudo mkdir -p /opt/scripts /backup/postgresql
sudo nano /opt/scripts/postgresql-backup.sh
```

**Backup script:**
```bash
#!/bin/bash
# Hostinger KVM 2 PostgreSQL Backup Script

BACKUP_DIR="/backup/postgresql"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="khidmaapp"
DB_USER="khidmaapp_user"
RETENTION_DAYS=14

# Log function
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" >> /var/log/postgresql-backup.log
}

log "Starting backup for database: $DB_NAME"

# Create backup directory
mkdir -p $BACKUP_DIR

# Create dump with custom format (faster restore)
PGPASSWORD='Khidma2024!Strong#Pass' pg_dump \
    -h localhost \
    -U $DB_USER \
    -d $DB_NAME \
    -Fc \
    -f $BACKUP_DIR/khidmaapp_$DATE.dump

if [ $? -eq 0 ]; then
    log "Backup completed successfully: khidmaapp_$DATE.dump"
    
    # Compress backup for storage efficiency
    gzip $BACKUP_DIR/khidmaapp_$DATE.dump
    log "Backup compressed: khidmaapp_$DATE.dump.gz"
    
    # Calculate backup size
    SIZE=$(du -sh $BACKUP_DIR/khidmaapp_$DATE.dump.gz | cut -f1)
    log "Backup size: $SIZE"
    
    # Clean old backups (keep last 14 days)
    find $BACKUP_DIR -name "khidmaapp_*.dump.gz" -mtime +$RETENTION_DAYS -delete
    log "Old backups cleaned (retention: $RETENTION_DAYS days)"
    
    # Upload to external storage (optional)
    # aws s3 cp $BACKUP_DIR/khidmaapp_$DATE.dump.gz s3://your-bucket/backups/
    
else
    log "ERROR: Backup failed!"
    exit 1
fi

log "Backup process completed"
```

### 2. Setup Cron Jobs
```bash
# Script'i executable yap
sudo chmod +x /opt/scripts/postgresql-backup.sh

# Cron job ekle
sudo crontab -e

# Daily backup at 2:30 AM
30 2 * * * /opt/scripts/postgresql-backup.sh

# Weekly VACUUM ANALYZE at Sunday 3 AM
0 3 * * 0 sudo -u postgres psql -d khidmaapp -c "VACUUM ANALYZE;"
```

## 📊 Monitoring & Maintenance

### 1. Performance Monitoring Script
```bash
sudo nano /opt/scripts/pg-monitor.sh
```

**Monitor script:**
```bash
#!/bin/bash
# PostgreSQL Health Monitor for Hostinger KVM 2

echo "=== PostgreSQL Health Check $(date) ==="

# Service Status
PG_STATUS=$(systemctl is-active postgresql)
echo "PostgreSQL Status: $PG_STATUS"

# Connection Count
CONNECTIONS=$(sudo -u postgres psql -t -c "SELECT count(*) FROM pg_stat_activity;")
echo "Active Connections: $CONNECTIONS/200"

# Database Size
DB_SIZE=$(sudo -u postgres psql -d khidmaapp -t -c "SELECT pg_size_pretty(pg_database_size('khidmaapp'));")
echo "Database Size: $DB_SIZE"

# Cache Hit Ratio
CACHE_HIT=$(sudo -u postgres psql -d khidmaapp -t -c "SELECT round(100.0 * sum(blks_hit) / sum(blks_hit + blks_read), 2) AS cache_hit_ratio FROM pg_stat_database WHERE datname = 'khidmaapp';")
echo "Cache Hit Ratio: $CACHE_HIT%"

# Disk Usage
DISK_USAGE=$(df -h /var/lib/postgresql | awk 'NR==2{print $5}')
echo "PostgreSQL Disk Usage: $DISK_USAGE"

# System Resources
RAM_USAGE=$(free | grep Mem | awk '{printf "%.1f", ($3/$2) * 100.0}')
CPU_LOAD=$(uptime | awk -F'load average:' '{ print $2 }')
echo "RAM Usage: ${RAM_USAGE}%"
echo "CPU Load: $CPU_LOAD"

# Slow Queries (> 1 second)
SLOW_QUERIES=$(sudo -u postgres psql -d khidmaapp -t -c "SELECT count(*) FROM pg_stat_statements WHERE mean_time > 1000;" 2>/dev/null || echo "0")
echo "Slow Queries (>1s): $SLOW_QUERIES"

echo "=== End Health Check ==="
```

### 2. Automated Monitoring
```bash
sudo chmod +x /opt/scripts/pg-monitor.sh

# Cron job - her 6 saatte bir check
sudo crontab -e

# Monitor every 6 hours
0 */6 * * * /opt/scripts/pg-monitor.sh >> /var/log/postgresql-monitor.log
```

## 🔧 Laravel Connection Configuration

### Backend/.env Configuration:
```env
# PostgreSQL Connection (Hostinger KVM 2)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=khidmaapp
DB_USERNAME=khidmaapp_user
DB_PASSWORD=Khidma2024!Strong#Pass

# SSL Configuration (Production)
DB_SSLMODE=require

# Connection Pool Settings
DB_POOL_MIN=5
DB_POOL_MAX=20
```

### Test Connection:
```bash
cd /path/to/khidmaapp/backend
php artisan migrate:status
```

## 🎯 Maintenance Commands

### Daily Maintenance:
```bash
# Vacuum analyze (optimize)
sudo -u postgres psql -d khidmaapp -c "VACUUM ANALYZE;"

# Check database stats
sudo -u postgres psql -d khidmaapp -c "
SELECT 
    schemaname,
    tablename,
    n_tup_ins as inserts,
    n_tup_upd as updates,
    n_tup_del as deletes,
    n_live_tup as live_rows
FROM pg_stat_user_tables 
ORDER BY n_live_tup DESC LIMIT 10;
"

# Check index usage
sudo -u postgres psql -d khidmaapp -c "
SELECT 
    schemaname,
    tablename,
    indexname,
    idx_tup_read,
    idx_tup_fetch 
FROM pg_stat_user_indexes 
ORDER BY idx_tup_read DESC LIMIT 10;
"
```

## 📈 Hostinger KVM 2 Optimal Usage

### Resource Usage Targets:
- **RAM Usage**: 60-70% (5-6GB used)
- **CPU Usage**: <80% avg
- **Disk Usage**: <80% (80GB max)
- **Connections**: <150 active

### Scaling Strategy:
```
Current: Hostinger KVM 2 (8GB RAM) ✅
Next: Hostinger KVM 4 (16GB RAM) - 50K+ users
Alternative: AWS RDS - Enterprise scale
```

## 🚀 Quick Start Commands

### Setup Script:
```bash
#!/bin/bash
echo "=== Hostinger KVM 2 PostgreSQL Quick Setup ==="

# Update system
sudo apt update && sudo apt upgrade -y

# Install PostgreSQL 15
curl -fsSL https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo gpg --dearmor -o /etc/apt/trusted.gpg.d/postgresql.gpg
echo "deb http://apt.postgresql.org/pub/repos/apt/ $(lsb_release -cs)-pgdg main" | sudo tee /etc/apt/sources.list.d/pgdg.list
sudo apt update && sudo apt install -y postgresql-15 postgresql-contrib-15

# Start service
sudo systemctl start postgresql && sudo systemctl enable postgresql

echo "✅ PostgreSQL kuruldu! Database oluşturmaya hazır."
echo "Sonraki adım: Database ve user setup"
```

---

## 💡 Pro Tips for Hostinger KVM 2:

1. **NVMe SSD**: Random page cost = 1.1 (optimal for SSD)
2. **8GB RAM**: Shared buffers = 2GB (25% rule)
3. **AMD EPYC**: Effective_io_concurrency = 300 
4. **1 Gbps Network**: Backup uploads very fast
5. **Weekly Backup**: Hostinger backup + custom script = double protection

**Bu VPS PostgreSQL için mükemmel! Production-ready setup! 🔥**
