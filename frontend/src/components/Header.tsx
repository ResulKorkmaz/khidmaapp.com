import Image from "next/image"
import Link from "next/link"

export default function Header() {

  return (
    <header className="bg-white shadow-lg border-b-2 border-gold-400">
      <div className="container mx-auto px-4 py-4">
        <div className="flex justify-between items-center">
          {/* Logo */}
          <Link href="/" className="flex items-center">
            <div className="bg-primary-500 p-3 rounded-lg shadow-md hover:bg-primary-600 transition-colors">
              <Image 
                src="/logo.png" 
                alt="خدمة أب (KhidmaApp) Logo" 
                width={150} 
                height={60} 
                className="h-12 w-auto"
                priority
              />
            </div>
          </Link>

          {/* Navigation */}
          <nav className="hidden md:flex items-center space-x-8 rtl:space-x-reverse">
            <Link 
              href="/" 
              className="text-navy-700 hover:text-primary-600 font-medium transition-colors"
            >
              الرئيسية
            </Link>
            <Link 
              href="/services" 
              className="text-navy-700 hover:text-primary-600 font-medium transition-colors"
            >
              الخدمات
            </Link>
            <Link 
              href="/providers" 
              className="text-navy-700 hover:text-primary-600 font-medium transition-colors"
            >
              مقدمو الخدمات
            </Link>
            <Link 
              href="/about" 
              className="text-navy-700 hover:text-primary-600 font-medium transition-colors"
            >
              من نحن
            </Link>
            <Link 
              href="/contact" 
              className="text-navy-700 hover:text-primary-600 font-medium transition-colors"
            >
              اتصل بنا
            </Link>
          </nav>

          {/* Auth Buttons */}
          <div className="flex items-center space-x-4 rtl:space-x-reverse">
            <Link 
              href="/ar/login" 
              className="text-navy-700 hover:text-primary-600 font-medium transition-colors"
            >
              تسجيل الدخول
            </Link>
            <Link 
              href="/ar/provider/join" 
              className="bg-primary-500 text-white px-6 py-2 rounded-lg hover:bg-primary-600 transition-colors border-2 border-gold-400 hover:border-gold-300 font-semibold"
            >
              انضم كمقدم خدمة
            </Link>
          </div>

          {/* Mobile Menu Button */}
          <button className="md:hidden flex items-center px-3 py-2 border rounded text-navy-700 border-navy-300 hover:text-primary-600 hover:border-primary-300">
            <svg className="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <title>Menu</title>
              <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/>
            </svg>
          </button>
        </div>
      </div>
    </header>
  )
}
