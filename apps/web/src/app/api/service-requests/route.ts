// Açıklama: Service request API - CRUD operasyonları
import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@onlineusta/database';
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

export async function POST(request: NextRequest) {
  try {
    const body = await request.json();
    
    // Validate request body
    const validatedData = CreateServiceRequestSchema.parse(body);
    
    // TODO: Get user ID from session/auth
    const customerId = 'temp-user-id'; // Replace with actual user ID
    
    // Create service request
    const serviceRequest = await prisma.serviceRequest.create({
      data: {
        ...validatedData,
        customerId,
        preferredDate: validatedData.preferredDate 
          ? new Date(validatedData.preferredDate) 
          : undefined,
        status: 'PUBLISHED',
      },
      include: {
        category: true,
        customer: {
          select: {
            id: true,
            firstName: true,
            lastName: true,
            avatar: true,
          },
        },
      },
    });

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
    
    const offset = (page - 1) * limit;
    
    // Build where clause
    const where: any = {};
    if (category) where.category = { slug: category };
    if (city) where.city = city;
    if (status) where.status = status;
    
    // Get service requests with pagination
    const [serviceRequests, total] = await Promise.all([
      prisma.serviceRequest.findMany({
        where,
        include: {
          category: true,
          customer: {
            select: {
              id: true,
              firstName: true,
              lastName: true,
              avatar: true,
              rating: true,
            },
          },
          _count: {
            select: {
              offers: true,
            },
          },
        },
        orderBy: {
          createdAt: 'desc',
        },
        skip: offset,
        take: limit,
      }),
      prisma.serviceRequest.count({ where }),
    ]);

    return NextResponse.json({
      success: true,
      data: serviceRequests,
      pagination: {
        page,
        limit,
        total,
        totalPages: Math.ceil(total / limit),
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