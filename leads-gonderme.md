# 📋 LEAD GÖNDERME SİSTEMİ - DETAYLI AÇIKLAMA

```
██████╗ ██╗██╗  ██╗██╗  ██╗ █████╗ ████████╗    ██╗
██╔══██╗██║██║ ██╔╝██║ ██╔╝██╔══██╗╚══██╔══╝    ██║
██║  ██║██║█████╔╝ █████╔╝ ███████║   ██║       ██║
██║  ██║██║██╔═██╗ ██╔═██╗ ██╔══██║   ██║       ╚═╝
██████╔╝██║██║  ██╗██║  ██╗██║  ██║   ██║       ██╗
╚═════╝ ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝       ╚═╝
```

## 🔴🔴🔴 ÇOK ÖNEMLİ - ASLA UNUTMA! 🔴🔴🔴

Bu döküman, lead'lerin ustalara nasıl gönderileceğini açıklar.

---

## 🚨🚨🚨 KRİTİK KURAL: OTOMATİK GÖNDERIM YOK! 🚨🚨🚨

### **TÜM LEAD'LER SADECE ADMİN TARAFINDAN MANUEL GÖNDERİLİR!**

```
❌ ASLA otomatik gönderim yapma
❌ ASLA cron job kullanma
❌ ASLA scheduled task kullanma
❌ ASLA auto-assign yapma

✅ HER ZAMAN admin tıklar
✅ HER ZAMAN admin kontrol eder
✅ HER ZAMAN admin onaylar
✅ HER ZAMAN manuel işlem
```

---

## 🎯 SİSTEM AKIŞI

### **1. Paket Satın Alma Senaryosu**

#### **Örnek Durum:**
- **Şehir:** Riyadh
- **Hizmet Türü:** Elektrik (electric)
- **3 Usta var:**
  - **Usta A:** Bugün (Pazartesi) 3'lü paket aldı → Sıra: 1
  - **Usta B:** Bugün (Pazartesi) 3'lü paket aldı → Sıra: 2
  - **Usta C:** Yarın (Salı) 3'lü paket aldı → Sıra: 3

---

## 📦 PAKET SATIN ALINDIKTA NE OLUR?

### **İLK LEAD - ADMİN MANUEL GÖNDERİR**

✅ **Paket satın alındığında:**
```
- Admin paneline BİLDİRİM gelir
- Admin, yeni paket satın alındığını görür
- Admin, sistemde uygun lead var mı kontrol eder
- Varsa → Admin MANUEL olarak 1 adet lead gönderir
```

❌ **Sistemde lead yoksa:**
```
- Admin bekler
- Yeni lead sisteme girdiğinde
- Admin sıraya göre gönderir
```

### 🔴 **NEDEN OTOMATİK DEĞİL?**

**Çünkü:**
1. Lead kalitesi admin kontrolü gerektirir
2. Usta bilgileri doğru mu kontrol edilmeli
3. Lead usta için uygun mu değerlendirilmeli
4. Her lead değerli, otomatik spam riski var

---

## 🔔 KALAN LEAD'LER - USTA TALEBİ İLE

### **Kritik Kural: "LEAD İSTE" BUTONU - ZORUNLU!**

**Kalan 2 lead için:**

1. ✅ **Usta "Lead İste" butonuna MUTLAKA basmalı**
   - Bu buton provider dashboard'da olacak
   - Her tıklamada 1 lead isteği admin paneline düşer
   - Admin panelinde "Yeni İstek" bildirimi gelir

2. ✅ **Admin kontrol eder ve MANUEL gönderir:**
   - Usta lead istedi mi? → Müsait demektir!
   - Sistemde uygun lead var mı kontrol eder
   - Varsa → Admin MANUEL olarak gönderir
   - Yoksa → Bekler, yeni lead geldiğinde gönderir

3. ❌ **Usta lead isteği göndermezse:**
   - Lead ASLA GÖNDERİLMEZ!
   - Çünkü müsait olup olmadığı belli değil
   - Müsait olmayan ustaya lead gönderilirse:
     - ❌ Müşteri hizmet alamaz (usta müsait değil)
     - ❌ Lead boşa gider
     - ❌ Diğer hazır bekleyen ustalar lead alamaz

### 🎯 **NEDEN "LEAD İSTE" BUTONU ZORUNLU?**

**Senaryo 1: Usta müsait DEĞİL**
```
Usta A: 3'lü paket aldı, 1 lead aldı
  ↓
Usta A: Yoğun, şu an çalışamaz
  ↓
"Lead İste" butonuna BASMIYOR
  ↓
Admin: Usta müsait değil, lead göndermez
  ↓
✅ Lead boşa gitmedi
✅ Diğer ustalar lead alabilir
```

**Senaryo 2: Usta MÜSAİT**
```
Usta B: 3'lü paket aldı, 1 lead aldı
  ↓
Usta B: Hazır, yeni müşteri istiyorum
  ↓
"Lead İste" butonuna BASIYOR
  ↓
Admin: Usta müsait! → Lead gönder
  ↓
✅ Usta müşteri aldı
✅ Müşteri hizmet aldı
✅ Sistem sağlıklı çalışıyor
```

---

## 📊 ÖRNEK SENARYO - ADIM ADIM

### **Senaryo: 3 Usta, 5 Yeni Lead**

#### **Başlangıç Durumu:**
```
Usta A (Pazartesi satın aldı): 
  - Toplam: 3 lead
  - Kalan: 3 lead
  - Otomatik gönderilen: 0

Usta B (Pazartesi satın aldı):
  - Toplam: 3 lead
  - Kalan: 3 lead
  - Otomatik gönderilen: 0

Usta C (Salı satın aldı):
  - Toplam: 3 lead
  - Kalan: 3 lead
  - Otomatik gönderilen: 0
```

---

### **ADIM 1: Paket Satın Alma (Pazartesi)**

**Usta A, 3'lü paket satın alır (Saat 10:00):**
```
✅ Admin paneline BİLDİRİM gelir: "Yeni paket satın alındı"
✅ Admin kontrol eder: Sistemde 2 lead var (Riyadh + Elektrik)
✅ Admin MANUEL olarak 1 lead gönderir
✅ Kalan: 2 lead (beklemede - usta isteği gerekli)
```

**Usta B, 3'lü paket satın alır (Saat 14:00):**
```
✅ Admin paneline BİLDİRİM gelir
✅ Admin kontrol eder: Sistemde 1 lead kaldı
✅ Admin MANUEL olarak 1 lead gönderir
✅ Kalan: 2 lead (beklemede - usta isteği gerekli)
```

**Durum Özeti (Pazartesi Akşam):**
```
Usta A: 1 lead aldı (admin gönderdi), 2 bekliyor
Usta B: 1 lead aldı (admin gönderdi), 2 bekliyor
Sistemde: 0 lead kaldı
Admin: İki ustaya da ilk lead'i gönderdi ✅
```

---

### **ADIM 2: Yeni Lead Gelir (Salı Sabah)**

**Sistem:** 2 yeni müşteri kaydı geldi (Riyadh + Elektrik)

**Ama kimseye GÖNDERİLMEZ çünkü:**
❌ Usta A "Lead İste" butonuna basmadı
❌ Usta B "Lead İste" butonuna basmadı

```
Lead'ler beklemede kalır!
```

---

### **ADIM 3: Usta A "Lead İste" Butonuna Basar**

**Usta A, dashboard'dan "Lead İste" butonuna tıklar:**
```
✅ İstek admin paneline düşer
✅ Admin panelinde BİLDİRİM: "Usta A lead istiyor - MÜSAİT!"
✅ Admin kontrol eder: "Sistemde 2 lead var"
✅ Admin MANUEL olarak 1 lead Usta A'ya gönderir
✅ İstek durumu: "Tamamlandı" olarak işaretlenir
```

**Durum:**
```
Usta A: 2 lead aldı, 1 bekliyor
Usta B: 1 lead aldı, 2 bekliyor (henüz istemedi - müsait değil!)
Sistemde: 1 lead kaldı
```

---

### **ADIM 4: Usta C Paket Satın Alır (Salı Öğleden Sonra)**

**Usta C, 3'lü paket satın alır:**
```
✅ Admin paneline BİLDİRİM gelir
✅ Admin kontrol eder: Sistemde 1 lead var
✅ Admin MANUEL olarak 1 lead gönderir
✅ Kalan: 2 lead (beklemede - usta isteği gerekli)
```

**Durum:**
```
Usta A: 2 lead aldı, 1 bekliyor (sıra: 1)
Usta B: 1 lead aldı, 2 bekliyor (sıra: 2 - henüz istemedi!)
Usta C: 1 lead aldı, 2 bekliyor (sıra: 3)
Sistemde: 0 lead
```

---

### **ADIM 5: Yeni Lead + Talepler**

**3 yeni müşteri kaydı geldi (Çarşamba):**

1. **Usta A "Lead İste" butonuna basar:**
   - ✅ İstek admin paneline düşer
   - ✅ Admin: "Usta A müsait! Sistemde 3 lead var"
   - ✅ Admin MANUEL olarak 1 lead gönderir
   - ✅ Usta A'nın paketi tamamlandı! (3/3)

2. **Usta B "Lead İste" butonuna basar (ilk kez):**
   - ✅ İstek admin paneline düşer
   - ✅ Admin: "Usta B müsait! Sistemde 2 lead var"
   - ✅ Admin MANUEL olarak 1 lead gönderir
   - ✅ Usta B: 2/3 lead aldı

3. **Usta B tekrar "Lead İste" butonuna basar:**
   - ✅ İstek admin paneline düşer
   - ✅ Admin: "Sistemde 1 lead var"
   - ✅ Admin MANUEL olarak 1 lead gönderir
   - ✅ Usta B'nin paketi tamamlandı! (3/3)

4. **Artık sıra Usta C'de:**
   - Usta C "Lead İste" butonuna basarsa
   - Admin bildirimi alır
   - Admin lead gönderir

---

## 🚨 KRİTİK KURALLAR

### ✅ **YAPILMASI GEREKENLER:**

1. **Paket satın alındığında:**
   - ✅ Admin paneline BİLDİRİM gönder
   - ✅ Admin sistemde lead var mı kontrol eder
   - ✅ Varsa → Admin MANUEL olarak 1 adet gönderir
   - ✅ Yoksa → Admin bekler, lead geldiğinde gönderir

2. **Kalan lead'ler için (ZORUNLU):**
   - ✅ Usta "Lead İste" butonuna MUTLAKA basmalı
   - ✅ Admin panelinde "Yeni İstek" bildirimi gelsin
   - ✅ Admin kontrol edip MANUEL gönderir
   - ✅ İstek durumu güncellenir (pending → completed)

3. **Sıralama (Admin kontrolü):**
   - ✅ İlk satın alan → İlk sırada
   - ✅ Aynı gün satın alanlar → Saat sırasına göre
   - ✅ Paket bitince → Sonraki sıraya geç
   - ✅ Admin her zaman sırayı kontrol eder

### ❌ **YAPILMAMASI GEREKENLER:**

1. **❌ OTOMATİK GÖNDERIM YASAK:**
   - ❌ ASLA otomatik lead gönderme
   - ❌ Her şey admin kontrolünde olmalı
   - ❌ "Auto-assign" özelliği YOK

2. **❌ İstek olmadan gönderme:**
   - ❌ Usta istemediği halde gönderme
   - ❌ "Lead İste" butonu ZORUNLU
   - ❌ Müsait olmayan ustaya lead gönderme

3. **❌ Sıra atlatma:**
   - ❌ Sonraki ustaya geçme (önceki bitmeden/istekte bulunmadan)
   - ❌ Admin bile sırayı atlayamaz (özel durumlar hariç)

### 🔴 **EN ÖNEMLİ KURAL:**

**TÜM LEAD GÖNDERİMLERİ ADMİN TARAFINDAN MANUEL YAPILIR!**

**Neden?**
- Lead kalitesi kontrolü
- Usta müsaitlik kontrolü
- Doğru eşleştirme kontrolü
- Spam/hata önleme

---

## 🎯 SONUÇ

### **Sistem Mantığı:**

```
1. Paket Al → Admin BİLDİRİM alır
2. Admin Kontrol → İlk Lead MANUEL gönderir (varsa)
3. Kalan Lead'ler → Usta "Lead İste" butonuna basar
4. Admin BİLDİRİM alır → Kontrol → MANUEL gönderir
5. Paket Bitince → Sonraki Sıraya
```

### **Neden Bu Sistem?**

✅ **Admin kontrolü:** Her lead admin onayı ile gider
✅ **Usta müsaitlik kontrolü:** "Lead İste" = Ben hazırım!
✅ **Lead kalitesi:** Müsait usta = daha iyi hizmet
✅ **Adil dağıtım:** Sıra sistemi (admin takibi)
✅ **Spam önleme:** Otomatik bombardıman YOK
✅ **Doğruluk:** Admin lead-usta uyumunu kontrol eder

### **🔴 TEKRAR: OTOMATİK GÖNDERIM YOK!**

**Her lead gönderimi:**
- ✅ Admin görür
- ✅ Admin kontrol eder
- ✅ Admin tıklayarak gönderir
- ✅ Admin onaylar

**ASLA:**
- ❌ Otomatik sistem gönderimi yok
- ❌ Scheduled job gönderimi yok
- ❌ Cron job gönderimi yok

---

## 📌 ÖRNEK DATABASE AKIŞI

### **1. Paket Satın Alma:**
```sql
INSERT INTO provider_purchases (
    provider_id, package_id, leads_count, remaining_leads, 
    payment_status, purchased_at
) VALUES (
    3, 1, 3, 3, 'completed', NOW()
);
```

### **2. İlk Lead - Admin Manuel Gönderim:**
```sql
-- Admin panelinde: Yeni paket bildirimi
-- Admin kontrol eder: Sistemde lead var mı?
SELECT * FROM leads 
WHERE city = 'Riyadh' AND service_type = 'electric' 
AND status = 'new' 
LIMIT 1;

-- Admin "Lead Gönder" butonuna tıklar
INSERT INTO provider_lead_deliveries (
    purchase_id, provider_id, lead_id, 
    delivery_method, delivered_at, delivered_by
) VALUES (
    123, 3, 456, 'admin_manual', NOW(), 1
);

-- Kalan lead'i azalt
UPDATE provider_purchases 
SET remaining_leads = remaining_leads - 1 
WHERE id = 123;

-- Lead durumunu güncelle
UPDATE leads 
SET status = 'sold' 
WHERE id = 456;
```

### **3. "Lead İste" İsteği:**
```sql
INSERT INTO lead_requests (
    provider_id, purchase_id, 
    request_status, requested_at
) VALUES (
    3, 123, 'pending', NOW()
);
```

### **4. Admin Manuel Onayı ve Gönderimi:**
```sql
-- Admin panelinde istek görünür
-- Admin "Lead Gönder" butonuna tıklar

-- İsteği onayla
UPDATE lead_requests 
SET request_status = 'approved', 
    approved_at = NOW(),
    approved_by = 1  -- admin ID
WHERE id = 789;

-- Lead'i MANUEL gönder
INSERT INTO provider_lead_deliveries (
    purchase_id, provider_id, lead_id, 
    delivery_method, delivered_at, delivered_by,
    request_id
) VALUES (
    123, 3, 457, 'admin_manual', NOW(), 1, 789
);

-- Kalan lead'i azalt
UPDATE provider_purchases 
SET remaining_leads = remaining_leads - 1 
WHERE id = 123;

-- Lead durumunu güncelle
UPDATE leads 
SET status = 'sold' 
WHERE id = 457;
```

---

## 🎨 UI/UX GEREKSİNİMLERİ

### **Provider Dashboard:**

1. **"Lead İste" Butonu:**
   ```
   - Her pakette görünmeli
   - Kalan lead varsa aktif
   - Tıklanınca → İstek gönder
   - Loading animasyonu
   - Success mesajı
   ```

2. **Paket Durumu Kartı:**
   ```
   - Toplam Lead: 3
   - Alınan Lead: 1
   - Kalan Lead: 2
   - "Lead İste" Butonu (2 kez basabilir)
   ```

### **Admin Panel:**

1. **Yeni Paket Bildirimleri:**
   ```
   - "Yeni Paket Satın Alındı" bildirimi
   - Usta Adı + Paket Detayı
   - "İlk Lead'i Gönder" butonu
   - Sistemde uygun lead var mı göstergesi
   ```

2. **Lead İstekleri Tablosu:**
   ```
   - Usta Adı
   - Paket Bilgisi
   - İstek Tarihi
   - Durum (Beklemede/Onaylandı)
   - "Lead Gönder" Butonu (MANUEL)
   - Bildirim sayısı (kaç bekleyen istek var)
   ```

3. **Lead Havuzu:**
   ```
   - Kullanılabilir lead'ler
   - Filtreleme (şehir, hizmet türü)
   - "Bu Lead'i Gönder" butonu
   - Usta seçimi dropdown
   ```

4. **❌ OTOMATİK KONTROL YOK:**
   ```
   ❌ Otomatik gönderim YOK
   ❌ Cron job YOK
   ❌ Auto-assign YOK
   ✅ Sadece MANUEL admin gönderimi
   ```

---

## ✅ UYGULAMA PLANI

### **Gerekli Tablolar:**

1. **lead_requests** (yeni tablo):
   ```sql
   CREATE TABLE lead_requests (
       id INT AUTO_INCREMENT PRIMARY KEY,
       provider_id INT NOT NULL,
       purchase_id INT NOT NULL,
       request_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
       requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       approved_at TIMESTAMP NULL,
       approved_by INT NULL,
       notes TEXT NULL,
       FOREIGN KEY (provider_id) REFERENCES service_providers(id),
       FOREIGN KEY (purchase_id) REFERENCES provider_purchases(id),
       FOREIGN KEY (approved_by) REFERENCES admins(id)
   );
   ```

### **Gerekli Fonksiyonlar:**

1. **Backend (PHP) - Provider:**
   - `requestLead()` - Usta lead ister (AJAX endpoint)
   - `getMyRequests()` - Ustanın isteklerini listeler

2. **Backend (PHP) - Admin:**
   - `listPendingRequests()` - Bekleyen istekleri listeler
   - `sendLeadManually()` - Admin MANUEL lead gönderir
   - `getPurchaseNotifications()` - Yeni paket bildirimlerini getirir
   - `getAvailableLeads()` - Mevcut lead'leri listeler
   - ❌ `autoAssignLead()` - BU FONKSİYON YOK!
   - ❌ `autoCheckAndSend()` - BU FONKSİYON YOK!

3. **Frontend (JavaScript) - Provider:**
   - `requestLeadButton()` - AJAX request
   - `showRequestStatus()` - Durum göster
   - `updateRemainingLeads()` - Kalan lead güncelle

4. **Frontend (JavaScript) - Admin:**
   - `sendLeadToProvider()` - Lead gönder butonu (AJAX)
   - `loadPendingRequests()` - İstekleri yükle
   - `loadPurchaseNotifications()` - Paket bildirimlerini yükle
   - `showLeadPreview()` - Lead önizleme modali

---

## 🔒 GÜVENLİK KURALLARI

1. **Spam Önleme:**
   - Aynı usta 5 dakikada 1 istek yapabilir
   - Rate limiting

2. **Doğrulama:**
   - Ustanın kalan lead'i var mı?
   - Paket aktif mi?
   - Ödeme tamamlanmış mı?

3. **Log:**
   - Tüm istekleri kaydet
   - Admin onaylarını logla
   - Lead gönderimlerini takip et

---

## 📝 HIZLI ÖZET - COPY/PASTE İÇİN

### **3 Basit Kural:**

```
1. Paket satın alındı
   → Admin paneline BİLDİRİM
   → Admin kontrol eder
   → Admin MANUEL 1 lead gönderir

2. Usta "Lead İste" butonuna bastı
   → Admin paneline BİLDİRİM
   → Admin kontrol eder
   → Admin MANUEL 1 lead gönderir

3. Usta "Lead İste" butonuna BASMAZSA
   → Lead GÖNDERİLMEZ
   → Çünkü müsait değil demektir
```

### **1 Önemli Yasak:**

```
❌ OTOMATİK GÖNDERIM YAPMA!

Hiçbir koşulda, hiçbir durumda,
otomatik lead gönderme sistemi kurma.

Her lead, admin'in "Gönder" butonuna
basmasıyla gider. Başka yolu yok.
```

---

**SON GÜNCELLEME:** 2024-11-18

**HAZIRLAYAN:** AI Assistant

**DURUM:** ✅ GÜNCELLEME TAMAMLANDI - MANUEL GÖNDERIM SİSTEMİ

**VERSİYON:** 2.0 - Manuel Admin Kontrolü


```
██████╗ ██╗██╗  ██╗██╗  ██╗ █████╗ ████████╗    ██╗
██╔══██╗██║██║ ██╔╝██║ ██╔╝██╔══██╗╚══██╔══╝    ██║
██║  ██║██║█████╔╝ █████╔╝ ███████║   ██║       ██║
██║  ██║██║██╔═██╗ ██╔═██╗ ██╔══██║   ██║       ╚═╝
██████╔╝██║██║  ██╗██║  ██╗██║  ██║   ██║       ██╗
╚═════╝ ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝       ╚═╝
```

## 🔴🔴🔴 ÇOK ÖNEMLİ - ASLA UNUTMA! 🔴🔴🔴

Bu döküman, lead'lerin ustalara nasıl gönderileceğini açıklar.

---

## 🚨🚨🚨 KRİTİK KURAL: OTOMATİK GÖNDERIM YOK! 🚨🚨🚨

### **TÜM LEAD'LER SADECE ADMİN TARAFINDAN MANUEL GÖNDERİLİR!**

```
❌ ASLA otomatik gönderim yapma
❌ ASLA cron job kullanma
❌ ASLA scheduled task kullanma
❌ ASLA auto-assign yapma

✅ HER ZAMAN admin tıklar
✅ HER ZAMAN admin kontrol eder
✅ HER ZAMAN admin onaylar
✅ HER ZAMAN manuel işlem
```

---

## 🎯 SİSTEM AKIŞI

### **1. Paket Satın Alma Senaryosu**

#### **Örnek Durum:**
- **Şehir:** Riyadh
- **Hizmet Türü:** Elektrik (electric)
- **3 Usta var:**
  - **Usta A:** Bugün (Pazartesi) 3'lü paket aldı → Sıra: 1
  - **Usta B:** Bugün (Pazartesi) 3'lü paket aldı → Sıra: 2
  - **Usta C:** Yarın (Salı) 3'lü paket aldı → Sıra: 3

---

## 📦 PAKET SATIN ALINDIKTA NE OLUR?

### **İLK LEAD - ADMİN MANUEL GÖNDERİR**

✅ **Paket satın alındığında:**
```
- Admin paneline BİLDİRİM gelir
- Admin, yeni paket satın alındığını görür
- Admin, sistemde uygun lead var mı kontrol eder
- Varsa → Admin MANUEL olarak 1 adet lead gönderir
```

❌ **Sistemde lead yoksa:**
```
- Admin bekler
- Yeni lead sisteme girdiğinde
- Admin sıraya göre gönderir
```

### 🔴 **NEDEN OTOMATİK DEĞİL?**

**Çünkü:**
1. Lead kalitesi admin kontrolü gerektirir
2. Usta bilgileri doğru mu kontrol edilmeli
3. Lead usta için uygun mu değerlendirilmeli
4. Her lead değerli, otomatik spam riski var

---

## 🔔 KALAN LEAD'LER - USTA TALEBİ İLE

### **Kritik Kural: "LEAD İSTE" BUTONU - ZORUNLU!**

**Kalan 2 lead için:**

1. ✅ **Usta "Lead İste" butonuna MUTLAKA basmalı**
   - Bu buton provider dashboard'da olacak
   - Her tıklamada 1 lead isteği admin paneline düşer
   - Admin panelinde "Yeni İstek" bildirimi gelir

2. ✅ **Admin kontrol eder ve MANUEL gönderir:**
   - Usta lead istedi mi? → Müsait demektir!
   - Sistemde uygun lead var mı kontrol eder
   - Varsa → Admin MANUEL olarak gönderir
   - Yoksa → Bekler, yeni lead geldiğinde gönderir

3. ❌ **Usta lead isteği göndermezse:**
   - Lead ASLA GÖNDERİLMEZ!
   - Çünkü müsait olup olmadığı belli değil
   - Müsait olmayan ustaya lead gönderilirse:
     - ❌ Müşteri hizmet alamaz (usta müsait değil)
     - ❌ Lead boşa gider
     - ❌ Diğer hazır bekleyen ustalar lead alamaz

### 🎯 **NEDEN "LEAD İSTE" BUTONU ZORUNLU?**

**Senaryo 1: Usta müsait DEĞİL**
```
Usta A: 3'lü paket aldı, 1 lead aldı
  ↓
Usta A: Yoğun, şu an çalışamaz
  ↓
"Lead İste" butonuna BASMIYOR
  ↓
Admin: Usta müsait değil, lead göndermez
  ↓
✅ Lead boşa gitmedi
✅ Diğer ustalar lead alabilir
```

**Senaryo 2: Usta MÜSAİT**
```
Usta B: 3'lü paket aldı, 1 lead aldı
  ↓
Usta B: Hazır, yeni müşteri istiyorum
  ↓
"Lead İste" butonuna BASIYOR
  ↓
Admin: Usta müsait! → Lead gönder
  ↓
✅ Usta müşteri aldı
✅ Müşteri hizmet aldı
✅ Sistem sağlıklı çalışıyor
```

---

## 📊 ÖRNEK SENARYO - ADIM ADIM

### **Senaryo: 3 Usta, 5 Yeni Lead**

#### **Başlangıç Durumu:**
```
Usta A (Pazartesi satın aldı): 
  - Toplam: 3 lead
  - Kalan: 3 lead
  - Otomatik gönderilen: 0

Usta B (Pazartesi satın aldı):
  - Toplam: 3 lead
  - Kalan: 3 lead
  - Otomatik gönderilen: 0

Usta C (Salı satın aldı):
  - Toplam: 3 lead
  - Kalan: 3 lead
  - Otomatik gönderilen: 0
```

---

### **ADIM 1: Paket Satın Alma (Pazartesi)**

**Usta A, 3'lü paket satın alır (Saat 10:00):**
```
✅ Admin paneline BİLDİRİM gelir: "Yeni paket satın alındı"
✅ Admin kontrol eder: Sistemde 2 lead var (Riyadh + Elektrik)
✅ Admin MANUEL olarak 1 lead gönderir
✅ Kalan: 2 lead (beklemede - usta isteği gerekli)
```

**Usta B, 3'lü paket satın alır (Saat 14:00):**
```
✅ Admin paneline BİLDİRİM gelir
✅ Admin kontrol eder: Sistemde 1 lead kaldı
✅ Admin MANUEL olarak 1 lead gönderir
✅ Kalan: 2 lead (beklemede - usta isteği gerekli)
```

**Durum Özeti (Pazartesi Akşam):**
```
Usta A: 1 lead aldı (admin gönderdi), 2 bekliyor
Usta B: 1 lead aldı (admin gönderdi), 2 bekliyor
Sistemde: 0 lead kaldı
Admin: İki ustaya da ilk lead'i gönderdi ✅
```

---

### **ADIM 2: Yeni Lead Gelir (Salı Sabah)**

**Sistem:** 2 yeni müşteri kaydı geldi (Riyadh + Elektrik)

**Ama kimseye GÖNDERİLMEZ çünkü:**
❌ Usta A "Lead İste" butonuna basmadı
❌ Usta B "Lead İste" butonuna basmadı

```
Lead'ler beklemede kalır!
```

---

### **ADIM 3: Usta A "Lead İste" Butonuna Basar**

**Usta A, dashboard'dan "Lead İste" butonuna tıklar:**
```
✅ İstek admin paneline düşer
✅ Admin panelinde BİLDİRİM: "Usta A lead istiyor - MÜSAİT!"
✅ Admin kontrol eder: "Sistemde 2 lead var"
✅ Admin MANUEL olarak 1 lead Usta A'ya gönderir
✅ İstek durumu: "Tamamlandı" olarak işaretlenir
```

**Durum:**
```
Usta A: 2 lead aldı, 1 bekliyor
Usta B: 1 lead aldı, 2 bekliyor (henüz istemedi - müsait değil!)
Sistemde: 1 lead kaldı
```

---

### **ADIM 4: Usta C Paket Satın Alır (Salı Öğleden Sonra)**

**Usta C, 3'lü paket satın alır:**
```
✅ Admin paneline BİLDİRİM gelir
✅ Admin kontrol eder: Sistemde 1 lead var
✅ Admin MANUEL olarak 1 lead gönderir
✅ Kalan: 2 lead (beklemede - usta isteği gerekli)
```

**Durum:**
```
Usta A: 2 lead aldı, 1 bekliyor (sıra: 1)
Usta B: 1 lead aldı, 2 bekliyor (sıra: 2 - henüz istemedi!)
Usta C: 1 lead aldı, 2 bekliyor (sıra: 3)
Sistemde: 0 lead
```

---

### **ADIM 5: Yeni Lead + Talepler**

**3 yeni müşteri kaydı geldi (Çarşamba):**

1. **Usta A "Lead İste" butonuna basar:**
   - ✅ İstek admin paneline düşer
   - ✅ Admin: "Usta A müsait! Sistemde 3 lead var"
   - ✅ Admin MANUEL olarak 1 lead gönderir
   - ✅ Usta A'nın paketi tamamlandı! (3/3)

2. **Usta B "Lead İste" butonuna basar (ilk kez):**
   - ✅ İstek admin paneline düşer
   - ✅ Admin: "Usta B müsait! Sistemde 2 lead var"
   - ✅ Admin MANUEL olarak 1 lead gönderir
   - ✅ Usta B: 2/3 lead aldı

3. **Usta B tekrar "Lead İste" butonuna basar:**
   - ✅ İstek admin paneline düşer
   - ✅ Admin: "Sistemde 1 lead var"
   - ✅ Admin MANUEL olarak 1 lead gönderir
   - ✅ Usta B'nin paketi tamamlandı! (3/3)

4. **Artık sıra Usta C'de:**
   - Usta C "Lead İste" butonuna basarsa
   - Admin bildirimi alır
   - Admin lead gönderir

---

## 🚨 KRİTİK KURALLAR

### ✅ **YAPILMASI GEREKENLER:**

1. **Paket satın alındığında:**
   - ✅ Admin paneline BİLDİRİM gönder
   - ✅ Admin sistemde lead var mı kontrol eder
   - ✅ Varsa → Admin MANUEL olarak 1 adet gönderir
   - ✅ Yoksa → Admin bekler, lead geldiğinde gönderir

2. **Kalan lead'ler için (ZORUNLU):**
   - ✅ Usta "Lead İste" butonuna MUTLAKA basmalı
   - ✅ Admin panelinde "Yeni İstek" bildirimi gelsin
   - ✅ Admin kontrol edip MANUEL gönderir
   - ✅ İstek durumu güncellenir (pending → completed)

3. **Sıralama (Admin kontrolü):**
   - ✅ İlk satın alan → İlk sırada
   - ✅ Aynı gün satın alanlar → Saat sırasına göre
   - ✅ Paket bitince → Sonraki sıraya geç
   - ✅ Admin her zaman sırayı kontrol eder

### ❌ **YAPILMAMASI GEREKENLER:**

1. **❌ OTOMATİK GÖNDERIM YASAK:**
   - ❌ ASLA otomatik lead gönderme
   - ❌ Her şey admin kontrolünde olmalı
   - ❌ "Auto-assign" özelliği YOK

2. **❌ İstek olmadan gönderme:**
   - ❌ Usta istemediği halde gönderme
   - ❌ "Lead İste" butonu ZORUNLU
   - ❌ Müsait olmayan ustaya lead gönderme

3. **❌ Sıra atlatma:**
   - ❌ Sonraki ustaya geçme (önceki bitmeden/istekte bulunmadan)
   - ❌ Admin bile sırayı atlayamaz (özel durumlar hariç)

### 🔴 **EN ÖNEMLİ KURAL:**

**TÜM LEAD GÖNDERİMLERİ ADMİN TARAFINDAN MANUEL YAPILIR!**

**Neden?**
- Lead kalitesi kontrolü
- Usta müsaitlik kontrolü
- Doğru eşleştirme kontrolü
- Spam/hata önleme

---

## 🎯 SONUÇ

### **Sistem Mantığı:**

```
1. Paket Al → Admin BİLDİRİM alır
2. Admin Kontrol → İlk Lead MANUEL gönderir (varsa)
3. Kalan Lead'ler → Usta "Lead İste" butonuna basar
4. Admin BİLDİRİM alır → Kontrol → MANUEL gönderir
5. Paket Bitince → Sonraki Sıraya
```

### **Neden Bu Sistem?**

✅ **Admin kontrolü:** Her lead admin onayı ile gider
✅ **Usta müsaitlik kontrolü:** "Lead İste" = Ben hazırım!
✅ **Lead kalitesi:** Müsait usta = daha iyi hizmet
✅ **Adil dağıtım:** Sıra sistemi (admin takibi)
✅ **Spam önleme:** Otomatik bombardıman YOK
✅ **Doğruluk:** Admin lead-usta uyumunu kontrol eder

### **🔴 TEKRAR: OTOMATİK GÖNDERIM YOK!**

**Her lead gönderimi:**
- ✅ Admin görür
- ✅ Admin kontrol eder
- ✅ Admin tıklayarak gönderir
- ✅ Admin onaylar

**ASLA:**
- ❌ Otomatik sistem gönderimi yok
- ❌ Scheduled job gönderimi yok
- ❌ Cron job gönderimi yok

---

## 📌 ÖRNEK DATABASE AKIŞI

### **1. Paket Satın Alma:**
```sql
INSERT INTO provider_purchases (
    provider_id, package_id, leads_count, remaining_leads, 
    payment_status, purchased_at
) VALUES (
    3, 1, 3, 3, 'completed', NOW()
);
```

### **2. İlk Lead - Admin Manuel Gönderim:**
```sql
-- Admin panelinde: Yeni paket bildirimi
-- Admin kontrol eder: Sistemde lead var mı?
SELECT * FROM leads 
WHERE city = 'Riyadh' AND service_type = 'electric' 
AND status = 'new' 
LIMIT 1;

-- Admin "Lead Gönder" butonuna tıklar
INSERT INTO provider_lead_deliveries (
    purchase_id, provider_id, lead_id, 
    delivery_method, delivered_at, delivered_by
) VALUES (
    123, 3, 456, 'admin_manual', NOW(), 1
);

-- Kalan lead'i azalt
UPDATE provider_purchases 
SET remaining_leads = remaining_leads - 1 
WHERE id = 123;

-- Lead durumunu güncelle
UPDATE leads 
SET status = 'sold' 
WHERE id = 456;
```

### **3. "Lead İste" İsteği:**
```sql
INSERT INTO lead_requests (
    provider_id, purchase_id, 
    request_status, requested_at
) VALUES (
    3, 123, 'pending', NOW()
);
```

### **4. Admin Manuel Onayı ve Gönderimi:**
```sql
-- Admin panelinde istek görünür
-- Admin "Lead Gönder" butonuna tıklar

-- İsteği onayla
UPDATE lead_requests 
SET request_status = 'approved', 
    approved_at = NOW(),
    approved_by = 1  -- admin ID
WHERE id = 789;

-- Lead'i MANUEL gönder
INSERT INTO provider_lead_deliveries (
    purchase_id, provider_id, lead_id, 
    delivery_method, delivered_at, delivered_by,
    request_id
) VALUES (
    123, 3, 457, 'admin_manual', NOW(), 1, 789
);

-- Kalan lead'i azalt
UPDATE provider_purchases 
SET remaining_leads = remaining_leads - 1 
WHERE id = 123;

-- Lead durumunu güncelle
UPDATE leads 
SET status = 'sold' 
WHERE id = 457;
```

---

## 🎨 UI/UX GEREKSİNİMLERİ

### **Provider Dashboard:**

1. **"Lead İste" Butonu:**
   ```
   - Her pakette görünmeli
   - Kalan lead varsa aktif
   - Tıklanınca → İstek gönder
   - Loading animasyonu
   - Success mesajı
   ```

2. **Paket Durumu Kartı:**
   ```
   - Toplam Lead: 3
   - Alınan Lead: 1
   - Kalan Lead: 2
   - "Lead İste" Butonu (2 kez basabilir)
   ```

### **Admin Panel:**

1. **Yeni Paket Bildirimleri:**
   ```
   - "Yeni Paket Satın Alındı" bildirimi
   - Usta Adı + Paket Detayı
   - "İlk Lead'i Gönder" butonu
   - Sistemde uygun lead var mı göstergesi
   ```

2. **Lead İstekleri Tablosu:**
   ```
   - Usta Adı
   - Paket Bilgisi
   - İstek Tarihi
   - Durum (Beklemede/Onaylandı)
   - "Lead Gönder" Butonu (MANUEL)
   - Bildirim sayısı (kaç bekleyen istek var)
   ```

3. **Lead Havuzu:**
   ```
   - Kullanılabilir lead'ler
   - Filtreleme (şehir, hizmet türü)
   - "Bu Lead'i Gönder" butonu
   - Usta seçimi dropdown
   ```

4. **❌ OTOMATİK KONTROL YOK:**
   ```
   ❌ Otomatik gönderim YOK
   ❌ Cron job YOK
   ❌ Auto-assign YOK
   ✅ Sadece MANUEL admin gönderimi
   ```

---

## ✅ UYGULAMA PLANI

### **Gerekli Tablolar:**

1. **lead_requests** (yeni tablo):
   ```sql
   CREATE TABLE lead_requests (
       id INT AUTO_INCREMENT PRIMARY KEY,
       provider_id INT NOT NULL,
       purchase_id INT NOT NULL,
       request_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
       requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       approved_at TIMESTAMP NULL,
       approved_by INT NULL,
       notes TEXT NULL,
       FOREIGN KEY (provider_id) REFERENCES service_providers(id),
       FOREIGN KEY (purchase_id) REFERENCES provider_purchases(id),
       FOREIGN KEY (approved_by) REFERENCES admins(id)
   );
   ```

### **Gerekli Fonksiyonlar:**

1. **Backend (PHP) - Provider:**
   - `requestLead()` - Usta lead ister (AJAX endpoint)
   - `getMyRequests()` - Ustanın isteklerini listeler

2. **Backend (PHP) - Admin:**
   - `listPendingRequests()` - Bekleyen istekleri listeler
   - `sendLeadManually()` - Admin MANUEL lead gönderir
   - `getPurchaseNotifications()` - Yeni paket bildirimlerini getirir
   - `getAvailableLeads()` - Mevcut lead'leri listeler
   - ❌ `autoAssignLead()` - BU FONKSİYON YOK!
   - ❌ `autoCheckAndSend()` - BU FONKSİYON YOK!

3. **Frontend (JavaScript) - Provider:**
   - `requestLeadButton()` - AJAX request
   - `showRequestStatus()` - Durum göster
   - `updateRemainingLeads()` - Kalan lead güncelle

4. **Frontend (JavaScript) - Admin:**
   - `sendLeadToProvider()` - Lead gönder butonu (AJAX)
   - `loadPendingRequests()` - İstekleri yükle
   - `loadPurchaseNotifications()` - Paket bildirimlerini yükle
   - `showLeadPreview()` - Lead önizleme modali

---

## 🔒 GÜVENLİK KURALLARI

1. **Spam Önleme:**
   - Aynı usta 5 dakikada 1 istek yapabilir
   - Rate limiting

2. **Doğrulama:**
   - Ustanın kalan lead'i var mı?
   - Paket aktif mi?
   - Ödeme tamamlanmış mı?

3. **Log:**
   - Tüm istekleri kaydet
   - Admin onaylarını logla
   - Lead gönderimlerini takip et

---

## 📝 HIZLI ÖZET - COPY/PASTE İÇİN

### **3 Basit Kural:**

```
1. Paket satın alındı
   → Admin paneline BİLDİRİM
   → Admin kontrol eder
   → Admin MANUEL 1 lead gönderir

2. Usta "Lead İste" butonuna bastı
   → Admin paneline BİLDİRİM
   → Admin kontrol eder
   → Admin MANUEL 1 lead gönderir

3. Usta "Lead İste" butonuna BASMAZSA
   → Lead GÖNDERİLMEZ
   → Çünkü müsait değil demektir
```

### **1 Önemli Yasak:**

```
❌ OTOMATİK GÖNDERIM YAPMA!

Hiçbir koşulda, hiçbir durumda,
otomatik lead gönderme sistemi kurma.

Her lead, admin'in "Gönder" butonuna
basmasıyla gider. Başka yolu yok.
```

---

**SON GÜNCELLEME:** 2024-11-18

**HAZIRLAYAN:** AI Assistant

**DURUM:** ✅ GÜNCELLEME TAMAMLANDI - MANUEL GÖNDERIM SİSTEMİ

**VERSİYON:** 2.0 - Manuel Admin Kontrolü



