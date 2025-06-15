// Açıklama: Ana sayfa bileşeni - Modern tasarım ve #9333ea renk teması
import Link from "next/link";
import Image from "next/image";
import Footer from "../components/Footer";

export default function HomePage() {
  const categories = [
    {
      name: "Ev Temizliği",
      slug: "ev-temizligi",
      icon: "🏠",
      description: "Profesyonel ev temizlik hizmetleri",
    },
    {
      name: "Elektrik",
      slug: "elektrik",
      icon: "⚡",
      description: "Elektrik tesisatı ve onarım",
    },
    {
      name: "Tesisatçı",
      slug: "tesisat",
      icon: "🔧",
      description: "Su tesisatı ve doğalgaz hizmetleri",
    },
    {
      name: "Boyacı",
      slug: "boyaci",
      icon: "🎨",
      description: "İç ve dış mekan boyama",
    },
    {
      name: "Nakliyat",
      slug: "nakliyat",
      icon: "🚚",
      description: "Ev ve ofis taşımacılığı",
    },
  ];

  return (
    <main className="flex min-h-screen flex-col">
      {/* Hero Section */}
      <section className="relative h-[80vh] flex items-center text-white overflow-hidden">
        {/* Arka plan resmi */}
        <div className="absolute inset-0">
          <Image
            src="/images/hero.jpg"
            alt="Profesyonel usta ve müşteri el sıkışması"
            fill
            className="object-cover object-center scale-150 md:scale-100"
            priority
          />
          {/* Overlay */}
          <div className="absolute inset-0 bg-gradient-to-r from-green-900/30 via-green-800/25 to-green-700/20"></div>
        </div>

        <div className="container mx-auto px-4 py-20 relative z-10">
          <div className="max-w-4xl mx-auto text-center">
            <div className="space-y-8">
              <h1 className="text-5xl lg:text-6xl font-bold text-white drop-shadow-2xl">
                Online Usta
              </h1>

              <p className="text-xl lg:text-2xl text-white leading-relaxed drop-shadow-xl">
                Güvenilir ustalardan hızlı teklif alın.
              </p>

              {/* Arama Kutusu */}
              <div className="max-w-2xl mx-auto mt-8">
                <div className="relative flex items-center bg-white rounded-full shadow-lg overflow-hidden">
                  <input
                    type="text"
                    placeholder="Hangi hizmeti arıyorsun?"
                    className="flex-1 px-6 py-4 text-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-0 border-0"
                  />
                  <button className="bg-green-500 hover:bg-green-600 text-white font-bold px-8 py-4 transition-colors duration-200 flex items-center justify-center min-w-[100px]">
                    <span className="text-lg">Ara</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Trend Hizmetler Section */}
      <section className="py-16 bg-white">
        <div className="container mx-auto px-4">
          <div className="text-center mb-12">
            <h2 className="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
              Trend Hizmetler
            </h2>
            <p className="text-lg text-gray-600 max-w-2xl mx-auto">
              En çok talep edilen hizmet alanları
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {/* 1. Satır */}
            <div className="group">
              <div className="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 overflow-hidden">
                <div className="h-48 relative overflow-hidden">
                  <Image
                    src="https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=400&h=300&fit=crop&crop=center"
                    alt="Elektrik Tamiri"
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="p-5">
                  <h3 className="text-lg font-semibold text-gray-900 text-center mb-2">
                    Elektrik Tamiri
                  </h3>
                  <div className="flex items-center justify-center text-sm text-gray-500 mb-4">
                    <svg
                      className="w-4 h-4 mr-1.5"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fillRule="evenodd"
                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clipRule="evenodd"
                      />
                    </svg>
                    <span>47 Hizmet Veren</span>
                  </div>
                  <button className="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium py-2.5 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                    Teklif Al
                  </button>
                </div>
              </div>
            </div>

            <div className="group">
              <div className="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 overflow-hidden">
                <div className="h-48 relative overflow-hidden">
                  <Image
                    src="https://images.unsplash.com/photo-1607472586893-edb57bdc0e39?w=400&h=300&fit=crop&crop=center"
                    alt="Tesisatçı"
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="p-5">
                  <h3 className="text-lg font-semibold text-gray-900 text-center mb-2">
                    Tesisatçı
                  </h3>
                  <div className="flex items-center justify-center text-sm text-gray-500 mb-4">
                    <svg
                      className="w-4 h-4 mr-1.5"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fillRule="evenodd"
                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clipRule="evenodd"
                      />
                    </svg>
                    <span>32 Hizmet Veren</span>
                  </div>
                  <button className="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium py-2.5 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                    Teklif Al
                  </button>
                </div>
              </div>
            </div>

            <div className="group">
              <div className="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 overflow-hidden">
                <div className="h-48 relative overflow-hidden">
                  <Image
                    src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=300&fit=crop&crop=center"
                    alt="Ev Temizliği"
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="p-5">
                  <h3 className="text-lg font-semibold text-gray-900 text-center mb-2">
                    Ev Temizliği
                  </h3>
                  <div className="flex items-center justify-center text-sm text-gray-500 mb-4">
                    <svg
                      className="w-4 h-4 mr-1.5"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fillRule="evenodd"
                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clipRule="evenodd"
                      />
                    </svg>
                    <span>28 Hizmet Veren</span>
                  </div>
                  <button className="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium py-2.5 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                    Teklif Al
                  </button>
                </div>
              </div>
            </div>

            <div className="group">
              <div className="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 overflow-hidden">
                <div className="h-48 relative overflow-hidden">
                  <Image
                    src="https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=400&h=300&fit=crop&crop=center"
                    alt="Boyacı"
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="p-5">
                  <h3 className="text-lg font-semibold text-gray-900 text-center mb-2">
                    Boyacı
                  </h3>
                  <div className="flex items-center justify-center text-sm text-gray-500 mb-4">
                    <svg
                      className="w-4 h-4 mr-1.5"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fillRule="evenodd"
                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clipRule="evenodd"
                      />
                    </svg>
                    <span>19 Hizmet Veren</span>
                  </div>
                  <button className="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium py-2.5 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                    Teklif Al
                  </button>
                </div>
              </div>
            </div>

            {/* 2. Satır */}
            <div className="group">
              <div className="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 overflow-hidden">
                <div className="h-48 relative overflow-hidden">
                  <Image
                    src="https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=400&h=300&fit=crop&crop=center"
                    alt="Klima Tamiri"
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="p-5">
                  <h3 className="text-lg font-semibold text-gray-900 text-center mb-2">
                    Klima Tamiri
                  </h3>
                  <div className="flex items-center justify-center text-sm text-gray-500 mb-4">
                    <svg
                      className="w-4 h-4 mr-1.5"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fillRule="evenodd"
                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clipRule="evenodd"
                      />
                    </svg>
                    <span>15 Hizmet Veren</span>
                  </div>
                  <button className="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium py-2.5 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                    Teklif Al
                  </button>
                </div>
              </div>
            </div>

            <div className="group">
              <div className="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 overflow-hidden">
                <div className="h-48 relative overflow-hidden">
                  <Image
                    src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400&h=300&fit=crop&crop=center"
                    alt="Mobilyacı"
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="p-5">
                  <h3 className="text-lg font-semibold text-gray-900 text-center mb-2">
                    Mobilyacı
                  </h3>
                  <div className="flex items-center justify-center text-sm text-gray-500 mb-4">
                    <svg
                      className="w-4 h-4 mr-1.5"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fillRule="evenodd"
                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clipRule="evenodd"
                      />
                    </svg>
                    <span>12 Hizmet Veren</span>
                  </div>
                  <button className="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium py-2.5 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                    Teklif Al
                  </button>
                </div>
              </div>
            </div>

            <div className="group">
              <div className="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 overflow-hidden">
                <div className="h-48 relative overflow-hidden">
                  <Image
                    src="https://images.unsplash.com/photo-1487754180451-c456f719a1fc?w=400&h=300&fit=crop&crop=center"
                    alt="Oto Tamiri"
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="p-5">
                  <h3 className="text-lg font-semibold text-gray-900 text-center mb-2">
                    Oto Tamiri
                  </h3>
                  <div className="flex items-center justify-center text-sm text-gray-500 mb-4">
                    <svg
                      className="w-4 h-4 mr-1.5"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fillRule="evenodd"
                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clipRule="evenodd"
                      />
                    </svg>
                    <span>23 Hizmet Veren</span>
                  </div>
                  <button className="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium py-2.5 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                    Teklif Al
                  </button>
                </div>
              </div>
            </div>

            <div className="group">
              <div className="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 overflow-hidden">
                <div className="h-48 relative overflow-hidden">
                  <Image
                    src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=400&h=300&fit=crop&crop=center"
                    alt="Bilgisayar Tamiri"
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="p-5">
                  <h3 className="text-lg font-semibold text-gray-900 text-center mb-2">
                    Bilgisayar Tamiri
                  </h3>
                  <div className="flex items-center justify-center text-sm text-gray-500 mb-4">
                    <svg
                      className="w-4 h-4 mr-1.5"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fillRule="evenodd"
                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clipRule="evenodd"
                      />
                    </svg>
                    <span>18 Hizmet Veren</span>
                  </div>
                  <button className="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium py-2.5 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                    Teklif Al
                  </button>
                </div>
              </div>
            </div>

            {/* 3. Satır */}
            <div className="group">
              <div className="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 overflow-hidden">
                <div className="h-48 relative overflow-hidden">
                  <Image
                    src="https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=400&h=300&fit=crop&crop=center"
                    alt="Bahçıvan"
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="p-5">
                  <h3 className="text-lg font-semibold text-gray-900 text-center mb-2">
                    Bahçıvan
                  </h3>
                  <div className="flex items-center justify-center text-sm text-gray-500 mb-4">
                    <svg
                      className="w-4 h-4 mr-1.5"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fillRule="evenodd"
                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clipRule="evenodd"
                      />
                    </svg>
                    <span>9 Hizmet Veren</span>
                  </div>
                  <button className="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium py-2.5 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                    Teklif Al
                  </button>
                </div>
              </div>
            </div>

            <div className="group">
              <div className="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 overflow-hidden">
                <div className="h-48 relative overflow-hidden">
                  <Image
                    src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=300&fit=crop&crop=center"
                    alt="Nakliyat"
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="p-5">
                  <h3 className="text-lg font-semibold text-gray-900 text-center mb-2">
                    Nakliyat
                  </h3>
                  <div className="flex items-center justify-center text-sm text-gray-500 mb-4">
                    <svg
                      className="w-4 h-4 mr-1.5"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fillRule="evenodd"
                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clipRule="evenodd"
                      />
                    </svg>
                    <span>34 Hizmet Veren</span>
                  </div>
                  <button className="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium py-2.5 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                    Teklif Al
                  </button>
                </div>
              </div>
            </div>

            <div className="group">
              <div className="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 overflow-hidden">
                <div className="h-48 relative overflow-hidden">
                  <Image
                    src="https://images.unsplash.com/photo-1504148455328-c376907d081c?w=400&h=300&fit=crop&crop=center"
                    alt="Marangoz"
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="p-5">
                  <h3 className="text-lg font-semibold text-gray-900 text-center mb-2">
                    Marangoz
                  </h3>
                  <div className="flex items-center justify-center text-sm text-gray-500 mb-4">
                    <svg
                      className="w-4 h-4 mr-1.5"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fillRule="evenodd"
                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clipRule="evenodd"
                      />
                    </svg>
                    <span>14 Hizmet Veren</span>
                  </div>
                  <button className="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium py-2.5 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                    Teklif Al
                  </button>
                </div>
              </div>
            </div>

            <div className="group">
              <div className="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 overflow-hidden">
                <div className="h-48 relative overflow-hidden">
                  <Image
                    src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=400&h=300&fit=crop&crop=center"
                    alt="Telefon Tamiri"
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="p-5">
                  <h3 className="text-lg font-semibold text-gray-900 text-center mb-2">
                    Telefon Tamiri
                  </h3>
                  <div className="flex items-center justify-center text-sm text-gray-500 mb-4">
                    <svg
                      className="w-4 h-4 mr-1.5"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fillRule="evenodd"
                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clipRule="evenodd"
                      />
                    </svg>
                    <span>21 Hizmet Veren</span>
                  </div>
                  <button className="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium py-2.5 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                    Teklif Al
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Nasıl Usta Bulurum Section */}
      <section className="py-20 bg-gray-50 relative overflow-hidden">
        <div className="container mx-auto px-4">
          <div className="text-center mb-16">
            <h2 className="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
              Nasıl Usta Bulurum?
            </h2>
            <p className="text-lg text-gray-600 max-w-2xl mx-auto">
              3 basit adımda güvenilir ustalardan teklif alın
            </p>
          </div>

          {/* Modern Timeline */}
          <div className="relative max-w-6xl mx-auto">
            {/* Timeline Line - Hidden on mobile, visible on desktop */}
            <div className="hidden md:block absolute top-24 left-1/2 transform -translate-x-1/2 w-full max-w-4xl">
              <div className="relative h-1">
                <div className="absolute inset-0 bg-gradient-to-r from-green-400 via-blue-400 to-purple-400 rounded-full"></div>

                {/* Çizgi üzerindeki statik noktalar */}
                <div className="absolute left-1/6 top-1/2 transform -translate-x-1/2 -translate-y-1/2 w-4 h-4 bg-green-500 rounded-full border-2 border-white shadow-md"></div>
                <div className="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 w-4 h-4 bg-blue-500 rounded-full border-2 border-white shadow-md"></div>
                <div
                  className="absolute top-1/2 transform -translate-y-1/2 w-4 h-4 bg-purple-500 rounded-full border-2 border-white shadow-md"
                  style={{
                    left: "100%",
                    transform: "translateX(-100%) translateY(-50%)",
                  }}
                ></div>
              </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
              {/* 1. Adım */}
              <div className="flex flex-col items-center group">
                <div className="relative mb-8">
                  <div className="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-xl group-hover:shadow-2xl transition-all duration-300 group-hover:scale-110 border-4 border-white">
                    <span className="text-2xl font-bold text-white">1</span>
                  </div>
                </div>
                <div className="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 group-hover:scale-105 border border-gray-100 h-full flex flex-col">
                  <div className="flex justify-center mb-4">
                    <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                      <svg
                        className="w-8 h-8 text-green-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth={2}
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                        />
                      </svg>
                    </div>
                  </div>
                  <h3 className="text-xl font-bold text-gray-900 mb-4 text-center">
                    Talebinizi Oluşturun
                  </h3>
                  <p className="text-gray-600 leading-relaxed text-center flex-grow">
                    İhtiyacınızı detayları ile birlikte tanımlayın. Hangi
                    hizmeti istediğinizi, ne zaman yapılmasını istediğinizi
                    belirtin.
                  </p>
                  <div className="mt-6 flex justify-center">
                    <div className="w-12 h-1 bg-gradient-to-r from-green-400 to-green-500 rounded-full"></div>
                  </div>
                </div>
              </div>

              {/* 2. Adım */}
              <div className="flex flex-col items-center group">
                <div className="relative mb-8">
                  <div className="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-xl group-hover:shadow-2xl transition-all duration-300 group-hover:scale-110 border-4 border-white">
                    <span className="text-2xl font-bold text-white">2</span>
                  </div>
                </div>
                <div className="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 group-hover:scale-105 border border-gray-100 h-full flex flex-col">
                  <div className="flex justify-center mb-4">
                    <div className="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                      <svg
                        className="w-8 h-8 text-blue-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth={2}
                          d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                        />
                      </svg>
                    </div>
                  </div>
                  <h3 className="text-xl font-bold text-gray-900 mb-4 text-center">
                    Teklifleri Alın
                  </h3>
                  <p className="text-gray-600 leading-relaxed text-center flex-grow">
                    Alanında uzman ustalar size özel teklifler gönderir. Fiyat,
                    süre ve hizmet detaylarını karşılaştırın.
                  </p>
                  <div className="mt-6 flex justify-center">
                    <div className="w-12 h-1 bg-gradient-to-r from-blue-400 to-blue-500 rounded-full"></div>
                  </div>
                </div>
              </div>

              {/* 3. Adım */}
              <div className="flex flex-col items-center group">
                <div className="relative mb-8">
                  <div className="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center shadow-xl group-hover:shadow-2xl transition-all duration-300 group-hover:scale-110 border-4 border-white">
                    <span className="text-2xl font-bold text-white">3</span>
                  </div>
                </div>
                <div className="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 group-hover:scale-105 border border-gray-100 h-full flex flex-col">
                  <div className="flex justify-center mb-4">
                    <div className="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                      <svg
                        className="w-8 h-8 text-purple-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth={2}
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                      </svg>
                    </div>
                  </div>
                  <h3 className="text-xl font-bold text-gray-900 mb-4 text-center">
                    Karşılaştır ve Seç
                  </h3>
                  <p className="text-gray-600 leading-relaxed text-center flex-grow">
                    Müşteri yorumlarını incele, pazarlık yap. En uygun teklifi
                    seçin.
                  </p>
                  <div className="mt-6 flex justify-center">
                    <div className="w-12 h-1 bg-gradient-to-r from-purple-400 to-purple-500 rounded-full"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Hizmet Veren Ol Section */}
      <section className="py-20 bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 text-white relative overflow-hidden">
        {/* Background Pattern */}
        <div className="absolute inset-0 opacity-10">
          <div className="absolute top-10 left-10 w-32 h-32 bg-white rounded-full"></div>
          <div className="absolute top-40 right-20 w-24 h-24 bg-white rounded-full"></div>
          <div className="absolute bottom-20 left-1/4 w-16 h-16 bg-white rounded-full"></div>
          <div className="absolute bottom-40 right-1/3 w-20 h-20 bg-white rounded-full"></div>
        </div>

        <div className="container mx-auto px-4 relative z-10">
          <div className="max-w-6xl mx-auto">
            <div className="grid lg:grid-cols-2 gap-16 items-center">
              {/* Sol Taraf - Metin İçeriği */}
              <div className="text-center lg:text-left">
                <div className="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-6 py-3 mb-8">
                  <span className="text-2xl mr-3">🔧</span>
                  <span className="font-semibold text-lg">
                    Profesyonel Usta Ol
                  </span>
                </div>

                <h2 className="text-4xl lg:text-5xl font-bold mb-6 leading-tight">
                  Uzmanlığınızı
                  <span className="text-yellow-300"> Gösterin </span>
                </h2>

                <p className="text-xl lg:text-2xl mb-8 text-blue-100 leading-relaxed">
                  Müşterilerimize kaliteli hizmet sunun ve güvenilir usta
                  ağımızın bir parçası olun.
                </p>

                {/* Özellikler */}
                <div className="grid md:grid-cols-2 gap-6 mb-10">
                  <div className="flex items-center">
                    <div className="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                      <svg
                        className="w-6 h-6 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth={2}
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                      </svg>
                    </div>
                    <div>
                      <h4 className="font-bold text-lg">Güvenilir Platform</h4>
                      <p className="text-blue-200">Doğrulanmış müşteriler</p>
                    </div>
                  </div>

                  <div className="flex items-center">
                    <div className="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                      <svg
                        className="w-6 h-6 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth={2}
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                        />
                      </svg>
                    </div>
                    <div>
                      <h4 className="font-bold text-lg">Geniş Müşteri Ağı</h4>
                      <p className="text-blue-200">Binlerce aktif kullanıcı</p>
                    </div>
                  </div>

                  <div className="flex items-center">
                    <div className="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                      <svg
                        className="w-6 h-6 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth={2}
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                      </svg>
                    </div>
                    <div>
                      <h4 className="font-bold text-lg">Kolay Yönetim</h4>
                      <p className="text-blue-200">Basit talep sistemi</p>
                    </div>
                  </div>

                  <div className="flex items-center">
                    <div className="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                      <svg
                        className="w-6 h-6 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth={2}
                          d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                      </svg>
                    </div>
                    <div>
                      <h4 className="font-bold text-lg">7/24 Destek</h4>
                      <p className="text-blue-200">Her zaman yanınızda</p>
                    </div>
                  </div>
                </div>

                {/* CTA Butonları */}
                <div className="flex justify-center lg:justify-start">
                  <button className="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-blue-700 bg-white rounded-xl hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <span className="mr-3">🚀</span>
                    Usta Başvurusu Yap
                    <span className="ml-3">→</span>
                  </button>
                </div>
              </div>

              {/* Sağ Taraf - Özellikler */}
              <div className="relative">
                {/* Ana Kart */}
                <div className="bg-white/10 backdrop-blur-lg rounded-3xl p-8 border border-white/20">
                  <div className="text-center mb-8">
                    <h3 className="text-2xl font-bold mb-2">
                      Neden OnlineUsta?
                    </h3>
                    <p className="text-blue-200">
                      Profesyonel hizmet platformu
                    </p>
                  </div>

                  {/* Özellik Listesi */}
                  <div className="space-y-6">
                    <div className="flex items-center bg-white/10 rounded-xl p-4">
                      <div className="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mr-4">
                        <svg
                          className="w-6 h-6 text-white"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth={2}
                            d="M5 13l4 4L19 7"
                          />
                        </svg>
                      </div>
                      <div className="flex-1">
                        <h4 className="font-bold">Kaliteli Müşteriler</h4>
                        <p className="text-blue-200 text-sm">
                          Doğrulanmış ve güvenilir müşteri portföyü
                        </p>
                      </div>
                    </div>

                    <div className="flex items-center bg-white/10 rounded-xl p-4">
                      <div className="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center mr-4">
                        <svg
                          className="w-6 h-6 text-white"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth={2}
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"
                          />
                        </svg>
                      </div>
                      <div className="flex-1">
                        <h4 className="font-bold">Adil Fiyatlandırma</h4>
                        <p className="text-blue-200 text-sm">
                          Rekabetçi ve şeffaf ücret sistemi
                        </p>
                      </div>
                    </div>

                    <div className="flex items-center bg-white/10 rounded-xl p-4">
                      <div className="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center mr-4">
                        <svg
                          className="w-6 h-6 text-white"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth={2}
                            d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                          />
                        </svg>
                      </div>
                      <div className="flex-1">
                        <h4 className="font-bold">7/24 Destek</h4>
                        <p className="text-blue-200 text-sm">
                          Her zaman yanınızda olan teknik destek
                        </p>
                      </div>
                    </div>
                  </div>

                  {/* İstatistik */}
                  <div className="mt-8 pt-6 border-t border-white/20">
                    <div className="grid grid-cols-3 gap-4 text-center">
                      <div>
                        <div className="text-2xl font-bold text-yellow-300">
                          2.500+
                        </div>
                        <div className="text-sm text-blue-200">Aktif Usta</div>
                      </div>
                      <div>
                        <div className="text-2xl font-bold text-green-300">
                          50.000+
                        </div>
                        <div className="text-sm text-blue-200">
                          Tamamlanan İş
                        </div>
                      </div>
                      <div>
                        <div className="text-2xl font-bold text-purple-300">
                          4.8⭐
                        </div>
                        <div className="text-sm text-blue-200">
                          Müşteri Puanı
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                {/* Floating Elements */}
                <div className="absolute -top-4 -right-4 w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center animate-bounce">
                  <span className="text-2xl">⭐</span>
                </div>

                <div className="absolute -bottom-4 -left-4 w-12 h-12 bg-green-400 rounded-full flex items-center justify-center animate-pulse">
                  <span className="text-xl">✨</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Hizmet Bölgelerimiz Section */}
      <section className="py-20 bg-gray-50">
        <div className="container mx-auto px-4">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 mb-6">
              Hizmet Bölgelerimiz
            </h2>
            <p className="text-xl text-gray-600 max-w-4xl mx-auto">
              Türkiye&apos;nin her yerinde güvenilir ustalar! 32 büyük şehirde
              binlerce profesyonel usta ile hizmetinizdeyiz.
              <span className="font-semibold text-green-600">
                {" "}
                Ücretsiz teklif alın, en uygun ustayı seçin.
              </span>
            </p>
          </div>

          <div className="grid grid-cols-4 md:grid-cols-8 gap-4">
            {[
              {
                name: "İstanbul",
                population: "15.5M",
                region: "Marmara",
                icon: "🏙️",
              },
              {
                name: "Ankara",
                population: "5.7M",
                region: "İç Anadolu",
                icon: "🏛️",
              },
              { name: "İzmir", population: "4.4M", region: "Ege", icon: "🌊" },
              {
                name: "Bursa",
                population: "3.1M",
                region: "Marmara",
                icon: "🏔️",
              },
              {
                name: "Antalya",
                population: "2.6M",
                region: "Akdeniz",
                icon: "🏖️",
              },
              {
                name: "Adana",
                population: "2.3M",
                region: "Akdeniz",
                icon: "🌾",
              },
              {
                name: "Konya",
                population: "2.2M",
                region: "İç Anadolu",
                icon: "🕌",
              },
              {
                name: "Gaziantep",
                population: "2.1M",
                region: "Güneydoğu",
                icon: "🥙",
              },
              {
                name: "Şanlıurfa",
                population: "2.1M",
                region: "Güneydoğu",
                icon: "🏺",
              },
              {
                name: "Kocaeli",
                population: "2.0M",
                region: "Marmara",
                icon: "🏭",
              },
              {
                name: "Mersin",
                population: "1.9M",
                region: "Akdeniz",
                icon: "🚢",
              },
              {
                name: "Diyarbakır",
                population: "1.8M",
                region: "Güneydoğu",
                icon: "🏰",
              },
              {
                name: "Hatay",
                population: "1.7M",
                region: "Akdeniz",
                icon: "🌿",
              },
              { name: "Manisa", population: "1.4M", region: "Ege", icon: "🍇" },
              {
                name: "Kayseri",
                population: "1.4M",
                region: "İç Anadolu",
                icon: "⛰️",
              },
              {
                name: "Samsun",
                population: "1.4M",
                region: "Karadeniz",
                icon: "🌊",
              },
              {
                name: "Balıkesir",
                population: "1.2M",
                region: "Marmara",
                icon: "🐟",
              },
              {
                name: "K.Maraş",
                population: "1.2M",
                region: "Akdeniz",
                icon: "🍯",
              },
              {
                name: "Van",
                population: "1.1M",
                region: "Doğu Anadolu",
                icon: "🏔️",
              },
              { name: "Aydın", population: "1.1M", region: "Ege", icon: "🫒" },
              {
                name: "Batman",
                population: "0.6M",
                region: "Güneydoğu",
                icon: "🛢️",
              },
              {
                name: "Denizli",
                population: "1.0M",
                region: "Ege",
                icon: "♨️",
              },
              {
                name: "Tekirdağ",
                population: "1.0M",
                region: "Marmara",
                icon: "🌻",
              },
              { name: "Muğla", population: "1.0M", region: "Ege", icon: "🏖️" },
              {
                name: "Eskişehir",
                population: "0.9M",
                region: "İç Anadolu",
                icon: "🎓",
              },
              {
                name: "Trabzon",
                population: "0.8M",
                region: "Karadeniz",
                icon: "🌲",
              },
              {
                name: "Malatya",
                population: "0.8M",
                region: "Doğu Anadolu",
                icon: "🍑",
              },
              {
                name: "Erzurum",
                population: "0.8M",
                region: "Doğu Anadolu",
                icon: "❄️",
              },
              {
                name: "Ordu",
                population: "0.8M",
                region: "Karadeniz",
                icon: "🌰",
              },
              {
                name: "Sakarya",
                population: "1.0M",
                region: "Marmara",
                icon: "🌊",
              },
              {
                name: "Elazığ",
                population: "0.6M",
                region: "Doğu Anadolu",
                icon: "🌉",
              },
              {
                name: "Zonguldak",
                population: "0.6M",
                region: "Karadeniz",
                icon: "⛏️",
              },
            ].map((city) => (
              <div
                key={city.name}
                className="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 hover:scale-105 border border-gray-100 p-4 cursor-pointer"
              >
                <div className="text-center">
                  <div className="text-3xl mb-3 group-hover:scale-110 transition-transform duration-300">
                    {city.icon}
                  </div>
                  <h3 className="text-lg font-bold text-gray-900 mb-1">
                    {city.name}
                  </h3>
                  <p className="text-sm text-gray-500 mb-1">
                    {city.population} • {city.region}
                  </p>
                  <div className="mt-3 inline-flex items-center text-green-600 font-semibold text-sm group-hover:text-green-700">
                    Ustalar
                    <span className="ml-1 group-hover:translate-x-1 transition-transform">
                      →
                    </span>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Hakkımızda Section */}
      <section className="py-20 bg-white">
        <div className="container mx-auto px-4">
          <div className="max-w-6xl mx-auto">
            <div className="grid lg:grid-cols-2 gap-16 items-center">
              {/* Sol Taraf - Metin İçeriği */}
              <div>
                <h2 className="text-4xl font-bold text-gray-900 mb-6">
                  Hakkımızda
                </h2>
                <p className="text-xl text-gray-600 mb-8 leading-relaxed">
                  OnlineUsta, Türkiye&apos;nin güvenilir usta bulma
                  platformudur. Müşteriler ve profesyonel ustalar arasında köprü
                  kurarak, kaliteli hizmet almanızı sağlıyoruz.
                </p>

                <div className="space-y-4 mb-8">
                  <div className="flex items-center">
                    <div className="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                      <svg
                        className="w-3 h-3 text-white"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <path
                          fillRule="evenodd"
                          d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                          clipRule="evenodd"
                        />
                      </svg>
                    </div>
                    <span className="text-gray-700">
                      32 şehirde binlerce doğrulanmış usta
                    </span>
                  </div>

                  <div className="flex items-center">
                    <div className="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                      <svg
                        className="w-3 h-3 text-white"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <path
                          fillRule="evenodd"
                          d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                          clipRule="evenodd"
                        />
                      </svg>
                    </div>
                    <span className="text-gray-700">
                      Ücretsiz teklif alma hizmeti
                    </span>
                  </div>

                  <div className="flex items-center">
                    <div className="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                      <svg
                        className="w-3 h-3 text-white"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <path
                          fillRule="evenodd"
                          d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                          clipRule="evenodd"
                        />
                      </svg>
                    </div>
                    <span className="text-gray-700">7/24 müşteri desteği</span>
                  </div>
                </div>
              </div>

              {/* Sağ Taraf - Neden OnlineUsta */}
              <div className="bg-gray-50 rounded-2xl p-8">
                <h3 className="text-2xl font-bold text-gray-900 mb-8 text-center">
                  Neden OnlineUsta?
                </h3>

                <div className="space-y-6">
                  <div className="flex items-start">
                    <div className="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                      <svg
                        className="w-6 h-6 text-blue-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth={2}
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                      </svg>
                    </div>
                    <div>
                      <h4 className="font-bold text-gray-900 mb-1">
                        Güvenilir Ustalar
                      </h4>
                      <p className="text-gray-600 text-sm">
                        Kimlik ve yetkinlik kontrolünden geçmiş profesyoneller
                      </p>
                    </div>
                  </div>

                  <div className="flex items-start">
                    <div className="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                      <svg
                        className="w-6 h-6 text-green-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth={2}
                          d="M13 10V3L4 14h7v7l9-11h-7z"
                        />
                      </svg>
                    </div>
                    <div>
                      <h4 className="font-bold text-gray-900 mb-1">
                        Hızlı Eşleşme
                      </h4>
                      <p className="text-gray-600 text-sm">
                        Dakikalar içinde uygun ustalardan teklif alın
                      </p>
                    </div>
                  </div>

                  <div className="flex items-start">
                    <div className="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                      <svg
                        className="w-6 h-6 text-purple-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth={2}
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                        />
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth={2}
                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                      </svg>
                    </div>
                    <div>
                      <h4 className="font-bold text-gray-900 mb-1">
                        Geniş Kapsama
                      </h4>
                      <p className="text-gray-600 text-sm">
                        Türkiye&apos;nin 32 büyük şehrinde hizmet
                      </p>
                    </div>
                  </div>

                  <div className="flex items-start">
                    <div className="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                      <svg
                        className="w-6 h-6 text-orange-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth={2}
                          d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                      </svg>
                    </div>
                    <div>
                      <h4 className="font-bold text-gray-900 mb-1">
                        Sürekli Destek
                      </h4>
                      <p className="text-gray-600 text-sm">
                        Her adımda yanınızda olan müşteri hizmetleri
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <Footer />
    </main>
  );
}
