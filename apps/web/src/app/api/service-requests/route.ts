// Açıklama: Service request API - CRUD operasyonları
import { NextRequest, NextResponse } from 'next/server';
// import { prisma } from '@onlineusta/database';
import { z } from 'zod';

// Validation schemas
const CreateServiceRequestSchema = z.object({
  categoryId: z.number(),
  title: z.string().min(5).max(100),
  description: z.string().min(10).max(1000),
  budget: z.number().positive().optional(),
  city: z.string().min(2).max(50),
  district: z.string().min(2).max(50),
  address: z.string().max(200).optional(),
  preferredDate: z.string().datetime().optional(),
  isFlexible: z.boolean().default(true),
  images: z.array(z.string().url()).default([]),
});

const GetServiceRequestsSchema = z.object({
  page: z.string().transform(Number).pipe(z.number().min(1)).default('1'),
  limit: z.string().transform(Number).pipe(z.number().min(1).max(100)).default('10'),
  category: z.string().optional(),
  city: z.string().optional(),
  status: z.enum(['DRAFT', 'PUBLISHED', 'IN_PROGRESS', 'COMPLETED', 'CANCELLED', 'EXPIRED']).optional(),
});

// Mock data for development
const mockServiceRequest = {
  id: 'req_001',
  title: 'Ev Genel Temizliği',
  description: '3+1 dairem için genel temizlik hizmeti',
  budget: 500,
  city: 'İstanbul',
  district: 'Kadıköy',
  status: 'PUBLISHED',
  createdAt: new Date().toISOString(),
  category: {
    id: 1,
    name: 'Ev Temizliği',
    slug: 'ev-temizligi',
  },
  customer: {
    id: 'user_001',
    firstName: 'Ahmet',
    lastName: 'Yılmaz',
    avatar: null,
    rating: 4.5,
  },
  _count: {
    offers: 3,
  },
};

export async function POST(request: NextRequest) {
  try {
    const body = await request.json();
    
    // Validate request body
    const validatedData = CreateServiceRequestSchema.parse(body);
    
    // Mock response - replace with actual database call
    const serviceRequest = {
      ...mockServiceRequest,
      ...validatedData,
      id: `req_${Date.now()}`,
      createdAt: new Date().toISOString(),
    };

    return NextResponse.json({
      success: true,
      data: serviceRequest,
    }, { status: 201 });

  } catch (error) {
    console.error('Service request creation error:', error);
    
    if (error instanceof z.ZodError) {
      return NextResponse.json({
        success: false,
        error: 'Validation failed',
        details: error.errors,
      }, { status: 400 });
    }

    return NextResponse.json({
      success: false,
      error: 'Internal server error',
    }, { status: 500 });
  }
}

export async function GET(request: NextRequest) {
  try {
    const { searchParams } = new URL(request.url);
    const params = Object.fromEntries(searchParams);
    
    // Validate query parameters
    const { page, limit, category, city, status } = GetServiceRequestsSchema.parse(params);
    
    // Mock response - replace with actual database call
    const serviceRequests = [
      mockServiceRequest,
      {
        ...mockServiceRequest,
        id: 'req_002',
        title: 'Elektrik Arızası Onarımı',
        description: 'Sigortalar atıyor, elektrik kesiliyor',
        budget: null,
        city: 'Ankara',
        district: 'Çankaya',
        category: {
          id: 2,
          name: 'Elektrik',
          slug: 'elektrik',
        },
        customer: {
          id: 'user_002',
          firstName: 'Mehmet',
          lastName: 'Kaya',
          avatar: null,
          rating: 4.8,
        },
        _count: {
          offers: 1,
        },
      },
    ];

    return NextResponse.json({
      success: true,
      data: serviceRequests,
      pagination: {
        page,
        limit,
        total: 2,
        totalPages: 1,
      },
    });

  } catch (error) {
    console.error('Service requests fetch error:', error);
    
    if (error instanceof z.ZodError) {
      return NextResponse.json({
        success: false,
        error: 'Invalid query parameters',
        details: error.errors,
      }, { status: 400 });
    }

    return NextResponse.json({
      success: false,
      error: 'Internal server error',
    }, { status: 500 });
  }
} 