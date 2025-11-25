<?php
/**
 * Provider Lead Paketleri Görünüm Sayfası
 * Sadece 2 paket: 1 Lead ve 3 Lead
 */

// Layout için içerik yakalama başlat
ob_start();

// Sayfa verilerini al
$provider = $provider ?? [];
$packages = $packages ?? [];
$isActive = ($provider['status'] ?? '') === 'active';

// Ustanın hizmet türü adını al
$serviceTypes = getServiceTypes();
$providerServiceName = $serviceTypes[$provider['service_type'] ?? '']['ar'] ?? ($provider['service_type'] ?? '');
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
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
    <div class="mb-6 sm:mb-8 text-center">
        <div class="flex items-center justify-center gap-3 mb-4">
            <a href="/provider/dashboard" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-300 text-gray-700 hover:bg-gray-50 hover:border-blue-500 rounded-xl transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="text-sm font-semibold">العودة</span>
            </a>
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-gray-900 mb-3">حزم الطلبات المتاحة</h1>
        <p class="text-base sm:text-lg text-gray-600">
            اختر الحزمة المناسبة لخدمة <span class="font-bold text-blue-600"><?= htmlspecialchars($providerServiceName) ?></span>
        </p>
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
            <p class="text-sm sm:text-base text-gray-600">سيتم إضافة حزم جديدة قريباً</p>
        </div>
    <?php else: ?>
        <!-- Paket Kartları - 2 paket yan yana -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
            <?php foreach ($packages as $index => $package): 
                $discount = floatval($package['discount_percentage'] ?? 0);
                $pricePerLead = floatval($package['price_per_lead'] ?? ($package['price_sar'] / $package['lead_count']));
                $isPopular = $package['lead_count'] == 3; // 3'lü paket popüler
            ?>
                <div class="bg-white rounded-2xl shadow-lg border-2 <?= $isPopular ? 'border-green-500 ring-2 ring-green-200' : 'border-gray-200' ?> hover:shadow-2xl transition-all duration-300 overflow-hidden relative">
                    <?php if ($isPopular): ?>
                        <!-- Popüler Badge -->
                        <div class="absolute top-0 right-0 bg-green-500 text-white px-4 py-1 text-xs font-bold rounded-bl-xl">
                            الأكثر شعبية ⭐
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($discount > 0): ?>
                        <!-- İndirim Badge -->
                        <div class="absolute top-3 left-3 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg z-10">
                            وفر <?= number_format($discount) ?>%
                        </div>
                    <?php endif; ?>
                    
                    <!-- Paket Başlığı -->
                    <div class="<?= $isPopular ? 'bg-gradient-to-br from-green-600 to-green-700' : 'bg-gradient-to-br from-blue-600 to-blue-700' ?> text-white p-8 text-center relative overflow-hidden">
                        <!-- Decorative circles -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-5 rounded-full -ml-12 -mb-12"></div>
                        
                        <div class="relative z-10">
                            <div class="text-6xl sm:text-7xl font-black mb-2"><?= $package['lead_count'] ?></div>
                            <div class="text-2xl font-bold opacity-95">طلب</div>
                            <div class="mt-3 text-white/80 text-sm font-medium">
                                <?= htmlspecialchars($package['name_ar'] ?? ($package['lead_count'] == 1 ? 'حزمة طلب واحد' : 'حزمة ' . $package['lead_count'] . ' طلبات')) ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Paket İçeriği -->
                    <div class="p-6 sm:p-8">
                        <!-- Fiyat Bilgisi -->
                        <div class="text-center mb-6 pb-6 border-b-2 border-gray-100">
                            <div class="inline-flex items-baseline gap-2 mb-2">
                                <span class="text-5xl font-black <?= $isPopular ? 'text-green-600' : 'text-blue-600' ?>"><?= number_format($package['price_sar'], 0) ?></span>
                                <span class="text-xl font-bold text-gray-600">ريال</span>
                            </div>
                            <div class="flex items-center justify-center gap-2 mt-2">
                                <div class="h-px w-8 bg-gray-300"></div>
                                <div class="text-sm font-medium text-gray-500 bg-gray-50 px-3 py-1 rounded-full">
                                    <?= number_format($pricePerLead, 0) ?> ريال / طلب
                                </div>
                                <div class="h-px w-8 bg-gray-300"></div>
                            </div>
                        </div>
                        
                        <!-- Paket Özellikleri -->
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-center gap-3 text-gray-700">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span><?= $package['lead_count'] ?> عميل محتمل مضمون</span>
                            </li>
                            <li class="flex items-center gap-3 text-gray-700">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>معلومات الاتصال الكاملة</span>
                            </li>
                            <li class="flex items-center gap-3 text-gray-700">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>إرسال يدوي من الإدارة</span>
                            </li>
                            <?php if ($discount > 0): ?>
                            <li class="flex items-center gap-3 text-green-700 font-medium">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>توفير <?= number_format($discount) ?>% من السعر!</span>
                            </li>
                            <?php endif; ?>
                        </ul>
                        
                        <!-- Açıklama -->
                        <?php if (!empty($package['description_ar'])): ?>
                        <p class="text-sm text-gray-600 mb-6 text-center leading-relaxed">
                            <?= htmlspecialchars($package['description_ar']) ?>
                        </p>
                        <?php endif; ?>
                        
                        <!-- Satın Al Butonu -->
                        <?php if ($isActive): ?>
                            <a href="/provider/purchase/<?= $package['id'] ?>" 
                               class="block w-full py-4 <?= $isPopular ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700' ?> text-white font-bold rounded-xl transition-all transform hover:scale-[1.02] shadow-lg hover:shadow-xl text-center">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    اشترِ الآن
                                </span>
                            </a>
                        <?php else: ?>
                            <button disabled class="block w-full py-4 bg-gray-300 text-gray-500 font-bold rounded-xl cursor-not-allowed text-center">
                                يجب تفعيل الحساب أولاً
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Bilgilendirme Kutusu -->
        <div class="mt-8 bg-blue-50 border-2 border-blue-200 rounded-2xl p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 text-lg mb-2">كيف يعمل النظام؟</h4>
                    <ul class="text-sm text-gray-700 space-y-2">
                        <li class="flex items-center gap-2">
                            <span class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-bold">1</span>
                            اشترِ الحزمة المناسبة لك
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-bold">2</span>
                            اضغط على "طلب عميل" من لوحة التحكم
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-bold">3</span>
                            ستصلك بيانات العميل من الإدارة
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();

// Provider layout'u yükle
$pageTitle = 'حزم الطلبات';
$currentPage = 'browse-packages';
require __DIR__ . '/layout.php';
?>
