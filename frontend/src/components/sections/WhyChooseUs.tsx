import { Shield, Users, Clock, Star, CheckCircle, HeadphonesIcon } from "lucide-react"

export default function WhyChooseUs() {
  const features = [
    {
      icon: Shield,
      title: "أمان مضمون",
      description: "جميع مقدمي الخدمات معتمدون ومفحوصون بعناية لضمان جودة الخدمة",
      color: "text-primary-500"
    },
    {
      icon: Users,
      title: "شبكة واسعة",
      description: "أكثر من 5000 مقدم خدمة معتمد في جميع أنحاء المملكة العربية السعودية",
      color: "text-gold-500"
    },
    {
      icon: Clock,
      title: "سرعة في التنفيذ",
      description: "احصل على عروض أسعار خلال دقائق واختر الأنسب لك",
      color: "text-navy-500"
    },
    {
      icon: Star,
      title: "تقييمات حقيقية",
      description: "نظام تقييم شفاف يساعدك في اختيار أفضل مقدمي الخدمات",
      color: "text-primary-500"
    },
    {
      icon: CheckCircle,
      title: "ضمان الجودة",
      description: "ضمان استرداد الأموال في حالة عدم الرضا عن الخدمة المقدمة",
      color: "text-gold-500"
    },
    {
      icon: HeadphonesIcon,
      title: "دعم على مدار الساعة",
      description: "فريق دعم فني متاح 24/7 لمساعدتك في أي وقت",
      color: "text-navy-500"
    }
  ]

  return (
    <section className="py-16 bg-gray-50" id="why-choose-us">
      <div className="container mx-auto px-4">
        {/* Section Header */}
        <div className="text-center mb-12">
          <h2 className="text-4xl font-bold text-navy-800 mb-4">
            لماذا تختار خدمة أب (KhidmaApp)؟
          </h2>
          <p className="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
            نحن نربط بين العملاء ومقدمي الخدمات المهرة لتوفير تجربة استثنائية وموثوقة في جميع أنحاء المملكة
          </p>
          <div className="w-24 h-1 bg-gradient-to-r from-primary-500 to-gold-500 mx-auto mt-6 rounded-full"></div>
        </div>

        {/* Features Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {features.map((feature, index) => {
            const IconComponent = feature.icon
            return (
              <div 
                key={index}
                className="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-primary-200 group"
              >
                <div className="flex flex-col items-center text-center">
                  {/* Icon */}
                  <div className={`p-4 rounded-full bg-gray-50 group-hover:bg-primary-50 transition-colors duration-300 mb-6`}>
                    <IconComponent className={`w-8 h-8 ${feature.color} group-hover:text-primary-600 transition-colors duration-300`} />
                  </div>
                  
                  {/* Title */}
                  <h3 className="text-xl font-bold text-navy-800 mb-4 group-hover:text-primary-700 transition-colors duration-300">
                    {feature.title}
                  </h3>
                  
                  {/* Description */}
                  <p className="text-gray-600 leading-relaxed">
                    {feature.description}
                  </p>
                </div>
              </div>
            )
          })}
        </div>

        {/* Call to Action */}
        <div className="text-center mt-12">
          <div className="bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl p-8 text-white shadow-xl">
            <h3 className="text-2xl font-bold mb-4">جاهز للبدء؟</h3>
            <p className="text-lg mb-6 opacity-90">
              انضم إلى آلاف العملاء الراضين واحصل على خدمة استثنائية اليوم
            </p>
            <button className="bg-gold-500 hover:bg-gold-600 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-300 shadow-lg hover:shadow-xl">
              ابدأ الآن مجاناً
            </button>
          </div>
        </div>
      </div>
    </section>
  )
}
