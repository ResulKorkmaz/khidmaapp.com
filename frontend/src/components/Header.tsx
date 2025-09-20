'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'
import { 
  Bars3Icon, 
  XMarkIcon
} from '@heroicons/react/24/outline'
import Image from 'next/image'

export default function Header() {
  const [isMenuOpen, setIsMenuOpen] = useState(false)
  const [isScrolled, setIsScrolled] = useState(false)
  
  // Fixed locale for now - you can make this dynamic later
  const locale = 'ar'
  const isRTL = locale === 'ar'

  // Handle scroll effect
  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 20)
    }

    window.addEventListener('scroll', handleScroll)
    return () => window.removeEventListener('scroll', handleScroll)
  }, [])

  // Handle scroll to search section - works on any page
  const handleScrollToSearch = () => {
    // If we're on homepage, scroll directly
    const searchElement = document.getElementById('search-section')
    if (searchElement) {
      searchElement.scrollIntoView({ behavior: 'smooth' })
    } else {
      // If not on homepage, navigate to homepage with hash
      window.location.href = `/${locale}#search-section`
    }
  }

  // Handle scroll to how it works section - works on any page
  const handleScrollToHowItWorks = () => {
    // If we're on homepage, scroll directly
    const howItWorksElement = document.getElementById('how-it-works-section')
    if (howItWorksElement) {
      howItWorksElement.scrollIntoView({ behavior: 'smooth' })
    } else {
      // If not on homepage, navigate to homepage with hash
      window.location.href = `/${locale}#how-it-works-section`
    }
  }

  // Main navigation items
  const navigationItems: Array<{name: string, href: string, onClick?: () => void}> = [
    { name: 'اطلب خدمة', href: '#', onClick: handleScrollToSearch },
    { name: 'كيف يعمل؟', href: '#', onClick: handleScrollToHowItWorks },
  ]

  return (
    <header className="fixed top-0 left-0 right-0 z-50">
      <nav 
        className={`
          transition-all duration-500 ease-out
          ${isScrolled 
            ? 'bg-white/95 backdrop-blur-xl shadow-2xl shadow-green-500/10 border-b border-green-100/50' 
            : 'bg-gradient-to-r from-transparent via-white/10 to-transparent backdrop-blur-md'
          }
        `}
      >
        <div className="max-w-7xl mx-auto px-6 lg:px-8">
          <div className="flex items-center justify-between h-20 lg:h-24">
            
            {/* Logo Section - Enhanced */}
            <div className="flex items-center">
              <Link href={`/${locale}`} className="group">
                <div className="relative">
                  <div className={`
                    w-16 h-16 lg:w-18 lg:h-18 
                    bg-gradient-to-br from-green-500 via-green-600 to-green-700
                    rounded-2xl shadow-2xl shadow-green-500/30
                    flex items-center justify-center
                    transition-all duration-300
                    group-hover:scale-105 group-hover:rotate-2 group-hover:shadow-green-500/50
                    ring-4 ring-white/20 group-hover:ring-green-300/30
                  `}>
                    <Image
                      src="/logo.png"
                      alt="Khidmaapp"
                      width={48}
                      height={48}
                      className="transition-all duration-300 group-hover:scale-110"
                      priority
                    />
                  </div>
                  {/* Logo glow effect */}
                  <div className="absolute inset-0 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl blur opacity-20 group-hover:opacity-40 transition-opacity duration-300 -z-10" />
                </div>
              </Link>
            </div>

            {/* Center Navigation - Desktop */}
            <div className="hidden lg:flex items-center justify-center flex-1 max-w-md mx-8">
              <div className={`
                flex items-center space-x-2 
                ${isRTL ? 'space-x-reverse' : ''}
                bg-white/80 backdrop-blur-lg rounded-2xl p-2
                shadow-xl shadow-green-500/10 border border-green-100/30
              `}>
                {navigationItems.map((item, index) => (
                  <button
                    key={item.name}
                    onClick={item.onClick || (() => window.location.href = item.href)}
                    className={`
                      px-6 py-3 rounded-xl font-bold text-base
                      transition-all duration-300
                      ${isScrolled 
                        ? 'text-gray-700 hover:text-green-600 hover:bg-green-50' 
                        : 'text-gray-800 hover:text-green-600 hover:bg-white/60'
                      }
                      hover:scale-105 hover:shadow-lg hover:shadow-green-500/20
                      active:scale-95
                    `}
                  >
                    {item.name}
                  </button>
                ))}
              </div>
            </div>

            {/* Right Actions - Enhanced */}
            <div className="hidden lg:flex items-center space-x-3">
              
              {/* Login Button - Minimal Style */}
              <Link
                href={`/${locale}/login`}
                className={`
                  px-5 py-2.5 rounded-xl font-bold text-sm
                  transition-all duration-300
                  ${isScrolled 
                    ? 'text-gray-600 hover:text-green-600 hover:bg-green-50' 
                    : 'text-gray-700 hover:text-green-600 hover:bg-white/60'
                  }
                  hover:scale-105 active:scale-95
                `}
              >
                تسجيل الدخول
              </Link>
              
              {/* Customer Register Button - Premium Style */}
              <Link
                href={`/${locale}/customer/join`}
                className={`
                  px-5 py-2.5 rounded-xl font-bold text-sm
                  bg-gradient-to-r from-green-500 to-green-600 text-white
                  border-2 border-yellow-400 
                  shadow-lg shadow-green-500/30
                  transition-all duration-300
                  hover:from-green-600 hover:to-green-700 
                  hover:border-yellow-300 hover:scale-105 
                  hover:shadow-xl hover:shadow-green-500/40
                  active:scale-95
                  relative overflow-hidden
                  before:absolute before:inset-0 before:bg-gradient-to-r 
                  before:from-yellow-400/20 before:to-yellow-300/20 
                  before:opacity-0 before:transition-opacity before:duration-300
                  hover:before:opacity-100
                `}
              >
                احصل على خدمة
              </Link>
              
              {/* Provider Register Button - Premium Style */}
              <Link
                href={`/${locale}/provider/join`}
                className={`
                  px-5 py-2.5 rounded-xl font-bold text-sm
                  bg-gradient-to-r from-green-500 to-green-600 text-white
                  border-2 border-yellow-400 
                  shadow-lg shadow-green-500/30
                  transition-all duration-300
                  hover:from-green-600 hover:to-green-700 
                  hover:border-yellow-300 hover:scale-105 
                  hover:shadow-xl hover:shadow-green-500/40
                  active:scale-95
                  relative overflow-hidden
                  before:absolute before:inset-0 before:bg-gradient-to-r 
                  before:from-yellow-400/20 before:to-yellow-300/20 
                  before:opacity-0 before:transition-opacity before:duration-300
                  hover:before:opacity-100
                `}
              >
                قدم خدمة
              </Link>
            </div>

            {/* Mobile Menu Button - Enhanced */}
            <div className="lg:hidden">
              <button
                onClick={() => setIsMenuOpen(!isMenuOpen)}
                className={`
                  p-3 rounded-2xl
                  transition-all duration-300
                  ${isScrolled 
                    ? 'text-gray-700 hover:text-green-600 hover:bg-green-50' 
                    : 'text-gray-800 hover:text-green-600 hover:bg-white/60'
                  }
                  hover:scale-105 active:scale-95
                  shadow-lg shadow-green-500/10
                `}
                aria-label="Toggle menu"
              >
                {isMenuOpen ? (
                  <XMarkIcon className="w-6 h-6" />
                ) : (
                  <Bars3Icon className="w-6 h-6" />
                )}
              </button>
            </div>
          </div>

          {/* Mobile Menu - Modern Slide Down */}
          {isMenuOpen && (
            <div className={`
              lg:hidden absolute top-full left-0 right-0 
              bg-white/98 backdrop-blur-xl 
              shadow-2xl shadow-green-500/20
              border-t border-green-100/50
              animate-in slide-in-from-top-4 duration-300
            `}>
              <div className="px-6 py-8 space-y-6">
                
                {/* Mobile Navigation Links */}
                <div className="space-y-3">
                  {navigationItems.map((item) => (
                    <button
                      key={item.name}
                      onClick={() => {
                        setIsMenuOpen(false)
                        if (item.onClick) {
                          item.onClick()
                        } else {
                          window.location.href = item.href
                        }
                      }}
                      className={`
                        w-full px-6 py-4 rounded-xl font-bold text-lg
                        text-gray-700 hover:text-green-600 hover:bg-green-50
                        transition-all duration-300 text-center
                        hover:scale-105 active:scale-95
                        border border-transparent hover:border-green-200
                      `}
                    >
                      {item.name}
                    </button>
                  ))}
                </div>

                {/* Mobile Divider */}
                <div className="border-t border-green-100 my-6" />

                {/* Mobile Auth Buttons */}
                <div className="space-y-4">
                  
                  {/* Mobile Login */}
                  <Link
                    href={`/${locale}/login`}
                    onClick={() => setIsMenuOpen(false)}
                    className={`
                      block w-full px-6 py-4 rounded-xl font-bold text-lg text-center
                      text-gray-700 hover:text-green-600 hover:bg-green-50
                      transition-all duration-300
                      hover:scale-105 active:scale-95
                      border border-transparent hover:border-green-200
                    `}
                  >
                    تسجيل الدخول
                  </Link>
                  
                  {/* Mobile Customer Register */}
                  <Link
                    href={`/${locale}/customer/join`}
                    onClick={() => setIsMenuOpen(false)}
                    className={`
                      block w-full px-6 py-4 rounded-xl font-bold text-lg text-center
                      bg-gradient-to-r from-green-500 to-green-600 text-white
                      border-2 border-yellow-400 
                      shadow-lg shadow-green-500/30
                      transition-all duration-300
                      hover:from-green-600 hover:to-green-700 
                      hover:border-yellow-300 hover:scale-105 
                      hover:shadow-xl hover:shadow-green-500/40
                      active:scale-95
                    `}
                  >
                    احصل على خدمة
                  </Link>
                  
                  {/* Mobile Provider Register */}
                  <Link
                    href={`/${locale}/provider/join`}
                    onClick={() => setIsMenuOpen(false)}
                    className={`
                      block w-full px-6 py-4 rounded-xl font-bold text-lg text-center
                      bg-gradient-to-r from-green-500 to-green-600 text-white
                      border-2 border-yellow-400 
                      shadow-lg shadow-green-500/30
                      transition-all duration-300
                      hover:from-green-600 hover:to-green-700 
                      hover:border-yellow-300 hover:scale-105 
                      hover:shadow-xl hover:shadow-green-500/40
                      active:scale-95
                    `}
                  >
                    قدم خدمة
                  </Link>
                </div>
              </div>
            </div>
          )}

        </div>
      </nav>

      {/* Mobile Menu Overlay */}
      {isMenuOpen && (
        <div
          className="lg:hidden fixed inset-0 bg-black/20 backdrop-blur-sm -z-10 animate-in fade-in duration-300"
          onClick={() => setIsMenuOpen(false)}
        />
      )}
    </header>
  )
}