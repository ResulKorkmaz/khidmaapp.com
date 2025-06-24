"use client";

import { useState } from "react";

interface CreateWebPageModalProps {
  isOpen: boolean;
  onClose: () => void;
  userCategories: Array<{
    id: number;
    name: string;
    experience?: string;
    skills?: string[];
  }>;
  userData: {
    firstName: string;
    lastName: string;
    bio?: string;
    companyName?: string;
    experienceYears?: number;
    city?: string;
    district?: string;
  };
  onSuccess: (pageUrl: string) => void;
}

export default function CreateWebPageModal({
  isOpen,
  onClose,
  userCategories,
  userData,
  onSuccess
}: CreateWebPageModalProps) {
  // Otomatik başlık ve slug oluştur
  const generateAutoPageData = () => {
    const name = `${userData.firstName} ${userData.lastName}`;
    const primaryCategory = userCategories[0]?.name || 'Uzman';
    const city = userData.city || '';
    
    // Başlık: "Resul Korkmaz - Google Ads Uzmanı"
    const pageTitle = `${name} - ${primaryCategory}`;
    
    // Slug: "resul-korkmaz-google-ads-uzmani-samsun"
    const slugParts = [
      name,
      primaryCategory,
      city
    ].filter(Boolean); // Boş değerleri filtrele
    
    const slug = slugParts
      .join(' ')
      .toLowerCase()
      .replace(/ğ/g, 'g')
      .replace(/ü/g, 'u')
      .replace(/ş/g, 's')
      .replace(/ı/g, 'i')
      .replace(/ö/g, 'o')
      .replace(/ç/g, 'c')
      .replace(/[^a-z0-9\s]/g, '')
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-')
      .replace(/^-+|-+$/g, '');
    
    return { pageTitle, slug };
  };

  const { pageTitle, slug } = generateAutoPageData();
  const previewUrl = `onlineusta.com.tr/1/${slug}`;

  const [isLoading, setIsLoading] = useState(false);

  const handleSubmit = async () => {
    if (!pageTitle.trim()) {
      const event = new CustomEvent('toast', {
        detail: {
          type: 'warning',
          message: 'Sayfa bilgileri eksik!'
        }
      });
      window.dispatchEvent(event);
      return;
    }

    setIsLoading(true);

    try {
      // API çağrısı
      const response = await fetch('/api/create-public-page', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          pageTitle,
          slug,
          categoryId: userCategories[0]?.id || 0,
          // Profil bilgilerini de gönder
          userData: {
            firstName: userData.firstName,
            lastName: userData.lastName,
            bio: userData.bio,
            companyName: userData.companyName,
            experienceYears: userData.experienceYears,
            city: userData.city,
            district: userData.district
          }
        }),
      });

      const data = await response.json();

      if (data.success) {
        const pageUrl = `/${data.userId}/${slug}`;
        onSuccess(pageUrl);
        onClose();
        
        // Toast mesajı
        const event = new CustomEvent('toast', {
          detail: {
            type: 'success',
            message: 'Web sayfanız başarıyla oluşturuldu! URL kopyalandı.',
          }
        });
        window.dispatchEvent(event);
      } else {
        throw new Error(data.error || 'Sayfa oluşturulamadı');
      }
    } catch (error) {
      console.error('Web page creation error:', error);
      const event = new CustomEvent('toast', {
        detail: {
          type: 'error',
          message: 'Sayfa oluşturulamadı. Lütfen tekrar deneyin.',
        }
      });
      window.dispatchEvent(event);
    } finally {
      setIsLoading(false);
    }
  };

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div className="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        {/* Header */}
        <div className="bg-gradient-to-r from-indigo-600 to-purple-600 p-6 text-white rounded-t-2xl">
          <div className="flex items-center justify-between">
            <div>
              <h2 className="text-2xl font-bold">Web Sayfası Oluştur</h2>
              <p className="text-indigo-100 mt-1">
                Profil bilgilerinizden otomatik sayfa oluşturulacak
              </p>
            </div>
            <button
              onClick={onClose}
              className="text-white/80 hover:text-white"
            >
              <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        {/* Content */}
        <div className="p-6 space-y-6">
          {/* Profil Özeti */}
          <div className="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-200">
            <h3 className="font-semibold text-gray-900 mb-3">Profil Bilgileriniz</h3>
            <div className="grid grid-cols-2 gap-4 text-sm">
              <div>
                <span className="text-gray-600">İsim:</span>
                <span className="ml-2 font-medium">{userData.firstName} {userData.lastName}</span>
              </div>
              {userData.companyName && (
                <div>
                  <span className="text-gray-600">Firma:</span>
                  <span className="ml-2 font-medium">{userData.companyName}</span>
                </div>
              )}
              <div>
                <span className="text-gray-600">Kategori:</span>
                <span className="ml-2 font-medium">{userCategories[0]?.name}</span>
              </div>
              {userData.city && (
                <div>
                  <span className="text-gray-600">Şehir:</span>
                  <span className="ml-2 font-medium">{userData.city}</span>
                </div>
              )}
              {userData.experienceYears && (
                <div>
                  <span className="text-gray-600">Deneyim:</span>
                  <span className="ml-2 font-medium">{userData.experienceYears} yıl</span>
                </div>
              )}
            </div>
          </div>

          {/* Otomatik Oluşturulan Sayfa Bilgileri */}
          <div className="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-lg border border-green-200">
            <h3 className="font-semibold text-gray-900 mb-4 flex items-center">
              <svg className="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Oluşturulacak Web Sayfası
            </h3>
            
            <div className="space-y-4">
              {/* Sayfa Başlığı */}
              <div className="bg-white p-4 rounded-lg border border-green-200">
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Sayfa Başlığı
                </label>
                <div className="text-lg font-semibold text-gray-900">
                  {pageTitle}
                </div>
              </div>

              {/* URL */}
              <div className="bg-white p-4 rounded-lg border border-green-200">
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Web Sayfası URL'si
                </label>
                <div className="text-lg font-mono text-indigo-600 bg-indigo-50 p-3 rounded-lg border border-indigo-200">
                  {previewUrl}
                </div>
              </div>

              {/* Açıklama */}
              <div className="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                <div className="flex items-start">
                  <svg className="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <div className="text-sm text-yellow-800">
                    <p className="font-semibold mb-1">Otomatik Oluşturma</p>
                    <p>
                      Sayfa başlığı ve URL'si profil bilgilerinizden otomatik olarak oluşturulmuştur. 
                      Bu bilgiler SEO dostu ve arama motorları için optimize edilmiştir.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Eylem Butonları */}
          <div className="flex items-center justify-end space-x-4 pt-6 border-t">
            <button
              type="button"
              onClick={onClose}
              className="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
            >
              İptal
            </button>
            <button
              onClick={handleSubmit}
              disabled={isLoading}
              className="px-8 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center"
            >
              {isLoading ? (
                <>
                  <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Oluşturuluyor...
                </>
              ) : (
                <>
                  <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                  Web Sayfası Oluştur
                </>
              )}
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}