"use client";

import { useState, useEffect } from "react";
import { Button, useCities, useDistricts, useToast, ToastContainer } from "@onlineusta/ui";
import Link from "next/link";
import Image from "next/image";
import Navigation from "../../components/Navigation";
import Footer from "../../components/Footer";
import CreateWebPageModal from "../../components/CreateWebPageModal";

// Service category interface
interface ServiceCategory {
  id: number;
  name: string;
  slug: string;
  description?: string;
  icon?: string;
  color?: string;
}

interface Category {
  id: number;
  name: string;
  experience: string;
  skills: string[];
  minPrice: number | null;
  maxPrice: number | null;
  priceUnit: string;
}

interface User {
  id: string;
  email: string;
  firstName: string;
  lastName: string;
  role: string;
  city: string;
  district: string;
  phone?: string;
  bio?: string;
  experienceYears?: number;
  rating?: number;
  reviewCount?: number;
  completedJobsCount?: number;
  isVerified?: boolean;
  categories?: Category[];
  avatar?: string;
  createdAt?: string;
  lastActiveAt?: string;
  membershipType?: 'STANDARD' | 'PROFESSIONAL' | 'PREMIUM';
  publicPageUrl?: string;
  companyName?: string;
}

interface Offer {
  id: string;
  serviceRequestId: string;
  amount: number;
  description: string;
  status: 'PENDING' | 'ACCEPTED' | 'REJECTED' | 'EXPIRED';
  createdAt: string;
  serviceRequest: {
    id: string;
    title: string;
    description: string;
    category: string;
    city: string;
    district: string;
    budget: number;
    status: string;
    createdAt: string;
  };
}

interface Message {
  id: string;
  senderId: string;
  senderName: string;
  senderRole: 'CLIENT' | 'PROFESSIONAL';
  content: string;
  isRead: boolean;
  createdAt: string;
  conversationId: string;
  serviceRequest?: {
    id: string;
    title: string;
  };
}

interface Conversation {
  id: string;
  clientId: string;
  clientName: string;
  professionalId: string;
  lastMessage: string;
  lastMessageAt: string;
  unreadCount: number;
  serviceRequest?: {
    id: string;
    title: string;
  };
  messages: Message[];
}

interface ServiceRequest {
  id: string;
  title: string;
  description: string;
  budget: number | null;
  city: string;
  district: string;
  createdAt: string;
  category: {
    id: number;
    name: string;
    slug: string;
  };
  user: {
    id: string;
    firstName: string;
    lastName: string;
    email: string;
  };
}

export default function ProfilePage() {
  const [currentUser, setCurrentUser] = useState<User | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [activeSection, setActiveSection] = useState("overview");
  const [recentOffers, setRecentOffers] = useState<Offer[]>([]);
  const [conversations, setConversations] = useState<Conversation[]>([]);
  const [opportunities, setOpportunities] = useState<ServiceRequest[]>([]);
  const [showProfileModal, setShowProfileModal] = useState(false);
  const [showWebPageModal, setShowWebPageModal] = useState(false);
  const [editingField, setEditingField] = useState<string | null>(null);
  const [editValues, setEditValues] = useState<{[key: string]: string}>({});
  const [isEditingAll, setIsEditingAll] = useState(false);
  const [selectedCategories, setSelectedCategories] = useState<string[]>([]);
  const [categorySearch, setCategorySearch] = useState('');
  const [showCategoryDropdown, setShowCategoryDropdown] = useState(false);
  const [selectedCityId, setSelectedCityId] = useState<number | null>(null);
  const [selectedDistrictId, setSelectedDistrictId] = useState<number | null>(null);
  const [availableCategories, setAvailableCategories] = useState<ServiceCategory[]>([]);
  const [categoriesLoading, setCategoriesLoading] = useState(false);
  
  // Toast sistemi
  const toast = useToast();
  
  // API Hooks
  const { cities, loading: citiesLoading } = useCities();
  const { districts, loading: districtsLoading } = useDistricts(selectedCityId);
  
  // Load categories from API
  useEffect(() => {
    const loadCategories = async () => {
      try {
        setCategoriesLoading(true);
        
        // Ana kategorileri çek
        const mainResponse = await fetch('/api/categories');
        const mainResult = await mainResponse.json();
        
        if (mainResult.success) {
          const allCategories: ServiceCategory[] = [];
          
          // Ana kategorileri ekle
          allCategories.push(...mainResult.categories);
          
          // Her ana kategori için alt kategorileri çek
          for (const mainCat of mainResult.categories) {
            const subResponse = await fetch(`/api/categories?parentId=${mainCat.id}`);
            const subResult = await subResponse.json();
            
            if (subResult.success) {
              allCategories.push(...subResult.categories);
            }
          }
          
          setAvailableCategories(allCategories);
        }
      } catch (error) {
        console.error('Categories loading error:', error);
      } finally {
        setCategoriesLoading(false);
      }
    };

    loadCategories();
  }, []);
  
  // Category filtreleme
  const filteredCategories = categorySearch 
    ? availableCategories.filter(cat => 
        cat.name.toLowerCase().includes(categorySearch.toLowerCase())
      )
    : availableCategories;

  // Close dropdown when clicking outside
  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (showCategoryDropdown) {
        const target = event.target as Element;
        if (!target.closest('.category-dropdown-container')) {
          setShowCategoryDropdown(false);
        }
      }
    };

    document.addEventListener('mousedown', handleClickOutside);
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, [showCategoryDropdown]);

  // Kullanıcı bilgilerini yükle
  useEffect(() => {
    const loadUserData = async () => {
      try {
        const isLoggedIn = localStorage.getItem('isLoggedIn');
        const userData = localStorage.getItem('currentUser');
        
        if (isLoggedIn === 'true' && userData) {
          let user: User = JSON.parse(userData);
          
          // Eğer publicPageUrl yoksa ama sayfa oluşturulmuş ise URL'i oluştur
          if (!user.publicPageUrl) {
            // Mock: ayakkabı tamircisi için publicPageUrl set et
            if (user.id === "123") {
              user.publicPageUrl = "/123/ayakkabi-tamiri";
              localStorage.setItem('currentUser', JSON.stringify(user));
            }
          }
          
          setCurrentUser(user);
          
          // API'den tam profil bilgilerini çek
          try {
            const response = await fetch(`/api/profile?userId=${user.id}`);
            const profileData = await response.json();
            
            if (response.ok && profileData.success) {
              // API'den gelen data ile localStorage'ı güncelle
              const updatedUser = {
                ...profileData.user,
                publicPageUrl: profileData.user.publicPageUrl || user.publicPageUrl
              };
              setCurrentUser(updatedUser);
              localStorage.setItem('currentUser', JSON.stringify(updatedUser));
            }
          } catch (apiError) {
            console.error('API profile fetch error:', apiError);
          }

          // Son teklifleri çek
          try {
            const offersResponse = await fetch(`/api/offers?userId=${user.id}&limit=5`);
            const offersData = await offersResponse.json();
            
            if (offersResponse.ok && offersData.success) {
              setRecentOffers(offersData.offers);
            }
          } catch (offersError) {
            console.error('Recent offers fetch error:', offersError);
          }

          // Mesajları çek
          try {
            const messagesResponse = await fetch(`/api/messages?userId=${user.id}`);
            const messagesData = await messagesResponse.json();
            
            if (messagesResponse.ok && messagesData.success) {
              setConversations(messagesData.conversations);
            }
          } catch (messagesError) {
            console.error('Messages fetch error:', messagesError);
          }

          // Fırsatları çek (kullanıcının şehrindeki iş ilanları)
          try {
            const opportunitiesResponse = await fetch(`/api/service-requests?city=${user.city}&limit=5`);
            const opportunitiesData = await opportunitiesResponse.json();
            
            if (opportunitiesResponse.ok && opportunitiesData.success) {
              setOpportunities(opportunitiesData.data);
            }
          } catch (opportunitiesError) {
            console.error('Opportunities fetch error:', opportunitiesError);
          }
        } else {
          window.location.href = '/hizmet-veren-girisi';
        }
      } catch (error) {
        console.error('User data loading error:', error);
        window.location.href = '/hizmet-veren-girisi';
      } finally {
        setIsLoading(false);
      }
    };

    loadUserData();
  }, []);

  const handleLogout = () => {
    localStorage.removeItem('currentUser');
    localStorage.removeItem('isLoggedIn');
    window.location.href = '/';
  };

  const handleEditField = (fieldName: string, currentValue: string) => {
    setEditingField(fieldName);
    setEditValues({ ...editValues, [fieldName]: currentValue });
  };

  const handleSaveField = async (fieldName: string) => {
    if (!currentUser) return;
    
    try {
      console.log('Saving field:', fieldName, 'Value:', editValues[fieldName]);
      
      const requestBody = {
        [fieldName]: editValues[fieldName]
      };
      
      // Özel kategori güncelleme için selected categories kullan
      if (fieldName === 'categories') {
        requestBody[fieldName] = selectedCategories.join(', ');
      }
      
      // Şehir değişikliği kontrolü
      const isCityChange = fieldName === 'city' && editValues[fieldName] !== currentUser.city;
      
      console.log('Request body:', requestBody);
      
      const response = await fetch(`/api/profile?userId=${currentUser.id}`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(requestBody)
      });

      console.log('Response status:', response.status);
      const data = await response.json();
      console.log('Response data:', data);

      if (response.ok && data.success) {
        // Kullanıcı bilgilerini güncelle
        if (fieldName === 'categories') {
          // Kategoriler için API'den dönen user data'yı kullan
          setCurrentUser(data.user);
          localStorage.setItem('currentUser', JSON.stringify(data.user));
        } else {
          setCurrentUser(prev => prev ? {
            ...prev,
            [fieldName]: fieldName === 'experienceYears' ? parseInt(editValues[fieldName]) : editValues[fieldName],
            // Şehir değiştiyse ilçeyi temizle
            ...(isCityChange ? { district: '' } : {})
          } : null);
          const updatedUser = { 
            ...currentUser, 
            [fieldName]: fieldName === 'experienceYears' ? parseInt(editValues[fieldName]) : editValues[fieldName],
            ...(isCityChange ? { district: '' } : {})
          };
          localStorage.setItem('currentUser', JSON.stringify(updatedUser));
        }
        
        setEditingField(null);
        setEditValues({});
        setSelectedCityId(null);
        setSelectedDistrictId(null);
        setSelectedCategories([]);
        setCategorySearch('');
        setShowCategoryDropdown(false);
        
        console.log('Field updated successfully:', fieldName);
        
        // Şehir değişikliği için özel mesaj
        if (isCityChange) {
          toast.warning('Şehir Değiştirildi', 'İlçe bilginiz temizlendi. Lütfen yeni şehrinize uygun ilçeyi seçin.');
        } else {
          toast.success('Profil Güncellendi', 'Değişiklikleriniz başarıyla kaydedildi.');
        }
      } else {
        console.error('Güncelleme hatası:', data.error);
        toast.error('Güncelleme Hatası', data.error || 'Bilinmeyen hata');
      }
    } catch (error) {
      console.error('Güncelleme hatası:', error);
      toast.error('Güncelleme Hatası', error.message);
    }
  };

  const handleCancelEdit = () => {
    setEditingField(null);
    setEditValues({});
    setSelectedCityId(null);
    setSelectedDistrictId(null);
    setSelectedCategories([]);
    setCategorySearch('');
    setShowCategoryDropdown(false);
  };

  const handleEditAll = () => {
    if (!currentUser) return;
    
    setIsEditingAll(true);
    setEditingField(null);
    
    // Load current city and district IDs
    const currentCity = cities.find(city => city.name === currentUser.city);
    if (currentCity) {
      setSelectedCityId(currentCity.id);
      // Wait for districts to load, then find district ID
      setTimeout(() => {
        const currentDistrict = districts.find(district => district.name === currentUser.district);
        setSelectedDistrictId(currentDistrict?.id || null);
      }, 100);
    }
    
    // Load current categories
    const categoryNames = currentUser.categories?.map(cat => cat.name) || [];
    setSelectedCategories(categoryNames);
    
    setEditValues({
      firstName: currentUser.firstName || '',
      lastName: currentUser.lastName || '',
      email: currentUser.email || '',
      phone: currentUser.phone || '',
      city: currentUser.city || '',
      district: currentUser.district || '',
      bio: currentUser.bio || '',
      experienceYears: currentUser.experienceYears?.toString() || '0',
      categories: categoryNames.join(', ')
    });
  };

  const handleSaveAll = async () => {
    if (!currentUser) return;
    
    try {
      console.log('Saving all fields:', editValues);
      console.log('Selected categories:', selectedCategories);
      
      // Request body hazırla
      const requestBody = {
        ...editValues,
        categories: selectedCategories.join(', ')
      };
      
      console.log('Request body for save all:', requestBody);
      
      const response = await fetch(`/api/profile?userId=${currentUser.id}`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(requestBody)
      });

      console.log('Save all response status:', response.status);
      const data = await response.json();
      console.log('Save all response data:', data);

      if (response.ok && data.success) {
        // API'den dönen güncel user data'yı kullan
        setCurrentUser(data.user);
        setIsEditingAll(false);
        setEditValues({});
        setSelectedCityId(null);
        setSelectedDistrictId(null);
        setSelectedCategories([]);
        setCategorySearch('');
        setShowCategoryDropdown(false);
        
        // LocalStorage'ı da güncelle
        localStorage.setItem('currentUser', JSON.stringify(data.user));
        
        console.log('All fields updated successfully');
        toast.success('Profil Güncellendi', 'Tüm değişiklikleriniz başarıyla kaydedildi.');
      } else {
        console.error('Güncelleme hatası:', data.error);
        toast.error('Güncelleme Hatası', data.error || 'Bilinmeyen hata');
      }
    } catch (error) {
      console.error('Güncelleme hatası:', error);
      toast.error('Güncelleme Hatası', error.message);
    }
  };

  const handleCancelAll = () => {
    setIsEditingAll(false);
    setEditValues({});
    setSelectedCityId(null);
    setSelectedDistrictId(null);
    setSelectedCategories([]);
    setCategorySearch('');
    setShowCategoryDropdown(false);
  };

  const handleWebPageSuccess = (pageUrl: string) => {
    // Kullanıcı state'ini güncelle
    setCurrentUser(prev => prev ? {
      ...prev,
      publicPageUrl: pageUrl
    } : null);
    
    // localStorage'ı da güncelle
    if (currentUser) {
      const updatedUser = { ...currentUser, publicPageUrl: pageUrl };
      localStorage.setItem('currentUser', JSON.stringify(updatedUser));
    }
    
    // URL'yi panoya kopyala
    const fullUrl = `https://onlineusta.com.tr${pageUrl}`;
    navigator.clipboard.writeText(fullUrl);
    
    // Toast mesajı
    toast.success('Web Sayfanız Hazır!', 
      `Sayfanız oluşturuldu ve URL panoya kopyalandı: ${fullUrl.replace('https://', '')}`
    );
  };

  // Loading state
  if (isLoading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto mb-4"></div>
          <p className="text-gray-600">Profil yükleniyor...</p>
        </div>
      </div>
    );
  }

  if (!currentUser) return null;

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 via-sky-50 to-blue-100">
      {/* Navigation */}
      <Navigation />
      {/* Hero Section - Professional Header */}
      <div className="relative bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 text-white pt-20">
        <div className="absolute inset-0 bg-black opacity-10"></div>
        <div className="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
          {/* Desktop View */}
          <div className="hidden md:flex items-center space-x-6">
            
            {/* Profile Photo */}
            <div className="flex-shrink-0">
              <div className="relative">
                <div 
                  className="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center border-2 border-green-400 cursor-pointer hover:border-green-300 transition-colors"
                  onClick={() => setShowProfileModal(true)}
                >
                  {currentUser.avatar ? (
                    <Image
                      src={currentUser.avatar}
                      alt={`${currentUser.firstName} ${currentUser.lastName}`}
                      width={80}
                      height={80}
                      className="w-full h-full rounded-full object-cover"
                    />
                  ) : (
                    <div className="text-2xl font-bold text-white">
                      {currentUser.firstName?.[0]}{currentUser.lastName?.[0]}
                    </div>
                  )}
                </div>
                
                {/* Verification Badge */}
                {currentUser.isVerified && (
                  <div className="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center border-2 border-white">
                    <svg className="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                    </svg>
                  </div>
                )}
              </div>
            </div>

            {/* Main Info */}
            <div className="flex-1 min-w-0">
              <div className="flex items-center justify-between">
                
                {/* Left: Name & Details */}
                <div>
                  <div className="flex items-center space-x-3 mb-1">
                    <h1 className="text-2xl font-bold text-white">
                      {currentUser.firstName} {currentUser.lastName}
                    </h1>
                    {currentUser.isVerified && (
                      <span className="px-2 py-1 bg-green-500/20 text-green-300 text-xs font-medium rounded-full">
                        Doğrulanmış
                      </span>
                    )}
                  </div>
                  
                  <div className="flex items-center space-x-4 text-white/90 text-sm mb-2">
                    {/* Location */}
                    <div className="flex items-center">
                      <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                      <span>{currentUser.district}, {currentUser.city}</span>
                    </div>
                    
                    {/* Experience */}
                    {currentUser.experienceYears && (
                      <div className="flex items-center">
                        <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{currentUser.experienceYears} yıl</span>
                      </div>
                    )}
                    
                    {/* Rating */}
                    <div className="flex items-center">
                      <svg className="w-4 h-4 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <span>{currentUser.rating?.toFixed(1) || '5.0'}</span>
                    </div>
                  </div>
                  
                  {/* Categories */}
                  {currentUser.categories && currentUser.categories.length > 0 && (
                    <div className="flex flex-wrap gap-2">
                      {currentUser.categories.slice(0, 3).map((category) => (
                        <span
                          key={category.id}
                          className="px-2 py-1 bg-white/15 rounded text-xs font-medium text-white/90"
                        >
                          {category.name}
                        </span>
                      ))}
                      {currentUser.categories.length > 3 && (
                        <span className="px-2 py-1 bg-white/15 rounded text-xs font-medium text-white/90">
                          +{currentUser.categories.length - 3}
                        </span>
                      )}
                    </div>
                  )}
                </div>

                {/* Right: Action Buttons */}
                <div className="flex space-x-3">
                  <Link
                    href="/ilanlar"
                    className="px-4 py-2 bg-white text-blue-600 font-medium rounded-lg hover:bg-gray-100 transition-colors text-sm"
                  >
                    Teklif Ver
                  </Link>
                  <button 
                    onClick={() => setShowProfileModal(true)}
                    className="px-4 py-2 bg-white/20 border border-white/30 text-white font-medium rounded-lg hover:bg-white/30 transition-colors text-sm"
                  >
                    Profili Görüntüle
                  </button>
                </div>
              </div>
            </div>
          </div>

          {/* Mobile View - Simplified */}
          <div className="md:hidden">
            <div className="flex items-center space-x-4">
              {/* Profile Photo with Badge */}
              <div className="flex-shrink-0 relative">
                <div 
                  className="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center border-2 border-green-400 cursor-pointer"
                  onClick={() => setShowProfileModal(true)}
                >
                  {currentUser.avatar ? (
                    <Image
                      src={currentUser.avatar}
                      alt={`${currentUser.firstName} ${currentUser.lastName}`}
                      width={64}
                      height={64}
                      className="w-full h-full rounded-full object-cover"
                    />
                  ) : (
                    <div className="text-xl font-bold text-white">
                      {currentUser.firstName?.[0]}{currentUser.lastName?.[0]}
                    </div>
                  )}
                </div>
                
                {/* Membership Badge */}
                <div className="absolute -bottom-1 left-1/2 transform -translate-x-1/2">
                  {(currentUser.membershipType === 'PROFESSIONAL' || currentUser.membershipType === 'PREMIUM') ? (
                    <div className="bg-gradient-to-r from-yellow-400 to-orange-500 px-2 py-1 rounded-full shadow-lg border-2 border-white">
                      <div className="flex items-center space-x-1">
                        <svg className="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                          <path fillRule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                        </svg>
                        <span className="text-xs font-bold text-white">
                          {currentUser.membershipType === 'PREMIUM' ? 'PRO+' : 'PRO'}
                        </span>
                      </div>
                    </div>
                  ) : (
                    <div className="bg-gradient-to-r from-gray-400 to-gray-500 px-2 py-1 rounded-full shadow-lg border-2 border-white">
                      <span className="text-xs font-bold text-white">STD</span>
                    </div>
                  )}
                </div>
              </div>

              {/* Simplified Info */}
              <div className="flex-1 min-w-0">
                {/* Name */}
                <h1 className="text-xl font-bold text-white mb-1">
                  {currentUser.firstName} {currentUser.lastName}
                </h1>
                
                {/* Location */}
                <div className="flex items-center text-white/90 text-sm mb-2">
                  <svg className="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  <span>{currentUser.district}, {currentUser.city}</span>
                </div>

                {/* Phone */}
                {currentUser.phone && (
                  <div className="flex items-center text-white/90 text-sm mb-2">
                    <svg className="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span>{currentUser.phone}</span>
                  </div>
                )}
                
                {/* Primary Service + Others */}
                {currentUser.categories && currentUser.categories.length > 0 && (
                  <div className="flex items-center space-x-2">
                    <span className="px-2 py-1 bg-white/15 rounded text-xs font-medium text-white/90">
                      {currentUser.categories[0].name}
                    </span>
                    {currentUser.categories.length > 1 && (
                      <span className="text-white/70 text-xs">
                        +{currentUser.categories.length - 1}
                      </span>
                    )}
                  </div>
                )}
              </div>

              {/* Mobile Action */}
              <div className="flex-shrink-0">
                <button 
                  onClick={() => setShowProfileModal(true)}
                  className="p-2 bg-white/20 border border-white/30 text-white rounded-lg hover:bg-white/30 transition-colors"
                >
                  <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Navigation Tabs */}
      <div className="sticky top-14 z-30 bg-white border-b border-gray-200 shadow-sm">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <nav className="flex space-x-8">
            {[
              { id: 'overview', label: 'Genel Bakış', icon: 'M4 6h16M4 10h16M4 14h16M4 18h16' },
              { id: 'offers', label: 'Tekliflerim', icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' },
              { id: 'messages', label: 'Mesajlar', icon: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z' }
            ].map((tab) => (
              <button
                key={tab.id}
                onClick={() => setActiveSection(tab.id)}
                className={`flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors ${
                  activeSection === tab.id
                    ? 'border-blue-500 text-blue-600'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                }`}
              >
                <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d={tab.icon} />
                </svg>
                {tab.label}
              </button>
            ))}
          </nav>
        </div>
      </div>

      {/* Main Content */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-8">
          
          {/* Main Content Area */}
          <div className="lg:col-span-2 order-2 lg:order-1">
            {/* Overview Section */}
            {activeSection === 'overview' && (
              <div className="space-y-8">
                {/* Opportunities */}
                <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                  <div className="flex items-center justify-between mb-6">
                    <h3 className="text-xl font-semibold text-gray-900 flex items-center">
                      <svg className="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                      </svg>
                      Fırsatlar
                    </h3>
                    <Link 
                      href="/ilanlar" 
                      className="text-green-600 hover:text-green-700 font-medium text-sm flex items-center"
                    >
                      Tümünü Gör
                      <svg className="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7" />
                      </svg>
                    </Link>
                  </div>
                  
                  <div className="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                    <p className="text-green-800 text-sm font-medium flex items-center">
                      <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                      {currentUser.city} bölgesindeki yeni iş fırsatları
                    </p>
                  </div>
                  
                  {opportunities.length > 0 ? (
                    <div className="space-y-4">
                      {opportunities.map((opportunity) => (
                        <div key={opportunity.id} className="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                          <div className="flex items-start justify-between">
                            <div className="flex-1">
                              <div className="flex items-center mb-2">
                                <h4 className="font-semibold text-gray-900 mr-3">{opportunity.title}</h4>
                                <span className="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                  {opportunity.category.name}
                                </span>
                              </div>
                              <p className="text-gray-600 text-sm mb-3 line-clamp-2">{opportunity.description}</p>
                              <div className="flex items-center space-x-4 text-sm text-gray-500">
                                <span className="flex items-center">
                                  <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                  </svg>
                                  {opportunity.district}, {opportunity.city}
                                </span>
                                <span className="flex items-center">
                                  <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                  </svg>
                                  {opportunity.user.firstName} {opportunity.user.lastName}
                                </span>
                                <span className="flex items-center">
                                  <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                  </svg>
                                  {new Date(opportunity.createdAt).toLocaleDateString('tr-TR')}
                                </span>
                              </div>
                            </div>
                            <div className="flex flex-col items-end ml-4">
                              {opportunity.budget && (
                                <div className="text-lg font-bold text-green-600 mb-2">
                                  {opportunity.budget.toLocaleString('tr-TR')}₺
                                </div>
                              )}
                              <Link
                                href={`/teklif-gonder/${opportunity.id}`}
                                className="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors text-sm"
                              >
                                Teklif Ver
                              </Link>
                            </div>
                          </div>
                        </div>
                      ))}
                    </div>
                  ) : (
                    <div className="text-center py-12">
                      <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                      </svg>
                      <h3 className="mt-2 text-sm font-medium text-gray-900">Henüz fırsat bulunamadı</h3>
                      <p className="mt-1 text-sm text-gray-500">{currentUser.city} bölgesinde yeni iş ilanları henüz yok.</p>
                      <div className="mt-4">
                        <Link
                          href="/ilanlar"
                          className="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors"
                        >
                          Tüm İlanları İncele
                        </Link>
                      </div>
                    </div>
                  )}
                </div>




              </div>
            )}

            {/* Offers Section */}
            {activeSection === 'offers' && (
              <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-center justify-between mb-6 space-y-4 sm:space-y-0">
                  <h3 className="text-xl font-semibold text-gray-900 flex items-center">
                    <svg className="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Web Sayfası Yap
                  </h3>
                  {/* Legend - Desktop */}
                  <div className="hidden sm:flex items-center space-x-4 text-sm text-gray-600">
                    <span className="flex items-center">
                      <div className="w-3 h-3 bg-yellow-400 rounded-full mr-2"></div>
                      Bekliyor
                    </span>
                    <span className="flex items-center">
                      <div className="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                      Kabul Edildi
                    </span>
                    <span className="flex items-center">
                      <div className="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                      Reddedildi
                    </span>
                  </div>
                  {/* Legend - Mobile */}
                  <div className="flex sm:hidden justify-center space-x-6 text-xs text-gray-600">
                    <span className="flex items-center">
                      <div className="w-2 h-2 bg-yellow-400 rounded-full mr-1"></div>
                      Bekliyor
                    </span>
                    <span className="flex items-center">
                      <div className="w-2 h-2 bg-green-500 rounded-full mr-1"></div>
                      Kabul
                    </span>
                    <span className="flex items-center">
                      <div className="w-2 h-2 bg-red-500 rounded-full mr-1"></div>
                      Red
                    </span>
                  </div>
                </div>
                
                {recentOffers.length > 0 ? (
                  <div className="space-y-4">
                    {recentOffers.map((offer) => (
                      <div key={offer.id} className="border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                        {/* Desktop Layout */}
                        <div className="hidden md:block p-6">
                          <div className="flex items-start justify-between">
                            <div className="flex-1">
                              <div className="flex items-center mb-3">
                                <h4 className="font-semibold text-gray-900 mr-3">{offer.serviceRequest.title}</h4>
                                <span className={`px-3 py-1 rounded-full text-xs font-medium ${
                                  offer.status === 'PENDING' ? 'bg-yellow-100 text-yellow-800' :
                                  offer.status === 'ACCEPTED' ? 'bg-green-100 text-green-800' :
                                  offer.status === 'REJECTED' ? 'bg-red-100 text-red-800' :
                                  'bg-gray-100 text-gray-800'
                                }`}>
                                  {offer.status === 'PENDING' ? 'Bekliyor' :
                                   offer.status === 'ACCEPTED' ? 'Kabul Edildi' :
                                   offer.status === 'REJECTED' ? 'Reddedildi' : 'Süresi Doldu'}
                                </span>
                              </div>
                              
                              <p className="text-gray-600 text-sm mb-3 line-clamp-2">{offer.description}</p>
                              
                              <div className="flex items-center space-x-4 text-sm text-gray-500 mb-3">
                                <span className="flex items-center">
                                  <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                  </svg>
                                  {offer.serviceRequest.district}, {offer.serviceRequest.city}
                                </span>
                                <span className="flex items-center">
                                  <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                  </svg>
                                  {new Date(offer.createdAt).toLocaleDateString('tr-TR')}
                                </span>
                                <span className="flex items-center">
                                  <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                  </svg>
                                  {offer.serviceRequest.category}
                                </span>
                              </div>
                              
                              {offer.status === 'ACCEPTED' && (
                                <div className="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                                  <svg className="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                  </svg>
                                  <span className="text-green-800 text-sm font-medium">Teklif kabul edildi! Müşteriyle iletişime geçebilirsiniz.</span>
                                </div>
                              )}
                              
                              {offer.status === 'REJECTED' && (
                                <div className="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                                  <svg className="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                  </svg>
                                  <span className="text-red-800 text-sm font-medium">Teklif reddedildi. Başka fırsatlar için ilanları takip edin.</span>
                                </div>
                              )}
                            </div>
                            
                            <div className="flex flex-col items-end ml-6">
                              <div className="text-2xl font-bold text-green-600 mb-3">
                                {offer.amount.toLocaleString('tr-TR')}₺
                              </div>
                              
                              {offer.status === 'PENDING' && (
                                <div className="flex flex-col space-y-2">
                                  <Button className="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors text-sm">
                                    Düzenle
                                  </Button>
                                  <Button className="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors text-sm">
                                    İptal Et
                                  </Button>
                                </div>
                              )}
                              
                              {offer.status === 'ACCEPTED' && (
                                <button
                                  onClick={() => setActiveSection('messages')}
                                  className="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors text-sm"
                                >
                                  Müşteriyle İletişim
                                </button>
                              )}
                            </div>
                          </div>
                        </div>

                        {/* Mobile Layout */}
                        <div className="md:hidden p-4">
                          <div className="space-y-3">
                            {/* Header Row */}
                            <div className="flex items-start justify-between">
                              <div className="flex-1 min-w-0">
                                <h4 className="font-semibold text-gray-900 text-sm leading-tight mb-1">
                                  {offer.serviceRequest.title}
                                </h4>
                                <div className="flex items-center space-x-2">
                                  <span className={`px-2 py-1 rounded-full text-xs font-medium ${
                                    offer.status === 'PENDING' ? 'bg-yellow-100 text-yellow-800' :
                                    offer.status === 'ACCEPTED' ? 'bg-green-100 text-green-800' :
                                    offer.status === 'REJECTED' ? 'bg-red-100 text-red-800' :
                                    'bg-gray-100 text-gray-800'
                                  }`}>
                                    {offer.status === 'PENDING' ? 'Bekliyor' :
                                     offer.status === 'ACCEPTED' ? 'Kabul' :
                                     offer.status === 'REJECTED' ? 'Red' : 'Doldu'}
                                  </span>
                                </div>
                              </div>
                              <div className="text-right ml-3">
                                <div className="text-lg font-bold text-green-600">
                                  {offer.amount.toLocaleString('tr-TR')}₺
                                </div>
                              </div>
                            </div>

                            {/* Description */}
                            <p className="text-gray-600 text-sm line-clamp-2">{offer.description}</p>
                            
                            {/* Meta Info */}
                            <div className="space-y-1">
                              <div className="flex items-center text-xs text-gray-500">
                                <svg className="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {offer.serviceRequest.district}, {offer.serviceRequest.city}
                              </div>
                              <div className="flex items-center justify-between text-xs text-gray-500">
                                <span className="flex items-center">
                                  <svg className="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                  </svg>
                                  {new Date(offer.createdAt).toLocaleDateString('tr-TR')}
                                </span>
                                <span className="flex items-center">
                                  <svg className="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                  </svg>
                                  {offer.serviceRequest.category}
                                </span>
                              </div>
                            </div>
                            
                            {/* Status Messages */}
                            {offer.status === 'ACCEPTED' && (
                              <div className="flex items-center p-2 bg-green-50 border border-green-200 rounded-lg">
                                <svg className="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span className="text-green-800 text-xs font-medium">Teklif kabul edildi!</span>
                              </div>
                            )}
                            
                            {offer.status === 'REJECTED' && (
                              <div className="flex items-center p-2 bg-red-50 border border-red-200 rounded-lg">
                                <svg className="w-4 h-4 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span className="text-red-800 text-xs font-medium">Teklif reddedildi</span>
                              </div>
                            )}

                            {/* Action Buttons */}
                            <div className="flex space-x-2 pt-2">
                              {offer.status === 'PENDING' && (
                                <>
                                  <Button className="flex-1 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors text-sm">
                                    Düzenle
                                  </Button>
                                  <Button className="flex-1 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors text-sm">
                                    İptal
                                  </Button>
                                </>
                              )}
                              
                              {offer.status === 'ACCEPTED' && (
                                <button
                                  onClick={() => setActiveSection('messages')}
                                  className="w-full py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors text-sm"
                                >
                                  Müşteriyle İletişim
                                </button>
                              )}
                            </div>
                          </div>
                        </div>
                      </div>
                    ))}
                  </div>
                ) : (
                  <div className="text-center py-12">
                    <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 className="mt-2 text-sm font-medium text-gray-900">Henüz teklif vermediniz</h3>
                    <p className="mt-1 text-sm text-gray-500">İlk teklifinizi vermek için fırsatları inceleyin.</p>
                    <div className="mt-4">
                      <Link
                        href="/ilanlar"
                        className="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors"
                      >
                        <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Fırsatları İncele
                      </Link>
                    </div>
                  </div>
                )}
              </div>
            )}



            {/* Messages Section */}
            {activeSection === 'messages' && (
              <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 className="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                  <svg className="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                  </svg>
                  Müşteri Mesajları
                </h3>
                
                {conversations.length > 0 ? (
                  <div className="space-y-4">
                    {conversations.map((conversation) => (
                      <div key={conversation.id} className="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer">
                        <div className="flex items-start justify-between">
                          <div className="flex items-center space-x-4">
                            {/* Avatar */}
                            <div className="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                              <svg className="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                              </svg>
                            </div>
                            
                            <div className="flex-1">
                              <div className="flex items-center space-x-2 mb-1">
                                <h4 className="font-semibold text-gray-900">{conversation.clientName}</h4>
                                {conversation.unreadCount > 0 && (
                                  <span className="px-2 py-1 bg-red-500 text-white text-xs rounded-full">
                                    {conversation.unreadCount}
                                  </span>
                                )}
                              </div>
                              
                              {conversation.serviceRequest && (
                                <p className="text-sm text-blue-600 mb-1">
                                  📋 {conversation.serviceRequest.title}
                                </p>
                              )}
                              
                              <p className="text-gray-600 text-sm line-clamp-2">
                                {conversation.lastMessage}
                              </p>
                              
                              <p className="text-gray-400 text-xs mt-1">
                                {new Date(conversation.lastMessageAt).toLocaleDateString('tr-TR', {
                                  day: 'numeric',
                                  month: 'short',
                                  hour: '2-digit',
                                  minute: '2-digit'
                                })}
                              </p>
                            </div>
                          </div>
                          
                          <div className="flex flex-col items-end space-y-2">
                            <Button className="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors text-sm">
                              Yanıtla
                            </Button>
                            {conversation.unreadCount === 0 && (
                              <svg className="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                              </svg>
                            )}
                          </div>
                        </div>
                      </div>
                    ))}
                  </div>
                ) : (
                  <div className="text-center py-12">
                    <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <h3 className="mt-2 text-sm font-medium text-gray-900">Henüz mesaj yok</h3>
                    <p className="mt-1 text-sm text-gray-500">Müşteriler size mesaj gönderdiğinde burada görünecek.</p>
                    <div className="mt-4">
                      <Link
                        href="/ilanlar"
                        className="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors"
                      >
                        İlanları İncele
                      </Link>
                    </div>
                  </div>
                )}
              </div>
            )}
          </div>

          {/* Sidebar */}
          <div className="space-y-4 md:space-y-6 order-1 lg:order-2">
            {/* Quick Actions */}
            <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6">
              <h3 className="text-lg font-semibold text-gray-900 mb-4">Hızlı İşlemler</h3>
              
              {/* Desktop View */}
              <div className="hidden md:block space-y-3">
                {currentUser.publicPageUrl ? (
                  <Link
                    href={currentUser.publicPageUrl}
                    target="_blank"
                    className="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors text-center flex items-center justify-center space-x-2"
                  >
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span>Web Sayfanı Gör</span>
                  </Link>
                ) : (
                  <button
                    onClick={() => setShowWebPageModal(true)}
                    className="w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors text-center"
                  >
                    Web Sayfası Yap
                  </button>
                )}
                <button
                  onClick={() => setActiveSection('messages')}
                  className="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors text-center"
                >
                  Mesajlarım
                </button>
                <button
                  onClick={() => setActiveSection('opportunities')}
                  className="w-full px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors text-center"
                >
                  Fırsatlar
                </button>
                <button
                  onClick={() => setShowProfileModal(true)}
                  className="w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors"
                >
                  Profili Düzenle
                </button>
                <Link 
                  href="/hizmetveren-ayarlar" 
                  className="w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors text-center block"
                >
                  Ayarlar
                </Link>
                <button
                  onClick={handleLogout}
                  className="w-full px-4 py-3 bg-red-50 hover:bg-red-100 text-red-600 font-medium rounded-lg transition-colors"
                >
                  Çıkış Yap
                </button>
              </div>

              {/* Mobile View - Grid Layout */}
              <div className="md:hidden grid grid-cols-3 gap-2">
                {currentUser.publicPageUrl ? (
                  <Link
                    href={currentUser.publicPageUrl}
                    target="_blank"
                    className="flex flex-col items-center p-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg"
                  >
                    <svg className="w-4 h-4 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span className="text-xs leading-tight text-center">Web Sayfanı<br/>Gör</span>
                  </Link>
                ) : (
                  <button
                    onClick={() => setShowWebPageModal(true)}
                    className="flex flex-col items-center p-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg"
                  >
                    <svg className="w-4 h-4 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span className="text-xs leading-tight text-center">Web Sayfası<br/>Yap</span>
                  </button>
                )}
                <button
                  onClick={() => setActiveSection('messages')}
                  className="flex flex-col items-center p-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors"
                >
                  <svg className="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                  </svg>
                  <span className="text-xs">Mesajlar</span>
                </button>
                <button
                  onClick={() => setActiveSection('opportunities')}
                  className="flex flex-col items-center p-3 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors"
                >
                  <svg className="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                  <span className="text-xs">Fırsatlar</span>
                </button>
                <button
                  onClick={() => setShowProfileModal(true)}
                  className="flex flex-col items-center p-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors"
                >
                  <svg className="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  <span className="text-xs">Profil</span>
                </button>
                <Link 
                  href="/hizmetveren-ayarlar" 
                  className="flex flex-col items-center p-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors"
                >
                  <svg className="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  <span className="text-xs">Ayarlar</span>
                </Link>
                <button
                  onClick={handleLogout}
                  className="flex flex-col items-center p-3 bg-red-50 hover:bg-red-100 text-red-600 font-medium rounded-lg transition-colors"
                >
                  <svg className="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                  </svg>
                  <span className="text-xs">Çıkış</span>
                </button>
              </div>
            </div>



            {/* Professional Badge - Hidden on Mobile */}
            <div className="hidden md:block bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl p-6 text-white">
              <div className="flex items-center mb-3">
                <svg className="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20">
                  <path fillRule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                </svg>
                <h3 className="text-lg font-semibold">Profesyonel Üye</h3>
              </div>
              <p className="text-white/90 text-sm">
                Doğrulanmış profesyonel hesap sahibisiniz. Müşterileriniz size daha çok güvenecek.
              </p>
            </div>


          </div>
        </div>
      </div>

      {/* Profile Modal */}
      {showProfileModal && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
          <div className="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            {/* Modal Header */}
            <div className="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-6 py-4 rounded-t-2xl">
              <div className="flex items-center justify-between">
                <div className="flex items-center space-x-3">
                  <div className="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  <div>
                    <h2 className="text-xl font-bold">Profil Yönetimi</h2>
                    <p className="text-white/80 text-sm">Bilgilerinizi güncelleyin</p>
                  </div>
                </div>
                
                <div className="flex items-center space-x-3">
                  <button
                    onClick={handleEditAll}
                    className="px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm border border-white/30 text-white font-medium rounded-lg transition-all flex items-center"
                  >
                    <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    {isEditingAll ? 'Düzenleme Aktif' : 'Düzenle'}
                  </button>
                  {isEditingAll && (
                    <div className="flex space-x-2">
                      <button
                        onClick={handleSaveAll}
                        className="px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg transition-colors flex items-center shadow-lg"
                      >
                        <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Kaydet
                      </button>
                      <button
                        onClick={handleCancelAll}
                        className="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors flex items-center shadow-lg"
                      >
                        <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        İptal
                      </button>
                    </div>
                  )}
                  <button
                    onClick={() => setShowProfileModal(false)}
                    className="p-2 hover:bg-white/20 rounded-lg transition-colors"
                  >
                    <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
              {isEditingAll && (
                <div className="mt-3 p-3 bg-white/10 rounded-lg border border-white/20">
                  <p className="text-sm text-white/90 flex items-center">
                    <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Düzenleme modunda - Değişikliklerinizi kaydetmeyi unutmayın
                  </p>
                </div>
              )}
            </div>

            {/* Modal Body */}
            <div className="p-6">
              {/* Profile Photo Section */}
              <div className="text-center mb-8">
                <div className="relative inline-block">
                  <div className="w-32 h-32 rounded-full bg-gradient-to-r from-blue-500 to-blue-700 flex items-center justify-center border-4 border-green-400 mx-auto">
                    {currentUser.avatar ? (
                      <Image
                        src={currentUser.avatar}
                        alt={`${currentUser.firstName} ${currentUser.lastName}`}
                        width={128}
                        height={128}
                        className="w-full h-full rounded-full object-cover"
                      />
                    ) : (
                      <div className="text-4xl font-bold text-white">
                        {currentUser.firstName?.[0]}{currentUser.lastName?.[0]}
                      </div>
                    )}
                  </div>
                  
                  {/* Online Status Badge */}
                  <div className="absolute bottom-2 right-2 px-3 py-1 bg-green-500 text-white text-sm font-medium rounded-full">
                    Online
                  </div>
                  
                  {/* Verification Badge */}
                  {currentUser.isVerified && (
                    <div className="absolute top-2 right-2 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center border-2 border-white">
                      <svg className="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                      </svg>
                    </div>
                  )}
                </div>
                
                <h3 className="text-2xl font-bold text-gray-900 mt-4">
                  {currentUser.firstName} {currentUser.lastName}
                </h3>
              </div>

              {/* Profile Details */}
              <div className="space-y-6">
                {/* Personal Information */}
                <div>
                  <h4 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg className="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Kişisel Bilgiler
                  </h4>
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {/* First Name */}
                    <div className="bg-gray-50 p-4 rounded-lg relative group">
                      <label className="text-sm font-medium text-gray-500">Ad</label>
                      {(editingField === 'firstName' || isEditingAll) ? (
                        <div className="flex items-center space-x-2 mt-1">
                          <input
                            type="text"
                            value={editValues.firstName || ''}
                            onChange={(e) => setEditValues({...editValues, firstName: e.target.value})}
                            className="flex-1 p-1 border border-gray-300 rounded text-gray-900 font-medium"
                            autoFocus={editingField === 'firstName'}
                          />
                          {!isEditingAll && (
                            <>
                              <button
                                onClick={() => handleSaveField('firstName')}
                                className="p-1 text-green-600 hover:text-green-700"
                              >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                                </svg>
                              </button>
                              <button
                                onClick={handleCancelEdit}
                                className="p-1 text-red-600 hover:text-red-700"
                              >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                              </button>
                            </>
                          )}
                        </div>
                      ) : (
                        <div className="flex items-center justify-between">
                          <p className="text-gray-900 font-medium">{currentUser.firstName}</p>
                          {!isEditingAll && (
                            <button
                              onClick={() => handleEditField('firstName', currentUser.firstName)}
                              className="p-1 text-gray-400 hover:text-blue-600 transition-all"
                            >
                              <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                              </svg>
                            </button>
                          )}
                        </div>
                      )}
                    </div>

                    {/* Last Name */}
                    <div className="bg-gray-50 p-4 rounded-lg relative group">
                      <label className="text-sm font-medium text-gray-500">Soyad</label>
                      {(editingField === 'lastName' || isEditingAll) ? (
                        <div className="flex items-center space-x-2 mt-1">
                          <input
                            type="text"
                            value={editValues.lastName || ''}
                            onChange={(e) => setEditValues({...editValues, lastName: e.target.value})}
                            className="flex-1 p-1 border border-gray-300 rounded text-gray-900 font-medium"
                            autoFocus={editingField === 'lastName'}
                          />
                          {!isEditingAll && (
                            <>
                              <button
                                onClick={() => handleSaveField('lastName')}
                                className="p-1 text-green-600 hover:text-green-700"
                              >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                                </svg>
                              </button>
                              <button
                                onClick={handleCancelEdit}
                                className="p-1 text-red-600 hover:text-red-700"
                              >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                              </button>
                            </>
                          )}
                        </div>
                      ) : (
                        <div className="flex items-center justify-between">
                          <p className="text-gray-900 font-medium">{currentUser.lastName}</p>
                          {!isEditingAll && (
                            <button
                              onClick={() => handleEditField('lastName', currentUser.lastName)}
                              className="p-1 text-gray-400 hover:text-blue-600 transition-all"
                            >
                              <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                              </svg>
                            </button>
                          )}
                        </div>
                      )}
                    </div>

                    {/* Email */}
                    <div className="bg-gray-50 p-4 rounded-lg relative group">
                      <label className="text-sm font-medium text-gray-500">E-posta</label>
                      {(editingField === 'email' || isEditingAll) ? (
                        <div className="flex items-center space-x-2 mt-1">
                          <input
                            type="email"
                            value={editValues.email || ''}
                            onChange={(e) => setEditValues({...editValues, email: e.target.value})}
                            className="flex-1 p-1 border border-gray-300 rounded text-gray-900 font-medium"
                            autoFocus={editingField === 'email'}
                          />
                          {!isEditingAll && (
                            <>
                              <button
                                onClick={() => handleSaveField('email')}
                                className="p-1 text-green-600 hover:text-green-700"
                              >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                                </svg>
                              </button>
                              <button
                                onClick={handleCancelEdit}
                                className="p-1 text-red-600 hover:text-red-700"
                              >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                              </button>
                            </>
                          )}
                        </div>
                      ) : (
                        <div className="flex items-center justify-between">
                          <p className="text-gray-900 font-medium">{currentUser.email}</p>
                          {!isEditingAll && (
                            <button
                              onClick={() => handleEditField('email', currentUser.email)}
                              className="p-1 text-gray-400 hover:text-blue-600 transition-all"
                            >
                              <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                              </svg>
                            </button>
                          )}
                        </div>
                      )}
                    </div>

                    {/* Phone */}
                    <div className="bg-gray-50 p-4 rounded-lg relative group">
                      <label className="text-sm font-medium text-gray-500">Telefon</label>
                      {(editingField === 'phone' || isEditingAll) ? (
                        <div className="flex items-center space-x-2 mt-1">
                          <input
                            type="tel"
                            value={editValues.phone || ''}
                            onChange={(e) => setEditValues({...editValues, phone: e.target.value})}
                            className="flex-1 p-1 border border-gray-300 rounded text-gray-900 font-medium"
                            autoFocus={editingField === 'phone'}
                          />
                          {!isEditingAll && (
                            <>
                              <button
                                onClick={() => handleSaveField('phone')}
                                className="p-1 text-green-600 hover:text-green-700"
                              >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                                </svg>
                              </button>
                              <button
                                onClick={handleCancelEdit}
                                className="p-1 text-red-600 hover:text-red-700"
                              >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                              </button>
                            </>
                          )}
                        </div>
                      ) : (
                        <div className="flex items-center justify-between">
                          <p className="text-gray-900 font-medium">{currentUser.phone || 'Telefon ekle'}</p>
                          {!isEditingAll && (
                            <button
                              onClick={() => handleEditField('phone', currentUser.phone || '')}
                              className="p-1 text-gray-400 hover:text-blue-600 transition-all"
                            >
                              <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                              </svg>
                            </button>
                          )}
                        </div>
                      )}
                    </div>
                  </div>
                </div>

                {/* Professional Information */}
                <div>
                  <h4 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg className="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2" />
                    </svg>
                    Profesyonel Bilgiler
                  </h4>
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {/* Experience Years */}
                    <div className="bg-gray-50 p-4 rounded-lg relative group">
                      <label className="text-sm font-medium text-gray-500">Deneyim</label>
                      {(editingField === 'experienceYears' || isEditingAll) ? (
                        <div className="flex items-center space-x-2 mt-1">
                          <input
                            type="number"
                            value={editValues.experienceYears || ''}
                            onChange={(e) => setEditValues({...editValues, experienceYears: e.target.value})}
                            className="flex-1 p-1 border border-gray-300 rounded text-gray-900 font-medium"
                            autoFocus={editingField === 'experienceYears'}
                            min="0"
                            max="50"
                          />
                          <span className="text-sm text-gray-500">yıl</span>
                          {!isEditingAll && (
                            <>
                              <button
                                onClick={() => handleSaveField('experienceYears')}
                                className="p-1 text-green-600 hover:text-green-700"
                              >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                                </svg>
                              </button>
                              <button
                                onClick={handleCancelEdit}
                                className="p-1 text-red-600 hover:text-red-700"
                              >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                              </button>
                            </>
                          )}
                        </div>
                      ) : (
                        <div className="flex items-center justify-between">
                          <p className="text-gray-900 font-medium">
                            {currentUser.experienceYears ? `${currentUser.experienceYears} yıl` : 'Deneyim belirtilmemiş'}
                          </p>
                          {!isEditingAll && (
                            <button
                              onClick={() => handleEditField('experienceYears', currentUser.experienceYears?.toString() || '')}
                              className="p-1 text-gray-400 hover:text-blue-600 transition-all"
                            >
                              <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                              </svg>
                            </button>
                          )}
                        </div>
                      )}
                    </div>

                    {/* Location - City */}
                    <div className="bg-gray-50 p-4 rounded-lg relative group">
                      <label className="text-sm font-medium text-gray-500">Şehir</label>
                      {(editingField === 'city' || isEditingAll) ? (
                        <div className="flex items-center space-x-2 mt-1">
                          <select
                            value={selectedCityId || ''}
                            onChange={(e) => {
                              const cityId = e.target.value ? parseInt(e.target.value) : null;
                              setSelectedCityId(cityId);
                              const selectedCity = cities.find(city => city.id === cityId);
                              setEditValues({
                                ...editValues, 
                                city: selectedCity?.name || ''
                              });
                              // Reset district when city changes
                              setSelectedDistrictId(null);
                              setEditValues(prev => ({...prev, district: ''}));
                            }}
                            className="flex-1 p-1 border border-gray-300 rounded text-gray-900 font-medium"
                            autoFocus={editingField === 'city'}
                          >
                            <option value="">Şehir seçin...</option>
                            {cities.map((city) => (
                              <option key={city.id} value={city.id}>
                                {city.name}
                              </option>
                            ))}
                          </select>
                          {!isEditingAll && (
                            <>
                              <button
                                onClick={() => handleSaveField('city')}
                                className="p-1 text-green-600 hover:text-green-700"
                                disabled={!selectedCityId}
                              >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                                </svg>
                              </button>
                              <button
                                onClick={handleCancelEdit}
                                className="p-1 text-red-600 hover:text-red-700"
                              >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                              </button>
                            </>
                          )}
                        </div>
                      ) : (
                        <div className="flex items-center justify-between">
                          <p className="text-gray-900 font-medium">{currentUser.city}</p>
                          {!isEditingAll && (
                            <button
                              onClick={() => {
                                // Find current city ID
                                const currentCity = cities.find(city => city.name === currentUser.city);
                                setSelectedCityId(currentCity?.id || null);
                                handleEditField('city', currentUser.city);
                              }}
                              className="p-1 text-gray-400 hover:text-blue-600 transition-all"
                            >
                              <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                              </svg>
                            </button>
                          )}
                        </div>
                      )}
                    </div>

                    {/* District */}
                    <div className="bg-gray-50 p-4 rounded-lg relative group">
                      <label className="text-sm font-medium text-gray-500">İlçe</label>
                      {(editingField === 'district' || isEditingAll) ? (
                        <div className="flex items-center space-x-2 mt-1">
                          <select
                            value={selectedDistrictId || ''}
                            onChange={(e) => {
                              const districtId = e.target.value ? parseInt(e.target.value) : null;
                              setSelectedDistrictId(districtId);
                              const selectedDistrict = districts.find(district => district.id === districtId);
                              setEditValues({
                                ...editValues, 
                                district: selectedDistrict?.name || ''
                              });
                            }}
                            className="flex-1 p-1 border border-gray-300 rounded text-gray-900 font-medium"
                            autoFocus={editingField === 'district'}
                            disabled={districtsLoading || !selectedCityId}
                          >
                            <option value="">
                              {!selectedCityId ? 'Önce şehir seçin' : 'İlçe seçin...'}
                            </option>
                            {districts.map((district) => (
                              <option key={district.id} value={district.id}>
                                {district.name}
                              </option>
                            ))}
                          </select>
                          {!isEditingAll && (
                            <>
                              <button
                                onClick={() => handleSaveField('district')}
                                className="p-1 text-green-600 hover:text-green-700"
                                disabled={!selectedDistrictId}
                              >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                                </svg>
                              </button>
                              <button
                                onClick={handleCancelEdit}
                                className="p-1 text-red-600 hover:text-red-700"
                              >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                              </button>
                            </>
                          )}
                        </div>
                      ) : (
                        <div className="flex items-center justify-between">
                          <p className="text-gray-900 font-medium">{currentUser.district}</p>
                          {!isEditingAll && (
                            <button
                              onClick={() => {
                                // Find current city and district IDs
                                const currentCity = cities.find(city => city.name === currentUser.city);
                                if (currentCity) {
                                  setSelectedCityId(currentCity.id);
                                  // Wait for districts to load, then find district ID
                                  setTimeout(() => {
                                    const currentDistrict = districts.find(district => district.name === currentUser.district);
                                    setSelectedDistrictId(currentDistrict?.id || null);
                                  }, 100);
                                }
                                handleEditField('district', currentUser.district);
                              }}
                              className="p-1 text-gray-400 hover:text-blue-600 transition-all"
                            >
                              <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                              </svg>
                            </button>
                          )}
                        </div>
                      )}
                    </div>
                  </div>
                </div>

                {/* Categories - MOVED HERE */}
                <div>
                  <h4 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg className="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    Uzmanlık Alanları
                  </h4>
                  <div className="bg-gray-50 p-4 rounded-lg relative group">
                    {(editingField === 'categories' || isEditingAll) ? (
                      <div className="space-y-3">
                        {/* Category Search and Selection */}
                                                <div className="relative category-dropdown-container">
                          <input
                            type="text"
                            value={categorySearch}
                            onChange={(e) => {
                              setCategorySearch(e.target.value);
                              setShowCategoryDropdown(true);
                            }}
                            onFocus={() => {
                              setShowCategoryDropdown(true);
                            }}
                            className="w-full p-3 border border-gray-300 rounded-lg text-gray-900"
                            placeholder="Kategori ara ve seç... (örn: elektrikçi, temizlik, boyacı)"
                            autoFocus={editingField === 'categories'}
                          />
                          
                          {/* Category Dropdown */}
                          {showCategoryDropdown && (
                            <div className="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                              <div className="p-2">
                                <div className="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                                  {categorySearch ? `Arama Sonuçları (${filteredCategories.length})` : `Tüm Kategoriler (${filteredCategories.length})`}
                                </div>
                                {filteredCategories.length === 0 ? (
                                  <div className="text-sm text-gray-500 p-2">
                                    {categorySearch ? 'Arama sonucu bulunamadı' : 'Kategori yükleniyor...'}
                                  </div>
                                ) : (
                                  filteredCategories.map((category, index) => (
                                    <button
                                      key={index}
                                      onClick={() => {
                                        const categoryName = category.name;
                                        if (!selectedCategories.includes(categoryName)) {
                                          if (selectedCategories.length >= 5) {
                                            toast.error(
                                              'Kategori Sınırı', 
                                              'En fazla 5 kategori seçebilirsiniz. Ana uzmanlık alanlarınızı seçin.'
                                            );
                                            return;
                                          }
                                          const newCategories = [...selectedCategories, categoryName];
                                          setSelectedCategories(newCategories);
                                          setEditValues({...editValues, categories: newCategories.join(', ')});
                                        }
                                        setCategorySearch('');
                                        setShowCategoryDropdown(false);
                                      }}
                                      className="w-full text-left px-3 py-2 hover:bg-indigo-50 text-sm text-gray-700 rounded transition-colors"
                                      disabled={selectedCategories.includes(category.name)}
                                    >
                                      <span className={selectedCategories.includes(category.name) ? 'text-gray-400' : 'text-gray-700'}>
                                        {category.icon && <span className="mr-2">{category.icon}</span>}
                                        {category.name}
                                      </span>
                                      {selectedCategories.includes(category.name) && (
                                        <span className="text-green-500 ml-2">✓ Seçildi</span>
                                      )}
                                    </button>
                                  ))
                                )}
                              </div>
                            </div>
                          )}
                        </div>

                        {/* Selected Categories */}
                        {selectedCategories.length > 0 && (
                          <div className="space-y-2">
                            <label className="text-sm font-medium text-gray-500">
                              Seçilen Kategoriler ({selectedCategories.length}/5):
                            </label>
                            <div className="flex flex-wrap gap-2">
                              {selectedCategories.map((category) => (
                                <span
                                  key={category}
                                  className="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800"
                                >
                                  {category}
                                  <button
                                    onClick={() => {
                                      const newCategories = selectedCategories.filter(c => c !== category);
                                      setSelectedCategories(newCategories);
                                      setEditValues({...editValues, categories: newCategories.join(', ')});
                                    }}
                                    className="ml-2 text-blue-600 hover:text-indigo-800"
                                  >
                                    ×
                                  </button>
                                </span>
                              ))}
                            </div>
                            {selectedCategories.length >= 4 && (
                              <div className="text-sm text-amber-600 bg-amber-50 p-2 rounded-lg">
                                💡 {selectedCategories.length === 4 ? 'Son 1 kategori seçebilirsiniz!' : 'Maksimum 5 kategori seçildi. Ana uzmanlık alanlarınızı seçtiniz.'}
                              </div>
                            )}
                          </div>
                        )}

                        {!isEditingAll && (
                          <div className="flex items-center justify-end space-x-2 pt-3">
                            <button
                              onClick={() => handleSaveField('categories')}
                              className="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center"
                            >
                              <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                              </svg>
                              Kaydet
                            </button>
                            <button
                              onClick={handleCancelEdit}
                              className="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center"
                            >
                              <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                              </svg>
                              İptal
                            </button>
                          </div>
                        )}
                      </div>
                    ) : (
                      <div className="flex items-start justify-between">
                        <div className="flex-1">
                          {currentUser.categories && currentUser.categories.length > 0 ? (
                            <div className="flex flex-wrap gap-2">
                              {currentUser.categories.map((category) => (
                                <span
                                  key={category.id}
                                  className="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800"
                                >
                                  {category.name}
                                </span>
                              ))}
                            </div>
                          ) : (
                            <p className="text-gray-500 italic">
                              Henüz uzmanlık alanı eklenmemiş. Kategorilerinizi seçmek için düzenle butonuna tıklayın.
                            </p>
                          )}
                        </div>
                        {!isEditingAll && (
                          <button
                            onClick={() => {
                              // Set current categories for editing
                              const currentCategories = currentUser.categories?.map(cat => cat.name) || [];
                              setSelectedCategories(currentCategories);
                              setEditValues({...editValues, categories: currentCategories.join(', ')});
                              handleEditField('categories', currentCategories.join(', '));
                            }}
                            className="p-1 text-gray-400 hover:text-blue-600 transition-all ml-2 flex-shrink-0"
                          >
                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                          </button>
                        )}
                      </div>
                    )}
                  </div>
                </div>

                {/* Bio */}
                <div>
                  <h4 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg className="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    Hakkımda
                  </h4>
                  <div className="bg-gray-50 p-4 rounded-lg relative group">
                    {(editingField === 'bio' || isEditingAll) ? (
                      <div className="space-y-3">
                        <textarea
                          value={editValues.bio || ''}
                          onChange={(e) => setEditValues({...editValues, bio: e.target.value})}
                          className="w-full p-3 border border-gray-300 rounded-lg text-gray-900 leading-relaxed resize-none"
                          rows={4}
                          placeholder="Kendinizden bahsedin, uzmanlık alanlarınızı ve deneyimlerinizi paylaşın..."
                          autoFocus={editingField === 'bio'}
                        />
                        {!isEditingAll && (
                          <div className="flex items-center justify-end space-x-2">
                            <button
                              onClick={() => handleSaveField('bio')}
                              className="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center"
                            >
                              <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                              </svg>
                              Kaydet
                            </button>
                            <button
                              onClick={handleCancelEdit}
                              className="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center"
                            >
                              <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                              </svg>
                              İptal
                            </button>
                          </div>
                        )}
                      </div>
                    ) : (
                      <div className="flex items-start justify-between">
                        <p className="text-gray-900 leading-relaxed flex-1">
                          {currentUser.bio || 'Henüz bir açıklama eklenmemiş. Kendinizden bahsetmek için tıklayın.'}
                        </p>
                        {!isEditingAll && (
                          <button
                            onClick={() => handleEditField('bio', currentUser.bio || '')}
                            className="p-1 text-gray-400 hover:text-blue-600 transition-all ml-2 flex-shrink-0"
                          >
                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                          </button>
                        )}
                      </div>
                    )}
                  </div>
                </div>

                {/* Rating - Read Only */}
                <div className="bg-gray-50 p-4 rounded-lg">
                  <label className="text-sm font-medium text-gray-500">Değerlendirme</label>
                  <p className="text-gray-900 font-medium flex items-center">
                    <svg className="w-4 h-4 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    {currentUser.rating?.toFixed(1) || '5.0'} ({currentUser.reviewCount || 0} değerlendirme)
                  </p>
                </div>
              </div>
            </div>

            {/* Modal Footer */}
            <div className="flex items-center justify-center p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
              {isEditingAll ? (
                <div className="flex items-center space-x-4">
                  <button
                    onClick={handleSaveAll}
                    className="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors flex items-center"
                  >
                    <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Tüm Değişiklikleri Kaydet
                  </button>
                  <button
                    onClick={handleCancelAll}
                    className="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors flex items-center"
                  >
                    <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    İptal Et
                  </button>
                </div>
              ) : (
                <button
                  onClick={() => setShowProfileModal(false)}
                  className="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors"
                >
                  Kapat
                </button>
              )}
            </div>
          </div>
        </div>
      )}
      
      {/* Web Page Creation Modal */}
      {showWebPageModal && currentUser.categories && (
        <CreateWebPageModal
          isOpen={showWebPageModal}
          onClose={() => setShowWebPageModal(false)}
          userCategories={currentUser.categories}
          userData={{
            firstName: currentUser.firstName,
            lastName: currentUser.lastName,
            bio: currentUser.bio,
            companyName: currentUser.companyName,
            experienceYears: currentUser.experienceYears,
            city: currentUser.city,
            district: currentUser.district
          }}
          onSuccess={handleWebPageSuccess}
        />
      )}
      
      {/* Toast Container */}
      <ToastContainer toasts={toast.toasts} onRemove={toast.removeToast} />
      
      {/* Footer */}
      <Footer />
    </div>
  );
}
