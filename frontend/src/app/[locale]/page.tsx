'use client'

import { useEffect } from 'react'
import WhyChooseUs from "@/components/sections/WhyChooseUs"
import PopularServices from "@/components/sections/PopularServices"
import HowItWorks from "@/components/sections/HowItWorks"
import ServiceAreas from "@/components/sections/ServiceAreas"
import Footer from "@/components/Footer"
import SearchInput from "@/components/SearchInput"

export default function HomePage() {
  // Handle hash-based navigation from other pages
  useEffect(() => {
    const hash = window.location.hash.slice(1) // Remove the # symbol
    if (hash) {
      // Wait for the page to fully load
      setTimeout(() => {
        const element = document.getElementById(hash)
        if (element) {
          element.scrollIntoView({ behavior: 'smooth' })
          // Clean up the hash from URL
          window.history.replaceState({}, document.title, window.location.pathname)
        }
      }, 100)
    }
  }, [])

  const handleSearch = (query: string) => {
    // Handle search logic here
    console.log('Searching for:', query)
  }

  return (
    <div className="min-h-screen bg-white">
      {/* Hero Section */}
      <section className="bg-gradient-to-r from-primary-500 to-primary-700 pt-40 pb-20">
        <div className="container mx-auto px-4 text-center text-white">
          <h1 className="text-5xl md:text-6xl font-bold mb-6 text-white">
            مرحباً بكم في خدمة أب (KhidmaApp)
          </h1>
          <p className="text-xl md:text-2xl mb-8 opacity-90 max-w-3xl mx-auto">
            منصة الخدمات الرائدة في المملكة العربية السعودية - نربط بين العملاء ومقدمي الخدمات المهرة
          </p>
          
          {/* Smart Search Box */}
          <div id="search-section" className="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl mx-auto text-gray-900 mb-12">
            <h2 className="text-2xl font-semibold mb-6 text-navy-800">
              ابحث عن الخدمة التي تحتاجها
            </h2>
            <SearchInput 
              placeholder="ما نوع الخدمة التي تحتاجها؟"
              onSearch={handleSearch}
              className="w-full"
            />
            <p className="text-gray-500 mt-4 text-sm">
              أو تصفح الفئات الشائعة أدناه
            </p>
          </div>

          {/* Quick Stats */}
          <div className="grid grid-cols-1 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
            <div className="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-6 border border-white border-opacity-20">
              <div className="text-3xl font-bold text-gold-400 mb-2">10,000+</div>
              <div className="text-lg">خدمة مكتملة</div>
            </div>
            <div className="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-6 border border-white border-opacity-20">
              <div className="text-3xl font-bold text-gold-400 mb-2">5,000+</div>
              <div className="text-lg">مقدم خدمة</div>
            </div>
            <div className="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-6 border border-white border-opacity-20">
              <div className="text-3xl font-bold text-gold-400 mb-2">25+</div>
              <div className="text-lg">مدينة</div>
            </div>
            <div className="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-6 border border-white border-opacity-20">
              <div className="text-3xl font-bold text-gold-400 mb-2">4.8★</div>
              <div className="text-lg">تقييم العملاء</div>
            </div>
          </div>
        </div>
      </section>

      {/* Page Sections */}
      <WhyChooseUs />
      <PopularServices />
      <div id="how-it-works-section">
        <HowItWorks />
      </div>
      <ServiceAreas />
      <Footer />
    </div>
  );
}
