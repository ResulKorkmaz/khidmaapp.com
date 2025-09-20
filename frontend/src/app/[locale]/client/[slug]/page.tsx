'use client'

import { useState, useEffect } from 'react'
import { Phone, MapPin, Star, User, Mail, MessageCircle, ArrowLeft, ShoppingBag, Clock, CheckCircle } from 'lucide-react'
import Link from 'next/link'
import { useParams } from 'next/navigation'

interface ClientData {
  firstName: string
  lastName: string
  phone: string
  email: string
  city: string
  district: string
  gender: string
}

interface WebsiteData {
  slug: string
  profileData: ClientData
  type: string
  createdAt: string
}

export default function ClientWebsitePage() {
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
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-gold-600 mx-auto mb-4"></div>
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
              className="inline-flex items-center bg-gold-500 text-white px-6 py-3 rounded-lg hover:bg-gold-600 transition-colors"
            >
              <ArrowLeft className="w-4 h-4 ml-2" />
              العودة للرئيسية
            </Link>
          </div>
        </div>
      </div>
    )
  }

  const client = websiteData.profileData
  const fullName = `${client.firstName} ${client.lastName}`

  return (
    <div className="min-h-screen bg-gradient-to-br from-gold-50 to-gold-100 pt-32 pb-12" dir="rtl">
      {/* Header */}
      <header className="bg-white shadow-sm">
        <div className="max-w-6xl mx-auto px-4 py-4">
          <div className="flex items-center justify-between">
            <div className="flex items-center space-x-3 rtl:space-x-reverse">
              <div className="w-10 h-10 bg-gold-500 rounded-lg flex items-center justify-center">
                <User className="w-6 h-6 text-white" />
              </div>
              <div>
                <h1 className="text-xl font-bold text-gold-800">{fullName}</h1>
                <p className="text-sm text-gray-600">عميل خدمة أب</p>
              </div>
            </div>
            <Link 
              href="/ar"
              className="text-gray-600 hover:text-gold-600 transition-colors"
            >
              منصة خدمة أب
            </Link>
          </div>
        </div>
      </header>

      {/* Hero Section */}
      <section className="bg-gradient-to-r from-gold-500 to-gold-700 text-white py-16">
        <div className="max-w-6xl mx-auto px-4">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
              <div className="flex items-center mb-4">
                <div className="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center ml-6">
                  <User className="w-12 h-12 text-white" />
                </div>
                <div>
                  <h1 className="text-4xl font-bold mb-2">{fullName}</h1>
                  <p className="text-xl text-gold-100">عميل موثق</p>
                </div>
              </div>
              
              <div className="flex items-center space-x-6 rtl:space-x-reverse mb-6">
                <div className="flex items-center">
                  <MapPin className="w-5 h-5 text-gold-200 ml-2" />
                  <span className="text-gold-100">{client.city} {client.district && `- ${client.district}`}</span>
                </div>
                <div className="flex items-center">
                  <CheckCircle className="w-5 h-5 text-green-400 ml-1" />
                  <span className="text-gold-100">عميل موثق</span>
                </div>
              </div>

              <div className="flex flex-col sm:flex-row gap-4 mt-8">
                <a 
                  href={`tel:${client.phone}`}
                  className="bg-white text-gold-700 px-8 py-4 rounded-xl font-bold hover:bg-gold-50 transition-colors flex items-center justify-center"
                >
                  <Phone className="w-5 h-5 ml-2" />
                  اتصل الآن
                </a>
                <a 
                  href={`https://wa.me/${client.phone.replace(/[^0-9]/g, '')}`}
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
                      <p className="text-gold-100 text-sm">رقم الجوال</p>
                      <p className="text-white font-bold text-lg">{client.phone}</p>
                    </div>
                  </div>
                  
                  <div className="flex items-center">
                    <div className="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center ml-4">
                      <MapPin className="w-6 h-6 text-white" />
                    </div>
                    <div>
                      <p className="text-gold-100 text-sm">الموقع</p>
                      <p className="text-white font-bold">{client.city} {client.district && `- ${client.district}`}</p>
                    </div>
                  </div>
                  
                  {client.email && (
                    <div className="flex items-center">
                      <div className="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center ml-4">
                        <Mail className="w-6 h-6 text-white" />
                      </div>
                      <div>
                        <p className="text-gold-100 text-sm">البريد الإلكتروني</p>
                        <p className="text-white font-bold">{client.email}</p>
                      </div>
                    </div>
                  )}
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* About Section */}
      <section className="py-16 bg-white">
        <div className="max-w-6xl mx-auto px-4">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-gold-800 mb-4">عميل موثق في خدمة أب</h2>
            <p className="text-gray-600 text-lg">عضو في منصة خدمة أب لطلب الخدمات المنزلية والمهنية</p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div className="bg-gold-50 rounded-xl p-6 text-center">
              <div className="w-16 h-16 bg-gold-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                <CheckCircle className="w-8 h-8 text-gold-600" />
              </div>
              <h3 className="text-xl font-bold text-gold-800 mb-2">عميل موثق</h3>
              <p className="text-gray-600">تم التحقق من الهوية والمعلومات الشخصية</p>
            </div>

            <div className="bg-gold-50 rounded-xl p-6 text-center">
              <div className="w-16 h-16 bg-gold-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                <ShoppingBag className="w-8 h-8 text-gold-600" />
              </div>
              <h3 className="text-xl font-bold text-gold-800 mb-2">عضو نشط</h3>
              <p className="text-gray-600">يستخدم المنصة بانتظام لطلب الخدمات</p>
            </div>

            <div className="bg-gold-50 rounded-xl p-6 text-center">
              <div className="w-16 h-16 bg-gold-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                <Star className="w-8 h-8 text-gold-600" />
              </div>
              <h3 className="text-xl font-bold text-gold-800 mb-2">تقييمات إيجابية</h3>
              <p className="text-gray-600">حاصل على تقييمات ممتازة من مقدمي الخدمات</p>
            </div>
          </div>
        </div>
      </section>

      {/* Services Section */}
      <section className="py-16 bg-gray-50">
        <div className="max-w-6xl mx-auto px-4">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-gold-800 mb-4">الخدمات المطلوبة</h2>
            <p className="text-gray-600 text-lg">الخدمات التي يبحث عنها {fullName} عادة</p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div className="bg-white rounded-xl p-6 border border-gold-200 hover:shadow-lg transition-shadow">
              <div className="text-center">
                <div className="w-12 h-12 bg-gold-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                  🏠
                </div>
                <h4 className="font-bold text-gray-800">خدمات منزلية</h4>
              </div>
            </div>
            
            <div className="bg-white rounded-xl p-6 border border-gold-200 hover:shadow-lg transition-shadow">
              <div className="text-center">
                <div className="w-12 h-12 bg-gold-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                  🔧
                </div>
                <h4 className="font-bold text-gray-800">صيانة وإصلاح</h4>
              </div>
            </div>
            
            <div className="bg-white rounded-xl p-6 border border-gold-200 hover:shadow-lg transition-shadow">
              <div className="text-center">
                <div className="w-12 h-12 bg-gold-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                  🚛
                </div>
                <h4 className="font-bold text-gray-800">نقل وتوصيل</h4>
              </div>
            </div>
            
            <div className="bg-white rounded-xl p-6 border border-gold-200 hover:shadow-lg transition-shadow">
              <div className="text-center">
                <div className="w-12 h-12 bg-gold-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                  ⚡
                </div>
                <h4 className="font-bold text-gray-800">خدمات سريعة</h4>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-16 bg-gold-800 text-white">
        <div className="max-w-4xl mx-auto px-4 text-center">
          <h2 className="text-3xl font-bold mb-4">هل أنت مقدم خدمة؟</h2>
          <p className="text-xl text-gold-300 mb-8">تواصل مع {fullName} لتقديم خدماتك المهنية</p>
          
          <div className="flex flex-col sm:flex-row gap-4 justify-center max-w-md mx-auto">
            <a 
              href={`tel:${client.phone}`}
              className="bg-white text-gold-700 px-8 py-4 rounded-xl font-bold hover:bg-gold-50 transition-colors flex items-center justify-center"
            >
              <Phone className="w-5 h-5 ml-2" />
              اتصل الآن
            </a>
            <a 
              href={`https://wa.me/${client.phone.replace(/[^0-9]/g, '')}`}
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
              <p className="text-gray-400">عميل موثق في منصة خدمة أب</p>
              <p className="text-gray-400">{client.city} {client.district && `- ${client.district}`}</p>
            </div>
            
            <div>
              <h4 className="font-bold mb-4">معلومات الاتصال</h4>
              <div className="space-y-2 text-gray-400">
                <p>📱 {client.phone}</p>
                <p>📍 {client.city} {client.district && `- ${client.district}`}</p>
                {client.email && <p>✉️ {client.email}</p>}
              </div>
            </div>
            
            <div>
              <h4 className="font-bold mb-4">منصة خدمة أب</h4>
              <div className="space-y-1 text-gray-400">
                <p>• خدمات منزلية</p>
                <p>• مقدمي خدمات موثقين</p>
                <p>• أسعار تنافسية</p>
                <p>• خدمة عملاء 24/7</p>
              </div>
            </div>
          </div>
          
          <div className="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>جميع الحقوق محفوظة © {new Date().getFullYear()} - تم إنشاء هذا الموقع عبر منصة خدمة أب</p>
            <Link href="/ar" className="text-gold-400 hover:text-gold-300 mt-2 inline-block">
              khidmaapp.com
            </Link>
          </div>
        </div>
      </footer>
    </div>
  )
}
