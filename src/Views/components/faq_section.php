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

        <!-- CTA Box -->
        <div class="mt-12 text-center">
            <div class="inline-flex flex-col items-center bg-blue-50 rounded-2xl p-6 md:p-8 border border-[#3B9DD9]/20">
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-[#3B9DD9]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">لم تجد إجابة لسؤالك؟</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    فريق الدعم جاهز للإجابة على استفساراتك عبر الواتساب
                </p>
                <a href="<?= htmlspecialchars(WHATSAPP_CHANNEL_URL) ?>" 
                   target="_blank"
                   class="inline-flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#128C7E] text-white font-bold py-3 px-8 rounded-xl transition-all duration-300 shadow-lg shadow-green-500/20 hover:translate-y-[-2px]">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                    </svg>
                    تواصل معنا عبر واتساب
                </a>
            </div>
        </div>
    </div>
</section>
