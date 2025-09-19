import { MapPin, Users, Building2, Clock } from "lucide-react"
import Link from "next/link"

export default function ServiceAreas() {
  const majorCities = [
    {
      name: "الرياض",
      nameEn: "Riyadh",
      providers: "2,150",
      services: "8,340",
      image: "🏙️",
      popular: ["التنظيف", "الصيانة", "الكهرباء"],
      href: "/ar/riyadh"
    },
    {
      name: "جدة",
      nameEn: "Jeddah", 
      providers: "1,876",
      services: "6,890",
      image: "🌊",
      popular: ["السباكة", "التكييف", "النجارة"],
      href: "/ar/jeddah"
    },
    {
      name: "الدمام",
      nameEn: "Dammam",
      providers: "1,234",
      services: "4,560",
      image: "🏭",
      popular: ["الدهانات", "البستنة", "التنظيف"],
      href: "/ar/dammam"
    },
    {
      name: "مكة المكرمة",
      nameEn: "Makkah",
      providers: "987",
      services: "3,210",
      image: "🕋",
      popular: ["التنظيف", "الصيانة", "السباكة"],
      href: "/ar/makkah"
    }
  ]

  const allCities = [
    "المدينة المنورة", "الطائف", "تبوك", "بريدة", "الخبر", "خميس مشيط",
    "الهفوف", "المبرز", "حائل", "نجران", "الجبيل", "ينبع", "أبها", "عرعر",
    "سكاكا", "جيزان", "القطيف", "الباحة", "رفحاء", "تيماء", "ضباء"
  ]

  const stats = [
    {
      icon: MapPin,
      number: "25+",
      label: "مدينة",
      color: "text-blue-600"
    },
    {
      icon: Users,
      number: "5,000+",
      label: "مقدم خدمة",
      color: "text-green-600"
    },
    {
      icon: Building2,
      number: "50,000+",
      label: "عميل راضي",
      color: "text-purple-600"
    },
    {
      icon: Clock,
      number: "24/7",
      label: "دعم فني",
      color: "text-orange-600"
    }
  ]

  return (
    <section className="py-16 bg-white" id="service-areas">
      <div className="container mx-auto px-4">
        {/* Section Header */}
        <div className="text-center mb-12">
          <h2 className="text-4xl font-bold text-navy-800 mb-4">
            مناطق الخدمة
          </h2>
          <p className="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
            نغطي جميع المناطق الرئيسية في المملكة العربية السعودية بشبكة واسعة من مقدمي الخدمات المعتمدين
          </p>
          <div className="w-24 h-1 bg-gradient-to-r from-primary-500 to-gold-500 mx-auto mt-6 rounded-full"></div>
        </div>

        {/* Stats Row */}
        <div className="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
          {stats.map((stat, index) => {
            const IconComponent = stat.icon
            return (
              <div key={index} className="text-center">
                <div className={`inline-flex p-3 rounded-full bg-gray-50 mb-3`}>
                  <IconComponent className={`w-6 h-6 ${stat.color}`} />
                </div>
                <div className="text-2xl font-bold text-navy-800 mb-1">{stat.number}</div>
                <div className="text-gray-600 text-sm">{stat.label}</div>
              </div>
            )
          })}
        </div>

        {/* Major Cities */}
        <div className="mb-12">
          <h3 className="text-2xl font-bold text-navy-800 mb-8 text-center">
            المدن الرئيسية
          </h3>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {majorCities.map((city, index) => (
              <Link key={index} href={city.href} className="group">
                <div className="bg-white border border-gray-200 rounded-xl p-6 hover:border-primary-300 hover:shadow-lg transition-all duration-300 h-full">
                  {/* City Header */}
                  <div className="text-center mb-4">
                    <div className="text-4xl mb-2">{city.image}</div>
                    <h4 className="text-xl font-bold text-navy-800 group-hover:text-primary-700 transition-colors duration-300">
                      {city.name}
                    </h4>
                    <p className="text-gray-500 text-sm">{city.nameEn}</p>
                  </div>

                  {/* Stats */}
                  <div className="grid grid-cols-2 gap-4 mb-4">
                    <div className="text-center">
                      <div className="text-lg font-bold text-primary-600">{city.providers}</div>
                      <div className="text-xs text-gray-500">مقدم خدمة</div>
                    </div>
                    <div className="text-center">
                      <div className="text-lg font-bold text-gold-600">{city.services}</div>
                      <div className="text-xs text-gray-500">خدمة مكتملة</div>
                    </div>
                  </div>

                  {/* Popular Services */}
                  <div className="mb-4">
                    <div className="text-sm font-semibold text-gray-700 mb-2">الخدمات الشائعة:</div>
                    <div className="flex flex-wrap gap-1">
                      {city.popular.map((service, serviceIndex) => (
                        <span 
                          key={serviceIndex}
                          className="bg-primary-50 text-primary-700 px-2 py-1 rounded-full text-xs"
                        >
                          {service}
                        </span>
                      ))}
                    </div>
                  </div>

                  {/* Action */}
                  <div className="text-center">
                    <span className="text-primary-600 text-sm font-medium group-hover:text-primary-700 transition-colors duration-300">
                      استكشف الخدمات ←
                    </span>
                  </div>
                </div>
              </Link>
            ))}
          </div>
        </div>

        {/* All Cities */}
        <div className="bg-gray-50 rounded-2xl p-8">
          <h3 className="text-xl font-bold text-navy-800 mb-6 text-center">
            جميع المدن المتاحة
          </h3>
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3">
            {allCities.map((city, index) => (
              <Link 
                key={index}
                href={`/ar/${city.toLowerCase().replace(/\s+/g, '-')}`}
                className="text-center py-3 px-4 bg-white rounded-lg border border-gray-200 hover:border-primary-300 hover:bg-primary-50 transition-all duration-300 text-sm font-medium text-gray-700 hover:text-primary-700"
              >
                {city}
              </Link>
            ))}
          </div>
        </div>

        {/* Expansion Notice */}
        <div className="mt-12 text-center">
          <div className="bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl p-8 text-white">
            <h3 className="text-2xl font-bold mb-4">
              مدينتك غير موجودة؟
            </h3>
            <p className="text-lg mb-6 opacity-90">
              نحن نتوسع باستمرار! سجل اهتمامك وسنخبرك عند وصولنا لمنطقتك
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <button className="bg-white text-primary-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-300">
                اطلب منطقتك
              </button>
              <button className="border border-white border-opacity-50 text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:bg-opacity-10 transition-colors duration-300">
                كن شريكاً معنا
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}
