"use client";

import Link from "next/link";
import Image from "next/image";
import { useState, useEffect } from "react";
import { usePathname } from "next/navigation";

export default function Navigation() {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [scrollY, setScrollY] = useState(0);
  const [mounted, setMounted] = useState(false);
  const pathname = usePathname();
  
  // Anasayfada mıyız kontrol et
  const isHomePage = pathname === '/';
  const heroHeight = 400; // Sabit hero yüksekliği

  useEffect(() => {
    const handleScroll = () => {
      setScrollY(window.scrollY);
    };

    // Component mount olduğunda mevcut scroll pozisyonunu hemen al
    setScrollY(window.scrollY);
    setMounted(true);

    window.addEventListener('scroll', handleScroll, { passive: true });
    
      return () => {
      window.removeEventListener('scroll', handleScroll);
      };
  }, []);

  // Arka plan gösterim koşulu:
  // Ana sayfa dışında VEYA ana sayfada hero alanını geçtiyse
  const shouldShowBackground = !isHomePage || scrollY > heroHeight;

  // Hydration güvenliği
  if (!mounted) {
    return null;
  }

  return (
    <nav className="fixed left-0 right-0 z-50 transition-all duration-300">
      {/* Arka plan katmanı - Premium Koyu Tasarım */}
      <div 
        className={`absolute inset-0 transition-all duration-500 ${
          shouldShowBackground 
            ? 'bg-slate-900/80 backdrop-blur-xl border-b border-slate-700/50 shadow-2xl shadow-black/20' 
            : 'bg-transparent'
        }`}
      ></div>
      
      <div className="relative container mx-auto px-6 py-4">
        <div className="flex items-center justify-between">
          {/* Logo */}
          <div className="flex items-center">
            <Link href="/" className="group">
              <Image
                src="/images/logo.png"
                alt="OnlineUsta Logo"
                width={144}
                height={144}
                className="object-contain hover:scale-105 transition-transform duration-300"
              />
            </Link>
          </div>

          {/* Desktop Navigation */}
          <div className="hidden md:flex items-center space-x-6">
            <Link 
              href="/hizmet-alan-girisi"
              className={`px-6 py-3 font-medium text-lg transition-all duration-300 tracking-wide border rounded-full backdrop-blur-sm hover:backdrop-blur-md hover:shadow-lg ${
                shouldShowBackground 
                  ? 'text-white hover:text-emerald-400 hover:border-emerald-400/60 hover:bg-emerald-500/10 border-slate-600/60 hover:shadow-emerald-500/20'
                  : 'text-white hover:text-green-100 hover:border-green-100 hover:bg-black/10 border-white/40 drop-shadow-lg'
              }`}
            >
              Hizmet Al
            </Link>
            <Link 
              href="/hizmet-veren-girisi"
              className={`px-6 py-3 font-medium text-lg transition-all duration-300 tracking-wide border rounded-full backdrop-blur-sm hover:backdrop-blur-md hover:shadow-lg ${
                shouldShowBackground 
                  ? 'text-white hover:text-emerald-400 hover:border-emerald-400/60 hover:bg-emerald-500/10 border-slate-600/60 hover:shadow-emerald-500/20'
                  : 'text-white hover:text-green-100 hover:border-green-100 hover:bg-black/10 border-white/40 drop-shadow-lg'
              }`}
            >
              Hizmet Ver
            </Link>
            <Link 
              href="/hakkimizda"
              className={`px-6 py-3 font-medium text-lg transition-all duration-300 tracking-wide border rounded-full backdrop-blur-sm hover:backdrop-blur-md hover:shadow-lg ${
                shouldShowBackground 
                  ? 'text-slate-300 hover:text-white hover:border-slate-500 hover:bg-slate-700/30 border-slate-600/60'
                  : 'text-white hover:text-green-100 hover:border-green-100 hover:bg-black/10 border-white/40 drop-shadow-lg'
              }`}
            >
              Hakkımızda
            </Link>
            <Link 
              href="/yardim"
              className={`px-6 py-3 font-medium text-lg transition-all duration-300 tracking-wide border rounded-full backdrop-blur-sm hover:backdrop-blur-md hover:shadow-lg ${
                shouldShowBackground 
                  ? 'text-slate-300 hover:text-white hover:border-slate-500 hover:bg-slate-700/30 border-slate-600/60'
                  : 'text-white hover:text-green-100 hover:border-green-100 hover:bg-black/10 border-white/40 drop-shadow-lg'
              }`}
            >
              Yardım
            </Link>
          </div>

          {/* Mobile menu button and Hizmet Ver */}
          <div className="md:hidden flex items-center space-x-3">
            <Link 
              href="/hizmet-veren-girisi"
              className={`px-4 py-2 font-medium text-sm transition-all duration-300 tracking-wide border rounded-full backdrop-blur-sm hover:backdrop-blur-md hover:shadow-lg ${
                shouldShowBackground 
                  ? 'text-white hover:text-emerald-400 hover:border-emerald-400/60 hover:bg-emerald-500/10 border-slate-600/60 hover:shadow-emerald-500/20'
                  : 'text-white hover:text-green-100 hover:border-green-100 hover:bg-black/10 border-white/40 drop-shadow-lg'
              }`}
            >
              Hizmet Ver
            </Link>
            <button
              onClick={() => setIsMenuOpen(!isMenuOpen)}
              className={`p-2 rounded-lg transition-all duration-300 ${
                shouldShowBackground 
                  ? 'text-white hover:text-emerald-400 hover:bg-emerald-500/10'
                  : 'text-white hover:text-green-100 hover:bg-black/10 drop-shadow-lg'
              }`}
            >
              <svg className="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="2">
                {isMenuOpen ? (
                  <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
                ) : (
                  <path strokeLinecap="round" strokeLinejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                )}
              </svg>
            </button>
          </div>
        </div>

        {/* Mobile menu */}
        {isMenuOpen && (
          <div className={`md:hidden mt-4 py-4 border-t rounded-lg backdrop-blur-md shadow-lg ${
            shouldShowBackground 
              ? 'border-slate-700/60 bg-slate-800/40'
              : 'border-white/20 bg-black/15'
          }`}>
            <div className="space-y-3">
              <Link 
                href="/hizmet-alan-girisi"
                className={`block font-medium text-lg transition-all duration-300 tracking-wide px-4 py-3 border rounded-full backdrop-blur-sm hover:backdrop-blur-md hover:shadow-lg ${
                  shouldShowBackground 
                    ? 'text-white hover:text-emerald-400 hover:border-emerald-400/60 hover:bg-emerald-500/10 border-slate-600/60 hover:shadow-emerald-500/20'
                    : 'text-white hover:text-green-100 hover:border-green-100 hover:bg-black/10 border-white/40 drop-shadow-lg'
                }`}
                onClick={() => setIsMenuOpen(false)}
              >
                Hizmet Al
              </Link>
              <Link 
                href="/hizmet-veren-girisi"
                className={`block font-medium text-lg transition-all duration-300 tracking-wide px-4 py-3 border rounded-full backdrop-blur-sm hover:backdrop-blur-md hover:shadow-lg ${
                  shouldShowBackground 
                    ? 'text-white hover:text-emerald-400 hover:border-emerald-400/60 hover:bg-emerald-500/10 border-slate-600/60 hover:shadow-emerald-500/20'
                    : 'text-white hover:text-green-100 hover:border-green-100 hover:bg-black/10 border-white/40 drop-shadow-lg'
                }`}
                onClick={() => setIsMenuOpen(false)}
              >
                Hizmet Ver
              </Link>
              <Link 
                href="/hakkimizda"
                className={`block font-medium text-lg transition-all duration-300 tracking-wide px-4 py-3 border rounded-full backdrop-blur-sm hover:backdrop-blur-md hover:shadow-lg ${
                  shouldShowBackground 
                    ? 'text-slate-300 hover:text-white hover:border-slate-500 hover:bg-slate-700/30 border-slate-600/60'
                    : 'text-white hover:text-green-100 hover:border-green-100 hover:bg-black/10 border-white/40 drop-shadow-lg'
                }`}
                onClick={() => setIsMenuOpen(false)}
              >
                Hakkımızda
              </Link>
              <Link 
                href="/yardim"
                className={`block font-medium text-lg transition-all duration-300 tracking-wide px-4 py-3 border rounded-full backdrop-blur-sm hover:backdrop-blur-md hover:shadow-lg ${
                  shouldShowBackground 
                    ? 'text-slate-300 hover:text-white hover:border-slate-500 hover:bg-slate-700/30 border-slate-600/60'
                    : 'text-white hover:text-green-100 hover:border-green-100 hover:bg-black/10 border-white/40 drop-shadow-lg'
                }`}
                onClick={() => setIsMenuOpen(false)}
              >
                Yardım
              </Link>
            </div>
          </div>
        )}
      </div>
    </nav>
  );
} 