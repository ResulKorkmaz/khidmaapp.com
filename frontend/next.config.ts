import type { NextConfig } from 'next';

const nextConfig: NextConfig = {
  // Remove experimental config for better stability
  images: {
    domains: ['localhost'],
  },
  // Optimize for development
  typescript: {
    ignoreBuildErrors: false,
  },
  eslint: {
    ignoreDuringBuilds: false,
  },
};

export default nextConfig;