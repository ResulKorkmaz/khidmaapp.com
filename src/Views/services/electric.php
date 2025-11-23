<?php
/**
 * Service Page: Electric (كهرباء)
 * Business Model: Lead Generation Platform (Connecting Customers with Verified Professionals)
 * Design: Solid Blue (#3B9DD9), Professional, High Contrast
 */

$serviceKey = 'electric';
$serviceName = 'كهرباء';
$serviceNameEn = 'Electrical Services';
// Correct Business Model Description
$serviceDescription = 'منصة خدمة تربطك بأفضل الكهربائيين المعتمدين في منطقتك. إصلاح أعطال، تمديدات، تركيب إضاءة، وأنظمة ذكية.';
$serviceMetaDescription = 'أفضل كهربائي منازل في السعودية | إصلاح التماس كهربائي | تركيب ثريات وإضاءة | تمديد كابلات | صيانة طبلون | كهربائي 24 ساعة';
$serviceKeywords = 'كهربائي, رقم كهربائي, صيانة كهرباء, التماس كهربائي, تركيب ثريات, تمديد اسلاك, كهربائي منازل';
$cities = ['الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 'الخبر', 'تبوك', 'أبها'];
$pageTitle = 'اطلب أفضل كهربائي منازل في السعودية | صيانة فورية وآمنة | KhidmaApp';

$breadcrumb = [
    ['name' => 'الرئيسية', 'url' => '/'],
    ['name' => 'الخدمات', 'url' => '/#services'],
    ['name' => $serviceName, 'url' => '']
];

require_once __DIR__ . '/../layouts/header.php';
?>

<!-- Schema.org -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Service",
  "serviceType": "Electrical Services Intermediary",
  "provider": {
    "@type": "LocalBusiness",
    "name": "KhidmaApp - خدمة",
    "description": "منصة لربط العملاء بكهربائيين محترفين وشركات صيانة",
    "priceRange": "$$"
  },
  "areaServed": {
    "@type": "Country",
    "name": "Saudi Arabia"
  }
}
</script>

<!-- HERO SECTION -->
<section class="relative py-20 md:py-32 overflow-hidden" style="background-color: #3B9DD9;">
    <!-- Pattern Overlay -->
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'0 0 2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

    <div class="container-custom relative z-10">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 space-x-reverse bg-white/10 backdrop-blur-sm px-4 py-2 rounded-lg border border-white/20">
                <?php foreach ($breadcrumb as $index => $item): ?>
                    <li class="inline-flex items-center">
                        <?php if ($item['url']): ?>
                            <a href="<?= htmlspecialchars($item['url']) ?>" class="text-white hover:text-blue-100 transition-colors text-sm font-bold">
                                <?= htmlspecialchars($item['name']) ?>
                            </a>
                        <?php else: ?>
                            <span class="text-white font-black text-sm"><?= htmlspecialchars($item['name']) ?></span>
                        <?php endif; ?>
                        <?php if ($index < count($breadcrumb) - 1): ?>
                            <svg class="w-3 h-3 mx-2 text-white/70 rotate-180" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        </nav>

        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="text-right text-white">
                <!-- Platform Badge -->
                <div class="inline-flex items-center gap-2 bg-white/20 border border-white/30 px-4 py-2 rounded-full mb-6 backdrop-blur-sm">
                    <span class="text-sm font-bold">منصة معتمدة لخدمات الكهرباء</span>
                    <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>

                <!-- Headline - Specific Electric Messaging -->
                <h1 class="text-4xl md:text-6xl font-black mb-6 leading-tight">
                    كهرباء المنزل انقطعت أو
                    <span class="block mt-2 text-blue-100">تواجه التماس خطير؟</span>
                </h1>

                <!-- Description -->
                <p class="text-lg md:text-xl text-blue-50 mb-8 leading-relaxed font-medium max-w-2xl">
                    الكهرباء لا تحتمل الانتظار. نربطك فوراً بأقرب كهربائي محترف وموثوق لإصلاح الأعطال، تركيب الإضاءة، وتأمين منزلك من المخاطر.
                </p>

                <!-- Key Benefits -->
                <ul class="space-y-3 mb-10">
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center text-[#3B9DD9] font-bold text-xs">✓</div>
                        <span class="font-bold">كهربائيون مؤهلون للتعامل مع الجهد العالي</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center text-[#3B9DD9] font-bold text-xs">✓</div>
                        <span class="font-bold">إصلاح القواطع والأسلاك بأدوات آمنة</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center text-[#3B9DD9] font-bold text-xs">✓</div>
                        <span class="font-bold">خدمة طوارئ سريعة من مقدمي الخدمة</span>
                    </li>
                </ul>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap gap-4">
                    <a href="#request-service" class="inline-flex items-center justify-center px-8 py-4 text-lg font-black text-[#1E5A8A] bg-white rounded-xl hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        اطلب كهربائي الآن
                    </a>
                </div>
            </div>

            <!-- Hero Card -->
            <div class="hidden lg:block relative">
                <div class="bg-white rounded-3xl p-8 shadow-2xl border-4 border-white/20">
                    <div class="text-center mb-8">
                        <span class="text-6xl block mb-4">⚡</span>
                        <h3 class="text-2xl font-black text-gray-900">خدمات الكهرباء</h3>
                    </div>
                    
                    <div class="space-y-6 relative">
                        <!-- Step 1 -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-[#3B9DD9] font-black text-xl shrink-0">1</div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">كشف الأعطال</h4>
                                <p class="text-gray-600 text-sm">الفني يحدد سبب انقطاع الكهرباء.</p>
                            </div>
                        </div>
                        
                        <!-- Step 2 -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-[#3B9DD9] font-black text-xl shrink-0">2</div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">تلقي العروض</h4>
                                <p class="text-gray-600 text-sm">اختر أفضل كهربائي قريب منك.</p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-[#3B9DD9] font-black text-xl shrink-0">3</div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">إصلاح آمن</h4>
                                <p class="text-gray-600 text-sm">صيانة مضمونة للطبلون والأسلاك.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SERVICES GRID -->
<section class="py-20 bg-gray-50">
    <div class="container-custom">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">خدمات كهربائية شاملة</h2>
            <p class="text-xl text-gray-600">نربطك بخبراء لجميع مشاكل الكهرباء المنزلية والتجارية</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $services = [
                ['title' => 'إصلاح التماس كهربائي', 'icon' => '🔌', 'desc' => 'فحص وإصلاح القفلات والتماسات الكهرباء التي تسبب انقطاع التيار.'],
                ['title' => 'صيانة الطبلون', 'icon' => '⚡', 'desc' => 'تغيير القواطع (المفاتيح) التالفة، وترتيب أسلاك الطبلون، وزيادة الأحمال.'],
                ['title' => 'تمديد وتأسيس', 'icon' => '🏗️', 'desc' => 'مقاولون لتأسيس شبكة الكهرباء للمباني الجديدة، وتمديد كابلات وأسلاك إضافية.'],
                ['title' => 'تركيب إضاءة', 'icon' => '💡', 'desc' => 'فنيون لتركيب الثريات، السبوت لايت، الإضاءة المخفية (LED)، وكشافات الحدائق.'],
                ['title' => 'أفياش ومفاتيح', 'icon' => '🔘', 'desc' => 'تغيير وتركيب الأفياش ومفاتيح الإنارة وتوزيع نقاط الكهرباء الجديدة.'],
                ['title' => 'المنزل الذكي', 'icon' => '📱', 'desc' => 'شركات متخصصة لتركيب أنظمة التحكم الذكي بالإضاءة والمكيفات وكاميرات المراقبة.']
            ];
            
            foreach ($services as $service):
            ?>
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all border border-gray-100 hover:border-[#3B9DD9] group cursor-default">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-4xl mb-6 group-hover:bg-[#3B9DD9] group-hover:text-white transition-colors">
                        <?= $service['icon'] ?>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-3"><?= $service['title'] ?></h3>
                    <p class="text-gray-600 leading-relaxed"><?= $service['desc'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- WHY CHOOSE US -->
<section class="py-20 bg-white border-t border-gray-100">
    <div class="container-custom">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">لماذا تختار كهربائيي منصة خدمة؟</h2>
            <p class="text-xl text-gray-600">السلامة أولاً.. والجودة دائماً</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-20 h-20 bg-[#3B9DD9]/10 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">🛡️</div>
                <h3 class="text-xl font-black text-gray-900 mb-3">السلامة والأمان</h3>
                <p class="text-gray-600">الفنيون يلتزمون بأعلى معايير السلامة لتجنب أي مخاطر كهربائية.</p>
            </div>
            <div class="text-center p-6">
                <div class="w-20 h-20 bg-[#3B9DD9]/10 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">👨‍🔧</div>
                <h3 class="text-xl font-black text-gray-900 mb-3">فنيون معتمدون</h3>
                <p class="text-gray-600">نتحقق من هوية وكفاءة الكهربائيين لضمان التعامل مع محترفين فقط.</p>
            </div>
            <div class="text-center p-6">
                <div class="w-20 h-20 bg-[#3B9DD9]/10 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">⏱️</div>
                <h3 class="text-xl font-black text-gray-900 mb-3">سرعة الوصول</h3>
                <p class="text-gray-600">شبكة واسعة من الكهربائيين تغطي جميع الأحياء لضمان وصول سريع عند الطوارئ.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA / FORM SECTION -->
<section id="request-service" class="py-20" style="background-color: #3B9DD9;">
    <div class="container-custom">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="text-white">
                <h2 class="text-3xl md:text-5xl font-black mb-6">اطلب كهربائي الآن</h2>
                <p class="text-xl text-blue-50 mb-8 leading-relaxed">
                    لا تخاطر بالتعامل مع الكهرباء بنفسك. سجل طلبك وسنرسل لك كهربائياً محترفاً لحل المشكلة بأمان وسرعة.
                </p>
                
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20">
                    <p class="font-bold text-lg mb-2">💡 القواطع تفصل باستمرار؟</p>
                    <p class="text-blue-50">هذا مؤشر على حمل زائد أو التماس. اطلب فحصاً فورياً لتجنب تلف الأجهزة أو الحريق لا قدر الله.</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-8 shadow-2xl">
                <h3 class="text-2xl font-black text-gray-900 mb-2 text-center">طلب خدمة كهرباء</h3>
                <p class="text-center text-gray-500 mb-8 text-sm">خدمة آمنة ومضمونة</p>
                <?php
                require_once __DIR__ . '/../helpers/form_helper.php';
                render_service_request_form('electric-request-form', 'electric', [
                    'button_text' => 'إرسال الطلب للكهربائيين',
                    'preselected_service' => 'electric',
                    'form_origin' => 'electric_page',
                    'compact' => false,
                    'button_classes' => 'btn-primary w-full text-lg py-4 relative bg-[#3B9DD9] hover:bg-[#2B7AB8] text-white font-bold rounded-xl'
                ]);
                ?>
            </div>
        </div>
    </div>
</section>

<!-- CITIES SECTION -->
<section class="py-16 bg-white border-t border-gray-100">
    <div class="container-custom text-center">
        <h2 class="text-2xl font-black text-gray-900 mb-8">كهربائيين في جميع المدن</h2>
        <div class="flex flex-wrap justify-center gap-3">
            <?php foreach ($cities as $city): ?>
                <span class="px-6 py-3 bg-gray-50 rounded-full text-gray-700 font-bold border border-gray-200 cursor-default hover:border-[#3B9DD9] hover:text-[#3B9DD9] transition-colors">
                    كهربائي في <?= $city ?>
                </span>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
