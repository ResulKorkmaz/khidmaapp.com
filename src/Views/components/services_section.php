<!-- Services Section -->
<section id="services" class="py-20 md:py-32 bg-gray-50 relative overflow-hidden">
    
    <div class="container-custom relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-2 bg-[#3B9DD9]/10 border border-[#3B9DD9]/20 px-4 py-2 rounded-full mb-6">
                <span class="w-2 h-2 rounded-full bg-[#3B9DD9]"></span>
                <span class="text-sm font-bold text-[#1E5A8A]">
                    خدماتنا الاحترافية
                </span>
            </div>
            <h2 class="text-3xl md:text-5xl font-black text-gray-900 mb-6">
                كل ما تحتاجه في مكان واحد
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                نقدم مجموعة شاملة من الخدمات المنزلية بأعلى معايير الجودة. اختر الخدمة التي تحتاجها واترك الباقي علينا.
            </p>
        </div>
        
        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php 
            $serviceIcons = [
                'paint' => [
                    'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
                    'image' => 'paint.jpg',
                    'desc' => 'دهانات داخلية وخارجية بأحدث التقنيات'
                ],
                'renovation' => [
                    'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                    'image' => 'renovation.jpg',
                    'desc' => 'ترميم وتجديد المنازل والمكاتب'
                ],
                'plumbing' => [
                    'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                    'image' => 'plumbing.png',
                    'desc' => 'إصلاح وصيانة أنظمة السباكة'
                ],
                'electric' => [
                    'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
                    'image' => 'electric.jpg',
                    'desc' => 'خدمات كهربائية شاملة وآمنة'
                ],
                'cleaning' => [
                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    'image' => 'cleaning.webp',
                    'desc' => 'تنظيف شامل للمنازل والمكاتب'
                ],
                'ac' => [
                    'icon' => 'M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z',
                    'image' => 'ac.jpg',
                    'desc' => 'صيانة وتركيب أنظمة التكييف'
                ]
            ];
            
            foreach (getServiceTypes() as $key => $service): 
                $serviceData = $serviceIcons[$key] ?? $serviceIcons['paint'];
            ?>
            <!-- Service Card -->
            <div class="group bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-gray-100 flex flex-col">
                
                <!-- Image Container - Fixed Height -->
                <div class="relative w-full h-64 overflow-hidden flex-shrink-0 bg-gray-100">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent z-10"></div>
                    <img src="/assets/images/<?= htmlspecialchars($serviceData['image']) ?>" 
                         alt="<?= htmlspecialchars($service['ar']) ?>"
                         class="absolute inset-0 w-full h-full object-cover object-center transform group-hover:scale-110 transition-transform duration-700"
                         loading="lazy">
                    
                    <!-- Icon Badge -->
                    <div class="absolute bottom-4 right-4 z-20 w-14 h-14 bg-white rounded-xl flex items-center justify-center shadow-xl">
                        <svg class="w-7 h-7 text-[#3B9DD9]" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="<?= $serviceData['icon'] ?>"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Content Area - Flexible Height -->
                <div class="p-6 flex flex-col flex-grow">
                    <!-- Title -->
                    <h3 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-[#3B9DD9] transition-colors">
                        <?= htmlspecialchars($service['ar']) ?>
                    </h3>
                    
                    <!-- Description -->
                    <p class="text-gray-600 leading-relaxed mb-6 flex-grow">
                        <?= $serviceData['desc'] ?>
                    </p>
                    
                    <!-- Action Button - Always at Bottom -->
                    <a href="/services/<?= htmlspecialchars($key) ?>" 
                       class="flex items-center justify-between w-full px-6 py-4 rounded-xl font-bold text-base transition-all duration-300 shadow-sm" 
                       style="background-color: #3B9DD9; color: white;">
                        <span>طلب الخدمة</span>
                        <svg class="w-5 h-5 transform rotate-180 group-hover:-translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
