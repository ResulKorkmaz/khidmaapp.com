// Açıklama: Ana layout bileşeni - SEO ve metadata yönetimi
import type { Metadata } from "next";
import { Inter } from "next/font/google";
import "./globals.css";
import Navigation from "../components/Navigation";
import CookieConsent from "../components/CookieConsent";

const inter = Inter({ subsets: ["latin"] });

export const metadata: Metadata = {
  title: {
    default: "OnlineUsta - Profesyonel Hizmet Platformu",
    template: "%s | OnlineUsta",
  },
  description:
    "Türkiye'nin en güvenilir profesyonel hizmet platformu. Usta bul, teklif al, işini hallettir.",
  keywords: [
    "usta",
    "hizmet",
    "tamirci",
    "elektrikçi",
    "tesisatçı",
    "boyacı",
    "profesyonel hizmet",
  ],
  authors: [{ name: "OnlineUsta Team" }],
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
    url: "https://onlineusta.com.tr",
    siteName: "OnlineUsta",
    title: "OnlineUsta - Profesyonel Hizmet Platformu",
    description:
      "Türkiye'nin en güvenilir profesyonel hizmet platformu. Usta bul, teklif al, işini hallettir.",
    images: [
      {
        url: "/og-image.jpg",
        width: 1200,
        height: 630,
        alt: "OnlineUsta",
      },
    ],
  },
  twitter: {
    card: "summary_large_image",
    title: "OnlineUsta - Profesyonel Hizmet Platformu",
    description:
      "Türkiye'nin en güvenilir profesyonel hizmet platformu. Usta bul, teklif al, işini hallettir.",
    images: ["/og-image.jpg"],
  },
  robots: {
    index: true,
    follow: true,
    googleBot: {
      index: true,
      follow: true,
      "max-video-preview": -1,
      "max-image-preview": "large",
      "max-snippet": -1,
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
        <Navigation />
        {children}
        <CookieConsent />
      </body>
    </html>
  );
} 