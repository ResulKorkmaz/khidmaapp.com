<!-- Services Section -->
<section id="services" class="section-padding relative overflow-hidden">
    <!-- Modern Background with Gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-indigo-50"></div>
    
    <!-- Decorative Elements -->
    <div class="absolute top-0 left-0 w-64 md:w-96 h-64 md:h-96 bg-blue-200/20 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-64 md:w-96 h-64 md:h-96 bg-indigo-200/20 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
    
    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(circle, #000 1px, transparent 1px); background-size: 40px 40px;"></div>
    
    <div class="container-custom relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-12 md:mb-16">
            <div class="inline-block mb-3 md:mb-4">
                <span class="text-xs md:text-sm font-semibold text-blue-600 bg-blue-50 px-3 md:px-4 py-1.5 md:py-2 rounded-full">
                    خدماتنا
                </span>
            </div>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 md:mb-6">
                خدماتنا المتخصصة
            </h2>
            <p class="text-base md:text-lg lg:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed px-4">
                نقدم مجموعة شاملة من الخدمات المنزلية والتجارية بأعلى معايير الجودة والاحترافية
            </p>
        </div>
        
        <!-- Services Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8 px-4">
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
                <div class="relative h-full bg-white/80 backdrop-blur-xl rounded-2xl md:rounded-3xl p-5 md:p-8 border border-gray-200/50 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 overflow-hidden">
                    <!-- Hover Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-br <?= $serviceData['color'] ?> opacity-0 group-hover:opacity-5 transition-opacity duration-500 rounded-2xl md:rounded-3xl"></div>
                    
                    <!-- Service Image -->
                    <?php if (isset($serviceData['image'])): ?>
                    <div class="relative mb-5 md:mb-6 h-40 md:h-48 rounded-xl md:rounded-2xl overflow-hidden bg-gradient-to-br <?= $serviceData['color'] ?>">
                        <img src="/assets/images/<?= htmlspecialchars($serviceData['image']) ?>" 
                             alt="<?= htmlspecialchars($service['ar']) ?>"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                             loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        <!-- Icon Overlay -->
                        <div class="absolute top-3 md:top-4 left-3 md:left-4 w-12 h-12 md:w-14 md:h-14 bg-white/90 backdrop-blur-sm rounded-lg md:rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <svg class="w-6 h-6 md:w-8 md:h-8 text-gray-800" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="<?= $serviceData['viewBox'] ?? '0 0 24 24' ?>">
                                <path d="<?= $serviceData['icon'] ?>"/>
                            </svg>
                        </div>
                    </div>
                    <?php else: ?>
                    <!-- Icon Container (Fallback if no image) -->
                    <div class="relative mb-5 md:mb-6">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br <?= $serviceData['color'] ?> rounded-xl md:rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <svg class="w-10 h-10 md:w-12 md:h-12 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="<?= $serviceData['viewBox'] ?? '0 0 24 24' ?>">
                                <path d="<?= $serviceData['icon'] ?>"/>
                            </svg>
                        </div>
                        <!-- Decorative Circle -->
                        <div class="absolute -top-2 -right-2 w-6 h-6 md:w-8 md:h-8 <?= $serviceData['bgColor'] ?> rounded-full border-2 <?= $serviceData['borderColor'] ?> opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Content -->
                    <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3 md:mb-4 group-hover:text-blue-600 transition-colors duration-300">
                        <?= htmlspecialchars($service['ar']) ?>
                    </h3>
                    
                    <p class="text-gray-600 mb-5 md:mb-6 leading-relaxed text-sm md:text-base">
                        <?= $serviceData['desc'] ?>
                    </p>
                    
                    <!-- CTA Button -->
                    <a href="/services/<?= htmlspecialchars($key) ?>" 
                       class="inline-flex items-center justify-center gap-2 bg-[#3B9DD9] hover:bg-[#2B7AB8] text-white font-bold text-sm md:text-base px-6 py-3 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <span>طلب الخدمة</span>
                        <svg class="w-4 h-4 md:w-5 md:h-5 group-hover:translate-x-1 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

