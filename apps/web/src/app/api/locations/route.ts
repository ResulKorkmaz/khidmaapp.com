import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@onlineusta/database';

export async function GET(request: NextRequest) {
  try {
    const { searchParams } = new URL(request.url);
    const cityId = searchParams.get('cityId');
    
    if (cityId) {
      // Belirli şehrin ilçelerini getir
      const districts = await prisma.district.findMany({
        where: {
          cityId: parseInt(cityId),
          isActive: true
        },
        orderBy: {
          name: 'asc'
        },
        select: {
          id: true,
          name: true,
          slug: true
        }
      });
      
      return NextResponse.json({
        success: true,
        districts
      });
    } else {
      // Tüm şehirleri getir
      const cities = await prisma.city.findMany({
        where: {
          isActive: true
        },
        orderBy: {
          name: 'asc'
        },
        select: {
          id: true,
          name: true,
          slug: true,
          plateCode: true,
          region: true
        }
      });
      
      return NextResponse.json({
        success: true,
        cities
      });
    }
  } catch (error) {
    console.error('Locations API error:', error);
    return NextResponse.json(
      { success: false, error: 'Konum verileri alınamadı' },
      { status: 500 }
    );
  }
} 