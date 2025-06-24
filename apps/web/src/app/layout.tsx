// Açıklama: Ana layout bileşeni - SEO ve metadata yönetimi
import type { Metadata } from "next";
import { Inter } from "next/font/google";
import "./globals.css";
import CookieConsent from "../components/CookieConsent";

const inter = Inter({ subsets: ["latin"] });

export const metadata: Metadata = {
  metadataBase: new URL(process.env.NEXT_PUBLIC_APP_URL || 'https://online-usta-com-tr.vercel.app'),
  title: {
    default: "OnlineUsta - Profesyonel Hizmet Platformu",
    template: "%s | OnlineUsta"
  },
  description: "Türkiye'nin en güvenilir profesyonel hizmet platformu. Ev temizliği, elektrik, tesisatçı ve daha fazlası için uzman hizmet sağlayıcıları bulun.",
  keywords: ["hizmet", "usta", "profesyonel", "ev temizliği", "elektrik", "tesisatçı", "tadilat"],
  authors: [{ name: "OnlineUsta" }],
  creator: "OnlineUsta",
  publisher: "OnlineUsta",
  formatDetection: {
    email: false,
    address: false,
    telephone: false,
  },
  openGraph: {
    type: "website",
    locale: "tr_TR",
    url: process.env.NEXT_PUBLIC_APP_URL || 'https://online-usta-com-tr.vercel.app',
    siteName: "OnlineUsta",
    title: "OnlineUsta - Profesyonel Hizmet Platformu",
    description: "Türkiye'nin en güvenilir profesyonel hizmet platformu",
  },
  twitter: {
    card: "summary_large_image",
    title: "OnlineUsta - Profesyonel Hizmet Platformu",
    description: "Türkiye'nin en güvenilir profesyonel hizmet platformu",
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
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="tr" suppressHydrationWarning>
      <body className={inter.className}>
        {children}
        <CookieConsent />
      </body>
    </html>
  );
} 