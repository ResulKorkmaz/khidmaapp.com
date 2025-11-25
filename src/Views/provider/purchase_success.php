<?php
// Provider layout'u başlat - içeriği ob_start ile yakala
ob_start();

// Değişkenler extract ile gelen değerlerden alınır
$session = $session ?? null;
$purchase = $purchase ?? [];
$serviceTypes = getServiceTypes();
?>

<div class="max-w-3xl mx-auto px-4 py-12">
    <!-- Success Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <!-- Success Header -->
        <div class="bg-green-600 text-white p-8 text-center">
            <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold mb-2">تمت عملية الشراء بنجاح!</h1>
            <p class="text-green-100 text-lg">تم إضافة الـ leads إلى حسابك</p>
        </div>
        
        <!-- Purchase Details -->
        <div class="p-8">
            <?php if ($purchase): ?>
                <!-- Package Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
                    <h3 class="text-lg font-bold text-blue-900 mb-4">تفاصيل الحزمة</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-blue-600 mb-1">عدد الطلبات</div>
                            <div class="text-2xl font-bold text-blue-900"><?= $purchase['lead_count'] ?? 0 ?></div>
                        </div>
                        <div>
                            <div class="text-sm text-blue-600 mb-1">المبلغ المدفوع</div>
                            <div class="text-2xl font-bold text-blue-900"><?= number_format($purchase['price_paid'] ?? 0, 2) ?> ريال</div>
                        </div>
                    </div>
                </div>
                
                <!-- What's Next -->
                <div class="space-y-4 mb-8">
                    <h3 class="text-lg font-bold text-gray-900">الخطوات التالية:</h3>
                    
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center flex-shrink-0 font-bold">
                            1
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900 mb-1">انتظار موافقة المسؤول</h4>
                            <p class="text-gray-600 text-sm">سيتم إرسال الطلب الأول يدوياً من قبل المسؤول بعد المراجعة</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center flex-shrink-0 font-bold">
                            2
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900 mb-1">استخدم زر "طلب عميل"</h4>
                            <p class="text-gray-600 text-sm">للحصول على الطلبات المتبقية، يجب عليك استخدام زر <strong>"طلب عميل"</strong> في لوحة التحكم عندما تكون جاهزاً</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center flex-shrink-0 font-bold">
                            3
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900 mb-1">تواصل مع العملاء</h4>
                            <p class="text-gray-600 text-sm">عندما يتم تسليم الطلب، ستجد تفاصيل الاتصال في قسم "الطلبات المستلمة"</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="/provider/dashboard" 
                   class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>لوحة التحكم</span>
                </a>
                <a href="/provider/browse-packages" 
                   class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>شراء المزيد</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Support Info -->
    <div class="mt-8 text-center">
        <p class="text-gray-600 text-sm">
            هل لديك أسئلة؟ تواصل معنا عبر 
            <a href="https://wa.me/YOUR_WHATSAPP" target="_blank" class="text-blue-600 hover:text-blue-700 font-semibold">
                واتساب
            </a>
        </p>
    </div>
</div>

<?php
// İçeriği al
$content = ob_get_clean();

// Layout'u yükle
$pageTitle = $pageTitle ?? 'عملية شراء ناجحة';
$currentPage = 'browse-packages';
require __DIR__ . '/layout.php';
