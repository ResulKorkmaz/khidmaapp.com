import type { Metadata } from 'next';
import './globals.css';

export const metadata: Metadata = {
  title: 'خدمة أب (KhidmaApp) - منصة الخدمات الرائدة في السعودية',
  description: 'اكتشف أفضل مقدمي الخدمات في مدينتك',
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="ar" dir="rtl">
      <body className="font-sans antialiased">
        {children}
      </body>
    </html>
  );
}