<?php
/**
 * Service Page: Renovation (ترميم)
 * SEO-Optimized with Local SEO for Saudi Arabia
 */

$serviceKey = 'renovation';
$serviceName = 'ترميم';
$serviceNameEn = 'Renovation Services';
$serviceDescription = 'خدمات ترميم وتجديد المنازل في السعودية - ترميم شامل بأعلى جودة';
$serviceMetaDescription = 'خدمات ترميم محترفة في السعودية | ترميم منازل | تجديد مباني | ترميم شقق | أسعار تنافسية في الرياض جدة الدمام';
$serviceKeywords = 'ترميم, ترميم منازل, تجديد منازل, ترميم شقق, مقاول ترميم, ترميم في الرياض, ترميم في جدة';
$cities = ['الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 'الخبر', 'تبوك', 'أبها'];
$pageTitle = 'خدمات ترميم احترافية في السعودية | مقاول ترميم محترف | KhidmaApp';
$breadcrumb = [['name' => 'الرئيسية', 'url' => '/'], ['name' => 'الخدمات', 'url' => '/#services'], ['name' => $serviceName, 'url' => '']];

require_once __DIR__ . '/../layouts/header.php';
?>

<script type="application/ld+json">
{"@context": "https://schema.org", "@type": "Service", "serviceType": "<?= $serviceName ?> - <?= $serviceNameEn ?>", "provider": {"@type": "LocalBusiness", "name": "KhidmaApp - خدمة", "priceRange": "$$$", "aggregateRating": {"@type": "AggregateRating", "ratingValue": "4.9", "reviewCount": "900"}}}
</script>

<section class="relative bg-gradient-to-br from-amber-900 via-orange-800 to-slate-900 text-white py-16 md:py-24">
    <div class="container-custom relative z-10">
        <nav class="flex mb-8 text-sm"><ol class="inline-flex items-center space-x-reverse space-x-3"><?php foreach ($breadcrumb as $index => $item): ?><li><?php if ($item['url']): ?><a href="<?= $item['url'] ?>" class="text-amber-200 hover:text-white"><?= $item['name'] ?></a><?php else: ?><span class="text-white font-semibold"><?= $item['name'] ?></span><?php endif; ?><?php if ($index < count($breadcrumb) - 1): ?><svg class="w-4 h-4 mx-2 rotate-180 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg><?php endif; ?></li><?php endforeach; ?></ol></nav>
        <div class="grid lg:grid-cols-2 gap-12"><div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6">خدمات <span class="bg-gradient-to-r from-amber-400 to-orange-400 bg-clip-text text-transparent">ترميم وتجديد</span></h1>
            <p class="text-xl text-amber-100 mb-8">خدمات ترميم شاملة للمنازل والمباني - تجديد ورفع مستوى المباني القديمة بأحدث المعايير والتقنيات</p>
            <a href="#request-service" class="inline-flex items-center gap-2 bg-white text-amber-900 font-bold px-8 py-4 rounded-xl shadow-2xl hover:scale-105 transition-all">اطلب خدمة ترميم الآن</a>
            <div class="grid grid-cols-3 gap-6 mt-12 pt-8 border-t border-white/20">
                <div class="text-center"><div class="text-3xl font-bold">3500+</div><div class="text-sm">مشروع</div></div>
                <div class="text-center"><div class="text-3xl font-bold">4.9/5</div><div class="text-sm">تقييم</div></div>
                <div class="text-center"><div class="text-3xl font-bold">15+</div><div class="text-sm">سنة خبرة</div></div>
            </div>
        </div></div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="container-custom">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">خدمات الترميم الشاملة</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <?php
            $services = [
                ['title' => 'ترميم منازل', 'icon' => '🏠', 'desc' => 'ترميم شامل للمنازل والفلل القديمة'],
                ['title' => 'تجديد مباني', 'icon' => '🏗️', 'desc' => 'تجديد المباني السكنية والتجارية'],
                ['title' => 'ترميم واجهات', 'icon' => '🏛️', 'desc' => 'ترميم الواجهات الخارجية بأحدث المواد'],
                ['title' => 'معالجة تشققات', 'icon' => '🔨', 'desc' => 'معالجة التشققات والتصدعات في المباني'],
                ['title' => 'عزل مائي وحراري', 'icon' => '💧', 'desc' => 'تركيب العزل المائي والحراري'],
                ['title' => 'توسعة وإضافات', 'icon' => '📐', 'desc' => 'إضافة غرف وتوسعة المباني']
            ];
            foreach ($services as $s): ?>
                <div class="bg-gray-50 p-6 rounded-2xl border hover:border-amber-400 hover:shadow-xl transition-all">
                    <div class="text-5xl mb-4"><?= $s['icon'] ?></div>
                    <h3 class="text-xl font-bold mb-3"><?= $s['title'] ?></h3>
                    <p class="text-gray-600"><?= $s['desc'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-16 bg-gray-50">
    <div class="container-custom">
        <h2 class="text-3xl font-bold text-center mb-12">خدمات الترميم في مدن السعودية</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php foreach ($cities as $city): ?>
                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl text-center border-2 hover:border-amber-500 transition-all">
                    <div class="text-3xl mb-2">📍</div><h3 class="font-bold">ترميم في <?= $city ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="request-service" class="py-16 md:py-24 bg-gradient-to-br from-amber-900 to-orange-800 text-white">
    <div class="container-custom max-w-4xl">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">اطلب خدمة ترميم الآن</h2>
        <div class="bg-white/10 backdrop-blur-md rounded-3xl p-8 border border-white/20">
            <?php
            require_once __DIR__ . '/../helpers/form_helper.php';
            render_service_request_form('service-renovation-form', 'service', ['dark_theme' => true, 'button_text' => 'اطلب خدمة ترميم', 'preselected_service' => 'renovation', 'form_origin' => 'service_page_renovation']);
            ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

