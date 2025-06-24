# 🏗️ OnlineUsta Modular Architecture

## 📋 Overview

OnlineUsta'nın modüler mimarisi Next.js frontend/API-gateway ile Laravel mikroservis backend'ini birleştirerek yüksek performanslı, ölçeklenebilir bir marketplace platformu oluşturur.

## 🎯 Architecture Principles

### **Separation of Concerns**
- **Next.js**: Presentation layer, user authentication, API routing
- **Laravel**: Business logic, background jobs, payments, integrations

### **Scalability**
- Independent scaling of frontend and backend services
- Queue-based asynchronous processing
- Microservice communication via REST/GraphQL

### **Reliability**
- Background job processing with retry mechanisms
- Distributed caching (Redis)
- Database replication and backup strategies

---

## 🏛️ System Architecture

### **Core Components**

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Web Frontend  │    │   API Gateway   │    │ Laravel Service │
│    (Next.js)    │◄──►│   (Next.js)     │◄──►│  (Microservice) │
│                 │    │                 │    │                 │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Static CDN    │    │   PostgreSQL    │    │   Redis Queue   │
│   (Vercel)      │    │   (Database)    │    │   (Background)  │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

### **Service Boundaries**

| Service | Responsibility | Technology |
|---------|---------------|------------|
| **Frontend** | UI/UX, SSR, Client routing | Next.js 14, React, TailwindCSS |
| **API Gateway** | Request routing, Auth, Rate limiting | Next.js API Routes |
| **Core Service** | Business logic, Database operations | Laravel 10, Eloquent |
| **Job Queue** | Background tasks, Email, Notifications | Laravel Queues, Redis |
| **Payment Service** | Stripe Connect, Transfers, Webhooks | Laravel, Stripe SDK |

---

## 🔄 Service Communication

### **Request Flow**

1. **User Request** → Next.js Frontend
2. **API Call** → Next.js API Gateway
3. **Service Request** → Laravel Microservice
4. **Database Query** → PostgreSQL
5. **Queue Job** → Redis (if async needed)
6. **Response** → Back through the chain

### **Authentication Flow**

```typescript
// Next.js API Gateway
export async function middleware(request: NextRequest) {
  const token = request.cookies.get('auth-token');
  
  // Verify JWT locally or with Laravel
  const user = await verifyToken(token);
  
  // Add user context to headers
  request.headers.set('X-User-ID', user.id);
  request.headers.set('X-User-Role', user.role);
}
```

---

## 📡 API Design

### **Next.js API Gateway Routes**

```typescript
// pages/api/v1/[...slug].ts - Universal proxy
export default async function handler(req: NextRequest) {
  const { slug } = req.query;
  const path = Array.isArray(slug) ? slug.join('/') : slug;
  
  // Route to appropriate Laravel endpoint
  const response = await fetch(`${LARAVEL_API_URL}/api/v1/${path}`, {
    method: req.method,
    headers: {
      'Authorization': req.headers.authorization,
      'X-User-ID': req.headers['x-user-id'],
      'Content-Type': 'application/json',
    },
    body: req.method !== 'GET' ? JSON.stringify(req.body) : undefined,
  });
  
  return response.json();
}
```

### **Laravel API Endpoints**

```php
// routes/api.php
Route::prefix('v1')->middleware(['auth:api', 'throttle:60,1'])->group(function () {
    // Service Requests
    Route::apiResource('service-requests', ServiceRequestController::class);
    Route::post('service-requests/{id}/offers', [OfferController::class, 'store']);
    
    // Professionals
    Route::apiResource('professionals', ProfessionalController::class);
    Route::post('professionals/{id}/verify', [ProfessionalController::class, 'verify']);
    
    // Payments
    Route::post('payments/stripe-connect', [PaymentController::class, 'connectStripe']);
    Route::post('payments/transfer', [PaymentController::class, 'transfer']);
    
    // Background Jobs
    Route::post('jobs/send-notifications', [NotificationController::class, 'queue']);
    Route::post('jobs/process-reviews', [ReviewController::class, 'processAsync']);
});
```

---

## ⚙️ Background Job System

### **Laravel Queue Configuration**

```php
// config/queue.php
'connections' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
        'block_for' => null,
    ],
],

// Job Classes
class ProcessStripeTransfer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function handle()
    {
        // Process Stripe Connect transfer
        $stripe = new StripeService();
        $stripe->transferToProfessional($this->offer);
        
        // Send notifications
        SendNotification::dispatch($this->offer->professional, 'payment_received');
    }
}
```

### **Cron Job Schedule**

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Daily cleanup
    $schedule->command('cleanup:expired-offers')->daily();
    
    // Weekly reports
    $schedule->command('reports:weekly-stats')->weeklyOn(1, '09:00');
    
    // Hourly notification processing
    $schedule->command('notifications:process')->hourly();
    
    // Every 10 minutes: check pending payments
    $schedule->command('payments:check-pending')->everyTenMinutes();
    
    // Daily Stripe reconciliation
    $schedule->command('stripe:reconcile')->dailyAt('02:00');
}
```

---

## 💳 Stripe Connect Integration

### **Professional Onboarding**

```php
// app/Services/StripeService.php
class StripeService
{
    public function createConnectedAccount(Professional $professional)
    {
        $account = $this->stripe->accounts->create([
            'type' => 'express',
            'country' => 'TR',
            'email' => $professional->email,
            'business_type' => 'individual',
            'metadata' => [
                'professional_id' => $professional->id,
                'platform' => 'onlineusta'
            ]
        ]);
        
        $professional->update(['stripe_account_id' => $account->id]);
        
        return $account;
    }
    
    public function createOnboardingLink(Professional $professional)
    {
        return $this->stripe->accountLinks->create([
            'account' => $professional->stripe_account_id,
            'refresh_url' => config('app.url') . '/stripe/reauth',
            'return_url' => config('app.url') . '/dashboard/payments',
            'type' => 'account_onboarding',
        ]);
    }
}
```

### **Payment Processing**

```php
// app/Jobs/ProcessPayment.php
class ProcessPayment implements ShouldQueue
{
    public function handle()
    {
        $paymentIntent = $this->stripe->paymentIntents->create([
            'amount' => $this->offer->price * 100, // cents
            'currency' => 'try',
            'application_fee_amount' => $this->calculatePlatformFee(),
            'transfer_data' => [
                'destination' => $this->offer->professional->stripe_account_id,
            ],
            'metadata' => [
                'offer_id' => $this->offer->id,
                'service_request_id' => $this->offer->service_request_id,
            ]
        ]);
        
        // Update offer status
        $this->offer->update(['payment_intent_id' => $paymentIntent->id]);
        
        // Queue notification
        SendNotification::dispatch($this->offer->customer, 'payment_processing');
    }
}
```

---

## 🗄️ Database Strategy

### **Database Architecture**

```sql
-- Shared Database (PostgreSQL)
-- Both Next.js and Laravel connect to same DB
-- Laravel handles complex queries and transactions
-- Next.js handles simple reads for SSR

-- Connection Pooling
-- Laravel: Database connection pool (20 connections)
-- Next.js: Lightweight connection pool (5 connections)
```

### **Data Access Patterns**

```php
// Laravel: Complex business logic
class OfferService
{
    public function createOfferWithNotifications(array $data)
    {
        DB::transaction(function () use ($data) {
            $offer = Offer::create($data);
            
            // Queue notifications
            SendNotification::dispatch($offer->customer, 'new_offer', $offer);
            
            // Update service request stats
            $offer->serviceRequest->increment('offer_count');
            
            // Professional analytics
            Analytics::track('offer_created', $offer->professional);
            
            return $offer;
        });
    }
}
```

```typescript
// Next.js: Simple data fetching for SSR
export async function getServerSideProps() {
  const offers = await prisma.offer.findMany({
    where: { status: 'PUBLISHED' },
    include: { professional: true, serviceRequest: true },
    take: 10
  });
  
  return { props: { offers } };
}
```

---

## 📊 Monitoring & Observability

### **Health Checks**

```php
// Laravel Health Endpoint
Route::get('/health', function () {
    return [
        'status' => 'healthy',
        'timestamp' => now(),
        'services' => [
            'database' => DB::connection()->getPdo() ? 'up' : 'down',
            'redis' => Redis::ping() ? 'up' : 'down',
            'stripe' => app(StripeService::class)->ping() ? 'up' : 'down',
        ],
        'queue_size' => Queue::size(),
        'memory_usage' => memory_get_usage(true),
    ];
});
```

### **Logging Strategy**

```php
// config/logging.php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'sentry'],
    ],
    
    'sentry' => [
        'driver' => 'sentry',
        'level' => 'error',
    ],
    
    'payment_logs' => [
        'driver' => 'daily',
        'path' => storage_path('logs/payments.log'),
        'level' => 'info',
    ],
];
```

---

## 🚀 Deployment Architecture

### **Infrastructure Overview**

```yaml
# docker-compose.production.yml
version: '3.8'

services:
  # Next.js Frontend (Vercel)
  # - Automatic deployments
  # - Edge functions
  # - CDN distribution
  
  # Laravel API (Railway/DigitalOcean)
  laravel-api:
    image: onlineusta/laravel-api:latest
    environment:
      - APP_ENV=production
      - DB_CONNECTION=pgsql
      - REDIS_URL=${REDIS_URL}
      - STRIPE_SECRET=${STRIPE_SECRET}
    deploy:
      replicas: 3
      update_config:
        parallelism: 1
        delay: 30s
      restart_policy:
        condition: on-failure
        delay: 5s
        max_attempts: 3
  
  # Queue Workers
  laravel-worker:
    image: onlineusta/laravel-api:latest
    command: php artisan queue:work --sleep=3 --tries=3
    deploy:
      replicas: 2
  
  # Scheduled Tasks
  laravel-scheduler:
    image: onlineusta/laravel-api:latest
    command: php artisan schedule:work
    deploy:
      replicas: 1

  # Redis
  redis:
    image: redis:7-alpine
    command: redis-server --appendonly yes
    volumes:
      - redis_data:/data

  # PostgreSQL
  postgres:
    image: postgres:15
    environment:
      POSTGRES_DB: onlineusta_prod
      POSTGRES_USER: ${DB_USER}
      POSTGRES_PASSWORD: ${DB_PASSWORD}
    volumes:
      - postgres_data:/var/lib/postgresql/data

volumes:
  redis_data:
  postgres_data:
```

### **Environment Configuration**

```bash
# Next.js (.env.production)
NEXT_PUBLIC_APP_URL=https://onlineusta.com.tr
NEXT_PUBLIC_API_URL=https://api.onlineusta.com.tr
NEXTAUTH_URL=https://onlineusta.com.tr
NEXTAUTH_SECRET=${NEXTAUTH_SECRET}

# Laravel (.env.production)
APP_ENV=production
APP_URL=https://api.onlineusta.com.tr
DB_URL=${DATABASE_URL}
REDIS_URL=${REDIS_URL}
STRIPE_KEY=${STRIPE_SECRET_KEY}
STRIPE_WEBHOOK_SECRET=${STRIPE_WEBHOOK_SECRET}
QUEUE_CONNECTION=redis
```

---

## 📈 Performance Optimization

### **Caching Strategy**

```php
// Laravel Caching
class ServiceRequestService
{
    public function getFeaturedRequests()
    {
        return Cache::remember('featured_requests', 3600, function () {
            return ServiceRequest::with(['category', 'customer'])
                ->where('status', 'PUBLISHED')
                ->where('featured', true)
                ->limit(10)
                ->get();
        });
    }
}
```

```typescript
// Next.js Caching
export const getStaticProps: GetStaticProps = async () => {
  const categories = await fetch(`${API_URL}/categories`);
  
  return {
    props: { categories },
    revalidate: 3600, // ISR - revalidate every hour
  };
};
```

### **Database Optimization**

```sql
-- Database Indexes
CREATE INDEX idx_service_requests_status_city ON service_requests(status, city);
CREATE INDEX idx_offers_professional_status ON offers(professional_id, status);
CREATE INDEX idx_users_role_city ON users(role, city) WHERE role = 'PROFESSIONAL';

-- Materialized Views for Analytics
CREATE MATERIALIZED VIEW professional_stats AS
SELECT 
    professional_id,
    COUNT(*) as total_offers,
    AVG(price) as avg_price,
    COUNT(CASE WHEN status = 'ACCEPTED' THEN 1 END) as accepted_offers
FROM offers 
GROUP BY professional_id;
```

---

## 🔐 Security Implementation

### **API Security**

```php
// Laravel Middleware
class RateLimitMiddleware
{
    public function handle($request, Closure $next)
    {
        $key = 'api_limit:' . $request->ip();
        
        if (Redis::get($key) >= 100) {
            return response()->json(['error' => 'Rate limit exceeded'], 429);
        }
        
        Redis::incr($key);
        Redis::expire($key, 3600);
        
        return $next($request);
    }
}
```

### **Data Validation**

```php
// Laravel Form Requests
class CreateOfferRequest extends FormRequest
{
    public function rules()
    {
        return [
            'service_request_id' => 'required|exists:service_requests,id',
            'price' => 'required|numeric|min:10|max:50000',
            'description' => 'required|string|min:20|max:1000',
            'estimated_duration' => 'required|integer|min:1|max:168',
        ];
    }
}
```

---

## 📊 System Flow Sequence Diagram

Sistemin işleyişini gösteren detaylı sequence diagram yukarıda gösterilmiştir. Bu diagram şu ana akışları kapsar:

1. **Service Request Creation**: Kullanıcı talep oluşturma süreci
2. **Professional Offer Flow**: Profesyonel teklif verme süreci
3. **Payment Processing**: Ödeme işleme süreci
4. **Background Job Processing**: Arka plan iş süreçleri
5. **Scheduled Tasks**: Zamanlanmış görevler

Her akış, Next.js frontend'den başlayarak Laravel mikroservis üzerinden veritabanı ve external servislerle olan etkileşimi gösterir.

---

## 🧪 Testing Strategy

### **Laravel Testing**

```php
// tests/Feature/OfferCreationTest.php
class OfferCreationTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_professional_can_create_offer()
    {
        $professional = User::factory()->professional()->create();
        $serviceRequest = ServiceRequest::factory()->create();
        
        $response = $this->actingAs($professional)
            ->postJson('/api/v1/offers', [
                'service_request_id' => $serviceRequest->id,
                'price' => 150,
                'description' => 'Professional service offer',
            ]);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('offers', [
            'professional_id' => $professional->id,
            'service_request_id' => $serviceRequest->id,
        ]);
    }
}
```

### **Next.js Testing**

```typescript
// __tests__/api/offers.test.ts
import { createMocks } from 'node-mocks-http';
import handler from '@/pages/api/offers';

describe('/api/offers', () => {
  it('proxies request to Laravel API', async () => {
    const { req, res } = createMocks({
      method: 'GET',
      headers: { authorization: 'Bearer token123' },
    });

    await handler(req, res);

    expect(res._getStatusCode()).toBe(200);
    expect(JSON.parse(res._getData())).toHaveProperty('data');
  });
});
```

---

## 📋 Deployment Checklist

Detaylı deployment checklist `docs/deployment-checklist.md` dosyasında bulunabilir. Bu checklist şunları içerir:

### **Pre-Deployment**
- Database ve Redis kurulumu
- Environment variables konfigürasyonu
- SSL ve security konfigürasyonu

### **Infrastructure Deployment**
- Next.js frontend deployment (Vercel)
- Laravel API deployment (Railway/DigitalOcean)
- Background services konfigürasyonu

### **Production Readiness**
- Monitoring ve observability
- Payment system setup
- Testing ve quality assurance
- Go-live prosedürleri

**🚀 [Deployment Checklist'e Git →](./deployment-checklist.md)** 