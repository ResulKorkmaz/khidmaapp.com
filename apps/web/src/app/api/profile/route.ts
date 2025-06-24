import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@onlineusta/database';

export async function GET(request: NextRequest) {
  try {
    const { searchParams } = new URL(request.url);
    const userId = searchParams.get('userId');

    if (!userId) {
      return NextResponse.json(
        { error: 'User ID gerekli.' },
        { status: 400 }
      );
    }

    const user = await prisma.user.findUnique({
      where: { id: userId },
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

    if (!user) {
      return NextResponse.json(
        { error: 'Kullanıcı bulunamadı.' },
        { status: 404 }
      );
    }

    // Güvenli user data (şifre hariç)
    const safeUserData = {
      id: user.id,
      email: user.email,
      firstName: user.firstName,
      lastName: user.lastName,
      phone: user.phone,
      role: user.role,
      city: user.city?.name || '',
      district: user.district?.name || '',
      address: user.address,
      bio: user.bio,
      website: user.website,
      experienceYears: user.experienceYears,
      isVerified: user.isVerified,
      rating: user.rating,
      reviewCount: user.reviewCount,
      completedJobsCount: user.completedJobsCount,
      isAvailable: user.isAvailable,
      companyName: user.companyName,
      avatar: user.avatar,
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
      lastActiveAt: user.lastActiveAt
    };

    return NextResponse.json({
      success: true,
      user: safeUserData
    });

  } catch (error) {
    console.error('Profile fetch error:', error);
    return NextResponse.json(
      { error: 'Profil bilgileri alınamadı.' },
      { status: 500 }
    );
  }
}

export async function PATCH(request: NextRequest) {
  try {
    const body = await request.json();
    const url = new URL(request.url);
    const userId = url.searchParams.get('userId');

    if (!userId) {
      return NextResponse.json(
        { error: 'User ID gerekli.' },
        { status: 400 }
      );
    }

    console.log('Profile update body:', body);

    // Güncellenebilir alanları filtrele
    const allowedFields = [
      'firstName', 'lastName', 'email', 'phone', 
      'city', 'district', 'bio', 'experienceYears', 'categories',
      'cityId', 'districtId', 'address', 'website'
    ];

    const updateData: any = {};
    let categoriesData: string[] = [];
    let cityId: number | null = null;
    let districtId: number | null = null;
    let isCityChanged = false;
    
    // Mevcut kullanıcının şehir ID'sini al
    const currentUser = await prisma.user.findUnique({
      where: { id: userId },
      select: { cityId: true }
    });
    
    if (currentUser?.cityId) {
      cityId = currentUser.cityId;
    }
    
    // Önce city kontrolü yap
    if (body.city) {
      const cityName = body.city as string;
      const city = await prisma.city.findFirst({
        where: { name: cityName }
      });
      if (city) {
        cityId = city.id;
        isCityChanged = true;
      }
    }
    
    for (const [key, value] of Object.entries(body)) {
      if (allowedFields.includes(key)) {
        // experienceYears sayıya çevir
        if (key === 'experienceYears') {
          updateData[key] = parseInt(value as string) || 0;
        } else if (key === 'categories') {
          // Kategorileri ayrı işle
          if (Array.isArray(value)) {
            categoriesData = value;
          } else {
            categoriesData = (value as string).split(',').map(cat => cat.trim()).filter(cat => cat.length > 0);
          }
        } else if (key === 'city') {
          // Şehir zaten yukarıda işlendi, skip
          continue;
        } else if (key === 'district') {
          // İlçe adından ID bul
          const districtName = value as string;
          if (districtName && cityId !== null) {
            const district = await prisma.district.findFirst({
              where: { 
                name: districtName,
                cityId: cityId
              }
            });
            if (district) {
              districtId = district.id;
            } else {
              console.log('District not found:', districtName, 'for cityId:', cityId);
            }
          } else {
            // İlçe boşsa null yap
            districtId = null;
          }
        } else if (key === 'cityId') {
          cityId = parseInt(value as string) || null;
          isCityChanged = true;
        } else if (key === 'districtId') {
          districtId = parseInt(value as string) || null;
        } else {
          // Diğer alanları updateData'ya ekle
          updateData[key] = value;
        }
      }
    }

    // City ve district ID'lerini update data'ya ekle
    if (cityId !== null) {
      updateData.cityId = cityId;
    }
    
    // Şehir değiştiyse ilçeyi null yap
    if (isCityChanged) {
      updateData.districtId = null;
    } else if (districtId !== null) {
      updateData.districtId = districtId;
    }

    console.log('Update data prepared:', updateData);

    if (Object.keys(updateData).length === 0 && categoriesData.length === 0) {
      return NextResponse.json(
        { error: 'Güncellenecek geçerli alan bulunamadı.' },
        { status: 400 }
      );
    }

    // Kategoriler varsa önce işle
    if (categoriesData.length > 0) {
      console.log('Processing categories:', categoriesData);
      
      // Mevcut kullanıcı kategorilerini sil
      await prisma.userCategory.deleteMany({
        where: { userId: userId }
      });

      // Yeni kategorileri ekle
      for (const categoryName of categoriesData) {
        // Kategori var mı kontrol et, yoksa oluştur
        let category = await prisma.serviceCategory.findFirst({
          where: { name: categoryName }
        });

        if (!category) {
          // Yeni kategori oluştur
          category = await prisma.serviceCategory.create({
            data: {
              name: categoryName,
              slug: categoryName.toLowerCase()
                .replace(/ğ/g, 'g')
                .replace(/ü/g, 'u')
                .replace(/ş/g, 's')
                .replace(/ı/g, 'i')
                .replace(/ö/g, 'o')
                .replace(/ç/g, 'c')
                .replace(/\s+/g, '-')
                .replace(/[^a-z0-9-]/g, ''),
              description: `${categoryName} hizmetleri`,
              isActive: true
            }
          });
        }

        // UserCategory bağlantısını oluştur
        await prisma.userCategory.create({
          data: {
            userId: userId,
            categoryId: category.id,
            experience: '',
            skills: [],
            minPrice: null,
            maxPrice: null,
            priceUnit: 'PER_JOB'
          }
        });
      }
    }

    // Kullanıcıyı güncelle
    const updatedUser = await prisma.user.update({
      where: { id: userId },
      data: updateData,
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

    console.log('User updated successfully');

    // Güvenli user data döndür
    const safeUserData = {
      id: updatedUser.id,
      email: updatedUser.email,
      firstName: updatedUser.firstName,
      lastName: updatedUser.lastName,
      phone: updatedUser.phone,
      role: updatedUser.role,
      city: updatedUser.city?.name || '',
      district: updatedUser.district?.name || '',
      address: updatedUser.address,
      bio: updatedUser.bio,
      website: updatedUser.website,
      experienceYears: updatedUser.experienceYears,
      isVerified: updatedUser.isVerified,
      rating: updatedUser.rating,
      reviewCount: updatedUser.reviewCount,
      completedJobsCount: updatedUser.completedJobsCount,
      isAvailable: updatedUser.isAvailable,
      companyName: updatedUser.companyName,
      avatar: updatedUser.avatar,
      categories: updatedUser.userCategories.map(uc => ({
        id: uc.categoryId,
        name: uc.category.name,
        experience: uc.experience,
        skills: uc.skills,
        minPrice: uc.minPrice,
        maxPrice: uc.maxPrice,
        priceUnit: uc.priceUnit
      })),
      createdAt: updatedUser.createdAt,
      lastActiveAt: updatedUser.lastActiveAt
    };

    return NextResponse.json({
      success: true,
      user: safeUserData,
      message: 'Profil başarıyla güncellendi.'
    });

  } catch (error) {
    console.error('Profile update error:', error);
    return NextResponse.json(
      { error: 'Profil güncellenemedi.' },
      { status: 500 }
    );
  }
}