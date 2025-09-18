import { Metadata } from 'next';
import { getTranslations } from 'next-intl/server';
import { CitiesAPI, CategoriesAPI, ServicesAPI } from '@/lib/api';
import HeroSection from '@/components/home/HeroSection';
import PopularCategories from '@/components/home/PopularCategories';
import PopularCities from '@/components/home/PopularCities';
import FeaturedServices from '@/components/home/FeaturedServices';
import RecentServices from '@/components/home/RecentServices';
import HowItWorks from '@/components/home/HowItWorks';
import StatsSection from '@/components/home/StatsSection';
import Navigation from '@/components/layout/Navigation';
import Footer from '@/components/layout/Footer';

// Revalidate every 5 minutes
export const revalidate = 300;

type Props = {
  params: { locale: string };
};

export async function generateMetadata({ params: { locale } }: Props): Promise<Metadata> {
  const t = await getTranslations({ locale, namespace: 'home' });

  const title = t('title');
  const description = t('description');

  return {
    title,
    description,
    keywords: locale === 'ar' 
      ? ['خدمات', 'السعودية', 'صيانة', 'تنظيف', 'خدمة أب', 'مقدمي خدمات']
      : ['services', 'saudi arabia', 'maintenance', 'cleaning', 'khidmaapp', 'service providers'],
    openGraph: {
      title,
      description,
      url: `https://khidmaapp.com/${locale}`,
      siteName: 'KhidmaApp',
      images: [
        {
          url: '/og-home.jpg',
          width: 1200,
          height: 630,
          alt: 'KhidmaApp - Service Platform',
        },
      ],
      locale: locale === 'ar' ? 'ar_SA' : 'en_US',
      type: 'website',
    },
    twitter: {
      card: 'summary_large_image',
      title,
      description,
      images: ['/og-home.jpg'],
    },
    alternates: {
      canonical: `https://khidmaapp.com/${locale}`,
      languages: {
        'ar-SA': 'https://khidmaapp.com/ar',
        'en-US': 'https://khidmaapp.com/en',
      },
    },
    other: {
      'article:author': 'KhidmaApp',
      'article:section': 'Services',
    },
  };
}

export default async function HomePage({ params: { locale } }: Props) {
  // Fetch data in parallel for better performance
  const [
    popularCategories,
    popularCities,
    featuredServices,
    recentServices,
  ] = await Promise.allSettled([
    CategoriesAPI.getPopular(8),
    CitiesAPI.getPopular(6),
    ServicesAPI.getFeatured(6),
    ServicesAPI.getRecent(7, 6),
  ]);

  // Extract successful results or provide fallbacks
  const categories = popularCategories.status === 'fulfilled' ? popularCategories.value : [];
  const cities = popularCities.status === 'fulfilled' ? popularCities.value : [];
  const featured = featuredServices.status === 'fulfilled' ? featuredServices.value : [];
  const recent = recentServices.status === 'fulfilled' ? recentServices.value : [];

  // JSON-LD structured data for SEO
  const jsonLd = {
    '@context': 'https://schema.org',
    '@type': 'WebSite',
    name: 'KhidmaApp',
    description: locale === 'ar' 
      ? 'منصة الخدمات الرائدة في المملكة العربية السعودية'
      : 'Leading service platform in Saudi Arabia',
    url: `https://khidmaapp.com/${locale}`,
    potentialAction: {
      '@type': 'SearchAction',
      target: {
        '@type': 'EntryPoint',
        urlTemplate: `https://khidmaapp.com/${locale}/search?q={search_term_string}`,
      },
      'query-input': 'required name=search_term_string',
    },
    publisher: {
      '@type': 'Organization',
      name: 'KhidmaApp',
      url: 'https://khidmaapp.com',
      logo: {
        '@type': 'ImageObject',
        url: 'https://khidmaapp.com/logo.png',
      },
    },
    sameAs: [
      'https://twitter.com/khidmaapp',
      'https://instagram.com/khidmaapp',
      'https://linkedin.com/company/khidmaapp',
    ],
  };

  const organizationJsonLd = {
    '@context': 'https://schema.org',
    '@type': 'Organization',
    name: 'KhidmaApp',
    url: 'https://khidmaapp.com',
    logo: 'https://khidmaapp.com/logo.png',
    description: locale === 'ar'
      ? 'منصة تربط العملاء بأفضل مقدمي الخدمات المحترفين في السعودية'
      : 'Platform connecting customers with the best professional service providers in Saudi Arabia',
    address: {
      '@type': 'PostalAddress',
      addressCountry: 'SA',
      addressRegion: 'Riyadh',
    },
    contactPoint: {
      '@type': 'ContactPoint',
      contactType: 'customer service',
      email: 'support@khidmaapp.com',
      availableLanguage: ['Arabic', 'English'],
    },
    areaServed: {
      '@type': 'Country',
      name: 'Saudi Arabia',
    },
  };

  return (
    <>
      {/* Structured Data */}
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }}
      />
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(organizationJsonLd) }}
      />

      {/* Navigation */}
      <Navigation locale={locale} />

      {/* Main Content */}
      <main className="min-h-screen">
        {/* Hero Section */}
        <HeroSection locale={locale} categories={categories} cities={cities} />

        {/* Popular Categories */}
        {categories.length > 0 && (
          <PopularCategories locale={locale} categories={categories} />
        )}

        {/* How It Works */}
        <HowItWorks locale={locale} />

        {/* Featured Services */}
        {featured.length > 0 && (
          <FeaturedServices locale={locale} services={featured} />
        )}

        {/* Popular Cities */}
        {cities.length > 0 && (
          <PopularCities locale={locale} cities={cities} />
        )}

        {/* Recent Services */}
        {recent.length > 0 && (
          <RecentServices locale={locale} services={recent} />
        )}

        {/* Stats Section */}
        <StatsSection locale={locale} />
      </main>

      {/* Footer */}
      <Footer locale={locale} />
    </>
  );
}
