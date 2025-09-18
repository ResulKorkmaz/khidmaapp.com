#!/bin/bash

# KhidmaApp Deployment Script
# This script sets up the entire application with one command

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Helper functions
print_header() {
    echo -e "\n${BLUE}================================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}================================================${NC}\n"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

# Check if running as root for VPS deployment
check_root() {
    if [ "$EUID" -eq 0 ]; then
        print_warning "Running as root. This is recommended for VPS deployment."
        return 0
    else
        print_info "Running as non-root user. This is fine for local development."
        return 1
    fi
}

# Check system requirements
check_requirements() {
    print_header "Checking System Requirements"
    
    # Check PHP
    if command -v php &> /dev/null; then
        PHP_VERSION=$(php -v | head -n1 | cut -d" " -f2 | cut -d"." -f1,2)
        print_success "PHP $PHP_VERSION found"
        if [[ $(echo "$PHP_VERSION >= 8.2" | bc -l) -eq 1 ]]; then
            print_success "PHP version is compatible"
        else
            print_error "PHP 8.2+ is required. Current version: $PHP_VERSION"
            exit 1
        fi
    else
        print_error "PHP is not installed"
        exit 1
    fi
    
    # Check Composer
    if command -v composer &> /dev/null; then
        COMPOSER_VERSION=$(composer --version | head -n1 | cut -d" " -f3)
        print_success "Composer $COMPOSER_VERSION found"
    else
        print_error "Composer is not installed"
        exit 1
    fi
    
    # Check Node.js
    if command -v node &> /dev/null; then
        NODE_VERSION=$(node --version)
        print_success "Node.js $NODE_VERSION found"
    else
        print_error "Node.js is not installed"
        exit 1
    fi
    
    # Check npm
    if command -v npm &> /dev/null; then
        NPM_VERSION=$(npm --version)
        print_success "npm $NPM_VERSION found"
    else
        print_error "npm is not installed"
        exit 1
    fi
}

# Setup backend (Laravel)
setup_backend() {
    print_header "Setting Up Backend (Laravel API)"
    
    cd backend
    
    print_info "Installing PHP dependencies..."
    composer install --optimize-autoloader --no-dev
    
    print_info "Setting up environment..."
    if [ ! -f .env ]; then
        cp .env.example .env
        print_warning "Please edit .env file with your database credentials"
        read -p "Press Enter to continue after editing .env..."
    fi
    
    print_info "Generating application key..."
    php artisan key:generate --force
    
    print_info "Running database migrations..."
    php artisan migrate --force
    
    print_info "Seeding database with demo data..."
    php artisan db:seed --force
    
    print_info "Optimizing Laravel..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    print_info "Setting up storage symlink..."
    php artisan storage:link
    
    print_info "Setting permissions..."
    if check_root; then
        chown -R www-data:www-data storage bootstrap/cache
        chmod -R 775 storage bootstrap/cache
    else
        chmod -R 775 storage bootstrap/cache
    fi
    
    cd ..
    print_success "Backend setup completed!"
}

# Setup frontend (Next.js)
setup_frontend() {
    print_header "Setting Up Frontend (Next.js)"
    
    cd frontend
    
    print_info "Installing Node.js dependencies..."
    npm install
    
    print_info "Setting up environment..."
    if [ ! -f .env.local ]; then
        cp .env.example .env.local
        print_warning "Please edit .env.local file with your API URL"
        read -p "Press Enter to continue after editing .env.local..."
    fi
    
    print_info "Building for production..."
    npm run build
    
    cd ..
    print_success "Frontend setup completed!"
}

# Setup Nginx configuration
setup_nginx() {
    if ! check_root; then
        print_warning "Skipping Nginx setup (not running as root)"
        return
    fi
    
    print_header "Setting Up Nginx Configuration"
    
    # Backend Nginx config
    cat > /etc/nginx/sites-available/api.khidmaapp.com << 'EOF'
server {
    listen 80;
    server_name api.khidmaapp.com;
    root /var/www/khidmaapp/backend/public;
    index index.php;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # CORS headers
    add_header Access-Control-Allow-Origin "https://khidmaapp.com" always;
    add_header Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS" always;
    add_header Access-Control-Allow-Headers "Accept, Authorization, Cache-Control, Content-Type, DNT, If-Modified-Since, Keep-Alive, Origin, User-Agent, X-Requested-With" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Enable gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private auth;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/json;

    # Cache static files
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
EOF

    # Enable the site
    ln -sf /etc/nginx/sites-available/api.khidmaapp.com /etc/nginx/sites-enabled/
    
    # Test Nginx configuration
    nginx -t
    
    # Reload Nginx
    systemctl reload nginx
    
    print_success "Nginx configuration completed!"
}

# Setup SSL with Certbot
setup_ssl() {
    if ! check_root; then
        print_warning "Skipping SSL setup (not running as root)"
        return
    fi
    
    print_header "Setting Up SSL Certificates"
    
    if command -v certbot &> /dev/null; then
        print_info "Installing SSL certificate for api.khidmaapp.com..."
        certbot --nginx -d api.khidmaapp.com --non-interactive --agree-tos --email admin@khidmaapp.com
        print_success "SSL certificate installed!"
    else
        print_warning "Certbot not found. Please install SSL manually."
        print_info "Run: apt install certbot python3-certbot-nginx"
        print_info "Then: certbot --nginx -d api.khidmaapp.com"
    fi
}

# Setup cron jobs
setup_cron() {
    if ! check_root; then
        print_warning "Skipping cron setup (not running as root)"
        return
    fi
    
    print_header "Setting Up Cron Jobs"
    
    # Laravel scheduler
    (crontab -l 2>/dev/null; echo "* * * * * cd /var/www/khidmaapp/backend && php artisan schedule:run >> /dev/null 2>&1") | crontab -
    
    print_success "Cron jobs configured!"
}

# Setup monitoring
setup_monitoring() {
    print_header "Setting Up Monitoring"
    
    cd backend
    
    # Create health check endpoint test
    print_info "Testing API health check..."
    if curl -f http://localhost:8000/api/health &> /dev/null; then
        print_success "API health check is working!"
    else
        print_warning "API health check failed. Check your setup."
    fi
    
    cd ..
}

# Main deployment function
main() {
    print_header "KhidmaApp Deployment Script"
    print_info "This script will set up the complete KhidmaApp platform"
    print_info "Make sure you have edited the .env files before proceeding"
    
    read -p "Do you want to continue? (y/N): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        print_info "Deployment cancelled."
        exit 1
    fi
    
    # Run setup steps
    check_requirements
    setup_backend
    setup_frontend
    
    if check_root; then
        setup_nginx
        setup_ssl
        setup_cron
    fi
    
    setup_monitoring
    
    print_header "Deployment Completed Successfully! 🎉"
    print_success "Backend API: http://localhost:8000 (or https://api.khidmaapp.com)"
    print_success "Frontend: http://localhost:3000 (or https://khidmaapp.com)"
    print_success "Admin Panel: http://localhost:8000/admin"
    
    print_info "Next steps:"
    echo "1. Edit environment files if not done already"
    echo "2. Configure your domain DNS to point to this server"
    echo "3. Test the application thoroughly"
    echo "4. Set up backups and monitoring"
    
    print_info "For support, visit: https://github.com/khidmaapp"
}

# Run the deployment
main "$@"
