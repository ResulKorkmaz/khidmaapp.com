# RULES

Bu dosya, **onlineusta.com.tr** projesine katkı sağlayan herkes için zorunlu kuralları ve en iyi uygulamaları içerir.

## 1️⃣ Genel Prensipler
1. **Kullanıcı Önceliği**: Her karar, son kullanıcının ihtiyacını çözmeli.
2. **Temiz Kod**: Anlaşılır, okunabilir ve test edilebilir kod.
3. **Güvenlik**: "Security by design" yaklaşımı.
4. **Performans**: İlk byte < 100 ms, LCP < 2.5 s.
5. **SEO**: Her yeni sayfa, Lighthouse SEO skoru ≥ 90.

## 2️⃣ Geliştirme Kuralları
- **Dil**: TypeScript (strict) zorunlu.
- **Çerçeveler**: Frontend `Next.js`, backend `NestJS`.
- **Kod Stili**: ESLint + Prettier; commit öncesi `pnpm lint:fix`.
- **Branch İsmi**: `feature/`, `bugfix/`, `hotfix/`, `chore/` önekleri.
- **Commit Mesajı**: Conventional Commits (örn. `feat(account): add password reset`).
- **Test**: Her servis için unit + e2e ≤ 80% coverage.
- **PR**: Açık PR min. 1 reviewer, CI yeşil olmadan merge **yasak**.

## 3️⃣ UI/UX Kuralları
- **Tasarım Sistemi**: `packages/ui` haricinde ham CSS / farklı UI kütüphanesi kullanmak yasak.
- **Erişilebilirlik**: WCAG 2.1 AA uyumu; `aria-*` etiketleri.
- **Responsive**: Mobile-first, min-width 320px.
- **Renk Paleti**: Tailwind config'deki `brand-*` renkleri dışına çıkma.

## 4️⃣ SEO Kuralları
- Sayfa başına **tek** `<h1>` etiketi.
- Dinamik title & meta description.
- Open Graph ve Twitter Card zorunlu.
- Structured Data (JSON-LD) uygun tipte (Service, Product, FAQ).
- Robots.txt değişikliği PR'da SEO label'ı gerektirir.

## 5️⃣ Güvenlik Kuralları
- **OWASP**: Top 10 kontrolleri checklist.
- **Env**: .env dosyaları repo dışında, `doppler` ile inject.
- **Secrets**: Kodda secret **tutma**.
- **SCA**: Dependabot uyarılarını 48 s içinde kapat.

## 6️⃣ Kod İnceleme Checklist (Pull Request Template)
1. [ ] Lint & tests ✅
2. [ ] Fonksiyonel gereksinimler sağlanıyor
3. [ ] Kod okunabilir / yorum satırı gerek yok
4. [ ] Güvenlik açıkları yok
5. [ ] SEO ve performans kriterleri karşılanıyor

## 7️⃣ Deploy Politikası
- `main` ➜ Staging (otomatik)
- `production` branch merge ➜ Production
- Rollback: `kubectl rollout undo` + incident raporu 24 s içinde.

---
**Bu kurallar ihlal edildiğinde PR reddedilir.** 