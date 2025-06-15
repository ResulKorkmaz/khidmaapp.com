// Açıklama: Database service - Prisma client wrapper
import { Injectable, OnModuleInit, Logger } from '@nestjs/common';
import { prisma } from '@onlineusta/database';

@Injectable()
export class DatabaseService implements OnModuleInit {
  private readonly logger = new Logger(DatabaseService.name);

  async onModuleInit() {
    try {
      await prisma.$connect();
      this.logger.log('✅ Database connected successfully');
    } catch (error) {
      this.logger.error('❌ Database connection failed:', error);
      throw error;
    }
  }

  get client() {
    return prisma;
  }

  async onModuleDestroy() {
    await prisma.$disconnect();
    this.logger.log('📴 Database disconnected');
  }
} 