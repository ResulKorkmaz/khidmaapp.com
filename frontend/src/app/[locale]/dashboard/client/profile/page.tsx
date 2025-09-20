'use client'

import { useState, useEffect } from 'react'
import { User, Camera, MapPin, Edit, Save, ArrowLeft, MessageCircle, Star, Settings, Eye, EyeOff, ShoppingBag, Clock, CheckCircle, Globe } from 'lucide-react'
import Link from 'next/link'

interface ClientProfileData {
  firstName: string
  lastName: string
  phone: string
  email: string
  city: string
  district: string
  gender: string
}

interface Order {
  id: string
  serviceName: string
  providerName: string
  status: string
  date: string
  price: string
  description: string
}

export default function ClientProfilePage() {
  const [isLoading, setIsLoading] = useState(true)
  const [profileData, setProfileData] = useState<ClientProfileData>({
    firstName: '',
    lastName: '',
    phone: '',
    email: '',
    city: '',
    district: '',
    gender: ''
  })
  
  // Website Builder için gerekli state
  const [existingWebsiteSlug, setExistingWebsiteSlug] = useState<string | null>(null)
  
  const [activeTab, setActiveTab] = useState('orders')
  const [isEditing, setIsEditing] = useState(false)
  const [firstName, setFirstName] = useState('')
  const [lastName, setLastName] = useState('')
  const [phone, setPhone] = useState('')
  const [email, setEmail] = useState('')
  const [city, setCity] = useState('')
  const [district, setDistrict] = useState('')
  const [gender, setGender] = useState('')
  
  // Settings states
  const [currentPassword, setCurrentPassword] = useState('')
  const [newPassword, setNewPassword] = useState('')
  const [confirmPassword, setConfirmPassword] = useState('')
  const [secretWord, setSecretWord] = useState('')
  const [showPasswords, setShowPasswords] = useState(false)
  
  // Mock orders data
  const [orders] = useState<Order[]>([
    {
      id: '1',
      serviceName: 'تنظيف منزل',
      providerName: 'أحمد محمد',
      status: 'completed',
      date: '2024-01-15',
      price: '250 ريال',
      description: 'تنظيف شقة 3 غرف وصالة'
    },
    {
      id: '2',
      serviceName: 'إصلاح سباكة',
      providerName: 'محمد علي',
      status: 'in_progress',
      date: '2024-01-16',
      price: '180 ريال',
      description: 'إصلاح تسريب في المطبخ'
    },
    {
      id: '3',
      serviceName: 'نقل أثاث',
      providerName: 'فهد السعد',
      status: 'pending',
      date: '2024-01-18',
      price: '400 ريال',
      description: 'نقل أثاث من شقة إلى أخرى'
    }
  ])

  const cities = [
    'الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 'الخبر', 'الظهران',
    'تبوك', 'بريدة', 'خميس مشيط', 'أبها', 'نجران', 'الطائف', 'حائل', 'الجبيل',
    'ينبع', 'الاحساء', 'القطيف', 'عرعر', 'سكاكا', 'جازان', 'الباحة'
  ]

  const genderOptions = [
    { value: 'male', label: 'ذكر' },
    { value: 'female', label: 'أنثى' }
  ]

  const getStatusText = (status: string) => {
    switch (status) {
      case 'completed':
        return 'مكتمل'
      case 'in_progress':
        return 'جاري التنفيذ'
      case 'pending':
        return 'في الانتظار'
      default:
        return status
    }
  }

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'completed':
        return 'bg-green-100 text-green-700'
      case 'in_progress':
        return 'bg-blue-100 text-blue-700'
      case 'pending':
        return 'bg-yellow-100 text-yellow-700'
      default:
        return 'bg-gray-100 text-gray-700'
    }
  }

  // Load profile data on component mount
  useEffect(() => {
    const checkUserAndLoadProfile = async () => {
      // KATI KURAL: Role-based routing control
      // Bu sayfa SADECE müşteriler için!
    
    const savedAuthUser = localStorage.getItem('authUser')
    
    if (savedAuthUser) {
      const authUser = JSON.parse(savedAuthUser)
      const userRole = authUser.role
      
      console.log('🔒 Client Profile Security Check - User Role:', userRole)
      
      // Provider bu sayfaya erişmeye çalışıyorsa, provider-profile'a yönlendir
      if (userRole === 'individual_provider') {
        console.log('🚨 SECURITY: Provider accessing client profile - REDIRECTING')
        setIsLoading(false) // Loading'i durdur
        window.location.href = '/ar/dashboard/provider/profile'
        return
      }
      
      // Company bu sayfaya erişmeye çalışıyorsa, business-profile'a yönlendir
      if (userRole === 'company_provider') {
        console.log('🚨 SECURITY: Company accessing client profile - REDIRECTING')
        setIsLoading(false) // Loading'i durdur
        window.location.href = '/ar/dashboard/business/profile'
        return
      }
      
      // Sadece customer bu sayfayı görebilir
      if (userRole !== 'customer') {
        console.log('🚨 SECURITY: Unauthorized role accessing client profile - REDIRECTING TO LOGIN')
        setIsLoading(false) // Loading'i durdur
        window.location.href = '/ar/login'
        return
      }
      
      console.log('✅ SECURITY: Customer authorized for this profile')
    } else {
      // Authentication yoksa login'e yönlendir
      console.log('🚨 SECURITY: No authentication found - REDIRECTING TO LOGIN')
      setIsLoading(false) // Loading'i durdur
      window.location.href = '/ar/login'
      return
    }
    
    const savedProfileData = localStorage.getItem('profileData')
    // savedAuthUser already declared above for security check
    
    console.log('🔍 Client Profile: localStorage check - profileData exists:', !!savedProfileData)
    console.log('🔍 Client Profile: localStorage check - authUser exists:', !!savedAuthUser)
    if (savedProfileData) {
      console.log('🔍 Client Profile: savedProfileData content:', JSON.parse(savedProfileData))
    }
    
    let profileDataToUse: ClientProfileData
    
    // 🚀 SENIOR UZMAN ÇÖZÜMÜ: BACKEND-FIRST APPROACH
    console.log('🚀 Client Profile: BACKEND-FIRST data loading başlıyor...')
    
    try {
      // ÖNCE BACKEND'DEN VERİ ÇEK (Primary Source)
      console.log('📡 Client Profile: Backend\'den fresh data çekiliyor...')
      const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/auth/me`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
          'Content-Type': 'application/json',
        }
      })
      
      if (response.ok) {
        const backendResponse = await response.json()
        console.log('✅ Client Profile: Backend response SUCCESS:', backendResponse)
        
        if (backendResponse.data || backendResponse.user) {
          const user = backendResponse.data || backendResponse.user
          console.log('👤 Client Profile: Backend user data:', user)
          
          // Parse backend name into firstName/lastName  
          let firstName = user.first_name || user.firstName || ''
          let lastName = user.last_name || user.lastName || ''
          
          // If no separate names but full name exists, parse it
          if (!firstName && !lastName && user.name) {
            console.log('🔄 Client Profile: Parsing backend name:', user.name)
            const nameParts = user.name.trim().split(' ')
            firstName = nameParts[0] || ''
            lastName = nameParts.slice(1).join(' ') || ''
            console.log('✅ Client Profile: Parsed backend name:', { firstName, lastName })
          }
          
          // BACKEND DATA = PRIMARY SOURCE
          profileDataToUse = {
            firstName: firstName,
            lastName: lastName,
            phone: user.phone || '',
            email: user.email || '',  
            city: user.city || 'الرياض',
            district: user.district || '',
            gender: user.gender || ''
          }
          
          console.log('🎯 Client Profile: BACKEND data als primary source:', profileDataToUse)
          
          // Update localStorage cache with fresh backend data
          localStorage.setItem('profileData', JSON.stringify(profileDataToUse))
          
          // Update authUser with fresh data
          const updatedAuthUser = {
            id: user.id,
            name: user.name || `${firstName} ${lastName}`.trim(),
            first_name: firstName,
            last_name: lastName,
            email: user.email,
            phone: user.phone,
            role: user.role,
            city: user.city,
            locale: user.locale,
            is_verified: user.is_verified
          }
          localStorage.setItem('authUser', JSON.stringify(updatedAuthUser))
          console.log('✅ Client Profile: localStorage updated with backend data')
          
        } else {
          throw new Error('No user data in backend response')
        }
      } else {
        throw new Error(`Backend error: ${response.status}`)
      }
    } catch (backendError) {
      // FALLBACK: localStorage verileri kullan
      console.log('⚠️ Client Profile: Backend error, falling back to localStorage:', backendError)
      
      if (savedAuthUser) {
        console.log('📦 Client Profile: Using authUser fallback')
        const authUser = JSON.parse(savedAuthUser)
        
        let firstName = authUser.first_name || authUser.firstName || ''
        let lastName = authUser.last_name || authUser.lastName || ''
        
        if (!firstName && !lastName && authUser.name) {
          const nameParts = authUser.name.trim().split(' ')
          firstName = nameParts[0] || ''
          lastName = nameParts.slice(1).join(' ') || ''
        }
        
        profileDataToUse = {
          firstName: firstName,
          lastName: lastName,
          phone: authUser.phone || '',
          email: authUser.email || '',
          city: authUser.city || 'الرياض',
          district: authUser.district || '',
          gender: authUser.gender || ''
        }
        
        console.log('🎯 Client Profile: FALLBACK data from authUser:', profileDataToUse)
      } else if (savedProfileData) {
        console.log('📦 Client Profile: Using profileData fallback (last resort)')
        const data = JSON.parse(savedProfileData)
        profileDataToUse = {
          firstName: data.firstName || '',
          lastName: data.lastName || '',
          phone: data.phone || '',
          email: data.email || '',
          city: data.city || '',
          district: data.district || '',
          gender: data.gender || ''
        }
        console.log('🎯 Client Profile: LAST RESORT data from profileData:', profileDataToUse)
      } else {
        // Final fallback: empty data
        console.log('📦 Client Profile: No data available, using empty defaults')
        profileDataToUse = {
          firstName: '',
          lastName: '',
          phone: '',
          email: '',
          city: 'الرياض',
          district: '',
          gender: ''
        }
        console.log('🎯 Client Profile: EMPTY DEFAULTS:', profileDataToUse)
      }
    }
    
    setProfileData(profileDataToUse)
    setFirstName(profileDataToUse.firstName)
    setLastName(profileDataToUse.lastName)
    setPhone(profileDataToUse.phone)
    setEmail(profileDataToUse.email)
    setCity(profileDataToUse.city)
    setDistrict(profileDataToUse.district)
    setGender(profileDataToUse.gender)
    
    // Check if website already exists
    checkExistingWebsite(profileDataToUse)
    
    // Loading complete
    setIsLoading(false)
    }
    
    // Call the async function
    checkUserAndLoadProfile()
  }, [])

  const handleLogout = () => {
    if (confirm('هل تريد تسجيل الخروج؟')) {
      // Clear all localStorage data
      localStorage.removeItem('authUser')
      localStorage.removeItem('profileData')
      localStorage.removeItem('token')
      localStorage.removeItem('registrationData')
      
      // Clear any existing website data as well
      const keys = Object.keys(localStorage)
      keys.forEach(key => {
        if (key.startsWith('website-')) {
          localStorage.removeItem(key)
        }
      })
      
      console.log('🚪 User logged out - localStorage cleared')
      
      // Redirect to login
      window.location.href = '/ar/login'
    }
  }

  const handleSave = async () => {
    if (!firstName.trim() || !lastName.trim() || !phone.trim() || !email.trim()) {
      alert('جميع الحقول الأساسية مطلوبة')
      return
    }

    // Validate phone number format
    const phoneRegex = /^(05|009665)[0-9]{8}$/
    if (!phoneRegex.test(phone.trim().replace(/[\s\-]/g, ''))) {
      alert('رقم الجوال غير صحيح. يجب أن يبدأ بـ 05 ويتكون من 10 أرقام\nمثال: 0501234567')
      return
    }

    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!emailRegex.test(email.trim())) {
      alert('البريد الإلكتروني غير صحيح')
      return
    }

    const updatedData: ClientProfileData = {
      ...profileData,
      firstName: firstName.trim(),
      lastName: lastName.trim(),
      phone: phone.trim(),
      email: email.trim(),
      city: city.trim(),
      district: district.trim(),
      gender: gender
    }

    console.log('💾 Client Profile: Saving data to backend:', updatedData)
    
    try {
      // Save to backend
      const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/auth/profile`, {
        method: 'PUT',
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          name: `${updatedData.firstName} ${updatedData.lastName}`,
          first_name: updatedData.firstName,
          last_name: updatedData.lastName,
          phone: updatedData.phone,
          email: updatedData.email,
          city: updatedData.city,
          district: updatedData.district,
          gender: updatedData.gender
        })
      })

      if (response.ok) {
        const result = await response.json()
        console.log('✅ Client Profile: Backend save successful:', result)
        
        // Update localStorage with backend response
        const updatedAuthUser = JSON.parse(localStorage.getItem('authUser') || '{}')
        updatedAuthUser.first_name = updatedData.firstName
        updatedAuthUser.last_name = updatedData.lastName
        updatedAuthUser.name = `${updatedData.firstName} ${updatedData.lastName}`
        updatedAuthUser.phone = updatedData.phone
        updatedAuthUser.email = updatedData.email
        updatedAuthUser.city = updatedData.city
        updatedAuthUser.district = updatedData.district
        updatedAuthUser.gender = updatedData.gender
        localStorage.setItem('authUser', JSON.stringify(updatedAuthUser))
        
        console.log('✅ Client Profile: localStorage updated with backend data')
        
        setProfileData(updatedData)
        setIsEditing(false)
        localStorage.setItem('profileData', JSON.stringify(updatedData))
        
        // Check if website exists with updated data
        checkExistingWebsite(updatedData)
        
        alert('تم حفظ البيانات بنجاح في قاعدة البيانات!')
      } else {
        console.error('❌ Client Profile: Backend save failed:', response.status)
        throw new Error('Failed to save to backend')
      }
    } catch (error) {
      console.error('❌ Client Profile: Save error:', error)
      
      // Fallback to localStorage only
      console.log('📱 Client Profile: Fallback - saving to localStorage only')
      setProfileData(updatedData)
      setIsEditing(false)
      localStorage.setItem('profileData', JSON.stringify(updatedData))
      
      // Check if website exists with updated data
      checkExistingWebsite(updatedData)
      
      alert('تم حفظ البيانات محلياً. قد تحتاج لاتصال بالإنترنت لمزامنة قاعدة البيانات.')
    }
  }

  const handleCancel = () => {
    setFirstName(profileData.firstName)
    setLastName(profileData.lastName)
    setPhone(profileData.phone)
    setEmail(profileData.email)
    setCity(profileData.city)
    setDistrict(profileData.district)
    setGender(profileData.gender)
    setIsEditing(false)
  }

  const handlePasswordChange = () => {
    if (!currentPassword || !newPassword || !confirmPassword) {
      alert('جميع حقول كلمة المرور مطلوبة')
      return
    }
    
    if (newPassword !== confirmPassword) {
      alert('كلمات المرور الجديدة غير متطابقة')
      return
    }
    
    if (newPassword.length < 8) {
      alert('كلمة المرور يجب أن تكون 8 أحرف على الأقل')
      return
    }
    
    // Save password change (in real app, send to backend)
    localStorage.setItem('userPassword', newPassword)
    setCurrentPassword('')
    setNewPassword('')
    setConfirmPassword('')
    alert('تم تغيير كلمة المرور بنجاح!')
  }

  const handleSecretWordSave = () => {
    if (!secretWord.trim()) {
      alert('يجب إدخال الكلمة السرية')
      return
    }
    
    localStorage.setItem('secretWord', secretWord.trim())
    alert('تم حفظ الكلمة السرية بنجاح!')
  }

  // Check if website already exists
  const checkExistingWebsite = (data: ClientData) => {
    const city = data.city || 'الرياض'
    const name = `${data.firstName}-${data.lastName}`
    const phone = data.phone || '0500000000'
    
    const citySlug = city.replace(/\s+/g, '-')
    const nameSlug = name.replace(/\s+/g, '-')
    const phoneSlug = phone.replace(/[^0-9]/g, '')
    
    const websiteSlug = `عميل-${citySlug}-${nameSlug}-${phoneSlug}`
    
    // Check if website exists in localStorage
    const existingWebsite = localStorage.getItem(`website-${websiteSlug}`)
    if (existingWebsite) {
      setExistingWebsiteSlug(websiteSlug)
    }
  }

  // Website Builder Function - Client için özel
  const createWebsiteUrl = () => {
    // If website already exists, redirect to it
    if (existingWebsiteSlug) {
      const websiteUrl = `/ar/client/${existingWebsiteSlug}`
      window.open(websiteUrl, '_blank')
      return
    }

    // Client profili için website oluştur
    if (!profileData.firstName || !profileData.lastName) {
      alert('يجب ملء البيانات الشخصية أولاً')
      return
    }
    
    // Create Arabic URL slug from client profile data
    const city = profileData.city || 'الرياض'
    const name = `${profileData.firstName}-${profileData.lastName}`
    const phone = profileData.phone || '0500000000'
    
    // Convert Arabic to URL-friendly format
    const citySlug = city.replace(/\s+/g, '-')
    const nameSlug = name.replace(/\s+/g, '-')
    const phoneSlug = phone.replace(/[^0-9]/g, '')
    
    const websiteSlug = `عميل-${citySlug}-${nameSlug}-${phoneSlug}`
    
    // Save website data for the generated page
    const websiteData = {
      slug: websiteSlug,
      profileData: profileData,
      type: 'client', // Client type
      createdAt: new Date().toISOString()
    }
    
    // Save website data
    localStorage.setItem(`website-${websiteSlug}`, JSON.stringify(websiteData))
    
    // Update state to reflect website exists
    setExistingWebsiteSlug(websiteSlug)
    
    // Show confirmation and URL
    alert(`تم إنشاء موقعك الشخصي بنجاح! \n\nالرابط: khidmaapp.com/ar/client/${websiteSlug}`)
    
    // Redirect to the generated website (in new tab)
    const websiteUrl = `/ar/client/${websiteSlug}`
    window.open(websiteUrl, '_blank')
  }

  // Show loading while checking authentication and role
  if (isLoading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center" dir="rtl">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto mb-4"></div>
          <p className="text-gray-600">جاري التحميل...</p>
        </div>
      </div>
    )
  }

  // This is the Client Profile Page
  return (
    <div className="min-h-screen bg-gray-50 pt-32 pb-12" dir="rtl">
      {/* Header */}
      <div className="bg-white shadow-sm border-b">
        <div className="max-w-7xl mx-auto px-4 py-4">
          <div className="flex items-center justify-between">
            <div className="flex items-center space-x-4 rtl:space-x-reverse">
              <Link 
                href="/ar"
                className="flex items-center text-gray-600 hover:text-primary-600 transition-colors"
              >
                <ArrowLeft className="w-5 h-5 ml-2" />
                العودة للرئيسية
              </Link>
            </div>
            <div className="flex items-center space-x-4 rtl:space-x-reverse">
              <h1 className="text-2xl font-bold text-navy-800">ملف العميل الشخصي</h1>
            </div>
          </div>
        </div>
      </div>

      {/* Main Dashboard Layout */}
      <div className="max-w-7xl mx-auto px-4 py-8">
        <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
          
          {/* Sidebar Navigation */}
          <div className="lg:col-span-1">
            <div className="bg-white rounded-xl shadow-sm p-6 sticky top-8">
              {/* Profile Summary Card */}
              <div className="text-center mb-6 pb-6 border-b border-gray-200">
                <div className="relative w-20 h-20 mx-auto mb-4">
                  <div className="w-full h-full bg-gold-100 rounded-full flex items-center justify-center">
                    <User className="w-10 h-10 text-gold-600" />
                  </div>
                  <button className="absolute -bottom-1 -right-1 bg-gold-500 text-white p-1.5 rounded-full hover:bg-gold-600 transition-colors">
                    <Camera className="w-3 h-3" />
                  </button>
                </div>
                <h3 className="font-bold text-navy-800">
                  {profileData.firstName} {profileData.lastName}
                </h3>
                <div className="flex items-center justify-center text-gray-600 text-sm mt-1">
                  <MapPin className="w-3 h-3 ml-1" />
                  {profileData.city}
                </div>
                <span className="inline-block bg-gold-100 text-gold-700 px-3 py-1 rounded-full text-xs font-semibold mt-2">
                  عميل
                </span>
              </div>

              <nav className="space-y-2">
                <button
                  onClick={() => setActiveTab('profile')}
                  className={`w-full flex items-center px-4 py-3 text-right rounded-lg transition-colors ${
                    activeTab === 'profile' 
                      ? 'bg-gold-50 text-gold-700 border-r-4 border-gold-500' 
                      : 'text-gray-700 hover:bg-gray-50'
                  }`}
                >
                  <User className="w-5 h-5 ml-3" />
                  الملف الشخصي
                </button>
                
                <button
                  onClick={() => setActiveTab('orders')}
                  className={`w-full flex items-center px-4 py-3 text-right rounded-lg transition-colors ${
                    activeTab === 'orders' 
                      ? 'bg-gold-50 text-gold-700 border-r-4 border-gold-500' 
                      : 'text-gray-700 hover:bg-gray-50'
                  }`}
                >
                  <ShoppingBag className="w-5 h-5 ml-3" />
                  طلباتي
                </button>
                
                <button
                  onClick={() => setActiveTab('settings')}
                  className={`w-full flex items-center px-4 py-3 text-right rounded-lg transition-colors ${
                    activeTab === 'settings' 
                      ? 'bg-gold-50 text-gold-700 border-r-4 border-gold-500' 
                      : 'text-gray-700 hover:bg-gray-50'
                  }`}
                >
                  <Settings className="w-5 h-5 ml-3" />
                  الإعدادات
                </button>
                
                {/* Website Builder Button */}
                <div className="pt-4 mt-4 border-t border-gray-200">
                  <button 
                    onClick={createWebsiteUrl}
                    className={`w-full flex items-center justify-center px-4 py-3 text-white rounded-lg transition-all duration-300 bg-gradient-to-r from-gold-500 to-gold-600 hover:from-gold-600 hover:to-gold-700`}
                  >
                    <Globe className="w-5 h-5 ml-2" />
                    {existingWebsiteSlug ? 'زيارة موقعي الويب' : 'إنشاء موقع ويب'}
                  </button>
                </div>
              </nav>
            </div>
          </div>

          {/* Main Content */}
          <div className="lg:col-span-3">
            {/* Profile Tab */}
            {activeTab === 'profile' && (
              <div className="space-y-6">
                {/* Profile Header Card */}
                <div className="bg-white rounded-xl shadow-sm p-6">
                  <div className="flex items-start justify-between mb-6">
                    <div>
                      <h2 className="text-2xl font-bold text-navy-800 mb-2">الملف الشخصي</h2>
                      <p className="text-gray-600">معلوماتك الشخصية كعميل</p>
                    </div>
                    <button
                      onClick={() => setIsEditing(true)}
                      className="flex items-center bg-gold-500 text-white px-4 py-2 rounded-lg hover:bg-gold-600 transition-colors"
                    >
                      <Edit className="w-4 h-4 ml-2" />
                      تحرير الملف الشخصي
                    </button>
                  </div>

                  {!isEditing ? (
                    /* View Mode */
                    <div className="space-y-6">
                      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                          <label className="block text-gray-700 font-semibold mb-2">الاسم الأول</label>
                          <p className="text-gray-900 bg-gray-50 px-4 py-3 rounded-lg">{profileData.firstName}</p>
                        </div>
                        <div>
                          <label className="block text-gray-700 font-semibold mb-2">الاسم الأخير</label>
                          <p className="text-gray-900 bg-gray-50 px-4 py-3 rounded-lg">{profileData.lastName}</p>
                        </div>
                      </div>

                      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                          <label className="block text-gray-700 font-semibold mb-2">رقم الجوال</label>
                          <p className="text-gray-900 bg-gray-50 px-4 py-3 rounded-lg">{profileData.phone}</p>
                        </div>
                        <div>
                          <label className="block text-gray-700 font-semibold mb-2">البريد الإلكتروني</label>
                          <p className="text-gray-900 bg-gray-50 px-4 py-3 rounded-lg">{profileData.email}</p>
                        </div>
                      </div>

                      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                          <label className="block text-gray-700 font-semibold mb-2">المدينة</label>
                          <p className="text-gray-900 bg-gray-50 px-4 py-3 rounded-lg">{profileData.city}</p>
                        </div>
                        <div>
                          <label className="block text-gray-700 font-semibold mb-2">الحي</label>
                          <p className="text-gray-900 bg-gray-50 px-4 py-3 rounded-lg">{profileData.district}</p>
                        </div>
                      </div>

                      <div>
                        <label className="block text-gray-700 font-semibold mb-2">الجنس</label>
                        <p className="text-gray-900 bg-gray-50 px-4 py-3 rounded-lg">
                          {profileData.gender === 'male' ? 'ذكر' : profileData.gender === 'female' ? 'أنثى' : 'غير محدد'}
                        </p>
                      </div>
                    </div>
                  ) : (
                    /* Edit Mode */
                    <div className="space-y-6">
                      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                          <label className="block text-gray-700 font-semibold mb-2">الاسم الأول *</label>
                          <input
                            type="text"
                            value={firstName}
                            onChange={(e) => setFirstName(e.target.value)}
                            className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-transparent text-lg"
                            dir="rtl"
                          />
                        </div>
                        <div>
                          <label className="block text-gray-700 font-semibold mb-2">الاسم الأخير *</label>
                          <input
                            type="text"
                            value={lastName}
                            onChange={(e) => setLastName(e.target.value)}
                            className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-transparent text-lg"
                            dir="rtl"
                          />
                        </div>
                      </div>

                      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                          <label className="block text-gray-700 font-semibold mb-2">رقم الجوال *</label>
                          <input
                            type="tel"
                            value={phone}
                            onChange={(e) => {
                              const value = e.target.value.replace(/[^0-9+\-\s]/g, '')
                              if (value.length <= 15) {
                                setPhone(value)
                              }
                            }}
                            placeholder="05XXXXXXXX"
                            className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-transparent text-lg"
                            dir="ltr"
                          />
                          <p className="text-sm text-gray-500 mt-1">مثال: 0501234567</p>
                        </div>
                        <div>
                          <label className="block text-gray-700 font-semibold mb-2">البريد الإلكتروني *</label>
                          <input
                            type="email"
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-transparent text-lg"
                            dir="ltr"
                          />
                        </div>
                      </div>

                      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                          <label className="block text-gray-700 font-semibold mb-2">المدينة *</label>
                          <select
                            value={city}
                            onChange={(e) => setCity(e.target.value)}
                            className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-transparent text-lg"
                            dir="rtl"
                          >
                            <option value="">اختر المدينة</option>
                            {cities.map(cityName => (
                              <option key={cityName} value={cityName}>{cityName}</option>
                            ))}
                          </select>
                        </div>
                        <div>
                          <label className="block text-gray-700 font-semibold mb-2">الحي *</label>
                          <input
                            type="text"
                            value={district}
                            onChange={(e) => setDistrict(e.target.value)}
                            className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-transparent text-lg"
                            dir="rtl"
                          />
                        </div>
                      </div>

                      <div>
                        <label className="block text-gray-700 font-semibold mb-2">الجنس</label>
                        <div className="flex space-x-4 rtl:space-x-reverse">
                          {genderOptions.map(option => (
                            <label key={option.value} className="flex items-center">
                              <input
                                type="radio"
                                name="gender"
                                value={option.value}
                                checked={gender === option.value}
                                onChange={(e) => setGender(e.target.value)}
                                className="ml-2 text-gold-500 focus:ring-gold-500"
                              />
                              {option.label}
                            </label>
                          ))}
                        </div>
                      </div>

                      <div className="flex flex-col sm:flex-row gap-4 pt-6">
                        <button
                          onClick={handleCancel}
                          className="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                          إلغاء
                        </button>
                        <button
                          onClick={handleSave}
                          className="flex-1 flex items-center justify-center bg-gradient-to-r from-gold-500 to-gold-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-gold-600 hover:to-gold-700 transition-all duration-300"
                        >
                          <Save className="w-4 h-4 ml-2" />
                          حفظ التغييرات
                        </button>
                      </div>
                    </div>
                  )}
                </div>
              </div>
            )}

            {/* Orders Tab */}
            {activeTab === 'orders' && (
              <div className="bg-white rounded-xl shadow-sm p-6">
                <h2 className="text-2xl font-bold text-navy-800 mb-6">طلباتي</h2>
                
                <div className="space-y-4">
                  {orders.map(order => (
                    <div key={order.id} className="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                      <div className="flex items-start justify-between mb-3">
                        <div>
                          <h4 className="font-bold text-navy-800 mb-1">{order.serviceName}</h4>
                          <p className="text-gray-600 text-sm">مقدم الخدمة: {order.providerName}</p>
                          <p className="text-gray-600 text-sm">{order.description}</p>
                        </div>
                        <div className="text-right">
                          <span className={`inline-block px-3 py-1 rounded-full text-sm font-semibold ${getStatusColor(order.status)}`}>
                            {getStatusText(order.status)}
                          </span>
                          <div className="text-lg font-bold text-gray-900 mt-2">{order.price}</div>
                        </div>
                      </div>
                      
                      <div className="flex items-center justify-between text-sm text-gray-500">
                        <div className="flex items-center">
                          <Clock className="w-4 h-4 ml-1" />
                          {order.date}
                        </div>
                        <div className="flex space-x-2 rtl:space-x-reverse">
                          <button className="bg-gold-500 text-white px-4 py-2 rounded-lg hover:bg-gold-600 transition-colors text-sm">
                            تفاصيل
                          </button>
                          {order.status === 'completed' && (
                            <button className="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                              تقييم
                            </button>
                          )}
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            )}

            {/* Settings Tab */}
            {activeTab === 'settings' && (
              <div className="space-y-6">
                {/* Password Change */}
                <div className="bg-white rounded-xl shadow-sm p-6">
                  <h3 className="text-xl font-bold text-navy-800 mb-6">تغيير كلمة المرور</h3>
                  
                  <div className="space-y-6">
                    <div>
                      <label className="block text-gray-700 font-semibold mb-2">كلمة المرور الحالية</label>
                      <div className="relative">
                        <input
                          type={showPasswords ? "text" : "password"}
                          value={currentPassword}
                          onChange={(e) => setCurrentPassword(e.target.value)}
                          className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-transparent text-lg"
                          dir="ltr"
                        />
                        <button
                          type="button"
                          onClick={() => setShowPasswords(!showPasswords)}
                          className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                        >
                          {showPasswords ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
                        </button>
                      </div>
                    </div>
                    
                    <div>
                      <label className="block text-gray-700 font-semibold mb-2">كلمة المرور الجديدة</label>
                      <input
                        type={showPasswords ? "text" : "password"}
                        value={newPassword}
                        onChange={(e) => setNewPassword(e.target.value)}
                        className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-transparent text-lg"
                        dir="ltr"
                      />
                    </div>
                    
                    <div>
                      <label className="block text-gray-700 font-semibold mb-2">تأكيد كلمة المرور الجديدة</label>
                      <input
                        type={showPasswords ? "text" : "password"}
                        value={confirmPassword}
                        onChange={(e) => setConfirmPassword(e.target.value)}
                        className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-transparent text-lg"
                        dir="ltr"
                      />
                    </div>
                    
                    <button
                      onClick={handlePasswordChange}
                      className="bg-gold-500 text-white px-6 py-3 rounded-lg hover:bg-gold-600 transition-colors"
                    >
                      تغيير كلمة المرور
                    </button>
                  </div>
                </div>

                {/* Secret Word */}
                <div className="bg-white rounded-xl shadow-sm p-6">
                  <h3 className="text-xl font-bold text-navy-800 mb-6">الكلمة السرية</h3>
                  <p className="text-gray-600 mb-4">يمكنك استخدام هذه الكلمة لاستعادة كلمة المرور</p>
                  
                  <div className="space-y-4">
                    <div>
                      <label className="block text-gray-700 font-semibold mb-2">الكلمة السرية</label>
                      <input
                        type="text"
                        value={secretWord}
                        onChange={(e) => setSecretWord(e.target.value)}
                        placeholder="اختر كلمة سرية يسهل عليك تذكرها"
                        className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-transparent text-lg"
                        dir="rtl"
                      />
                    </div>
                    
                    <button
                      onClick={handleSecretWordSave}
                      className="bg-gold-500 text-white px-6 py-3 rounded-lg hover:bg-gold-600 transition-colors"
                    >
                      حفظ الكلمة السرية
                    </button>
                  </div>
                </div>

                {/* Account Settings */}
                <div className="bg-white rounded-xl shadow-sm p-6">
                  <h3 className="text-xl font-bold text-navy-800 mb-6">إعدادات الحساب</h3>
                  
                  <div className="space-y-4">
                    <div className="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                      <div>
                        <h4 className="font-semibold text-gray-800">إشعارات البريد الإلكتروني</h4>
                        <p className="text-sm text-gray-600">تلقي إشعارات عن حالة الطلبات</p>
                      </div>
                      <label className="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" defaultChecked className="sr-only peer" />
                        <div className="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gold-600"></div>
                      </label>
                    </div>
                    
                    <div className="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                      <div>
                        <h4 className="font-semibold text-gray-800">إشعارات الهاتف</h4>
                        <p className="text-sm text-gray-600">تلقي رسائل نصية عن العروض الجديدة</p>
                      </div>
                      <label className="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" defaultChecked className="sr-only peer" />
                        <div className="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gold-600"></div>
                      </label>
                    </div>
                    
                    {/* Logout Section */}
                    <div className="pt-6 mt-6 border-t border-red-200">
                      <div className="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                        <h4 className="font-semibold text-red-800 mb-2">منطقة الخطر</h4>
                        <p className="text-sm text-red-600 mb-4">
                          سيؤدي تسجيل الخروج إلى حذف جميع البيانات المحلية المحفوظة على هذا الجهاز
                        </p>
                        <button
                          onClick={handleLogout}
                          className="w-full bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition-colors font-medium"
                        >
                          تسجيل الخروج
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  )
}
