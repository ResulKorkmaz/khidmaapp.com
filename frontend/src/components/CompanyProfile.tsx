'use client'

import { useState, useEffect, useMemo } from 'react'
import { 
  Building2, Camera, MapPin, Briefcase, Edit, Save, ArrowLeft, Bell, MessageCircle, 
  Star, Settings, Globe, Award, Clock, Shield, Eye, EyeOff, X, CheckCircle, 
  AlertCircle, Crown, Sparkles, Phone, Mail, Users, Calendar, FileText,
  Zap, TrendingUp
} from 'lucide-react'
import Link from 'next/link'

interface CompanyProfileData {
  companyName: string
  authorizedPersonName: string
  authorizedPersonSurname: string
  companyPhone: string
  companyEmail: string
  serviceCities: string[]
  services: string[]
  companyDocument?: File | null
  companyLogo?: File | null
  taxNumber?: string
  tradeRegisterNumber?: string
  address: string
  establishedYear: number
  employeeCount: string
  about: string
  experience: string
  website?: string
  socialMedia?: {
    linkedin?: string
    twitter?: string
    instagram?: string
  }
}

interface VerificationData {
  status: 'none' | 'pending' | 'approved' | 'rejected' | 'expired'
  isActive: boolean
  isTrial: boolean
  expiresAt?: string
  trialExpiresAt?: string
  daysUntilExpiry: number
  verifiedAt?: string
}

interface Message {
  id: string
  senderName: string
  message: string
  timestamp: string
  isRead: boolean
}

interface Ad {
  id: string
  title: string
  description: string
  budget: string
  location: string
  timestamp: string
  category: string
}

export default function CompanyProfile() {
  const [isLoading, setIsLoading] = useState(true)
  const [activeTab, setActiveTab] = useState('relevant-ads')
  const [isEditing, setIsEditing] = useState(false)
  const [showWebsite, setShowWebsite] = useState(false)
  const [websiteUrl, setWebsiteUrl] = useState('')

  // Company Profile Data
  const [profileData, setProfileData] = useState<CompanyProfileData>({
    companyName: '',
    authorizedPersonName: '',
    authorizedPersonSurname: '',
    companyPhone: '',
    companyEmail: '',
    serviceCities: [],
    services: [],
    taxNumber: '',
    tradeRegisterNumber: '',
    address: '',
    establishedYear: new Date().getFullYear(),
    employeeCount: '1-10',
    about: '',
    experience: '',
    website: '',
    socialMedia: {}
  })

  // Verification Data
  const [verificationData, setVerificationData] = useState<VerificationData>({
    status: 'none',
    isActive: false,
    isTrial: false,
    daysUntilExpiry: 0
  })

  const [messages] = useState<Message[]>([
    {
      id: '1',
      senderName: 'أحمد محمد',
      message: 'مرحباً، أحتاج خدمة تنظيف مكاتب',
      timestamp: '2024-01-15T10:30:00Z',
      isRead: false
    },
    {
      id: '2', 
      senderName: 'فاطمة أحمد',
      message: 'هل يمكنكم تقديم خدمة صيانة المكيفات؟',
      timestamp: '2024-01-15T09:15:00Z',
      isRead: false
    }
  ])

  // Mock data for relevant ads
  const relatedAds = useMemo(() => {
    return [
      {
        id: '1',
        title: 'تنظيف مكاتب شركة',
        description: 'مطلوب خدمة تنظيف دورية لمكاتب الشركة',
        budget: '2000 ريال',
        location: 'الرياض - حي العليا',
        timestamp: '2024-01-15T12:00:00Z',
        category: 'تنظيف'
      },
      {
        id: '2',
        title: 'صيانة أجهزة الكمبيوتر',
        description: 'صيانة وإصلاح أجهزة الكمبيوتر في الشركة',
        budget: '1500 ريال',
        location: 'الرياض - حي الملز',
        timestamp: '2024-01-15T10:30:00Z',
        category: 'صيانة تقنية'
      }
    ]
  }, [profileData.services])

  useEffect(() => {
    // KATI KURAL: Role-based routing control
    // Bu sayfa SADECE şirket kullanıcıları için!
    
    const savedAuthUser = localStorage.getItem('authUser')
    
    if (savedAuthUser) {
      const authUser = JSON.parse(savedAuthUser)
      const userRole = authUser.role
      
      console.log('🔒 Business Profile Security Check - User Role:', userRole)
      
      // Kullanıcıları doğru profile sayfasına yönlendir
      if (userRole === 'individual_provider') {
        console.log('🚨 SECURITY: Individual provider accessing business profile - REDIRECTING')
        window.location.href = '/ar/dashboard/provider/profile'
        return
      }
      
      if (userRole === 'customer') {
        console.log('🚨 SECURITY: Customer accessing business profile - REDIRECTING')
        window.location.href = '/ar/dashboard/client/profile'
        return
      }
      
      // Sadece company_provider'lar bu sayfayı görebilir
      if (userRole !== 'company_provider') {
        console.log('🚨 SECURITY: Unauthorized role accessing business profile - REDIRECTING TO LOGIN')
        window.location.href = '/ar/login'
        return
      }
      
      console.log('✅ SECURITY: Company user authorized for business profile')
    } else {
      // Authentication yoksa login'e yönlendir
      console.log('🚨 SECURITY: No authentication found - REDIRECTING TO LOGIN')
      window.location.href = '/ar/login'
      return
    }

    loadProfileData()
    loadVerificationData()
    
    // Loading complete
    setIsLoading(false)
  }, [])

  const loadProfileData = () => {
    const savedData = localStorage.getItem('companyRegistrationData')
    if (savedData) {
      const parsedData = JSON.parse(savedData)
      setProfileData(prev => ({
        ...prev,
        companyName: parsedData.companyName || '',
        authorizedPersonName: parsedData.authorizedPersonName || '',
        authorizedPersonSurname: parsedData.authorizedPersonSurname || '',
        companyPhone: parsedData.companyPhone || '',
        companyEmail: parsedData.companyEmail || '',
        serviceCities: parsedData.serviceCities || [],
        services: parsedData.services || [],
        registrationDate: parsedData.registrationDate
      }))
    }

    // Check if website URL exists
    const savedUrl = localStorage.getItem(`companyWebsiteUrl_${profileData.companyName}`)
    if (savedUrl) {
      setWebsiteUrl(savedUrl)
      setShowWebsite(true)
    }
  }

  const loadVerificationData = async () => {
    try {
      // TODO: Load from API
      // const response = await apiService.getVerificationStatus()
      // setVerificationData(response.data)
      
      // Mock data for now
      setVerificationData({
        status: 'approved',
        isActive: true,
        isTrial: true,
        daysUntilExpiry: 45,
        trialExpiresAt: '2024-07-15',
        verifiedAt: '2024-01-15'
      })
    } catch (error) {
      console.error('Failed to load verification data:', error)
    }
  }

  const handleCreateWebsite = () => {
    if (!profileData.companyName || !profileData.companyPhone) {
      alert('يرجى ملء بيانات الشركة أولاً')
      return
    }

    // Generate website URL using company data
    const companySlug = profileData.companyName
      .replace(/\s+/g, '-')
      .replace(/[^\w\u0600-\u06FF-]/g, '')
      .toLowerCase()

    const citySlug = profileData.serviceCities[0]?.replace(/\s+/g, '-').toLowerCase() || 'saudi'
    const serviceSlug = profileData.services[0]?.replace(/\s+/g, '-').toLowerCase() || 'services'
    
    const generatedUrl = `/${citySlug}/${serviceSlug}/${companySlug}-${profileData.companyPhone.slice(-4)}`
    
    setWebsiteUrl(generatedUrl)
    localStorage.setItem(`companyWebsiteUrl_${profileData.companyName}`, generatedUrl)
    setShowWebsite(true)
    
    alert('تم إنشاء موقع الشركة بنجاح!')
  }

  const handleShowWebsite = () => {
    if (websiteUrl) {
      window.open(websiteUrl, '_blank')
    }
  }

  const handleSaveProfile = () => {
    localStorage.setItem('companyRegistrationData', JSON.stringify({
      ...profileData,
      lastUpdated: new Date().toISOString()
    }))
    setIsEditing(false)
    alert('تم حفظ بيانات الشركة بنجاح!')
  }

  const getVerificationBadge = () => {
    if (verificationData.status === 'approved' && verificationData.isActive) {
      return (
        <div className="flex items-center gap-2 bg-gradient-to-r from-blue-50 to-green-50 px-3 py-1 rounded-full border border-blue-200">
          <CheckCircle className="w-4 h-4 text-blue-600" />
          <span className="text-sm font-medium text-blue-800">
            {verificationData.isTrial ? 'تجربة مجانية' : 'محقق'}
          </span>
          {verificationData.isTrial && (
            <span className="text-xs text-blue-600">
              ({verificationData.daysUntilExpiry} يوم متبقي)
            </span>
          )}
        </div>
      )
    } else if (verificationData.status === 'pending') {
      return (
        <div className="flex items-center gap-2 bg-yellow-50 px-3 py-1 rounded-full border border-yellow-200">
          <Clock className="w-4 h-4 text-yellow-600" />
          <span className="text-sm font-medium text-yellow-800">قيد المراجعة</span>
        </div>
      )
    } else if (verificationData.status === 'expired') {
      return (
        <div className="flex items-center gap-2 bg-red-50 px-3 py-1 rounded-full border border-red-200">
          <AlertCircle className="w-4 h-4 text-red-600" />
          <span className="text-sm font-medium text-red-800">منتهي الصلاحية</span>
        </div>
      )
    }
    return null
  }

  const getVerificationUpgradeCard = () => {
    if (verificationData.status === 'none') {
      return (
        <div className="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 rounded-2xl shadow-lg">
          <div className="flex items-center gap-3 mb-4">
            <Crown className="w-6 h-6 text-yellow-300" />
            <h3 className="text-xl font-bold">احصل على التوثيق المميز</h3>
          </div>
          <p className="mb-4 opacity-90">
            وثق ملف شركتك واحصل على مزايا حصرية تساعدك في الحصول على المزيد من العملاء
          </p>
          <div className="flex items-center gap-4 mb-4">
            <div className="flex items-center gap-2">
              <Sparkles className="w-4 h-4 text-yellow-300" />
              <span className="text-sm">6 أشهر مجاناً</span>
            </div>
            <div className="flex items-center gap-2">
              <TrendingUp className="w-4 h-4 text-green-300" />
              <span className="text-sm">أولوية في النتائج</span>
            </div>
          </div>
          <button className="bg-white text-blue-600 px-6 py-2 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
            ابدأ التجربة المجانية
          </button>
        </div>
      )
    } else if (verificationData.isTrial && verificationData.daysUntilExpiry <= 30) {
      return (
        <div className="bg-gradient-to-r from-orange-500 to-red-500 text-white p-6 rounded-2xl shadow-lg">
          <div className="flex items-center gap-3 mb-4">
            <Zap className="w-6 h-6 text-yellow-300" />
            <h3 className="text-xl font-bold">قم بالترقية الآن</h3>
          </div>
          <p className="mb-4 opacity-90">
            التجربة المجانية تنتهي خلال {verificationData.daysUntilExpiry} يوم. قم بالترقية للحفاظ على مزاياك
          </p>
          <div className="text-sm mb-4 opacity-90">
            $50/سنة - فقط $4.17/شهر
          </div>
          <button className="bg-white text-orange-600 px-6 py-2 rounded-lg font-semibold hover:bg-orange-50 transition-colors">
            ترقية الآن - $50/سنة
          </button>
        </div>
      )
    }
    return null
  }

  // Show loading while checking authentication and role
  if (isLoading) {
    return (
      <div className="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 flex items-center justify-center" dir="rtl">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto mb-4"></div>
          <p className="text-gray-600">جاري تحميل الملف الشخصي للشركة...</p>
        </div>
      </div>
    )
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 pt-32 pb-12" dir="rtl">
      <div className="max-w-7xl mx-auto px-4 py-8">
        {/* Header */}
        <div className="flex items-center justify-between mb-8">
          <Link href="/ar" className="flex items-center gap-2 text-navy-700 hover:text-primary-600 transition-colors">
            <ArrowLeft className="w-5 h-5" />
            <span>العودة للرئيسية</span>
          </Link>
          
          <div className="flex items-center gap-4">
            <div className="relative">
              <Bell className="w-6 h-6 text-gray-600 cursor-pointer hover:text-primary-600 transition-colors" />
              {messages.filter(m => !m.isRead).length > 0 && (
                <span className="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                  {messages.filter(m => !m.isRead).length}
                </span>
              )}
            </div>
            <Settings className="w-6 h-6 text-gray-600 cursor-pointer hover:text-primary-600 transition-colors" />
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Left Sidebar - Company Info */}
          <div className="lg:col-span-1 space-y-6">
            {/* Company Profile Card */}
            <div className="bg-white rounded-2xl shadow-xl p-6">
              {/* Company Avatar */}
              <div className="flex flex-col items-center mb-6">
                <div className="relative">
                  <div className="w-24 h-24 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <Building2 className="w-12 h-12 text-white" />
                  </div>
                  <button className="absolute -bottom-2 -right-2 bg-white p-2 rounded-full shadow-lg border hover:bg-gray-50 transition-colors">
                    <Camera className="w-4 h-4 text-gray-600" />
                  </button>
                </div>
                
                {/* Company Name & Verification */}
                <div className="text-center">
                  <h1 className="text-xl font-bold text-gray-800 mb-2">
                    {profileData.companyName || 'اسم الشركة'}
                  </h1>
                  {getVerificationBadge()}
                </div>
              </div>

              {/* Quick Stats */}
              <div className="grid grid-cols-2 gap-4 mb-6">
                <div className="text-center p-3 bg-blue-50 rounded-lg">
                  <div className="text-2xl font-bold text-blue-600">4.8</div>
                  <div className="text-sm text-gray-600">التقييم</div>
                </div>
                <div className="text-center p-3 bg-green-50 rounded-lg">
                  <div className="text-2xl font-bold text-green-600">127</div>
                  <div className="text-sm text-gray-600">المشاريع</div>
                </div>
              </div>

              {/* Company Details */}
              <div className="space-y-3 text-sm">
                <div className="flex items-center gap-3 text-gray-600">
                  <Users className="w-4 h-4" />
                  <span>{profileData.employeeCount || '1-10'} موظف</span>
                </div>
                <div className="flex items-center gap-3 text-gray-600">
                  <Calendar className="w-4 h-4" />
                  <span>تأسست عام {profileData.establishedYear || '2020'}</span>
                </div>
                <div className="flex items-center gap-3 text-gray-600">
                  <MapPin className="w-4 h-4" />
                  <span>{profileData.serviceCities?.[0] || 'الرياض'}</span>
                </div>
                <div className="flex items-center gap-3 text-gray-600">
                  <Phone className="w-4 h-4" />
                  <span>{profileData.companyPhone || 'غير محدد'}</span>
                </div>
                <div className="flex items-center gap-3 text-gray-600">
                  <Mail className="w-4 h-4" />
                  <span>{profileData.companyEmail || 'غير محدد'}</span>
                </div>
              </div>

              {/* Website Button */}
              <div className="mt-6 pt-4 border-t">
                {showWebsite ? (
                  <button
                    onClick={handleShowWebsite}
                    className="w-full bg-gradient-to-r from-green-600 to-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-green-700 hover:to-blue-700 transition-all duration-300 flex items-center justify-center gap-2"
                  >
                    <Globe className="w-5 h-5" />
                    عرض موقع الشركة
                  </button>
                ) : (
                  <button
                    onClick={handleCreateWebsite}
                    className="w-full bg-gradient-to-r from-primary-600 to-gold-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-primary-700 hover:to-gold-700 transition-all duration-300 flex items-center justify-center gap-2"
                  >
                    <Globe className="w-5 h-5" />
                    إنشاء موقع الشركة
                  </button>
                )}
              </div>
            </div>

            {/* Verification Upgrade Card */}
            {getVerificationUpgradeCard() && (
              <div>{getVerificationUpgradeCard()}</div>
            )}

            {/* Quick Actions */}
            <div className="bg-white rounded-2xl shadow-xl p-6">
              <h3 className="font-bold text-gray-800 mb-4">إجراءات سريعة</h3>
              <div className="space-y-3">
                <button className="w-full bg-blue-50 text-blue-700 py-3 px-4 rounded-lg font-medium hover:bg-blue-100 transition-colors text-right">
                  عرض العروض المرسلة
                </button>
                <button className="w-full bg-green-50 text-green-700 py-3 px-4 rounded-lg font-medium hover:bg-green-100 transition-colors text-right">
                  إدارة الخدمات
                </button>
                <button className="w-full bg-purple-50 text-purple-700 py-3 px-4 rounded-lg font-medium hover:bg-purple-100 transition-colors text-right">
                  تقارير الأداء
                </button>
              </div>
            </div>
          </div>

          {/* Main Content */}
          <div className="lg:col-span-2">
            {/* Navigation Tabs */}
            <div className="bg-white rounded-2xl shadow-xl p-2 mb-6">
              <div className="flex gap-2">
                {[
                  { id: 'relevant-ads', label: 'الإعلانات المناسبة', icon: Briefcase },
                  { id: 'profile', label: 'بيانات الشركة', icon: Building2 },
                  { id: 'messages', label: 'الرسائل', icon: MessageCircle, count: messages.filter(m => !m.isRead).length },
                ].map((tab) => (
                  <button
                    key={tab.id}
                    onClick={() => setActiveTab(tab.id)}
                    className={`flex items-center gap-2 px-6 py-3 rounded-xl font-medium transition-all duration-300 relative ${
                      activeTab === tab.id
                        ? 'bg-gradient-to-r from-primary-600 to-blue-600 text-white shadow-lg'
                        : 'text-gray-600 hover:bg-gray-50'
                    }`}
                  >
                    <tab.icon className="w-5 h-5" />
                    <span>{tab.label}</span>
                    {tab.count > 0 && (
                      <span className="bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                        {tab.count}
                      </span>
                    )}
                  </button>
                ))}
              </div>
            </div>

            {/* Content Area */}
            <div className="space-y-6">
              {/* Relevant Ads Tab */}
              {activeTab === 'relevant-ads' && (
                <div className="space-y-4">
                  <div className="bg-white rounded-2xl shadow-xl p-6">
                    <h2 className="text-xl font-bold text-gray-800 mb-6">الإعلانات المناسبة لخدماتك</h2>
                    
                    {relatedAds.length > 0 ? (
                      <div className="space-y-4">
                        {relatedAds.map((ad) => (
                          <div key={ad.id} className="border border-gray-200 rounded-xl p-4 hover:shadow-lg transition-shadow">
                            <div className="flex justify-between items-start mb-3">
                              <h3 className="font-semibold text-gray-800">{ad.title}</h3>
                              <span className="text-sm text-gray-500">{ad.budget}</span>
                            </div>
                            <p className="text-gray-600 mb-3">{ad.description}</p>
                            <div className="flex items-center justify-between">
                              <div className="flex items-center gap-4 text-sm text-gray-500">
                                <div className="flex items-center gap-1">
                                  <MapPin className="w-4 h-4" />
                                  <span>{ad.location}</span>
                                </div>
                                <div className="flex items-center gap-1">
                                  <Clock className="w-4 h-4" />
                                  <span>منذ ساعتين</span>
                                </div>
                              </div>
                              <button className="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 transition-colors">
                                تقديم عرض
                              </button>
                            </div>
                          </div>
                        ))}
                      </div>
                    ) : (
                      <div className="text-center py-12">
                        <Briefcase className="w-16 h-16 text-gray-300 mx-auto mb-4" />
                        <h3 className="text-lg font-semibold text-gray-600 mb-2">لا توجد إعلانات مناسبة حالياً</h3>
                        <p className="text-gray-500">سيتم عرض الإعلانات المناسبة لخدماتك هنا</p>
                      </div>
                    )}
                  </div>
                </div>
              )}

              {/* Profile Tab */}
              {activeTab === 'profile' && (
                <div className="bg-white rounded-2xl shadow-xl p-6">
                  <div className="flex justify-between items-center mb-6">
                    <h2 className="text-xl font-bold text-gray-800">بيانات الشركة</h2>
                    <button
                      onClick={() => isEditing ? handleSaveProfile() : setIsEditing(true)}
                      className={`flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors ${
                        isEditing
                          ? 'bg-green-600 text-white hover:bg-green-700'
                          : 'bg-primary-600 text-white hover:bg-primary-700'
                      }`}
                    >
                      {isEditing ? <Save className="w-4 h-4" /> : <Edit className="w-4 h-4" />}
                      {isEditing ? 'حفظ التغييرات' : 'تعديل البيانات'}
                    </button>
                  </div>

                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {/* Company Name */}
                    <div>
                      <label className="block text-gray-700 font-semibold mb-2">اسم الشركة *</label>
                      {isEditing ? (
                        <input
                          type="text"
                          value={profileData.companyName}
                          onChange={(e) => setProfileData(prev => ({ ...prev, companyName: e.target.value }))}
                          className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary-500"
                          placeholder="أدخل اسم الشركة"
                        />
                      ) : (
                        <p className="text-gray-800 bg-gray-50 px-4 py-3 rounded-lg">
                          {profileData.companyName || 'غير محدد'}
                        </p>
                      )}
                    </div>

                    {/* Authorized Person */}
                    <div>
                      <label className="block text-gray-700 font-semibold mb-2">المخول بالتوقيع *</label>
                      {isEditing ? (
                        <input
                          type="text"
                          value={`${profileData.authorizedPersonName} ${profileData.authorizedPersonSurname}`.trim()}
                          onChange={(e) => {
                            const names = e.target.value.split(' ')
                            setProfileData(prev => ({
                              ...prev,
                              authorizedPersonName: names[0] || '',
                              authorizedPersonSurname: names.slice(1).join(' ')
                            }))
                          }}
                          className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary-500"
                          placeholder="أدخل اسم المخول بالتوقيع"
                        />
                      ) : (
                        <p className="text-gray-800 bg-gray-50 px-4 py-3 rounded-lg">
                          {`${profileData.authorizedPersonName || ''} ${profileData.authorizedPersonSurname || ''}`.trim() || 'غير محدد'}
                        </p>
                      )}
                    </div>

                    {/* Phone */}
                    <div>
                      <label className="block text-gray-700 font-semibold mb-2">رقم الهاتف *</label>
                      {isEditing ? (
                        <input
                          type="tel"
                          value={profileData.companyPhone}
                          onChange={(e) => {
                            // Only allow numbers and format for Saudi Arabia
                            const value = e.target.value.replace(/\D/g, '')
                            if (value.length <= 10) {
                              setProfileData(prev => ({ ...prev, companyPhone: value }))
                            }
                          }}
                          className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary-500"
                          placeholder="05xxxxxxxx"
                        />
                      ) : (
                        <p className="text-gray-800 bg-gray-50 px-4 py-3 rounded-lg">
                          {profileData.companyPhone || 'غير محدد'}
                        </p>
                      )}
                    </div>

                    {/* Email */}
                    <div>
                      <label className="block text-gray-700 font-semibold mb-2">البريد الإلكتروني *</label>
                      {isEditing ? (
                        <input
                          type="email"
                          value={profileData.companyEmail}
                          onChange={(e) => setProfileData(prev => ({ ...prev, companyEmail: e.target.value }))}
                          className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary-500"
                          placeholder="company@example.com"
                        />
                      ) : (
                        <p className="text-gray-800 bg-gray-50 px-4 py-3 rounded-lg">
                          {profileData.companyEmail || 'غير محدد'}
                        </p>
                      )}
                    </div>
                  </div>

                  {/* About Section */}
                  <div className="mt-8">
                    <h3 className="text-lg font-semibold text-gray-800 mb-4">عن الشركة</h3>
                    <div className="grid grid-cols-1 gap-6">
                      <div>
                        <label className="block text-gray-700 font-semibold mb-2">التخصصات والمهارات</label>
                        {isEditing ? (
                          <textarea
                            value={profileData.about}
                            onChange={(e) => setProfileData(prev => ({ ...prev, about: e.target.value }))}
                            rows={4}
                            className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary-500"
                            placeholder="اكتب عن تخصصات ومهارات شركتك..."
                          />
                        ) : (
                          <p className="text-gray-800 bg-gray-50 px-4 py-3 rounded-lg min-h-[100px]">
                            {profileData.about || 'لم يتم إضافة معلومات بعد'}
                          </p>
                        )}
                      </div>

                      <div>
                        <label className="block text-gray-700 font-semibold mb-2">الخبرة والإنجازات</label>
                        {isEditing ? (
                          <textarea
                            value={profileData.experience}
                            onChange={(e) => setProfileData(prev => ({ ...prev, experience: e.target.value }))}
                            rows={4}
                            className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary-500"
                            placeholder="اكتب عن خبرة شركتك وإنجازاتها..."
                          />
                        ) : (
                          <p className="text-gray-800 bg-gray-50 px-4 py-3 rounded-lg min-h-[100px]">
                            {profileData.experience || 'لم يتم إضافة معلومات بعد'}
                          </p>
                        )}
                      </div>
                    </div>
                  </div>
                </div>
              )}

              {/* Messages Tab */}
              {activeTab === 'messages' && (
                <div className="bg-white rounded-2xl shadow-xl p-6">
                  <h2 className="text-xl font-bold text-gray-800 mb-6">الرسائل الواردة</h2>
                  
                  {messages.length > 0 ? (
                    <div className="space-y-4">
                      {messages.map((message) => (
                        <div key={message.id} className={`border rounded-xl p-4 transition-colors ${
                          !message.isRead ? 'bg-blue-50 border-blue-200' : 'border-gray-200'
                        }`}>
                          <div className="flex justify-between items-center mb-2">
                            <h4 className="font-semibold text-gray-800">{message.senderName}</h4>
                            <span className="text-sm text-gray-500">منذ ساعتين</span>
                          </div>
                          <p className="text-gray-600">{message.message}</p>
                          <div className="flex justify-end mt-3">
                            <button className="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 transition-colors">
                              رد على الرسالة
                            </button>
                          </div>
                        </div>
                      ))}
                    </div>
                  ) : (
                    <div className="text-center py-12">
                      <MessageCircle className="w-16 h-16 text-gray-300 mx-auto mb-4" />
                      <h3 className="text-lg font-semibold text-gray-600 mb-2">لا توجد رسائل</h3>
                      <p className="text-gray-500">ستظهر الرسائل الواردة هنا</p>
                    </div>
                  )}
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}
