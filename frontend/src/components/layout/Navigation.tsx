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
  PlusIcon
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
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
        isScrolled 
          ? 'bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-100' 
          : 'bg-transparent'
      }`}
    >
      <nav className="container mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16 lg:h-20">
          
          {/* Logo */}
          <div className="flex items-center">
            <Link href={`/${locale}`} className="flex items-center space-x-3">
              <div className="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center">
                <span className="text-white font-bold text-lg">خ</span>
              </div>
              <span className="text-xl font-bold text-gray-900">
                {locale === 'ar' ? 'خدمة أب' : 'KhidmaApp'}
              </span>
            </Link>
          </div>

          {/* Desktop Navigation */}
          <div className="hidden lg:flex items-center space-x-8">
            {navigationItems.map((item) => (
              <Link
                key={item.name}
                href={item.href}
                className={`text-sm font-medium transition-colors hover:text-primary-600 ${
                  pathname === item.href 
                    ? 'text-primary-600' 
                    : isScrolled ? 'text-gray-700' : 'text-gray-600'
                }`}
              >
                {item.name}
              </Link>
            ))}
          </div>

          {/* Right Actions */}
          <div className="flex items-center space-x-4">
            
            {/* Language Switcher */}
            <div className="relative">
              <button
                onClick={() => setIsLanguageOpen(!isLanguageOpen)}
                className={`flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors ${
                  isScrolled 
                    ? 'text-gray-700 hover:bg-gray-100' 
                    : 'text-gray-600 hover:bg-white/10'
                }`}
              >
                <GlobeAltIcon className="w-4 h-4" />
                <span>{currentLang}</span>
                <ChevronDownIcon className="w-3 h-3" />
              </button>

              {isLanguageOpen && (
                <div className="absolute top-full mt-2 right-0 bg-white rounded-lg shadow-lg border border-gray-100 py-2 min-w-[120px]">
                  <button
                    onClick={() => {
                      switchLanguage();
                      setIsLanguageOpen(false);
                    }}
                    className="w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 text-start"
                  >
                    {otherLang}
                  </button>
                </div>
              )}
            </div>

            {/* Post Service Button */}
            <Link
              href={`/${locale}/post-service`}
              className="hidden sm:inline-flex items-center space-x-2 btn btn-primary"
            >
              <PlusIcon className="w-4 h-4" />
              <span>{locale === 'ar' ? 'اطلب خدمة' : 'Post Service'}</span>
            </Link>

            {/* Auth Buttons */}
            <div className="hidden md:flex items-center space-x-3">
              <Link
                href={`/${locale}/login`}
                className={`text-sm font-medium transition-colors hover:text-primary-600 ${
                  isScrolled ? 'text-gray-700' : 'text-gray-600'
                }`}
              >
                {t('login')}
              </Link>
              <Link
                href={`/${locale}/register`}
                className="btn btn-outline btn-sm"
              >
                {t('register')}
              </Link>
            </div>

            {/* Mobile Menu Button */}
            <button
              onClick={() => setIsMenuOpen(!isMenuOpen)}
              className={`lg:hidden p-2 rounded-lg transition-colors ${
                isScrolled 
                  ? 'text-gray-700 hover:bg-gray-100' 
                  : 'text-gray-600 hover:bg-white/10'
              }`}
            >
              {isMenuOpen ? (
                <XMarkIcon className="w-6 h-6" />
              ) : (
                <Bars3Icon className="w-6 h-6" />
              )}
            </button>
          </div>
        </div>

        {/* Mobile Menu */}
        {isMenuOpen && (
          <div className="lg:hidden border-t border-gray-100 mt-4 pt-4 pb-6 bg-white/95 backdrop-blur-md rounded-b-2xl shadow-lg">
            <div className="space-y-4">
              {/* Navigation Links */}
              {navigationItems.map((item) => (
                <Link
                  key={item.name}
                  href={item.href}
                  className={`block px-4 py-2 text-base font-medium transition-colors hover:text-primary-600 hover:bg-primary-50 rounded-lg ${
                    pathname === item.href ? 'text-primary-600 bg-primary-50' : 'text-gray-700'
                  }`}
                  onClick={() => setIsMenuOpen(false)}
                >
                  {item.name}
                </Link>
              ))}

              {/* Divider */}
              <div className="border-t border-gray-100 my-4" />

              {/* Post Service Button */}
              <Link
                href={`/${locale}/post-service`}
                className="flex items-center space-x-2 w-full btn btn-primary justify-center mb-4"
                onClick={() => setIsMenuOpen(false)}
              >
                <PlusIcon className="w-4 h-4" />
                <span>{locale === 'ar' ? 'اطلب خدمة' : 'Post Service'}</span>
              </Link>

              {/* Auth Buttons */}
              <div className="flex space-x-3">
                <Link
                  href={`/${locale}/login`}
                  className="flex-1 btn btn-outline justify-center"
                  onClick={() => setIsMenuOpen(false)}
                >
                  {t('login')}
                </Link>
                <Link
                  href={`/${locale}/register`}
                  className="flex-1 btn btn-secondary justify-center"
                  onClick={() => setIsMenuOpen(false)}
                >
                  {t('register')}
                </Link>
              </div>
            </div>
          </div>
        )}
      </nav>

      {/* Mobile Menu Overlay */}
      {isMenuOpen && (
        <div
          className="lg:hidden fixed inset-0 bg-black/20 backdrop-blur-sm -z-10"
          onClick={() => setIsMenuOpen(false)}
        />
      )}
    </header>
  );
}
