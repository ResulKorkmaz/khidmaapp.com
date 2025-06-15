// Açıklama: Next.js konfigürasyonu - performance ve security optimizasyonları
/** @type {import('next').NextConfig} */
const nextConfig = {
  transpilePackages: ["@onlineusta/ui"],
  experimental: {
    turbo: true,
  },
  trailingSlash: false,
  poweredByHeader: false,
};

module.exports = nextConfig;
