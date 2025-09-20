'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'
import Image from 'next/image'
import { Eye, EyeOff, Mail, Phone, Lock, ArrowRight, Loader2 } from 'lucide-react'

interface LoginFormData {
  identifier: string // email or phone
  password: string
}

interface LoginPageProps {
  params: Promise<{ locale: string }>
}

export default function LoginPage({ params }: LoginPageProps) {
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

      // Prepare login data
      const loginData = {
        email: loginType === 'email' ? formData.identifier : `${formData.identifier}@phone.local`,
        password: formData.password
      }

      // If it's a phone login, we might need to handle it differently
      // For now, we'll use email field for both
      const response = await apiService.login({
        email: loginType === 'phone' 
          ? formData.identifier // Backend should handle phone lookup
          : formData.identifier,
        password: formData.password
      }) as any

      if (response.success) {
        // Save token and user data
        apiService.setToken(response.data.token)
        localStorage.setItem('authUser', JSON.stringify(response.data.user))
        
        // Show success message
        alert('تم تسجيل الدخول بنجاح!')
        
        // Redirect based on user role
        if (response.data.user.role === 'individual_provider') {
          window.location.href = `/${locale}/dashboard/provider/profile`
        } else if (response.data.user.role === 'company_provider') {
          window.location.href = `/${locale}/dashboard/business/profile`
        } else if (response.data.user.role === 'customer') {
          window.location.href = `/${locale}/dashboard/client/profile`
        } else {
          window.location.href = `/${locale}/dashboard/client/profile` // Default for unknown roles
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
    <div className="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center pt-32 pb-12 px-4 sm:px-6 lg:px-8" dir={isRTL ? 'rtl' : 'ltr'}>
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
          
          <h2 className="text-3xl font-bold text-navy-800 mb-2">
            اختر نوع الحساب
          </h2>
          <p className="text-gray-600 mb-8">
            حدد نوع حسابك للدخول إلى النظام
          </p>
        </div>

        {/* Role Selection */}
        <div className="space-y-4">
          {/* Provider Login Option */}
          <Link href={`/${locale}/login/provider`}>
            <div className="bg-white rounded-2xl shadow-xl p-8 border border-gray-200 hover:shadow-2xl transition-all duration-300 hover:scale-105 cursor-pointer group">
              <div className="flex items-center justify-between">
                <div className="flex items-center space-x-4 rtl:space-x-reverse">
                  <div className="bg-gradient-to-r from-primary-500 to-primary-600 p-4 rounded-xl text-white group-hover:from-primary-600 group-hover:to-primary-700 transition-all">
                    <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  <div className="text-right">
                    <h3 className="text-xl font-bold text-navy-800 mb-1">
                      دخول مقدمي الخدمة
                    </h3>
                    <p className="text-gray-600">
                      للمتخصصين ومقدمي الخدمات
                    </p>
                  </div>
                </div>
                <ArrowRight className="w-6 h-6 text-gray-400 group-hover:text-primary-600 transition-colors" />
              </div>
            </div>
          </Link>

          {/* Customer Login Option */}
          <Link href={`/${locale}/login/customer`}>
            <div className="bg-white rounded-2xl shadow-xl p-8 border border-gray-200 hover:shadow-2xl transition-all duration-300 hover:scale-105 cursor-pointer group">
              <div className="flex items-center justify-between">
                <div className="flex items-center space-x-4 rtl:space-x-reverse">
                  <div className="bg-gradient-to-r from-gold-500 to-gold-600 p-4 rounded-xl text-white group-hover:from-gold-600 group-hover:to-gold-700 transition-all">
                    <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  <div className="text-right">
                    <h3 className="text-xl font-bold text-navy-800 mb-1">
                      دخول العملاء
                    </h3>
                    <p className="text-gray-600">
                      للعملاء الباحثين عن الخدمات
                    </p>
                  </div>
                </div>
                <ArrowRight className="w-6 h-6 text-gray-400 group-hover:text-gold-600 transition-colors" />
              </div>
            </div>
          </Link>

        </div>

        {/* Footer */}
        <div className="text-center">
          <Link 
            href={`/${locale}`}
            className="text-gray-500 hover:text-gray-700 transition-colors flex items-center justify-center"
          >
            <ArrowRight className="h-4 w-4 ml-2" />
            العودة للرئيسية
          </Link>
        </div>
      </div>
    </div>
  )
}
