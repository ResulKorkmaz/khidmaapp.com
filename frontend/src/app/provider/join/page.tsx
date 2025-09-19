'use client'

import { useEffect } from 'react'
import { useRouter } from 'next/navigation'

export default function ProviderJoinRedirect() {
  const router = useRouter()

  useEffect(() => {
    // Redirect to Arabic version by default
    router.replace('/ar/provider/join')
  }, [router])

  return (
    <div className="min-h-screen bg-gray-50 flex items-center justify-center">
      <div className="text-center">
        <div className="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600 mb-4"></div>
        <p className="text-gray-600">جاري التحويل...</p>
      </div>
    </div>
  )
}
