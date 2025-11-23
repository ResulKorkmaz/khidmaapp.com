<!DOCTYPE html>
<html dir="rtl" lang="ar-SA">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- SEO Tags -->
    <title><?= $pageTitle ?? 'خدمة - منصة الخدمات الرائدة في السعودية' ?></title>
    <meta name="description" content="<?= $pageDescription ?? 'منصة خدماتية متخصصة في ربط العملاء بمقدمي الخدمات المنزلية والتجارية في السعودية' ?>">
    <meta name="keywords" content="<?= $pageKeywords ?? 'خدمات, دهانات, ترميم, تنظيف, صيانة, كهرباء, سباكة, مكيفات, السعودية' ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/assets/images/logo.png">
    
    <!-- Tailwind CSS -->
    <link href="/assets/css/app.css" rel="stylesheet">
    
    <!-- Theme Color -->
    <meta name="theme-color" content="#059669">
</head>
<body class="bg-gray-50">

<?php
// Check if we are on the home page
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$isHome = ($currentPath === '/' || $currentPath === '/index.php');
$linkPrefix = $isHome ? '' : '/';
?>

<!-- Header -->
<header id="main-header" class="fixed top-0 w-full z-[100] shadow-md transition-colors duration-300" style="background-color: #059669;">
    <div class="container-custom">
        <div class="flex items-center justify-between h-20 md:h-24">
            
            <!-- Logo -->
            <div class="flex items-center flex-shrink-0 z-[101]">
                <a href="/" class="group relative block">
                    <div class="bg-white/10 rounded-2xl p-2 border border-white/20 transition-transform duration-300 group-hover:scale-105">
                        <img src="/assets/images/logo-new.png?v=<?= time() . rand(1000, 9999) ?>" alt="KhidmaApp" 
                             class="w-10 h-10 md:w-12 md:h-12 object-contain filter brightness-0 invert" 
                             onload="console.log('Logo loaded')"
                             onerror="console.error('Logo failed')"
                             style="max-width: none;">
                    </div>
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-8 mx-auto">
                <a href="/" class="nav-link-white <?= $isHome ? 'nav-active' : '' ?>" style="color: #ffffff !important;">الرئيسية</a>
                <a href="<?= $linkPrefix ?>#services" class="nav-link-white" style="color: #ffffff !important;">الخدمات</a>
                <a href="<?= $linkPrefix ?>#about" class="nav-link-white" style="color: #ffffff !important;">عن خدمة</a>
                <a href="<?= $linkPrefix ?>#faq" class="nav-link-white" style="color: #ffffff !important;">الأسئلة الشائعة</a>
            </nav>
            
            <!-- Right Side Actions -->
            <div class="flex items-center gap-4 z-[101]">
                
                <!-- Provider Login -->
                <button onclick="openProviderAuthModal()" class="hidden md:inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-white border border-white hover:bg-white/10 font-bold text-sm transition-all duration-300 hover:-translate-y-0.5" style="color: #ffffff !important; border-color: #ffffff !important;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>دخول المهنيين</span>
                </button>
                
                <!-- Request Service CTA -->
                <a href="<?= $linkPrefix ?>#request-service" class="hidden md:inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white text-emerald-700 font-bold text-sm shadow-lg hover:bg-gray-50 transition-all duration-300 hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>اطلب خدمة</span>
                </a>
                
                <!-- Mobile Menu Toggle Button (Inline JS for reliability) -->
                <button type="button" 
                        id="mobile-menu-btn" 
                        onclick="toggleMobileMenu(event)"
                        class="lg:hidden p-3 rounded-xl hover:bg-white/10 transition-colors focus:outline-none touch-manipulation cursor-pointer relative z-[9999]">
                    <div class="w-6 h-5 flex flex-col justify-between pointer-events-none">
                        <span class="menu-line w-full h-0.5 bg-white rounded-full transition-all duration-300 origin-right" style="background-color: #ffffff !important;"></span>
                        <span class="menu-line w-full h-0.5 bg-white rounded-full transition-all duration-300" style="background-color: #ffffff !important;"></span>
                        <span class="menu-line w-full h-0.5 bg-white rounded-full transition-all duration-300 origin-right" style="background-color: #ffffff !important;"></span>
                    </div>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu" 
         class="lg:hidden fixed inset-0 top-[80px] bg-emerald-700 z-[90] transform translate-x-full opacity-0 pointer-events-none transition-all duration-500 ease-in-out flex flex-col overflow-y-auto border-t border-emerald-600" 
         style="height: calc(100vh - 80px); background-color: #047857 !important; will-change: transform, opacity;">
        <div class="p-6 space-y-8 min-h-full flex flex-col">
            <nav class="flex flex-col gap-4">
                <a href="/" onclick="toggleMobileMenu(event)" class="mobile-link-white <?= $isHome ? 'active' : '' ?>" style="color: #ffffff !important;">
                    <span class="text-2xl">🏠</span>
                    <span class="text-xl font-bold">الرئيسية</span>
                </a>
                <a href="<?= $linkPrefix ?>#services" onclick="toggleMobileMenu(event)" class="mobile-link-white" style="color: #ffffff !important;">
                    <span class="text-2xl">🛠️</span>
                    <span class="text-xl font-bold">الخدمات</span>
                </a>
                <a href="<?= $linkPrefix ?>#about" onclick="toggleMobileMenu(event)" class="mobile-link-white" style="color: #ffffff !important;">
                    <span class="text-2xl">ℹ️</span>
                    <span class="text-xl font-bold">عن خدمة</span>
                </a>
                <a href="<?= $linkPrefix ?>#faq" onclick="toggleMobileMenu(event)" class="mobile-link-white" style="color: #ffffff !important;">
                    <span class="text-2xl">❓</span>
                    <span class="text-xl font-bold">الأسئلة الشائعة</span>
                </a>
            </nav>
            
            <div class="mt-auto pt-8 border-t border-emerald-600 flex flex-col gap-4 pb-8">
                <a href="<?= $linkPrefix ?>#request-service" onclick="toggleMobileMenu(event)" class="flex items-center justify-center gap-3 w-full py-4 bg-white text-emerald-700 rounded-2xl font-bold text-xl shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>اطلب خدمة الآن</span>
                </a>
                <button onclick="openProviderAuthModal(); toggleMobileMenu(event);" class="flex items-center justify-center gap-3 w-full py-4 bg-emerald-800 text-white rounded-2xl font-bold text-xl border border-emerald-600" style="color: #ffffff !important;">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>دخول المهنيين</span>
                </button>
            </div>
        </div>
    </div>
</header>

<style>
/* Styles */
.nav-link-white {
    @apply text-white font-bold text-lg hover:text-emerald-100 transition-colors py-2 relative opacity-90 hover:opacity-100 cursor-pointer;
    color: #ffffff !important;
}
.nav-link-white::after {
    content: '';
    @apply absolute bottom-0 right-0 w-0 h-0.5 bg-white transition-all duration-300;
}
.nav-link-white:hover::after, .nav-link-white.nav-active::after {
    @apply w-full;
}
.nav-link-white.nav-active {
    @apply text-white opacity-100;
}

.mobile-link-white {
    @apply flex items-center gap-4 p-4 rounded-2xl text-white hover:bg-emerald-600 transition-all cursor-pointer select-none;
    color: #ffffff !important;
}
.mobile-link-white:active {
    @apply bg-emerald-800 transform scale-[0.98];
}
.mobile-link-white.active {
    @apply bg-emerald-800 text-white border-r-4 border-emerald-400;
}

.header-scrolled {
    background-color: #047857 !important; /* emerald-700 */
    @apply shadow-xl;
}

/* Mobile Menu State Classes - FORCE VISIBILITY */
#mobile-menu.is-open {
    transform: translateX(0) !important;
    opacity: 1 !important;
    pointer-events: auto !important;
    visibility: visible !important;
}

/* Hamburger Animation - Keep White Color */
#mobile-menu-btn.active span:nth-child(1) {
    @apply -rotate-45 -translate-y-[7px];
    background-color: #ffffff !important;
}
#mobile-menu-btn.active span:nth-child(2) {
    @apply opacity-0;
    background-color: #ffffff !important;
}
#mobile-menu-btn.active span:nth-child(3) {
    @apply rotate-45 translate-y-[7px];
    background-color: #ffffff !important;
}
</style>

<script>
// Mobile Menu Toggle - Clean & Professional
window.toggleMobileMenu = function(e) {
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    const body = document.body;
    
    if (!btn || !menu) return;
    
    // Toggle classes
    const isOpen = menu.classList.contains('is-open');
    
    if (isOpen) {
        // Close
        btn.classList.remove('active');
        menu.classList.remove('is-open');
        body.style.overflow = '';
    } else {
        // Open
        btn.classList.add('active');
        menu.classList.add('is-open');
        body.style.overflow = 'hidden';
    }
};

// Scroll Effect
document.addEventListener('DOMContentLoaded', function() {
    const header = document.getElementById('main-header');
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 10) {
            header.classList.add('header-scrolled');
        } else {
            header.classList.remove('header-scrolled');
        }
    });
});
</script>
