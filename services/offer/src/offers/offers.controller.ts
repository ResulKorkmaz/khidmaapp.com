// Açıklama: Offers controller - teklif API endpoints
import { Controller, Post, Get, Patch, Body, Param, Query, UseGuards, Req } from '@nestjs/common';
import { ApiTags, ApiOperation, ApiResponse, ApiBearerAuth, ApiQuery } from '@nestjs/swagger';
import { OffersService } from './offers.service';
import { CreateOfferDto } from './dto/create-offer.dto';

@ApiTags('offers')
@Controller('offers')
@ApiBearerAuth()
export class OffersController {
  constructor(private readonly offersService: OffersService) {}

  @Post()
  @ApiOperation({ summary: 'Yeni teklif oluştur' })
  @ApiResponse({ status: 201, description: 'Teklif başarıyla oluşturuldu' })
  @ApiResponse({ status: 400, description: 'Geçersiz veri' })
  @ApiResponse({ status: 404, description: 'Hizmet talebi bulunamadı' })
  async createOffer(
    @Body() createOfferDto: CreateOfferDto,
    @Req() req: any // TODO: Replace with proper auth guard
  ) {
    // TODO: Get professional ID from JWT token
    const professionalId = req.headers['x-user-id'] || 'temp-professional-id';
    return this.offersService.createOffer(createOfferDto, professionalId);
  }

  @Get('service-request/:serviceRequestId')
  @ApiOperation({ summary: 'Hizmet talebine ait teklifleri getir' })
  @ApiResponse({ status: 200, description: 'Teklifler başarıyla getirildi' })
  @ApiQuery({ name: 'page', required: false, description: 'Sayfa numarası' })
  @ApiQuery({ name: 'limit', required: false, description: 'Sayfa başına kayıt sayısı' })
  async getOffersByServiceRequest(
    @Param('serviceRequestId') serviceRequestId: string,
    @Query('page') page = 1,
    @Query('limit') limit = 10
  ) {
    return this.offersService.getOffersByServiceRequest(
      serviceRequestId,
      Number(page),
      Number(limit)
    );
  }

  @Get('professional/:professionalId')
  @ApiOperation({ summary: 'Profesyonelin tekliflerini getir' })
  @ApiResponse({ status: 200, description: 'Teklifler başarıyla getirildi' })
  @ApiQuery({ name: 'page', required: false, description: 'Sayfa numarası' })
  @ApiQuery({ name: 'limit', required: false, description: 'Sayfa başına kayıt sayısı' })
  async getOffersByProfessional(
    @Param('professionalId') professionalId: string,
    @Query('page') page = 1,
    @Query('limit') limit = 10
  ) {
    return this.offersService.getOffersByProfessional(
      professionalId,
      Number(page),
      Number(limit)
    );
  }

  @Patch(':offerId/status')
  @ApiOperation({ summary: 'Teklif durumunu güncelle' })
  @ApiResponse({ status: 200, description: 'Teklif durumu başarıyla güncellendi' })
  @ApiResponse({ status: 404, description: 'Teklif bulunamadı' })
  @ApiResponse({ status: 403, description: 'Bu işlem için yetkiniz yok' })
  async updateOfferStatus(
    @Param('offerId') offerId: string,
    @Body() body: { status: 'ACCEPTED' | 'REJECTED' | 'WITHDRAWN' },
    @Req() req: any // TODO: Replace with proper auth guard
  ) {
    // TODO: Get user ID from JWT token
    const userId = req.headers['x-user-id'] || 'temp-user-id';
    return this.offersService.updateOfferStatus(offerId, body.status, userId);
  }
} 