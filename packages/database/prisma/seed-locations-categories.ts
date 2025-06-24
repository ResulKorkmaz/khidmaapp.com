import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

// Şehir ve İlçe Verileri
const CITIES_DISTRICTS = {
  "İstanbul": {
    plateCode: "34",
    region: "Marmara",
    districts: [
      "Adalar", "Arnavutköy", "Ataşehir", "Avcılar", "Bağcılar", "Bahçelievler", 
      "Bakırköy", "Başakşehir", "Bayrampaşa", "Beşiktaş", "Beykoz", "Beylikdüzü", 
      "Beyoğlu", "Büyükçekmece", "Çatalca", "Çekmeköy", "Esenler", "Esenyurt", 
      "Etiler", "Eyüpsultan", "Fatih", "Gaziosmanpaşa", "Güngören", "Kadıköy", 
      "Kağıthane", "Kartal", "Küçükçekmece", "Maltepe", "Pendik", "Sancaktepe", 
      "Sarıyer", "Silivri", "Sultanbeyli", "Sultangazi", "Şile", "Şişli", 
      "Tuzla", "Ümraniye", "Üsküdar", "Zeytinburnu"
    ]
  },
  "Ankara": {
    plateCode: "06",
    region: "İç Anadolu",
    districts: [
      "Akyurt", "Altındağ", "Ayaş", "Bala", "Beypazarı", "Çamlıdere", "Çankaya", 
      "Çubuk", "Elmadağ", "Etimesgut", "Evren", "Gölbaşı", "Güdül", "Haymana", 
      "Kalecik", "Kazan", "Keçiören", "Kızılcahamam", "Mamak", "Nallıhan", 
      "Polatlı", "Pursaklar", "Sincan", "Şereflikoçhisar", "Yenimahalle"
    ]
  },
  "İzmir": {
    plateCode: "35",
    region: "Ege",
    districts: [
      "Aliağa", "Balçova", "Bayındır", "Bayraklı", "Bergama", "Beydağ", "Bornova", 
      "Buca", "Çeşme", "Çiğli", "Dikili", "Foça", "Gaziemir", "Güzelbahçe", 
      "Karabağlar", "Karaburun", "Karşıyaka", "Kemalpaşa", "Kınık", "Kiraz", 
      "Konak", "Menderes", "Menemen", "Narlıdere", "Ödemiş", "Seferihisar", 
      "Selçuk", "Tire", "Torbalı", "Urla"
    ]
  },
  "Bursa": {
    plateCode: "16",
    region: "Marmara",
    districts: [
      "Büyükorhan", "Gemlik", "Gürsu", "Harmancık", "İnegöl", "İznik", "Karacabey", 
      "Keles", "Kestel", "Mudanya", "Mustafakemalpaşa", "Nilüfer", "Orhaneli", 
      "Orhangazi", "Osmangazi", "Yenişehir", "Yıldırım"
    ]
  },
  "Antalya": {
    plateCode: "07",
    region: "Akdeniz",
    districts: [
      "Akseki", "Aksu", "Alanya", "Demre", "Döşemealtı", "Elmalı", "Finike", 
      "Gazipaşa", "Gündoğmuş", "İbradı", "Kaş", "Kemer", "Kepez", "Konyaaltı", 
      "Korkuteli", "Kumluca", "Manavgat", "Muratpaşa", "Serik"
    ]
  },
  "Adana": {
    plateCode: "01",
    region: "Akdeniz",
    districts: [
      "Aladağ", "Ceyhan", "Çukurova", "Feke", "İmamoğlu", "Karaisalı", "Karataş", 
      "Kozan", "Pozantı", "Saimbeyli", "Sarıçam", "Seyhan", "Tufanbeyli", "Yumurtalık", "Yüreğir"
    ]
  },
  "Adıyaman": {
    plateCode: "02",
    region: "Güneydoğu Anadolu",
    districts: [
      "Besni", "Çelikhan", "Gerger", "Gölbaşı", "Kahta", "Merkez", "Samsat", "Sincik", "Tut"
    ]
  },
  "Afyonkarahisar": {
    plateCode: "03",
    region: "İç Anadolu",
    districts: [
      "Başmakçı", "Bayat", "Bolvadin", "Çay", "Çobanlar", "Dazkırı", "Dinar", 
      "Emirdağ", "Evciler", "Hocalar", "İhsaniye", "İscehisar", "Kızılören", 
      "Merkez", "Sandıklı", "Sinanpaşa", "Sultandağı", "Şuhut"
    ]
  }
};

// Hizmet Kategorileri
const SERVICE_CATEGORIES = [
  {
    name: "Teknik Hizmetler",
    slug: "teknik-hizmetler",
    description: "Ev ve işyeri teknik hizmetleri",
    icon: "🔧",
    color: "#3B82F6",
    order: 1,
    children: [
      "Elektrikçi", "Tesisatçı", "Klima Teknisyeni", "Beyaz Eşya Tamiri", 
      "Elektronik Tamiri", "Bilgisayar Tamiri", "Cep Telefonu Tamiri", 
      "TV Montajı", "Anten Kurulumu", "Güvenlik Sistemi"
    ]
  },
  {
    name: "İnşaat ve Tadilat",
    slug: "insaat-tadilat",
    description: "İnşaat, tadilat ve dekorasyon hizmetleri",
    icon: "🏗️",
    color: "#F59E0B",
    order: 2,
    children: [
      "Boyacı", "Ustası", "Seramik Döşeme", "Parke Döşeme", "Cam Balkon", 
      "Mutfak Tadilat", "Banyo Tadilat", "Çatı İzolasyon", "Su İzolasyon", 
      "Duvar Kağıdı", "Alçıpan", "Laminat Döşeme", "Doğramacı", "Demirci"
    ]
  },
  {
    name: "Temizlik Hizmetleri",
    slug: "temizlik-hizmetleri",
    description: "Ev ve işyeri temizlik hizmetleri",
    icon: "🧹",
    color: "#10B981",
    order: 3,
    children: [
      "Ev Temizliği", "Derin Temizlik", "Cam Temizliği", "Halı Yıkama", 
      "Koltuk Yıkama", "Perde Yıkama", "İnşaat Sonrası Temizlik", 
      "Ofis Temizliği", "Villa Temizliği", "Dezenfeksiyon"
    ]
  },
  {
    name: "Nakliye ve Taşımacılık",
    slug: "nakliye-tasimacilik",
    description: "Nakliye ve taşımacılık hizmetleri",
    icon: "🚛",
    color: "#EF4444",
    order: 4,
    children: [
      "Ev Taşıma", "Ofis Taşıma", "Eşya Taşıma", "Piyano Taşıma", 
      "Ambar Taşıma", "Şehir İçi Nakliye", "Şehirler Arası Nakliye", 
      "Asansörlü Taşıma", "Depo Taşıma", "Fabrika Taşıma"
    ]
  },
  {
    name: "Bahçe ve Peyzaj",
    slug: "bahce-peyzaj",
    description: "Bahçe bakımı ve peyzaj hizmetleri",
    icon: "🌱",
    color: "#22C55E",
    order: 5,
    children: [
      "Bahçe Bakımı", "Çim Biçme", "Ağaç Budama", "Peyzaj Tasarım", 
      "Sulama Sistemi", "Çim Ekimi", "Çiçek Dikimi", "Ağaç Dikimi", 
      "Bahçe Temizliği", "Bitki Bakımı"
    ]
  },
  {
    name: "Güzellik ve Bakım",
    slug: "guzellik-bakim",
    description: "Kişisel bakım ve güzellik hizmetleri",
    icon: "💄",
    color: "#EC4899",
    order: 6,
    children: [
      "Kuaför (Kadın)", "Kuaför (Erkek)", "Güzellik Merkezi", "Makyaj", 
      "Gelinlik Makyaj", "Solaryum", "Masaj", "Cilt Bakımı", 
      "Tırnak Bakımı", "Kalıcı Makyaj"
    ]
  },
  {
    name: "Eğitim ve Özel Dersler",
    slug: "egitim-ozel-dersler",
    description: "Özel dersler ve eğitim hizmetleri",
    icon: "📚",
    color: "#8B5CF6",
    order: 7,
    children: [
      "Matematik Dersi", "Fizik Dersi", "Kimya Dersi", "İngilizce Dersi", 
      "Almanca Dersi", "Fransızca Dersi", "Piyano Dersi", "Gitar Dersi", 
      "Resim Dersi", "Dans Dersi", "Yüzme Dersi", "Sürücü Kursu"
    ]
  },
  {
    name: "Sanat ve Tasarım",
    slug: "sanat-tasarim",
    description: "Kreatif ve sanat hizmetleri",
    icon: "🎨",
    color: "#F97316",
    order: 8,
    children: [
      "Grafik Tasarım", "Logo Tasarım", "Web Tasarım", "İç Mimarlık", 
      "Fotoğrafçılık", "Video Çekimi", "Düğün Fotoğrafçısı", "Müzik Prodüksiyon", 
      "Animasyon", "3D Modelleme"
    ]
  },
  {
    name: "Teknoloji ve Yazılım",
    slug: "teknoloji-yazilim",
    description: "Teknoloji ve yazılım hizmetleri",
    icon: "💻",
    color: "#6366F1",
    order: 9,
    children: [
      "Web Sitesi Yapımı", "Mobil Uygulama", "E-ticaret Sitesi", 
      "Bilgisayar Kurulumu", "Ağ Kurulumu", "Güvenlik Yazılımı", 
      "Veri Kurtarma", "SEO Hizmeti", "Sosyal Medya Yönetimi", "Yazılım Geliştirme"
    ]
  },
  {
    name: "Hukuk ve Danışmanlık",
    slug: "hukuk-danismanlik",
    description: "Hukuki ve danışmanlık hizmetleri",
    icon: "⚖️",
    color: "#1F2937",
    order: 10,
    children: [
      "Avukat", "Mali Müşavir", "Hukuki Danışmanlık", "Vergi Danışmanlığı", 
      "İş Hukuku", "Aile Hukuku", "Gayrimenkul Hukuku", "Ticaret Hukuku", 
      "Sigorta Danışmanlığı", "Emlak Danışmanlığı"
    ]
  },
  {
    name: "Sağlık Hizmetleri",
    slug: "saglik-hizmetleri",
    description: "Sağlık ve wellness hizmetleri",
    icon: "🏥",
    color: "#DC2626",
    order: 11,
    children: [
      "Fizyoterapist", "Diyetisyen", "Psikolog", "Evde Hemşire", 
      "Evde Bakım", "Yaşlı Bakımı", "Bebek Bakıcısı", "Pet Bakımı", 
      "Veteriner", "Hasta Nakil"
    ]
  },
  {
    name: "Otomotiv",
    slug: "otomotiv",
    description: "Araç bakım ve onarım hizmetleri",
    icon: "🚗",
    color: "#374151",
    order: 12,
    children: [
      "Oto Elektrik", "Oto Mekanik", "Kaportacı", "Boyacı", 
      "Lastik Değişimi", "Cam Filmi", "Oto Yıkama", "Detaylı Temizlik", 
      "Araç Muayene", "Oto Çilingir", "Araç Ekspertizi", "Yedek Parça"
    ]
  }
];

async function seedLocationsAndCategories() {
  console.log('🌍 Şehir ve ilçe verileri yükleniyor...');
  
  // Şehir ve ilçeleri yükle
  for (const [cityName, cityData] of Object.entries(CITIES_DISTRICTS)) {
    console.log(`📍 ${cityName} yükleniyor...`);
    
    const city = await prisma.city.create({
      data: {
        name: cityName,
        slug: cityName.toLowerCase().replace(/ş/g, 's').replace(/ı/g, 'i').replace(/ğ/g, 'g').replace(/ü/g, 'u').replace(/ç/g, 'c').replace(/ö/g, 'o').replace(/İ/g, 'i').replace(/\s+/g, '-'),
        plateCode: cityData.plateCode,
        region: cityData.region,
        isActive: true,
        order: Object.keys(CITIES_DISTRICTS).indexOf(cityName) + 1
      }
    });

    // İlçeleri yükle
    for (let i = 0; i < cityData.districts.length; i++) {
      const districtName = cityData.districts[i];
      await prisma.district.create({
        data: {
          name: districtName,
          slug: districtName.toLowerCase().replace(/ş/g, 's').replace(/ı/g, 'i').replace(/ğ/g, 'g').replace(/ü/g, 'u').replace(/ç/g, 'c').replace(/ö/g, 'o').replace(/İ/g, 'i').replace(/\s+/g, '-'),
          cityId: city.id,
          isActive: true,
          order: i + 1
        }
      });
    }
    
    console.log(`✅ ${cityName} - ${cityData.districts.length} ilçe yüklendi`);
  }

  console.log('🔧 Hizmet kategorileri yükleniyor...');
  
  // Ana kategorileri ve alt kategorileri yükle
  for (const category of SERVICE_CATEGORIES) {
    console.log(`📂 ${category.name} yükleniyor...`);
    
    const parentCategory = await prisma.serviceCategory.create({
      data: {
        name: category.name,
        slug: category.slug,
        description: category.description,
        icon: category.icon,
        color: category.color,
        order: category.order,
        isActive: true
      }
    });

    // Alt kategorileri yükle
    for (let i = 0; i < category.children.length; i++) {
      const childName = category.children[i];
      await prisma.serviceCategory.create({
        data: {
          name: childName,
          slug: `${category.slug}-${childName.toLowerCase().replace(/\s+/g, '-').replace(/ş/g, 's').replace(/ı/g, 'i').replace(/ğ/g, 'g').replace(/ü/g, 'u').replace(/ç/g, 'c').replace(/ö/g, 'o').replace(/İ/g, 'i')}`,
          description: `${childName} hizmeti`,
          parentId: parentCategory.id,
          order: i + 1,
          isActive: true
        }
      });
    }
    
    console.log(`✅ ${category.name} - ${category.children.length} alt kategori yüklendi`);
  }

  console.log('🎉 Tüm veriler başarıyla yüklendi!');
}

async function main() {
  try {
    // Önce mevcut verileri temizle (isteğe bağlı)
    console.log('🗑️ Mevcut veriler temizleniyor...');
    
    // Foreign key bağlantılarını önce sil (cascade order)
    await prisma.userCategory.deleteMany();
    await prisma.serviceRequest.deleteMany();
    await prisma.serviceCategory.deleteMany();
    
    await prisma.district.deleteMany();
    await prisma.city.deleteMany();
    
    await seedLocationsAndCategories();
  } catch (error) {
    console.error('❌ Seed işlemi başarısız:', error);
    throw error;
  } finally {
    await prisma.$disconnect();
  }
}

if (require.main === module) {
  main();
}

export { seedLocationsAndCategories }; 