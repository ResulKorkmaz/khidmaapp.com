<?php
/**
 * Kullanıcı Yönetimi - Yeni Kullanıcı Oluştur
 * Super Admin ve Admin erişebilir
 */
$pageTitle = 'Yeni Kullanıcı Oluştur';
$currentPage = 'users';

// Mevcut kullanıcı rolünü al
$currentRole = 'user';
try {
    $pdo = getDatabase();
    $stmt = $pdo->prepare("SELECT role FROM admins WHERE id = ?");
    $stmt->execute([$_SESSION['admin_id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $currentRole = $result['role'] ?? 'user';
} catch (PDOException $e) {
    error_log("Get current role error: " . $e->getMessage());
}

ob_start();
?>

<div class="p-6 max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="/admin/users" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Geri Dön
        </a>
        <h1 class="text-2xl font-bold text-gray-900">➕ Yeni Kullanıcı Oluştur</h1>
        <p class="text-gray-600 mt-1">Sisteme yeni kullanıcı ekleyin</p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="/admin/users/create" class="space-y-6">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <!-- Kullanıcı Adı -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                    Kullanıcı Adı <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="örn: ahmet.yilmaz">
                <p class="mt-1 text-sm text-gray-500">Benzersiz bir kullanıcı adı girin (boşluk yok)</p>
            </div>

            <!-- E-posta -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    E-posta <span class="text-red-500">*</span>
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="ornek@email.com">
            </div>

            <!-- Şifre -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Şifre <span class="text-red-500">*</span>
                </label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required 
                       minlength="6"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="En az 6 karakter">
                <p class="mt-1 text-sm text-gray-500">Minimum 6 karakter</p>
            </div>

            <!-- Rol Seçimi -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                    Kullanıcı Rolü <span class="text-red-500">*</span>
                </label>
                <select id="role" 
                        name="role" 
                        required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <?php if ($currentRole === 'super_admin'): ?>
                        <option value="user" selected>👤 User - Sınırlı Erişim</option>
                        <option value="admin">🔑 Admin - Kullanıcı Yönetimi</option>
                        <option value="super_admin">👑 Super Admin - Tam Yetki</option>
                    <?php else: ?>
                        <option value="user" selected>👤 User - Sınırlı Erişim</option>
                    <?php endif; ?>
                </select>
                
                <!-- Rol Açıklamaları -->
                <div class="mt-3 space-y-2">
                    <?php if ($currentRole === 'super_admin'): ?>
                        <div class="bg-purple-50 border border-purple-200 rounded p-3 text-sm">
                            <p class="font-semibold text-purple-900">👑 Super Admin:</p>
                            <p class="text-purple-700">• Tam sistem yetkisi</p>
                            <p class="text-purple-700">• Admin ve User kullanıcısı oluşturabilir</p>
                            <p class="text-purple-700">• Tüm özelliklere erişim</p>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded p-3 text-sm">
                            <p class="font-semibold text-blue-900">🔑 Admin:</p>
                            <p class="text-blue-700">• User kullanıcısı oluşturabilir</p>
                            <p class="text-blue-700">• Lead ve Provider yönetimi</p>
                            <p class="text-blue-700">• Raporlama yetkisi</p>
                        </div>
                    <?php endif; ?>
                    <div class="bg-gray-50 border border-gray-200 rounded p-3 text-sm">
                        <p class="font-semibold text-gray-900">👤 User:</p>
                        <p class="text-gray-700">• Sınırlı erişim</p>
                        <p class="text-gray-700">• Lead görüntüleme</p>
                        <p class="text-gray-700">• Temel raporlar</p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <button type="submit" 
                        class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                    ✓ Kullanıcı Oluştur
                </button>
                <a href="/admin/users" 
                   class="flex-1 bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition font-medium text-center">
                    ✗ İptal
                </a>
            </div>
        </form>
    </div>

    <!-- Bilgilendirme -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="font-semibold text-blue-900 mb-2">💡 Önemli Bilgiler</h3>
        <ul class="text-sm text-blue-700 space-y-1">
            <li>• Kullanıcı adı benzersiz olmalıdır</li>
            <li>• E-posta adresi geçerli olmalıdır</li>
            <li>• Şifre en az 6 karakter içermelidir</li>
            <?php if ($currentRole === 'admin'): ?>
                <li>• Admin kullanıcıları sadece "User" rolü atayabilir</li>
            <?php endif; ?>
            <li>• Kullanıcı oluşturulduktan sonra aktif olacaktır</li>
        </ul>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>

