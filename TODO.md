# KhidmaApp.com - TODO List

## 🔄 Provider Dashboard - Lead Management

### ✅ Tamamlanan Özellikler
- [x] Lead teslimat sistemi (provider_lead_deliveries tablosu)
- [x] Admin panelinden lead gönderme (sistem veya WhatsApp)
- [x] Provider dashboard'unda teslim edilen lead'leri listeleme
- [x] Lead detay modal ile görüntüleme
- [x] "Tamamlandı" ve "Beklet" durumu butonları

### 🚧 Sonraki Versiyon İyileştirmeleri

#### 1. Lead İade Sistemi
**Öncelik: Yüksek**

**Açıklama:**
Provider'ların kendilerine teslim edilen lead'leri iade edebilme özelliği. Geçersiz, yanlış bilgi, müşteri ulaşılamıyor gibi durumlarda kullanılacak.

**Gereksinimler:**
- [ ] Database'e `lead_returns` tablosu ekle
  - `id`, `lead_id`, `provider_id`, `purchase_id`, `return_reason`, `return_notes`, `returned_at`, `admin_approved`, `refund_status`
- [ ] İade sebepleri enum:
  - Yanlış telefon numarası
  - Müşteriye ulaşılamıyor
  - Müşteri hizmeti iptal etti
  - Bilgiler eksik/yanlış
  - Hizmet türü eşleşmiyor
  - Diğer (not zorunlu)
- [ ] Provider dashboard'unda "İade Et" butonu (detay modal'da)
- [ ] İade formu:
  - Sebep seçimi (dropdown)
  - Not alanı (textarea, opsiyonel)
  - Onay checkbox: "İade sebebimin geçerli olduğunu onaylıyorum"
- [ ] Admin panelinde iade isteklerini görüntüleme sayfası
- [ ] Admin onay/red butonu
- [ ] İade onaylanınca:
  - Lead'i provider'dan geri al
  - `provider_purchases` tablosunda `used_leads--`, `remaining_leads++`
  - `provider_lead_deliveries` kaydını sil veya `status='returned'` ekle
  - Lead durumunu tekrar 'new' yap
  - İade eden provider'a bildirim gönder
- [ ] İade red edilince:
  - Provider'a sebep bildirimi
  - Lead provider'da kalır
- [ ] İstatistikler:
  - Provider başına iade oranı
  - En çok iade edilen sebep
  - İade onay/red oranları

**Notlar:**
- İade hakkı: Lead tesliminden sonra ilk 24 saat içinde
- İade limiti: Paket başına maksimum 1 lead iade edilebilir
- Kötüye kullanım önlemi: 3 red sonrası admin onayı gereksin
- İade reddedilen lead'ler için tekrar iade talebi açılamaz

**Tahmin:** 3-4 gün geliştirme

---

#### 2. Lead Durumu Takibi
**Öncelik: Orta**

- [ ] Provider dashboard'unda lead durumu güncelleme:
  - "Beklemede" → Müşteri ile görüşülecek
  - "Devam Ediyor" → İş başladı
  - "Tamamlandı" → İş bitti
  - "İptal" → Müşteri iptal etti
- [ ] Her durum değişikliğinde log kaydı
- [ ] Admin panelinde durum timeline gösterimi

**Tahmin:** 1-2 gün geliştirme

---

#### 3. Lead Değerlendirme Sistemi
**Öncelik: Düşük**

- [ ] Provider lead kalitesini değerlendirebilir (1-5 yıldız)
- [ ] Geri bildirim notu (opsiyonel)
- [ ] Admin panelinde lead kaynak kalitesi analizi
- [ ] Düşük puanlı kaynakları filtreleme

**Tahmin:** 1 gün geliştirme

---

#### 4. Otomatik Lead Dağıtımı
**Öncelik: Orta**

- [ ] Admin manuel gönderim yerine otomatik dağıtım
- [ ] Satın alma sırasına göre queue sistemi
- [ ] Yeni lead geldiğinde otomatik provider'a ata
- [ ] Email/SMS bildirimi

**Tahmin:** 2-3 gün geliştirme

---

## 📊 Lead Kalite & Dinamik Fiyatlandırma Sistemi
**Öncelik: Yüksek** | **Tahmin:** 3-4 gün

### Amaç
Lead kalitesine göre farklı fiyatlandırma yaparak:
- Provider'lara kaliteli lead'ler sunmak
- Geliri optimize etmek
- Lead kaynaklarını analiz etmek

### Lead Kalite Skoru (100 puan üzerinden)

#### 1. Bilgi Tamlığı (40 puan)
- [ ] WhatsApp numarası var mı? (+20 puan)
- [ ] Açıklama detaylı mı? (>50 karakter: +20 puan, >100 karakter: +30 puan)
- [ ] Bütçe bilgisi var mı? (+10 puan - gelecekte)

#### 2. Aciliyet Skoru (30 puan)
- [ ] Acil (urgent): +30 puan
- [ ] 24 saat içinde (within_24h): +20 puan
- [ ] Planlı (scheduled): +10 puan

#### 3. Şehir Değeri (20 puan)
- [ ] Tier 1 şehirler (Riyad, Cidde, Dammam): +20 puan
- [ ] Tier 2 şehirler (Mekke, Medine, Taif): +15 puan
- [ ] Diğer şehirler: +10 puan

#### 4. Hizmet Türü (10 puan)
- [ ] Yüksek talep hizmetler (klima, elektrik): +10 puan
- [ ] Orta talep (sıhhi tesisat, tadilat): +7 puan
- [ ] Diğer hizmetler: +5 puan

### Fiyat Kademeleri

```sql
-- leads_packages tablosuna yeni sütunlar ekle
ALTER TABLE leads_packages 
ADD COLUMN quality_tier ENUM('premium', 'standard', 'basic') DEFAULT 'standard',
ADD COLUMN min_quality_score INT DEFAULT 0 COMMENT 'Minimum kalite skoru';

-- Yeni paketler
INSERT INTO leads_packages (name_ar, name_tr, lead_count, price, quality_tier, min_quality_score) VALUES
('حزمة بريميوم - طلب واحد', '1 Premium Lead', 1, 100, 'premium', 80),
('حزمة بريميوم - 3 طلبات', '3 Premium Leads', 3, 270, 'premium', 80),
('حزمة عادية - طلب واحد', '1 Standard Lead', 1, 60, 'standard', 50),
('حزمة عادية - 3 طلبات', '3 Standard Leads', 3, 150, 'standard', 50),
('حزمة أساسية - 3 طلبات', '3 Basic Leads', 3, 90, 'basic', 0);
```

### Backend İmplementasyon

- [ ] `src/Services/LeadQualityService.php` oluştur
- [ ] `calculateQualityScore(array $lead): int` fonksiyonu
- [ ] Lead kaydedilirken otomatik skor hesapla
- [ ] `leads` tablosuna `quality_score` ve `quality_tier` sütunları ekle
- [ ] Admin panelinde kalite skoru gösterimi
- [ ] Provider dashboard'unda paket seçerken kalite tier'ı göster

### Admin Analitikleri

- [ ] Ortalama lead kalite skoru
- [ ] Kalite tier'ına göre dağılım (Premium: 20%, Standard: 50%, Basic: 30%)
- [ ] Şehre göre kalite analizi
- [ ] Hizmet türüne göre kalite analizi
- [ ] Trend grafikleri (kalite zamanla artıyor mu?)

### Provider Paketi Seçimi

- [ ] Provider'a paket seçerken kalite tier'ı açıkça göster:
  ```
  🏆 حزمة بريميوم (3 طلبات)
  - طلبات عاجلة ومفصلة
  - معلومات كاملة (واتساب + وصف)
  - مدن رئيسية
  السعر: 270 ريال (90 ريال/طلب)
  
  ⭐ حزمة عادية (3 طلبات)
  - طلبات جيدة
  - معلومات جيدة
  السعر: 150 ريال (50 ريال/طلب)
  
  💡 حزمة أساسية (3 طلبات)
  - طلبات عادية
  - معلومات أساسية
  السعر: 90 ريال (30 ريال/طلب)
  ```

### Otomatik Lead Dağıtımı (İleri Seviye)

- [ ] Premium lead'ler önce Premium paket alan provider'lara
- [ ] Kalite tier'ına göre sıralama
- [ ] Provider'ın aldığı paket tier'ına göre lead ataması

### A/B Testing

- [ ] Hangi form alanları lead kalitesini artırıyor?
- [ ] Acil işlerin dönüşüm oranı nedir?
- [ ] Detaylı açıklama conversion'a etkisi

---

## 📝 Diğer İyileştirmeler

### Provider Profili
- [ ] Provider fotoğraf yükleme
- [ ] İş örnekleri galerisi
- [ ] Müşteri yorumları ve puanları
- [ ] Sertifikalar/belgeler

### Raporlama
- [ ] Provider'a aylık performans raporu
- [ ] Kazanç özeti
- [ ] Lead dönüşüm oranları

### Bildirimler
- [ ] Push notifications (browser)
- [ ] WhatsApp bildirimleri
- [ ] Email özet raporları

---

**Son Güncelleme:** 15 Kasım 2025
**Versiyon:** 1.0.0-beta


## 🔄 Provider Dashboard - Lead Management

### ✅ Tamamlanan Özellikler
- [x] Lead teslimat sistemi (provider_lead_deliveries tablosu)
- [x] Admin panelinden lead gönderme (sistem veya WhatsApp)
- [x] Provider dashboard'unda teslim edilen lead'leri listeleme
- [x] Lead detay modal ile görüntüleme
- [x] "Tamamlandı" ve "Beklet" durumu butonları

### 🚧 Sonraki Versiyon İyileştirmeleri

#### 1. Lead İade Sistemi
**Öncelik: Yüksek**

**Açıklama:**
Provider'ların kendilerine teslim edilen lead'leri iade edebilme özelliği. Geçersiz, yanlış bilgi, müşteri ulaşılamıyor gibi durumlarda kullanılacak.

**Gereksinimler:**
- [ ] Database'e `lead_returns` tablosu ekle
  - `id`, `lead_id`, `provider_id`, `purchase_id`, `return_reason`, `return_notes`, `returned_at`, `admin_approved`, `refund_status`
- [ ] İade sebepleri enum:
  - Yanlış telefon numarası
  - Müşteriye ulaşılamıyor
  - Müşteri hizmeti iptal etti
  - Bilgiler eksik/yanlış
  - Hizmet türü eşleşmiyor
  - Diğer (not zorunlu)
- [ ] Provider dashboard'unda "İade Et" butonu (detay modal'da)
- [ ] İade formu:
  - Sebep seçimi (dropdown)
  - Not alanı (textarea, opsiyonel)
  - Onay checkbox: "İade sebebimin geçerli olduğunu onaylıyorum"
- [ ] Admin panelinde iade isteklerini görüntüleme sayfası
- [ ] Admin onay/red butonu
- [ ] İade onaylanınca:
  - Lead'i provider'dan geri al
  - `provider_purchases` tablosunda `used_leads--`, `remaining_leads++`
  - `provider_lead_deliveries` kaydını sil veya `status='returned'` ekle
  - Lead durumunu tekrar 'new' yap
  - İade eden provider'a bildirim gönder
- [ ] İade red edilince:
  - Provider'a sebep bildirimi
  - Lead provider'da kalır
- [ ] İstatistikler:
  - Provider başına iade oranı
  - En çok iade edilen sebep
  - İade onay/red oranları

**Notlar:**
- İade hakkı: Lead tesliminden sonra ilk 24 saat içinde
- İade limiti: Paket başına maksimum 1 lead iade edilebilir
- Kötüye kullanım önlemi: 3 red sonrası admin onayı gereksin
- İade reddedilen lead'ler için tekrar iade talebi açılamaz

**Tahmin:** 3-4 gün geliştirme

---

#### 2. Lead Durumu Takibi
**Öncelik: Orta**

- [ ] Provider dashboard'unda lead durumu güncelleme:
  - "Beklemede" → Müşteri ile görüşülecek
  - "Devam Ediyor" → İş başladı
  - "Tamamlandı" → İş bitti
  - "İptal" → Müşteri iptal etti
- [ ] Her durum değişikliğinde log kaydı
- [ ] Admin panelinde durum timeline gösterimi

**Tahmin:** 1-2 gün geliştirme

---

#### 3. Lead Değerlendirme Sistemi
**Öncelik: Düşük**

- [ ] Provider lead kalitesini değerlendirebilir (1-5 yıldız)
- [ ] Geri bildirim notu (opsiyonel)
- [ ] Admin panelinde lead kaynak kalitesi analizi
- [ ] Düşük puanlı kaynakları filtreleme

**Tahmin:** 1 gün geliştirme

---

#### 4. Otomatik Lead Dağıtımı
**Öncelik: Orta**

- [ ] Admin manuel gönderim yerine otomatik dağıtım
- [ ] Satın alma sırasına göre queue sistemi
- [ ] Yeni lead geldiğinde otomatik provider'a ata
- [ ] Email/SMS bildirimi

**Tahmin:** 2-3 gün geliştirme

---

## 📊 Lead Kalite & Dinamik Fiyatlandırma Sistemi
**Öncelik: Yüksek** | **Tahmin:** 3-4 gün

### Amaç
Lead kalitesine göre farklı fiyatlandırma yaparak:
- Provider'lara kaliteli lead'ler sunmak
- Geliri optimize etmek
- Lead kaynaklarını analiz etmek

### Lead Kalite Skoru (100 puan üzerinden)

#### 1. Bilgi Tamlığı (40 puan)
- [ ] WhatsApp numarası var mı? (+20 puan)
- [ ] Açıklama detaylı mı? (>50 karakter: +20 puan, >100 karakter: +30 puan)
- [ ] Bütçe bilgisi var mı? (+10 puan - gelecekte)

#### 2. Aciliyet Skoru (30 puan)
- [ ] Acil (urgent): +30 puan
- [ ] 24 saat içinde (within_24h): +20 puan
- [ ] Planlı (scheduled): +10 puan

#### 3. Şehir Değeri (20 puan)
- [ ] Tier 1 şehirler (Riyad, Cidde, Dammam): +20 puan
- [ ] Tier 2 şehirler (Mekke, Medine, Taif): +15 puan
- [ ] Diğer şehirler: +10 puan

#### 4. Hizmet Türü (10 puan)
- [ ] Yüksek talep hizmetler (klima, elektrik): +10 puan
- [ ] Orta talep (sıhhi tesisat, tadilat): +7 puan
- [ ] Diğer hizmetler: +5 puan

### Fiyat Kademeleri

```sql
-- leads_packages tablosuna yeni sütunlar ekle
ALTER TABLE leads_packages 
ADD COLUMN quality_tier ENUM('premium', 'standard', 'basic') DEFAULT 'standard',
ADD COLUMN min_quality_score INT DEFAULT 0 COMMENT 'Minimum kalite skoru';

-- Yeni paketler
INSERT INTO leads_packages (name_ar, name_tr, lead_count, price, quality_tier, min_quality_score) VALUES
('حزمة بريميوم - طلب واحد', '1 Premium Lead', 1, 100, 'premium', 80),
('حزمة بريميوم - 3 طلبات', '3 Premium Leads', 3, 270, 'premium', 80),
('حزمة عادية - طلب واحد', '1 Standard Lead', 1, 60, 'standard', 50),
('حزمة عادية - 3 طلبات', '3 Standard Leads', 3, 150, 'standard', 50),
('حزمة أساسية - 3 طلبات', '3 Basic Leads', 3, 90, 'basic', 0);
```

### Backend İmplementasyon

- [ ] `src/Services/LeadQualityService.php` oluştur
- [ ] `calculateQualityScore(array $lead): int` fonksiyonu
- [ ] Lead kaydedilirken otomatik skor hesapla
- [ ] `leads` tablosuna `quality_score` ve `quality_tier` sütunları ekle
- [ ] Admin panelinde kalite skoru gösterimi
- [ ] Provider dashboard'unda paket seçerken kalite tier'ı göster

### Admin Analitikleri

- [ ] Ortalama lead kalite skoru
- [ ] Kalite tier'ına göre dağılım (Premium: 20%, Standard: 50%, Basic: 30%)
- [ ] Şehre göre kalite analizi
- [ ] Hizmet türüne göre kalite analizi
- [ ] Trend grafikleri (kalite zamanla artıyor mu?)

### Provider Paketi Seçimi

- [ ] Provider'a paket seçerken kalite tier'ı açıkça göster:
  ```
  🏆 حزمة بريميوم (3 طلبات)
  - طلبات عاجلة ومفصلة
  - معلومات كاملة (واتساب + وصف)
  - مدن رئيسية
  السعر: 270 ريال (90 ريال/طلب)
  
  ⭐ حزمة عادية (3 طلبات)
  - طلبات جيدة
  - معلومات جيدة
  السعر: 150 ريال (50 ريال/طلب)
  
  💡 حزمة أساسية (3 طلبات)
  - طلبات عادية
  - معلومات أساسية
  السعر: 90 ريال (30 ريال/طلب)
  ```

### Otomatik Lead Dağıtımı (İleri Seviye)

- [ ] Premium lead'ler önce Premium paket alan provider'lara
- [ ] Kalite tier'ına göre sıralama
- [ ] Provider'ın aldığı paket tier'ına göre lead ataması

### A/B Testing

- [ ] Hangi form alanları lead kalitesini artırıyor?
- [ ] Acil işlerin dönüşüm oranı nedir?
- [ ] Detaylı açıklama conversion'a etkisi

---

## 📝 Diğer İyileştirmeler

### Provider Profili
- [ ] Provider fotoğraf yükleme
- [ ] İş örnekleri galerisi
- [ ] Müşteri yorumları ve puanları
- [ ] Sertifikalar/belgeler

### Raporlama
- [ ] Provider'a aylık performans raporu
- [ ] Kazanç özeti
- [ ] Lead dönüşüm oranları

### Bildirimler
- [ ] Push notifications (browser)
- [ ] WhatsApp bildirimleri
- [ ] Email özet raporları

---

**Son Güncelleme:** 15 Kasım 2025
**Versiyon:** 1.0.0-beta



