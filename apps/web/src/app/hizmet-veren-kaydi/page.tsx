"use client";

import { useState, useEffect, useRef } from "react";
import { Button, Input } from "@onlineusta/ui";
import Link from "next/link";

// Mock hizmet verileri
const HIZMETLER = [
  // Teknik Hizmetler
  "Elektrikçi",
  "Tesisatçı", 
  "Klima Servisi",
  "Beyaz Eşya Servisi",
  "Kombi Servisi",
  "Şofben Servisi",
  "Buzdolabı Servisi",
  "Çamaşır Makinesi Servisi",
  "Bulaşık Makinesi Servisi",
  "Fırın Servisi",
  "Televizyon Servisi",
  "Bilgisayar Tamiri",
  "Telefon Tamiri",
  "Tablet Tamiri",
  "Laptop Tamiri",
  "Yazıcı Servisi",
  "Güvenlik Kamerası",
  "Alarm Sistemi",
  "İnterkom Sistemi",
  "Uydu Servisi",
  "Antenci",
  "Çilingir",
  "Kasa Açma",
  "Anahtar Çoğaltma",
  "Kilit Değişimi",
  
  // İnşaat ve Tadilat
  "Boyacı",
  "Marangoz",
  "Tadilat",
  "Döşeme",
  "Seramik Döşeme",
  "Parke Döşeme",
  "Laminat Döşeme",
  "Halı Döşeme",
  "Duvar Kağıdı",
  "Alçı Tavan",
  "Kartonpiyer",
  "Cam Balkon",
  "PVC Doğrama",
  "Alüminyum Doğrama",
  "Çatı Onarımı",
  "İzolasyon",
  "Su İzolasyonu",
  "Isı İzolasyonu",
  "Ses İzolasyonu",
  "Boya Badana",
  "Dekorasyon",
  "İç Mimar",
  "Mimar",
  "İnşaat Mühendisi",
  "Yapı Denetim",
  "Ruhsat İşlemleri",
  
  // Temizlik Hizmetleri
  "Temizlik",
  "Ev Temizliği",
  "Ofis Temizliği",
  "İnşaat Sonrası Temizlik",
  "Cam Temizliği",
  "Halı Yıkama",
  "Koltuk Yıkama",
  "Perde Yıkama",
  "Battaniye Yıkama",
  "Yatak Temizliği",
  "Dezenfeksiyon",
  "Böcek İlaçlama",
  "Fare İlaçlama",
  "Haşere İlaçlama",
  
  // Nakliye ve Taşımacılık
  "Nakliye",
  "Ev Taşıma",
  "Ofis Taşıma",
  "Eşya Taşıma",
  "Piyano Taşıma",
  "Kasa Taşıma",
  "Ambar Taşıma",
  "Şehir İçi Nakliye",
  "Şehir Dışı Nakliye",
  "Uluslararası Nakliye",
  "Kurye",
  "Moto Kurye",
  "Kargo",
  "Paket Servisi",
  
  // Bahçe ve Peyzaj
  "Bahçıvan",
  "Peyzaj",
  "Bahçe Düzenleme",
  "Çim Biçme",
  "Ağaç Budama",
  "Ağaç Kesme",
  "Çiçek Dikme",
  "Sulama Sistemi",
  "Bahçe Aydınlatması",
  "Pergola",
  "Çardak",
  "Havuz Yapımı",
  "Havuz Bakımı",
  "Jakuzi Servisi",
  
  // Güzellik ve Bakım
  "Kuaför",
  "Berber",
  "Güzellik Uzmanı",
  "Estetisyen",
  "Masaj",
  "Masöz",
  "Fizyoterapist",
  "Diyetisyen",
  "Psikolog",
  "Makyöz",
  "Nail Art",
  "Kaş Tasarım",
  "Kirpik Lifting",
  "Cilt Bakımı",
  "Epilasyon",
  "Solaryum",
  
  // Eğitim ve Özel Dersler
  "Özel Ders",
  "Matematik Dersi",
  "Fizik Dersi",
  "Kimya Dersi",
  "Biyoloji Dersi",
  "Türkçe Dersi",
  "Edebiyat Dersi",
  "Tarih Dersi",
  "Coğrafya Dersi",
  "İngilizce Dersi",
  "Almanca Dersi",
  "Fransızca Dersi",
  "Rusça Dersi",
  "Arapça Dersi",
  "Çince Dersi",
  "Japonca Dersi",
  "İspanyolca Dersi",
  "İtalyanca Dersi",
  "Piyano Dersi",
  "Gitar Dersi",
  "Keman Dersi",
  "Bağlama Dersi",
  "Davul Dersi",
  "Ses Eğitimi",
  "Dans Dersi",
  "Bale Dersi",
  "Halk Oyunları",
  "Modern Dans",
  "Latin Dansları",
  "Tango Dersi",
  "Salsa Dersi",
  "Yoga Dersi",
  "Pilates Dersi",
  "Fitness Antrenörü",
  "Kişisel Antrenör",
  "Yüzme Antrenörü",
  "Tenis Antrenörü",
  "Futbol Antrenörü",
  "Basketbol Antrenörü",
  "Voleybol Antrenörü",
  
  // Sanat ve Tasarım
  "Fotoğrafçı",
  "Video Çekimi",
  "Düğün Fotoğrafçısı",
  "Etkinlik Fotoğrafçısı",
  "Ürün Fotoğrafçısı",
  "Portre Fotoğrafçısı",
  "Grafik Tasarım",
  "Web Tasarım",
  "Logo Tasarım",
  "Afiş Tasarım",
  "Kartvizit Tasarım",
  "Broşür Tasarım",
  "Katalog Tasarım",
  "Ambalaj Tasarım",
  "İç Mekan Tasarım",
  "Mobilya Tasarım",
  "Moda Tasarım",
  "Takı Tasarım",
  "Seramik Sanatı",
  "Resim Dersi",
  "Heykel Dersi",
  "Çizim Dersi",
  "Karakalem Dersi",
  "Yağlı Boya Dersi",
  "Sulu Boya Dersi",
  "Ebru Sanatı",
  "Hat Sanatı",
  "Tezhip Sanatı",
  
  // Teknoloji ve Yazılım
  "Web Geliştirme",
  "Mobil Uygulama",
  "Yazılım Geliştirme",
  "Veritabanı Yönetimi",
  "Sistem Yönetimi",
  "Ağ Kurulumu",
  "Bilgisayar Kurulumu",
  "Yazılım Kurulumu",
  "Veri Kurtarma",
  "Siber Güvenlik",
  "SEO Uzmanı",
  "Sosyal Medya Uzmanı",
  "Dijital Pazarlama",
  "E-ticaret Uzmanı",
  "Google Ads Uzmanı",
  "Facebook Ads Uzmanı",
  "İçerik Yazarı",
  "Copywriter",
  "Çeviri",
  "Tercümanlık",
  "Editörlük",
  "Proofreading",
  
  // Hukuk ve Danışmanlık
  "Avukat",
  "Hukuk Danışmanı",
  "Mali Müşavir",
  "Muhasebeci",
  "Vergi Danışmanı",
  "İnsan Kaynakları",
  "İş Danışmanı",
  "Emlak Danışmanı",
  "Emlak Uzmanı",
  "Emlak Değerleme",
  "Sigorta Acentesi",
  "Finansal Danışman",
  "Yatırım Danışmanı",
  "Borsa Analisti",
  "Ekonomist",
  "İstatistikçi",
  "Aktüer",
  
  // Sağlık Hizmetleri
  "Doktor",
  "Hemşire",
  "Ebe",
  "Fizyoterapist",
  "Diyetisyen",
  "Psikolog",
  "Psikiyatrist",
  "Veteriner",
  "Veteriner Hekim",
  "Pet Kuaförü",
  "Hayvan Bakıcısı",
  "Hasta Bakıcısı",
  "Yaşlı Bakımı",
  "Bebek Bakıcısı",
  "Çocuk Bakıcısı",
  
  // Otomotiv
  "Oto Elektrikçi",
  "Oto Tamircisi",
  "Kaportacı",
  "Boyacı (Oto)",
  "Lastikçi",
  "Oto Cam",
  "Oto Klima",
  "Oto Ses Sistemi",
  "Oto Alarm",
  "Oto Yıkama",
  "Detailing",
  "Araç Muayene",
  "Ehliyet Kursu",
  "Sürücü Kursu",
  "Motorsiklet Tamiri",
  "Bisiklet Tamiri",
  
  // Etkinlik ve Organizasyon
  "Düğün Organizatörü",
  "Etkinlik Organizatörü",
  "Doğum Günü Organizatörü",
  "Nişan Organizatörü",
  "Kına Organizatörü",
  "Sünnet Organizatörü",
  "Mezuniyet Organizatörü",
  "Şirket Etkinliği",
  "Konser Organizatörü",
  "Festival Organizatörü",
  "Fuar Organizatörü",
  "Seminer Organizatörü",
  "Workshop Organizatörü",
  "DJ",
  "Müzisyen",
  "Ses Teknisyeni",
  "Işık Teknisyeni",
  "Sahne Tasarımı",
  "Dekoratör",
  "Çiçekçi",
  "Pastane",
  "Catering",
  "Aşçı",
  "Garson",
  "Barista",
  "Barmen",
  
  // Diğer Hizmetler
  "Tercüman",
  "Rehber",
  "Turist Rehberi",
  "Şoför",
  "Valet",
  "Güvenlik",
  "Temizlik Görevlisi",
  "Bahçıvan",
  "Kapıcı",
  "Asistan",
  "Sekreter",
  "Veri Girişi",
  "Anket Yapma",
  "Araştırma",
  "Danışmanlık",
  "Koçluk",
  "Yaşam Koçu",
  "İş Koçu",
  "Kariyer Koçu",
  "Eğitim Koçu",
  "Spor Koçu",
  "Beslenme Koçu",
  "Motivasyon Koçu"
];

export default function ServiceProviderRegistrationPage() {
  const [currentStep, setCurrentStep] = useState(1);
  const [searchTerm, setSearchTerm] = useState("");
  const [selectedServices, setSelectedServices] = useState<string[]>([]);
  const [showDropdown, setShowDropdown] = useState(false);
  const [accountType, setAccountType] = useState<"individual" | "company" | null>(null);
  const [kvkkAccepted, setKvkkAccepted] = useState(false);
  const [showExistingUserModal, setShowExistingUserModal] = useState(false);
  const [existingUserType, setExistingUserType] = useState<'email' | 'phone' | null>(null);
  const [existingUserValue, setExistingUserValue] = useState('');
  const [registrationSuccess, setRegistrationSuccess] = useState(false);
  const dropdownRef = useRef<HTMLDivElement>(null);
  const [formData, setFormData] = useState({
    // Şahıs bilgileri
    firstName: "",
    lastName: "",
    phone: "",
    email: "",
    // Şirket bilgileri
    companyName: "",
    companyPhone: "",
    companyEmail: "",
    contactPerson: "",
    // Yetenekler ve uzmanlık
    skills: "",
    experience: "",
    description: "",
    // Şifre
    password: "",
    confirmPassword: "",
  });

  // Dropdown dışına tıklandığında kapat
  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
        setShowDropdown(false);
      }
    };

    document.addEventListener('mousedown', handleClickOutside);
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, []);

  // Arama sonuçlarını filtrele - her harf girildikçe daha hassas filtreleme
  const filteredServices = HIZMETLER.filter(service => {
    const serviceLower = service.toLowerCase();
    const searchLower = searchTerm.toLowerCase().trim();
    
    if (searchLower === '') return true;
    
    // Kelime başlangıcından eşleşme kontrolü (öncelikli)
    if (serviceLower.startsWith(searchLower)) {
      return true;
    }
    
    // Kelime içinde eşleşme kontrolü
    if (serviceLower.includes(searchLower)) {
      return true;
    }
    
    // Kelimelerin başlangıcında eşleşme kontrolü (örn: "web tas" -> "Web Tasarım")
    const words = serviceLower.split(' ');
    const searchWords = searchLower.split(' ');
    
    if (searchWords.length === 1) {
      // Tek kelime arama - herhangi bir kelimenin başlangıcında eşleşme
      return words.some(word => word.startsWith(searchLower));
    } else {
      // Çoklu kelime arama - sıralı eşleşme
      let searchIndex = 0;
      for (let word of words) {
        if (searchIndex < searchWords.length && word.startsWith(searchWords[searchIndex])) {
          searchIndex++;
        }
      }
      return searchIndex === searchWords.length;
    }
  }).sort((a, b) => {
    // Sonuçları relevansa göre sırala
    const aLower = a.toLowerCase();
    const bLower = b.toLowerCase();
    const searchLower = searchTerm.toLowerCase().trim();
    
    if (searchLower === '') return 0;
    
    // Tam başlangıç eşleşmesi en üstte
    const aStartsWith = aLower.startsWith(searchLower);
    const bStartsWith = bLower.startsWith(searchLower);
    
    if (aStartsWith && !bStartsWith) return -1;
    if (!aStartsWith && bStartsWith) return 1;
    
    // Kelime başlangıcı eşleşmesi
    const aWordsMatch = aLower.split(' ').some(word => word.startsWith(searchLower));
    const bWordsMatch = bLower.split(' ').some(word => word.startsWith(searchLower));
    
    if (aWordsMatch && !bWordsMatch) return -1;
    if (!aWordsMatch && bWordsMatch) return 1;
    
    // Alfabetik sıralama
    return a.localeCompare(b, 'tr');
  });

  const handleServiceSelect = (service: string) => {
    if (!selectedServices.includes(service) && selectedServices.length < 5) {
      setSelectedServices([...selectedServices, service]);
    }
    setSearchTerm("");
    setShowDropdown(false);
  };

  const removeService = (service: string) => {
    setSelectedServices(selectedServices.filter(s => s !== service));
  };

  const handleSearchFocus = () => {
    setShowDropdown(true);
  };

  const handleSearchChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setSearchTerm(e.target.value);
    setShowDropdown(true);
  };

  const handleNextStep = async () => {
    if (currentStep === 1 && selectedServices.length > 0) {
      setCurrentStep(2);
    } else if (currentStep === 2 && accountType) {
      setCurrentStep(3);
    } else if (currentStep === 3 && isStep3Valid()) {
      // Kullanıcı kontrolü yap
      const email = accountType === "individual" ? formData.email : formData.companyEmail;
      const phone = accountType === "individual" ? formData.phone : formData.companyPhone;
      
      const userCheck = await checkExistingUser(email, phone);
      
      if (userCheck.exists) {
        setExistingUserType(userCheck.type as 'email' | 'phone');
        setExistingUserValue(userCheck.value);
        setShowExistingUserModal(true);
        return;
      }
      
      setCurrentStep(4);
    } else if (currentStep === 4 && isStep4Valid()) {
      setCurrentStep(5);
    } else if (currentStep === 5 && isStep5Valid()) {
      handleRegistration();
    }
  };

  const isStep3Valid = () => {
    if (accountType === "individual") {
      return formData.firstName && formData.lastName && formData.phone && formData.email && kvkkAccepted;
    } else if (accountType === "company") {
      return formData.companyName && formData.companyPhone && formData.companyEmail && formData.contactPerson && kvkkAccepted;
    }
    return false;
  };

  const isStep4Valid = () => {
    return formData.skills.trim() && formData.experience.trim() && formData.description.trim();
  };

  const isStep5Valid = () => {
    return formData.password && formData.confirmPassword && formData.password === formData.confirmPassword && formData.password.length >= 6;
  };

  // Mock kullanıcı veritabanı kontrolü
  const checkExistingUser = async (email: string, phone: string) => {
    // Gerçek uygulamada bu API çağrısı olacak
    const mockUsers = [
      { email: 'test@example.com', phone: '05551234567' },
      { email: 'demo@test.com', phone: '05559876543' },
      { email: 'ahmet@gmail.com', phone: '05321234567' },
      { email: 'mehmet@hotmail.com', phone: '05339876543' },
    ];
    
    const existingByEmail = mockUsers.find(user => user.email.toLowerCase() === email.toLowerCase());
    const existingByPhone = mockUsers.find(user => user.phone === phone);
    
    if (existingByEmail) {
      return { exists: true, type: 'email', value: email };
    }
    if (existingByPhone) {
      return { exists: true, type: 'phone', value: phone };
    }
    
    return { exists: false, type: null, value: '' };
  };

  const handleRegistration = () => {
    // Kayıt işlemi burada yapılacak
    // Burada API çağrısı yapılabilir
    
    // Başarı sayfasını göster
    setRegistrationSuccess(true);
    
    // 3 saniye sonra profil sayfasına yönlendir
    setTimeout(() => {
      window.location.href = '/profil';
    }, 3000);
  };

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    });
  };

  const handlePrevStep = () => {
    if (currentStep > 1) {
      setCurrentStep(currentStep - 1);
    }
  };

  return (
    <>
      {/* Existing User Modal */}
      {showExistingUserModal && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
          <div className="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
            <div className="text-center mb-6">
              <div className="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-red-100 mb-4">
                <svg className="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
              </div>
              <h3 className="text-xl font-bold text-gray-900 mb-2">Bu bilgiler zaten kayıtlı!</h3>
              <p className="text-gray-600">
                {existingUserType === 'email' 
                  ? `${existingUserValue} e-posta adresi` 
                  : `${existingUserValue} telefon numarası`
                } ile daha önce kayıt olunmuş.
              </p>
            </div>

            <div className="space-y-3">
              <Link
                href="/hizmet-veren-girisi"
                className="w-full flex items-center justify-center px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors"
              >
                <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                Giriş Yap
              </Link>
              
              <Link
                href="/sifremi-unuttum"
                className="w-full flex items-center justify-center px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors"
              >
                <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                Şifremi Unuttum
              </Link>

              <button
                onClick={() => setShowExistingUserModal(false)}
                className="w-full flex items-center justify-center px-4 py-3 border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold rounded-xl transition-colors"
              >
                <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Farklı Bilgilerle Devam Et
              </button>
            </div>

            <div className="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
              <div className="flex items-start">
                <svg className="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div className="text-sm text-blue-800">
                  <p className="font-medium mb-1">Bilgi:</p>
                  <p>Eğer bu hesap size ait değilse, farklı e-posta adresi ve telefon numarası ile kayıt olabilirsiniz.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      )}

      <div className="min-h-screen bg-gradient-to-br from-indigo-50 via-blue-50 to-purple-50 pt-24 pb-8 px-4">
      <div className="max-w-2xl mx-auto">
        {/* Progress Bar */}
        <div className="mb-8">
          <div className="flex items-center justify-between mb-4">
            <span className="text-sm font-medium text-indigo-600">Adım {currentStep} / 5</span>
            <span className="text-sm text-gray-500">
              {currentStep === 1 && "Hizmet Seçimi"}
              {currentStep === 2 && "Hesap Türü"}
              {currentStep === 3 && "Kişisel Bilgiler"}
              {currentStep === 4 && "Yetenekler"}
              {currentStep === 5 && "Şifre ve Kayıt"}
            </span>
          </div>
          <div className="w-full bg-gray-200 rounded-full h-2">
            <div className="bg-indigo-600 h-2 rounded-full transition-all duration-300" style={{ width: `${(currentStep / 5) * 100}%` }}></div>
          </div>
        </div>

        {/* Main Card */}
        <div className="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
          
          {/* Registration Success Page */}
          {registrationSuccess ? (
            <div className="text-center py-12">
              <div className="mx-auto h-24 w-24 flex items-center justify-center rounded-full bg-green-100 mb-6">
                <svg className="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              
              <h1 className="text-3xl font-bold text-gray-900 mb-4">
                Kayıt Başarıyla Tamamlandı! 🎉
              </h1>
              
              <p className="text-lg text-gray-600 mb-8">
                Hoş geldin! OnlineUsta ailesine katıldığın için teşekkürler.
              </p>
              
              <div className="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                <div className="flex items-start">
                  <svg className="w-6 h-6 text-green-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <div className="text-left">
                    <h3 className="font-semibold text-green-800 mb-2">Sırada Ne Var?</h3>
                    <ul className="text-sm text-green-700 space-y-1">
                      <li>• Profilini tamamla ve fotoğraf ekle</li>
                      <li>• İş örneklerin ve referanslarını paylaş</li>
                      <li>• Müşteri tekliflerini almaya başla</li>
                      <li>• Kazanmaya başla! 💰</li>
                    </ul>
                  </div>
                </div>
              </div>
              
              <div className="space-y-4">
                <p className="text-gray-500">
                  3 saniye içinde profil sayfasına yönlendirileceksin...
                </p>
                
                <div className="flex justify-center space-x-4">
                  <Link
                    href="/profil"
                    className="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors"
                  >
                    <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profilime Git
                  </Link>
                  
                  <Link
                    href="/"
                    className="inline-flex items-center px-6 py-3 border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold rounded-xl transition-colors"
                  >
                    <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Ana Sayfaya Dön
                  </Link>
                </div>
              </div>
            </div>
          ) : (
            <>
              {/* Normal Registration Form */}
          {/* Header */}
          <div className="text-center mb-8">
            <div className="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-indigo-100 mb-4">
              {currentStep === 1 && (
                <svg className="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              )}
              {currentStep === 2 && (
                <svg className="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              )}
              {currentStep === 3 && (
                <svg className="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              )}
              {currentStep === 4 && (
                <svg className="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
              )}
              {currentStep === 5 && (
                <svg className="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              )}
            </div>
            <h1 className="text-2xl font-bold text-gray-900 mb-2">
              {currentStep === 1 && "Hangi hizmeti arıyorsun?"}
              {currentStep === 2 && "Hesap türünü seç"}
              {currentStep === 3 && (accountType === "individual" ? "Kişisel Bilgilerin" : "Şirket Bilgileri")}
              {currentStep === 4 && "Yeteneklerini anlat"}
              {currentStep === 5 && "Şifreni oluştur"}
            </h1>
            <p className="text-gray-600 text-sm">
              {currentStep === 1 && "Sunduğun hizmetleri seç (maksimum 5 hizmet) ve müşterilerin seni kolayca bulsun"}
              {currentStep === 2 && "Bireysel mi yoksa şirket hesabı mı açmak istiyorsun?"}
              {currentStep === 3 && (accountType === "individual" 
                ? "Profilini oluşturmak için kişisel bilgilerini gir"
                : "Şirket profilini oluşturmak için şirket bilgilerini gir"
              )}
              {currentStep === 4 && "Uzmanlık alanlarını ve deneyimlerini paylaş"}
              {currentStep === 5 && "Hesabını güvenli bir şifre ile koru"}
            </p>
          </div>

          {/* Previous Step Button - Top */}
          {currentStep > 1 && (
            <div className="pb-6 mb-6 border-b border-gray-200">
              <button
                type="button"
                onClick={handlePrevStep}
                className="flex items-center text-gray-600 hover:text-gray-800 font-medium transition-colors px-4 py-2 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
              >
                <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7" />
                </svg>
                Önceki adım
              </button>
            </div>
          )}

          {/* Step 1: Service Selection */}
          {currentStep === 1 && (
            <>
              {/* Search Input */}
              <div className="relative mb-6" ref={dropdownRef}>
            <div className="relative">
              <Input
                type="text"
                placeholder="Hangi hizmeti arıyorsun?"
                value={searchTerm}
                onChange={handleSearchChange}
                onFocus={handleSearchFocus}
                className="w-full h-14 pl-12 pr-20 text-lg border-2 border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
              />
              <div className="absolute left-4 top-1/2 transform -translate-y-1/2">
                <svg className="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
              <Button className="absolute right-2 top-1/2 transform -translate-y-1/2 h-10 px-6 bg-green-500 hover:bg-green-600 text-white rounded-xl">
                Ara
              </Button>
            </div>

            {/* Dropdown */}
            {showDropdown && (
              <div className="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-200 rounded-xl shadow-lg z-50 max-h-60 overflow-y-auto">
                {filteredServices.length > 0 ? (
                  filteredServices.map((service, index) => (
                    <button
                      key={index}
                      onClick={() => handleServiceSelect(service)}
                      disabled={selectedServices.includes(service) || selectedServices.length >= 5}
                      className={`w-full text-left px-4 py-3 transition-colors border-b border-gray-100 last:border-b-0 ${
                        selectedServices.includes(service) 
                          ? "bg-gray-100 text-gray-500 cursor-not-allowed" 
                          : selectedServices.length >= 5
                          ? "bg-gray-50 text-gray-400 cursor-not-allowed"
                          : "hover:bg-gray-50"
                      }`}
                    >
                      <div className="flex items-center">
                        <div className="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                          <svg className="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                          </svg>
                        </div>
                        <span className="font-medium text-gray-900">{service}</span>
                      </div>
                    </button>
                  ))
                ) : (
                  <div className="px-4 py-3 text-gray-500 text-center">
                    Aradığınız hizmet bulunamadı
                  </div>
                )}
              </div>
            )}
          </div>

              {/* Selected Services */}
              {selectedServices.length > 0 && (
                <div className="mb-8">
                  <div className="flex items-center justify-between mb-4">
                    <h3 className="text-lg font-semibold text-gray-900">Seçilen Hizmetler</h3>
                    <span className={`text-sm font-medium px-3 py-1 rounded-full ${
                      selectedServices.length >= 5 
                        ? "bg-red-100 text-red-700" 
                        : "bg-indigo-100 text-indigo-700"
                    }`}>
                      {selectedServices.length}/5
                    </span>
                  </div>
                  <div className="flex flex-wrap gap-3">
                    {selectedServices.map((service, index) => (
                      <div
                        key={index}
                        className="flex items-center bg-indigo-100 text-indigo-800 px-4 py-2 rounded-full"
                      >
                        <span className="font-medium">{service}</span>
                        <button
                          onClick={() => removeService(service)}
                          className="ml-2 text-indigo-600 hover:text-indigo-800"
                        >
                          <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </div>
                    ))}
                  </div>
                  {selectedServices.length >= 5 && (
                    <div className="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                      <div className="flex items-start">
                        <svg className="w-5 h-5 text-amber-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <div className="text-sm text-amber-800">
                          <p className="font-medium">Maksimum hizmet sayısına ulaştın!</p>
                          <p>En fazla 5 hizmet seçebilirsin. Değişiklik yapmak için mevcut hizmetlerden birini kaldır.</p>
                        </div>
                      </div>
                    </div>
                  )}
                </div>
              )}
            </>
          )}

          {/* Step 2: Account Type Selection */}
          {currentStep === 2 && (
            <div className="space-y-6">
              {/* Account Type Options */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                {/* Individual Account */}
                <button
                  onClick={() => setAccountType("individual")}
                  className={`p-6 rounded-2xl border-2 transition-all duration-200 text-left ${
                    accountType === "individual"
                      ? "border-indigo-500 bg-indigo-50 shadow-lg"
                      : "border-gray-200 hover:border-gray-300 hover:shadow-md"
                  }`}
                >
                  <div className="flex items-center mb-4">
                    <div className={`w-12 h-12 rounded-full flex items-center justify-center ${
                      accountType === "individual" ? "bg-indigo-100" : "bg-gray-100"
                    }`}>
                      <svg className={`w-6 h-6 ${
                        accountType === "individual" ? "text-indigo-600" : "text-gray-600"
                      }`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                    </div>
                    {accountType === "individual" && (
                      <div className="ml-auto">
                        <div className="w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center">
                          <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                          </svg>
                        </div>
                      </div>
                    )}
                  </div>
                  <h3 className={`text-xl font-semibold mb-2 ${
                    accountType === "individual" ? "text-indigo-900" : "text-gray-900"
                  }`}>
                    Bireysel (Şahıs)
                  </h3>
                  <p className={`text-sm ${
                    accountType === "individual" ? "text-indigo-700" : "text-gray-600"
                  }`}>
                    Kendi adına hizmet vermek istiyorsan bu seçeneği seç. Hızlı kayıt ve kolay başlangıç.
                  </p>
                  <div className="mt-4 space-y-2">
                    <div className="flex items-center text-sm text-gray-600">
                      <svg className="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                      </svg>
                      Hızlı kayıt
                    </div>
                    <div className="flex items-center text-sm text-gray-600">
                      <svg className="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                      </svg>
                      Basit vergi işlemleri
                    </div>
                  </div>
                </button>

                {/* Company Account */}
                <button
                  onClick={() => setAccountType("company")}
                  className={`p-6 rounded-2xl border-2 transition-all duration-200 text-left ${
                    accountType === "company"
                      ? "border-indigo-500 bg-indigo-50 shadow-lg"
                      : "border-gray-200 hover:border-gray-300 hover:shadow-md"
                  }`}
                >
                  <div className="flex items-center mb-4">
                    <div className={`w-12 h-12 rounded-full flex items-center justify-center ${
                      accountType === "company" ? "bg-indigo-100" : "bg-gray-100"
                    }`}>
                      <svg className={`w-6 h-6 ${
                        accountType === "company" ? "text-indigo-600" : "text-gray-600"
                      }`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                      </svg>
                    </div>
                    {accountType === "company" && (
                      <div className="ml-auto">
                        <div className="w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center">
                          <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                          </svg>
                        </div>
                      </div>
                    )}
                  </div>
                  <h3 className={`text-xl font-semibold mb-2 ${
                    accountType === "company" ? "text-indigo-900" : "text-gray-900"
                  }`}>
                    Şirket
                  </h3>
                  <p className={`text-sm ${
                    accountType === "company" ? "text-indigo-700" : "text-gray-600"
                  }`}>
                    Şirket adına hizmet vermek istiyorsan bu seçeneği seç. Profesyonel görünüm ve ekip yönetimi.
                  </p>
                  <div className="mt-4 space-y-2">
                    <div className="flex items-center text-sm text-gray-600">
                      <svg className="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                      </svg>
                      Profesyonel profil
                    </div>
                    <div className="flex items-center text-sm text-gray-600">
                      <svg className="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                      </svg>
                      Ekip yönetimi
                    </div>
                  </div>
                </button>
                             </div>
             </div>
           )}

          {/* Step 3: Personal/Company Information */}
          {currentStep === 3 && (
            <div className="space-y-6">
              {accountType === "individual" ? (
                // Şahıs Formu
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label htmlFor="firstName" className="block text-sm font-medium text-gray-700 mb-2">
                      Ad *
                    </label>
                    <Input
                      id="firstName"
                      name="firstName"
                      type="text"
                      placeholder="Adınızı girin"
                      value={formData.firstName}
                      onChange={handleInputChange}
                      className="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                      required
                    />
                  </div>

                  <div>
                    <label htmlFor="lastName" className="block text-sm font-medium text-gray-700 mb-2">
                      Soyad *
                    </label>
                    <Input
                      id="lastName"
                      name="lastName"
                      type="text"
                      placeholder="Soyadınızı girin"
                      value={formData.lastName}
                      onChange={handleInputChange}
                      className="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                      required
                    />
                  </div>

                  <div>
                    <label htmlFor="phone" className="block text-sm font-medium text-gray-700 mb-2">
                      Telefon Numarası *
                    </label>
                    <Input
                      id="phone"
                      name="phone"
                      type="tel"
                      placeholder="0555 123 45 67"
                      value={formData.phone}
                      onChange={handleInputChange}
                      className="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                      required
                    />
                  </div>

                  <div>
                    <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-2">
                      E-posta Adresi *
                    </label>
                    <Input
                      id="email"
                      name="email"
                      type="email"
                      placeholder="ornek@email.com"
                      value={formData.email}
                      onChange={handleInputChange}
                      className="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                      required
                    />
                  </div>
                </div>
              ) : (
                // Şirket Formu
                <div className="space-y-6">
                  <div>
                    <label htmlFor="companyName" className="block text-sm font-medium text-gray-700 mb-2">
                      Şirket/İşletme Adı *
                    </label>
                    <Input
                      id="companyName"
                      name="companyName"
                      type="text"
                      placeholder="Şirket adınızı girin"
                      value={formData.companyName}
                      onChange={handleInputChange}
                      className="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                      required
                    />
                  </div>

                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                      <label htmlFor="companyPhone" className="block text-sm font-medium text-gray-700 mb-2">
                        Şirket Telefonu *
                      </label>
                      <Input
                        id="companyPhone"
                        name="companyPhone"
                        type="tel"
                        placeholder="0212 123 45 67"
                        value={formData.companyPhone}
                        onChange={handleInputChange}
                        className="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                        required
                      />
                    </div>

                    <div>
                      <label htmlFor="companyEmail" className="block text-sm font-medium text-gray-700 mb-2">
                        Şirket E-postası *
                      </label>
                      <Input
                        id="companyEmail"
                        name="companyEmail"
                        type="email"
                        placeholder="info@sirket.com"
                        value={formData.companyEmail}
                        onChange={handleInputChange}
                        className="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                        required
                      />
                    </div>
                  </div>

                  <div>
                    <label htmlFor="contactPerson" className="block text-sm font-medium text-gray-700 mb-2">
                      İletişim Kişisi (Ad Soyad) *
                    </label>
                    <Input
                      id="contactPerson"
                      name="contactPerson"
                      type="text"
                      placeholder="İletişim kişisinin adı soyadı"
                      value={formData.contactPerson}
                      onChange={handleInputChange}
                      className="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                      required
                    />
                  </div>
                </div>
              )}

              {/* KVKK Onay Kutusu */}
              <div className="mt-8 p-6 bg-gray-50 rounded-xl border border-gray-200">
                <div className="flex items-start space-x-3">
                  <div className="flex items-center h-5">
                    <input
                      id="kvkk-consent"
                      name="kvkk-consent"
                      type="checkbox"
                      checked={kvkkAccepted}
                      onChange={(e) => setKvkkAccepted(e.target.checked)}
                      className="w-4 h-4 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500 focus:ring-2"
                    />
                  </div>
                  <div className="text-sm">
                    <label htmlFor="kvkk-consent" className="font-medium text-gray-900 cursor-pointer">
                      KVKK Aydınlatma Metni ve Gizlilik Politikası *
                    </label>
                    <p className="text-gray-600 mt-1">
                      Kişisel verilerimin işlenmesine ilişkin{" "}
                      <a href="/gizlilik-politikasi" target="_blank" className="text-indigo-600 hover:text-indigo-800 underline">
                        Gizlilik Politikası
                      </a>
                      'nı okudum, anladım ve kabul ediyorum. Kişisel verilerimin belirtilen amaçlar doğrultusunda işlenmesine onay veriyorum.
                    </p>
                  </div>
                </div>
              </div>

                        </div>
          )}

          {/* Step 4: Skills and Experience */}
          {currentStep === 4 && (
            <div className="space-y-6">
              <div>
                <label htmlFor="skills" className="block text-sm font-medium text-gray-700 mb-2">
                  Yeteneklerin ve Uzmanlık Alanların *
                </label>
                <textarea
                  id="skills"
                  name="skills"
                  rows={4}
                  placeholder="Örnek: Elektrik tesisatı, aydınlatma sistemleri, pano montajı, arıza tespiti..."
                  value={formData.skills}
                  onChange={handleInputChange}
                  className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none"
                  required
                />
              </div>

              <div>
                <label htmlFor="experience" className="block text-sm font-medium text-gray-700 mb-2">
                  Deneyimin *
                </label>
                <textarea
                  id="experience"
                  name="experience"
                  rows={3}
                  placeholder="Örnek: 5 yıllık deneyim, 200+ proje tamamladım, konut ve işyeri elektrik işleri..."
                  value={formData.experience}
                  onChange={handleInputChange}
                  className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none"
                  required
                />
              </div>

              <div>
                <label htmlFor="description" className="block text-sm font-medium text-gray-700 mb-2">
                  Kendini Tanıt *
                </label>
                <textarea
                  id="description"
                  name="description"
                  rows={4}
                  placeholder="Müşterilere kendini tanıt. Çalışma tarzın, güçlü yanların ve neden seni seçmeleri gerektiğini anlat..."
                  value={formData.description}
                  onChange={handleInputChange}
                  className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none"
                  required
                />
              </div>

              <div className="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div className="flex items-start">
                  <svg className="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <div className="text-sm text-blue-800">
                    <p className="font-medium mb-1">İpucu:</p>
                    <p>Detaylı ve samimi bir tanıtım yazarak müşterilerin güvenini kazanabilirsin. Önceki işlerinden örnekler verebilir, çalışma prensiplerini paylaşabilirsin.</p>
                  </div>
                </div>
              </div>
            </div>
          )}

          {/* Step 5: Password Creation */}
          {currentStep === 5 && (
            <div className="space-y-6">
              <div>
                <label htmlFor="password" className="block text-sm font-medium text-gray-700 mb-2">
                  Şifre *
                </label>
                <Input
                  id="password"
                  name="password"
                  type="password"
                  placeholder="En az 6 karakter"
                  value={formData.password}
                  onChange={handleInputChange}
                  className="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                  required
                />
              </div>

              <div>
                <label htmlFor="confirmPassword" className="block text-sm font-medium text-gray-700 mb-2">
                  Şifre Tekrar *
                </label>
                <Input
                  id="confirmPassword"
                  name="confirmPassword"
                  type="password"
                  placeholder="Şifreni tekrar gir"
                  value={formData.confirmPassword}
                  onChange={handleInputChange}
                  className="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                  required
                />
                {formData.confirmPassword && formData.password !== formData.confirmPassword && (
                  <p className="text-red-600 text-sm mt-1">Şifreler eşleşmiyor</p>
                )}
              </div>

              <div className="bg-green-50 border border-green-200 rounded-lg p-4">
                <div className="flex items-start">
                  <svg className="w-5 h-5 text-green-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <div className="text-sm text-green-800">
                    <p className="font-medium mb-1">Neredeyse bitti!</p>
                    <p>Güvenli bir şifre oluştur ve OnlineUsta ailesine katıl. Kayıt olduktan sonra hemen müşteri tekliflerini almaya başlayabilirsin.</p>
                  </div>
                </div>
              </div>

              {/* Registration Summary */}
              <div className="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <h3 className="font-semibold text-gray-900 mb-4">Kayıt Özeti</h3>
                <div className="space-y-2 text-sm">
                  <div className="flex justify-between">
                    <span className="text-gray-600">Hesap Türü:</span>
                    <span className="font-medium">{accountType === "individual" ? "Bireysel" : "Şirket"}</span>
                  </div>
                  <div className="flex justify-between">
                    <span className="text-gray-600">Hizmet Sayısı:</span>
                    <span className="font-medium">{selectedServices.length} hizmet</span>
                  </div>
                  <div className="flex justify-between">
                    <span className="text-gray-600">E-posta:</span>
                    <span className="font-medium">{accountType === "individual" ? formData.email : formData.companyEmail}</span>
                  </div>
                </div>
              </div>
            </div>
          )}

          {/* Next Step Button - Bottom */}
          <div className="flex justify-between items-center pt-6 mt-6 border-t border-gray-200">
            {currentStep === 1 ? (
              <Link
                href="/hizmet-veren-girisi"
                className="flex items-center text-gray-600 hover:text-gray-800 font-medium transition-colors px-4 py-2 rounded-lg hover:bg-gray-50"
              >
                <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7" />
                </svg>
                Giriş sayfasına dön
              </Link>
            ) : (
              <div></div>
            )}
            
            <Button
              onClick={handleNextStep}
              disabled={
                (currentStep === 1 && selectedServices.length === 0) ||
                (currentStep === 2 && !accountType) ||
                (currentStep === 3 && !isStep3Valid()) ||
                (currentStep === 4 && !isStep4Valid()) ||
                (currentStep === 5 && !isStep5Valid())
              }
              className={`flex items-center px-8 py-3 rounded-xl font-semibold transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 ${
                ((currentStep === 1 && selectedServices.length > 0) ||
                 (currentStep === 2 && accountType) ||
                 (currentStep === 3 && isStep3Valid()) ||
                 (currentStep === 4 && isStep4Valid()) ||
                 (currentStep === 5 && isStep5Valid()))
                  ? "bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg hover:shadow-xl"
                  : "bg-gray-300 text-gray-500 cursor-not-allowed"
              }`}
            >
              {currentStep === 5 ? "Kayıt Ol" : "Devam Et"}
              <svg className="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7" />
              </svg>
            </Button>
          </div>
            </>
          )}
 
        </div>


      </div>
    </div>
    </>
  );
} 