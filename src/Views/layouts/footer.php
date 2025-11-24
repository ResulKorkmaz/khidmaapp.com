<?php 
// Ensure session is started for CSRF tokens
if (session_status() === PHP_SESSION_NONE) {
    startSession();
}
?>
<footer class="relative bg-[#0F2942] text-white overflow-hidden" dir="rtl">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 30px 30px;"></div>
    
    <!-- Decorative Gradient Orbs -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#3B9DD9]/10 rounded-full blur-[100px] translate-x-1/3 -translate-y-1/2 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[100px] -translate-x-1/3 translate-y-1/2 pointer-events-none"></div>
    
    <div class="relative z-10">
        <!-- Main Footer Content -->
        <div class="container-custom py-16 md:py-20 px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">
                
                <!-- 1. Brand & Description -->
                <div class="col-span-1 md:col-span-2 lg:col-span-1">
                    <!-- Logo -->
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                            <!-- Simple Logo Icon -->
                            <svg class="w-7 h-7 text-[#3B9DD9]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="mr-4">
                            <h3 class="text-2xl font-black text-white tracking-wide">
                                خدمة
                            </h3>
                            <p class="text-blue-300/80 text-xs font-medium tracking-widest uppercase">KhidmaApp.com</p>
                        </div>
                    </div>
                    
                    <p class="text-gray-400 mb-8 leading-relaxed text-sm">
                        المنصة الأولى في المملكة لربط العملاء بأفضل مقدمي الخدمات المحترفين. جودة، سرعة، وموثوقية في كل خدمة.
                    </p>
                    
                    <!-- Social Media Links -->
                    <div class="flex items-center gap-3">
                        <!-- WhatsApp -->
                        <a href="<?= htmlspecialchars(WHATSAPP_CHANNEL_URL) ?>" target="_blank" class="group w-10 h-10 bg-white/5 hover:bg-[#25D366] rounded-lg flex items-center justify-center transition-all duration-300 border border-white/10 hover:border-transparent">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                            </svg>
                        </a>
                        
                        <!-- X (Twitter) -->
                        <a href="#" class="group w-10 h-10 bg-white/5 hover:bg-black rounded-lg flex items-center justify-center transition-all duration-300 border border-white/10 hover:border-transparent">
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>

                        <!-- Instagram -->
                        <a href="#" class="group w-10 h-10 bg-white/5 hover:bg-pink-600 rounded-lg flex items-center justify-center transition-all duration-300 border border-white/10 hover:border-transparent">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.451 2.53c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.821-.049.975-.045 1.504-.207 1.857-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.049-3.821-.045-.975-.207-1.504-.344-1.857a3.657 3.657 0 00-.748-1.15 3.657 3.657 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/>
                            </svg>
                        </a>

                        <!-- Facebook -->
                        <a href="#" class="group w-10 h-10 bg-white/5 hover:bg-blue-600 rounded-lg flex items-center justify-center transition-all duration-300 border border-white/10 hover:border-transparent">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- 2. Quick Links -->
                <div>
                    <h4 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-[#3B9DD9] rounded-full"></span>
                        روابط سريعة
                    </h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="/" class="text-gray-400 hover:text-[#3B9DD9] hover:translate-x-[-4px] transition-all duration-200 flex items-center gap-2 text-sm">
                                <span class="w-1.5 h-1.5 bg-gray-600 rounded-full group-hover:bg-[#3B9DD9]"></span>
                                الرئيسية
                            </a>
                        </li>
                        <li>
                            <a href="/#services" class="text-gray-400 hover:text-[#3B9DD9] hover:translate-x-[-4px] transition-all duration-200 flex items-center gap-2 text-sm">
                                <span class="w-1.5 h-1.5 bg-gray-600 rounded-full group-hover:bg-[#3B9DD9]"></span>
                                الخدمات
                            </a>
                        </li>
                        <li>
                            <a href="/#about" class="text-gray-400 hover:text-[#3B9DD9] hover:translate-x-[-4px] transition-all duration-200 flex items-center gap-2 text-sm">
                                <span class="w-1.5 h-1.5 bg-gray-600 rounded-full group-hover:bg-[#3B9DD9]"></span>
                                عن خدمة
                            </a>
                        </li>
                        <li>
                            <a href="/#faq" class="text-gray-400 hover:text-[#3B9DD9] hover:translate-x-[-4px] transition-all duration-200 flex items-center gap-2 text-sm">
                                <span class="w-1.5 h-1.5 bg-gray-600 rounded-full group-hover:bg-[#3B9DD9]"></span>
                                الأسئلة الشائعة
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- 3. Services -->
                <div>
                    <h4 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-[#3B9DD9] rounded-full"></span>
                        خدماتنا
                    </h4>
                    <ul class="space-y-3">
                        <?php 
                        $footerServices = array_slice(getServiceTypes(), 0, 5); // Show only first 5
                        foreach ($footerServices as $key => $service): ?>
                        <li>
                            <a href="/services/<?= htmlspecialchars($key) ?>" class="text-gray-400 hover:text-[#3B9DD9] hover:translate-x-[-4px] transition-all duration-200 flex items-center gap-2 text-sm">
                                <span class="w-1.5 h-1.5 bg-gray-600 rounded-full group-hover:bg-[#3B9DD9]"></span>
                                <?= htmlspecialchars($service['ar']) ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- 4. Contact & CTA -->
                <div>
                    <h4 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-[#3B9DD9] rounded-full"></span>
                        المساعدة والدعم
                    </h4>
                    
                    <!-- Complaint Button -->
                    <div class="mb-4">
                        <button onclick="openComplaintModal()" class="w-full group flex items-center justify-between p-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition-all duration-300">
                            <span class="text-gray-300 text-sm font-medium group-hover:text-white">تقديم شكوى</span>
                            <div class="w-8 h-8 bg-red-500/20 text-red-400 rounded-lg flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                        </button>
                    </div>
                    
                    <!-- Join Provider -->
                    <div>
                        <a href="/#join-provider" class="w-full group flex items-center justify-between p-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition-all duration-300">
                            <span class="text-gray-300 text-sm font-medium group-hover:text-white">انضم كمقدم خدمة</span>
                            <div class="w-8 h-8 bg-[#3B9DD9]/20 text-[#3B9DD9] rounded-lg flex items-center justify-center group-hover:bg-[#3B9DD9] group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom Footer Bar -->
        <div class="border-t border-white/5 bg-[#0A1D30]">
            <div class="container-custom py-6 px-4">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <!-- Copyright -->
                    <div class="text-center md:text-start order-2 md:order-1">
                        <p class="text-gray-500 text-xs">
                            © <?= date('Y') ?> جميع الحقوق محفوظة لمنصة <span class="text-white font-semibold">خدمة</span>
                        </p>
                    </div>
                    
                    <!-- Legal Links -->
                    <div class="flex items-center gap-6 order-1 md:order-2">
                        <a href="/privacy" class="text-gray-500 hover:text-[#3B9DD9] text-xs transition-colors">
                            سياسة الخصوصية
                        </a>
                        <a href="/terms" class="text-gray-500 hover:text-[#3B9DD9] text-xs transition-colors">
                            شروط الاستخدام
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Modals and Scripts remain the same -->
<!-- Complaint Modal -->
<div id="complaintModal" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 transition-opacity duration-300" style="background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(4px);">
    <div class="relative w-full max-w-lg max-h-[95vh] overflow-y-auto bg-white rounded-2xl sm:rounded-3xl shadow-2xl transform transition-all duration-300 scale-95 opacity-0" id="complaintModalContent">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-gradient-to-r from-red-600 to-red-700 text-white p-4 sm:p-5 rounded-t-2xl sm:rounded-t-3xl flex items-center justify-between z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg sm:text-xl font-bold">تقديم شكوى</h3>
                    <p class="text-red-100 text-xs sm:text-sm">نحن هنا لمساعدتك وحل مشكلتك</p>
                </div>
            </div>
            <button onclick="closeComplaintModal()" class="w-9 h-9 sm:w-10 sm:h-10 bg-white/20 hover:bg-white/30 rounded-lg sm:rounded-xl flex items-center justify-center transition-colors flex-shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-4 sm:p-6">
            <!-- Info Box -->
            <div class="mb-4 sm:mb-5 p-3 sm:p-4 bg-blue-50 rounded-lg sm:rounded-xl border border-blue-200">
                <div class="flex items-start gap-2 sm:gap-3">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-700 leading-relaxed">
                            سيتم إرسال شكواك مباشرة إلى فريق خدمة العملاء عبر WhatsApp. 
                            سنقوم بالرد عليك في أقرب وقت ممكن.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Complaint Form -->
            <form id="complaintForm" class="space-y-4 sm:space-y-5">
                <!-- Name -->
                <div>
                    <label for="complaint_name" class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                        الاسم الكامل *
                    </label>
                    <input type="text" 
                           id="complaint_name" 
                           name="name" 
                           required
                           class="w-full px-3 py-2.5 sm:px-4 sm:py-3 text-sm border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                           placeholder="أدخل اسمك الكامل">
                </div>
                
                <!-- Phone -->
                <div>
                    <label for="complaint_phone" class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                        رقم الهاتف *
                    </label>
                    <input type="tel" 
                           id="complaint_phone" 
                           name="phone" 
                           required
                           class="w-full px-3 py-2.5 sm:px-4 sm:py-3 text-sm border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                           placeholder="05xxxxxxxx"
                           inputmode="numeric"
                           dir="ltr"
                           maxlength="12"
                           pattern="[0-9]*">
                    <p class="mt-1.5 text-xs text-gray-500">أدخل رقم هاتفك السعودي</p>
                </div>
                
                <!-- Email (Optional) -->
                <div>
                    <label for="complaint_email" class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                        البريد الإلكتروني (اختياري)
                    </label>
                    <input type="email" 
                           id="complaint_email" 
                           name="email" 
                           class="w-full px-3 py-2.5 sm:px-4 sm:py-3 text-sm border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                           placeholder="example@email.com"
                           dir="ltr">
                </div>
                
                <!-- Complaint Type -->
                <div>
                    <label for="complaint_type" class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                        نوع الشكوى *
                    </label>
                    <select id="complaint_type" 
                            name="type" 
                            required
                            class="w-full px-3 py-2.5 sm:px-4 sm:py-3 text-sm border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                        <option value="">اختر نوع الشكوى</option>
                        <option value="service_quality">جودة الخدمة</option>
                        <option value="provider_issue">مشكلة مع مقدم الخدمة</option>
                        <option value="payment">مشكلة في الدفع</option>
                        <option value="website">مشكلة تقنية في الموقع</option>
                        <option value="other">أخرى</option>
                    </select>
                </div>
                
                <!-- Complaint Details -->
                <div>
                    <label for="complaint_details" class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                        تفاصيل الشكوى *
                    </label>
                    <textarea id="complaint_details" 
                              name="details" 
                              required
                              rows="4"
                              class="w-full px-3 py-2.5 sm:px-4 sm:py-3 text-sm border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all resize-none"
                              placeholder="يرجى وصف مشكلتك بالتفصيل..."></textarea>
                    <p class="mt-1.5 text-xs text-gray-500">كلما كانت التفاصيل أكثر، سيساعدنا ذلك في حل مشكلتك بشكل أسرع</p>
                </div>
                
                <!-- Submit Button -->
                <div class="pt-2 sm:pt-3">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 sm:py-3.5 px-4 sm:px-6 rounded-lg sm:rounded-xl shadow-lg shadow-green-500/30 hover:shadow-green-500/40 transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center gap-2 sm:gap-3 text-sm sm:text-base">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                        </svg>
                        <span>إرسال الشكوى عبر WhatsApp</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Complaint Modal Functions
function openComplaintModal() {
    const modal = document.getElementById('complaintModal');
    const modalContent = document.getElementById('complaintModalContent');
    if (modal && modalContent) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        
        // Trigger animation
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
}

function closeComplaintModal() {
    const modal = document.getElementById('complaintModal');
    const modalContent = document.getElementById('complaintModalContent');
    if (modal && modalContent) {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
            // Reset form
            document.getElementById('complaintForm')?.reset();
        }, 300);
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('complaintModal');
    if (modal && event.target === modal) {
        closeComplaintModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeComplaintModal();
    }
});

// Phone Input Formatting for Complaint Form
document.addEventListener('DOMContentLoaded', function() {
    const complaintPhone = document.getElementById('complaint_phone');
    if (complaintPhone) {
        complaintPhone.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // Auto-format to 05xxxxxxxx
            if (value.length > 0 && !value.startsWith('0')) {
                if (value.startsWith('5')) {
                    value = '0' + value;
                }
            }
            
            // Limit to 12 characters
            if (value.length > 12) {
                value = value.substring(0, 12);
            }
            
            e.target.value = value;
        });
        
        // Prevent paste
        complaintPhone.addEventListener('paste', function(e) {
            e.preventDefault();
        });
    }
    
    // Complaint Form Submission
    const complaintForm = document.getElementById('complaintForm');
    if (complaintForm) {
        complaintForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(complaintForm);
            const name = formData.get('name').trim();
            const phone = formData.get('phone').trim();
            const email = formData.get('email')?.trim() || '';
            const type = formData.get('type');
            const details = formData.get('details').trim();
            
            // Validate phone (Saudi format)
            const phoneRegex = /^(05|5)[0-9]{8,9}$/;
            const cleanPhone = phone.replace(/\D/g, '');
            
            if (!phoneRegex.test(cleanPhone)) {
                alert('يرجى إدخال رقم هاتف سعودي صحيح (مثال: 0501234567)');
                return;
            }
            
            // Complaint type labels
            const typeLabels = {
                'service_quality': 'جودة الخدمة',
                'provider_issue': 'مشكلة مع مقدم الخدمة',
                'payment': 'مشكلة في الدفع',
                'website': 'مشكلة تقنية في الموقع',
                'other': 'أخرى'
            };
            
            // Format message for WhatsApp
            const whatsappMessage = `*شكوى من موقع خدمة*\n\n` +
                `*الاسم:* ${name}\n` +
                `*رقم الهاتف:* ${phone}\n` +
                (email ? `*البريد الإلكتروني:* ${email}\n` : '') +
                `*نوع الشكوى:* ${typeLabels[type] || type}\n\n` +
                `*تفاصيل الشكوى:*\n${details}`;
            
            // WhatsApp number: +1 628 800 68 18
            const whatsappNumber = '16288006818';
            const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(whatsappMessage)}`;
            
            // Open WhatsApp
            window.open(whatsappUrl, '_blank');
            
            // Close modal after a short delay
            setTimeout(() => {
                closeComplaintModal();
            }, 500);
        });
    }
    
    // ============================================
    // PROVIDER AUTHENTICATION MODAL
    // ============================================
    window.openProviderAuthModal = function() {
        const modal = document.getElementById('providerAuthModal');
        const overlay = document.getElementById('providerAuthOverlay');
        
        if (modal && overlay) {
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            
            // Animate in
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                modal.classList.remove('scale-95', 'opacity-0');
                modal.classList.add('scale-100', 'opacity-100');
            }, 10);
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }
    };
    
    window.closeProviderAuthModal = function() {
        const modal = document.getElementById('providerAuthModal');
        const overlay = document.getElementById('providerAuthOverlay');
        
        if (modal && overlay) {
            // Animate out
            overlay.classList.add('opacity-0');
            modal.classList.remove('scale-100', 'opacity-100');
            modal.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                overlay.classList.remove('flex');
                overlay.classList.add('hidden');
            }, 300);
            
            // Restore body scroll
            document.body.style.overflow = '';
        }
    };
    
    window.switchProviderAuthTab = function(tab) {
        const loginForm = document.getElementById('providerLoginForm');
        const registerForm = document.getElementById('providerRegisterForm');
        const loginTab = document.getElementById('providerLoginTab');
        const registerTab = document.getElementById('providerRegisterTab');
        
        if (tab === 'login') {
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
            loginTab.classList.add('bg-blue-600', 'text-white');
            loginTab.classList.remove('text-gray-600', 'hover:bg-gray-100');
            registerTab.classList.remove('bg-blue-600', 'text-white');
            registerTab.classList.add('text-gray-600', 'hover:bg-gray-100');
        } else {
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
            registerTab.classList.add('bg-blue-600', 'text-white');
            registerTab.classList.remove('text-gray-600', 'hover:bg-gray-100');
            loginTab.classList.remove('bg-blue-600', 'text-white');
            loginTab.classList.add('text-gray-600', 'hover:bg-gray-100');
        }
    };
    
    // Close modal on overlay click
    const providerAuthOverlay = document.getElementById('providerAuthOverlay');
    if (providerAuthOverlay) {
        providerAuthOverlay.addEventListener('click', function(e) {
            if (e.target === this) {
                closeProviderAuthModal();
            }
        });
    }
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const overlay = document.getElementById('providerAuthOverlay');
            if (overlay && !overlay.classList.contains('hidden')) {
                closeProviderAuthModal();
            }
        }
    });
});
</script>

<!-- Provider Authentication Modal -->
<div id="providerAuthOverlay" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 opacity-0 transition-opacity duration-300 justify-center items-center p-4">
    <div id="providerAuthModal" class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300">
        <!-- Close Button -->
        <button onclick="closeProviderAuthModal()" class="absolute top-4 left-4 z-10 w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        
        <!-- Header -->
        <div class="bg-blue-600 text-white px-8 py-8 rounded-t-3xl text-center">
            <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold">حساب الأُستاذ (مقدم الخدمة)</h2>
            <p class="text-blue-100 mt-2">سجل دخولك أو أنشئ حساباً جديداً</p>
        </div>
        
        <!-- Tabs -->
        <div class="flex border-b border-gray-200 px-8 pt-6">
            <button id="providerLoginTab" onclick="switchProviderAuthTab('login')" class="flex-1 py-3 text-center font-semibold rounded-t-lg transition-colors bg-blue-600 text-white">
                تسجيل الدخول
            </button>
            <button id="providerRegisterTab" onclick="switchProviderAuthTab('register')" class="flex-1 py-3 text-center font-semibold rounded-t-lg transition-colors text-gray-600 hover:bg-gray-100">
                إنشاء حساب
            </button>
        </div>
        
        <!-- Login Form -->
        <div id="providerLoginForm" class="p-8">
            <form action="/provider/login" method="POST" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                
                <!-- Email or Phone -->
                <div>
                    <label for="login_identifier" class="block text-sm font-semibold text-gray-900 mb-2">
                        البريد الإلكتروني أو رقم الهاتف
                    </label>
                <input type="text" id="login_identifier" name="identifier" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                       placeholder="example@mail.com أو 0501234567">
                </div>
                
                <!-- Password -->
                <div>
                    <label for="login_password" class="block text-sm font-semibold text-gray-900 mb-2">
                        كلمة المرور
                    </label>
                    <input type="password" id="login_password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="••••••••">
                </div>
                
                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center text-gray-700">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 ml-2">
                        تذكرني
                    </label>
                    <a href="/provider/forgot-password" class="text-blue-600 hover:text-blue-700 font-medium">
                        نسيت كلمة المرور؟
                    </a>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition-colors shadow-lg hover:shadow-xl">
                    تسجيل الدخول
                </button>
            </form>
        </div>
        
        <!-- Register Form -->
        <div id="providerRegisterForm" class="hidden p-8">
            <form action="/provider/register" method="POST" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                
                <!-- WhatsApp Channel Alert -->
                <div class="bg-green-50 border-2 border-green-200 rounded-xl p-4 mb-4">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-green-900 mb-2">⚠️ مهم! يجب الانضمام إلى قناتنا على WhatsApp</h4>
                            <p class="text-sm text-green-800 leading-relaxed mb-3">
                                قبل التسجيل، يرجى الانضمام إلى قناة WhatsApp الخاصة بنا لتلقي طلبات العملاء. 
                                بدون الانضمام، لن تتمكن من استلام الطلبات.
                            </p>
                            <a href="https://whatsapp.com/channel/0029VbCCqZoI1rcjIn9IWV2l" 
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-colors shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5" viewBox="0 0 24 24">
                                    <path fill="#FFFFFF" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                                </svg>
                                انضم إلى القناة الآن
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Full Name -->
                <div>
                    <label for="register_name" class="block text-sm font-semibold text-gray-900 mb-2">
                        الاسم الكامل *
                    </label>
                    <input type="text" id="register_name" name="name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="محمد أحمد">
                </div>
                
                <!-- Email -->
                <div>
                    <label for="register_email" class="block text-sm font-semibold text-gray-900 mb-2">
                        البريد الإلكتروني *
                    </label>
                    <input type="email" id="register_email" name="email" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="example@mail.com">
                </div>
                
                <!-- Phone -->
                <div>
                    <label for="register_phone" class="block text-sm font-semibold text-gray-900 mb-2">
                        رقم الهاتف *
                    </label>
                    <input type="tel" id="register_phone" name="phone" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="05xxxxxxxx"
                           pattern="[0-9]+"
                           maxlength="10">
                </div>
                
                <!-- Service Type -->
                <div>
                    <label for="register_service_type" class="block text-sm font-semibold text-gray-900 mb-2">
                        نوع الخدمة *
                    </label>
                    <select id="register_service_type" name="service_type" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">اختر التخصص</option>
                        <?php foreach (getServiceTypes() as $key => $service): ?>
                            <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($service['ar']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- City -->
                <div>
                    <label for="register_city" class="block text-sm font-semibold text-gray-900 mb-2">
                        المدينة *
                    </label>
                    <select id="register_city" name="city" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">اختر المدينة</option>
                        <option value="riyadh">الرياض (Riyadh)</option>
                        <option value="jeddah">جدة (Jeddah)</option>
                        <option value="dammam">الدمام / الخبر / الظهران (Dammam / Khobar / Dhahran)</option>
                    </select>
                </div>
                
                <!-- Password -->
                <div>
                    <label for="register_password" class="block text-sm font-semibold text-gray-900 mb-2">
                        كلمة المرور *
                    </label>
                    <input type="password" id="register_password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="••••••••"
                           minlength="6">
                    <p class="text-xs text-gray-500 mt-1">6 أحرف على الأقل</p>
                </div>
                
                <!-- Password Confirm -->
                <div>
                    <label for="register_password_confirm" class="block text-sm font-semibold text-gray-900 mb-2">
                        تأكيد كلمة المرور *
                    </label>
                    <input type="password" id="register_password_confirm" name="password_confirm" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="••••••••">
                </div>
                
                <!-- WhatsApp Channel Confirmation -->
                <div class="flex items-start p-3 bg-green-50 border border-green-200 rounded-lg">
                    <input type="checkbox" name="channel_joined" required
                           class="rounded border-green-300 text-green-600 focus:ring-green-500 mt-1 ml-2">
                    <label class="text-sm text-gray-900 font-medium">
                        <span class="text-green-700">✅</span> لقد انضممت إلى قناة WhatsApp وأؤكد أنني عضو
                        <span class="text-red-600">*</span>
                    </label>
                </div>
                
                <!-- Terms -->
                <div class="flex items-start">
                    <input type="checkbox" name="terms" required
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mt-1 ml-2">
                    <label class="text-sm text-gray-700">
                        أوافق على <a href="/terms" target="_blank" class="text-blue-600 hover:text-blue-700 font-medium">شروط الاستخدام</a> و
                        <a href="/privacy" target="_blank" class="text-blue-600 hover:text-blue-700 font-medium">سياسة الخصوصية</a>
                    </label>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition-colors shadow-lg hover:shadow-xl">
                    إنشاء حساب
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // ============================================
    // FAQ ACCORDION
    // ============================================
    window.toggleFaq = function(button) {
        const faqItem = button.closest('.faq-item');
        const answer = faqItem.querySelector('.faq-answer');
        const icon = button.querySelector('.faq-icon');
        const isOpen = !answer.classList.contains('hidden');
        
        if (isOpen) {
            // Close
            answer.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        } else {
            // Close all other FAQs first
            document.querySelectorAll('.faq-answer').forEach(a => a.classList.add('hidden'));
            document.querySelectorAll('.faq-icon').forEach(i => i.style.transform = 'rotate(0deg)');
            
            // Open this one
            answer.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        }
    }

    // ============================================
    // AUTO-FILL SERVICE DESCRIPTION
    // ============================================
    // خاصية ملء وصف الخدمة تلقائياً للعملاء الكسولين 😄
    document.addEventListener('DOMContentLoaded', function() {
        // قاموس الرسائل الافتراضية لكل خدمة
        const serviceMessages = {
            'paint': 'أحتاج إلى دهان غرفة أو أكثر في المنزل',
            'renovation': 'أحتاج إلى ترميم وتجديد',
            'cleaning': 'أحتاج إلى تنظيف شامل للمنزل',
            'ac': 'يوجد مشكلة في المكيف - يحتاج صيانة أو تنظيف',
            'plumbing': 'يوجد تسريب مياه أو مشكلة في السباكة تحتاج إصلاح',
            'electric': 'يوجد مشكلة في الكهرباء تحتاج إصلاح'
        };
        
        // البحث عن جميع نماذج الخدمة في الصفحة
        document.querySelectorAll('.service-request-form').forEach(function(form) {
            const serviceSelect = form.querySelector('.service-type-select');
            const descriptionTextarea = form.querySelector('textarea[name="description"]');
            
            // إذا لم يكن هناك حقل وصف، تجاهل هذا النموذج
            if (!serviceSelect || !descriptionTextarea) {
                return;
            }
            
            // متابعة ما إذا كان المستخدم قد عدّل النص يدوياً
            let userModified = false;
            
            // تتبع التعديلات اليدوية
            descriptionTextarea.addEventListener('input', function() {
                // إذا كان الحقل فارغاً، اسمح بالتعبئة التلقائية مرة أخرى
                if (this.value.trim() === '') {
                    userModified = false;
                } else {
                    // المستخدم كتب شيئاً، علّم أنه معدّل يدوياً
                    const currentService = serviceSelect.value;
                    const defaultMessage = serviceMessages[currentService] || '';
                    // فقط علّم كمعدّل إذا كان النص مختلفاً عن الافتراضي
                    if (this.value.trim() !== defaultMessage.trim()) {
                        userModified = true;
                    }
                }
            });
            
            // عند تغيير نوع الخدمة
            serviceSelect.addEventListener('change', function() {
                const selectedService = this.value;
                
                // إذا لم يعدّل المستخدم النص، املأ الرسالة الافتراضية
                if (!userModified && selectedService && serviceMessages[selectedService]) {
                    descriptionTextarea.value = serviceMessages[selectedService];
                    // أضف تأثير بسيط لجذب الانتباه
                    descriptionTextarea.classList.add('ring-2', 'ring-blue-300');
                    setTimeout(function() {
                        descriptionTextarea.classList.remove('ring-2', 'ring-blue-300');
                    }, 1000);
                }
            });
            
            // إذا كانت الخدمة محددة مسبقاً (عند تحميل الصفحة)
            if (serviceSelect.value && !descriptionTextarea.value && serviceMessages[serviceSelect.value]) {
                descriptionTextarea.value = serviceMessages[serviceSelect.value];
            }
        });
    });
</script>

</body>
</html>
