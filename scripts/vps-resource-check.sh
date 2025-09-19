#!/bin/bash
# VPS Resource Analysis for Dual Project Setup
# Hostinger KVM 2 - Current Usage Check

echo "🔍 HOSTINGER KVM 2 - KAYNAK KULLANIM ANALİZİ"
echo "=============================================="
date
echo ""

echo "📊 CPU & MEMORY USAGE:"
echo "----------------------"
# CPU usage
CPU_USAGE=$(top -bn1 | grep "Cpu(s)" | awk '{print $2+$4}' | cut -d'%' -f1)
echo "Current CPU Usage: ${CPU_USAGE}%"

# Memory usage breakdown
echo ""
echo "Memory Details:"
free -h | grep -E "Mem|Swap"
echo ""

# Memory usage by top processes
echo "🔥 TOP MEMORY CONSUMERS:"
echo "------------------------"
ps aux --sort=-%mem | head -10 | awk '{printf "%-8s %-6s %-6s %-15s %s\n", $1, $3, $4, $11, $2}'
echo ""

echo "💾 DISK USAGE:"
echo "--------------"
df -h | grep -E "Filesystem|/dev/"
echo ""

echo "📈 SYSTEM LOAD:"
echo "---------------"
uptime
echo ""

echo "🌐 NETWORK CONNECTIONS:"
echo "----------------------"
ss -tuln | grep LISTEN | wc -l
echo "Active listening ports: $(ss -tuln | grep LISTEN | wc -l)"
echo ""

echo "🔍 N8N PROCESS CHECK:"
echo "--------------------"
if pgrep -f "n8n" > /dev/null; then
    echo "✅ N8N is running"
    N8N_PID=$(pgrep -f "n8n")
    N8N_MEM=$(ps -p $N8N_PID -o %mem --no-headers | tr -d ' ')
    N8N_CPU=$(ps -p $N8N_PID -o %cpu --no-headers | tr -d ' ')
    echo "N8N Memory Usage: ${N8N_MEM}%"
    echo "N8N CPU Usage: ${N8N_CPU}%"
else
    echo "❌ N8N process not found"
fi
echo ""

echo "🐳 DOCKER CHECK:"
echo "----------------"
if command -v docker &> /dev/null; then
    echo "✅ Docker is installed"
    docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}" 2>/dev/null || echo "No running containers"
else
    echo "❌ Docker not installed"
fi
echo ""

echo "🗄️ DATABASE CHECK:"
echo "------------------"
if systemctl is-active --quiet postgresql; then
    echo "✅ PostgreSQL is running"
    PG_MEM=$(ps aux | grep postgres | awk '{sum+=$4} END {printf "%.1f", sum}')
    echo "PostgreSQL Memory Usage: ${PG_MEM}%"
elif systemctl is-active --quiet mysql; then
    echo "✅ MySQL is running"
    MYSQL_MEM=$(ps aux | grep mysql | awk '{sum+=$4} END {printf "%.1f", sum}')
    echo "MySQL Memory Usage: ${MYSQL_MEM}%"
else
    echo "❌ No major database detected"
fi
echo ""

echo "🌍 WEB SERVER CHECK:"
echo "--------------------"
if systemctl is-active --quiet nginx; then
    echo "✅ Nginx is running"
elif systemctl is-active --quiet apache2; then
    echo "✅ Apache is running"
else
    echo "❌ No web server detected"
fi
echo ""

echo "📦 AVAILABLE RESOURCES FOR KHIDMAAPP:"
echo "====================================="
TOTAL_MEM=$(free | grep Mem | awk '{print $2}')
USED_MEM=$(free | grep Mem | awk '{print $3}')
AVAILABLE_MEM=$(free | grep Mem | awk '{print $7}')
AVAILABLE_PERCENT=$(( AVAILABLE_MEM * 100 / TOTAL_MEM ))

echo "Total RAM: $(( TOTAL_MEM / 1024 / 1024 ))GB"
echo "Used RAM: $(( USED_MEM / 1024 / 1024 ))GB"
echo "Available RAM: $(( AVAILABLE_MEM / 1024 / 1024 ))GB (${AVAILABLE_PERCENT}%)"
echo ""

if [ $AVAILABLE_PERCENT -gt 40 ]; then
    echo "✅ SUFFICIENT RESOURCES - Khidmaapp can run comfortably"
elif [ $AVAILABLE_PERCENT -gt 25 ]; then
    echo "⚠️ MODERATE RESOURCES - Careful resource management needed"
else
    echo "❌ LIMITED RESOURCES - Consider optimization or upgrade"
fi

echo ""
echo "🎯 RECOMMENDATION:"
echo "=================="
if [ $AVAILABLE_PERCENT -gt 40 ]; then
    echo "✅ Proceed with dual-project setup"
    echo "✅ Use Docker containers for isolation"
    echo "✅ Set up resource limits and monitoring"
else
    echo "⚠️ Optimize current setup first"
    echo "⚠️ Consider resource limits"
    echo "⚠️ Monitor performance closely"
fi
