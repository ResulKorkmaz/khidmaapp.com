import type { Metadata } from "next";

export const metadata: Metadata = {
  title: "Hizmet Veren Kaydı - OnlineUsta",
  description: "OnlineUsta platformuna hizmet veren olarak kayıt ol ve müşterilere ulaş.",
};

export default function HizmetVerenKaydiLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return children;
} 