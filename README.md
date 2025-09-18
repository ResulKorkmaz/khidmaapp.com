# KhidmaApp - خدمة أب

[![Deploy with Vercel](https://vercel.com/button)](https://vercel.com/new/clone?repository-url=https://github.com/khidmaapp/frontend)

## 🚀 Hızlı Başlangıç

Bu proje **hızlı, güvenli, mükemmel SEO** ve **kolay yönetilebilir** dinamik web sitesi olarak tasarlanmıştır.

### 📋 Gereksinimler

- **Backend**: PHP 8.2+, Composer, MySQL 8.0+
- **Frontend**: Node.js 18+, npm/yarn
- **Hosting**: Hostinger VPS + Vercel

### 🛠️ Kurulum

#### 1. Backend (Laravel API)

```bash
cd backend

# Bağımlılıkları yükle
composer install

# Environment dosyasını kopyala
cp .env.example .env

# Uygulama anahtarı oluştur
php artisan key:generate

# Veritabanını ayarla
php artisan migrate --seed

# Sunucuyu başlat
php artisan serve
```

#### 2. Frontend (Next.js)

```bash
cd frontend

# Bağımlılıkları yükle
npm install

# Environment dosyasını ayarla
cp .env.example .env.local

# Development sunucusu başlat
npm run dev

# Production build
npm run build
npm start
```

## 🏗️ Mimari

### Backend (Laravel 11 API)
- **🔐 Authentication**: Laravel Sanctum
- **📊 Database**: MySQL/PostgreSQL with UUID primary keys
- **📱 Admin Panel**: FilamentPHP (Türkçe)
- **🌍 Multi-language**: Arabic (ar), English (en)
- **📈 Performance**: Redis caching, Horizon queues
- **🔍 Search**: Basic Laravel Scout (ready for MeiliSearch)

### Frontend (Next.js 15)
- **⚡ Performance**: App Router, ISR, SSR
- **🌐 Multi-language**: next-intl (Arabic RTL + English LTR)
- **🎨 Styling**: Tailwind CSS with RTL support
- **📱 Responsive**: Mobile-first design
- **🔍 SEO**: Perfect Lighthouse scores, Schema.org

### Deployment
- **Frontend**: Vercel (khidmaapp.com)
- **Backend**: Hostinger VPS (api.khidmaapp.com)
- **CDN**: Cloudflare
- **Database**: Hostinger MySQL

## 📁 Proje Yapısı

```
khidmaapp.com/
├── backend/                    # Laravel 11 API
│   ├── app/
│   │   ├── Http/Controllers/Api/V1/
│   │   ├── Models/
│   │   ├── Filament/          # Admin panel
│   │   └── Resources/
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   └── routes/api.php
├── frontend/                   # Next.js 15
│   ├── src/
│   │   ├── app/[locale]/      # App Router with i18n
│   │   ├── components/        # Reusable components
│   │   ├── lib/              # API client, utilities
│   │   └── middleware.ts     # i18n middleware
│   ├── messages/             # Translation files
│   │   ├── ar.json          # Arabic
│   │   └── en.json          # English
│   ├── public/
│   └── tailwind.config.js   # With RTL support
└── docs/                    # Documentation
```

## 🌐 Özellikler

### ✅ Mevcut Özellikler
- [x] **Çok Dilli Destek**: Arapça (RTL) ve İngilizce
- [x] **API Backend**: Laravel 11 ile RESTful API
- [x] **Modern Frontend**: Next.js 15 App Router
- [x] **Admin Panel**: FilamentPHP (Türkçe)
- [x] **SEO Optimized**: Sitemap, Schema.org, hreflang
- [x] **Responsive Design**: Mobil-first yaklaşım
- [x] **Performance**: ISR, caching, optimization

### 🚧 Gelecek Özellikler
- [ ] **Authentication**: User login/register
- [ ] **Service Posting**: Create service requests
- [ ] **Bidding System**: Providers can bid on services
- [ ] **Messaging**: Real-time chat
- [ ] **Reviews**: Rating and review system
- [ ] **Payment Integration**: HyperPay, PayTabs
- [ ] **Mobile Apps**: React Native
- [ ] **Advanced Search**: MeiliSearch integration

## 🌍 Çok Dil Desteği

### Desteklenen Diller
- **العربية (ar)**: Ana dil, RTL desteği
- **English (en)**: İkincil dil, LTR

### URL Yapısı
```
https://khidmaapp.com/ar/          # Arabic homepage
https://khidmaapp.com/en/          # English homepage
https://khidmaapp.com/ar/riyadh/cleaning/  # Arabic category page
https://khidmaapp.com/en/jeddah/plumbing/  # English category page
```

## 🔧 Deployment

### Frontend (Vercel)
1. GitHub'a push yapın
2. Vercel'e bağlayın
3. Environment variables ayarlayın
4. Auto-deploy aktif

### Backend (Hostinger VPS)
```bash
# VPS'e bağlan
ssh root@your-server-ip

# Projeyi klonla
git clone https://github.com/khidmaapp/backend
cd backend

# Kurulum
composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Nginx yapılandırması
# /etc/nginx/sites-available/api.khidmaapp.com
```

## 📈 Performance

### Lighthouse Scores (Hedef)
- **Performance**: 95+
- **Accessibility**: 100
- **Best Practices**: 100
- **SEO**: 100

### Optimizasyonlar
- **ISR**: 5 dakika cache
- **Image Optimization**: Next.js Image + WebP
- **Code Splitting**: Automatic
- **Compression**: Gzip/Brotli
- **CDN**: Cloudflare

## 🛡️ Güvenlik

- **CORS**: Configured for frontend domain
- **Rate Limiting**: API endpoints protected
- **Input Validation**: Zod schemas
- **XSS Protection**: Built-in Next.js protection
- **CSRF**: Laravel Sanctum tokens
- **HTTPS**: Forced in production

## 📊 Monitoring

- **Frontend**: Vercel Analytics
- **Backend**: Laravel Telescope
- **Errors**: Sentry (optional)
- **Uptime**: UptimeRobot

## 🤝 Katkıda Bulunma

1. Fork yapın
2. Feature branch oluşturun (`git checkout -b feature/amazing-feature`)
3. Commit yapın (`git commit -m 'Add some amazing feature'`)
4. Push yapın (`git push origin feature/amazing-feature`)
5. Pull Request açın

## 📝 Lisans

Bu proje MIT lisansı altında lisanslanmıştır. Detaylar için [LICENSE](LICENSE) dosyasına bakın.

## 🆘 Destek

- **Email**: support@khidmaapp.com
- **Discord**: [KhidmaApp Community](https://discord.gg/khidmaapp)
- **Documentation**: [docs.khidmaapp.com](https://docs.khidmaapp.com)

## 🇸🇦 Made in Saudi Arabia

Bu proje Suudi Arabistan pazarı için özel olarak geliştirilmiştir.

---

**⭐ Eğer bu proje işinize yaradıysa, lütfen star verin!**