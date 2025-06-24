import { PrismaClient } from '@prisma/client';
import bcrypt from 'bcryptjs';
import { UserRole, RequestStatus, OfferStatus, BudgetType, Urgency, MessageType, PriceUnit } from '@prisma/client';

const prisma = new PrismaClient();

async function main() {
  console.log('🌱 Marketplace seed data oluşturuluyor...');

  // ====================================
  // CITIES & DISTRICTS
  // ====================================
  
  // İstanbul
  const istanbul = await prisma.city.create({
    data: {
      name: 'İstanbul',
      slug: 'istanbul',
      plateCode: '34',
      latitude: 41.0082,
      longitude: 28.9784,
      region: 'Marmara',
      isActive: true,
      order: 1
    }
  });

  const istanbulDistricts = await Promise.all([
    prisma.district.create({
      data: { name: 'Kadıköy', slug: 'kadikoy', cityId: istanbul.id, isActive: true, order: 1 }
    }),
    prisma.district.create({
      data: { name: 'Beşiktaş', slug: 'besiktas', cityId: istanbul.id, isActive: true, order: 2 }
    }),
    prisma.district.create({
      data: { name: 'Şişli', slug: 'sisli', cityId: istanbul.id, isActive: true, order: 3 }
    })
  ]);

  // Ankara
  const ankara = await prisma.city.create({
    data: {
      name: 'Ankara',
      slug: 'ankara',
      plateCode: '06',
      latitude: 39.9334,
      longitude: 32.8597,
      region: 'İç Anadolu',
      isActive: true,
      order: 2
    }
  });

  const ankaraDistricts = await Promise.all([
    prisma.district.create({
      data: { name: 'Çankaya', slug: 'cankaya', cityId: ankara.id, isActive: true, order: 1 }
    }),
    prisma.district.create({
      data: { name: 'Kızılay', slug: 'kizilay', cityId: ankara.id, isActive: true, order: 2 }
    })
  ]);

  // İzmir
  const izmir = await prisma.city.create({
    data: {
      name: 'İzmir',
      slug: 'izmir',
      plateCode: '35',
      latitude: 38.4192,
      longitude: 27.1287,
      region: 'Ege',
      isActive: true,
      order: 3
    }
  });

  const izmirDistricts = await Promise.all([
    prisma.district.create({
      data: { name: 'Konak', slug: 'konak', cityId: izmir.id, isActive: true, order: 1 }
    }),
    prisma.district.create({
      data: { name: 'Bornova', slug: 'bornova', cityId: izmir.id, isActive: true, order: 2 }
    })
  ]);

  console.log('✅ Şehir ve ilçe verileri oluşturuldu');

  // ====================================
  // SERVICE CATEGORIES
  // ====================================

  const categories = [
    {
      name: 'Ev Temizliği',
      slug: 'ev-temizligi',
      description: 'Profesyonel ev temizlik hizmetleri',
      icon: '🧹',
      color: '#3B82F6',
      isActive: true,
      order: 1,
      requiresLocation: true,
      maxBudget: 500.0,
      minBudget: 50.0,
      customQuestions: {
        homeSize: {
          type: 'select',
          label: 'Ev büyüklüğü',
          options: ['1+0', '1+1', '2+1', '3+1', '4+1', '5+1 ve üzeri'],
          required: true
        },
        frequency: {
          type: 'select',
          label: 'Temizlik sıklığı',
          options: ['Tek seferlik', 'Haftalık', 'İki haftada bir', 'Aylık'],
          required: true
        },
        supplies: {
          type: 'boolean',
          label: 'Temizlik malzemelerini getirecek misiniz?',
          required: true
        }
      }
    },
    {
      name: 'Elektrik',
      slug: 'elektrik',
      description: 'Elektrik tesisatı ve onarım hizmetleri',
      icon: '⚡',
      color: '#F59E0B',
      isActive: true,
      order: 2,
      requiresLocation: true,
      maxBudget: 2000.0,
      minBudget: 100.0,
      customQuestions: {
        problemType: {
          type: 'select',
          label: 'Problem türü',
          options: ['Priz sorunu', 'Sigorta atıyor', 'Aydınlatma problemi', 'Genel elektrik arızası'],
          required: true
        },
        urgency: {
          type: 'select',
          label: 'Aciliyet durumu',
          options: ['Normal (1 hafta)', 'Acil (24 saat)', 'Çok acil (birkaç saat)'],
          required: true
        },
        materials: {
          type: 'boolean',
          label: 'Malzemeleri temin edecek misiniz?',
          required: true
        }
      }
    },
    {
      name: 'Tesisatçı',
      slug: 'tesisat',
      description: 'Su tesisatı, doğalgaz ve kombi hizmetleri',
      icon: '🔧',
      color: '#10B981',
      isActive: true,
      order: 3,
      requiresLocation: true,
      maxBudget: 1500.0,
      minBudget: 80.0
    },
    {
      name: 'Boyacı',
      slug: 'boyaci',
      description: 'İç ve dış mekan boyama hizmetleri',
      icon: '🎨',
      color: '#8B5CF6',
      isActive: true,
      order: 4,
      requiresLocation: true,
      maxBudget: 3000.0,
      minBudget: 200.0
    },
    {
      name: 'Ayakkabı Tamiri',
      slug: 'ayakkabi-tamiri',
      description: 'Ayakkabı ve deri eşya tamiri',
      icon: '👞',
      color: '#EF4444',
      isActive: true,
      order: 5,
      requiresLocation: false,
      maxBudget: 200.0,
      minBudget: 20.0
    }
  ];

  const createdCategories = await Promise.all(
    categories.map(async (category) => {
      return await prisma.serviceCategory.create({
        data: category
      });
    })
  );

  console.log(`✅ ${createdCategories.length} kategori oluşturuldu`);

  // ====================================
  // USERS (Customers and Professionals)
  // ====================================

  const passwordHash = await bcrypt.hash('password123', 10);

  // Customers
  const customers = [
    {
      email: 'ahmet@example.com',
      firstName: 'Ahmet',
      lastName: 'Yılmaz',
      phone: '+905551234567',
      cityId: istanbul.id,
      districtId: istanbulDistricts[0].id, // Kadıköy
      role: UserRole.CUSTOMER,
      passwordHash,
      emailVerified: new Date(),
    },
    {
      email: 'ayse@example.com',
      firstName: 'Ayşe',
      lastName: 'Demir',
      phone: '+905551234568',
      cityId: ankara.id,
      districtId: ankaraDistricts[0].id, // Çankaya
      role: UserRole.CUSTOMER,
      passwordHash,
      emailVerified: new Date(),
    },
    {
      email: 'mehmet@example.com',
      firstName: 'Mehmet',
      lastName: 'Kaya',
      phone: '+905551234569',
      cityId: izmir.id,
      districtId: izmirDistricts[0].id, // Konak
      role: UserRole.CUSTOMER,
      passwordHash,
      emailVerified: new Date(),
    }
  ];

  // Professionals
  const professionals = [
    {
      email: 'temizlik.expert@example.com',
      firstName: 'Elif',
      lastName: 'Çetin',
      phone: '+905557654321',
      cityId: istanbul.id,
      districtId: istanbulDistricts[1].id, // Beşiktaş
      role: UserRole.PROFESSIONAL,
      passwordHash,
      emailVerified: new Date(),
      phoneVerified: new Date(),
      bio: '8 yıllık deneyimle profesyonel ev temizlik hizmetleri. Tüm temizlik malzemelerim dahil.',
      experienceYears: 8,
      isVerified: true,
      verifiedAt: new Date(),
      rating: 4.8,
      reviewCount: 156,
      completedJobsCount: 234,
      responseTimeAvg: 45,
      companyName: 'Elif Temizlik Hizmetleri',
    },
    {
      email: 'elektrik.usta@example.com',
      firstName: 'Murat',
      lastName: 'Öztürk',
      phone: '+905557654322',
      cityId: istanbul.id,
      districtId: istanbulDistricts[2].id, // Şişli
      role: UserRole.PROFESSIONAL,
      passwordHash,
      emailVerified: new Date(),
      phoneVerified: new Date(),
      bio: 'Elektrik mühendisi ve 12 yıllık saha deneyimi. 7/24 acil servis.',
      experienceYears: 12,
      isVerified: true,
      verifiedAt: new Date(),
      rating: 4.9,
      reviewCount: 203,
      completedJobsCount: 312,
      responseTimeAvg: 30,
      companyName: 'Öztürk Elektrik',
    },
    {
      email: 'tesisat.pro@example.com',
      firstName: 'Hasan',
      lastName: 'Aydın',
      phone: '+905557654323',
      cityId: ankara.id,
      districtId: ankaraDistricts[1].id, // Kızılay
      role: UserRole.PROFESSIONAL,
      passwordHash,
      emailVerified: new Date(),
      phoneVerified: new Date(),
      bio: 'Su tesisatı uzmanı. Garantili hizmet ve kaliteli malzeme kullanımı.',
      experienceYears: 15,
      isVerified: true,
      verifiedAt: new Date(),
      rating: 4.7,
      reviewCount: 89,
      completedJobsCount: 156,
      responseTimeAvg: 60,
    },
    {
      email: 'boya.master@example.com',
      firstName: 'Kemal',
      lastName: 'Doğan',
      phone: '+905557654324',
      cityId: izmir.id,
      districtId: izmirDistricts[1].id, // Bornova
      role: UserRole.PROFESSIONAL,
      passwordHash,
      emailVerified: new Date(),
      phoneVerified: new Date(),
      bio: 'İç ve dış mekan boyama uzmanı. Kaliteli boya ve işçilik garantisi.',
      experienceYears: 10,
      isVerified: true,
      verifiedAt: new Date(),
      rating: 4.6,
      reviewCount: 67,
      completedJobsCount: 98,
      responseTimeAvg: 120,
      companyName: 'Doğan Boya',
    },
    {
      email: 'resul@example.com',
      firstName: 'Resul',
      lastName: 'Korkmaz',
      phone: '+905551112233',
      cityId: istanbul.id,
      districtId: istanbulDistricts[0].id, // Kadıköy
      role: UserRole.PROFESSIONAL,
      passwordHash,
      emailVerified: new Date(),
      phoneVerified: new Date(),
      bio: 'Ayakkabı tamiri konusunda 15 yıllık deneyim. Her türlü ayakkabı ve deri eşya tamiri.',
      experienceYears: 15,
      isVerified: true,
      verifiedAt: new Date(),
      rating: 4.9,
      reviewCount: 342,
      completedJobsCount: 567,
      responseTimeAvg: 25,
      companyName: 'Korkmaz Ayakkabı Tamiri',
      publicPageTitle: 'Resul Korkmaz - Ayakkabı Tamiri Uzmanı',
      publicPageSlug: 'ayakkabi-tamiri',
      publicPageActive: true,
    }
  ];

  const createdCustomers = await Promise.all(
    customers.map(async (customer) => {
      return await prisma.user.create({ data: customer });
    })
  );

  const createdProfessionals = await Promise.all(
    professionals.map(async (professional) => {
      return await prisma.user.create({ data: professional });
    })
  );

  console.log(`✅ ${createdCustomers.length} müşteri ve ${createdProfessionals.length} profesyonel oluşturuldu`);

  // ====================================
  // USER CATEGORIES (Professional Skills)
  // ====================================

  const userCategories = [
    {
      userId: createdProfessionals[0].id, // Elif - Temizlik
      categoryId: createdCategories[0].id, // Ev Temizliği
      minPrice: 80,
      maxPrice: 200,
      experience: 'Ev temizliği konusunda 8 yıllık deneyim. Özel temizlik ürünleri kullanımı.',
      skills: ['Genel temizlik', 'Cam temizliği', 'Halı temizliği', 'Bulaşık makinesi temizliği'],
    },
    {
      userId: createdProfessionals[1].id, // Murat - Elektrik
      categoryId: createdCategories[1].id, // Elektrik
      minPrice: 150,
      maxPrice: 800,
      experience: 'Elektrik mühendisi diploması ve saha deneyimi.',
      skills: ['Ev elektriği', 'Endüstriyel elektrik', 'LED aydınlatma', 'Akıllı ev sistemleri'],
    },
    {
      userId: createdProfessionals[2].id, // Hasan - Tesisat
      categoryId: createdCategories[2].id, // Tesisatçı
      minPrice: 100,
      maxPrice: 500,
      experience: '15 yıllık tesisat deneyimi. Su ve doğalgaz tesisatı uzmanı.',
      skills: ['Su tesisatı', 'Doğalgaz tesisatı', 'Petek temizliği', 'Banyo renovasyonu'],
    },
    {
      userId: createdProfessionals[3].id, // Kemal - Boyacı
      categoryId: createdCategories[3].id, // Boyacı
      minPrice: 200,
      maxPrice: 1200,
      experience: 'İç ve dış mekan boyama uzmanı. Dekoratif boyama teknikleri.',
      skills: ['Duvar boyama', 'Ahşap boyama', 'Dekoratif teknikler', 'Sprey boya'],
    },
    {
      userId: createdProfessionals[4].id, // Resul - Ayakkabı Tamiri
      categoryId: createdCategories[4].id, // Ayakkabı Tamiri
      minPrice: 25,
      maxPrice: 150,
      experience: 'Ayakkabı tamiri konusunda 15 yıllık deneyim. Her türlü ayakkabı ve deri eşya tamiri.',
      skills: ['Ayakkabı tamiri', 'Deri eşya tamiri', 'Ökçe tamiri', 'Fermuar değişimi'],
    }
  ];

  const createdUserCategories = await Promise.all(
    userCategories.map(async (userCategory) => {
      return await prisma.userCategory.create({ data: userCategory });
    })
  );

  console.log(`✅ ${createdUserCategories.length} profesyonel kategori bağlantısı oluşturuldu`);

  console.log('\n🎉 Marketplace seed data başarıyla oluşturuldu!');
  console.log('\n📊 Oluşturulan veriler:');
  console.log(`• ${createdCategories.length} hizmet kategorisi`);
  console.log(`• ${createdCustomers.length} müşteri`);
  console.log(`• ${createdProfessionals.length} profesyonel`);
  console.log(`• ${createdUserCategories.length} kategori bağlantısı`);
  
  console.log('\n🔑 Test hesapları:');
  console.log('Müşteri: ahmet@example.com / password123');
  console.log('Profesyonel: temizlik.expert@example.com / password123');
  console.log('Elektrikçi: elektrik.usta@example.com / password123');
  console.log('Resul Korkmaz: resul@example.com / password123');
}

main()
  .then(async () => {
    await prisma.$disconnect();
  })
  .catch(async (e) => {
    console.error(e);
    await prisma.$disconnect();
    process.exit(1);
  }); 