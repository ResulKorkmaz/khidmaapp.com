'use client'

import { useState, useCallback, memo, useEffect } from 'react'
import { User, Building2, ArrowLeft, CheckCircle, Phone, Mail, MapPin, Camera, FileText, CreditCard, Users, Search, X, AlertCircle } from 'lucide-react'
import Link from 'next/link'

type ProviderType = 'individual' | 'company' | null
type Step = 'type-selection' | 'individual-services' | 'individual-personal' | 'individual-location' | 'individual-password' | 'company-services' | 'company-personal' | 'company-location' | 'company-documents' | 'company-logo' | 'company-password' | 'success' | 'password-reset'

interface FormData {
  // Common fields
  phone: string
  email: string
  password: string
  confirmPassword: string
  city: string
  district: string
  services: string[]
  
  // Individual specific
  firstName: string
  lastName: string
  
  // Company specific
  companyName?: string
  authorizedPersonName?: string
  authorizedPersonSurname?: string
  companyPhone?: string
  companyEmail?: string
  serviceCities?: string[]
  companyDocument?: File | null
  companyLogo?: File | null
}

interface ValidationErrors {
  password?: string
  confirmPassword?: string
  email?: string
  phone?: string
  services?: string
  firstName?: string
  lastName?: string
  city?: string
  district?: string
  companyName?: string
  authorizedPersonName?: string
  authorizedPersonSurname?: string
  companyPhone?: string
  companyEmail?: string
  serviceCities?: string
  companyDocument?: string
}

export default function ProviderJoinPage() {
  const [currentStep, setCurrentStep] = useState<Step>('type-selection')
  const [providerType, setProviderType] = useState<ProviderType>(null)
  const [formData, setFormData] = useState<FormData>({
    phone: '',
    email: '',
    password: '',
    confirmPassword: '',
    city: '',
    district: '',
    services: [],
    firstName: '',
    lastName: '',
    // Company specific
    companyName: '',
    authorizedPersonName: '',
    authorizedPersonSurname: '',
    companyPhone: '',
    companyEmail: '',
    serviceCities: [],
    companyDocument: null,
    companyLogo: null
  })
  const [errors, setErrors] = useState<ValidationErrors>({})
  const [searchQuery, setSearchQuery] = useState('')
  const [showPasswordResetEmail, setShowPasswordResetEmail] = useState('')

  const serviceCategories = [
    'تنظيف المنازل', 'تنظيف المنازل الفارغة', 'خدمات السباكة', 'خدمات الكهرباء', 
    'تركيب وصيانة التكييف', 'إصلاح الأجهزة المنزلية', 'الدهان والطلاء', 'التجديد وتطوير المنزل',
    'النجارة وتركيب الأثاث', 'تنظيف السجاد والمفروشات', 'مكافحة الحشرات والتطهير', 'تنسيق الحدائق والمناظر الطبيعية',
    'خدمات التجميل المنزلية', 'خدمات النقل والتخزين', 'تركيب الأنظمة الإلكترونية', 'الخدمات المالية والتأمين',
    'التعليم والدروس الخصوصية', 'التمريض والعلاج الطبيعي المنزلي', 'إعداد الطعام والضيافة', 'التنظيف الجاف وخدمات الغسيل',
    'المساعدة المنزلية اليومية', 'الخدمات القانونية والترجمة', 'المحاسبة والاستشارات الضريبية', 'التسويق الرقمي وإدارة وسائل التواصل',
    'تصميم المواقع والبرمجة', 'الدعم التقني وإصلاح الأجهزة', 'تركيب أنظمة الأمان', 'خدمات الأرضيات والبلاط',
    'العزل المائي والحراري', 'تجديد وتنجيد الأثاث', 'تركيب الإضاءة والكهربائيات', 'الديكور والتصميم الداخلي',
    'تعليم القيادة', 'ديكور الجدران', 'تنظيف وغسيل الأرائك', 'الاستشارات النفسية'
  ]

  const filteredServices = serviceCategories.filter(service => 
    service.toLowerCase().includes(searchQuery.toLowerCase())
  )

  const cities = [
    'الرياض', 'جدة', 'الدمام', 'مكة المكرمة', 'المدينة المنورة', 'الطائف',
    'تبوك', 'بريدة', 'خميس مشيط', 'الأحساء', 'نجران', 'جازان'
  ]

  // Validation functions
  const validatePassword = (password: string) => {
    if (password.length < 8) {
      return 'كلمة المرور يجب أن تكون 8 أحرف على الأقل'
    }
    return ''
  }

  const validatePasswordMatch = (password: string, confirmPassword: string) => {
    if (password !== confirmPassword) {
      return 'كلمات المرور غير متطابقة'
    }
    return ''
  }

  const validateEmail = (email: string) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!emailRegex.test(email)) {
      return 'صيغة البريد الإلكتروني غير صحيحة'
    }
    return ''
  }

  const validatePhone = (phone: string) => {
    const phoneRegex = /^05\d{8}$/
    if (!phoneRegex.test(phone)) {
      return 'رقم الجوال يجب أن يبدأ بـ 05 ويكون 10 أرقام'
    }
    return ''
  }

  const maskEmail = (email: string) => {
    if (email.length < 4) return email
    const [username, domain] = email.split('@')
    if (!domain) return email
    const maskedUsername = username.slice(0, 2) + '*'.repeat(Math.max(0, username.length - 4)) + username.slice(-2)
    return `${maskedUsername}@${domain}`
  }

  // Mock function to check if email/phone exists
  const checkUserExists = async (email: string, phone: string) => {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 500))
    
    // Mock existing users
    const existingEmails = ['test@example.com', 'user@gmail.com']
    const existingPhones = ['0512345678', '0598765432']
    
    return {
      emailExists: existingEmails.includes(email),
      phoneExists: existingPhones.includes(phone)
    }
  }

  const handleTypeSelection = (type: ProviderType) => {
    setProviderType(type)
    if (type === 'individual') {
      setCurrentStep('individual-services')
    } else if (type === 'company') {
      setCurrentStep('company-services')
    }
  }

  const handleInputChange = useCallback((field: keyof FormData, value: string | string[]) => {
    setFormData(prev => ({
      ...prev,
      [field]: value
    }))
    
    // Clear any existing errors for this field
    if (errors[field as keyof ValidationErrors]) {
      setErrors(prev => {
        const newErrors = { ...prev }
        delete newErrors[field as keyof ValidationErrors]
        return newErrors
      })
    }
  }, [errors])

  const handleServiceToggle = (service: string) => {
    setFormData(prev => ({
      ...prev,
      services: prev.services.includes(service)
        ? prev.services.filter(s => s !== service)
        : [...prev.services, service]
    }))
  }

  const handleServicesNext = () => {
    if (formData.services.length === 0) {
      setErrors({ services: 'يجب اختيار خدمة واحدة على الأقل' })
      return
    }
    if (formData.services.length > 3) {
      setErrors({ services: 'يمكن اختيار 3 خدمات كحد أقصى' })
      return
    }
    setErrors({})
    
    // Save services to localStorage for persistence
    localStorage.setItem('tempRegistrationData', JSON.stringify(formData))
    
    setCurrentStep('individual-personal')
  }

  const handlePersonalNext = () => {
    const newErrors: ValidationErrors = {}
    
    if (!formData.firstName.trim()) {
      newErrors.firstName = 'الاسم الأول مطلوب'
    }
    if (!formData.lastName.trim()) {
      newErrors.lastName = 'الاسم الأخير مطلوب'
    }
    
    const phoneError = validatePhone(formData.phone)
    if (phoneError) {
      newErrors.phone = phoneError
    }
    
    const emailError = validateEmail(formData.email)
    if (emailError) {
      newErrors.email = emailError
    }
    
    if (Object.keys(newErrors).length > 0) {
      setErrors(newErrors)
      return
    }
    
    setErrors({})
    setCurrentStep('individual-location')
  }

  const handleLocationNext = () => {
    const newErrors: ValidationErrors = {}
    
    if (!formData.city) {
      newErrors.city = 'يجب اختيار المدينة'
    }
    if (!formData.district?.trim()) {
      newErrors.district = 'يجب إدخال اسم الحي'
    }
    
    if (Object.keys(newErrors).length > 0) {
      setErrors(newErrors)
      return
    }
    
    setErrors({})
    setCurrentStep('individual-password')
  }

  const handlePasswordSubmit = async () => {
    const newErrors: ValidationErrors = {}
    
    const passwordError = validatePassword(formData.password)
    if (passwordError) {
      newErrors.password = passwordError
    }
    
    const confirmError = validatePasswordMatch(formData.password, formData.confirmPassword)
    if (confirmError) {
      newErrors.confirmPassword = confirmError
    }
    
    if (Object.keys(newErrors).length > 0) {
      setErrors(newErrors)
      return
    }
    
    // Check if user exists
    try {
      const userCheck = await checkUserExists(formData.email, formData.phone)
      
      if (userCheck.emailExists || userCheck.phoneExists) {
        setShowPasswordResetEmail(formData.email)
        setCurrentStep('password-reset')
        return
      }
      
      // Submit form data
      setCurrentStep('success')
    } catch (error) {
      setErrors({ email: 'حدث خطأ في التحقق من البيانات' })
    }
  }

  const handlePasswordChange = (value: string) => {
    handleInputChange('password', value)
    if (value && formData.confirmPassword) {
      const error = validatePasswordMatch(value, formData.confirmPassword)
      setErrors(prev => {
        // Only update if error state actually changes
        if (prev.confirmPassword !== error) {
          return { ...prev, confirmPassword: error }
        }
        return prev
      })
    }
  }

  const handleConfirmPasswordChange = (value: string) => {
    handleInputChange('confirmPassword', value)
    if (value && formData.password) {
      const error = validatePasswordMatch(formData.password, value)
      setErrors(prev => {
        // Only update if error state actually changes
        if (prev.confirmPassword !== error) {
          return { ...prev, confirmPassword: error }
        }
        return prev
      })
    }
  }

  function TypeSelectionStep() {
    return (
    <div className="max-w-4xl mx-auto">
      <div className="text-center mb-12">
        <h1 className="text-4xl font-bold text-navy-800 mb-4">
          انضم كمقدم خدمة
        </h1>
        <p className="text-xl text-gray-600 max-w-2xl mx-auto">
          اختر نوع التسجيل المناسب لك وابدأ رحلتك معنا
        </p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
        {/* Individual Registration */}
        <div 
          onClick={() => handleTypeSelection('individual')}
          className="bg-white rounded-2xl p-8 border-2 border-gray-200 hover:border-primary-500 cursor-pointer transition-all duration-300 hover:shadow-xl group"
        >
          <div className="text-center">
            <div className="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-primary-200 transition-colors">
              <User className="w-10 h-10 text-primary-600" />
            </div>
            <h3 className="text-2xl font-bold text-navy-800 mb-4">
              تسجيل شخصي
            </h3>
            <p className="text-gray-600 mb-6 leading-relaxed">
              للأفراد الذين يرغبون في تقديم الخدمات بشكل شخصي
            </p>
            <ul className="text-right space-y-3 mb-8">
              <li className="flex items-center">
                <CheckCircle className="w-5 h-5 text-green-500 ml-3 flex-shrink-0" />
                <span className="text-gray-700">تسجيل سريع وبسيط</span>
              </li>
              <li className="flex items-center">
                <CheckCircle className="w-5 h-5 text-green-500 ml-3 flex-shrink-0" />
                <span className="text-gray-700">مرونة في المواعيد</span>
              </li>
              <li className="flex items-center">
                <CheckCircle className="w-5 h-5 text-green-500 ml-3 flex-shrink-0" />
                <span className="text-gray-700">عمولة أقل</span>
              </li>
            </ul>
            <div className="bg-primary-500 text-white px-6 py-3 rounded-lg font-semibold group-hover:bg-primary-600 transition-colors">
              ابدأ كفرد
            </div>
          </div>
        </div>

        {/* Company Registration */}
        <div 
          onClick={() => handleTypeSelection('company')}
          className="bg-white rounded-2xl p-8 border-2 border-gray-200 hover:border-gold-500 cursor-pointer transition-all duration-300 hover:shadow-xl group"
        >
          <div className="text-center">
            <div className="w-20 h-20 bg-gold-100 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-gold-200 transition-colors">
              <Building2 className="w-10 h-10 text-gold-600" />
            </div>
            <h3 className="text-2xl font-bold text-navy-800 mb-4">
              تسجيل شركة
            </h3>
            <p className="text-gray-600 mb-6 leading-relaxed">
              للشركات والمؤسسات التي تقدم الخدمات بشكل احترافي
            </p>
            <ul className="text-right space-y-3 mb-8">
              <li className="flex items-center">
                <CheckCircle className="w-5 h-5 text-green-500 ml-3 flex-shrink-0" />
                <span className="text-gray-700">تعدد مقدمي الخدمات</span>
              </li>
              <li className="flex items-center">
                <CheckCircle className="w-5 h-5 text-green-500 ml-3 flex-shrink-0" />
                <span className="text-gray-700">أولوية في العرض</span>
              </li>
              <li className="flex items-center">
                <CheckCircle className="w-5 h-5 text-green-500 ml-3 flex-shrink-0" />
                <span className="text-gray-700">دعم مخصص</span>
              </li>
            </ul>
            <div className="bg-gold-500 text-white px-6 py-3 rounded-lg font-semibold group-hover:bg-gold-600 transition-colors">
              ابدأ كشركة
            </div>
          </div>
        </div>
      </div>

      <div className="text-center mt-12">
        <Link 
          href="/"
          className="inline-flex items-center text-gray-600 hover:text-primary-600 transition-colors"
        >
          <ArrowLeft className="w-5 h-5 ml-2" />
          العودة للرئيسية
        </Link>
      </div>
    </div>
    )
  }

  // Step 2: Services Selection
  function IndividualServicesStep() {
    return (
    <div className="max-w-2xl mx-auto">
      <div className="text-center mb-8">
        <h2 className="text-3xl font-bold text-navy-800 mb-2">
          اختر الخدمات التي تقدمها
        </h2>
        <p className="text-gray-600">
          يمكنك اختيار حتى 3 خدمات كحد أقصى
        </p>
        <div className="text-sm text-primary-600 mt-2">
          تم اختيار {formData.services.length} من 3 خدمات
        </div>
      </div>

      <div className="space-y-6">
        {/* Search Input */}
        <div className="relative">
          <Search className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
          <input
            type="text"
            placeholder="ابحث عن الخدمة..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary-500"
          />
          {searchQuery && (
            <button
              onClick={() => setSearchQuery('')}
              className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
            >
              <X className="w-5 h-5" />
            </button>
          )}
        </div>

        {/* Services Grid */}
        <div className="max-h-96 overflow-y-auto border border-gray-300 rounded-lg p-4">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
            {filteredServices.map(service => {
              const isSelected = formData.services.includes(service)
              const isDisabled = !isSelected && formData.services.length >= 3
              
              return (
                <label 
                  key={service} 
                  className={`flex items-center cursor-pointer p-3 rounded-lg border-2 transition-all ${
                    isSelected 
                      ? 'border-primary-500 bg-primary-50' 
                      : isDisabled 
                        ? 'border-gray-200 bg-gray-50 opacity-50 cursor-not-allowed'
                        : 'border-gray-200 hover:border-primary-300 hover:bg-primary-50'
                  }`}
                >
                  <input
                    type="checkbox"
                    checked={isSelected}
                    disabled={isDisabled}
                    onChange={() => !isDisabled && handleServiceToggle(service)}
                    className="ml-3 text-primary-500 focus:ring-primary-500 disabled:opacity-50"
                  />
                  <span className={`text-gray-700 ${isDisabled ? 'text-gray-400' : ''}`}>
                    {service}
                  </span>
                </label>
              )
            })}
          </div>
        </div>

        {/* Error Message */}
        {errors.services && (
          <div className="flex items-center text-red-600 text-sm">
            <AlertCircle className="w-4 h-4 ml-2" />
            {errors.services}
          </div>
        )}

        {/* Navigation */}
        <div className="flex flex-col sm:flex-row gap-4 pt-6">
          <button
            onClick={() => setCurrentStep('type-selection')}
            className="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
          >
            رجوع
          </button>
          <button
            onClick={handleServicesNext}
            className="flex-1 bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300"
          >
            التالي
          </button>
        </div>
      </div>
    </div>
    )
  }

  // Step 3: Personal Information - COMPLETELY REBUILT with local state like SearchInput
  function IndividualPersonalStep() {
    const [firstName, setFirstName] = useState('')
    const [lastName, setLastName] = useState('')
    const [phone, setPhone] = useState('')
    const [email, setEmail] = useState('')

    const handleNext = () => {
      // Simple validation
      if (!firstName.trim() || !lastName.trim() || !phone.trim() || !email.trim()) {
        alert('جميع الحقول مطلوبة')
        return
      }
      
      // Update main form data
      const updatedData = {
        ...formData,
        firstName: firstName.trim(),
        lastName: lastName.trim(),
        phone: phone.trim(),
        email: email.trim()
      }
      
      setFormData(updatedData)
      
      // Save to localStorage for persistence
      localStorage.setItem('tempRegistrationData', JSON.stringify(updatedData))
      
      setCurrentStep('individual-location')
    }

    return (
      <div className="max-w-2xl mx-auto" dir="rtl">
        <div className="text-center mb-8">
          <h2 className="text-3xl font-bold text-navy-800 mb-2">
            البيانات الشخصية
          </h2>
          <p className="text-gray-600">
            املأ بياناتك الشخصية ومعلومات التواصل
          </p>
        </div>

        <div className="space-y-6">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label className="block text-gray-700 font-semibold mb-2">
                الاسم الأول *
              </label>
              <input
                type="text"
                value={firstName}
                onChange={(e) => setFirstName(e.target.value)}
                placeholder="محمد"
                className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg"
                dir="rtl"
              />
            </div>
            <div>
              <label className="block text-gray-700 font-semibold mb-2">
                الاسم الأخير *
              </label>
              <input
                type="text"
                value={lastName}
                onChange={(e) => setLastName(e.target.value)}
                placeholder="أحمد"
                className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg"
                dir="rtl"
              />
            </div>
          </div>

          <div>
            <label className="block text-gray-700 font-semibold mb-2">
              رقم الجوال *
            </label>
            <input
              type="tel"
              value={phone}
              onChange={(e) => setPhone(e.target.value)}
              placeholder="05xxxxxxxx"
              className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg"
              dir="ltr"
            />
          </div>

          <div>
            <label className="block text-gray-700 font-semibold mb-2">
              البريد الإلكتروني *
            </label>
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              placeholder="example@email.com"
              className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg"
              dir="ltr"
            />
          </div>

          <div className="flex flex-col sm:flex-row gap-4 pt-6">
            <button
              onClick={() => setCurrentStep('individual-services')}
              className="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
            >
              رجوع
            </button>
            <button
              onClick={handleNext}
              className="flex-1 bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300"
            >
              التالي
            </button>
          </div>
        </div>
      </div>
    )
  }

  // Step 4: Location - REBUILT with local state like SearchInput
  function IndividualLocationStep() {
    const [city, setCity] = useState('')
    const [district, setDistrict] = useState('')

    const cities = [
      'الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 'الخبر', 'الظهران',
      'تبوك', 'بريدة', 'خميس مشيط', 'أبها', 'نجران', 'الطائف', 'حائل', 'الجبيل',
      'ينبع', 'الاحساء', 'القطيف', 'عرعر', 'سكاكا', 'جازان', 'الباحة'
    ]

    const handleNext = () => {
      // Simple validation
      if (!city.trim() || !district.trim()) {
        alert('يجب اختيار المدينة وإدخال اسم الحي')
        return
      }
      
      // Update main form data
      const updatedData = {
        ...formData,
        city: city.trim(),
        district: district.trim()
      }
      
      setFormData(updatedData)
      
      // Save to localStorage for persistence
      localStorage.setItem('tempRegistrationData', JSON.stringify(updatedData))
      
      setCurrentStep('individual-password')
    }

    return (
      <div className="max-w-2xl mx-auto" dir="rtl">
        <div className="text-center mb-8">
          <h2 className="text-3xl font-bold text-navy-800 mb-2">
            الموقع
          </h2>
          <p className="text-gray-600">
            حدد المدينة والحي الذي تقدم فيه الخدمات
          </p>
        </div>

        <div className="space-y-6">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label className="block text-gray-700 font-semibold mb-2">
                المدينة *
              </label>
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
              <label className="block text-gray-700 font-semibold mb-2">
                الحي *
              </label>
              <input
                type="text"
                value={district}
                onChange={(e) => setDistrict(e.target.value)}
                placeholder="اسم الحي"
                className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg"
                dir="rtl"
              />
            </div>
          </div>

          <div className="flex flex-col sm:flex-row gap-4 pt-6">
            <button
              onClick={() => setCurrentStep('individual-personal')}
              className="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
            >
              رجوع
            </button>
            <button
              onClick={handleNext}
              className="flex-1 bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300"
            >
              التالي
            </button>
          </div>
        </div>
      </div>
    )
  }

  // Step 5: Password - REBUILT with local state like SearchInput
  function IndividualPasswordStep() {
    const [password, setPassword] = useState('')
    const [confirmPassword, setConfirmPassword] = useState('')
    const [isMatching, setIsMatching] = useState(false)
    const [isLoading, setIsLoading] = useState(false)

    // Check password matching in real-time
    useEffect(() => {
      if (password && confirmPassword) {
        setIsMatching(password === confirmPassword)
      } else {
        setIsMatching(false)
      }
    }, [password, confirmPassword])

    const handleCreateAccount = async () => {
      // Simple validation
      if (!password || password.length < 8) {
        alert('كلمة المرور يجب أن تكون 8 أحرف على الأقل')
        return
      }
      
      if (!confirmPassword) {
        alert('يجب تأكيد كلمة المرور')
        return
      }
      
      if (password !== confirmPassword) {
        alert('كلمات المرور غير متطابقة')
        return
      }

      setIsLoading(true)

      try {
        // Get any temporarily saved data and merge with current form data
        const tempData = localStorage.getItem('tempRegistrationData')
        const tempRegistrationData = tempData ? JSON.parse(tempData) : {}
        
        // Update main form data with all collected information
        const completeFormData = {
          ...formData,
          ...tempRegistrationData,  // Include any temporary data
          password: password,
          confirmPassword: confirmPassword
        }

        setFormData(completeFormData)

        // Prepare data for backend API
        const registrationData = {
          name: `${completeFormData.firstName} ${completeFormData.lastName}`,
          email: completeFormData.email,
          phone: completeFormData.phone,
          password: password,
          password_confirmation: confirmPassword,
          role: 'individual_provider',
          locale: 'ar',
          terms_accepted: true
        }

        // Import API service dynamically
        const { default: apiService } = await import('@/services/api')

        // Send registration to backend
        const response = await apiService.register(registrationData) as any

        if (response.success) {
          // Save token for authentication
          apiService.setToken(response.data.token)

          // Save complete profile data including services and location for profile page
          const profileData = {
            firstName: completeFormData.firstName,
            lastName: completeFormData.lastName,
            phone: completeFormData.phone,
            email: completeFormData.email,
            city: completeFormData.city,
            district: completeFormData.district,
            services: completeFormData.services,
            userId: response.data.user.id,
            registrationDate: new Date().toISOString()
          }

          localStorage.setItem('registrationData', JSON.stringify(profileData))
          localStorage.setItem('authUser', JSON.stringify(response.data.user))
          
          // Clean up temporary data
          localStorage.removeItem('tempRegistrationData')
          
          // Show success message
          alert('تم إنشاء الحساب بنجاح! مرحباً بك في خدمة أب')
          
          // Redirect to profile page
          window.location.href = '/ar/dashboard/profile'
        } else {
          throw new Error(response.message || 'حدث خطأ أثناء إنشاء الحساب')
        }

      } catch (error: any) {
        console.error('Registration error:', error)
        
        // Handle different error types
        if (error.message?.includes('duplicate') || error.message?.includes('unique')) {
          alert('البريد الإلكتروني أو رقم الجوال مستخدم بالفعل. يرجى استخدام بيانات أخرى.')
        } else if (error.message?.includes('validation')) {
          alert('البيانات المدخلة غير صحيحة. يرجى مراجعة جميع الحقول.')
        } else if (error.message?.includes('network') || error.message?.includes('fetch')) {
          alert('خطأ في الاتصال. يرجى التأكد من اتصال الإنترنت والمحاولة مجدداً.')
        } else {
          alert(`حدث خطأ أثناء إنشاء الحساب: ${error.message}`)
        }
      } finally {
        setIsLoading(false)
      }
    }

    return (
      <div className="max-w-2xl mx-auto" dir="rtl">
        <div className="text-center mb-8">
          <h2 className="text-3xl font-bold text-navy-800 mb-2">
            إنشاء كلمة المرور
          </h2>
          <p className="text-gray-600">
            اختر كلمة مرور قوية لحماية حسابك
          </p>
        </div>

        <div className="space-y-6">
          <div>
            <label className="block text-gray-700 font-semibold mb-2">
              كلمة المرور *
            </label>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              placeholder="8 أحرف على الأقل"
              className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg"
              dir="ltr"
            />
            {password && password.length < 8 && (
              <p className="text-orange-600 text-sm mt-2">كلمة المرور قصيرة جداً</p>
            )}
            {password && password.length >= 8 && (
              <p className="text-green-600 text-sm mt-2">كلمة المرور قوية ✓</p>
            )}
          </div>

          <div>
            <label className="block text-gray-700 font-semibold mb-2">
              تأكيد كلمة المرور *
            </label>
            <input
              type="password"
              value={confirmPassword}
              onChange={(e) => setConfirmPassword(e.target.value)}
              placeholder="أعد كتابة كلمة المرور"
              className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg"
              dir="ltr"
            />
            {confirmPassword && !isMatching && (
              <p className="text-red-600 text-sm mt-2">كلمات المرور غير متطابقة</p>
            )}
            {confirmPassword && isMatching && (
              <p className="text-green-600 text-sm mt-2">كلمات المرور متطابقة ✓</p>
            )}
          </div>

          <div className="flex flex-col sm:flex-row gap-4 pt-6">
            <button
              onClick={() => setCurrentStep('individual-location')}
              className="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
            >
              رجوع
            </button>
            <button
              onClick={handleCreateAccount}
              disabled={!isMatching || password.length < 8 || isLoading}
              className={`flex-1 bg-gradient-to-r ${(!isMatching || password.length < 8 || isLoading) ? 'from-gray-400 to-gray-500 cursor-not-allowed' : 'from-green-500 to-green-600 hover:from-green-600 hover:to-green-700'} text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center justify-center`}
            >
              {isLoading ? (
                <>
                  <div className="animate-spin rounded-full h-5 w-5 border-b-2 border-white ml-2"></div>
                  جاري إنشاء الحساب...
                </>
              ) : (
                'إنشاء الحساب'
              )}
            </button>
          </div>
        </div>
      </div>
    )
  }

  // Company Registration Steps
  
  // Step 1: Company Services Selection (7 categories max)
  const CompanyServicesStep = () => {
    const [selectedServices, setSelectedServices] = useState<string[]>(formData.services || [])
    
    const handleServiceToggle = (service: string) => {
      setSelectedServices(prev => {
        if (prev.includes(service)) {
          return prev.filter(s => s !== service)
        } else if (prev.length < 7) {
          return [...prev, service]
        }
        return prev
      })
    }

    const handleNext = () => {
      if (selectedServices.length === 0) {
        alert('يرجى اختيار خدمة واحدة على الأقل')
        return
      }
      setFormData(prev => ({ ...prev, services: selectedServices }))
      setCurrentStep('company-personal')
    }

    return (
      <div className="max-w-4xl mx-auto">
        <div className="text-center mb-8">
          <h2 className="text-3xl font-bold text-navy-800 mb-2">
            اختر خدمات شركتك
          </h2>
          <p className="text-gray-600">
            يمكنك اختيار حتى 7 خدمات تقدمها شركتك ({selectedServices.length}/7)
          </p>
        </div>

        <div className="mb-6">
          <div className="relative">
            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
            <input
              type="text"
              placeholder="ابحث عن الخدمات..."
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              className="w-full pl-12 pr-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-transparent text-lg"
              dir="rtl"
            />
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8 max-h-96 overflow-y-auto custom-scrollbar">
          {filteredServices.map((service, index) => (
            <div
              key={index}
              onClick={() => handleServiceToggle(service)}
              className={`p-4 rounded-xl border-2 cursor-pointer transition-all duration-300 hover:shadow-lg ${
                selectedServices.includes(service)
                  ? 'border-gold-500 bg-gold-50 shadow-md'
                  : 'border-gray-200 hover:border-gold-300'
              }`}
            >
              <div className="flex items-center justify-between">
                <span className="text-gray-800 font-medium text-right flex-1">
                  {service}
                </span>
                {selectedServices.includes(service) && (
                  <CheckCircle className="w-5 h-5 text-gold-600 ml-2" />
                )}
              </div>
            </div>
          ))}
        </div>

        <div className="flex justify-between">
          <button
            onClick={() => setCurrentStep('type-selection')}
            className="flex items-center px-6 py-3 text-gray-600 hover:text-gray-800 transition-colors"
          >
            <ArrowLeft className="w-5 h-5 ml-2" />
            السابق
          </button>
          
          <button
            onClick={handleNext}
            disabled={selectedServices.length === 0}
            className={`px-8 py-3 rounded-xl font-semibold transition-all ${
              selectedServices.length === 0
                ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                : 'bg-gradient-to-r from-gold-500 to-gold-600 text-white hover:from-gold-600 hover:to-gold-700'
            }`}
          >
            التالي
          </button>
        </div>
      </div>
    )
  }

  // Step 2: Company Personal Information  
  const CompanyPersonalStep = () => {
    const [companyName, setCompanyName] = useState(formData.companyName || '')
    const [authorizedPersonName, setAuthorizedPersonName] = useState(formData.authorizedPersonName || '')
    const [authorizedPersonSurname, setAuthorizedPersonSurname] = useState(formData.authorizedPersonSurname || '')
    const [companyPhone, setCompanyPhone] = useState(formData.companyPhone || '')
    const [companyEmail, setCompanyEmail] = useState(formData.companyEmail || '')

    const handleNext = () => {
      if (!companyName.trim()) {
        alert('يرجى إدخال اسم الشركة')
        return
      }
      if (!authorizedPersonName.trim()) {
        alert('يرجى إدخال اسم المفوض')
        return
      }
      if (!authorizedPersonSurname.trim()) {
        alert('يرجى إدخال اسم عائلة المفوض')
        return
      }
      const phoneRegex = /^(05|009665)[0-9]{8}$/
      if (!phoneRegex.test(companyPhone.trim().replace(/[\s\-]/g, ''))) {
        alert('رقم الجوال غير صحيح. يجب أن يبدأ بـ 05 ويتكون من 10 أرقام\nمثال: 0501234567')
        return
      }
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      if (!emailRegex.test(companyEmail.trim())) {
        alert('البريد الإلكتروني غير صحيح')
        return
      }

      setFormData(prev => ({ 
        ...prev, 
        companyName: companyName.trim(),
        authorizedPersonName: authorizedPersonName.trim(),
        authorizedPersonSurname: authorizedPersonSurname.trim(),
        companyPhone: companyPhone.trim(),
        companyEmail: companyEmail.trim()
      }))
      setCurrentStep('company-location')
    }

    return (
      <div className="max-w-2xl mx-auto">
        <div className="text-center mb-8">
          <h2 className="text-3xl font-bold text-navy-800 mb-2">
            بيانات الشركة
          </h2>
          <p className="text-gray-600">
            املأ المعلومات الأساسية للشركة
          </p>
        </div>

        <div className="space-y-6">
          <div>
            <label className="block text-gray-700 font-semibold mb-2">
              اسم الشركة *
            </label>
            <input
              type="text"
              value={companyName}
              onChange={(e) => setCompanyName(e.target.value)}
              className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-transparent text-lg"
              placeholder="مثال: شركة الخدمات المتقدمة"
              dir="rtl"
            />
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label className="block text-gray-700 font-semibold mb-2">
                اسم المفوض *
              </label>
              <input
                type="text"
                value={authorizedPersonName}
                onChange={(e) => setAuthorizedPersonName(e.target.value)}
                className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-transparent text-lg"
                placeholder="أحمد"
                dir="rtl"
              />
            </div>
            <div>
              <label className="block text-gray-700 font-semibold mb-2">
                اسم العائلة *
              </label>
              <input
                type="text"
                value={authorizedPersonSurname}
                onChange={(e) => setAuthorizedPersonSurname(e.target.value)}
                className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-transparent text-lg"
                placeholder="السعودي"
                dir="rtl"
              />
            </div>
          </div>

          <div>
            <label className="block text-gray-700 font-semibold mb-2">
              رقم جوال الشركة *
            </label>
            <input
              type="tel"
              value={companyPhone}
              onChange={(e) => {
                const value = e.target.value.replace(/[^0-9+\-\s]/g, '')
                if (value.length <= 15) {
                  setCompanyPhone(value)
                }
              }}
              onKeyPress={(e) => {
                if (!/[0-9+\-\s]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab') {
                  e.preventDefault()
                }
              }}
              className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-transparent text-lg"
              placeholder="05XXXXXXXX"
              pattern="^(05|009665)[0-9]{8}$"
              dir="ltr"
            />
            <p className="text-sm text-gray-500 mt-1">مثال: 0501234567</p>
          </div>

          <div>
            <label className="block text-gray-700 font-semibold mb-2">
              البريد الإلكتروني للشركة *
            </label>
            <input
              type="email"
              value={companyEmail}
              onChange={(e) => setCompanyEmail(e.target.value)}
              className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-transparent text-lg"
              placeholder="info@company.com"
              dir="ltr"
            />
          </div>
        </div>

        <div className="flex justify-between mt-8">
          <button
            onClick={() => setCurrentStep('company-services')}
            className="flex items-center px-6 py-3 text-gray-600 hover:text-gray-800 transition-colors"
          >
            <ArrowLeft className="w-5 h-5 ml-2" />
            السابق
          </button>
          
          <button
            onClick={handleNext}
            className="px-8 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-white rounded-xl font-semibold hover:from-gold-600 hover:to-gold-700 transition-all"
          >
            التالي
          </button>
        </div>
      </div>
    )
  }

  // Step 3: Company Location (3 cities max)
  const CompanyLocationStep = () => {
    const [selectedCities, setSelectedCities] = useState<string[]>(formData.serviceCities || [])
    
    const saudiCities = [
      'الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 'الخبر', 'الظهران',
      'تبوك', 'خميس مشيط', 'أبها', 'نجران', 'جازان', 'حائل', 'الطائف', 'القطيف',
      'بريدة', 'عنيزة', 'الرس', 'سكاكا', 'عرعر', 'رفحاء', 'القريات', 'طريف',
      'الباحة', 'بيشة', 'المجمعة', 'الزلفي', 'شقراء', 'الدوادمي', 'الأفلاج',
      'وادي الدواسر', 'الخرج', 'حوطة بني تميم', 'المزاحمية', 'رماح'
    ]

    const handleCityToggle = (city: string) => {
      setSelectedCities(prev => {
        if (prev.includes(city)) {
          return prev.filter(c => c !== city)
        } else if (prev.length < 3) {
          return [...prev, city]
        }
        return prev
      })
    }

    const handleNext = () => {
      if (selectedCities.length === 0) {
        alert('يرجى اختيار مدينة واحدة على الأقل')
        return
      }
      setFormData(prev => ({ ...prev, serviceCities: selectedCities }))
      setCurrentStep('company-documents')
    }

    return (
      <div className="max-w-4xl mx-auto">
        <div className="text-center mb-8">
          <h2 className="text-3xl font-bold text-navy-800 mb-2">
            مناطق الخدمة
          </h2>
          <p className="text-gray-600">
            اختر المدن التي تقدم فيها شركتك الخدمات (حتى 3 مدن) ({selectedCities.length}/3)
          </p>
        </div>

        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8 max-h-96 overflow-y-auto custom-scrollbar">
          {saudiCities.map((city, index) => (
            <div
              key={index}
              onClick={() => handleCityToggle(city)}
              className={`p-4 rounded-xl border-2 cursor-pointer transition-all duration-300 hover:shadow-lg text-center ${
                selectedCities.includes(city)
                  ? 'border-gold-500 bg-gold-50 shadow-md'
                  : 'border-gray-200 hover:border-gold-300'
              }`}
            >
              <div className="flex items-center justify-center">
                <span className="text-gray-800 font-medium">
                  {city}
                </span>
                {selectedCities.includes(city) && (
                  <CheckCircle className="w-5 h-5 text-gold-600 mr-2" />
                )}
              </div>
            </div>
          ))}
        </div>

        <div className="flex justify-between">
          <button
            onClick={() => setCurrentStep('company-personal')}
            className="flex items-center px-6 py-3 text-gray-600 hover:text-gray-800 transition-colors"
          >
            <ArrowLeft className="w-5 h-5 ml-2" />
            السابق
          </button>
          
          <button
            onClick={handleNext}
            disabled={selectedCities.length === 0}
            className={`px-8 py-3 rounded-xl font-semibold transition-all ${
              selectedCities.length === 0
                ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                : 'bg-gradient-to-r from-gold-500 to-gold-600 text-white hover:from-gold-600 hover:to-gold-700'
            }`}
          >
            التالي
          </button>
        </div>
      </div>
    )
  }

  // Step 4: Company Documents
  const CompanyDocumentsStep = () => {
    const [selectedFile, setSelectedFile] = useState<File | null>(formData.companyDocument || null)
    const [dragActive, setDragActive] = useState(false)
    
    const handleFileSelect = (file: File) => {
      const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg']
      const maxSize = 5 * 1024 * 1024 // 5MB
      
      if (!allowedTypes.includes(file.type)) {
        alert('يرجى رفع ملف PDF أو صورة (JPG, PNG)')
        return
      }
      
      if (file.size > maxSize) {
        alert('حجم الملف كبير جداً. يرجى رفع ملف أصغر من 5 ميجابايت')
        return
      }
      
      setSelectedFile(file)
    }

    const handleDrag = (e: React.DragEvent) => {
      e.preventDefault()
      e.stopPropagation()
      if (e.type === 'dragenter' || e.type === 'dragover') {
        setDragActive(true)
      } else if (e.type === 'dragleave') {
        setDragActive(false)
      }
    }

    const handleDrop = (e: React.DragEvent) => {
      e.preventDefault()
      e.stopPropagation()
      setDragActive(false)
      
      if (e.dataTransfer.files && e.dataTransfer.files[0]) {
        handleFileSelect(e.dataTransfer.files[0])
      }
    }

    const handleNext = () => {
      if (!selectedFile) {
        alert('يرجى رفع وثيقة الشركة')
        return
      }
      setFormData(prev => ({ ...prev, companyDocument: selectedFile }))
      setCurrentStep('company-logo')
    }

    return (
      <div className="max-w-2xl mx-auto">
        <div className="text-center mb-8">
          <h2 className="text-3xl font-bold text-navy-800 mb-2">
            وثائق الشركة
          </h2>
          <p className="text-gray-600">
            ارفع وثيقة رسمية للشركة (السجل التجاري، الرخصة التجارية، إلخ)
          </p>
        </div>

        <div 
          className={`border-2 border-dashed rounded-xl p-8 text-center transition-all ${
            dragActive 
              ? 'border-gold-500 bg-gold-50' 
              : selectedFile 
                ? 'border-green-500 bg-green-50'
                : 'border-gray-300 hover:border-gold-400'
          }`}
          onDragEnter={handleDrag}
          onDragLeave={handleDrag}
          onDragOver={handleDrag}
          onDrop={handleDrop}
        >
          {selectedFile ? (
            <div className="space-y-4">
              <FileText className="w-16 h-16 text-green-600 mx-auto" />
              <div>
                <p className="text-lg font-semibold text-green-700">تم اختيار الملف:</p>
                <p className="text-gray-600">{selectedFile.name}</p>
                <p className="text-sm text-gray-500">
                  {(selectedFile.size / 1024 / 1024).toFixed(2)} ميجابايت
                </p>
              </div>
              <button
                onClick={() => setSelectedFile(null)}
                className="text-red-600 hover:text-red-800 transition-colors"
              >
                إزالة الملف
              </button>
            </div>
          ) : (
            <div className="space-y-4">
              <FileText className="w-16 h-16 text-gray-400 mx-auto" />
              <div>
                <p className="text-lg font-semibold text-gray-700 mb-2">
                  اسحب الملف هنا أو اضغط للاختيار
                </p>
                <p className="text-gray-500 text-sm">
                  PDF, JPG, PNG (حتى 5 ميجابايت)
                </p>
              </div>
              <input
                type="file"
                accept=".pdf,.jpg,.jpeg,.png"
                onChange={(e) => e.target.files && handleFileSelect(e.target.files[0])}
                className="hidden"
                id="file-upload"
              />
              <label
                htmlFor="file-upload"
                className="inline-block px-6 py-3 bg-gold-500 text-white rounded-lg hover:bg-gold-600 transition-colors cursor-pointer"
              >
                اختر ملف
              </label>
            </div>
          )}
        </div>

        <div className="flex justify-between mt-8">
          <button
            onClick={() => setCurrentStep('company-location')}
            className="flex items-center px-6 py-3 text-gray-600 hover:text-gray-800 transition-colors"
          >
            <ArrowLeft className="w-5 h-5 ml-2" />
            السابق
          </button>
          
          <button
            onClick={handleNext}
            disabled={!selectedFile}
            className={`px-8 py-3 rounded-xl font-semibold transition-all ${
              !selectedFile
                ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                : 'bg-gradient-to-r from-gold-500 to-gold-600 text-white hover:from-gold-600 hover:to-gold-700'
            }`}
          >
            التالي
          </button>
        </div>
      </div>
    )
  }

  // Step 5: Company Logo (Optional)
  const CompanyLogoStep = () => {
    const [selectedLogo, setSelectedLogo] = useState<File | null>(formData.companyLogo || null)
    const [dragActive, setDragActive] = useState(false)
    
    const handleLogoSelect = (file: File) => {
      const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg']
      const maxSize = 2 * 1024 * 1024 // 2MB
      
      if (!allowedTypes.includes(file.type)) {
        alert('يرجى رفع صورة (JPG, PNG)')
        return
      }
      
      if (file.size > maxSize) {
        alert('حجم الصورة كبير جداً. يرجى رفع صورة أصغر من 2 ميجابايت')
        return
      }
      
      setSelectedLogo(file)
    }

    const handleDrag = (e: React.DragEvent) => {
      e.preventDefault()
      e.stopPropagation()
      if (e.type === 'dragenter' || e.type === 'dragover') {
        setDragActive(true)
      } else if (e.type === 'dragleave') {
        setDragActive(false)
      }
    }

    const handleDrop = (e: React.DragEvent) => {
      e.preventDefault()
      e.stopPropagation()
      setDragActive(false)
      
      if (e.dataTransfer.files && e.dataTransfer.files[0]) {
        handleLogoSelect(e.dataTransfer.files[0])
      }
    }

    const handleNext = () => {
      setFormData(prev => ({ ...prev, companyLogo: selectedLogo }))
      setCurrentStep('company-password')
    }

    const handleSkip = () => {
      setFormData(prev => ({ ...prev, companyLogo: null }))
      setCurrentStep('company-password')
    }

    return (
      <div className="max-w-2xl mx-auto">
        <div className="text-center mb-8">
          <h2 className="text-3xl font-bold text-navy-800 mb-2">
            شعار الشركة
          </h2>
          <p className="text-gray-600">
            ارفع شعار شركتك (اختياري) لعرضه في ملفك التجاري
          </p>
        </div>

        <div 
          className={`border-2 border-dashed rounded-xl p-8 text-center transition-all ${
            dragActive 
              ? 'border-gold-500 bg-gold-50' 
              : selectedLogo 
                ? 'border-green-500 bg-green-50'
                : 'border-gray-300 hover:border-gold-400'
          }`}
          onDragEnter={handleDrag}
          onDragLeave={handleDrag}
          onDragOver={handleDrag}
          onDrop={handleDrop}
        >
          {selectedLogo ? (
            <div className="space-y-4">
              <div className="relative w-32 h-32 mx-auto">
                <img 
                  src={URL.createObjectURL(selectedLogo)}
                  alt="Company Logo Preview"
                  className="w-full h-full object-contain rounded-lg border"
                />
              </div>
              <div>
                <p className="text-lg font-semibold text-green-700">تم اختيار الشعار:</p>
                <p className="text-gray-600">{selectedLogo.name}</p>
                <p className="text-sm text-gray-500">
                  {(selectedLogo.size / 1024 / 1024).toFixed(2)} ميجابايت
                </p>
              </div>
              <button
                onClick={() => setSelectedLogo(null)}
                className="text-red-600 hover:text-red-800 transition-colors"
              >
                إزالة الشعار
              </button>
            </div>
          ) : (
            <div className="space-y-4">
              <Camera className="w-16 h-16 text-gray-400 mx-auto" />
              <div>
                <p className="text-lg font-semibold text-gray-700 mb-2">
                  اسحب الشعار هنا أو اضغط للاختيار
                </p>
                <p className="text-gray-500 text-sm">
                  JPG, PNG (حتى 2 ميجابايت)
                </p>
              </div>
              <input
                type="file"
                accept=".jpg,.jpeg,.png"
                onChange={(e) => e.target.files && handleLogoSelect(e.target.files[0])}
                className="hidden"
                id="logo-upload"
              />
              <label
                htmlFor="logo-upload"
                className="inline-block px-6 py-3 bg-gold-500 text-white rounded-lg hover:bg-gold-600 transition-colors cursor-pointer"
              >
                اختر شعار
              </label>
            </div>
          )}
        </div>

        <div className="flex justify-between mt-8">
          <button
            onClick={() => setCurrentStep('company-documents')}
            className="flex items-center px-6 py-3 text-gray-600 hover:text-gray-800 transition-colors"
          >
            <ArrowLeft className="w-5 h-5 ml-2" />
            السابق
          </button>
          
          <div className="space-x-4 rtl:space-x-reverse">
            <button
              onClick={handleSkip}
              className="px-6 py-3 text-gray-600 hover:text-gray-800 transition-colors"
            >
              تخطي
            </button>
            <button
              onClick={handleNext}
              className="px-8 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-white rounded-xl font-semibold hover:from-gold-600 hover:to-gold-700 transition-all"
            >
              التالي
            </button>
          </div>
        </div>
      </div>
    )
  }

  // Step 6: Company Password
  const CompanyPasswordStep = () => {
    const [password, setPassword] = useState('')
    const [confirmPassword, setConfirmPassword] = useState('')
    const [isMatching, setIsMatching] = useState(false)
    const [isLoading, setIsLoading] = useState(false)

    useEffect(() => {
      setIsMatching(password === confirmPassword && password.length > 0)
    }, [password, confirmPassword])

    const handleCreateAccount = async () => {
      if (password.length < 8) {
        alert('كلمة المرور يجب أن تكون 8 أحرف على الأقل')
        return
      }
      if (password !== confirmPassword) {
        alert('كلمات المرور غير متطابقة')
        return
      }

      setIsLoading(true)
      try {
        // Get complete form data
        const completeFormData = {
          ...formData,
          password,
          confirmPassword
        }
        
        // Prepare registration data for backend
        const registrationData = {
          name: `${completeFormData.companyName}`,
          email: completeFormData.companyEmail,
          phone: completeFormData.companyPhone,
          password: password,
          password_confirmation: confirmPassword,
          role: 'company_provider',
          locale: 'ar',
          terms_accepted: true,
          // Company specific data
          company_name: completeFormData.companyName,
          authorized_person_name: completeFormData.authorizedPersonName,
          authorized_person_surname: completeFormData.authorizedPersonSurname,
          service_cities: completeFormData.serviceCities,
          services: completeFormData.services
        }

        const { default: apiService } = await import('@/services/api')
        
        const response = await apiService.register(registrationData) as any

        if (response.success) {
          // Store authentication data
          apiService.setToken(response.data.token)
          
          // Store complete profile data
          const profileData = {
            userId: response.data.user.id,
            type: 'company',
            companyName: completeFormData.companyName,
            authorizedPersonName: completeFormData.authorizedPersonName,
            authorizedPersonSurname: completeFormData.authorizedPersonSurname,
            phone: completeFormData.companyPhone,
            email: completeFormData.companyEmail,
            serviceCities: completeFormData.serviceCities,
            services: completeFormData.services,
            companyDocument: completeFormData.companyDocument,
            companyLogo: completeFormData.companyLogo
          }
          
          localStorage.setItem('registrationData', JSON.stringify(profileData))
          localStorage.setItem('authUser', JSON.stringify(response.data.user))
          localStorage.removeItem('tempRegistrationData')
          
          alert('تم إنشاء حساب الشركة بنجاح! مرحباً بك في خدمة أب')
          window.location.href = '/ar/dashboard/profile'
        } else {
          throw new Error(response.message || 'حدث خطأ أثناء إنشاء الحساب')
        }
      } catch (error: any) {
        console.error('Registration error:', error)
        
        if (error.message.includes('email') || error.message.includes('duplicate') || error.message.includes('already exists')) {
          const emailExists = error.message.includes('email')
          const existingIdentifier = emailExists ? formData.companyEmail : formData.companyPhone
          const maskedIdentifier = emailExists 
            ? `${existingIdentifier?.slice(0, 2)}***${existingIdentifier?.slice(-2)}`
            : `${existingIdentifier?.slice(0, 2)}***${existingIdentifier?.slice(-2)}`
          
          setShowPasswordResetEmail(maskedIdentifier || '')
          setCurrentStep('password-reset')
        } else if (error.message.includes('validation') || error.message.includes('422')) {
          alert('يرجى التحقق من البيانات المدخلة والمحاولة مرة أخرى')
        } else if (error.message.includes('network') || error.message.includes('fetch')) {
          alert('مشكلة في الاتصال. يرجى التحقق من الإنترنت والمحاولة مرة أخرى')
        } else {
          alert('حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى أو التواصل مع الدعم الفني')
        }
      } finally {
        setIsLoading(false)
      }
    }

    return (
      <div className="max-w-2xl mx-auto">
        <div className="text-center mb-8">
          <h2 className="text-3xl font-bold text-navy-800 mb-2">
            إنشاء كلمة المرور
          </h2>
          <p className="text-gray-600">
            أنشئ كلمة مرور قوية لحماية حساب شركتك
          </p>
        </div>

        <div className="space-y-6">
          <div>
            <label className="block text-gray-700 font-semibold mb-2">
              كلمة المرور *
            </label>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-transparent text-lg"
              placeholder="أدخل كلمة المرور"
              dir="ltr"
            />
            {password.length > 0 && (
              <p className={`text-sm mt-1 ${password.length >= 8 ? 'text-green-600' : 'text-red-600'}`}>
                {password.length >= 8 ? '✓ كلمة المرور قوية' : '⚠ كلمة المرور يجب أن تكون 8 أحرف على الأقل'}
              </p>
            )}
          </div>

          <div>
            <label className="block text-gray-700 font-semibold mb-2">
              تأكيد كلمة المرور *
            </label>
            <input
              type="password"
              value={confirmPassword}
              onChange={(e) => setConfirmPassword(e.target.value)}
              className="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-transparent text-lg"
              placeholder="أعد إدخال كلمة المرور"
              dir="ltr"
            />
            {confirmPassword.length > 0 && (
              <p className={`text-sm mt-1 ${isMatching ? 'text-green-600' : 'text-red-600'}`}>
                {isMatching ? '✓ كلمات المرور متطابقة' : '⚠ كلمات المرور غير متطابقة'}
              </p>
            )}
          </div>
        </div>

        <div className="flex justify-between mt-8">
          <button
            onClick={() => setCurrentStep('company-logo')}
            className="flex items-center px-6 py-3 text-gray-600 hover:text-gray-800 transition-colors"
          >
            <ArrowLeft className="w-5 h-5 ml-2" />
            السابق
          </button>
          
          <button
            onClick={handleCreateAccount}
            disabled={!isMatching || password.length < 8 || isLoading}
            className={`px-8 py-3 rounded-xl font-semibold transition-all relative ${
              !isMatching || password.length < 8 || isLoading
                ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                : 'bg-gradient-to-r from-gold-500 to-gold-600 text-white hover:from-gold-600 hover:to-gold-700'
            }`}
          >
            {isLoading ? (
              <>
                <span className="opacity-0">إنشاء حساب الشركة</span>
                <div className="absolute inset-0 flex items-center justify-center">
                  <div className="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                </div>
              </>
            ) : (
              'إنشاء حساب الشركة'
            )}
          </button>
        </div>
      </div>
    )
  }

  // Password Reset Step
  const PasswordResetStep = () => (
    <div className="max-w-md mx-auto text-center">
      <div className="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <AlertCircle className="w-10 h-10 text-red-600" />
      </div>
      <h2 className="text-3xl font-bold text-navy-800 mb-4">
        البريد الإلكتروني مسجل مسبقاً
      </h2>
      <p className="text-gray-600 mb-6 leading-relaxed">
        يبدو أن لديك حساب بالفعل بهذا البريد الإلكتروني:
      </p>
      <div className="bg-gray-100 p-4 rounded-lg mb-6">
        <p className="font-medium text-gray-800">
          {maskEmail(showPasswordResetEmail)}
        </p>
      </div>
      <div className="space-y-4">
        <button 
          onClick={() => {
            // TODO: Send password reset email
            alert('تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني')
          }}
          className="w-full bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300"
        >
          إرسال رابط إعادة تعيين كلمة المرور
        </button>
        <Link 
          href="/help"
          className="block border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
        >
          الحصول على المساعدة
        </Link>
        <button
          onClick={() => setCurrentStep('type-selection')}
          className="w-full text-gray-500 hover:text-gray-700 transition-colors"
        >
          تجربة بريد إلكتروني آخر
        </button>
      </div>
    </div>
  )

  const SuccessStep = () => (
    <div className="max-w-md mx-auto text-center">
      <div className="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <CheckCircle className="w-10 h-10 text-green-600" />
      </div>
      <h2 className="text-3xl font-bold text-navy-800 mb-4">
        تم إنشاء الحساب بنجاح!
      </h2>
      <p className="text-gray-600 mb-8 leading-relaxed">
        شكراً لانضمامك إلينا. سيتم مراجعة طلبك خلال 24 ساعة وستصلك رسالة تأكيد على البريد الإلكتروني.
      </p>
      <div className="space-y-4">
        <Link 
          href="/provider/dashboard"
          className="block bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300"
        >
          انتقل إلى لوحة التحكم
        </Link>
        <Link 
          href="/"
          className="block border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
        >
          العودة للرئيسية
        </Link>
      </div>
    </div>
  )

  return (
    <div className="min-h-screen bg-gray-50 py-12">
      <div className="container mx-auto px-4">
        {/* Progress Indicator */}
        {providerType === 'individual' && currentStep !== 'type-selection' && currentStep !== 'success' && currentStep !== 'password-reset' && (
          <div className="max-w-2xl mx-auto mb-8">
            <div className="flex items-center justify-between mb-4">
              <div className="flex space-x-2 rtl:space-x-reverse">
                {['الخدمات', 'البيانات الشخصية', 'الموقع', 'كلمة المرور'].map((step, index) => {
                  const stepNames = ['individual-services', 'individual-personal', 'individual-location', 'individual-password']
                  const currentIndex = stepNames.indexOf(currentStep)
                  const isActive = index === currentIndex
                  const isCompleted = index < currentIndex
                  
                  return (
                    <div 
                      key={step}
                      className={`flex items-center ${index < 3 ? 'flex-1' : ''}`}
                    >
                      <div className={`w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold ${
                        isCompleted 
                          ? 'bg-green-500 text-white' 
                          : isActive 
                            ? 'bg-primary-500 text-white'
                            : 'bg-gray-300 text-gray-600'
                      }`}>
                        {isCompleted ? '✓' : index + 1}
                      </div>
                      <span className={`mr-2 text-sm font-medium ${
                        isActive ? 'text-primary-600' : isCompleted ? 'text-green-600' : 'text-gray-500'
                      }`}>
                        {step}
                      </span>
                      {index < 3 && (
                        <div className={`flex-1 h-1 mx-4 rounded ${
                          isCompleted ? 'bg-green-500' : 'bg-gray-300'
                        }`} />
                      )}
                    </div>
                  )
                })}
              </div>
            </div>
          </div>
        )}

        {/* Steps */}
        {currentStep === 'type-selection' && <TypeSelectionStep />}
        {currentStep === 'individual-services' && <IndividualServicesStep />}
        {currentStep === 'individual-personal' && <IndividualPersonalStep />}
        {currentStep === 'individual-location' && <IndividualLocationStep />}
        {currentStep === 'individual-password' && <IndividualPasswordStep />}
        {currentStep === 'company-services' && <CompanyServicesStep />}
        {currentStep === 'company-personal' && <CompanyPersonalStep />}
        {currentStep === 'company-location' && <CompanyLocationStep />}
        {currentStep === 'company-documents' && <CompanyDocumentsStep />}
        {currentStep === 'company-logo' && <CompanyLogoStep />}
        {currentStep === 'company-password' && <CompanyPasswordStep />}
        {currentStep === 'password-reset' && <PasswordResetStep />}
        {currentStep === 'success' && <SuccessStep />}
      </div>
    </div>
  )
}
