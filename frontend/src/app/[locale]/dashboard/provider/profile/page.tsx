'use client'

import { useState, useEffect, useMemo } from 'react'
import { User, Camera, MapPin, Briefcase, Edit, Save, ArrowLeft, Bell, MessageCircle, Star, Settings, Globe, Award, Clock, Shield, Eye, EyeOff, X } from 'lucide-react'
import Link from 'next/link'

interface ProfileData {
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

export default function ProviderProfilePage() {
  const [isLoading, setIsLoading] = useState(true)
  const [profileData, setProfileData] = useState<ProfileData>({
    firstName: '',
    lastName: '',
    phone: '',
    email: '',
    city: '',
    district: '',
    services: [],
    gender: '',
    about: '',
    skills: '',
    experience: ''
  })
  
  const [activeTab, setActiveTab] = useState('ads')
  const [isEditing, setIsEditing] = useState(false)
  const [firstName, setFirstName] = useState('')
  const [lastName, setLastName] = useState('')
  const [phone, setPhone] = useState('')
  const [email, setEmail] = useState('')
  const [city, setCity] = useState('')
  const [district, setDistrict] = useState('')
  const [gender, setGender] = useState('')
  const [about, setAbout] = useState('')
  const [skills, setSkills] = useState('')
  const [experience, setExperience] = useState('')
  
  // Settings states
  const [currentPassword, setCurrentPassword] = useState('')
  const [newPassword, setNewPassword] = useState('')
  const [confirmPassword, setConfirmPassword] = useState('')
  const [secretWord, setSecretWord] = useState('')
  const [showPasswords, setShowPasswords] = useState(false)
  const [existingWebsiteSlug, setExistingWebsiteSlug] = useState<string | null>(null)
  
  // Mock data for messages and ads
  const [messages] = useState<Message[]>([
    {
      id: '1',
      senderName: 'أحمد محمد',
      message: 'مرحباً، أحتاج خدمة تنظيف منزل في الرياض',
      timestamp: '2024-01-15T10:30:00Z',
      isRead: false
    },
    {
      id: '2',
      senderName: 'فاطمة علي',
      message: 'هل يمكنك القيام بخدمة السباكة في المطبخ؟',
      timestamp: '2024-01-14T15:20:00Z',
      isRead: false
    },
    {
      id: '3',
      senderName: 'سارة أحمد',
      message: 'أرغب في تنظيف شقة، كم التكلفة؟',
      timestamp: '2024-01-13T09:15:00Z',
      isRead: true
    }
  ])
  
  // All available ads pool
  const allAds: Ad[] = [
    {
      id: '1',
      title: 'تنظيف شقة في الرياض',
      description: 'أحتاج تنظيف شقة مكونة من 3 غرف وصالة ومطبخ',
      budget: '200-300 ريال',
      location: 'الرياض - الملك فهد',
      timestamp: '2024-01-15T08:00:00Z',
      category: 'تنظيف المنازل'
    },
    {
      id: '2',
      title: 'إصلاح سباكة في المطبخ',
      description: 'تسريب في أنابيب المطبخ يحتاج إصلاح فوري',
      budget: '150-250 ريال',
      location: 'الرياض - العليا',
      timestamp: '2024-01-14T16:30:00Z',
      category: 'خدمات السباكة'
    },
    {
      id: '3',
      title: 'تنظيف مكتب تجاري',
      description: 'تنظيف مكتب بمساحة 200 متر مربع',
      budget: '300-400 ريال',
      location: 'الرياض - الملك فهد',
      timestamp: '2024-01-14T11:00:00Z',
      category: 'تنظيف المنازل'
    },
    {
      id: '4',
      title: 'صيانة تكييف مركزي',
      description: 'يحتاج تنظيف وصيانة دورية للتكييف المركزي',
      budget: '400-600 ريال',
      location: 'جدة - الحمراء',
      timestamp: '2024-01-13T14:00:00Z',
      category: 'خدمات التكييف'
    },
    {
      id: '5',
      title: 'خدمات كهربائية منزلية',
      description: 'تركيب إضاءة جديدة وإصلاح مفاتيح الكهرباء',
      budget: '150-300 ريال',
      location: 'الرياض - النرجس',
      timestamp: '2024-01-13T10:00:00Z',
      category: 'خدمات الكهرباء'
    },
    {
      id: '6',
      title: 'نقل أثاث المنزل',
      description: 'نقل أثاث من شقة إلى أخرى داخل الرياض',
      budget: '500-800 ريال',
      location: 'الرياض - العليا',
      timestamp: '2024-01-12T16:00:00Z',
      category: 'نقل الأثاث'
    },
    {
      id: '7',
      title: 'تنظيف السجاد والمفروشات',
      description: 'تنظيف سجاد صالة كبيرة ومفروشات',
      budget: '200-350 ريال',
      location: 'مكة المكرمة - العزيزية',
      timestamp: '2024-01-12T12:00:00Z',
      category: 'تنظيف السجاد والموكيت'
    },
    {
      id: '8',
      title: 'صيانة أجهزة المطبخ',
      description: 'إصلاح غسالة الأطباق وميكروويف',
      budget: '200-400 ريال',
      location: 'الدمام - الشاطئ',
      timestamp: '2024-01-11T15:30:00Z',
      category: 'صيانة الأجهزة المنزلية'
    },
    {
      id: '9',
      title: 'دروس خصوصية في الرياضيات',
      description: 'دروس خصوصية لطالب ثانوي في الرياضيات',
      budget: '100-150 ريال/ساعة',
      location: 'الرياض - الملقا',
      timestamp: '2024-01-11T09:00:00Z',
      category: 'خدمات التعليم والدروس الخصوصية'
    },
    {
      id: '10',
      title: 'تصميم وتطوير موقع إلكتروني',
      description: 'تصميم موقع إلكتروني لشركة صغيرة',
      budget: '2000-4000 ريال',
      location: 'جدة - النسيم',
      timestamp: '2024-01-10T13:00:00Z',
      category: 'تصميم المواقع'
    }
  ]

  // Filter ads based on user's services (dynamically updated)
  const relatedAds = useMemo(() => {
    if (!profileData.services || profileData.services.length === 0) {
      return allAds.slice(0, 3) // Default: show first 3 ads
    }
    
    // Filter ads that match user's services
    const matchingAds = allAds.filter(ad => 
      profileData.services.some(service => ad.category === service)
    )
    
    // If no matching ads, show some random ads
    if (matchingAds.length === 0) {
      return allAds.slice(0, 3)
    }
    
    // Show matching ads first, then fill with others if needed
    const otherAds = allAds.filter(ad => 
      !profileData.services.some(service => ad.category === service)
    )
    
    return [...matchingAds, ...otherAds].slice(0, 6) // Show up to 6 ads
  }, [profileData.services])
  
  const unreadCount = messages.filter(m => !m.isRead).length

  const cities = [
    'الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 'الخبر', 'الظهران',
    'تبوك', 'بريدة', 'خميس مشيط', 'أبها', 'نجران', 'الطائف', 'حائل', 'الجبيل',
    'ينبع', 'الاحساء', 'القطيف', 'عرعر', 'سكاكا', 'جازان', 'الباحة'
  ]

  const genderOptions = [
    { value: 'male', label: 'ذكر' },
    { value: 'female', label: 'أنثى' }
  ]

  const availableServices = [
    'تنظيف المنازل',
    'تنظيف المكاتب والشركات', 
    'تنظيف السجاد والموكيت',
    'تنظيف الخزانات',
    'مكافحة الحشرات',
    'خدمات السباكة',
    'خدمات الكهرباء',
    'خدمات التكييف',
    'صيانة الأجهزة المنزلية',
    'صيانة السيارات',
    'تصليح الهواتف المحمولة',
    'نقل الأثاث',
    'تركيب الأثاث',
    'دهان ومقاولات',
    'تصميم الديكور',
    'تنسيق الحدائق',
    'خدمات الطبخ',
    'تنظيم المناسبات',
    'التصوير الفوتوغرافي',
    'خدمات التوصيل',
    'رعاية المسنين',
    'رعاية الأطفال',
    'خدمات التعليم والدروس الخصوصية',
    'ترجمة المستندات',
    'خدمات المحاسبة',
    'استشارات قانونية',
    'خدمات تقنية المعلومات',
    'تصميم المواقع',
    'التسويق الرقمي',
    'خدمات طبية',
    'علاج طبيعي',
    'جلسات تدليك',
    'حلاقة وتجميل',
    'تدريب رياضي',
    'تدريس السياقة',
    'خدمات أخرى'
  ]

  // Load profile data on component mount
  useEffect(() => {
    // KATI KURAL: Role-based routing control
    // Bu sayfa SADECE bireysel hizmet veren için!
    
    const savedAuthUser = localStorage.getItem('authUser')
    
    if (savedAuthUser) {
      const authUser = JSON.parse(savedAuthUser)
      const userRole = authUser.role
      
      console.log('🔒 Provider Profile Security Check - User Role:', userRole)
      
      // Şirket kullanıcısı bu sayfaya erişmeye çalışıyorsa, zorla business/profile'a yönlendir
      if (userRole === 'company_provider') {
        console.log('🚨 SECURITY: Company user accessing provider profile - REDIRECTING')
        setIsLoading(false) // Loading'i durdur
        window.location.href = '/ar/dashboard/business/profile'
        return
      }
      
      // Customer bu sayfaya erişmeye çalışıyorsa, client/profile'a yönlendir
      if (userRole === 'customer') {
        console.log('🚨 SECURITY: Customer accessing provider profile - REDIRECTING')
        setIsLoading(false) // Loading'i durdur
        window.location.href = '/ar/dashboard/client/profile'
        return
      }
      
      // Sadece individual_provider bu sayfayı görebilir
      if (userRole !== 'individual_provider') {
        console.log('🚨 SECURITY: Unauthorized role accessing provider profile - REDIRECTING TO LOGIN')
        setIsLoading(false) // Loading'i durdur
        window.location.href = '/ar/login'
        return
      }
      
      console.log('✅ SECURITY: Individual provider authorized for this profile')
    } else {
      // Authentication yoksa login'e yönlendir
      console.log('🚨 SECURITY: No authentication found - REDIRECTING TO LOGIN')
      setIsLoading(false) // Loading'i durdur
      window.location.href = '/ar/login'
      return
    }
    
    const savedProfileData = localStorage.getItem('profileData')
    
    let profileDataToUse: ProfileData
    
    if (savedProfileData) {
      profileDataToUse = JSON.parse(savedProfileData)
    } else {
      const savedRegistrationData = localStorage.getItem('registrationData')
      
      if (savedRegistrationData) {
        const registrationData = JSON.parse(savedRegistrationData)
        profileDataToUse = {
          firstName: registrationData.firstName || '',
          lastName: registrationData.lastName || '',
          phone: registrationData.phone || '',
          email: registrationData.email || '',
          city: registrationData.city || '',
          district: registrationData.district || '',
          services: registrationData.services || [],
          gender: '',
          about: '',
          skills: '',
          experience: ''
        }
      } else {
        profileDataToUse = {
          firstName: '',
          lastName: '',
          phone: '',
          email: '',
          city: '',
          district: '',
          services: [],
          gender: '',
          about: '',
          skills: '',
          experience: ''
        }
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
    setAbout(profileDataToUse.about)
    setSkills(profileDataToUse.skills)
    setExperience(profileDataToUse.experience)
    
    // Check if user already has a website
    checkExistingWebsite(profileDataToUse)
    
    // Loading complete
    setIsLoading(false)
  }, [])

  const checkExistingWebsite = (data: ProfileData) => {
    if (!data.firstName || !data.lastName || !data.phone || !data.city) {
      return
    }
    
    // Generate slug from current profile data
    const city = data.city || 'الرياض'
    const service = data.services[0] || 'خدمات-عامة'
    const name = `${data.firstName}-${data.lastName}`
    const phone = data.phone || '0500000000'
    
    const citySlug = city.replace(/\s+/g, '-')
    const serviceSlug = service.replace(/\s+/g, '-')
    const nameSlug = name.replace(/\s+/g, '-')
    const phoneSlug = phone.replace(/[^0-9]/g, '')
    
    const websiteSlug = `${citySlug}-${serviceSlug}-${nameSlug}-${phoneSlug}`
    
    // Check if website data exists in localStorage (try both formats)
    let websiteData = localStorage.getItem(`website-${websiteSlug}`)
    
    // Also try URL encoded version
    const encodedSlug = encodeURIComponent(websiteSlug)
    if (!websiteData) {
      websiteData = localStorage.getItem(`website-${encodedSlug}`)
    }
    
    if (websiteData) {
      setExistingWebsiteSlug(websiteSlug)
    } else {
      setExistingWebsiteSlug(null)
    }
  }

  const handleSave = () => {
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

    const updatedData: ProfileData = {
      ...profileData,
      firstName: firstName.trim(),
      lastName: lastName.trim(),
      phone: phone.trim(),
      email: email.trim(),
      city: city.trim(),
      district: district.trim(),
      gender: gender,
      about: about.trim(),
      skills: skills.trim(),
      experience: experience.trim()
    }

    setProfileData(updatedData)
    setIsEditing(false)
    localStorage.setItem('profileData', JSON.stringify(updatedData))
    
    // Check if website needs to be updated
    checkExistingWebsite(updatedData)
    
    alert('تم حفظ البيانات بنجاح!')
  }

  const handleCancel = () => {
    setFirstName(profileData.firstName)
    setLastName(profileData.lastName)
    setPhone(profileData.phone)
    setEmail(profileData.email)
    setCity(profileData.city)
    setDistrict(profileData.district)
    setGender(profileData.gender)
    setAbout(profileData.about)
    setSkills(profileData.skills)
    setExperience(profileData.experience)
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

  const addService = (service: string) => {
    if (profileData.services.includes(service)) {
      alert('هذه الخدمة مضافة بالفعل')
      return
    }
    
    if (profileData.services.length >= 3) {
      alert('لا يمكن إضافة أكثر من 3 خدمات')
      return
    }
    
    const updatedData = {
      ...profileData,
      services: [...profileData.services, service]
    }
    
    setProfileData(updatedData)
    localStorage.setItem('profileData', JSON.stringify(updatedData))
    
    // Update website slug if needed
    checkExistingWebsite(updatedData)
  }

  const removeService = (serviceToRemove: string) => {
    if (profileData.services.length <= 1) {
      alert('يجب أن تكون لديك خدمة واحدة على الأقل')
      return
    }
    
    const updatedData = {
      ...profileData,
      services: profileData.services.filter(service => service !== serviceToRemove)
    }
    
    setProfileData(updatedData)
    localStorage.setItem('profileData', JSON.stringify(updatedData))
    
    // Update website slug if needed
    checkExistingWebsite(updatedData)
  }

  const createWebsiteUrl = () => {
    // If website already exists, redirect to it
    if (existingWebsiteSlug) {
      const websiteUrl = `/ar/provider/${existingWebsiteSlug}`
      window.open(websiteUrl, '_blank')
      return
    }
    
    // Validate that profile data exists for new website
    if (!profileData.firstName || !profileData.lastName) {
      alert('يجب ملء البيانات الشخصية أولاً')
      return
    }
    
    if (!profileData.services || profileData.services.length === 0) {
      alert('يجب اختيار خدمة واحدة على الأقل')
      return
    }
    
    // Create Arabic URL slug from profile data
    const city = profileData.city || 'الرياض'
    const service = profileData.services[0] || 'خدمات-عامة'
    const name = `${profileData.firstName}-${profileData.lastName}`
    const phone = profileData.phone || '0500000000'
    
    // Convert Arabic to URL-friendly format
    const citySlug = city.replace(/\s+/g, '-')
    const serviceSlug = service.replace(/\s+/g, '-')
    const nameSlug = name.replace(/\s+/g, '-')
    const phoneSlug = phone.replace(/[^0-9]/g, '')
    
    const websiteSlug = `${citySlug}-${serviceSlug}-${nameSlug}-${phoneSlug}`
    
    // Save website data for the generated page
    const websiteData = {
      slug: websiteSlug,
      profileData: profileData,
      createdAt: new Date().toISOString()
    }
    
    // Save website data with both normal and encoded keys for compatibility
    localStorage.setItem(`website-${websiteSlug}`, JSON.stringify(websiteData))
    
    // Also save with encoded slug for URL compatibility
    const encodedSlug = encodeURIComponent(websiteSlug)
    localStorage.setItem(`website-${encodedSlug}`, JSON.stringify(websiteData))
    
    // Update state to reflect website exists
    setExistingWebsiteSlug(websiteSlug)
    
    // Show confirmation and URL
    alert(`تم إنشاء موقعك الشخصي بنجاح! \n\nالرابط: khidmaapp.com/ar/provider/${websiteSlug}`)
    
    // Redirect to the generated website
    const websiteUrl = `/ar/provider/${websiteSlug}`
    window.open(websiteUrl, '_blank')
  }

  const formatTimestamp = (timestamp: string) => {
    const date = new Date(timestamp)
    return date.toLocaleDateString('ar-SA', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    })
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

  // This is the Individual Provider Profile Page
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
              <h1 className="text-2xl font-bold text-navy-800">لوحة تحكم مقدم الخدمة</h1>
              <div className="relative">
                <button 
                  onClick={() => setActiveTab('messages')}
                  className="p-2 text-gray-600 hover:text-primary-600 transition-colors"
                >
                  <MessageCircle className="w-6 h-6" />
                  {unreadCount > 0 && (
                    <span className="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                      {unreadCount}
                    </span>
                  )}
                </button>
              </div>
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
                  <div className="w-full h-full bg-primary-100 rounded-full flex items-center justify-center">
                    <User className="w-10 h-10 text-primary-600" />
                  </div>
                  <button className="absolute -bottom-1 -right-1 bg-primary-500 text-white p-1.5 rounded-full hover:bg-primary-600 transition-colors">
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
              </div>

              <nav className="space-y-2">
                <button
                  onClick={() => setActiveTab('profile')}
                  className={`w-full flex items-center px-4 py-3 text-right rounded-lg transition-colors ${
                    activeTab === 'profile' 
                      ? 'bg-primary-50 text-primary-700 border-r-4 border-primary-500' 
                      : 'text-gray-700 hover:bg-gray-50'
                  }`}
                >
                  <User className="w-5 h-5 ml-3" />
                  الملف الشخصي
                </button>
                
                <button
                  onClick={() => setActiveTab('messages')}
                  className={`w-full flex items-center px-4 py-3 text-right rounded-lg transition-colors relative ${
                    activeTab === 'messages' 
                      ? 'bg-primary-50 text-primary-700 border-r-4 border-primary-500' 
                      : 'text-gray-700 hover:bg-gray-50'
                  }`}
                >
                  <MessageCircle className="w-5 h-5 ml-3" />
                  الرسائل
                  {unreadCount > 0 && (
                    <span className="absolute left-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                      {unreadCount}
                    </span>
                  )}
                </button>
                
                <button
                  onClick={() => setActiveTab('ads')}
                  className={`w-full flex items-center px-4 py-3 text-right rounded-lg transition-colors ${
                    activeTab === 'ads' 
                      ? 'bg-primary-50 text-primary-700 border-r-4 border-primary-500' 
                      : 'text-gray-700 hover:bg-gray-50'
                  }`}
                >
                  <Briefcase className="w-5 h-5 ml-3" />
                  الإعلانات المناسبة
                </button>
                
                <button
                  onClick={() => setActiveTab('settings')}
                  className={`w-full flex items-center px-4 py-3 text-right rounded-lg transition-colors ${
                    activeTab === 'settings' 
                      ? 'bg-primary-50 text-primary-700 border-r-4 border-primary-500' 
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
                    className={`w-full flex items-center justify-center px-4 py-3 text-white rounded-lg transition-all duration-300 ${
                      existingWebsiteSlug 
                        ? 'bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700'
                        : 'bg-gradient-to-r from-gold-500 to-gold-600 hover:from-gold-600 hover:to-gold-700'
                    }`}
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
                      <div className="flex items-center text-gray-600">
                        <Briefcase className="w-4 h-4 ml-1" />
                        {profileData.services.join(' • ')}
                      </div>
                    </div>
                    <button
                      onClick={() => setIsEditing(true)}
                      className="flex items-center bg-primary-500 text-white px-4 py-2 rounded-lg hover:bg-primary-600 transition-colors"
                    >
                      <Edit className="w-4 h-4 ml-2" />
                      تحرير الملف الشخصي
                    </button>
                  </div>

                  {!isEditing ? (
                    /* View Mode */
                    <div className="space-y-6">
                      {/* Basic Info */}
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

                      {/* Services Section - View Mode */}
                      <div>
                        <div className="flex items-center justify-between mb-4">
                          <label className="block text-gray-700 font-semibold">خدماتي ({profileData.services.length}/3)</label>
                          <div className="text-sm text-gray-500">يمكنك إضافة حتى 3 خدمات</div>
                        </div>
                        <div className="space-y-3">
                          {profileData.services.map((service, index) => (
                            <div key={index} className="flex items-center justify-between bg-gray-50 px-4 py-3 rounded-lg">
                              <div className="flex items-center">
                                <Briefcase className="w-4 h-4 text-primary-600 ml-2" />
                                <span className="text-gray-900">{service}</span>
                              </div>
                              <button
                                onClick={() => removeService(service)}
                                className="text-red-500 hover:text-red-700 transition-colors"
                                title="حذف الخدمة"
                              >
                                <X className="w-4 h-4" />
                              </button>
                            </div>
                          ))}
                          
                          {/* Add Service Dropdown */}
                          {profileData.services.length < 3 && (
                            <div className="border-2 border-dashed border-gray-300 rounded-lg p-4">
                              <select
                                onChange={(e) => {
                                  if (e.target.value) {
                                    addService(e.target.value)
                                    e.target.value = ''
                                  }
                                }}
                                className="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                dir="rtl"
                              >
                                <option value="">+ إضافة خدمة جديدة</option>
                                {availableServices
                                  .filter(service => !profileData.services.includes(service))
                                  .map((service, index) => (
                                    <option key={index} value={service}>
                                      {service}
                                    </option>
                                  ))}
                              </select>
                            </div>
                          )}
                        </div>
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
                            className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg"
                            dir="rtl"
                          />
                        </div>
                        <div>
                          <label className="block text-gray-700 font-semibold mb-2">الاسم الأخير *</label>
                          <input
                            type="text"
                            value={lastName}
                            onChange={(e) => setLastName(e.target.value)}
                            className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg"
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
                              // Only allow numbers, spaces, and common phone characters
                              const value = e.target.value.replace(/[^0-9+\-\s]/g, '')
                              // Limit to 15 characters (international phone standard)
                              if (value.length <= 15) {
                                setPhone(value)
                              }
                            }}
                            onKeyPress={(e) => {
                              // Only allow numbers, +, -, and space
                              if (!/[0-9+\-\s]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab') {
                                e.preventDefault()
                              }
                            }}
                            placeholder="05XXXXXXXX"
                            pattern="^(05|009665)[0-9]{8}$"
                            className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg"
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
                            className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg"
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
                            className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg"
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
                            className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg"
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
                                className="ml-2 text-primary-500 focus:ring-primary-500"
                              />
                              {option.label}
                            </label>
                          ))}
                        </div>
                      </div>

                      {/* Services Section - Edit Mode */}
                      <div>
                        <div className="flex items-center justify-between mb-4">
                          <label className="block text-gray-700 font-semibold">خدماتي ({profileData.services.length}/3) *</label>
                          <div className="text-sm text-gray-500">يجب اختيار خدمة واحدة على الأقل</div>
                        </div>
                        <div className="space-y-3">
                          {profileData.services.map((service, index) => (
                            <div key={index} className="flex items-center justify-between bg-primary-50 px-4 py-3 rounded-lg border border-primary-200">
                              <div className="flex items-center">
                                <Briefcase className="w-4 h-4 text-primary-600 ml-2" />
                                <span className="text-primary-800 font-medium">{service}</span>
                              </div>
                              <button
                                onClick={() => removeService(service)}
                                className="text-red-500 hover:text-red-700 hover:bg-red-50 p-1 rounded transition-colors"
                                title="حذف الخدمة"
                                type="button"
                              >
                                <X className="w-4 h-4" />
                              </button>
                            </div>
                          ))}
                          
                          {/* Add Service Dropdown */}
                          {profileData.services.length < 3 && (
                            <div className="border-2 border-dashed border-primary-300 rounded-lg p-4 bg-primary-25">
                              <select
                                onChange={(e) => {
                                  if (e.target.value) {
                                    addService(e.target.value)
                                    e.target.value = ''
                                  }
                                }}
                                className="w-full px-4 py-3 bg-white border border-primary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                dir="rtl"
                              >
                                <option value="">+ إضافة خدمة جديدة</option>
                                {availableServices
                                  .filter(service => !profileData.services.includes(service))
                                  .map((service, index) => (
                                    <option key={index} value={service}>
                                      {service}
                                    </option>
                                  ))}
                              </select>
                            </div>
                          )}
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
                          className="flex-1 flex items-center justify-center bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300"
                        >
                          <Save className="w-4 h-4 ml-2" />
                          حفظ التغييرات
                        </button>
                      </div>
                    </div>
                  )}
                </div>

                {/* About Section */}
                <div className="bg-white rounded-xl shadow-sm p-6">
                  <h3 className="text-xl font-bold text-navy-800 mb-6">نبذة عني</h3>
                  
                  {!isEditing ? (
                    <div className="space-y-6">
                      <div>
                        <div className="flex items-center mb-3">
                          <Award className="w-5 h-5 text-primary-600 ml-2" />
                          <h4 className="font-semibold text-gray-800">المهارات والخبرات</h4>
                        </div>
                        <p className="text-gray-700 bg-gray-50 p-4 rounded-lg">
                          {profileData.skills || 'لم يتم إضافة المهارات بعد'}
                        </p>
                      </div>
                      
                      <div>
                        <div className="flex items-center mb-3">
                          <Clock className="w-5 h-5 text-primary-600 ml-2" />
                          <h4 className="font-semibold text-gray-800">الخبرة والتعريف الشخصي</h4>
                        </div>
                        <p className="text-gray-700 bg-gray-50 p-4 rounded-lg">
                          {profileData.experience || 'لم يتم إضافة نبذة شخصية بعد'}
                        </p>
                      </div>
                    </div>
                  ) : (
                    <div className="space-y-6">
                      <div>
                        <label className="block text-gray-700 font-semibold mb-2">المهارات والخبرات</label>
                        <textarea
                          value={skills}
                          onChange={(e) => setSkills(e.target.value)}
                          placeholder="اذكر مهاراتك وتخصصاتك (مثل: خبرة 5 سنوات في التنظيف، استخدام أحدث المعدات...)"
                          rows={4}
                          className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg resize-none"
                          dir="rtl"
                        />
                      </div>
                      
                      <div>
                        <label className="block text-gray-700 font-semibold mb-2">الخبرة والتعريف الشخصي</label>
                        <textarea
                          value={experience}
                          onChange={(e) => setExperience(e.target.value)}
                          placeholder="اكتب نبذة عن خبرتك وما يميزك عن غيرك..."
                          rows={4}
                          className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg resize-none"
                          dir="rtl"
                        />
                      </div>
                    </div>
                  )}
                </div>
              </div>
            )}

            {/* Messages Tab */}
            {activeTab === 'messages' && (
              <div className="bg-white rounded-xl shadow-sm p-6">
                <h2 className="text-2xl font-bold text-navy-800 mb-6">الرسائل</h2>
                
                <div className="space-y-4">
                  {messages.map(message => (
                    <div 
                      key={message.id} 
                      className={`border rounded-lg p-4 transition-colors hover:bg-gray-50 ${
                        !message.isRead ? 'border-primary-200 bg-primary-50' : 'border-gray-200'
                      }`}
                    >
                      <div className="flex items-start justify-between mb-2">
                        <div className="flex items-center">
                          <div className="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center ml-3">
                            <User className="w-5 h-5 text-primary-600" />
                          </div>
                          <div>
                            <h4 className="font-semibold text-gray-800">{message.senderName}</h4>
                            <p className="text-sm text-gray-500">{formatTimestamp(message.timestamp)}</p>
                          </div>
                        </div>
                        {!message.isRead && (
                          <span className="bg-red-500 text-white text-xs px-2 py-1 rounded-full">جديد</span>
                        )}
                      </div>
                      <p className="text-gray-700">{message.message}</p>
                      <div className="mt-3 flex space-x-2 rtl:space-x-reverse">
                        <button className="bg-primary-500 text-white px-4 py-2 rounded-lg hover:bg-primary-600 transition-colors">
                          رد
                        </button>
                        <button className="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                          تفاصيل
                        </button>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            )}

            {/* Ads Tab */}
            {activeTab === 'ads' && (
              <div className="bg-white rounded-xl shadow-sm p-6">
                <h2 className="text-2xl font-bold text-navy-800 mb-6">الإعلانات المناسبة لك</h2>
                
                <div className="space-y-4">
                  {relatedAds.map(ad => (
                    <div key={ad.id} className="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                      <div className="flex items-start justify-between mb-3">
                        <div>
                          <h4 className="font-bold text-navy-800 mb-1">{ad.title}</h4>
                          <div className="flex items-center text-sm text-gray-600">
                            <MapPin className="w-4 h-4 ml-1" />
                            {ad.location}
                            <span className="mx-2">•</span>
                            <Clock className="w-4 h-4 ml-1" />
                            {formatTimestamp(ad.timestamp)}
                          </div>
                        </div>
                        <span className="bg-primary-100 text-primary-700 px-3 py-1 rounded-full text-sm">
                          {ad.category}
                        </span>
                      </div>
                      
                      <p className="text-gray-700 mb-3">{ad.description}</p>
                      
                      <div className="flex items-center justify-between">
                        <div className="text-lg font-bold text-green-600">{ad.budget}</div>
                        <div className="flex space-x-2 rtl:space-x-reverse">
                          <button className="bg-primary-500 text-white px-4 py-2 rounded-lg hover:bg-primary-600 transition-colors">
                            تقديم عرض
                          </button>
                          <button className="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                            تفاصيل
                          </button>
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
                          className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg"
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
                        className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg"
                        dir="ltr"
                      />
                    </div>
                    
                    <div>
                      <label className="block text-gray-700 font-semibold mb-2">تأكيد كلمة المرور الجديدة</label>
                      <input
                        type={showPasswords ? "text" : "password"}
                        value={confirmPassword}
                        onChange={(e) => setConfirmPassword(e.target.value)}
                        className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg"
                        dir="ltr"
                      />
                    </div>
                    
                    <button
                      onClick={handlePasswordChange}
                      className="bg-primary-500 text-white px-6 py-3 rounded-lg hover:bg-primary-600 transition-colors"
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
                        className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg"
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
                        <p className="text-sm text-gray-600">تلقي إشعارات عن الرسائل والعروض الجديدة</p>
                      </div>
                      <label className="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" defaultChecked className="sr-only peer" />
                        <div className="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                      </label>
                    </div>
                    
                    <div className="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                      <div>
                        <h4 className="font-semibold text-gray-800">إشعارات الهاتف</h4>
                        <p className="text-sm text-gray-600">تلقي رسائل نصية للفرص الجديدة</p>
                      </div>
                      <label className="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" defaultChecked className="sr-only peer" />
                        <div className="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                      </label>
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
