import type { Metadata } from "next";

export const metadata: Metadata = {
  title: "Profesyonel Hizmet | OnlineUsta",
  description: "OnlineUsta'da doğrulanmış profesyonel hizmet sağlayıcısı",
};

export default function PublicProfileLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return children;
} 