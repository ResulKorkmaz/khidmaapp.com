#!/bin/bash

# OnlineUsta Database Setup Script
echo "🚀 OnlineUsta Database Setup başlatılıyor..."

# Environment dosyası oluştur
echo "📁 Environment dosyası oluşturuluyor..."
cp env.example .env.local

# Database URL'yi güncelle
echo "🔧 Database URL güncelleniyor..."
if [[ "$OSTYPE" == "darwin"* ]]; then
  # macOS
  sed -i '' 's|postgresql://username:password@localhost:5432/onlineusta_dev|postgresql://postgres:password123@localhost:5432/onlineusta_dev|g' .env.local
else
  # Linux
  sed -i 's|postgresql://username:password@localhost:5432/onlineusta_dev|postgresql://postgres:password123@localhost:5432/onlineusta_dev|g' .env.local
fi

# Docker kontrol
if ! command -v docker &> /dev/null; then
    echo "❌ Docker kurulu değil!"
    echo "🔗 Docker Desktop'ı indirin: https://www.docker.com/products/docker-desktop"
    echo ""
    echo "Docker kurulduktan sonra bu script'i tekrar çalıştırın:"
    echo "chmod +x setup-database.sh && ./setup-database.sh"
    exit 1
fi

# Docker Compose kontrol
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose kurulu değil!"
    echo "Docker Desktop ile birlikte gelir, Docker'ı yeniden kurun."
    exit 1
fi

# Container'ları başlat
echo "🐳 Docker container'ları başlatılıyor..."
docker-compose up -d

# Container'ların başlamasını bekle
echo "⏳ PostgreSQL'in başlaması bekleniyor..."
sleep 10

# Database migrations
echo "🔄 Database migrations çalıştırılıyor..."
cd packages/database
pnpm run db:push

# Seed data
echo "🌱 Seed data ekleniyor..."
pnpm run db:generate
npx tsx prisma/seed.ts

cd ../..

echo "✅ Database setup tamamlandı!"
echo ""
echo "🎯 Kullanılabilir servisler:"
echo "• PostgreSQL: localhost:5432"
echo "• Redis: localhost:6379" 
echo "• PgAdmin: http://localhost:8080 (admin@onlineusta.com / admin123)"
echo ""
echo "📱 Uygulamayı başlatmak için:"
echo "pnpm dev" 