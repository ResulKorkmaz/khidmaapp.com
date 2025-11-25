<!DOCTYPE html>
<html dir="rtl" lang="ar-SA">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- SEO Tags -->
    <title><?= $pageTitle ?? SITE_TITLE_AR ?></title>
    <meta name="description" content="<?= $pageDescription ?? 'منصة خدماتية متخصصة في ربط العملاء بمقدمي الخدمات المنزلية والتجارية في السعودية' ?>">
    <meta name="keywords" content="<?= $pageKeywords ?? 'خدمات, دهانات, ترميم, تنظيف, صيانة, كهرباء, سباكة, مكيفات, السعودية' ?>">
    <meta name="author" content="KhidmaApp.com">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="<?= $pageTitle ?? SITE_TITLE_AR ?>">
    <meta property="og:description" content="<?= $pageDescription ?? 'منصة خدماتية متخصصة في ربط العملاء بمقدمي الخدمات المنزلية والتجارية في السعودية' ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= SITE_URL ?>">
    <meta property="og:image" content="<?= SITE_URL ?>/assets/images/logo.png">
    <meta property="og:locale" content="ar_SA">
    
    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $pageTitle ?? SITE_TITLE_AR ?>">
    <meta name="twitter:description" content="<?= $pageDescription ?? 'منصة خدماتية متخصصة في ربط العملاء بمقدمي الخدمات المنزلية والتجارية في السعودية' ?>">
    <meta name="twitter:image" content="<?= SITE_URL ?>/assets/images/logo.png">
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
    
    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- CSS Files -->
    <link href="/assets/css/app.css" rel="stylesheet">
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "KhidmaApp",
        "description": "منصة خدماتية متخصصة في ربط العملاء بمقدمي الخدمات المنزلية والتجارية",
        "url": "<?= SITE_URL ?>",
        "telephone": "<?= CONTACT_PHONE ?>",
        "email": "<?= CONTACT_EMAIL ?>",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "SA",
            "addressLocality": "الرياض"
        },
        "areaServed": [
            "الرياض", "جدة", "مكة المكرمة", "المدينة المنورة", "الدمام", "الخبر"
        ],
        "serviceType": [
            "دهانات", "ترميم", "تنظيف", "صيانة", "كهرباء", "سباكة", "مكيفات"
        ]
    }
    </script>
    
    <!-- Additional Meta Tags -->
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#0ea5e9">
    <meta name="msapplication-TileColor" content="#0ea5e9">
    
    <?php if (isset($additionalHead)): ?>
        <?= $additionalHead ?>
    <?php endif; ?>
</head>
<body class="<?= $bodyClass ?? 'bg-gradient-soft min-h-screen' ?>">
    <!-- Loading Screen -->
    <div id="loading-screen" class="fixed inset-0 bg-white z-50 flex items-center justify-center transition-opacity duration-500" style="display: none;">
        <div class="text-center">
            <div class="spinner w-8 h-8 border-primary-600 mx-auto mb-4"></div>
            <p class="text-gray-600 font-medium">جاري التحميل...</p>
        </div>
    </div>
    
    <!-- Skip to Content Link -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-primary-600 text-white px-4 py-2 rounded-lg z-50">
        الانتقال إلى المحتوى الرئيسي
    </a>
    
    <!-- Header -->
    <?php include_once __DIR__ . '/header.php'; ?>
    
    <!-- Main Content -->
    <main id="main-content" class="<?= $mainClass ?? '' ?>">
        <?= $content ?? '' ?>
    </main>
    
    <!-- Footer -->
    <?php include_once __DIR__ . '/footer.php'; ?>
    
    <!-- Back to Top Button -->
    <button id="backToTop" class="fixed bottom-6 left-6 bg-primary-600 text-white p-3 rounded-full shadow-float opacity-0 invisible transition-all duration-300 hover:bg-primary-700 focus:outline-none focus:ring-4 focus:ring-primary-200 z-40">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
        </svg>
        <span class="sr-only">العودة إلى الأعلى</span>
    </button>
    
    <!-- JavaScript -->
    <script>
        // Loading screen management
        window.addEventListener('load', function() {
            const loadingScreen = document.getElementById('loading-screen');
            setTimeout(() => {
                loadingScreen.style.opacity = '0';
                setTimeout(() => {
                    loadingScreen.style.display = 'none';
                }, 500);
            }, 800);
        });
        
        // Back to top button
        const backToTopButton = document.getElementById('backToTop');
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('opacity-0', 'invisible');
                backToTopButton.classList.add('opacity-100', 'visible');
            } else {
                backToTopButton.classList.add('opacity-0', 'invisible');
                backToTopButton.classList.remove('opacity-100', 'visible');
            }
        });
        
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        // Form validation helpers
        function validatePhone(phone) {
            const phoneRegex = /^(\+966|966|0)?[5][0-9]{8}$/;
            return phoneRegex.test(phone.replace(/\s+/g, ''));
        }
        
        function showAlert(message, type = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full`;
            
            const colors = {
                success: 'bg-success-500 text-white',
                error: 'bg-danger-500 text-white',
                warning: 'bg-warning-500 text-white',
                info: 'bg-primary-500 text-white'
            };
            
            alertDiv.className += ` ${colors[type] || colors.info}`;
            alertDiv.innerHTML = `
                <div class="flex items-center justify-between">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="mr-4 text-white hover:text-gray-200">
                        ×
                    </button>
                </div>
            `;
            
            document.body.appendChild(alertDiv);
            
            // Animate in
            setTimeout(() => {
                alertDiv.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                alertDiv.classList.add('translate-x-full');
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 300);
            }, 5000);
        }
        
        // CSRF Token helper
        function getCsrfToken() {
            return '<?= generateCsrfToken() ?>';
        }
    </script>
    
    <?php if (isset($additionalScript)): ?>
        <?= $additionalScript ?>
    <?php endif; ?>
</body>
</html>

