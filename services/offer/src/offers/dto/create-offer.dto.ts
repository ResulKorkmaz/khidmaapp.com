// Açıklama: Teklif oluşturma DTO - validation ile
import { ApiProperty } from '@nestjs/swagger';
import { IsString, IsNumber, IsOptional, IsDateString, Min, Max, Length } from 'class-validator';
import { Transform } from 'class-transformer';

export class CreateOfferDto {
  @ApiProperty({
    description: 'Teklif fiyatı (TL)',
    example: 500,
    minimum: 1,
  })
  @IsNumber({ maxDecimalPlaces: 2 })
  @Min(1, { message: 'Fiyat en az 1 TL olmalıdır' })
  @Max(100000, { message: 'Fiyat en fazla 100.000 TL olabilir' })
  price: number;

  @ApiProperty({
    description: 'Teklif açıklaması',
    example: 'Profesyonel ev temizliği hizmeti sunuyorum. 5 yıllık deneyimim var.',
    minLength: 20,
    maxLength: 1000,
  })
  @IsString()
  @Length(20, 1000, { message: 'Açıklama 20-1000 karakter arasında olmalıdır' })
  description: string;

  @ApiProperty({
    description: 'Tahmini iş süresi (saat)',
    example: 4,
    required: false,
  })
  @IsOptional()
  @IsNumber()
  @Min(1, { message: 'Tahmini süre en az 1 saat olmalıdır' })
  @Max(168, { message: 'Tahmini süre en fazla 168 saat (1 hafta) olabilir' })
  estimatedDuration?: number;

  @ApiProperty({
    description: 'En erken başlama tarihi',
    example: '2024-01-20T09:00:00Z',
    required: false,
  })
  @IsOptional()
  @IsDateString()
  @Transform(({ value }) => value ? new Date(value).toISOString() : undefined)
  availableFrom?: string;

  @ApiProperty({
    description: 'Hizmet talebi ID',
    example: 'req_123456789',
  })
  @IsString()
  serviceRequestId: string;
} 