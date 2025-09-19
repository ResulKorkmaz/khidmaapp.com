#!/bin/bash
# Quick SSH Connection to Hostinger VPS
# srv895407.hstgr.cloud - 145.223.81.123

VPS_HOST="srv895407.hstgr.cloud"
VPS_IP="145.223.81.123"
VPS_USER="root"

echo "🚀 HOSTINGER VPS QUICK CONNECT"
echo "=============================="
echo "Host: $VPS_HOST"
echo "IP: $VPS_IP"
echo ""

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${BLUE}[INFO]${NC} Attempting SSH connection..."

# Try different connection methods
echo -e "${YELLOW}Method 1:${NC} Connecting via hostname..."
if timeout 10 ssh -o ConnectTimeout=5 -o StrictHostKeyChecking=no root@$VPS_HOST "echo 'Connected successfully via hostname'"; then
    echo -e "${GREEN}[SUCCESS]${NC} Connected via hostname!"
    echo ""
    echo "To connect again, use:"
    echo "ssh root@$VPS_HOST"
    exit 0
fi

echo -e "${YELLOW}Method 2:${NC} Connecting via IP address..."
if timeout 10 ssh -o ConnectTimeout=5 -o StrictHostKeyChecking=no root@$VPS_IP "echo 'Connected successfully via IP'"; then
    echo -e "${GREEN}[SUCCESS]${NC} Connected via IP address!"
    echo ""
    echo "To connect again, use:"
    echo "ssh root@$VPS_IP"
    exit 0
fi

echo -e "${YELLOW}Method 3:${NC} Trying port 2222..."
if timeout 10 ssh -o ConnectTimeout=5 -o StrictHostKeyChecking=no -p 2222 root@$VPS_HOST "echo 'Connected successfully on port 2222'"; then
    echo -e "${GREEN}[SUCCESS]${NC} Connected on port 2222!"
    echo ""
    echo "To connect again, use:"
    echo "ssh -p 2222 root@$VPS_HOST"
    exit 0
fi

echo -e "${YELLOW}Method 4:${NC} Interactive connection attempt..."
echo "Trying manual connection..."
ssh root@$VPS_HOST
