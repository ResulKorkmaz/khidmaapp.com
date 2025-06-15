// Açıklama: Notification service - WebSocket ve NATS koordinasyonu
import { Injectable, Logger, OnModuleInit } from '@nestjs/common';
import { NotificationGateway } from './notification.gateway';
import { NatsService, NotificationEvent } from './nats/nats.service';

export interface OfferNotificationData {
  offerId: string;
  serviceRequestId: string;
  serviceRequestTitle: string;
  professional: {
    id: string;
    firstName: string;
    lastName: string;
    avatar?: string;
  };
  customer: {
    id: string;
    firstName: string;
    lastName: string;
  };
  price: number;
  status?: string;
}

@Injectable()
export class NotificationService implements OnModuleInit {
  private readonly logger = new Logger(NotificationService.name);

  constructor(
    private readonly notificationGateway: NotificationGateway,
    private readonly natsService: NatsService,
  ) {}

  async onModuleInit() {
    await this.setupEventSubscriptions();
  }

  private async setupEventSubscriptions() {
    try {
      // Subscribe to all notification events
      await this.natsService.subscribeToNotifications(
        'notifications.*',
        this.handleNotificationEvent.bind(this)
      );
      
      this.logger.log('📡 Notification event subscriptions setup complete');
    } catch (error) {
      this.logger.error('Failed to setup event subscriptions:', error);
    }
  }

  private async handleNotificationEvent(event: NotificationEvent) {
    try {
      this.logger.log(`🔔 Processing notification event: ${event.type} for user ${event.userId}`);

      // Send to WebSocket based on event type
      switch (event.type) {
        case 'OFFER_CREATED':
          await this.handleOfferCreated(event);
          break;
        case 'OFFER_ACCEPTED':
          await this.handleOfferAccepted(event);
          break;
        case 'OFFER_REJECTED':
          await this.handleOfferRejected(event);
          break;
        case 'OFFER_WITHDRAWN':
          await this.handleOfferWithdrawn(event);
          break;
        case 'SERVICE_REQUEST_UPDATED':
          await this.handleServiceRequestUpdated(event);
          break;
        default:
          this.logger.warn(`Unknown notification type: ${event.type}`);
      }
    } catch (error) {
      this.logger.error('Error handling notification event:', error);
    }
  }

  private async handleOfferCreated(event: NotificationEvent) {
    const data = event.data as OfferNotificationData;
    
    // Notify customer about new offer
    this.notificationGateway.sendToUser(data.customer.id, 'offer:created', {
      message: `${data.professional.firstName} ${data.professional.lastName} size ${data.price} TL'ye teklif gönderdi`,
      offer: data,
      timestamp: event.timestamp,
    });

    // Notify service request room
    this.notificationGateway.sendToRoom(
      `service-request:${data.serviceRequestId}`,
      'offer:created',
      {
        offer: data,
        timestamp: event.timestamp,
      }
    );
  }

  private async handleOfferAccepted(event: NotificationEvent) {
    const data = event.data as OfferNotificationData;

    // Notify professional about accepted offer
    this.notificationGateway.sendToUser(data.professional.id, 'offer:accepted', {
      message: `Teklifiniz kabul edildi! "${data.serviceRequestTitle}" işi için anlaşma sağlandı.`,
      offer: data,
      timestamp: event.timestamp,
    });

    // Notify service request room
    this.notificationGateway.sendToRoom(
      `service-request:${data.serviceRequestId}`,
      'offer:accepted',
      {
        offer: data,
        timestamp: event.timestamp,
      }
    );
  }

  private async handleOfferRejected(event: NotificationEvent) {
    const data = event.data as OfferNotificationData;

    // Notify professional about rejected offer
    this.notificationGateway.sendToUser(data.professional.id, 'offer:rejected', {
      message: `Teklifiniz reddedildi: "${data.serviceRequestTitle}"`,
      offer: data,
      timestamp: event.timestamp,
    });
  }

  private async handleOfferWithdrawn(event: NotificationEvent) {
    const data = event.data as OfferNotificationData;

    // Notify customer about withdrawn offer
    this.notificationGateway.sendToUser(data.customer.id, 'offer:withdrawn', {
      message: `${data.professional.firstName} ${data.professional.lastName} teklifini geri çekti`,
      offer: data,
      timestamp: event.timestamp,
    });

    // Notify service request room
    this.notificationGateway.sendToRoom(
      `service-request:${data.serviceRequestId}`,
      'offer:withdrawn',
      {
        offer: data,
        timestamp: event.timestamp,
      }
    );
  }

  private async handleServiceRequestUpdated(event: NotificationEvent) {
    const data = event.data;

    // Notify all interested parties about service request changes
    this.notificationGateway.sendToRoom(
      `service-request:${data.serviceRequestId}`,
      'service-request:updated',
      {
        serviceRequest: data,
        timestamp: event.timestamp,
      }
    );
  }

  // Public methods for triggering notifications
  async notifyOfferCreated(offerData: OfferNotificationData) {
    await this.natsService.publishUserNotification(
      offerData.customer.id,
      'OFFER_CREATED',
      offerData,
      {
        priority: 'high',
        category: 'offer',
      }
    );
  }

  async notifyOfferAccepted(offerData: OfferNotificationData) {
    await this.natsService.publishUserNotification(
      offerData.professional.id,
      'OFFER_ACCEPTED',
      offerData,
      {
        priority: 'high',
        category: 'offer',
      }
    );
  }

  async notifyOfferRejected(offerData: OfferNotificationData) {
    await this.natsService.publishUserNotification(
      offerData.professional.id,
      'OFFER_REJECTED',
      offerData,
      {
        priority: 'medium',
        category: 'offer',
      }
    );
  }

  async notifyOfferWithdrawn(offerData: OfferNotificationData) {
    await this.natsService.publishUserNotification(
      offerData.customer.id,
      'OFFER_WITHDRAWN',
      offerData,
      {
        priority: 'medium',
        category: 'offer',
      }
    );
  }

  async notifyServiceRequestUpdated(serviceRequestData: any) {
    // Get all involved users for this service request
    const involvedUsers = [
      serviceRequestData.customerId,
      ...(serviceRequestData.offers?.map((offer: any) => offer.professionalId) || [])
    ];

    for (const userId of involvedUsers) {
      await this.natsService.publishUserNotification(
        userId,
        'SERVICE_REQUEST_UPDATED',
        serviceRequestData,
        {
          priority: 'medium',
          category: 'service-request',
        }
      );
    }
  }

  // Health check method
  async getNotificationStats() {
    return {
      natsConnected: this.natsService.isConnected,
      webSocketConnections: this.notificationGateway.getConnectionCount(),
      timestamp: new Date().toISOString(),
    };
  }
} 