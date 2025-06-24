import { NextRequest, NextResponse } from 'next/server';
import { PrismaClient } from '@onlineusta/database';

const prisma = new PrismaClient();

export async function GET(request: NextRequest) {
  try {
    const { searchParams } = new URL(request.url);
    const userId = searchParams.get('userId');

    if (!userId) {
      return NextResponse.json(
        { success: false, error: 'Kullanıcı ID gerekli' },
        { status: 400 }
      );
    }

    console.log('Fetching messages for user:', userId);

    // Mock data for now - gerçek implementation gelecekte eklenecek
    const mockConversations = [
      {
        id: 'conv1',
        clientId: 'client1',
        clientName: 'Ahmet Yılmaz',
        professionalId: userId,
        lastMessage: 'Merhaba, elektrik tesisatı için ne zaman müsaitsiniz?',
        lastMessageAt: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString(), // 2 saat önce
        unreadCount: 2,
        serviceRequest: {
          id: 'req1',
          title: 'Ev Elektrik Tesisatı'
        },
        messages: [
          {
            id: 'msg1',
            senderId: 'client1',
            senderName: 'Ahmet Yılmaz',
            senderRole: 'CLIENT',
            content: 'Merhaba, elektrik tesisatı için teklif vermiştiniz. Detayları konuşabilir miyiz?',
            isRead: true,
            createdAt: new Date(Date.now() - 3 * 60 * 60 * 1000).toISOString(),
            conversationId: 'conv1'
          },
          {
            id: 'msg2',
            senderId: 'client1',
            senderName: 'Ahmet Yılmaz',
            senderRole: 'CLIENT',
            content: 'Merhaba, elektrik tesisatı için ne zaman müsaitsiniz?',
            isRead: false,
            createdAt: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString(),
            conversationId: 'conv1'
          }
        ]
      },
      {
        id: 'conv2',
        clientId: 'client2',
        clientName: 'Fatma Özkan',
        professionalId: userId,
        lastMessage: 'Teşekkürler, çok memnun kaldım. 5 yıldız veriyorum!',
        lastMessageAt: new Date(Date.now() - 1 * 24 * 60 * 60 * 1000).toISOString(), // 1 gün önce
        unreadCount: 0,
        serviceRequest: {
          id: 'req2',
          title: 'Banyo Tesisat Tamiri'
        },
        messages: [
          {
            id: 'msg3',
            senderId: 'client2',
            senderName: 'Fatma Özkan',
            senderRole: 'CLIENT',
            content: 'Teşekkürler, çok memnun kaldım. 5 yıldız veriyorum!',
            isRead: true,
            createdAt: new Date(Date.now() - 1 * 24 * 60 * 60 * 1000).toISOString(),
            conversationId: 'conv2'
          }
        ]
      },
      {
        id: 'conv3',
        clientId: 'client3',
        clientName: 'Mehmet Kaya',
        professionalId: userId,
        lastMessage: 'Fiyat biraz yüksek geldi, pazarlık yapabilir miyiz?',
        lastMessageAt: new Date(Date.now() - 3 * 24 * 60 * 60 * 1000).toISOString(), // 3 gün önce
        unreadCount: 1,
        serviceRequest: {
          id: 'req3',
          title: 'Daire Boyama İşi'
        },
        messages: [
          {
            id: 'msg4',
            senderId: 'client3',
            senderName: 'Mehmet Kaya',
            senderRole: 'CLIENT',
            content: 'Fiyat biraz yüksek geldi, pazarlık yapabilir miyiz?',
            isRead: false,
            createdAt: new Date(Date.now() - 3 * 24 * 60 * 60 * 1000).toISOString(),
            conversationId: 'conv3'
          }
        ]
      }
    ];

    // Son mesaj tarihine göre sırala (en yeni en üstte)
    const sortedConversations = mockConversations.sort(
      (a, b) => new Date(b.lastMessageAt).getTime() - new Date(a.lastMessageAt).getTime()
    );

    console.log(`Returning ${sortedConversations.length} conversations for user ${userId}`);

    return NextResponse.json({
      success: true,
      conversations: sortedConversations,
      totalUnread: sortedConversations.reduce((sum, conv) => sum + conv.unreadCount, 0)
    });

  } catch (error) {
    console.error('Messages fetch error:', error);
    return NextResponse.json(
      { success: false, error: 'Mesajlar getirilirken hata oluştu' },
      { status: 500 }
    );
  }
} 