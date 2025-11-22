<!-- FAQ Section -->
<section id="faq" class="section-padding bg-white">
    <div class="container-custom max-w-4xl">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                الأسئلة الشائعة
            </h2>
            <p class="text-lg text-gray-600">
                إجابات على الأسئلة الأكثر شيوعاً حول منصة خدمة
            </p>
        </div>

        <!-- FAQ Items -->
        <div class="space-y-4">
            <!-- FAQ Item 1 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">ما هو دور منصة خدمة بالضبط؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <p class="text-gray-600 leading-relaxed">
                        <strong class="text-gray-900">منصة خدمة هي وسيط إلكتروني</strong> يربط بين العملاء ومقدمي الخدمات فقط. دورنا ينحصر في توصيل طلبك إلى مقدمي الخدمات المناسبين في منطقتك. <strong class="text-orange-600">نحن لا نقدم الخدمات بأنفسنا</strong> ولا نتحمل مسؤولية جودة العمل أو التزام مقدم الخدمة. العلاقة التعاقدية تكون مباشرة بينك وبين مقدم الخدمة.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">هل تضمنون جودة الخدمات المقدمة؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p><strong class="text-gray-900">قانونياً: لا نضمن جودة الخدمات</strong> لأننا وسيط فقط. قد تختلف جودة الخدمة من مقدم لآخر (ممتازة، جيدة، متوسطة، أو غير مرضية).</p>
                        
                        <div class="bg-orange-50 border-r-4 border-orange-500 p-4 rounded">
                            <p class="font-semibold text-orange-900 mb-2">⚠️ تنبيه هام:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm text-orange-800">
                                <li>قد يقوم بعض مقدمي الخدمات بعمل غير احترافي</li>
                                <li>المسؤولية القانونية الكاملة تقع على عاتق مقدم الخدمة</li>
                                <li>ننصح بالتحقق والاتفاق على التفاصيل قبل بدء العمل</li>
                            </ul>
                        </div>

                        <!-- لكن نراقب الجودة -->
                        <div class="bg-green-50 border-r-4 border-green-500 p-4 rounded">
                            <p class="font-semibold text-green-900 mb-2">✅ لكننا نعمل على مراقبة الجودة:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm text-green-800">
                                <li><strong>نستقبل شكاويك</strong> ونراجعها بجدية</li>
                                <li><strong>نحذر</strong> مقدمي الخدمات الذين يتكرر عليهم الشكاوى</li>
                                <li><strong>نفصل نهائياً</strong> مقدمي الخدمات سيئي النية أو غير المحترفين</li>
                                <li><strong>نمنع وصول طلبات جديدة</strong> للمفصولين من المنصة</li>
                                <li>هدفنا: <strong>تحسين جودة الشبكة باستمرار</strong> بناءً على ملاحظاتك</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">هل المعلومات المدخلة في الطلبات دقيقة دائماً؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p><strong class="text-gray-900">لا، المعلومات قد تكون غير دقيقة.</strong> نحن لا نتحقق من صحة البيانات التي يدخلها العملاء في النموذج.</p>
                        <div class="bg-yellow-50 border-r-4 border-yellow-500 p-4 rounded">
                            <p class="font-semibold text-yellow-900 mb-2">⚠️ احتمالات خاصة بالطلبات:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm text-yellow-800">
                                <li>قد يكون رقم الهاتف خاطئاً أو غير صحيح</li>
                                <li>قد يملأ العميل النموذج بشكل ناقص أو خاطئ</li>
                                <li>قد تكون تفاصيل الخدمة المطلوبة غير واضحة</li>
                                <li><strong>لا يمكن استرداد الأموال بسبب معلومات خاطئة من العميل</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">كيف يمكنني طلب خدمة من خلال المنصة؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <p class="text-gray-600 leading-relaxed">
                        طلب الخدمة سهل جداً! املأ النموذج في الصفحة الرئيسية بتحديد نوع الخدمة، مدينتك، ورقم هاتفك. <strong class="text-gray-900">سيتم توصيل طلبك مباشرة إلى مقدمي الخدمات المتاحين</strong> في منطقتك، وسيتواصل معك مقدم الخدمة مباشرة لترتيب التفاصيل والأسعار.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 5 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">ما هي أنواع الخدمات المتاحة؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <p class="text-gray-600 leading-relaxed">
                        نوصل طلبات الخدمات المنزلية والتجارية المتنوعة: الدهانات والترميم، التنظيف، الصيانة، الكهرباء، السباكة، المكيفات، وغيرها. نعمل مع شبكة واسعة من مقدمي الخدمات المستقلين في مختلف التخصصات.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 6 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">ما هي تكلفة الخدمات؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <p class="text-gray-600 leading-relaxed">
                        <strong class="text-gray-900">الأسعار يحددها مقدم الخدمة مباشرة</strong> وتختلف حسب نوع الخدمة ونطاق العمل. المنصة لا تتدخل في تحديد الأسعار أو التفاوض. التعامل المالي يكون مباشرة بينك وبين مقدم الخدمة. ننصحك بالاتفاق على السعر والتفاصيل قبل بدء العمل.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 7 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">ماذا لو لم أكن راضياً عن الخدمة؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <div class="text-gray-600 leading-relaxed space-y-3">
                        <p>على الرغم من أن المنصة هي مجرد وسيط ولا تتحمل المسؤولية القانونية عن جودة الخدمات، <strong class="text-green-700">نحن نهتم برضاك ونعمل على تحسين جودة الشبكة.</strong></p>
                        
                        <!-- خطوات الشكوى -->
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-300 p-4 rounded-lg">
                            <p class="font-bold text-green-900 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                📢 نظام الشكاوى والمتابعة:
                            </p>
                            <ol class="list-decimal list-inside space-y-2 text-sm text-green-900">
                                <li><strong>أولاً:</strong> حاول حل المشكلة مباشرة مع مقدم الخدمة</li>
                                <li><strong>ثانياً:</strong> إذا لم يتم الحل، <strong class="text-green-700">أبلغنا فوراً عبر نظام الشكاوى</strong> في الموقع أو WhatsApp</li>
                                <li><strong>ثالثاً:</strong> سنراجع شكواك ونتواصل مع الطرفين لفهم الموقف</li>
                                <li><strong>رابعاً:</strong> في حالة ثبوت سوء النية أو العمل غير الاحترافي، <strong class="text-red-600">سنوقف التعامل مع هذا المقدم نهائياً</strong></li>
                            </ol>
                        </div>

                        <!-- ما نفعله -->
                        <div class="bg-blue-50 border-r-4 border-blue-500 p-4 rounded">
                            <p class="font-semibold text-blue-900 mb-2">✅ التزامنا تجاهك:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm text-blue-800">
                                <li>نراجع جميع الشكاوى بجدية واهتمام</li>
                                <li>نحاول الوساطة لحل المشكلات</li>
                                <li>نحذر مقدمي الخدمات المتكررة شكاويهم</li>
                                <li><strong>نفصل نهائياً أي مقدم خدمة سيء النية أو غير محترف</strong></li>
                                <li>نمنع وصول طلبات جديدة لمقدمي الخدمات المفصولين</li>
                            </ul>
                        </div>

                        <!-- نصائح -->
                        <div class="bg-amber-50 border-r-4 border-amber-500 p-4 rounded">
                            <p class="font-semibold text-amber-900 mb-2">💡 نصائح مهمة:</p>
                            <ul class="list-disc list-inside space-y-1 text-sm text-amber-800">
                                <li>اتفق على كل التفاصيل والأسعار قبل بدء العمل</li>
                                <li>احتفظ بأدلة تواصلك (رسائل، صور) كإثبات</li>
                                <li>أبلغنا فوراً عند ملاحظة أي مشكلة</li>
                                <li>تذكر: المنصة لا تضمن النتائج لكننا نعمل لحمايتك</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 8 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">كيف يمكنني الانضمام كمقدم خدمة؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <p class="text-gray-600 leading-relaxed">
                        إذا كنت مقدم خدمة محترف، اضغط على زر "انضم كمقدم خدمة" في الصفحة الرئيسية. سيتم توجيهك لقناة التسجيل. <strong class="text-gray-900">ملاحظة:</strong> قبولك في المنصة لا يعني تزكية أو ضمان من المنصة. أنت مسؤول بالكامل عن جودة عملك والتزاماتك تجاه العملاء.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 9 -->
            <div class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-gray-900">في أي المدن تتوفر الخدمة؟</span>
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <p class="text-gray-600 leading-relaxed">
                        نوصل الطلبات في مدن رئيسية بالمملكة العربية السعودية: الرياض، جدة، الدمام، الخبر، الطائف، مكة المكرمة، المدينة المنورة، أبها، تبوك، حائل، بريدة، جازان، ونجران. نعمل باستمرار على التوسع لمناطق جديدة.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 10 - NEW -->
            <div class="faq-item bg-white border border-orange-300 rounded-xl overflow-hidden hover:border-orange-400 transition-colors">
                <button class="faq-question w-full text-right p-5 flex items-center justify-between gap-3 hover:bg-orange-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-base font-semibold text-orange-900">⚠️ إخلاء المسؤولية القانونية</span>
                    <svg class="w-5 h-5 text-orange-600 flex-shrink-0 faq-icon transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer hidden p-5 pt-0">
                    <div class="bg-orange-50 border-2 border-orange-300 rounded-lg p-5">
                        <p class="text-orange-900 font-bold mb-3 text-lg">📢 تنويه قانوني هام:</p>
                        <div class="space-y-4 text-gray-700">
                            <p><strong>منصة خدمة هي وسيط إلكتروني فقط</strong> لتسهيل التواصل بين العملاء ومقدمي الخدمات المستقلين.</p>
                            
                            <div class="bg-white rounded-lg p-4 border border-orange-200">
                                <p class="font-semibold text-orange-900 mb-2">🚫 المنصة غير مسؤولة قانونياً عن:</p>
                                <ul class="list-disc list-inside space-y-1 text-sm">
                                    <li>جودة الخدمات المقدمة (ممتازة، جيدة، متوسطة، أو رديئة)</li>
                                    <li>دقة المعلومات المدخلة من قبل العملاء</li>
                                    <li>أي أضرار أو خسائر ناتجة عن تنفيذ الخدمة</li>
                                    <li>التزامات أو تصرفات مقدمي الخدمات</li>
                                    <li>أرقام هواتف خاطئة أو معلومات ناقصة</li>
                                    <li>النزاعات أو الخلافات بين الطرفين</li>
                                </ul>
                            </div>

                            <!-- لكننا نهتم بالجودة -->
                            <div class="bg-green-100 rounded-lg p-4 border-2 border-green-400">
                                <p class="font-bold text-green-900 mb-2 flex items-center gap-2">
