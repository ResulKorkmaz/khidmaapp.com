import { Metadata } from "next";

export const metadata: Metadata = {
  title: "Hizmet Alan Girişi - OnlineUsta",
  description: "Hizmet almak için giriş yapın veya yeni hesap oluşturun",
};

export default function HizmetAlanGirisiLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return <>{children}</>;
} 