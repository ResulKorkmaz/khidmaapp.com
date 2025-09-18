import type { Metadata } from 'next';
import { Inter } from 'next/font/google';
import './globals.css';

const inter = Inter({ 
  subsets: ['latin'],
  variable: '--font-inter',
  display: 'swap',
});

export const metadata: Metadata = {
  title: {
    template: '%s | KhidmaApp',
    default: 'KhidmaApp - منصة الخدمات الرائدة في السعودية',
  },
  description: 'اكتشف أفضل مقدمي الخدمات في مدينتك. من الصيانة إلى التنظيف، نربطك بالمحترفين المناسبين.',
  keywords: ['خدمات', 'السعودية', 'صيانة', 'تنظيف', 'services', 'saudi arabia'],
  authors: [{ name: 'KhidmaApp' }],
  creator: 'KhidmaApp',
  publisher: 'KhidmaApp',
  metadataBase: new URL('https://khidmaapp.com'),
  openGraph: {
    type: 'website',
    locale: 'ar_SA',
    alternateLocale: 'en_US',
    url: 'https://khidmaapp.com',
    siteName: 'KhidmaApp',
    title: 'KhidmaApp - منصة الخدمات الرائدة في السعودية',
    description: 'اكتشف أفضل مقدمي الخدمات في مدينتك. من الصيانة إلى التنظيف، نربطك بالمحترفين المناسبين.',
    images: [
      {
        url: '/og-image.jpg',
        width: 1200,
        height: 630,
        alt: 'KhidmaApp',
      },
    ],
  },
  twitter: {
    card: 'summary_large_image',
    title: 'KhidmaApp - منصة الخدمات الرائدة في السعودية',
    description: 'اكتشف أفضل مقدمي الخدمات في مدينتك. من الصيانة إلى التنظيف، نربطك بالمحترفين المناسبين.',
    images: ['/og-image.jpg'],
  },
  robots: {
    index: true,
    follow: true,
    googleBot: {
      index: true,
      follow: true,
      'max-video-preview': -1,
      'max-image-preview': 'large',
      'max-snippet': -1,
    },
  },
  verification: {
    google: process.env.GOOGLE_VERIFICATION_ID,
    yandex: process.env.YANDEX_VERIFICATION_ID,
  },
  alternates: {
    canonical: 'https://khidmaapp.com',
    languages: {
      'ar-SA': 'https://khidmaapp.com/ar',
      'en-US': 'https://khidmaapp.com/en',
    },
  },
  other: {
    'google-site-verification': process.env.GOOGLE_VERIFICATION_ID || '',
  },
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="ar" suppressHydrationWarning>
      <head>
        <link rel="icon" href="/favicon.ico" sizes="any" />
        <link rel="icon" href="/icon.svg" type="image/svg+xml" />
        <link rel="apple-touch-icon" href="/apple-touch-icon.png" />
        <link rel="manifest" href="/manifest.json" />
        <meta name="theme-color" content="#0ea5e9" />
        <meta name="msapplication-TileColor" content="#0ea5e9" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5" />
        
        {/* Preconnect to external domains */}
        <link rel="preconnect" href="https://api.khidmaapp.com" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
        
        {/* Google Analytics */}
        {process.env.NEXT_PUBLIC_GA_ID && (
          <>
            <script
              async
              src={`https://www.googletagmanager.com/gtag/js?id=${process.env.NEXT_PUBLIC_GA_ID}`}
            />
            <script
              dangerouslySetInnerHTML={{
                __html: `
                  window.dataLayer = window.dataLayer || [];
                  function gtag(){dataLayer.push(arguments);}
                  gtag('js', new Date());
                  gtag('config', '${process.env.NEXT_PUBLIC_GA_ID}', {
                    page_title: document.title,
                    page_location: window.location.href,
                  });
                `,
              }}
            />
          </>
        )}
        
        {/* Meta Pixel */}
        {process.env.NEXT_PUBLIC_META_PIXEL_ID && (
          <script
            dangerouslySetInnerHTML={{
              __html: `
                !function(f,b,e,v,n,t,s)
                {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', '${process.env.NEXT_PUBLIC_META_PIXEL_ID}');
                fbq('track', 'PageView');
              `,
            }}
          />
        )}
      </head>
      <body className={`${inter.variable} font-sans antialiased`}>
        {children}
      </body>
    </html>
  );
}
