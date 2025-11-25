<?php
/**
 * Provider Lead Paketleri Görünüm Sayfası
 * Usta paneli için lead paket satın alma sayfası
 */

// Layout için içerik yakalama başlat
ob_start();

// Sayfa verilerini al (extract ile gelen değişkenler)
$provider = $provider ?? [];
$packages = $packages ?? [];
$serviceTypes = getServiceTypes();
$serviceName = $serviceTypes[$provider['service_type'] ?? '']['ar'] ?? ($provider['service_type'] ?? '');
$isActive = ($provider['status'] ?? '') === 'active';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
    <?php if (!$isActive): ?>
    <!-- Hesap Aktif Değil Uyarısı -->
    <div class="mb-6 bg-yellow-50 border-2 border-yellow-300 rounded-2xl p-6 text-center">
        <div class="flex items-center justify-center gap-3 mb-3">
            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <h3 class="text-xl font-bold text-yellow-800">حسابك غير مفعّل</h3>
        </div>
        <p class="text-yellow-700 mb-4">يجب أن يكون حسابك مفعّلاً لشراء حزم الطلبات. يرجى الانتظار حتى يتم مراجعة حسابك من قبل الإدارة.</p>
        <a href="/provider/dashboard" class="inline-flex items-center gap-2 px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            العودة للوحة التحكم
        </a>
    </div>
    <?php endif; ?>
    
    <!-- Sayfa Başlığı -->
    <div class="mb-6 sm:mb-8 text-center lg:text-right">
        <div class="flex items-center justify-center lg:justify-start gap-3 mb-4">
            <a href="/provider/dashboard" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-300 text-gray-700 hover:bg-gray-50 hover:border-blue-500 rounded-xl transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="text-sm font-semibold">العودة</span>
            </a>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-gray-900 mb-3">حزم الطلبات المتاحة</h1>
        <p class="text-base sm:text-lg text-gray-600">اختر الحزمة المناسبة لعملك - <span class="font-bold text-blue-600"><?= htmlspecialchars($serviceName) ?></span></p>
    </div>
    
    <?php if (empty($packages)): ?>
        <!-- Paket Bulunamadı Mesajı -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-12 text-center">
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">لا توجد حزم متاحة حالياً</h3>
            <p class="text-sm sm:text-base text-gray-600">سيتم إضافة حزم جديدة قريباً لخدمتك</p>
        </div>
    <?php else: ?>
        <!-- Paket Kartları Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            <?php foreach ($packages as $package): 
                $discount = $package['discount_percentage'] ?? 0;
                $pricePerLead = $package['price_sar'] / $package['lead_count'];
            ?>
                <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-200 hover:border-blue-500 hover:shadow-2xl hover:scale-[1.02] transition-all duration-300 overflow-hidden relative">
                    <?php if ($discount > 0): ?>
                        <!-- İndirim Badge -->
                        <div class="absolute top-3 left-3 sm:top-4 sm:left-4 bg-red-500 text-white px-2 py-1 sm:px-3 sm:py-1.5 rounded-full text-xs sm:text-sm font-bold shadow-lg z-10">
                            -%<?= number_format($discount) ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Paket Başlığı -->
                    <div class="bg-blue-600 text-white p-6 sm:p-8 text-center relative overflow-hidden">
                        <!-- Decorative circles -->
                        <div class="absolute top-0 right-0 w-24 h-24 sm:w-32 sm:h-32 bg-white opacity-5 rounded-full -mr-12 -mt-12 sm:-mr-16 sm:-mt-16"></div>
                        <div class="absolute bottom-0 left-0 w-20 h-20 sm:w-24 sm:h-24 bg-white opacity-5 rounded-full -ml-10 -mb-10 sm:-ml-12 sm:-mb-12"></div>
                        
                        <div class="relative z-10">
                            <div class="text-5xl sm:text-6xl font-black mb-2"><?= $package['lead_count'] ?></div>
                            <div class="text-xl sm:text-2xl font-bold opacity-95">طلب</div>
                            <div class="mt-2 sm:mt-3 text-blue-100 text-xs sm:text-sm font-medium">
                                <?= htmlspecialchars($package['name_ar'] ?? '') ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Paket İçeriği -->
                    <div class="p-4 sm:p-6">
                        <!-- Fiyat Bilgisi - Modern -->
                        <div class="text-center mb-5 sm:mb-6 pb-5 sm:pb-6 border-b-2 border-gray-100">
                            <div class="inline-flex items-baseline gap-1.5 sm:gap-2 mb-2">
                                <span class="text-4xl sm:text-5xl font-black text-blue-600"><?= number_format($package['price_sar'], 0) ?></span>
                                <span class="text-lg sm:text-xl font-bold text-gray-600">ريال</span>
                            </div>
                            <div class="flex items-center justify-center gap-2 mt-2">
                                <div class="h-px w-6 sm:w-8 bg-gray-300"></div>
                                <div class="text-xs sm:text-sm font-medium text-gray-500 bg-gray-50 px-2 sm:px-3 py-1 rounded-full">
                                    <?= number_format($pricePerLead, 2) ?> ريال / طلب
                                </div>
                                <div class="h-px w-6 sm:w-8 bg-gray-300"></div>
                            </div>
                        </div>
                        
                        <!-- Paket Özellikleri -->
                        <ul class="space-y-2.5 sm:space-y-3 mb-5 sm:mb-6">
                            <li class="flex items-center gap-2.5 sm:gap-3 text-gray-700 text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span><?= $package['lead_count'] ?> عميل محتمل مضمون</span>
                            </li>
                            <li class="flex items-center gap-2.5 sm:gap-3 text-gray-700 text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>معلومات اتصال كاملة</span>
                            </li>
                            <li class="flex items-center gap-2.5 sm:gap-3 text-gray-700 text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>دعم فني 24/7</span>
                            </li>
                            <?php if ($package['lead_count'] > 1): ?>
                                <li class="flex items-center gap-2.5 sm:gap-3 text-gray-700 text-sm sm:text-base">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="font-semibold text-green-600">
                                        وفّر <?= number_format(($package['price_sar'] / $package['lead_count'] - $package['price_per_lead']) * $package['lead_count'], 0) ?> ريال!
                                    </span>
                                </li>
                            <?php endif; ?>
                        </ul>
                        
                        <!-- Satın Al Butonu -->
                        <?php if ($isActive): ?>
                        <button onclick="showTermsModal(<?= $package['id'] ?>)" 
                                id="buy-btn-<?= $package['id'] ?>"
                                class="group relative w-full py-3 sm:py-4 px-4 sm:px-6 font-black text-base sm:text-lg rounded-xl transition-all shadow-lg hover:shadow-2xl overflow-hidden"
                                style="background-color: #16a34a !important; color: #ffffff !important; border: none !important;">
                            
                            <span class="relative z-20 flex items-center justify-center gap-2 sm:gap-3" style="color: #ffffff !important;">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 group-hover:scale-110 transition-transform" fill="none" stroke="#ffffff" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span style="color: #ffffff !important;">اشتري الآن</span>
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="#ffffff" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </span>
                        </button>
                        <?php else: ?>
                        <button disabled
                                class="group relative w-full py-3 sm:py-4 px-4 sm:px-6 font-black text-base sm:text-lg rounded-xl overflow-hidden cursor-not-allowed opacity-60"
                                style="background-color: #9ca3af !important; color: #ffffff !important; border: none !important;">
                            
                            <span class="relative z-20 flex items-center justify-center gap-2 sm:gap-3" style="color: #ffffff !important;">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="#ffffff" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <span style="color: #ffffff !important;">الحساب غير مفعّل</span>
                            </span>
                        </button>
                        <?php endif; ?>
                        
                        <!-- Güven Badge -->
                        <div class="mt-3 sm:mt-4 text-center">
                            <div class="inline-flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-gray-500">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">دفع آمن 100%</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- معلومات مهمة - كيف يعمل النظام -->
        <div class="mt-12 mb-8 bg-blue-50 border-2 border-blue-200 rounded-2xl p-4 sm:p-6 shadow-md">
            <div class="flex flex-col lg:flex-row items-start gap-4">
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 w-full">
                    <h3 class="text-xl sm:text-2xl font-black text-blue-900 mb-4">⚡ كيف تحصل على الطلبات؟</h3>
                    
                    <div class="bg-white rounded-xl p-4 sm:p-5 mb-4 border-2 border-blue-300 shadow-md">
                        <div class="space-y-3 sm:space-y-4">
                            <!-- الخطوة 1 -->
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center flex-shrink-0 font-black text-base sm:text-lg shadow-md">
                                    1
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-black text-gray-900 text-base sm:text-lg mb-1">🛒 اشتري الحزمة</h4>
                                    <p class="text-xs sm:text-sm text-gray-700">اختر الحزمة المناسبة وادفع بأمان</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                            </div>
                            
                            <!-- الخطوة 2 -->
                            <div class="flex items-start gap-3 bg-green-50 rounded-lg p-2 sm:p-3 border border-green-200">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-green-600 text-white rounded-lg flex items-center justify-center flex-shrink-0 font-black text-base sm:text-lg shadow-md">
                                    2
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-black text-gray-900 text-base sm:text-lg mb-1">👉 اضغط "طلب عميل"</h4>
                                    <p class="text-xs sm:text-sm text-gray-700">في لوحة التحكم، اضغط على زر <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-600 text-white text-xs font-bold rounded">طلب عميل</span> عندما تكون جاهزاً</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                            </div>
                            
                            <!-- الخطوة 3 -->
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-orange-600 text-white rounded-lg flex items-center justify-center flex-shrink-0 font-black text-base sm:text-lg shadow-md">
                                    3
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-black text-gray-900 text-base sm:text-lg mb-1">⏰ انتظر قليلاً</h4>
                                    <p class="text-xs sm:text-sm text-gray-700">الإدارة ستراجع طلبك وترسل لك العميل المناسب</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                            </div>
                            
                            <!-- الخطوة 4 -->
                            <div class="flex items-start gap-3 bg-purple-50 rounded-lg p-2 sm:p-3 border-2 border-purple-300">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-purple-600 text-white rounded-lg flex items-center justify-center flex-shrink-0 shadow-md">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-black text-purple-900 text-base sm:text-lg mb-1">🎉 استلم الطلب!</h4>
                                    <p class="text-xs sm:text-sm text-purple-800">ستجد معلومات العميل كاملة في قسم <strong>"الطلبات المستلمة"</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- ملاحظات مهمة -->
                    <div class="bg-yellow-50 border-2 border-yellow-300 rounded-xl p-3 sm:p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 bg-yellow-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-yellow-900 mb-2 text-sm sm:text-base">📌 ملاحظات مهمة:</h4>
                                <ul class="space-y-1 sm:space-y-2 text-xs sm:text-sm text-yellow-900">
                                    <li class="flex items-start gap-2">
                                        <span class="text-yellow-600 font-bold">•</span>
                                        <span><strong>لن تستلم الطلبات تلقائياً</strong> - يجب أن تضغط زر "طلب عميل"</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-yellow-600 font-bold">•</span>
                                        <span>إذا اشتريت حزمة <strong>3 طلبات</strong> → اضغط الزر <strong>3 مرات</strong> (مرة لكل طلب)</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-yellow-600 font-bold">•</span>
                                        <span><strong>الأولوية للأسبق</strong> - من اشترى أولاً يحصل على الطلبات أولاً</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Ödeme Bilgilendirme -->
        <div class="bg-blue-50 border-2 border-blue-200 rounded-2xl p-4 sm:p-6 shadow-sm">
            <div class="flex items-start gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base sm:text-lg font-bold text-blue-900 mb-2">🔒 الدفع الآمن عبر Stripe</h3>
                    <p class="text-blue-800 text-xs sm:text-sm leading-relaxed">
                        جميع المدفوعات محمية بأعلى معايير الأمان. نحن لا نخزن معلومات بطاقتك الائتمانية. 
                        بعد إتمام الدفع، سيتم إضافة الطلبات إلى حسابك فوراً.
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Şartlar ve Koşullar Modal -->
<div id="termsModal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative">
        <!-- Başlık -->
        <div class="flex items-center gap-3 mb-4 pb-4 border-b-2 border-gray-200">
            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-xl font-black text-gray-900">شروط الشراء</h3>
        </div>

        <!-- Şartlar Listesi -->
        <div class="mb-5 space-y-3">
            <div class="flex items-start gap-3 bg-orange-50 p-3 rounded-lg border border-orange-200">
                <span class="text-orange-600 text-lg font-bold mt-0.5">•</span>
                <p class="text-sm text-gray-800">العميل قد يملأ النموذج بشكل خاطئ أو ناقص</p>
            </div>
            <div class="flex items-start gap-3 bg-orange-50 p-3 rounded-lg border border-orange-200">
                <span class="text-orange-600 text-lg font-bold mt-0.5">•</span>
                <p class="text-sm text-gray-800">الشركة غير مسؤولة عن جودة الطلبات</p>
            </div>
            <div class="flex items-start gap-3 bg-orange-50 p-3 rounded-lg border border-orange-200">
                <span class="text-orange-600 text-lg font-bold mt-0.5">•</span>
                <p class="text-sm text-gray-800">لا يمكن استرداد الأموال بسبب معلومات خاطئة</p>
            </div>
        </div>

        <!-- Onay Checkbox -->
        <label class="flex items-start gap-3 mb-5 cursor-pointer group">
            <input type="checkbox" id="termsCheckbox" class="mt-1 w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-2 focus:ring-blue-500">
            <span class="text-sm text-gray-700 font-medium group-hover:text-gray-900">أوافق على الشروط وأفهم السياسة</span>
        </label>

        <!-- Butonlar -->
        <div class="flex gap-3">
            <button onclick="closeTermsModal()" class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold rounded-xl transition-colors">
                إلغاء
            </button>
            <button onclick="acceptTerms()" id="acceptBtn" disabled class="flex-1 px-4 py-3 bg-blue-600 text-white font-bold rounded-xl transition-all opacity-50 cursor-not-allowed">
                متابعة إلى الدفع
            </button>
        </div>
    </div>
</div>

<script>
// CSRF token'ı al
const csrfToken = '<?= generateCsrfToken() ?>';
let selectedPackageId = null;

// Modal Fonksiyonları
function showTermsModal(packageId) {
    selectedPackageId = packageId;
    document.getElementById('termsModal').classList.remove('hidden');
    document.getElementById('termsCheckbox').checked = false;
    document.getElementById('acceptBtn').disabled = true;
    document.getElementById('acceptBtn').classList.add('opacity-50', 'cursor-not-allowed');
}

function closeTermsModal() {
    document.getElementById('termsModal').classList.add('hidden');
    selectedPackageId = null;
}

function acceptTerms() {
    const checkbox = document.getElementById('termsCheckbox');
    console.log('acceptTerms called');
    console.log('Checkbox checked:', checkbox ? checkbox.checked : 'null');
    console.log('Selected package ID:', selectedPackageId);
    
    if (checkbox && checkbox.checked && selectedPackageId) {
        console.log('Proceeding to buyPackage');
        const packageId = selectedPackageId; // ID'yi kaydet
        closeTermsModal(); // Modal'ı kapat (selectedPackageId = null olacak)
        buyPackage(packageId); // Kaydedilmiş ID'yi kullan
    } else {
        console.error('Cannot proceed:', {
            checkbox: checkbox ? 'exists' : 'null',
            checked: checkbox ? checkbox.checked : 'N/A',
            packageId: selectedPackageId
        });
    }
}

// Sayfa yüklendiğinde event listener'ları ekle
document.addEventListener('DOMContentLoaded', function() {
    // Checkbox değiştiğinde butonu aktifleştir
    const termsCheckbox = document.getElementById('termsCheckbox');
    if (termsCheckbox) {
        termsCheckbox.addEventListener('change', function() {
            const acceptBtn = document.getElementById('acceptBtn');
            if (this.checked) {
                acceptBtn.disabled = false;
                acceptBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                acceptBtn.classList.add('hover:bg-blue-700');
            } else {
                acceptBtn.disabled = true;
                acceptBtn.classList.add('opacity-50', 'cursor-not-allowed');
                acceptBtn.classList.remove('hover:bg-blue-700');
            }
        });
    }
    
    // ESC tuşu ile modal'ı kapat
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTermsModal();
        }
    });
});

/**
 * Paket satın alma işlemi
 * Stripe Checkout oturumu oluşturur ve yönlendirir
 */
function buyPackage(packageId) {
    console.log('buyPackage called with ID:', packageId);
    
    // Loading overlay göster
    const loadingOverlay = document.createElement('div');
    loadingOverlay.id = 'loading-overlay';
    loadingOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-[60] flex items-center justify-center';
    loadingOverlay.innerHTML = `
        <div class="bg-white rounded-2xl p-8 text-center shadow-2xl">
            <svg class="w-16 h-16 animate-spin mx-auto mb-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-xl font-bold text-gray-900">جاري التحميل...</p>
            <p class="text-sm text-gray-600 mt-2">يرجى الانتظار</p>
        </div>
    `;
    document.body.appendChild(loadingOverlay);
    
    // Stripe Checkout oturumu oluştur
    fetch('/provider/create-checkout-session', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'csrf_token=' + encodeURIComponent(csrfToken) + '&package_id=' + packageId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Stripe checkout URL:', data.checkout_url);
            // Stripe Checkout sayfasına yönlendir
            window.location.href = data.checkout_url;
        } else {
            console.error('Checkout session error:', data.error);
            document.body.removeChild(loadingOverlay);
            alert('❌ خطأ: ' + (data.error || 'فشل إنشاء جلسة الدفع'));
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        document.body.removeChild(loadingOverlay);
        alert('❌ حدث خطأ في الاتصال: ' + error.message);
    });
}
</script>

<?php
// İçeriği al
$content = ob_get_clean();

// Layout'u yükle
$pageTitle = $pageTitle ?? 'شراء حزمة';
$currentPage = 'browse-packages';
require __DIR__ . '/layout.php';
