// Açıklama: Offer mikroservisi main entry point
import { NestFactory } from '@nestjs/core';
import { ValidationPipe, Logger } from '@nestjs/common';
import { SwaggerModule, DocumentBuilder } from '@nestjs/swagger';
import helmet from 'helmet';
import { AppModule } from './app.module';

async function bootstrap() {
  const logger = new Logger('Bootstrap');
  
  const app = await NestFactory.create(AppModule, {
    logger: ['error', 'warn', 'log', 'debug'],
  });

  // Security
  app.use(helmet());
  
  // Global validation pipe
  app.useGlobalPipes(
    new ValidationPipe({
      whitelist: true,
      forbidNonWhitelisted: true,
      transform: true,
      transformOptions: {
        enableImplicitConversion: true,
      },
    }),
  );

  // CORS
  app.enableCors({
    origin: process.env.ALLOWED_ORIGINS?.split(',') || ['http://localhost:3000'],
    credentials: true,
  });

  // API Prefix
  app.setGlobalPrefix('api/v1');

  // Swagger Documentation
  if (process.env.NODE_ENV !== 'production') {
    const config = new DocumentBuilder()
      .setTitle('OnlineUsta Offer Service')
      .setDescription('Teklif yönetimi mikroservisi API dokümantasyonu')
      .setVersion('1.0')
      .addBearerAuth()
      .addTag('offers', 'Teklif işlemleri')
      .addTag('notifications', 'Bildirim işlemleri')
      .build();

    const document = SwaggerModule.createDocument(app, config);
    SwaggerModule.setup('docs', app, document, {
      swaggerOptions: {
        persistAuthorization: true,
      },
    });
  }

  const port = process.env.PORT || 3001;
  await app.listen(port);
  
  logger.log(`🚀 Offer Service started on http://localhost:${port}`);
  logger.log(`📚 API Docs available at http://localhost:${port}/docs`);
}

bootstrap().catch((error) => {
  console.error('❌ Failed to start Offer Service:', error);
  process.exit(1);
}); 