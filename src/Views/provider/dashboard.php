<?php
/**
 * Provider Dashboard - Modern & Sade Tasarım
 */
ob_start();

$provider = $provider ?? [];
$stats = $stats ?? [];
$activePackages = $activePackages ?? [];
$recentLeads = $recentLeads ?? [];

$serviceTypes = getServiceTypes();
$serviceName = $serviceTypes[$provider['service_type'] ?? '']['ar'] ?? '';
$statusLabels = [
    'pending' => ['label' => 'قيد المراجعة', 'color' => 'yellow', 'icon' => '⏳'],
    'active' => ['label' => 'نشط', 'color' => 'green', 'icon' => '✓'],
    'suspended' => ['label' => 'معلق', 'color' => 'red', 'icon' => '⚠'],
    'rejected' => ['label' => 'مرفوض', 'color' => 'gray', 'icon' => '✕']
];
$status = $statusLabels[$provider['status'] ?? 'pending'] ?? $statusLabels['pending'];

// E-posta doğrulama durumu
$emailVerified = $provider['email_verified'] ?? false;
$showVerificationBanner = !$emailVerified || (isset($_SESSION['show_email_verification_banner']) && $_SESSION['show_email_verification_banner']);
if (isset($_SESSION['show_email_verification_banner'])) unset($_SESSION['show_email_verification_banner']);
?>

<div class="max-w-5xl mx-auto px-4 py-6">

    <?php if ($showVerificationBanner && !$emailVerified): ?>
    <!-- E-posta Doğrulama Banner -->
    <div id="email-verification-banner" class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-2xl p-4 mb-6 shadow-lg">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white text-lg font-bold">يرجى تأكيد بريدك الإلكتروني</p>
                    <p class="text-yellow-100 text-sm">تحقق من بريدك الإلكتروني واضغط على رابط التأكيد</p>
                    <p class="text-yellow-200 text-xs mt-1">⚠️ تحقق أيضاً من مجلد الرسائل غير المرغوب فيها (Spam)</p>
                </div>
            </div>
            <button id="resend-verification-btn" 
                    onclick="resendVerificationEmail()"
                    class="bg-white text-orange-600 px-5 py-2 rounded-full shadow-md hover:bg-orange-50 transition-colors flex items-center gap-2 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                إعادة الإرسال
            </button>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">مرحباً، <?= htmlspecialchars($provider['name'] ?? '') ?></h1>
            <p class="text-sm text-gray-500"><?= htmlspecialchars($serviceName) ?></p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-<?= $status['color'] ?>-100 text-<?= $status['color'] ?>-700">
                <span><?= $status['icon'] ?></span>
                <?= $status['label'] ?>
            </span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        <div class="bg-white rounded-xl p-4 border border-gray-200">
            <div class="text-2xl font-bold text-gray-900"><?= $stats['total_leads_received'] ?? 0 ?></div>
            <div class="text-xs text-gray-500">طلب مستلم</div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200">
            <div class="text-2xl font-bold text-blue-600"><?= $stats['remaining_leads'] ?? 0 ?></div>
            <div class="text-xs text-gray-500">طلب متبقي</div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200">
            <div class="text-2xl font-bold text-green-600"><?= $stats['available_leads'] ?? 0 ?></div>
            <div class="text-xs text-gray-500">متاح للطلب</div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200">
            <div class="text-2xl font-bold text-gray-900"><?= $stats['total_purchases'] ?? 0 ?></div>
            <div class="text-xs text-gray-500">حزمة مشتراة</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 gap-3 mb-6">
        <a href="/provider/browse-packages" class="flex items-center gap-3 bg-green-600 hover:bg-green-700 text-white rounded-xl p-4 transition-colors">
            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <div>
                <div class="font-semibold">شراء حزمة</div>
                <div class="text-xs text-green-100">احصل على عملاء جدد</div>
            </div>
        </a>
        <a href="/provider/leads" class="flex items-center gap-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl p-4 transition-colors">
            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <div class="font-semibold">طلباتي</div>
                <div class="text-xs text-blue-100">عرض جميع الطلبات</div>
            </div>
        </a>
    </div>

    <!-- Active Packages -->
    <?php 
    $activePurchases = array_filter($activePackages, fn($p) => ($p['remaining_leads'] ?? 0) > 0);
    if (!empty($activePurchases)): 
    ?>
    <div class="bg-white rounded-xl border border-gray-200 mb-6">
        <div class="p-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-900">حزمي النشطة</h2>
        </div>
        <div class="divide-y divide-gray-100">
            <?php foreach ($activePurchases as $purchase): 
                $total = $purchase['leads_count'] ?? 0;
                $remaining = $purchase['remaining_leads'] ?? 0;
                $delivered = $total - $remaining;
                $percent = $total > 0 ? ($delivered / $total) * 100 : 0;
            ?>
            <div class="p-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <div class="font-semibold text-gray-900"><?= htmlspecialchars($purchase['package_name'] ?? ($total . ' طلب')) ?></div>
                        <div class="text-xs text-gray-500"><?= date('Y/m/d', strtotime($purchase['purchased_at'] ?? 'now')) ?></div>
                    </div>
                    <div class="text-left">
                        <div class="text-sm font-bold text-green-600"><?= $remaining ?> متبقي</div>
                        <div class="text-xs text-gray-400"><?= $delivered ?>/<?= $total ?></div>
                    </div>
                </div>
                
                <!-- Progress -->
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden mb-3">
                    <div class="h-full bg-green-500 rounded-full" style="width: <?= $percent ?>%"></div>
                </div>
                
                <!-- Request Button -->
                <?php if ($remaining > 0 && ($provider['status'] ?? '') === 'active'): ?>
                <button onclick="requestLead(<?= $purchase['id'] ?>)" 
                        id="btn-<?= $purchase['id'] ?>"
                        class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    طلب عميل جديد
                </button>
                <?php elseif (($provider['status'] ?? '') !== 'active'): ?>
                <div class="text-center py-2 text-sm text-yellow-600 bg-yellow-50 rounded-lg">
                    يجب تفعيل حسابك أولاً
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php else: ?>
    <!-- No Active Packages -->
    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 text-center mb-6">
        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <p class="text-gray-600 mb-3">لا توجد حزم نشطة</p>
        <a href="/provider/browse-packages" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            شراء حزمة
        </a>
    </div>
    <?php endif; ?>

    <!-- Recent Leads -->
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-900">آخر الطلبات</h2>
            <a href="/provider/leads" class="text-sm text-blue-600 hover:text-blue-700 font-medium">عرض الكل</a>
        </div>
        
        <?php if (empty($recentLeads)): ?>
        <div class="p-8 text-center">
            <p class="text-gray-500 text-sm">لا توجد طلبات بعد</p>
        </div>
        <?php else: ?>
        <div class="divide-y divide-gray-100">
            <?php foreach (array_slice($recentLeads, 0, 5) as $lead): 
                $leadService = $serviceTypes[$lead['service_type'] ?? '']['ar'] ?? '';
            ?>
            <div class="p-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($leadService) ?></div>
                            <div class="text-xs text-gray-500"><?= htmlspecialchars($lead['city'] ?? '') ?></div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-400">
                        <?= date('m/d', strtotime($lead['delivered_at'] ?? $lead['created_at'] ?? 'now')) ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-3 gap-3 mt-6">
        <a href="/provider/profile" class="bg-white rounded-xl border border-gray-200 p-4 text-center hover:border-blue-300 transition-colors">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div class="text-xs font-medium text-gray-700">الملف الشخصي</div>
        </a>
        <a href="/provider/messages" class="bg-white rounded-xl border border-gray-200 p-4 text-center hover:border-blue-300 transition-colors relative">
            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
            </div>
            <div class="text-xs font-medium text-gray-700">الرسائل</div>
            <?php if (($unreadMessages ?? 0) > 0): ?>
            <span class="absolute top-2 right-2 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center"><?= $unreadMessages ?></span>
            <?php endif; ?>
        </a>
        <a href="/provider/settings" class="bg-white rounded-xl border border-gray-200 p-4 text-center hover:border-blue-300 transition-colors">
            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="text-xs font-medium text-gray-700">الإعدادات</div>
        </a>
    </div>
</div>

<script>
async function requestLead(purchaseId) {
    const btn = document.getElementById('btn-' + purchaseId);
    if (!btn || btn.disabled) return;
    
    const original = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';
    
    try {
        const fd = new FormData();
        fd.append('purchase_id', purchaseId);
        fd.append('csrf_token', '<?= generateCsrfToken() ?>');
        
        const res = await fetch('/provider/request-lead', { method: 'POST', body: fd });
        const data = await res.json();
        
        if (data.success) {
            showToast('تم إرسال طلبك بنجاح', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message || 'حدث خطأ', 'error');
            btn.disabled = false;
            btn.innerHTML = original;
        }
    } catch (e) {
        showToast('حدث خطأ في الاتصال', 'error');
        btn.disabled = false;
        btn.innerHTML = original;
    }
}

function showToast(msg, type) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 left-1/2 -translate-x-1/2 z-50 px-4 py-3 rounded-lg shadow-lg text-sm font-medium ' + 
        (type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white');
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
}

// E-posta doğrulama yeniden gönder
async function resendVerificationEmail() {
    const btn = document.getElementById('resend-verification-btn');
    if (!btn || btn.disabled) return;
    
    const original = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> جاري الإرسال...';
    
    try {
        const fd = new FormData();
        fd.append('csrf_token', '<?= generateCsrfToken() ?>');
        
        const res = await fetch('/provider/resend-verification', { 
            method: 'POST', 
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        
        if (data.success) {
            showToast(data.message || 'تم إرسال رابط التأكيد بنجاح', 'success');
            btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> تم الإرسال';
            btn.classList.remove('bg-white', 'text-orange-600', 'hover:bg-orange-50');
            btn.classList.add('bg-green-500', 'text-white');
            
            // 2 dakika sonra butonu tekrar aktif et
            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = original;
                btn.classList.remove('bg-green-500', 'text-white');
                btn.classList.add('bg-white', 'text-orange-600', 'hover:bg-orange-50');
            }, 120000);
        } else {
            showToast(data.message || 'حدث خطأ', 'error');
            btn.disabled = false;
            btn.innerHTML = original;
            
            // Cooldown varsa butonu geçici olarak devre dışı bırak
            if (data.cooldown) {
                btn.disabled = true;
                let remaining = data.cooldown;
                const interval = setInterval(() => {
                    remaining--;
                    if (remaining <= 0) {
                        clearInterval(interval);
                        btn.disabled = false;
                        btn.innerHTML = original;
                    } else {
                        btn.innerHTML = `انتظر ${Math.ceil(remaining/60)} دقيقة`;
                    }
                }, 1000);
            }
        }
    } catch (e) {
        showToast('حدث خطأ في الاتصال', 'error');
        btn.disabled = false;
        btn.innerHTML = original;
    }
}
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'لوحة التحكم';
$currentPage = 'dashboard';
require __DIR__ . '/layout.php';
?>
