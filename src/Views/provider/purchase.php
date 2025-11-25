<?php
// Provider layout'u başlat - içeriği ob_start ile yakala
$pageTitle = 'تأكيد الشراء';
$currentPage = 'browse-packages';
ob_start();

// Paket adını al
$packageName = $package['name_ar'] ?? ($package['lead_count'] == 1 ? 'حزمة طلب واحد' : 'حزمة ' . $package['lead_count'] . ' طلبات');
?>

<!-- Sayfa Başlığı -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">تأكيد الشراء</h1>
            <p class="text-gray-600 mt-1">مرحباً، <?= htmlspecialchars($provider['name'] ?? 'مقدم الخدمة') ?></p>
        </div>
        <a href="/provider/browse-packages" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors">
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
                <h3 class="text-xl font-bold text-gray-900 mb-4"><?= htmlspecialchars($packageName) ?></h3>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-center justify-between py-2 border-b border-green-200">
                        <span class="text-gray-700">عدد الطلبات:</span>
                        <span class="font-bold text-gray-900"><?= $package['lead_count'] ?> طلبات</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-2 border-b border-green-200">
                        <span class="text-gray-700">السعر:</span>
                        <span class="font-bold text-gray-900"><?= number_format($package['price_sar'], 2) ?> ريال</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-2">
                        <span class="text-gray-700">سعر الطلب الواحد:</span>
                        <span class="font-bold text-green-600"><?= number_format($package['price_per_lead'] ?? ($package['price_sar'] / $package['lead_count']), 2) ?> ريال</span>
                    </div>
                </div>
                
                <?php if (!empty($package['description_ar'])): ?>
                <div class="bg-white rounded-lg p-4 border border-green-200">
                    <p class="text-sm text-gray-700 leading-relaxed">
                        <?= htmlspecialchars($package['description_ar']) ?>
                    </p>
                </div>
                <?php endif; ?>
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
                                بعد شراء الحزمة، يمكنك طلب العملاء المحتملين من لوحة التحكم. سيتم إرسال طلبات العملاء إليك من قبل الإدارة.
                            </p>
                            <ul class="text-sm text-gray-700 space-y-2">
                                <li class="flex items-center gap-2">
                                    <span class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-bold">1</span>
                                    اشترِ الحزمة المناسبة
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-bold">2</span>
                                    اطلب عملاء من لوحة التحكم
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-bold">3</span>
                                    ستصلك بيانات العملاء من الإدارة
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Purchase Button -->
            <form id="purchaseForm" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="hidden" name="package_id" value="<?= $package['id'] ?>">
                
                <button type="submit" id="purchaseBtn"
                        class="w-full py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-all transform hover:scale-[1.02] shadow-lg hover:shadow-xl flex items-center justify-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <span>إتمام الشراء - <?= number_format($package['price_sar'], 2) ?> ريال</span>
                </button>
            </form>
            
            <!-- Security Note -->
            <div class="mt-6 flex items-center justify-center gap-2 text-sm text-gray-500">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>الدفع آمن ومشفر بالكامل عبر Stripe</span>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('purchaseForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('purchaseBtn');
    const originalText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>جاري التحويل للدفع...</span>
    `;
    
    try {
        const formData = new FormData(this);
        
        const response = await fetch('/provider/create-checkout-session', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success && result.url) {
            window.location.href = result.url;
        } else {
            alert(result.message || 'حدث خطأ أثناء إنشاء جلسة الدفع');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    } catch (error) {
        console.error('Error:', error);
        alert('حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
});
</script>

<?php
$content = ob_get_clean();

// Provider layout'u yükle
require __DIR__ . '/layout.php';
?>
