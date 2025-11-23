<?php
/**
 * Service Page: AC (تكييف)
 * SEO-Optimized with Local SEO for Saudi Arabia
 */

$serviceKey = 'ac';
$serviceName = 'تكييف';
$serviceNameEn = 'Air Conditioning Services';
$serviceDescription = 'خدمات تكييف احترافية في السعودية - صيانة وتركيب المكيفات';
$serviceMetaDescription = 'خدمات تكييف محترفة في السعودية | صيانة مكيفات | تركيب مكيفات | فني تكييف | أسعار تنافسية في الرياض جدة الدمام';
$serviceKeywords = 'تكييف, صيانة مكيفات, تركيب مكيف, فني تكييف, تنظيف مكيفات, مكيف في الرياض, مكيف في جدة';
$cities = ['الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 'الخبر', 'تبوك', 'أبها'];
$pageTitle = 'خدمات تكييف احترافية في السعودية | فني تكييف محترف | KhidmaApp';
$breadcrumb = [['name' => 'الرئيسية', 'url' => '/'], ['name' => 'الخدمات', 'url' => '/#services'], ['name' => $serviceName, 'url' => '']];

require_once __DIR__ . '/../layouts/header.php';
?>

<script type="application/ld+json">
{"@context": "https://schema.org", "@type": "Service", "serviceType": "<?= $serviceName ?> - <?= $serviceNameEn ?>", "provider": {"@type": "LocalBusiness", "name": "KhidmaApp - خدمة", "priceRange": "$$", "aggregateRating": {"@type": "AggregateRating", "ratingValue": "4.9", "reviewCount": "1350"}}}
</script>

<section class="relative bg-gradient-to-br from-sky-900 via-blue-800 to-slate-900 text-white py-16 md:py-24">
    <div class="container-custom relative z-10">
        <nav class="flex mb-8 text-sm"><ol class="inline-flex items-center space-x-reverse space-x-3"><?php foreach ($breadcrumb as $index => $item): ?><li><?php if ($item['url']): ?><a href="<?= $item['url'] ?>" class="text-sky-200 hover:text-white"><?= $item['name'] ?></a><?php else: ?><span class="text-white font-semibold"><?= $item['name'] ?></span><?php endif; ?><?php if ($index < count($breadcrumb) - 1): ?><svg class="w-4 h-4 mx-2 rotate-180 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg><?php endif; ?></li><?php endforeach; ?></ol></nav>
        <div class="grid lg:grid-cols-2 gap-12"><div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6">خدمات <span class="bg-gradient-to-r from-sky-400 to-blue-400 bg-clip-text text-transparent">تكييف احترافية</span></h1>
            <p class="text-xl text-sky-100 mb-8">خدمات تكييف شاملة - صيانة، تركيب، وتنظيف المكيفات بأعلى معايير الجودة في جميع مدن المملكة</p>
            <a href="#request-service" class="inline-flex items-center gap-2 bg-white text-sky-900 font-bold px-8 py-4 rounded-xl shadow-2xl hover:scale-105 transition-all">اطلب فني تكييف الآن</a>
            <div class="grid grid-cols-3 gap-6 mt-12 pt-8 border-t border-white/20">
                <div class="text-center"><div class="text-3xl font-bold">5500+</div><div class="text-sm">عميل</div></div>
                <div class="text-center"><div class="text-3xl font-bold">4.9/5</div><div class="text-sm">تقييم</div></div>
                <div class="text-center"><div class="text-3xl font-bold">24/7</div><div class="text-sm">متاح</div></div>
            </div>
        </div></div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="container-custom">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">خدمات التكييف الشاملة</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <?php
            $services = [
                ['title' => 'صيانة مكيفات', 'icon' => '❄️', 'desc' => 'صيانة دورية وإصلاح جميع أنواع المكيفات'],
                ['title' => 'تركيب مكيفات', 'icon' => '🔧', 'desc' => 'تركيب مكيفات جديدة بأعلى معايير الجودة'],
                ['title' => 'تنظيف مكيفات', 'icon' => '🧹', 'desc' => 'تنظيف وتعقيم المكيفات بالكامل'],
                ['title' => 'شحن فريون', 'icon' => '🌡️', 'desc' => 'شحن الفريون وفحص نظام التبريد'],
                ['title' => 'فحص دوري', 'icon' => '🔍', 'desc' => 'فحص شامل لجميع أجزاء المكيف'],
                ['title' => 'عقود صيانة', 'icon' => '📋', 'desc' => 'عقود صيانة سنوية بأسعار تنافسية']
            ];
            foreach ($services as $s): ?>
                <div class="bg-gray-50 p-6 rounded-2xl border hover:border-sky-400 hover:shadow-xl transition-all">
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
        <h2 class="text-3xl font-bold text-center mb-12">خدمات التكييف في مدن السعودية</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php foreach ($cities as $city): ?>
                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl text-center border-2 hover:border-sky-500 transition-all">
                    <div class="text-3xl mb-2">📍</div><h3 class="font-bold">تكييف في <?= $city ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="request-service" class="py-16 md:py-24 bg-gradient-to-br from-sky-900 to-blue-800 text-white">
    <div class="container-custom max-w-4xl">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">اطلب فني تكييف الآن</h2>
        <div class="bg-white/10 backdrop-blur-md rounded-3xl p-8 border border-white/20">
            <?php
            require_once __DIR__ . '/../helpers/form_helper.php';
            render_service_request_form('service-ac-form', 'service', ['dark_theme' => true, 'button_text' => 'اطلب فني تكييف', 'preselected_service' => 'ac', 'form_origin' => 'service_page_ac']);
            ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

