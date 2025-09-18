import type { NextConfig } from 'next';
import createNextIntlPlugin from 'next-intl/plugin';

const withNextIntl = createNextIntlPlugin();

const nextConfig: NextConfig = {
  experimental: {
    typedRoutes: true,
  },
  
  images: {
    domains: [
      'api.khidmaapp.com',
      'localhost',
      'ui-avatars.com'
    ],
    formats: ['image/webp', 'image/avif'],
  },

  async headers() {
    return [
      {
        source: '/api/:path*',
        headers: [
          { key: 'Access-Control-Allow-Origin', value: 'https://api.khidmaapp.com' },
          { key: 'Access-Control-Allow-Methods', value: 'GET,OPTIONS,PATCH,DELETE,POST,PUT' },
          { key: 'Access-Control-Allow-Headers', value: 'X-CSRF-Token, X-Requested-With, Accept, Accept-Version, Content-Length, Content-MD5, Content-Type, Date, X-Api-Version' },
        ],
      },
    ];
  },

  async rewrites() {
    return [
      // Redirect old URLs to new structure
      {
        source: '/ar/خدمات/:city/:category',
        destination: '/ar/:city/:category',
      },
      {
        source: '/en/services/:city/:category',
        destination: '/en/:city/:category',
      },
    ];
  },

  async redirects() {
    return [
      // Redirect root to Arabic
      {
        source: '/',
        destination: '/ar',
        permanent: true,
      },
      // Old domain redirects (if needed)
      {
        source: '/home',
        destination: '/ar',
        permanent: true,
      },
    ];
  },

  // Generate static params for ISR
  async generateBuildId() {
    return 'khidmaapp-' + Date.now();
  },

  // Compress responses
  compress: true,

  // Security headers
  poweredByHeader: false,
  
  env: {
    CUSTOM_KEY: process.env.CUSTOM_KEY,
    API_BASE_URL: process.env.NEXT_PUBLIC_API_URL,
  },
};

export default withNextIntl(nextConfig);
