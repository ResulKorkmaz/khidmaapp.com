<?php
$pageTitle = "خدمة | منصة طلب الخدمات المنزلية والتجارية";
$pageDescription = "احصل على أفضل الخدمات المنزلية والتجارية من مقدمين موثوقين في السعودية. دهانات، ترميم، تنظيف، صيانة، كهرباء، سباكة ومكيفات.";
$bodyClass = "bg-gradient-soft min-h-screen";

ob_start();

// Form helper fonksiyonu
if (!function_exists('render_service_request_form')) {
    require_once __DIR__ . '/helpers/form_helper.php';
}
?>

<!-- Notification Messages -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="fixed top-20 right-4 left-4 md:left-auto md:w-96 z-50 animate-slide-in-top">
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-xl">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div class="mr-3 flex-1">
                    <p class="text-sm font-medium text-green-800"><?= htmlspecialchars($_SESSION['success']) ?></p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-green-500 hover:text-green-700">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="fixed top-20 right-4 left-4 md:left-auto md:w-96 z-50 animate-slide-in-top">
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-xl">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div class="mr-3 flex-1">
                    <p class="text-sm font-medium text-red-800"><?= nl2br(htmlspecialchars($_SESSION['error'])) ?></p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<script>
// Auto-hide notifications after 5 seconds
setTimeout(() => {
    document.querySelectorAll('.animate-slide-in-top').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(-20px)';
        el.style.transition = 'all 0.3s ease';
        setTimeout(() => el.remove(), 300);
    });
}, 5000);
</script>

<!-- Hero Section -->
<section class="hero-dark-section relative overflow-hidden pt-16 md:pt-18 min-h-screen flex items-center">
    <!-- Modern Dark Gradient Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800"></div>
    
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <!-- Large Gradient Orbs -->
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-primary-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>
        
        <!-- Grid Pattern Overlay -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
    </div>
    
    <div class="container-custom relative z-10 py-20">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <!-- Content -->
            <div class="text-center lg:text-start">
                <!-- Badge -->
                <div class="inline-flex items-center bg-white/10 backdrop-blur-md text-white px-5 py-2.5 rounded-full text-sm font-semibold mb-8 border border-white/20 shadow-lg">
                    <svg class="w-4 h-4 me-2 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    خدمات موثوقة ومضمونة
                </div>
                
                <!-- Main Heading -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-extrabold text-white mb-6 leading-tight">
                    احصل على 
                    <span class="bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-400 bg-clip-text text-transparent">
                        أفضل الخدمات
                    </span>
                    <br>بأسهل طريقة
                </h1>
                
                <!-- Description -->
                <p class="text-lg md:text-xl text-blue-100 mb-10 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                    منصة متخصصة تربط بين العملاء ومقدمي الخدمات المنزلية والتجارية الموثوقين في جميع أنحاء المملكة العربية السعودية
                </p>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-10">
                    <a href="#request-service" class="group inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold text-lg px-8 py-4 rounded-2xl shadow-2xl shadow-blue-500/50 hover:shadow-blue-600/50 transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 me-2 group-hover:translate-y-[-2px] transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                        </svg>
                        اطلب خدمة الآن
                    </a>
                    
                    <a href="<?= htmlspecialchars(WHATSAPP_CHANNEL_URL) ?>" target="_blank" rel="noopener noreferrer" 
                       class="group inline-flex items-center justify-center bg-white/10 backdrop-blur-md hover:bg-white/20 text-white font-semibold text-lg px-8 py-4 rounded-2xl border border-white/20 hover:border-white/30 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 me-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                        </svg>
                        انضم كمقدم خدمة
                    </a>
                </div>
            </div>
            
            <!-- Form Card -->
            <div class="relative">
                <div class="relative mx-auto max-w-md lg:max-w-lg">
                    <!-- Glassmorphism Form Card -->
                    <div class="relative bg-white/10 backdrop-blur-2xl rounded-2xl lg:rounded-3xl p-6 lg:p-7 border border-white/20 shadow-2xl">
                        <!-- Card Glow Effect -->
                        <div class="absolute inset-0 rounded-2xl lg:rounded-3xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 opacity-50 blur-2xl -z-10"></div>
                        
                        <!-- Form Title -->
                        <div class="text-center mb-5 lg:mb-6">
                            <h2 class="text-xl lg:text-2xl font-bold text-white mb-1">
                                اطلب خدمتك الآن
                            </h2>
                            <p class="text-blue-200 text-xs lg:text-sm">
                                نتواصل معك خلال دقائق
                            </p>
                        </div>
                        
                        <?php
                        render_service_request_form('hero-service-form', 'hero', [
                            'compact' => true,
                            'ultra_compact' => true,
                            'button_text' => 'إرسال الطلب',
                            'button_classes' => 'btn-primary w-full text-sm lg:text-base py-2.5 lg:py-3 relative',
                            'form_origin' => 'hero'
                        ]);
                        ?>
                    </div>
                    
                    <!-- Decorative Elements -->
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-blue-500/30 rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-cyan-500/30 rounded-full blur-2xl"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="section-padding relative overflow-hidden">
    <!-- Modern Background with Gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-indigo-50"></div>
    
    <!-- Decorative Elements -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-blue-200/20 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-200/20 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
    
    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(circle, #000 1px, transparent 1px); background-size: 40px 40px;"></div>
    
    <div class="container-custom relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <div class="inline-block mb-4">
                <span class="text-sm font-semibold text-blue-600 bg-blue-50 px-4 py-2 rounded-full">
                    خدماتنا
                </span>
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                خدماتنا المتخصصة
            </h2>
            <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                نقدم مجموعة شاملة من الخدمات المنزلية والتجارية بأعلى معايير الجودة والاحترافية
            </p>
        </div>
        
        <!-- Services Grid -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            <?php 
            $serviceIcons = [
                'paint' => [
                    'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
                    'viewBox' => '0 0 24 24',
                    'image' => 'paint.jpg',
                    'color' => 'from-blue-500 to-blue-600',
                    'bgColor' => 'bg-blue-50',
                    'borderColor' => 'border-blue-200',
                    'desc' => 'دهانات داخلية وخارجية بأحدث التقنيات'
                ],
                'renovation' => [
                    'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                    'viewBox' => '0 0 24 24',
                    'image' => 'renovation.jpg',
                    'color' => 'from-orange-500 to-orange-600',
                    'bgColor' => 'bg-orange-50',
                    'borderColor' => 'border-orange-200',
                    'desc' => 'ترميم وتجديد المنازل والمكاتب'
                ],
                'plumbing' => [
                    'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                    'viewBox' => '0 0 24 24',
                    'image' => 'plumbing.png',
                    'color' => 'from-cyan-500 to-cyan-600',
                    'bgColor' => 'bg-cyan-50',
                    'borderColor' => 'border-cyan-200',
                    'desc' => 'إصلاح وصيانة أنظمة السباكة'
                ],
                'electric' => [
                    'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
                    'viewBox' => '0 0 24 24',
                    'image' => 'electric.jpg',
                    'color' => 'from-yellow-500 to-yellow-600',
                    'bgColor' => 'bg-yellow-50',
                    'borderColor' => 'border-yellow-200',
                    'desc' => 'خدمات كهربائية شاملة وآمنة'
                ],
                'cleaning' => [
                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    'viewBox' => '0 0 24 24',
                    'image' => 'cleaning.webp',
                    'color' => 'from-green-500 to-green-600',
                    'bgColor' => 'bg-green-50',
                    'borderColor' => 'border-green-200',
                    'desc' => 'تنظيف شامل للمنازل والمكاتب'
                ],
                'ac' => [
                    'icon' => 'M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z',
                    'viewBox' => '0 0 24 24',
                    'image' => 'ac.jpg',
                    'color' => 'from-indigo-500 to-indigo-600',
                    'bgColor' => 'bg-indigo-50',
                    'borderColor' => 'border-indigo-200',
                    'desc' => 'صيانة وتركيب أنظمة التكييف'
                ]
            ];
            
            foreach (getServiceTypes() as $key => $service): 
                $serviceData = $serviceIcons[$key] ?? $serviceIcons['paint'];
            ?>
            <div class="group relative">
                <!-- Glassmorphism Card -->
                <div class="relative h-full bg-white/80 backdrop-blur-xl rounded-3xl p-8 border border-gray-200/50 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 overflow-hidden">
                    <!-- Hover Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-br <?= $serviceData['color'] ?> opacity-0 group-hover:opacity-5 transition-opacity duration-500 rounded-3xl"></div>
                    
                    <!-- Service Image -->
                    <?php if (isset($serviceData['image'])): ?>
                    <div class="relative mb-6 h-48 rounded-2xl overflow-hidden bg-gradient-to-br <?= $serviceData['color'] ?>">
                        <img src="/assets/images/<?= htmlspecialchars($serviceData['image']) ?>" 
                             alt="<?= htmlspecialchars($service['ar']) ?>"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        <!-- Icon Overlay -->
                        <div class="absolute top-4 left-4 w-14 h-14 bg-white/90 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <svg class="w-8 h-8 text-gray-800" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="<?= $serviceData['viewBox'] ?? '0 0 24 24' ?>">
                                <path d="<?= $serviceData['icon'] ?>"/>
                            </svg>
                        </div>
                    </div>
                    <?php else: ?>
                    <!-- Icon Container (Fallback if no image) -->
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br <?= $serviceData['color'] ?> rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="<?= $serviceData['viewBox'] ?? '0 0 24 24' ?>">
                                <path d="<?= $serviceData['icon'] ?>"/>
                            </svg>
                        </div>
                        <!-- Decorative Circle -->
                        <div class="absolute -top-2 -right-2 w-8 h-8 <?= $serviceData['bgColor'] ?> rounded-full border-2 <?= $serviceData['borderColor'] ?> opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Content -->
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-blue-600 transition-colors duration-300">
                        <?= htmlspecialchars($service['ar']) ?>
                    </h3>
                    
                    <p class="text-gray-600 mb-6 leading-relaxed text-base">
                        <?= $serviceData['desc'] ?>
                    </p>
                    
                    <!-- CTA Button -->
                    <a href="/services/<?= htmlspecialchars($key) ?>" class="inline-flex items-center justify-center gap-2 text-blue-600 hover:text-blue-700 font-semibold group/btn relative">
                        <span class="relative z-10">طلب الخدمة</span>
                        <svg class="w-5 h-5 relative z-10 group-hover/btn:translate-x-1 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        <!-- Underline Effect -->
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 group-hover/btn:w-full transition-all duration-300"></span>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="section-padding relative overflow-hidden bg-white">
    <!-- Simple Decorative Elements -->
    <div class="absolute inset-0 overflow-hidden opacity-30">
        <div class="absolute top-20 -right-40 w-80 h-80 bg-blue-100 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 -left-40 w-80 h-80 bg-indigo-100 rounded-full blur-3xl"></div>
    </div>
    
    <div class="container-custom relative z-10">
            <!-- Section Header -->
        <div class="text-center mb-16">
                <!-- Badge -->
            <div class="inline-flex items-center bg-blue-600 text-white px-6 py-2.5 rounded-full text-sm font-semibold mb-6 shadow-lg">
                    <svg class="w-4 h-4 me-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                لماذا نحن الأفضل؟
                </div>
                
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight text-gray-900">
                لماذا تختار <span class="text-blue-600">خدمة؟</span>
                </h2>
            <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                منصة تربط بين العملاء ومقدمي الخدمات المنزلية والتجارية في جميع أنحاء المملكة
                </p>
            </div>
            
        <!-- Features Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            <!-- Feature 1 -->
            <div class="group relative">
                <div class="relative h-full bg-gray-50 rounded-2xl p-6 border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-green-600 rounded-xl flex items-center justify-center mb-4 shadow-lg group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">مستوى عالٍ من رضا العملاء</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        نستقبل شكاويك ونعمل على تحسين جودة الشبكة باستمرار
            </p>
        </div>
                </div>

            <!-- Feature 2 -->
            <div class="group relative">
                <div class="relative h-full bg-gray-50 rounded-2xl p-6 border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center mb-4 shadow-lg group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">توصيل سريع للطلبات</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        نوصل طلبك إلى مقدمي الخدمات المتاحين في منطقتك فوراً
                            </p>
                </div>
                        </div>
                        
            <!-- Feature 3 -->
            <div class="group relative">
                <div class="relative h-full bg-gray-50 rounded-2xl p-6 border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-orange-600 rounded-xl flex items-center justify-center mb-4 shadow-lg group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">مراقبة الجودة والنزاهة</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        نفصل نهائياً مقدمي الخدمات سيئي النية أو غير المحترفين
                            </p>
                </div>
                        </div>
                        
            <!-- Feature 4 -->
            <div class="group relative">
                <div class="relative h-full bg-gray-50 rounded-2xl p-6 border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-purple-600 rounded-xl flex items-center justify-center mb-4 shadow-lg group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">نظام شكاوى فعّال</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        نراجع شكاويك ونتخذ إجراءات ضد مقدمي الخدمات المخالفين
                            </p>
                    </div>
                </div>
            </div>

        <div class="grid lg:grid-cols-2 gap-8 mb-16">
            <!-- Left Column - من نحن -->
            <div class="relative bg-gray-50 rounded-3xl p-8 border border-gray-200 shadow-lg h-full">
                <div class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-semibold mb-4 shadow-md">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                    من نحن
                </div>
                <p class="text-gray-800 font-medium leading-relaxed mb-4">
                    خدمة هي منصة إلكترونية متخصصة في <strong>ربط العملاء بمقدمي الخدمات</strong> المنزلية والتجارية في جميع أنحاء المملكة العربية السعودية. <strong>نحن وسيط فقط</strong> - دورنا توصيل طلبك إلى مقدمي الخدمات المناسبين في منطقتك.
                </p>
                            <p class="text-gray-600 leading-relaxed mb-4">
                    من خلال منصتنا، يمكنك الوصول بسهولة إلى شبكة واسعة من مقدمي الخدمات المستقلين في مختلف المجالات: الدهانات، التنظيف، الصيانة، الكهرباء، السباكة، المكيفات، وغيرها.
                            </p>
                            <div class="bg-green-50 border-r-4 border-green-500 p-4 rounded-lg">
                                <p class="text-sm text-green-800 font-semibold mb-2">✅ التزامنا تجاه الجودة:</p>
                                <ul class="text-sm text-green-700 space-y-1">
                                    <li>• نستقبل شكاويك ونراجعها بجدية</li>
                                    <li>• نحذر مقدمي الخدمات المتكررة شكاويهم</li>
                                    <li>• <strong>نفصل نهائياً</strong> مقدمي الخدمات سيئي النية</li>
                                    <li>• نعمل على تحسين جودة الشبكة باستمرار</li>
                                </ul>
                            </div>
                        </div>
                        
            <!-- Right Column - إحصائياتنا -->
            <div class="relative bg-blue-600 rounded-3xl p-8 shadow-lg h-full">
                <div class="inline-flex items-center gap-2 bg-white/20 text-white px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                    </svg>
                    إحصائياتنا
                        </div>
                        
                <h3 class="text-2xl font-extrabold text-white mb-6">
                    نفتخر بإنجازاتنا
                </h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <!-- Stat 1 -->
                    <div class="bg-white/10 rounded-2xl p-4 border border-white/20">
                        <div class="text-4xl font-extrabold text-white mb-1">
                            500+
                        </div>
                        <div class="text-white/90 text-sm font-medium">
                            مقدم خدمة
                        </div>
                        </div>
                        
                    <!-- Stat 2 -->
                    <div class="bg-white/10 rounded-2xl p-4 border border-white/20">
                        <div class="text-4xl font-extrabold text-white mb-1">
                            5K+
                        </div>
                        <div class="text-white/90 text-sm font-medium">
                            عميل راضي
                    </div>
                </div>

                    <!-- Stat 3 -->
                    <div class="bg-white/10 rounded-2xl p-4 border border-white/20">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="text-4xl font-extrabold text-white">
                                4.9
                        </div>
                            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <div class="text-white/90 text-sm font-medium">
                            تقييم العملاء
                        </div>
                        </div>
                    
                    <!-- Stat 4 -->
                    <div class="bg-white/10 rounded-2xl p-4 border border-white/20">
                        <div class="text-4xl font-extrabold text-white mb-1">
                            8
                        </div>
                        <div class="text-white/90 text-sm font-medium">
                            مدينة
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Join as Provider Section (Full Width) -->
<section class="section-padding bg-gradient-to-r from-green-50 to-emerald-50 relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
    </div>
    
    <div class="container-custom relative z-10">
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 border border-gray-100">
                <div class="text-right">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                        هل تريد الانضمام كمقدم خدمة؟
                    </h2>
                    
                    <p class="text-lg md:text-xl text-gray-600 mb-8 leading-relaxed">
                        انضم إلى شبكتنا المتنامية من مقدمي الخدمات الموثوقين واحصل على المزيد من العملاء. نحن نبحث عن محترفين متخصصين في مختلف المجالات لخدمة عملائنا بأفضل جودة ممكنة.
                    </p>
                    
                    <div class="grid md:grid-cols-3 gap-6 mb-8">
                        <div class="flex items-center justify-end gap-3 text-gray-700 bg-gray-50 p-4 rounded-xl">
                            <span class="text-base font-medium">وصول إلى آلاف العملاء المحتملين</span>
                            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex items-center justify-end gap-3 text-gray-700 bg-gray-50 p-4 rounded-xl">
                            <span class="text-base font-medium">دعم فني وإداري مستمر</span>
                            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex items-center justify-end gap-3 text-gray-700 bg-gray-50 p-4 rounded-xl">
                            <span class="text-base font-medium">منصة سهلة الاستخدام لإدارة الطلبات</span>
                            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <a href="<?= htmlspecialchars(WHATSAPP_CHANNEL_URL) ?>" target="_blank" rel="noopener noreferrer" 
                           class="group inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-semibold text-lg px-8 py-4 rounded-xl shadow-lg shadow-green-500/30 hover:shadow-green-500/40 transition-all duration-300 transform hover:scale-105">
                            <svg class="w-6 h-6 me-2 group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                            </svg>
                            انضم إلى قناة واتساب مقدمي الخدمة
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section id="faq" class="section-padding bg-white">
    <div class="container-custom max-w-4xl">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                الأسئلة الشائعة
            </h2>
            <p class="text-lg text-gray-600">
                إجابات على الأسئلة الأكثر شيوعاً حول منصة خدمة
            </p>
        </div>

        <!-- FAQ Items -->
        <div class="space-y-4">
            <!-- FAQ Item 1 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">ما هو دور منصة خدمة بالضبط؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <p class="text-gray-600 leading-relaxed">
                        <strong class="text-gray-900">منصة خدمة هي وسيط إلكتروني</strong> يربط بين العملاء ومقدمي الخدمات فقط. دورنا ينحصر في توصيل طلبك إلى مقدمي الخدمات المناسبين في منطقتك. <strong class="text-orange-600">نحن لا نقدم الخدمات بأنفسنا</strong> ولا نتحمل مسؤولية جودة العمل أو التزام مقدم الخدمة. العلاقة التعاقدية تكون مباشرة بينك وبين مقدم الخدمة.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">هل تضمنون جودة الخدمات المقدمة؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p><strong class="text-gray-900">قانونياً: لا نضمن جودة الخدمات</strong> لأننا وسيط فقط. قد تختلف جودة الخدمة من مقدم لآخر (ممتازة، جيدة، متوسطة، أو غير مرضية).</p>
                        
                        <div class="bg-orange-50 border-r-4 border-orange-500 p-4 rounded">
                            <p class="font-semibold text-orange-900 mb-2">⚠️ تنبيه هام:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm text-orange-800">
                                <li>قد يقوم بعض مقدمي الخدمات بعمل غير احترافي</li>
                                <li>المسؤولية القانونية الكاملة تقع على عاتق مقدم الخدمة</li>
                                <li>ننصح بالتحقق والاتفاق على التفاصيل قبل بدء العمل</li>
                            </ul>
                        </div>

                        <!-- لكن نراقب الجودة -->
                        <div class="bg-green-50 border-r-4 border-green-500 p-4 rounded">
                            <p class="font-semibold text-green-900 mb-2">✅ لكننا نعمل على مراقبة الجودة:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm text-green-800">
                                <li><strong>نستقبل شكاويك</strong> ونراجعها بجدية</li>
                                <li><strong>نحذر</strong> مقدمي الخدمات الذين يتكرر عليهم الشكاوى</li>
                                <li><strong>نفصل نهائياً</strong> مقدمي الخدمات سيئي النية أو غير المحترفين</li>
                                <li><strong>نمنع وصول طلبات جديدة</strong> للمفصولين من المنصة</li>
                                <li>هدفنا: <strong>تحسين جودة الشبكة باستمرار</strong> بناءً على ملاحظاتك</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">هل المعلومات المدخلة في الطلبات دقيقة دائماً؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p><strong class="text-gray-900">لا، المعلومات قد تكون غير دقيقة.</strong> نحن لا نتحقق من صحة البيانات التي يدخلها العملاء في النموذج.</p>
                        <div class="bg-yellow-50 border-r-4 border-yellow-500 p-4 rounded">
                            <p class="font-semibold text-yellow-900 mb-2">⚠️ احتمالات خاصة بالطلبات:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm text-yellow-800">
                                <li>قد يكون رقم الهاتف خاطئاً أو غير صحيح</li>
                                <li>قد يملأ العميل النموذج بشكل ناقص أو خاطئ</li>
                                <li>قد تكون تفاصيل الخدمة المطلوبة غير واضحة</li>
                                <li><strong>لا يمكن استرداد الأموال بسبب معلومات خاطئة من العميل</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">كيف يمكنني طلب خدمة من خلال المنصة؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <p class="text-gray-600 leading-relaxed">
                        طلب الخدمة سهل جداً! املأ النموذج في الصفحة الرئيسية بتحديد نوع الخدمة، مدينتك، ورقم هاتفك. <strong class="text-gray-900">سيتم توصيل طلبك مباشرة إلى مقدمي الخدمات المتاحين</strong> في منطقتك، وسيتواصل معك مقدم الخدمة مباشرة لترتيب التفاصيل والأسعار.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 5 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">ما هي أنواع الخدمات المتاحة؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <p class="text-gray-600 leading-relaxed">
                        نوصل طلبات الخدمات المنزلية والتجارية المتنوعة: الدهانات والترميم، التنظيف، الصيانة، الكهرباء، السباكة، المكيفات، وغيرها. نعمل مع شبكة واسعة من مقدمي الخدمات المستقلين في مختلف التخصصات.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 6 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">ما هي تكلفة الخدمات؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <p class="text-gray-600 leading-relaxed">
                        <strong class="text-gray-900">الأسعار يحددها مقدم الخدمة مباشرة</strong> وتختلف حسب نوع الخدمة ونطاق العمل. المنصة لا تتدخل في تحديد الأسعار أو التفاوض. التعامل المالي يكون مباشرة بينك وبين مقدم الخدمة. ننصحك بالاتفاق على السعر والتفاصيل قبل بدء العمل.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 7 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">ماذا لو لم أكن راضياً عن الخدمة؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p>على الرغم من أن المنصة هي مجرد وسيط ولا تتحمل المسؤولية القانونية عن جودة الخدمات، <strong class="text-green-700">نحن نهتم برضاك ونعمل على تحسين جودة الشبكة.</strong></p>
                        
                        <!-- خطوات الشكوى -->
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-300 p-4 rounded-lg">
                            <p class="font-bold text-green-900 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                📢 نظام الشكاوى والمتابعة:
                            </p>
                            <ol class="list-decimal list-inside space-y-2 text-sm text-green-900">
                                <li><strong>أولاً:</strong> حاول حل المشكلة مباشرة مع مقدم الخدمة</li>
                                <li><strong>ثانياً:</strong> إذا لم يتم الحل، <strong class="text-green-700">أبلغنا فوراً عبر نظام الشكاوى</strong> في الموقع أو WhatsApp</li>
                                <li><strong>ثالثاً:</strong> سنراجع شكواك ونتواصل مع الطرفين لفهم الموقف</li>
                                <li><strong>رابعاً:</strong> في حالة ثبوت سوء النية أو العمل غير الاحترافي، <strong class="text-red-600">سنوقف التعامل مع هذا المقدم نهائياً</strong></li>
                            </ol>
                        </div>

                        <!-- ما نفعله -->
                        <div class="bg-blue-50 border-r-4 border-blue-500 p-4 rounded">
                            <p class="font-semibold text-blue-900 mb-2">✅ التزامنا تجاهك:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm text-blue-800">
                                <li>نراجع جميع الشكاوى بجدية واهتمام</li>
                                <li>نحاول الوساطة لحل المشكلات</li>
                                <li>نحذر مقدمي الخدمات المتكررة شكاويهم</li>
                                <li><strong>نفصل نهائياً أي مقدم خدمة سيء النية أو غير محترف</strong></li>
                                <li>نمنع وصول طلبات جديدة لمقدمي الخدمات المفصولين</li>
                            </ul>
                        </div>

                        <!-- نصائح -->
                        <div class="bg-amber-50 border-r-4 border-amber-500 p-4 rounded">
                            <p class="font-semibold text-amber-900 mb-2">💡 نصائح مهمة:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm text-amber-800">
                                <li>اتفق على كل التفاصيل والأسعار قبل بدء العمل</li>
                                <li>احتفظ بأدلة تواصلك (رسائل، صور) كإثبات</li>
                                <li>أبلغنا فوراً عند ملاحظة أي مشكلة</li>
                                <li>تذكر: المنصة لا تضمن النتائج لكننا نعمل لحمايتك</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 8 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">كيف يمكنني الانضمام كمقدم خدمة؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <p class="text-gray-600 leading-relaxed">
                        إذا كنت مقدم خدمة محترف، اضغط على زر "انضم كمقدم خدمة" في الصفحة الرئيسية. سيتم توجيهك لقناة التسجيل. <strong class="text-gray-900">ملاحظة:</strong> قبولك في المنصة لا يعني تزكية أو ضمان من المنصة. أنت مسؤول بالكامل عن جودة عملك والتزاماتك تجاه العملاء.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 9 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">في أي المدن تتوفر الخدمة؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <p class="text-gray-600 leading-relaxed">
                        نوصل الطلبات في مدن رئيسية بالمملكة العربية السعودية: الرياض، جدة، الدمام، الخبر، الطائف، مكة المكرمة، المدينة المنورة، أبها، تبوك، حائل، بريدة، جازان، ونجران. نعمل باستمرار على التوسع لمناطق جديدة.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 10 - NEW -->
            <div class="faq-item bg-white border border-orange-300 rounded-xl overflow-hidden hover:border-orange-400 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-orange-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-orange-900">⚠️ إخلاء المسؤولية القانونية</span>
                    <svg class="w-5 h-5 text-orange-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <div class="bg-orange-50 border-2 border-orange-300 rounded-lg p-5">
                        <p class="text-orange-900 font-bold mb-3 text-lg">📢 تنويه قانوني هام:</p>
                        <div class="space-y-4 text-gray-700">
                            <p><strong>منصة خدمة هي وسيط إلكتروني فقط</strong> لتسهيل التواصل بين العملاء ومقدمي الخدمات المستقلين.</p>
                            
                            <div class="bg-white rounded-lg p-4 border border-orange-200">
                                <p class="font-semibold text-orange-900 mb-2">🚫 المنصة غير مسؤولة قانونياً عن:</p>
                                <ul class="list-disc list-inside space-y-1 text-sm">
                                    <li>جودة الخدمات المقدمة (ممتازة، جيدة، متوسطة، أو رديئة)</li>
                                    <li>دقة المعلومات المدخلة من قبل العملاء</li>
                                    <li>أي أضرار أو خسائر ناتجة عن تنفيذ الخدمة</li>
                                    <li>التزامات أو تصرفات مقدمي الخدمات</li>
                                    <li>أرقام هواتف خاطئة أو معلومات ناقصة</li>
                                    <li>النزاعات أو الخلافات بين الطرفين</li>
                                </ul>
                            </div>

                            <!-- لكننا نهتم بالجودة -->
                            <div class="bg-green-100 rounded-lg p-4 border-2 border-green-400">
                                <p class="font-bold text-green-900 mb-2 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    ✅ لكن التزامنا الأخلاقي تجاهك:
                                </p>
                                <ul class="list-disc list-inside space-y-1 text-sm text-green-800">
                                    <li><strong>نستقبل شكاويك</strong> عبر نظام الشكاوى أو WhatsApp</li>
                                    <li><strong>نراجع الشكاوى</strong> ونحاول الوساطة لحل المشكلات</li>
                                    <li><strong>نحذر</strong> مقدمي الخدمات الذين تتكرر عليهم الشكاوى</li>
                                    <li><strong>نفصل نهائياً</strong> مقدمي الخدمات سيئي النية أو غير المحترفين</li>
                                    <li><strong>نمنع وصول طلبات جديدة</strong> للمفصولين من شبكتنا</li>
                                </ul>
                            </div>
                            
                            <p class="text-center font-bold text-orange-800 bg-orange-100 py-3 px-4 rounded-lg border-2 border-orange-400">
                                ⚖️ المسؤولية القانونية مباشرة بين العميل ومقدم الخدمة فقط
                                <br>
                                <span class="text-sm font-normal">لكننا نعمل جاهدين لتحسين جودة الشبكة باستمرار</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// ===== VALIDATION ERROR HANDLER =====
function handleValidationErrors(errors, form) {
    // Tüm hataları temizle
    form.querySelectorAll('.field-error').forEach(el => el.remove());
    form.querySelectorAll('.border-red-500').forEach(el => {
        el.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
        el.classList.add('border-gray-300');
    });
    
    // Her hata için mesaj göster
    Object.keys(errors).forEach(fieldName => {
        const errorMessage = errors[fieldName];
        
        // Field mapping
        const fieldMap = {
            'phone': '.phone-input-primary',
            'phone_confirm': '.phone-input-confirm',
            'service_type': '.service-type-select',
            'city': 'select[name="city"]'
        };
        
        const selector = fieldMap[fieldName] || `[name="${fieldName}"]`;
        const field = form.querySelector(selector);
        
        if (field) {
            // Field'ı kırmızı yap
            field.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
            field.classList.remove('border-gray-300');
            
            // Error mesajı ekle
            const errorDiv = document.createElement('p');
            errorDiv.className = 'field-error text-red-600 text-sm mt-1 font-medium';
            errorDiv.textContent = errorMessage;
            
            const parent = field.closest('div') || field.parentElement;
            parent.appendChild(errorDiv);
            
            // İlk hataya focus
            if (Object.keys(errors)[0] === fieldName) {
                field.focus();
            }
        }
    });
}

// ===== PHONE VALIDATION HELPERS =====
function validateSaudiPhone(phone) {
    // Remove all non-digits except +
    const cleaned = phone.replace(/[^\d+]/g, '');
    
    // Suudi telefon formatları:
    // 05xxxxxxxx (10 digits)
    // 5xxxxxxxxx (9 digits)
    // +9665xxxxxxxx (13 chars)
    // 009665xxxxxxxx (14 chars)
    
    const patterns = [
        /^05[0-9]{8}$/,           // 05xxxxxxxx
        /^5[0-9]{8}$/,            // 5xxxxxxxx
        /^\+9665[0-9]{8}$/,       // +9665xxxxxxxx
        /^009665[0-9]{8}$/        // 009665xxxxxxxx
    ];
    
    return patterns.some(pattern => pattern.test(cleaned));
}

function normalizeSaudiPhone(phone) {
    let cleaned = phone.replace(/[^\d+]/g, '');
    
    // Normalize to 05xxxxxxxx format
    if (cleaned.startsWith('+9665')) {
        cleaned = '0' + cleaned.substring(4);
    } else if (cleaned.startsWith('009665')) {
        cleaned = '0' + cleaned.substring(5);
    } else if (cleaned.startsWith('9665')) {
        cleaned = '0' + cleaned.substring(3);
    } else if (cleaned.startsWith('5') && cleaned.length === 9) {
        cleaned = '0' + cleaned;
    }
    
    return cleaned;
}

function showPhoneError(input, message) {
    input.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
    input.classList.remove('border-gray-300');
    
    let errorDiv = input.parentElement.querySelector('.phone-error');
    if (!errorDiv) {
        errorDiv = document.createElement('p');
        errorDiv.className = 'phone-error text-red-600 text-sm mt-1 font-medium';
        input.parentElement.appendChild(errorDiv);
    }
    errorDiv.textContent = message;
}

function clearPhoneError(input) {
    input.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
    input.classList.add('border-gray-300');
    
    const errorDiv = input.parentElement.querySelector('.phone-error');
    if (errorDiv) errorDiv.remove();
}

// ===== SERVICE TIME SELECTION =====
document.querySelectorAll('input[name="scheduled_date"]').forEach(dateInput => {
    // When date picker is clicked/changed, auto-select the 'scheduled' radio
    dateInput.addEventListener('focus', function() {
        const form = this.closest('form');
        const scheduledRadio = form.querySelector('input[value="scheduled"][name="service_time_type"]');
        if (scheduledRadio) {
            scheduledRadio.checked = true;
            // Trigger change event to update visual state
            scheduledRadio.dispatchEvent(new Event('change'));
        }
    });
    
    dateInput.addEventListener('change', function() {
        const form = this.closest('form');
        const scheduledRadio = form.querySelector('input[value="scheduled"][name="service_time_type"]');
        if (scheduledRadio && this.value) {
            scheduledRadio.checked = true;
            scheduledRadio.dispatchEvent(new Event('change'));
        }
    });
});

// ===== FORM HANDLING =====
document.querySelectorAll('.service-request-form').forEach(form => {
    const phoneInputPrimary = form.querySelector('.phone-input-primary');
    const phoneInputConfirm = form.querySelector('.phone-input-confirm');

    // Phone input validation (real-time)
    [phoneInputPrimary, phoneInputConfirm].forEach(input => {
        if (!input) return;
        
        // Format input
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d+]/g, '');
            
            // Auto-format: if starts with 5, add 0
            if (value.length === 1 && value === '5') {
                value = '05';
            }
            
            // Limit length
            if (value.startsWith('05') || value.startsWith('5')) {
                value = value.substring(0, 10);
            } else if (value.startsWith('+966')) {
                value = value.substring(0, 13);
            }
            
            e.target.value = value;
            
            // Clear error on input
            if (value.length > 0) {
                clearPhoneError(e.target);
            }
        });
        
        // Prevent paste
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            showAlert('يرجى كتابة رقم الهاتف يدوياً', 'error');
        });
        
        // Validate on blur
        input.addEventListener('blur', function(e) {
            const value = e.target.value;
            if (value.length === 0) return; // Skip if empty
            
            if (!validateSaudiPhone(value)) {
                showPhoneError(e.target, 'رقم هاتف سعودي غير صحيح (مثال: 0501234567)');
            } else {
                clearPhoneError(e.target);
            }
        });
    });

    // Phone confirmation match check
    if (phoneInputConfirm) {
        phoneInputConfirm.addEventListener('blur', function() {
            if (phoneInputPrimary && phoneInputConfirm.value.length > 0) {
                if (phoneInputPrimary.value !== phoneInputConfirm.value) {
                    showPhoneError(phoneInputConfirm, 'رقم الهاتف غير متطابق');
                } else {
                    clearPhoneError(phoneInputConfirm);
                }
            }
        });
    }

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = form.querySelector('[data-submit-btn]');
        const btnText = submitBtn?.querySelector('.btn-text');
        const btnLoading = submitBtn?.querySelector('.btn-loading');

        // Final validation
        let hasError = false;

        // Validate primary phone
        if (phoneInputPrimary) {
            const phone = phoneInputPrimary.value;
            if (!phone || phone.length === 0) {
                showPhoneError(phoneInputPrimary, 'رقم الهاتف مطلوب');
                hasError = true;
            } else if (!validateSaudiPhone(phone)) {
                showPhoneError(phoneInputPrimary, 'رقم هاتف سعودي غير صحيح (مثال: 0501234567)');
                hasError = true;
            }
        }

        // Validate phone confirmation
        if (phoneInputConfirm) {
            const phoneConfirm = phoneInputConfirm.value;
            if (!phoneConfirm || phoneConfirm.length === 0) {
                showPhoneError(phoneInputConfirm, 'تأكيد رقم الهاتف مطلوب');
                hasError = true;
            } else if (phoneInputPrimary && phoneInputPrimary.value !== phoneConfirm) {
                showPhoneError(phoneInputConfirm, 'رقم الهاتف غير متطابق');
                hasError = true;
            }
        }

        if (hasError) {
            showAlert('يرجى تصحيح الأخطاء في النموذج', 'error');
            return;
        }

        // Show loading state
        if (submitBtn) {
            submitBtn.disabled = true;
            btnText?.classList.add('hidden');
            btnLoading?.classList.remove('hidden');
        }

        const formData = new FormData(this);

        try {
            const response = await fetch('/lead/submit', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            });

            const contentType = response.headers.get('content-type') || '';
            const responseText = await response.text();
            
            if (!response.ok) {
                let errorData;
                try {
                    errorData = JSON.parse(responseText);
                } catch {
                    throw new Error(`HTTP ${response.status}`);
                }
                
                if (errorData.errors) {
                    handleValidationErrors(errorData.errors, form);
                }
                
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            }

            if (!contentType.includes('application/json')) {
                throw new Error('Sunucudan geçersiz yanıt alındı');
            }

            const result = JSON.parse(responseText);

            if (result.success) {
                showAlert('✅ تم إرسال طلبك بنجاح! سنتواصل معك خلال دقائق', 'success');
                
                // Redirect to thanks page
                if (result.redirect) {
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1000);
                } else {
                this.reset();
                toggleOtherFields();
                form.querySelectorAll('.phone-input').forEach(clearPhoneError);
                }
            } else {
                if (result.errors) {
                    handleValidationErrors(result.errors, form);
                }
                showAlert(result.message || 'حدث خطأ أثناء إرسال الطلب', 'error');
            }
        } catch (error) {
            let errorMessage = '❌ حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى';
            if (error.message.includes('HTTP error')) {
                errorMessage = '❌ حدث خطأ في الخادم. يرجى المحاولة مرة أخرى';
            }
            showAlert(errorMessage, 'error');
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                btnText?.classList.remove('hidden');
                btnLoading?.classList.add('hidden');
            }
        }
    });
});

// ===== FAQ TOGGLE =====
function toggleFaq(button) {
    const faqItem = button.closest('.faq-item');
    const answer = faqItem.querySelector('.faq-answer');
    const icon = button.querySelector('.faq-icon');
    
    // Toggle answer visibility
    answer.classList.toggle('hidden');
    
    // Rotate icon
    if (answer.classList.contains('hidden')) {
        icon.style.transform = 'rotate(0deg)';
    } else {
        icon.style.transform = 'rotate(180deg)';
    }
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/base.php';
?>

$pageDescription = "احصل على أفضل الخدمات المنزلية والتجارية من مقدمين موثوقين في السعودية. دهانات، ترميم، تنظيف، صيانة، كهرباء، سباكة ومكيفات.";
$bodyClass = "bg-gradient-soft min-h-screen";

ob_start();

// Form helper fonksiyonu
if (!function_exists('render_service_request_form')) {
    require_once __DIR__ . '/helpers/form_helper.php';
}
?>

<!-- Notification Messages -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="fixed top-20 right-4 left-4 md:left-auto md:w-96 z-50 animate-slide-in-top">
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-xl">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div class="mr-3 flex-1">
                    <p class="text-sm font-medium text-green-800"><?= htmlspecialchars($_SESSION['success']) ?></p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-green-500 hover:text-green-700">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="fixed top-20 right-4 left-4 md:left-auto md:w-96 z-50 animate-slide-in-top">
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-xl">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div class="mr-3 flex-1">
                    <p class="text-sm font-medium text-red-800"><?= nl2br(htmlspecialchars($_SESSION['error'])) ?></p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<script>
// Auto-hide notifications after 5 seconds
setTimeout(() => {
    document.querySelectorAll('.animate-slide-in-top').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(-20px)';
        el.style.transition = 'all 0.3s ease';
        setTimeout(() => el.remove(), 300);
    });
}, 5000);
</script>

<!-- Hero Section -->
<section class="hero-dark-section relative overflow-hidden pt-16 md:pt-18 min-h-screen flex items-center">
    <!-- Modern Dark Gradient Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800"></div>
    
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <!-- Large Gradient Orbs -->
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-primary-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>
        
        <!-- Grid Pattern Overlay -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
    </div>
    
    <div class="container-custom relative z-10 py-20">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <!-- Content -->
            <div class="text-center lg:text-start">
                <!-- Badge -->
                <div class="inline-flex items-center bg-white/10 backdrop-blur-md text-white px-5 py-2.5 rounded-full text-sm font-semibold mb-8 border border-white/20 shadow-lg">
                    <svg class="w-4 h-4 me-2 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    خدمات موثوقة ومضمونة
                </div>
                
                <!-- Main Heading -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-extrabold text-white mb-6 leading-tight">
                    احصل على 
                    <span class="bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-400 bg-clip-text text-transparent">
                        أفضل الخدمات
                    </span>
                    <br>بأسهل طريقة
                </h1>
                
                <!-- Description -->
                <p class="text-lg md:text-xl text-blue-100 mb-10 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                    منصة متخصصة تربط بين العملاء ومقدمي الخدمات المنزلية والتجارية الموثوقين في جميع أنحاء المملكة العربية السعودية
                </p>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-10">
                    <a href="#request-service" class="group inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold text-lg px-8 py-4 rounded-2xl shadow-2xl shadow-blue-500/50 hover:shadow-blue-600/50 transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 me-2 group-hover:translate-y-[-2px] transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                        </svg>
                        اطلب خدمة الآن
                    </a>
                    
                    <a href="<?= htmlspecialchars(WHATSAPP_CHANNEL_URL) ?>" target="_blank" rel="noopener noreferrer" 
                       class="group inline-flex items-center justify-center bg-white/10 backdrop-blur-md hover:bg-white/20 text-white font-semibold text-lg px-8 py-4 rounded-2xl border border-white/20 hover:border-white/30 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 me-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                        </svg>
                        انضم كمقدم خدمة
                    </a>
                </div>
            </div>
            
            <!-- Form Card -->
            <div class="relative">
                <div class="relative mx-auto max-w-md lg:max-w-lg">
                    <!-- Glassmorphism Form Card -->
                    <div class="relative bg-white/10 backdrop-blur-2xl rounded-2xl lg:rounded-3xl p-6 lg:p-7 border border-white/20 shadow-2xl">
                        <!-- Card Glow Effect -->
                        <div class="absolute inset-0 rounded-2xl lg:rounded-3xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 opacity-50 blur-2xl -z-10"></div>
                        
                        <!-- Form Title -->
                        <div class="text-center mb-5 lg:mb-6">
                            <h2 class="text-xl lg:text-2xl font-bold text-white mb-1">
                                اطلب خدمتك الآن
                            </h2>
                            <p class="text-blue-200 text-xs lg:text-sm">
                                نتواصل معك خلال دقائق
                            </p>
                        </div>
                        
                        <?php
                        render_service_request_form('hero-service-form', 'hero', [
                            'compact' => true,
                            'ultra_compact' => true,
                            'button_text' => 'إرسال الطلب',
                            'button_classes' => 'btn-primary w-full text-sm lg:text-base py-2.5 lg:py-3 relative',
                            'form_origin' => 'hero'
                        ]);
                        ?>
                    </div>
                    
                    <!-- Decorative Elements -->
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-blue-500/30 rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-cyan-500/30 rounded-full blur-2xl"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="section-padding relative overflow-hidden">
    <!-- Modern Background with Gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-indigo-50"></div>
    
    <!-- Decorative Elements -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-blue-200/20 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-200/20 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
    
    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(circle, #000 1px, transparent 1px); background-size: 40px 40px;"></div>
    
    <div class="container-custom relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <div class="inline-block mb-4">
                <span class="text-sm font-semibold text-blue-600 bg-blue-50 px-4 py-2 rounded-full">
                    خدماتنا
                </span>
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                خدماتنا المتخصصة
            </h2>
            <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                نقدم مجموعة شاملة من الخدمات المنزلية والتجارية بأعلى معايير الجودة والاحترافية
            </p>
        </div>
        
        <!-- Services Grid -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            <?php 
            $serviceIcons = [
                'paint' => [
                    'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
                    'viewBox' => '0 0 24 24',
                    'image' => 'paint.jpg',
                    'color' => 'from-blue-500 to-blue-600',
                    'bgColor' => 'bg-blue-50',
                    'borderColor' => 'border-blue-200',
                    'desc' => 'دهانات داخلية وخارجية بأحدث التقنيات'
                ],
                'renovation' => [
                    'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                    'viewBox' => '0 0 24 24',
                    'image' => 'renovation.jpg',
                    'color' => 'from-orange-500 to-orange-600',
                    'bgColor' => 'bg-orange-50',
                    'borderColor' => 'border-orange-200',
                    'desc' => 'ترميم وتجديد المنازل والمكاتب'
                ],
                'plumbing' => [
                    'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                    'viewBox' => '0 0 24 24',
                    'image' => 'plumbing.png',
                    'color' => 'from-cyan-500 to-cyan-600',
                    'bgColor' => 'bg-cyan-50',
                    'borderColor' => 'border-cyan-200',
                    'desc' => 'إصلاح وصيانة أنظمة السباكة'
                ],
                'electric' => [
                    'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
                    'viewBox' => '0 0 24 24',
                    'image' => 'electric.jpg',
                    'color' => 'from-yellow-500 to-yellow-600',
                    'bgColor' => 'bg-yellow-50',
                    'borderColor' => 'border-yellow-200',
                    'desc' => 'خدمات كهربائية شاملة وآمنة'
                ],
                'cleaning' => [
                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    'viewBox' => '0 0 24 24',
                    'image' => 'cleaning.webp',
                    'color' => 'from-green-500 to-green-600',
                    'bgColor' => 'bg-green-50',
                    'borderColor' => 'border-green-200',
                    'desc' => 'تنظيف شامل للمنازل والمكاتب'
                ],
                'ac' => [
                    'icon' => 'M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z',
                    'viewBox' => '0 0 24 24',
                    'image' => 'ac.jpg',
                    'color' => 'from-indigo-500 to-indigo-600',
                    'bgColor' => 'bg-indigo-50',
                    'borderColor' => 'border-indigo-200',
                    'desc' => 'صيانة وتركيب أنظمة التكييف'
                ]
            ];
            
            foreach (getServiceTypes() as $key => $service): 
                $serviceData = $serviceIcons[$key] ?? $serviceIcons['paint'];
            ?>
            <div class="group relative">
                <!-- Glassmorphism Card -->
                <div class="relative h-full bg-white/80 backdrop-blur-xl rounded-3xl p-8 border border-gray-200/50 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 overflow-hidden">
                    <!-- Hover Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-br <?= $serviceData['color'] ?> opacity-0 group-hover:opacity-5 transition-opacity duration-500 rounded-3xl"></div>
                    
                    <!-- Service Image -->
                    <?php if (isset($serviceData['image'])): ?>
                    <div class="relative mb-6 h-48 rounded-2xl overflow-hidden bg-gradient-to-br <?= $serviceData['color'] ?>">
                        <img src="/assets/images/<?= htmlspecialchars($serviceData['image']) ?>" 
                             alt="<?= htmlspecialchars($service['ar']) ?>"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        <!-- Icon Overlay -->
                        <div class="absolute top-4 left-4 w-14 h-14 bg-white/90 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <svg class="w-8 h-8 text-gray-800" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="<?= $serviceData['viewBox'] ?? '0 0 24 24' ?>">
                                <path d="<?= $serviceData['icon'] ?>"/>
                            </svg>
                        </div>
                    </div>
                    <?php else: ?>
                    <!-- Icon Container (Fallback if no image) -->
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br <?= $serviceData['color'] ?> rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="<?= $serviceData['viewBox'] ?? '0 0 24 24' ?>">
                                <path d="<?= $serviceData['icon'] ?>"/>
                            </svg>
                        </div>
                        <!-- Decorative Circle -->
                        <div class="absolute -top-2 -right-2 w-8 h-8 <?= $serviceData['bgColor'] ?> rounded-full border-2 <?= $serviceData['borderColor'] ?> opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Content -->
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-blue-600 transition-colors duration-300">
                        <?= htmlspecialchars($service['ar']) ?>
                    </h3>
                    
                    <p class="text-gray-600 mb-6 leading-relaxed text-base">
                        <?= $serviceData['desc'] ?>
                    </p>
                    
                    <!-- CTA Button -->
                    <a href="/services/<?= htmlspecialchars($key) ?>" class="inline-flex items-center justify-center gap-2 text-blue-600 hover:text-blue-700 font-semibold group/btn relative">
                        <span class="relative z-10">طلب الخدمة</span>
                        <svg class="w-5 h-5 relative z-10 group-hover/btn:translate-x-1 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        <!-- Underline Effect -->
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 group-hover/btn:w-full transition-all duration-300"></span>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="section-padding relative overflow-hidden bg-white">
    <!-- Simple Decorative Elements -->
    <div class="absolute inset-0 overflow-hidden opacity-30">
        <div class="absolute top-20 -right-40 w-80 h-80 bg-blue-100 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 -left-40 w-80 h-80 bg-indigo-100 rounded-full blur-3xl"></div>
    </div>
    
    <div class="container-custom relative z-10">
            <!-- Section Header -->
        <div class="text-center mb-16">
                <!-- Badge -->
            <div class="inline-flex items-center bg-blue-600 text-white px-6 py-2.5 rounded-full text-sm font-semibold mb-6 shadow-lg">
                    <svg class="w-4 h-4 me-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                لماذا نحن الأفضل؟
                </div>
                
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight text-gray-900">
                لماذا تختار <span class="text-blue-600">خدمة؟</span>
                </h2>
            <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                منصة تربط بين العملاء ومقدمي الخدمات المنزلية والتجارية في جميع أنحاء المملكة
                </p>
            </div>
            
        <!-- Features Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            <!-- Feature 1 -->
            <div class="group relative">
                <div class="relative h-full bg-gray-50 rounded-2xl p-6 border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-green-600 rounded-xl flex items-center justify-center mb-4 shadow-lg group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">مستوى عالٍ من رضا العملاء</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        نستقبل شكاويك ونعمل على تحسين جودة الشبكة باستمرار
            </p>
        </div>
                </div>

            <!-- Feature 2 -->
            <div class="group relative">
                <div class="relative h-full bg-gray-50 rounded-2xl p-6 border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center mb-4 shadow-lg group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">توصيل سريع للطلبات</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        نوصل طلبك إلى مقدمي الخدمات المتاحين في منطقتك فوراً
                            </p>
                </div>
                        </div>
                        
            <!-- Feature 3 -->
            <div class="group relative">
                <div class="relative h-full bg-gray-50 rounded-2xl p-6 border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-orange-600 rounded-xl flex items-center justify-center mb-4 shadow-lg group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">مراقبة الجودة والنزاهة</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        نفصل نهائياً مقدمي الخدمات سيئي النية أو غير المحترفين
                            </p>
                </div>
                        </div>
                        
            <!-- Feature 4 -->
            <div class="group relative">
                <div class="relative h-full bg-gray-50 rounded-2xl p-6 border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-purple-600 rounded-xl flex items-center justify-center mb-4 shadow-lg group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">نظام شكاوى فعّال</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        نراجع شكاويك ونتخذ إجراءات ضد مقدمي الخدمات المخالفين
                            </p>
                    </div>
                </div>
            </div>

        <div class="grid lg:grid-cols-2 gap-8 mb-16">
            <!-- Left Column - من نحن -->
            <div class="relative bg-gray-50 rounded-3xl p-8 border border-gray-200 shadow-lg h-full">
                <div class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-semibold mb-4 shadow-md">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                    من نحن
                </div>
                <p class="text-gray-800 font-medium leading-relaxed mb-4">
                    خدمة هي منصة إلكترونية متخصصة في <strong>ربط العملاء بمقدمي الخدمات</strong> المنزلية والتجارية في جميع أنحاء المملكة العربية السعودية. <strong>نحن وسيط فقط</strong> - دورنا توصيل طلبك إلى مقدمي الخدمات المناسبين في منطقتك.
                </p>
                            <p class="text-gray-600 leading-relaxed mb-4">
                    من خلال منصتنا، يمكنك الوصول بسهولة إلى شبكة واسعة من مقدمي الخدمات المستقلين في مختلف المجالات: الدهانات، التنظيف، الصيانة، الكهرباء، السباكة، المكيفات، وغيرها.
                            </p>
                            <div class="bg-green-50 border-r-4 border-green-500 p-4 rounded-lg">
                                <p class="text-sm text-green-800 font-semibold mb-2">✅ التزامنا تجاه الجودة:</p>
                                <ul class="text-sm text-green-700 space-y-1">
                                    <li>• نستقبل شكاويك ونراجعها بجدية</li>
                                    <li>• نحذر مقدمي الخدمات المتكررة شكاويهم</li>
                                    <li>• <strong>نفصل نهائياً</strong> مقدمي الخدمات سيئي النية</li>
                                    <li>• نعمل على تحسين جودة الشبكة باستمرار</li>
                                </ul>
                            </div>
                        </div>
                        
            <!-- Right Column - إحصائياتنا -->
            <div class="relative bg-blue-600 rounded-3xl p-8 shadow-lg h-full">
                <div class="inline-flex items-center gap-2 bg-white/20 text-white px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                    </svg>
                    إحصائياتنا
                        </div>
                        
                <h3 class="text-2xl font-extrabold text-white mb-6">
                    نفتخر بإنجازاتنا
                </h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <!-- Stat 1 -->
                    <div class="bg-white/10 rounded-2xl p-4 border border-white/20">
                        <div class="text-4xl font-extrabold text-white mb-1">
                            500+
                        </div>
                        <div class="text-white/90 text-sm font-medium">
                            مقدم خدمة
                        </div>
                        </div>
                        
                    <!-- Stat 2 -->
                    <div class="bg-white/10 rounded-2xl p-4 border border-white/20">
                        <div class="text-4xl font-extrabold text-white mb-1">
                            5K+
                        </div>
                        <div class="text-white/90 text-sm font-medium">
                            عميل راضي
                    </div>
                </div>

                    <!-- Stat 3 -->
                    <div class="bg-white/10 rounded-2xl p-4 border border-white/20">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="text-4xl font-extrabold text-white">
                                4.9
                        </div>
                            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <div class="text-white/90 text-sm font-medium">
                            تقييم العملاء
                        </div>
                        </div>
                    
                    <!-- Stat 4 -->
                    <div class="bg-white/10 rounded-2xl p-4 border border-white/20">
                        <div class="text-4xl font-extrabold text-white mb-1">
                            8
                        </div>
                        <div class="text-white/90 text-sm font-medium">
                            مدينة
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Join as Provider Section (Full Width) -->
<section class="section-padding bg-gradient-to-r from-green-50 to-emerald-50 relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
    </div>
    
    <div class="container-custom relative z-10">
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 border border-gray-100">
                <div class="text-right">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                        هل تريد الانضمام كمقدم خدمة؟
                    </h2>
                    
                    <p class="text-lg md:text-xl text-gray-600 mb-8 leading-relaxed">
                        انضم إلى شبكتنا المتنامية من مقدمي الخدمات الموثوقين واحصل على المزيد من العملاء. نحن نبحث عن محترفين متخصصين في مختلف المجالات لخدمة عملائنا بأفضل جودة ممكنة.
                    </p>
                    
                    <div class="grid md:grid-cols-3 gap-6 mb-8">
                        <div class="flex items-center justify-end gap-3 text-gray-700 bg-gray-50 p-4 rounded-xl">
                            <span class="text-base font-medium">وصول إلى آلاف العملاء المحتملين</span>
                            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex items-center justify-end gap-3 text-gray-700 bg-gray-50 p-4 rounded-xl">
                            <span class="text-base font-medium">دعم فني وإداري مستمر</span>
                            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex items-center justify-end gap-3 text-gray-700 bg-gray-50 p-4 rounded-xl">
                            <span class="text-base font-medium">منصة سهلة الاستخدام لإدارة الطلبات</span>
                            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <a href="<?= htmlspecialchars(WHATSAPP_CHANNEL_URL) ?>" target="_blank" rel="noopener noreferrer" 
                           class="group inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-semibold text-lg px-8 py-4 rounded-xl shadow-lg shadow-green-500/30 hover:shadow-green-500/40 transition-all duration-300 transform hover:scale-105">
                            <svg class="w-6 h-6 me-2 group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                            </svg>
                            انضم إلى قناة واتساب مقدمي الخدمة
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section id="faq" class="section-padding bg-white">
    <div class="container-custom max-w-4xl">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                الأسئلة الشائعة
            </h2>
            <p class="text-lg text-gray-600">
                إجابات على الأسئلة الأكثر شيوعاً حول منصة خدمة
            </p>
        </div>

        <!-- FAQ Items -->
        <div class="space-y-4">
            <!-- FAQ Item 1 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">ما هو دور منصة خدمة بالضبط؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <p class="text-gray-600 leading-relaxed">
                        <strong class="text-gray-900">منصة خدمة هي وسيط إلكتروني</strong> يربط بين العملاء ومقدمي الخدمات فقط. دورنا ينحصر في توصيل طلبك إلى مقدمي الخدمات المناسبين في منطقتك. <strong class="text-orange-600">نحن لا نقدم الخدمات بأنفسنا</strong> ولا نتحمل مسؤولية جودة العمل أو التزام مقدم الخدمة. العلاقة التعاقدية تكون مباشرة بينك وبين مقدم الخدمة.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">هل تضمنون جودة الخدمات المقدمة؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p><strong class="text-gray-900">قانونياً: لا نضمن جودة الخدمات</strong> لأننا وسيط فقط. قد تختلف جودة الخدمة من مقدم لآخر (ممتازة، جيدة، متوسطة، أو غير مرضية).</p>
                        
                        <div class="bg-orange-50 border-r-4 border-orange-500 p-4 rounded">
                            <p class="font-semibold text-orange-900 mb-2">⚠️ تنبيه هام:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm text-orange-800">
                                <li>قد يقوم بعض مقدمي الخدمات بعمل غير احترافي</li>
                                <li>المسؤولية القانونية الكاملة تقع على عاتق مقدم الخدمة</li>
                                <li>ننصح بالتحقق والاتفاق على التفاصيل قبل بدء العمل</li>
                            </ul>
                        </div>

                        <!-- لكن نراقب الجودة -->
                        <div class="bg-green-50 border-r-4 border-green-500 p-4 rounded">
                            <p class="font-semibold text-green-900 mb-2">✅ لكننا نعمل على مراقبة الجودة:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm text-green-800">
                                <li><strong>نستقبل شكاويك</strong> ونراجعها بجدية</li>
                                <li><strong>نحذر</strong> مقدمي الخدمات الذين يتكرر عليهم الشكاوى</li>
                                <li><strong>نفصل نهائياً</strong> مقدمي الخدمات سيئي النية أو غير المحترفين</li>
                                <li><strong>نمنع وصول طلبات جديدة</strong> للمفصولين من المنصة</li>
                                <li>هدفنا: <strong>تحسين جودة الشبكة باستمرار</strong> بناءً على ملاحظاتك</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">هل المعلومات المدخلة في الطلبات دقيقة دائماً؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p><strong class="text-gray-900">لا، المعلومات قد تكون غير دقيقة.</strong> نحن لا نتحقق من صحة البيانات التي يدخلها العملاء في النموذج.</p>
                        <div class="bg-yellow-50 border-r-4 border-yellow-500 p-4 rounded">
                            <p class="font-semibold text-yellow-900 mb-2">⚠️ احتمالات خاصة بالطلبات:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm text-yellow-800">
                                <li>قد يكون رقم الهاتف خاطئاً أو غير صحيح</li>
                                <li>قد يملأ العميل النموذج بشكل ناقص أو خاطئ</li>
                                <li>قد تكون تفاصيل الخدمة المطلوبة غير واضحة</li>
                                <li><strong>لا يمكن استرداد الأموال بسبب معلومات خاطئة من العميل</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">كيف يمكنني طلب خدمة من خلال المنصة؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <p class="text-gray-600 leading-relaxed">
                        طلب الخدمة سهل جداً! املأ النموذج في الصفحة الرئيسية بتحديد نوع الخدمة، مدينتك، ورقم هاتفك. <strong class="text-gray-900">سيتم توصيل طلبك مباشرة إلى مقدمي الخدمات المتاحين</strong> في منطقتك، وسيتواصل معك مقدم الخدمة مباشرة لترتيب التفاصيل والأسعار.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 5 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">ما هي أنواع الخدمات المتاحة؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <p class="text-gray-600 leading-relaxed">
                        نوصل طلبات الخدمات المنزلية والتجارية المتنوعة: الدهانات والترميم، التنظيف، الصيانة، الكهرباء، السباكة، المكيفات، وغيرها. نعمل مع شبكة واسعة من مقدمي الخدمات المستقلين في مختلف التخصصات.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 6 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">ما هي تكلفة الخدمات؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <p class="text-gray-600 leading-relaxed">
                        <strong class="text-gray-900">الأسعار يحددها مقدم الخدمة مباشرة</strong> وتختلف حسب نوع الخدمة ونطاق العمل. المنصة لا تتدخل في تحديد الأسعار أو التفاوض. التعامل المالي يكون مباشرة بينك وبين مقدم الخدمة. ننصحك بالاتفاق على السعر والتفاصيل قبل بدء العمل.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 7 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">ماذا لو لم أكن راضياً عن الخدمة؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p>على الرغم من أن المنصة هي مجرد وسيط ولا تتحمل المسؤولية القانونية عن جودة الخدمات، <strong class="text-green-700">نحن نهتم برضاك ونعمل على تحسين جودة الشبكة.</strong></p>
                        
                        <!-- خطوات الشكوى -->
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-300 p-4 rounded-lg">
                            <p class="font-bold text-green-900 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                📢 نظام الشكاوى والمتابعة:
                            </p>
                            <ol class="list-decimal list-inside space-y-2 text-sm text-green-900">
                                <li><strong>أولاً:</strong> حاول حل المشكلة مباشرة مع مقدم الخدمة</li>
                                <li><strong>ثانياً:</strong> إذا لم يتم الحل، <strong class="text-green-700">أبلغنا فوراً عبر نظام الشكاوى</strong> في الموقع أو WhatsApp</li>
                                <li><strong>ثالثاً:</strong> سنراجع شكواك ونتواصل مع الطرفين لفهم الموقف</li>
                                <li><strong>رابعاً:</strong> في حالة ثبوت سوء النية أو العمل غير الاحترافي، <strong class="text-red-600">سنوقف التعامل مع هذا المقدم نهائياً</strong></li>
                            </ol>
                        </div>

                        <!-- ما نفعله -->
                        <div class="bg-blue-50 border-r-4 border-blue-500 p-4 rounded">
                            <p class="font-semibold text-blue-900 mb-2">✅ التزامنا تجاهك:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm text-blue-800">
                                <li>نراجع جميع الشكاوى بجدية واهتمام</li>
                                <li>نحاول الوساطة لحل المشكلات</li>
                                <li>نحذر مقدمي الخدمات المتكررة شكاويهم</li>
                                <li><strong>نفصل نهائياً أي مقدم خدمة سيء النية أو غير محترف</strong></li>
                                <li>نمنع وصول طلبات جديدة لمقدمي الخدمات المفصولين</li>
                            </ul>
                        </div>

                        <!-- نصائح -->
                        <div class="bg-amber-50 border-r-4 border-amber-500 p-4 rounded">
                            <p class="font-semibold text-amber-900 mb-2">💡 نصائح مهمة:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm text-amber-800">
                                <li>اتفق على كل التفاصيل والأسعار قبل بدء العمل</li>
                                <li>احتفظ بأدلة تواصلك (رسائل، صور) كإثبات</li>
                                <li>أبلغنا فوراً عند ملاحظة أي مشكلة</li>
                                <li>تذكر: المنصة لا تضمن النتائج لكننا نعمل لحمايتك</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 8 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">كيف يمكنني الانضمام كمقدم خدمة؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <p class="text-gray-600 leading-relaxed">
                        إذا كنت مقدم خدمة محترف، اضغط على زر "انضم كمقدم خدمة" في الصفحة الرئيسية. سيتم توجيهك لقناة التسجيل. <strong class="text-gray-900">ملاحظة:</strong> قبولك في المنصة لا يعني تزكية أو ضمان من المنصة. أنت مسؤول بالكامل عن جودة عملك والتزاماتك تجاه العملاء.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 9 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">في أي المدن تتوفر الخدمة؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <p class="text-gray-600 leading-relaxed">
                        نوصل الطلبات في مدن رئيسية بالمملكة العربية السعودية: الرياض، جدة، الدمام، الخبر، الطائف، مكة المكرمة، المدينة المنورة، أبها، تبوك، حائل، بريدة، جازان، ونجران. نعمل باستمرار على التوسع لمناطق جديدة.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 10 - NEW -->
            <div class="faq-item bg-white border border-orange-300 rounded-xl overflow-hidden hover:border-orange-400 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-orange-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-orange-900">⚠️ إخلاء المسؤولية القانونية</span>
                    <svg class="w-5 h-5 text-orange-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <div class="bg-orange-50 border-2 border-orange-300 rounded-lg p-5">
                        <p class="text-orange-900 font-bold mb-3 text-lg">📢 تنويه قانوني هام:</p>
                        <div class="space-y-4 text-gray-700">
                            <p><strong>منصة خدمة هي وسيط إلكتروني فقط</strong> لتسهيل التواصل بين العملاء ومقدمي الخدمات المستقلين.</p>
                            
                            <div class="bg-white rounded-lg p-4 border border-orange-200">
                                <p class="font-semibold text-orange-900 mb-2">🚫 المنصة غير مسؤولة قانونياً عن:</p>
                                <ul class="list-disc list-inside space-y-1 text-sm">
                                    <li>جودة الخدمات المقدمة (ممتازة، جيدة، متوسطة، أو رديئة)</li>
                                    <li>دقة المعلومات المدخلة من قبل العملاء</li>
                                    <li>أي أضرار أو خسائر ناتجة عن تنفيذ الخدمة</li>
                                    <li>التزامات أو تصرفات مقدمي الخدمات</li>
                                    <li>أرقام هواتف خاطئة أو معلومات ناقصة</li>
                                    <li>النزاعات أو الخلافات بين الطرفين</li>
                                </ul>
                            </div>

                            <!-- لكننا نهتم بالجودة -->
                            <div class="bg-green-100 rounded-lg p-4 border-2 border-green-400">
                                <p class="font-bold text-green-900 mb-2 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    ✅ لكن التزامنا الأخلاقي تجاهك:
                                </p>
                                <ul class="list-disc list-inside space-y-1 text-sm text-green-800">
                                    <li><strong>نستقبل شكاويك</strong> عبر نظام الشكاوى أو WhatsApp</li>
                                    <li><strong>نراجع الشكاوى</strong> ونحاول الوساطة لحل المشكلات</li>
                                    <li><strong>نحذر</strong> مقدمي الخدمات الذين تتكرر عليهم الشكاوى</li>
                                    <li><strong>نفصل نهائياً</strong> مقدمي الخدمات سيئي النية أو غير المحترفين</li>
                                    <li><strong>نمنع وصول طلبات جديدة</strong> للمفصولين من شبكتنا</li>
                                </ul>
                            </div>
                            
                            <p class="text-center font-bold text-orange-800 bg-orange-100 py-3 px-4 rounded-lg border-2 border-orange-400">
                                ⚖️ المسؤولية القانونية مباشرة بين العميل ومقدم الخدمة فقط
                                <br>
                                <span class="text-sm font-normal">لكننا نعمل جاهدين لتحسين جودة الشبكة باستمرار</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// ===== VALIDATION ERROR HANDLER =====
function handleValidationErrors(errors, form) {
    // Tüm hataları temizle
    form.querySelectorAll('.field-error').forEach(el => el.remove());
    form.querySelectorAll('.border-red-500').forEach(el => {
        el.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
        el.classList.add('border-gray-300');
    });
    
    // Her hata için mesaj göster
    Object.keys(errors).forEach(fieldName => {
        const errorMessage = errors[fieldName];
        
        // Field mapping
        const fieldMap = {
            'phone': '.phone-input-primary',
            'phone_confirm': '.phone-input-confirm',
            'service_type': '.service-type-select',
            'city': 'select[name="city"]'
        };
        
        const selector = fieldMap[fieldName] || `[name="${fieldName}"]`;
        const field = form.querySelector(selector);
        
        if (field) {
            // Field'ı kırmızı yap
            field.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
            field.classList.remove('border-gray-300');
            
            // Error mesajı ekle
            const errorDiv = document.createElement('p');
            errorDiv.className = 'field-error text-red-600 text-sm mt-1 font-medium';
            errorDiv.textContent = errorMessage;
            
            const parent = field.closest('div') || field.parentElement;
            parent.appendChild(errorDiv);
            
            // İlk hataya focus
            if (Object.keys(errors)[0] === fieldName) {
                field.focus();
            }
        }
    });
}

// ===== PHONE VALIDATION HELPERS =====
function validateSaudiPhone(phone) {
    // Remove all non-digits except +
    const cleaned = phone.replace(/[^\d+]/g, '');
    
    // Suudi telefon formatları:
    // 05xxxxxxxx (10 digits)
    // 5xxxxxxxxx (9 digits)
    // +9665xxxxxxxx (13 chars)
    // 009665xxxxxxxx (14 chars)
    
    const patterns = [
        /^05[0-9]{8}$/,           // 05xxxxxxxx
        /^5[0-9]{8}$/,            // 5xxxxxxxx
        /^\+9665[0-9]{8}$/,       // +9665xxxxxxxx
        /^009665[0-9]{8}$/        // 009665xxxxxxxx
    ];
    
    return patterns.some(pattern => pattern.test(cleaned));
}

function normalizeSaudiPhone(phone) {
    let cleaned = phone.replace(/[^\d+]/g, '');
    
    // Normalize to 05xxxxxxxx format
    if (cleaned.startsWith('+9665')) {
        cleaned = '0' + cleaned.substring(4);
    } else if (cleaned.startsWith('009665')) {
        cleaned = '0' + cleaned.substring(5);
    } else if (cleaned.startsWith('9665')) {
        cleaned = '0' + cleaned.substring(3);
    } else if (cleaned.startsWith('5') && cleaned.length === 9) {
        cleaned = '0' + cleaned;
    }
    
    return cleaned;
}

function showPhoneError(input, message) {
    input.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
    input.classList.remove('border-gray-300');
    
    let errorDiv = input.parentElement.querySelector('.phone-error');
    if (!errorDiv) {
        errorDiv = document.createElement('p');
        errorDiv.className = 'phone-error text-red-600 text-sm mt-1 font-medium';
        input.parentElement.appendChild(errorDiv);
    }
    errorDiv.textContent = message;
}

function clearPhoneError(input) {
    input.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
    input.classList.add('border-gray-300');
    
    const errorDiv = input.parentElement.querySelector('.phone-error');
    if (errorDiv) errorDiv.remove();
}

// ===== SERVICE TIME SELECTION =====
document.querySelectorAll('input[name="scheduled_date"]').forEach(dateInput => {
    // When date picker is clicked/changed, auto-select the 'scheduled' radio
    dateInput.addEventListener('focus', function() {
        const form = this.closest('form');
        const scheduledRadio = form.querySelector('input[value="scheduled"][name="service_time_type"]');
        if (scheduledRadio) {
            scheduledRadio.checked = true;
            // Trigger change event to update visual state
            scheduledRadio.dispatchEvent(new Event('change'));
        }
    });
    
    dateInput.addEventListener('change', function() {
        const form = this.closest('form');
        const scheduledRadio = form.querySelector('input[value="scheduled"][name="service_time_type"]');
        if (scheduledRadio && this.value) {
            scheduledRadio.checked = true;
            scheduledRadio.dispatchEvent(new Event('change'));
        }
    });
});

// ===== FORM HANDLING =====
document.querySelectorAll('.service-request-form').forEach(form => {
    const phoneInputPrimary = form.querySelector('.phone-input-primary');
    const phoneInputConfirm = form.querySelector('.phone-input-confirm');

    // Phone input validation (real-time)
    [phoneInputPrimary, phoneInputConfirm].forEach(input => {
        if (!input) return;
        
        // Format input
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d+]/g, '');
            
            // Auto-format: if starts with 5, add 0
            if (value.length === 1 && value === '5') {
                value = '05';
            }
            
            // Limit length
            if (value.startsWith('05') || value.startsWith('5')) {
                value = value.substring(0, 10);
            } else if (value.startsWith('+966')) {
                value = value.substring(0, 13);
            }
            
            e.target.value = value;
            
            // Clear error on input
            if (value.length > 0) {
                clearPhoneError(e.target);
            }
        });
        
        // Prevent paste
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            showAlert('يرجى كتابة رقم الهاتف يدوياً', 'error');
        });
        
        // Validate on blur
        input.addEventListener('blur', function(e) {
            const value = e.target.value;
            if (value.length === 0) return; // Skip if empty
            
            if (!validateSaudiPhone(value)) {
                showPhoneError(e.target, 'رقم هاتف سعودي غير صحيح (مثال: 0501234567)');
            } else {
                clearPhoneError(e.target);
            }
        });
    });

    // Phone confirmation match check
    if (phoneInputConfirm) {
        phoneInputConfirm.addEventListener('blur', function() {
            if (phoneInputPrimary && phoneInputConfirm.value.length > 0) {
                if (phoneInputPrimary.value !== phoneInputConfirm.value) {
                    showPhoneError(phoneInputConfirm, 'رقم الهاتف غير متطابق');
                } else {
                    clearPhoneError(phoneInputConfirm);
                }
            }
        });
    }

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = form.querySelector('[data-submit-btn]');
        const btnText = submitBtn?.querySelector('.btn-text');
        const btnLoading = submitBtn?.querySelector('.btn-loading');

        // Final validation
        let hasError = false;

        // Validate primary phone
        if (phoneInputPrimary) {
            const phone = phoneInputPrimary.value;
            if (!phone || phone.length === 0) {
                showPhoneError(phoneInputPrimary, 'رقم الهاتف مطلوب');
                hasError = true;
            } else if (!validateSaudiPhone(phone)) {
                showPhoneError(phoneInputPrimary, 'رقم هاتف سعودي غير صحيح (مثال: 0501234567)');
                hasError = true;
            }
        }

        // Validate phone confirmation
        if (phoneInputConfirm) {
            const phoneConfirm = phoneInputConfirm.value;
            if (!phoneConfirm || phoneConfirm.length === 0) {
                showPhoneError(phoneInputConfirm, 'تأكيد رقم الهاتف مطلوب');
                hasError = true;
            } else if (phoneInputPrimary && phoneInputPrimary.value !== phoneConfirm) {
                showPhoneError(phoneInputConfirm, 'رقم الهاتف غير متطابق');
                hasError = true;
            }
        }

        if (hasError) {
            showAlert('يرجى تصحيح الأخطاء في النموذج', 'error');
            return;
        }

        // Show loading state
        if (submitBtn) {
            submitBtn.disabled = true;
            btnText?.classList.add('hidden');
            btnLoading?.classList.remove('hidden');
        }

        const formData = new FormData(this);

        try {
            const response = await fetch('/lead/submit', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            });

            const contentType = response.headers.get('content-type') || '';
            const responseText = await response.text();
            
            if (!response.ok) {
                let errorData;
                try {
                    errorData = JSON.parse(responseText);
                } catch {
                    throw new Error(`HTTP ${response.status}`);
                }
                
                if (errorData.errors) {
                    handleValidationErrors(errorData.errors, form);
                }
                
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            }

            if (!contentType.includes('application/json')) {
                throw new Error('Sunucudan geçersiz yanıt alındı');
            }

            const result = JSON.parse(responseText);

            if (result.success) {
                showAlert('✅ تم إرسال طلبك بنجاح! سنتواصل معك خلال دقائق', 'success');
                
                // Redirect to thanks page
                if (result.redirect) {
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1000);
                } else {
                this.reset();
                toggleOtherFields();
                form.querySelectorAll('.phone-input').forEach(clearPhoneError);
                }
            } else {
                if (result.errors) {
                    handleValidationErrors(result.errors, form);
                }
                showAlert(result.message || 'حدث خطأ أثناء إرسال الطلب', 'error');
            }
        } catch (error) {
            let errorMessage = '❌ حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى';
            if (error.message.includes('HTTP error')) {
                errorMessage = '❌ حدث خطأ في الخادم. يرجى المحاولة مرة أخرى';
            }
            showAlert(errorMessage, 'error');
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                btnText?.classList.remove('hidden');
                btnLoading?.classList.add('hidden');
            }
        }
    });
});

// ===== FAQ TOGGLE =====
function toggleFaq(button) {
    const faqItem = button.closest('.faq-item');
    const answer = faqItem.querySelector('.faq-answer');
    const icon = button.querySelector('.faq-icon');
    
    // Toggle answer visibility
    answer.classList.toggle('hidden');
    
    // Rotate icon
    if (answer.classList.contains('hidden')) {
        icon.style.transform = 'rotate(0deg)';
    } else {
        icon.style.transform = 'rotate(180deg)';
    }
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/base.php';
?>


