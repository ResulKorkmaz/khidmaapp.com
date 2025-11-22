<?php
/**
 * Hizmet Detay Sayfası
 * 
 * Her hizmet için özel detay sayfası
 */

// Service key (controller'dan geliyor)
$serviceKey = $serviceKey ?? 'paint';

// SEO Meta Tags
$metaTitle = $pageTitle ?? 'خدمة - خدمات منزلية وتجارية في السعودية';
$metaDescription = $pageDescription ?? 'خدمات منزلية وتجارية شاملة في المملكة العربية السعودية';
$metaKeywords = $pageKeywords ?? 'خدمات، منزلية، تجارية، السعودية';

// Hizmet resmi
$serviceImages = [
    'paint' => 'paint.jpg',
    'renovation' => 'renovation.jpg',
    'plumbing' => 'plumbing.png',
    'electric' => 'electric.jpg',
    'cleaning' => 'cleaning.webp',
    'ac' => 'ac.jpg'
];
$serviceImage = $serviceImages[$serviceKey] ?? 'paint.jpg';

// Form helper fonksiyonu
if (!function_exists('render_service_request_form')) {
    require_once __DIR__ . '/helpers/form_helper.php';
}

ob_start();
?>

<!-- Hero Section -->
<section class="relative bg-blue-600 text-white overflow-hidden min-h-[70vh] flex items-center">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 50px 50px;"></div>
    
    <!-- Decorative Orbs -->
    <div class="absolute top-0 right-0 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>
    
    <div class="container-custom relative z-10 py-16 md:py-24">
        <div class="max-w-5xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="mb-8 text-sm" aria-label="breadcrumb">
                <ol class="flex items-center justify-center gap-2 text-blue-200/90">
                    <li><a href="/" class="hover:text-white transition-colors duration-200 font-medium">الرئيسية</a></li>
                    <li class="text-blue-300/60">/</li>
                    <li><a href="/#services" class="hover:text-white transition-colors duration-200 font-medium">الخدمات</a></li>
                    <li class="text-blue-300/60">/</li>
                    <li class="text-white font-semibold"><?= htmlspecialchars($serviceName) ?></li>
                </ol>
            </nav>
            
            <div class="text-center mb-12">
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold mb-6 leading-tight tracking-tight">
                    <?= htmlspecialchars($serviceName) ?>
                </h1>
                
                <p class="text-xl md:text-2xl text-blue-100/90 mb-10 max-w-3xl mx-auto leading-relaxed font-light">
                    <?= htmlspecialchars($serviceContent['intro'] ?? '') ?>
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#request-form" class="group inline-flex items-center justify-center bg-white text-blue-600 hover:bg-blue-50 font-semibold text-lg px-8 py-4 rounded-2xl shadow-2xl shadow-blue-900/30 hover:shadow-blue-900/40 transition-all duration-300 transform hover:scale-105">
                        <span>اطلب الخدمة الآن</span>
                        <svg class="w-5 h-5 me-2 group-hover:translate-x-1 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <a href="/#services" class="group inline-flex items-center justify-center bg-white/10 backdrop-blur-md text-white border-2 border-white/30 hover:bg-white/20 hover:border-white/40 font-semibold text-lg px-8 py-4 rounded-2xl transition-all duration-300 transform hover:scale-105">
                        <span>عرض جميع الخدمات</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Service Image Section -->
<section class="py-16 md:py-20 bg-gray-50 relative overflow-hidden">
    <div class="container-custom relative z-10">
        <div class="max-w-6xl mx-auto">
            <div class="relative group">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-white">
                    <img src="/assets/images/<?= htmlspecialchars($serviceImage) ?>" 
                         alt="<?= htmlspecialchars($serviceName) ?>"
                         class="w-full h-[500px] md:h-[600px] object-cover">
                    <div class="absolute inset-0 bg-black/40"></div>
                    <!-- Overlay Content -->
                    <div class="absolute bottom-0 left-0 right-0 p-8 md:p-12">
                        <div class="max-w-2xl">
                            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                                خدمة <?= htmlspecialchars($serviceName) ?> الاحترافية
                            </h2>
                            <p class="text-lg text-gray-100 leading-relaxed">
                                نقدم لك أفضل الحلول والخدمات بأعلى معايير الجودة والاحترافية
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section-padding bg-white relative overflow-hidden">
    <div class="container-custom relative z-10">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16 md:mb-20">
                <div class="inline-block mb-4">
                    <span class="text-sm font-semibold text-blue-600 bg-blue-50 px-4 py-2 rounded-full border border-blue-100">
                        المميزات
                    </span>
                </div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">
                    مميزات خدمة <?= htmlspecialchars($serviceName) ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    نقدم لك أفضل الخدمات بأعلى معايير الجودة والاحترافية
                </p>
            </div>
            
            <!-- Features Grid -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <?php if (isset($serviceContent['features']) && is_array($serviceContent['features'])): ?>
                    <?php foreach ($serviceContent['features'] as $index => $feature): ?>
                    <div class="group relative">
                        <div class="relative h-full bg-gray-50 rounded-2xl p-8 border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                            <!-- Icon Container -->
                            <div class="mb-6">
                                <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <h3 class="text-xl font-bold text-gray-900 mb-3 leading-snug">
                                <?= htmlspecialchars($feature) ?>
                            </h3>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="section-padding bg-white relative overflow-hidden">
    <!-- Decorative Background -->
    <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(circle, #3b82f6 1px, transparent 1px); background-size: 50px 50px;"></div>
    
    <div class="container-custom relative z-10">
        <div class="max-w-6xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16 md:mb-20">
                <div class="inline-block mb-4">
                    <span class="text-sm font-semibold text-blue-600 bg-blue-50 px-4 py-2 rounded-full border border-blue-100">
                        العملية
                    </span>
                </div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">
                    خطوات العمل
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    نتبع عملية منظمة وشفافة لضمان أفضل النتائج
                </p>
            </div>
            
            <!-- Process Steps -->
            <div class="relative">
                <!-- Vertical Timeline Line (Desktop) -->
                <div class="hidden lg:block absolute top-0 left-1/2 transform -translate-x-1/2 w-1.5 h-full bg-blue-200 rounded-full"></div>
                
                <div class="space-y-16 md:space-y-20">
                    <?php if (isset($serviceContent['process']) && is_array($serviceContent['process'])): ?>
                        <?php foreach ($serviceContent['process'] as $index => $step): ?>
                        <div class="relative flex flex-col lg:flex-row items-center gap-8 lg:gap-12">
                            <!-- Step Number (Left Side) -->
                            <div class="relative z-10 flex-shrink-0 order-1 lg:order-1">
                                <div class="w-24 h-24 bg-blue-600 rounded-full flex items-center justify-center text-white text-3xl font-extrabold shadow-xl border-4 border-white">
                                    <?= $index + 1 ?>
                                </div>
                                <!-- Connecting Line Dot -->
                                <div class="hidden lg:block absolute top-1/2 left-full w-6 h-6 bg-blue-500 rounded-full border-4 border-white shadow-lg transform -translate-y-1/2 translate-x-1/2"></div>
                            </div>
                            
                            <!-- Step Content (Right Side) -->
                            <div class="flex-1 order-2 lg:order-2 lg:text-right lg:pr-8">
                                <div class="bg-gray-50 rounded-3xl p-8 md:p-10 shadow-lg border border-gray-200">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center lg:hidden">
                                            <span class="text-blue-600 font-bold text-lg"><?= $index + 1 ?></span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3 leading-tight">
                                                <?= htmlspecialchars($step) ?>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Request Form Section -->
<section id="request-form" class="section-padding bg-slate-800 relative overflow-hidden hero-dark-section">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-80 h-80 bg-blue-600/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-indigo-600/10 rounded-full blur-3xl"></div>
    
    <div class="container-custom relative z-10">
        <div class="max-w-5xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-12 md:mb-16">
                <div class="inline-block mb-4">
                    <span class="text-sm font-semibold text-white bg-blue-600 px-4 py-2 rounded-full shadow-md">
                        اطلب الآن
                    </span>
                </div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6">
                    اطلب خدمة <?= htmlspecialchars($serviceName) ?> الآن
                </h2>
                <p class="text-xl text-gray-300 max-w-2xl mx-auto leading-relaxed">
                    املأ البيانات أدناه وسنتواصل معك خلال دقائق
                </p>
            </div>
            
            <!-- Form Card -->
            <div class="relative">
                <div class="relative bg-slate-700 rounded-3xl shadow-2xl p-8 md:p-12 border border-slate-600">
                    <?php
                    render_service_request_form('service-detail-form', 'detail', [
                        'button_text' => 'إرسال الطلب',
                        'button_classes' => 'btn-primary w-full text-lg py-4 relative bg-blue-600 hover:bg-blue-700',
                        'form_origin' => 'service_detail',
                        'preselected_service' => $serviceKey,
                        'dark_theme' => true
                    ]);
                    ?>
                    
                    <!-- Terms -->
                    <p class="text-center text-sm text-gray-300 mt-8 leading-relaxed">
                    بإرسال الطلب، أنت توافق على 
                    <a href="/terms" class="text-blue-400 hover:text-blue-300 font-semibold transition-colors">شروط الاستخدام</a>
                    و
                    <a href="/privacy" class="text-blue-400 hover:text-blue-300 font-semibold transition-colors">سياسة الخصوصية</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section-padding bg-blue-600 text-white relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 50px 50px;"></div>
    
    <!-- Decorative Orbs -->
    <div class="absolute top-0 right-0 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>
    
    <div class="container-custom relative z-10">
        <div class="max-w-5xl mx-auto text-center">
            <div class="inline-block mb-6">
                <span class="text-sm font-semibold text-blue-200 bg-white/10 backdrop-blur-md px-4 py-2 rounded-full border border-white/20">
                    الدعم
                </span>
            </div>
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                هل تحتاج إلى مساعدة؟
            </h2>
            <p class="text-xl md:text-2xl text-blue-100/90 mb-10 max-w-3xl mx-auto leading-relaxed">
                فريقنا جاهز للإجابة على جميع استفساراتك ومساعدتك في اختيار الخدمة المناسبة
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/#contact" class="group inline-flex items-center justify-center bg-white text-blue-600 hover:bg-blue-50 font-semibold text-lg px-8 py-4 rounded-2xl shadow-2xl shadow-blue-900/30 hover:shadow-blue-900/40 transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 me-2 group-hover:translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                    تواصل معنا
                </a>
                <a href="/#services" class="group inline-flex items-center justify-center bg-white/10 backdrop-blur-md text-white border-2 border-white/30 hover:bg-white/20 hover:border-white/40 font-semibold text-lg px-8 py-4 rounded-2xl transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 me-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    عرض جميع الخدمات
                </a>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();

// Base layout'a gönder
$pageTitle = $metaTitle;
$pageDescription = $metaDescription;
$pageKeywords = $metaKeywords;

// Structured Data (JSON-LD) for SEO
$structuredData = [
    '@context' => 'https://schema.org',
    '@type' => 'Service',
    'name' => $serviceName,
    'description' => $metaDescription,
    'provider' => [
        '@type' => 'Organization',
        'name' => 'KhidmaApp.com',
        'url' => SITE_URL
    ],
    'areaServed' => [
        '@type' => 'Country',
        'name' => 'Saudi Arabia'
    ],
    'serviceType' => $serviceName
];

include __DIR__ . '/layouts/base.php';
?>

<script type="application/ld+json">
<?= json_encode($structuredData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>


 * Hizmet Detay Sayfası
 * 
 * Her hizmet için özel detay sayfası
 */

// Service key (controller'dan geliyor)
$serviceKey = $serviceKey ?? 'paint';

// SEO Meta Tags
$metaTitle = $pageTitle ?? 'خدمة - خدمات منزلية وتجارية في السعودية';
$metaDescription = $pageDescription ?? 'خدمات منزلية وتجارية شاملة في المملكة العربية السعودية';
$metaKeywords = $pageKeywords ?? 'خدمات، منزلية، تجارية، السعودية';

// Hizmet resmi
$serviceImages = [
    'paint' => 'paint.jpg',
    'renovation' => 'renovation.jpg',
    'plumbing' => 'plumbing.png',
    'electric' => 'electric.jpg',
    'cleaning' => 'cleaning.webp',
    'ac' => 'ac.jpg'
];
$serviceImage = $serviceImages[$serviceKey] ?? 'paint.jpg';

// Form helper fonksiyonu
if (!function_exists('render_service_request_form')) {
    require_once __DIR__ . '/helpers/form_helper.php';
}

ob_start();
?>

<!-- Hero Section -->
<section class="relative bg-blue-600 text-white overflow-hidden min-h-[70vh] flex items-center">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 50px 50px;"></div>
    
    <!-- Decorative Orbs -->
    <div class="absolute top-0 right-0 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>
    
    <div class="container-custom relative z-10 py-16 md:py-24">
        <div class="max-w-5xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="mb-8 text-sm" aria-label="breadcrumb">
                <ol class="flex items-center justify-center gap-2 text-blue-200/90">
                    <li><a href="/" class="hover:text-white transition-colors duration-200 font-medium">الرئيسية</a></li>
                    <li class="text-blue-300/60">/</li>
                    <li><a href="/#services" class="hover:text-white transition-colors duration-200 font-medium">الخدمات</a></li>
                    <li class="text-blue-300/60">/</li>
                    <li class="text-white font-semibold"><?= htmlspecialchars($serviceName) ?></li>
                </ol>
            </nav>
            
            <div class="text-center mb-12">
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold mb-6 leading-tight tracking-tight">
                    <?= htmlspecialchars($serviceName) ?>
                </h1>
                
                <p class="text-xl md:text-2xl text-blue-100/90 mb-10 max-w-3xl mx-auto leading-relaxed font-light">
                    <?= htmlspecialchars($serviceContent['intro'] ?? '') ?>
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#request-form" class="group inline-flex items-center justify-center bg-white text-blue-600 hover:bg-blue-50 font-semibold text-lg px-8 py-4 rounded-2xl shadow-2xl shadow-blue-900/30 hover:shadow-blue-900/40 transition-all duration-300 transform hover:scale-105">
                        <span>اطلب الخدمة الآن</span>
                        <svg class="w-5 h-5 me-2 group-hover:translate-x-1 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <a href="/#services" class="group inline-flex items-center justify-center bg-white/10 backdrop-blur-md text-white border-2 border-white/30 hover:bg-white/20 hover:border-white/40 font-semibold text-lg px-8 py-4 rounded-2xl transition-all duration-300 transform hover:scale-105">
                        <span>عرض جميع الخدمات</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Service Image Section -->
<section class="py-16 md:py-20 bg-gray-50 relative overflow-hidden">
    <div class="container-custom relative z-10">
        <div class="max-w-6xl mx-auto">
            <div class="relative group">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-white">
                    <img src="/assets/images/<?= htmlspecialchars($serviceImage) ?>" 
                         alt="<?= htmlspecialchars($serviceName) ?>"
                         class="w-full h-[500px] md:h-[600px] object-cover">
                    <div class="absolute inset-0 bg-black/40"></div>
                    <!-- Overlay Content -->
                    <div class="absolute bottom-0 left-0 right-0 p-8 md:p-12">
                        <div class="max-w-2xl">
                            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                                خدمة <?= htmlspecialchars($serviceName) ?> الاحترافية
                            </h2>
                            <p class="text-lg text-gray-100 leading-relaxed">
                                نقدم لك أفضل الحلول والخدمات بأعلى معايير الجودة والاحترافية
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section-padding bg-white relative overflow-hidden">
    <div class="container-custom relative z-10">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16 md:mb-20">
                <div class="inline-block mb-4">
                    <span class="text-sm font-semibold text-blue-600 bg-blue-50 px-4 py-2 rounded-full border border-blue-100">
                        المميزات
                    </span>
                </div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">
                    مميزات خدمة <?= htmlspecialchars($serviceName) ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    نقدم لك أفضل الخدمات بأعلى معايير الجودة والاحترافية
                </p>
            </div>
            
            <!-- Features Grid -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <?php if (isset($serviceContent['features']) && is_array($serviceContent['features'])): ?>
                    <?php foreach ($serviceContent['features'] as $index => $feature): ?>
                    <div class="group relative">
                        <div class="relative h-full bg-gray-50 rounded-2xl p-8 border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                            <!-- Icon Container -->
                            <div class="mb-6">
                                <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <h3 class="text-xl font-bold text-gray-900 mb-3 leading-snug">
                                <?= htmlspecialchars($feature) ?>
                            </h3>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="section-padding bg-white relative overflow-hidden">
    <!-- Decorative Background -->
    <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(circle, #3b82f6 1px, transparent 1px); background-size: 50px 50px;"></div>
    
    <div class="container-custom relative z-10">
        <div class="max-w-6xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16 md:mb-20">
                <div class="inline-block mb-4">
                    <span class="text-sm font-semibold text-blue-600 bg-blue-50 px-4 py-2 rounded-full border border-blue-100">
                        العملية
                    </span>
                </div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">
                    خطوات العمل
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    نتبع عملية منظمة وشفافة لضمان أفضل النتائج
                </p>
            </div>
            
            <!-- Process Steps -->
            <div class="relative">
                <!-- Vertical Timeline Line (Desktop) -->
                <div class="hidden lg:block absolute top-0 left-1/2 transform -translate-x-1/2 w-1.5 h-full bg-blue-200 rounded-full"></div>
                
                <div class="space-y-16 md:space-y-20">
                    <?php if (isset($serviceContent['process']) && is_array($serviceContent['process'])): ?>
                        <?php foreach ($serviceContent['process'] as $index => $step): ?>
                        <div class="relative flex flex-col lg:flex-row items-center gap-8 lg:gap-12">
                            <!-- Step Number (Left Side) -->
                            <div class="relative z-10 flex-shrink-0 order-1 lg:order-1">
                                <div class="w-24 h-24 bg-blue-600 rounded-full flex items-center justify-center text-white text-3xl font-extrabold shadow-xl border-4 border-white">
                                    <?= $index + 1 ?>
                                </div>
                                <!-- Connecting Line Dot -->
                                <div class="hidden lg:block absolute top-1/2 left-full w-6 h-6 bg-blue-500 rounded-full border-4 border-white shadow-lg transform -translate-y-1/2 translate-x-1/2"></div>
                            </div>
                            
                            <!-- Step Content (Right Side) -->
                            <div class="flex-1 order-2 lg:order-2 lg:text-right lg:pr-8">
                                <div class="bg-gray-50 rounded-3xl p-8 md:p-10 shadow-lg border border-gray-200">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center lg:hidden">
                                            <span class="text-blue-600 font-bold text-lg"><?= $index + 1 ?></span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3 leading-tight">
                                                <?= htmlspecialchars($step) ?>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Request Form Section -->
<section id="request-form" class="section-padding bg-slate-800 relative overflow-hidden hero-dark-section">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-80 h-80 bg-blue-600/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-indigo-600/10 rounded-full blur-3xl"></div>
    
    <div class="container-custom relative z-10">
        <div class="max-w-5xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-12 md:mb-16">
                <div class="inline-block mb-4">
                    <span class="text-sm font-semibold text-white bg-blue-600 px-4 py-2 rounded-full shadow-md">
                        اطلب الآن
                    </span>
                </div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6">
                    اطلب خدمة <?= htmlspecialchars($serviceName) ?> الآن
                </h2>
                <p class="text-xl text-gray-300 max-w-2xl mx-auto leading-relaxed">
                    املأ البيانات أدناه وسنتواصل معك خلال دقائق
                </p>
            </div>
            
            <!-- Form Card -->
            <div class="relative">
                <div class="relative bg-slate-700 rounded-3xl shadow-2xl p-8 md:p-12 border border-slate-600">
                    <?php
                    render_service_request_form('service-detail-form', 'detail', [
                        'button_text' => 'إرسال الطلب',
                        'button_classes' => 'btn-primary w-full text-lg py-4 relative bg-blue-600 hover:bg-blue-700',
                        'form_origin' => 'service_detail',
                        'preselected_service' => $serviceKey,
                        'dark_theme' => true
                    ]);
                    ?>
                    
                    <!-- Terms -->
                    <p class="text-center text-sm text-gray-300 mt-8 leading-relaxed">
                    بإرسال الطلب، أنت توافق على 
                    <a href="/terms" class="text-blue-400 hover:text-blue-300 font-semibold transition-colors">شروط الاستخدام</a>
                    و
                    <a href="/privacy" class="text-blue-400 hover:text-blue-300 font-semibold transition-colors">سياسة الخصوصية</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section-padding bg-blue-600 text-white relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 50px 50px;"></div>
    
    <!-- Decorative Orbs -->
    <div class="absolute top-0 right-0 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>
    
    <div class="container-custom relative z-10">
        <div class="max-w-5xl mx-auto text-center">
            <div class="inline-block mb-6">
                <span class="text-sm font-semibold text-blue-200 bg-white/10 backdrop-blur-md px-4 py-2 rounded-full border border-white/20">
                    الدعم
                </span>
            </div>
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                هل تحتاج إلى مساعدة؟
            </h2>
            <p class="text-xl md:text-2xl text-blue-100/90 mb-10 max-w-3xl mx-auto leading-relaxed">
                فريقنا جاهز للإجابة على جميع استفساراتك ومساعدتك في اختيار الخدمة المناسبة
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/#contact" class="group inline-flex items-center justify-center bg-white text-blue-600 hover:bg-blue-50 font-semibold text-lg px-8 py-4 rounded-2xl shadow-2xl shadow-blue-900/30 hover:shadow-blue-900/40 transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 me-2 group-hover:translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                    تواصل معنا
                </a>
                <a href="/#services" class="group inline-flex items-center justify-center bg-white/10 backdrop-blur-md text-white border-2 border-white/30 hover:bg-white/20 hover:border-white/40 font-semibold text-lg px-8 py-4 rounded-2xl transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 me-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    عرض جميع الخدمات
                </a>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();

// Base layout'a gönder
$pageTitle = $metaTitle;
$pageDescription = $metaDescription;
$pageKeywords = $metaKeywords;

// Structured Data (JSON-LD) for SEO
$structuredData = [
    '@context' => 'https://schema.org',
    '@type' => 'Service',
    'name' => $serviceName,
    'description' => $metaDescription,
    'provider' => [
        '@type' => 'Organization',
        'name' => 'KhidmaApp.com',
        'url' => SITE_URL
    ],
    'areaServed' => [
        '@type' => 'Country',
        'name' => 'Saudi Arabia'
    ],
    'serviceType' => $serviceName
];

include __DIR__ . '/layouts/base.php';
?>

<script type="application/ld+json">
<?= json_encode($structuredData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>



