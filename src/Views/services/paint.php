<?php
/**
 * Service Page: Paint (Dهان)
 * SEO-Optimized with Local SEO for Saudi Arabia
 */

$serviceKey = 'paint';
$serviceName = 'دهان';
$serviceNameEn = 'Painting Services';
$serviceDescription = 'خدمات دهان احترافية في السعودية - دهانات داخلية وخارجية بأعلى جودة';
$serviceMetaDescription = 'احصل على أفضل خدمات الدهان في السعودية | دهانات داخلية وخارجية | فني دهان محترف | أسعار تنافسية | جودة عالية في الرياض جدة الدمام';
$serviceKeywords = 'دهان, معلم دهان, فني دهان, دهانات منزلية, دهانات خارجية, دهان في الرياض, دهان في جدة, دهان في الدمام, خدمات دهان, أسعار الدهان';

// Cities for local SEO
$cities = ['الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 'الخبر', 'تبوك', 'أبها'];

// Page title for SEO
$pageTitle = 'خدمات دهان احترافية في السعودية | معلم دهان محترف | KhidmaApp';

// Breadcrumb
$breadcrumb = [
    ['name' => 'الرئيسية', 'url' => '/'],
    ['name' => 'الخدمات', 'url' => '/#services'],
    ['name' => $serviceName, 'url' => '']
];

require_once __DIR__ . '/../layouts/header.php';
?>

<!-- Schema.org Structured Data for Local SEO -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Service",
  "serviceType": "<?= $serviceName ?> - <?= $serviceNameEn ?>",
  "provider": {
    "@type": "LocalBusiness",
    "name": "KhidmaApp - خدمة",
    "image": "<?= htmlspecialchars($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']) ?>/assets/images/logo-new.png",
    "url": "<?= htmlspecialchars($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']) ?>",
    "telephone": "+966-XX-XXX-XXXX",
    "priceRange": "$$",
    "address": {
      "@type": "PostalAddress",
      "addressCountry": "SA",
      "addressRegion": "المملكة العربية السعودية"
    },
    "areaServed": [
      <?php foreach ($cities as $index => $city): ?>
        {
          "@type": "City",
          "name": "<?= $city ?>"
        }<?= $index < count($cities) - 1 ? ',' : '' ?>
      <?php endforeach; ?>
    ],
    "aggregateRating": {
      "@type": "AggregateRating",
      "ratingValue": "4.9",
      "reviewCount": "1250"
    }
  },
  "description": "<?= htmlspecialchars($serviceDescription) ?>",
  "areaServed": {
    "@type": "Country",
    "name": "Saudi Arabia"
  }
}
</script>

<!-- Breadcrumb Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    <?php foreach ($breadcrumb as $index => $item): ?>
    {
      "@type": "ListItem",
      "position": <?= $index + 1 ?>,
      "name": "<?= htmlspecialchars($item['name']) ?>",
      "item": "<?= htmlspecialchars($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $item['url']) ?>"
    }<?= $index < count($breadcrumb) - 1 ? ',' : '' ?>
    <?php endforeach; ?>
  ]
}
</script>

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 text-white py-16 md:py-24 overflow-hidden">
    <!-- Decorative Background -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-cyan-500 rounded-full blur-3xl"></div>
    </div>
    
    <div class="container-custom relative z-10">
        <!-- Breadcrumb -->
        <nav class="flex mb-8 text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 space-x-reverse">
                <?php foreach ($breadcrumb as $index => $item): ?>
                    <li class="inline-flex items-center">
                        <?php if ($item['url']): ?>
                            <a href="<?= htmlspecialchars($item['url']) ?>" class="text-blue-200 hover:text-white transition-colors">
                                <?= htmlspecialchars($item['name']) ?>
                            </a>
                        <?php else: ?>
                            <span class="text-white font-semibold"><?= htmlspecialchars($item['name']) ?></span>
                        <?php endif; ?>
                        <?php if ($index < count($breadcrumb) - 1): ?>
                            <svg class="w-4 h-4 mx-2 rotate-180" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        </nav>
        
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-flex items-center bg-white/10 backdrop-blur-md px-4 py-2 rounded-full mb-6">
                    <svg class="w-5 h-5 me-2 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-semibold">خدمة معتمدة ومضمونة</span>
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                    خدمات <span class="bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">دهان احترافية</span> في السعودية
                </h1>
                
                <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                    احصل على أفضل خدمات الدهان الداخلية والخارجية من معلمين محترفين في جميع مدن المملكة. جودة عالية وأسعار تنافسية.
                </p>
                
                <div class="flex flex-wrap gap-4">
                    <a href="#request-service" class="inline-flex items-center gap-2 bg-white text-blue-900 font-bold px-8 py-4 rounded-xl shadow-2xl hover:shadow-white/20 transition-all hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        اطلب خدمة الدهان الآن
                    </a>
                    
                    <a href="tel:+966XXXXXXXXX" class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border-2 border-white/30 text-white font-bold px-8 py-4 rounded-xl hover:bg-white/20 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        اتصل بنا
                    </a>
                </div>
                
                <!-- Trust Indicators -->
                <div class="grid grid-cols-3 gap-6 mt-12 pt-8 border-t border-white/20">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white mb-1">5000+</div>
                        <div class="text-sm text-blue-200">عميل راضي</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white mb-1">4.9/5</div>
                        <div class="text-sm text-blue-200">تقييم العملاء</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white mb-1">24/7</div>
                        <div class="text-sm text-blue-200">دعم مستمر</div>
                    </div>
                </div>
            </div>
            
            <!-- Service Image -->
            <div class="hidden lg:block">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/30 to-cyan-500/30 rounded-3xl blur-3xl"></div>
                    <div class="relative bg-white/10 backdrop-blur-md rounded-3xl p-8 border border-white/20">
                        <svg class="w-full h-64 text-white/20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.71 16.71l-2.42-2.42a1 1 0 00-1.42 0l-3.58 3.58a1 1 0 00-.21 1.09A8 8 0 0016.71 21h.21a10 10 0 008.09-8.09 1 1 0 00-1.09-.21zM9 13a1 1 0 01-1 1H4a1 1 0 010-2h4a1 1 0 011 1z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Service Details -->
<section class="py-16 md:py-24 bg-white">
    <div class="container-custom">
        <div class="text-center mb-16">
            <span class="text-blue-600 font-semibold text-lg">خدماتنا</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2 mb-4">خدمات الدهان الشاملة</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                نقدم جميع أنواع خدمات الدهان بأعلى معايير الجودة والاحترافية
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $services = [
                ['title' => 'دهان داخلي', 'icon' => '🏠', 'desc' => 'دهان الغرف والصالات والمجالس بأحدث الألوان والتقنيات'],
                ['title' => 'دهان خارجي', 'icon' => '🏛️', 'desc' => 'دهان الواجهات الخارجية بمواد مقاومة للعوامل الجوية'],
                ['title' => 'دهان ديكورات', 'icon' => '🎨', 'desc' => 'تنفيذ ديكورات حديثة ورسومات فنية على الجدران'],
                ['title' => 'تركيب ورق جدران', 'icon' => '📜', 'desc' => 'تركيب ورق جدران بأشكال وأنواع متعددة'],
                ['title' => 'معالجة التشققات', 'icon' => '🔨', 'desc' => 'إصلاح ومعالجة التشققات والعيوب في الجدران'],
                ['title' => 'دهان أبواب ونوافذ', 'icon' => '🚪', 'desc' => 'دهان الأبواب والنوافذ الخشبية والحديدية']
            ];
            
            foreach ($services as $service):
            ?>
                <div class="bg-gradient-to-br from-gray-50 to-white p-6 rounded-2xl border border-gray-200 hover:border-blue-400 hover:shadow-xl transition-all group">
                    <div class="text-5xl mb-4 group-hover:scale-110 transition-transform"><?= $service['icon'] ?></div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3"><?= $service['title'] ?></h3>
                    <p class="text-gray-600 leading-relaxed"><?= $service['desc'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Cities We Serve -->
<section class="py-16 md:py-24 bg-gray-50">
    <div class="container-custom">
        <div class="text-center mb-12">
            <span class="text-blue-600 font-semibold text-lg">نغطي جميع المدن</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2 mb-4">خدمات الدهان في مدن السعودية</h2>
            <p class="text-xl text-gray-600">نقدم خدماتنا في جميع مدن المملكة العربية السعودية</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php foreach ($cities as $city): ?>
                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition-all text-center group cursor-pointer border-2 border-transparent hover:border-blue-500">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">📍</div>
                    <h3 class="font-bold text-gray-900">دهان في <?= $city ?></h3>
                    <p class="text-sm text-gray-600 mt-1">خدمة سريعة</p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 md:py-24 bg-white">
    <div class="container-custom max-w-4xl">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">الأسئلة الشائعة حول الدهان</h2>
        </div>
        
        <div class="space-y-4">
            <?php
            $faqs = [
                ['q' => 'كم تكلفة دهان الشقة؟', 'a' => 'تختلف التكلفة حسب مساحة الشقة ونوع الدهان المستخدم. يمكنك طلب عرض سعر مجاني من خلال النموذج أدناه.'],
                ['q' => 'كم يستغرق دهان المنزل؟', 'a' => 'عادةً يستغرق دهان منزل متوسط من 3-5 أيام حسب المساحة وحالة الجدران.'],
                ['q' => 'هل الأسعار شاملة المواد؟', 'a' => 'نعم، أسعارنا شاملة جميع المواد والأدوات اللازمة للدهان.'],
                ['q' => 'هل يوجد ضمان على الدهان؟', 'a' => 'نعم، نقدم ضمان على جودة العمل لمدة تصل إلى سنة واحدة.'],
                ['q' => 'هل تقدمون استشارة مجانية؟', 'a' => 'نعم، نقدم استشارة مجانية وفحص الموقع قبل البدء بالعمل.']
            ];
            
            foreach ($faqs as $index => $faq):
            ?>
                <div class="faq-item border border-gray-200 rounded-xl overflow-hidden">
                    <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between p-6 text-right hover:bg-gray-50 transition-colors">
                        <span class="font-bold text-gray-900 text-lg"><?= $faq['q'] ?></span>
                        <svg class="faq-icon w-6 h-6 text-blue-600 flex-shrink-0 mr-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="faq-answer hidden px-6 pb-6">
                        <p class="text-gray-600 leading-relaxed"><?= $faq['a'] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section id="request-service" class="py-16 md:py-24 bg-gradient-to-br from-blue-900 to-blue-800 text-white">
    <div class="container-custom max-w-4xl">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">اطلب خدمة الدهان الآن</h2>
            <p class="text-xl text-blue-100">احصل على أفضل الأسعار من معلمي الدهان المحترفين في منطقتك</p>
        </div>
        
        <div class="bg-white/10 backdrop-blur-md rounded-3xl p-8 border border-white/20">
            <?php
            require_once __DIR__ . '/../helpers/form_helper.php';
            render_service_request_form('service-paint-form', 'service', [
                'dark_theme' => true,
                'button_text' => 'اطلب خدمة الدهان',
                'preselected_service' => 'paint',
                'form_origin' => 'service_page_paint'
            ]);
            ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

