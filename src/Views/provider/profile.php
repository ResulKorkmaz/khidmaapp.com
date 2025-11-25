<?php ob_start(); ?>

<!-- Başlık -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        الملف الشخصي
    </h1>
    <p class="text-sm text-gray-600 mt-1">تحديث معلومات حسابك</p>
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

<!-- Profil Formu -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
    <form action="/provider/profile" method="POST" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        
        <!-- İsim -->
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">الاسم الكامل</label>
            <input type="text" id="name" name="name" 
                   value="<?= htmlspecialchars($provider['name'] ?? '') ?>"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                   placeholder="أدخل اسمك الكامل" required>
        </div>
        
        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">البريد الإلكتروني</label>
            <input type="email" id="email" name="email" 
                   value="<?= htmlspecialchars($provider['email'] ?? '') ?>"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                   placeholder="example@email.com" required>
        </div>
        
        <!-- Telefon -->
        <div>
            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">رقم الهاتف</label>
            <input type="tel" id="phone" name="phone" 
                   value="<?= htmlspecialchars($provider['phone'] ?? '') ?>"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                   placeholder="+971 XX XXX XXXX" required>
        </div>
        
        <!-- Hizmet Türü -->
        <div>
            <label for="service_type" class="block text-sm font-semibold text-gray-700 mb-2">نوع الخدمة</label>
            <select id="service_type" name="service_type" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                <?php foreach ($serviceTypes ?? [] as $key => $service): ?>
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
                <?php 
                $cities = getCities();
                foreach ($cities as $key => $city): 
                ?>
                    <option value="<?= htmlspecialchars($key) ?>" <?= ($provider['city'] ?? '') === $key ? 'selected' : '' ?>>
                        <?= htmlspecialchars($city['ar'] ?? $key) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <!-- Açıklama -->
        <div>
            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">نبذة عنك</label>
            <textarea id="description" name="description" rows="4"
                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none"
                      placeholder="اكتب نبذة قصيرة عن خبرتك وخدماتك..."><?= htmlspecialchars($provider['description'] ?? '') ?></textarea>
        </div>
        
        <!-- Kaydet Butonu -->
        <div class="flex justify-end">
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                حفظ التغييرات
            </button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'الملف الشخصي';
$currentPage = 'profile';
require __DIR__ . '/layout.php';
?>

