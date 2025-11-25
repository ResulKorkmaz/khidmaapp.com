# 🚀 KhidmaApp.com - Production Deployment Checklist

**Version:** 1.0  
**Last Updated:** 22 November 2025

---

## ✅ PRE-DEPLOYMENT CHECKLIST

### 1. Code Quality & Testing ✅
- [ ] All unit tests pass (`vendor/bin/phpunit`)
- [ ] Code coverage > 70%
- [ ] No PHP syntax errors (`php -l`)
- [ ] No security vulnerabilities (`composer audit`)
- [ ] Code follows PSR-12 standards
- [ ] No duplicate code remaining
- [ ] All TODO comments addressed

### 2. Environment Configuration 🔐
- [ ] `.env` file created (NOT .env.example)
- [ ] All secrets moved from hardcoded values to `.env`
- [ ] Database credentials updated for production
- [ ] Stripe LIVE keys configured (not test keys)
- [ ] Webhook secrets configured
- [ ] `APP_ENV=production` set
- [ ] `APP_DEBUG=false` set

### 3. Security Hardening 🛡️
- [ ] `.gitignore` properly configured
- [ ] Security headers enabled (.htaccess)
- [ ] HTTPS redirect enabled
- [ ] CSRF protection tested
- [ ] SQL injection tests passed
- [ ] XSS protection verified
- [ ] File upload security (if applicable)
- [ ] Rate limiting configured
- [ ] Session security enabled
- [ ] Password hashing using bcrypt

### 4. Database 🗄️
- [ ] Production database created
- [ ] Database migrations applied
- [ ] Database indexes created
- [ ] Database backups configured
- [ ] Test data removed
- [ ] Admin user created
- [ ] Database character set: utf8mb4

### 5. Performance ⚡
- [ ] OPcache enabled
- [ ] Gzip compression enabled (.htaccess)
- [ ] Browser caching configured
- [ ] Static assets minified
- [ ] Images optimized
- [ ] Query optimization done
- [ ] Slow query log enabled

### 6. Monitoring & Logging 📊
- [ ] Error logging configured
- [ ] Log rotation setup
- [ ] Monitoring tool configured (optional: Sentry)
- [ ] Uptime monitoring enabled
- [ ] Database query logging (for optimization)

### 7. Stripe Payment ���
- [ ] Test mode thoroughly tested
- [ ] Live mode API keys configured
- [ ] Webhook endpoint registered
- [ ] Webhook secret configured
- [ ] Payment flow tested end-to-end
- [ ] Refund process documented
- [ ] Failed payment handling tested

### 8. Email & Notifications (Future) 📧
- [ ] SMTP credentials configured
- [ ] Email templates tested
- [ ] Notification service tested
- [ ] Error notification emails setup

### 9. Backup Strategy 💾
- [ ] Database backup script created
- [ ] Daily automated backups scheduled
- [ ] Backup retention policy defined (30 days recommended)
- [ ] Backup restore tested
- [ ] Offsite backup location configured

### 10. Documentation 📚
- [ ] README.md updated
- [ ] API documentation complete
- [ ] Deployment guide written
- [ ] Emergency procedures documented
- [ ] Admin panel guide created
- [ ] Provider guide created

---

## 🔧 DEPLOYMENT STEPS

### Step 1: Prepare Production Server
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.1+
sudo apt install php8.1 php8.1-fpm php8.1-mysql php8.1-mbstring \
    php8.1-xml php8.1-curl php8.1-zip -y

# Install MySQL
sudo apt install mysql-server -y

# Install Apache/Nginx
sudo apt install apache2 -y
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Step 2: Clone Repository
```bash
cd /var/www
git clone https://github.com/yourusername/khidmaapp.com.git
cd khidmaapp.com
```

### Step 3: Install Dependencies
```bash
# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies
npm install --production

# Build assets
npm run build
```

### Step 4: Configure Environment
```bash
# Copy environment file
cp .env.example .env

# Edit environment variables
nano .env

# Set correct permissions
chmod 600 .env
chown www-data:www-data .env
```

### Step 5: Setup Database
```bash
# Create database
mysql -u root -p << EOF
CREATE DATABASE khidmaapp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'khidmaapp_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON khidmaapp.* TO 'khidmaapp_user'@'localhost';
FLUSH PRIVILEGES;
EOF

# Import schema
mysql -u khidmaapp_user -p khidmaapp < database/schema.sql

# Run migrations
for file in database/migration_*.sql; do
    mysql -u khidmaapp_user -p khidmaapp < "$file"
done
```

### Step 6: Set Permissions
```bash
# Set directory permissions
sudo chown -R www-data:www-data /var/www/khidmaapp.com
sudo chmod -R 755 /var/www/khidmaapp.com

# Set specific permissions
sudo chmod -R 775 /var/www/khidmaapp.com/logs
sudo chmod -R 775 /var/www/khidmaapp.com/public/assets

# Secure sensitive files
sudo chmod 600 /var/www/khidmaapp.com/.env
sudo chmod 600 /var/www/khidmaapp.com/src/config/config.php
```

### Step 7: Configure Apache Virtual Host
```apache
# /etc/apache2/sites-available/khidmaapp.com.conf
<VirtualHost *:80>
    ServerName khidmaapp.com
    ServerAlias www.khidmaapp.com
    DocumentRoot /var/www/khidmaapp.com/public
    
    <Directory /var/www/khidmaapp.com/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/khidmaapp_error.log
    CustomLog ${APACHE_LOG_DIR}/khidmaapp_access.log combined
    
    # Redirect to HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>

<VirtualHost *:443>
    ServerName khidmaapp.com
    ServerAlias www.khidmaapp.com
    DocumentRoot /var/www/khidmaapp.com/public
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    SSLCertificateChainFile /path/to/chain.crt
    
    <Directory /var/www/khidmaapp.com/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/khidmaapp_ssl_error.log
    CustomLog ${APACHE_LOG_DIR}/khidmaapp_ssl_access.log combined
</VirtualHost>
```

### Step 8: Enable Site
```bash
sudo a2ensite khidmaapp.com.conf
sudo systemctl reload apache2
```

### Step 9: Setup SSL (Let's Encrypt)
```bash
sudo apt install certbot python3-certbot-apache -y
sudo certbot --apache -d khidmaapp.com -d www.khidmaapp.com
```

### Step 10: Configure Cron Jobs
```bash
# Edit crontab
sudo crontab -e

# Add these lines:
# Database backup (daily at 2 AM)
0 2 * * * /var/www/khidmaapp.com/scripts/backup_database.sh

# Clean expired CSRF tokens (hourly)
0 * * * * /usr/bin/php /var/www/khidmaapp.com/scripts/cleanup_sessions.php

# Clean old logs (daily)
0 3 * * * find /var/www/khidmaapp.com/logs -name "*.log.*" -mtime +30 -delete
```

---

## 🧪 POST-DEPLOYMENT TESTING

### Functional Tests
- [ ] Homepage loads correctly
- [ ] Lead submission works
- [ ] Admin login functional
- [ ] Provider registration works
- [ ] Lead package purchase works (Stripe)
- [ ] Webhook receives Stripe events
- [ ] Email notifications sent (if configured)
- [ ] Arabic/RTL layout displays correctly
- [ ] Mobile responsiveness verified

### Security Tests
- [ ] HTTPS redirect working
- [ ] Security headers present (check with securityheaders.com)
- [ ] CSRF protection working
- [ ] SQL injection attempts blocked
- [ ] XSS attempts blocked
- [ ] Directory browsing disabled
- [ ] Sensitive files not accessible (.env, config.php)

### Performance Tests
- [ ] Page load time < 3 seconds
- [ ] Time to First Byte (TTFB) < 600ms
- [ ] Images load quickly
- [ ] No JavaScript errors in console
- [ ] Database queries optimized (< 100ms)

---

## 🆘 ROLLBACK PROCEDURE

If something goes wrong:

```bash
# 1. Restore previous version
cd /var/www/khidmaapp.com
git checkout previous-stable-tag

# 2. Restore database backup
mysql -u khidmaapp_user -p khidmaapp < /backups/khidmaapp_backup_YYYYMMDD.sql

# 3. Clear cache
php artisan cache:clear  # If using Laravel
# Or
rm -rf /var/www/khidmaapp.com/cache/*

# 4. Restart services
sudo systemctl restart apache2
sudo systemctl restart mysql
```

---

## 📞 POST-DEPLOYMENT CONTACTS

**Technical Support:**
- Primary: [Your Name] - [email]
- Backup: [Backup Person] - [email]

**Service Providers:**
- Hosting: [Hosting Provider]
- Domain: [Domain Registrar]
- SSL: Let's Encrypt
- Payment: Stripe Support

---

## 📊 SUCCESS METRICS

After deployment, monitor these metrics:

- **Uptime:** > 99.9%
- **Page Load Time:** < 3 seconds
- **Error Rate:** < 0.1%
- **Payment Success Rate:** > 95%
- **Database Query Time:** < 100ms average

---

**Deployed By:** _______________  
**Deployment Date:** _______________  
**Sign-off:** _______________

---

> **Note:** Keep this checklist updated with each deployment. Document any issues encountered and solutions applied.



