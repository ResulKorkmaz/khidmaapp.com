import { Metadata } from "next";

export const metadata: Metadata = {
  title: "Hizmet Talebi Oluştur",
  description: "Profesyonel hizmet sağlayıcılarından teklif alın",
};

export default function ServiceRequestLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return <>{children}</>;
} 