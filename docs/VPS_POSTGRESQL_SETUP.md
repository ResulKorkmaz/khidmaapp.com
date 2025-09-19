# 🖥️ VPS PostgreSQL Kurulum Rehberi

## 📋 Ön Gereksinimler

### VPS Minimum Gereksinimleri:
- **RAM**: En az 2GB (4GB+ önerilen)
- **CPU**: En az 2 core
- **Disk**: En az 20GB SSD
- **OS**: Ubuntu 20.04+ / CentOS 8+ / Debian 11+
- **Network**: Public IP + SSH access

## 🚀 Hızlı Kurulum (Ubuntu/Debian)

### 1. Sistem Güncellemesi
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y wget ca-certificates
```

### 2. PostgreSQL Repository Ekleme
```bash
# PostgreSQL 15 (latest stable)
wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add -
echo "deb http://apt.postgresql.org/pub/repos/apt/ $(lsb_release -cs)-pgdg main" | sudo tee /etc/apt/sources.list.d/pgdg.list
sudo apt update
```

### 3. PostgreSQL Kurulumu
```bash
sudo apt install -y postgresql-15 postgresql-contrib-15
sudo systemctl start postgresql
sudo systemctl enable postgresql
```

### 4. İlk Konfigürasyon
```bash
# PostgreSQL kullanıcısına geçiş
sudo -u postgres psql

-- Database ve kullanıcı oluşturma
CREATE DATABASE khidmaapp;
CREATE USER khidmaapp_user WITH PASSWORD 'güçlü_şifre_buraya';
GRANT ALL PRIVILEGES ON DATABASE khidmaapp TO khidmaapp_user;
ALTER USER khidmaapp_user CREATEDB;
\q
```

## 🔒 Güvenlik Konfigürasyonu

### 1. PostgreSQL Authentication
```bash
sudo nano /etc/postgresql/15/main/pg_hba.conf
```

Eklenecek satırlar:
```
# IPv4 local connections:
host    khidmaapp    khidmaapp_user    127.0.0.1/32    md5
host    khidmaapp    khidmaapp_user    VPS_IP/32       md5
```

### 2. PostgreSQL Listen Addresses
```bash
sudo nano /etc/postgresql/15/main/postgresql.conf
```

Değiştirilecek:
```
listen_addresses = 'localhost,VPS_PRIVATE_IP'
port = 5432
```

### 3. Firewall Konfigürasyonu
```bash
# UFW kullanımı
sudo ufw allow ssh
sudo ufw allow from APP_SERVER_IP to any port 5432
sudo ufw enable
```

## ⚡ Performance Tuning

### Optimal Ayarlar (RAM miktarına göre):

#### 2GB RAM için:
```
shared_buffers = 512MB
effective_cache_size = 1536MB
maintenance_work_mem = 128MB
checkpoint_completion_target = 0.9
wal_buffers = 16MB
default_statistics_target = 100
random_page_cost = 1.1
effective_io_concurrency = 200
work_mem = 4MB
min_wal_size = 1GB
max_wal_size = 4GB
```

#### 4GB RAM için:
```
shared_buffers = 1GB
effective_cache_size = 3GB
maintenance_work_mem = 256MB
checkpoint_completion_target = 0.9
wal_buffers = 16MB
default_statistics_target = 100
random_page_cost = 1.1
effective_io_concurrency = 200
work_mem = 8MB
min_wal_size = 2GB
max_wal_size = 8GB
```

### Konfigürasyon Uygulama:
```bash
sudo nano /etc/postgresql/15/main/postgresql.conf
sudo systemctl restart postgresql
```

## 💾 Backup Stratejisi

### 1. Automated Daily Backup Script
```bash
sudo nano /opt/postgresql-backup.sh
```

Script içeriği:
```bash
#!/bin/bash
BACKUP_DIR="/backup/postgresql"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="khidmaapp"

mkdir -p $BACKUP_DIR

# Full backup
pg_dump -h localhost -U khidmaapp_user -d $DB_NAME > $BACKUP_DIR/khidmaapp_$DATE.sql

# Compress backup
gzip $BACKUP_DIR/khidmaapp_$DATE.sql

# Keep only last 7 days
find $BACKUP_DIR -name "khidmaapp_*.sql.gz" -mtime +7 -delete

# Upload to cloud storage (optional)
# aws s3 cp $BACKUP_DIR/khidmaapp_$DATE.sql.gz s3://your-bucket/backups/
```

### 2. Cron Job Setup
```bash
sudo chmod +x /opt/postgresql-backup.sh
sudo crontab -e

# Daily backup at 2 AM
0 2 * * * /opt/postgresql-backup.sh
```

## 📊 Monitoring Setup

### 1. Basic Health Check Script
```bash
sudo nano /opt/pg-health-check.sh
```

Script içeriği:
```bash
#!/bin/bash
PG_STATUS=$(systemctl is-active postgresql)
DISK_USAGE=$(df -h /var/lib/postgresql | awk 'NR==2{print $5}')
MEMORY_USAGE=$(free | grep Mem | awk '{printf "%.2f", ($3/$2) * 100.0}')

echo "PostgreSQL Status: $PG_STATUS"
echo "Disk Usage: $DISK_USAGE"
echo "Memory Usage: ${MEMORY_USAGE}%"

# Alert if disk > 80%
if [[ ${DISK_USAGE%?} -gt 80 ]]; then
    echo "WARNING: Disk usage is high!"
fi
```

### 2. Log Monitoring
```bash
# PostgreSQL logs
sudo tail -f /var/log/postgresql/postgresql-15-main.log

# System logs
sudo journalctl -u postgresql -f
```

## 🔧 Laravel Connection Configuration

### .env dosyası:
```env
DB_CONNECTION=pgsql
DB_HOST=VPS_IP_ADDRESS
DB_PORT=5432
DB_DATABASE=khidmaapp
DB_USERNAME=khidmaapp_user
DB_PASSWORD=güçlü_şifre_buraya

# SSL için (production)
DB_SSLMODE=require
```

## 🚀 Maintenance Commands

### Daily Maintenance:
```bash
# Vacuum and analyze
sudo -u postgres psql -d khidmaapp -c "VACUUM ANALYZE;"

# Reindex if needed
sudo -u postgres psql -d khidmaapp -c "REINDEX DATABASE khidmaapp;"

# Check database size
sudo -u postgres psql -d khidmaapp -c "SELECT pg_size_pretty(pg_database_size('khidmaapp'));"
```

## 🎯 Next Steps

1. **VPS özelliklerini belirtin** (RAM, CPU, lokasyon)
2. **Optimal konfigürasyon** sizin VPS'e göre ayarlanacak
3. **SSL sertifikası** kurulumu (Let's Encrypt)
4. **Monitoring dashboard** kurulumu
5. **Automated scaling** stratejisi

---

**Not**: Bu rehber genel bir şablon niteliğindedir. VPS özelliklerinize göre optimize edilmiş versiyonu hazırlanacaktır.
