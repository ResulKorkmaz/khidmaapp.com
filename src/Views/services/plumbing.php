<?php
/**
 * Service Page: Plumbing (سباكة)
 * SEO-Optimized with Local SEO for Saudi Arabia
 */

$serviceKey = 'plumbing';
$serviceName = 'سباكة';
$serviceNameEn = 'Plumbing Services';
$serviceDescription = 'خدمات سباكة احترافية في السعودية - إصلاح تسربات المياه وصيانة أنظمة السباكة';
$serviceMetaDescription = 'خدمات سباكة محترفة في السعودية | إصلاح تسربات المياه | فني سباكة | صيانة سباكة | أسعار تنافسية في الرياض جدة الدمام';
$serviceKeywords = 'سباكة, فني سباكة, معلم سباكة, إصلاح تسربات, صيانة سباكة, سباك في الرياض, سباك في جدة, سباك في الدمام';
$cities = ['الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 'الخبر', 'تبوك', 'أبها'];
$pageTitle = 'خدمات سباكة احترافية في السعودية | فني سباكة محترف | KhidmaApp';
$breadcrumb = [
    ['name' => 'الرئيسية', 'url' => '/'],
    ['name' => 'الخدمات', 'url' => '/#services'],
    ['name' => $serviceName, 'url' => '']
];

require_once __DIR__ . '/../layouts/header.php';
?>

<!-- Schema.org Structured Data -->
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
    "priceRange": "$$",
    "address": {
      "@type": "PostalAddress",
      "addressCountry": "SA"
    },
    "areaServed": [
      <?php foreach ($cities as $index => $city): ?>
        {"@type": "City", "name": "<?= $city ?>"}<?= $index < count($cities) - 1 ? ',' : '' ?>
      <?php endforeach; ?>
    ],
    "aggregateRating": {
      "@type": "AggregateRating",
      "ratingValue": "4.9",
      "reviewCount": "1100"
    }
  }
}
</script>

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-cyan-900 via-blue-800 to-slate-900 text-white py-16 md:py-24 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-96 h-96 bg-cyan-500 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-500 rounded-full blur-3xl"></div>
    </div>
    
    <div class="container-custom relative z-10">
        <nav class="flex mb-8 text-sm">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 space-x-reverse">
                <?php foreach ($breadcrumb as $index => $item): ?>
                    <li class="inline-flex items-center">
                        <?php if ($item['url']): ?>
                            <a href="<?= htmlspecialchars($item['url']) ?>" class="text-cyan-200 hover:text-white"><?= htmlspecialchars($item['name']) ?></a>
                        <?php else: ?>
                            <span class="text-white font-semibold"><?= htmlspecialchars($item['name']) ?></span>
                        <?php endif; ?>
                        <?php if ($index < count($breadcrumb) - 1): ?>
                            <svg class="w-4 h-4 mx-2 rotate-180" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
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
                    <span class="text-sm font-semibold">خدمة سريعة ومضمونة</span>
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                    خدمات <span class="bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">سباكة احترافية</span> في السعودية
                </h1>
                
                <p class="text-xl text-cyan-100 mb-8 leading-relaxed">
                    خدمات سباكة شاملة - إصلاح تسربات المياه، صيانة الأنابيب، وتركيب الأدوات الصحية بأعلى جودة في جميع مدن المملكة
                </p>
                
                <div class="flex flex-wrap gap-4">
                    <a href="#request-service" class="inline-flex items-center gap-2 bg-white text-cyan-900 font-bold px-8 py-4 rounded-xl shadow-2xl hover:shadow-white/20 transition-all hover:scale-105">
                        اطلب فني سباكة الآن
                    </a>
                </div>
                
                <div class="grid grid-cols-3 gap-6 mt-12 pt-8 border-t border-white/20">
                    <div class="text-center">
                        <div class="text-3xl font-bold">4500+</div>
                        <div class="text-sm text-cyan-200">عميل راضي</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold">4.9/5</div>
                        <div class="text-sm text-cyan-200">تقييم</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold">24/7</div>
                        <div class="text-sm text-cyan-200">متاح</div>
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
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2 mb-4">خدمات السباكة الشاملة</h2>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $services = [
                ['title' => 'إصلاح تسربات المياه', 'icon' => '💧', 'desc' => 'كشف وإصلاح تسربات المياه في الجدران والأرضيات'],
                ['title' => 'صيانة الأنابيب', 'icon' => '🔧', 'desc' => 'صيانة وإصلاح أنابيب المياه الساخنة والباردة'],
                ['title' => 'تركيب أدوات صحية', 'icon' => '🚿', 'desc' => 'تركيب وتبديل الأدوات الصحية والحنفيات'],
                ['title' => 'تسليك المجاري', 'icon' => '🚰', 'desc' => 'تسليك المجاري والبالوعات بأحدث الأجهزة'],
                ['title' => 'فحص التمديدات', 'icon' => '🔍', 'desc' => 'فحص شامل لتمديدات المياه والصرف الصحي'],
                ['title' => 'صيانة دورية', 'icon' => '⏰', 'desc' => 'عقود صيانة دورية للمنازل والمباني']
            ];
            
            foreach ($services as $service):
            ?>
                <div class="bg-gradient-to-br from-gray-50 to-white p-6 rounded-2xl border border-gray-200 hover:border-cyan-400 hover:shadow-xl transition-all">
                    <div class="text-5xl mb-4"><?= $service['icon'] ?></div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3"><?= $service['title'] ?></h3>
                    <p class="text-gray-600"><?= $service['desc'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Cities -->
<section class="py-16 bg-gray-50">
    <div class="container-custom">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">خدمات السباكة في مدن السعودية</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php foreach ($cities as $city): ?>
                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition-all text-center border-2 border-transparent hover:border-cyan-500">
                    <div class="text-3xl mb-2">📍</div>
                    <h3 class="font-bold">سباكة في <?= $city ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section id="request-service" class="py-16 md:py-24 bg-gradient-to-br from-cyan-900 to-blue-800 text-white">
    <div class="container-custom max-w-4xl">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">اطلب فني سباكة الآن</h2>
        </div>
        <div class="bg-white/10 backdrop-blur-md rounded-3xl p-8 border border-white/20">
            <?php
            require_once __DIR__ . '/../helpers/form_helper.php';
            render_service_request_form('service-plumbing-form', 'service', [
                'dark_theme' => true,
                'button_text' => 'اطلب فني سباكة',
                'preselected_service' => 'plumbing',
                'form_origin' => 'service_page_plumbing'
            ]);
            ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

