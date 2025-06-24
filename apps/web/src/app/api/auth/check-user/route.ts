import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@onlineusta/database';

export async function POST(request: NextRequest) {
  try {
    const { email, phone } = await request.json();

    if (!email || !phone) {
      return NextResponse.json(
        { error: 'E-posta ve telefon numarası gerekli' },
        { status: 400 }
      );
    }

    // Önce e-posta ile kontrol et
    const userByEmail = await prisma.user.findUnique({
      where: { email },
      select: { id: true, email: true, phone: true }
    });

    if (userByEmail) {
      return NextResponse.json({
        exists: true,
        type: 'email',
        value: email,
        message: 'Bu e-posta adresi ile zaten bir hesap bulunmaktadır.'
      });
    }

    // Sonra telefon numarası ile kontrol et
    const userByPhone = await prisma.user.findUnique({
      where: { phone },
      select: { id: true, email: true, phone: true }
    });

    if (userByPhone) {
      return NextResponse.json({
        exists: true,
        type: 'phone',
        value: phone,
        message: 'Bu telefon numarası ile zaten bir hesap bulunmaktadır.'
      });
    }

    // Kullanıcı bulunamadı
    return NextResponse.json({
      exists: false,
      message: 'Kullanıcı bulunamadı, kayıt işlemine devam edebilirsiniz.'
    });

  } catch (error) {
    console.error('Kullanıcı kontrolü hatası:', error);
    return NextResponse.json(
      { error: 'Kullanıcı kontrolü sırasında bir hata oluştu' },
      { status: 500 }
    );
  }
} 