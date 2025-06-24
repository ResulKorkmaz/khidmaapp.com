import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function addServiceRequests() {
  console.log('🔧 Service request\'ler ekleniyor...');

  try {
    // Mevcut kategorileri ve kullanıcıları al
    const categories = await prisma.serviceCategory.findMany();
    const customers = await prisma.user.findMany({
      where: { role: 'CUSTOMER' }
    });

    console.log(`Bulunan kategoriler: ${categories.length}`);
    console.log(`Bulunan müşteriler: ${customers.length}`);

    if (categories.length === 0 || customers.length === 0) {
      console.log('❌ Kategori veya müşteri bulunamadı');
      return;
    }

    const serviceRequests = [
      {
        title: 'Klima Montajı - 2 Adet Split Klima',
        description: 'Yeni aldığım 2 adet split klimanın montajını yaptırmak istiyorum. Biri yatak odası için 12.000 BTU, diğeri salon için 18.000 BTU.',
        budget: 800,
        budgetType: 'NEGOTIABLE',
        city: 'İstanbul',
        district: 'Kadıköy',
        categoryId: categories.find(c => c.slug === 'klima')?.id || categories[0].id,
        customerId: customers[0]?.id,
        status: 'PUBLISHED',
        urgency: 'THIS_WEEK',
        preferredDate: new Date(Date.now() + 5 * 24 * 60 * 60 * 1000),
        isFlexible: true,
        publishedAt: new Date(),
      },
      {
        title: 'Acil Tesisat Tamiri - Su Kaçağı',
        description: 'Mutfak lavabosunun altından su sızıyor. Acil müdahale gerekiyor. Malzeme dahil hizmet istiyorum.',
        budget: 250,
        budgetType: BudgetType.FIXED,
        city: 'İstanbul',
        district: 'Beşiktaş',
        categoryId: categories.find(c => c.slug === 'tesisat')?.id || categories[0].id,
        customerId: customers[1]?.id || customers[0]?.id,
        status: RequestStatus.PUBLISHED,
        urgency: Urgency.ASAP,
        preferredDate: new Date(Date.now() + 1 * 24 * 60 * 60 * 1000),
        isFlexible: false,
        publishedAt: new Date(),
      },
      {
        title: 'Ofis Taşımacılığı - Şişli\'den Levent\'e',
        description: 'Küçük ofisimi Şişli\'den Levent\'e taşıtmak istiyorum. Yaklaşık 20 koli, 2 masa, 4 sandalye ve 1 dolap var.',
        budget: 600,
        budgetType: BudgetType.NEGOTIABLE,
        city: 'İstanbul',
        district: 'Şişli',
        categoryId: categories.find(c => c.slug === 'nakliyat')?.id || categories[0].id,
        customerId: customers[2]?.id || customers[0]?.id,
        status: RequestStatus.PUBLISHED,
        urgency: Urgency.THIS_MONTH,
        preferredDate: new Date(Date.now() + 10 * 24 * 60 * 60 * 1000),
        isFlexible: true,
        publishedAt: new Date(),
      },
      {
        title: 'Salon ve Yatak Odası Boyası',
        description: '2+1 dairemde salon ve yatak odasını boyatmak istiyorum. Duvarlar beyaz, tavan da dahil. Kaliteli boya kullanılmasını istiyorum.',
        budget: 1200,
        budgetType: BudgetType.FIXED,
        city: 'İstanbul',
        district: 'Üsküdar',
        categoryId: categories.find(c => c.slug === 'boyaci')?.id || categories[0].id,
        customerId: customers[0]?.id,
        status: RequestStatus.PUBLISHED,
        urgency: Urgency.THIS_WEEK,
        preferredDate: new Date(Date.now() + 7 * 24 * 60 * 60 * 1000),
        isFlexible: true,
        publishedAt: new Date(),
      },
      {
        title: 'Haftalık Ev Temizliği - Bakırköy',
        description: '3+1 dairem için haftalık düzenli temizlik hizmeti arıyorum. Temizlik malzemeleri dahil olsun.',
        budget: 180,
        budgetType: BudgetType.FIXED,
        city: 'İstanbul',
        district: 'Bakırköy',
        categoryId: categories.find(c => c.slug === 'ev-temizligi')?.id || categories[0].id,
        customerId: customers[1]?.id || customers[0]?.id,
        status: RequestStatus.PUBLISHED,
        urgency: Urgency.THIS_WEEK,
        preferredDate: new Date(Date.now() + 3 * 24 * 60 * 60 * 1000),
        isFlexible: true,
        publishedAt: new Date(),
      }
    ];

    const created = await Promise.all(
      serviceRequests.map(async (request) => {
        return await prisma.serviceRequest.create({ data: request });
      })
    );

    console.log(`✅ ${created.length} service request eklendi`);
    
    // Eklenen service request'leri listele
    created.forEach((req, index) => {
      console.log(`${index + 1}. ${req.title} - ${req.city}, ${req.district}`);
    });

  } catch (error) {
    console.error('❌ Hata:', error);
  } finally {
    await prisma.$disconnect();
  }
}

addServiceRequests(); 