"use client";

import { useState, useEffect } from "react";
import Link from "next/link";

export default function CookieConsent() {
  const [isVisible, setIsVisible] = useState(false);

  useEffect(() => {
    // Çerez onayı daha önce verilmiş mi kontrol et
    const cookieConsent = localStorage.getItem("cookieConsent");
    if (!cookieConsent) {
      setIsVisible(true);
    }
  }, []);

  const acceptCookies = () => {
    localStorage.setItem("cookieConsent", "accepted");
    setIsVisible(false);
  };

  const rejectCookies = () => {
    localStorage.setItem("cookieConsent", "rejected");
    setIsVisible(false);
  };

  if (!isVisible) return null;

  return (
    <div className="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-50">
      <div className="container mx-auto px-4 py-4">
        <div className="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
          <div className="flex-1">
            <p className="text-sm text-gray-700 leading-relaxed">
              Web sitemizde size en iyi deneyimi sunabilmek için çerezleri kullanıyoruz. 
              Sitemizi kullanmaya devam ederek çerez kullanımını kabul etmiş olursunuz. 
              Daha fazla bilgi için{" "}
              <Link 
                href="/cerez-politikasi" 
                className="text-green-500 hover:text-green-600 underline"
              >
                Çerez Politikamızı
              </Link>{" "}
              inceleyebilirsiniz.
            </p>
          </div>
          
          <div className="flex gap-3">
            <button
              onClick={rejectCookies}
              className="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
            >
              Reddet
            </button>
            <button
              onClick={acceptCookies}
              className="px-4 py-2 text-sm font-medium text-white bg-green-500 hover:bg-green-600 rounded-lg transition-colors"
            >
              Kabul Et
            </button>
          </div>
        </div>
      </div>
    </div>
  );
} 