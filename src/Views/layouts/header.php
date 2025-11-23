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

<header class="fixed top-0 w-full bg-emerald-600 border-b border-emerald-500 z-50 transition-all duration-500 shadow-md">
    <div class="container-custom">
        <div class="flex items-center justify-between h-20 md:h-24">
            
            <!-- Logo Section -->
            <div class="flex items-center flex-shrink-0">
                <a href="/" class="group relative block">
                    <!-- Logo Box (Transparent on Green Header) -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-2 border border-white/10 transition-transform duration-300 group-hover:scale-105">
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
                <a href="/" class="nav-link-white <?= $isHome ? 'nav-active' : '' ?>">الرئيسية</a>
                <a href="<?= $linkPrefix ?>#services" class="nav-link-white">الخدمات</a>
                <a href="<?= $linkPrefix ?>#about" class="nav-link-white">عن خدمة</a>
                <a href="<?= $linkPrefix ?>#faq" class="nav-link-white">الأسئلة الشائعة</a>
            </nav>
            
            <!-- Right Side Actions -->
            <div class="flex items-center gap-4">
                
                <!-- Provider Login (White Text) -->
                <button onclick="openProviderAuthModal()" class="hidden md:inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-white border border-emerald-400 hover:bg-emerald-500 font-bold text-sm transition-all duration-300 hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>دخول المهنيين</span>
                </button>
                
                <!-- Request Service CTA (White BG, Green Text) -->
                <a href="<?= $linkPrefix ?>#request-service" class="hidden md:inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white text-emerald-600 font-bold text-sm shadow-lg hover:bg-gray-50 transition-all duration-300 hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>اطلب خدمة</span>
                </a>
                
                <!-- Mobile Menu Toggle (White) -->
                <button type="button" id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg text-white hover:bg-white/10 transition-colors">
                    <div class="w-6 h-5 flex flex-col justify-between relative">
                        <span class="w-full h-0.5 bg-current rounded-full transition-all duration-300 origin-right"></span>
                        <span class="w-full h-0.5 bg-current rounded-full transition-all duration-300"></span>
                        <span class="w-full h-0.5 bg-current rounded-full transition-all duration-300 origin-right"></span>
                    </div>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu" class="lg:hidden fixed inset-x-0 top-[80px] bottom-0 bg-emerald-600 z-40 transform translate-x-full transition-transform duration-300 flex flex-col overflow-y-auto border-t border-emerald-500">
        <div class="p-6 space-y-6">
            <nav class="flex flex-col gap-2">
                <a href="/" class="mobile-link-white <?= $isHome ? 'active' : '' ?>">
                    <span class="text-2xl">🏠</span>
                    <span class="text-lg font-bold">الرئيسية</span>
                </a>
                <a href="<?= $linkPrefix ?>#services" class="mobile-link-white">
                    <span class="text-2xl">🛠️</span>
                    <span class="text-lg font-bold">الخدمات</span>
                </a>
                <a href="<?= $linkPrefix ?>#about" class="mobile-link-white">
                    <span class="text-2xl">ℹ️</span>
                    <span class="text-lg font-bold">عن خدمة</span>
                </a>
                <a href="<?= $linkPrefix ?>#faq" class="mobile-link-white">
                    <span class="text-2xl">❓</span>
                    <span class="text-lg font-bold">الأسئلة الشائعة</span>
                </a>
            </nav>
            
            <div class="pt-6 border-t border-emerald-500 flex flex-col gap-3">
                <a href="<?= $linkPrefix ?>#request-service" class="flex items-center justify-center gap-2 w-full py-4 bg-white text-emerald-600 rounded-xl font-bold text-lg shadow-lg">
                    <span>اطلب خدمة الآن</span>
                </a>
                <button onclick="openProviderAuthModal()" class="flex items-center justify-center gap-2 w-full py-4 bg-emerald-700 text-white rounded-xl font-bold text-lg border border-emerald-500">
                    <span>دخول المهنيين</span>
                </button>
            </div>
        </div>
    </div>
</header>

<style>
/* White Navigation Styles */
.nav-link-white {
    @apply text-emerald-100 font-bold text-base hover:text-white transition-colors py-2 relative;
}

.nav-link-white::after {
    content: '';
    @apply absolute bottom-0 right-0 w-0 h-0.5 bg-white transition-all duration-300 opacity-80;
}

.nav-link-white:hover::after,
.nav-link-white.nav-active::after {
    @apply w-full;
}

.nav-link-white.nav-active {
    @apply text-white;
}

.mobile-link-white {
    @apply flex items-center gap-4 p-4 rounded-xl text-emerald-100 hover:bg-emerald-500 hover:text-white transition-all;
}

.mobile-link-white.active {
    @apply bg-emerald-700 text-white border-r-4 border-emerald-300;
}

/* Header Scroll State - Keep it Green but add shadow */
.header-scrolled {
    @apply shadow-xl bg-emerald-700 border-emerald-600;
}

/* Mobile Menu Animation Classes */
.menu-open {
    @apply overflow-hidden;
}

#mobile-menu.is-open {
    @apply translate-x-0;
}

/* Hamburger Animation */
#mobile-menu-btn.active span:nth-child(1) {
    @apply -rotate-45 -translate-y-[5px];
}
#mobile-menu-btn.active span:nth-child(2) {
    @apply opacity-0;
}
#mobile-menu-btn.active span:nth-child(3) {
    @apply rotate-45 translate-y-[5px];
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    const header = document.querySelector('header');
    let isOpen = false;

    function toggleMenu() {
        isOpen = !isOpen;
        menuBtn.classList.toggle('active');
        menu.classList.toggle('is-open');
        document.body.classList.toggle('menu-open');
    }

    if (menuBtn) {
        menuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleMenu();
        });
    }

    // Close on link click
    document.querySelectorAll('.mobile-link-white, #mobile-menu button, #mobile-menu a').forEach(link => {
        link.addEventListener('click', () => {
            if (isOpen) toggleMenu();
        });
    });

    // Scroll Effect
    window.addEventListener('scroll', () => {
        if (window.scrollY > 10) {
            header.classList.add('header-scrolled');
        } else {
            header.classList.remove('header-scrolled');
        }
        
        // Active Link Logic (Home only)
        <?php if ($isHome): ?>
        const sections = document.querySelectorAll('section[id]');
        const links = document.querySelectorAll('.nav-link-white');
        
        let current = '';
        const scrollY = window.scrollY + 150;
        
        sections.forEach(section => {
            if (scrollY >= section.offsetTop) {
                current = section.getAttribute('id');
            }
        });
        
        links.forEach(link => {
            link.classList.remove('nav-active');
            const href = link.getAttribute('href');
            if (href === '/' && window.scrollY < 100) {
                if (link.getAttribute('href') === '/') link.classList.add('nav-active');
            } else if (href === `#${current}`) {
                link.classList.add('nav-active');
            }
        });
        <?php endif; ?>
    });
});
</script>
