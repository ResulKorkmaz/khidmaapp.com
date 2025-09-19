'use client'

import { useState, useEffect } from 'react'
import { Phone, MapPin, Star, Award, Clock, User, Mail, CheckCircle, MessageCircle, ArrowLeft } from 'lucide-react'
import Link from 'next/link'
import { useParams } from 'next/navigation'

interface ProviderData {
  firstName: string
  lastName: string
  phone: string
  email: string
  city: string
  district: string
  services: string[]
  gender: string
  about: string
  skills: string
  experience: string
}

interface WebsiteData {
  slug: string
  profileData: ProviderData
  createdAt: string
}

export default function ProviderWebsitePage() {
  const params = useParams()
  const slug = Array.isArray(params.slug) ? params.slug[0] : params.slug as string
  
  const [websiteData, setWebsiteData] = useState<WebsiteData | null>(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    // Try to load website data with different slug formats
    const decodedSlug = decodeURIComponent(slug)
    
    // Try multiple key formats for compatibility
    let savedWebsiteData = localStorage.getItem(`website-${slug}`)
    
    if (!savedWebsiteData) {
      savedWebsiteData = localStorage.getItem(`website-${decodedSlug}`)
    }
    
    // Also try with original encoding
    if (!savedWebsiteData) {
      const encodedSlug = encodeURIComponent(decodedSlug)
      savedWebsiteData = localStorage.getItem(`website-${encodedSlug}`)
    }
    
    if (savedWebsiteData) {
      setWebsiteData(JSON.parse(savedWebsiteData))
    }
    
    setLoading(false)
  }, [slug])

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto mb-4"></div>
          <p className="text-gray-600">جاري تحميل الموقع...</p>
        </div>
      </div>
    )
  }

  if (!websiteData) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center" dir="rtl">
        <div className="text-center max-w-md mx-auto px-4">
          <div className="bg-white rounded-xl shadow-lg p-8">
            <div className="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <User className="w-8 h-8 text-red-600" />
            </div>
            <h1 className="text-2xl font-bold text-gray-800 mb-2">الموقع غير موجود</h1>
            <p className="text-gray-600 mb-6">عذراً، الموقع المطلوب غير متوفر</p>
            <Link 
              href="/ar"
              className="inline-flex items-center bg-primary-500 text-white px-6 py-3 rounded-lg hover:bg-primary-600 transition-colors"
            >
              <ArrowLeft className="w-4 h-4 ml-2" />
              العودة للرئيسية
            </Link>
          </div>
        </div>
      </div>
    )
  }

  const provider = websiteData.profileData
  const fullName = `${provider.firstName} ${provider.lastName}`
  const primaryService = provider.services[0] || 'مقدم خدمات'

  return (
    <div className="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100" dir="rtl">
      {/* Header */}
      <header className="bg-white shadow-sm">
        <div className="max-w-6xl mx-auto px-4 py-4">
          <div className="flex items-center justify-between">
            <div className="flex items-center space-x-3 rtl:space-x-reverse">
              <div className="w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center">
                <User className="w-6 h-6 text-white" />
              </div>
              <div>
                <h1 className="text-xl font-bold text-navy-800">{fullName}</h1>
                <p className="text-sm text-gray-600">{primaryService}</p>
              </div>
            </div>
            <Link 
              href="/ar"
              className="text-gray-600 hover:text-primary-600 transition-colors"
            >
              منصة خدمة أب
            </Link>
          </div>
        </div>
      </header>

      {/* Hero Section */}
      <section className="bg-gradient-to-r from-primary-500 to-primary-700 text-white py-16">
        <div className="max-w-6xl mx-auto px-4">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
              <div className="flex items-center mb-4">
                <div className="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center ml-6">
                  <User className="w-12 h-12 text-white" />
                </div>
                <div>
                  <h1 className="text-4xl font-bold mb-2">{fullName}</h1>
                  <p className="text-xl text-primary-100">{primaryService}</p>
                </div>
              </div>
              
              <div className="flex items-center space-x-6 rtl:space-x-reverse mb-6">
                <div className="flex items-center">
                  <MapPin className="w-5 h-5 text-primary-200 ml-2" />
                  <span className="text-primary-100">{provider.city} - {provider.district}</span>
                </div>
                <div className="flex items-center">
                  <Star className="w-5 h-5 text-yellow-400 ml-1" />
                  <span className="text-primary-100">4.8 (127 تقييم)</span>
                </div>
              </div>

              <div className="flex flex-col sm:flex-row gap-4 mt-8">
                <a 
                  href={`tel:${provider.phone}`}
                  className="bg-white text-primary-700 px-8 py-4 rounded-xl font-bold hover:bg-primary-50 transition-colors flex items-center justify-center"
                >
                  <Phone className="w-5 h-5 ml-2" />
                  اتصل الآن
                </a>
                <a 
                  href={`https://wa.me/${provider.phone.replace(/[^0-9]/g, '')}`}
                  className="bg-green-500 text-white px-8 py-4 rounded-xl font-bold hover:bg-green-600 transition-colors flex items-center justify-center"
                >
                  <MessageCircle className="w-5 h-5 ml-2" />
                  واتساب
                </a>
              </div>
            </div>

            <div className="relative">
              <div className="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                <h3 className="text-2xl font-bold mb-6">معلومات الاتصال</h3>
                
                <div className="space-y-4">
                  <div className="flex items-center">
                    <div className="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center ml-4">
                      <Phone className="w-6 h-6 text-white" />
                    </div>
                    <div>
                      <p className="text-primary-100 text-sm">رقم الجوال</p>
                      <p className="text-white font-bold text-lg">{provider.phone}</p>
                    </div>
                  </div>
                  
                  <div className="flex items-center">
                    <div className="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center ml-4">
                      <MapPin className="w-6 h-6 text-white" />
                    </div>
                    <div>
                      <p className="text-primary-100 text-sm">الموقع</p>
                      <p className="text-white font-bold">{provider.city} - {provider.district}</p>
                    </div>
                  </div>
                  
                  {provider.email && (
                    <div className="flex items-center">
                      <div className="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center ml-4">
                        <Mail className="w-6 h-6 text-white" />
                      </div>
                      <div>
                        <p className="text-primary-100 text-sm">البريد الإلكتروني</p>
                        <p className="text-white font-bold">{provider.email}</p>
                      </div>
                    </div>
                  )}
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Services Section */}
      <section className="py-16 bg-white">
        <div className="max-w-6xl mx-auto px-4">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-navy-800 mb-4">الخدمات المتوفرة</h2>
            <p className="text-gray-600 text-lg">نقدم خدمات عالية الجودة مع ضمان الرضا التام</p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {provider.services.map((service, index) => (
              <div key={index} className="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-shadow">
                <div className="w-16 h-16 bg-primary-100 rounded-xl flex items-center justify-center mb-4">
                  <Award className="w-8 h-8 text-primary-600" />
                </div>
                <h3 className="text-xl font-bold text-navy-800 mb-2">{service}</h3>
                <p className="text-gray-600 mb-4">خدمة احترافية عالية الجودة مع ضمان الرضا</p>
                <div className="flex items-center justify-between">
                  <span className="text-primary-600 font-bold">متوفر</span>
                  <CheckCircle className="w-5 h-5 text-green-500" />
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* About Section */}
      <section className="py-16 bg-gray-50">
        <div className="max-w-6xl mx-auto px-4">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
              <h2 className="text-3xl font-bold text-navy-800 mb-6">من نحن</h2>
              <div className="prose prose-lg text-gray-700">
                <p className="mb-6 leading-relaxed">
                  {provider.about || `نحن فريق محترف متخصص في ${primaryService} نسعى لتقديم أفضل الخدمات لعملائنا الكرام مع ضمان الجودة والمواعيد.`}
                </p>
                
                {provider.experience && (
                  <div className="bg-white p-6 rounded-xl border-r-4 border-primary-500">
                    <h4 className="font-bold text-navy-800 mb-2 flex items-center">
                      <Clock className="w-5 h-5 text-primary-600 ml-2" />
                      خبرتنا
                    </h4>
                    <p className="text-gray-700">{provider.experience}</p>
                  </div>
                )}
              </div>
            </div>

            <div>
              <h2 className="text-3xl font-bold text-navy-800 mb-6">مهاراتنا</h2>
              
              {provider.skills ? (
                <div className="bg-white p-6 rounded-xl">
                  <div className="flex items-center mb-4">
                    <Award className="w-6 h-6 text-primary-600 ml-2" />
                    <h4 className="font-bold text-navy-800">المهارات والتخصصات</h4>
                  </div>
                  <p className="text-gray-700 leading-relaxed">{provider.skills}</p>
                </div>
              ) : (
                <div className="space-y-4">
                  <div className="flex items-center bg-white p-4 rounded-xl">
                    <CheckCircle className="w-6 h-6 text-green-500 ml-3" />
                    <span className="text-gray-800">جودة عالية في العمل</span>
                  </div>
                  <div className="flex items-center bg-white p-4 rounded-xl">
                    <CheckCircle className="w-6 h-6 text-green-500 ml-3" />
                    <span className="text-gray-800">التزام بالمواعيد</span>
                  </div>
                  <div className="flex items-center bg-white p-4 rounded-xl">
                    <CheckCircle className="w-6 h-6 text-green-500 ml-3" />
                    <span className="text-gray-800">أسعار تنافسية</span>
                  </div>
                  <div className="flex items-center bg-white p-4 rounded-xl">
                    <CheckCircle className="w-6 h-6 text-green-500 ml-3" />
                    <span className="text-gray-800">خدمة عملاء ممتازة</span>
                  </div>
                </div>
              )}
            </div>
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-16 bg-navy-800 text-white">
        <div className="max-w-4xl mx-auto px-4 text-center">
          <h2 className="text-3xl font-bold mb-4">هل تحتاج خدماتنا؟</h2>
          <p className="text-xl text-gray-300 mb-8">اتصل بنا الآن للحصول على استشارة مجانية وعرض سعر</p>
          
          <div className="flex flex-col sm:flex-row gap-4 justify-center max-w-md mx-auto">
            <a 
              href={`tel:${provider.phone}`}
              className="bg-primary-500 text-white px-8 py-4 rounded-xl font-bold hover:bg-primary-600 transition-colors flex items-center justify-center"
            >
              <Phone className="w-5 h-5 ml-2" />
              اتصل الآن
            </a>
            <a 
              href={`https://wa.me/${provider.phone.replace(/[^0-9]/g, '')}`}
              className="bg-green-500 text-white px-8 py-4 rounded-xl font-bold hover:bg-green-600 transition-colors flex items-center justify-center"
            >
              <MessageCircle className="w-5 h-5 ml-2" />
              واتساب
            </a>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-gray-900 text-white py-8">
        <div className="max-w-6xl mx-auto px-4">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
              <h3 className="text-lg font-bold mb-4">{fullName}</h3>
              <p className="text-gray-400">{primaryService} في {provider.city}</p>
            </div>
            
            <div>
              <h4 className="font-bold mb-4">معلومات الاتصال</h4>
              <div className="space-y-2 text-gray-400">
                <p>📱 {provider.phone}</p>
                <p>📍 {provider.city} - {provider.district}</p>
                {provider.email && <p>✉️ {provider.email}</p>}
              </div>
            </div>
            
            <div>
              <h4 className="font-bold mb-4">الخدمات</h4>
              <div className="space-y-1 text-gray-400">
                {provider.services.map((service, index) => (
                  <p key={index}>• {service}</p>
                ))}
              </div>
            </div>
          </div>
          
          <div className="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>جميع الحقوق محفوظة © {new Date().getFullYear()} - تم إنشاء هذا الموقع عبر منصة خدمة أب</p>
            <Link href="/ar" className="text-primary-400 hover:text-primary-300 mt-2 inline-block">
              khidmaapp.com
            </Link>
          </div>
        </div>
      </footer>
    </div>
  )
}
