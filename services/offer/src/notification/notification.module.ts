// Açıklama: Notification modül - WebSocket ve NATS bildirim sistemi
import { Module } from '@nestjs/common';
import { NotificationGateway } from './notification.gateway';
import { NotificationService } from './notification.service';
import { NotificationController } from './notification.controller';
import { NatsModule } from './nats/nats.module';

@Module({
  imports: [NatsModule],
  controllers: [NotificationController],
  providers: [NotificationGateway, NotificationService],
  exports: [NotificationService],
})
export class NotificationModule {} 