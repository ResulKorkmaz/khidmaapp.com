'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'
import Image from 'next/image'
import { Eye, EyeOff, Mail, Phone, Lock, ArrowRight, Loader2, Briefcase } from 'lucide-react'

interface LoginFormData {
  identifier: string // email or phone
  password: string
}

interface ProviderLoginPageProps {
  params: Promise<{ locale: string }>
}

export default function ProviderLoginPage({ params }: ProviderLoginPageProps) {
  const [locale, setLocale] = useState('ar')
  const [loginType, setLoginType] = useState<'email' | 'phone'>('email')
  const [formData, setFormData] = useState<LoginFormData>({
    identifier: '',
    password: ''
  })
  const [showPassword, setShowPassword] = useState(false)
  const [isLoading, setIsLoading] = useState(false)
  const [errors, setErrors] = useState<Record<string, string>>({})

  // Resolve params in useEffect
  useEffect(() => {
    params.then((resolvedParams) => {
      setLocale(resolvedParams.locale)
    })
  }, [params])

  const isRTL = locale === 'ar'

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

  // Detect if input is email or phone
  const detectLoginType = (value: string) => {
    if (value.includes('@')) {
      setLoginType('email')
    } else if (value.startsWith('05') || value.startsWith('009665') || /^[0-9]/.test(value)) {
      setLoginType('phone')
    }
  }

  // Handle input changes
  const handleInputChange = (field: keyof LoginFormData, value: string) => {
    setFormData(prev => ({ ...prev, [field]: value }))
    
    // Clear errors when user starts typing
    if (errors[field]) {
      setErrors(prev => ({ ...prev, [field]: '' }))
    }

    // Auto-detect login type for identifier field
    if (field === 'identifier') {
      detectLoginType(value)
    }
  }

  // Validate form
  const validateForm = (): boolean => {
    const newErrors: Record<string, string> = {}

    // Validate identifier (email or phone)
    if (!formData.identifier.trim()) {
      newErrors.identifier = 'البريد الإلكتروني أو رقم الجوال مطلوب'
    } else if (loginType === 'email' && !isValidEmail(formData.identifier)) {
      newErrors.identifier = 'البريد الإلكتروني غير صحيح'
    } else if (loginType === 'phone' && !isValidSaudiPhone(formData.identifier)) {
      newErrors.identifier = 'رقم الجوال غير صحيح. مثال: 0501234567'
    }

    // Validate password
    if (!formData.password) {
      newErrors.password = 'كلمة المرور مطلوبة'
    }

    setErrors(newErrors)
    return Object.keys(newErrors).length === 0
  }

  // Handle login submission
  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault()

    if (!validateForm()) {
      return
    }

    setIsLoading(true)

    try {
      // Import API service dynamically
      const { default: apiService } = await import('@/services/api')

      // Send login request
      const response = await apiService.login({
        email: formData.identifier,
        password: formData.password
      }) as any

      if (response.success) {
        // Check if user is a provider
        if (!['individual_provider', 'company_provider', 'provider'].includes(response.data.user.role)) {
          alert('هذا الحساب ليس حساب مقدم خدمة. يرجى استخدام دخول العملاء.')
          return
        }

        // Save token and user data
        apiService.setToken(response.data.token)
        localStorage.setItem('authUser', JSON.stringify(response.data.user))
        
        // Show success message
        alert('تم تسجيل الدخول بنجاح!')
        
        // Redirect based on provider type
        if (response.data.user.role === 'individual_provider') {
          window.location.href = `/${locale}/dashboard/provider/profile`
        } else if (response.data.user.role === 'company_provider') {
          window.location.href = `/${locale}/dashboard/business/profile`
        } else {
          window.location.href = `/${locale}/dashboard/provider/profile` // Default for individual
        }
      } else {
        throw new Error(response.message || 'خطأ في تسجيل الدخول')
      }

    } catch (error: any) {
      console.error('Login error:', error)
      
      // Handle different error types
      if (error.message?.includes('401') || error.message?.includes('unauthorized')) {
        setErrors({ password: 'البريد الإلكتروني أو كلمة المرور غير صحيحة' })
      } else if (error.message?.includes('network') || error.message?.includes('fetch')) {
        alert('خطأ في الاتصال. يرجى التأكد من اتصال الإنترنت والمحاولة مجدداً.')
      } else {
        alert(`خطأ في تسجيل الدخول: ${error.message}`)
      }
    } finally {
      setIsLoading(false)
    }
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center pt-32 pb-12 px-4 sm:px-6 lg:px-8" dir={isRTL ? 'rtl' : 'ltr'}>
      <div className="max-w-md w-full space-y-8">
        {/* Header */}
        <div className="text-center">
          {/* Logo */}
          <Link href={`/${locale}`} className="inline-block mb-6">
            <div className="bg-primary-500 p-4 rounded-xl shadow-lg hover:bg-primary-600 transition-colors">
              <Image 
                src="/logo.png" 
                alt="خدمة أب (KhidmaApp) Logo" 
                width={120} 
                height={48} 
                className="h-12 w-auto"
                priority
              />
            </div>
          </Link>
          
          {/* Provider Icon */}
          <div className="mb-4">
            <div className="bg-primary-500 p-3 rounded-full inline-block">
              <Briefcase className="w-8 h-8 text-white" />
            </div>
          </div>
          
          <h2 className="text-3xl font-bold text-navy-800 mb-2">
            دخول مقدمي الخدمة
          </h2>
          <p className="text-gray-600 mb-8">
            ادخل بياناتك للوصول إلى لوحة التحكم
          </p>
        </div>

        {/* Login Form */}
        <div className="bg-white rounded-2xl shadow-xl p-8 border border-gray-200">
          <form onSubmit={handleLogin} className="space-y-6">
            {/* Email/Phone Input */}
            <div>
              <label className="block text-gray-700 font-semibold mb-2">
                البريد الإلكتروني أو رقم الجوال *
              </label>
              <div className="relative">
                <div className="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                  {loginType === 'email' ? (
                    <Mail className="h-5 w-5 text-gray-400" />
                  ) : (
                    <Phone className="h-5 w-5 text-gray-400" />
                  )}
                </div>
                <input
                  type={loginType === 'email' ? 'email' : 'tel'}
                  value={formData.identifier}
                  onChange={(e) => handleInputChange('identifier', e.target.value)}
                  className={`w-full px-4 py-4 pr-12 border rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg ${
                    errors.identifier ? 'border-red-500' : 'border-gray-300'
                  }`}
                  placeholder={loginType === 'email' ? 'example@email.com' : '0501234567'}
                  dir={loginType === 'email' ? 'ltr' : 'ltr'}
                />
              </div>
              {errors.identifier && (
                <p className="text-red-500 text-sm mt-1">{errors.identifier}</p>
              )}
              <div className="flex justify-between items-center mt-2">
                <p className="text-xs text-gray-500">
                  {loginType === 'email' ? 'يتم تحديد نوع الدخول تلقائياً' : 'رقم جوال سعودي'}
                </p>
                <span className={`text-xs px-2 py-1 rounded-full ${
                  loginType === 'email' 
                    ? 'bg-blue-100 text-blue-600' 
                    : 'bg-green-100 text-green-600'
                }`}>
                  {loginType === 'email' ? 'بريد إلكتروني' : 'رقم جوال'}
                </span>
              </div>
            </div>

            {/* Password Input */}
            <div>
              <label className="block text-gray-700 font-semibold mb-2">
                كلمة المرور *
              </label>
              <div className="relative">
                <div className="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                  <Lock className="h-5 w-5 text-gray-400" />
                </div>
                <input
                  type={showPassword ? 'text' : 'password'}
                  value={formData.password}
                  onChange={(e) => handleInputChange('password', e.target.value)}
                  className={`w-full px-4 py-4 pr-12 pl-12 border rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg ${
                    errors.password ? 'border-red-500' : 'border-gray-300'
                  }`}
                  placeholder="ادخل كلمة المرور"
                  dir="ltr"
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute inset-y-0 left-0 pl-3 flex items-center"
                >
                  {showPassword ? (
                    <EyeOff className="h-5 w-5 text-gray-400 hover:text-gray-600" />
                  ) : (
                    <Eye className="h-5 w-5 text-gray-400 hover:text-gray-600" />
                  )}
                </button>
              </div>
              {errors.password && (
                <p className="text-red-500 text-sm mt-1">{errors.password}</p>
              )}
            </div>

            {/* Forgot Password */}
            <div className="flex justify-between items-center">
              <Link 
                href={`/${locale}/forgot-password`}
                className="text-sm text-primary-600 hover:text-primary-500 transition-colors"
              >
                نسيت كلمة المرور؟
              </Link>
            </div>

            {/* Login Button */}
            <button
              type="submit"
              disabled={isLoading}
              className={`w-full flex justify-center items-center px-6 py-4 rounded-xl text-white font-semibold text-lg transition-all duration-300 ${
                isLoading 
                  ? 'bg-gray-400 cursor-not-allowed' 
                  : 'bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 shadow-lg hover:shadow-xl'
              }`}
            >
              {isLoading ? (
                <>
                  <Loader2 className="animate-spin h-5 w-5 ml-2" />
                  جاري تسجيل الدخول...
                </>
              ) : (
                <>
                  دخول مقدم الخدمة
                  <ArrowRight className="h-5 w-5 mr-2" />
                </>
              )}
            </button>
          </form>

          {/* Register Link */}
          <div className="mt-8 text-center">
            <p className="text-gray-600">
              ليس لديك حساب؟{' '}
              <Link 
                href={`/${locale}/provider/join`}
                className="text-primary-600 hover:text-primary-500 font-semibold transition-colors"
              >
                انضم كمقدم خدمة
              </Link>
            </p>
          </div>
        </div>

        {/* Footer */}
        <div className="text-center space-y-2">
          <Link 
            href={`/${locale}/login`}
            className="text-gray-500 hover:text-gray-700 transition-colors flex items-center justify-center"
          >
            <ArrowRight className="h-4 w-4 ml-2" />
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
