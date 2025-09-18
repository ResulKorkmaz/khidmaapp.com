import {NextIntlClientProvider} from 'next-intl';
import {getMessages, getTranslations} from 'next-intl/server';
import {notFound} from 'next/navigation';
import {ReactNode} from 'react';
import {locales} from '@/i18n';

type Props = {
  children: ReactNode;
  params: {locale: string};
};

export function generateStaticParams() {
  return locales.map((locale) => ({locale}));
}

export async function generateMetadata({params: {locale}}: Omit<Props, 'children'>) {
  const t = await getTranslations({locale, namespace: 'home'});

  return {
    title: t('title'),
    description: t('description'),
    alternates: {
      canonical: `https://khidmaapp.com/${locale}`,
      languages: {
        'ar-SA': 'https://khidmaapp.com/ar',
        'en-US': 'https://khidmaapp.com/en',
      },
    },
    openGraph: {
      title: t('title'),
      description: t('description'),
      url: `https://khidmaapp.com/${locale}`,
      locale: locale === 'ar' ? 'ar_SA' : 'en_US',
      alternateLocale: locale === 'ar' ? 'en_US' : 'ar_SA',
    },
  };
}

export default async function LocaleLayout({children, params: {locale}}: Props) {
  // Validate that the incoming `locale` parameter is valid
  if (!locales.includes(locale as any)) {
    notFound();
  }

  // Providing all messages to the client
  // side is the easiest way to get started
  const messages = await getMessages();

  // Set document direction
  const direction = locale === 'ar' ? 'rtl' : 'ltr';

  return (
    <html lang={locale} dir={direction} suppressHydrationWarning>
      <body className={`${locale === 'ar' ? 'text-arabic' : ''} antialiased`}>
        <NextIntlClientProvider messages={messages}>
          {children}
        </NextIntlClientProvider>
      </body>
    </html>
  );
}
