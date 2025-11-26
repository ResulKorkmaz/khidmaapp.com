<?php
/**
 * Kullanıcı Düzenleme
 */
$pageTitle = 'Kullanıcı Düzenle';
$currentPage = 'users';
ob_start();

$user = $user ?? [];
$currentRole = $currentRole ?? 'user';
$superAdminUsername = $superAdminUsername ?? 'rslkrkmz';
$isSuperAdmin = ($user['username'] ?? '') === $superAdminUsername;
$isOwnAccount = ($user['id'] ?? 0) == ($_SESSION['admin_id'] ?? 0);
?>

<div class="container mx-auto px-4 py-4 max-w-lg">
    <!-- Header -->
    <div class="mb-4">
        <a href="/admin/users" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 text-sm mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Geri Dön
        </a>
        <h1 class="text-xl font-bold text-gray-900">✏️ Kullanıcı Düzenle</h1>
        <p class="text-gray-600 text-sm mt-1">
            <?= htmlspecialchars($user['username'] ?? '') ?> kullanıcısının bilgilerini düzenleyin
        </p>
    </div>

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
            Bu Super Admin hesabının kullanıcı adı değiştirilemez.
        </div>
    <?php endif; ?>

    <?php if ($isOwnAccount): ?>
        <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
            <span>ℹ️</span>
            Kendi hesabınızı düzenliyorsunuz.
        </div>
    <?php endif; ?>

    <!-- Form -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <form method="POST" action="/admin/users/update" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?? '' ?>">
            
            <!-- Kullanıcı Adı -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                    Kullanıcı Adı <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       required 
                       <?= $isSuperAdmin ? 'readonly' : '' ?>
                       value="<?= htmlspecialchars($user['username'] ?? '') ?>"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent <?= $isSuperAdmin ? 'bg-gray-100 cursor-not-allowed' : '' ?>">
                <?php if ($isSuperAdmin): ?>
                    <p class="mt-1 text-xs text-yellow-600">🔒 Super Admin kullanıcı adı değiştirilemez</p>
                <?php endif; ?>
            </div>

            <!-- E-posta -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    E-posta <span class="text-red-500">*</span>
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       required 
                       value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Yeni Şifre -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    Yeni Şifre <span class="text-gray-400">(Opsiyonel)</span>
                </label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       minlength="6"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Değiştirmek için yeni şifre girin">
                <p class="mt-1 text-xs text-gray-500">Boş bırakırsanız şifre değişmez. Minimum 6 karakter.</p>
            </div>

            <!-- Rol Bilgisi (readonly) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                <?php
                $roleColors = [
                    'super_admin' => 'bg-purple-100 text-purple-700 border-purple-200',
                    'admin' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'user' => 'bg-gray-100 text-gray-700 border-gray-200'
                ];
                $roleNames = [
                    'super_admin' => '👑 Super Admin',
                    'admin' => '🔑 Admin',
                    'user' => '👤 User'
                ];
                $roleColor = $roleColors[$user['role'] ?? 'user'] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                $roleName = $roleNames[$user['role'] ?? 'user'] ?? 'User';
                ?>
                <div class="px-3 py-2 border rounded-lg text-sm <?= $roleColor ?>">
                    <?= $roleName ?>
                </div>
                <p class="mt-1 text-xs text-gray-500">Rol değişikliği için yöneticinize başvurun.</p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-2 pt-2">
                <button type="submit" 
                        class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium text-sm">
                    💾 Kaydet
                </button>
                <a href="/admin/users" 
                   class="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition font-medium text-center text-sm">
                    İptal
                </a>
            </div>
        </form>
    </div>

    <!-- Info -->
    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
        <h3 class="font-semibold text-blue-900 text-sm mb-1">💡 Bilgi</h3>
        <ul class="text-xs text-blue-700 space-y-0.5">
            <li>• Kullanıcı adı benzersiz olmalıdır</li>
            <li>• E-posta adresi geçerli olmalıdır</li>
            <li>• Şifre değiştirmek için yeni şifre girin</li>
            <?php if ($isSuperAdmin): ?>
            <li>• Super Admin kullanıcı adı korumalıdır</li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>

