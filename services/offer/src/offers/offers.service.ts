// Açıklama: Offers service - teklif iş mantığı
import { Injectable, BadRequestException, NotFoundException, ForbiddenException, Logger } from '@nestjs/common';
import { DatabaseService } from '../database/database.service';
import { CreateOfferDto } from './dto/create-offer.dto';
import { OfferStatus } from '@onlineusta/database';
import { NotificationService } from '../notification/notification.service';

@Injectable()
export class OffersService {
  private readonly logger = new Logger(OffersService.name);

  constructor(
    private readonly db: DatabaseService,
    private readonly notificationService: NotificationService,
  ) {}

  async createOffer(createOfferDto: CreateOfferDto, professionalId: string) {
    try {
      // Check if service request exists and is published
      const serviceRequest = await this.db.client.serviceRequest.findUnique({
        where: { id: createOfferDto.serviceRequestId },
        include: {
          customer: true,
          category: true,
        },
      });

      if (!serviceRequest) {
        throw new NotFoundException('Hizmet talebi bulunamadı');
      }

      if (serviceRequest.status !== 'PUBLISHED') {
        throw new BadRequestException('Bu talep için teklif verilemez');
      }

      if (serviceRequest.customerId === professionalId) {
        throw new ForbiddenException('Kendi talebinize teklif veremezsiniz');
      }

      // Check if professional already has an offer for this request
      const existingOffer = await this.db.client.offer.findUnique({
        where: {
          serviceRequestId_professionalId: {
            serviceRequestId: createOfferDto.serviceRequestId,
            professionalId,
          },
        },
      });

      if (existingOffer) {
        throw new BadRequestException('Bu talep için zaten teklifiniz bulunmaktadır');
      }

      // Create the offer
      const offer = await this.db.client.offer.create({
        data: {
          ...createOfferDto,
          professionalId,
          availableFrom: createOfferDto.availableFrom 
            ? new Date(createOfferDto.availableFrom) 
            : undefined,
          expiresAt: new Date(Date.now() + 7 * 24 * 60 * 60 * 1000), // 7 days from now
        },
        include: {
          professional: {
            select: {
              id: true,
              firstName: true,
              lastName: true,
              avatar: true,
              rating: true,
              reviewCount: true,
              isVerified: true,
            },
          },
          serviceRequest: {
            select: {
              id: true,
              title: true,
              category: {
                select: {
                  name: true,
                  slug: true,
                },
              },
            },
          },
        },
      });

      this.logger.log(`New offer created: ${offer.id} for request ${serviceRequest.id}`);

      // Send notification about new offer
      await this.notificationService.notifyOfferCreated({
        offerId: offer.id,
        serviceRequestId: offer.serviceRequestId,
        serviceRequestTitle: serviceRequest.title,
        professional: {
          id: offer.professional.id,
          firstName: offer.professional.firstName,
          lastName: offer.professional.lastName,
          avatar: offer.professional.avatar,
        },
        customer: {
          id: serviceRequest.customer.id,
          firstName: serviceRequest.customer.firstName,
          lastName: serviceRequest.customer.lastName,
        },
        price: offer.price,
      });

      return {
        success: true,
        data: offer,
      };
    } catch (error) {
      this.logger.error('Error creating offer:', error);
      throw error;
    }
  }

  async getOffersByServiceRequest(serviceRequestId: string, page = 1, limit = 10) {
    try {
      const offset = (page - 1) * limit;

      const [offers, total] = await Promise.all([
        this.db.client.offer.findMany({
          where: { serviceRequestId },
          include: {
            professional: {
              select: {
                id: true,
                firstName: true,
                lastName: true,
                avatar: true,
                rating: true,
                reviewCount: true,
                isVerified: true,
                city: true,
                district: true,
              },
            },
          },
          orderBy: {
            createdAt: 'desc',
          },
          skip: offset,
          take: limit,
        }),
        this.db.client.offer.count({
          where: { serviceRequestId },
        }),
      ]);

      return {
        success: true,
        data: offers,
        pagination: {
          page,
          limit,
          total,
          totalPages: Math.ceil(total / limit),
        },
      };
    } catch (error) {
      this.logger.error('Error fetching offers:', error);
      throw error;
    }
  }

  async updateOfferStatus(offerId: string, status: OfferStatus, userId: string) {
    try {
      const offer = await this.db.client.offer.findUnique({
        where: { id: offerId },
        include: {
          serviceRequest: {
            include: {
              customer: true,
            },
          },
          professional: true,
        },
      });

      if (!offer) {
        throw new NotFoundException('Teklif bulunamadı');
      }

      // Only customer can accept/reject, only professional can withdraw
      if (status === 'ACCEPTED' || status === 'REJECTED') {
        if (offer.serviceRequest.customerId !== userId) {
          throw new ForbiddenException('Bu işlem için yetkiniz yok');
        }
      } else if (status === 'WITHDRAWN') {
        if (offer.professionalId !== userId) {
          throw new ForbiddenException('Sadece kendi teklifinizi geri çekebilirsiniz');
        }
      }

      // Update offer status
      const updatedOffer = await this.db.client.offer.update({
        where: { id: offerId },
        data: { status },
        include: {
          professional: {
            select: {
              id: true,
              firstName: true,
              lastName: true,
              avatar: true,
            },
          },
          serviceRequest: {
            select: {
              id: true,
              title: true,
            },
          },
        },
      });

      // If offer is accepted, update service request status and reject other offers
      if (status === 'ACCEPTED') {
        await Promise.all([
          // Update service request status
          this.db.client.serviceRequest.update({
            where: { id: offer.serviceRequestId },
            data: { status: 'IN_PROGRESS' },
          }),
          // Reject all other pending offers
          this.db.client.offer.updateMany({
            where: {
              serviceRequestId: offer.serviceRequestId,
              id: { not: offerId },
              status: 'PENDING',
            },
            data: { status: 'REJECTED' },
          }),
        ]);
      }

      this.logger.log(`Offer ${offerId} status updated to ${status}`);

      // Send notifications based on status change
      const notificationData = {
        offerId: updatedOffer.id,
        serviceRequestId: updatedOffer.serviceRequest.id,
        serviceRequestTitle: updatedOffer.serviceRequest.title,
        professional: {
          id: updatedOffer.professional.id,
          firstName: updatedOffer.professional.firstName,
          lastName: updatedOffer.professional.lastName,
          avatar: updatedOffer.professional.avatar,
        },
        customer: {
          id: offer.serviceRequest.customer.id,
          firstName: offer.serviceRequest.customer.firstName,
          lastName: offer.serviceRequest.customer.lastName,
        },
        price: updatedOffer.price,
        status,
      };

      switch (status) {
        case 'ACCEPTED':
          await this.notificationService.notifyOfferAccepted(notificationData);
          break;
        case 'REJECTED':
          await this.notificationService.notifyOfferRejected(notificationData);
          break;
        case 'WITHDRAWN':
          await this.notificationService.notifyOfferWithdrawn(notificationData);
          break;
      }

      return {
        success: true,
        data: updatedOffer,
      };
    } catch (error) {
      this.logger.error('Error updating offer status:', error);
      throw error;
    }
  }

  async getOffersByProfessional(professionalId: string, page = 1, limit = 10) {
    try {
      const offset = (page - 1) * limit;

      const [offers, total] = await Promise.all([
        this.db.client.offer.findMany({
          where: { professionalId },
          include: {
            serviceRequest: {
              select: {
                id: true,
                title: true,
                description: true,
                city: true,
                district: true,
                budget: true,
                status: true,
                createdAt: true,
                category: {
                  select: {
                    name: true,
                    slug: true,
                  },
                },
                customer: {
                  select: {
                    id: true,
                    firstName: true,
                    lastName: true,
                    avatar: true,
                  },
                },
              },
            },
          },
          orderBy: {
            createdAt: 'desc',
          },
          skip: offset,
          take: limit,
        }),
        this.db.client.offer.count({
          where: { professionalId },
        }),
      ]);

      return {
        success: true,
        data: offers,
        pagination: {
          page,
          limit,
          total,
          totalPages: Math.ceil(total / limit),
        },
      };
    } catch (error) {
      this.logger.error('Error fetching professional offers:', error);
      throw error;
    }
  }
} 