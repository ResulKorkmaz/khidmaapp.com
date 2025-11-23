<?php
/**
 * Service Page: Electrical (كهرباء) - MODERN PROFESSIONAL DESIGN
 * Ultra SEO-Optimized with Local SEO for Saudi Arabia
 */

$serviceKey = 'electric';
$serviceName = 'كهرباء';
$serviceNameEn = 'Electrical Services';
$serviceColor = 'orange'; // Brand color for this service
$serviceIcon = '⚡';
$serviceDescription = 'خدمات كهرباء شاملة في السعودية - كهرباء منازل ومكاتب بأعلى جودة';
$serviceMetaDescription = 'خدمات كهرباء احترافية في السعودية | كهرباء منازل | كهرباء مكاتب | كهرباء شقق | شركة كهرباء في الرياض جدة الدمام';
$serviceKeywords = 'كهرباء, كهرباء منازل, كهرباء شقق, كهرباء مكاتب, شركة كهرباء, كهرباء في الرياض, كهرباء في جدة';
$cities = ['الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 'الخبر', 'تبوك', 'أبها'];
$pageTitle = 'خدمات كهرباء احترافية في السعودية | شركة كهرباء محترفة | KhidmaApp';
$breadcrumb = [
    ['name' => 'الرئيسية', 'url' => '/'],
    ['name' => 'الخدمات', 'url' => '/#services'],
    ['name' => $serviceName, 'url' => '']
];

require_once __DIR__ . '/../layouts/header.php';
?>

<!-- Schema.org Structured Data -->
<script type="application/ld+json">
{"@context": "https://schema.org", "@type": "Service", "serviceType": "<?= $serviceName ?> - <?= $serviceNameEn ?>", "provider": {"@type": "LocalBusiness", "name": "KhidmaApp - خدمة", "priceRange": "$$", "aggregateRating": {"@type": "AggregateRating", "ratingValue": "4.9", "reviewCount": "1400"}}}
</script>

<!-- MODERN HERO SECTION -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden" style="background: linear-gradient(135deg, #ea580c 0%, #f97316 50%, #c2410c 100%);">
    <!-- Animated Background -->
    <div class="absolute inset-0">
        <div class="absolute top-20 right-20 w-72 h-72 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob" style="background: #fb923c;"></div>
        <div class="absolute top-40 left-20 w-72 h-72 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000" style="background: #f97316;"></div>
        <div class="absolute bottom-20 left-1/2 w-72 h-72 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000" style="background: #ea580c;"></div>
    </div>
    
    <div class="container-custom relative z-10 py-20">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 space-x-reverse bg-white/10 backdrop-blur-md px-4 py-2 rounded-full">
                <?php foreach ($breadcrumb as $index => $item): ?>
                    <li class="inline-flex items-center">
                        <?php if ($item['url']): ?>
                            <a href="<?= htmlspecialchars($item['url']) ?>" class="text-white/80 hover:text-white transition-colors text-sm font-medium">
                                <?= htmlspecialchars($item['name']) ?>
                            </a>
                        <?php else: ?>
                            <span class="text-white font-semibold text-sm"><?= htmlspecialchars($item['name']) ?></span>
                        <?php endif; ?>
                        <?php if ($index < count($breadcrumb) - 1): ?>
                            <svg class="w-3 h-3 mx-2 rotate-180" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        </nav>
        
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="text-white">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md px-5 py-2.5 rounded-full mb-8 border border-white/30">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-300 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-400"></span>
                    </span>
                    <span class="text-sm font-bold">متوفر الآن - خدمة فورية</span>
                </div>
                
                <!-- Main Heading -->
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-black mb-6 leading-tight">
                    <span class="block mb-2">خدمات <?= $serviceIcon ?></span>
                    <span class="block bg-gradient-to-r from-white via-orange-100 to-white bg-clip-text text-transparent">
                        كهرباء احترافية
                    </span>
                </h1>
                
                <!-- Description -->
                <p class="text-xl md:text-2xl text-orange-50 mb-8 leading-relaxed font-semibold">
                    كهرباء شامل للمنازل والمكاتب والشقق مع ضمان الجودة والنظافة التامة في جميع أنحاء المملكة
                </p>
                
                <!-- CTA Buttons -->
                <div class="flex flex-wrap gap-4 mb-12">
                    <a href="#request-service" class="group relative inline-flex items-center gap-3 bg-white text-orange-700 font-bold text-lg px-8 py-4 rounded-2xl shadow-2xl hover:shadow-white/30 transition-all hover:scale-105 overflow-hidden">
                        <span class="absolute inset-0 bg-gradient-to-r from-orange-400 to-green-400 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        <svg class="w-6 h-6 relative z-10 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                        <span class="relative z-10 group-hover:text-white transition-colors">اطلب خدمة الكهرباء الآن</span>
                    </a>
                    
                    <a href="tel:+966XXXXXXXXX" class="inline-flex items-center gap-3 bg-white/10 backdrop-blur-md border-2 border-white/40 text-white font-bold text-lg px-8 py-4 rounded-2xl hover:bg-white/20 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>اتصل بنا</span>
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-3 gap-6 bg-white/10 backdrop-blur-md rounded-3xl p-6 border border-white/20">
                    <div class="text-center">
                        <div class="text-4xl font-black mb-1">6000+</div>
                        <div class="text-orange-100 text-sm font-semibold">عميل سعيد</div>
                    </div>
                    <div class="text-center border-r border-l border-white/20">
                        <div class="flex items-center justify-center gap-1 mb-1">
                            <span class="text-4xl font-black">4.9</span>
                            <svg class="w-6 h-6 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <div class="text-orange-100 text-sm font-semibold">التقييم</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-black mb-1">100%</div>
                        <div class="text-orange-100 text-sm font-semibold">رضا العملاء</div>
                    </div>
                </div>
            </div>
            
            <!-- Hero Image/Illustration -->
            <div class="hidden lg:block">
                <div class="relative">
                    <!-- Decorative circles -->
                    <div class="absolute -top-10 -right-10 w-72 h-72 bg-white/20 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-72 h-72 bg-orange-400/30 rounded-full blur-3xl"></div>
                    
                    <!-- Main card -->
                    <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border-2 border-white/30 shadow-2xl transform hover:scale-105 transition-transform duration-500">
                        <div class="text-9xl text-center mb-4 filter drop-shadow-2xl">⚡</div>
                        <div class="text-center text-white">
                            <h3 class="text-2xl font-bold mb-2">نظافة مثالية مضمونة</h3>
                            <p class="text-orange-100">فريق محترف وأدوات حديثة</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bottom Wave -->
    <div class="absolute bottom-0 left-0 w-full">
        <svg class="w-full h-24 fill-current text-white" viewBox="0 0 1440 74" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,32L48,37.3C96,43,192,53,288,58.7C384,64,480,64,576,58.7C672,53,768,43,864,42.7C960,43,1056,53,1152,53.3C1248,53,1344,43,1392,37.3L1440,32L1440,74L1392,74C1344,74,1248,74,1152,74C1056,74,960,74,864,74C768,74,672,74,576,74C480,74,384,74,288,74C192,74,96,74,48,74L0,74Z"></path>
        </svg>
    </div>
</section>

<!-- SERVICES SECTION -->
<section class="py-24 bg-white">
    <div class="container-custom">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block bg-orange-100 text-orange-700 font-bold px-6 py-2 rounded-full mb-4">خدماتنا الشاملة</span>
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">ماذا نقدم لك؟</h2>
            <p class="text-xl text-gray-600">حلول كهرباء متكاملة لجميع احتياجاتك</p>
        </div>
        
        <!-- Services Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $services = [
                ['title' => 'كهرباء منازل', 'icon' => '🏠', 'desc' => 'كهرباء شامل للمنازل والفلل بجميع المرافق بأعلى معايير النظافة', 'color' => 'orange'],
                ['title' => 'كهرباء شقق', 'icon' => '🏢', 'desc' => 'كهرباء احترافي للشقق السكنية قبل وبعد الانتقال', 'color' => 'green'],
                ['title' => 'كهرباء مكاتب', 'icon' => '🏪', 'desc' => 'خدمات كهرباء دورية للمكاتب والشركات مع عقود صيانة', 'color' => 'teal'],
                ['title' => 'كهرباء كنب وسجاد', 'icon' => '🛋️', 'desc' => 'كهرباء عميق بالبخار للكنب والسجاد والموكيت', 'color' => 'cyan'],
                ['title' => 'كهرباء مطابخ', 'icon' => '🍳', 'desc' => 'كهرباء المطابخ وإزالة الدهون المستعصية بأحدث المواد', 'color' => 'lime'],
                ['title' => 'تلميع وجلي', 'icon' => '⚡', 'desc' => 'تلميع الأرضيات والرخام والسيراميك بلمعان دائم', 'color' => 'yellow']
            ];
            
            foreach ($services as $index => $service):
            ?>
                <div class="group relative bg-gradient-to-br from-gray-50 to-white p-8 rounded-3xl border-2 border-gray-100 hover:border-<?= $service['color'] ?>-400 hover:shadow-2xl hover:shadow-<?= $service['color'] ?>-100 transition-all duration-500 hover:-translate-y-2">
                    <!-- Icon -->
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-<?= $service['color'] ?>-400 to-<?= $service['color'] ?>-600 rounded-2xl mb-6 text-3xl shadow-lg group-hover:scale-110 transition-transform duration-500">
                        <?= $service['icon'] ?>
                    </div>
                    
                    <!-- Title -->
                    <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-<?= $service['color'] ?>-600 transition-colors">
                        <?= $service['title'] ?>
                    </h3>
                    
                    <!-- Description -->
                    <p class="text-gray-600 leading-relaxed mb-4"><?= $service['desc'] ?></p>
                    
                    <!-- Arrow -->
                    <div class="flex items-center text-<?= $service['color'] ?>-600 font-bold opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="ml-2">اعرف المزيد</span>
                        <svg class="w-5 h-5 transform rotate-180 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </div>
                    
                    <!-- Decorative element -->
                    <div class="absolute top-4 left-4 w-20 h-20 bg-<?= $service['color'] ?>-400/10 rounded-full blur-2xl group-hover:w-32 group-hover:h-32 transition-all duration-500"></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CITIES SECTION -->
<section class="py-24 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="container-custom">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block bg-orange-100 text-orange-700 font-bold px-6 py-2 rounded-full mb-4">نغطي جميع المدن</span>
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">خدمات الكهرباء في مدن السعودية</h2>
            <p class="text-xl text-gray-600">نقدم خدماتنا في جميع المدن الرئيسية بالمملكة</p>
        </div>
        
        <!-- Cities Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php foreach ($cities as $index => $city): ?>
                <div class="group relative bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border-2 border-transparent hover:border-orange-400 cursor-pointer overflow-hidden">
                    <!-- Background Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-400/0 to-orange-600/0 group-hover:from-orange-400/10 group-hover:to-orange-600/5 transition-all duration-500"></div>
                    
                    <!-- Content -->
                    <div class="relative z-10 text-center">
                        <div class="text-4xl mb-3 group-hover:scale-110 transition-transform duration-500">📍</div>
                        <h3 class="font-black text-gray-900 text-lg mb-1 group-hover:text-orange-600 transition-colors">كهرباء في <?= $city ?></h3>
                        <p class="text-sm text-gray-500 group-hover:text-orange-600 transition-colors">خدمة فورية</p>
                    </div>
                    
                    <!-- Decorative corner -->
                    <div class="absolute top-0 right-0 w-16 h-16 bg-orange-400/20 rounded-bl-full transform translate-x-8 -translate-y-8 group-hover:translate-x-0 group-hover:translate-y-0 transition-transform duration-500"></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA FORM SECTION -->
<section id="request-service" class="py-24 relative overflow-hidden" style="background: linear-gradient(135deg, #ea580c 0%, #f97316 50%, #c2410c 100%);">
    <!-- Background Pattern -->
    <div class="absolute inset-0" style="background-image: linear-gradient(rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 20px 20px;"></div>
    
    <div class="container-custom relative z-10">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-4">احصل على عرض سعر مجاني</h2>
            <p class="text-xl text-orange-100">املأ النموذج وسنتصل بك خلال دقائق</p>
        </div>
        
        <!-- Form Container -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12">
                <?php
                require_once __DIR__ . '/../helpers/form_helper.php';
                render_service_request_form('service-electric-form', 'service', [
                    'button_text' => 'اطلب خدمة الكهرباء الآن',
                    'preselected_service' => 'electric',
                    'form_origin' => 'service_page_electric'
                ]);
                ?>
            </div>
        </div>
        
        <!-- Trust Badges -->
        <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto mt-12">
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center border border-white/20">
                <div class="text-3xl mb-2">🔒</div>
                <h4 class="font-bold text-white mb-1">معلوماتك آمنة</h4>
                <p class="text-orange-100 text-sm">نحمي بياناتك بأعلى معايير الأمان</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center border border-white/20">
                <div class="text-3xl mb-2">⚡</div>
                <h4 class="font-bold text-white mb-1">رد فوري</h4>
                <p class="text-orange-100 text-sm">نتصل بك خلال 5 دقائق</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center border border-white/20">
                <div class="text-3xl mb-2">💯</div>
                <h4 class="font-bold text-white mb-1">ضمان الجودة</h4>
                <p class="text-orange-100 text-sm">نضمن رضاك 100%</p>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
    animation: blob 7s infinite;
}
.animation-delay-2000 {
    animation-delay: 2s;
}
.animation-delay-4000 {
    animation-delay: 4s;
}
.bg-grid-white\/\[0\.05\] {
    background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px),
                      linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px);
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
