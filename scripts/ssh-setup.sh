#!/bin/bash
# SSH Setup Script for Hostinger KVM 2 VPS
# Host: srv895407.hstgr.cloud
# IP: 145.223.81.123

echo "🔐 HOSTINGER VPS SSH SETUP"
echo "=========================="
echo "Host: srv895407.hstgr.cloud"
echo "IP: 145.223.81.123"
echo ""

# VPS Details
VPS_HOST="srv895407.hstgr.cloud"
VPS_IP="145.223.81.123"
VPS_USER="root"  # Hostinger default
SSH_PORT="22"    # Default port

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Function to test SSH connection
test_ssh_connection() {
    print_status "Testing SSH connection to $VPS_HOST..."
    
    # Test different ports (Hostinger sometimes uses non-standard ports)
    for port in 22 2222 1022; do
        print_status "Trying port $port..."
        if timeout 10 ssh -o ConnectTimeout=5 -o StrictHostKeyChecking=no -p $port $VPS_USER@$VPS_HOST "echo 'SSH connection successful on port $port'" 2>/dev/null; then
            SSH_PORT=$port
            print_success "SSH connection successful on port $port"
            return 0
        fi
    done
    
    print_error "SSH connection failed on all tested ports"
    return 1
}

# Function to create SSH config entry
create_ssh_config() {
    SSH_CONFIG_FILE="$HOME/.ssh/config"
    
    print_status "Creating SSH config entry..."
    
    # Create SSH config directory if it doesn't exist
    mkdir -p "$HOME/.ssh"
    chmod 700 "$HOME/.ssh"
    
    # Remove existing entry if exists
    if grep -q "Host hostinger-khidmaapp" "$SSH_CONFIG_FILE" 2>/dev/null; then
        print_warning "Existing SSH config entry found, updating..."
        # Remove old entry (from Host line to next Host line or end of file)
        sed -i '/^Host hostinger-khidmaapp$/,/^Host /{ /^Host hostinger-khidmaapp$/d; /^Host /!d; }' "$SSH_CONFIG_FILE"
    fi
    
    # Add new SSH config entry
    cat >> "$SSH_CONFIG_FILE" << EOF

# Hostinger KVM 2 VPS for Khidmaapp.com
Host hostinger-khidmaapp
    HostName $VPS_HOST
    User $VPS_USER
    Port $SSH_PORT
    IdentityFile ~/.ssh/id_rsa
    StrictHostKeyChecking no
    UserKnownHostsFile /dev/null
    ServerAliveInterval 60
    ServerAliveCountMax 3
    
# Alternative using IP address
Host hostinger-khidmaapp-ip
    HostName $VPS_IP
    User $VPS_USER
    Port $SSH_PORT
    IdentityFile ~/.ssh/id_rsa
    StrictHostKeyChecking no
    UserKnownHostsFile /dev/null
    ServerAliveInterval 60
    ServerAliveCountMax 3

EOF

    chmod 600 "$SSH_CONFIG_FILE"
    print_success "SSH config entry created successfully!"
    
    echo ""
    print_status "You can now connect using:"
    echo "  ssh hostinger-khidmaapp"
    echo "  ssh hostinger-khidmaapp-ip"
}

# Function to generate SSH key if not exists
generate_ssh_key() {
    if [ ! -f "$HOME/.ssh/id_rsa" ]; then
        print_status "Generating SSH key pair..."
        ssh-keygen -t rsa -b 4096 -C "khidmaapp-$(whoami)@$(hostname)" -f "$HOME/.ssh/id_rsa" -N ""
        print_success "SSH key pair generated!"
        
        echo ""
        print_warning "IMPORTANT: Copy this public key to your VPS:"
        echo "----------------------------------------"
        cat "$HOME/.ssh/id_rsa.pub"
        echo "----------------------------------------"
        echo ""
        print_status "Add this key to VPS: echo 'PUBLIC_KEY_ABOVE' >> ~/.ssh/authorized_keys"
    else
        print_status "SSH key already exists"
    fi
}

# Function to upload resource check script to VPS
upload_resource_script() {
    local script_path="./vps-resource-check.sh"
    
    if [ ! -f "$script_path" ]; then
        print_error "Resource check script not found: $script_path"
        return 1
    fi
    
    print_status "Uploading resource check script to VPS..."
    
    # Upload script
    scp -P $SSH_PORT "$script_path" $VPS_USER@$VPS_HOST:/tmp/vps-resource-check.sh
    
    if [ $? -eq 0 ]; then
        print_success "Script uploaded successfully!"
        
        # Make script executable and run it
        ssh -p $SSH_PORT $VPS_USER@$VPS_HOST "chmod +x /tmp/vps-resource-check.sh && /tmp/vps-resource-check.sh"
    else
        print_error "Failed to upload script"
        return 1
    fi
}

# Function to run initial VPS analysis
run_initial_analysis() {
    print_status "Running initial VPS analysis..."
    
    ssh -p $SSH_PORT $VPS_USER@$VPS_HOST << 'EOF'
echo "🔍 HOSTINGER KVM 2 INITIAL ANALYSIS"
echo "===================================="
date
echo ""

echo "📊 SYSTEM INFORMATION:"
echo "----------------------"
echo "Hostname: $(hostname)"
echo "OS: $(cat /etc/os-release | grep PRETTY_NAME | cut -d'"' -f2)"
echo "Kernel: $(uname -r)"
echo "Architecture: $(uname -m)"
echo ""

echo "💾 HARDWARE SPECIFICATIONS:"
echo "---------------------------"
echo "CPU Cores: $(nproc)"
echo "CPU Model: $(lscpu | grep 'Model name' | cut -d':' -f2 | xargs)"
echo "Total RAM: $(free -h | grep Mem | awk '{print $2}')"
echo "Total Disk: $(df -h / | tail -1 | awk '{print $2}')"
echo ""

echo "⚡ CURRENT RESOURCE USAGE:"
echo "-------------------------"
echo "CPU Usage: $(top -bn1 | grep "Cpu(s)" | awk '{print $2+$4}' | cut -d'%' -f1)%"
echo "Memory Usage:"
free -h | grep -E "Mem|Swap"
echo ""
echo "Disk Usage:"
df -h | grep -E "Filesystem|/dev/"
echo ""

echo "🔍 RUNNING SERVICES:"
echo "-------------------"
echo "Active services:"
systemctl list-units --type=service --state=running | grep -E "(nginx|apache|mysql|postgresql|docker|n8n)" || echo "No major services detected"
echo ""

echo "🌐 NETWORK PORTS:"
echo "----------------"
echo "Listening ports:"
ss -tuln | grep LISTEN | head -10
echo ""

echo "📦 DOCKER STATUS:"
echo "----------------"
if command -v docker &> /dev/null; then
    echo "✅ Docker is installed"
    echo "Docker version: $(docker --version)"
    echo "Running containers:"
    docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}" 2>/dev/null || echo "No containers running"
else
    echo "❌ Docker not installed"
fi
echo ""

echo "🗄️ DATABASE STATUS:"
echo "------------------"
if systemctl is-active --quiet postgresql; then
    echo "✅ PostgreSQL is running"
    sudo -u postgres psql -c "SELECT version();" 2>/dev/null | head -3
elif systemctl is-active --quiet mysql; then
    echo "✅ MySQL is running"
    mysql --version
else
    echo "❌ No major database detected"
fi
echo ""

echo "🔍 N8N DETECTION:"
echo "----------------"
if pgrep -f "n8n" > /dev/null; then
    echo "✅ N8N process detected"
    N8N_PID=$(pgrep -f "n8n")
    echo "N8N PID: $N8N_PID"
    echo "N8N Memory: $(ps -p $N8N_PID -o %mem --no-headers | tr -d ' ')%"
    echo "N8N CPU: $(ps -p $N8N_PID -o %cpu --no-headers | tr -d ' ')%"
elif systemctl list-units --type=service | grep -i n8n; then
    echo "✅ N8N service detected"
    systemctl status n8n --no-pager -l
else
    echo "❌ N8N not detected as process or service"
    echo "Checking for node processes:"
    ps aux | grep node | grep -v grep || echo "No node processes found"
fi
echo ""

echo "📁 WEB DIRECTORIES:"
echo "------------------"
echo "Common web directories:"
ls -la /var/www/ 2>/dev/null || echo "/var/www not found"
ls -la /opt/ 2>/dev/null | grep -E "(n8n|node|app)" || echo "No apps in /opt"
ls -la /home/ 2>/dev/null | grep -v root || echo "No user directories"
echo ""

echo "🎯 KHIDMAAPP READINESS:"
echo "======================"
AVAILABLE_RAM=$(free | grep Mem | awk '{printf "%.0f", ($7/1024/1024)}')
AVAILABLE_PERCENT=$(free | grep Mem | awk '{printf "%.0f", ($7/$2)*100}')
DISK_FREE=$(df / | tail -1 | awk '{print $4}')
DISK_FREE_GB=$(( DISK_FREE / 1024 / 1024 ))

echo "Available RAM: ${AVAILABLE_RAM}GB (${AVAILABLE_PERCENT}%)"
echo "Available Disk: ${DISK_FREE_GB}GB"

if [ $AVAILABLE_PERCENT -gt 40 ] && [ $DISK_FREE_GB -gt 50 ]; then
    echo "✅ EXCELLENT: Ready for Khidmaapp deployment!"
elif [ $AVAILABLE_PERCENT -gt 25 ] && [ $DISK_FREE_GB -gt 30 ]; then
    echo "⚠️ GOOD: Khidmaapp can be deployed with monitoring"
else
    echo "❌ LIMITED: Optimization required before deployment"
fi

echo ""
echo "Analysis completed at $(date)"
EOF
}

# Main execution
main() {
    echo "Starting SSH setup for Hostinger VPS..."
    echo ""
    
    # Step 1: Generate SSH key if needed
    generate_ssh_key
    
    # Step 2: Test SSH connection
    if test_ssh_connection; then
        # Step 3: Create SSH config
        create_ssh_config
        
        # Step 4: Run initial analysis
        run_initial_analysis
        
        echo ""
        print_success "SSH setup completed successfully!"
        echo ""
        print_status "Next steps:"
        echo "1. Review the analysis above"
        echo "2. If ready, proceed with dual-project deployment"
        echo "3. Use 'ssh hostinger-khidmaapp' to connect anytime"
        echo ""
        print_status "Quick commands:"
        echo "  Connect: ssh hostinger-khidmaapp"
        echo "  Upload files: scp file.txt hostinger-khidmaapp:/tmp/"
        echo "  Run commands: ssh hostinger-khidmaapp 'command'"
        
    else
        print_error "SSH connection failed. Please check:"
        echo "1. VPS is running and accessible"
        echo "2. SSH service is running on VPS"
        echo "3. Your IP is not blocked by firewall"
        echo "4. Username/password are correct"
        echo ""
        print_status "Manual connection test:"
        echo "ssh -p 22 root@$VPS_HOST"
        echo "ssh -p 22 root@$VPS_IP"
    fi
}

# Run main function
main "$@"
