# KhidmaApp.com – README.md

PHP + MySQL + Tailwind CSS ile geliştirilen,
Suudi Arabistan pazarına yönelik **lead satış / müşteri talebi platformu**.

- Müşteri tarafı: Hizmet arayan kullanıcılar (boya, tadilat, temizlik, klima, vb.)
- Usta / firma tarafı: **WhatsApp kanalı** ve site üzerinden toplanan hizmet verenler
- İş modeli: Toplanan her **doğrulanmış müşteri talebi (lead)** hizmet verenlere **lead başı** veya **paket** olarak satılır.

> Not: Bu dosya, özellikle **Cursor + yapay zeka ile geliştirme** yaparken projeyi doğru yönlendirmek için referans dokümandır.

> ⚠️ **ÖNEMLİ:** Lead gönderme sistemi detayları için `leads-gonderme.md` dosyasına bakın!

---

## 1. Amaç ve İş Modeli

**KhidmaApp.com'un ana amacı:**

1. Suudi Arabistan'da **bakım / tadilat / hizmet** arayan kullanıcıları toplamak  
2. Müşterilerin taleplerini **form veya WhatsApp** üzerinden almak  
3. Bu talepleri:
   - Doğrulamak (telefon/WhatsApp üzerinden)
   - Kategorize etmek (şehir, hizmet türü, bütçe, aciliyet)
4. Doğrulanmış lead'leri:
   - **WhatsApp kanalı** üzerinden ustalara göstermek (örnek/preview olarak)
   - Tam iletişim bilgisi ve detayları **lead satışı** veya **abonelik** ile vermek

Bu proje **Armut gibi pazar yeri değil**, **lead satış platformudur**.

---

## 2. Teknoloji Stack'i

- **Backend:** PHP (>= 8.x, framework'süz veya hafif MVC yapı)
- **Veritabanı:** MySQL / MariaDB
- **Frontend:** Tailwind CSS (arabic/RTL uyumlu)
- **View yapısı:** PHP template dosyaları (`views/` altında)
- **Sunucu:** Paylaşımlı hosting (ör: Hostinger) veya basit VPS

---

## 3. Ana Özellikler (MVP)

### 3.1. Müşteri (Lead) tarafı

- [x] Hizmet seçimi (boya, tadilat, temizlik, klima, vb.)
- [x] Şehir / bölge seçimi (Riyad, Cidde, Mekke, vb.)
- [x] Açıklama alanı (kısa iş tarifi)
- [x] Telefon numarası alanı (zorunlu)
- [x] Tercihen WhatsApp'a yönlendirme veya "biz sizi arayalım" akışı
- [x] Form gönderildiğinde `leads` tablosuna kayıt
- [x] Basit SPAM koruması (honeypot field)
- [x] "Teşekkürler" sayfası

### 3.2. Usta / Hizmet veren tarafı

- [x] Sitede "**Ustalarımız Arasına Katılın** / انضم إلى مزودي الخدمة" butonu
- [x] Bu butonun **WhatsApp kanalına yönlendirilmesi**
- [ ] İleride: Ustalara özel panel (e-posta / telefon doğrulamalı login)
- [ ] Paket ve lead geçmişi görüntüleme

### 3.3. Admin Panel (v1 basit taslak)

- [x] Giriş (tek admin hesabı, database'den)
- [x] Lead listesi (filtre: tarih, şehir, hizmet türü, lead durumu)
- [x] Lead durumu:
  - `new`
  - `verified`
  - `sold`
  - `invalid`
- [x] Lead detay sayfası
- [x] Pagination sistemi
- [x] Status güncelleme
- [ ] İleride: Hangi ustaya satıldı, kaç kez gösterildi gibi alanlar

---

## 4. Önerilen Dizin Yapısı

```bash
project-root/
├─ public/
│  ├─ index.php        # Ana giriş (router)
│  ├─ assets/
│  │  ├─ css/
│  │  │  └─ app.css    # Tailwind build çıktısı
│  │  ├─ js/
│  │  │  └─ app.js
│  │  └─ images/
│  └─ .htaccess        # URL yönlendirme (public index'e)
├─ src/
│  ├─ config/
│  │  └─ config.php    # DB bağlantısı, genel ayarlar
│  ├─ Controllers/
│  │  ├─ HomeController.php
│  │  ├─ LeadController.php
│  │  └─ AdminController.php
│  ├─ Models/
│  │  ├─ Lead.php
│  │  └─ Admin.php
│  └─ Views/
│     ├─ layouts/
│     │  ├─ header.php
│     │  ├─ footer.php
│     │  └─ base.php   # ortak layout
│     ├─ home.php
│     ├─ lead_form.php
│     ├─ thanks.php
│     └─ admin/
│        ├─ login.php
│        └─ leads.php
├─ database/
│  └─ schema.sql       # tablo tanımları
├─ tailwind.config.js
├─ package.json        # (sadece Tailwind build için)
├─ composer.json       # (gerekirse)
├─ .env.example
└─ README.md
```

## 5. Veritabanı Taslak Şeması

### 5.1. leads tablosu

```sql
CREATE TABLE leads (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_type VARCHAR(50) NOT NULL,       -- paint, renovation, cleaning, ac, etc.
    city VARCHAR(100) NOT NULL,              -- Riyadh, Jeddah, vb.
    description TEXT,
    phone VARCHAR(30) NOT NULL,
    whatsapp_phone VARCHAR(30) NULL,
    budget_min INT NULL,
    budget_max INT NULL,
    source VARCHAR(50) DEFAULT 'website',    -- website, whatsapp, form, etc.
    status ENUM('new', 'verified', 'sold', 'invalid') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 5.2. admins tablosu (basit)

```sql
CREATE TABLE admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 6. Kurulum (Local Geliştirme)

### 6.1. Gereksinimler

- PHP 8.x
- MySQL / MariaDB
- Node.js (Tailwind için)
- Composer (opsiyonel)

### 6.2. Adımlar

1. Depoyu klonla veya dosyaları klasöre kopyala.

2. `database/schema.sql` dosyasını MySQL üzerinde çalıştır.

3. `.env.example` dosyasını `.env` olarak kopyala ve düzenle:

```env
APP_ENV=local
APP_DEBUG=true

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=khidmaapp
DB_USER=root
DB_PASS=your_password

BASE_URL=http://localhost/khidmaapp/public
WHATSAPP_CHANNEL_URL=https://whatsapp.com/channel/0029VbCCqZoI1rcjIn9IWV2l
```

4. `src/config/config.php` içinde .env değerlerini okuyacak basit bir fonksiyon kullan.

5. Tailwind'i kur:

```bash
npm install
# veya
npm install tailwindcss postcss autoprefixer
npx tailwindcss init
```

6. Tailwind build script'i:

`package.json` içine:

```json
{
  "scripts": {
    "dev": "npx tailwindcss -i ./resources/css/app.css -o ./public/assets/css/app.css --watch",
    "build": "npx tailwindcss -i ./resources/css/app.css -o ./public/assets/css/app.css --minify"
  }
}
```

7. Local sunucu:

```bash
php -S localhost:8000 -t public
```

## 7. Tailwind ve Arayüz Kuralları

**Tasarım:**
- Minimal, profesyonel, sade.
- Arka plan genelde açık / soft tonlar.
- Tüm form ve butonlar mobil öncelikli (mobile-first) tasarlanacak.

**Dil / yön:**
- Müşteri görünen kısım Arapça + RTL odaklı olacak.
- Gerekirse `<html dir="rtl" lang="ar">` kullan.

**Bileşenler:**
- Formlar için tekrar kullanılabilir partial'lar (Views/partials/).
- Header/footer ayrı dosyalara bölünecek.

**Örnek Tailwind buton sınıfı:**

```html
<a href="<?= htmlspecialchars($whatsappChannelUrl) ?>"
   class="inline-flex items-center justify-center px-4 py-2 rounded-md text-sm font-semibold
          bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:ring-2
          focus:ring-offset-2 focus:ring-green-600">
    انضم إلى مزودي الخدمة
</a>
```

## 8. Routing Mantığı (Basit)

`public/index.php` içinde basit bir router:

```php
<?php

require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/Controllers/HomeController.php';
require_once __DIR__ . '/../src/Controllers/LeadController.php';
require_once __DIR__ . '/../src/Controllers/AdminController.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/public', '', $path); // ihtiyaca göre

switch ($path) {
    case '/':
    case '/home':
        (new HomeController())->index();
        break;

    case '/lead/submit':
        (new LeadController())->store();
        break;

    case '/admin':
        (new AdminController())->index();
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
```

## 9. Güvenlik ve Temel Kurallar

**Tüm input'larda:**
- `filter_input()` veya manuel `trim` + `htmlspecialchars` kullan.
- SQL için prepared statement'lar (`PDO::prepare`).

**Admin giriş:**
- Şifreler `password_hash`/`password_verify` ile saklanacak.
- Admin kullanıcı adı/şifresi ilk kurulumda manuel eklenebilir (`INSERT INTO admins...`).

## 10. Cursor & Yapay Zeka ile Çalışma Notları

Bu proje Cursor + yapay zeka ile geliştirilecekse:

**Dosya yapısına saygı:**
- `public/` sadece entry point ve statik dosyalar
- `src/Controllers`, `src/Models`, `src/Views` ayrımına sadık kal

**Değişiklik kapsamı:**
- Tek seferde küçük, net işleri yap (örn. sadece LeadController'a validasyon ekle).
- Büyük refactor işlemlerinde önce planı yorum satırında anlat, sonra uygula.

**Konfigürasyon:**
- `.env` dosyasına dokunma, sadece `.env.example` üzerinde öneri ver.

**Dil:**
- Kod yorumları İngilizce kısa ve net.
- Kullanıcıya gösterilen metinler Arapça, yönetim/README Türkçe.

## 11. Yol Haritası (Roadmap – Kısa)

- [x] Ana sayfa (Arapça, RTL, "Hizmet Talep Et" formu)
- [x] Lead oluşturma (form → DB)
- [x] Basit teşekkür sayfası
- [x] "Ustalarımız Arasına Katılın" butonu → WhatsApp kanal linki
- [x] Basit admin login
- [x] Admin'de lead listesi ve filtreleme
- [x] Lead durumu güncelleme (new → verified → sold)
- [x] Lead detay sayfası
- [x] Pagination
- [x] SPAM koruması (honeypot)
- [x] Model katmanı (Lead, Admin)
- [x] Notification infrastructure
- [x] Service detail sayfaları
- [ ] İleride: Ustalara özel panel, paket sistemi, ödeme entegrasyonu (Stripe, Paytabs vb.)

---

Bu README, KhidmaApp.com projesi için temel teknik çerçeveyi ve iş modelinin mantığını tanımlar.
Geliştirme sırasında bu yapıya sadık kalınırsa proje temiz, genişlemeye uygun ve yönetilebilir kalacaktır.

---

## 12. 🚨 LEAD GÖNDERME SİSTEMİ - KRİTİK KURALLAR

> **⚠️ ASLA UNUTMA! Detaylı dokümantasyon: `leads-gonderme.md`**

### **Özet Sistem Akışı:**

#### **1. Paket Satın Alındığında:**
- ✅ Admin paneline **BİLDİRİM** gelir
- ✅ Admin kontrol eder: Sistemde uygun lead var mı?
- ✅ Varsa → Admin **MANUEL** olarak 1 adet gönderir
- ❌ Kalan 2 lead otomatik GÖNDERİLMEZ!

#### **2. Kalan Lead'ler İçin:**
- ✅ Usta **"Lead İste"** butonuna MUTLAKA basmalı
- ✅ Admin panelinde **BİLDİRİM** gelir
- ✅ Admin kontrol eder ve **MANUEL** gönderir

#### **3. Sıralama Sistemi:**
- Aynı şehir + aynı hizmet türü
- İlk satın alan → İlk sırada
- Paket bitince → Sonraki sıraya

#### **4. Örnek Senaryo:**
```
Usta A: Bugün 3'lü paket aldı
  → Admin paneline bildirim geldi
  → Admin 1 lead MANUEL gönderdi
  → Usta "Lead İste" butonuna bastı → Admin 1 lead daha gönderdi
  → Usta "Lead İste" butonuna bastı → Admin 1 lead daha gönderdi
  → Paket tamamlandı (3/3)

Usta B: Aynı gün 3'lü paket aldı
  → Admin paneline bildirim geldi
  → Admin 1 lead MANUEL gönderdi
  → Henüz "Lead İste" butonuna basmadı (müsait değil)
  → Kalan 2 lead beklemede
```

### **Neden Bu Sistem?**
- ✅ **Admin kontrolü:** Her lead admin onayı ile gider
- ✅ **Müsaitlik kontrolü:** "Lead İste" = "Ben hazırım!"
- ✅ **Lead kalitesi:** Müsait usta = daha iyi hizmet
- ✅ **Adil dağıtım:** Sıra sistemi (admin takibi)
- ✅ **Spam önleme:** Otomatik bombardıman YOK
- ❌ **Otomatik gönderim YOK:** Her şey manuel

### **Gerekli UI/UX:**
1. **Provider Dashboard:**
   - "Lead İste" butonu (her pakette)
   - Kalan lead sayısı göstergesi
   - Loading + success animasyonu

2. **Admin Panel:**
   - Yeni paket bildirimleri
   - Lead istekleri tablosu
   - "Lead Gönder" butonu (MANUEL)
   - ❌ Otomatik kontrol sistemi YOK

**Detaylı dokümantasyon, kod örnekleri, tablo yapıları için:**
👉 **`leads-gonderme.md`** dosyasına bakın!

---




