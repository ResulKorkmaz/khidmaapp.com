<?php
/**
 * Provider Lead Paketleri - Kompakt Görünüm
 * Sadece 2 paket: 1 Lead ve 3 Lead
 */

ob_start();

$provider = $provider ?? [];
$packages = $packages ?? [];
$isActive = ($provider['status'] ?? '') === 'active';
$serviceTypes = getServiceTypes();
$providerServiceName = $serviceTypes[$provider['service_type'] ?? '']['ar'] ?? ($provider['service_type'] ?? '');
?>

<div class="max-w-3xl mx-auto px-4 py-6">
    <?php if (!$isActive): ?>
    <!-- Hesap Aktif Değil Uyarısı -->
    <div class="mb-4 bg-yellow-50 border border-yellow-300 rounded-xl p-4 text-center">
        <div class="flex items-center justify-center gap-2 mb-2">
            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span class="font-bold text-yellow-800">حسابك غير مفعّل</span>
        </div>
        <p class="text-yellow-700 text-sm">يرجى الانتظار حتى يتم مراجعة حسابك من قبل الإدارة</p>
    </div>
    <?php endif; ?>
    
    <!-- Başlık -->
    <div class="mb-6 text-center">
        <a href="/provider/dashboard" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-blue-600 mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            العودة
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mb-1">حزم الطلبات</h1>
        <p class="text-gray-600 text-sm">خدمة <span class="font-semibold text-blue-600"><?= htmlspecialchars($providerServiceName) ?></span></p>
    </div>
    
    <?php if (empty($packages)): ?>
        <div class="bg-white rounded-xl shadow p-8 text-center">
            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <p class="text-gray-600">لا توجد حزم متاحة حالياً</p>
        </div>
    <?php else: ?>
        <!-- ✅ Onay Kutusu - EN ÜSTTE -->
        <div class="mb-4 bg-green-50 border-2 border-green-300 rounded-lg p-4">
            <label class="flex items-start gap-3 cursor-pointer select-none">
                <input type="checkbox" id="policy-accept" class="w-5 h-5 mt-0.5 text-green-600 border-2 border-green-400 rounded focus:ring-green-500 cursor-pointer">
                <div class="text-sm">
                    <p class="font-bold text-green-800">
                        ✅ قرأت وفهمت <a href="/provider/lead-policy" target="_blank" class="underline hover:text-green-900">سياسة جودة الطلبات</a> وأوافق عليها
                    </p>
                    <p class="text-green-700 mt-1 text-xs">
                        أفهم أن الطلبات غير الصالحة في <strong>الشراء الأول</strong> سيتم استبدالها، وفي الشراء التالي سأحصل على <strong>+1 طلب إضافي</strong> (مرة واحدة فقط).
                    </p>
                </div>
            </label>
        </div>
        
        <!-- Paket Kartları -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <?php foreach ($packages as $package): 
                $discount = floatval($package['discount_percentage'] ?? 0);
                $pricePerLead = floatval($package['price_per_lead'] ?? ($package['price_sar'] / $package['lead_count']));
                $isPopular = $package['lead_count'] == 3;
                $bgColor = $isPopular ? 'from-green-500 to-green-600' : 'from-blue-500 to-blue-600';
                $btnColor = $isPopular ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700';
                $textColor = $isPopular ? 'text-green-600' : 'text-blue-600';
            ?>
                <div class="bg-white rounded-xl shadow-md border <?= $isPopular ? 'border-green-400' : 'border-gray-200' ?> overflow-hidden relative">
                    <?php if ($isPopular): ?>
                    <div class="absolute top-0 right-0 bg-green-500 text-white px-3 py-0.5 text-xs font-bold rounded-bl-lg z-20">
                        ⭐ الأفضل
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($discount > 0): ?>
                    <div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-0.5 rounded-full text-xs font-bold z-20">
                        -<?= number_format($discount) ?>%
                    </div>
                    <?php endif; ?>
                    
                    <!-- Header - Kompakt -->
                    <div class="bg-gradient-to-r <?= $bgColor ?> p-5 text-center">
                        <div class="text-5xl font-black text-white mb-1" style="text-shadow: 0 2px 4px rgba(0,0,0,0.2);"><?= $package['lead_count'] ?></div>
                        <div class="text-lg font-bold text-white" style="text-shadow: 0 1px 2px rgba(0,0,0,0.2);">طلب</div>
                    </div>
                    
                    <!-- İçerik - Kompakt -->
                    <div class="p-4">
                        <!-- Fiyat -->
                        <div class="text-center mb-3 pb-3 border-b border-gray-100">
                            <div class="flex items-baseline justify-center gap-1">
                                <span class="text-3xl font-black <?= $textColor ?>"><?= number_format($package['price_sar'], 0) ?></span>
                                <span class="text-base font-semibold text-gray-500">ريال</span>
                            </div>
                            <div class="text-xs text-gray-400 mt-1"><?= number_format($pricePerLead, 0) ?> ريال / طلب</div>
                        </div>
                        
                        <!-- Özellikler - Kompakt -->
                        <ul class="space-y-1.5 mb-4 text-sm">
                            <li class="flex items-center gap-2 text-gray-600">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <?= $package['lead_count'] ?> عميل مضمون
                            </li>
                            <li class="flex items-center gap-2 text-gray-600">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                معلومات الاتصال الكاملة
                            </li>
                            <?php if ($discount > 0): ?>
                            <li class="flex items-center gap-2 text-green-600 font-medium">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                وفر <?= number_format($discount) ?>%!
                            </li>
                            <?php endif; ?>
                        </ul>
                        
                        <!-- Buton - Başlangıçta Pasif -->
                        <?php if ($isActive): ?>
                        <a href="/provider/purchase/<?= $package['id'] ?>" 
                           id="buy-btn-<?= $package['id'] ?>"
                           class="buy-button block w-full py-3 bg-gray-300 text-gray-500 font-bold rounded-lg text-center cursor-not-allowed transition-all">
                            اشترِ الآن
                        </a>
                        <?php else: ?>
                        <button disabled class="block w-full py-3 bg-gray-200 text-gray-400 font-bold rounded-lg cursor-not-allowed">
                            يجب تفعيل الحساب
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Bilgi Kutusu - Kompakt -->
        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-sm">
                    <p class="font-semibold text-gray-800 mb-1">كيف يعمل؟</p>
                    <p class="text-gray-600">اشترِ الحزمة ← اطلب عميل من لوحة التحكم ← ستصلك البيانات من الإدارة</p>
                </div>
            </div>
        </div>
        
        <!-- ⚠️ Önemli Uyarı - Lead Kalitesi -->
        <div class="mt-4 bg-amber-50 border border-amber-300 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="text-sm">
                    <p class="font-bold text-amber-800 mb-2">⚠️ تنبيه مهم قبل الشراء</p>
                    <ul class="text-amber-700 space-y-1.5 list-disc list-inside">
                        <li>الطلبات تأتي من إعلانات Google ونماذج الموقع</li>
                        <li>قد تحتوي بعض الطلبات على بيانات غير صحيحة أو أرقام خاطئة</li>
                        <li><strong>لا نضمن صحة 100% لبيانات العملاء</strong></li>
                        <li>يمكنك الإبلاغ عن طلب غير صالح خلال 48 ساعة</li>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// Onay kutusu kontrolü
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('policy-accept');
    const policyBox = checkbox ? checkbox.closest('.bg-green-50') : null;
    const buyButtons = document.querySelectorAll('.buy-button');
    
    if (checkbox && buyButtons.length > 0) {
        // Checkbox değiştiğinde butonları güncelle
        checkbox.addEventListener('change', function() {
            buyButtons.forEach(btn => {
                if (this.checked) {
                    // Aktif yap
                    btn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
                    btn.removeAttribute('data-disabled');
                    
                    // Butonun paket ID'sine göre renk belirle
                    // 3'lü paket yeşil, diğerleri mavi
                    if (btn.closest('.border-green-400')) {
                        btn.classList.add('bg-green-600', 'hover:bg-green-700', 'text-white', 'hover:shadow-lg');
                    } else {
                        btn.classList.add('bg-blue-600', 'hover:bg-blue-700', 'text-white', 'hover:shadow-lg');
                    }
                } else {
                    // Pasif yap
                    btn.classList.remove('bg-green-600', 'hover:bg-green-700', 'bg-blue-600', 'hover:bg-blue-700', 'text-white', 'hover:shadow-lg');
                    btn.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
                    btn.setAttribute('data-disabled', 'true');
                }
            });
            
            // Onay kutusu işaretlendiğinde vurguyu kaldır
            if (this.checked && policyBox) {
                policyBox.classList.remove('ring-4', 'ring-red-400', 'animate-pulse');
            }
        });
        
        // Pasif butona tıklandığında uyarı göster ve onay kutusuna yönlendir
        buyButtons.forEach(btn => {
            btn.setAttribute('data-disabled', 'true');
            btn.addEventListener('click', function(e) {
                if (this.getAttribute('data-disabled') === 'true') {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Bildirim göster
                    showNotification('⚠️ يرجى الموافقة على شروط الشراء أولاً', 'warning');
                    
                    // Onay kutusunu vurgula ve kaydır
                    if (policyBox) {
                        policyBox.classList.add('ring-4', 'ring-red-400', 'animate-pulse');
                        policyBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        
                        // 3 saniye sonra vurguyu kaldır
                        setTimeout(() => {
                            policyBox.classList.remove('ring-4', 'ring-red-400', 'animate-pulse');
                        }, 3000);
                    }
                    
                    return false;
                }
            });
        });
    }
});

// Bildirim gösterme fonksiyonu
function showNotification(message, type = 'info') {
    // Mevcut bildirimi kaldır
    const existing = document.getElementById('toast-notification');
    if (existing) existing.remove();
    
    // Renk ayarları
    const colors = {
        warning: 'bg-amber-500',
        error: 'bg-red-500',
        success: 'bg-green-500',
        info: 'bg-blue-500'
    };
    
    // Bildirim oluştur
    const toast = document.createElement('div');
    toast.id = 'toast-notification';
    toast.className = `fixed top-4 left-1/2 transform -translate-x-1/2 ${colors[type]} text-white px-6 py-3 rounded-xl shadow-2xl z-50 flex items-center gap-3 animate-bounce`;
    toast.innerHTML = `
        <span class="text-lg">${message}</span>
        <button onclick="this.parentElement.remove()" class="text-white/80 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;
    
    document.body.appendChild(toast);
    
    // 4 saniye sonra otomatik kapat
    setTimeout(() => {
        if (toast.parentElement) {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }
    }, 4000);
}
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'حزم الطلبات';
$currentPage = 'browse-packages';
require __DIR__ . '/layout.php';
?>
