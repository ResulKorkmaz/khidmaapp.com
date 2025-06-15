// Açıklama: Notification controller - bildirim sistemi monitoring
import { Controller, Get } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiResponse } from '@nestjs/swagger';
import { NotificationService } from './notification.service';

@ApiTags('notifications')
@Controller('notifications')
export class NotificationController {
  constructor(private readonly notificationService: NotificationService) {}

  @Get('stats')
  @ApiOperation({ summary: 'Bildirim sistemi istatistikleri' })
  @ApiResponse({ status: 200, description: 'Sistem durumu başarıyla alındı' })
  async getNotificationStats() {
    return this.notificationService.getNotificationStats();
  }

  @Get('health')
  @ApiOperation({ summary: 'Bildirim sistemi sağlık kontrolü' })
  @ApiResponse({ status: 200, description: 'Sistem sağlıklı' })
  getHealth() {
    return {
      status: 'OK',
      service: 'notification-system',
      timestamp: new Date().toISOString(),
    };
  }
} 