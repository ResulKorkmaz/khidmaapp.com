#!/bin/bash

# OnlineUsta Marketplace Database Migration Script
echo "🚀 Armut.com benzeri marketplace database migration başlatılıyor..."

# Environment kontrol
if [[ -z "$DATABASE_URL" ]]; then
  echo "❌ DATABASE_URL environment variable bulunamadı!"
  echo "Local development için: export DATABASE_URL=\"postgresql://postgres:password123@localhost:5432/onlineusta_dev\""
  echo "Production için: Vercel dashboard'dan DATABASE_URL'yi kontrol edin."
  exit 1
fi

echo "📊 Database URL: ${DATABASE_URL:0:30}..."

# Güvenlik onayı
read -p "⚠️  Bu script database'i marketplace schema'ya göre değiştirecek. Devam edilsin mi? (y/N): " confirm

if [[ $confirm != [yY] ]]; then
  echo "❌ İşlem iptal edildi."
  exit 1
fi

# Backup oluştur (production için)
if [[ $DATABASE_URL == *"postgres"* ]] && [[ $DATABASE_URL != *"localhost"* ]]; then
  echo "💾 Production database backup alınıyor..."
  BACKUP_FILE="marketplace_backup_$(date +%Y%m%d_%H%M%S).sql"
  pg_dump $DATABASE_URL > $BACKUP_FILE
  
  if [[ $? -eq 0 ]]; then
    echo "✅ Backup başarıyla oluşturuldu: $BACKUP_FILE"
  else
    echo "❌ Backup oluşturulamadı! İşlem durduruluyor."
    exit 1
  fi
fi

# Eski schema'yı marketplace schema ile değiştir
echo "🔄 Schema dosyası güncelleniyor..."
cp prisma/schema.prisma prisma/schema-backup.prisma
cp schema-marketplace.prisma prisma/schema.prisma

# Prisma client generate
echo "🔧 Prisma client generate ediliyor..."
npx prisma generate

if [[ $? -ne 0 ]]; then
  echo "❌ Prisma generate başarısız!"
  cp prisma/schema-backup.prisma prisma/schema.prisma
  exit 1
fi

# Database migration (dikkatli!)
echo "🔄 Database migration çalıştırılıyor..."
npx prisma db push --accept-data-loss

if [[ $? -eq 0 ]]; then
  echo "✅ Migration başarıyla tamamlandı!"
else
  echo "❌ Migration başarısız! Schema restore ediliyor..."
  cp prisma/schema-backup.prisma prisma/schema.prisma
  npx prisma generate
  exit 1
fi

# Dependencies kontrol ve yükleme
echo "📦 Gerekli dependencies kontrol ediliyor..."
if ! npm list bcryptjs >/dev/null 2>&1; then
  echo "📥 bcryptjs yükleniyor..."
  npm install bcryptjs @types/bcryptjs
fi

if ! npm list tsx >/dev/null 2>&1; then
  echo "📥 tsx yükleniyor..."
  npm install tsx --save-dev
fi

# Marketplace seed data
echo "🌱 Marketplace seed data eklemek istiyor musunuz?"
echo "Bu işlem örnek kategoriler, kullanıcılar ve hizmet talepleri ekleyecek."
read -p "Seed data eklensin mi? (y/N): " seed_confirm

if [[ $seed_confirm == [yY] ]]; then
  echo "🌱 Marketplace seed data ekleniyor..."
  
  # Seed script'ini çalıştır
  if [[ -f "seed-marketplace.ts" ]]; then
    npx tsx seed-marketplace.ts
  else
    echo "⚠️  seed-marketplace.ts dosyası bulunamadı. Manual seed gerekli."
  fi
  
  if [[ $? -eq 0 ]]; then
    echo "✅ Seed data başarıyla eklendi!"
  else
    echo "⚠️  Seed data eklenemedi, ama migration tamamlandı."
  fi
fi

echo ""
echo "🎉 Marketplace database migration tamamlandı!"
echo ""
echo "📊 Yeni schema özellikleri:"
echo "• ✅ Gelişmiş kullanıcı sistemi (authentication, verification)"
echo "• ✅ Mesajlaşma sistemi (conversations, messages)"
echo "• ✅ Portfolyo yönetimi"
echo "• ✅ Transaction/ödeme sistemi"
echo "• ✅ Bildirim sistemi"
echo "• ✅ Şikayet ve moderasyon sistemi"
echo "• ✅ Gelişmiş review sistemi"
echo "• ✅ Coğrafi konum desteği"
echo ""
echo "🔧 Database'i kontrol etmek için:"
echo "npx prisma studio"
echo ""
echo "🚀 Web uygulamasını başlatmak için:"
echo "cd ../.. && pnpm dev"
echo ""

if [[ -f $BACKUP_FILE ]]; then
  echo "📁 Backup dosyası: $BACKUP_FILE"
  echo "🔄 Rollback için: psql \$DATABASE_URL < $BACKUP_FILE"
fi 