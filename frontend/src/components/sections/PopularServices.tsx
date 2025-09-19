import { Wrench, Zap, Droplets, PaintBucket, Hammer, Wind, Car, Leaf } from "lucide-react"
import Link from "next/link"

export default function PopularServices() {
  const services = [
    {
      icon: Droplets,
      title: "التنظيف",
      description: "تنظيف المنازل والمكاتب والسجاد",
      serviceCount: "2,543",
      color: "bg-blue-50 text-blue-600",
      href: "/ar/services/cleaning"
    },
    {
      icon: Wrench,
      title: "الصيانة العامة",
      description: "إصلاح وصيانة الأجهزة المنزلية",
      serviceCount: "1,876",
      color: "bg-gray-50 text-gray-600",
      href: "/ar/services/maintenance"
    },
    {
      icon: Zap,
      title: "الكهرباء",
      description: "أعمال كهربائية وإضاءة",
      serviceCount: "1,234",
      color: "bg-yellow-50 text-yellow-600",
      href: "/ar/services/electrical"
    },
    {
      icon: Droplets,
      title: "السباكة",
      description: "إصلاح التسريبات وأعمال السباكة",
      serviceCount: "1,567",
      color: "bg-blue-50 text-blue-600",
      href: "/ar/services/plumbing"
    },
    {
      icon: Wind,
      title: "التكييف",
      description: "صيانة وتركيب أجهزة التكييف",
      serviceCount: "987",
      color: "bg-cyan-50 text-cyan-600",
      href: "/ar/services/hvac"
    },
    {
      icon: PaintBucket,
      title: "الدهانات",
      description: "أعمال الدهان والديكور",
      serviceCount: "756",
      color: "bg-purple-50 text-purple-600",
      href: "/ar/services/painting"
    },
    {
      icon: Hammer,
      title: "النجارة",
      description: "أعمال النجارة والأثاث",
      serviceCount: "654",
      color: "bg-orange-50 text-orange-600",
      href: "/ar/services/carpentry"
    },
    {
      icon: Leaf,
      title: "البستنة",
      description: "تنسيق الحدائق والعناية بالنباتات",
      serviceCount: "432",
      color: "bg-green-50 text-green-600",
      href: "/ar/services/gardening"
    }
  ]

  return (
    <section className="py-16 bg-white" id="popular-services">
      <div className="container mx-auto px-4">
        {/* Section Header */}
        <div className="text-center mb-12">
          <h2 className="text-4xl font-bold text-navy-800 mb-4">
            الخدمات الأكثر طلباً
          </h2>
          <p className="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
            اكتشف أفضل الخدمات المنزلية والمهنية المتاحة في منطقتك من خلال مقدمي خدمات معتمدين
          </p>
          <div className="w-24 h-1 bg-gradient-to-r from-gold-500 to-primary-500 mx-auto mt-6 rounded-full"></div>
        </div>

        {/* Services Grid */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
          {services.map((service, index) => {
            const IconComponent = service.icon
            return (
              <Link 
                key={index}
                href={service.href}
                className="group"
              >
                <div className="bg-white rounded-xl p-6 border border-gray-200 hover:border-primary-300 hover:shadow-lg transition-all duration-300 h-full">
                  {/* Icon and Badge */}
                  <div className="flex items-start justify-between mb-4">
                    <div className={`p-3 rounded-lg ${service.color} group-hover:scale-110 transition-transform duration-300`}>
                      <IconComponent className="w-6 h-6" />
                    </div>
                    <span className="bg-primary-100 text-primary-700 px-2 py-1 rounded-full text-xs font-semibold">
                      {service.serviceCount}+
                    </span>
                  </div>
                  
                  {/* Content */}
                  <h3 className="text-lg font-bold text-navy-800 mb-2 group-hover:text-primary-700 transition-colors duration-300">
                    {service.title}
                  </h3>
                  <p className="text-gray-600 text-sm leading-relaxed">
                    {service.description}
                  </p>
                  
                  {/* Arrow */}
                  <div className="mt-4 flex items-center text-primary-600 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <span>استكشف الخدمة</span>
                    <svg className="w-4 h-4 mr-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                    </svg>
                  </div>
                </div>
              </Link>
            )
          })}
        </div>

        {/* View All Services CTA */}
        <div className="text-center">
          <Link 
            href="/ar/services"
            className="inline-flex items-center bg-gradient-to-r from-primary-500 to-primary-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-lg hover:shadow-xl group"
          >
            <span>استكشف جميع الخدمات</span>
            <svg className="w-5 h-5 mr-3 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
            </svg>
          </Link>
        </div>

        {/* Stats */}
        <div className="mt-16 bg-gradient-to-r from-navy-800 to-navy-900 rounded-2xl p-8 text-white">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
              <div className="text-3xl font-bold text-gold-400 mb-2">10,000+</div>
              <div className="text-sm opacity-90">خدمة مكتملة</div>
            </div>
            <div>
              <div className="text-3xl font-bold text-gold-400 mb-2">5,000+</div>
              <div className="text-sm opacity-90">مقدم خدمة</div>
            </div>
            <div>
              <div className="text-3xl font-bold text-gold-400 mb-2">25+</div>
              <div className="text-sm opacity-90">مدينة</div>
            </div>
            <div>
              <div className="text-3xl font-bold text-gold-400 mb-2">4.8★</div>
              <div className="text-sm opacity-90">تقييم العملاء</div>
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}
