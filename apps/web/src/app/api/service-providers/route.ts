import { NextRequest, NextResponse } from 'next/server';
import { PrismaClient } from '@onlineusta/database';
import bcrypt from 'bcryptjs';

const prisma = new PrismaClient();

export async function POST(request: NextRequest) {
  let body: any;
  try {
    body = await request.json();
    
    const {
      // Hesap türü
      accountType,
      // Bireysel bilgiler
      firstName,
      lastName,
      email,
      phone,
      // Şirket bilgileri
      companyName,
      companyEmail,
      companyPhone,
      contactPerson,
      // Konum
      city,
      district,
      // Yetenekler
      skills,
      experience,
      description,
      // Şifre
      password,
      // Hizmetler
      selectedServices
    } = body;

    // Email kontrolü
    const existingUserEmail = await prisma.user.findUnique({
      where: { 
        email: accountType === 'individual' ? email : companyEmail 
      }
    });

    if (existingUserEmail) {
      return NextResponse.json(
        { error: 'Bu e-posta adresi zaten kayıtlı.' },
        { status: 400 }
      );
    }

    // Telefon kontrolü
    const existingUserPhone = await prisma.user.findUnique({
      where: { 
        phone: accountType === 'individual' ? phone : companyPhone 
      }
    });

    if (existingUserPhone) {
      return NextResponse.json(
        { error: 'Bu telefon numarası zaten kayıtlı.' },
        { status: 400 }
      );
    }

    // Şifreyi hash'le
    const hashedPassword = await bcrypt.hash(password, 12);

    // Kullanıcı oluştur
    const userData = {
      email: accountType === 'individual' ? email : companyEmail,
      phone: accountType === 'individual' ? phone : companyPhone,
      passwordHash: hashedPassword,
      role: 'PROFESSIONAL' as const,
      firstName: accountType === 'individual' ? firstName : contactPerson.split(' ')[0] || contactPerson,
      lastName: accountType === 'individual' ? lastName : contactPerson.split(' ').slice(1).join(' ') || '',
      city,
      district,
      bio: description,
      experienceYears: parseInt(experience) || 0,
      isVerified: false,
      rating: 0,
      reviewCount: 0,
      completedJobsCount: 0,
      isAvailable: true,
      ...(accountType === 'company' && { companyName })
    };

    const user = await prisma.user.create({
      data: userData
    });

    // Kategori ilişkilerini oluştur
    for (const serviceName of selectedServices) {
      // Kategoriyi bul veya oluştur
      let category = await prisma.serviceCategory.findFirst({
        where: { 
          name: {
            contains: serviceName,
            mode: 'insensitive'
          }
        }
      });

      if (!category) {
        // Kategori yoksa oluştur
        category = await prisma.serviceCategory.create({
          data: {
            name: serviceName,
            slug: serviceName.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, ''),
            description: `${serviceName} hizmet kategorisi`,
            isActive: true
          }
        });
      }

      // UserCategory ilişkisi oluştur
      await prisma.userCategory.create({
        data: {
          userId: user.id,
          categoryId: category.id,
          experience: skills,
          isActive: true
        }
      });
    }

    return NextResponse.json({
      success: true,
      message: 'Kayıt başarılı! Hoş geldiniz.',
      user: {
        id: user.id,
        email: user.email,
        firstName: user.firstName,
        lastName: user.lastName,
        role: user.role,
        city: user.city,
        district: user.district
      }
    });

  } catch (error) {
    console.error('Service provider registration error:', error);
    console.error('Error details:', {
      message: error instanceof Error ? error.message : 'Unknown error',
      stack: error instanceof Error ? error.stack : undefined,
      body: body
    });
    return NextResponse.json(
      { 
        error: 'Kayıt sırasında bir hata oluştu.',
        details: error instanceof Error ? error.message : 'Unknown error'
      },
      { status: 500 }
    );
  } finally {
    await prisma.$disconnect();
  }
} 