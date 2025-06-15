// Açıklama: NATS service - event-driven mikroservis iletişimi
import { Injectable, Logger, OnModuleDestroy } from '@nestjs/common';
import { ConfigService } from '@nestjs/config';
import { connect, NatsConnection, StringCodec, JSONCodec } from 'nats';

export interface NotificationEvent {
  type: 'OFFER_CREATED' | 'OFFER_ACCEPTED' | 'OFFER_REJECTED' | 'OFFER_WITHDRAWN' | 'SERVICE_REQUEST_UPDATED';
  userId: string;
  data: any;
  timestamp: string;
  metadata?: Record<string, any>;
}

@Injectable()
export class NatsService implements OnModuleDestroy {
  private readonly logger = new Logger(NatsService.name);
  private connection: NatsConnection | null = null;
  private readonly stringCodec = StringCodec();
  private readonly jsonCodec = JSONCodec();

  constructor(private readonly configService: ConfigService) {}

  async connect(): Promise<void> {
    try {
      const natsUrl = this.configService.get<string>('NATS_URL', 'nats://localhost:4222');
      
      this.connection = await connect({
        servers: [natsUrl],
        name: 'offer-service',
        maxReconnectAttempts: 10,
        reconnectTimeWait: 2000,
      });

      this.logger.log(`✅ Connected to NATS server: ${natsUrl}`);
      this.setupConnectionHandlers();
    } catch (error) {
      this.logger.error('❌ Failed to connect to NATS:', error);
      throw error;
    }
  }

  private setupConnectionHandlers(): void {
    if (!this.connection) return;

    this.connection.closed().then((err) => {
      if (err) {
        this.logger.error('NATS connection closed with error:', err);
      } else {
        this.logger.log('NATS connection closed gracefully');
      }
    });
  }

  async publishNotification(event: NotificationEvent): Promise<void> {
    if (!this.connection) {
      this.logger.warn('NATS connection not available, skipping publish');
      return;
    }

    try {
      const subject = `notifications.${event.type.toLowerCase()}`;
      const payload = {
        ...event,
        timestamp: new Date().toISOString(),
      };

      await this.connection.publish(subject, this.jsonCodec.encode(payload));
      this.logger.log(`📤 Published event: ${event.type} to ${subject}`);
    } catch (error) {
      this.logger.error('Failed to publish notification:', error);
      throw error;
    }
  }

  async subscribeToNotifications(
    pattern: string,
    handler: (event: NotificationEvent) => Promise<void>
  ): Promise<void> {
    if (!this.connection) {
      this.logger.warn('NATS connection not available, skipping subscription');
      return;
    }

    try {
      const subscription = this.connection.subscribe(pattern);
      this.logger.log(`📥 Subscribed to pattern: ${pattern}`);

      for await (const message of subscription) {
        try {
          const event = this.jsonCodec.decode(message.data) as NotificationEvent;
          await handler(event);
        } catch (error) {
          this.logger.error('Error processing notification:', error);
        }
      }
    } catch (error) {
      this.logger.error('Failed to setup subscription:', error);
      throw error;
    }
  }

  async publishUserNotification(
    userId: string,
    type: NotificationEvent['type'],
    data: any,
    metadata?: Record<string, any>
  ): Promise<void> {
    await this.publishNotification({
      type,
      userId,
      data,
      timestamp: new Date().toISOString(),
      metadata,
    });
  }

  async request<T = any>(subject: string, data: any, timeout = 5000): Promise<T> {
    if (!this.connection) {
      throw new Error('NATS connection not available');
    }

    try {
      const response = await this.connection.request(
        subject,
        this.jsonCodec.encode(data),
        { timeout }
      );
      
      return this.jsonCodec.decode(response.data) as T;
    } catch (error) {
      this.logger.error(`Request failed for subject ${subject}:`, error);
      throw error;
    }
  }

  async onModuleDestroy(): Promise<void> {
    if (this.connection) {
      await this.connection.close();
      this.logger.log('📴 NATS connection closed');
    }
  }

  get isConnected(): boolean {
    return this.connection !== null && !this.connection.isClosed();
  }
} 