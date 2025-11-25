<?php ob_start(); ?>

<!-- Başlık -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
        <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        الإعدادات
    </h1>
    <p class="text-sm text-gray-600 mt-1">إدارة إعدادات حسابك</p>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-green-800"><?= htmlspecialchars($_SESSION['success']) ?></p>
        </div>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-red-800"><?= $_SESSION['error'] ?></p>
        </div>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="space-y-6">
    <!-- Şifre Değiştirme -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            تغيير كلمة المرور
        </h2>
        
        <form action="/provider/settings" method="POST" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="action" value="change_password">
            
            <div>
                <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">كلمة المرور الحالية</label>
                <input type="password" id="current_password" name="current_password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                       placeholder="أدخل كلمة المرور الحالية" required>
            </div>
            
            <div>
                <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">كلمة المرور الجديدة</label>
                <input type="password" id="new_password" name="new_password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                       placeholder="أدخل كلمة المرور الجديدة" required minlength="6">
            </div>
            
            <div>
                <label for="confirm_password" class="block text-sm font-semibold text-gray-700 mb-2">تأكيد كلمة المرور</label>
                <input type="password" id="confirm_password" name="confirm_password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                       placeholder="أعد إدخال كلمة المرور الجديدة" required minlength="6">
            </div>
            
            <div class="flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    تحديث كلمة المرور
                </button>
            </div>
        </form>
    </div>
    
    <!-- Bildirim Ayarları -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            إعدادات الإشعارات
        </h2>
        
        <form action="/provider/settings" method="POST" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="action" value="update_notifications">
            
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div>
                    <h3 class="font-semibold text-gray-900">إشعارات البريد الإلكتروني</h3>
                    <p class="text-sm text-gray-500">استلام إشعارات عبر البريد الإلكتروني</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="email_notifications" class="sr-only peer" 
                           <?= ($provider['email_notifications'] ?? 1) ? 'checked' : '' ?>>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                </label>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div>
                    <h3 class="font-semibold text-gray-900">إشعارات الرسائل القصيرة</h3>
                    <p class="text-sm text-gray-500">استلام إشعارات عبر الرسائل القصيرة</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="sms_notifications" class="sr-only peer"
                           <?= ($provider['sms_notifications'] ?? 0) ? 'checked' : '' ?>>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                </label>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    حفظ الإعدادات
                </button>
            </div>
        </form>
    </div>
    
    <!-- Hesap Durumu -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            حالة الحساب
        </h2>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <span class="font-semibold text-gray-700">حالة الحساب</span>
                <?php
                $statusColors = [
                    'active' => 'bg-green-100 text-green-800',
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'suspended' => 'bg-red-100 text-red-800',
                    'rejected' => 'bg-red-100 text-red-800',
                ];
                $statusLabels = [
                    'active' => 'نشط',
                    'pending' => 'قيد المراجعة',
                    'suspended' => 'معلق',
                    'rejected' => 'مرفوض',
                ];
                $status = $provider['status'] ?? 'pending';
                $statusColor = $statusColors[$status] ?? 'bg-gray-100 text-gray-800';
                $statusLabel = $statusLabels[$status] ?? $status;
                ?>
                <span class="px-3 py-1 rounded-full text-sm font-semibold <?= $statusColor ?>">
                    <?= $statusLabel ?>
                </span>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <span class="font-semibold text-gray-700">تاريخ التسجيل</span>
                <span class="text-gray-600"><?= date('Y-m-d', strtotime($provider['created_at'] ?? 'now')) ?></span>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <span class="font-semibold text-gray-700">البريد الإلكتروني</span>
                <div class="flex items-center gap-2">
                    <?php if (!empty($provider['email_verified_at'])): ?>
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-green-600 text-sm">تم التحقق</span>
                    <?php else: ?>
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-yellow-600 text-sm">لم يتم التحقق</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'الإعدادات';
$currentPage = 'settings';
require __DIR__ . '/layout.php';
?>

