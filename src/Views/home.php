<?php
/**
 * Home Page
 * 
 * Main landing page with all sections organized as components
 * 
 * @package KhidmaApp
 */

$pageTitle = 'الصفحة الرئيسية - ' . SITE_TITLE_AR;
$pageDescription = 'منصة خدمة - اكتشف أفضل مقدمي الخدمات في مدينتك. نربط بين العملاء ومقدمي الخدمات المنزلية والتجارية الموثوقين في جميع أنحاء المملكة العربية السعودية';
$pageKeywords = 'خدمات منزلية, صيانة, دهانات, تنظيف, كهرباء, سباكة, تكييف';

// Load helper functions
require_once __DIR__ . '/helpers/form_helper.php';

require_once __DIR__ . '/layouts/header.php';
?>

<!-- Hero Section -->
<?php require __DIR__ . '/components/hero_section.php'; ?>

<!-- Services Section -->
<?php require __DIR__ . '/components/services_section.php'; ?>

<!-- About Section -->
<?php require __DIR__ . '/components/about_section.php'; ?>

<!-- Join as Provider Section -->
<?php require __DIR__ . '/components/join_provider_section.php'; ?>

<!-- FAQ Section -->
<?php require __DIR__ . '/components/faq_section.php'; ?>

<!-- Final CTA Section -->
<section id="request-service" class="section-padding bg-gradient-to-r from-blue-600 to-indigo-700 relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-64 md:w-96 h-64 md:h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl opacity-10"></div>
        <div class="absolute -bottom-40 -left-40 w-64 md:w-96 h-64 md:h-96 bg-cyan-400 rounded-full mix-blend-overlay filter blur-3xl opacity-10"></div>
    </div>
    
    <div class="container-custom relative z-10 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white mb-4 md:mb-6">
                هل أنت مستعد للبدء؟
            </h2>
            <p class="text-base md:text-lg lg:text-xl text-blue-100 mb-8 md:mb-12 max-w-2xl mx-auto leading-relaxed">
                احصل على أفضل الخدمات من مقدمي خدمات محترفين في منطقتك. املأ النموذج الآن وسنوصل طلبك فوراً!
            </p>
            
            <!-- Service Request Form -->
            <div class="max-w-2xl mx-auto bg-white/10 backdrop-blur-2xl rounded-2xl md:rounded-3xl p-6 md:p-8 border border-white/20 shadow-2xl">
                <?php
                render_service_request_form('cta-service-form', 'cta', [
                    'compact' => false,
                    'button_text' => 'إرسال الطلب',
                    'button_classes' => 'btn-primary w-full py-3 md:py-4 text-base md:text-lg',
                    'form_origin' => 'cta_bottom'
                ]);
                ?>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
