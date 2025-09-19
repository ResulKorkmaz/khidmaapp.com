# 📋 KhidmaApp - Proje Geliştirme Kuralları

Bu dokümantasyon, KhidmaApp projesinin tutarlı ve profesyonel gelişimini sağlamak için uyulması gereken kuralları içerir.

---

## 🌍 **DİL KURALLARI**

### **Frontend (Müşteri Arayüzü)**
- **Birincil Dil**: Arapça (ar) 🇸🇦
- **İkincil Dil**: İngilizce (en) 🇺🇸
- **Varsayılan**: Arapça (ar)
- **URL Yapısı**: `/ar/...` ve `/en/...`
- **RTL Desteği**: Arapça için zorunlu
- **Font**: Noto Sans Arabic (Arapça), Inter (İngilizce)

### **Backend (Admin Panel)**
- **Admin Arayüzü**: Türkçe (tr) 🇹🇷
- **API Dökümanları**: Türkçe (ana) + İngilizce (teknik)
- **Kod Yorumları**: Türkçe (açıklayıcı ve net)
- **Database**: İngilizce field adları (standart)
- **Log Dosyaları**: Türkçe (hata mesajları anlaşılır olsun)

### **Kod ve Dökümanlar**
- **Değişken Adları**: İngilizce, camelCase (standart)
- **Fonksiyon Adları**: İngilizce, açıklayıcı (standart)
- **Kod Yorumları**: Türkçe (anlayabilmek için)
- **Dökümanlar**: Türkçe (README, API docs, guides)
- **Commit Mesajları**: İngilizce (Git standart)
- **API Endpoints**: İngilizce (REST standart)

### **Türkçe Kod Yorumu Kuralları**
```php
// ✅ DOĞRU - Açıklayıcı ve net
// Kullanıcı authentication durumunu kontrol eder
if ($user->isAuthenticated()) {
    // Aktif kullanıcıları getir ve cache'e kaydet
    $activeUsers = $this->userService->getActiveUsers();
}

// ✅ DOĞRU - İş mantığını açıklar  
/**
 * Ödeme işlemini başlatır ve sonucunu döndürür
 * 
 * @param PaymentRequest $request Ödeme bilgileri
 * @return PaymentResponse İşlem sonucu (başarılı/hatalı)
 */

// ❌ YANLIŞ - Gereksiz yorumlar
$name = "Ahmet"; // name değişkenine Ahmet atanıyor
$i++; // i değerini 1 artır
```

---

## 🎨 **RENK PALETİ KURALLARI**

### **Frontend Renkleri**
```css
Primary: Yeşil (#006C35)     → Ana butonlar, CTA, branding
Gold: Altın (#D4AF37)        → Vurgular, premium özellikler
Navy: Lacivert (#1B263B)     → Başlıklar, navigasyon
Neutral: Gri tonları         → Arka plan, metinler
```

### **Backend Renkleri**
```css
Primary: Buz Mavisi (#14b8a6)  → Admin butonları, aktif durumlar
Gold: Altın (#D4AF37)          → Vurgu borderları, ikonlar  
Navy: Lacivert (#1B263B)       → Sidebar, başlıklar
```

### **Renk Kullanım Kuralları**
- ❌ **YASAK**: Kırmızı + Yeşil birlikte (traffic light effect)
- ❌ **YASAK**: Çok fazla renk karışımı (max 3 ana renk)
- ✅ **ZORUNLU**: WCAG AA uyumlu kontrast oranları
- ✅ **ZORUNLU**: Color-blind friendly kombinasyonlar

---

## 💻 **TEKNOLOJİ STACK KURALLARI**

### **Frontend**
```typescript
// ZORUNLU Stack
Framework: Next.js 15+ (App Router)
Language: TypeScript 5+
Styling: Tailwind CSS 3+
UI Library: shadcn/ui
i18n: next-intl
Icons: Heroicons / Lucide React

// YASAK Stack  
❌ Styled Components (Tailwind kullan)
❌ CSS Modules (Tailwind kullan)
❌ jQuery (Modern React kullan)
❌ Bootstrap (Tailwind kullan)
```

### **Backend**
```php
// ZORUNLU Stack
Framework: Laravel 11+
PHP Version: 8.3+
Database: PostgreSQL 15+
Admin Panel: FilamentPHP 3+
Authentication: Laravel Sanctum
API Format: REST + JSON

// YASAK Stack
❌ WordPress (Laravel kullan)
❌ CodeIgniter (Laravel kullan)  
❌ MySQL (PostgreSQL kullan)
❌ Session Auth (Sanctum kullan)
```

---

## 📂 **DOSYA VE KLASÖR KURALLARI**

### **Proje Yapısı**
```
khidmaapp.com/
├── frontend/           → Next.js uygulaması
├── backend/           → Laravel API
├── docs/              → Dökümanlar
├── RULES.md          → Bu dosya
├── README.md         → Proje tanıtımı
└── RENK_PALETI.md    → Renk rehberi
```

### **Frontend Klasör Yapısı**
```
frontend/src/
├── app/               → Next.js App Router
│   ├── [locale]/     → Dil rotaları
│   ├── globals.css   → Global stiller
│   └── layout.tsx    → Ana layout
├── components/        → Reusable bileşenler
│   ├── ui/           → shadcn/ui bileşenleri
│   ├── forms/        → Form bileşenleri
│   └── layout/       → Layout bileşenleri
├── lib/              → Utilities
├── hooks/            → Custom hooks
└── types/            → TypeScript tanımları
```

### **Backend Klasör Yapısı**
```
backend/
├── app/
│   ├── Http/Controllers/Api/V1/  → API controllers
│   ├── Http/Resources/           → API resources
│   ├── Http/Requests/           → Form requests
│   ├── Models/                  → Eloquent models
│   ├── Policies/               → Authorization
│   └── Filament/               → Admin panel
├── database/
│   ├── migrations/             → Database migrations
│   └── seeders/               → Test data
└── resources/
    ├── css/                   → Styles
    └── js/                    → JavaScript
```

---

## 🔗 **API KURALLARI**

### **URL Yapısı**
```bash
# DOĞRU Format
GET    /api/v1/services           → Servisleri listele
POST   /api/v1/services           → Yeni servis oluştur
GET    /api/v1/services/{id}      → Tek servis getir
PUT    /api/v1/services/{id}      → Servisi güncelle
DELETE /api/v1/services/{id}      → Servisi sil

# YANLIŞ Format ❌
GET /api/getServices              → Verb kullanma
GET /api/v1/service              → Singular kullanma
GET /api/services-list           → Dash kullanma
```

### **Response Format**
```json
// BAŞARILI Response
{
  "success": true,
  "message": "İşlem başarılı",
  "data": {
    "services": [...],
    "pagination": {...}
  },
  "meta": {
    "timestamp": "2024-01-01T00:00:00Z",
    "version": "v1"
  }
}

// HATA Response  
{
  "success": false,
  "message": "Hata mesajı",
  "errors": {
    "field": ["Validation error"]
  },
  "meta": {
    "timestamp": "2024-01-01T00:00:00Z",
    "error_code": "VALIDATION_ERROR"
  }
}
```

### **HTTP Status Codes**
```bash
200 OK          → Başarılı GET/PUT
201 Created     → Başarılı POST  
204 No Content  → Başarılı DELETE
400 Bad Request → Validation hatası
401 Unauthorized → Auth gerekli
403 Forbidden   → Yetki yok
404 Not Found   → Kaynak bulunamadı
422 Unprocessable → İş mantığı hatası
500 Server Error → Sistem hatası
```

---

## 🛡️ **GÜVENLİK KURALLARI**

### **Authentication**
- ✅ **ZORUNLU**: Laravel Sanctum token authentication
- ✅ **ZORUNLU**: Password hashing (bcrypt/argon2)
- ✅ **ZORUNLU**: Rate limiting (60 req/min)
- ✅ **ZORUNLU**: CSRF protection
- ❌ **YASAK**: Plain text passwords
- ❌ **YASAK**: Session-based auth for API

### **Validation**
- ✅ **ZORUNLU**: Server-side validation (Form Requests)
- ✅ **ZORUNLU**: Client-side validation (Zod)
- ✅ **ZORUNLU**: SQL Injection prevention (Eloquent ORM)
- ✅ **ZORUNLU**: XSS prevention (HTML sanitization)

### **Data Protection**
- ✅ **ZORUNLU**: HTTPS only (production)
- ✅ **ZORUNLU**: Sensitive data encryption
- ✅ **ZORUNLU**: Environment variables (.env)
- ❌ **YASAK**: Hard-coded credentials
- ❌ **YASAK**: API keys in frontend code

---

## 📱 **UI/UX KURALLARI**

### **Responsive Design**
```css
Mobile First    → 320px+ (Zorunlu)
Tablet         → 768px+ 
Desktop        → 1024px+
Large Desktop  → 1440px+
```

### **Accessibility (A11Y)**
- ✅ **ZORUNLU**: WCAG 2.1 AA uyumlu
- ✅ **ZORUNLU**: Keyboard navigation
- ✅ **ZORUNLU**: Screen reader support
- ✅ **ZORUNLU**: Alt text for images
- ✅ **ZORUNLU**: Color contrast 4.5:1+

### **Performance**
```bash
Lighthouse Score:
- Performance: 90+ ⚡
- Accessibility: 90+ ♿
- Best Practices: 90+ ✅
- SEO: 90+ 🔍

Core Web Vitals:
- LCP: <2.5s
- FID: <100ms  
- CLS: <0.1
```

### **RTL Support (Arapça)**
- ✅ **ZORUNLU**: `dir="rtl"` attribute
- ✅ **ZORUNLU**: RTL-aware CSS (Tailwind RTL)
- ✅ **ZORUNLU**: Icon direction reversal
- ✅ **ZORUNLU**: Text alignment reversal

---

## 🗄️ **DATABASE KURALLARI**

### **Naming Conventions**
```sql
-- DOĞRU
users, user_profiles, service_categories
created_at, updated_at, deleted_at
is_active, is_verified, has_permission

-- YANLIŞ ❌
User, userProfile, ServiceCat
createdat, UpdatedAt, deletedAt  
active, verified, permission
```

### **Migration Rules**
- ✅ **ZORUNLU**: UUID primary keys
- ✅ **ZORUNLU**: Timestamp fields (created_at, updated_at)
- ✅ **ZORUNLU**: Soft deletes where applicable
- ✅ **ZORUNLU**: Foreign key constraints
- ✅ **ZORUNLU**: Database indexes for queries
- ❌ **YASAK**: Auto-increment integer IDs
- ❌ **YASAK**: Nullable foreign keys without reason

### **Data Types**
```php
// DOĞRU
'id' => 'uuid'
'email' => 'string', 'unique'
'phone' => 'string', 'max:20'
'price' => 'decimal:8,2'
'is_active' => 'boolean'
'metadata' => 'json'

// YANLIŞ ❌
'id' => 'increments'
'email' => 'text'
'phone' => 'integer'  
'price' => 'string'
'active' => 'integer'
'metadata' => 'text'
```

---

## 🧪 **TEST KURALLARI**

### **Backend Testing**
```php
// ZORUNLU Test Types
Unit Tests      → Model logic, services
Feature Tests   → API endpoints, workflows  
Integration     → Database, external APIs
Policy Tests    → Authorization logic

// Minimum Coverage: 80%
```

### **Frontend Testing**
```typescript
// ZORUNLU Test Types
Component Tests → React component behavior
Integration     → API calls, user flows
E2E Tests      → Critical user journeys (Playwright)
Accessibility  → Screen reader, keyboard nav

// Minimum Coverage: 70%
```

---

## 📝 **CODE STYLE KURALLARI**

### **PHP (Backend)**
```php
<?php

/**
 * Servis işlemlerini yöneten controller sınıfı
 * Bu sınıf servis listesi, detay, oluşturma gibi işlemleri yönetir
 */
class ServiceController extends BaseController
{
    /**
     * Servis listesini sayfalı olarak getirir
     * 
     * @param ServiceIndexRequest $request Filtreleme ve sayfalama parametreleri
     * @return JsonResponse JSON formatında servis listesi
     */
    public function index(ServiceIndexRequest $request): JsonResponse
    {
        // Sayfa başına gösterilecek kayıt sayısını al (varsayılan: 15)
        $perPage = $request->input('per_page', 15);
        
        // Servisleri sayfalı olarak getir
        $services = $this->serviceRepository->paginate($perPage);
        
        // Başarılı response döndür
        return $this->success(
            data: ServiceResource::collection($services),
            message: 'Servisler başarıyla getirildi'
        );
    }
    
    /**
     * Yeni servis oluşturur
     */
    public function store(ServiceStoreRequest $request): JsonResponse
    {
        // Validate edilmiş verileri al
        $validatedData = $request->validated();
        
        // Yeni servis oluştur
        $service = $this->serviceRepository->create($validatedData);
        
        // Resource formatında döndür
        return $this->success(
            data: new ServiceResource($service),
            message: 'Servis başarıyla oluşturuldu',
            code: 201
        );
    }
}
```

### **TypeScript (Frontend)**
```typescript
import { useTranslations } from 'next-intl';

/**
 * Servis kartı bileşeni - Ana sayfada ve listeleme sayfalarında kullanılır
 */
interface ServiceCardProps {
  service: Service;           // Servis verisi
  onSelect: (id: string) => void; // Seçim callback fonksiyonu
  featured?: boolean;         // Öne çıkarılmış servis mi
}

/**
 * Servis bilgilerini kart formatında gösteren React bileşeni
 * RTL desteği ve responsive tasarıma sahip
 */
export const ServiceCard: React.FC<ServiceCardProps> = ({ 
  service, 
  onSelect,
  featured = false
}) => {
  // Çeviri fonksiyonunu al (Arapça/İngilizce)
  const { t } = useTranslations('services');
  
  /**
   * Kart tıklandığında servis seçimini handle eder
   */
  const handleCardClick = () => {
    onSelect(service.id);
  };
  
  return (
    <div 
      className={`
        bg-white rounded-lg shadow-md p-6 cursor-pointer
        hover:shadow-lg transition-shadow duration-200
        ${featured ? 'border-l-4 border-gold-500' : ''}
      `}
      onClick={handleCardClick}
    >
      {/* Servis başlığı */}
      <h3 className="text-lg font-semibold text-navy-800 mb-2">
        {service.title}
      </h3>
      
      {/* Servis açıklaması */}
      <p className="text-neutral-600 mb-4 line-clamp-2">
        {service.description}
      </p>
      
      {/* Fiyat ve kategori bilgisi */}
      <div className="flex justify-between items-center">
        <span className="text-primary-600 font-medium">
          {service.price} {t('currency')}
        </span>
        <span className="text-sm text-neutral-500">
          {service.category}
        </span>
      </div>
    </div>
  );
};
```

---

## 🚀 **DEPLOYMENT KURALLARI**

### **Environment Setup**
```bash
Development  → Local (localhost)
Staging      → Vercel (preview)
Production   → Vercel + VPS

# Environment Variables
Frontend: NEXT_PUBLIC_* only for public
Backend: Never expose sensitive data
```

### **Git Workflow**
```bash
main       → Production ready code
develop    → Integration branch  
feature/*  → New features
hotfix/*   → Emergency fixes
release/*  → Release preparation

# Commit Format
feat: add user authentication
fix: resolve payment validation bug
docs: update API documentation
style: fix code formatting
```

---

## 🔍 **SEO KURALLARI**

### **Meta Tags (Zorunlu)**
```html
<title>KhidmaApp - خدمات موثوقة في السعودية</title>
<meta name="description" content="..." />
<meta property="og:title" content="..." />
<meta property="og:image" content="..." />
<link rel="canonical" href="https://khidmaapp.com" />
<link rel="alternate" hreflang="ar" href="https://khidmaapp.com/ar" />
<link rel="alternate" hreflang="en" href="https://khidmaapp.com/en" />
```

### **URL Structure**
```bash
# Frontend URLs
/ar/                    → Ana sayfa (Arapça)
/en/                    → Homepage (English)
/ar/riyadh/cleaning     → Şehir + kategori
/ar/service/123-slug    → Servis detay
/ar/provider/456-slug   → Sağlayıcı profil

# API URLs  
/api/v1/services        → RESTful API
/api/health            → Health check
```

---

## ⚠️ **YASAK UYGULAMALAR**

### **Asla Yapılmaması Gerekenler**
- ❌ Production'da debug mode açık bırakma
- ❌ .env dosyasını Git'e commit etme
- ❌ Plain text şifre saklama
- ❌ SQL injection açığı bırakma
- ❌ Hardcoded API keys
- ❌ CORS policy'sini * yapma
- ❌ Unauthorized database direct access
- ❌ Mixed language user interfaces
- ❌ Accessibility ignore etme
- ❌ Mobile responsive yapmama

---

## 📊 **PERFORMANS HEDEFLERI**

### **Backend Performance**
```bash
API Response Time: <200ms (95th percentile)
Database Queries: <100ms average
Memory Usage: <512MB peak
CPU Usage: <70% average
Uptime: 99.9%
```

### **Frontend Performance**  
```bash
First Contentful Paint: <1.5s
Largest Contentful Paint: <2.5s  
Time to Interactive: <3s
Bundle Size: <1MB total
Image Optimization: WebP/AVIF
```

---

## 🎯 **PROJE HEDEFLERİ**

### **Kısa Vadeli (1-3 ay)**
- ✅ MVP platform hazır
- ✅ Admin panel tam fonksiyonel  
- ✅ Kullanıcı authentication
- 🔄 Payment integration
- 🔄 Mobile app (React Native)

### **Orta Vadeli (3-6 ay)**
- 📋 Real-time messaging
- 📋 Push notifications
- 📋 Advanced search & filters
- 📋 Review & rating system
- 📋 Multi-city expansion

### **Uzun Vadeli (6-12 ay)**
- 📋 AI-powered matching
- 📋 IoT integration
- 📋 Franchise system
- 📋 Gulf region expansion
- 📋 Enterprise solutions

---

## 🔄 **SÜREKLI İYİLEŞTİRME**

Bu rules dosyası canlı bir dokümandır ve proje ilerledikçe güncellenmelidir.

**Son Güncelleme**: 18 Eylül 2024
**Versiyon**: 1.1
**Sorumlu**: Development Team

---

## 🗄️ **DATABASE PLATFORM SEÇİMİ**

### PostgreSQL Hosting Platform Önerisi

**🏆 ÖNERİLEN: DigitalOcean Managed PostgreSQL**

#### Teknik Detaylar:
- **Region**: Frankfurt (Europe)
- **Latency**: 100-150ms to Saudi Arabia  
- **Plan**: Professional ($60/ay, 4GB RAM)
- **Features**: 
  - Automated daily backups
  - SSL/TLS encryption
  - Connection pooling
  - Built-in monitoring
  - Read replicas support

#### Connection String Format:
```
DATABASE_URL=postgresql://username:password@host:port/database?sslmode=require
```

#### Alternative Platforms:
1. **AWS RDS PostgreSQL** (Enterprise scale için)
   - Region: Middle East (Bahrain)
   - Latency: 50-100ms 
   - Cost: $85+/ay

2. **Supabase** (MVP/testing için)
   - Global CDN
   - Real-time features built-in
   - Free tier: 500MB
   - Cost: $25/ay Pro plan

#### Migration Path:
```
Phase 1: Supabase (Free) → Testing
Phase 2: DigitalOcean ($60/ay) → Production  
Phase 3: AWS RDS ($85+/ay) → Enterprise Scale
```

### 🖥️ **VPS PostgreSQL Kurulumu** (Önerilen)

**🏆 Önerilen VPS: Hostinger KVM 2**
- **CPU**: 2 vCPU (AMD EPYC)
- **RAM**: 8 GB 
- **Storage**: 100 GB NVMe SSD
- **Network**: 1 Gbps, 8 TB bandwidth
- **Backup**: Haftalık otomatik backup dahil
- **Maliyet**: ~$20-30/ay (database için $0 ek maliyet)

**Avantajlar:**
- **Maliyet**: $0/ay database (sadece VPS maliyeti)
- **Kontrol**: Tam konfigürasyon hakimiyeti
- **Performance**: Production-ready! 8GB RAM ile excellent
- **Güvenlik**: Kendi güvenlik kurallarınız
- **Ölçeklenebilirlik**: Manuel ama esnek

**Kurulum Rehberleri:**
- **Genel VPS**: `docs/VPS_POSTGRESQL_SETUP.md`
- **Hostinger KVM 2**: `docs/HOSTINGER_KVM2_POSTGRESQL_SETUP.md` (Optimize)

**Performance Tuning (Hostinger KVM 2 için optimize):**
```
8GB RAM: shared_buffers=2GB, work_mem=32MB, effective_cache_size=6GB
NVMe SSD: random_page_cost=1.1, effective_io_concurrency=300
AMD EPYC: max_connections=200, checkpoint_completion_target=0.9
```

### 🔑 **Hostinger API Entegrasyonu**

**API Management & Automation:**
- **API Key**: Güvenli konfigürasyon (`config/hostinger.conf`)
- **VPS Monitoring**: Real-time resource tracking
- **Automated Deployment**: Dual-project container setup
- **Backup Management**: API-based backup creation
- **Performance Analytics**: Combined API + SSH monitoring

**Management Scripts:**
- `scripts/hostinger-api.sh` - API management interface
- `scripts/deployment-manager.sh` - Automated deployment
- `scripts/ssh-setup.sh` - SSH connectivity setup
- `scripts/hostinger-commands.sh` - Quick VPS commands

**Security Features:**
- ✅ API key encryption and secure storage
- ✅ SSH key-based authentication
- ✅ Firewall rules via API
- ✅ IP whitelisting configuration
- ✅ Automated security updates

---

**Not**: Bu kurallar, projenin kaliteli ve tutarlı gelişimini sağlamak için hazırlanmıştır. Herhangi bir değişiklik tüm ekip üyeleri ile görüşülerek yapılmalıdır.
