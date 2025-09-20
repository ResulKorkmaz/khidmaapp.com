'use client'

import { useState, useEffect, useRef, useCallback } from 'react'
import { Search, X, Clock, TrendingUp } from 'lucide-react'
import Link from 'next/link'

interface ServiceCategory {
  id: string
  name_ar: string
  name_en: string
  slug: string
  icon: string
  keywords: string[]
  count: number
  isPopular?: boolean
}

interface SearchInputProps {
  placeholder?: string
  onSearch?: (query: string) => void
  className?: string
}

export default function SearchInput({ 
  placeholder = "ما نوع الخدمة التي تحتاجها؟", 
  onSearch, 
  className = "" 
}: SearchInputProps) {
  const [query, setQuery] = useState('')
  const [isOpen, setIsOpen] = useState(false)
  const [filteredCategories, setFilteredCategories] = useState<ServiceCategory[]>([])
  const [selectedIndex, setSelectedIndex] = useState(-1)
  const [recentSearches, setRecentSearches] = useState<string[]>([])
  
  const inputRef = useRef<HTMLInputElement>(null)
  const dropdownRef = useRef<HTMLDivElement>(null)

  // Comprehensive service categories - فئات الخدمات الشاملة
  const staticCategories: ServiceCategory[] = [
    // تنظيف وصيانة المنزل - Home Cleaning & Maintenance
    {
      id: '1',
      name_ar: 'تنظيف المنازل',
      name_en: 'Home Cleaning',
      slug: 'home-cleaning',
      icon: '',
      keywords: ['تنظيف', 'منازل', 'نظافة', 'ev temizliği', 'home cleaning', 'house cleaning'],
      count: 2847,
      isPopular: true
    },
    {
      id: '2', 
      name_ar: 'تنظيف المنازل الفارغة',
      name_en: 'Empty House Cleaning',
      slug: 'empty-house-cleaning',
      icon: '',
      keywords: ['تنظيف', 'منازل فارغة', 'نظافة عميقة', 'boş ev temizliği', 'empty house', 'deep cleaning'],
      count: 1234,
      isPopular: true
    },
    {
      id: '3',
      name_ar: 'خدمات السباكة',
      name_en: 'Plumbing Services',
      slug: 'plumbing',
      icon: '',
      keywords: ['سباكة', 'تسريب', 'أنابيب', 'tesisatçı', 'plumbing', 'pipe repair'],
      count: 1567,
      isPopular: true
    },
    {
      id: '4',
      name_ar: 'خدمات الكهرباء',
      name_en: 'Electrical Services',
      slug: 'electrical',
      icon: '',
      keywords: ['كهرباء', 'كهربائي', 'إضاءة', 'elektrikçi', 'electrical', 'electrician'],
      count: 1456,
      isPopular: true
    },
    {
      id: '5',
      name_ar: 'تركيب وصيانة التكييف',
      name_en: 'AC Installation & Service',
      slug: 'ac-service',
      icon: '',
      keywords: ['تكييف', 'تركيب', 'صيانة', 'klima montaj', 'ac installation', 'hvac'],
      count: 1298,
      isPopular: true
    },
    {
      id: '6',
      name_ar: 'إصلاح الأجهزة المنزلية',
      name_en: 'Home Appliance Repair',
      slug: 'appliance-repair',
      icon: '',
      keywords: ['أجهزة منزلية', 'إصلاح', 'beyaz eşya', 'appliance repair', 'home appliances'],
      count: 1123,
      isPopular: true
    },
    {
      id: '7',
      name_ar: 'الدهان والطلاء',
      name_en: 'Painting & Decorating',
      slug: 'painting',
      icon: '',
      keywords: ['دهان', 'طلاء', 'بادانا', 'boya badana', 'painting', 'decorating'],
      count: 987,
      isPopular: true
    },
    {
      id: '8',
      name_ar: 'التجديد وتطوير المنزل',
      name_en: 'Home Renovation',
      slug: 'renovation',
      icon: '',
      keywords: ['تجديد', 'تطوير', 'تاديلات', 'tadilat', 'renovation', 'home improvement'],
      count: 856,
      isPopular: true
    },
    {
      id: '9',
      name_ar: 'النجارة وتركيب الأثاث',
      name_en: 'Carpentry & Furniture Assembly',
      slug: 'carpentry',
      icon: '',
      keywords: ['نجارة', 'أثاث', 'تركيب', 'marangozluk', 'carpentry', 'furniture assembly'],
      count: 743,
      isPopular: true
    },
    {
      id: '10',
      name_ar: 'تنظيف السجاد والمفروشات',
      name_en: 'Carpet & Upholstery Cleaning',
      slug: 'carpet-cleaning',
      icon: '',
      keywords: ['سجاد', 'مفروشات', 'ستائر', 'halı temizliği', 'carpet cleaning', 'upholstery'],
      count: 634,
      isPopular: true
    },
    {
      id: '11',
      name_ar: 'مكافحة الحشرات والتطهير',
      name_en: 'Pest Control & Disinfection',
      slug: 'pest-control',
      icon: '',
      keywords: ['حشرات', 'تطهير', 'رش', 'haşere kontrolü', 'pest control', 'disinfection'],
      count: 567,
      isPopular: true
    },
    {
      id: '12',
      name_ar: 'تنسيق الحدائق والمناظر الطبيعية',
      name_en: 'Garden & Landscape Services',
      slug: 'gardening',
      icon: '',
      keywords: ['حدائق', 'تنسيق', 'زراعة', 'bahçe peyzaj', 'gardening', 'landscaping'],
      count: 445,
    },
    {
      id: '13',
      name_ar: 'خدمات التجميل المنزلية',
      name_en: 'Mobile Beauty Services',
      slug: 'beauty-services',
      icon: '',
      keywords: ['تجميل', 'كوافير', 'مانيكير', 'mobil güzellik', 'beauty services', 'mobile salon'],
      count: 398,
    },
    {
      id: '14',
      name_ar: 'خدمات النقل والتخزين',
      name_en: 'Moving & Storage Services',
      slug: 'moving',
      icon: '',
      keywords: ['نقل', 'تخزين', 'انتقال', 'nakliye taşınma', 'moving', 'storage'],
      count: 523,
    },
    {
      id: '15',
      name_ar: 'تركيب الأنظمة الإلكترونية',
      name_en: 'Electronics & IT Installation',
      slug: 'electronics-installation',
      icon: '',
      keywords: ['إلكترونيات', 'واي فاي', 'كاميرات', 'elektronik IT', 'wifi setup', 'cctv'],
      count: 367,
    },
    {
      id: '16',
      name_ar: 'الخدمات المالية والتأمين',
      name_en: 'Financial & Insurance Services',
      slug: 'financial-services',
      icon: '',
      keywords: ['خدمات مالية', 'تأمين', 'استشارات', 'finansal hizmetler', 'financial', 'insurance'],
      count: 234,
    },
    {
      id: '17',
      name_ar: 'التعليم والدروس الخصوصية',
      name_en: 'Education & Private Tutoring',
      slug: 'education',
      icon: '',
      keywords: ['تعليم', 'دروس خصوصية', 'معلم', 'eğitim özel ders', 'education', 'tutoring'],
      count: 456,
    },
    {
      id: '18',
      name_ar: 'التمريض والعلاج الطبيعي المنزلي',
      name_en: 'Home Nursing & Physiotherapy',
      slug: 'home-healthcare',
      icon: '',
      keywords: ['تمريض', 'علاج طبيعي', 'رعاية', 'hemşirelik fizik tedavi', 'nursing', 'physiotherapy'],
      count: 298,
    },
    {
      id: '19',
      name_ar: 'إعداد الطعام والضيافة',
      name_en: 'Food Preparation & Catering',
      slug: 'catering',
      icon: '',
      keywords: ['طعام', 'طبخ', 'ضيافة', 'yemek hazırlama', 'catering', 'cooking'],
      count: 345,
    },
    {
      id: '20',
      name_ar: 'التنظيف الجاف وخدمات الغسيل',
      name_en: 'Dry Cleaning & Laundry Services',
      slug: 'dry-cleaning',
      icon: '',
      keywords: ['تنظيف جاف', 'غسيل', 'ملابس', 'kuru temizleme', 'dry cleaning', 'laundry'],
      count: 312,
    },
    {
      id: '21',
      name_ar: 'المساعدة المنزلية اليومية',
      name_en: 'Daily Household Assistance',
      slug: 'household-help',
      icon: '',
      keywords: ['مساعدة منزلية', 'أعمال يومية', 'ضيافة', 'ev yardımı', 'household help', 'daily assistance'],
      count: 678,
    },
    {
      id: '22',
      name_ar: 'الخدمات القانونية والترجمة',
      name_en: 'Legal & Translation Services',
      slug: 'legal-translation',
      icon: '',
      keywords: ['قانونية', 'ترجمة', 'محامي', 'hukuk tercüme', 'legal services', 'translation'],
      count: 189,
    },
    {
      id: '23',
      name_ar: 'المحاسبة والاستشارات الضريبية',
      name_en: 'Accounting & Tax Consultation',
      slug: 'accounting',
      icon: '',
      keywords: ['محاسبة', 'ضرائب', 'استشارات', 'muhasebe vergi', 'accounting', 'tax consultation'],
      count: 167,
    },
    {
      id: '24',
      name_ar: 'التسويق الرقمي وإدارة وسائل التواصل',
      name_en: 'Digital Marketing & Social Media',
      slug: 'digital-marketing',
      icon: '',
      keywords: ['تسويق رقمي', 'سوشيال ميديا', 'إعلانات', 'dijital pazarlama', 'digital marketing', 'social media'],
      count: 245,
    },
    {
      id: '25',
      name_ar: 'تصميم المواقع والبرمجة',
      name_en: 'Web Design & Software Development',
      slug: 'web-development',
      icon: '',
      keywords: ['تصميم مواقع', 'برمجة', 'تطوير', 'web tasarım yazılım', 'web design', 'programming'],
      count: 198,
    },
    {
      id: '26',
      name_ar: 'الدعم التقني وإصلاح الأجهزة',
      name_en: 'IT Support & Device Repair',
      slug: 'it-support',
      icon: '',
      keywords: ['دعم تقني', 'إصلاح أجهزة', 'كمبيوتر', 'IT destek onarım', 'IT support', 'device repair'],
      count: 156,
    },
    {
      id: '27',
      name_ar: 'تركيب أنظمة الأمان',
      name_en: 'Security Systems Installation',
      slug: 'security-systems',
      icon: '',
      keywords: ['أنظمة أمان', 'حراسة', 'مراقبة', 'güvenlik sistemleri', 'security systems', 'surveillance'],
      count: 123,
    },
    {
      id: '28',
      name_ar: 'خدمات الأرضيات والبلاط',
      name_en: 'Flooring & Tile Services',
      slug: 'flooring',
      icon: '',
      keywords: ['أرضيات', 'بلاط', 'فايانس', 'taş döşeme fayans', 'flooring', 'tiling'],
      count: 287,
    },
    {
      id: '29',
      name_ar: 'العزل المائي والحراري',
      name_en: 'Waterproofing & Insulation',
      slug: 'insulation',
      icon: '',
      keywords: ['عزل', 'مائي', 'حراري', 'yalıtım izolasyon', 'waterproofing', 'insulation'],
      count: 145,
    },
    {
      id: '30',
      name_ar: 'تجديد وتنجيد الأثاث',
      name_en: 'Furniture Renovation & Upholstery',
      slug: 'furniture-renovation',
      icon: '',
      keywords: ['تجديد أثاث', 'تنجيد', 'كسوة', 'mobilya kaplama', 'furniture renovation', 'upholstery'],
      count: 178,
    },
    {
      id: '31',
      name_ar: 'تركيب الإضاءة والكهربائيات',
      name_en: 'Lighting & Electrical Installation',
      slug: 'lighting-installation',
      icon: '',
      keywords: ['إضاءة', 'تركيب كهربائي', 'أنوار', 'aydınlatma elektrik', 'lighting', 'electrical installation'],
      count: 234,
    },
    {
      id: '32',
      name_ar: 'الديكور والتصميم الداخلي',
      name_en: 'Interior Design & Decoration',
      slug: 'interior-design',
      icon: '',
      keywords: ['ديكور', 'تصميم داخلي', 'تنسيق', 'dekorasyon iç mimarlık', 'interior design', 'decoration'],
      count: 267,
    },
    {
      id: '33',
      name_ar: 'تعليم القيادة',
      name_en: 'Driving Lessons',
      slug: 'driving-lessons',
      icon: '',
      keywords: ['تعليم قيادة', 'رخصة', 'سياقة', 'direksiyon dersi', 'driving lessons', 'driving school'],
      count: 189,
    },
    {
      id: '34',
      name_ar: 'ديكور الجدران',
      name_en: 'Wall Decoration',
      slug: 'wall-decoration',
      icon: '',
      keywords: ['ديكور جدران', 'تزيين', 'ورق جدران', 'duvar dekorasyonu', 'wall decoration', 'wallpaper'],
      count: 145,
    },
    {
      id: '35',
      name_ar: 'تنظيف وغسيل الأرائك',
      name_en: 'Sofa Cleaning & Washing',
      slug: 'sofa-cleaning',
      icon: '',
      keywords: ['تنظيف أرائك', 'غسيل كنب', 'مفروشات', 'koltuk yıkama', 'sofa cleaning', 'upholstery cleaning'],
      count: 312,
    },
    {
      id: '36',
      name_ar: 'الاستشارات النفسية',
      name_en: 'Psychological Counseling',
      slug: 'psychology',
      icon: '',
      keywords: ['استشارات نفسية', 'طبيب نفسي', 'علاج نفسي', 'psikolog', 'psychology', 'counseling'],
      count: 198,
    }
  ]

  // Show all categories when dropdown opens
  const displayCategories = staticCategories.sort((a, b) => {
    // Popular categories first, then by count
    if (a.isPopular && !b.isPopular) return -1
    if (!a.isPopular && b.isPopular) return 1
    return b.count - a.count
  })
  
  const popularCategories = staticCategories.filter(cat => cat.isPopular || cat.count > 800)

  // Debounced search function
  const debouncedFilter = useCallback(
    debounce((searchQuery: string) => {
      if (!searchQuery.trim()) {
        setFilteredCategories(displayCategories)
        return
      }

      const filtered = staticCategories.filter(category =>
        category.name_ar.includes(searchQuery) ||
        category.name_en.toLowerCase().includes(searchQuery.toLowerCase()) ||
        category.keywords.some(keyword => 
          keyword.includes(searchQuery) || 
          searchQuery.includes(keyword)
        )
      ).sort((a, b) => {
        // Önce exact match, sonra starts with, sonra contains
        const aExact = a.name_ar === searchQuery ? 3 : 
                      a.name_ar.startsWith(searchQuery) ? 2 : 
                      a.name_ar.includes(searchQuery) ? 1 : 0
        const bExact = b.name_ar === searchQuery ? 3 : 
                      b.name_ar.startsWith(searchQuery) ? 2 : 
                      b.name_ar.includes(searchQuery) ? 1 : 0
        
        if (aExact !== bExact) return bExact - aExact
        return b.count - a.count // Popularity'e göre sırala
      })

      setFilteredCategories(filtered.slice(0, 20)) // Max 20 sonuç
    }, 300),
    []
  )

  // Load recent searches from localStorage
  useEffect(() => {
    const saved = localStorage.getItem('khidmaapp_recent_searches')
    if (saved) {
      setRecentSearches(JSON.parse(saved))
    }
  }, [])

  // Filter categories when query changes
  useEffect(() => {
    debouncedFilter(query)
    setSelectedIndex(-1)
  }, [query, debouncedFilter])

  // Show all categories when focused and no query
  useEffect(() => {
    if (isOpen && !query.trim()) {
      setFilteredCategories(displayCategories)
    }
  }, [isOpen, query, displayCategories])

  // Handle input focus
  const handleFocus = () => {
    setIsOpen(true)
    if (!query.trim()) {
      setFilteredCategories(displayCategories)
    }
  }

  // Handle input blur
  const handleBlur = (e: React.FocusEvent) => {
    // Delay to allow clicks on dropdown items
    setTimeout(() => {
      if (!dropdownRef.current?.contains(document.activeElement)) {
        setIsOpen(false)
      }
    }, 150)
  }

  // Handle search submit
  const handleSearch = (searchQuery?: string) => {
    const finalQuery = searchQuery || query
    if (finalQuery.trim()) {
      // Save to recent searches
      const newRecent = [finalQuery, ...recentSearches.filter(s => s !== finalQuery)].slice(0, 5)
      setRecentSearches(newRecent)
      localStorage.setItem('khidmaapp_recent_searches', JSON.stringify(newRecent))
      
      // Call onSearch callback
      onSearch?.(finalQuery)
      
      // Close dropdown
      setIsOpen(false)
      
      // Navigate to search results (implement this later)
      window.location.href = `/ar/search?q=${encodeURIComponent(finalQuery)}`
    }
  }

  // Handle keyboard navigation
  const handleKeyDown = (e: React.KeyboardEvent) => {
    if (!isOpen) return

    switch (e.key) {
      case 'ArrowDown':
        e.preventDefault()
        setSelectedIndex(prev => 
          prev < filteredCategories.length - 1 ? prev + 1 : prev
        )
        break
      case 'ArrowUp':
        e.preventDefault() 
        setSelectedIndex(prev => prev > 0 ? prev - 1 : -1)
        break
      case 'Enter':
        e.preventDefault()
        if (selectedIndex >= 0 && filteredCategories[selectedIndex]) {
          const category = filteredCategories[selectedIndex]
          setQuery(category.name_ar)
          handleSearch(category.name_ar)
        } else {
          handleSearch()
        }
        break
      case 'Escape':
        setIsOpen(false)
        inputRef.current?.blur()
        break
    }
  }

  // Clear search
  const clearSearch = () => {
    setQuery('')
    setIsOpen(false)
    inputRef.current?.focus()
  }

  return (
    <div className={`relative ${className}`}>
      {/* Search Input */}
      <div className="relative">
        <Search className="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
        <input
          ref={inputRef}
          type="text"
          value={query}
          onChange={(e) => setQuery(e.target.value)}
          onFocus={handleFocus}
          onBlur={handleBlur}
          onKeyDown={handleKeyDown}
          placeholder={placeholder}
          className="w-full pl-4 pr-12 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-lg"
          dir="rtl"
        />
        {query && (
          <button
            onClick={clearSearch}
            className="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
          >
            <X className="w-5 h-5" />
          </button>
        )}
      </div>

      {/* Dropdown */}
      {isOpen && (
        <div 
          ref={dropdownRef}
          className="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-gray-200 z-50 max-h-[450px] overflow-y-auto custom-scrollbar"
          style={{
            scrollbarWidth: 'thin',
            scrollbarColor: '#CBD5E0 #F7FAFC'
          }}
        >
          {/* Recent Searches */}
          {!query.trim() && recentSearches.length > 0 && (
            <div className="p-4 border-b border-gray-100">
              <div className="flex items-center gap-2 text-sm text-gray-500 mb-3">
                <Clock className="w-4 h-4" />
                عمليات البحث الأخيرة
              </div>
              <div className="flex flex-wrap gap-2">
                {recentSearches.map((search, index) => (
                  <button
                    key={index}
                    onClick={() => {
                      setQuery(search)
                      handleSearch(search)
                    }}
                    className="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition-colors"
                  >
                    {search}
                  </button>
                ))}
              </div>
            </div>
          )}

          {/* Popular/Filtered Categories */}
          <div className="p-2">
            {!query.trim() && (
              <div className="px-3 py-2 flex items-center justify-between text-sm text-gray-500">
                <div className="flex items-center gap-2">
                <TrendingUp className="w-4 h-4" />
                  جميع الخدمات المتاحة
                </div>
                <span className="text-xs">{filteredCategories.length} من {staticCategories.length}</span>
              </div>
            )}
            
            {filteredCategories.length > 0 ? (
              filteredCategories.map((category, index) => (
                <Link
                  key={category.id}
                  href={`/ar/services/${category.slug}`}
                  className={`block px-4 py-3 hover:bg-gray-50 rounded-lg transition-colors ${
                    index === selectedIndex ? 'bg-primary-50 border-r-2 border-primary-500' : ''
                  }`}
                  onClick={() => {
                    setQuery(category.name_ar)
                    handleSearch(category.name_ar)
                  }}
                >
                  <div className="flex items-center justify-between">
                    <div className="flex items-center gap-3">
                      <span className="text-2xl">{category.icon}</span>
                      <div>
                        <div className="font-medium text-gray-900">{category.name_ar}</div>
                        <div className="text-sm text-gray-500">{category.name_en}</div>
                      </div>
                    </div>
                    <div className="text-sm text-gray-400">
                      {category.count.toLocaleString()}+
                    </div>
                  </div>
                </Link>
              ))
            ) : query.trim() ? (
              <div className="px-4 py-8 text-center text-gray-500">
                <Search className="w-8 h-8 mx-auto mb-2 text-gray-300" />
                <div>لم يتم العثور على نتائج لـ "{query}"</div>
                <div className="text-sm mt-1">جرب البحث بكلمات أخرى</div>
              </div>
            ) : null}
            
            {/* Show All Categories Button */}
            {!query.trim() && (
              <div className="border-t border-gray-100 p-4">
                <div className="text-center">
                  <div className="text-sm text-gray-500 mb-3">
                    {staticCategories.length} فئة خدمة متاحة - قم بالتمرير لرؤية المزيد
                  </div>
                  <Link
                    href="/ar/services"
                    className="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium text-sm transition-colors"
                    onClick={() => setIsOpen(false)}
                  >
                    <span>تصفح جميع الخدمات</span>
                    <svg className="w-4 h-4 mr-2 transform rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                  </Link>
                </div>
              </div>
            )}
          </div>

          {/* Quick Actions */}
          {query.trim() && (
            <div className="border-t border-gray-100 p-4">
              <button
                onClick={() => handleSearch()}
                className="w-full bg-gradient-to-r from-primary-500 to-primary-600 text-white py-3 rounded-lg font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300"
              >
                ابحث عن "{query}"
              </button>
            </div>
          )}
        </div>
      )}
    </div>
  )
}

// Debounce utility function
function debounce<T extends (...args: any[]) => any>(
  func: T,
  wait: number
): (...args: Parameters<T>) => void {
  let timeout: NodeJS.Timeout
  return (...args: Parameters<T>) => {
    clearTimeout(timeout)
    timeout = setTimeout(() => func(...args), wait)
  }
}
