#!/bin/bash
# Hostinger VPS Management Commands
# Quick reference for common operations

VPS_HOST="srv895407.hstgr.cloud"
VPS_IP="145.223.81.123"

echo "🖥️ HOSTINGER VPS MANAGEMENT COMMANDS"
echo "===================================="
echo "Host: $VPS_HOST"
echo "IP: $VPS_IP"
echo ""

# Function to show command examples
show_commands() {
    echo "📋 BASIC CONNECTION COMMANDS:"
    echo "----------------------------"
    echo "# Standard connection:"
    echo "ssh root@$VPS_HOST"
    echo ""
    echo "# Connection via IP:"
    echo "ssh root@$VPS_IP"
    echo ""
    echo "# Connection with specific port (if needed):"
    echo "ssh -p 2222 root@$VPS_HOST"
    echo ""
    
    echo "📁 FILE TRANSFER COMMANDS:"
    echo "-------------------------"
    echo "# Upload file to VPS:"
    echo "scp local-file.txt root@$VPS_HOST:/tmp/"
    echo ""
    echo "# Download file from VPS:"
    echo "scp root@$VPS_HOST:/path/to/file.txt ./"
    echo ""
    echo "# Upload directory:"
    echo "scp -r local-directory/ root@$VPS_HOST:/tmp/"
    echo ""
    
    echo "🔍 RESOURCE MONITORING COMMANDS:"
    echo "--------------------------------"
    echo "# Check resource usage:"
    echo "ssh root@$VPS_HOST 'htop'"
    echo ""
    echo "# Memory usage:"
    echo "ssh root@$VPS_HOST 'free -h'"
    echo ""
    echo "# Disk usage:"
    echo "ssh root@$VPS_HOST 'df -h'"
    echo ""
    echo "# CPU information:"
    echo "ssh root@$VPS_HOST 'lscpu'"
    echo ""
    echo "# Running processes:"
    echo "ssh root@$VPS_HOST 'ps aux | head -20'"
    echo ""
    
    echo "🐳 DOCKER COMMANDS:"
    echo "------------------"
    echo "# Check if Docker is installed:"
    echo "ssh root@$VPS_HOST 'docker --version'"
    echo ""
    echo "# List running containers:"
    echo "ssh root@$VPS_HOST 'docker ps'"
    echo ""
    echo "# List all containers:"
    echo "ssh root@$VPS_HOST 'docker ps -a'"
    echo ""
    echo "# Docker system info:"
    echo "ssh root@$VPS_HOST 'docker system df'"
    echo ""
    
    echo "🔍 N8N DETECTION COMMANDS:"
    echo "-------------------------"
    echo "# Check N8N process:"
    echo "ssh root@$VPS_HOST 'pgrep -f n8n'"
    echo ""
    echo "# Check N8N service:"
    echo "ssh root@$VPS_HOST 'systemctl status n8n'"
    echo ""
    echo "# Check Node.js processes:"
    echo "ssh root@$VPS_HOST 'ps aux | grep node'"
    echo ""
    echo "# Check listening ports:"
    echo "ssh root@$VPS_HOST 'ss -tuln | grep LISTEN'"
    echo ""
    
    echo "🗄️ DATABASE COMMANDS:"
    echo "--------------------"
    echo "# Check PostgreSQL:"
    echo "ssh root@$VPS_HOST 'systemctl status postgresql'"
    echo ""
    echo "# Check MySQL:"
    echo "ssh root@$VPS_HOST 'systemctl status mysql'"
    echo ""
    echo "# List databases (PostgreSQL):"
    echo "ssh root@$VPS_HOST 'sudo -u postgres psql -l'"
    echo ""
    
    echo "🌐 WEB SERVER COMMANDS:"
    echo "----------------------"
    echo "# Check Nginx:"
    echo "ssh root@$VPS_HOST 'systemctl status nginx'"
    echo ""
    echo "# Check Apache:"
    echo "ssh root@$VPS_HOST 'systemctl status apache2'"
    echo ""
    echo "# Check web directories:"
    echo "ssh root@$VPS_HOST 'ls -la /var/www/'"
    echo ""
    
    echo "🔥 QUICK ANALYSIS COMMANDS:"
    echo "--------------------------"
    echo "# Complete system overview:"
    echo "ssh root@$VPS_HOST << 'EOF'"
    echo "echo '=== SYSTEM INFO ==='"
    echo "uname -a"
    echo "echo '=== MEMORY ==='"
    echo "free -h"
    echo "echo '=== DISK ==='"
    echo "df -h"
    echo "echo '=== CPU ==='"
    echo "top -bn1 | head -20"
    echo "echo '=== SERVICES ==='"
    echo "systemctl list-units --type=service --state=running | grep -E '(nginx|apache|mysql|postgresql|docker|n8n)'"
    echo "EOF"
    echo ""
}

# Function to run specific command
run_command() {
    case $1 in
        "connect"|"ssh")
            echo "🔗 Connecting to VPS..."
            ssh root@$VPS_HOST
            ;;
        "status"|"info")
            echo "📊 Getting VPS status..."
            ssh root@$VPS_HOST << 'EOF'
echo "🖥️  HOSTINGER KVM 2 STATUS"
echo "=========================="
echo "Hostname: $(hostname)"
echo "Uptime: $(uptime)"
echo "Load: $(cat /proc/loadavg)"
echo ""
echo "💾 Memory:"
free -h
echo ""
echo "💿 Disk:"
df -h /
echo ""
echo "🔥 Top processes:"
ps aux --sort=-%mem | head -10
EOF
            ;;
        "n8n")
            echo "🔍 Checking N8N status..."
            ssh root@$VPS_HOST << 'EOF'
echo "🎯 N8N STATUS CHECK"
echo "=================="
if pgrep -f "n8n" > /dev/null; then
    echo "✅ N8N is running"
    echo "PID: $(pgrep -f n8n)"
    echo "Memory: $(ps -p $(pgrep -f n8n) -o %mem --no-headers)%"
    echo "CPU: $(ps -p $(pgrep -f n8n) -o %cpu --no-headers)%"
else
    echo "❌ N8N process not found"
fi

echo ""
echo "🌐 Listening ports:"
ss -tuln | grep LISTEN | grep -E "(5678|3000|8080)"
EOF
            ;;
        "resources")
            echo "📈 Checking available resources..."
            ssh root@$VPS_HOST << 'EOF'
echo "📊 AVAILABLE RESOURCES FOR KHIDMAAPP"
echo "===================================="
TOTAL_RAM=$(free | grep Mem | awk '{print $2}')
USED_RAM=$(free | grep Mem | awk '{print $3}')
AVAILABLE_RAM=$(free | grep Mem | awk '{print $7}')
AVAILABLE_PERCENT=$(( AVAILABLE_RAM * 100 / TOTAL_RAM ))

echo "Total RAM: $(( TOTAL_RAM / 1024 / 1024 ))GB"
echo "Used RAM: $(( USED_RAM / 1024 / 1024 ))GB"
echo "Available RAM: $(( AVAILABLE_RAM / 1024 / 1024 ))GB (${AVAILABLE_PERCENT}%)"

DISK_FREE=$(df / | tail -1 | awk '{print $4}')
DISK_FREE_GB=$(( DISK_FREE / 1024 / 1024 ))
echo "Available Disk: ${DISK_FREE_GB}GB"

echo ""
if [ $AVAILABLE_PERCENT -gt 40 ] && [ $DISK_FREE_GB -gt 50 ]; then
    echo "✅ EXCELLENT: Ready for Khidmaapp deployment!"
elif [ $AVAILABLE_PERCENT -gt 25 ] && [ $DISK_FREE_GB -gt 30 ]; then
    echo "⚠️ GOOD: Khidmaapp can be deployed with monitoring"
else
    echo "❌ LIMITED: Optimization required"
fi
EOF
            ;;
        *)
            show_commands
            ;;
    esac
}

# Main menu
if [ $# -eq 0 ]; then
    echo "🎯 USAGE:"
    echo "-------"
    echo "$0 connect    - Connect to VPS"
    echo "$0 status     - Show VPS status"
    echo "$0 n8n        - Check N8N status"
    echo "$0 resources  - Check available resources"
    echo "$0 commands   - Show all available commands"
    echo ""
    echo "Or run without parameters to see all commands."
    echo ""
    show_commands
else
    run_command $1
fi
