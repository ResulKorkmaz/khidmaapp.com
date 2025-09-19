# 🏗️ Hostinger KVM 2 - Dual Project Professional Architecture

## 📋 Current Setup Analysis
- **Existing**: n8n.alasistan.com (N8N Workflow Automation)
- **New**: khidmaapp.com (Service Marketplace)
- **VPS**: Hostinger KVM 2 (2 vCPU, 8GB RAM, 100GB NVMe)

## 🎯 PROFESSIONAL DEPLOYMENT STRATEGY

### 1. 🐳 Container-Based Isolation (Docker)

#### Architecture Overview:
```
┌─────────────────────────────────────────────────────────────┐
│                    Hostinger KVM 2 VPS                      │
│  ┌─────────────────────┐  ┌─────────────────────────────────┐ │
│  │   Nginx Reverse     │  │        PostgreSQL               │ │
│  │   Proxy Container   │  │     (Shared but isolated DBs)   │ │
│  │                     │  │                                 │ │
│  │ ├─ n8n.alasistan    │  │ ├─ n8n_db                      │ │
│  │ ├─ khidmaapp.com    │  │ ├─ khidmaapp_db                │ │
│  │ ├─ admin.khidmaapp  │  │                                 │ │
│  └─────────────────────┘  └─────────────────────────────────┘ │
│  ┌─────────────────────┐  ┌─────────────────────────────────┐ │
│  │   N8N Container     │  │     Khidmaapp Stack             │ │
│  │   (Existing)        │  │                                 │ │
│  │                     │  │ ├─ Laravel API (PHP-FPM)       │ │
│  │ ├─ Node.js App      │  │ ├─ Next.js Frontend             │ │
│  │ ├─ SQLite/DB        │  │ ├─ Redis (Queue/Cache)          │ │
│  │ ├─ Port: 5678       │  │ ├─ MeiliSearch (Search)         │ │
│  └─────────────────────┘  └─────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

### 2. 📊 Resource Allocation Strategy

#### Optimal Resource Distribution (8GB RAM):
```yaml
System Reserved:     1.5GB  (OS + Docker)
PostgreSQL:         2.0GB  (Shared database)
N8N Container:      1.5GB  (Existing workflow)
Khidmaapp Stack:    2.5GB  (New project)
Buffer/Cache:       0.5GB  (Safety margin)
```

#### CPU Distribution:
```yaml
N8N Container:      0.5 vCPU (25%)
Khidmaapp API:      1.0 vCPU (50%)
Database:           0.3 vCPU (15%)
System:             0.2 vCPU (10%)
```

### 3. 🐳 Docker Compose Configuration

```yaml
# docker-compose.yml for Dual Project Setup
version: '3.8'

services:
  # Nginx Reverse Proxy
  nginx:
    image: nginx:alpine
    container_name: nginx-proxy
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx/conf.d:/etc/nginx/conf.d
      - ./ssl:/etc/ssl/certs
    restart: unless-stopped
    depends_on:
      - n8n
      - khidmaapp-api
      - khidmaapp-frontend

  # PostgreSQL (Shared for both projects)
  postgres:
    image: postgres:15-alpine
    container_name: postgres-shared
    environment:
      POSTGRES_DB: postgres
      POSTGRES_USER: postgres
      POSTGRES_PASSWORD: ${POSTGRES_PASSWORD}
    volumes:
      - postgres_data:/var/lib/postgresql/data
      - ./init-db:/docker-entrypoint-initdb.d
    ports:
      - "127.0.0.1:5432:5432"
    restart: unless-stopped
    deploy:
      resources:
        limits:
          memory: 2G
          cpus: "0.3"
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U postgres"]
      interval: 30s
      timeout: 10s
      retries: 3

  # N8N (Existing Project)
  n8n:
    image: n8nio/n8n:latest
    container_name: n8n-workflow
    environment:
      - N8N_BASIC_AUTH_ACTIVE=true
      - N8N_BASIC_AUTH_USER=${N8N_USER}
      - N8N_BASIC_AUTH_PASSWORD=${N8N_PASSWORD}
      - WEBHOOK_URL=https://n8n.alasistan.com
      - GENERIC_TIMEZONE=Europe/Istanbul
      - DB_TYPE=postgresdb
      - DB_POSTGRESDB_HOST=postgres
      - DB_POSTGRESDB_DATABASE=n8n_db
      - DB_POSTGRESDB_USER=n8n_user
      - DB_POSTGRESDB_PASSWORD=${N8N_DB_PASSWORD}
    volumes:
      - n8n_data:/home/node/.n8n
    restart: unless-stopped
    depends_on:
      postgres:
        condition: service_healthy
    deploy:
      resources:
        limits:
          memory: 1.5G
          cpus: "0.5"

  # Redis (for Khidmaapp caching and queues)
  redis:
    image: redis:7-alpine
    container_name: redis-khidmaapp
    restart: unless-stopped
    volumes:
      - redis_data:/data
    deploy:
      resources:
        limits:
          memory: 256M
          cpus: "0.1"

  # MeiliSearch (for Khidmaapp search)
  meilisearch:
    image: getmeili/meilisearch:latest
    container_name: meilisearch-khidmaapp
    environment:
      - MEILI_MASTER_KEY=${MEILI_MASTER_KEY}
      - MEILI_ENV=production
    volumes:
      - meilisearch_data:/meili_data
    restart: unless-stopped
    deploy:
      resources:
        limits:
          memory: 512M
          cpus: "0.2"

  # Khidmaapp Laravel API
  khidmaapp-api:
    build:
      context: ./khidmaapp/backend
      dockerfile: Dockerfile
    container_name: khidmaapp-api
    environment:
      - APP_ENV=production
      - DB_HOST=postgres
      - DB_DATABASE=khidmaapp_db
      - DB_USERNAME=khidmaapp_user
      - DB_PASSWORD=${KHIDMAAPP_DB_PASSWORD}
      - REDIS_HOST=redis
      - MEILISEARCH_HOST=http://meilisearch:7700
      - MEILISEARCH_KEY=${MEILI_MASTER_KEY}
    volumes:
      - ./khidmaapp/backend:/var/www/html
      - ./storage/app:/var/www/html/storage/app
    restart: unless-stopped
    depends_on:
      - postgres
      - redis
      - meilisearch
    deploy:
      resources:
        limits:
          memory: 1.5G
          cpus: "0.8"

  # Khidmaapp Next.js Frontend
  khidmaapp-frontend:
    build:
      context: ./khidmaapp/frontend
      dockerfile: Dockerfile
    container_name: khidmaapp-frontend
    environment:
      - NODE_ENV=production
      - NEXT_PUBLIC_API_URL=https://api.khidmaapp.com
    restart: unless-stopped
    depends_on:
      - khidmaapp-api
    deploy:
      resources:
        limits:
          memory: 1G
          cpus: "0.5"

volumes:
  postgres_data:
  n8n_data:
  redis_data:
  meilisearch_data:
```

### 4. 🌐 Nginx Reverse Proxy Configuration

```nginx
# /nginx/conf.d/default.conf

# N8N (Existing)
upstream n8n_backend {
    server n8n:5678;
}

# Khidmaapp API
upstream khidmaapp_api {
    server khidmaapp-api:9000;
}

# Khidmaapp Frontend
upstream khidmaapp_frontend {
    server khidmaapp-frontend:3000;
}

# N8N Domain
server {
    listen 80;
    server_name n8n.alasistan.com;
    
    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name n8n.alasistan.com;
    
    ssl_certificate /etc/ssl/certs/alasistan.com.crt;
    ssl_certificate_key /etc/ssl/certs/alasistan.com.key;
    
    location / {
        proxy_pass http://n8n_backend;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        
        # WebSocket support for N8N
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
    }
}

# Khidmaapp Frontend
server {
    listen 80;
    server_name khidmaapp.com www.khidmaapp.com;
    return 301 https://khidmaapp.com$request_uri;
}

server {
    listen 443 ssl http2;
    server_name khidmaapp.com www.khidmaapp.com;
    
    ssl_certificate /etc/ssl/certs/khidmaapp.com.crt;
    ssl_certificate_key /etc/ssl/certs/khidmaapp.com.key;
    
    # Redirect www to non-www
    if ($host = 'www.khidmaapp.com') {
        return 301 https://khidmaapp.com$request_uri;
    }
    
    location / {
        proxy_pass http://khidmaapp_frontend;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}

# Khidmaapp API
server {
    listen 80;
    server_name api.khidmaapp.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name api.khidmaapp.com;
    
    ssl_certificate /etc/ssl/certs/khidmaapp.com.crt;
    ssl_certificate_key /etc/ssl/certs/khidmaapp.com.key;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass khidmaapp_api;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}

# Admin Panel
server {
    listen 443 ssl http2;
    server_name admin.khidmaapp.com;
    
    ssl_certificate /etc/ssl/certs/khidmaapp.com.crt;
    ssl_certificate_key /etc/ssl/certs/khidmaapp.com.key;
    
    location / {
        proxy_pass http://khidmaapp_api;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

### 5. 🗄️ Database Isolation Strategy

```sql
-- init-db/01-create-databases.sql
-- Create separate databases for each project

-- N8N Database
CREATE DATABASE n8n_db;
CREATE USER n8n_user WITH PASSWORD 'secure_n8n_password';
GRANT ALL PRIVILEGES ON DATABASE n8n_db TO n8n_user;

-- Khidmaapp Database  
CREATE DATABASE khidmaapp_db;
CREATE USER khidmaapp_user WITH PASSWORD 'secure_khidmaapp_password';
GRANT ALL PRIVILEGES ON DATABASE khidmaapp_db TO khidmaapp_user;

-- Enable extensions for Khidmaapp
\c khidmaapp_db;
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pg_trgm";
CREATE EXTENSION IF NOT EXISTS "unaccent";
```

### 6. 📊 Monitoring & Resource Management

#### Docker Monitoring Script:
```bash
#!/bin/bash
# monitoring/docker-resources.sh

echo "🐳 DOCKER CONTAINER RESOURCE USAGE"
echo "=================================="
docker stats --no-stream --format "table {{.Container}}\t{{.CPUPerc}}\t{{.MemUsage}}\t{{.MemPerc}}\t{{.NetIO}}\t{{.BlockIO}}"

echo ""
echo "💾 MEMORY BREAKDOWN:"
echo "==================="
docker system df

echo ""
echo "🔥 TOP MEMORY CONSUMERS:"
echo "========================"
docker stats --no-stream --format "{{.Container}} {{.MemPerc}}" | sort -k2 -nr | head -5
```

#### Resource Limits via systemd:
```ini
# /etc/systemd/system/docker-khidmaapp.slice
[Unit]
Description=Khidmaapp Docker Slice
Before=slices.target

[Slice]
CPUQuota=150%
MemoryMax=3G
TasksMax=100
```

### 7. 🔒 Security Isolation

#### Network Isolation:
```yaml
# docker-compose.yml networks section
networks:
  n8n_network:
    driver: bridge
    internal: false
  khidmaapp_network:
    driver: bridge
    internal: false
  db_network:
    driver: bridge
    internal: true
```

#### Firewall Rules:
```bash
# UFW rules for dual project
sudo ufw allow ssh
sudo ufw allow 80
sudo ufw allow 443

# PostgreSQL only from localhost
sudo ufw allow from 127.0.0.1 to any port 5432

# Block direct access to container ports
sudo ufw deny 3000  # Next.js
sudo ufw deny 5678  # N8N
sudo ufw deny 9000  # PHP-FPM
```

### 8. 💾 Backup Strategy for Dual Projects

```bash
#!/bin/bash
# backup/dual-project-backup.sh

BACKUP_DIR="/backup/dual-projects"
DATE=$(date +%Y%m%d_%H%M%S)

# N8N Data Backup
docker exec postgres-shared pg_dump -U n8n_user -d n8n_db > $BACKUP_DIR/n8n_$DATE.sql
docker cp n8n-workflow:/home/node/.n8n $BACKUP_DIR/n8n_workflows_$DATE/

# Khidmaapp Data Backup  
docker exec postgres-shared pg_dump -U khidmaapp_user -d khidmaapp_db -Fc > $BACKUP_DIR/khidmaapp_$DATE.dump
docker cp khidmaapp-api:/var/www/html/storage $BACKUP_DIR/khidmaapp_storage_$DATE/

# Compress backups
tar -czf $BACKUP_DIR/n8n_complete_$DATE.tar.gz $BACKUP_DIR/n8n_*_$DATE.*
tar -czf $BACKUP_DIR/khidmaapp_complete_$DATE.tar.gz $BACKUP_DIR/khidmaapp_*_$DATE.*

# Upload to cloud storage
# aws s3 sync $BACKUP_DIR s3://dual-projects-backup/
```

## 🎯 DEPLOYMENT PHASES

### Phase 1: Preparation & Testing (1-2 days)
1. ✅ Current resource analysis
2. ✅ Docker environment setup
3. ✅ Database migration planning
4. ✅ SSL certificate acquisition

### Phase 2: Infrastructure Setup (2-3 days)
1. ✅ Docker containers deployment
2. ✅ Nginx reverse proxy configuration  
3. ✅ Database setup and migration
4. ✅ SSL and domain configuration

### Phase 3: Application Deployment (3-4 days)
1. ✅ Khidmaapp backend deployment
2. ✅ Frontend deployment and optimization
3. ✅ Testing and performance tuning
4. ✅ Monitoring and alerting setup

### Phase 4: Production Optimization (1-2 days)
1. ✅ Performance monitoring
2. ✅ Resource optimization
3. ✅ Backup verification
4. ✅ Security audit

## 📈 PERFORMANCE EXPECTATIONS

### Expected Resource Usage:
```
💾 RAM Usage: 75-85% (6-7GB used)
⚡ CPU Usage: 40-60% average
💿 Disk Usage: 40-50GB for applications
🌐 Network: Excellent (1 Gbps)
```

### Performance Targets:
- **N8N**: No performance degradation
- **Khidmaapp**: <200ms API response
- **Frontend**: <2s page load time
- **Database**: <100ms query time

## 🚨 Risk Mitigation

### Potential Issues & Solutions:
1. **Memory exhaustion**: Resource limits + alerting
2. **CPU bottleneck**: Process prioritization
3. **Disk space**: Automated cleanup + monitoring
4. **Network conflicts**: Port isolation + proxy

### Rollback Plan:
1. **Database snapshots** before deployment
2. **Container image tagging** for quick rollback
3. **DNS TTL reduction** for quick switching
4. **Backup restoration** procedures documented

## 💰 COST ANALYSIS

### Current vs New Setup:
```
Current: Hostinger KVM 2 ($25/ay) + N8N
New: Hostinger KVM 2 ($25/ay) + N8N + Khidmaapp

Additional Costs: $0/ay
Savings vs Cloud: $60-85/ay (vs managed services)
F/P Ratio: EXCELLENT! 🔥
```

## ✅ GO/NO-GO CRITERIA

### ✅ GO Conditions:
- Available RAM > 50% (4GB+)
- CPU load < 70%
- Disk space > 50GB free
- N8N performance stable

### ❌ NO-GO Conditions:
- Available RAM < 30% (2.4GB)
- CPU load > 90% sustained
- Disk space < 30GB free
- N8N performance degraded

---

**SONUÇ: Hostinger KVM 2'de iki proje profesyonel şekilde çalıştırılabilir! Container isolation + resource management ile excellent F/P ratio! 🚀**
