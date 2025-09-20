'use client'

import { useState, useEffect } from 'react'
import { ArrowRight, ArrowLeft, Mail, Phone, Lock, User, MapPin, CheckCircle, Loader2, Eye, EyeOff } from 'lucide-react'
import Link from 'next/link'

interface CustomerJoinPageProps {
  params: Promise<{ locale: string }>
}

interface CustomerFormData {
  firstName: string
  lastName: string
  email: string
  phone: string
  city: string
  password: string
  confirmPassword: string
}

interface ValidationErrors {
  firstName?: string
  lastName?: string
  email?: string
  phone?: string
  city?: string
  password?: string
  confirmPassword?: string
}

export default function CustomerJoinPage({ params }: CustomerJoinPageProps) {
  const [locale, setLocale] = useState('ar')
  const [formData, setFormData] = useState<CustomerFormData>({
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    city: '',
    password: '',
    confirmPassword: ''
  })
  const [errors, setErrors] = useState<ValidationErrors>({})
  const [isLoading, setIsLoading] = useState(false)
  const [showPassword, setShowPassword] = useState(false)
  const [showConfirmPassword, setShowConfirmPassword] = useState(false)
  const [isSuccess, setIsSuccess] = useState(false)

  // Resolve params in useEffect
  useEffect(() => {
    params.then((resolvedParams) => {
      setLocale(resolvedParams.locale)
    })
  }, [params])

  const isRTL = locale === 'ar'

  // Saudi cities
  const saudiCities = [
    'الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 'الخبر', 
    'الطائف', 'بريدة', 'تبوك', 'خميس مشيط', 'حائل', 'الجبيل', 
    'الخرج', 'أبها', 'ينبع', 'القطيف', 'الأحساء', 'نجران'
  ]

  // Validate email format
  const isValidEmail = (email: string) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return emailRegex.test(email)
  }

  // Validate Saudi phone format
  const isValidSaudiPhone = (phone: string) => {
    const phoneRegex = /^(05|009665)[0-9]{8}$/
    return phoneRegex.test(phone.replace(/[\s\-]/g, ''))
  }

  // Handle input changes
  const handleInputChange = (field: keyof CustomerFormData, value: string) => {
    setFormData(prev => ({ ...prev, [field]: value }))
    
    // Clear errors when user starts typing
    if (errors[field]) {
      setErrors(prev => ({ ...prev, [field]: '' }))
    }
  }

  // Validate form
  const validateForm = (): boolean => {
    const newErrors: ValidationErrors = {}

    // First Name validation
    if (!formData.firstName.trim()) {
      newErrors.firstName = 'الاسم الأول مطلوب'
    } else if (formData.firstName.trim().length < 2) {
      newErrors.firstName = 'الاسم الأول يجب أن يكون على الأقل حرفين'
    }

    // Last Name validation
    if (!formData.lastName.trim()) {
      newErrors.lastName = 'اسم العائلة مطلوب'
    } else if (formData.lastName.trim().length < 2) {
      newErrors.lastName = 'اسم العائلة يجب أن يكون على الأقل حرفين'
    }

    // Email validation
    if (!formData.email.trim()) {
      newErrors.email = 'البريد الإلكتروني مطلوب'
    } else if (!isValidEmail(formData.email)) {
      newErrors.email = 'صيغة البريد الإلكتروني غير صحيحة'
    }

    // Phone validation
    if (!formData.phone.trim()) {
      newErrors.phone = 'رقم الهاتف مطلوب'
    } else if (!isValidSaudiPhone(formData.phone)) {
      newErrors.phone = 'رقم الهاتف يجب أن يبدأ بـ 05 ويكون 10 أرقام'
    }

    // City validation
    if (!formData.city) {
      newErrors.city = 'المدينة مطلوبة'
    }

    // Password validation
    if (!formData.password) {
      newErrors.password = 'كلمة المرور مطلوبة'
    } else if (formData.password.length < 8) {
      newErrors.password = 'كلمة المرور يجب أن تكون على الأقل 8 أحرف'
    }

    // Confirm Password validation
    if (!formData.confirmPassword) {
      newErrors.confirmPassword = 'تأكيد كلمة المرور مطلوب'
    } else if (formData.password !== formData.confirmPassword) {
      newErrors.confirmPassword = 'كلمات المرور غير متطابقة'
    }

    setErrors(newErrors)
    return Object.keys(newErrors).length === 0
  }

  // Handle form submission
  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    
    if (!validateForm()) return

    setIsLoading(true)

    try {
      // Import API service dynamically
      const { default: apiService } = await import('@/services/api')

      // Prepare data for backend API
      const registrationData = {
        name: `${formData.firstName} ${formData.lastName}`,
        email: formData.email,
        phone: formData.phone,
        password: formData.password,
        password_confirmation: formData.confirmPassword,
        role: 'customer',
        city: formData.city,
        locale: 'ar',
        terms_accepted: true
      }

      // Send registration to backend
      const response = await apiService.register(registrationData) as any

      if (response.success) {
        // Save token for authentication
        apiService.setToken(response.data.token)

        // Save user data for future use
        const userData = {
          id: response.data.user.id,
          name: response.data.user.name,
          first_name: formData.firstName,  // Separate first name
          last_name: formData.lastName,    // Separate last name  
          email: response.data.user.email,
          phone: response.data.user.phone,
          role: response.data.user.role,
          city: formData.city,
          locale: response.data.user.locale,
          is_verified: response.data.user.is_verified
        }

        // Save to localStorage
        localStorage.setItem('authUser', JSON.stringify(userData))
        localStorage.setItem('authToken', response.data.token)

        // Save customer registration data
        const customerData = {
          ...formData,
          userId: response.data.user.id,
          registrationDate: new Date().toISOString()
        }
        localStorage.setItem('customerRegistrationData', JSON.stringify(customerData))

        setIsSuccess(true)

        // Redirect after success animation
        setTimeout(() => {
          window.location.href = `/${locale}/dashboard/client/profile`
        }, 2000)

      } else {
        console.error('Registration failed:', response)
        alert(response.message || 'فشل في إنشاء الحساب. يرجى المحاولة مرة أخرى')
      }
    } catch (error: any) {
      console.error('Registration error:', error)
      alert('حدث خطأ في الاتصال. يرجى التحقق من الإنترنت والمحاولة مرة أخرى')
    } finally {
      setIsLoading(false)
    }
  }

  // Success Animation Component
  if (isSuccess) {
    return (
      <div className="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-100 flex items-center justify-center" dir="rtl">
        <div className="bg-white rounded-3xl shadow-2xl p-8 text-center max-w-md mx-4 animate-in zoom-in duration-500">
          <div className="mb-6">
            <div className="w-20 h-20 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
              <CheckCircle className="w-10 h-10 text-white" />
            </div>
            <h2 className="text-2xl font-bold text-gray-800 mb-2">تم إنشاء حسابك بنجاح!</h2>
            <p className="text-gray-600">مرحباً بك في منصة خدمة أب</p>
          </div>
          <div className="flex justify-center">
            <Loader2 className="animate-spin h-6 w-6 text-green-500" />
          </div>
        </div>
      </div>
    )
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-green-50 via-white to-blue-50" dir={isRTL ? 'rtl' : 'ltr'}>
      <div className="container mx-auto px-4 pt-28 pb-8">
        {/* Header */}
        <div className="text-center mb-8">
          <div className="flex items-center justify-center mb-6">
            <div className="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
              <User className="w-8 h-8 text-white" />
            </div>
          </div>
          <h1 className="text-3xl lg:text-4xl font-bold text-gray-800 mb-2">
            انضم كعميل
          </h1>
          <p className="text-gray-600 text-lg">
            أنشئ حساباً جديداً للوصول إلى أفضل مقدمي الخدمات
          </p>
        </div>

        {/* Registration Form */}
        <div className="max-w-2xl mx-auto">
          <div className="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-gray-100/50 p-8">
            <form onSubmit={handleSubmit} className="space-y-6">
              {/* Name Fields */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                {/* First Name */}
                <div>
                  <label className="block text-sm font-semibold text-gray-700 mb-2">
                    الاسم الأول
                  </label>
                  <div className="relative">
                    <User className="absolute top-1/2 transform -translate-y-1/2 right-4 h-5 w-5 text-gray-400" />
                    <input
                      type="text"
                      value={formData.firstName}
                      onChange={(e) => handleInputChange('firstName', e.target.value)}
                      className={`w-full pr-12 pl-4 py-4 border-2 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none transition-all duration-300 ${
                        errors.firstName 
                          ? 'border-red-300 focus:border-red-500 bg-red-50' 
                          : 'border-gray-200 focus:border-green-500 hover:border-gray-300'
                      }`}
                      placeholder="أدخل اسمك الأول"
                    />
                  </div>
                  {errors.firstName && (
                    <p className="text-red-500 text-sm mt-2">{errors.firstName}</p>
                  )}
                </div>

                {/* Last Name */}
                <div>
                  <label className="block text-sm font-semibold text-gray-700 mb-2">
                    اسم العائلة
                  </label>
                  <div className="relative">
                    <User className="absolute top-1/2 transform -translate-y-1/2 right-4 h-5 w-5 text-gray-400" />
                    <input
                      type="text"
                      value={formData.lastName}
                      onChange={(e) => handleInputChange('lastName', e.target.value)}
                      className={`w-full pr-12 pl-4 py-4 border-2 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none transition-all duration-300 ${
                        errors.lastName 
                          ? 'border-red-300 focus:border-red-500 bg-red-50' 
                          : 'border-gray-200 focus:border-green-500 hover:border-gray-300'
                      }`}
                      placeholder="أدخل اسم عائلتك"
                    />
                  </div>
                  {errors.lastName && (
                    <p className="text-red-500 text-sm mt-2">{errors.lastName}</p>
                  )}
                </div>
              </div>

              {/* Contact Fields */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                {/* Email */}
                <div>
                  <label className="block text-sm font-semibold text-gray-700 mb-2">
                    البريد الإلكتروني
                  </label>
                  <div className="relative">
                    <Mail className="absolute top-1/2 transform -translate-y-1/2 right-4 h-5 w-5 text-gray-400" />
                    <input
                      type="email"
                      value={formData.email}
                      onChange={(e) => handleInputChange('email', e.target.value)}
                      className={`w-full pr-12 pl-4 py-4 border-2 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none transition-all duration-300 ${
                        errors.email 
                          ? 'border-red-300 focus:border-red-500 bg-red-50' 
                          : 'border-gray-200 focus:border-green-500 hover:border-gray-300'
                      }`}
                      placeholder="example@email.com"
                    />
                  </div>
                  {errors.email && (
                    <p className="text-red-500 text-sm mt-2">{errors.email}</p>
                  )}
                </div>

                {/* Phone */}
                <div>
                  <label className="block text-sm font-semibold text-gray-700 mb-2">
                    رقم الهاتف
                  </label>
                  <div className="relative">
                    <Phone className="absolute top-1/2 transform -translate-y-1/2 right-4 h-5 w-5 text-gray-400" />
                    <input
                      type="tel"
                      value={formData.phone}
                      onChange={(e) => handleInputChange('phone', e.target.value)}
                      className={`w-full pr-12 pl-4 py-4 border-2 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none transition-all duration-300 ${
                        errors.phone 
                          ? 'border-red-300 focus:border-red-500 bg-red-50' 
                          : 'border-gray-200 focus:border-green-500 hover:border-gray-300'
                      }`}
                      placeholder="05xxxxxxxx"
                    />
                  </div>
                  {errors.phone && (
                    <p className="text-red-500 text-sm mt-2">{errors.phone}</p>
                  )}
                </div>
              </div>

              {/* City */}
              <div>
                <label className="block text-sm font-semibold text-gray-700 mb-2">
                  المدينة
                </label>
                <div className="relative">
                  <MapPin className="absolute top-1/2 transform -translate-y-1/2 right-4 h-5 w-5 text-gray-400" />
                  <select
                    value={formData.city}
                    onChange={(e) => handleInputChange('city', e.target.value)}
                    className={`w-full pr-12 pl-4 py-4 border-2 rounded-xl text-gray-800 focus:outline-none transition-all duration-300 ${
                      errors.city 
                        ? 'border-red-300 focus:border-red-500 bg-red-50' 
                        : 'border-gray-200 focus:border-green-500 hover:border-gray-300'
                    }`}
                  >
                    <option value="">اختر مدينتك</option>
                    {saudiCities.map((city) => (
                      <option key={city} value={city}>{city}</option>
                    ))}
                  </select>
                </div>
                {errors.city && (
                  <p className="text-red-500 text-sm mt-2">{errors.city}</p>
                )}
              </div>

              {/* Password Fields */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                {/* Password */}
                <div>
                  <label className="block text-sm font-semibold text-gray-700 mb-2">
                    كلمة المرور
                  </label>
                  <div className="relative">
                    <Lock className="absolute top-1/2 transform -translate-y-1/2 right-4 h-5 w-5 text-gray-400" />
                    <input
                      type={showPassword ? 'text' : 'password'}
                      value={formData.password}
                      onChange={(e) => handleInputChange('password', e.target.value)}
                      className={`w-full pr-12 pl-12 py-4 border-2 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none transition-all duration-300 ${
                        errors.password 
                          ? 'border-red-300 focus:border-red-500 bg-red-50' 
                          : 'border-gray-200 focus:border-green-500 hover:border-gray-300'
                      }`}
                      placeholder="أدخل كلمة المرور"
                    />
                    <button
                      type="button"
                      onClick={() => setShowPassword(!showPassword)}
                      className="absolute top-1/2 transform -translate-y-1/2 left-4 text-gray-400 hover:text-gray-600"
                    >
                      {showPassword ? <EyeOff className="h-5 w-5" /> : <Eye className="h-5 w-5" />}
                    </button>
                  </div>
                  {errors.password && (
                    <p className="text-red-500 text-sm mt-2">{errors.password}</p>
                  )}
                </div>

                {/* Confirm Password */}
                <div>
                  <label className="block text-sm font-semibold text-gray-700 mb-2">
                    تأكيد كلمة المرور
                  </label>
                  <div className="relative">
                    <Lock className="absolute top-1/2 transform -translate-y-1/2 right-4 h-5 w-5 text-gray-400" />
                    <input
                      type={showConfirmPassword ? 'text' : 'password'}
                      value={formData.confirmPassword}
                      onChange={(e) => handleInputChange('confirmPassword', e.target.value)}
                      className={`w-full pr-12 pl-12 py-4 border-2 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none transition-all duration-300 ${
                        errors.confirmPassword 
                          ? 'border-red-300 focus:border-red-500 bg-red-50' 
                          : 'border-gray-200 focus:border-green-500 hover:border-gray-300'
                      }`}
                      placeholder="أعد إدخال كلمة المرور"
                    />
                    <button
                      type="button"
                      onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                      className="absolute top-1/2 transform -translate-y-1/2 left-4 text-gray-400 hover:text-gray-600"
                    >
                      {showConfirmPassword ? <EyeOff className="h-5 w-5" /> : <Eye className="h-5 w-5" />}
                    </button>
                  </div>
                  {errors.confirmPassword && (
                    <p className="text-red-500 text-sm mt-2">{errors.confirmPassword}</p>
                  )}
                </div>
              </div>

              {/* Submit Button */}
              <button
                type="submit"
                disabled={isLoading}
                className={`w-full flex justify-center items-center px-6 py-4 rounded-xl text-white font-bold text-lg transition-all duration-300 ${
                  isLoading 
                    ? 'bg-gray-400 cursor-not-allowed' 
                    : 'bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 shadow-lg hover:shadow-xl hover:scale-105'
                }`}
              >
                {isLoading ? (
                  <>
                    <Loader2 className="animate-spin h-5 w-5 ml-2" />
                    جاري إنشاء الحساب...
                  </>
                ) : (
                  <>
                    إنشاء حساب عميل
                    <ArrowRight className="h-5 w-5 mr-2" />
                  </>
                )}
              </button>
            </form>

            {/* Login Link */}
            <div className="mt-8 text-center">
              <p className="text-gray-600">
                لديك حساب بالفعل؟{' '}
                <Link 
                  href={`/${locale}/login/customer`}
                  className="text-green-600 hover:text-green-500 font-semibold transition-colors"
                >
                  تسجيل الدخول
                </Link>
              </p>
            </div>
          </div>
        </div>

        {/* Footer */}
        <div className="text-center mt-8 space-y-2">
          <Link 
            href={`/${locale}/login`}
            className="text-gray-500 hover:text-gray-700 transition-colors flex items-center justify-center"
          >
            <ArrowLeft className="h-4 w-4 ml-2" />
            العودة لاختيار نوع الحساب
          </Link>
          <Link 
            href={`/${locale}`}
            className="text-gray-400 hover:text-gray-600 transition-colors text-sm"
          >
            العودة للرئيسية
          </Link>
        </div>
      </div>
    </div>
  )
}
