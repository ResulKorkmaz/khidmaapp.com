<?php
/**
 * Form Helper Functions
 * 
 * Service request form rendering helper
 */

if (!function_exists('render_service_request_form')) {
    /**
     * Render the service request form.
     *
     * @param string $formId      Unique form id attribute (must be unique per page)
     * @param string $fieldPrefix Prefix for input ids to keep them unique
     * @param array  $options     Additional options (compact, include_description, button_text, button_classes, form_origin, preselected_service)
     */
    function render_service_request_form(string $formId, string $fieldPrefix, array $options = []): void
    {
        $compact = $options['compact'] ?? false;
        $ultraCompact = $options['ultra_compact'] ?? false;
        $includeDescription = $options['include_description'] ?? true;
        $buttonText = $options['button_text'] ?? 'إرسال الطلب';
        $buttonClasses = $options['button_classes'] ?? 'btn-primary w-full text-lg py-4 relative';
        $formOrigin = $options['form_origin'] ?? $fieldPrefix;
        $preselectedService = $options['preselected_service'] ?? null;
        $darkTheme = $options['dark_theme'] ?? false; // Koyu tema için beyaz yazı, açık tema için koyu yazı
        
        // Ultra compact için daha minimal ayarlar
        $spacingClass = $ultraCompact ? 'space-y-3' : ($compact ? 'space-y-4' : 'space-y-6');
        $labelSpacing = $ultraCompact ? 'mb-1.5' : ($compact ? 'mb-2' : 'mb-3');
        $textareaHeight = $ultraCompact ? 'h-20' : ($compact ? 'h-24' : 'h-32');
        $labelClasses = $ultraCompact ? 'text-xs' : 'text-sm';
        
        // Tema renkleri
        $labelColor = $darkTheme ? 'text-white' : 'text-gray-900';
        $textColor = $darkTheme ? 'text-gray-100' : 'text-gray-600';
        $inputBg = $darkTheme ? 'bg-white/10' : 'bg-gray-50';
        $inputBorder = $darkTheme ? 'border-white/20' : 'border-gray-300';
        $inputText = $darkTheme ? 'text-white' : 'text-gray-900';
        $inputPlaceholder = $darkTheme ? 'placeholder-gray-400' : 'placeholder-gray-400';
        $linkColor = $darkTheme ? 'text-blue-300 hover:text-blue-200' : 'text-blue-600 hover:text-blue-800';
        
        // Input base classes
        $inputClasses = "form-input w-full rounded-xl {$inputBg} {$inputBorder} {$inputText} {$inputPlaceholder} focus:border-[#10b981] focus:ring-[#10b981] transition-all duration-200";
        ?>
        <form id="<?= htmlspecialchars($formId) ?>" method="POST" action="/lead/submit"
              class="service-request-form <?= $spacingClass ?>">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="form_origin" value="<?= htmlspecialchars($formOrigin) ?>">
            
            <!-- Honeypot field for spam protection (hidden from real users) -->
            <input type="text" 
                   name="website" 
                   id="<?= $fieldPrefix ?>_website" 
                   value="" 
                   autocomplete="off"
                   tabindex="-1"
                   style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0;pointer-events:none;"
                   aria-hidden="true">

            <!-- Service Type -->
            <div>
                <label for="<?= $fieldPrefix ?>_service_type" class="block <?= $labelClasses ?> font-bold <?= $labelColor ?> <?= $labelSpacing ?>">
                    نوع الخدمة *
                </label>
                <div class="relative">
                    <select id="<?= $fieldPrefix ?>_service_type" name="service_type" required 
                            class="<?= $inputClasses ?> py-3 appearance-none service-type-select">
                        <option value="" class="text-gray-500">اختر الخدمة</option>
                        <?php foreach (getServiceTypes() as $key => $service): ?>
                            <option value="<?= htmlspecialchars($key) ?>" <?= ($preselectedService === $key) ? 'selected' : '' ?> class="text-gray-900 bg-white">
                                <?= htmlspecialchars($service['ar']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- City -->
            <div>
                <label for="<?= $fieldPrefix ?>_city" class="block <?= $labelClasses ?> font-bold <?= $labelColor ?> <?= $labelSpacing ?>">
                    المدينة *
                </label>
                <div class="relative">
                    <select id="<?= $fieldPrefix ?>_city" name="city" required 
                            class="<?= $inputClasses ?> py-3 appearance-none">
                        <option value="" class="text-gray-500">اختر المدينة</option>
                        <?php foreach (getCities() as $key => $city): ?>
                            <option value="<?= htmlspecialchars($key) ?>" class="text-gray-900 bg-white">
                                <?= htmlspecialchars($city['ar']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Phone -->
            <div>
                <label for="<?= $fieldPrefix ?>_phone" class="block <?= $labelClasses ?> font-bold <?= $labelColor ?> <?= $labelSpacing ?>">
                    رقم الهاتف *
                </label>
                <div class="relative">
                    <input type="tel"
                           id="<?= $fieldPrefix ?>_phone"
                           name="phone"
                           required
                           class="<?= $inputClasses ?> py-3 phone-input phone-input-primary text-right pr-3 pl-10"
                           placeholder="05xxxxxxxx"
                           inputmode="numeric"
                           dir="rtl"
                           maxlength="12"
                           pattern="[0-9]*">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3">
                        <span class="text-gray-500">📱</span>
                    </div>
                </div>
            </div>

            <!-- Phone Confirmation -->
            <div>
                <label for="<?= $fieldPrefix ?>_phone_confirm" class="block <?= $labelClasses ?> font-bold <?= $labelColor ?> <?= $labelSpacing ?>">
                    تأكيد الهاتف *
                </label>
                <div class="relative">
                    <input type="tel"
                           id="<?= $fieldPrefix ?>_phone_confirm"
                           name="phone_confirm"
                           required
                           class="<?= $inputClasses ?> py-3 phone-input phone-input-confirm text-right pr-3 pl-10"
                           placeholder="أعد كتابة الرقم"
                           inputmode="numeric"
                           dir="rtl"
                           maxlength="12"
                           pattern="[0-9]*">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3">
                        <span class="text-gray-500">✔️</span>
                    </div>
                </div>
            </div>

            <!-- Service Time (Minimal Single Row) -->
            <div>
                <label class="block <?= $labelClasses ?> font-bold <?= $labelColor ?> <?= $labelSpacing ?>">
                    وقت الخدمة *
                </label>
                <div class="grid grid-cols-3 gap-2">
                    <!-- Urgent -->
                    <label class="service-time-btn cursor-pointer group">
                        <input type="radio" 
                               name="service_time_type" 
                               value="urgent" 
                               id="<?= $fieldPrefix ?>_time_urgent"
                               class="service-time-radio sr-only" 
                               required>
                        <div class="service-time-label py-3 px-2 border-2 <?= $darkTheme ? 'border-white/20' : 'border-gray-200' ?> rounded-xl text-center group-hover:border-red-400 transition-all bg-white/5">
                            <p class="text-sm font-bold <?= $darkTheme ? 'text-white' : 'text-gray-700' ?>">⚡ عاجل</p>
                        </div>
                    </label>
                    
                    <!-- Within 24h -->
                    <label class="service-time-btn cursor-pointer group">
                        <input type="radio" 
                               name="service_time_type" 
                               value="within_24h" 
                               id="<?= $fieldPrefix ?>_time_24h"
                               class="service-time-radio sr-only" 
                               required>
                        <div class="service-time-label py-3 px-2 border-2 <?= $darkTheme ? 'border-white/20' : 'border-gray-200' ?> rounded-xl text-center group-hover:border-blue-400 transition-all bg-white/5">
                            <p class="text-sm font-bold <?= $darkTheme ? 'text-white' : 'text-gray-700' ?>">⏰ 24 ساعة</p>
                        </div>
                    </label>
                    
                    <!-- Scheduled (Date Picker) -->
                    <label class="service-time-btn cursor-pointer group">
                        <input type="radio" 
                               name="service_time_type" 
                               value="scheduled" 
                               id="<?= $fieldPrefix ?>_time_scheduled"
                               class="service-time-radio sr-only" 
                               required>
                        <div class="service-time-label py-3 px-2 border-2 <?= $darkTheme ? 'border-white/20' : 'border-gray-200' ?> rounded-xl text-center group-hover:border-green-400 transition-all bg-white/5">
                            <input type="date" 
                                   id="<?= $fieldPrefix ?>_scheduled_date"
                                   name="scheduled_date" 
                                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                   max="<?= date('Y-m-d', strtotime('+90 days')) ?>"
                                   class="w-full bg-transparent <?= $darkTheme ? 'text-white' : 'text-gray-700' ?> text-sm font-bold text-center border-0 focus:outline-none cursor-pointer p-0"
                                   dir="rtl"
                                   lang="ar-SA"
                                   placeholder="📅 تاريخ">
                        </div>
                    </label>
                </div>
            </div>

            <!-- Description -->
            <?php if ($includeDescription): ?>
                <div>
                    <label for="<?= $fieldPrefix ?>_description" class="block <?= $labelClasses ?> font-bold <?= $labelColor ?> <?= $labelSpacing ?>">
                        وصف الخدمة
                    </label>
                    <textarea id="<?= $fieldPrefix ?>_description"
                              name="description"
                              dir="rtl"
                              class="<?= $inputClasses ?> <?= $textareaHeight ?> p-3 text-right"
                              placeholder="<?= $ultraCompact ? 'التفاصيل...' : 'اكتب تفاصيل أكثر...' ?>"></textarea>
                </div>
            <?php endif; ?>

            <!-- Terms & Privacy Notice -->
            <div class="<?= $ultraCompact ? 'pt-1' : 'pt-2' ?>">
                <p class="text-xs <?= $textColor ?> leading-relaxed <?= $ultraCompact ? 'text-[10px] lg:text-xs' : '' ?>">
                    بإرسالك توافق على 
                    <a href="/privacy" target="_blank" class="<?= $linkColor ?> underline underline-offset-2 transition-colors font-semibold">
                        سياسة الخصوصية
                    </a>
                    و
                    <a href="/terms" target="_blank" class="<?= $linkColor ?> underline underline-offset-2 transition-colors font-semibold">
                        الشروط
                    </a>
                </p>
            </div>

            <!-- Submit Button -->
            <div class="<?= $ultraCompact ? 'pt-2' : 'pt-4' ?>">
                <button type="submit" class="<?= $buttonClasses ?> shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 font-bold rounded-xl" data-submit-btn>
                    <span class="btn-text"><?= $buttonText ?></span>
                    <span class="btn-loading hidden flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        جاري الإرسال...
                    </span>
                </button>
            </div>
        </form>
        
        <!-- Custom Styles for Service Time Radio -->
        <style>
            .service-time-radio:checked + .service-time-label {
                border-color: #10b981;
                background-color: <?= $darkTheme ? 'rgba(16, 185, 129, 0.2)' : '#ecfdf5' ?>;
                color: <?= $darkTheme ? '#ffffff' : '#047857' ?>;
            }
            .service-time-radio:checked + .service-time-label p,
            .service-time-radio:checked + .service-time-label input {
                color: <?= $darkTheme ? '#ffffff' : '#047857' ?> !important;
            }
        </style>
        
        <!-- Auto-fill Service Description Script -->
        <script>
        (function() {
            const formId = '<?= $formId ?>';
            const fieldPrefix = '<?= $fieldPrefix ?>';
            const serviceSelect = document.getElementById(fieldPrefix + '_service_type');
            const descriptionTextarea = document.getElementById(fieldPrefix + '_description');
            
            if (!serviceSelect || !descriptionTextarea) return;
            
            let userModifiedDescription = false;
            
            // Hazır mesajlar (Auto-fill messages)
            const serviceMessages = {
                'paint': 'أحتاج إلى دهان غرفة أو أكثر في المنزل',
                'renovation': 'أحتاج إلى ترميم وتجديد',
                'cleaning': 'أحتاج إلى تنظيف شامل للمنزل',
                'ac': 'يوجد مشكلة في المكيف - يحتاج صيانة أو تنظيف',
                'plumbing': 'يوجد تسريب مياه أو مشكلة في السباكة تحتاج إصلاح',
                'electric': 'يوجد مشكلة في الكهرباء تحتاج إصلاح'
            };
            
            // Kullanıcı manuel değişiklik yaptığında işaretle
            descriptionTextarea.addEventListener('input', function() {
                userModifiedDescription = true;
            });
            
            // Servis seçildiğinde otomatik doldur
            serviceSelect.addEventListener('change', function() {
                const selectedService = this.value;
                
                if (serviceMessages[selectedService] && !userModifiedDescription) {
                    descriptionTextarea.value = serviceMessages[selectedService];
                    
                    // Görsel feedback (mavi ring)
                    descriptionTextarea.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50');
                    setTimeout(() => {
                        descriptionTextarea.classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50');
                    }, 1000);
                } else if (!selectedService && !userModifiedDescription) {
                    descriptionTextarea.value = '';
                }
                
                // Reset flag after auto-fill
                userModifiedDescription = false;
            });
            
            // Sayfa yüklendiğinde eğer servis seçiliyse otomatik doldur
            if (serviceSelect.value && serviceMessages[serviceSelect.value] && !userModifiedDescription) {
                descriptionTextarea.value = serviceMessages[serviceSelect.value];
            }
        })();
        </script>
        <?php
    }
}
