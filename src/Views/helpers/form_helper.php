<?php
/**
 * Form Helper Functions
 * 
 * Service request form rendering helper
 * Enhanced with real-time validation, modern UI, and better UX
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
        $textareaHeight = $ultraCompact ? 'h-16' : ($compact ? 'h-20' : 'h-28');
        $labelClasses = $ultraCompact ? 'text-xs' : 'text-sm';
        
        // Tema renkleri
        $labelColor = $darkTheme ? 'text-white' : 'text-gray-800';
        $textColor = $darkTheme ? 'text-gray-100' : 'text-gray-500';
        $inputBg = $darkTheme ? 'bg-white/10' : 'bg-white';
        $inputBorder = $darkTheme ? 'border-white/20' : 'border-gray-200';
        $inputText = $darkTheme ? 'text-white' : 'text-gray-900';
        $inputPlaceholder = $darkTheme ? 'placeholder-gray-400' : 'placeholder-gray-400';
        $linkColor = $darkTheme ? 'text-blue-300 hover:text-blue-200' : 'text-[#3B9DD9] hover:text-[#2B7AB8]';
        
        // Input base classes - modernized
        $inputClasses = "form-input w-full rounded-xl {$inputBg} border-2 {$inputBorder} {$inputText} {$inputPlaceholder} focus:border-[#3B9DD9] focus:ring-2 focus:ring-[#3B9DD9]/20 hover:border-gray-300 transition-all duration-200 shadow-sm";
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
                <div class="relative group">
                    <input type="tel"
                           id="<?= $fieldPrefix ?>_phone"
                           name="phone"
                           required
                           class="<?= $inputClasses ?> py-3 phone-input phone-input-primary text-right pr-3 pl-12"
                           placeholder="05xxxxxxxx"
                           inputmode="numeric"
                           dir="rtl"
                           maxlength="10"
                           pattern="05[0-9]{8}"
                           autocomplete="tel">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#3B9DD9] to-[#2B7AB8] flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                    </div>
                    <!-- Validation indicator -->
                    <div id="<?= $fieldPrefix ?>_phone_status" class="absolute inset-y-0 right-3 flex items-center pointer-events-none opacity-0 transition-opacity duration-200">
                        <svg class="w-5 h-5 text-green-500 phone-valid hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <svg class="w-5 h-5 text-red-500 phone-invalid hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <p id="<?= $fieldPrefix ?>_phone_hint" class="text-xs mt-1 <?= $textColor ?> opacity-0 transition-opacity duration-200"></p>
            </div>

            <!-- Phone Confirmation -->
            <div>
                <label for="<?= $fieldPrefix ?>_phone_confirm" class="block <?= $labelClasses ?> font-bold <?= $labelColor ?> <?= $labelSpacing ?>">
                    تأكيد الهاتف *
                </label>
                <div class="relative group">
                    <input type="tel"
                           id="<?= $fieldPrefix ?>_phone_confirm"
                           name="phone_confirm"
                           required
                           class="<?= $inputClasses ?> py-3 phone-input phone-input-confirm text-right pr-3 pl-12"
                           placeholder="أعد كتابة الرقم"
                           inputmode="numeric"
                           dir="rtl"
                           maxlength="10"
                           pattern="05[0-9]{8}"
                           autocomplete="tel">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <div id="<?= $fieldPrefix ?>_confirm_icon" class="w-8 h-8 rounded-lg bg-gray-200 flex items-center justify-center shadow-sm transition-all duration-300">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <!-- Match indicator -->
                    <div id="<?= $fieldPrefix ?>_confirm_status" class="absolute inset-y-0 right-3 flex items-center pointer-events-none opacity-0 transition-opacity duration-200">
                        <svg class="w-5 h-5 text-green-500 confirm-match hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <svg class="w-5 h-5 text-red-500 confirm-mismatch hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <p id="<?= $fieldPrefix ?>_confirm_hint" class="text-xs mt-1 <?= $textColor ?> opacity-0 transition-opacity duration-200"></p>
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
                        <div class="service-time-label py-3 px-2 border-2 <?= $darkTheme ? 'border-white/20' : 'border-gray-200' ?> rounded-xl text-center group-hover:border-red-400 group-hover:bg-red-50 transition-all duration-200 bg-white shadow-sm">
                            <div class="flex items-center justify-center gap-1">
                                <span class="text-lg">⚡</span>
                                <p class="text-sm font-bold <?= $darkTheme ? 'text-white' : 'text-gray-700' ?>">عاجل</p>
                            </div>
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
                        <div class="service-time-label py-3 px-2 border-2 <?= $darkTheme ? 'border-white/20' : 'border-gray-200' ?> rounded-xl text-center group-hover:border-blue-400 group-hover:bg-blue-50 transition-all duration-200 bg-white shadow-sm">
                            <div class="flex items-center justify-center gap-1">
                                <span class="text-lg">⏰</span>
                                <p class="text-sm font-bold <?= $darkTheme ? 'text-white' : 'text-gray-700' ?>">24 ساعة</p>
                            </div>
                        </div>
                    </label>
                    
                    <!-- Scheduled (Date Picker) -->
                    <label class="service-time-btn cursor-pointer group relative">
                        <input type="radio" 
                               name="service_time_type" 
                               value="scheduled" 
                               id="<?= $fieldPrefix ?>_time_scheduled"
                               class="service-time-radio sr-only" 
                               required>
                        <div class="service-time-label py-3 px-2 border-2 <?= $darkTheme ? 'border-white/20' : 'border-gray-200' ?> rounded-xl text-center group-hover:border-emerald-400 group-hover:bg-emerald-50 transition-all duration-200 bg-white shadow-sm">
                            <div class="flex items-center justify-center gap-1">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <input type="date" 
                                       id="<?= $fieldPrefix ?>_scheduled_date"
                                       name="scheduled_date" 
                                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                       max="<?= date('Y-m-d', strtotime('+90 days')) ?>"
                                       class="w-16 bg-transparent <?= $darkTheme ? 'text-white' : 'text-gray-700' ?> text-xs font-bold text-center border-0 focus:outline-none cursor-pointer p-0"
                                       dir="rtl"
                                       lang="ar-SA"
                                       title="اختر تاريخ">
                            </div>
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
                <button type="submit" 
                        id="<?= $fieldPrefix ?>_submit_btn"
                        class="w-full py-4 px-6 rounded-xl font-bold text-white text-lg
                               bg-gradient-to-r from-[#1E5A8A] via-[#2B7AB8] to-[#3B9DD9]
                               hover:from-[#165080] hover:via-[#2570A8] hover:to-[#3590C9]
                               shadow-lg hover:shadow-xl hover:shadow-[#3B9DD9]/30
                               transform hover:-translate-y-0.5 active:translate-y-0
                               transition-all duration-300 ease-out
                               disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none
                               relative overflow-hidden group" 
                        data-submit-btn>
                    <!-- Shimmer effect -->
                    <div class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                    
                    <span class="btn-text flex items-center justify-center gap-2 relative z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        <?= $buttonText ?>
                    </span>
                    <span class="btn-loading hidden items-center justify-center gap-2 relative z-10">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
                border-color: #3B9DD9 !important;
                background: linear-gradient(135deg, rgba(59, 157, 217, 0.1), rgba(43, 122, 184, 0.15)) !important;
                box-shadow: 0 4px 12px rgba(59, 157, 217, 0.2);
            }
            .service-time-radio:checked + .service-time-label p,
            .service-time-radio:checked + .service-time-label input {
                color: #1E5A8A !important;
                font-weight: 700 !important;
            }
            
            /* Input focus glow effect */
            .form-input:focus {
                box-shadow: 0 0 0 3px rgba(59, 157, 217, 0.15), 0 4px 12px rgba(59, 157, 217, 0.1);
            }
            
            /* Valid/Invalid states */
            .form-input.input-valid {
                border-color: #10b981 !important;
                background-color: rgba(16, 185, 129, 0.05);
            }
            .form-input.input-invalid {
                border-color: #ef4444 !important;
                background-color: rgba(239, 68, 68, 0.05);
            }
            
            /* Toast animations - RTL friendly (slide from right) */
            @keyframes slideInFromRight {
                from { transform: translateX(120%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutToRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(120%); opacity: 0; }
            }
            .toast-enter { animation: slideInFromRight 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
            .toast-exit { animation: slideOutToRight 0.3s ease-in forwards; }
            
            /* Progress bar animation */
            @keyframes progressShrink {
                from { width: 100%; }
                to { width: 0%; }
            }
        </style>
        
        <!-- Enhanced Form Script with Validation, Toast & UX -->
        <script>
        (function() {
            const formId = '<?= $formId ?>';
            const fieldPrefix = '<?= $fieldPrefix ?>';
            const form = document.getElementById(formId);
            const serviceSelect = document.getElementById(fieldPrefix + '_service_type');
            const descriptionTextarea = document.getElementById(fieldPrefix + '_description');
            const phoneInput = document.getElementById(fieldPrefix + '_phone');
            const phoneConfirmInput = document.getElementById(fieldPrefix + '_phone_confirm');
            const phoneStatus = document.getElementById(fieldPrefix + '_phone_status');
            const phoneHint = document.getElementById(fieldPrefix + '_phone_hint');
            const confirmStatus = document.getElementById(fieldPrefix + '_confirm_status');
            const confirmHint = document.getElementById(fieldPrefix + '_confirm_hint');
            const confirmIcon = document.getElementById(fieldPrefix + '_confirm_icon');
            const submitBtn = document.getElementById(fieldPrefix + '_submit_btn');
            
            if (!form) return;
            
            let userModifiedDescription = false;
            
            // ===== TOAST NOTIFICATION SYSTEM =====
            const ToastManager = {
                container: null,
                
                init() {
                    if (!this.container) {
                        this.container = document.createElement('div');
                        this.container.id = 'toast-container';
                        this.container.className = 'fixed bottom-4 right-4 z-[99999] flex flex-col gap-3 max-w-sm';
                        this.container.style.cssText = 'direction: rtl;';
                        document.body.appendChild(this.container);
                    }
                },
                
                show(message, type = 'info', duration = 5000) {
                    this.init();
                    
                    const toast = document.createElement('div');
                    toast.className = 'toast-enter rounded-xl shadow-2xl p-4 flex items-start gap-3 backdrop-blur-sm';
                    
                    const configs = {
                        success: {
                            bg: 'bg-gradient-to-r from-emerald-500 to-green-600',
                            icon: '<svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>'
                        },
                        error: {
                            bg: 'bg-gradient-to-r from-red-500 to-rose-600',
                            icon: '<svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>'
                        },
                        warning: {
                            bg: 'bg-gradient-to-r from-amber-500 to-orange-600',
                            icon: '<svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>'
                        },
                        info: {
                            bg: 'bg-gradient-to-r from-[#3B9DD9] to-[#2B7AB8]',
                            icon: '<svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>'
                        }
                    };
                    
                    const config = configs[type] || configs.info;
                    toast.classList.add(...config.bg.split(' '));
                    
                    toast.innerHTML = `
                        <div class="flex-shrink-0">${config.icon}</div>
                        <div class="flex-1">
                            <p class="text-white font-medium text-sm" style="direction: rtl;">${message}</p>
                        </div>
                        <button class="flex-shrink-0 text-white/80 hover:text-white transition-colors" onclick="this.closest('.toast-enter, .toast-exit').remove()">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    `;
                    
                    // Progress bar
                    const progress = document.createElement('div');
                    progress.className = 'absolute bottom-0 left-0 h-1 bg-white/30 rounded-b-xl';
                    progress.style.cssText = `width: 100%; animation: progressShrink ${duration}ms linear forwards;`;
                    toast.style.position = 'relative';
                    toast.appendChild(progress);
                    
                    this.container.appendChild(toast);
                    
                    // Auto remove
                    setTimeout(() => {
                        toast.classList.remove('toast-enter');
                        toast.classList.add('toast-exit');
                        setTimeout(() => toast.remove(), 300);
                    }, duration);
                    
                    return toast;
                }
            };
            
            // Global toast function
            window.showToast = ToastManager.show.bind(ToastManager);
            
            // ===== PHONE VALIDATION =====
            function validateSaudiPhone(phone) {
                const cleaned = phone.replace(/\s+/g, '');
                return /^05[0-9]{8}$/.test(cleaned);
            }
            
            function updatePhoneValidation() {
                if (!phoneInput || !phoneStatus) return;
                
                const phone = phoneInput.value.trim();
                const validIcon = phoneStatus.querySelector('.phone-valid');
                const invalidIcon = phoneStatus.querySelector('.phone-invalid');
                
                if (phone.length === 0) {
                    phoneStatus.style.opacity = '0';
                    phoneInput.classList.remove('input-valid', 'input-invalid');
                    phoneHint.style.opacity = '0';
                    return;
                }
                
                phoneStatus.style.opacity = '1';
                
                if (validateSaudiPhone(phone)) {
                    validIcon.classList.remove('hidden');
                    invalidIcon.classList.add('hidden');
                    phoneInput.classList.add('input-valid');
                    phoneInput.classList.remove('input-invalid');
                    phoneHint.textContent = '✓ رقم صحيح';
                    phoneHint.classList.remove('text-red-500');
                    phoneHint.classList.add('text-green-500');
                } else {
                    validIcon.classList.add('hidden');
                    invalidIcon.classList.remove('hidden');
                    phoneInput.classList.add('input-invalid');
                    phoneInput.classList.remove('input-valid');
                    
                    if (!phone.startsWith('05')) {
                        phoneHint.textContent = 'يجب أن يبدأ بـ 05';
                    } else if (phone.length < 10) {
                        phoneHint.textContent = `${10 - phone.length} أرقام متبقية`;
                    } else {
                        phoneHint.textContent = 'رقم غير صحيح';
                    }
                    phoneHint.classList.add('text-red-500');
                    phoneHint.classList.remove('text-green-500');
                }
                phoneHint.style.opacity = '1';
            }
            
            function updatePhoneConfirmation() {
                if (!phoneConfirmInput || !confirmStatus) return;
                
                const phone = phoneInput.value.trim();
                const confirm = phoneConfirmInput.value.trim();
                const matchIcon = confirmStatus.querySelector('.confirm-match');
                const mismatchIcon = confirmStatus.querySelector('.confirm-mismatch');
                
                if (confirm.length === 0) {
                    confirmStatus.style.opacity = '0';
                    phoneConfirmInput.classList.remove('input-valid', 'input-invalid');
                    confirmHint.style.opacity = '0';
                    confirmIcon.classList.remove('bg-green-500', 'bg-red-400');
                    confirmIcon.classList.add('bg-gray-200');
                    return;
                }
                
                confirmStatus.style.opacity = '1';
                
                if (phone === confirm && validateSaudiPhone(confirm)) {
                    matchIcon.classList.remove('hidden');
                    mismatchIcon.classList.add('hidden');
                    phoneConfirmInput.classList.add('input-valid');
                    phoneConfirmInput.classList.remove('input-invalid');
                    confirmHint.textContent = '✓ الأرقام متطابقة';
                    confirmHint.classList.remove('text-red-500');
                    confirmHint.classList.add('text-green-500');
                    confirmIcon.classList.remove('bg-gray-200', 'bg-red-400');
                    confirmIcon.classList.add('bg-green-500');
                    confirmIcon.innerHTML = '<svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>';
                } else {
                    matchIcon.classList.add('hidden');
                    mismatchIcon.classList.remove('hidden');
                    phoneConfirmInput.classList.add('input-invalid');
                    phoneConfirmInput.classList.remove('input-valid');
                    confirmHint.textContent = 'الأرقام غير متطابقة';
                    confirmHint.classList.add('text-red-500');
                    confirmHint.classList.remove('text-green-500');
                    confirmIcon.classList.remove('bg-gray-200', 'bg-green-500');
                    confirmIcon.classList.add('bg-red-400');
                    confirmIcon.innerHTML = '<svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>';
                }
                confirmHint.style.opacity = '1';
            }
            
            // Phone input events
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    // Only allow numbers
                    this.value = this.value.replace(/[^0-9]/g, '');
                    updatePhoneValidation();
                    updatePhoneConfirmation();
                });
                phoneInput.addEventListener('blur', updatePhoneValidation);
            }
            
            if (phoneConfirmInput) {
                phoneConfirmInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    updatePhoneConfirmation();
                });
                phoneConfirmInput.addEventListener('blur', updatePhoneConfirmation);
            }
            
            // ===== AUTO-FILL SERVICE DESCRIPTION =====
            const serviceMessages = {
                'paint': 'أحتاج إلى دهان غرفة أو أكثر في المنزل',
                'renovation': 'أحتاج إلى ترميم وتجديد',
                'cleaning': 'أحتاج إلى تنظيف شامل للمنزل',
                'ac': 'يوجد مشكلة في المكيف - يحتاج صيانة أو تنظيف',
                'plumbing': 'يوجد تسريب مياه أو مشكلة في السباكة تحتاج إصلاح',
                'electric': 'يوجد مشكلة في الكهرباء تحتاج إصلاح'
            };
            
            if (descriptionTextarea) {
                descriptionTextarea.addEventListener('input', function() {
                    userModifiedDescription = true;
                });
            }
            
            if (serviceSelect) {
                serviceSelect.addEventListener('change', function() {
                    const selectedService = this.value;
                    
                    if (descriptionTextarea && serviceMessages[selectedService] && !userModifiedDescription) {
                        descriptionTextarea.value = serviceMessages[selectedService];
                        descriptionTextarea.classList.add('ring-2', 'ring-[#3B9DD9]', 'ring-opacity-50');
                        setTimeout(() => {
                            descriptionTextarea.classList.remove('ring-2', 'ring-[#3B9DD9]', 'ring-opacity-50');
                        }, 1000);
                    } else if (descriptionTextarea && !selectedService && !userModifiedDescription) {
                        descriptionTextarea.value = '';
                    }
                    userModifiedDescription = false;
                });
                
                // Initial fill if service is preselected
                if (descriptionTextarea && serviceSelect.value && serviceMessages[serviceSelect.value] && !userModifiedDescription) {
                    descriptionTextarea.value = serviceMessages[serviceSelect.value];
                }
            }
            
            // ===== DATE PICKER AUTO-SELECT =====
            const scheduledDateInput = document.getElementById(fieldPrefix + '_scheduled_date');
            const scheduledRadio = document.getElementById(fieldPrefix + '_time_scheduled');
            
            if (scheduledDateInput && scheduledRadio) {
                scheduledDateInput.addEventListener('click', function() {
                    scheduledRadio.checked = true;
                });
                scheduledDateInput.addEventListener('change', function() {
                    if (this.value) {
                        scheduledRadio.checked = true;
                    }
                });
            }
            
            // ===== FORM SUBMISSION =====
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate phone
                const phone = phoneInput ? phoneInput.value.trim() : '';
                const phoneConfirm = phoneConfirmInput ? phoneConfirmInput.value.trim() : '';
                
                if (!validateSaudiPhone(phone)) {
                    showToast('يرجى إدخال رقم هاتف صحيح يبدأ بـ 05', 'error');
                    phoneInput?.focus();
                    return;
                }
                
                if (phone !== phoneConfirm) {
                    showToast('أرقام الهاتف غير متطابقة', 'error');
                    phoneConfirmInput?.focus();
                    return;
                }
                
                // Validate service time
                const timeRadios = form.querySelectorAll('input[name="service_time_type"]');
                const timeSelected = Array.from(timeRadios).some(r => r.checked);
                if (!timeSelected) {
                    showToast('يرجى اختيار وقت الخدمة', 'warning');
                    return;
                }
                
                // Show loading state
                if (submitBtn) {
                    submitBtn.disabled = true;
                    const btnText = submitBtn.querySelector('.btn-text');
                    const btnLoading = submitBtn.querySelector('.btn-loading');
                    if (btnText) btnText.classList.add('hidden');
                    if (btnLoading) btnLoading.classList.remove('hidden');
                    btnLoading.style.display = 'flex';
                }
                
                // Submit form via AJAX
                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('تم إرسال طلبك بنجاح! سنتواصل معك قريباً', 'success', 6000);
                        form.reset();
                        // Reset validation states
                        if (phoneStatus) phoneStatus.style.opacity = '0';
                        if (confirmStatus) confirmStatus.style.opacity = '0';
                        if (phoneInput) phoneInput.classList.remove('input-valid', 'input-invalid');
                        if (phoneConfirmInput) phoneConfirmInput.classList.remove('input-valid', 'input-invalid');
                        if (phoneHint) phoneHint.style.opacity = '0';
                        if (confirmHint) confirmHint.style.opacity = '0';
                        userModifiedDescription = false;
                    } else {
                        showToast(data.message || 'حدث خطأ، يرجى المحاولة مرة أخرى', 'error');
                    }
                })
                .catch(error => {
                    console.error('Form submission error:', error);
                    showToast('حدث خطأ في الاتصال، يرجى المحاولة مرة أخرى', 'error');
                })
                .finally(() => {
                    // Reset button state
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        const btnText = submitBtn.querySelector('.btn-text');
                        const btnLoading = submitBtn.querySelector('.btn-loading');
                        if (btnText) btnText.classList.remove('hidden');
                        if (btnLoading) {
                            btnLoading.classList.add('hidden');
                            btnLoading.style.display = 'none';
                        }
                    }
                });
            });
        })();
        </script>
        <?php
    }
}
