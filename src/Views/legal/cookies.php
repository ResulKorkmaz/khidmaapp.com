<?php
/**
 * Cookies Policy / سياسة ملفات تعريف الارتباط
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
                    <li class="text-white font-semibold">ملفات تعريف الارتباط</li>
                </ol>
            </nav>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-4 leading-tight">
                سياسة ملفات تعريف الارتباط
            </h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                كيف نستخدم Cookies على منصتنا
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
            <div class="mb-12 p-6 bg-blue-50 rounded-2xl border border-blue-200">
                <p class="text-gray-700 leading-relaxed">
                    <strong>المنصة:</strong> خدمة (KhidmaApp.com) | 
                    <strong>المشغل:</strong> Aptiro LLC (New Mexico, USA) | 
                    <strong>الموقع:</strong> 
                    <a href="https://www.aptiroglobal.com" target="_blank" class="text-blue-600 hover:text-blue-700 font-semibold underline">www.aptiroglobal.com</a>
                </p>
            </div>
            
            <!-- What Are Cookies -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">1</span>
                    ما هي ملفات تعريف الارتباط (Cookies)؟
                </h2>
                <div class="space-y-4 text-lg text-gray-700 leading-relaxed">
                    <p>
                        ملفات تعريف الارتباط (Cookies) هي <strong>ملفات نصية صغيرة</strong> يتم تخزينها على جهازك 
                        (الكمبيوتر، الهاتف، أو الجهاز اللوحي) عند زيارة موقع ويب.
                    </p>
                    <div class="bg-green-50 border-r-4 border-green-500 p-6 rounded-xl">
                        <p class="font-semibold text-gray-900 mb-3">✓ الغرض من Cookies:</p>
                        <ul class="space-y-2 text-gray-700">
                            <li>• تحسين تجربتك على الموقع</li>
                            <li>• تذكر تفضيلاتك وإعداداتك</li>
                            <li>• تحليل كيفية استخدام الموقع</li>
                            <li>• تمكين وظائف معينة</li>
                        </ul>
                    </div>
                    <div class="bg-blue-50 p-6 rounded-xl border border-blue-100">
                        <p class="text-gray-700">
                            <strong class="text-gray-900">🔒 ملاحظة أمنية:</strong> 
                            ملفات تعريف الارتباط <strong>لا تحتوي على</strong> معلومات شخصية قابلة للتحديد ولا يمكنها 
                            <strong>الوصول إلى جهازك أو ملفاتك</strong>. إنها آمنة تماماً ولا تسبب أي ضرر لجهازك.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Types of Cookies -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">2</span>
                    أنواع Cookies التي نستخدمها
                </h2>
                
                <div class="space-y-6">
                    <!-- Essential Cookies -->
                    <div class="bg-red-50 rounded-2xl p-6 border-2 border-red-200">
                        <div class="flex items-start mb-4">
                            <div class="w-12 h-12 bg-red-600 text-white rounded-lg flex items-center justify-center text-xl font-bold me-4 flex-shrink-0">
                                1
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">ملفات تعريف الارتباط الضرورية</h3>
                                <p class="text-gray-700 mb-3">
                                    <strong>ضرورية لتشغيل الموقع</strong> - لا يمكن تعطيلها
                                </p>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg p-5 mb-3">
                            <p class="text-gray-700 leading-relaxed mb-3">
                                هذه الملفات <strong>مطلوبة</strong> لتمكينك من استخدام الوظائف الأساسية للمنصة:
                            </p>
                            <ul class="space-y-2 text-gray-700 text-sm">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-red-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <strong>Session Cookies:</strong> إدارة جلسة التصفح الخاصة بك
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-red-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <strong>CSRF Tokens:</strong> حماية النماذج من الهجمات الإلكترونية
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-red-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <strong>Login State:</strong> تذكر حالة تسجيل الدخول للمسؤولين
                                </li>
                            </ul>
                        </div>
                        <div class="bg-red-100 rounded-lg p-4 text-sm text-red-900">
                            <strong>⚠️ مهم:</strong> بدون هذه الملفات، لن تتمكن من استخدام الموقع بشكل صحيح.
                        </div>
                    </div>
                    
                    <!-- Performance Cookies -->
                    <div class="bg-blue-50 rounded-2xl p-6 border-2 border-blue-200">
                        <div class="flex items-start mb-4">
                            <div class="w-12 h-12 bg-blue-600 text-white rounded-lg flex items-center justify-center text-xl font-bold me-4 flex-shrink-0">
                                2
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">ملفات الأداء والتحليلات</h3>
                                <p class="text-gray-700 mb-3">
                                    لفهم كيفية استخدام الموقع وتحسين الأداء
                                </p>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg p-5 mb-3">
                            <p class="text-gray-700 leading-relaxed mb-3">
                                تساعدنا في تحسين المنصة من خلال:
                            </p>
                            <ul class="space-y-2 text-gray-700 text-sm">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                    </svg>
                                    <span>تحليل حركة المرور وعدد الزوار</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                    </svg>
                                    <span>معرفة الصفحات الأكثر زيارة</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                    </svg>
                                    <span>قياس سرعة وأداء الصفحات</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                    </svg>
                                    <span>تحديد الأخطاء والمشاكل التقنية</span>
                                </li>
                            </ul>
                        </div>
                        <div class="bg-blue-100 rounded-lg p-4 text-sm text-gray-700">
                            <strong>ℹ️ ملاحظة:</strong> البيانات المجمعة <strong>مجهولة المصدر</strong> ولا تحدد هويتك الشخصية.
                        </div>
                    </div>
                    
                    <!-- Functionality Cookies -->
                    <div class="bg-purple-50 rounded-2xl p-6 border-2 border-purple-200">
                        <div class="flex items-start mb-4">
                            <div class="w-12 h-12 bg-purple-600 text-white rounded-lg flex items-center justify-center text-xl font-bold me-4 flex-shrink-0">
                                3
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">ملفات الوظائف والتخصيص</h3>
                                <p class="text-gray-700 mb-3">
                                    لتذكر تفضيلاتك وتخصيص تجربتك
                                </p>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg p-5 mb-3">
                            <ul class="space-y-2 text-gray-700 text-sm">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>تذكر تفضيلات اللغة (العربية/English)</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>حفظ البيانات المدخلة في النماذج مؤقتاً</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>تذكر الخيارات التي اخترتها سابقاً</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Third-Party Cookies -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">3</span>
                    ملفات تعريف الارتباط من جهات خارجية
                </h2>
                <div class="space-y-4 text-lg text-gray-700 leading-relaxed">
                    <p>
                        قد نستخدم خدمات من شركات خارجية موثوقة تضع ملفات تعريف الارتباط الخاصة بها:
                    </p>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                            <h4 class="font-bold text-gray-900 mb-2">📊 Google Analytics</h4>
                            <p class="text-sm text-gray-700">لتحليل حركة المرور والإحصائيات</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                            <h4 class="font-bold text-gray-900 mb-2">☁️ خدمات الاستضافة</h4>
                            <p class="text-sm text-gray-700">لتقديم المحتوى بسرعة وأمان</p>
                        </div>
                    </div>
                    <div class="bg-yellow-50 p-6 rounded-xl border border-yellow-200">
                        <p class="text-gray-700">
                            <strong class="text-gray-900">ℹ️ ملاحظة:</strong> 
                            هذه الشركات الخارجية لها سياسات خصوصية خاصة بها. نحن نختار بعناية الشركات التي تلتزم بمعايير عالية لحماية البيانات.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Managing Cookies -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">4</span>
                    كيفية إدارة والتحكم في Cookies
                </h2>
                
                <div class="space-y-6">
                    <p class="text-lg text-gray-700 leading-relaxed">
                        لديك <strong>السيطرة الكاملة</strong> على ملفات تعريف الارتباط. يمكنك إدارتها من خلال متصفحك:
                    </p>
                    
                    <!-- Browser Settings -->
                    <div class="bg-blue-50 rounded-xl p-6 border-2 border-blue-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">🌐 إعدادات المتصفحات الشائعة:</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-white rounded-lg p-5 border border-blue-200">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold me-3">
                                        G
                                    </div>
                                    <h4 class="font-bold text-gray-900">Google Chrome</h4>
                                </div>
                                <p class="text-sm text-gray-700 mb-2">
                                    <strong>الإعدادات</strong> → <strong>الخصوصية والأمان</strong> → <strong>ملفات تعريف الارتباط وبيانات الموقع الأخرى</strong>
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-5 border border-blue-200">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center text-white font-bold me-3">
                                        F
                                    </div>
                                    <h4 class="font-bold text-gray-900">Mozilla Firefox</h4>
                                </div>
                                <p class="text-sm text-gray-700">
                                    <strong>الإعدادات</strong> → <strong>الخصوصية والأمان</strong> → <strong>ملفات تعريف الارتباط وبيانات المواقع</strong>
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-5 border border-blue-200">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 bg-blue-400 rounded-lg flex items-center justify-center text-white font-bold me-3">
                                        S
                                    </div>
                                    <h4 class="font-bold text-gray-900">Safari</h4>
                                </div>
                                <p class="text-sm text-gray-700">
                                    <strong>التفضيلات</strong> → <strong>الخصوصية</strong> → <strong>إدارة بيانات الموقع</strong>
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-5 border border-blue-200">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 bg-blue-700 rounded-lg flex items-center justify-center text-white font-bold me-3">
                                        E
                                    </div>
                                    <h4 class="font-bold text-gray-900">Microsoft Edge</h4>
                                </div>
                                <p class="text-sm text-gray-700">
                                    <strong>الإعدادات</strong> → <strong>ملفات تعريف الارتباط وأذونات الموقع</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- What You Can Do -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">⚙️ ما يمكنك فعله:</h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <span class="text-blue-600 text-xl me-3">✓</span>
                                <span><strong>عرض Cookies:</strong> رؤية جميع ملفات تعريف الارتباط المخزنة</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 text-xl me-3">✓</span>
                                <span><strong>حذف Cookies:</strong> إزالة ملفات تعريف الارتباط الموجودة</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 text-xl me-3">✓</span>
                                <span><strong>حظر Cookies:</strong> منع مواقع معينة من وضع Cookies</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 text-xl me-3">✓</span>
                                <span><strong>تعطيل Cookies طرف ثالث:</strong> السماح فقط بـ Cookies من الموقع نفسه</span>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Warning -->
                    <div class="bg-red-50 border-r-4 border-red-600 p-6 rounded-xl">
                        <h3 class="font-bold text-red-900 text-lg mb-3">⚠️ تحذير مهم</h3>
                        <p class="text-gray-700 leading-relaxed">
                            إذا قمت بتعطيل أو حذف ملفات تعريف الارتباط الضرورية، <strong>قد لا تعمل بعض ميزات الموقع بشكل صحيح</strong>. 
                            على سبيل المثال:
                        </p>
                        <ul class="mt-3 space-y-2 text-gray-700 text-sm">
                            <li>• قد لا تتمكن من تقديم طلبات خدمة</li>
                            <li>• قد تفقد البيانات المدخلة في النماذج</li>
                            <li>• قد يتم تسجيل خروجك تلقائياً (للمسؤولين)</li>
                            <li>• قد تحتاج إلى إعادة إدخال تفضيلاتك في كل زيارة</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Changes to Policy -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">5</span>
                    تحديثات السياسة
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed bg-gray-50 p-6 rounded-xl border border-gray-200">
                    قد نقوم بتحديث سياسة Cookies هذه من وقت لآخر. سيتم نشر أي تغييرات على هذه الصفحة مع 
                    تحديث تاريخ "آخر تحديث" في الأعلى. ننصحك بمراجعة هذه السياسة بشكل دوري.
                </p>
            </div>
            
            <!-- More Information -->
            <div class="mb-12 bg-indigo-50 rounded-2xl p-8 border-2 border-indigo-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">📚 مزيد من المعلومات</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    لمزيد من التفاصيل حول كيفية حماية خصوصيتك ومعالجة بياناتك، يرجى مراجعة:
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="/privacy" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors shadow-md">
                        <svg class="w-5 h-5 me-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        سياسة الخصوصية
                    </a>
                    <a href="/terms" class="inline-flex items-center bg-gray-700 hover:bg-gray-800 text-white font-semibold px-6 py-3 rounded-lg transition-colors shadow-md">
                        <svg class="w-5 h-5 me-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                        </svg>
                        شروط الاستخدام
                    </a>
                </div>
            </div>
            
            <!-- Contact -->
            <div class="mb-12 p-8 bg-blue-600 rounded-2xl text-white shadow-xl">
                <h2 class="text-2xl font-bold mb-4">📞 هل لديك أسئلة؟</h2>
                <p class="text-blue-100 mb-6 leading-relaxed">
                    إذا كان لديك أي أسئلة حول استخدامنا لملفات تعريف الارتباط، نحن هنا لمساعدتك:
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
$pageTitle = 'سياسة ملفات تعريف الارتباط (Cookies) - خدمة | KhidmaApp.com';
$pageDescription = 'سياسة استخدام ملفات تعريف الارتباط (Cookies) في منصة خدمة - فهم كيفية استخدامنا للـ Cookies وكيفية إدارتها';
$pageKeywords = 'ملفات تعريف الارتباط, Cookies Policy, خدمة, KhidmaApp, Aptiro LLC';

include __DIR__ . '/../layouts/base.php';
?>

 * Cookies Policy / سياسة ملفات تعريف الارتباط
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
                    <li class="text-white font-semibold">ملفات تعريف الارتباط</li>
                </ol>
            </nav>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-4 leading-tight">
                سياسة ملفات تعريف الارتباط
            </h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                كيف نستخدم Cookies على منصتنا
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
            <div class="mb-12 p-6 bg-blue-50 rounded-2xl border border-blue-200">
                <p class="text-gray-700 leading-relaxed">
                    <strong>المنصة:</strong> خدمة (KhidmaApp.com) | 
                    <strong>المشغل:</strong> Aptiro LLC (New Mexico, USA) | 
                    <strong>الموقع:</strong> 
                    <a href="https://www.aptiroglobal.com" target="_blank" class="text-blue-600 hover:text-blue-700 font-semibold underline">www.aptiroglobal.com</a>
                </p>
            </div>
            
            <!-- What Are Cookies -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">1</span>
                    ما هي ملفات تعريف الارتباط (Cookies)؟
                </h2>
                <div class="space-y-4 text-lg text-gray-700 leading-relaxed">
                    <p>
                        ملفات تعريف الارتباط (Cookies) هي <strong>ملفات نصية صغيرة</strong> يتم تخزينها على جهازك 
                        (الكمبيوتر، الهاتف، أو الجهاز اللوحي) عند زيارة موقع ويب.
                    </p>
                    <div class="bg-green-50 border-r-4 border-green-500 p-6 rounded-xl">
                        <p class="font-semibold text-gray-900 mb-3">✓ الغرض من Cookies:</p>
                        <ul class="space-y-2 text-gray-700">
                            <li>• تحسين تجربتك على الموقع</li>
                            <li>• تذكر تفضيلاتك وإعداداتك</li>
                            <li>• تحليل كيفية استخدام الموقع</li>
                            <li>• تمكين وظائف معينة</li>
                        </ul>
                    </div>
                    <div class="bg-blue-50 p-6 rounded-xl border border-blue-100">
                        <p class="text-gray-700">
                            <strong class="text-gray-900">🔒 ملاحظة أمنية:</strong> 
                            ملفات تعريف الارتباط <strong>لا تحتوي على</strong> معلومات شخصية قابلة للتحديد ولا يمكنها 
                            <strong>الوصول إلى جهازك أو ملفاتك</strong>. إنها آمنة تماماً ولا تسبب أي ضرر لجهازك.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Types of Cookies -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">2</span>
                    أنواع Cookies التي نستخدمها
                </h2>
                
                <div class="space-y-6">
                    <!-- Essential Cookies -->
                    <div class="bg-red-50 rounded-2xl p-6 border-2 border-red-200">
                        <div class="flex items-start mb-4">
                            <div class="w-12 h-12 bg-red-600 text-white rounded-lg flex items-center justify-center text-xl font-bold me-4 flex-shrink-0">
                                1
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">ملفات تعريف الارتباط الضرورية</h3>
                                <p class="text-gray-700 mb-3">
                                    <strong>ضرورية لتشغيل الموقع</strong> - لا يمكن تعطيلها
                                </p>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg p-5 mb-3">
                            <p class="text-gray-700 leading-relaxed mb-3">
                                هذه الملفات <strong>مطلوبة</strong> لتمكينك من استخدام الوظائف الأساسية للمنصة:
                            </p>
                            <ul class="space-y-2 text-gray-700 text-sm">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-red-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <strong>Session Cookies:</strong> إدارة جلسة التصفح الخاصة بك
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-red-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <strong>CSRF Tokens:</strong> حماية النماذج من الهجمات الإلكترونية
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-red-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <strong>Login State:</strong> تذكر حالة تسجيل الدخول للمسؤولين
                                </li>
                            </ul>
                        </div>
                        <div class="bg-red-100 rounded-lg p-4 text-sm text-red-900">
                            <strong>⚠️ مهم:</strong> بدون هذه الملفات، لن تتمكن من استخدام الموقع بشكل صحيح.
                        </div>
                    </div>
                    
                    <!-- Performance Cookies -->
                    <div class="bg-blue-50 rounded-2xl p-6 border-2 border-blue-200">
                        <div class="flex items-start mb-4">
                            <div class="w-12 h-12 bg-blue-600 text-white rounded-lg flex items-center justify-center text-xl font-bold me-4 flex-shrink-0">
                                2
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">ملفات الأداء والتحليلات</h3>
                                <p class="text-gray-700 mb-3">
                                    لفهم كيفية استخدام الموقع وتحسين الأداء
                                </p>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg p-5 mb-3">
                            <p class="text-gray-700 leading-relaxed mb-3">
                                تساعدنا في تحسين المنصة من خلال:
                            </p>
                            <ul class="space-y-2 text-gray-700 text-sm">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                    </svg>
                                    <span>تحليل حركة المرور وعدد الزوار</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                    </svg>
                                    <span>معرفة الصفحات الأكثر زيارة</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                    </svg>
                                    <span>قياس سرعة وأداء الصفحات</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                    </svg>
                                    <span>تحديد الأخطاء والمشاكل التقنية</span>
                                </li>
                            </ul>
                        </div>
                        <div class="bg-blue-100 rounded-lg p-4 text-sm text-gray-700">
                            <strong>ℹ️ ملاحظة:</strong> البيانات المجمعة <strong>مجهولة المصدر</strong> ولا تحدد هويتك الشخصية.
                        </div>
                    </div>
                    
                    <!-- Functionality Cookies -->
                    <div class="bg-purple-50 rounded-2xl p-6 border-2 border-purple-200">
                        <div class="flex items-start mb-4">
                            <div class="w-12 h-12 bg-purple-600 text-white rounded-lg flex items-center justify-center text-xl font-bold me-4 flex-shrink-0">
                                3
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">ملفات الوظائف والتخصيص</h3>
                                <p class="text-gray-700 mb-3">
                                    لتذكر تفضيلاتك وتخصيص تجربتك
                                </p>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg p-5 mb-3">
                            <ul class="space-y-2 text-gray-700 text-sm">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>تذكر تفضيلات اللغة (العربية/English)</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>حفظ البيانات المدخلة في النماذج مؤقتاً</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>تذكر الخيارات التي اخترتها سابقاً</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Third-Party Cookies -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">3</span>
                    ملفات تعريف الارتباط من جهات خارجية
                </h2>
                <div class="space-y-4 text-lg text-gray-700 leading-relaxed">
                    <p>
                        قد نستخدم خدمات من شركات خارجية موثوقة تضع ملفات تعريف الارتباط الخاصة بها:
                    </p>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                            <h4 class="font-bold text-gray-900 mb-2">📊 Google Analytics</h4>
                            <p class="text-sm text-gray-700">لتحليل حركة المرور والإحصائيات</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                            <h4 class="font-bold text-gray-900 mb-2">☁️ خدمات الاستضافة</h4>
                            <p class="text-sm text-gray-700">لتقديم المحتوى بسرعة وأمان</p>
                        </div>
                    </div>
                    <div class="bg-yellow-50 p-6 rounded-xl border border-yellow-200">
                        <p class="text-gray-700">
                            <strong class="text-gray-900">ℹ️ ملاحظة:</strong> 
                            هذه الشركات الخارجية لها سياسات خصوصية خاصة بها. نحن نختار بعناية الشركات التي تلتزم بمعايير عالية لحماية البيانات.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Managing Cookies -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">4</span>
                    كيفية إدارة والتحكم في Cookies
                </h2>
                
                <div class="space-y-6">
                    <p class="text-lg text-gray-700 leading-relaxed">
                        لديك <strong>السيطرة الكاملة</strong> على ملفات تعريف الارتباط. يمكنك إدارتها من خلال متصفحك:
                    </p>
                    
                    <!-- Browser Settings -->
                    <div class="bg-blue-50 rounded-xl p-6 border-2 border-blue-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">🌐 إعدادات المتصفحات الشائعة:</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-white rounded-lg p-5 border border-blue-200">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold me-3">
                                        G
                                    </div>
                                    <h4 class="font-bold text-gray-900">Google Chrome</h4>
                                </div>
                                <p class="text-sm text-gray-700 mb-2">
                                    <strong>الإعدادات</strong> → <strong>الخصوصية والأمان</strong> → <strong>ملفات تعريف الارتباط وبيانات الموقع الأخرى</strong>
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-5 border border-blue-200">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center text-white font-bold me-3">
                                        F
                                    </div>
                                    <h4 class="font-bold text-gray-900">Mozilla Firefox</h4>
                                </div>
                                <p class="text-sm text-gray-700">
                                    <strong>الإعدادات</strong> → <strong>الخصوصية والأمان</strong> → <strong>ملفات تعريف الارتباط وبيانات المواقع</strong>
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-5 border border-blue-200">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 bg-blue-400 rounded-lg flex items-center justify-center text-white font-bold me-3">
                                        S
                                    </div>
                                    <h4 class="font-bold text-gray-900">Safari</h4>
                                </div>
                                <p class="text-sm text-gray-700">
                                    <strong>التفضيلات</strong> → <strong>الخصوصية</strong> → <strong>إدارة بيانات الموقع</strong>
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-5 border border-blue-200">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 bg-blue-700 rounded-lg flex items-center justify-center text-white font-bold me-3">
                                        E
                                    </div>
                                    <h4 class="font-bold text-gray-900">Microsoft Edge</h4>
                                </div>
                                <p class="text-sm text-gray-700">
                                    <strong>الإعدادات</strong> → <strong>ملفات تعريف الارتباط وأذونات الموقع</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- What You Can Do -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">⚙️ ما يمكنك فعله:</h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <span class="text-blue-600 text-xl me-3">✓</span>
                                <span><strong>عرض Cookies:</strong> رؤية جميع ملفات تعريف الارتباط المخزنة</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 text-xl me-3">✓</span>
                                <span><strong>حذف Cookies:</strong> إزالة ملفات تعريف الارتباط الموجودة</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 text-xl me-3">✓</span>
                                <span><strong>حظر Cookies:</strong> منع مواقع معينة من وضع Cookies</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 text-xl me-3">✓</span>
                                <span><strong>تعطيل Cookies طرف ثالث:</strong> السماح فقط بـ Cookies من الموقع نفسه</span>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Warning -->
                    <div class="bg-red-50 border-r-4 border-red-600 p-6 rounded-xl">
                        <h3 class="font-bold text-red-900 text-lg mb-3">⚠️ تحذير مهم</h3>
                        <p class="text-gray-700 leading-relaxed">
                            إذا قمت بتعطيل أو حذف ملفات تعريف الارتباط الضرورية، <strong>قد لا تعمل بعض ميزات الموقع بشكل صحيح</strong>. 
                            على سبيل المثال:
                        </p>
                        <ul class="mt-3 space-y-2 text-gray-700 text-sm">
                            <li>• قد لا تتمكن من تقديم طلبات خدمة</li>
                            <li>• قد تفقد البيانات المدخلة في النماذج</li>
                            <li>• قد يتم تسجيل خروجك تلقائياً (للمسؤولين)</li>
                            <li>• قد تحتاج إلى إعادة إدخال تفضيلاتك في كل زيارة</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Changes to Policy -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">5</span>
                    تحديثات السياسة
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed bg-gray-50 p-6 rounded-xl border border-gray-200">
                    قد نقوم بتحديث سياسة Cookies هذه من وقت لآخر. سيتم نشر أي تغييرات على هذه الصفحة مع 
                    تحديث تاريخ "آخر تحديث" في الأعلى. ننصحك بمراجعة هذه السياسة بشكل دوري.
                </p>
            </div>
            
            <!-- More Information -->
            <div class="mb-12 bg-indigo-50 rounded-2xl p-8 border-2 border-indigo-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">📚 مزيد من المعلومات</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    لمزيد من التفاصيل حول كيفية حماية خصوصيتك ومعالجة بياناتك، يرجى مراجعة:
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="/privacy" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors shadow-md">
                        <svg class="w-5 h-5 me-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        سياسة الخصوصية
                    </a>
                    <a href="/terms" class="inline-flex items-center bg-gray-700 hover:bg-gray-800 text-white font-semibold px-6 py-3 rounded-lg transition-colors shadow-md">
                        <svg class="w-5 h-5 me-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                        </svg>
                        شروط الاستخدام
                    </a>
                </div>
            </div>
            
            <!-- Contact -->
            <div class="mb-12 p-8 bg-blue-600 rounded-2xl text-white shadow-xl">
                <h2 class="text-2xl font-bold mb-4">📞 هل لديك أسئلة؟</h2>
                <p class="text-blue-100 mb-6 leading-relaxed">
                    إذا كان لديك أي أسئلة حول استخدامنا لملفات تعريف الارتباط، نحن هنا لمساعدتك:
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
$pageTitle = 'سياسة ملفات تعريف الارتباط (Cookies) - خدمة | KhidmaApp.com';
$pageDescription = 'سياسة استخدام ملفات تعريف الارتباط (Cookies) في منصة خدمة - فهم كيفية استخدامنا للـ Cookies وكيفية إدارتها';
$pageKeywords = 'ملفات تعريف الارتباط, Cookies Policy, خدمة, KhidmaApp, Aptiro LLC';

include __DIR__ . '/../layouts/base.php';
?>


