// Ana kategoriler ve alt kategoriler
export const MAIN_CATEGORIES = [
  "Teknik Hizmetler",
  "İnşaat ve Tadilat", 
  "Temizlik Hizmetleri",
  "Nakliye ve Taşımacılık",
  "Bahçe ve Peyzaj",
  "Güzellik ve Bakım",
  "Eğitim ve Özel Dersler",
  "Teknoloji ve Yazılım",
  "Otomotiv",
  "Sağlık Hizmetleri",
  "Hukuk ve Danışmanlık",
  "Gıda ve Mutfak",
  "Sanat ve Tasarım",
  "Etkinlik ve Organizasyon",
  "Evcil Hayvan"
] as const;

export const SUBCATEGORIES: Record<string, string[]> = {
  "Teknik Hizmetler": [
    "Elektrik İşleri", "Tesisat İşleri", "Beyaz Eşya Servisi", 
    "Isıtma ve Soğutma", "Elektronik Tamiri", "Güvenlik Sistemleri", "Çilingir Hizmetleri"
  ],
  "İnşaat ve Tadilat": [
    "Boya ve Badana", "Marangozluk", "Döşeme İşleri", 
    "Cam ve Alüminyum", "Çatı ve İzolasyon"
  ],
  "Temizlik Hizmetleri": [
    "Ev Temizliği", "Ofis Temizliği", "Halı ve Döşeme", "İlaçlama"
  ],
  "Nakliye ve Taşımacılık": [
    "Ev Taşıma", "Ofis Taşıma", "Kurye Hizmeti"
  ],
  "Bahçe ve Peyzaj": [
    "Bahçe Bakımı", "Ağaç ve Bitki", "Peyzaj Tasarım", "Su Öğeleri"
  ],
  "Güzellik ve Bakım": [
    "Kuaförlük", "Güzellik Merkezi", "Makyaj", "Tırnak Bakımı"
  ],
  "Eğitim ve Özel Dersler": [
    "Akademik Dersler", "Dil Eğitimi", "Müzik Eğitimi", "Sanat Eğitimi", "Spor Eğitimi"
  ],
  "Teknoloji ve Yazılım": [
    "Web Geliştirme", "Mobil Uygulama", "Dijital Pazarlama", "Bilgisayar Hizmetleri"
  ],
  "Otomotiv": [
    "Oto Tamiri", "Kaportaj", "Bakım Servisi", "Detaylandırma"
  ],
  "Sağlık Hizmetleri": [
    "Evde Sağlık", "Bakım Hizmetleri", "Terapi Hizmetleri", "Veteriner Hizmetleri"
  ],
  "Hukuk ve Danışmanlık": [
    "Hukuki Danışmanlık", "Mali Müşavirlik", "Şirket İşlemleri", "Emlak Danışmanlığı"
  ],
  "Gıda ve Mutfak": [
    "Catering", "Aşçılık", "Pasta ve Tatlı", "Kurye ve Teslimat"
  ],
  "Sanat ve Tasarım": [
    "Grafik Tasarım", "Web Tasarım", "İç Mimarlık", "Fotoğrafçılık", "Video Prodüksiyon"
  ],
  "Etkinlik ve Organizasyon": [
    "Düğün Organizasyonu", "Çocuk Etkinlikleri", "Kurumsal Etkinlik", "Müzik ve Eğlence"
  ],
  "Evcil Hayvan": [
    "Veteriner Hizmetleri", "Pet Bakımı", "Pet Eğitimi", "Pet Bakıcılığı"
  ]
};

export const SERVICES: Record<string, string[]> = {
  // Teknik Hizmetler
  "Elektrik İşleri": [
    "Elektrikçi", "Elektrik Tesisatı", "Elektrik Panosu", "Anahtar Priz Montajı", 
    "Avize Montajı", "LED Montajı", "Spot Montajı", "Elektrik Arızası", 
    "Sayaç Arızası", "Topraklama", "Paratoner", "Jeneratör Servisi"
  ],
  "Tesisat İşleri": [
    "Tesisatçı", "Su Tesisatı", "Doğalgaz Tesisatı", "Kalorifer Tesisatı", 
    "Sıhhi Tesisat", "Tıkanıklık Açma", "Su Kaçağı", "Musluk Tamiri", 
    "Klozet Tamiri", "Lavabo Montajı", "Duş Kabini Montajı", "Jakuzi Montajı"
  ],
  "Beyaz Eşya Servisi": [
    "Buzdolabı Tamiri", "Çamaşır Makinesi Tamiri", "Bulaşık Makinesi Tamiri", 
    "Fırın Tamiri", "Ocak Tamiri", "Mikrodalga Tamiri", "Aspiratör Tamiri",
    "Derin Dondurucu Tamiri", "Su Sebili Tamiri", "Kahve Makinesi Tamiri"
  ],
  "Isıtma ve Soğutma": [
    "Klima Montajı", "Klima Servisi", "Klima Temizliği", "Kombi Servisi", 
    "Şofben Servisi", "Termosifon Servisi", "Kalorifer Radyatörü", 
    "Yerden Isıtma", "Havalandırma Sistemi", "Vantilatör Montajı"
  ],
  "Elektronik Tamiri": [
    "Televizyon Tamiri", "Bilgisayar Tamiri", "Laptop Tamiri", "Tablet Tamiri", 
    "Telefon Tamiri", "Yazıcı Tamiri", "Ses Sistemi Tamiri", "Projeksiyon Tamiri", 
    "Oyun Konsolu Tamiri", "Smartwatch Tamiri", "Kulaklık Tamiri"
  ],
  "Güvenlik Sistemleri": [
    "Güvenlik Kamerası", "Alarm Sistemi", "Interkom Sistemi", "Kapı Zili", 
    "Akıllı Kilit", "Parmak İzi Sistemi", "Yangın Alarm", "Duman Dedektörü", 
    "Hırsız Alarm", "Bebek Telsizi"
  ],
  "Çilingir Hizmetleri": [
    "Çilingir", "Kasa Açma", "Anahtar Çoğaltma", "Kilit Değişimi", 
    "Kapı Kilidi", "Çelik Kasa", "Para Kasası", "Oto Çilingir", 
    "Akıllı Kilit Montajı", "Güvenlik Kilidi"
  ],
  
  // İnşaat ve Tadilat
  "Boya ve Badana": [
    "Ev Boyacısı", "Plastik Boya", "Yağlı Boya", "Silikonlu Boya", 
    "Duvar Boyası", "Tavan Boyası", "Dekoratif Boya", "Sprey Boya", 
    "Ahşap Boyası", "Metal Boyası", "Çini Boyası", "İpek Boyası"
  ],
  "Marangozluk": [
    "Marangoz", "Mobilya Tamiri", "Dolap Yapımı", "Kapı Montajı", 
    "Pencere Montajı", "Mutfak Dolabı", "Yatak Odası Takımı", 
    "TV Ünitesi", "Kitaplık", "Gardırop", "Çekmece Tamiri", "Menteşe Tamiri"
  ],
  "Döşeme İşleri": [
    "Seramik Döşeme", "Parke Döşeme", "Laminat Döşeme", "Vinil Döşeme", 
    "Mermer Döşeme", "Granit Döşeme", "Travertin Döşeme", "Halı Döşeme", 
    "Moquette Döşeme", "Linoleum Döşeme", "Epoksi Döşeme"
  ],
  "Cam ve Alüminyum": [
    "Cam Balkon", "PVC Doğrama", "Alüminyum Doğrama", "Cam Montajı", 
    "Ayna Montajı", "Duş Kabini", "Cam Raylı Sistem", "Katlanır Cam", 
    "Giyotin Cam", "İtfaiye Merdiveni", "Cam Çatı", "Cam Korkuluk"
  ],
  "Çatı ve İzolasyon": [
    "Çatı Tamiri", "Çatı İzolasyonu", "Su İzolasyonu", "Isı İzolasyonu", 
    "Ses İzolasyonu", "Teras İzolasyonu", "Bodrum İzolasyonu", 
    "Kiremit Döşeme", "Çatı Membranı", "Oluk Tamiri"
  ],
  
  // Temizlik Hizmetleri
  "Ev Temizliği": [
    "Genel Ev Temizliği", "Derin Temizlik", "Günlük Temizlik", "Haftalık Temizlik", 
    "Aylık Temizlik", "Bayram Temizliği", "Taşınma Temizliği", "Villa Temizliği", 
    "Daire Temizliği", "Stüdyo Temizliği", "Çatı Katı Temizliği"
  ],
  "Ofis Temizliği": [
    "Ofis Temizlik", "Büro Temizlik", "Fabrika Temizlik", "Mağaza Temizlik", 
    "Otel Temizlik", "Restoran Temizlik", "Hastane Temizlik", "Okul Temizlik", 
    "Klinik Temizlik", "Laboratuvar Temizlik"
  ],
  "Halı ve Döşeme": [
    "Halı Yıkama", "Kilim Yıkama", "Koltuk Yıkama", "Perde Yıkama", 
    "Yatak Temizlik", "Battaniye Yıkama", "Kırlent Yıkama", 
    "Otel Tekstili", "Minderli Koltuk", "Sandalye Yıkama"
  ],
  "İlaçlama": [
    "Böcek İlaçlama", "Karınca İlaçlama", "Hamamböceği İlaçlama", 
    "Fare İlaçlama", "Sıçan İlaçlama", "Güve İlaçlama", "Sivrisinek İlaçlama", 
    "Termit İlaçlama", "Güverçin İlaçlama"
  ]
};

// Yardımcı fonksiyonlar
export const getAllMainCategories = (): readonly string[] => {
  return MAIN_CATEGORIES;
};

export const getSubcategories = (mainCategory: string): string[] => {
  return SUBCATEGORIES[mainCategory] || [];
};

export const getServices = (subcategory: string): string[] => {
  return SERVICES[subcategory] || [];
};

export const getAllServices = (): string[] => {
  const allServices: string[] = [];
  
  for (const subcategory of Object.keys(SERVICES)) {
    const services = SERVICES[subcategory];
    allServices.push(...services);
  }
  
  return allServices;
};

export const searchServices = (query: string): string[] => {
  const allServices = getAllServices();
  return allServices.filter(service => 
    service.toLowerCase().includes(query.toLowerCase())
  );
};

// Eski uyumluluk için
export const getServiceCategories = getAllMainCategories;
export const getServicesByCategory = (category: string): string[] => {
  const subcategories = getSubcategories(category);
  const allServices: string[] = [];
  
  for (const subcategory of subcategories) {
    const services = getServices(subcategory);
    allServices.push(...services);
  }
  
  return allServices;
}; 