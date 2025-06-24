import { NextRequest, NextResponse } from 'next/server';
import { PrismaClient } from '@onlineusta/database';
import bcrypt from 'bcryptjs';

const prisma = new PrismaClient();

export async function POST(request: NextRequest) {
  try {
    const body = await request.json();
    const { email, phone, password } = body;

    // Email veya telefon gerekli
    if (!password) {
      return NextResponse.json(
        { error: 'Şifre gerekli.' },
        { status: 400 }
      );
    }

    if (!email && !phone) {
      return NextResponse.json(
        { error: 'Email veya telefon numarası gerekli.' },
        { status: 400 }
      );
    }

    // Kullanıcıyı bul
    let user;
    if (email) {
      user = await prisma.user.findUnique({
        where: { email },
        include: {
          city: true,
          district: true,
          userCategories: {
            include: {
              category: true
            }
          }
        }
      });
    } else if (phone) {
      user = await prisma.user.findUnique({
        where: { phone },
        include: {
          city: true,
          district: true,
          userCategories: {
            include: {
              category: true
            }
          }
        }
      });
    }

    // Kullanıcı bulunamadı
    if (!user) {
      const notFoundMessage = email 
        ? 'Bu email adresi ile kayıtlı kullanıcı bulunamadı.'
        : 'Bu telefon numarası ile kayıtlı kullanıcı bulunamadı.';
      
      return NextResponse.json(
        { error: notFoundMessage },
        { status: 404 }
      );
    }

    // Sadece PROFESSIONAL kullanıcılar giriş yapabilir
    if (user.role !== 'PROFESSIONAL') {
      return NextResponse.json(
        { error: 'Bu hesap hizmet veren hesabı değil.' },
        { status: 403 }
      );
    }

    // Şifre kontrolü
    const isPasswordValid = await bcrypt.compare(password, user.passwordHash);
    if (!isPasswordValid) {
      return NextResponse.json(
        { error: 'Şifre yanlış.' },
        { status: 401 }
      );
    }

    // Son aktiflik zamanını güncelle
    await prisma.user.update({
      where: { id: user.id },
      data: { lastActiveAt: new Date() }
    });

    // Güvenli user data (şifre hariç)
    const safeUserData = {
      id: user.id,
      email: user.email,
      firstName: user.firstName,
      lastName: user.lastName,
      phone: user.phone,
      role: user.role,
      city: user.city,
      district: user.district,
      bio: user.bio,
      experienceYears: user.experienceYears,
      isVerified: user.isVerified,
      rating: user.rating,
      reviewCount: user.reviewCount,
      completedJobsCount: user.completedJobsCount,
      isAvailable: user.isAvailable,
      companyName: user.companyName,
      avatar: user.avatar,
      website: user.website,
      membershipType: user.membershipType || 'PROFESSIONAL', // Default olarak PROFESSIONAL
      categories: user.userCategories.map(uc => ({
        id: uc.categoryId,
        name: uc.category.name,
        experience: uc.experience,
        skills: uc.skills,
        minPrice: uc.minPrice,
        maxPrice: uc.maxPrice,
        priceUnit: uc.priceUnit
      })),
      createdAt: user.createdAt,
      lastActiveAt: user.lastActiveAt,
      publicPageUrl: (user.publicPageSlug && user.publicPageActive && user.publicId) 
        ? `/${user.publicId}/${user.publicPageSlug}` 
        : null
    };

    return NextResponse.json({
      success: true,
      message: 'Giriş başarılı!',
      user: safeUserData
    });

  } catch (error) {
    console.error('Login error:', error);
    return NextResponse.json(
      { error: 'Giriş işlemi sırasında bir hata oluştu.' },
      { status: 500 }
    );
  } finally {
    await prisma.$disconnect();
  }
} 