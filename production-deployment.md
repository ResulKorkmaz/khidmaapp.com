# 🚀 OnlineUsta Production Deployment Rehberi

## 📋 Deployment Checklist

### 1. 🗄️ Production Database Setup

#### Seçenek A: Vercel Postgres (Önerilen)
```bash
# Vercel dashboard'dan Postgres ekleyin
# Otomatik environment variables inject edilir
```

#### Seçenek B: Supabase (Alternatif)
```bash
# 1. Supabase.com'da proje oluşturun
# 2. Database URL'yi kopyalayın:
DATABASE_URL="postgresql://postgres:[password]@[host]:5432/postgres"
```

#### Seçenek C: Railway
```bash
# Railway.app'te PostgreSQL provision edin
DATABASE_URL="postgresql://[user]:[password]@[host]:[port]/[database]"
```

### 2. 🔐 Environment Variables (Vercel Dashboard)

#### Production Environment Variables
```env
# Database
DATABASE_URL="your_production_database_url"

# App URL
NEXT_PUBLIC_APP_URL="https://onlineusta.com.tr"

# NextAuth
NEXTAUTH_SECRET="your-super-secure-secret-32-chars+"
NEXTAUTH_URL="https://onlineusta.com.tr"

# Email (Production SMTP)
SMTP_HOST="smtp.gmail.com"
SMTP_PORT="587"
SMTP_USER="noreply@onlineusta.com.tr"
SMTP_PASS="your-app-password"

# Monitoring
SENTRY_DSN="https://your-sentry-project-dsn"

# Analytics
NEXT_PUBLIC_GA_ID="G-XXXXXXXXXX"
```

### 3. 🌐 Domain Setup

#### Custom Domain Bağlama
```bash
# Vercel Dashboard → Settings → Domains
# 1. Domain ekleyin: onlineusta.com.tr
# 2. DNS kayıtlarını güncelleyin:

# A Record
@ → 76.76.19.61

# CNAME Record  
www → cname.vercel-dns.com

# Alternatif: Cloudflare proxy
# CNAME Record
@ → online-usta-com-tr.vercel.app
```

### 4. 📊 Database Migration

#### Production Migration Script
```bash
# packages/database/scripts/production-migrate.sh
#!/bin/bash

echo "🔄 Production migration başlatılıyor..."

# Production database URL kontrolü
if [[ -z "$DATABASE_URL" ]]; then
  echo "❌ DATABASE_URL bulunamadı!"
  exit 1
fi

# Backup oluştur (önerilen)
echo "💾 Database backup alınıyor..."
pg_dump $DATABASE_URL > backup_$(date +%Y%m%d_%H%M%S).sql

# Migration çalıştır
echo "🔄 Migration çalıştırılıyor..."
npx prisma db push --accept-data-loss

# Seed data (sadece ilk deploy için)
echo "🌱 Seed data ekleniyor..."
npx tsx prisma/seed.ts

echo "✅ Migration tamamlandı!"
```

### 5. 🔒 Güvenlik Konfigürasyonu

#### Content Security Policy
```typescript
// next.config.js
const nextConfig = {
  // ... existing config
  
  async headers() {
    return [
      {
        source: '/(.*)',
        headers: [
          {
            key: 'X-Frame-Options',
            value: 'DENY',
          },
          {
            key: 'X-Content-Type-Options',
            value: 'nosniff',
          },
          {
            key: 'Referrer-Policy',
            value: 'origin-when-cross-origin',
          },
          {
            key: 'Content-Security-Policy',
            value: "default-src 'self'; script-src 'self' 'unsafe-eval' 'unsafe-inline' *.vercel-analytics.com *.google-analytics.com; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' fonts.gstatic.com;",
          },
        ],
      },
    ];
  },
};
```

### 6. 📈 Monitoring & Analytics

#### Sentry Error Tracking
```bash
# 1. Sentry.io'da proje oluşturun
pnpm add @sentry/nextjs

# 2. Konfigürasyon
# sentry.client.config.ts
import * as Sentry from '@sentry/nextjs';

Sentry.init({
  dsn: process.env.SENTRY_DSN,
  tracesSampleRate: 1.0,
});
```

#### Google Analytics
```typescript
// lib/gtag.ts
export const GA_TRACKING_ID = process.env.NEXT_PUBLIC_GA_ID;

export const pageview = (url: string) => {
  if (typeof window !== 'undefined') {
    window.gtag('config', GA_TRACKING_ID, {
      page_path: url,
    });
  }
};
```

### 7. 🚀 Deployment Automation

#### GitHub Actions CI/CD
```yaml
# .github/workflows/deploy.yml
name: Deploy to Production

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
      - name: Checkout
        uses: actions/checkout@v4
        
      - name: Setup Node.js
        uses: actions/setup-node@v4
        with:
          node-version: '20'
          
      - name: Install pnpm
        uses: pnpm/action-setup@v2
        with:
          version: 8
          
      - name: Install dependencies
        run: pnpm install
        
      - name: Run tests
        run: pnpm test
        
      - name: Build
        run: pnpm build:web
        
      - name: Deploy to Vercel
        uses: amondnet/vercel-action@v25
        with:
          vercel-token: ${{ secrets.VERCEL_TOKEN }}
          vercel-org-id: ${{ secrets.ORG_ID }}
          vercel-project-id: ${{ secrets.PROJECT_ID }}
          vercel-args: '--prod'
```

### 8. 🔄 Zero-Downtime Deployment

#### Database Migration Strategy
```bash
# 1. Staging ortamında test edin
# 2. Backup alın
# 3. Migration'ı çalıştırın
# 4. Rollback planını hazırlayın

# Rollback script
#!/bin/bash
echo "🔄 Rollback başlatılıyor..."
git revert HEAD
vercel --prod
pg_restore backup_latest.sql
```

### 9. 📊 Performance Optimization

#### Image Optimization
```typescript
// next.config.js
const nextConfig = {
  images: {
    domains: ['images.unsplash.com'],
    formats: ['image/avif', 'image/webp'],
  },
  
  // Compression
  compress: true,
  
  // Static exports for better caching
  output: 'standalone',
};
```

### 10. 🌟 Post-Deployment Checklist

#### Launch Day Tasks
```bash
✅ SSL certificate aktif
✅ Custom domain çalışıyor
✅ Database bağlantısı OK
✅ Email sending test edildi
✅ Error monitoring aktif
✅ Analytics tracking çalışıyor
✅ Site performance test (Lighthouse)
✅ SEO meta tags doğru
✅ Robots.txt ve sitemap.xml aktif
✅ GDPR/KVKK compliance kontrol
```

## 🎯 Deployment Komutu

```bash
# Final deployment
git add .
git commit -m "feat: Production ready deployment"
git push origin main

# Vercel otomatik deploy edecek
# Domain: https://onlineusta.com.tr
```

## 📞 Post-Launch Monitoring

```bash
# Health check endpoints
GET /api/health
GET /api/health/database

# Performance monitoring
- Vercel Analytics
- Google PageSpeed Insights
- Core Web Vitals

# Error tracking
- Sentry alerts
- Vercel Function logs
```

## 🚨 Emergency Procedures

```bash
# Instant rollback
vercel rollback

# Database rollback
pg_restore backup_YYYYMMDD_HHMMSS.sql

# Maintenance mode
# Vercel'de temporary redirect ekleyin
``` 