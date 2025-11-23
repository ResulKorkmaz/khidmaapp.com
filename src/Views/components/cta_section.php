<!-- Final CTA Section -->
<section id="request-service" class="py-12 md:py-16 relative overflow-hidden" style="background: linear-gradient(to right, #1E5A8A, #3B9DD9);">
    <!-- Decorative Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-64 md:w-96 h-64 md:h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl opacity-10"></div>
        <div class="absolute -bottom-40 -left-40 w-64 md:w-96 h-64 md:h-96 bg-blue-400 rounded-full mix-blend-overlay filter blur-3xl opacity-10"></div>
    </div>
    
    <div class="container-custom relative z-10 px-4">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-white mb-3 md:mb-4">
                هل أنت مستعد للبدء؟
            </h2>
            <p class="text-sm md:text-base lg:text-lg text-blue-50 mb-6 md:mb-8 max-w-xl mx-auto leading-relaxed">
                احصل على أفضل الخدمات من مقدمي خدمات محترفين في منطقتك. املأ النموذج الآن وسنوصل طلبك فوراً!
            </p>
            
            <!-- Service Request Form -->
            <div class="max-w-xl mx-auto bg-white rounded-xl md:rounded-2xl p-4 md:p-6 shadow-2xl text-right">
                <?php
                render_service_request_form('cta-service-form', 'cta', [
                    'compact' => true,
                    'ultra_compact' => true,
                    'include_description' => false,
                    'button_text' => 'إرسال الطلب',
                    'button_classes' => 'btn-primary w-full py-2.5 md:py-3 text-sm md:text-base relative',
                    'form_origin' => 'cta_bottom',
                    'dark_theme' => false // Form is on white background now for better contrast
                ]);
                ?>
            </div>
        </div>
    </div>
</section>

