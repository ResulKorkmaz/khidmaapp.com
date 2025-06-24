#!/bin/bash

# OnlineUsta Production Migration Script
echo "🔄 Production migration başlatılıyor..."

# Production database URL kontrolü
if [[ -z "$DATABASE_URL" ]]; then
  echo "❌ DATABASE_URL environment variable bulunamadı!"
  echo "Vercel dashboard'dan DATABASE_URL'yi kontrol edin."
  exit 1
fi

# Güvenlik onayı
echo "⚠️  Bu script production database'ini etkileyecek!"
echo "Database URL: ${DATABASE_URL:0:30}..."
read -p "Devam etmek istediğinizden emin misiniz? (y/N): " confirm

if [[ $confirm != [yY] ]]; then
  echo "❌ İşlem iptal edildi."
  exit 1
fi

# Backup oluştur
echo "💾 Database backup alınıyor..."
BACKUP_FILE="backup_$(date +%Y%m%d_%H%M%S).sql"
pg_dump $DATABASE_URL > $BACKUP_FILE

if [[ $? -eq 0 ]]; then
  echo "✅ Backup başarıyla oluşturuldu: $BACKUP_FILE"
else
  echo "❌ Backup oluşturulamadı! İşlem durduruluyor."
  exit 1
fi

# Prisma client generate
echo "🔧 Prisma client generate ediliyor..."
npx prisma generate

# Migration çalıştır
echo "🔄 Database migration çalıştırılıyor..."
npx prisma db push

if [[ $? -eq 0 ]]; then
  echo "✅ Migration başarıyla tamamlandı!"
else
  echo "❌ Migration başarısız! Backup'tan restore etmek istiyor musunuz?"
  read -p "Restore edilsin mi? (y/N): " restore_confirm
  
  if [[ $restore_confirm == [yY] ]]; then
    echo "🔄 Database restore ediliyor..."
    psql $DATABASE_URL < $BACKUP_FILE
    echo "✅ Restore tamamlandı."
  fi
  exit 1
fi

# Seed data (opsiyonel)
echo "🌱 Seed data eklemek istiyor musunuz? (production için dikkatli olun)"
read -p "Seed data eklensin mi? (y/N): " seed_confirm

if [[ $seed_confirm == [yY] ]]; then
  echo "🌱 Seed data ekleniyor..."
  npx tsx prisma/seed.ts
  
  if [[ $? -eq 0 ]]; then
    echo "✅ Seed data başarıyla eklendi!"
  else
    echo "⚠️  Seed data eklenemedi, ama migration tamamlandı."
  fi
fi

echo ""
echo "🎉 Production migration tamamlandı!"
echo "📁 Backup dosyası: $BACKUP_FILE"
echo "🌐 Database durumunu kontrol edin: npx prisma studio"
echo "" 