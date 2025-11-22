<!-- Hero Section -->
<section id="hero" class="hero-dark-section relative overflow-hidden pt-16 md:pt-18 min-h-screen flex items-center">
    <!-- Modern Dark Gradient Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800"></div>
    
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <!-- Large Gradient Orbs -->
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-primary-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse" style="animation-delay: 2s;"></div>
        
        <!-- Grid Pattern Overlay -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
    </div>
    
    <div class="container-custom relative z-10 py-12 md:py-20">
        <div class="grid lg:grid-cols-2 gap-8 md:gap-12 lg:gap-16 items-center">
            <!-- Content -->
            <div class="text-center lg:text-start order-2 lg:order-1">
                <!-- Badge -->
                <div class="inline-flex items-center bg-white/10 backdrop-blur-md text-white px-4 md:px-5 py-2 md:py-2.5 rounded-full text-xs md:text-sm font-semibold mb-6 md:mb-8 border border-white/20 shadow-lg">
                    <svg class="w-3 h-3 md:w-4 md:h-4 me-2 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    خدمات موثوقة ومضمونة
                </div>
                
                <!-- Main Heading -->
                <h1 class="text-3xl md:text-4xl lg:text-5xl xl:text-6xl 2xl:text-7xl font-extrabold text-white mb-4 md:mb-6 leading-tight">
                    احصل على 
                    <span class="bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-400 bg-clip-text text-transparent animate-gradient">
                        أفضل الخدمات
                    </span>
                    <br>بأسهل طريقة
                </h1>
                
                <!-- Description -->
                <p class="text-base md:text-lg lg:text-xl text-blue-100 mb-8 md:mb-10 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                    منصة متخصصة تربط بين العملاء ومقدمي الخدمات المنزلية والتجارية الموثوقين في جميع أنحاء المملكة العربية السعودية
                </p>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center lg:justify-start mb-8 md:mb-10">
                    <a href="#request-service" 
                       class="group inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold text-base md:text-lg px-6 md:px-8 py-3 md:py-4 rounded-xl md:rounded-2xl shadow-2xl shadow-blue-500/50 hover:shadow-blue-600/50 transition-all duration-300 transform hover:scale-105">
                        <svg class="w-4 h-4 md:w-5 md:h-5 me-2 group-hover:translate-y-[-2px] transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                        </svg>
                        اطلب خدمة الآن
                    </a>
                    
                    <a href="<?= htmlspecialchars(WHATSAPP_CHANNEL_URL) ?>" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="group inline-flex items-center justify-center bg-white/10 backdrop-blur-md hover:bg-white/20 text-white font-semibold text-base md:text-lg px-6 md:px-8 py-3 md:py-4 rounded-xl md:rounded-2xl border border-white/20 hover:border-white/30 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <svg class="w-4 h-4 md:w-5 md:h-5 me-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                        </svg>
                        انضم كمقدم خدمة
                    </a>
                </div>
            </div>
            
            <!-- Form Card -->
            <div class="relative order-1 lg:order-2">
                <div class="relative mx-auto max-w-md lg:max-w-lg">
                    <!-- Glassmorphism Form Card -->
                    <div class="relative bg-white/10 backdrop-blur-2xl rounded-xl md:rounded-2xl lg:rounded-3xl p-5 md:p-6 lg:p-7 border border-white/20 shadow-2xl">
                        <!-- Card Glow Effect -->
                        <div class="absolute inset-0 rounded-xl md:rounded-2xl lg:rounded-3xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 opacity-50 blur-2xl -z-10"></div>
                        
                        <!-- Form Title -->
                        <div class="text-center mb-4 md:mb-5 lg:mb-6">
                            <h2 class="text-lg md:text-xl lg:text-2xl font-bold text-white mb-1">
                                اطلب خدمتك الآن
                            </h2>
                            <p class="text-blue-200 text-xs md:text-sm">
                                نتواصل معك خلال دقائق
                            </p>
                        </div>
                        
                        <?php
                        render_service_request_form('hero-service-form', 'hero', [
                            'compact' => true,
                            'ultra_compact' => true,
                            'button_text' => 'إرسال الطلب',
                            'button_classes' => 'btn-primary w-full text-sm md:text-base py-2.5 md:py-3 relative',
                            'form_origin' => 'hero'
                        ]);
                        ?>
                    </div>
                    
                    <!-- Decorative Elements -->
                    <div class="absolute -top-4 -right-4 w-16 h-16 md:w-20 md:h-20 bg-blue-500/30 rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-4 -left-4 w-20 h-20 md:w-24 md:h-24 bg-cyan-500/30 rounded-full blur-2xl"></div>
                </div>
            </div>
        </div>
    </div>
</section>

