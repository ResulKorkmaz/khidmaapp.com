<?php
/**
 * Profil Ayarları
 * Her kullanıcı kendi profilini düzenleyebilir
 */
$pageTitle = 'Profil Ayarları';
$currentPage = 'profile';
ob_start();

$user = $user ?? [];
$currentRole = $currentRole ?? 'user';
$superAdminUsername = $superAdminUsername ?? 'rslkrkmz';
$isSuperAdmin = ($user['username'] ?? '') === $superAdminUsername;

$roleNames = [
    'super_admin' => '👑 Super Admin',
    'admin' => '🔑 Admin',
    'user' => '👤 User'
];
$roleName = $roleNames[$user['role'] ?? 'user'] ?? 'User';
?>

<div class="container mx-auto px-4 py-4 max-w-lg">
    <!-- Header -->
    <div class="bg-blue-600 rounded-xl p-4 mb-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-white text-xl font-bold">
                <?= strtoupper(substr($user['username'] ?? '', 0, 2)) ?>
            </div>
            <div>
                <h1 class="text-lg font-bold text-white">Profil Ayarları</h1>
                <p class="text-blue-200 text-xs"><?= $roleName ?></p>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
            <span>✅</span>
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
            <span>❌</span>
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if ($isSuperAdmin): ?>
        <div class="mb-4 bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
            <span>🔒</span>
            Super Admin kullanıcı adı değiştirilemez.
        </div>
    <?php endif; ?>

    <!-- Profil Bilgileri -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 mb-4">
        <h2 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
            <span>📋</span> Hesap Bilgileri
        </h2>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Kullanıcı Adı</span>
                <span class="font-medium text-gray-900"><?= htmlspecialchars($user['username'] ?? '') ?></span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">E-posta</span>
                <span class="font-medium text-gray-900"><?= htmlspecialchars($user['email'] ?? '') ?></span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Rol</span>
                <span class="font-medium text-gray-900"><?= $roleName ?></span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Kayıt Tarihi</span>
                <span class="font-medium text-gray-900"><?= date('d.m.Y', strtotime($user['created_at'] ?? 'now')) ?></span>
            </div>
            <div class="flex justify-between py-2">
                <span class="text-gray-500">Son Giriş</span>
                <span class="font-medium text-gray-900">
                    <?= $user['last_login'] ? date('d.m.Y H:i', strtotime($user['last_login'])) : 'Şimdi' ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Profil Düzenleme Formu -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <h2 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
            <span>✏️</span> Bilgileri Güncelle
        </h2>
        <form method="POST" action="/admin/profile/update" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <!-- Kullanıcı Adı -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                    Kullanıcı Adı
                </label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       required 
                       <?= $isSuperAdmin ? 'readonly' : '' ?>
                       value="<?= htmlspecialchars($user['username'] ?? '') ?>"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent <?= $isSuperAdmin ? 'bg-gray-100 cursor-not-allowed' : '' ?>">
                <?php if ($isSuperAdmin): ?>
                    <p class="mt-1 text-xs text-yellow-600">🔒 Korumalı - değiştirilemez</p>
                <?php endif; ?>
            </div>

            <!-- E-posta -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    E-posta
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       required 
                       value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <hr class="border-gray-200">

            <p class="text-xs text-gray-500">Şifrenizi değiştirmek için aşağıdaki alanları doldurun:</p>

            <!-- Mevcut Şifre -->
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                    Mevcut Şifre
                </label>
                <input type="password" 
                       id="current_password" 
                       name="current_password" 
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Şifre değiştirmek için gerekli">
            </div>

            <!-- Yeni Şifre -->
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">
                    Yeni Şifre
                </label>
                <input type="password" 
                       id="new_password" 
                       name="new_password" 
                       minlength="6"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Minimum 6 karakter">
            </div>

            <!-- Kaydet Butonu -->
            <button type="submit" 
                    class="w-full bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 transition font-medium text-sm">
                💾 Değişiklikleri Kaydet
            </button>
        </form>
    </div>

    <!-- Geri Dön -->
    <div class="mt-4 text-center">
        <a href="/admin" class="text-sm text-blue-600 hover:text-blue-800">
            ← Dashboard'a Dön
        </a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>

