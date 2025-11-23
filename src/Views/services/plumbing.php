<?php
/**
 * Service Page: Plumbing (سباكة)
 * Business Model: Lead Generation Platform (Connecting Customers with Verified Professionals)
 * Design: Solid Green (#10b981), Professional, High Contrast
 */

$serviceKey = 'plumbing';
$serviceName = 'سباكة';
$serviceNameEn = 'Plumbing Services';
// Correct Business Model Description
$serviceDescription = 'منصة خدمة تربطك بأفضل السباكين والفنيين المعتمدين في منطقتك. كشف تسربات، تسليك مجاري، وتركيب أدوات صحية.';
$serviceMetaDescription = 'أفضل سباك في السعودية | كشف تسربات المياه | تسليك مجاري بالضغط | تركيب مغاسل وخلاطات | صيانة سباكة فورية | عروض أسعار';
$serviceKeywords = 'سباك, رقم سباك, كشف تسربات, تسليك مجاري, سباك وكهربائي, صيانة حمامات, تأسيس سباكة';
$cities = ['الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 'الخبر', 'تبوك', 'أبها'];
$pageTitle = 'اطلب أقرب سباك محترف في السعودية | كشف تسربات وصيانة | KhidmaApp';

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
  "serviceType": "Plumbing Intermediary",
  "provider": {
    "@type": "LocalBusiness",
    "name": "KhidmaApp - خدمة",
    "description": "منصة لربط العملاء بسباكين محترفين وشركات كشف تسربات",
    "priceRange": "$$"
  },
  "areaServed": {
    "@type": "Country",
    "name": "Saudi Arabia"
  }
}
</script>

<!-- HERO SECTION -->
<section class="relative py-20 md:py-32 overflow-hidden" style="background-color: #10b981;">
    <!-- Pattern Overlay -->
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'0 0 2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

    <div class="container-custom relative z-10">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 space-x-reverse bg-white/10 backdrop-blur-sm px-4 py-2 rounded-lg border border-white/20">
                <?php foreach ($breadcrumb as $index => $item): ?>
                    <li class="inline-flex items-center">
                        <?php if ($item['url']): ?>
                            <a href="<?= htmlspecialchars($item['url']) ?>" class="text-white hover:text-green-100 transition-colors text-sm font-bold">
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
                    <span class="text-sm font-bold">منصة معتمدة للسباكة وكشف التسربات</span>
                    <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>

                <!-- Headline - Specific Plumbing Messaging -->
                <h1 class="text-4xl md:text-6xl font-black mb-6 leading-tight">
                    لديك تسريب مياه أو
                    <span class="block mt-2 text-green-100">مشكلة سباكة طارئة؟</span>
                </h1>

                <!-- Description -->
                <p class="text-lg md:text-xl text-green-50 mb-8 leading-relaxed font-medium max-w-2xl">
                    لا تكسر منزلك! نربطك بسباكين محترفين يستخدمون أحدث الأجهزة لكشف التسربات وإصلاح الأعطال بدون تكسير.
                </p>

                <!-- Key Benefits -->
                <ul class="space-y-3 mb-10">
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center text-[#10b981] font-bold text-xs">✓</div>
                        <span class="font-bold">كشف تسربات المياه بالأجهزة الإلكترونية</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center text-[#10b981] font-bold text-xs">✓</div>
                        <span class="font-bold">تسليك المجاري وشفط البيارات بأحدث المعدات</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center text-[#10b981] font-bold text-xs">✓</div>
                        <span class="font-bold">ضمان على الإصلاح وقطع الغيار</span>
                    </li>
                </ul>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap gap-4">
                    <a href="#request-service" class="inline-flex items-center justify-center px-8 py-4 text-lg font-black text-gray-900 bg-white rounded-xl hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        اطلب سباك فوراً
                    </a>
                </div>
            </div>

            <!-- Hero Card -->
            <div class="hidden lg:block relative">
                <div class="bg-white rounded-3xl p-8 shadow-2xl border-4 border-white/20">
                    <div class="text-center mb-8">
                        <span class="text-6xl block mb-4">🚿</span>
                        <h3 class="text-2xl font-black text-gray-900">خدمات السباكة</h3>
                    </div>
                    
                    <div class="space-y-6 relative">
                        <!-- Step 1 -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-[#10b981] font-black text-xl shrink-0">1</div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">كشف التسربات</h4>
                                <p class="text-gray-600 text-sm">فحص دقيق بالأجهزة لتحديد مكان التسريب.</p>
                            </div>
                        </div>
                        
                        <!-- Step 2 -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-[#10b981] font-black text-xl shrink-0">2</div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">إصلاح الأعطال</h4>
                                <p class="text-gray-600 text-sm">معالجة المشكلة جذرياً بأقل تكلفة وتكسير.</p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-[#10b981] font-black text-xl shrink-0">3</div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">تركيب وصيانة</h4>
                                <p class="text-gray-600 text-sm">تركيب الخلاطات، المغاسل، السخانات والمضخات.</p>
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
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">حلول السباكة المتكاملة</h2>
            <p class="text-xl text-gray-600">نصلح جميع مشاكل المياه والصرف الصحي</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $services = [
                ['title' => 'كشف تسربات المياه', 'icon' => '💧', 'desc' => 'استخدام أجهزة الموجات الصوتية والحرارية لكشف التسربات بدون تكسير للجدران والأرضيات.'],
                ['title' => 'تسليك مجاري', 'icon' => '🛁', 'desc' => 'فتح انسداد المجاري والبالوعات وغرف التفتيش باستخدام مكائن الضغط والسبرينغ.'],
                ['title' => 'تأسيس سباكة', 'icon' => '🏗️', 'desc' => 'تأسيس شبكات التغذية والصرف للمباني الجديدة والحمامات والمطابخ بأجود المواسير.'],
                ['title' => 'تركيب أدوات صحية', 'icon' => '🚽', 'desc' => 'تركيب الكراسي، المغاسل، الشاورات، الخلاطات، والإكسسوارات بدقة عالية.'],
                ['title' => 'صيانة مضخات', 'icon' => '⚙️', 'desc' => 'تركيب وصيانة مضخات المياه والدينامو لتقوية ضغط المياه في الأدوار العليا.'],
                ['title' => 'سخانات وفلاتر', 'icon' => '🔥', 'desc' => 'تركيب وصيانة السخانات المركزية والعادية وفلاتر تنقية المياه المنزلية.']
            ];
            
            foreach ($services as $service):
            ?>
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all border border-gray-100 hover:border-green-500 group cursor-default">
                    <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center text-4xl mb-6 group-hover:bg-[#10b981] group-hover:text-white transition-colors">
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
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">لماذا تختار سباكين منصة خدمة؟</h2>
            <p class="text-xl text-gray-600">خبرة، أمانة، وسرعة في الإنجاز</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-20 h-20 bg-[#10b981]/10 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">🔍</div>
                <h3 class="text-xl font-black text-gray-900 mb-3">دقة التشخيص</h3>
                <p class="text-gray-600">نحدد مكان العطل بدقة لنحميك من التكسير العشوائي والتكاليف الزائدة.</p>
            </div>
            <div class="text-center p-6">
                <div class="w-20 h-20 bg-[#10b981]/10 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">⏱️</div>
                <h3 class="text-xl font-black text-gray-900 mb-3">استجابة طارئة</h3>
                <p class="text-gray-600">ندرك خطورة تسربات المياه، لذا نصلك في أسرع وقت ممكن لوقف الضرر.</p>
            </div>
            <div class="text-center p-6">
                <div class="w-20 h-20 bg-[#10b981]/10 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">✅</div>
                <h3 class="text-xl font-black text-gray-900 mb-3">مواد أصلية</h3>
                <p class="text-gray-600">نستخدم قطع غيار ومواسير أصلية لضمان عدم تكرار المشكلة مستقبلاً.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA / FORM SECTION -->
<section id="request-service" class="py-20" style="background-color: #10b981;">
    <div class="container-custom">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="text-white">
                <h2 class="text-3xl md:text-5xl font-black mb-6">اطلب سباك محترف الآن</h2>
                <p class="text-xl text-green-50 mb-8 leading-relaxed">
                    لا تنتظر تفاقم المشكلة. املأ النموذج وسيصلك أقرب سباك لمعاينة العطل وإصلاحه فوراً.
                </p>
                
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20">
                    <p class="font-bold text-lg mb-2">💡 هل فاتورة المياه مرتفعة؟</p>
                    <p class="text-green-50">قد يكون لديك تسريب خفي غير مرئي. اطلب خدمة "كشف تسربات" لفحص الشبكة وتوفير المال.</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-8 shadow-2xl">
                <h3 class="text-2xl font-black text-gray-900 mb-2 text-center">طلب خدمة سباكة</h3>
                <p class="text-center text-gray-500 mb-8 text-sm">نصلك أينما كنت</p>
                <?php
                require_once __DIR__ . '/../helpers/form_helper.php';
                render_service_request_form('plumbing-request-form', 'plumbing', [
                    'button_text' => 'إرسال الطلب للسباكين',
                    'preselected_service' => 'plumbing',
                    'form_origin' => 'plumbing_page',
                    'compact' => false
                ]);
                ?>
            </div>
        </div>
    </div>
</section>

<!-- CITIES SECTION -->
<section class="py-16 bg-white border-t border-gray-100">
    <div class="container-custom text-center">
        <h2 class="text-2xl font-black text-gray-900 mb-8">سباكين في جميع المدن</h2>
        <div class="flex flex-wrap justify-center gap-3">
            <?php foreach ($cities as $city): ?>
                <span class="px-6 py-3 bg-gray-50 rounded-full text-gray-700 font-bold border border-gray-200 cursor-default hover:border-[#10b981] hover:text-[#10b981] transition-colors">
                    سباك في <?= $city ?>
                </span>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
