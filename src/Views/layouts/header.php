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
    <meta name="theme-color" content="#0ea5e9">
</head>
<body class="bg-gray-50">

<header class="fixed top-0 w-full bg-white/90 backdrop-blur-xl border-b border-gray-100/50 z-50 transition-all duration-500 shadow-sm hover:shadow-md">
    <div class="container-custom">
        <div class="flex items-center justify-between h-16 md:h-18">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="/" class="group transition-all duration-300 hover:scale-105">
                    <!-- Logo Only -->
                    <div class="relative flex-shrink-0">
                        <div class="bg-blue-800/80 backdrop-blur-sm rounded-lg p-1 shadow-md border border-blue-700/20">
                            <img src="/assets/images/logo-new.png?v=<?= time() . rand(1000, 9999) ?>" alt="KhidmaApp" 
                                 class="w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 object-contain transition-all duration-300 group-hover:brightness-125 group-hover:scale-105"
                                 onload="console.log('Logo only loaded:', this.src)"
                                 onerror="console.error('Logo failed to load:', this.src)"
                                 style="max-width: none; max-height: none; filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1));">
                        </div>
                        <div class="absolute inset-0 rounded-lg bg-gradient-to-br from-white/5 to-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                </a>
            </div>
            
            <!-- Desktop Navigation - Only show on desktop -->
            <nav class="hidden lg:flex items-center space-x-8 rtl:space-x-reverse">
                <a href="/" class="nav-link-clean nav-active">الرئيسية</a>
                <a href="#services" class="nav-link-clean">الخدمات</a>
                <a href="#about" class="nav-link-clean">عن خدمة</a>
                <a href="#faq" class="nav-link-clean">الأسئلة الشائعة</a>
            </nav>
            
            <!-- CTA Buttons & Mobile Menu -->
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <!-- Provider Login Button (Desktop & Mobile) -->
                <button onclick="openProviderAuthModal()" class="inline-flex items-center px-4 py-2 rounded-full bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 text-sm font-medium transition-all duration-300 hover:scale-105">
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="hidden sm:inline">دخول الأُستاذ</span>
                    <span class="sm:hidden">دخول</span>
                </button>
                
                <!-- WhatsApp Button (Desktop Only) -->
                <a href="<?= htmlspecialchars(WHATSAPP_CHANNEL_URL) ?>" target="_blank" rel="noopener noreferrer" 
                   class="hidden xl:inline-flex items-center px-4 py-2 rounded-full bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 text-sm font-medium transition-all duration-300 hover:scale-105">
                    <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                    </svg>
                    انضم إلينا
                </a>
                
                <!-- Request Service Button (Desktop) -->
                <a href="#request-service" class="hidden md:inline-flex items-center px-5 py-2.5 rounded-full bg-gradient-to-r from-primary-600 to-primary-700 text-white text-sm font-medium transition-all duration-300 hover:from-primary-700 hover:to-primary-800 hover:scale-105 hover:shadow-lg">
                    <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    اطلب خدمة
                </a>
                
                <!-- Mobile Menu Button -->
                <button type="button" id="mobile-menu-btn" class="lg:hidden relative p-3 rounded-full hover:bg-gray-100 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary-500/30 group z-50">
                    <span class="sr-only">فتح القائمة</span>
                    <div class="w-6 h-6 flex flex-col justify-center items-center relative">
                        <span id="menu-line-1" class="block w-6 h-0.5 bg-gray-700 transition-all duration-300 group-hover:bg-primary-600 absolute"></span>
                        <span id="menu-line-2" class="block w-4 h-0.5 bg-gray-700 transition-all duration-300 group-hover:bg-primary-600 absolute"></span>
                        <span id="menu-line-3" class="block w-6 h-0.5 bg-gray-700 transition-all duration-300 group-hover:bg-primary-600 absolute"></span>
                    </div>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="lg:hidden fixed inset-x-0 top-16 bg-white backdrop-blur-xl border-b border-gray-200 shadow-2xl transform -translate-y-full opacity-0 transition-all duration-500 ease-out z-40">
        <div class="container-custom py-8">
            <!-- Mobile Navigation -->
            <nav class="flex flex-col space-y-4">
                <a href="/" class="mobile-nav-clean mobile-nav-active">الرئيسية</a>
                <a href="#services" class="mobile-nav-clean">الخدمات</a>
                <a href="#about" class="mobile-nav-clean">عن خدمة</a>
                <a href="#faq" class="mobile-nav-clean">الأسئلة الشائعة</a>
            </nav>
            
            <!-- Mobile CTA Section -->
            <div class="mt-6 pt-6 border-t border-gray-100 space-y-3">
                <!-- WhatsApp Channel Button (Mobile Priority) -->
                <a href="<?= htmlspecialchars(WHATSAPP_CHANNEL_URL) ?>" target="_blank" rel="noopener noreferrer" 
                   class="w-full inline-flex items-center justify-center px-6 py-3.5 rounded-2xl bg-green-600 text-white font-semibold text-base hover:bg-green-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                    </svg>
                    انضم كمقدم خدمة
                </a>
                
                <!-- Request Service Button -->
                <a href="#request-service" class="w-full inline-flex items-center justify-center px-6 py-3.5 rounded-2xl bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold text-base hover:from-primary-700 hover:to-primary-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    اطلب خدمة الآن
                </a>
            </div>
        </div>
    </div>
</header>

<style>
/* Clean Navigation Styles */
.nav-link-clean {
    @apply px-4 py-2 text-gray-700 hover:text-primary-600 font-medium text-base 
           transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary-500/20
           relative;
}

.nav-link-clean:hover {
    @apply text-primary-600;
}

.nav-link-clean.nav-active {
    @apply text-primary-700 font-semibold;
}

.nav-link-clean.nav-active::after {
    content: '';
    position: absolute;
    bottom: -8px;
    right: 50%;
    transform: translateX(50%);
    width: 24px;
    height: 2px;
    background: linear-gradient(90deg, #0ea5e9, #0284c7);
    border-radius: 1px;
}

    .mobile-nav-clean {
        @apply block w-full px-6 py-5 text-gray-900 hover:text-primary-700 
               hover:bg-primary-100 font-semibold text-xl transition-all duration-300
               focus:outline-none focus:ring-2 focus:ring-primary-500/30 rounded-2xl text-center
               border border-gray-200/60 hover:border-primary-300 shadow-md hover:shadow-lg
               bg-gray-50/50;
        display: block !important;
        width: 100% !important;
    }

    .mobile-nav-clean.mobile-nav-active {
        @apply text-white bg-gradient-to-r from-primary-600 to-primary-700 font-bold
               border-primary-500 shadow-lg;
    }

    /* Force vertical mobile menu layout */
    #mobile-menu nav {
        display: flex !important;
        flex-direction: column !important;
    }

    #mobile-menu nav a {
        display: block !important;
        width: 100% !important;
    }

/* Mobile Menu Button Animation */
#mobile-menu-btn #menu-line-1 {
    top: 6px;
}

#mobile-menu-btn #menu-line-2 {
    top: 11px;
}

#mobile-menu-btn #menu-line-3 {
    top: 16px;
}

#mobile-menu-btn.active #menu-line-1 {
    top: 11px;
    transform: rotate(45deg);
}

#mobile-menu-btn.active #menu-line-2 {
    opacity: 0;
}

#mobile-menu-btn.active #menu-line-3 {
    top: 11px;
    transform: rotate(-45deg);
}

/* Header scroll effect */
.header-scrolled {
    @apply bg-white/98 shadow-lg border-b-primary-100/30;
}

/* RTL Support */
[dir="rtl"] .nav-link-clean.nav-active::after {
    right: auto;
    left: 50%;
    transform: translateX(-50%);
}
</style>

<script>
// Modern Mobile Menu & Navigation
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const header = document.querySelector('header');
    let isMenuOpen = false;
    
    // Mobile menu toggle with hamburger animation
    if (mobileMenuBtn && mobileMenu) {
        // Prevent any other click handlers
        mobileMenuBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            console.log('Hamburger menu clicked!'); // Debug
            
            isMenuOpen = !isMenuOpen;
            
            // Toggle hamburger animation
            mobileMenuBtn.classList.toggle('active');
            
            if (isMenuOpen) {
                // Open menu
                mobileMenu.classList.remove('-translate-y-full', 'opacity-0');
                mobileMenu.classList.add('translate-y-0', 'opacity-100');
                document.body.style.overflow = 'hidden'; // Prevent background scroll
            } else {
                // Close menu
                mobileMenu.classList.add('-translate-y-full', 'opacity-0');
                mobileMenu.classList.remove('translate-y-0', 'opacity-100');
                document.body.style.overflow = 'auto';
            }
        }, true); // Use capture phase
        
        // Close menu when clicking on mobile nav links
        const mobileNavLinks = document.querySelectorAll('.mobile-nav-clean');
        mobileNavLinks.forEach(link => {
            link.addEventListener('click', function() {
                closeMobileMenu();
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (isMenuOpen && !mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                closeMobileMenu();
            }
        });
        
        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isMenuOpen) {
                closeMobileMenu();
            }
        });
        
        function closeMobileMenu() {
            isMenuOpen = false;
            mobileMenuBtn.classList.remove('active');
            mobileMenu.classList.add('-translate-y-full', 'opacity-0');
            mobileMenu.classList.remove('translate-y-0', 'opacity-100');
            document.body.style.overflow = 'auto';
        }
    }
    
    // Enhanced header scroll effects
    let lastScrollY = window.scrollY;
    let ticking = false;
    
    function updateHeader() {
        const currentScrollY = window.scrollY;
        
        // Add/remove scrolled class for styling
        if (currentScrollY > 20) {
            header.classList.add('header-scrolled');
        } else {
            header.classList.remove('header-scrolled');
        }
        
        // Hide/show header on scroll (only on mobile/tablet for better UX)
        if (window.innerWidth < 1024) {
            if (currentScrollY > lastScrollY && currentScrollY > 100 && !isMenuOpen) {
                // Scrolling down & past threshold & menu not open
                header.style.transform = 'translateY(-100%)';
            } else {
                // Scrolling up or at top or menu is open
                header.style.transform = 'translateY(0)';
            }
        } else {
            header.style.transform = 'translateY(0)'; // Always show on desktop
        }
        
        lastScrollY = currentScrollY;
        ticking = false;
    }
    
    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(updateHeader);
            ticking = true;
        }
    });
    
    // Active nav link highlighting
    function updateActiveNavLink() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link-clean, .mobile-nav-clean');
        
        let currentSection = '';
        const scrollPosition = window.scrollY + 100;
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            
            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                currentSection = section.getAttribute('id');
            }
        });
        
        // Update active states
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            link.classList.remove('nav-active', 'mobile-nav-active');
            
            if (href === '/' && window.scrollY < 100) {
                link.classList.add(link.classList.contains('nav-link-clean') ? 'nav-active' : 'mobile-nav-active');
            } else if (href === `#${currentSection}`) {
                link.classList.add(link.classList.contains('nav-link-clean') ? 'nav-active' : 'mobile-nav-active');
            }
        });
    }
    
    // Update active nav on scroll
    window.addEventListener('scroll', updateActiveNavLink);
    updateActiveNavLink(); // Initial call
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            
            if (target) {
                const headerHeight = header.offsetHeight;
                const targetPosition = target.offsetTop - headerHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
                
                // Close mobile menu if open
                if (isMenuOpen) {
                    closeMobileMenu();
                }
            }
        });
    });
});
</script>

        <div class="flex items-center justify-between h-16 md:h-18">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="/" class="group transition-all duration-300 hover:scale-105">
                    <!-- Logo Only -->
                    <div class="relative flex-shrink-0">
                        <div class="bg-blue-800/80 backdrop-blur-sm rounded-lg p-1 shadow-md border border-blue-700/20">
                            <img src="/assets/images/logo-new.png?v=<?= time() . rand(1000, 9999) ?>" alt="KhidmaApp" 
                                 class="w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 object-contain transition-all duration-300 group-hover:brightness-125 group-hover:scale-105"
                                 onload="console.log('Logo only loaded:', this.src)"
                                 onerror="console.error('Logo failed to load:', this.src)"
                                 style="max-width: none; max-height: none; filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1));">
                        </div>
                        <div class="absolute inset-0 rounded-lg bg-gradient-to-br from-white/5 to-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                </a>
            </div>
            
            <!-- Desktop Navigation - Only show on desktop -->
            <nav class="hidden lg:flex items-center space-x-8 rtl:space-x-reverse">
                <a href="/" class="nav-link-clean nav-active">الرئيسية</a>
                <a href="#services" class="nav-link-clean">الخدمات</a>
                <a href="#about" class="nav-link-clean">عن خدمة</a>
                <a href="#faq" class="nav-link-clean">الأسئلة الشائعة</a>
            </nav>
            
            <!-- CTA Buttons & Mobile Menu -->
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <!-- Provider Login Button (Desktop & Mobile) -->
                <button onclick="openProviderAuthModal()" class="inline-flex items-center px-4 py-2 rounded-full bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 text-sm font-medium transition-all duration-300 hover:scale-105">
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="hidden sm:inline">دخول الأُستاذ</span>
                    <span class="sm:hidden">دخول</span>
                </button>
                
                <!-- WhatsApp Button (Desktop Only) -->
                <a href="<?= htmlspecialchars(WHATSAPP_CHANNEL_URL) ?>" target="_blank" rel="noopener noreferrer" 
                   class="hidden xl:inline-flex items-center px-4 py-2 rounded-full bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 text-sm font-medium transition-all duration-300 hover:scale-105">
                    <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                    </svg>
                    انضم إلينا
                </a>
                
                <!-- Request Service Button (Desktop) -->
                <a href="#request-service" class="hidden md:inline-flex items-center px-5 py-2.5 rounded-full bg-gradient-to-r from-primary-600 to-primary-700 text-white text-sm font-medium transition-all duration-300 hover:from-primary-700 hover:to-primary-800 hover:scale-105 hover:shadow-lg">
                    <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    اطلب خدمة
                </a>
                
                <!-- Mobile Menu Button -->
                <button type="button" id="mobile-menu-btn" class="lg:hidden relative p-3 rounded-full hover:bg-gray-100 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary-500/30 group z-50">
                    <span class="sr-only">فتح القائمة</span>
                    <div class="w-6 h-6 flex flex-col justify-center items-center relative">
                        <span id="menu-line-1" class="block w-6 h-0.5 bg-gray-700 transition-all duration-300 group-hover:bg-primary-600 absolute"></span>
                        <span id="menu-line-2" class="block w-4 h-0.5 bg-gray-700 transition-all duration-300 group-hover:bg-primary-600 absolute"></span>
                        <span id="menu-line-3" class="block w-6 h-0.5 bg-gray-700 transition-all duration-300 group-hover:bg-primary-600 absolute"></span>
                    </div>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="lg:hidden fixed inset-x-0 top-16 bg-white backdrop-blur-xl border-b border-gray-200 shadow-2xl transform -translate-y-full opacity-0 transition-all duration-500 ease-out z-40">
        <div class="container-custom py-8">
            <!-- Mobile Navigation -->
            <nav class="flex flex-col space-y-4">
                <a href="/" class="mobile-nav-clean mobile-nav-active">الرئيسية</a>
                <a href="#services" class="mobile-nav-clean">الخدمات</a>
                <a href="#about" class="mobile-nav-clean">عن خدمة</a>
                <a href="#faq" class="mobile-nav-clean">الأسئلة الشائعة</a>
            </nav>
            
            <!-- Mobile CTA Section -->
            <div class="mt-6 pt-6 border-t border-gray-100 space-y-3">
                <!-- WhatsApp Channel Button (Mobile Priority) -->
                <a href="<?= htmlspecialchars(WHATSAPP_CHANNEL_URL) ?>" target="_blank" rel="noopener noreferrer" 
                   class="w-full inline-flex items-center justify-center px-6 py-3.5 rounded-2xl bg-green-600 text-white font-semibold text-base hover:bg-green-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                    </svg>
                    انضم كمقدم خدمة
                </a>
                
                <!-- Request Service Button -->
                <a href="#request-service" class="w-full inline-flex items-center justify-center px-6 py-3.5 rounded-2xl bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold text-base hover:from-primary-700 hover:to-primary-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    اطلب خدمة الآن
                </a>
            </div>
        </div>
    </div>
</header>

<style>
/* Clean Navigation Styles */
.nav-link-clean {
    @apply px-4 py-2 text-gray-700 hover:text-primary-600 font-medium text-base 
           transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary-500/20
           relative;
}

.nav-link-clean:hover {
    @apply text-primary-600;
}

.nav-link-clean.nav-active {
    @apply text-primary-700 font-semibold;
}

.nav-link-clean.nav-active::after {
    content: '';
    position: absolute;
    bottom: -8px;
    right: 50%;
    transform: translateX(50%);
    width: 24px;
    height: 2px;
    background: linear-gradient(90deg, #0ea5e9, #0284c7);
    border-radius: 1px;
}

    .mobile-nav-clean {
        @apply block w-full px-6 py-5 text-gray-900 hover:text-primary-700 
               hover:bg-primary-100 font-semibold text-xl transition-all duration-300
               focus:outline-none focus:ring-2 focus:ring-primary-500/30 rounded-2xl text-center
               border border-gray-200/60 hover:border-primary-300 shadow-md hover:shadow-lg
               bg-gray-50/50;
        display: block !important;
        width: 100% !important;
    }

    .mobile-nav-clean.mobile-nav-active {
        @apply text-white bg-gradient-to-r from-primary-600 to-primary-700 font-bold
               border-primary-500 shadow-lg;
    }

    /* Force vertical mobile menu layout */
    #mobile-menu nav {
        display: flex !important;
        flex-direction: column !important;
    }

    #mobile-menu nav a {
        display: block !important;
        width: 100% !important;
    }

/* Mobile Menu Button Animation */
#mobile-menu-btn #menu-line-1 {
    top: 6px;
}

#mobile-menu-btn #menu-line-2 {
    top: 11px;
}

#mobile-menu-btn #menu-line-3 {
    top: 16px;
}

#mobile-menu-btn.active #menu-line-1 {
    top: 11px;
    transform: rotate(45deg);
}

#mobile-menu-btn.active #menu-line-2 {
    opacity: 0;
}

#mobile-menu-btn.active #menu-line-3 {
    top: 11px;
    transform: rotate(-45deg);
}

/* Header scroll effect */
.header-scrolled {
    @apply bg-white/98 shadow-lg border-b-primary-100/30;
}

/* RTL Support */
[dir="rtl"] .nav-link-clean.nav-active::after {
    right: auto;
    left: 50%;
    transform: translateX(-50%);
}
</style>

<script>
// Modern Mobile Menu & Navigation
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const header = document.querySelector('header');
    let isMenuOpen = false;
    
    // Mobile menu toggle with hamburger animation
    if (mobileMenuBtn && mobileMenu) {
        // Prevent any other click handlers
        mobileMenuBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            console.log('Hamburger menu clicked!'); // Debug
            
            isMenuOpen = !isMenuOpen;
            
            // Toggle hamburger animation
            mobileMenuBtn.classList.toggle('active');
            
            if (isMenuOpen) {
                // Open menu
                mobileMenu.classList.remove('-translate-y-full', 'opacity-0');
                mobileMenu.classList.add('translate-y-0', 'opacity-100');
                document.body.style.overflow = 'hidden'; // Prevent background scroll
            } else {
                // Close menu
                mobileMenu.classList.add('-translate-y-full', 'opacity-0');
                mobileMenu.classList.remove('translate-y-0', 'opacity-100');
                document.body.style.overflow = 'auto';
            }
        }, true); // Use capture phase
        
        // Close menu when clicking on mobile nav links
        const mobileNavLinks = document.querySelectorAll('.mobile-nav-clean');
        mobileNavLinks.forEach(link => {
            link.addEventListener('click', function() {
                closeMobileMenu();
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (isMenuOpen && !mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                closeMobileMenu();
            }
        });
        
        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isMenuOpen) {
                closeMobileMenu();
            }
        });
        
        function closeMobileMenu() {
            isMenuOpen = false;
            mobileMenuBtn.classList.remove('active');
            mobileMenu.classList.add('-translate-y-full', 'opacity-0');
            mobileMenu.classList.remove('translate-y-0', 'opacity-100');
            document.body.style.overflow = 'auto';
        }
    }
    
    // Enhanced header scroll effects
    let lastScrollY = window.scrollY;
    let ticking = false;
    
    function updateHeader() {
        const currentScrollY = window.scrollY;
        
        // Add/remove scrolled class for styling
        if (currentScrollY > 20) {
            header.classList.add('header-scrolled');
        } else {
            header.classList.remove('header-scrolled');
        }
        
        // Hide/show header on scroll (only on mobile/tablet for better UX)
        if (window.innerWidth < 1024) {
            if (currentScrollY > lastScrollY && currentScrollY > 100 && !isMenuOpen) {
                // Scrolling down & past threshold & menu not open
                header.style.transform = 'translateY(-100%)';
            } else {
                // Scrolling up or at top or menu is open
                header.style.transform = 'translateY(0)';
            }
        } else {
            header.style.transform = 'translateY(0)'; // Always show on desktop
        }
        
        lastScrollY = currentScrollY;
        ticking = false;
    }
    
    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(updateHeader);
            ticking = true;
        }
    });
    
    // Active nav link highlighting
    function updateActiveNavLink() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link-clean, .mobile-nav-clean');
        
        let currentSection = '';
        const scrollPosition = window.scrollY + 100;
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            
            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                currentSection = section.getAttribute('id');
            }
        });
        
        // Update active states
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            link.classList.remove('nav-active', 'mobile-nav-active');
            
            if (href === '/' && window.scrollY < 100) {
                link.classList.add(link.classList.contains('nav-link-clean') ? 'nav-active' : 'mobile-nav-active');
            } else if (href === `#${currentSection}`) {
                link.classList.add(link.classList.contains('nav-link-clean') ? 'nav-active' : 'mobile-nav-active');
            }
        });
    }
    
    // Update active nav on scroll
    window.addEventListener('scroll', updateActiveNavLink);
    updateActiveNavLink(); // Initial call
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            
            if (target) {
                const headerHeight = header.offsetHeight;
                const targetPosition = target.offsetTop - headerHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
                
                // Close mobile menu if open
                if (isMenuOpen) {
                    closeMobileMenu();
                }
            }
        });
    });
});
</script>


