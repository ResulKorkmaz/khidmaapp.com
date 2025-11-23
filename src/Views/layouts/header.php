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
    <meta name="theme-color" content="#10b981">
</head>
<body class="bg-gray-50">

<?php
// Check if we are on the home page
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$isHome = ($currentPath === '/' || $currentPath === '/index.php');
$linkPrefix = $isHome ? '' : '/';
?>

<header class="fixed top-0 w-full bg-white/90 backdrop-blur-xl border-b border-emerald-100/50 z-50 transition-all duration-500 shadow-sm hover:shadow-md">
    <div class="container-custom">
        <div class="flex items-center justify-between h-20"> <!-- Yüksekliği biraz artırdım (h-16 -> h-20) daha ferah görünüm için -->
            <!-- Logo -->
            <div class="flex items-center">
                <a href="/" class="group transition-all duration-300 hover:scale-105">
                    <!-- Logo Box - Updated to Green -->
                    <div class="relative flex-shrink-0">
                        <div class="bg-emerald-600 rounded-xl p-1.5 shadow-lg shadow-emerald-200 border border-emerald-500/20">
                            <img src="/assets/images/logo-new.png?v=<?= time() . rand(1000, 9999) ?>" alt="KhidmaApp" 
                                 class="w-10 h-10 sm:w-12 sm:h-12 object-contain transition-all duration-300 group-hover:brightness-110 filter brightness-0 invert" 
                                 onload="console.log('Logo only loaded:', this.src)"
                                 onerror="console.error('Logo failed to load:', this.src)"
                                 style="max-width: none; max-height: none;">
                        </div>
                        <!-- Glow Effect -->
                        <div class="absolute -inset-1 bg-emerald-500/30 rounded-xl blur opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                </a>
            </div>
            
            <!-- Desktop Navigation - Only show on desktop -->
            <nav class="hidden lg:flex items-center space-x-1 rtl:space-x-reverse bg-gray-50/50 p-1.5 rounded-full border border-gray-100">
                <a href="/" class="nav-link-modern <?= $isHome ? 'nav-active' : '' ?>">الرئيسية</a>
                <a href="<?= $linkPrefix ?>#services" class="nav-link-modern">الخدمات</a>
                <a href="<?= $linkPrefix ?>#about" class="nav-link-modern">عن خدمة</a>
                <a href="<?= $linkPrefix ?>#faq" class="nav-link-modern">الأسئلة الشائعة</a>
            </nav>
            
            <!-- CTA Buttons & Mobile Menu -->
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <!-- Provider Login Button (Desktop & Mobile) -->
                <button onclick="openProviderAuthModal()" class="inline-flex items-center px-5 py-2.5 rounded-full bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 text-sm font-bold transition-all duration-300 hover:scale-105 hover:shadow-md group">
                    <svg class="w-4 h-4 ml-2 transition-transform duration-300 group-hover:-translate-y-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="hidden sm:inline">دخول المهنيين</span>
                    <span class="sm:hidden">دخول</span>
                </button>
                
                <!-- Request Service Button (Desktop) -->
                <a href="<?= $linkPrefix ?>#request-service" class="hidden md:inline-flex items-center px-6 py-2.5 rounded-full bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-sm font-bold transition-all duration-300 hover:from-emerald-600 hover:to-emerald-700 hover:scale-105 hover:shadow-lg hover:shadow-emerald-500/30 group">
                    <svg class="w-4 h-4 ml-2 transition-transform duration-300 group-hover:rotate-12" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    اطلب خدمة
                </a>
                
                <!-- Mobile Menu Button -->
                <button type="button" id="mobile-menu-btn" class="lg:hidden relative p-2 rounded-xl hover:bg-emerald-50 text-emerald-800 transition-all duration-300 focus:outline-none group z-50">
                    <span class="sr-only">فتح القائمة</span>
                    <div class="w-6 h-5 flex flex-col justify-between items-center relative">
                        <span id="menu-line-1" class="block w-full h-0.5 bg-emerald-800 rounded-full transition-all duration-300 origin-center"></span>
                        <span id="menu-line-2" class="block w-3/4 h-0.5 bg-emerald-800 rounded-full transition-all duration-300 origin-left self-end"></span>
                        <span id="menu-line-3" class="block w-full h-0.5 bg-emerald-800 rounded-full transition-all duration-300 origin-center"></span>
                    </div>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="lg:hidden fixed inset-x-0 top-[80px] bg-white/95 backdrop-blur-xl border-t border-emerald-100 shadow-2xl transform -translate-y-[150%] opacity-0 transition-all duration-500 ease-out z-40">
        <div class="container-custom py-6">
            <!-- Mobile Navigation -->
            <nav class="grid gap-2">
                <a href="/" class="mobile-nav-item <?= $isHome ? 'active' : '' ?>">
                    <span class="text-xl">🏠</span>
                    الرئيسية
                </a>
                <a href="<?= $linkPrefix ?>#services" class="mobile-nav-item">
                    <span class="text-xl">🛠️</span>
                    الخدمات
                </a>
                <a href="<?= $linkPrefix ?>#about" class="mobile-nav-item">
                    <span class="text-xl">ℹ️</span>
                    عن خدمة
                </a>
                <a href="<?= $linkPrefix ?>#faq" class="mobile-nav-item">
                    <span class="text-xl">❓</span>
                    الأسئلة الشائعة
                </a>
            </nav>
            
            <!-- Mobile CTA Section -->
            <div class="mt-6 pt-6 border-t border-emerald-100 space-y-3">
                <!-- Request Service Button -->
                <a href="<?= $linkPrefix ?>#request-service" class="w-full inline-flex items-center justify-center px-6 py-4 rounded-xl bg-emerald-600 text-white font-bold text-lg hover:bg-emerald-700 transition-all duration-300 shadow-lg shadow-emerald-200">
                    اطلب خدمة الآن
                </a>
                
                <!-- WhatsApp -->
                <a href="<?= htmlspecialchars(WHATSAPP_CHANNEL_URL) ?>" target="_blank" rel="noopener noreferrer" 
                   class="w-full inline-flex items-center justify-center px-6 py-4 rounded-xl bg-white border-2 border-green-500 text-green-600 font-bold text-lg hover:bg-green-50 transition-all duration-300">
                    <svg class="w-5 h-5 ml-2" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                    </svg>
                    واتساب
                </a>
            </div>
        </div>
    </div>
</header>

<style>
/* Modern Navigation Styles */
.nav-link-modern {
    @apply px-5 py-2.5 text-gray-600 font-bold text-sm rounded-full transition-all duration-300 relative overflow-hidden;
}

.nav-link-modern:hover {
    @apply text-emerald-600 bg-white shadow-sm;
}

.nav-link-modern.nav-active {
    @apply text-emerald-700 bg-white shadow-md text-emerald-600;
}

/* Mobile Nav Item */
.mobile-nav-item {
    @apply flex items-center gap-4 px-4 py-3 rounded-xl text-gray-700 font-bold text-lg transition-all duration-200 hover:bg-emerald-50 hover:text-emerald-700;
}

.mobile-nav-item.active {
    @apply bg-emerald-50 text-emerald-700 border-r-4 border-emerald-500;
}

/* Mobile Menu Animation */
#mobile-menu-btn.active #menu-line-1 {
    transform: translateY(7px) rotate(45deg);
}

#mobile-menu-btn.active #menu-line-2 {
    opacity: 0;
    transform: translateX(-20px);
}

#mobile-menu-btn.active #menu-line-3 {
    transform: translateY(-7px) rotate(-45deg);
}

/* Header scroll effect */
.header-scrolled {
    @apply bg-white/95 shadow-lg border-emerald-100;
}

/* RTL Support for Mobile Menu Animation */
[dir="rtl"] #mobile-menu-btn.active #menu-line-2 {
    transform: translateX(20px);
}
</style>

<script>
// Modern Mobile Menu & Navigation
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const header = document.querySelector('header');
    let isMenuOpen = false;
    
    // Mobile menu toggle
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            isMenuOpen = !isMenuOpen;
            
            mobileMenuBtn.classList.toggle('active');
            
            if (isMenuOpen) {
                mobileMenu.classList.remove('-translate-y-[150%]', 'opacity-0');
                mobileMenu.classList.add('translate-y-0', 'opacity-100');
                document.body.style.overflow = 'hidden';
            } else {
                mobileMenu.classList.add('-translate-y-[150%]', 'opacity-0');
                mobileMenu.classList.remove('translate-y-0', 'opacity-100');
                document.body.style.overflow = 'auto';
            }
        });
        
        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (isMenuOpen && !mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                closeMobileMenu();
            }
        });
        
        function closeMobileMenu() {
            isMenuOpen = false;
            mobileMenuBtn.classList.remove('active');
            mobileMenu.classList.add('-translate-y-[150%]', 'opacity-0');
            mobileMenu.classList.remove('translate-y-0', 'opacity-100');
            document.body.style.overflow = 'auto';
        }
        
        // Close on link click
        document.querySelectorAll('.mobile-nav-item').forEach(link => {
            link.addEventListener('click', closeMobileMenu);
        });
    }
    
    // Header Scroll Effect
    let lastScrollY = window.scrollY;
    
    window.addEventListener('scroll', () => {
        const currentScrollY = window.scrollY;
        
        if (currentScrollY > 10) {
            header.classList.add('header-scrolled');
        } else {
            header.classList.remove('header-scrolled');
        }
        
        lastScrollY = currentScrollY;
    });
    
    // Active Link Highlighting
    <?php if ($isHome): ?>
    function updateActiveNavLink() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link-modern');
        
        let currentSection = '';
        const scrollPosition = window.scrollY + 150;
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            
            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                currentSection = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            link.classList.remove('nav-active');
            
            if (href === '/' && window.scrollY < 100) {
                if (link.getAttribute('href') === '/') link.classList.add('nav-active');
            } else if (href === `#${currentSection}`) {
                link.classList.add('nav-active');
            }
        });
    }
    
    window.addEventListener('scroll', updateActiveNavLink);
    updateActiveNavLink();
    <?php endif; ?>

    // Smooth Scroll
    document.querySelectorAll('a[href*="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            // Check if it's an anchor link on current page
            if (href.startsWith('#') || (href.startsWith('/') && window.location.pathname === '/')) {
                const targetId = href.includes('#') ? href.split('#')[1] : '';
                const target = document.getElementById(targetId);
                
                if (target) {
                    e.preventDefault();
                    const headerHeight = header.offsetHeight;
                    const targetPosition = target.offsetTop - headerHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
});
</script>
