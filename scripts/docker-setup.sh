#!/bin/bash
# Docker-based Development Environment Setup
# Replaces Homebrew PostgreSQL with Docker containers

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_status() { echo -e "${BLUE}[INFO]${NC} $1"; }
print_success() { echo -e "${GREEN}[SUCCESS]${NC} $1"; }
print_warning() { echo -e "${YELLOW}[WARNING]${NC} $1"; }
print_error() { echo -e "${RED}[ERROR]${NC} $1"; }

echo "🐳 KHIDMAAPP DOCKER DEVELOPMENT SETUP"
echo "====================================="

# Step 1: Check if Docker is running
print_status "Checking Docker status..."
if ! docker info >/dev/null 2>&1; then
    print_error "Docker is not running. Please start Docker Desktop first."
    exit 1
fi
print_success "Docker is running"

# Step 2: Stop existing Homebrew PostgreSQL (if running)
print_status "Stopping Homebrew PostgreSQL (if running)..."
if brew services list | grep postgresql@15 | grep started >/dev/null; then
    print_warning "Stopping Homebrew PostgreSQL..."
    brew services stop postgresql@15
    print_success "Homebrew PostgreSQL stopped"
else
    print_status "Homebrew PostgreSQL not running"
fi

# Step 3: Stop Laravel development server (if running)
print_status "Stopping Laravel development server (if running)..."
pkill -f "php artisan serve" 2>/dev/null || true
print_status "Laravel server stopped"

# Step 4: Create directories
print_status "Creating required directories..."
mkdir -p database/backups
mkdir -p database/init
mkdir -p storage/logs

# Step 5: Build and start Docker containers
print_status "Starting Docker containers..."
docker-compose down 2>/dev/null || true  # Stop if already running
docker-compose up -d

# Step 6: Wait for PostgreSQL to be ready
print_status "Waiting for PostgreSQL to be ready..."
for i in {1..30}; do
    if docker exec khidmaapp-postgres pg_isready -U khidmaapp_user -d khidmaapp >/dev/null 2>&1; then
        break
    fi
    echo -n "."
    sleep 2
done
echo ""

if ! docker exec khidmaapp-postgres pg_isready -U khidmaapp_user -d khidmaapp >/dev/null 2>&1; then
    print_error "PostgreSQL failed to start within 60 seconds"
    docker-compose logs postgres
    exit 1
fi

print_success "PostgreSQL is ready!"

# Step 7: Update Laravel .env for Docker
print_status "Updating Laravel .env for Docker..."
cd backend

# Backup current .env
cp .env .env.backup.$(date +%Y%m%d_%H%M%S)

# Update database configuration
sed -i.tmp 's/DB_CONNECTION=.*/DB_CONNECTION=pgsql/' .env
sed -i.tmp 's/DB_HOST=.*/DB_HOST=127.0.0.1/' .env
sed -i.tmp 's/DB_PORT=.*/DB_PORT=5432/' .env
sed -i.tmp 's/DB_DATABASE=.*/DB_DATABASE=khidmaapp/' .env
sed -i.tmp 's/DB_USERNAME=.*/DB_USERNAME=khidmaapp_user/' .env
sed -i.tmp 's/DB_PASSWORD=.*/DB_PASSWORD=khidmaapp_password/' .env

# Update Redis configuration
if ! grep -q "REDIS_HOST" .env; then
    echo "" >> .env
    echo "# Redis Configuration" >> .env
    echo "REDIS_HOST=127.0.0.1" >> .env
    echo "REDIS_PASSWORD=null" >> .env
    echo "REDIS_PORT=6379" >> .env
else
    sed -i.tmp 's/REDIS_HOST=.*/REDIS_HOST=127.0.0.1/' .env
    sed -i.tmp 's/REDIS_PORT=.*/REDIS_PORT=6379/' .env
fi

# Update MeiliSearch configuration
if ! grep -q "MEILISEARCH_HOST" .env; then
    echo "" >> .env
    echo "# MeiliSearch Configuration" >> .env
    echo "MEILISEARCH_HOST=http://127.0.0.1:7700" >> .env
    echo "MEILISEARCH_KEY=khidmaapp_master_key_change_in_production" >> .env
else
    sed -i.tmp 's|MEILISEARCH_HOST=.*|MEILISEARCH_HOST=http://127.0.0.1:7700|' .env
    sed -i.tmp 's/MEILISEARCH_KEY=.*/MEILISEARCH_KEY=khidmaapp_master_key_change_in_production/' .env
fi

# Update Mail configuration for Mailpit
sed -i.tmp 's/MAIL_HOST=.*/MAIL_HOST=127.0.0.1/' .env
sed -i.tmp 's/MAIL_PORT=.*/MAIL_PORT=1025/' .env

# Clean up temp files
rm -f .env.tmp

print_success "Laravel .env updated for Docker"

# Step 8: Wait a bit more and run migrations
print_status "Waiting for all services to be ready..."
sleep 5

# Step 9: Test database connection
print_status "Testing database connection..."
if php -r "require 'vendor/autoload.php'; \$app = require 'bootstrap/app.php'; \$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); echo 'DB Connection: ' . \Illuminate\Support\Facades\DB::connection()->getPdo()->getAttribute(\PDO::ATTR_SERVER_VERSION) . PHP_EOL;"; then
    print_success "Database connection successful"
else
    print_error "Database connection failed"
    exit 1
fi

# Step 10: Run migrations
print_status "Running Laravel migrations..."
php artisan migrate:reset --force 2>/dev/null || true  # Reset if exists
php artisan migrate --force

# Step 11: Run seeders
print_status "Running database seeders..."
php artisan db:seed --force

# Step 12: Start Laravel development server
print_status "Starting Laravel development server..."
nohup php artisan serve --host=localhost --port=8000 > ../storage/logs/laravel-server.log 2>&1 &
LARAVEL_PID=$!

# Wait a moment for server to start
sleep 3

# Test Laravel server
if curl -s http://localhost:8000 >/dev/null; then
    print_success "Laravel server started successfully"
else
    print_warning "Laravel server may not have started properly"
fi

cd ..

# Step 13: Display status
echo ""
echo "🎉 DOCKER SETUP COMPLETED!"
echo "=========================="
echo ""
echo "📊 Services Running:"
echo "  🗄️  PostgreSQL:      http://localhost:5432"
echo "  🎛️  pgAdmin:         http://localhost:5050"
echo "  📝 Redis:            http://localhost:6379"
echo "  🔍 Redis Commander: http://localhost:8081"
echo "  🔎 MeiliSearch:      http://localhost:7700"
echo "  📧 Mailpit:          http://localhost:8025"
echo "  🌐 Laravel API:      http://localhost:8000"
echo "  👨‍💻 Admin Panel:      http://localhost:8000/admin"
echo ""
echo "🔐 Credentials:"
echo "  📊 pgAdmin:    admin@khidmaapp.com / admin123"
echo "  👨‍💻 Admin Panel: admin@khidmaapp.com / password"
echo ""
echo "🐳 Docker Commands:"
echo "  Stop all:     docker-compose down"
echo "  Start all:    docker-compose up -d"  
echo "  View logs:    docker-compose logs"
echo "  Status:       docker-compose ps"
echo ""

# Final status check
print_status "Final status check..."
docker-compose ps

echo ""
print_success "Setup complete! All services are running."
print_status "Open http://localhost:5050 for pgAdmin GUI access"
