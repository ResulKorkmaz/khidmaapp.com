"use client";

import { useState, useEffect, useRef } from "react";
import { Button, Input, useToast, ToastContainer } from "@onlineusta/ui";
import Link from "next/link";

// Service category interface
interface ServiceCategory {
  id: number;
  name: string;
  slug: string;
  description?: string;
  icon?: string;
  color?: string;
}

// Location interfaces
interface City {
  id: number;
  name: string;
  slug: string;
  plateCode?: string;
  region?: string;
}

interface District {
  id: number;
  name: string;
  slug: string;
}

export default function ServiceProviderRegistrationPage() {
  const [currentStep, setCurrentStep] = useState(1);
  const [searchTerm, setSearchTerm] = useState("");
  const [selectedServices, setSelectedServices] = useState<ServiceCategory[]>([]);
  const [availableServices, setAvailableServices] = useState<ServiceCategory[]>([]);
  const [loading, setLoading] = useState(false);
  const [showDropdown, setShowDropdown] = useState(false);
  
  // Location states
  const [cities, setCities] = useState<City[]>([]);
  const [districts, setDistricts] = useState<District[]>([]);
  const [loadingCities, setLoadingCities] = useState(false);
  const [loadingDistricts, setLoadingDistricts] = useState(false);
  const [accountType, setAccountType] = useState<"individual" | "company" | null>(null);
  const [kvkkAccepted, setKvkkAccepted] = useState(false);
  const [showExistingUserModal, setShowExistingUserModal] = useState(false);
  const [existingUserType, setExistingUserType] = useState<"email" | "phone" | null>(null);
  const [existingUserValue, setExistingUserValue] = useState("");
  const [registrationSuccess, setRegistrationSuccess] = useState(false);
  const dropdownRef = useRef<HTMLDivElement>(null);
  
  // Toast notification hook
  const { toasts, success, error, warning, removeToast } = useToast();

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
    // Konum bilgileri
    city: "",
    district: "",
    // Yetenekler ve uzmanlık
    skills: "",
    experience: "",
    description: "",
    // Şifre
    password: "",
    confirmPassword: "",
  });

  // Load all available services on component mount
  useEffect(() => {
    loadAllServices();
  }, []);

  const loadCities = async () => {
    try {
      setLoadingCities(true);
      const response = await fetch('/api/locations');
      const result = await response.json();
      
      if (result.success) {
        setCities(result.cities);
      } else {
        console.error('Şehir yükleme hatası:', result.error);
      }
    } catch (error) {
      console.error('Şehir yükleme hatası:', error);
    } finally {
      setLoadingCities(false);
    }
  };

  const loadDistricts = async (cityName: string) => {
    try {
      setLoadingDistricts(true);
      // Önce şehir ID'sini bul
      const selectedCity = cities.find(city => city.name === cityName);
      if (!selectedCity) {
        setDistricts([]);
        return;
      }
      
      const response = await fetch(`/api/locations?cityId=${selectedCity.id}`);
      const result = await response.json();
      
      if (result.success) {
        setDistricts(result.districts);
      } else {
        console.error('İlçe yükleme hatası:', result.error);
        setDistricts([]);
      }
    } catch (error) {
      console.error('İlçe yükleme hatası:', error);
      setDistricts([]);
    } finally {
      setLoadingDistricts(false);
    }
  };

  const loadAllServices = async () => {
    try {
      setLoading(true);
      
      // Tüm kategorileri çek
      const response = await fetch('/api/categories');
      const result = await response.json();
      
      if (result.success) {
        console.log('Yüklenen kategori sayısı:', result.categories.length);
        setAvailableServices(result.categories);
      } else {
        console.error('Kategori yükleme hatası:', result.error);
      }
    } catch (error) {
      console.error('Hizmet kategorileri yüklenirken hata:', error);
    } finally {
      setLoading(false);
    }
  };

  // Search services dynamically
  const searchServices = async (query: string) => {
    if (!query.trim()) {
      return availableServices;
    }

    try {
      const response = await fetch(`/api/categories?search=${encodeURIComponent(query)}`);
      const result = await response.json();
      
      if (result.success) {
        return result.categories;
      }
      
      return [];
    } catch (error) {
      console.error('Arama hatası:', error);
      return availableServices.filter(service => 
        service.name.toLowerCase().includes(query.toLowerCase())
      );
    }
  };

  // Dropdown dışına tıklandığında kapat
  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
        setShowDropdown(false);
      }
    };

    document.addEventListener("mousedown", handleClickOutside);
    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);

  // Filter services based on search term
  const [filteredServices, setFilteredServices] = useState<ServiceCategory[]>([]);

  useEffect(() => {
    const filterServices = async () => {
      if (searchTerm.trim()) {
        const results = await searchServices(searchTerm);
        setFilteredServices(results);
      } else {
        setFilteredServices(availableServices); // Tüm kategorileri göster
      }
    };

    filterServices();
  }, [searchTerm, availableServices]);

  const handleServiceSelect = (service: ServiceCategory) => {
    if (!selectedServices.find(s => s.id === service.id) && selectedServices.length < 5) {
      setSelectedServices([...selectedServices, service]);
    }
    setSearchTerm("");
    setShowDropdown(false);
  };

  const removeService = (serviceId: number) => {
    setSelectedServices(selectedServices.filter((s) => s.id !== serviceId));
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
        setExistingUserType(userCheck.type as "email" | "phone");
        setExistingUserValue(userCheck.value);
        setShowExistingUserModal(true);
        return;
      }

      setCurrentStep(4);
    } else if (currentStep === 4 && isStep4Valid()) {
      setCurrentStep(5);
    } else if (currentStep === 5 && isStep5Valid()) {
      setCurrentStep(6);
    } else if (currentStep === 6 && isStep6Valid()) {
      handleRegistration();
    }
  };

  const isStep3Valid = () => {
    if (accountType === "individual") {
      return (
        formData.firstName &&
        formData.lastName &&
        formData.phone &&
        formData.email &&
        kvkkAccepted
      );
    } else if (accountType === "company") {
      return (
        formData.companyName &&
        formData.companyPhone &&
        formData.companyEmail &&
        formData.contactPerson &&
        kvkkAccepted
      );
    }
    return false;
  };

  const isStep4Valid = () => {
    return formData.city && formData.district;
  };

  const isStep5Valid = () => {
    return formData.skills && formData.experience && formData.description;
  };

  const isStep6Valid = () => {
    return (
      formData.password &&
      formData.confirmPassword &&
      formData.password === formData.confirmPassword &&
      formData.password.length >= 6
    );
  };

  const checkExistingUser = async (email: string, phone: string) => {
    try {
      const response = await fetch("/api/auth/check-user", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ email, phone }),
      });

      const data = await response.json();
      return data;
    } catch (error) {
      console.error("Kullanıcı kontrolü hatası:", error);
      return { exists: false };
    }
  };

  const handleRegistration = async () => {
    try {
      const registrationData = {
        accountType,
        services: selectedServices.map(service => service.name),
        serviceIds: selectedServices.map(service => service.id),
        ...formData,
      };

      const response = await fetch("/api/auth/register", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(registrationData),
      });

      const data = await response.json();

      if (response.ok) {
        setRegistrationSuccess(true);
        success("Kayıt Başarılı!", "Hesabınız başarıyla oluşturuldu. Artık giriş yapabilirsiniz.");
      } else {
        error("Kayıt Hatası", data.error || "Bilinmeyen bir hata oluştu. Lütfen tekrar deneyin.");
      }
    } catch (error) {
      console.error("Kayıt hatası:", error);
      error("Bağlantı Hatası", "Sunucu ile bağlantı kurulamadı. Lütfen internet bağlantınızı kontrol edin.");
    }
  };

  const handleInputChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>,
  ) => {
    const { name, value } = e.target;
    
    // Şehir değiştiğinde ilçe seçimini temizle
    if (name === 'city') {
      setFormData({ ...formData, [name]: value, district: '' });
    } else {
      setFormData({ ...formData, [name]: value });
    }
  };

  const handlePrevStep = () => {
    if (currentStep > 1) {
      setCurrentStep(currentStep - 1);
    }
  };

  // Load cities on component mount
  useEffect(() => {
    loadCities();
  }, []);

  // Load districts when city changes
  useEffect(() => {
    if (formData.city) {
      loadDistricts(formData.city);
    } else {
      setDistricts([]);
    }
  }, [formData.city]);

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
      <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Başlık */}
        <div className="text-center mb-8">
          <h1 className="text-3xl font-bold text-gray-900 mb-2">
            Hizmet Veren Kaydı
          </h1>
              <p className="text-gray-600">
            Müşterilerinize ulaşın, işinizi büyütün
              </p>
            </div>

          {/* Progress Bar */}
          <div className="mb-8">
          <div className="flex items-center justify-between mb-2">
            {[1, 2, 3, 4, 5, 6].map((step) => (
              <div
                key={step}
                className={`w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium ${
                  step <= currentStep
                    ? "bg-blue-600 text-white"
                    : "bg-gray-200 text-gray-500"
                }`}
              >
                {step}
              </div>
            ))}
            </div>
            <div className="w-full bg-gray-200 rounded-full h-2">
              <div
              className="bg-blue-600 h-2 rounded-full transition-all duration-300"
              style={{ width: `${(currentStep / 6) * 100}%` }}
              ></div>
            </div>
          </div>

        {/* Step Content */}
        <div className="bg-white rounded-xl shadow-lg p-8">
                    {/* Step 1: Hizmet Seçimi */}
          {currentStep === 1 && (
            <div>
              <h2 className="text-2xl font-semibold mb-6 text-center">
                Hangi hizmetleri veriyorsunuz?
              </h2>
              <p className="text-gray-600 text-center mb-6">
                Önce ana kategoriyi, sonra uzmanlık alanınızı seçin. En fazla 5 hizmet seçebilirsiniz.
              </p>

              {/* Seçilen Hizmetler */}
              {selectedServices.length > 0 && (
                <div className="mb-6">
                  <h3 className="text-lg font-medium mb-3">Seçilen Hizmetler:</h3>
                  <div className="flex flex-wrap gap-2">
                    {selectedServices.map((service) => (
                      <span
                        key={service.id}
                        className="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"
                      >
                        {service.name}
                        <button
                          onClick={() => removeService(service.id)}
                          className="ml-2 text-blue-600 hover:text-blue-800"
                        >
                          ×
                        </button>
                      </span>
                    ))}
                  </div>
                </div>
              )}

              {selectedServices.length >= 5 && (
                <div className="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                  <p className="text-amber-700 text-sm font-medium">
                    ✓ Maksimum 5 hizmet seçildi. Başka hizmet eklemek için önce mevcut bir hizmeti kaldırın.
                  </p>
                </div>
              )}

              {/* Loading durumu */}
              {loading && (
                <div className="mb-6 text-center">
                  <div className="inline-flex items-center">
                    <div className="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-2"></div>
                    <span className="text-gray-600">Hizmet kategorileri yükleniyor...</span>
                  </div>
                </div>
              )}

              {/* Hizmet Arama */}
              {selectedServices.length < 5 && !loading && (
                <div className="mb-6">
                  <div className="relative" ref={dropdownRef}>
                    <input
                      type="text"
                      placeholder="Hizmet arayın (örn: elektrikçi, tesisatçı...)"
                      value={searchTerm}
                      onChange={handleSearchChange}
                      onFocus={handleSearchFocus}
                      className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />

                    {/* Dropdown */}
                    {showDropdown && (
                      <div className="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        {filteredServices.length > 0 ? (
                          filteredServices.map((service) => (
                            <button
                              key={service.id}
                              onClick={() => handleServiceSelect(service)}
                              className="w-full px-4 py-3 text-left hover:bg-blue-50 focus:bg-blue-50 focus:outline-none border-b border-gray-100 last:border-b-0"
                              disabled={selectedServices.some(s => s.id === service.id)}
                            >
                              <div className="flex items-center justify-between">
                                <div className="flex items-center">
                                  {service.icon && (
                                    <span className="mr-2 text-lg">{service.icon}</span>
                                  )}
                                  <div>
                                    <span className={selectedServices.some(s => s.id === service.id) ? "text-gray-400" : "text-gray-900"}>
                                      {service.name}
                                    </span>
                                    {service.description && (
                                      <p className="text-xs text-gray-500 mt-1">{service.description}</p>
                                    )}
                                  </div>
                                </div>
                                {selectedServices.some(s => s.id === service.id) && (
                                  <span className="text-green-600">✓</span>
                                )}
                              </div>
                            </button>
                          ))
                        ) : (
                          <div className="px-4 py-3 text-gray-500 text-center">
                            {searchTerm ? 'Aradığınız hizmet bulunamadı' : 'Hizmet kategorileri yükleniyor...'}
                          </div>
                        )}
                      </div>
                    )}
                  </div>
                </div>
              )}

              {/* Hizmet kategorisi yoksa uyarı */}
              {!loading && availableServices.length === 0 && (
                <div className="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                  <p className="text-red-700 text-sm font-medium">
                    ⚠️ Hizmet kategorileri yüklenemedi. Lütfen sayfayı yenileyin.
                  </p>
                </div>
              )}
            </div>
          )}

          {/* Step 2: Hesap Türü */}
                {currentStep === 2 && (
            <div>
              <h2 className="text-2xl font-semibold mb-6 text-center">
                Hesap türünüzü seçin
              </h2>
              
              <div className="space-y-4">
                <div
                        onClick={() => setAccountType("individual")}
                  className={`p-6 border-2 rounded-lg cursor-pointer transition-all ${
                          accountType === "individual"
                      ? "border-blue-500 bg-blue-50"
                      : "border-gray-200 hover:border-gray-300"
                        }`}
                      >
                  <div className="flex items-center">
                    <div className={`w-5 h-5 rounded-full border-2 mr-3 ${
                              accountType === "individual"
                        ? "border-blue-500 bg-blue-500"
                        : "border-gray-300"
                    }`}>
                          {accountType === "individual" && (
                        <div className="w-full h-full bg-white rounded-full scale-50"></div>
                          )}
                        </div>
                    <div>
                      <h3 className="text-lg font-medium">Bireysel Hesap</h3>
                      <p className="text-gray-600">Şahıs olarak hizmet veriyorum</p>
                          </div>
                          </div>
                        </div>

                <div
                        onClick={() => setAccountType("company")}
                  className={`p-6 border-2 rounded-lg cursor-pointer transition-all ${
                          accountType === "company"
                      ? "border-blue-500 bg-blue-50"
                      : "border-gray-200 hover:border-gray-300"
                        }`}
                      >
                  <div className="flex items-center">
                    <div className={`w-5 h-5 rounded-full border-2 mr-3 ${
                              accountType === "company"
                        ? "border-blue-500 bg-blue-500"
                        : "border-gray-300"
                    }`}>
                          {accountType === "company" && (
                        <div className="w-full h-full bg-white rounded-full scale-50"></div>
                          )}
                        </div>
                    <div>
                      <h3 className="text-lg font-medium">Kurumsal Hesap</h3>
                      <p className="text-gray-600">Şirket olarak hizmet veriyoruz</p>
                          </div>
                          </div>
                        </div>
                    </div>
                  </div>
                )}

          {/* Step 3: Kişisel/Şirket Bilgileri */}
                {currentStep === 3 && (
                        <div>
              <h2 className="text-2xl font-semibold mb-6 text-center">
                {accountType === "individual" ? "Kişisel Bilgiler" : "Şirket Bilgileri"}
              </h2>

              <div className="space-y-4">
                {accountType === "individual" ? (
                  <>
                    <div className="grid grid-cols-2 gap-4">
                          <Input
                        label="Ad"
                            name="firstName"
                            value={formData.firstName}
                            onChange={handleInputChange}
                            required
                          />
                          <Input
                        label="Soyad"
                            name="lastName"
                            value={formData.lastName}
                            onChange={handleInputChange}
                            required
                          />
                        </div>
                          <Input
                      label="Telefon"
                            name="phone"
                            type="tel"
                            value={formData.phone}
                            onChange={handleInputChange}
                            required
                          />
                          <Input
                      label="E-posta"
                            name="email"
                            type="email"
                            value={formData.email}
                            onChange={handleInputChange}
                            required
                          />
                  </>
                ) : (
                  <>
                          <Input
                      label="Şirket Adı"
                            name="companyName"
                            value={formData.companyName}
                            onChange={handleInputChange}
                            required
                          />
                            <Input
                      label="İletişim Kişisi"
                      name="contactPerson"
                      value={formData.contactPerson}
                      onChange={handleInputChange}
                      required
                    />
                    <Input
                      label="Şirket Telefonu"
                              name="companyPhone"
                              type="tel"
                              value={formData.companyPhone}
                              onChange={handleInputChange}
                              required
                            />
                            <Input
                      label="Şirket E-postası"
                              name="companyEmail"
                              type="email"
                              value={formData.companyEmail}
                              onChange={handleInputChange}
                              required
                            />
                  </>
                )}

                {/* KVKK Onayı */}
                <div className="flex items-start space-x-3 mt-6">
                          <input
                            type="checkbox"
                    id="kvkk"
                            checked={kvkkAccepted}
                            onChange={(e) => setKvkkAccepted(e.target.checked)}
                    className="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                  />
                  <label htmlFor="kvkk" className="text-sm text-gray-700">
                    <Link href="/kvkk" className="text-blue-600 hover:underline">
                      KVKK Metni
                    </Link>
                    'ni okudum ve kabul ediyorum
                  </label>
                        </div>
              </div>
            </div>
          )}

          {/* Step 4: Konum Bilgileri */}
          {currentStep === 4 && (
            <div>
              <h2 className="text-2xl font-semibold mb-6 text-center">
                Hangi bölgede hizmet veriyorsunuz?
              </h2>

              <div className="space-y-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Şehir
                  </label>
                  <select
                    name="city"
                    value={formData.city}
                    onChange={handleInputChange}
                    className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    required
                    disabled={loadingCities}
                  >
                    <option value="">
                      {loadingCities ? "Şehirler yükleniyor..." : "Şehir seçin"}
                    </option>
                    {cities.map((city) => (
                      <option key={city.id} value={city.name}>
                        {city.name}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    İlçe
                          </label>
                  <select
                    name="district"
                    value={formData.district}
                    onChange={handleInputChange}
                    className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    required
                    disabled={!formData.city || loadingDistricts}
                  >
                    <option value="">
                      {loadingDistricts ? "İlçeler yükleniyor..." : "İlçe seçin"}
                    </option>
                    {districts.map((district) => (
                      <option key={district.id} value={district.name}>
                        {district.name}
                      </option>
                    ))}
                  </select>
                      </div>
                    </div>
                  </div>
                )}

          {/* Step 5: Deneyim ve Açıklama */}
          {currentStep === 5 && (
                    <div>
              <h2 className="text-2xl font-semibold mb-6 text-center">
                Deneyiminizi anlatın
              </h2>

              <div className="space-y-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Yetenekleriniz
                      </label>
                      <textarea
                        name="skills"
                        value={formData.skills}
                        onChange={handleInputChange}
                    placeholder="Özel yeteneklerinizi ve uzmanlık alanlarınızı belirtin..."
                    className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 h-20"
                        required
                      />
                    </div>

                    <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Deneyim Yılı
                      </label>
                  <select
                        name="experience"
                        value={formData.experience}
                        onChange={handleInputChange}
                    className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                        required
                  >
                    <option value="">Deneyim yılınızı seçin</option>
                    <option value="0-1">0-1 yıl</option>
                    <option value="1-3">1-3 yıl</option>
                    <option value="3-5">3-5 yıl</option>
                    <option value="5-10">5-10 yıl</option>
                    <option value="10+">10+ yıl</option>
                  </select>
                    </div>

                    <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Kendinizi Tanıtın
                      </label>
                      <textarea
                        name="description"
                        value={formData.description}
                        onChange={handleInputChange}
                    placeholder="Müşterilerinize kendinizi tanıtın, neden sizi tercih etmeliler?"
                    className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 h-32"
                        required
                      />
                      </div>
                    </div>
                  </div>
                )}

          {/* Step 6: Şifre */}
          {currentStep === 6 && (
                    <div>
              <h2 className="text-2xl font-semibold mb-6 text-center">
                Hesap şifrenizi belirleyin
              </h2>

              <div className="space-y-4">
                      <Input
                  label="Şifre"
                        name="password"
                        type="password"
                        value={formData.password}
                        onChange={handleInputChange}
                        required
                  placeholder="En az 6 karakter"
                />
                      <Input
                  label="Şifre Tekrar"
                        name="confirmPassword"
                        type="password"
                        value={formData.confirmPassword}
                        onChange={handleInputChange}
                        required
                  placeholder="Şifrenizi tekrar girin"
                />

                {formData.password && formData.confirmPassword && formData.password !== formData.confirmPassword && (
                  <p className="text-red-600 text-sm">Şifreler eşleşmiyor</p>
                        )}
                    </div>
                  </div>
                )}

          {/* Navigation Buttons */}
          <div className="flex justify-between mt-8">
            <Button
              variant="outline"
              onClick={handlePrevStep}
              disabled={currentStep === 1}
            >
              Geri
            </Button>

                  <Button
                    onClick={handleNextStep}
                    disabled={
                      (currentStep === 1 && selectedServices.length === 0) ||
                      (currentStep === 2 && !accountType) ||
                      (currentStep === 3 && !isStep3Valid()) ||
                      (currentStep === 4 && !isStep4Valid()) ||
                (currentStep === 5 && !isStep5Valid()) ||
                (currentStep === 6 && !isStep6Valid())
              }
            >
              {currentStep === 6 ? "Kayıt Ol" : "İleri"}
            </Button>
          </div>
        </div>

        {/* Existing User Modal */}
        {showExistingUserModal && (
          <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div className="bg-white rounded-lg p-6 max-w-md w-full mx-4">
              <h3 className="text-lg font-semibold mb-4">Kullanıcı Zaten Kayıtlı</h3>
              <p className="text-gray-600 mb-4">
                Bu {existingUserType === "email" ? "e-posta" : "telefon"} numarası ile zaten bir hesap var: {existingUserValue}
              </p>
              <div className="flex space-x-3">
                <Link href="/hizmet-veren-girisi" className="flex-1">
                  <Button className="w-full">Giriş Yap</Button>
                </Link>
                <Button 
                  variant="outline" 
                  onClick={() => setShowExistingUserModal(false)}
                  className="flex-1"
                >
                  Geri Dön
                  </Button>
                </div>
            </div>
          </div>
        )}

        {/* Success Modal */}
        {registrationSuccess && (
          <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div className="bg-white rounded-lg p-6 max-w-md w-full mx-4 text-center">
              <div className="text-green-500 mb-4">
                <svg className="w-16 h-16 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                  <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                </svg>
          </div>
              <h3 className="text-lg font-semibold mb-2">Kayıt Başarılı!</h3>
              <p className="text-gray-600 mb-4">
                Hesabınız başarıyla oluşturuldu. Artık giriş yapabilirsiniz.
              </p>
              <Link href="/hizmet-veren-girisi">
                <Button className="w-full">Giriş Yap</Button>
              </Link>
        </div>
      </div>
        )}
      </div>
      
      {/* Toast Container */}
      <ToastContainer toasts={toasts} onRemove={removeToast} />
    </div>
  );
}