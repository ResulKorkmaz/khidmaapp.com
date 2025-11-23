<!-- FAQ Section -->
<section id="faq" class="py-16 md:py-24 bg-white relative overflow-hidden">
    <!-- Simple Background Decoration -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50 rounded-full mix-blend-multiply filter blur-3xl opacity-30 -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-50 rounded-full mix-blend-multiply filter blur-3xl opacity-30 translate-y-1/2 -translate-x-1/2"></div>

    <div class="container-custom max-w-4xl px-4 relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-12 md:mb-16">
            <div class="inline-flex items-center gap-2 bg-[#3B9DD9]/10 border border-[#3B9DD9]/20 px-4 py-2 rounded-full mb-6">
                <span class="w-2 h-2 rounded-full bg-[#3B9DD9]"></span>
                <span class="text-sm font-bold text-[#1E5A8A]">
                    الدعم والمساعدة
                </span>
            </div>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-6">
                الأسئلة الشائعة
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                إجابات على الأسئلة الأكثر شيوعاً حول منصة خدمة وكيفية عملها
            </p>
        </div>

        <!-- FAQ Items -->
        <div class="space-y-4">
            <!-- FAQ Item 1 -->
            <div class="faq-item group bg-white border-2 border-gray-100 rounded-2xl overflow-hidden hover:border-[#3B9DD9]/30 hover:shadow-lg transition-all duration-300">
                <button class="faq-question w-full text-right p-5 md:p-6 flex items-center justify-between gap-4" onclick="toggleFaq(this)">
                    <span class="text-base md:text-lg font-bold text-gray-900 group-hover:text-[#3B9DD9] transition-colors">ما هي منصة خدمة؟</span>
                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0 group-hover:bg-[#3B9DD9] transition-colors">
                        <svg class="w-5 h-5 text-[#3B9DD9] group-hover:text-white transition-colors faq-icon" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>
                <div class="faq-answer hidden p-5 md:p-6 pt-0">
                    <div class="text-gray-600 leading-relaxed space-y-4 border-t border-gray-100 pt-4 mt-2">
                        <p><strong class="text-[#1E5A8A]">منصة خدمة هي وسيط إلكتروني</strong> يربط بين العملاء ومقدمي الخدمات المنزلية والتجارية في المملكة العربية السعودية.</p>
                        <p>نقوم بتوصيل طلبك إلى مقدمي الخدمات المتاحين في منطقتك، ليتواصلوا معك مباشرة لتنفيذ الخدمة المطلوبة.</p>
                        <div class="bg-blue-50 border-r-4 border-[#3B9DD9] p-4 rounded-lg">
                            <p class="text-sm text-blue-900"><strong>ملاحظة:</strong> المنصة لا تقدم الخدمات بنفسها، بل تسهل التواصل بين الطرفين. العلاقة التعاقدية تكون مباشرة بينك وبين مقدم الخدمة.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="faq-item group bg-white border-2 border-gray-100 rounded-2xl overflow-hidden hover:border-[#3B9DD9]/30 hover:shadow-lg transition-all duration-300">
                <button class="faq-question w-full text-right p-5 md:p-6 flex items-center justify-between gap-4" onclick="toggleFaq(this)">
                    <span class="text-base md:text-lg font-bold text-gray-900 group-hover:text-[#3B9DD9] transition-colors">كيف يمكنني طلب خدمة؟</span>
                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0 group-hover:bg-[#3B9DD9] transition-colors">
                        <svg class="w-5 h-5 text-[#3B9DD9] group-hover:text-white transition-colors faq-icon" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>
                <div class="faq-answer hidden p-5 md:p-6 pt-0">
                    <div class="text-gray-600 leading-relaxed space-y-4 border-t border-gray-100 pt-4 mt-2">
                        <ol class="list-decimal list-inside space-y-2 marker:text-[#3B9DD9] marker:font-bold">
                            <li><strong>املأ النموذج</strong> في الصفحة الرئيسية بتحديد نوع الخدمة والمدينة ورقم هاتفك</li>
                            <li><strong>نوصل طلبك فوراً</strong> إلى مقدمي الخدمات المتاحين في منطقتك</li>
                            <li><strong>يتواصل معك مقدم الخدمة</strong> لترتيب التفاصيل والموعد والأسعار</li>
                        </ol>
                        <div class="bg-green-50 border-r-4 border-green-500 p-4 rounded-lg mt-2">
                            <p class="text-sm text-green-900"><strong>نصيحة:</strong> تأكد من إدخال رقم هاتف صحيح حتى يتمكن مقدم الخدمة من التواصل معك.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="faq-item group bg-white border-2 border-gray-100 rounded-2xl overflow-hidden hover:border-[#3B9DD9]/30 hover:shadow-lg transition-all duration-300">
                <button class="faq-question w-full text-right p-5 md:p-6 flex items-center justify-between gap-4" onclick="toggleFaq(this)">
                    <span class="text-base md:text-lg font-bold text-gray-900 group-hover:text-[#3B9DD9] transition-colors">ما هي الخدمات المتاحة؟</span>
                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0 group-hover:bg-[#3B9DD9] transition-colors">
                        <svg class="w-5 h-5 text-[#3B9DD9] group-hover:text-white transition-colors faq-icon" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>
                <div class="faq-answer hidden p-5 md:p-6 pt-0">
                    <div class="text-gray-600 leading-relaxed space-y-4 border-t border-gray-100 pt-4 mt-2">
                        <p>نوفر مجموعة شاملة من الخدمات المنزلية والتجارية:</p>
                        <div class="grid grid-cols-2 gap-3 text-sm font-medium">
                            <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                                <span class="text-[#3B9DD9]">✓</span>
                                <span>دهانات وترميم</span>
                            </div>
                            <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                                <span class="text-[#3B9DD9]">✓</span>
                                <span>كهرباء</span>
                            </div>
                            <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                                <span class="text-[#3B9DD9]">✓</span>
                                <span>سباكة</span>
                            </div>
                            <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                                <span class="text-[#3B9DD9]">✓</span>
                                <span>تكييف</span>
                            </div>
                            <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                                <span class="text-[#3B9DD9]">✓</span>
                                <span>تنظيف</span>
                            </div>
                            <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                                <span class="text-[#3B9DD9]">✓</span>
                                <span>صيانة عامة</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="faq-item group bg-white border-2 border-gray-100 rounded-2xl overflow-hidden hover:border-[#3B9DD9]/30 hover:shadow-lg transition-all duration-300">
                <button class="faq-question w-full text-right p-5 md:p-6 flex items-center justify-between gap-4" onclick="toggleFaq(this)">
                    <span class="text-base md:text-lg font-bold text-gray-900 group-hover:text-[#3B9DD9] transition-colors">هل تضمنون جودة الخدمات؟</span>
                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0 group-hover:bg-[#3B9DD9] transition-colors">
                        <svg class="w-5 h-5 text-[#3B9DD9] group-hover:text-white transition-colors faq-icon" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>
                <div class="faq-answer hidden p-5 md:p-6 pt-0">
                    <div class="text-gray-600 leading-relaxed space-y-4 border-t border-gray-100 pt-4 mt-2">
                        <p>المنصة وسيط إلكتروني فقط ولا تضمن جودة الخدمات قانونياً، حيث أن العلاقة التعاقدية مباشرة بينك وبين مقدم الخدمة.</p>
                        
                        <div class="bg-green-50 border-r-4 border-green-500 p-4 rounded-lg">
                            <p class="font-bold text-green-900 mb-2">✅ لكننا نعمل على مراقبة الجودة:</p>
                            <ul class="space-y-2 text-sm text-green-800">
                                <li class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    نستقبل شكاويك ونراجعها بجدية
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    نحذر مقدمي الخدمات الذين تتكرر عليهم الشكاوى
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    <strong>نفصل نهائياً</strong> مقدمي الخدمات سيئي النية
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 5 (Legal Disclaimer) -->
            <div class="faq-item group bg-white border-2 border-amber-100 rounded-2xl overflow-hidden hover:border-amber-300 hover:shadow-lg transition-all duration-300">
                <button class="faq-question w-full text-right p-5 md:p-6 flex items-center justify-between gap-4" onclick="toggleFaq(this)">
                    <span class="text-base md:text-lg font-bold text-gray-900 group-hover:text-amber-600 transition-colors flex items-center gap-2">
                        <span class="text-xl">⚠️</span>
                        إخلاء المسؤولية القانونية
                    </span>
                    <div class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center flex-shrink-0 group-hover:bg-amber-500 transition-colors">
                        <svg class="w-5 h-5 text-amber-500 group-hover:text-white transition-colors faq-icon" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>
                <div class="faq-answer hidden p-5 md:p-6 pt-0">
                    <div class="text-gray-600 leading-relaxed space-y-4 border-t border-gray-100 pt-4 mt-2">
                        <div class="bg-amber-50 border-r-4 border-amber-500 p-4 rounded-lg">
                            <p class="font-bold text-amber-900 mb-2">📢 تنويه قانوني هام:</p>
                            <p class="text-sm text-amber-900 mb-3">
                                منصة خدمة هي <strong>وسيط إلكتروني فقط</strong> لتسهيل التواصل بين العملاء ومقدمي الخدمات المستقلين.
                            </p>
                            
                            <div class="bg-white/80 rounded-lg p-3 border border-amber-200 mb-3">
                                <p class="font-semibold text-amber-900 mb-2 text-xs">🚫 المنصة غير مسؤولة قانونياً عن:</p>
                                <ul class="list-disc list-inside space-y-1 text-xs text-amber-800">
                                    <li>جودة الخدمات المقدمة</li>
                                    <li>دقة المعلومات المدخلة من قبل العملاء</li>
                                    <li>أي أضرار أو خسائر ناتجة عن تنفيذ الخدمة</li>
                                </ul>
                            </div>
                            
                            <p class="text-center text-xs font-bold text-amber-800 bg-white/50 py-2 px-3 rounded border border-amber-200">
                                ⚖️ المسؤولية القانونية مباشرة بين العميل ومقدم الخدمة
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
