import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@onlineusta/database';
import bcrypt from 'bcryptjs';

export async function POST(request: NextRequest) {
  try {
    const body = await request.json();
    const {
      accountType,
      services,
      serviceIds,
      firstName,
      lastName,
      phone,
      email,
      companyName,
      companyPhone,
      companyEmail,
      contactPerson,
      city,
      district,
      skills,
      experience,
      description,
      password
    } = body;

    // Şifre hash'le
    const hashedPassword = await bcrypt.hash(password, 12);

    // Şehir ve ilçe ID'lerini bul
    const selectedCity = await prisma.city.findFirst({
      where: { name: city }
    });
    
    const selectedDistrict = selectedCity ? await prisma.district.findFirst({
      where: { 
        name: district,
        cityId: selectedCity.id 
      }
    }) : null;

    // Hesap tipine göre farklı veriler hazırla
    let userData: any = {
      passwordHash: hashedPassword,
      role: 'PROFESSIONAL',
      bio: description,
      cityId: selectedCity?.id,
      districtId: selectedDistrict?.id
    };

    if (accountType === 'individual') {
      userData = {
        ...userData,
        firstName,
        lastName,
        phone,
        email
      };
    } else if (accountType === 'company') {
      userData = {
        ...userData,
        firstName: contactPerson,
        lastName: '',
        phone: companyPhone,
        email: companyEmail,
        companyName
      };
    }

    // Kullanıcıyı veritabanına kaydet
    const user = await prisma.user.create({
      data: userData
    });

    // Seçilen hizmetleri kullanıcıya bağla
    if (serviceIds && serviceIds.length > 0) {
      const userServices = serviceIds.map((categoryId: number) => ({
        userId: user.id,
        categoryId: categoryId,
        experience: experience,
        skills: skills ? [skills] : []
      }));

      await prisma.userCategory.createMany({
        data: userServices
      });
    }

    return NextResponse.json({
      success: true,
      message: 'Kayıt başarıyla tamamlandı',
      userId: user.id
    });

  } catch (error) {
    console.error('Registration error:', error);
    
    if (error instanceof Error) {
      // Unique constraint error
      if (error.message.includes('Unique constraint')) {
        if (error.message.includes('email')) {
          return NextResponse.json(
            { success: false, error: 'Bu e-posta adresi zaten kullanılıyor' },
            { status: 400 }
          );
        }
        if (error.message.includes('phone')) {
          return NextResponse.json(
            { success: false, error: 'Bu telefon numarası zaten kullanılıyor' },
            { status: 400 }
          );
        }
      }
    }

    return NextResponse.json(
      { success: false, error: 'Kayıt sırasında bir hata oluştu' },
      { status: 500 }
    );
  }
} 