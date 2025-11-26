<?php ob_start(); 
$serviceTypes = $serviceTypes ?? getServiceTypes();
$cities = getCities();
?>

<!-- Başlık -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex items-center gap-4">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">الملف الشخصي</h1>
            <p class="text-sm text-gray-600">تحديث معلومات حسابك وتغيير كلمة المرور</p>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-green-800 font-medium"><?= htmlspecialchars($_SESSION['success']) ?></p>
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
            <p class="text-red-800 font-medium"><?= $_SESSION['error'] ?></p>
        </div>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Sol Kolon: Temel Bilgiler -->
    <div class="space-y-6">
        <!-- Kişisel Bilgiler -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    المعلومات الشخصية
                </h2>
            </div>
            <form action="/provider/profile" method="POST" class="p-6 space-y-5">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="hidden" name="action" value="update_profile">
                
                <!-- İsim -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">الاسم الكامل *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input type="text" id="name" name="name" 
                               value="<?= htmlspecialchars($provider['name'] ?? '') ?>"
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="أدخل اسمك الكامل" required>
                    </div>
                </div>
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">البريد الإلكتروني *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" 
                               value="<?= htmlspecialchars($provider['email'] ?? '') ?>"
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="example@email.com" required>
                    </div>
                </div>
                
                <!-- Telefon -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">رقم الهاتف *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <input type="tel" id="phone" name="phone" 
                               value="<?= htmlspecialchars($provider['phone'] ?? '') ?>"
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-right"
                               placeholder="05xxxxxxxx" required>
                    </div>
                </div>
                
                <button type="submit" 
                        class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    حفظ المعلومات الشخصية
                </button>
            </form>
        </div>
        
        <!-- Hizmet Bilgileri -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    معلومات الخدمة
                </h2>
            </div>
            <form action="/provider/profile" method="POST" class="p-6 space-y-5">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="hidden" name="action" value="update_service">
                
                <!-- Hizmet Türü -->
                <div>
                    <label for="service_type" class="block text-sm font-semibold text-gray-700 mb-2">نوع الخدمة</label>
                    <select id="service_type" name="service_type" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                        <?php foreach ($serviceTypes as $key => $service): ?>
                            <option value="<?= htmlspecialchars($key) ?>" <?= ($provider['service_type'] ?? '') === $key ? 'selected' : '' ?>>
                                <?= htmlspecialchars($service['ar'] ?? $key) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Şehir -->
                <div>
                    <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">المدينة</label>
                    <select id="city" name="city" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                        <?php foreach ($cities as $key => $city): ?>
                            <option value="<?= htmlspecialchars($key) ?>" <?= ($provider['city'] ?? '') === $key ? 'selected' : '' ?>>
                                <?= htmlspecialchars($city['ar'] ?? $key) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Açıklama -->
                <div>
                    <label for="bio" class="block text-sm font-semibold text-gray-700 mb-2">نبذة عنك</label>
                    <textarea id="bio" name="bio" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none"
                              placeholder="اكتب نبذة قصيرة عن خبرتك وخدماتك..."><?= htmlspecialchars($provider['bio'] ?? '') ?></textarea>
                </div>
                
                <button type="submit" 
                        class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    حفظ معلومات الخدمة
                </button>
            </form>
        </div>
    </div>
    
    <!-- Sağ Kolon: Şifre ve Hesap Durumu -->
    <div class="space-y-6">
        <!-- Hesap Durumu -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    حالة الحساب
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-gray-500 mb-1">الحالة</p>
                        <?php 
                        $statusConfig = [
                            'active' => ['label' => 'نشط', 'color' => 'green', 'icon' => '✓'],
                            'pending' => ['label' => 'قيد المراجعة', 'color' => 'yellow', 'icon' => '⏳'],
                            'suspended' => ['label' => 'معلق', 'color' => 'red', 'icon' => '⚠'],
                            'rejected' => ['label' => 'مرفوض', 'color' => 'gray', 'icon' => '✕']
                        ];
                        $status = $statusConfig[$provider['status'] ?? 'pending'] ?? $statusConfig['pending'];
                        ?>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-semibold bg-<?= $status['color'] ?>-100 text-<?= $status['color'] ?>-700">
                            <span><?= $status['icon'] ?></span>
                            <?= $status['label'] ?>
                        </span>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-gray-500 mb-1">تاريخ التسجيل</p>
                        <p class="font-bold text-gray-900"><?= date('Y/m/d', strtotime($provider['created_at'] ?? 'now')) ?></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-gray-500 mb-1">التقييم</p>
                        <p class="font-bold text-yellow-600 flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <?= number_format($provider['rating'] ?? 0, 1) ?>
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-gray-500 mb-1">إجمالي الأعمال</p>
                        <p class="font-bold text-gray-900"><?= $provider['total_jobs'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Şifre Değiştirme -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    تغيير كلمة المرور
                </h2>
            </div>
            <form action="/provider/profile" method="POST" class="p-6 space-y-5">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="hidden" name="action" value="change_password">
                
                <!-- Mevcut Şifre -->
                <div>
                    <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">كلمة المرور الحالية *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" id="current_password" name="current_password" 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                               placeholder="أدخل كلمة المرور الحالية" required>
                    </div>
                </div>
                
                <!-- Yeni Şifre -->
                <div>
                    <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">كلمة المرور الجديدة *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <input type="password" id="new_password" name="new_password" 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                               placeholder="أدخل كلمة المرور الجديدة (6 أحرف على الأقل)" required minlength="6">
                    </div>
                </div>
                
                <!-- Şifre Onay -->
                <div>
                    <label for="confirm_password" class="block text-sm font-semibold text-gray-700 mb-2">تأكيد كلمة المرور الجديدة *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <input type="password" id="confirm_password" name="confirm_password" 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                               placeholder="أعد إدخال كلمة المرور الجديدة" required minlength="6">
                    </div>
                </div>
                
                <button type="submit" 
                        class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    تغيير كلمة المرور
                </button>
            </form>
        </div>
        
        <!-- Son Giriş Bilgisi -->
        <?php if (!empty($provider['last_login_at'])): ?>
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500">آخر تسجيل دخول</p>
                    <p class="text-sm font-medium text-gray-700"><?= date('Y/m/d H:i', strtotime($provider['last_login_at'])) ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'الملف الشخصي';
$currentPage = 'profile';
require __DIR__ . '/layout.php';
?>
