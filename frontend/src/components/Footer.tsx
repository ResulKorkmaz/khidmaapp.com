import Image from "next/image"
import Link from "next/link"
import { MapPin, Phone, Mail, Clock, Facebook, Twitter, Instagram, Linkedin } from "lucide-react"

export default function Footer() {
  const serviceCategories = [
    { name: "التنظيف", href: "/ar/services/cleaning" },
    { name: "السباكة", href: "/ar/services/plumbing" },
    { name: "الكهرباء", href: "/ar/services/electrical" },
    { name: "التكييف", href: "/ar/services/hvac" },
    { name: "الصيانة", href: "/ar/services/maintenance" },
    { name: "الدهانات", href: "/ar/services/painting" }
  ]

  const cities = [
    { name: "الرياض", href: "/ar/riyadh" },
    { name: "جدة", href: "/ar/jeddah" },
    { name: "الدمام", href: "/ar/dammam" },
    { name: "مكة المكرمة", href: "/ar/makkah" },
    { name: "المدينة المنورة", href: "/ar/madinah" },
    { name: "الطائف", href: "/ar/taif" }
  ]

  const companyLinks = [
    { name: "من نحن", href: "/ar/about" },
    { name: "كيف نعمل", href: "/ar/how-it-works" },
    { name: "الشروط والأحكام", href: "/ar/terms" },
    { name: "سياسة الخصوصية", href: "/ar/privacy" },
    { name: "اتصل بنا", href: "/ar/contact" },
    { name: "المساعدة", href: "/ar/help" }
  ]

  const forProviders = [
    { name: "انضم كمقدم خدمة", href: "/ar/join-provider" },
    { name: "تطبيق المقدمين", href: "/ar/provider-app" },
    { name: "مركز المساعدة", href: "/ar/provider-help" },
    { name: "التدريب", href: "/ar/training" },
    { name: "الشراكات", href: "/ar/partnerships" },
    { name: "مدونة المقدمين", href: "/ar/provider-blog" }
  ]

  const socialLinks = [
    { icon: Facebook, href: "https://facebook.com/khidmaapp", color: "hover:text-blue-600" },
    { icon: Twitter, href: "https://twitter.com/khidmaapp", color: "hover:text-blue-400" },
    { icon: Instagram, href: "https://instagram.com/khidmaapp", color: "hover:text-pink-600" },
    { icon: Linkedin, href: "https://linkedin.com/company/khidmaapp", color: "hover:text-blue-700" }
  ]

  return (
    <footer className="bg-navy-900 text-white">
      {/* Main Footer */}
      <div className="container mx-auto px-4 py-12">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
          {/* Company Info */}
          <div className="lg:col-span-2">
            <div className="mb-6">
              <div className="bg-primary-500 p-3 rounded-lg shadow-md inline-block mb-4">
                <Image 
                  src="/logo.png" 
                  alt="خدمة أب (KhidmaApp) Logo" 
                  width={120} 
                  height={48} 
                  className="h-8 w-auto"
                />
              </div>
              <h3 className="text-xl font-bold mb-3">خدمة أب (KhidmaApp)</h3>
              <p className="text-gray-300 leading-relaxed mb-4">
                منصة الخدمات الرائدة في المملكة العربية السعودية. نربط بين العملاء ومقدمي الخدمات المهرة 
                لتوفير تجربة استثنائية وموثوقة في جميع أنحاء المملكة.
              </p>
            </div>

            {/* Contact Info */}
            <div className="space-y-3">
              <div className="flex items-center">
                <MapPin className="w-5 h-5 text-gold-400 mr-3 flex-shrink-0" />
                <span className="text-gray-300">الرياض، المملكة العربية السعودية</span>
              </div>
              <div className="flex items-center">
                <Phone className="w-5 h-5 text-gold-400 mr-3 flex-shrink-0" />
                <span className="text-gray-300" dir="ltr">+966 11 123 4567</span>
              </div>
              <div className="flex items-center">
                <Mail className="w-5 h-5 text-gold-400 mr-3 flex-shrink-0" />
                <span className="text-gray-300">info@khidmaapp.com</span>
              </div>
              <div className="flex items-center">
                <Clock className="w-5 h-5 text-gold-400 mr-3 flex-shrink-0" />
                <span className="text-gray-300">24/7 خدمة العملاء</span>
              </div>
            </div>
          </div>

          {/* Services */}
          <div>
            <h4 className="text-lg font-bold mb-4 text-gold-400">الخدمات</h4>
            <ul className="space-y-2">
              {serviceCategories.map((service, index) => (
                <li key={index}>
                  <Link 
                    href={service.href}
                    className="text-gray-300 hover:text-primary-400 transition-colors duration-300"
                  >
                    {service.name}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Cities */}
          <div>
            <h4 className="text-lg font-bold mb-4 text-gold-400">المدن</h4>
            <ul className="space-y-2">
              {cities.map((city, index) => (
                <li key={index}>
                  <Link 
                    href={city.href}
                    className="text-gray-300 hover:text-primary-400 transition-colors duration-300"
                  >
                    {city.name}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Company */}
          <div>
            <h4 className="text-lg font-bold mb-4 text-gold-400">الشركة</h4>
            <ul className="space-y-2 mb-6">
              {companyLinks.map((link, index) => (
                <li key={index}>
                  <Link 
                    href={link.href}
                    className="text-gray-300 hover:text-primary-400 transition-colors duration-300"
                  >
                    {link.name}
                  </Link>
                </li>
              ))}
            </ul>

            <h4 className="text-lg font-bold mb-4 text-gold-400">للمقدمين</h4>
            <ul className="space-y-2">
              {forProviders.slice(0, 3).map((link, index) => (
                <li key={index}>
                  <Link 
                    href={link.href}
                    className="text-gray-300 hover:text-primary-400 transition-colors duration-300"
                  >
                    {link.name}
                  </Link>
                </li>
              ))}
            </ul>
          </div>
        </div>

        {/* Provider CTA */}
        <div className="mt-12 pt-8 border-t border-gray-700">
          <div className="text-center bg-gradient-to-r from-primary-600 to-primary-700 rounded-2xl p-8">
            <h4 className="text-2xl font-bold mb-3 text-white">
              هل تريد كسب دخل إضافي؟
            </h4>
            <p className="text-primary-100 mb-6 text-lg max-w-2xl mx-auto">
              انضم إلى آلاف مقدمي الخدمات واحصل على عملاء جدد كل يوم
            </p>
            <Link 
              href="/ar/provider/join"
              className="inline-block bg-white text-primary-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105"
            >
              انضم كمقدم خدمة الآن
            </Link>
          </div>
        </div>

        {/* Newsletter */}
        <div className="mt-12 pt-8 border-t border-gray-700">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <div>
              <h4 className="text-xl font-bold mb-3 text-gold-400">
                اشترك في النشرة الإخبارية
              </h4>
              <p className="text-gray-300 mb-4">
                احصل على آخر التحديثات والعروض الخاصة والنصائح المفيدة
              </p>
            </div>
            <div className="flex flex-col sm:flex-row gap-3">
              <input 
                type="email"
                placeholder="أدخل بريدك الإلكتروني"
                className="flex-1 px-4 py-3 rounded-lg bg-gray-800 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:border-primary-500"
              />
              <button className="bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300 whitespace-nowrap">
                اشترك الآن
              </button>
            </div>
          </div>
        </div>

        {/* App Download */}
        <div className="mt-8 pt-8 border-t border-gray-700">
          <div className="text-center lg:text-right">
            <h4 className="text-lg font-bold mb-4 text-gold-400">
              حمل التطبيق الآن
            </h4>
            <div className="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
              <Link href="#" className="inline-flex items-center bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition-colors duration-300">
                <div className="mr-3">
                  <div className="text-xs">متوفر على</div>
                  <div className="text-lg font-semibold">App Store</div>
                </div>
                <svg className="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                </svg>
              </Link>
              <Link href="#" className="inline-flex items-center bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition-colors duration-300">
                <div className="mr-3">
                  <div className="text-xs">احصل عليه من</div>
                  <div className="text-lg font-semibold">Google Play</div>
                </div>
                <svg className="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.53,12.9 20.18,13.18L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/>
                </svg>
              </Link>
            </div>
          </div>
        </div>
      </div>

      {/* Bottom Footer */}
      <div className="bg-navy-950 py-6">
        <div className="container mx-auto px-4">
          <div className="flex flex-col md:flex-row justify-between items-center">
            <div className="text-gray-400 text-sm mb-4 md:mb-0">
              © 2024 خدمة أب (KhidmaApp). جميع الحقوق محفوظة.
            </div>
            
            {/* Social Links */}
            <div className="flex items-center space-x-6 rtl:space-x-reverse">
              <span className="text-gray-400 text-sm mr-4">تابعنا:</span>
              {socialLinks.map((social, index) => {
                const IconComponent = social.icon
                return (
                  <Link 
                    key={index}
                    href={social.href}
                    target="_blank"
                    rel="noopener noreferrer"
                    className={`text-gray-400 ${social.color} transition-colors duration-300`}
                  >
                    <IconComponent className="w-5 h-5" />
                  </Link>
                )
              })}
            </div>
          </div>
        </div>
      </div>
    </footer>
  )
}
