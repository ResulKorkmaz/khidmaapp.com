#!/bin/bash
# Hostinger API Management Script
# Secure VPS management via Hostinger API

# Load configuration
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
CONFIG_FILE="$SCRIPT_DIR/../config/hostinger.conf"

if [ ! -f "$CONFIG_FILE" ]; then
    echo "❌ Error: Configuration file not found: $CONFIG_FILE"
    exit 1
fi

# Source configuration
source "$CONFIG_FILE"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# API Configuration
API_BASE_URL="${HOSTINGER_API_URL:-https://api.hostinger.com/v1}"
API_KEY="$HOSTINGER_API_KEY"

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

# Function to make API requests
api_request() {
    local method="$1"
    local endpoint="$2"
    local data="$3"
    
    local url="$API_BASE_URL$endpoint"
    local response
    
    if [ "$method" = "GET" ]; then
        response=$(curl -s -X GET \
            -H "Authorization: Bearer $API_KEY" \
            -H "Content-Type: application/json" \
            "$url")
    elif [ "$method" = "POST" ]; then
        response=$(curl -s -X POST \
            -H "Authorization: Bearer $API_KEY" \
            -H "Content-Type: application/json" \
            -d "$data" \
            "$url")
    elif [ "$method" = "PUT" ]; then
        response=$(curl -s -X PUT \
            -H "Authorization: Bearer $API_KEY" \
            -H "Content-Type: application/json" \
            -d "$data" \
            "$url")
    fi
    
    echo "$response"
}

# Function to test API connectivity
test_api() {
    print_status "Testing Hostinger API connectivity..."
    
    local response
    response=$(api_request "GET" "/user/profile" "")
    
    if echo "$response" | grep -q "error"; then
        print_error "API test failed: $response"
        return 1
    else
        print_success "API connectivity test passed"
        echo "$response" | jq '.' 2>/dev/null || echo "$response"
        return 0
    fi
}

# Function to get VPS list
get_vps_list() {
    print_status "Fetching VPS list..."
    
    local response
    response=$(api_request "GET" "/vps" "")
    
    if echo "$response" | grep -q "error"; then
        print_error "Failed to fetch VPS list: $response"
        return 1
    else
        print_success "VPS list retrieved successfully"
        echo "$response" | jq '.' 2>/dev/null || echo "$response"
        return 0
    fi
}

# Function to get specific VPS details
get_vps_details() {
    local vps_id="$1"
    
    if [ -z "$vps_id" ]; then
        print_error "VPS ID is required"
        return 1
    fi
    
    print_status "Fetching VPS details for ID: $vps_id"
    
    local response
    response=$(api_request "GET" "/vps/$vps_id" "")
    
    if echo "$response" | grep -q "error"; then
        print_error "Failed to fetch VPS details: $response"
        return 1
    else
        print_success "VPS details retrieved successfully"
        echo "$response" | jq '.' 2>/dev/null || echo "$response"
        return 0
    fi
}

# Function to get VPS statistics
get_vps_stats() {
    local vps_id="$1"
    
    if [ -z "$vps_id" ]; then
        print_error "VPS ID is required"
        return 1
    fi
    
    print_status "Fetching VPS statistics for ID: $vps_id"
    
    local response
    response=$(api_request "GET" "/vps/$vps_id/stats" "")
    
    if echo "$response" | grep -q "error"; then
        print_error "Failed to fetch VPS statistics: $response"
        return 1
    else
        print_success "VPS statistics retrieved successfully"
        echo "$response" | jq '.' 2>/dev/null || echo "$response"
        return 0
    fi
}

# Function to restart VPS
restart_vps() {
    local vps_id="$1"
    
    if [ -z "$vps_id" ]; then
        print_error "VPS ID is required"
        return 1
    fi
    
    print_warning "Restarting VPS with ID: $vps_id"
    read -p "Are you sure you want to restart the VPS? (y/N): " confirm
    
    if [ "$confirm" != "y" ] && [ "$confirm" != "Y" ]; then
        print_status "VPS restart cancelled"
        return 0
    fi
    
    local response
    response=$(api_request "POST" "/vps/$vps_id/restart" '{}')
    
    if echo "$response" | grep -q "error"; then
        print_error "Failed to restart VPS: $response"
        return 1
    else
        print_success "VPS restart initiated successfully"
        echo "$response" | jq '.' 2>/dev/null || echo "$response"
        return 0
    fi
}

# Function to get VPS backups
get_vps_backups() {
    local vps_id="$1"
    
    if [ -z "$vps_id" ]; then
        print_error "VPS ID is required"
        return 1
    fi
    
    print_status "Fetching VPS backups for ID: $vps_id"
    
    local response
    response=$(api_request "GET" "/vps/$vps_id/backups" "")
    
    if echo "$response" | grep -q "error"; then
        print_error "Failed to fetch VPS backups: $response"
        return 1
    else
        print_success "VPS backups retrieved successfully"
        echo "$response" | jq '.' 2>/dev/null || echo "$response"
        return 0
    fi
}

# Function to create VPS backup
create_vps_backup() {
    local vps_id="$1"
    local backup_name="$2"
    
    if [ -z "$vps_id" ]; then
        print_error "VPS ID is required"
        return 1
    fi
    
    if [ -z "$backup_name" ]; then
        backup_name="backup-$(date +%Y%m%d-%H%M%S)"
    fi
    
    print_status "Creating VPS backup: $backup_name"
    
    local data="{\"name\": \"$backup_name\"}"
    local response
    response=$(api_request "POST" "/vps/$vps_id/backups" "$data")
    
    if echo "$response" | grep -q "error"; then
        print_error "Failed to create VPS backup: $response"
        return 1
    else
        print_success "VPS backup creation initiated: $backup_name"
        echo "$response" | jq '.' 2>/dev/null || echo "$response"
        return 0
    fi
}

# Function to monitor VPS resources
monitor_vps() {
    local vps_id="$1"
    local interval="${2:-60}"
    
    if [ -z "$vps_id" ]; then
        print_error "VPS ID is required"
        return 1
    fi
    
    print_status "Starting VPS monitoring (interval: ${interval}s, Ctrl+C to stop)"
    
    while true; do
        echo ""
        echo "=== VPS MONITORING - $(date) ==="
        
        # Get VPS stats via API
        local stats_response
        stats_response=$(get_vps_stats "$vps_id")
        
        # Also get direct SSH stats for comparison
        print_status "Getting real-time SSH stats from VPS..."
        ssh -o ConnectTimeout=5 "$VPS_USER@$VPS_HOSTNAME" << 'EOF'
echo "📊 REAL-TIME VPS STATS"
echo "======================"
echo "CPU Usage: $(top -bn1 | grep "Cpu(s)" | awk '{print $2+$4}' | cut -d'%' -f1)%"
echo "Memory Usage:"
free -h | grep -E "Mem|Swap"
echo "Disk Usage:"
df -h / | tail -1
echo "Load Average: $(cat /proc/loadavg)"
echo "Active Connections: $(ss -tuln | grep LISTEN | wc -l)"
EOF
        
        sleep "$interval"
    done
}

# Function to show VPS dashboard
show_dashboard() {
    echo "🖥️ HOSTINGER VPS DASHBOARD"
    echo "=========================="
    echo "Host: $VPS_HOSTNAME"
    echo "IP: $VPS_IP"
    echo ""
    
    # Test API connectivity
    test_api
    echo ""
    
    # Get VPS list
    print_status "Loading VPS information..."
    get_vps_list
    echo ""
    
    # Combined stats (API + SSH)
    print_status "Getting combined stats..."
    echo ""
    echo "📡 API-based monitoring:"
    echo "========================"
    # API stats would go here
    
    echo ""
    echo "🔗 SSH-based monitoring:"
    echo "========================"
    ssh -o ConnectTimeout=10 "$VPS_USER@$VPS_HOSTNAME" << 'EOF'
echo "System: $(uname -o) $(cat /etc/os-release | grep PRETTY_NAME | cut -d'"' -f2)"
echo "Uptime: $(uptime | cut -d',' -f1 | cut -d' ' -f4-)"
echo "Load: $(cat /proc/loadavg | cut -d' ' -f1-3)"
echo ""
echo "💾 Memory:"
free -h | grep Mem
echo ""
echo "💿 Disk:"
df -h / | tail -1
echo ""
echo "🔥 Top Processes:"
ps aux --sort=-%mem | head -5 | tail -4
echo ""
echo "🌐 Network:"
ss -tuln | grep LISTEN | wc -l | xargs echo "Listening ports:"
echo ""
echo "🎯 Khidmaapp Readiness:"
AVAILABLE_RAM=$(free | grep Mem | awk '{printf "%.0f", ($7/1024/1024)}')
AVAILABLE_PERCENT=$(free | grep Mem | awk '{printf "%.0f", ($7/$2)*100}')
echo "Available RAM: ${AVAILABLE_RAM}GB (${AVAILABLE_PERCENT}%)"
if [ $AVAILABLE_PERCENT -gt 40 ]; then
    echo "✅ READY for dual-project deployment"
else
    echo "⚠️ Resource optimization recommended"
fi
EOF
}

# Main menu function
show_menu() {
    echo ""
    echo "🎯 HOSTINGER API MANAGEMENT MENU"
    echo "================================="
    echo "1. Test API connectivity"
    echo "2. Show VPS dashboard"
    echo "3. Get VPS list"
    echo "4. Get VPS details"
    echo "5. Get VPS statistics"
    echo "6. Monitor VPS (real-time)"
    echo "7. Get VPS backups"
    echo "8. Create VPS backup"
    echo "9. Restart VPS"
    echo "0. Exit"
    echo ""
    read -p "Select option [0-9]: " choice
    
    case $choice in
        1) test_api ;;
        2) show_dashboard ;;
        3) get_vps_list ;;
        4) 
            read -p "Enter VPS ID: " vps_id
            get_vps_details "$vps_id"
            ;;
        5)
            read -p "Enter VPS ID: " vps_id
            get_vps_stats "$vps_id"
            ;;
        6)
            read -p "Enter VPS ID: " vps_id
            read -p "Enter monitoring interval (seconds, default 60): " interval
            monitor_vps "$vps_id" "${interval:-60}"
            ;;
        7)
            read -p "Enter VPS ID: " vps_id
            get_vps_backups "$vps_id"
            ;;
        8)
            read -p "Enter VPS ID: " vps_id
            read -p "Enter backup name (optional): " backup_name
            create_vps_backup "$vps_id" "$backup_name"
            ;;
        9)
            read -p "Enter VPS ID: " vps_id
            restart_vps "$vps_id"
            ;;
        0) exit 0 ;;
        *) print_error "Invalid option" ;;
    esac
}

# Main execution
main() {
    if [ $# -eq 0 ]; then
        show_dashboard
        
        while true; do
            show_menu
        done
    else
        case $1 in
            "test") test_api ;;
            "dashboard") show_dashboard ;;
            "list") get_vps_list ;;
            "details") get_vps_details "$2" ;;
            "stats") get_vps_stats "$2" ;;
            "monitor") monitor_vps "$2" "$3" ;;
            "backups") get_vps_backups "$2" ;;
            "backup") create_vps_backup "$2" "$3" ;;
            "restart") restart_vps "$2" ;;
            *)
                echo "Usage: $0 [command] [options]"
                echo "Commands:"
                echo "  test          - Test API connectivity"
                echo "  dashboard     - Show VPS dashboard"
                echo "  list          - Get VPS list"
                echo "  details <id>  - Get VPS details"
                echo "  stats <id>    - Get VPS statistics"
                echo "  monitor <id>  - Monitor VPS resources"
                echo "  backups <id>  - Get VPS backups"
                echo "  backup <id>   - Create VPS backup"
                echo "  restart <id>  - Restart VPS"
                ;;
        esac
    fi
}

# Check dependencies
if ! command -v curl &> /dev/null; then
    print_error "curl is required but not installed"
    exit 1
fi

if ! command -v jq &> /dev/null; then
    print_warning "jq is not installed - JSON output will be raw"
fi

# Run main function
main "$@"
