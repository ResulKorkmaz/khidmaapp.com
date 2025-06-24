import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@onlineusta/database';

export async function GET(request: NextRequest) {
  try {
    const { searchParams } = new URL(request.url);
    const parentId = searchParams.get('parentId');
    const search = searchParams.get('search');
    
    if (parentId) {
      // Belirli kategorinin alt kategorilerini getir
      const categories = await prisma.serviceCategory.findMany({
        where: {
          parentId: parseInt(parentId),
          isActive: true
        },
        orderBy: {
          order: 'asc'
        },
        select: {
          id: true,
          name: true,
          slug: true,
          description: true,
          icon: true,
          color: true
        }
      });
      
      return NextResponse.json({
        success: true,
        categories
      });
    } else if (search) {
      // Kategori arama
      const categories = await prisma.serviceCategory.findMany({
        where: {
          OR: [
            { name: { contains: search, mode: 'insensitive' } },
            { description: { contains: search, mode: 'insensitive' } }
          ],
          isActive: true
        },
        orderBy: {
          order: 'asc'
        },
        select: {
          id: true,
          name: true,
          slug: true,
          description: true,
          icon: true,
          color: true,
          parent: {
            select: {
              id: true,
              name: true,
              slug: true
            }
          }
        },
        take: 20
      });
      
      return NextResponse.json({
        success: true,
        categories
      });
    } else {
      // Ana kategorileri getir
      const categories = await prisma.serviceCategory.findMany({
        where: {
          parentId: null,
          isActive: true
        },
        orderBy: {
          order: 'asc'
        },
        select: {
          id: true,
          name: true,
          slug: true,
          description: true,
          icon: true,
          color: true,
          _count: {
            select: {
              children: true
            }
          }
        }
      });
      
      return NextResponse.json({
        success: true,
        categories
      });
    }
  } catch (error) {
    console.error('Categories API error:', error);
    return NextResponse.json(
      { success: false, error: 'Kategori verileri alınamadı' },
      { status: 500 }
    );
  }
} 