"use client";

import { useState, useEffect } from "react";
import Link from "next/link";
import Image from "next/image";
import Navigation from "../../../components/Navigation";
import Footer from "../../../components/Footer";

interface User {
  id: string;
  firstName: string;
  lastName: string;
  email: string;
  phone?: string;
  avatar?: string;
  bio?: string;
  website?: string;
  experienceYears?: number;
  rating?: number;
  reviewCount?: number;
  completedJobsCount?: number;
  companyName?: string;
  isVerified?: boolean;
  city?: {
    name: string;
  };
  district?: {
    name: string;
  };
  userCategories?: {
    id: string;
    category: {
      id: number;
      name: string;
    };
    experience?: string;
    skills?: string[];
    minPrice?: number;
    maxPrice?: number;
  }[];
  publicPageTitle?: string;
  publicPageSlug?: string;
  publicPageActive?: boolean;
}

interface PublicProfilePageProps {
  params: {
    id: string;
    slug: string;
  };
}

export default function PublicProfilePage({ params }: PublicProfilePageProps) {
  const [user, setUser] = useState<User | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [notFound, setNotFound] = useState(false);

  useEffect(() => {
    const loadUserData = async () => {
      try {
        const response = await fetch(`/api/public-profile/${params.id}?slug=${params.slug}`);
        const data = await response.json();
        
        if (response.ok && data.success) {
          setUser(data.user);
        } else {
          setNotFound(true);
        }
      } catch (error) {
        console.error('Error loading public profile:', error);
        setNotFound(true);
      } finally {
        setIsLoading(false);
      }
    };

    loadUserData();
  }, [params.id, params.slug]);

  const handleCallNow = () => {
    if (user?.phone) {
      window.location.href = `tel:${user.phone}`;
    }
  };

  if (isLoading) {
    return (
      <div className="min-h-screen bg-gradient-to-br from-indigo-50 via-blue-50 to-purple-50 flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto mb-4"></div>
          <p className="text-gray-600">Profil yükleniyor...</p>
        </div>
      </div>
    );
  }

  if (notFound || !user || !user.publicPageActive) {
    return (
      <div className="min-h-screen bg-gradient-to-br from-indigo-50 via-blue-50 to-purple-50">
        <Navigation />
        <div className="pt-24 flex items-center justify-center min-h-screen">
          <div className="text-center max-w-md mx-auto px-4">
            <div className="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg className="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z" />
              </svg>
            </div>
            <h1 className="text-2xl font-bold text-gray-900 mb-4">Sayfa Bulunamadı</h1>
            <p className="text-gray-600 mb-8">Aradığınız profil sayfası mevcut değil veya kaldırılmış.</p>
            <Link
              href="/"
              className="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors"
            >
              Ana Sayfaya Dön
            </Link>
          </div>
        </div>
        <Footer />
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 via-sky-50 to-blue-100">
      {/* OnlineUsta Navigation */}
      <Navigation />

      {/* Hero Section */}
      <section className="pt-24 py-16 px-4 sm:px-6 lg:px-8">
        <div className="max-w-4xl mx-auto">
          <div className="bg-white rounded-3xl shadow-xl overflow-hidden">
            {/* Profile Header */}
            <div className="bg-gradient-to-r from-blue-600 to-blue-800 p-8 text-white">
              <div className="flex flex-col space-y-6">
                {/* Info */}
                <div className="text-center">
                  <h1 className="text-3xl md:text-4xl font-bold mb-4">
                    {user.publicPageTitle || user.userCategories?.[0]?.category.name || 'Profesyonel Hizmet'}
                  </h1>
                  
                  <div className="space-y-3">
                    {/* Location */}
                    <div className="flex items-center justify-center space-x-2">
                      <svg className="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                      <span className="text-white/90">{user.district?.name}, {user.city?.name}</span>
                    </div>

                    {/* Experience */}
                    {user.experienceYears && (
                      <div className="flex items-center justify-center space-x-2">
                        <svg className="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span className="text-white/90">{user.experienceYears} yıl deneyim</span>
                      </div>
                    )}

                    {/* Rating */}
                    {user.rating && (
                      <div className="flex items-center justify-center space-x-2">
                        <div className="flex items-center">
                          {[...Array(5)].map((_, i) => (
                            <svg
                              key={i}
                              className={`w-5 h-5 ${i < Math.floor(user.rating!) ? 'text-yellow-400' : 'text-white/30'}`}
                              fill="currentColor"
                              viewBox="0 0 20 20"
                            >
                              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                          ))}
                        </div>
                        <span className="text-white/90">
                          {user.rating.toFixed(1)} ({user.reviewCount || 0} değerlendirme)
                        </span>
                      </div>
                    )}

                    {/* Phone Number */}
                    {user.phone && (
                      <div className="mt-6 pt-6 border-t border-white/20">
                        <button
                          onClick={handleCallNow}
                          className="flex items-center justify-center space-x-3 w-full bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl py-4 px-6 transition-all duration-300 group border border-white/30 hover:border-white/50 max-w-md mx-auto"
                        >
                          <div className="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg className="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                          </div>
                          <div className="text-center">
                            <div className="text-white/80 text-sm font-medium">Hemen Ara</div>
                            <div className="text-white text-lg font-bold">{user.phone}</div>
                          </div>
                        </button>
                      </div>
                    )}
                  </div>
                </div>
              </div>
            </div>

            {/* Content */}
            <div className="p-8">
              <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Main Content */}
                <div className="lg:col-span-2 space-y-8">
                  {/* About */}
                  {user.bio && (
                    <section>
                      <h2 className="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg className="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Hakkımda
                      </h2>
                      <div className="bg-blue-50 rounded-xl p-6 border border-blue-100">
                        <p className="text-gray-700 leading-relaxed">{user.bio}</p>
                      </div>
                    </section>
                  )}

                  {/* Expertise */}
                  {user.userCategories && user.userCategories.length > 0 && (
                    <section>
                      <h2 className="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg className="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        Uzmanlık Alanları
                      </h2>
                      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {user.userCategories.map((category) => (
                          <div key={category.id} className="bg-gradient-to-r from-blue-50 to-sky-50 rounded-xl p-6 border border-blue-100">
                            <h3 className="font-semibold text-gray-900 mb-2">{category.category.name}</h3>
                            {category.experience && (
                              <p className="text-gray-600 text-sm mb-3">{category.experience}</p>
                            )}
                            {category.skills && category.skills.length > 0 && (
                              <div className="flex flex-wrap gap-2">
                                {category.skills.map((skill, index) => (
                                  <span
                                    key={index}
                                    className="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full"
                                  >
                                    {skill}
                                  </span>
                                ))}
                              </div>
                            )}
                          </div>
                        ))}
                      </div>
                    </section>
                  )}
                </div>

                {/* Sidebar - Call to Action */}
                <div className="space-y-6">
                  {/* Call Now */}
                  {user.phone && (
                    <div className="bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl p-6 text-white text-center shadow-xl">
                      <div className="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg className="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                      </div>
                      <h3 className="text-xl font-bold mb-2">Hemen Ara!</h3>
                      <p className="text-white/90 text-sm mb-4">
                        Ücretsiz görüşme ve fiyat teklifi için tıklayın
                      </p>
                      <button
                        onClick={handleCallNow}
                        className="w-full bg-white hover:bg-gray-100 text-green-600 font-bold py-4 px-6 rounded-xl transition-colors text-lg flex items-center justify-center space-x-2"
                      >
                        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>{user.phone}</span>
                      </button>
                    </div>
                  )}

                  {/* Features */}
                  <div className="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 className="font-semibold text-gray-900 mb-4">Neden Beni Seçmelisiniz?</h3>
                    <div className="space-y-3">
                      <div className="flex items-center space-x-3">
                        <div className="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                          <svg className="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                          </svg>
                        </div>
                        <span className="text-gray-700 text-sm">Hızlı ve güvenilir hizmet</span>
                      </div>
                      <div className="flex items-center space-x-3">
                        <div className="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                          <svg className="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                          </svg>
                        </div>
                        <span className="text-gray-700 text-sm">Uygun fiyat garantisi</span>
                      </div>
                      <div className="flex items-center space-x-3">
                        <div className="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                          <svg className="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                          </svg>
                        </div>
                        <span className="text-gray-700 text-sm">Sigortalı ve garantili</span>
                      </div>
                      {user.experienceYears && (
                        <div className="flex items-center space-x-3">
                          <div className="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg className="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                          </div>
                          <span className="text-gray-700 text-sm">{user.experienceYears} yıl tecrübe</span>
                        </div>
                      )}
                    </div>
                  </div>

                  {/* OnlineUsta Badge */}
                  <div className="bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 rounded-2xl p-6 text-white text-center shadow-2xl">
                    {/* Logo */}
                    <div className="flex items-center justify-center mb-4">
                      <Image
                        src="/images/logo.png"
                        alt="OnlineUsta Logo"
                        width={60}
                        height={60}
                        className="object-contain hover:scale-105 transition-transform duration-300"
                      />
                    </div>
                    
                    {/* Başlık */}
                    <h3 className="text-xl font-bold mb-3 leading-tight">
                      Siz de OnlineUsta'ya<br />
                      Üye Olun!
                    </h3>
                    
                    {/* Açıklama */}
                    <p className="text-white/90 text-sm mb-6 leading-relaxed">
                      Ücretsiz web sitenizi oluşturun<br />
                      ve yüzlerce iş ilanına ücretsiz<br />
                      teklif verin.
                    </p>
                    
                    {/* CTA Button */}
                    <Link
                      href="/hizmet-veren-kaydi"
                      className="inline-block w-full bg-white hover:bg-gray-100 text-blue-700 font-bold py-3 px-6 rounded-xl transition-all duration-300 hover:shadow-lg hover:scale-105"
                    >
                      Hemen Üye Ol
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* OnlineUsta Footer */}
      <Footer />
    </div>
  );
}