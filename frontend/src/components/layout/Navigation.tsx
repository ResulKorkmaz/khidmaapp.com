'use client';

import { useState, useEffect } from 'react';
import { useTranslations } from 'next-intl';
import Image from 'next/image';
import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { 
  Bars3Icon, 
  XMarkIcon, 
  ChevronDownIcon,
  GlobeAltIcon,
  UserIcon,
  PlusIcon,
  StarIcon,
  SparklesIcon,
  BuildingOfficeIcon,
  UserGroupIcon,
  BoltIcon
} from '@heroicons/react/24/outline';

interface NavigationProps {
  locale: string;
}

export default function Navigation({ locale }: NavigationProps) {
  const t = useTranslations('navigation');
  const pathname = usePathname();
  const isRTL = locale === 'ar';
  
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isScrolled, setIsScrolled] = useState(false);
  const [isLanguageOpen, setIsLanguageOpen] = useState(false);

  // Handle scroll effect
  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 20);
    };

    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  // Main navigation items
  const navigationItems = [
    { name: t('home'), href: `/${locale}` },
    { name: t('services'), href: `/${locale}/services` },
    { name: t('categories'), href: `/${locale}/categories` },
    { name: t('cities'), href: `/${locale}/cities` },
    { name: t('how_it_works'), href: `/${locale}/how-it-works` },
  ];

  // Language switcher
  const currentLang = locale === 'ar' ? 'العربية' : 'English';
  const otherLang = locale === 'ar' ? 'English' : 'العربية';
  const otherLocale = locale === 'ar' ? 'en' : 'ar';

  const switchLanguage = () => {
    const currentPath = pathname.replace(/^\/[a-z]{2}/, '');
    window.location.href = `/${otherLocale}${currentPath}`;
  };

  return (
    <header 
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-500 ${
        isScrolled 
          ? 'bg-white/95 backdrop-blur-xl shadow-xl border-b border-gray-100/50' 
          : 'bg-gradient-to-r from-white/10 via-white/5 to-white/10 backdrop-blur-sm'
      }`}
    >
      <nav className="container mx-auto px-4 sm:px-6 lg:px-8" dir={isRTL ? 'rtl' : 'ltr'}>
        <div className="flex items-center justify-between h-18 lg:h-22">
          
          {/* Logo */}
          <div className="flex items-center">
            <Link href={`/${locale}`} className={`flex items-center ${isRTL ? 'space-x-reverse' : ''} space-x-3 group`}>
              <div className="relative">
                <div className="w-12 h-12 bg-gradient-to-br from-primary-500 via-primary-600 to-primary-700 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-primary-500/25 transition-all duration-300 group-hover:scale-105">
                  <SparklesIcon className="w-6 h-6 text-white" />
                </div>
                <div className="absolute -top-1 -right-1 w-4 h-4 bg-gradient-to-r from-gold-400 to-gold-500 rounded-full shadow-sm animate-pulse"></div>
              </div>
              <div className="flex flex-col">
                <span className={`text-xl lg:text-2xl font-bold bg-gradient-to-r from-gray-800 via-gray-900 to-gray-800 bg-clip-text text-transparent ${
                  isScrolled ? '' : 'drop-shadow-sm'
                }`}>
                  {locale === 'ar' ? 'خدمة أب' : 'KhidmaApp'}
                </span>
                <span className="text-xs text-primary-600 font-medium -mt-1">
                  {locale === 'ar' ? 'منصة الخدمات الرائدة' : 'Leading Service Platform'}
                </span>
              </div>
            </Link>
          </div>

          {/* Desktop Navigation */}
          <div className={`hidden lg:flex items-center ${isRTL ? 'space-x-reverse' : ''} space-x-8`}>
            {navigationItems.map((item) => (
              <Link
                key={item.name}
                href={item.href}
                className={`relative text-sm font-semibold transition-all duration-300 hover:text-primary-600 hover:scale-105 group ${
                  pathname === item.href 
                    ? 'text-primary-600' 
                    : isScrolled ? 'text-gray-700' : 'text-gray-800'
                }`}
              >
                <span className="relative z-10">{item.name}</span>
                {pathname === item.href && (
                  <div className="absolute -bottom-1 left-0 right-0 h-0.5 bg-gradient-to-r from-primary-400 to-primary-600 rounded-full"></div>
                )}
                <div className="absolute -bottom-1 left-0 right-0 h-0.5 bg-gradient-to-r from-primary-400 to-primary-600 rounded-full scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-center"></div>
              </Link>
            ))}
          </div>

          {/* Right Actions */}
          <div className={`flex items-center ${isRTL ? 'space-x-reverse' : ''} space-x-3`}>
            
            {/* Language Switcher */}
            <div className="relative">
              <button
                onClick={() => setIsLanguageOpen(!isLanguageOpen)}
                className={`flex items-center ${isRTL ? 'space-x-reverse' : ''} space-x-2 px-3 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:scale-105 ${
                  isScrolled 
                    ? 'text-gray-700 hover:bg-primary-50 hover:text-primary-600' 
                    : 'text-gray-800 hover:bg-white/20 backdrop-blur-sm'
                }`}
              >
                <GlobeAltIcon className="w-4 h-4" />
                <span>{currentLang}</span>
                <ChevronDownIcon className={`w-3 h-3 transition-transform duration-200 ${isLanguageOpen ? 'rotate-180' : ''}`} />
              </button>

              {isLanguageOpen && (
                <div className="absolute top-full mt-2 right-0 bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-gray-100/50 py-2 min-w-[140px] animate-in slide-in-from-top-2 duration-200">
                  <button
                    onClick={() => {
                      switchLanguage();
                      setIsLanguageOpen(false);
                    }}
                    className="w-full px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 text-start transition-colors duration-200 font-medium"
                  >
                    {otherLang}
                  </button>
                </div>
              )}
            </div>

            {/* Post Service Button */}
            <Link
              href={`/${locale}/post-service`}
              className={`hidden sm:inline-flex items-center ${isRTL ? 'space-x-reverse' : ''} space-x-2 px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white rounded-xl text-sm font-semibold transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-primary-500/25`}
            >
              <BoltIcon className="w-4 h-4" />
              <span>{locale === 'ar' ? 'اطلب خدمة' : 'Post Service'}</span>
            </Link>

            {/* Auth Buttons */}
            <div className={`hidden md:flex items-center ${isRTL ? 'space-x-reverse' : ''} space-x-2`}>
              {/* Login Button */}
              <Link
                href={`/${locale}/login`}
                className={`flex items-center ${isRTL ? 'space-x-reverse' : ''} space-x-2 px-4 py-2 text-sm font-semibold transition-all duration-300 hover:scale-105 hover:text-primary-600 ${
                  isScrolled ? 'text-gray-700' : 'text-gray-800'
                }`}
              >
                <UserIcon className="w-4 h-4" />
                <span>{locale === 'ar' ? 'تسجيل الدخول' : 'Sign In'}</span>
              </Link>
              
              {/* Customer Register Button */}
              <Link
                href={`/${locale}/customer/join`}
                className={`flex items-center ${isRTL ? 'space-x-reverse' : ''} space-x-2 px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl text-sm font-semibold transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-green-500/25`}
              >
                <UserGroupIcon className="w-4 h-4" />
                <span>{locale === 'ar' ? 'انضم كعميل' : 'Join as Customer'}</span>
              </Link>
              
              {/* Provider Register Button */}
              <Link
                href={`/${locale}/provider/join`}
                className={`flex items-center ${isRTL ? 'space-x-reverse' : ''} space-x-2 px-4 py-2 bg-gradient-to-r from-gold-500 to-gold-600 hover:from-gold-600 hover:to-gold-700 text-white rounded-xl text-sm font-semibold transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-gold-500/25`}
              >
                <BuildingOfficeIcon className="w-4 h-4" />
                <span>{locale === 'ar' ? 'انضم كمقدم خدمة' : 'Join as Provider'}</span>
              </Link>
            </div>

            {/* Mobile Menu Button */}
            <button
              onClick={() => setIsMenuOpen(!isMenuOpen)}
              className={`lg:hidden relative p-2 rounded-xl transition-all duration-300 hover:scale-105 ${
                isScrolled 
                  ? 'text-gray-700 hover:bg-primary-50 hover:text-primary-600' 
                  : 'text-gray-800 hover:bg-white/20 backdrop-blur-sm'
              }`}
            >
              <div className="relative">
                {isMenuOpen ? (
                  <XMarkIcon className="w-6 h-6 transition-all duration-300" />
                ) : (
                  <Bars3Icon className="w-6 h-6 transition-all duration-300" />
                )}
                {!isMenuOpen && (
                  <div className="absolute -top-1 -right-1 w-2 h-2 bg-gradient-to-r from-primary-400 to-primary-600 rounded-full animate-pulse"></div>
                )}
              </div>
            </button>
          </div>
        </div>

        {/* Mobile Menu */}
        {isMenuOpen && (
          <div className="lg:hidden mt-4 animate-in slide-in-from-top-4 duration-300" dir={isRTL ? 'rtl' : 'ltr'}>
            <div className="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-gray-100/50 mx-4 p-6">
              <div className="space-y-6">
                {/* Navigation Links */}
                <div className="space-y-2">
                  {navigationItems.map((item) => (
                    <Link
                      key={item.name}
                      href={item.href}
                      className={`flex items-center px-4 py-3 text-base font-semibold rounded-2xl transition-all duration-300 hover:scale-105 ${
                        pathname === item.href 
                          ? 'text-primary-600 bg-gradient-to-r from-primary-50 to-primary-100 shadow-sm' 
                          : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50'
                      }`}
                      onClick={() => setIsMenuOpen(false)}
                    >
                      <StarIcon className="w-4 h-4 ml-3 text-primary-400" />
                      <span>{item.name}</span>
                      {pathname === item.href && (
                        <div className="ml-auto w-2 h-2 bg-primary-500 rounded-full animate-pulse"></div>
                      )}
                    </Link>
                  ))}
                </div>

                {/* Gradient Divider */}
                <div className="relative my-6">
                  <div className="absolute inset-0 flex items-center">
                    <div className="w-full border-t border-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                  </div>
                  <div className="relative flex justify-center">
                    <div className="px-4 bg-white">
                      <SparklesIcon className="w-5 h-5 text-primary-400" />
                    </div>
                  </div>
                </div>

                {/* Post Service Button */}
                <Link
                  href={`/${locale}/post-service`}
                  className={`flex items-center ${isRTL ? 'space-x-reverse' : ''} space-x-3 w-full px-6 py-4 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white rounded-2xl text-base font-bold justify-center transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-primary-500/25 mb-4`}
                  onClick={() => setIsMenuOpen(false)}
                >
                  <BoltIcon className="w-5 h-5" />
                  <span>{locale === 'ar' ? 'اطلب خدمة' : 'Post Service'}</span>
                </Link>

                {/* Auth Buttons */}
                <div className="space-y-3">
                  {/* Login Button */}
                  <Link
                    href={`/${locale}/login`}
                    className={`flex items-center ${isRTL ? 'space-x-reverse' : ''} space-x-3 w-full px-6 py-4 border-2 border-gray-200 hover:border-primary-300 text-gray-700 hover:text-primary-600 rounded-2xl font-semibold justify-center transition-all duration-300 hover:scale-105 hover:bg-primary-50`}
                    onClick={() => setIsMenuOpen(false)}
                  >
                    <UserIcon className="w-5 h-5" />
                    <span>{locale === 'ar' ? 'تسجيل الدخول' : 'Sign In'}</span>
                  </Link>
                  
                  {/* Customer Register Button */}
                  <Link
                    href={`/${locale}/customer/join`}
                    className={`flex items-center ${isRTL ? 'space-x-reverse' : ''} space-x-3 w-full px-6 py-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-2xl font-bold justify-center transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-green-500/25`}
                    onClick={() => setIsMenuOpen(false)}
                  >
                    <UserGroupIcon className="w-5 h-5" />
                    <span>{locale === 'ar' ? 'انضم كعميل' : 'Join as Customer'}</span>
                  </Link>
                  
                  {/* Provider Register Button */}
                  <Link
                    href={`/${locale}/provider/join`}
                    className={`flex items-center ${isRTL ? 'space-x-reverse' : ''} space-x-3 w-full px-6 py-4 bg-gradient-to-r from-gold-500 to-gold-600 hover:from-gold-600 hover:to-gold-700 text-white rounded-2xl font-bold justify-center transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-gold-500/25`}
                    onClick={() => setIsMenuOpen(false)}
                  >
                    <BuildingOfficeIcon className="w-5 h-5" />
                    <span>{locale === 'ar' ? 'انضم كمقدم خدمة' : 'Join as Provider'}</span>
                  </Link>
                </div>
              </div>
            </div>
          </div>
        )}
      </nav>

      {/* Mobile Menu Overlay */}
      {isMenuOpen && (
        <div
          className="lg:hidden fixed inset-0 bg-gradient-to-br from-black/30 via-black/20 to-black/30 backdrop-blur-lg -z-10 animate-in fade-in duration-300"
          onClick={() => setIsMenuOpen(false)}
        />
      )}
    </header>
  );
}
