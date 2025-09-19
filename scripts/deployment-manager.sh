#!/bin/bash
# Automated Deployment Manager for Dual Project Setup
# Hostinger KVM 2 + API Integration

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
CONFIG_FILE="$SCRIPT_DIR/../config/hostinger.conf"
API_SCRIPT="$SCRIPT_DIR/hostinger-api.sh"

# Load configuration
if [ ! -f "$CONFIG_FILE" ]; then
    echo "❌ Error: Configuration file not found: $CONFIG_FILE"
    exit 1
fi

source "$CONFIG_FILE"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

print_status() { echo -e "${BLUE}[INFO]${NC} $1"; }
print_success() { echo -e "${GREEN}[SUCCESS]${NC} $1"; }
print_warning() { echo -e "${YELLOW}[WARNING]${NC} $1"; }
print_error() { echo -e "${RED}[ERROR]${NC} $1"; }

# Function to check prerequisites
check_prerequisites() {
    print_status "Checking deployment prerequisites..."
    
    local errors=0
    
    # Check SSH connectivity
    if ! ssh -o ConnectTimeout=5 -o BatchMode=yes "$VPS_USER@$VPS_HOSTNAME" exit 2>/dev/null; then
        print_error "SSH connectivity to VPS failed"
        ((errors++))
    else
        print_success "SSH connectivity verified"
    fi
    
    # Check API connectivity
    if [ -f "$API_SCRIPT" ]; then
        if bash "$API_SCRIPT" test >/dev/null 2>&1; then
            print_success "Hostinger API connectivity verified"
        else
            print_warning "Hostinger API test failed (continuing anyway)"
        fi
    else
        print_warning "API script not found: $API_SCRIPT"
    fi
    
    # Check required tools
    for tool in curl jq docker; do
        if ! command -v "$tool" &> /dev/null; then
            print_warning "$tool is not installed locally"
        else
            print_success "$tool is available"
        fi
    done
    
    return $errors
}

# Function to analyze VPS resources
analyze_vps_resources() {
    print_status "Analyzing VPS resources..."
    
    local analysis_result
    analysis_result=$(ssh "$VPS_USER@$VPS_HOSTNAME" << 'EOF'
#!/bin/bash
echo "RESOURCE_ANALYSIS_START"

# Memory analysis
TOTAL_RAM=$(free | grep Mem | awk '{print $2}')
USED_RAM=$(free | grep Mem | awk '{print $3}')
AVAILABLE_RAM=$(free | grep Mem | awk '{print $7}')
AVAILABLE_PERCENT=$(( AVAILABLE_RAM * 100 / TOTAL_RAM ))

echo "TOTAL_RAM_MB=$((TOTAL_RAM / 1024))"
echo "USED_RAM_MB=$((USED_RAM / 1024))"
echo "AVAILABLE_RAM_MB=$((AVAILABLE_RAM / 1024))"
echo "AVAILABLE_RAM_PERCENT=$AVAILABLE_PERCENT"

# Disk analysis
DISK_TOTAL=$(df / | tail -1 | awk '{print $2}')
DISK_USED=$(df / | tail -1 | awk '{print $3}')
DISK_AVAILABLE=$(df / | tail -1 | awk '{print $4}')
DISK_USE_PERCENT=$(df / | tail -1 | awk '{print $5}' | sed 's/%//')

echo "DISK_TOTAL_GB=$((DISK_TOTAL / 1024 / 1024))"
echo "DISK_USED_GB=$((DISK_USED / 1024 / 1024))"
echo "DISK_AVAILABLE_GB=$((DISK_AVAILABLE / 1024 / 1024))"
echo "DISK_USE_PERCENT=$DISK_USE_PERCENT"

# CPU analysis
CPU_CORES=$(nproc)
LOAD_AVERAGE=$(cat /proc/loadavg | cut -d' ' -f1)
echo "CPU_CORES=$CPU_CORES"
echo "LOAD_AVERAGE=$LOAD_AVERAGE"

# N8N detection
if pgrep -f "n8n" > /dev/null; then
    N8N_PID=$(pgrep -f "n8n")
    N8N_MEM_PERCENT=$(ps -p $N8N_PID -o %mem --no-headers | tr -d ' ')
    N8N_CPU_PERCENT=$(ps -p $N8N_PID -o %cpu --no-headers | tr -d ' ')
    echo "N8N_RUNNING=true"
    echo "N8N_MEM_PERCENT=$N8N_MEM_PERCENT"
    echo "N8N_CPU_PERCENT=$N8N_CPU_PERCENT"
else
    echo "N8N_RUNNING=false"
fi

# Docker check
if command -v docker &> /dev/null; then
    echo "DOCKER_INSTALLED=true"
    DOCKER_CONTAINERS=$(docker ps -q | wc -l)
    echo "DOCKER_CONTAINERS_RUNNING=$DOCKER_CONTAINERS"
else
    echo "DOCKER_INSTALLED=false"
fi

echo "RESOURCE_ANALYSIS_END"
EOF
)
    
    # Parse analysis results
    local available_ram_percent
    local available_disk_gb
    local n8n_running
    
    available_ram_percent=$(echo "$analysis_result" | grep "AVAILABLE_RAM_PERCENT=" | cut -d'=' -f2)
    available_disk_gb=$(echo "$analysis_result" | grep "DISK_AVAILABLE_GB=" | cut -d'=' -f2)
    n8n_running=$(echo "$analysis_result" | grep "N8N_RUNNING=" | cut -d'=' -f2)
    
    echo ""
    echo "📊 RESOURCE ANALYSIS RESULTS:"
    echo "============================="
    echo "$analysis_result" | grep -E "^(TOTAL_RAM_MB|AVAILABLE_RAM_MB|AVAILABLE_RAM_PERCENT|DISK_AVAILABLE_GB|CPU_CORES|N8N_RUNNING)=" | while read line; do
        echo "  $line"
    done
    echo ""
    
    # Deployment recommendation
    if [ "$available_ram_percent" -gt 40 ] && [ "$available_disk_gb" -gt 50 ]; then
        print_success "✅ EXCELLENT: Ready for dual-project deployment!"
        echo "  Available RAM: ${available_ram_percent}%"
        echo "  Available Disk: ${available_disk_gb}GB"
        echo "  N8N Status: $n8n_running"
        return 0
    elif [ "$available_ram_percent" -gt 25 ] && [ "$available_disk_gb" -gt 30 ]; then
        print_warning "⚠️ GOOD: Deployment possible with careful resource management"
        echo "  Available RAM: ${available_ram_percent}%"
        echo "  Available Disk: ${available_disk_gb}GB"
        echo "  N8N Status: $n8n_running"
        return 1
    else
        print_error "❌ LIMITED: Optimization required before deployment"
        echo "  Available RAM: ${available_ram_percent}%"
        echo "  Available Disk: ${available_disk_gb}GB"
        echo "  N8N Status: $n8n_running"
        return 2
    fi
}

# Function to prepare VPS environment
prepare_vps_environment() {
    print_status "Preparing VPS environment for dual-project deployment..."
    
    ssh "$VPS_USER@$VPS_HOSTNAME" << 'PREPARE_EOF'
#!/bin/bash
set -e

echo "🔧 PREPARING VPS ENVIRONMENT"
echo "============================"

# Update system
echo "📦 Updating system packages..."
sudo apt update && sudo apt upgrade -y

# Install required packages
echo "🛠️ Installing required packages..."
sudo apt install -y curl wget git unzip software-properties-common apt-transport-https ca-certificates gnupg lsb-release

# Install Docker if not present
if ! command -v docker &> /dev/null; then
    echo "🐳 Installing Docker..."
    curl -fsSL https://get.docker.com -o get-docker.sh
    sudo sh get-docker.sh
    sudo usermod -aG docker $USER
    sudo systemctl enable docker
    sudo systemctl start docker
    rm get-docker.sh
    echo "✅ Docker installed successfully"
else
    echo "✅ Docker already installed"
fi

# Install Docker Compose
if ! command -v docker-compose &> /dev/null; then
    echo "🐙 Installing Docker Compose..."
    sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
    sudo chmod +x /usr/local/bin/docker-compose
    echo "✅ Docker Compose installed successfully"
else
    echo "✅ Docker Compose already installed"
fi

# Create project directories
echo "📁 Creating project directories..."
sudo mkdir -p /opt/khidmaapp/{backend,frontend,database,nginx,ssl,backups}
sudo mkdir -p /opt/khidmaapp/logs/{nginx,api,frontend,database}
sudo chown -R $USER:$USER /opt/khidmaapp

# Create backup directory
sudo mkdir -p /backup/dual-projects
sudo chown -R $USER:$USER /backup

# Install additional tools
echo "🔧 Installing additional tools..."
sudo apt install -y htop iotop ncdu jq postgresql-client

# Configure firewall
echo "🔒 Configuring firewall..."
sudo ufw --force reset
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow ssh
sudo ufw allow 80
sudo ufw allow 443
sudo ufw --force enable

echo "✅ VPS environment preparation completed!"
PREPARE_EOF
    
    if [ $? -eq 0 ]; then
        print_success "VPS environment prepared successfully"
        return 0
    else
        print_error "VPS environment preparation failed"
        return 1
    fi
}

# Function to deploy Docker infrastructure
deploy_infrastructure() {
    print_status "Deploying Docker infrastructure..."
    
    # Upload Docker configuration files
    print_status "Uploading Docker configuration..."
    
    # Create docker-compose.yml for dual project
    ssh "$VPS_USER@$VPS_HOSTNAME" << 'DOCKER_EOF'
cat > /opt/khidmaapp/docker-compose.yml << 'COMPOSE_FILE'
version: '3.8'

services:
  # Nginx Reverse Proxy
  nginx:
    image: nginx:alpine
    container_name: nginx-dual-proxy
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx/conf.d:/etc/nginx/conf.d:ro
      - ./ssl:/etc/ssl/certs:ro
      - ./logs/nginx:/var/log/nginx
    restart: unless-stopped
    depends_on:
      - khidmaapp-api
    deploy:
      resources:
        limits:
          memory: 256M
          cpus: "0.2"

  # PostgreSQL (Shared for both projects)
  postgres:
    image: postgres:15-alpine
    container_name: postgres-dual
    environment:
      POSTGRES_DB: postgres
      POSTGRES_USER: postgres
      POSTGRES_PASSWORD: ${POSTGRES_PASSWORD:-secure_password_change_me}
    volumes:
      - postgres_data:/var/lib/postgresql/data
      - ./database/init:/docker-entrypoint-initdb.d
      - ./logs/database:/var/log/postgresql
    ports:
      - "127.0.0.1:5432:5432"
    restart: unless-stopped
    deploy:
      resources:
        limits:
          memory: 2G
          cpus: "0.5"
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U postgres"]
      interval: 30s
      timeout: 10s
      retries: 3

  # Redis for Khidmaapp
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

  # MeiliSearch for Khidmaapp
  meilisearch:
    image: getmeili/meilisearch:latest
    container_name: meilisearch-khidmaapp
    environment:
      - MEILI_MASTER_KEY=${MEILI_MASTER_KEY:-secure_meili_key_change_me}
      - MEILI_ENV=production
    volumes:
      - meilisearch_data:/meili_data
    restart: unless-stopped
    deploy:
      resources:
        limits:
          memory: 512M
          cpus: "0.3"

  # Khidmaapp Laravel API (Placeholder)
  khidmaapp-api:
    image: nginx:alpine
    container_name: khidmaapp-api-placeholder
    volumes:
      - ./backend:/var/www/html:ro
    restart: unless-stopped
    deploy:
      resources:
        limits:
          memory: 1G
          cpus: "0.8"

volumes:
  postgres_data:
  redis_data:
  meilisearch_data:

COMPOSE_FILE

# Create nginx configuration
mkdir -p /opt/khidmaapp/nginx/conf.d
cat > /opt/khidmaapp/nginx/conf.d/default.conf << 'NGINX_CONF'
# Default server
server {
    listen 80 default_server;
    server_name _;
    
    # Redirect to HTTPS (when SSL is configured)
    # return 301 https://$server_name$request_uri;
    
    # Temporary placeholder
    location / {
        return 200 "Khidmaapp Infrastructure Ready - $(date)";
        add_header Content-Type text/plain;
    }
    
    location /health {
        access_log off;
        return 200 "OK";
        add_header Content-Type text/plain;
    }
}

# N8N subdomain (existing - to be configured)
# server {
#     listen 80;
#     server_name n8n.alasistan.com;
#     
#     location / {
#         proxy_pass http://host.docker.internal:5678;
#         proxy_set_header Host $host;
#         proxy_set_header X-Real-IP $remote_addr;
#         proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
#         proxy_set_header X-Forwarded-Proto $scheme;
#     }
# }

NGINX_CONF

# Create database initialization script
mkdir -p /opt/khidmaapp/database/init
cat > /opt/khidmaapp/database/init/01-init-databases.sql << 'SQL_INIT'
-- Create databases for dual project setup

-- N8N Database (if needed)
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

SQL_INIT

echo "✅ Docker configuration files created"
DOCKER_EOF
    
    # Start the infrastructure
    print_status "Starting Docker infrastructure..."
    ssh "$VPS_USER@$VPS_HOSTNAME" << 'START_DOCKER'
cd /opt/khidmaapp
docker-compose up -d
sleep 10
docker-compose ps
START_DOCKER
    
    if [ $? -eq 0 ]; then
        print_success "Docker infrastructure deployed successfully"
        return 0
    else
        print_error "Docker infrastructure deployment failed"
        return 1
    fi
}

# Function to verify deployment
verify_deployment() {
    print_status "Verifying deployment..."
    
    ssh "$VPS_USER@$VPS_HOSTNAME" << 'VERIFY_EOF'
echo "🔍 DEPLOYMENT VERIFICATION"
echo "=========================="

cd /opt/khidmaapp

echo "🐳 Docker containers status:"
docker-compose ps

echo ""
echo "📊 Container resource usage:"
docker stats --no-stream --format "table {{.Container}}\t{{.CPUPerc}}\t{{.MemUsage}}\t{{.MemPerc}}"

echo ""
echo "🗄️ Database connectivity:"
if docker exec postgres-dual pg_isready -U postgres; then
    echo "✅ PostgreSQL is ready"
    docker exec postgres-dual psql -U postgres -c "\l"
else
    echo "❌ PostgreSQL is not ready"
fi

echo ""
echo "🌐 Web server status:"
if curl -f http://localhost/health >/dev/null 2>&1; then
    echo "✅ Nginx is responding"
    curl -s http://localhost/
else
    echo "❌ Nginx is not responding"
fi

echo ""
echo "💾 System resources after deployment:"
echo "RAM Usage:"
free -h | grep Mem
echo "Disk Usage:"
df -h /
echo "Docker disk usage:"
docker system df

VERIFY_EOF
}

# Function to show deployment status
show_deployment_status() {
    echo ""
    echo "🎯 DEPLOYMENT STATUS DASHBOARD"
    echo "=============================="
    
    # API status
    if [ -f "$API_SCRIPT" ]; then
        echo "📡 Hostinger API Status:"
        if bash "$API_SCRIPT" test >/dev/null 2>&1; then
            echo "  ✅ API connectivity OK"
        else
            echo "  ❌ API connectivity failed"
        fi
    fi
    
    # SSH status  
    echo "🔗 SSH Connectivity:"
    if ssh -o ConnectTimeout=5 -o BatchMode=yes "$VPS_USER@$VPS_HOSTNAME" exit 2>/dev/null; then
        echo "  ✅ SSH OK"
    else
        echo "  ❌ SSH failed"
    fi
    
    # VPS status
    echo "🖥️ VPS Status:"
    ssh "$VPS_USER@$VPS_HOSTNAME" << 'STATUS_EOF'
echo "  Host: $(hostname)"
echo "  Uptime: $(uptime | cut -d',' -f1 | cut -d' ' -f4-)"
echo "  Load: $(cat /proc/loadavg | cut -d' ' -f1-3)"
echo "  Available RAM: $(free | grep Mem | awk '{printf "%.1f", ($7/1024/1024)}')GB"
echo "  Available Disk: $(df / | tail -1 | awk '{printf "%.1f", ($4/1024/1024)}')GB"

if command -v docker &> /dev/null; then
    echo "  Docker: $(docker --version | cut -d' ' -f3 | tr -d ',')"
    echo "  Running containers: $(docker ps -q | wc -l)"
else
    echo "  Docker: Not installed"
fi
STATUS_EOF
}

# Main menu
show_menu() {
    echo ""
    echo "🚀 DUAL PROJECT DEPLOYMENT MANAGER"
    echo "=================================="
    echo "1. Check prerequisites"
    echo "2. Analyze VPS resources"
    echo "3. Prepare VPS environment"
    echo "4. Deploy Docker infrastructure"
    echo "5. Verify deployment"
    echo "6. Show deployment status"
    echo "7. Full automated deployment"
    echo "8. API management (advanced)"
    echo "0. Exit"
    echo ""
    read -p "Select option [0-8]: " choice
    
    case $choice in
        1) check_prerequisites ;;
        2) analyze_vps_resources ;;
        3) prepare_vps_environment ;;
        4) deploy_infrastructure ;;
        5) verify_deployment ;;
        6) show_deployment_status ;;
        7) 
            print_status "Starting full automated deployment..."
            check_prerequisites && \
            analyze_vps_resources && \
            prepare_vps_environment && \
            deploy_infrastructure && \
            verify_deployment
            ;;
        8) 
            if [ -f "$API_SCRIPT" ]; then
                bash "$API_SCRIPT"
            else
                print_error "API script not found: $API_SCRIPT"
            fi
            ;;
        0) exit 0 ;;
        *) print_error "Invalid option" ;;
    esac
}

# Main execution
main() {
    echo "🎯 HOSTINGER DUAL PROJECT DEPLOYMENT"
    echo "Host: $VPS_HOSTNAME ($VPS_IP)"
    echo "======================================"
    
    if [ $# -eq 0 ]; then
        while true; do
            show_menu
        done
    else
        case $1 in
            "check") check_prerequisites ;;
            "analyze") analyze_vps_resources ;;
            "prepare") prepare_vps_environment ;;
            "deploy") deploy_infrastructure ;;
            "verify") verify_deployment ;;
            "status") show_deployment_status ;;
            "auto") 
                check_prerequisites && \
                analyze_vps_resources && \
                prepare_vps_environment && \
                deploy_infrastructure && \
                verify_deployment
                ;;
            *) 
                echo "Usage: $0 [command]"
                echo "Commands: check, analyze, prepare, deploy, verify, status, auto"
                ;;
        esac
    fi
}

# Run main function
main "$@"
