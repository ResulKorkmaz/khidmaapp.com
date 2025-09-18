import { useTranslations } from 'next-intl';
import Image from 'next/image';
import Link from 'next/link';
import { MagnifyingGlassIcon, MapPinIcon } from '@heroicons/react/24/outline';
import { Category, City } from '@/lib/api';

interface HeroSectionProps {
  locale: string;
  categories: Category[];
  cities: City[];
}

export default function HeroSection({ locale, categories, cities }: HeroSectionProps) {
  const t = useTranslations('home');
  const isRTL = locale === 'ar';

  return (
    <section className="relative bg-gradient-to-br from-primary-50 via-white to-primary-50 overflow-hidden">
      {/* Background Pattern */}
      <div className="absolute inset-0 opacity-5">
        <div className="absolute inset-0 bg-[url('/patterns/grid.svg')] bg-center [mask-image:linear-gradient(180deg,white,rgba(255,255,255,0))]" />
      </div>

      {/* Background Shapes */}
      <div className="absolute top-0 -right-40 w-80 h-80 bg-primary-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob" />
      <div className="absolute top-0 -left-40 w-80 h-80 bg-secondary-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000" />
      <div className="absolute -bottom-40 left-20 w-80 h-80 bg-primary-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000" />

      <div className="relative container mx-auto px-4 sm:px-6 lg:px-8">
        <div className="min-h-[600px] flex flex-col lg:flex-row items-center justify-between py-12 lg:py-20">
          
          {/* Content */}
          <div className={`w-full lg:w-1/2 ${isRTL ? 'lg:order-2 lg:pr-12' : 'lg:pr-12'}`}>
            <div className="text-center lg:text-start">
              {/* Badge */}
              <div className="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-primary-100 text-primary-800 mb-6">
                <span className="w-2 h-2 bg-primary-500 rounded-full mr-2 animate-pulse" />
                {locale === 'ar' ? 'منصة موثقة 🇸🇦' : 'Trusted Platform 🇸🇦'}
              </div>

              {/* Main Heading */}
              <h1 className="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                <span className="block">{t('hero_title')}</span>
                <span className="block text-primary-600 mt-2">
                  {locale === 'ar' ? 'في السعودية' : 'in Saudi'}
                </span>
              </h1>

              {/* Subtitle */}
              <p className="text-xl text-gray-600 mb-8 max-w-lg mx-auto lg:mx-0">
                {t('hero_subtitle')}
              </p>

              {/* Search Bar */}
              <div className="relative mb-8">
                <div className="flex flex-col sm:flex-row gap-3 p-2 bg-white rounded-2xl shadow-soft-lg border border-gray-100">
                  {/* Service Search */}
                  <div className="flex-1 relative">
                    <MagnifyingGlassIcon className={`absolute top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 ${isRTL ? 'right-4' : 'left-4'}`} />
                    <input
                      type="text"
                      placeholder={t('search_placeholder')}
                      className={`w-full ${isRTL ? 'pr-12 pl-4' : 'pl-12 pr-4'} py-4 text-gray-900 placeholder-gray-500 bg-transparent border-0 focus:outline-none focus:ring-0`}
                    />
                  </div>

                  {/* City Selector */}
                  <div className="relative">
                    <MapPinIcon className={`absolute top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 ${isRTL ? 'right-4' : 'left-4'}`} />
                    <select className={`${isRTL ? 'pr-12 pl-8' : 'pl-12 pr-8'} py-4 text-gray-900 bg-transparent border-0 focus:outline-none focus:ring-0 cursor-pointer`}>
                      <option value="">{locale === 'ar' ? 'جميع المدن' : 'All Cities'}</option>
                      {cities.slice(0, 5).map((city) => (
                        <option key={city.id} value={city.slug}>
                          {city.name}
                        </option>
                      ))}
                    </select>
                  </div>

                  {/* Search Button */}
                  <button className="btn btn-primary btn-lg px-8 py-4 whitespace-nowrap">
                    {locale === 'ar' ? 'بحث' : 'Search'}
                  </button>
                </div>
              </div>

              {/* Quick Categories */}
              <div className="flex flex-wrap gap-3 justify-center lg:justify-start">
                <span className="text-sm text-gray-500 self-center">
                  {locale === 'ar' ? 'فئات شائعة:' : 'Popular:'}
                </span>
                {categories.slice(0, 4).map((category) => (
                  <Link
                    key={category.id}
                    href={`/${locale}/category/${category.slug}`}
                    className="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors"
                  >
                    {category.icon && (
                      <Image
                        src={category.icon_url || '/icons/default.svg'}
                        alt=""
                        width={16}
                        height={16}
                        className={`${isRTL ? 'ml-2' : 'mr-2'}`}
                      />
                    )}
                    {category.name}
                  </Link>
                ))}
              </div>
            </div>
          </div>

          {/* Hero Image */}
          <div className={`w-full lg:w-1/2 mt-12 lg:mt-0 ${isRTL ? 'lg:order-1 lg:pl-12' : 'lg:pl-12'}`}>
            <div className="relative">
              {/* Main Image */}
              <div className="relative rounded-3xl overflow-hidden shadow-soft-lg">
                <Image
                  src="/images/hero-services.jpg"
                  alt={t('hero_title')}
                  width={600}
                  height={400}
                  className="w-full h-auto object-cover"
                  priority
                />
              </div>

              {/* Floating Cards */}
              <div className="absolute -top-4 -right-4 bg-white rounded-2xl shadow-soft-lg p-4 animate-float">
                <div className="flex items-center space-x-3">
                  <div className="w-10 h-10 bg-success-100 rounded-xl flex items-center justify-center">
                    <svg className="w-5 h-5 text-success-600" fill="currentColor" viewBox="0 0 20 20">
                      <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                    </svg>
                  </div>
                  <div>
                    <p className="text-sm font-semibold text-gray-900">
                      {locale === 'ar' ? '1000+ خدمة' : '1000+ Services'}
                    </p>
                    <p className="text-xs text-gray-500">
                      {locale === 'ar' ? 'مكتملة بنجاح' : 'Completed Successfully'}
                    </p>
                  </div>
                </div>
              </div>

              <div className="absolute -bottom-4 -left-4 bg-white rounded-2xl shadow-soft-lg p-4 animate-float animation-delay-2000">
                <div className="flex items-center space-x-3">
                  <div className="w-10 h-10 bg-warning-100 rounded-xl flex items-center justify-center">
                    <svg className="w-5 h-5 text-warning-600" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                  </div>
                  <div>
                    <p className="text-sm font-semibold text-gray-900">
                      {locale === 'ar' ? '4.9 تقييم' : '4.9 Rating'}
                    </p>
                    <p className="text-xs text-gray-500">
                      {locale === 'ar' ? 'من العملاء' : 'From Customers'}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Bottom Wave */}
      <div className="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" className="w-full h-auto">
          <path d="M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,48C672,43,768,53,864,69.3C960,85,1056,107,1152,112C1248,117,1344,107,1392,101.3L1440,96L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z" fill="white"/>
        </svg>
      </div>
    </section>
  );
}
