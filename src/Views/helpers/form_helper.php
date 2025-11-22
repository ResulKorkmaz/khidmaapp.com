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
        $darkTheme = $options['dark_theme'] ?? false; // Koyu tema için beyaz yazı
        
        // Ultra compact için daha minimal ayarlar
        $spacingClass = $ultraCompact ? 'space-y-3' : ($compact ? 'space-y-4' : 'space-y-6');
        $labelSpacing = $ultraCompact ? 'mb-1.5' : ($compact ? 'mb-2' : 'mb-3');
        $textareaHeight = $ultraCompact ? 'h-20' : ($compact ? 'h-24' : 'h-32');
        $labelClasses = $ultraCompact ? 'text-xs' : 'text-sm';
        $labelColor = $darkTheme ? 'text-white' : 'text-gray-900';
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
                <label for="<?= $fieldPrefix ?>_service_type" class="block <?= $labelClasses ?> font-semibold <?= $labelColor ?> <?= $labelSpacing ?>">
                    نوع الخدمة *
                </label>
                <select id="<?= $fieldPrefix ?>_service_type" name="service_type" required class="form-select service-type-select">
                    <option value="">اختر الخدمة</option>
                    <?php foreach (getServiceTypes() as $key => $service): ?>
                        <option value="<?= htmlspecialchars($key) ?>" <?= ($preselectedService === $key) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($service['ar']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- City -->
            <div>
                <label for="<?= $fieldPrefix ?>_city" class="block <?= $labelClasses ?> font-semibold <?= $labelColor ?> <?= $labelSpacing ?>">
                    المدينة *
                </label>
                <select id="<?= $fieldPrefix ?>_city" name="city" required class="form-select">
                    <option value="">اختر المدينة</option>
                    <?php foreach (getCities() as $key => $city): ?>
                        <option value="<?= htmlspecialchars($key) ?>">
                            <?= htmlspecialchars($city['ar']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Phone -->
            <div>
                <label for="<?= $fieldPrefix ?>_phone" class="block <?= $labelClasses ?> font-semibold <?= $labelColor ?> <?= $labelSpacing ?>">
                    رقم الهاتف *
                </label>
                <input type="tel"
                       id="<?= $fieldPrefix ?>_phone"
                       name="phone"
                       required
                       class="form-input phone-input phone-input-primary ltr-input"
                       placeholder="05xxxxxxxx"
                       inputmode="numeric"
                       dir="ltr"
                       maxlength="12"
                       pattern="[0-9]*">
            </div>

            <!-- Phone Confirmation -->
            <div>
                <label for="<?= $fieldPrefix ?>_phone_confirm" class="block <?= $labelClasses ?> font-semibold <?= $labelColor ?> <?= $labelSpacing ?>">
                    تأكيد الهاتف *
                </label>
                <input type="tel"
                       id="<?= $fieldPrefix ?>_phone_confirm"
                       name="phone_confirm"
                       required
                       class="form-input phone-input phone-input-confirm ltr-input"
                       placeholder="أعد كتابة الرقم"
                       inputmode="numeric"
                       dir="ltr"
                       maxlength="12"
                       pattern="[0-9]*">
            </div>

            <!-- Service Time (Minimal Single Row) -->
            <div>
                <label class="block <?= $labelClasses ?> font-semibold text-white <?= $labelSpacing ?>">
                    وقت الخدمة *
                </label>
                <div class="grid grid-cols-3 gap-2">
                    <!-- Urgent -->
                    <label class="service-time-btn cursor-pointer">
                        <input type="radio" 
                               name="service_time_type" 
                               value="urgent" 
                               id="<?= $fieldPrefix ?>_time_urgent"
                               class="service-time-radio sr-only" 
                               required>
                        <div class="service-time-label py-2 px-3 border-2 border-gray-200 rounded-lg text-center hover:border-red-400 transition-all">
                            <p class="text-sm font-semibold text-white">⚡ عاجل</p>
                        </div>
                    </label>
                    
                    <!-- Within 24h -->
                    <label class="service-time-btn cursor-pointer">
                        <input type="radio" 
                               name="service_time_type" 
                               value="within_24h" 
                               id="<?= $fieldPrefix ?>_time_24h"
                               class="service-time-radio sr-only" 
                               required>
                        <div class="service-time-label py-2 px-3 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all">
                            <p class="text-sm font-semibold text-white">⏰ 24 ساعة</p>
                        </div>
                    </label>
                    
                    <!-- Scheduled (Date Picker) -->
                    <label class="service-time-btn cursor-pointer">
                        <input type="radio" 
                               name="service_time_type" 
                               value="scheduled" 
                               id="<?= $fieldPrefix ?>_time_scheduled"
                               class="service-time-radio sr-only" 
                               required>
                        <div class="service-time-label py-2 px-3 border-2 border-gray-200 rounded-lg text-center hover:border-green-400 transition-all">
                            <input type="date" 
                                   id="<?= $fieldPrefix ?>_scheduled_date"
                                   name="scheduled_date" 
                                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                   max="<?= date('Y-m-d', strtotime('+90 days')) ?>"
                                   class="w-full bg-transparent text-white text-sm font-semibold text-center border-0 focus:outline-none cursor-pointer"
                                   dir="rtl"
                                   lang="ar-SA"
                                   placeholder="📅 تاريخ محدد">
                        </div>
                    </label>
                </div>
            </div>

            <!-- Description -->
            <?php if ($includeDescription): ?>
                <div>
                    <label for="<?= $fieldPrefix ?>_description" class="block <?= $labelClasses ?> font-semibold <?= $labelColor ?> <?= $labelSpacing ?>">
                        وصف الخدمة
                    </label>
                    <textarea id="<?= $fieldPrefix ?>_description"
                              name="description"
                              class="form-textarea <?= $textareaHeight ?>"
                              placeholder="<?= $ultraCompact ? 'التفاصيل...' : 'اكتب تفاصيل أكثر...' ?>"></textarea>
                </div>
            <?php endif; ?>

            <!-- Terms & Privacy Notice -->
            <div class="<?= $ultraCompact ? 'pt-1' : 'pt-2' ?>">
                <p class="text-xs text-white leading-relaxed <?= $ultraCompact ? 'text-[10px] lg:text-xs' : '' ?>">
                    بإرسالك توافق على 
                    <a href="/privacy" target="_blank" class="text-gray-300 hover:text-gray-200 underline underline-offset-2 transition-colors">
                        سياسة الخصوصية
                    </a>
                    و
                    <a href="/terms" target="_blank" class="text-gray-300 hover:text-gray-200 underline underline-offset-2 transition-colors">
                        الشروط
                    </a>
                </p>
            </div>

            <!-- Submit Button -->
            <div class="<?= $ultraCompact ? 'pt-2' : 'pt-4' ?>">
                <button type="submit" class="<?= $buttonClasses ?>" data-submit-btn>
                    <span class="btn-text"><?= $buttonText ?></span>
                    <span class="btn-loading hidden">
                        <span class="spinner me-2"></span>
                        جاري الإرسال...
                    </span>
                </button>
            </div>
        </form>
        <?php
    }
}

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
        $darkTheme = $options['dark_theme'] ?? false; // Koyu tema için beyaz yazı
        
        // Ultra compact için daha minimal ayarlar
        $spacingClass = $ultraCompact ? 'space-y-3' : ($compact ? 'space-y-4' : 'space-y-6');
        $labelSpacing = $ultraCompact ? 'mb-1.5' : ($compact ? 'mb-2' : 'mb-3');
        $textareaHeight = $ultraCompact ? 'h-20' : ($compact ? 'h-24' : 'h-32');
        $labelClasses = $ultraCompact ? 'text-xs' : 'text-sm';
        $labelColor = $darkTheme ? 'text-white' : 'text-gray-900';
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
                <label for="<?= $fieldPrefix ?>_service_type" class="block <?= $labelClasses ?> font-semibold <?= $labelColor ?> <?= $labelSpacing ?>">
                    نوع الخدمة *
                </label>
                <select id="<?= $fieldPrefix ?>_service_type" name="service_type" required class="form-select service-type-select">
                    <option value="">اختر الخدمة</option>
                    <?php foreach (getServiceTypes() as $key => $service): ?>
                        <option value="<?= htmlspecialchars($key) ?>" <?= ($preselectedService === $key) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($service['ar']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- City -->
            <div>
                <label for="<?= $fieldPrefix ?>_city" class="block <?= $labelClasses ?> font-semibold <?= $labelColor ?> <?= $labelSpacing ?>">
                    المدينة *
                </label>
                <select id="<?= $fieldPrefix ?>_city" name="city" required class="form-select">
                    <option value="">اختر المدينة</option>
                    <?php foreach (getCities() as $key => $city): ?>
                        <option value="<?= htmlspecialchars($key) ?>">
                            <?= htmlspecialchars($city['ar']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Phone -->
            <div>
                <label for="<?= $fieldPrefix ?>_phone" class="block <?= $labelClasses ?> font-semibold <?= $labelColor ?> <?= $labelSpacing ?>">
                    رقم الهاتف *
                </label>
                <input type="tel"
                       id="<?= $fieldPrefix ?>_phone"
                       name="phone"
                       required
                       class="form-input phone-input phone-input-primary ltr-input"
                       placeholder="05xxxxxxxx"
                       inputmode="numeric"
                       dir="ltr"
                       maxlength="12"
                       pattern="[0-9]*">
            </div>

            <!-- Phone Confirmation -->
            <div>
                <label for="<?= $fieldPrefix ?>_phone_confirm" class="block <?= $labelClasses ?> font-semibold <?= $labelColor ?> <?= $labelSpacing ?>">
                    تأكيد الهاتف *
                </label>
                <input type="tel"
                       id="<?= $fieldPrefix ?>_phone_confirm"
                       name="phone_confirm"
                       required
                       class="form-input phone-input phone-input-confirm ltr-input"
                       placeholder="أعد كتابة الرقم"
                       inputmode="numeric"
                       dir="ltr"
                       maxlength="12"
                       pattern="[0-9]*">
            </div>

            <!-- Service Time (Minimal Single Row) -->
            <div>
                <label class="block <?= $labelClasses ?> font-semibold text-white <?= $labelSpacing ?>">
                    وقت الخدمة *
                </label>
                <div class="grid grid-cols-3 gap-2">
                    <!-- Urgent -->
                    <label class="service-time-btn cursor-pointer">
                        <input type="radio" 
                               name="service_time_type" 
                               value="urgent" 
                               id="<?= $fieldPrefix ?>_time_urgent"
                               class="service-time-radio sr-only" 
                               required>
                        <div class="service-time-label py-2 px-3 border-2 border-gray-200 rounded-lg text-center hover:border-red-400 transition-all">
                            <p class="text-sm font-semibold text-white">⚡ عاجل</p>
                        </div>
                    </label>
                    
                    <!-- Within 24h -->
                    <label class="service-time-btn cursor-pointer">
                        <input type="radio" 
                               name="service_time_type" 
                               value="within_24h" 
                               id="<?= $fieldPrefix ?>_time_24h"
                               class="service-time-radio sr-only" 
                               required>
                        <div class="service-time-label py-2 px-3 border-2 border-gray-200 rounded-lg text-center hover:border-blue-400 transition-all">
                            <p class="text-sm font-semibold text-white">⏰ 24 ساعة</p>
                        </div>
                    </label>
                    
                    <!-- Scheduled (Date Picker) -->
                    <label class="service-time-btn cursor-pointer">
                        <input type="radio" 
                               name="service_time_type" 
                               value="scheduled" 
                               id="<?= $fieldPrefix ?>_time_scheduled"
                               class="service-time-radio sr-only" 
                               required>
                        <div class="service-time-label py-2 px-3 border-2 border-gray-200 rounded-lg text-center hover:border-green-400 transition-all">
                            <input type="date" 
                                   id="<?= $fieldPrefix ?>_scheduled_date"
                                   name="scheduled_date" 
                                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                   max="<?= date('Y-m-d', strtotime('+90 days')) ?>"
                                   class="w-full bg-transparent text-white text-sm font-semibold text-center border-0 focus:outline-none cursor-pointer"
                                   dir="rtl"
                                   lang="ar-SA"
                                   placeholder="📅 تاريخ محدد">
                        </div>
                    </label>
                </div>
            </div>

            <!-- Description -->
            <?php if ($includeDescription): ?>
                <div>
                    <label for="<?= $fieldPrefix ?>_description" class="block <?= $labelClasses ?> font-semibold <?= $labelColor ?> <?= $labelSpacing ?>">
                        وصف الخدمة
                    </label>
                    <textarea id="<?= $fieldPrefix ?>_description"
                              name="description"
                              class="form-textarea <?= $textareaHeight ?>"
                              placeholder="<?= $ultraCompact ? 'التفاصيل...' : 'اكتب تفاصيل أكثر...' ?>"></textarea>
                </div>
            <?php endif; ?>

            <!-- Terms & Privacy Notice -->
            <div class="<?= $ultraCompact ? 'pt-1' : 'pt-2' ?>">
                <p class="text-xs text-white leading-relaxed <?= $ultraCompact ? 'text-[10px] lg:text-xs' : '' ?>">
                    بإرسالك توافق على 
                    <a href="/privacy" target="_blank" class="text-gray-300 hover:text-gray-200 underline underline-offset-2 transition-colors">
                        سياسة الخصوصية
                    </a>
                    و
                    <a href="/terms" target="_blank" class="text-gray-300 hover:text-gray-200 underline underline-offset-2 transition-colors">
                        الشروط
                    </a>
                </p>
            </div>

            <!-- Submit Button -->
            <div class="<?= $ultraCompact ? 'pt-2' : 'pt-4' ?>">
                <button type="submit" class="<?= $buttonClasses ?>" data-submit-btn>
                    <span class="btn-text"><?= $buttonText ?></span>
                    <span class="btn-loading hidden">
                        <span class="spinner me-2"></span>
                        جاري الإرسال...
                    </span>
                </button>
            </div>
        </form>
        <?php
    }
}



