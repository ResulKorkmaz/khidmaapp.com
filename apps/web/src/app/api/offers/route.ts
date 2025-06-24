import { NextRequest, NextResponse } from 'next/server';
import { PrismaClient } from '@onlineusta/database';

const prisma = new PrismaClient();

export async function GET(request: NextRequest) {
  try {
    const { searchParams } = new URL(request.url);
    const userId = searchParams.get('userId');
    const limit = searchParams.get('limit');

    if (!userId) {
      return NextResponse.json(
        { success: false, error: 'Kullanıcı ID gerekli' },
        { status: 400 }
      );
    }

    console.log('Fetching offers for user:', userId);

    // Mock data for now - gerçek implementation gelecekte eklenecek
    const mockOffers = [
      {
        id: '1',
        serviceRequestId: 'req1',
        amount: 1500,
        description: 'Evinizin elektrik tesisatını güvenli ve profesyonel şekilde yapabilirim. 10 yıllık deneyimim var.',
        status: 'PENDING',
        createdAt: new Date().toISOString(),
        serviceRequest: {
          id: 'req1',
          title: 'Ev Elektrik Tesisatı',
          description: 'Yeni taşındığım evde elektrik tesisatı yaptırmak istiyorum.',
          category: 'Elektrikçi',
          city: 'İstanbul',
          district: 'Kadıköy',
          budget: 2000,
          status: 'ACTIVE',
          createdAt: new Date(Date.now() - 2 * 24 * 60 * 60 * 1000).toISOString()
        }
      },
      {
        id: '2',
        serviceRequestId: 'req2',
        amount: 800,
        description: 'Tesisat sorunlarınızı hızlı ve kaliteli şekilde çözebilirim.',
        status: 'ACCEPTED',
        createdAt: new Date(Date.now() - 5 * 24 * 60 * 60 * 1000).toISOString(),
        serviceRequest: {
          id: 'req2',
          title: 'Banyo Tesisat Tamiri',
          description: 'Banyo musluğu akıyor, tamir edilmesi gerekiyor.',
          category: 'Tesisatçı',
          city: 'İstanbul',
          district: 'Beşiktaş',
          budget: 1000,
          status: 'COMPLETED',
          createdAt: new Date(Date.now() - 7 * 24 * 60 * 60 * 1000).toISOString()
        }
      },
      {
        id: '3',
        serviceRequestId: 'req3',
        amount: 2500,
        description: 'Komple boyama işini profesyonel ekibimle birlikte yapabiliriz.',
        status: 'REJECTED',
        createdAt: new Date(Date.now() - 10 * 24 * 60 * 60 * 1000).toISOString(),
        serviceRequest: {
          id: 'req3',
          title: 'Daire Boyama İşi',
          description: '3+1 dairenin tamamının boyanması gerekiyor.',
          category: 'Boyacı',
          city: 'İstanbul',
          district: 'Şişli',
          budget: 3000,
          status: 'COMPLETED',
          createdAt: new Date(Date.now() - 12 * 24 * 60 * 60 * 1000).toISOString()
        }
      }
    ];

    // Limit uygula
    const limitNum = limit ? parseInt(limit) : mockOffers.length;
    const limitedOffers = mockOffers.slice(0, limitNum);

    console.log(`Returning ${limitedOffers.length} offers for user ${userId}`);

    return NextResponse.json({
      success: true,
      offers: limitedOffers,
      total: mockOffers.length
    });

  } catch (error) {
    console.error('Offers fetch error:', error);
    return NextResponse.json(
      { success: false, error: 'Teklifler getirilirken hata oluştu' },
      { status: 500 }
    );
  }
} 