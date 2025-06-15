import { Metadata } from "next";

export const metadata: Metadata = {
  title: "Hizmet Veren Girişi - OnlineUsta",
  description: "Profesyonel hizmet vermek için giriş yapın veya yeni hesap oluşturun",
};

export default function HizmetVerenGirisiLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return <>{children}</>;
} 