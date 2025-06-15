// Açıklama: Offer mikroservisi ana modül
import { Module } from '@nestjs/common';
import { ConfigModule } from '@nestjs/config';
import { ThrottlerModule } from '@nestjs/throttler';
import { OffersModule } from './offers/offers.module';
import { NotificationModule } from './notification/notification.module';
import { DatabaseModule } from './database/database.module';
import { HealthModule } from './health/health.module';

@Module({
  imports: [
    // Environment configuration
    ConfigModule.forRoot({
      isGlobal: true,
      envFilePath: ['.env.local', '.env'],
    }),
    
    // Rate limiting
    ThrottlerModule.forRoot([
      {
        ttl: 60000, // 1 minute
        limit: 100, // 100 requests per minute
      },
    ]),

    // Database connection
    DatabaseModule,
    
    // Feature modules
    OffersModule,
    NotificationModule,
    HealthModule,
  ],
  controllers: [],
  providers: [],
})
export class AppModule {} 