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
<?php require __DIR__ . '/components/cta_section.php'; ?>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
