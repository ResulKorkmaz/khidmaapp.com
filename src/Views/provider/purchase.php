<?php
// Provider layout'u başlat - içeriği ob_start ile yakala
$pageTitle = 'تأكيد الشراء';
$currentPage = 'browse-packages';
ob_start();
?>

<!-- Sayfa Başlığı -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">تأكيد الشراء</h1>
            <p class="text-gray-600 mt-1">مرحباً، <?= htmlspecialchars($provider['name'] ?? 'مقدم الخدمة') ?></p>
        </div>
        <a href="/provider/dashboard" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors">
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            العودة
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="bg-green-600 px-8 py-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-white text-center">تأكيد شراء الحزمة</h2>
            <p class="text-green-100 text-center mt-2">يرجى مراجعة تفاصيل الشراء</p>
        </div>
        
        <!-- Package Details -->
        <div class="p-8">
            <div class="border-2 border-green-100 rounded-xl p-6 mb-6 bg-green-50">
                <h3 class="text-xl font-bold text-gray-900 mb-4"><?= htmlspecialchars($package['name_ar']) ?></h3>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-center justify-between py-2 border-b border-green-200">
                        <span class="text-gray-700">عدد الطلبات:</span>
                        <span class="font-bold text-gray-900"><?= $package['lead_count'] ?> طلبات</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-2 border-b border-green-200">
                        <span class="text-gray-700">السعر:</span>
                        <span class="font-bold text-gray-900"><?= number_format($package['price'], 2) ?> ريال</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-2">
                        <span class="text-gray-700">سعر الطلب الواحد:</span>
                        <span class="font-bold text-green-600"><?= number_format($package['price'] / $package['lead_count'], 2) ?> ريال</span>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-4 border border-green-200">
                    <p class="text-sm text-gray-700 leading-relaxed">
                        <?= htmlspecialchars($package['description_ar']) ?>
                    </p>
                </div>
            </div>
            
            <!-- How it Works - IMPORTANT -->
            <div class="mb-6 p-5 bg-blue-50 border-2 border-blue-200 rounded-xl">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 text-lg mb-2">📋 كيف يعمل النظام؟</h4>
                        <div class="bg-white rounded-lg p-4 border border-blue-200">
                            <p class="text-sm text-gray-800 leading-relaxed mb-3">
                                <strong class="text-blue-600">⚠️ مهم جداً:</strong> عندما يتقدم عميل جديد بطلب يتطابق مع <strong>نفس نوع الخدمة ونفس المدينة</strong> التي تقدمها، سيتم إرسال الطلبات للأساتذة <strong class="text-blue-600">حسب ترتيب الشراء</strong>.
                            </p>
                            <div class="flex items-start gap-2 p-3 bg-blue-50 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm text-blue-900">
                                    <strong>مثال:</strong> إذا اشتريت حزمة اليوم، وزميلك اشترى غداً، ستحصل أنت على الطلبات الجديدة أولاً حتى تنتهي حزمتك، ثم يبدأ دوره.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Features -->
            <div class="mb-6">
                <h4 class="font-bold text-gray-900 mb-3">ما الذي ستحصل عليه:</h4>
                <ul class="space-y-2">
                    <li class="flex items-center text-sm text-gray-700">
                        <svg class="w-5 h-5 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        معلومات العميل الكاملة (الاسم، الهاتف، العنوان)
                    </li>
                    <li class="flex items-center text-sm text-gray-700">
                        <svg class="w-5 h-5 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        تفاصيل الخدمة المطلوبة بالكامل
                    </li>
                    <li class="flex items-center text-sm text-gray-700">
                        <svg class="w-5 h-5 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        طلبات في تخصصك ومدينتك فقط
                    </li>
                    <li class="flex items-center text-sm text-gray-700">
                        <svg class="w-5 h-5 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        توزيع تلقائي حسب أولوية الشراء
                    </li>
                    <li class="flex items-center text-sm text-gray-700">
                        <svg class="w-5 h-5 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        صلاحية الحزمة: 90 يوماً
                    </li>
                </ul>
            </div>
            
            <!-- Total -->
            <div class="border-t-2 border-gray-200 pt-6 mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-lg font-semibold text-gray-700">المبلغ الإجمالي:</span>
                    <span class="text-3xl font-bold text-green-600"><?= number_format($package['price'], 2) ?> <span class="text-lg text-gray-500">ريال</span></span>
                </div>
                <p class="text-sm text-gray-500 text-left">شامل ضريبة القيمة المضافة</p>
            </div>
            
            <!-- Confirm Purchase Form -->
            <form method="POST" action="/provider/purchase/<?= $package['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                
                <div class="flex flex-col gap-3">
                    <button type="submit" 
                            class="w-full py-4 bg-green-600 hover:bg-green-700 text-white text-lg font-bold rounded-xl transition-colors shadow-lg hover:shadow-xl">
                        تأكيد الشراء الآن 🎉
                    </button>
                    
                    <a href="/provider/dashboard" 
                       class="w-full py-4 bg-gray-100 hover:bg-gray-200 text-gray-700 text-center font-semibold rounded-xl transition-colors">
                        إلغاء
                    </a>
                </div>
            </form>
            
            <!-- Note -->
            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <h5 class="font-semibold text-yellow-900 mb-1">ملاحظة هامة:</h5>
                        <p class="text-sm text-yellow-800">
                            بالنقر على "تأكيد الشراء"، أنت توافق على شراء هذه الحزمة. سيتم خصم المبلغ وإضافة الطلبات إلى حسابك فوراً.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Layout'a içeriği gönder
$content = ob_get_clean();

// Provider layout'u yükle
require __DIR__ . '/layout.php';
?>

$pageTitle = 'تأكيد الشراء';
$currentPage = 'browse-packages';
ob_start();
?>

<!-- Sayfa Başlığı -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">تأكيد الشراء</h1>
            <p class="text-gray-600 mt-1">مرحباً، <?= htmlspecialchars($provider['name'] ?? 'مقدم الخدمة') ?></p>
        </div>
        <a href="/provider/dashboard" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors">
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            العودة
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="bg-green-600 px-8 py-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-white text-center">تأكيد شراء الحزمة</h2>
            <p class="text-green-100 text-center mt-2">يرجى مراجعة تفاصيل الشراء</p>
        </div>
        
        <!-- Package Details -->
        <div class="p-8">
            <div class="border-2 border-green-100 rounded-xl p-6 mb-6 bg-green-50">
                <h3 class="text-xl font-bold text-gray-900 mb-4"><?= htmlspecialchars($package['name_ar']) ?></h3>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-center justify-between py-2 border-b border-green-200">
                        <span class="text-gray-700">عدد الطلبات:</span>
                        <span class="font-bold text-gray-900"><?= $package['lead_count'] ?> طلبات</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-2 border-b border-green-200">
                        <span class="text-gray-700">السعر:</span>
                        <span class="font-bold text-gray-900"><?= number_format($package['price'], 2) ?> ريال</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-2">
                        <span class="text-gray-700">سعر الطلب الواحد:</span>
                        <span class="font-bold text-green-600"><?= number_format($package['price'] / $package['lead_count'], 2) ?> ريال</span>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-4 border border-green-200">
                    <p class="text-sm text-gray-700 leading-relaxed">
                        <?= htmlspecialchars($package['description_ar']) ?>
                    </p>
                </div>
            </div>
            
            <!-- How it Works - IMPORTANT -->
            <div class="mb-6 p-5 bg-blue-50 border-2 border-blue-200 rounded-xl">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 text-lg mb-2">📋 كيف يعمل النظام؟</h4>
                        <div class="bg-white rounded-lg p-4 border border-blue-200">
                            <p class="text-sm text-gray-800 leading-relaxed mb-3">
                                <strong class="text-blue-600">⚠️ مهم جداً:</strong> عندما يتقدم عميل جديد بطلب يتطابق مع <strong>نفس نوع الخدمة ونفس المدينة</strong> التي تقدمها، سيتم إرسال الطلبات للأساتذة <strong class="text-blue-600">حسب ترتيب الشراء</strong>.
                            </p>
                            <div class="flex items-start gap-2 p-3 bg-blue-50 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm text-blue-900">
                                    <strong>مثال:</strong> إذا اشتريت حزمة اليوم، وزميلك اشترى غداً، ستحصل أنت على الطلبات الجديدة أولاً حتى تنتهي حزمتك، ثم يبدأ دوره.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Features -->
            <div class="mb-6">
                <h4 class="font-bold text-gray-900 mb-3">ما الذي ستحصل عليه:</h4>
                <ul class="space-y-2">
                    <li class="flex items-center text-sm text-gray-700">
                        <svg class="w-5 h-5 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        معلومات العميل الكاملة (الاسم، الهاتف، العنوان)
                    </li>
                    <li class="flex items-center text-sm text-gray-700">
                        <svg class="w-5 h-5 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        تفاصيل الخدمة المطلوبة بالكامل
                    </li>
                    <li class="flex items-center text-sm text-gray-700">
                        <svg class="w-5 h-5 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        طلبات في تخصصك ومدينتك فقط
                    </li>
                    <li class="flex items-center text-sm text-gray-700">
                        <svg class="w-5 h-5 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        توزيع تلقائي حسب أولوية الشراء
                    </li>
                    <li class="flex items-center text-sm text-gray-700">
                        <svg class="w-5 h-5 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        صلاحية الحزمة: 90 يوماً
                    </li>
                </ul>
            </div>
            
            <!-- Total -->
            <div class="border-t-2 border-gray-200 pt-6 mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-lg font-semibold text-gray-700">المبلغ الإجمالي:</span>
                    <span class="text-3xl font-bold text-green-600"><?= number_format($package['price'], 2) ?> <span class="text-lg text-gray-500">ريال</span></span>
                </div>
                <p class="text-sm text-gray-500 text-left">شامل ضريبة القيمة المضافة</p>
            </div>
            
            <!-- Confirm Purchase Form -->
            <form method="POST" action="/provider/purchase/<?= $package['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                
                <div class="flex flex-col gap-3">
                    <button type="submit" 
                            class="w-full py-4 bg-green-600 hover:bg-green-700 text-white text-lg font-bold rounded-xl transition-colors shadow-lg hover:shadow-xl">
                        تأكيد الشراء الآن 🎉
                    </button>
                    
                    <a href="/provider/dashboard" 
                       class="w-full py-4 bg-gray-100 hover:bg-gray-200 text-gray-700 text-center font-semibold rounded-xl transition-colors">
                        إلغاء
                    </a>
                </div>
            </form>
            
            <!-- Note -->
            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <h5 class="font-semibold text-yellow-900 mb-1">ملاحظة هامة:</h5>
                        <p class="text-sm text-yellow-800">
                            بالنقر على "تأكيد الشراء"، أنت توافق على شراء هذه الحزمة. سيتم خصم المبلغ وإضافة الطلبات إلى حسابك فوراً.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Layout'a içeriği gönder
$content = ob_get_clean();

// Provider layout'u yükle
require __DIR__ . '/layout.php';
?>


