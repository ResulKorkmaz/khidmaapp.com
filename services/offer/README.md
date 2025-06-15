# OnlineUsta Offer Service

Teklif yönetimi mikroservisi - WebSocket bildirimler ve NATS event sistemi ile.

## 🚀 Özellikler

- **Teklif Yönetimi**: CRUD operasyonları (oluştur, listele, güncelle)
- **Real-time Bildirimler**: WebSocket ile anlık bildirimler
- **Event-Driven Architecture**: NATS ile mikroservis iletişimi
- **API Documentation**: Swagger/OpenAPI dokümantasyonu
- **Health Checks**: Sistem durumu monitoring

## 🏗️ Teknoloji Stack'i

- **Framework**: NestJS 10.x
- **Database**: Prisma ORM
- **WebSocket**: Socket.io
- **Messaging**: NATS
- **Validation**: Class-validator & Zod
- **Documentation**: Swagger
- **TypeScript**: Strict mode

## 📦 Kurulum

### Gereksinimler
- Node.js 20+
- PostgreSQL 15+
- NATS Server 2.10+

### Development Ortamı

1. **Dependencies'i yükle:**
   \`\`\`bash
   pnpm install
   \`\`\`

2. **NATS ve diğer servisleri başlat:**
   \`\`\`bash
   docker-compose up -d
   \`\`\`

3. **Environment dosyasını oluştur:**
   \`\`\`bash
   cp .env.example .env
   \`\`\`

4. **Servisi başlat:**
   \`\`\`bash
   pnpm run start:dev
   \`\`\`

### Production Build

\`\`\`bash
pnpm run build
pnpm run start:prod
\`\`\`

## 📡 API Endpoints

### Teklif İşlemleri
- \`POST /api/v1/offers\` - Yeni teklif oluştur
- \`GET /api/v1/offers/service-request/:id\` - Talep tekliflerini getir
- \`GET /api/v1/offers/professional/:id\` - Profesyonel tekliflerini getir
- \`PATCH /api/v1/offers/:id/status\` - Teklif durumunu güncelle

### Bildirim Sistemi
- \`GET /api/v1/notifications/stats\` - Sistem istatistikleri
- \`GET /api/v1/notifications/health\` - Sağlık kontrolü

### Health Checks
- \`GET /api/v1/health\` - Genel sağlık durumu
- \`GET /api/v1/health/ready\` - Readiness probe

## 🔌 WebSocket Events

### Client → Server
- \`join-room\` - Odaya katıl (service-request:ID)
- \`leave-room\` - Odadan ayrıl

### Server → Client
- \`offer:created\` - Yeni teklif bildirimi
- \`offer:accepted\` - Teklif kabul bildirimi
- \`offer:rejected\` - Teklif red bildirimi
- \`offer:withdrawn\` - Teklif geri çekme bildirimi

### Bağlantı Örneği

\`\`\`javascript
import io from 'socket.io-client';

const socket = io('http://localhost:3001/notifications', {
  auth: {
    userId: 'user-123'
  }
});

// Service request odası
socket.emit('join-room', { room: 'service-request:req_123' });

// Bildirim dinle
socket.on('offer:created', (data) => {
  console.log('Yeni teklif:', data);
});
\`\`\`

## 📊 NATS Event Architecture

### Event Types
- \`OFFER_CREATED\` - Yeni teklif oluşturuldu
- \`OFFER_ACCEPTED\` - Teklif kabul edildi
- \`OFFER_REJECTED\` - Teklif reddedildi
- \`OFFER_WITHDRAWN\` - Teklif geri çekildi
- \`SERVICE_REQUEST_UPDATED\` - Hizmet talebi güncellendi

### Subject Pattern
\`\`\`
notifications.{event_type}
├── notifications.offer_created
├── notifications.offer_accepted
├── notifications.offer_rejected
├── notifications.offer_withdrawn
└── notifications.service_request_updated
\`\`\`

## 🛡️ Security

- **Input Validation**: Class-validator ile strict validation
- **Rate Limiting**: Throttler ile rate limiting
- **CORS**: Configurable CORS ayarları
- **Helmet**: Security headers

## 🧪 Testing

\`\`\`bash
# Unit testler
pnpm run test

# Test coverage
pnpm run test:cov

# E2E testler
pnpm run test:e2e
\`\`\`

## 🐳 Docker

\`\`\`bash
# Build image
docker build -t onlineusta/offer-service .

# Run container
docker run -p 3001:3001 onlineusta/offer-service
\`\`\`

## 📈 Monitoring

### Health Endpoints
- \`GET /health\` - Temel health check
- \`GET /health/ready\` - Readiness probe
- \`GET /notifications/stats\` - WebSocket istatistikleri

### NATS Monitoring
- NATS monitoring UI: \`http://localhost:8222\`

## 🔄 Development Workflow

1. **Feature geliştir**: Yeni özellik dalı oluştur
2. **Test yaz**: Unit ve integration testler
3. **Lint kontrol**: \`pnpm run lint\`
4. **Build kontrol**: \`pnpm run build\`
5. **PR oluştur**: Review için pull request

## 📝 Contributing

1. Fork the repository
2. Create feature branch (\`git checkout -b feature/amazing-feature\`)
3. Commit changes (\`git commit -m 'Add amazing feature'\`)
4. Push to branch (\`git push origin feature/amazing-feature\`)
5. Open Pull Request

## 📄 License

MIT License - Detaylar için [LICENSE](LICENSE) dosyasına bakın. 