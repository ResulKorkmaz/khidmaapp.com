'use client'

import { useEffect } from 'react'

export default function AuthLoginRedirect() {
  useEffect(() => {
    // Redirect to the proper locale-based login page
    window.location.href = '/ar/login'
  }, [])

  return (
    <div className="min-h-screen bg-gray-50 flex items-center justify-center">
      <div className="text-center">
        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-500 mx-auto mb-4"></div>
        <p className="text-gray-600">جاري التحويل...</p>
      </div>
    </div>
  )
}
