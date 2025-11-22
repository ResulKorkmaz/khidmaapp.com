<?php
// Provider layout'u başlat - içeriği ob_start ile yakala
ob_start();
?>

<div class="max-w-2xl mx-auto px-4 py-12">
    <!-- Cancel Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <!-- Cancel Header -->
        <div class="bg-yellow-500 text-white p-8 text-center">
            <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold mb-2">تم إلغاء عملية الشراء</h1>
            <p class="text-yellow-100 text-lg">لم يتم خصم أي مبلغ من حسابك</p>
        </div>
        
        <!-- Cancel Body -->
        <div class="p-8">
            <!-- Info -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">ماذا حدث؟</h3>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>قمت بإلغاء عملية الدفع أو أغلقت نافذة الدفع</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>لم يتم إتمام عملية الدفع</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>بياناتك وأمانك محفوظة</span>
                    </li>
                </ul>
            </div>
            
            <!-- Why Buy? -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">لماذا يجب عليك شراء حزمة؟</h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">عملاء حقيقيون</h4>
                            <p class="text-gray-600 text-sm">احصل على معلومات اتصال حقيقية لعملاء يبحثون عن خدماتك</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">وفّر وقتك</h4>
                            <p class="text-gray-600 text-sm">لا حاجة للبحث عن العملاء - نحن نرسلهم إليك مباشرة</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">زِد دخلك</h4>
                            <p class="text-gray-600 text-sm">كل lead هو فرصة لمشروع جديد وزيادة أرباحك</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="/provider/browse-packages" 
                   class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>حاول مرة أخرى</span>
                </a>
                <a href="/provider/dashboard" 
                   class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>لوحة التحكم</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Support Info -->
    <div class="mt-8 text-center">
        <p class="text-gray-600 text-sm">
            هل تواجه مشكلة في عملية الدفع؟ تواصل معنا عبر 
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
$pageTitle = $pageData['pageTitle'] ?? 'تم إلغاء الشراء';
$currentPage = 'browse-packages';
require __DIR__ . '/layout.php';
?>



ob_start();
?>

<div class="max-w-2xl mx-auto px-4 py-12">
    <!-- Cancel Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <!-- Cancel Header -->
        <div class="bg-yellow-500 text-white p-8 text-center">
            <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold mb-2">تم إلغاء عملية الشراء</h1>
            <p class="text-yellow-100 text-lg">لم يتم خصم أي مبلغ من حسابك</p>
        </div>
        
        <!-- Cancel Body -->
        <div class="p-8">
            <!-- Info -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">ماذا حدث؟</h3>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>قمت بإلغاء عملية الدفع أو أغلقت نافذة الدفع</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>لم يتم إتمام عملية الدفع</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>بياناتك وأمانك محفوظة</span>
                    </li>
                </ul>
            </div>
            
            <!-- Why Buy? -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">لماذا يجب عليك شراء حزمة؟</h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">عملاء حقيقيون</h4>
                            <p class="text-gray-600 text-sm">احصل على معلومات اتصال حقيقية لعملاء يبحثون عن خدماتك</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">وفّر وقتك</h4>
                            <p class="text-gray-600 text-sm">لا حاجة للبحث عن العملاء - نحن نرسلهم إليك مباشرة</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">زِد دخلك</h4>
                            <p class="text-gray-600 text-sm">كل lead هو فرصة لمشروع جديد وزيادة أرباحك</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="/provider/browse-packages" 
                   class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>حاول مرة أخرى</span>
                </a>
                <a href="/provider/dashboard" 
                   class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>لوحة التحكم</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Support Info -->
    <div class="mt-8 text-center">
        <p class="text-gray-600 text-sm">
            هل تواجه مشكلة في عملية الدفع؟ تواصل معنا عبر 
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
$pageTitle = $pageData['pageTitle'] ?? 'تم إلغاء الشراء';
$currentPage = 'browse-packages';
require __DIR__ . '/layout.php';
?>




