import { Search, Users, MessageSquare, CheckCircle, ArrowLeft } from "lucide-react"
import Link from "next/link"

export default function HowItWorks() {
  const steps = [
    {
      icon: Search,
      title: "ابحث عن الخدمة",
      description: "اختر نوع الخدمة التي تحتاجها من بين مئات الفئات المتاحة",
      details: [
        "تصفح الخدمات حسب الفئة",
        "أو ابحث مباشرة عن ما تريد",
        "اختر موقعك وحدد التفاصيل"
      ],
      color: "from-blue-500 to-blue-600",
      bgColor: "bg-blue-50",
      iconColor: "text-blue-600"
    },
    {
      icon: Users,
      title: "احصل على عروض",
      description: "سيتواصل معك مقدمو الخدمات المتخصصون ويقدمون عروضهم",
      details: [
        "عروض أسعار مفصلة",
        "مقدمو خدمات معتمدون",
        "مقارنة التقييمات والأسعار"
      ],
      color: "from-green-500 to-green-600",
      bgColor: "bg-green-50",
      iconColor: "text-green-600"
    },
    {
      icon: MessageSquare,
      title: "تواصل واختر",
      description: "تحدث مع مقدمي الخدمات واختر الأنسب لك ولميزانيتك",
      details: [
        "محادثة مباشرة مع المقدمين",
        "ناقش التفاصيل والأسعار",
        "اختر العرض الأفضل"
      ],
      color: "from-purple-500 to-purple-600",
      bgColor: "bg-purple-50",
      iconColor: "text-purple-600"
    },
    {
      icon: CheckCircle,
      title: "اكمل واستمتع",
      description: "احصل على الخدمة في الوقت المحدد وقيم تجربتك",
      details: [
        "خدمة في الوقت المناسب",
        "جودة مضمونة",
        "قيم التجربة وساعد الآخرين"
      ],
      color: "from-gold-500 to-yellow-600",
      bgColor: "bg-yellow-50",
      iconColor: "text-yellow-600"
    }
  ]

  return (
    <section className="py-16 bg-gray-50" id="how-it-works">
      <div className="container mx-auto px-4">
        {/* Section Header */}
        <div className="text-center mb-16">
          <h2 className="text-4xl font-bold text-navy-800 mb-4">
            كيف يعمل خدمة أب (KhidmaApp)؟
          </h2>
          <p className="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
            عملية بسيطة وسريعة للحصول على أفضل الخدمات في منطقتك خلال دقائق معدودة
          </p>
          <div className="w-24 h-1 bg-gradient-to-r from-primary-500 to-gold-500 mx-auto mt-6 rounded-full"></div>
        </div>

        {/* Steps */}
        <div className="grid grid-cols-1 lg:grid-cols-4 gap-8 relative">
          {/* Connection Lines for Desktop */}
          <div className="hidden lg:block absolute top-16 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent z-0"></div>
          
          {steps.map((step, index) => {
            const IconComponent = step.icon
            return (
              <div key={index} className="relative z-10">
                {/* Mobile Connection Line */}
                {index < steps.length - 1 && (
                  <div className="lg:hidden absolute top-32 left-1/2 transform -translate-x-1/2 w-px h-16 bg-gray-300"></div>
                )}
                
                <div className="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 h-full">
                  {/* Step Number */}
                  <div className="flex items-center justify-between mb-6">
                    <div className={`w-12 h-12 bg-gradient-to-r ${step.color} rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg`}>
                      {index + 1}
                    </div>
                    <div className={`p-3 rounded-full ${step.bgColor}`}>
                      <IconComponent className={`w-6 h-6 ${step.iconColor}`} />
                    </div>
                  </div>
                  
                  {/* Content */}
                  <h3 className="text-xl font-bold text-navy-800 mb-3">
                    {step.title}
                  </h3>
                  <p className="text-gray-600 mb-4 leading-relaxed">
                    {step.description}
                  </p>
                  
                  {/* Details List */}
                  <ul className="space-y-2">
                    {step.details.map((detail, detailIndex) => (
                      <li key={detailIndex} className="flex items-center text-sm text-gray-500">
                        <div className="w-1.5 h-1.5 bg-primary-500 rounded-full mr-2 flex-shrink-0"></div>
                        {detail}
                      </li>
                    ))}
                  </ul>
                </div>
              </div>
            )
          })}
        </div>

        {/* Video/Demo Section */}
        <div className="mt-16 bg-white rounded-2xl p-8 shadow-lg">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <div>
              <h3 className="text-2xl font-bold text-navy-800 mb-4">
                شاهد كيف يعمل خدمة أب (KhidmaApp)
              </h3>
              <p className="text-gray-600 mb-6 leading-relaxed">
                جرب منصتنا بنفسك واكتشف مدى سهولة الحصول على الخدمات التي تحتاجها. 
                آلاف العملاء يثقون بنا يومياً للحصول على أفضل الخدمات.
              </p>
              <div className="flex flex-col sm:flex-row gap-4">
                <button className="bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                  جرب الآن مجاناً
                </button>
                <button className="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-all duration-300">
                  شاهد العرض التوضيحي
                </button>
              </div>
            </div>
            
            <div className="relative">
              {/* Placeholder for video/demo */}
              <div className="bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl p-8 text-white text-center aspect-video flex items-center justify-center">
                <div>
                  <div className="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg className="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z"/>
                    </svg>
                  </div>
                  <p className="text-lg font-semibold">عرض توضيحي تفاعلي</p>
                  <p className="text-sm opacity-90 mt-2">شاهد كيف تعمل المنصة خطوة بخطوة</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Success Stories */}
        <div className="mt-16 text-center">
          <h3 className="text-2xl font-bold text-navy-800 mb-8">
            قصص نجاح عملائنا
          </h3>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {[
              { metric: "98%", label: "رضا العملاء" },
              { metric: "< 5 دقائق", label: "متوسط وقت الاستجابة" },
              { metric: "24/7", label: "دعم فني متاح" }
            ].map((stat, index) => (
              <div key={index} className="bg-white rounded-xl p-6 shadow-md">
                <div className="text-3xl font-bold text-primary-600 mb-2">{stat.metric}</div>
                <div className="text-gray-600">{stat.label}</div>
              </div>
            ))}
          </div>
        </div>

        {/* Provider CTA */}
        <div className="mt-16 bg-gradient-to-r from-navy-800 to-navy-900 rounded-2xl p-8 text-center text-white">
          <h3 className="text-3xl font-bold mb-4">
            هل أنت مقدم خدمة؟
          </h3>
          <p className="text-xl mb-6 text-gray-300 max-w-2xl mx-auto">
            انضم إلى شبكتنا من مقدمي الخدمات المحترفين واحصل على عملاء جدد يومياً
          </p>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {[
              { title: "دخل إضافي", desc: "احصل على دخل ثابت من خلال تقديم خدماتك" },
              { title: "عملاء موثوقين", desc: "نضمن لك التعامل مع عملاء موثوقين ومؤمنين" },
              { title: "دعم كامل", desc: "نقدم لك الدعم الكامل والتدريب المطلوب" }
            ].map((benefit, index) => (
              <div key={index} className="bg-white bg-opacity-10 rounded-lg p-4">
                <h4 className="font-bold text-gold-400 mb-2">{benefit.title}</h4>
                <p className="text-sm text-gray-300">{benefit.desc}</p>
              </div>
            ))}
          </div>
          <Link 
            href="/ar/provider/join"
            className="inline-block bg-gradient-to-r from-primary-500 to-primary-600 text-white px-8 py-4 rounded-lg font-bold text-lg hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105"
          >
            انضم كمقدم خدمة الآن
          </Link>
        </div>
      </div>
    </section>
  )
}
