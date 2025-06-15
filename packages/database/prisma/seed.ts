// Açıklama: Database seed - hizmet kategorileri ve örnek veri
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

const serviceCategories = [
  {
    name: 'Ev Temizliği',
    slug: 'ev-temizligi',
    description: 'Profesyonel ev temizlik hizmetleri',
    icon: '🏠',
    metaTitle: 'Ev Temizliği Hizmeti | OnlineUsta',
    metaDescription: 'Profesyonel ev temizliği hizmeti al. Güvenilir temizlik ustalarından hızlı teklif al.',
    children: [
      {
        name: 'Genel Ev Temizliği',
        slug: 'genel-ev-temizligi',
        description: 'Tüm ev temizlik hizmetleri',
        icon: '🧽',
      },
      {
        name: 'Cam Temizliği',
        slug: 'cam-temizligi',
        description: 'Profesyonel cam temizlik hizmetleri',
        icon: '🪟',
      },
      {
        name: 'Halı Yıkama',
        slug: 'hali-yikama',
        description: 'Halı ve kilim yıkama hizmetleri',
        icon: '🪣',
      }
    ]
  },
  {
    name: 'Elektrik',
    slug: 'elektrik',
    description: 'Elektrik tesisatı ve onarım hizmetleri',
    icon: '⚡',
    metaTitle: 'Elektrikçi Hizmeti | OnlineUsta',
    metaDescription: 'Güvenilir elektrikçilerden hızlı teklif al. Elektrik arıza, tesisat ve onarım hizmetleri.',
    children: [
      {
        name: 'Elektrik Arızası',
        slug: 'elektrik-arizasi',
        description: 'Elektrik arıza tespit ve onarım',
        icon: '🔧',
      },
      {
        name: 'Anahtar Priz Montajı',
        slug: 'anahtar-priz-montaji',
        description: 'Anahtar ve priz montaj hizmetleri',
        icon: '🔌',
      },
      {
        name: 'Lamba Montajı',
        slug: 'lamba-montaji',
        description: 'Avize ve lamba montaj hizmetleri',
        icon: '💡',
      }
    ]
  },
  {
    name: 'Tesisatçı',
    slug: 'tesisat',
    description: 'Su tesisatı ve doğalgaz hizmetleri',
    icon: '🔧',
    metaTitle: 'Tesisatçı Hizmeti | OnlineUsta',
    metaDescription: 'Su kaçağı, tesisat onarımı ve montaj için tesisatçı bul. Hızlı ve güvenilir hizmet.',
    children: [
      {
        name: 'Su Kaçağı',
        slug: 'su-kacagi',
        description: 'Su kaçağı tespit ve onarım',
        icon: '💧',
      },
      {
        name: 'Tuvalet Onarımı',
        slug: 'tuvalet-onarimi',
        description: 'Klozet ve rezervuar onarımı',
        icon: '🚽',
      },
      {
        name: 'Lavabo Montajı',
        slug: 'lavabo-montaji',
        description: 'Lavabo ve eviye montaj hizmetleri',
        icon: '🚿',
      }
    ]
  },
  {
    name: 'Boyacı',
    slug: 'boyaci',
    description: 'İç ve dış mekan boyama hizmetleri',
    icon: '🎨',
    metaTitle: 'Boyacı Hizmeti | OnlineUsta',
    metaDescription: 'Profesyonel boyacılardan ev, ofis ve dış mekan boyama hizmeti al.',
    children: [
      {
        name: 'İç Mekan Boyama',
        slug: 'ic-mekan-boyama',
        description: 'Ev ve ofis iç boyama hizmetleri',
        icon: '🏠',
      },
      {
        name: 'Dış Mekan Boyama',
        slug: 'dis-mekan-boyama',
        description: 'Bina dış cephe boyama hizmetleri',
        icon: '🏢',
      },
      {
        name: 'Dekoratif Boyama',
        slug: 'dekoratif-boyama',
        description: 'Özel dekoratif boyama teknikleri',
        icon: '✨',
      }
    ]
  },
  {
    name: 'Nakliyat',
    slug: 'nakliyat',
    description: 'Ev ve ofis taşımacılığı hizmetleri',
    icon: '🚚',
    metaTitle: 'Nakliyat Hizmeti | OnlineUsta',
    metaDescription: 'Güvenilir nakliyat firmasından ev taşıma, eşya taşıma hizmeti al.',
    children: [
      {
        name: 'Ev Taşıma',
        slug: 'ev-tasima',
        description: 'Komple ev taşıması hizmetleri',
        icon: '🏠',
      },
      {
        name: 'Ofis Taşıma',
        slug: 'ofis-tasima',
        description: 'Ofis ve iş yeri taşıması',
        icon: '🏢',
      },
      {
        name: 'Eşya Taşıma',
        slug: 'esya-tasima',
        description: 'Küçük eşya ve mobilya taşıması',
        icon: '📦',
      }
    ]
  }
];

async function main() {
  console.log('🌱 Seed verisi ekleniyor...');

  // Service Categories seeding
  for (const categoryData of serviceCategories) {
    const { children, ...parentData } = categoryData;
    
    const parent = await prisma.serviceCategory.upsert({
      where: { slug: parentData.slug },
      update: parentData,
      create: parentData,
    });

    if (children) {
      for (const childData of children) {
        await prisma.serviceCategory.upsert({
          where: { slug: childData.slug },
          update: { ...childData, parentId: parent.id },
          create: { ...childData, parentId: parent.id },
        });
      }
    }
  }

  console.log('✅ Seed tamamlandı');
}

main()
  .catch((e) => {
    console.error('❌ Seed hatası:', e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  }); 