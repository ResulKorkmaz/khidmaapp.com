<?php
/**
 * Terms of Service / Kullanım Şartları
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
                    <li class="text-white font-semibold">شروط الاستخدام</li>
                </ol>
            </nav>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-4 leading-tight">
                شروط الاستخدام
            </h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                يرجى قراءة هذه الشروط بعناية قبل استخدام خدماتنا
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
                <h2 class="text-2xl font-bold text-gray-900 mb-4">معلومات الشركة والمشغل</h2>
                <div class="space-y-4 text-gray-700 leading-relaxed">
                    <p>
                        <strong class="text-gray-900">اسم المنصة:</strong> 
                        <span class="text-blue-600 font-semibold">خدمة (KhidmaApp.com)</span>
                    </p>
                    <p>
                        <strong class="text-gray-900">الشركة المشغلة:</strong> 
                        <span class="font-semibold">Aptiro LLC</span>
                    </p>
                    <p>
                        <strong class="text-gray-900">مقر التسجيل:</strong> 
                        الولايات المتحدة الأمريكية، ولاية نيو مكسيكو (New Mexico, USA)
                    </p>
                    <p>
                        <strong class="text-gray-900">منطقة الخدمة:</strong> 
                        المملكة العربية السعودية
                    </p>
                    <p>
                        <strong class="text-gray-900">الموقع الإلكتروني للشركة:</strong> 
                        <a href="https://www.aptiroglobal.com" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-700 font-semibold underline">
                            www.aptiroglobal.com
                        </a>
                    </p>
                </div>
            </div>
            
            <!-- Acceptance -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">1</span>
                    القبول والموافقة
                </h2>
                <div class="bg-yellow-50 border-r-4 border-yellow-500 p-6 rounded-xl mb-6">
                    <p class="text-gray-900 font-semibold mb-3">⚠️ موافقة ملزمة قانونياً</p>
                    <p class="text-gray-700 leading-relaxed">
                        باستخدام منصة <strong>خدمة</strong> (KhidmaApp.com) أو أي من خدماتها، بما في ذلك على سبيل المثال لا الحصر:
                    </p>
                    <ul class="mt-4 space-y-2 text-gray-700 list-disc list-inside">
                        <li><strong>تقديم طلب خدمة</strong> من خلال النموذج الإلكتروني</li>
                        <li><strong>التسجيل</strong> في الموقع أو إنشاء حساب</li>
                        <li><strong>الاشتراك في قناة WhatsApp</strong> الخاصة بالمنصة</li>
                        <li><strong>تصفح الموقع</strong> أو استخدام أي من ميزاته</li>
                    </ul>
                    <p class="mt-4 text-gray-700 leading-relaxed">
                        فإنك توافق تلقائياً وبشكل كامل وملزم قانونياً على جميع <strong>شروط الاستخدام</strong> و 
                        <a href="/privacy" class="text-blue-600 hover:text-blue-700 font-semibold underline">سياسة الخصوصية</a> و
                        <a href="/cookies" class="text-blue-600 hover:text-blue-700 font-semibold underline">سياسة ملفات تعريف الارتباط</a>.
                    </p>
                    <p class="mt-4 text-gray-900 font-semibold">
                        إذا كنت لا توافق على أي جزء من هذه الشروط، يُرجى التوقف فوراً عن استخدام المنصة.
                    </p>
                </div>
            </div>
            
            <!-- Platform Description -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">2</span>
                    وصف المنصة ونموذج العمل
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-6">
                    <strong>خدمة</strong> هي <strong class="text-blue-600">منصة وساطة إلكترونية (Marketplace Platform)</strong> 
                    تربط بين العملاء الباحثين عن خدمات منزلية وتجارية ومقدمي هذه الخدمات (الحرفيين/الفنيين/الأُستاذ) في المملكة العربية السعودية.
                </p>
                
                <div class="bg-gray-50 rounded-xl p-6 border-2 border-gray-200 mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">📌 دورنا كوسيط:</h3>
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-blue-600 me-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>نستقبل طلبات الخدمة من العملاء</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-blue-600 me-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>نوصل هذه الطلبات إلى مقدمي الخدمات المناسبين</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-blue-600 me-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>نسهل التواصل الأولي بين الطرفين</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-red-50 border-r-4 border-red-600 p-6 rounded-xl mb-6">
                    <h3 class="text-xl font-bold text-red-900 mb-4">⚠️ إخلاء مسؤولية هام</h3>
                    <div class="space-y-3 text-gray-800 leading-relaxed">
                        <p class="font-semibold">
                            نحن <strong>لسنا</strong> مقدمي الخدمة ولسنا طرفاً في أي عقد أو اتفاق بين العميل ومقدم الخدمة.
                        </p>
                        <p>
                            جميع العلاقات التعاقدية، الاتفاقيات، المدفوعات، والمسؤوليات تكون مباشرة بين:
                        </p>
                        <ul class="list-disc list-inside space-y-2 mr-4">
                            <li><strong>العميل</strong> (طالب الخدمة)</li>
                            <li><strong>مقدم الخدمة</strong> (الفني/الحرفي/الأُستاذ)</li>
                        </ul>
                        <p class="font-semibold text-red-900 mt-4">
                            📍 <strong>Aptiro LLC</strong> و <strong>خدمة (KhidmaApp.com)</strong> 
                            لا تتحمل أي مسؤولية قانونية أو مالية عن أي نزاعات، خلافات، أضرار، أو مطالبات تنشأ بين العميل ومقدم الخدمة.
                        </p>
                    </div>
                </div>

                <!-- لكننا نهتم بالجودة -->
                <div class="bg-green-50 border-r-4 border-green-600 p-6 rounded-xl">
                    <h3 class="text-xl font-bold text-green-900 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        ✅ لكننا ملتزمون بتحسين جودة الشبكة
                    </h3>
                    <div class="space-y-3 text-gray-800 leading-relaxed">
                        <p class="font-semibold">
                            رغم أننا لا نتحمل المسؤولية القانونية، إلا أننا <strong class="text-green-700">نلتزم أخلاقياً</strong> بالحفاظ على جودة الشبكة:
                        </p>
                        <ul class="space-y-2 mr-4">
                            <li class="flex items-start gap-2">
                                <span class="text-green-600 font-bold">✓</span>
                                <span><strong>نستقبل شكاويك:</strong> لديك الحق في تقديم شكوى عبر نظام الشكاوى أو WhatsApp</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-green-600 font-bold">✓</span>
                                <span><strong>نراجع الشكاوى بجدية:</strong> نحقق في كل شكوى ونتواصل مع الطرفين</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-green-600 font-bold">✓</span>
                                <span><strong>نحذر المتكررين:</strong> مقدمو الخدمات الذين تتكرر عليهم الشكاوى يحصلون على تحذيرات</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-green-600 font-bold">✓</span>
                                <span><strong>نفصل السيئين:</strong> نفصل نهائياً مقدمي الخدمات سيئي النية أو غير المحترفين</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-green-600 font-bold">✓</span>
                                <span><strong>نمنع الوصول:</strong> المفصولون لا يحصلون على طلبات جديدة من المنصة</span>
                            </li>
                        </ul>
                        <div class="bg-white rounded-lg p-4 mt-4 border-2 border-green-300">
                            <p class="text-sm text-gray-700">
                                <strong class="text-green-900">📝 ملاحظة مهمة:</strong> هذه الإجراءات هي جزء من <strong>التزامنا الأخلاقي</strong> 
                                لتحسين تجربة المستخدمين، لكنها <strong>لا تشكل التزاماً قانونياً</strong> أو ضماناً للجودة. 
                                المسؤولية القانونية الكاملة تبقى بين العميل ومقدم الخدمة.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User Obligations -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">3</span>
                    التزامات المستخدم
                </h2>
                <div class="space-y-4 text-lg text-gray-700 leading-relaxed">
                    <p class="mb-4">عند استخدام المنصة، أنت توافق على:</p>
                    <ul class="space-y-3 bg-blue-50 rounded-xl p-6 border border-blue-100">
                        <li class="flex items-start">
                            <span class="text-blue-600 font-bold me-3">✓</span>
                            <span>تقديم معلومات <strong>صحيحة ودقيقة</strong> عند تقديم طلب الخدمة</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 font-bold me-3">✓</span>
                            <span>استخدام المنصة <strong>للأغراض القانونية فقط</strong> وبما يتوافق مع القوانين السعودية والأمريكية</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 font-bold me-3">✓</span>
                            <span>احترام <strong>حقوق الملكية الفكرية</strong> لشركة Aptiro LLC</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 font-bold me-3">✓</span>
                            <span><strong>عدم محاولة</strong> الوصول غير المصرح به إلى النظام أو البيانات</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 font-bold me-3">✓</span>
                            <span><strong>عدم استخدام</strong> المنصة لنشر محتوى ضار، احتيالي، أو غير قانوني</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 font-bold me-3">✓</span>
                            <span>تحمل <strong>المسؤولية الكاملة</strong> عن أي تعاملات مع مقدمي الخدمات</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Limitation of Liability -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">4</span>
                    حدود المسؤولية وإخلاء الضمانات
                </h2>
                <div class="bg-red-50 border-2 border-red-300 rounded-xl p-8">
                    <h3 class="text-2xl font-bold text-red-900 mb-6">🚫 لا تتحمل Aptiro LLC أو خدمة (KhidmaApp.com) أي مسؤولية عن:</h3>
                    <div class="grid md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-white rounded-lg p-4 border border-red-200">
                            <p class="text-gray-800"><strong>❌ جودة الخدمات:</strong> جودة، كفاءة، أو نتائج الخدمات المقدمة من الفنيين</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-red-200">
                            <p class="text-gray-800"><strong>❌ الأضرار الجسدية:</strong> أي إصابات أو أضرار تحدث أثناء تقديم الخدمة</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-red-200">
                            <p class="text-gray-800"><strong>❌ الأضرار المادية:</strong> أضرار للممتلكات، المنزل، أو المعدات</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-red-200">
                            <p class="text-gray-800"><strong>❌ النزاعات المالية:</strong> خلافات حول الأسعار، الدفع، أو المبالغ المستحقة</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-red-200">
                            <p class="text-gray-800"><strong>❌ الاحتيال:</strong> أي عمليات احتيال من قبل مقدمي الخدمات أو العملاء</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-red-200">
                            <p class="text-gray-800"><strong>❌ فقدان البيانات:</strong> أي خسائر في البيانات أو معلومات العمل</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-red-200">
                            <p class="text-gray-800"><strong>❌ التأخير:</strong> تأخير مقدمي الخدمات أو عدم الحضور</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-red-200">
                            <p class="text-gray-800"><strong>❌ الضمانات:</strong> أي ضمانات صريحة أو ضمنية للخدمات المقدمة</p>
                        </div>
                    </div>
                    <p class="text-gray-800 leading-relaxed font-semibold mb-4">
                        📌 المنصة مقدمة "كما هي" (AS IS) و "حسب التوفر" (AS AVAILABLE) بدون أي ضمانات من أي نوع، 
                        سواء كانت صريحة أو ضمنية.
                    </p>
                    
                    <div class="bg-yellow-50 border-2 border-yellow-400 rounded-lg p-4 mt-4">
                        <p class="text-sm text-gray-800 leading-relaxed">
                            <strong class="text-yellow-900">⚠️ تنبيه مهم للعملاء:</strong> قد تكون المعلومات المدخلة من قبل العملاء غير دقيقة (أرقام هواتف خاطئة، بيانات ناقصة). 
                            نحن لا نتحقق من صحة البيانات المدخلة. <strong>لا يمكن استرداد أي مدفوعات</strong> تمت لمقدمي الخدمات بسبب معلومات خاطئة أو غير دقيقة.
                        </p>
                    </div>
                </div>
                
                <!-- Quality Commitment -->
                <div class="bg-blue-50 border-2 border-blue-300 rounded-xl p-6 mt-6">
                    <h3 class="text-xl font-bold text-blue-900 mb-4">💡 التزامنا تجاه تحسين الجودة</h3>
                    <p class="text-gray-800 leading-relaxed mb-3">
                        رغم عدم مسؤوليتنا القانونية، نعمل بشكل استباقي على <strong>مراقبة جودة الشبكة</strong> من خلال:
                    </p>
                    <ul class="grid md:grid-cols-2 gap-3">
                        <li class="flex items-center gap-2 bg-white p-3 rounded-lg">
                            <span class="text-blue-600">✓</span>
                            <span class="text-sm text-gray-700">نظام شكاوى متاح 24/7</span>
                        </li>
                        <li class="flex items-center gap-2 bg-white p-3 rounded-lg">
                            <span class="text-blue-600">✓</span>
                            <span class="text-sm text-gray-700">مراجعة وتحقيق في الشكاوى</span>
                        </li>
                        <li class="flex items-center gap-2 bg-white p-3 rounded-lg">
                            <span class="text-blue-600">✓</span>
                            <span class="text-sm text-gray-700">تحذير المخالفين</span>
                        </li>
                        <li class="flex items-center gap-2 bg-white p-3 rounded-lg">
                            <span class="text-blue-600">✓</span>
                            <span class="text-sm text-gray-700">فصل نهائي لسيئي النية</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Payment and Fees -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">5</span>
                    الدفع والرسوم
                </h2>
                <div class="space-y-4 text-lg text-gray-700 leading-relaxed">
                    <p class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                        <strong class="text-gray-900">• استخدام المنصة:</strong> حالياً، استخدام منصة خدمة لتقديم طلبات الخدمة <strong>مجاني</strong> للعملاء.
                    </p>
                    <p class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                        <strong class="text-gray-900">• الدفع لمقدمي الخدمات:</strong> جميع المدفوعات مقابل الخدمات تتم 
                        <strong>مباشرة بين العميل ومقدم الخدمة</strong>. لا نتعامل مع أي مدفوعات.
                    </p>
                    <p class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                        <strong class="text-gray-900">• الأسعار:</strong> يحدد مقدم الخدمة الأسعار. ننصح بالاتفاق على السعر 
                        <strong>قبل بدء العمل</strong> وطلب فاتورة رسمية.
                    </p>
                    <p class="bg-yellow-50 p-6 rounded-xl border border-yellow-200">
                        <strong class="text-gray-900">⚠️ رسوم مستقبلية:</strong> نحتفظ بالحق في فرض رسوم على بعض الخدمات أو الميزات في المستقبل. 
                        سيتم إشعارك قبل تطبيق أي رسوم بـ 30 يوماً على الأقل.
                    </p>
                </div>
            </div>
            
            <!-- Intellectual Property -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">6</span>
                    الملكية الفكرية
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-4">
                    جميع الحقوق الفكرية للمنصة، بما في ذلك على سبيل المثال لا الحصر:
                </p>
                <ul class="space-y-3 bg-indigo-50 rounded-xl p-6 border border-indigo-100 text-gray-700">
                    <li>• <strong>الاسم التجاري:</strong> "خدمة" و "KhidmaApp.com"</li>
                    <li>• <strong>الشعار والهوية البصرية</strong></li>
                    <li>• <strong>التصميم والواجهات</strong></li>
                    <li>• <strong>الأكواد البرمجية</strong></li>
                    <li>• <strong>المحتوى النصي والمرئي</strong></li>
                </ul>
                <p class="text-lg text-gray-700 leading-relaxed mt-6">
                    هي ملك حصري لشركة <strong class="text-blue-600">Aptiro LLC</strong> ومحمية بموجب قوانين حقوق النشر 
                    والعلامات التجارية الأمريكية والدولية. يحظر نسخها أو استخدامها دون إذن كتابي مسبق.
                </p>
            </div>
            
            <!-- Governing Law -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">7</span>
                    القانون الحاكم والنزاعات
                </h2>
                <div class="space-y-6">
                    <div class="bg-blue-50 rounded-xl p-6 border-2 border-blue-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">⚖️ الولاية القضائية:</h3>
                        <p class="text-gray-700 leading-relaxed mb-3">
                            تخضع هذه الشروط والأحكام لقوانين <strong>الولايات المتحدة الأمريكية</strong> (ولاية نيو مكسيكو) 
                            وقوانين <strong>المملكة العربية السعودية</strong> فيما يتعلق بالعمليات داخل المملكة.
                        </p>
                        <p class="text-gray-700 leading-relaxed">
                            <strong>النزاعات:</strong> أي نزاع ينشأ عن استخدام المنصة سيخضع للتحكيم أو الوساطة قبل اللجوء إلى المحاكم. 
                            الاختصاص القضائي يكون لمحاكم نيو مكسيكو، الولايات المتحدة الأمريكية.
                        </p>
                    </div>
                    
                    <div class="bg-green-50 rounded-xl p-6 border-2 border-green-300">
                        <h3 class="text-xl font-bold text-green-900 mb-3 flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                            </svg>
                            🤝 حل النزاعات ونظام الشكاوى
                        </h3>
                        <div class="space-y-4 text-gray-700 leading-relaxed">
                            <p>
                                نشجع المستخدمين على حل أي نزاعات مع مقدمي الخدمات بشكل ودي أولاً. 
                                <strong>المسؤولية القانونية للنزاعات تبقى بين الطرفين مباشرة.</strong>
                            </p>
                            
                            <div class="bg-white rounded-lg p-4 border border-green-200">
                                <p class="font-semibold text-green-900 mb-2">📋 خطوات تقديم الشكوى:</p>
                                <ol class="list-decimal list-inside space-y-2 text-sm mr-4">
                                    <li>حاول حل المشكلة مباشرة مع مقدم الخدمة</li>
                                    <li>إذا لم يتم الحل، قدم شكوى عبر <strong>نظام الشكاوى</strong> أو WhatsApp</li>
                                    <li>سنراجع شكواك ونتواصل مع الطرفين لفهم الموقف</li>
                                    <li>قد نحاول <strong>الوساطة</strong> بين الطرفين (دورنا استشاري فقط)</li>
                                    <li>في حالة ثبوت سوء النية أو الإهمال المتكرر، سنتخذ <strong>إجراءات ضد مقدم الخدمة</strong> (تحذير أو فصل نهائي)</li>
                                </ol>
                            </div>
                            
                            <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-3 text-sm">
                                <p class="text-yellow-900">
                                    <strong>⚠️ تنبيه قانوني:</strong> دورنا في الوساطة <strong>استشاري وغير ملزم قانونياً</strong>. 
                                    لا نتحمل مسؤولية قانونية عن النتائج. أي قرارات قانونية يجب أن تتم عبر المحاكم المختصة.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Changes to Terms -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">8</span>
                    تعديل الشروط
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-4">
                    نحتفظ بالحق في تعديل هذه الشروط في أي وقت. عند إجراء تغييرات جوهرية:
                </p>
                <ul class="space-y-2 text-gray-700 bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <li>• سننشر الشروط المحدثة على هذه الصفحة</li>
                    <li>• سنقوم بتحديث تاريخ "آخر تحديث" في الأعلى</li>
                    <li>• سنرسل إشعاراً عبر البريد الإلكتروني (إن أمكن) أو WhatsApp</li>
                </ul>
                <p class="text-lg text-gray-700 leading-relaxed mt-4 bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                    <strong>⚠️ استمرار استخدامك</strong> للمنصة بعد نشر التغييرات يعني <strong>موافقتك التامة</strong> على الشروط المحدثة.
                </p>
            </div>
            
            <!-- Termination -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">9</span>
                    إنهاء الخدمة
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-4">
                    نحتفظ بالحق في تعليق أو إنهاء وصولك إلى المنصة في أي من الحالات التالية:
                </p>
                <ul class="space-y-2 text-gray-700 list-disc list-inside bg-red-50 rounded-xl p-6 border border-red-100">
                    <li>انتهاك هذه الشروط والأحكام</li>
                    <li>تقديم معلومات كاذبة أو مضللة</li>
                    <li>استخدام المنصة لأغراض غير قانونية</li>
                    <li>إساءة استخدام المنصة أو محاولة اختراقها</li>
                    <li>إلحاق ضرر بسمعة المنصة أو المستخدمين الآخرين</li>
                </ul>
                <p class="text-lg text-gray-700 leading-relaxed mt-4">
                    يمكنك أيضاً التوقف عن استخدام المنصة في أي وقت دون إشعار مسبق.
                </p>
            </div>
            
            <!-- Contact -->
            <div class="mb-12 p-8 bg-blue-600 rounded-2xl text-white shadow-xl">
                <h2 class="text-2xl font-bold mb-4">📞 اتصل بنا</h2>
                <p class="text-blue-100 mb-6 leading-relaxed">
                    إذا كان لديك أي أسئلة، استفسارات، أو مخاوف بشأن شروط الاستخدام، نحن هنا لمساعدتك:
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
$pageTitle = 'شروط الاستخدام - خدمة | KhidmaApp.com';
$pageDescription = 'شروط استخدام منصة خدمة - اقرأ الشروط والأحكام الخاصة باستخدام منصة KhidmaApp.com التابعة لشركة Aptiro LLC';
$pageKeywords = 'شروط الاستخدام, Terms of Service, خدمة, KhidmaApp, Aptiro LLC';

include __DIR__ . '/../layouts/base.php';
?>

 * Terms of Service / Kullanım Şartları
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
                    <li class="text-white font-semibold">شروط الاستخدام</li>
                </ol>
            </nav>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-4 leading-tight">
                شروط الاستخدام
            </h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                يرجى قراءة هذه الشروط بعناية قبل استخدام خدماتنا
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
                <h2 class="text-2xl font-bold text-gray-900 mb-4">معلومات الشركة والمشغل</h2>
                <div class="space-y-4 text-gray-700 leading-relaxed">
                    <p>
                        <strong class="text-gray-900">اسم المنصة:</strong> 
                        <span class="text-blue-600 font-semibold">خدمة (KhidmaApp.com)</span>
                    </p>
                    <p>
                        <strong class="text-gray-900">الشركة المشغلة:</strong> 
                        <span class="font-semibold">Aptiro LLC</span>
                    </p>
                    <p>
                        <strong class="text-gray-900">مقر التسجيل:</strong> 
                        الولايات المتحدة الأمريكية، ولاية نيو مكسيكو (New Mexico, USA)
                    </p>
                    <p>
                        <strong class="text-gray-900">منطقة الخدمة:</strong> 
                        المملكة العربية السعودية
                    </p>
                    <p>
                        <strong class="text-gray-900">الموقع الإلكتروني للشركة:</strong> 
                        <a href="https://www.aptiroglobal.com" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-700 font-semibold underline">
                            www.aptiroglobal.com
                        </a>
                    </p>
                </div>
            </div>
            
            <!-- Acceptance -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">1</span>
                    القبول والموافقة
                </h2>
                <div class="bg-yellow-50 border-r-4 border-yellow-500 p-6 rounded-xl mb-6">
                    <p class="text-gray-900 font-semibold mb-3">⚠️ موافقة ملزمة قانونياً</p>
                    <p class="text-gray-700 leading-relaxed">
                        باستخدام منصة <strong>خدمة</strong> (KhidmaApp.com) أو أي من خدماتها، بما في ذلك على سبيل المثال لا الحصر:
                    </p>
                    <ul class="mt-4 space-y-2 text-gray-700 list-disc list-inside">
                        <li><strong>تقديم طلب خدمة</strong> من خلال النموذج الإلكتروني</li>
                        <li><strong>التسجيل</strong> في الموقع أو إنشاء حساب</li>
                        <li><strong>الاشتراك في قناة WhatsApp</strong> الخاصة بالمنصة</li>
                        <li><strong>تصفح الموقع</strong> أو استخدام أي من ميزاته</li>
                    </ul>
                    <p class="mt-4 text-gray-700 leading-relaxed">
                        فإنك توافق تلقائياً وبشكل كامل وملزم قانونياً على جميع <strong>شروط الاستخدام</strong> و 
                        <a href="/privacy" class="text-blue-600 hover:text-blue-700 font-semibold underline">سياسة الخصوصية</a> و
                        <a href="/cookies" class="text-blue-600 hover:text-blue-700 font-semibold underline">سياسة ملفات تعريف الارتباط</a>.
                    </p>
                    <p class="mt-4 text-gray-900 font-semibold">
                        إذا كنت لا توافق على أي جزء من هذه الشروط، يُرجى التوقف فوراً عن استخدام المنصة.
                    </p>
                </div>
            </div>
            
            <!-- Platform Description -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">2</span>
                    وصف المنصة ونموذج العمل
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-6">
                    <strong>خدمة</strong> هي <strong class="text-blue-600">منصة وساطة إلكترونية (Marketplace Platform)</strong> 
                    تربط بين العملاء الباحثين عن خدمات منزلية وتجارية ومقدمي هذه الخدمات (الحرفيين/الفنيين/الأُستاذ) في المملكة العربية السعودية.
                </p>
                
                <div class="bg-gray-50 rounded-xl p-6 border-2 border-gray-200 mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">📌 دورنا كوسيط:</h3>
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-blue-600 me-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>نستقبل طلبات الخدمة من العملاء</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-blue-600 me-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>نوصل هذه الطلبات إلى مقدمي الخدمات المناسبين</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-blue-600 me-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>نسهل التواصل الأولي بين الطرفين</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-red-50 border-r-4 border-red-600 p-6 rounded-xl mb-6">
                    <h3 class="text-xl font-bold text-red-900 mb-4">⚠️ إخلاء مسؤولية هام</h3>
                    <div class="space-y-3 text-gray-800 leading-relaxed">
                        <p class="font-semibold">
                            نحن <strong>لسنا</strong> مقدمي الخدمة ولسنا طرفاً في أي عقد أو اتفاق بين العميل ومقدم الخدمة.
                        </p>
                        <p>
                            جميع العلاقات التعاقدية، الاتفاقيات، المدفوعات، والمسؤوليات تكون مباشرة بين:
                        </p>
                        <ul class="list-disc list-inside space-y-2 mr-4">
                            <li><strong>العميل</strong> (طالب الخدمة)</li>
                            <li><strong>مقدم الخدمة</strong> (الفني/الحرفي/الأُستاذ)</li>
                        </ul>
                        <p class="font-semibold text-red-900 mt-4">
                            📍 <strong>Aptiro LLC</strong> و <strong>خدمة (KhidmaApp.com)</strong> 
                            لا تتحمل أي مسؤولية قانونية أو مالية عن أي نزاعات، خلافات، أضرار، أو مطالبات تنشأ بين العميل ومقدم الخدمة.
                        </p>
                    </div>
                </div>

                <!-- لكننا نهتم بالجودة -->
                <div class="bg-green-50 border-r-4 border-green-600 p-6 rounded-xl">
                    <h3 class="text-xl font-bold text-green-900 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        ✅ لكننا ملتزمون بتحسين جودة الشبكة
                    </h3>
                    <div class="space-y-3 text-gray-800 leading-relaxed">
                        <p class="font-semibold">
                            رغم أننا لا نتحمل المسؤولية القانونية، إلا أننا <strong class="text-green-700">نلتزم أخلاقياً</strong> بالحفاظ على جودة الشبكة:
                        </p>
                        <ul class="space-y-2 mr-4">
                            <li class="flex items-start gap-2">
                                <span class="text-green-600 font-bold">✓</span>
                                <span><strong>نستقبل شكاويك:</strong> لديك الحق في تقديم شكوى عبر نظام الشكاوى أو WhatsApp</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-green-600 font-bold">✓</span>
                                <span><strong>نراجع الشكاوى بجدية:</strong> نحقق في كل شكوى ونتواصل مع الطرفين</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-green-600 font-bold">✓</span>
                                <span><strong>نحذر المتكررين:</strong> مقدمو الخدمات الذين تتكرر عليهم الشكاوى يحصلون على تحذيرات</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-green-600 font-bold">✓</span>
                                <span><strong>نفصل السيئين:</strong> نفصل نهائياً مقدمي الخدمات سيئي النية أو غير المحترفين</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-green-600 font-bold">✓</span>
                                <span><strong>نمنع الوصول:</strong> المفصولون لا يحصلون على طلبات جديدة من المنصة</span>
                            </li>
                        </ul>
                        <div class="bg-white rounded-lg p-4 mt-4 border-2 border-green-300">
                            <p class="text-sm text-gray-700">
                                <strong class="text-green-900">📝 ملاحظة مهمة:</strong> هذه الإجراءات هي جزء من <strong>التزامنا الأخلاقي</strong> 
                                لتحسين تجربة المستخدمين، لكنها <strong>لا تشكل التزاماً قانونياً</strong> أو ضماناً للجودة. 
                                المسؤولية القانونية الكاملة تبقى بين العميل ومقدم الخدمة.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User Obligations -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">3</span>
                    التزامات المستخدم
                </h2>
                <div class="space-y-4 text-lg text-gray-700 leading-relaxed">
                    <p class="mb-4">عند استخدام المنصة، أنت توافق على:</p>
                    <ul class="space-y-3 bg-blue-50 rounded-xl p-6 border border-blue-100">
                        <li class="flex items-start">
                            <span class="text-blue-600 font-bold me-3">✓</span>
                            <span>تقديم معلومات <strong>صحيحة ودقيقة</strong> عند تقديم طلب الخدمة</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 font-bold me-3">✓</span>
                            <span>استخدام المنصة <strong>للأغراض القانونية فقط</strong> وبما يتوافق مع القوانين السعودية والأمريكية</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 font-bold me-3">✓</span>
                            <span>احترام <strong>حقوق الملكية الفكرية</strong> لشركة Aptiro LLC</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 font-bold me-3">✓</span>
                            <span><strong>عدم محاولة</strong> الوصول غير المصرح به إلى النظام أو البيانات</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 font-bold me-3">✓</span>
                            <span><strong>عدم استخدام</strong> المنصة لنشر محتوى ضار، احتيالي، أو غير قانوني</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 font-bold me-3">✓</span>
                            <span>تحمل <strong>المسؤولية الكاملة</strong> عن أي تعاملات مع مقدمي الخدمات</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Limitation of Liability -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">4</span>
                    حدود المسؤولية وإخلاء الضمانات
                </h2>
                <div class="bg-red-50 border-2 border-red-300 rounded-xl p-8">
                    <h3 class="text-2xl font-bold text-red-900 mb-6">🚫 لا تتحمل Aptiro LLC أو خدمة (KhidmaApp.com) أي مسؤولية عن:</h3>
                    <div class="grid md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-white rounded-lg p-4 border border-red-200">
                            <p class="text-gray-800"><strong>❌ جودة الخدمات:</strong> جودة، كفاءة، أو نتائج الخدمات المقدمة من الفنيين</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-red-200">
                            <p class="text-gray-800"><strong>❌ الأضرار الجسدية:</strong> أي إصابات أو أضرار تحدث أثناء تقديم الخدمة</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-red-200">
                            <p class="text-gray-800"><strong>❌ الأضرار المادية:</strong> أضرار للممتلكات، المنزل، أو المعدات</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-red-200">
                            <p class="text-gray-800"><strong>❌ النزاعات المالية:</strong> خلافات حول الأسعار، الدفع، أو المبالغ المستحقة</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-red-200">
                            <p class="text-gray-800"><strong>❌ الاحتيال:</strong> أي عمليات احتيال من قبل مقدمي الخدمات أو العملاء</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-red-200">
                            <p class="text-gray-800"><strong>❌ فقدان البيانات:</strong> أي خسائر في البيانات أو معلومات العمل</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-red-200">
                            <p class="text-gray-800"><strong>❌ التأخير:</strong> تأخير مقدمي الخدمات أو عدم الحضور</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-red-200">
                            <p class="text-gray-800"><strong>❌ الضمانات:</strong> أي ضمانات صريحة أو ضمنية للخدمات المقدمة</p>
                        </div>
                    </div>
                    <p class="text-gray-800 leading-relaxed font-semibold mb-4">
                        📌 المنصة مقدمة "كما هي" (AS IS) و "حسب التوفر" (AS AVAILABLE) بدون أي ضمانات من أي نوع، 
                        سواء كانت صريحة أو ضمنية.
                    </p>
                    
                    <div class="bg-yellow-50 border-2 border-yellow-400 rounded-lg p-4 mt-4">
                        <p class="text-sm text-gray-800 leading-relaxed">
                            <strong class="text-yellow-900">⚠️ تنبيه مهم للعملاء:</strong> قد تكون المعلومات المدخلة من قبل العملاء غير دقيقة (أرقام هواتف خاطئة، بيانات ناقصة). 
                            نحن لا نتحقق من صحة البيانات المدخلة. <strong>لا يمكن استرداد أي مدفوعات</strong> تمت لمقدمي الخدمات بسبب معلومات خاطئة أو غير دقيقة.
                        </p>
                    </div>
                </div>
                
                <!-- Quality Commitment -->
                <div class="bg-blue-50 border-2 border-blue-300 rounded-xl p-6 mt-6">
                    <h3 class="text-xl font-bold text-blue-900 mb-4">💡 التزامنا تجاه تحسين الجودة</h3>
                    <p class="text-gray-800 leading-relaxed mb-3">
                        رغم عدم مسؤوليتنا القانونية، نعمل بشكل استباقي على <strong>مراقبة جودة الشبكة</strong> من خلال:
                    </p>
                    <ul class="grid md:grid-cols-2 gap-3">
                        <li class="flex items-center gap-2 bg-white p-3 rounded-lg">
                            <span class="text-blue-600">✓</span>
                            <span class="text-sm text-gray-700">نظام شكاوى متاح 24/7</span>
                        </li>
                        <li class="flex items-center gap-2 bg-white p-3 rounded-lg">
                            <span class="text-blue-600">✓</span>
                            <span class="text-sm text-gray-700">مراجعة وتحقيق في الشكاوى</span>
                        </li>
                        <li class="flex items-center gap-2 bg-white p-3 rounded-lg">
                            <span class="text-blue-600">✓</span>
                            <span class="text-sm text-gray-700">تحذير المخالفين</span>
                        </li>
                        <li class="flex items-center gap-2 bg-white p-3 rounded-lg">
                            <span class="text-blue-600">✓</span>
                            <span class="text-sm text-gray-700">فصل نهائي لسيئي النية</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Payment and Fees -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">5</span>
                    الدفع والرسوم
                </h2>
                <div class="space-y-4 text-lg text-gray-700 leading-relaxed">
                    <p class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                        <strong class="text-gray-900">• استخدام المنصة:</strong> حالياً، استخدام منصة خدمة لتقديم طلبات الخدمة <strong>مجاني</strong> للعملاء.
                    </p>
                    <p class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                        <strong class="text-gray-900">• الدفع لمقدمي الخدمات:</strong> جميع المدفوعات مقابل الخدمات تتم 
                        <strong>مباشرة بين العميل ومقدم الخدمة</strong>. لا نتعامل مع أي مدفوعات.
                    </p>
                    <p class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                        <strong class="text-gray-900">• الأسعار:</strong> يحدد مقدم الخدمة الأسعار. ننصح بالاتفاق على السعر 
                        <strong>قبل بدء العمل</strong> وطلب فاتورة رسمية.
                    </p>
                    <p class="bg-yellow-50 p-6 rounded-xl border border-yellow-200">
                        <strong class="text-gray-900">⚠️ رسوم مستقبلية:</strong> نحتفظ بالحق في فرض رسوم على بعض الخدمات أو الميزات في المستقبل. 
                        سيتم إشعارك قبل تطبيق أي رسوم بـ 30 يوماً على الأقل.
                    </p>
                </div>
            </div>
            
            <!-- Intellectual Property -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">6</span>
                    الملكية الفكرية
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-4">
                    جميع الحقوق الفكرية للمنصة، بما في ذلك على سبيل المثال لا الحصر:
                </p>
                <ul class="space-y-3 bg-indigo-50 rounded-xl p-6 border border-indigo-100 text-gray-700">
                    <li>• <strong>الاسم التجاري:</strong> "خدمة" و "KhidmaApp.com"</li>
                    <li>• <strong>الشعار والهوية البصرية</strong></li>
                    <li>• <strong>التصميم والواجهات</strong></li>
                    <li>• <strong>الأكواد البرمجية</strong></li>
                    <li>• <strong>المحتوى النصي والمرئي</strong></li>
                </ul>
                <p class="text-lg text-gray-700 leading-relaxed mt-6">
                    هي ملك حصري لشركة <strong class="text-blue-600">Aptiro LLC</strong> ومحمية بموجب قوانين حقوق النشر 
                    والعلامات التجارية الأمريكية والدولية. يحظر نسخها أو استخدامها دون إذن كتابي مسبق.
                </p>
            </div>
            
            <!-- Governing Law -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">7</span>
                    القانون الحاكم والنزاعات
                </h2>
                <div class="space-y-6">
                    <div class="bg-blue-50 rounded-xl p-6 border-2 border-blue-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">⚖️ الولاية القضائية:</h3>
                        <p class="text-gray-700 leading-relaxed mb-3">
                            تخضع هذه الشروط والأحكام لقوانين <strong>الولايات المتحدة الأمريكية</strong> (ولاية نيو مكسيكو) 
                            وقوانين <strong>المملكة العربية السعودية</strong> فيما يتعلق بالعمليات داخل المملكة.
                        </p>
                        <p class="text-gray-700 leading-relaxed">
                            <strong>النزاعات:</strong> أي نزاع ينشأ عن استخدام المنصة سيخضع للتحكيم أو الوساطة قبل اللجوء إلى المحاكم. 
                            الاختصاص القضائي يكون لمحاكم نيو مكسيكو، الولايات المتحدة الأمريكية.
                        </p>
                    </div>
                    
                    <div class="bg-green-50 rounded-xl p-6 border-2 border-green-300">
                        <h3 class="text-xl font-bold text-green-900 mb-3 flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                            </svg>
                            🤝 حل النزاعات ونظام الشكاوى
                        </h3>
                        <div class="space-y-4 text-gray-700 leading-relaxed">
                            <p>
                                نشجع المستخدمين على حل أي نزاعات مع مقدمي الخدمات بشكل ودي أولاً. 
                                <strong>المسؤولية القانونية للنزاعات تبقى بين الطرفين مباشرة.</strong>
                            </p>
                            
                            <div class="bg-white rounded-lg p-4 border border-green-200">
                                <p class="font-semibold text-green-900 mb-2">📋 خطوات تقديم الشكوى:</p>
                                <ol class="list-decimal list-inside space-y-2 text-sm mr-4">
                                    <li>حاول حل المشكلة مباشرة مع مقدم الخدمة</li>
                                    <li>إذا لم يتم الحل، قدم شكوى عبر <strong>نظام الشكاوى</strong> أو WhatsApp</li>
                                    <li>سنراجع شكواك ونتواصل مع الطرفين لفهم الموقف</li>
                                    <li>قد نحاول <strong>الوساطة</strong> بين الطرفين (دورنا استشاري فقط)</li>
                                    <li>في حالة ثبوت سوء النية أو الإهمال المتكرر، سنتخذ <strong>إجراءات ضد مقدم الخدمة</strong> (تحذير أو فصل نهائي)</li>
                                </ol>
                            </div>
                            
                            <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-3 text-sm">
                                <p class="text-yellow-900">
                                    <strong>⚠️ تنبيه قانوني:</strong> دورنا في الوساطة <strong>استشاري وغير ملزم قانونياً</strong>. 
                                    لا نتحمل مسؤولية قانونية عن النتائج. أي قرارات قانونية يجب أن تتم عبر المحاكم المختصة.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Changes to Terms -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">8</span>
                    تعديل الشروط
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-4">
                    نحتفظ بالحق في تعديل هذه الشروط في أي وقت. عند إجراء تغييرات جوهرية:
                </p>
                <ul class="space-y-2 text-gray-700 bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <li>• سننشر الشروط المحدثة على هذه الصفحة</li>
                    <li>• سنقوم بتحديث تاريخ "آخر تحديث" في الأعلى</li>
                    <li>• سنرسل إشعاراً عبر البريد الإلكتروني (إن أمكن) أو WhatsApp</li>
                </ul>
                <p class="text-lg text-gray-700 leading-relaxed mt-4 bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                    <strong>⚠️ استمرار استخدامك</strong> للمنصة بعد نشر التغييرات يعني <strong>موافقتك التامة</strong> على الشروط المحدثة.
                </p>
            </div>
            
            <!-- Termination -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center me-3 text-lg">9</span>
                    إنهاء الخدمة
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-4">
                    نحتفظ بالحق في تعليق أو إنهاء وصولك إلى المنصة في أي من الحالات التالية:
                </p>
                <ul class="space-y-2 text-gray-700 list-disc list-inside bg-red-50 rounded-xl p-6 border border-red-100">
                    <li>انتهاك هذه الشروط والأحكام</li>
                    <li>تقديم معلومات كاذبة أو مضللة</li>
                    <li>استخدام المنصة لأغراض غير قانونية</li>
                    <li>إساءة استخدام المنصة أو محاولة اختراقها</li>
                    <li>إلحاق ضرر بسمعة المنصة أو المستخدمين الآخرين</li>
                </ul>
                <p class="text-lg text-gray-700 leading-relaxed mt-4">
                    يمكنك أيضاً التوقف عن استخدام المنصة في أي وقت دون إشعار مسبق.
                </p>
            </div>
            
            <!-- Contact -->
            <div class="mb-12 p-8 bg-blue-600 rounded-2xl text-white shadow-xl">
                <h2 class="text-2xl font-bold mb-4">📞 اتصل بنا</h2>
                <p class="text-blue-100 mb-6 leading-relaxed">
                    إذا كان لديك أي أسئلة، استفسارات، أو مخاوف بشأن شروط الاستخدام، نحن هنا لمساعدتك:
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
$pageTitle = 'شروط الاستخدام - خدمة | KhidmaApp.com';
$pageDescription = 'شروط استخدام منصة خدمة - اقرأ الشروط والأحكام الخاصة باستخدام منصة KhidmaApp.com التابعة لشركة Aptiro LLC';
$pageKeywords = 'شروط الاستخدام, Terms of Service, خدمة, KhidmaApp, Aptiro LLC';

include __DIR__ . '/../layouts/base.php';
?>


