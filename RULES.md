# 📋 KhidmaApp - Proje Geliştirme Kuralları

**Versiyon:** 2.0  
**Son Güncelleme:** 22 Kasım 2025  
**Durum:** Production-Ready Guidelines

---

## 🎯 PROJE AMACI VE İŞ MODELİ

### Ana Amaç
**KhidmaApp.com**, Suudi Arabistan pazarına yönelik **B2B lead generation (müşteri talebi toplama ve satış) platformudur**.

### İş Akışı
```
1. Müşteri → Website'den hizmet talebi oluşturur
2. Admin → Talebi doğrular ve onaylar (verified)
3. Usta (Provider) → Lead paketi satın alır (Stripe)
4. Admin → Lead'i MANUEL olarak ustaya gönderir
5. Usta → Müşteri ile iletişime geçer
```

### Önemli Prensip
❗ **Lead gönderimi ASLA otomatik değildir!**  
Her lead, admin onayı ile manuel olarak gönderilir. Bu kalite kontrolü ve adil dağıtım sağlar.

---

## 🛠️ TEKNOLOJİ STACK

### Backend
```
- PHP: 8.1+ (minimum 7.4)
- Architecture: MVC (Framework-less, lightweight)
- Database: MySQL 8.0+ / MariaDB 10.6+
- ORM: Raw PDO (Prepared Statements)
- Package Manager: Composer 2.x
```

### Frontend
```
- CSS Framework: Tailwind CSS 3.4+
- JavaScript: Vanilla JS (ES6+)
- Build Tool: Tailwind CLI
- RTL Support: Native Tailwind + Custom Utilities
```

### Infrastructure
```
- Web Server: Apache 2.4+ (with mod_rewrite)
- PHP Extensions: PDO, mbstring, json, openssl, curl
- Cache: File-based (Redis recommended for production)
- Payment: Stripe (Test + Live modes)
```

---

## 🏗️ ARCHITECTURE PRINCIPLES

### 1. **MVC Pattern**
```
public/index.php          → Router (Entry point)
src/Controllers/          → Business logic & HTTP handling
src/Models/              → Database operations (Active Record pattern)
src/Views/               → HTML templates (PHP native)
src/Services/            → Reusable services (Notifications, Export, etc.)
```

### 2. **Single Responsibility**
- **Controller:** Max 500 satır (idealinde 300)
- **Model:** Sadece database operations
- **Service:** İş mantığını kapsüller
- **Helper:** Global utility fonksiyonlar

### 3. **Security First**
```php
✅ ALWAYS use PDO Prepared Statements
✅ ALWAYS sanitize user input
✅ ALWAYS validate CSRF tokens on POST
✅ ALWAYS use password_hash() / password_verify()
❌ NEVER trust user input
❌ NEVER hardcode secrets
❌ NEVER use mysql_* functions
```

---

## 🔒 GÜVENLİK KURALLARI

### Zorunlu Güvenlik Önlemleri

#### 1. Input Validation & Sanitization
```php
// ✅ DOĞRU
$name = sanitizeInput($_POST['name']);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

// ❌ YANLIŞ
$name = $_POST['name']; // Direkt kullanım
```

#### 2. SQL Injection Prevention
```php
// ✅ DOĞRU - Prepared Statements
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);

// ❌ YANLIŞ - String concatenation
$query = "SELECT * FROM users WHERE email = '$email'";
```

#### 3. XSS Prevention
```php
// ✅ DOĞRU
echo htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8');

// ❌ YANLIŞ
echo $userInput; // Raw output
```

#### 4. CSRF Protection
```php
// Her form'da zorunlu
<input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

// Controller'da kontrol
if (!verifyCsrfToken($_POST['csrf_token'])) {
    die('CSRF attack detected');
}
```

#### 5. Password Security
```php
// ✅ DOĞRU - Bcrypt (cost 10+)
$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
$valid = password_verify($password, $hash);

// ❌ YANLIŞ - MD5, SHA1, plain text
$hash = md5($password);
```

### Secret Management
```bash
# ✅ DOĞRU - .env file
DB_PASS=secret123
STRIPE_SECRET_KEY=sk_live_xxx

# ❌ YANLIŞ - Hardcoded
define('DB_PASS', 'secret123');
```

---

## 📁 DOSYA ve KLASÖR YAPISI

### Standart Dizin Yapısı
```
khidmaapp.com/
├── public/                    # Web root (Document root)
│   ├── index.php             # Router (Entry point)
│   ├── .htaccess             # Apache config
│   └── assets/
│       ├── css/              # Compiled CSS
│       ├── js/               # JavaScript files
│       └── images/           # Static images
│
├── src/
│   ├── config/
│   │   ├── config.php        # Main configuration
│   │   ├── helpers.php       # Global helper functions
│   │   └── stripe.php        # Stripe configuration
│   │
│   ├── Controllers/          # HTTP request handlers
│   │   ├── HomeController.php
│   │   ├── LeadController.php
│   │   ├── AdminController.php
│   │   └── ProviderController.php
│   │
│   ├── Models/               # Database models
│   │   ├── Lead.php
│   │   ├── Provider.php
│   │   └── Admin.php
│   │
│   ├── Services/             # Business logic services
│   │   ├── NotificationService.php
│   │   └── LeadExportService.php
│   │
│   └── Views/                # HTML templates
│       ├── layouts/          # Common layouts
│       ├── admin/            # Admin panel views
│       └── provider/         # Provider dashboard views
│
├── database/
│   ├── schema.sql            # Database schema
│   └── migration_*.sql       # Database migrations
│
├── tests/                    # Unit & Integration tests
│   ├── Unit/
│   └── Integration/
│
├── vendor/                   # Composer dependencies (gitignored)
├── node_modules/             # NPM dependencies (gitignored)
│
├── .env                      # Environment variables (gitignored)
├── .env.example              # Environment template
├── .gitignore                # Git ignore rules
├── composer.json             # PHP dependencies
├── package.json              # Node dependencies
├── tailwind.config.js        # Tailwind configuration
├── README.md                 # Project documentation
├── RULES.md                  # This file
└── TODO.md                   # Task tracking
```

---

## 📝 KODLAMA STANDARTLARI

### 1. PHP Coding Standards (PSR-12)

```php
<?php

/**
 * Class docblock
 * 
 * @package KhidmaApp
 * @author Your Name
 */
class ExampleController
{
    // Properties
    private $pdo;
    
    // Constructor
    public function __construct()
    {
        $this->pdo = getDatabase();
    }
    
    // Methods
    public function index(): void
    {
        // 4 spaces indentation
        if ($condition) {
            // Code here
        }
    }
    
    // Private methods
    private function helperMethod(): array
    {
        return [];
    }
}
```

### 2. Naming Conventions

```php
// Classes: PascalCase
class LeadController {}
class NotificationService {}

// Methods: camelCase
public function getUserById() {}
public function sendNotification() {}

// Variables: camelCase
$userId = 123;
$leadData = [];

// Constants: UPPER_SNAKE_CASE
define('MAX_UPLOAD_SIZE', 10);
const DEFAULT_TIMEOUT = 30;

// Database tables: snake_case
leads, service_providers, provider_purchases

// Database columns: snake_case
created_at, service_type, is_active
```

### 3. Comments & Documentation

```php
/**
 * Send notification to provider
 * 
 * @param int $providerId Provider ID
 * @param array $data Notification data
 * @return bool Success status
 * @throws Exception If provider not found
 */
public function sendNotification(int $providerId, array $data): bool
{
    // Single-line comments for simple explanations
    $provider = $this->getProvider($providerId);
    
    // Multi-line comments for complex logic
    /*
     * We need to check if provider has an active subscription
     * before sending the notification. If not, log the error
     * and return false.
     */
    if (!$provider['is_active']) {
        error_log("Provider {$providerId} is not active");
        return false;
    }
    
    return true;
}
```

---

## 🗄️ DATABASE KURALLAR

### 1. Table Design Rules

```sql
-- ✅ DOĞRU: snake_case, explicit types, indexes
CREATE TABLE service_providers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    service_type VARCHAR(50) NOT NULL,
    city VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_service_city (service_type, city),
    INDEX idx_email (email),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ❌ YANLIŞ: CamelCase, no indexes, no comments
CREATE TABLE ServiceProviders (
    ID int,
    Email varchar(255),
    ServiceType varchar(255)
);
```

### 2. Migration Rules

```sql
-- Migration file naming: migration_YYYY_MM_DD_description.sql
-- Example: migration_2025_11_22_add_deleted_at_to_leads.sql

-- Always include:
-- 1. Description comment
-- 2. Rollback instructions
-- 3. Data migration if needed

-- Add new column
ALTER TABLE leads 
ADD COLUMN deleted_at TIMESTAMP NULL 
COMMENT 'Soft delete timestamp';

-- Rollback:
-- ALTER TABLE leads DROP COLUMN deleted_at;
```

### 3. Query Optimization Rules

```php
// ✅ DOĞRU: Select only needed columns
$stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE status = ?");

// ❌ YANLIŞ: Select all columns
$stmt = $pdo->prepare("SELECT * FROM users WHERE status = ?");

// ✅ DOĞRU: Use indexes
CREATE INDEX idx_email ON users(email);

// ✅ DOĞRU: Use LIMIT for pagination
SELECT * FROM leads ORDER BY created_at DESC LIMIT 20 OFFSET 0;

// ❌ YANLIŞ: Load all records
SELECT * FROM leads ORDER BY created_at DESC;
```

---

## 🧪 TESTING RULES

### 1. Unit Test Structure

```php
// tests/Unit/LeadValidationTest.php
use PHPUnit\Framework\TestCase;

class LeadValidationTest extends TestCase
{
    public function testPhoneValidation()
    {
        $validator = new LeadValidator();
        
        // Test valid phone
        $result = $validator->validatePhone('0501234567');
        $this->assertTrue($result['valid']);
        
        // Test invalid phone
        $result = $validator->validatePhone('123');
        $this->assertFalse($result['valid']);
    }
}
```

### 2. Test Coverage Requirements

```
- Critical functions: 100% coverage
- Controllers: 80% coverage
- Models: 90% coverage
- Helpers: 80% coverage
- Overall: Minimum 70% coverage
```

### 3. Test Naming Convention

```php
// Format: test[MethodName][Scenario][ExpectedResult]
public function testValidatePhoneWithValidNumberReturnsTrue() {}
public function testValidatePhoneWithInvalidNumberReturnsFalse() {}
public function testCreateLeadWithMissingDataThrowsException() {}
```

---

## 🚀 DEPLOYMENT RULES

### 1. Pre-Deployment Checklist

```bash
# ✅ Before deploying to production:
□ Run all tests (PHPUnit)
□ Check for .env file (not .env.example)
□ Verify database migrations
□ Test Stripe payment flow
□ Check error logging
□ Verify security headers
□ Test RTL layout (Arabic)
□ Check mobile responsiveness
□ Verify HTTPS redirect
□ Test CSRF protection
□ Check rate limiting
□ Verify backup strategy
```

### 2. Environment-Specific Configuration

```bash
# Development (.env)
APP_ENV=local
APP_DEBUG=true
STRIPE_SECRET_KEY=sk_test_...

# Production (.env)
APP_ENV=production
APP_DEBUG=false
STRIPE_SECRET_KEY=sk_live_...
```

### 3. Backup Strategy

```bash
# Daily database backup
mysqldump -u user -p khidmaapp > backup_$(date +%Y%m%d).sql

# Weekly full backup (files + database)
tar -czf backup_full_$(date +%Y%m%d).tar.gz \
    /path/to/project \
    backup_$(date +%Y%m%d).sql
```

---

## 🔧 MAINTENANCE RULES

### 1. Code Review Checklist

```
Before merging any PR:
□ Code follows PSR-12 standards
□ All tests pass
□ No hardcoded secrets
□ Security vulnerabilities checked
□ Documentation updated
□ TODO comments addressed
□ Error handling implemented
□ Database queries optimized
□ No code duplication (DRY principle)
□ Comments are clear and helpful
```

### 2. Error Handling Standards

```php
// ✅ DOĞRU: Specific exceptions, logging
try {
    $result = $this->processPayment($data);
} catch (StripeException $e) {
    error_log("Stripe error: " . $e->getMessage());
    return $this->jsonError('Payment failed', 500);
} catch (Exception $e) {
    error_log("Unexpected error: " . $e->getMessage());
    return $this->jsonError('Internal error', 500);
}

// ❌ YANLIŞ: Generic catch, no logging
try {
    $result = $this->processPayment($data);
} catch (Exception $e) {
    return false;
}
```

### 3. Performance Optimization

```php
// ✅ DOĞRU: Cache static data
function getServiceTypes() {
    static $cache = null;
    if ($cache === null) {
        $cache = $pdo->query("SELECT * FROM services")->fetchAll();
    }
    return $cache;
}

// ✅ DOĞRU: Use indexes
CREATE INDEX idx_service_city ON leads(service_type, city);

// ✅ DOĞRU: Limit query results
SELECT * FROM leads LIMIT 100;

// ❌ YANLIŞ: N+1 query problem
foreach ($leads as $lead) {
    $provider = getProvider($lead['provider_id']); // Database hit per iteration
}
```

---

## 📚 BEST PRACTICES

### 1. Never Do This ❌

```php
// ❌ SQL Injection
$query = "SELECT * FROM users WHERE id = {$_GET['id']}";

// ❌ XSS Vulnerability
echo $_POST['comment'];

// ❌ Hardcoded Secrets
define('STRIPE_KEY', 'sk_live_xxxx');

// ❌ Plain Text Passwords
$password = $_POST['password'];
// Save to database directly

// ❌ No Error Handling
$result = file_get_contents($url);
// Use $result without checking

// ❌ Global Variables Abuse
global $db, $user, $config;
```

### 2. Always Do This ✅

```php
// ✅ Prepared Statements
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);

// ✅ Output Escaping
echo htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');

// ✅ Environment Variables
$stripeKey = env('STRIPE_SECRET_KEY');

// ✅ Password Hashing
$hash = password_hash($password, PASSWORD_BCRYPT);

// ✅ Error Handling
try {
    $result = file_get_contents($url);
} catch (Exception $e) {
    error_log($e->getMessage());
    return false;
}

// ✅ Dependency Injection
public function __construct(PDO $pdo) {
    $this->pdo = $pdo;
}
```

---

## 🐛 DEBUGGING RULES

### 1. Error Logging

```php
// Development
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Production
ini_set('display_errors', 0);
error_reporting(E_ALL);
error_log("Error: " . $e->getMessage());
```

### 2. Debug Tools

```php
// ✅ Use proper logging
error_log("Debug: User ID = {$userId}");

// ✅ Use var_dump for development only
if (APP_DEBUG) {
    var_dump($data);
}

// ❌ Never use in production
echo "<pre>"; print_r($data); echo "</pre>";
```

---

## 📖 DOCUMENTATION REQUIREMENTS

### 1. README.md Must Include:
- Project overview
- Installation instructions
- Configuration guide
- API documentation
- Deployment guide
- Troubleshooting

### 2. Code Comments:
- All public methods must have PHPDoc
- Complex logic needs inline comments
- TODO comments must include date and author

### 3. Database Documentation:
- Schema diagram
- Table relationships
- Index explanations
- Migration history

---

## 🎯 VERSION CONTROL

### 1. Git Commit Messages

```bash
# Format: <type>(<scope>): <subject>

# Types:
feat: New feature
fix: Bug fix
docs: Documentation
style: Formatting
refactor: Code restructuring
test: Adding tests
chore: Maintenance

# Examples:
feat(lead): Add phone validation
fix(payment): Handle Stripe timeout
docs(readme): Update installation guide
refactor(admin): Split large controller
```

### 2. Branch Strategy

```
main (production)
  └── develop (staging)
      ├── feature/search-functionality
      ├── feature/lead-quality-scoring
      ├── bugfix/payment-timeout
      └── hotfix/security-patch
```

---

## 📞 SUPPORT & RESOURCES

### Internal Documentation
- README.md - Project overview
- TODO.md - Task management
- PROJECT_ANALYSIS.md - Architecture analysis
- RULES.md - This document

### External Resources
- [PHP PSR Standards](https://www.php-fig.org/psr/)
- [Tailwind CSS Docs](https://tailwindcss.com/docs)
- [Stripe API Docs](https://stripe.com/docs/api)
- [MySQL Documentation](https://dev.mysql.com/doc/)

---

**Last Updated:** 22 Nov 2025  
**Maintainer:** KhidmaApp Dev Team  
**Version:** 2.0 (Production-Ready)

> "Code is read much more often than it is written. Write code that your future self will thank you for." 🚀


