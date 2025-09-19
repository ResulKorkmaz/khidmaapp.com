import Link from 'next/link'

export default function NotFound() {
  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-50">
      <div className="max-w-md w-full bg-white shadow-lg rounded-lg p-6 text-center">
        <div className="mb-4">
          <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.467-.881-6.072-2.329C3.781 15.016 3 17.408 3 20h18c0-2.592-.781-4.984-2.928-7.329z" />
          </svg>
        </div>
        <h2 className="text-xl font-semibold text-gray-900 mb-2">الصفحة غير موجودة</h2>
        <p className="text-gray-600 mb-4">عذراً، الصفحة التي تبحث عنها غير موجودة.</p>
        <Link 
          href="/"
          className="bg-primary-500 text-white px-4 py-2 rounded-lg hover:bg-primary-600 transition-colors inline-block"
        >
          العودة للرئيسية
        </Link>
      </div>
    </div>
  )
}
