import { NextRequest, NextResponse } from 'next/server';
import { PrismaClient } from '@onlineusta/database';

const prisma = new PrismaClient();

export async function POST(request: NextRequest) {
  try {
    const { pageTitle, slug, categoryId, userData } = await request.json();

    if (!pageTitle || !slug) {
      return NextResponse.json(
        { success: false, error: 'Sayfa başlığı ve slug gerekli' },
        { status: 400 }
      );
    }

    // Geçici olarak mevcut kullanıcıyı kullan (normalde session'dan gelecek)
    // cmc9nzuyf0007o64cp5v8wff2 ID'li kullanıcıyı kullanıyoruz
    const userId = "cmc9nzuyf0007o64cp5v8wff2";

    // Kullanıcıyı bul
    const user = await prisma.user.findUnique({
      where: { id: userId },
      select: { id: true, publicId: true, role: true, firstName: true, lastName: true }
    });

    if (!user) {
      return NextResponse.json(
        { success: false, error: 'Kullanıcı bulunamadı' },
        { status: 404 }
      );
    }

    // Slug validation
    if (!/^[a-z0-9-]+$/.test(slug)) {
      return NextResponse.json(
        { success: false, error: 'Geçersiz URL formatı' },
        { status: 400 }
      );
    }

    // Check if slug already exists for this user
    const existingPage = await prisma.user.findFirst({
      where: {
        publicPageSlug: slug,
        NOT: { id: user.id }
      }
    });

    if (existingPage) {
      return NextResponse.json(
        { success: false, error: 'Bu URL zaten kullanımda' },
        { status: 409 }
      );
    }

    // Update user's public page info
    const updatedUser = await prisma.user.update({
      where: { id: user.id },
      data: {
        publicPageTitle: pageTitle,
        publicPageSlug: slug,
        publicPageActive: true
      },
      select: { publicId: true, publicPageSlug: true }
    });

    // publicId yoksa oluştur
    let publicId = updatedUser.publicId;
    if (!publicId) {
      // En yüksek publicId'yi bul ve 1 ekle
      const lastUser = await prisma.user.findFirst({
        where: { publicId: { not: null } },
        orderBy: { publicId: 'desc' },
        select: { publicId: true }
      });
      
      publicId = (lastUser?.publicId || 0) + 1;
      
      await prisma.user.update({
        where: { id: user.id },
        data: { publicId }
      });
    }

    const pageUrl = `/${publicId}/${slug}`;
    const fullUrl = `https://onlineusta.com.tr${pageUrl}`;

    return NextResponse.json({
      success: true,
      userId: publicId,
      slug,
      pageUrl,
      fullUrl,
      message: 'Web sayfanız başarıyla oluşturuldu!'
    });

  } catch (error) {
    console.error('Create public page error:', error);
    return NextResponse.json(
      { success: false, error: 'Sunucu hatası' },
      { status: 500 }
    );
  } finally {
    await prisma.$disconnect();
  }
} 