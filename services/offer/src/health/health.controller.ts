// Açıklama: Health check controller
import { Controller, Get } from '@nestjs/common';
import { ApiTags, ApiOperation } from '@nestjs/swagger';

@ApiTags('health')
@Controller('health')
export class HealthController {
  @Get()
  @ApiOperation({ summary: 'Service health check' })
  getHealth() {
    return {
      status: 'OK',
      timestamp: new Date().toISOString(),
      service: 'offer-service',
      version: '0.1.0',
    };
  }

  @Get('ready')
  @ApiOperation({ summary: 'Service readiness check' })
  getReadiness() {
    return {
      status: 'READY',
      timestamp: new Date().toISOString(),
    };
  }
} 