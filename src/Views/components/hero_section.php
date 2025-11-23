<!-- Hero Section -->
<section id="hero" class="relative overflow-hidden min-h-[85vh] flex items-center pt-24 md:pt-28" style="background: linear-gradient(135deg, #1E5A8A 0%, #2B7AB8 50%, #3B9DD9 100%);">
    
    <!-- Pattern Overlay (Optional) -->
    <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

    <div class="container-custom relative z-10 py-8 md:py-12">
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-16 items-center">
            
            <!-- LEFT COLUMN (Desktop): Text Content -->
            <div class="text-center lg:text-right order-1 lg:order-1">
                
                <!-- Trust Badge -->
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full px-4 py-1.5 mb-6 mx-auto lg:mx-0">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span class="text-white text-sm font-medium">منصة الخدمات رقم #1 في السعودية</span>
                </div>

                <!-- Main Headline -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6 drop-shadow-md">
                    خدمات منزلك <br>
                    <span class="text-blue-200">بأيدي أمينة</span>
                </h1>

                <!-- Subheadline -->
                <p class="text-lg md:text-xl text-white/90 mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-light">
                    نربطك بأفضل المهنيين المعتمدين لجميع خدمات الصيانة المنزلية. 
                    جودة مضمونة، أسعار شفافة، وراحة بال تامة.
                </p>

                <!-- Stats -->
                <div class="flex items-center justify-center lg:justify-start gap-8 border-t border-white/10 pt-8">
                    <div class="text-center lg:text-right">
                        <p class="text-3xl font-bold text-white">5000+</p>
                        <p class="text-blue-100 text-sm">عميل سعيد</p>
                    </div>
                    <div class="w-px h-10 bg-white/20"></div>
                    <div class="text-center lg:text-right">
                        <p class="text-3xl font-bold text-white">200+</p>
                        <p class="text-blue-100 text-sm">مهني محترف</p>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN (Desktop): Form -->
            <div class="order-2 lg:order-2">
                <!-- Form Container (Compact) -->
                <div class="bg-white rounded-2xl p-4 md:p-5 shadow-2xl border-2 border-white/10 max-w-md mx-auto">
                    
                    <div class="text-center mb-3">
                        <h3 class="text-lg font-bold text-gray-800 mb-1">طلب خدمة سريع</h3>
                        <p class="text-gray-500 text-[11px]">املأ النموذج وسيصلك أفضل العروض</p>
                    </div>

                    <?php
                    // Form Helper çağrısı - ultra_compact mode with description
                    render_service_request_form('hero-service-form', 'hero', [
                        'compact' => true,
                        'ultra_compact' => true,
                        'include_description' => true, // Açıklama alanı açık (hazır mesajlar için gerekli)
                        'button_text' => 'إرسال الطلب',
                        'button_classes' => 'w-full bg-[#1E5A8A] text-white hover:bg-[#165080] font-bold py-3 rounded-xl shadow-lg transition-all duration-300 text-sm',
                        'form_origin' => 'hero',
                        'dark_theme' => false
                    ]);
                    ?>
                </div>
            </div>
            
        </div>
    </div>
</section>
