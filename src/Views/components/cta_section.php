<!-- Final CTA Section -->
<section id="request-service" class="py-16 md:py-24 relative overflow-hidden" style="background: linear-gradient(to right, #1E5A8A, #3B9DD9);">
    <!-- Decorative Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-64 md:w-96 h-64 md:h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl opacity-10"></div>
        <div class="absolute -bottom-40 -left-40 w-64 md:w-96 h-64 md:h-96 bg-blue-400 rounded-full mix-blend-overlay filter blur-3xl opacity-10"></div>
    </div>
    
    <div class="container-custom relative z-10 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-white mb-4 md:mb-6">
                هل أنت مستعد للبدء؟
            </h2>
            <p class="text-base md:text-lg lg:text-xl text-blue-50 mb-8 md:mb-12 max-w-2xl mx-auto leading-relaxed">
                احصل على أفضل الخدمات من مقدمي خدمات محترفين في منطقتك. املأ النموذج الآن وسنوصل طلبك فوراً!
            </p>
            
            <!-- Service Request Form -->
            <div class="max-w-2xl mx-auto bg-white rounded-2xl md:rounded-3xl p-6 md:p-8 shadow-2xl">
                <?php
                render_service_request_form('cta-service-form', 'cta', [
                    'compact' => false,
                    'button_text' => 'إرسال الطلب',
                    'button_classes' => 'btn-primary w-full py-3 md:py-4 text-base md:text-lg relative',
                    'form_origin' => 'cta_bottom',
                    'dark_theme' => false // Form is on white background now for better contrast
                ]);
                ?>
            </div>
        </div>
    </div>
</section>

