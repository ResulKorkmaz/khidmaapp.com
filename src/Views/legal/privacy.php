<?php
/**
 * Privacy Policy / سياسة الخصوصية
 * Aptiro LLC - KhidmaApp.com
 */

ob_start();
?>

<!-- Hero Section -->
<section class="relative bg-blue-600 text-white overflow-hidden min-h-[40vh] flex items-center">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 50px 50px;"></div>
    
    <!-- Decorative Orbs -->
    <div class="absolute top-0 right-0 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>
    
    <div class="container-custom relative z-10 py-16 md:py-20">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Breadcrumb -->
            <nav class="mb-6 text-sm" aria-label="breadcrumb">
                <ol class="flex items-center justify-center gap-2 text-blue-200">
                    <li><a href="/" class="hover:text-white transition-colors duration-200 font-medium">الرئيسية</a></li>
                    <li class="text-blue-300">/</li>
                    <li class="text-white font-semibold">سياسة الخصوصية</li>
                </ol>
            </nav>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-4 leading-tight">
                سياسة الخصوصية
            </h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                نحن ملتزمون بحماية خصوصيتك وبياناتك الشخصية
            </p>
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="section-padding bg-white">
    <div class="container-custom">
        <div class="max-w-4xl mx-auto">
            <!-- Last Updated -->
            <div class="mb-8 p-4 bg-blue-50 rounded-xl border border-blue-100">
                <p class="text-sm text-gray-600">
                    <span class="font-semibold text-gray-900">آخر تحديث:</span> 
                    <?= date('Y-m-d', strtotime('today')) ?> (<?= date('d/m/Y') ?>)
                </p>
            </div>
            
            <!-- Company Info -->
            <div class="mb-12 p-8 bg-blue-50 rounded-2xl border-2 border-blue-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">📋 معلومات الشركة</h2>
                <div class="space-y-3 text-gray-700 leading-relaxed">
                    <p><strong>المنصة:</strong> خدمة (KhidmaApp.com)</p>
                    <p><strong>المشغل:</strong> Aptiro LLC</p>
                    <p><strong>مقر الشركة:</strong> نيو مكسيكو، الولايات المتحدة الأمريكية (New Mexico, USA)</p>
                    <p><strong>منطقة الخدمة:</strong> المملكة العربية السعودية</p>
                    <p><strong>الموقع:</strong> <a href="https://www.aptiroglobal.com" target="_blank" class="text-blue-600 hover:text-blue-700 font-semibold underline">www.aptiroglobal.com</a></p>
                </div>
            </div>
            
            <!-- Introduction -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">1</span>
                    مقدمة
                </h2>
                <div class="space-y-4 text-lg text-gray-700 leading-relaxed">
                    <p>
                        في <strong class="text-blue-600">Aptiro LLC</strong> ومنصة <strong>خدمة (KhidmaApp.com)</strong>، 
                        نحن نقدّر ثقتك ونلتزم بشدة بحماية خصوصيتك وبياناتك الشخصية.
                    </p>
                    <div class="bg-green-50 border-r-4 border-green-500 p-6 rounded-xl">
                        <p class="font-semibold text-gray-900 mb-2">✓ التزامنا:</p>
                        <p class="text-gray-700">
                            نلتزم بمعايير حماية البيانات وفقاً لـ:
                        </p>
                        <ul class="mt-3 space-y-2 text-gray-700">
                            <li>• قوانين حماية البيانات الأمريكية (US Privacy Laws)</li>
                            <li>• أنظمة حماية البيانات السعودية (PDPL)</li>
                            <li>• أفضل الممارسات العالمية لأمن المعلومات</li>
                        </ul>
                    </div>
                    <p class="bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                        <strong>⚠️ بإستخدام منصتنا</strong> (تقديم طلب، التسجيل، الاشتراك في WhatsApp، أو التصفح)، 
                        فإنك توافق على جمع واستخدام معلوماتك كما هو موضح في هذه السياسة.
                    </p>
                </div>
            </div>
            
            <!-- Information We Collect -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">2</span>
                    المعلومات التي نجمعها
                </h2>
                
                <div class="space-y-6">
                    <!-- Info You Provide -->
                    <div class="bg-blue-50 rounded-xl p-6 border-2 border-blue-200">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-blue-600 me-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            أ) المعلومات التي تقدمها لنا مباشرة
                        </h3>
                        <p class="text-gray-700 mb-3">عند تقديم طلب خدمة أو التسجيل، نجمع:</p>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <span class="text-blue-600 me-2">•</span>
                                <span><strong>رقم الهاتف:</strong> للتواصل معك وإرسال تفاصيل الخدمة</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 me-2">•</span>
                                <span><strong>رقم WhatsApp:</strong> (إن كان مختلفاً) لتسهيل التواصل</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 me-2">•</span>
                                <span><strong>المدينة:</strong> لمطابقتك بمقدمي خدمات محليين</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 me-2">•</span>
                                <span><strong>نوع الخدمة المطلوبة:</strong> مثل سباكة، كهرباء، تنظيف، إلخ</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 me-2">•</span>
                                <span><strong>وصف الخدمة:</strong> تفاصيل إضافية عن احتياجك</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 me-2">•</span>
                                <span><strong>الميزانية المتوقعة:</strong> (اختياري) لمساعدة مقدمي الخدمات</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 me-2">•</span>
                                <span><strong>وقت الخدمة المفضل:</strong> عاجل، خلال 24 ساعة، أو تاريخ محدد</span>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Auto Collected -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-gray-600 me-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"/>
                            </svg>
                            ب) المعلومات التي نجمعها تلقائياً
                        </h3>
                        <p class="text-gray-700 mb-3">عند استخدام الموقع، نجمع تلقائياً:</p>
                        <ul class="space-y-2 text-gray-700">
                            <li>• <strong>عنوان IP:</strong> لتحليل حركة المرور والأمان</li>
                            <li>• <strong>نوع المتصفح ونظام التشغيل:</strong> لتحسين التوافقية</li>
                            <li>• <strong>تاريخ ووقت الزيارة:</strong> لإحصائيات الاستخدام</li>
                            <li>• <strong>الصفحات المزارة:</strong> لفهم اهتمامات المستخدمين</li>
                            <li>• <strong>معلومات الجهاز:</strong> (نوع الجهاز، دقة الشاشة، اللغة)</li>
                            <li>• <strong>مصدر الزيارة:</strong> (محرك بحث، وسائل تواصل، رابط مباشر)</li>
                        </ul>
                    </div>
                    
                    <!-- Cookies -->
                    <div class="bg-purple-50 rounded-xl p-6 border border-purple-200">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-purple-600 me-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                            ج) ملفات تعريف الارتباط (Cookies)
                        </h3>
                        <p class="text-gray-700">
                            نستخدم Cookies لتحسين تجربتك وحفظ تفضيلاتك. لمزيد من التفاصيل، راجع 
                            <a href="/cookies" class="text-purple-600 hover:text-purple-700 font-semibold underline">سياسة ملفات تعريف الارتباط</a>.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- How We Use Information -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">3</span>
                    كيف نستخدم معلوماتك
                </h2>
                <div class="space-y-4">
                    <p class="text-lg text-gray-700 leading-relaxed mb-4">
                        نستخدم المعلومات التي نجمعها للأغراض التالية فقط:
                    </p>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-green-50 rounded-lg p-5 border border-green-200">
                            <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                                <span class="text-green-600 me-2">✓</span> تقديم الخدمة
                            </h4>
                            <p class="text-sm text-gray-700">معالجة طلبك وربطك بمقدمي الخدمات المناسبين</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-5 border border-green-200">
                            <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                                <span class="text-green-600 me-2">✓</span> التواصل
                            </h4>
                            <p class="text-sm text-gray-700">الاتصال بك عبر الهاتف أو WhatsApp بخصوص طلبك</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-5 border border-green-200">
                            <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                                <span class="text-green-600 me-2">✓</span> التحسين
                            </h4>
                            <p class="text-sm text-gray-700">تطوير وتحسين خدماتنا وتجربة المستخدم</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-5 border border-green-200">
                            <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                                <span class="text-green-600 me-2">✓</span> الإشعارات
                            </h4>
                            <p class="text-sm text-gray-700">إرسال تحديثات مهمة حول طلبك أو المنصة</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-5 border border-green-200">
                            <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                                <span class="text-green-600 me-2">✓</span> الأمان
                            </h4>
                            <p class="text-sm text-gray-700">منع الاحتيال وضمان أمان المنصة والمستخدمين</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-5 border border-green-200">
                            <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                                <span class="text-green-600 me-2">✓</span> الامتثال القانوني
                            </h4>
                            <p class="text-sm text-gray-700">الالتزام بالقوانين السعودية والأمريكية</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Data Sharing -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">4</span>
                    مشاركة البيانات
                </h2>
                <div class="bg-red-50 border-r-4 border-red-500 p-6 rounded-xl mb-6">
                    <p class="font-bold text-red-900 text-lg mb-3">🔒 سياسة صارمة: نحن لا نبيع بياناتك أبداً!</p>
                    <p class="text-gray-700">
                        معلوماتك الشخصية <strong>ليست للبيع</strong>. نحن لا نبيع، نؤجر، أو نتاجر ببياناتك مع أطراف ثالثة لأغراض تسويقية.
                    </p>
                </div>
                
                <p class="text-lg text-gray-700 leading-relaxed mb-4">
                    <strong>قد نشارك معلوماتك فقط مع:</strong>
                </p>
                
                <div class="space-y-4">
                    <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                        <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                            <span class="text-2xl me-3">👨‍🔧</span>
                            1. مقدمي الخدمات (الفنيين/الحرفيين)
                        </h4>
                        <p class="text-gray-700 text-sm">
                            نشارك اسمك، رقم هاتفك، المدينة، نوع الخدمة، ووصف الطلب <strong>فقط</strong> مع مقدمي الخدمات 
                            المناسبين لتمكينهم من الاتصال بك وتقديم عرض أسعار.
                        </p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                            <span class="text-2xl me-3">🔧</span>
                            2. مزودي الخدمات التقنية
                        </h4>
                        <p class="text-gray-700 text-sm mb-2">
                            قد نستخدم خدمات طرف ثالث موثوقة لمساعدتنا في:
                        </p>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li>• <strong>استضافة الموقع</strong> (Web Hosting)</li>
                            <li>• <strong>التحليلات</strong> (Google Analytics أو مشابه)</li>
                            <li>• <strong>إدارة قاعدة البيانات</strong></li>
                            <li>• <strong>خدمات الرسائل</strong> (SMS/WhatsApp APIs)</li>
                        </ul>
                        <p class="text-xs text-gray-600 mt-3">
                            * هذه الشركات ملزمة تعاقدياً بحماية بياناتك واستخدامها فقط للأغراض المحددة.
                        </p>
                    </div>
                    
                    <div class="bg-yellow-50 rounded-xl p-6 border border-yellow-200">
                        <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                            <span class="text-2xl me-3">⚖️</span>
                            3. السلطات القانونية والتنظيمية
                        </h4>
                        <p class="text-gray-700 text-sm">
                            قد نكشف عن معلوماتك إذا كان ذلك <strong>مطلوباً قانونياً</strong> أو للامتثال لـ:
                        </p>
                        <ul class="text-sm text-gray-700 space-y-1 mt-2">
                            <li>• أوامر محكمة أو استدعاءات قانونية</li>
                            <li>• حماية حقوقنا القانونية أو سلامة الآخرين</li>
                            <li>• التحقيق في احتيال أو انتهاك لشروط الاستخدام</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Data Security -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">5</span>
                    أمان وحماية البيانات
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-6">
                    نتخذ أمن بياناتك على محمل الجد. نطبق تدابير أمنية متعددة الطبقات:
                </p>
                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-green-50 rounded-lg p-5 border-2 border-green-300">
                        <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-green-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            تشفير SSL/TLS
                        </h4>
                        <p class="text-sm text-gray-700">جميع البيانات المنقولة مشفرة باستخدام بروتوكولات آمنة</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-5 border-2 border-green-300">
                        <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-green-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            جدران حماية متقدمة
                        </h4>
                        <p class="text-sm text-gray-700">حماية من الهجمات الإلكترونية والوصول غير المصرح</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-5 border-2 border-green-300">
                        <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-green-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                            </svg>
                            وصول محدود
                        </h4>
                        <p class="text-sm text-gray-700">فقط الموظفون المصرح لهم يمكنهم الوصول إلى البيانات الشخصية</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-5 border-2 border-green-300">
                        <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-green-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            مراجعة دورية
                        </h4>
                        <p class="text-sm text-gray-700">فحص منتظم لأنظمة الأمان وتحديث البروتوكولات</p>
                    </div>
                </div>
                <div class="bg-yellow-50 border-r-4 border-yellow-500 p-6 rounded-xl">
                    <p class="text-gray-700 text-sm">
                        <strong class="text-gray-900">⚠️ تنبيه مهم:</strong> 
                        على الرغم من تدابيرنا الأمنية القوية، لا يوجد نظام أمان إلكتروني آمن بنسبة 100٪. 
                        نحن نبذل قصارى جهدنا لحماية بياناتك، لكن لا يمكننا ضمان الأمان المطلق ضد جميع التهديدات الإلكترونية.
                    </p>
                </div>
            </div>
            
            <!-- Your Rights -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">6</span>
                    حقوقك فيما يتعلق ببياناتك
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-4">
                    وفقاً لقوانين حماية البيانات السعودية والأمريكية، لديك الحقوق التالية:
                </p>
                <div class="space-y-3">
                    <div class="bg-blue-50 rounded-lg p-5 border border-blue-200 flex items-start">
                        <span class="text-2xl me-3">📖</span>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">حق الوصول</h4>
                            <p class="text-sm text-gray-700">طلب نسخة من بياناتك الشخصية التي نحتفظ بها</p>
                        </div>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-5 border border-blue-200 flex items-start">
                        <span class="text-2xl me-3">✏️</span>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">حق التصحيح</h4>
                            <p class="text-sm text-gray-700">طلب تصحيح أي معلومات غير دقيقة أو غير كاملة</p>
                        </div>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-5 border border-blue-200 flex items-start">
                        <span class="text-2xl me-3">🗑️</span>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">حق الحذف</h4>
                            <p class="text-sm text-gray-700">طلب حذف بياناتك الشخصية (مع مراعاة الالتزامات القانونية)</p>
                        </div>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-5 border border-blue-200 flex items-start">
                        <span class="text-2xl me-3">⏸️</span>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">حق الاعتراض</h4>
                            <p class="text-sm text-gray-700">الاعتراض على معالجة بياناتك لأغراض معينة</p>
                        </div>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-5 border border-blue-200 flex items-start">
                        <span class="text-2xl me-3">📤</span>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">حق نقل البيانات</h4>
                            <p class="text-sm text-gray-700">طلب نسخة من بياناتك بصيغة قابلة للقراءة الآلية</p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 bg-green-50 border-2 border-green-300 rounded-xl p-6">
                    <p class="text-gray-900 font-semibold mb-2">🤝 كيفية ممارسة حقوقك:</p>
                    <p class="text-gray-700 text-sm">
                        لممارسة أي من هذه الحقوق، يرجى <a href="#contact" class="text-blue-600 hover:text-blue-700 font-semibold underline">الاتصال بنا</a> 
                        عبر البريد الإلكتروني مع تحديد طلبك بوضوح. سنرد على طلبك خلال <strong>30 يوماً</strong> كحد أقصى.
                    </p>
                </div>
            </div>
            
            <!-- Data Retention -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">7</span>
                    الاحتفاظ بالبيانات
                </h2>
                <div class="space-y-4 text-lg text-gray-700 leading-relaxed">
                    <p class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                        <strong class="text-gray-900">مدة الاحتفاظ:</strong> نحتفظ ببياناتك الشخصية <strong>طالما كان ذلك ضرورياً</strong> 
                        لتقديم خدماتنا أو للامتثال للمتطلبات القانونية والتنظيمية.
                    </p>
                    <p class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                        <strong class="text-gray-900">بعد انتهاء الحاجة:</strong> عندما لا تعود هناك حاجة تجارية أو قانونية للاحتفاظ بالبيانات، 
                        سنقوم <strong>بحذفها أو إخفاء هويتها بشكل آمن</strong>.
                    </p>
                    <p class="bg-yellow-50 p-6 rounded-xl border border-yellow-200">
                        <strong class="text-gray-900">⚠️ استثناءات:</strong> قد نحتفظ ببعض البيانات لفترة أطول إذا كان ذلك مطلوباً بموجب القانون 
                        (مثل السجلات المحاسبية، الضريبية، أو لأغراض التقاضي).
                    </p>
                </div>
            </div>
            
            <!-- Children's Privacy -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">8</span>
                    خصوصية الأطفال
                </h2>
                <div class="bg-red-50 border-2 border-red-300 rounded-xl p-6">
                    <p class="text-gray-900 font-bold mb-3">🚫 المنصة غير مخصصة للأطفال</p>
                    <p class="text-gray-700 leading-relaxed mb-3">
                        خدماتنا مخصصة للأفراد البالغين (18 عاماً فما فوق). نحن <strong>لا نجمع عن قصد</strong> 
                        معلومات شخصية من الأطفال دون سن 18 عاماً.
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        إذا علمت أن طفلك قدم معلومات شخصية لنا دون موافقتك، يرجى 
                        <a href="#contact" class="text-red-600 hover:text-red-700 font-semibold underline">الاتصال بنا فوراً</a> 
                        وسنحذف هذه المعلومات من سجلاتنا.
                    </p>
                </div>
            </div>
            
            <!-- Changes to Policy -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">9</span>
                    تغييرات سياسة الخصوصية
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-4">
                    قد نحدّث هذه السياسة من وقت لآخر لتعكس التغييرات في ممارساتنا أو المتطلبات القانونية. 
                    عند إجراء تغييرات جوهرية:
                </p>
                <ul class="space-y-2 text-gray-700 bg-blue-50 rounded-xl p-6 border border-blue-100">
                    <li>• سننشر السياسة المحدثة على هذه الصفحة</li>
                    <li>• سنحدّث تاريخ "آخر تحديث" في أعلى الصفحة</li>
                    <li>• سنرسل إشعاراً عبر البريد الإلكتروني أو WhatsApp (حسب الإمكان)</li>
                    <li>• قد نعرض إشعاراً بارزاً على الموقع</li>
                </ul>
                <p class="text-lg text-gray-700 leading-relaxed mt-4 bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                    <strong>⚠️ مراجعة منتظمة:</strong> ننصحك بمراجعة هذه السياسة بشكل دوري. 
                    استمرارك في استخدام المنصة بعد نشر التعديلات يعني موافقتك على السياسة المحدثة.
                </p>
            </div>
            
            <!-- Contact -->
            <div class="mb-12 p-8 bg-blue-600 rounded-2xl text-white shadow-xl">
                <h2 class="text-2xl font-bold mb-4">📧 اتصل بنا - مسؤول حماية البيانات</h2>
                <p class="text-blue-100 mb-6 leading-relaxed">
                    إذا كان لديك أي أسئلة، استفسارات، أو طلبات بشأن سياسة الخصوصية أو بياناتك الشخصية، 
                    يرجى الاتصال بنا:
                </p>
                
                <!-- Complaint Button -->
                <div>
                    <button onclick="openComplaintModal()" class="inline-flex items-center justify-center bg-red-500 hover:bg-red-600 text-white font-bold px-8 py-4 rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105">
                        <svg class="w-6 h-6 me-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        تقديم شكوى
                    </button>
                </div>
            </div>
            
            <!-- Back Button -->
            <div class="text-center pt-8 border-t-2 border-gray-200">
                <a href="/" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold text-lg transition-colors">
                    <svg class="w-6 h-6 me-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    العودة إلى الصفحة الرئيسية
                </a>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();

// Set page meta
$pageTitle = 'سياسة الخصوصية - خدمة | KhidmaApp.com';
$pageDescription = 'سياسة الخصوصية وحماية البيانات الشخصية في منصة خدمة - نحن ملتزمون بحماية خصوصيتك وفقاً للمعايير الأمريكية والسعودية';
$pageKeywords = 'سياسة الخصوصية, Privacy Policy, حماية البيانات, خدمة, KhidmaApp, Aptiro LLC, PDPL';

include __DIR__ . '/../layouts/base.php';
?>

 * Privacy Policy / سياسة الخصوصية
 * Aptiro LLC - KhidmaApp.com
 */

ob_start();
?>

<!-- Hero Section -->
<section class="relative bg-blue-600 text-white overflow-hidden min-h-[40vh] flex items-center">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 50px 50px;"></div>
    
    <!-- Decorative Orbs -->
    <div class="absolute top-0 right-0 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>
    
    <div class="container-custom relative z-10 py-16 md:py-20">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Breadcrumb -->
            <nav class="mb-6 text-sm" aria-label="breadcrumb">
                <ol class="flex items-center justify-center gap-2 text-blue-200">
                    <li><a href="/" class="hover:text-white transition-colors duration-200 font-medium">الرئيسية</a></li>
                    <li class="text-blue-300">/</li>
                    <li class="text-white font-semibold">سياسة الخصوصية</li>
                </ol>
            </nav>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-4 leading-tight">
                سياسة الخصوصية
            </h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                نحن ملتزمون بحماية خصوصيتك وبياناتك الشخصية
            </p>
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="section-padding bg-white">
    <div class="container-custom">
        <div class="max-w-4xl mx-auto">
            <!-- Last Updated -->
            <div class="mb-8 p-4 bg-blue-50 rounded-xl border border-blue-100">
                <p class="text-sm text-gray-600">
                    <span class="font-semibold text-gray-900">آخر تحديث:</span> 
                    <?= date('Y-m-d', strtotime('today')) ?> (<?= date('d/m/Y') ?>)
                </p>
            </div>
            
            <!-- Company Info -->
            <div class="mb-12 p-8 bg-blue-50 rounded-2xl border-2 border-blue-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">📋 معلومات الشركة</h2>
                <div class="space-y-3 text-gray-700 leading-relaxed">
                    <p><strong>المنصة:</strong> خدمة (KhidmaApp.com)</p>
                    <p><strong>المشغل:</strong> Aptiro LLC</p>
                    <p><strong>مقر الشركة:</strong> نيو مكسيكو، الولايات المتحدة الأمريكية (New Mexico, USA)</p>
                    <p><strong>منطقة الخدمة:</strong> المملكة العربية السعودية</p>
                    <p><strong>الموقع:</strong> <a href="https://www.aptiroglobal.com" target="_blank" class="text-blue-600 hover:text-blue-700 font-semibold underline">www.aptiroglobal.com</a></p>
                </div>
            </div>
            
            <!-- Introduction -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">1</span>
                    مقدمة
                </h2>
                <div class="space-y-4 text-lg text-gray-700 leading-relaxed">
                    <p>
                        في <strong class="text-blue-600">Aptiro LLC</strong> ومنصة <strong>خدمة (KhidmaApp.com)</strong>، 
                        نحن نقدّر ثقتك ونلتزم بشدة بحماية خصوصيتك وبياناتك الشخصية.
                    </p>
                    <div class="bg-green-50 border-r-4 border-green-500 p-6 rounded-xl">
                        <p class="font-semibold text-gray-900 mb-2">✓ التزامنا:</p>
                        <p class="text-gray-700">
                            نلتزم بمعايير حماية البيانات وفقاً لـ:
                        </p>
                        <ul class="mt-3 space-y-2 text-gray-700">
                            <li>• قوانين حماية البيانات الأمريكية (US Privacy Laws)</li>
                            <li>• أنظمة حماية البيانات السعودية (PDPL)</li>
                            <li>• أفضل الممارسات العالمية لأمن المعلومات</li>
                        </ul>
                    </div>
                    <p class="bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                        <strong>⚠️ بإستخدام منصتنا</strong> (تقديم طلب، التسجيل، الاشتراك في WhatsApp، أو التصفح)، 
                        فإنك توافق على جمع واستخدام معلوماتك كما هو موضح في هذه السياسة.
                    </p>
                </div>
            </div>
            
            <!-- Information We Collect -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">2</span>
                    المعلومات التي نجمعها
                </h2>
                
                <div class="space-y-6">
                    <!-- Info You Provide -->
                    <div class="bg-blue-50 rounded-xl p-6 border-2 border-blue-200">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-blue-600 me-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            أ) المعلومات التي تقدمها لنا مباشرة
                        </h3>
                        <p class="text-gray-700 mb-3">عند تقديم طلب خدمة أو التسجيل، نجمع:</p>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <span class="text-blue-600 me-2">•</span>
                                <span><strong>رقم الهاتف:</strong> للتواصل معك وإرسال تفاصيل الخدمة</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 me-2">•</span>
                                <span><strong>رقم WhatsApp:</strong> (إن كان مختلفاً) لتسهيل التواصل</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 me-2">•</span>
                                <span><strong>المدينة:</strong> لمطابقتك بمقدمي خدمات محليين</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 me-2">•</span>
                                <span><strong>نوع الخدمة المطلوبة:</strong> مثل سباكة، كهرباء، تنظيف، إلخ</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 me-2">•</span>
                                <span><strong>وصف الخدمة:</strong> تفاصيل إضافية عن احتياجك</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 me-2">•</span>
                                <span><strong>الميزانية المتوقعة:</strong> (اختياري) لمساعدة مقدمي الخدمات</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 me-2">•</span>
                                <span><strong>وقت الخدمة المفضل:</strong> عاجل، خلال 24 ساعة، أو تاريخ محدد</span>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Auto Collected -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-gray-600 me-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"/>
                            </svg>
                            ب) المعلومات التي نجمعها تلقائياً
                        </h3>
                        <p class="text-gray-700 mb-3">عند استخدام الموقع، نجمع تلقائياً:</p>
                        <ul class="space-y-2 text-gray-700">
                            <li>• <strong>عنوان IP:</strong> لتحليل حركة المرور والأمان</li>
                            <li>• <strong>نوع المتصفح ونظام التشغيل:</strong> لتحسين التوافقية</li>
                            <li>• <strong>تاريخ ووقت الزيارة:</strong> لإحصائيات الاستخدام</li>
                            <li>• <strong>الصفحات المزارة:</strong> لفهم اهتمامات المستخدمين</li>
                            <li>• <strong>معلومات الجهاز:</strong> (نوع الجهاز، دقة الشاشة، اللغة)</li>
                            <li>• <strong>مصدر الزيارة:</strong> (محرك بحث، وسائل تواصل، رابط مباشر)</li>
                        </ul>
                    </div>
                    
                    <!-- Cookies -->
                    <div class="bg-purple-50 rounded-xl p-6 border border-purple-200">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-purple-600 me-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                            ج) ملفات تعريف الارتباط (Cookies)
                        </h3>
                        <p class="text-gray-700">
                            نستخدم Cookies لتحسين تجربتك وحفظ تفضيلاتك. لمزيد من التفاصيل، راجع 
                            <a href="/cookies" class="text-purple-600 hover:text-purple-700 font-semibold underline">سياسة ملفات تعريف الارتباط</a>.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- How We Use Information -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">3</span>
                    كيف نستخدم معلوماتك
                </h2>
                <div class="space-y-4">
                    <p class="text-lg text-gray-700 leading-relaxed mb-4">
                        نستخدم المعلومات التي نجمعها للأغراض التالية فقط:
                    </p>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-green-50 rounded-lg p-5 border border-green-200">
                            <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                                <span class="text-green-600 me-2">✓</span> تقديم الخدمة
                            </h4>
                            <p class="text-sm text-gray-700">معالجة طلبك وربطك بمقدمي الخدمات المناسبين</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-5 border border-green-200">
                            <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                                <span class="text-green-600 me-2">✓</span> التواصل
                            </h4>
                            <p class="text-sm text-gray-700">الاتصال بك عبر الهاتف أو WhatsApp بخصوص طلبك</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-5 border border-green-200">
                            <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                                <span class="text-green-600 me-2">✓</span> التحسين
                            </h4>
                            <p class="text-sm text-gray-700">تطوير وتحسين خدماتنا وتجربة المستخدم</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-5 border border-green-200">
                            <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                                <span class="text-green-600 me-2">✓</span> الإشعارات
                            </h4>
                            <p class="text-sm text-gray-700">إرسال تحديثات مهمة حول طلبك أو المنصة</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-5 border border-green-200">
                            <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                                <span class="text-green-600 me-2">✓</span> الأمان
                            </h4>
                            <p class="text-sm text-gray-700">منع الاحتيال وضمان أمان المنصة والمستخدمين</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-5 border border-green-200">
                            <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                                <span class="text-green-600 me-2">✓</span> الامتثال القانوني
                            </h4>
                            <p class="text-sm text-gray-700">الالتزام بالقوانين السعودية والأمريكية</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Data Sharing -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">4</span>
                    مشاركة البيانات
                </h2>
                <div class="bg-red-50 border-r-4 border-red-500 p-6 rounded-xl mb-6">
                    <p class="font-bold text-red-900 text-lg mb-3">🔒 سياسة صارمة: نحن لا نبيع بياناتك أبداً!</p>
                    <p class="text-gray-700">
                        معلوماتك الشخصية <strong>ليست للبيع</strong>. نحن لا نبيع، نؤجر، أو نتاجر ببياناتك مع أطراف ثالثة لأغراض تسويقية.
                    </p>
                </div>
                
                <p class="text-lg text-gray-700 leading-relaxed mb-4">
                    <strong>قد نشارك معلوماتك فقط مع:</strong>
                </p>
                
                <div class="space-y-4">
                    <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                        <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                            <span class="text-2xl me-3">👨‍🔧</span>
                            1. مقدمي الخدمات (الفنيين/الحرفيين)
                        </h4>
                        <p class="text-gray-700 text-sm">
                            نشارك اسمك، رقم هاتفك، المدينة، نوع الخدمة، ووصف الطلب <strong>فقط</strong> مع مقدمي الخدمات 
                            المناسبين لتمكينهم من الاتصال بك وتقديم عرض أسعار.
                        </p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                            <span class="text-2xl me-3">🔧</span>
                            2. مزودي الخدمات التقنية
                        </h4>
                        <p class="text-gray-700 text-sm mb-2">
                            قد نستخدم خدمات طرف ثالث موثوقة لمساعدتنا في:
                        </p>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li>• <strong>استضافة الموقع</strong> (Web Hosting)</li>
                            <li>• <strong>التحليلات</strong> (Google Analytics أو مشابه)</li>
                            <li>• <strong>إدارة قاعدة البيانات</strong></li>
                            <li>• <strong>خدمات الرسائل</strong> (SMS/WhatsApp APIs)</li>
                        </ul>
                        <p class="text-xs text-gray-600 mt-3">
                            * هذه الشركات ملزمة تعاقدياً بحماية بياناتك واستخدامها فقط للأغراض المحددة.
                        </p>
                    </div>
                    
                    <div class="bg-yellow-50 rounded-xl p-6 border border-yellow-200">
                        <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                            <span class="text-2xl me-3">⚖️</span>
                            3. السلطات القانونية والتنظيمية
                        </h4>
                        <p class="text-gray-700 text-sm">
                            قد نكشف عن معلوماتك إذا كان ذلك <strong>مطلوباً قانونياً</strong> أو للامتثال لـ:
                        </p>
                        <ul class="text-sm text-gray-700 space-y-1 mt-2">
                            <li>• أوامر محكمة أو استدعاءات قانونية</li>
                            <li>• حماية حقوقنا القانونية أو سلامة الآخرين</li>
                            <li>• التحقيق في احتيال أو انتهاك لشروط الاستخدام</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Data Security -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">5</span>
                    أمان وحماية البيانات
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-6">
                    نتخذ أمن بياناتك على محمل الجد. نطبق تدابير أمنية متعددة الطبقات:
                </p>
                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-green-50 rounded-lg p-5 border-2 border-green-300">
                        <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-green-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            تشفير SSL/TLS
                        </h4>
                        <p class="text-sm text-gray-700">جميع البيانات المنقولة مشفرة باستخدام بروتوكولات آمنة</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-5 border-2 border-green-300">
                        <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-green-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            جدران حماية متقدمة
                        </h4>
                        <p class="text-sm text-gray-700">حماية من الهجمات الإلكترونية والوصول غير المصرح</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-5 border-2 border-green-300">
                        <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-green-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                            </svg>
                            وصول محدود
                        </h4>
                        <p class="text-sm text-gray-700">فقط الموظفون المصرح لهم يمكنهم الوصول إلى البيانات الشخصية</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-5 border-2 border-green-300">
                        <h4 class="font-bold text-gray-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-green-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            مراجعة دورية
                        </h4>
                        <p class="text-sm text-gray-700">فحص منتظم لأنظمة الأمان وتحديث البروتوكولات</p>
                    </div>
                </div>
                <div class="bg-yellow-50 border-r-4 border-yellow-500 p-6 rounded-xl">
                    <p class="text-gray-700 text-sm">
                        <strong class="text-gray-900">⚠️ تنبيه مهم:</strong> 
                        على الرغم من تدابيرنا الأمنية القوية، لا يوجد نظام أمان إلكتروني آمن بنسبة 100٪. 
                        نحن نبذل قصارى جهدنا لحماية بياناتك، لكن لا يمكننا ضمان الأمان المطلق ضد جميع التهديدات الإلكترونية.
                    </p>
                </div>
            </div>
            
            <!-- Your Rights -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">6</span>
                    حقوقك فيما يتعلق ببياناتك
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-4">
                    وفقاً لقوانين حماية البيانات السعودية والأمريكية، لديك الحقوق التالية:
                </p>
                <div class="space-y-3">
                    <div class="bg-blue-50 rounded-lg p-5 border border-blue-200 flex items-start">
                        <span class="text-2xl me-3">📖</span>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">حق الوصول</h4>
                            <p class="text-sm text-gray-700">طلب نسخة من بياناتك الشخصية التي نحتفظ بها</p>
                        </div>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-5 border border-blue-200 flex items-start">
                        <span class="text-2xl me-3">✏️</span>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">حق التصحيح</h4>
                            <p class="text-sm text-gray-700">طلب تصحيح أي معلومات غير دقيقة أو غير كاملة</p>
                        </div>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-5 border border-blue-200 flex items-start">
                        <span class="text-2xl me-3">🗑️</span>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">حق الحذف</h4>
                            <p class="text-sm text-gray-700">طلب حذف بياناتك الشخصية (مع مراعاة الالتزامات القانونية)</p>
                        </div>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-5 border border-blue-200 flex items-start">
                        <span class="text-2xl me-3">⏸️</span>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">حق الاعتراض</h4>
                            <p class="text-sm text-gray-700">الاعتراض على معالجة بياناتك لأغراض معينة</p>
                        </div>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-5 border border-blue-200 flex items-start">
                        <span class="text-2xl me-3">📤</span>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">حق نقل البيانات</h4>
                            <p class="text-sm text-gray-700">طلب نسخة من بياناتك بصيغة قابلة للقراءة الآلية</p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 bg-green-50 border-2 border-green-300 rounded-xl p-6">
                    <p class="text-gray-900 font-semibold mb-2">🤝 كيفية ممارسة حقوقك:</p>
                    <p class="text-gray-700 text-sm">
                        لممارسة أي من هذه الحقوق، يرجى <a href="#contact" class="text-blue-600 hover:text-blue-700 font-semibold underline">الاتصال بنا</a> 
                        عبر البريد الإلكتروني مع تحديد طلبك بوضوح. سنرد على طلبك خلال <strong>30 يوماً</strong> كحد أقصى.
                    </p>
                </div>
            </div>
            
            <!-- Data Retention -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">7</span>
                    الاحتفاظ بالبيانات
                </h2>
                <div class="space-y-4 text-lg text-gray-700 leading-relaxed">
                    <p class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                        <strong class="text-gray-900">مدة الاحتفاظ:</strong> نحتفظ ببياناتك الشخصية <strong>طالما كان ذلك ضرورياً</strong> 
                        لتقديم خدماتنا أو للامتثال للمتطلبات القانونية والتنظيمية.
                    </p>
                    <p class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                        <strong class="text-gray-900">بعد انتهاء الحاجة:</strong> عندما لا تعود هناك حاجة تجارية أو قانونية للاحتفاظ بالبيانات، 
                        سنقوم <strong>بحذفها أو إخفاء هويتها بشكل آمن</strong>.
                    </p>
                    <p class="bg-yellow-50 p-6 rounded-xl border border-yellow-200">
                        <strong class="text-gray-900">⚠️ استثناءات:</strong> قد نحتفظ ببعض البيانات لفترة أطول إذا كان ذلك مطلوباً بموجب القانون 
                        (مثل السجلات المحاسبية، الضريبية، أو لأغراض التقاضي).
                    </p>
                </div>
            </div>
            
            <!-- Children's Privacy -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">8</span>
                    خصوصية الأطفال
                </h2>
                <div class="bg-red-50 border-2 border-red-300 rounded-xl p-6">
                    <p class="text-gray-900 font-bold mb-3">🚫 المنصة غير مخصصة للأطفال</p>
                    <p class="text-gray-700 leading-relaxed mb-3">
                        خدماتنا مخصصة للأفراد البالغين (18 عاماً فما فوق). نحن <strong>لا نجمع عن قصد</strong> 
                        معلومات شخصية من الأطفال دون سن 18 عاماً.
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        إذا علمت أن طفلك قدم معلومات شخصية لنا دون موافقتك، يرجى 
                        <a href="#contact" class="text-red-600 hover:text-red-700 font-semibold underline">الاتصال بنا فوراً</a> 
                        وسنحذف هذه المعلومات من سجلاتنا.
                    </p>
                </div>
            </div>
            
            <!-- Changes to Policy -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">9</span>
                    تغييرات سياسة الخصوصية
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-4">
                    قد نحدّث هذه السياسة من وقت لآخر لتعكس التغييرات في ممارساتنا أو المتطلبات القانونية. 
                    عند إجراء تغييرات جوهرية:
                </p>
                <ul class="space-y-2 text-gray-700 bg-blue-50 rounded-xl p-6 border border-blue-100">
                    <li>• سننشر السياسة المحدثة على هذه الصفحة</li>
                    <li>• سنحدّث تاريخ "آخر تحديث" في أعلى الصفحة</li>
                    <li>• سنرسل إشعاراً عبر البريد الإلكتروني أو WhatsApp (حسب الإمكان)</li>
                    <li>• قد نعرض إشعاراً بارزاً على الموقع</li>
                </ul>
                <p class="text-lg text-gray-700 leading-relaxed mt-4 bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                    <strong>⚠️ مراجعة منتظمة:</strong> ننصحك بمراجعة هذه السياسة بشكل دوري. 
                    استمرارك في استخدام المنصة بعد نشر التعديلات يعني موافقتك على السياسة المحدثة.
                </p>
            </div>
            
            <!-- Contact -->
            <div class="mb-12 p-8 bg-blue-600 rounded-2xl text-white shadow-xl">
                <h2 class="text-2xl font-bold mb-4">📧 اتصل بنا - مسؤول حماية البيانات</h2>
                <p class="text-blue-100 mb-6 leading-relaxed">
                    إذا كان لديك أي أسئلة، استفسارات، أو طلبات بشأن سياسة الخصوصية أو بياناتك الشخصية، 
                    يرجى الاتصال بنا:
                </p>
                
                <!-- Complaint Button -->
                <div>
                    <button onclick="openComplaintModal()" class="inline-flex items-center justify-center bg-red-500 hover:bg-red-600 text-white font-bold px-8 py-4 rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105">
                        <svg class="w-6 h-6 me-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        تقديم شكوى
                    </button>
                </div>
            </div>
            
            <!-- Back Button -->
            <div class="text-center pt-8 border-t-2 border-gray-200">
                <a href="/" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold text-lg transition-colors">
                    <svg class="w-6 h-6 me-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    العودة إلى الصفحة الرئيسية
                </a>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();

// Set page meta
$pageTitle = 'سياسة الخصوصية - خدمة | KhidmaApp.com';
$pageDescription = 'سياسة الخصوصية وحماية البيانات الشخصية في منصة خدمة - نحن ملتزمون بحماية خصوصيتك وفقاً للمعايير الأمريكية والسعودية';
$pageKeywords = 'سياسة الخصوصية, Privacy Policy, حماية البيانات, خدمة, KhidmaApp, Aptiro LLC, PDPL';

include __DIR__ . '/../layouts/base.php';
?>


