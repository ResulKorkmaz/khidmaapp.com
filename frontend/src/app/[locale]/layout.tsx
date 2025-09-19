import type { Metadata } from 'next';
import Header from '@/components/Header';

export const metadata: Metadata = {
  title: 'خدمة أب (KhidmaApp) - منصة الخدمات الرائدة في السعودية',
  description: 'اكتشف أفضل مقدمي الخدمات في مدينتك',
  icons: {
    icon: '/favicon.ico',
  },
};

interface LocaleLayoutProps {
  children: React.ReactNode;
  params: Promise<{ locale: string }>;
}

export default async function LocaleLayout({ 
  children,
  params
}: LocaleLayoutProps) {
  const { locale } = await params;
  const isRTL = locale === 'ar';
  
  return (
    <div className={`min-h-screen ${isRTL ? 'text-arabic' : ''}`}>
      <Header />
      {children}
    </div>
  );
}