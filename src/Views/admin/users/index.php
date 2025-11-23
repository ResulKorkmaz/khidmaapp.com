<?php
/**
 * Kullanıcı Yönetimi - Liste
 * Sadece Super Admin erişebilir
 */
$pageTitle = 'Kullanıcı Yönetimi';
$currentPage = 'users';
ob_start();
?>

<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">👥 Kullanıcı Yönetimi</h1>
            <p class="text-gray-600 mt-1">Sistem kullanıcılarını yönetin</p>
        </div>
        <a href="/admin/users/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Yeni Kullanıcı
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Kullanıcı Tablosu -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kullanıcı</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-posta</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Son Giriş</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Henüz kullanıcı bulunmuyor
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr id="user-row-<?= $user['id'] ?>">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold">
                                            <?= strtoupper(substr($user['username'], 0, 2)) ?>
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($user['username']) ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            #<?= $user['id'] ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= htmlspecialchars($user['email']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $roleColors = [
                                    'super_admin' => 'bg-purple-100 text-purple-800',
                                    'admin' => 'bg-blue-100 text-blue-800',
                                    'user' => 'bg-gray-100 text-gray-800'
                                ];
                                $roleNames = [
                                    'super_admin' => '👑 Super Admin',
                                    'admin' => '🔑 Admin',
                                    'user' => '👤 User'
                                ];
                                $color = $roleColors[$user['role']] ?? 'bg-gray-100 text-gray-800';
                                $roleName = $roleNames[$user['role']] ?? $user['role'];
                                ?>
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $color ?>">
                                    <?= $roleName ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="status-badge px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $user['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= $user['is_active'] ? '✓ Aktif' : '✗ Pasif' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= $user['last_login'] ? date('d.m.Y H:i', strtotime($user['last_login'])) : 'Hiç giriş yapmadı' ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="toggleUserStatus(<?= $user['id'] ?>)" 
                                        class="text-blue-600 hover:text-blue-900 mr-3"
                                        title="Durumu Değiştir">
                                    🔄 Durum
                                </button>
                                <button onclick="deleteUser(<?= $user['id'] ?>, '<?= htmlspecialchars($user['username']) ?>')" 
                                        class="text-red-600 hover:text-red-900"
                                        title="Sil">
                                    🗑️ Sil
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- İstatistikler -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-purple-50 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-purple-600 font-medium">Super Admin</p>
                    <p class="text-2xl font-bold text-purple-900">
                        <?= count(array_filter($users, fn($u) => $u['role'] === 'super_admin')) ?>
                    </p>
                </div>
                <div class="text-3xl">👑</div>
            </div>
        </div>
        <div class="bg-blue-50 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-600 font-medium">Admin</p>
                    <p class="text-2xl font-bold text-blue-900">
                        <?= count(array_filter($users, fn($u) => $u['role'] === 'admin')) ?>
                    </p>
                </div>
                <div class="text-3xl">🔑</div>
            </div>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">User</p>
                    <p class="text-2xl font-bold text-gray-900">
                        <?= count(array_filter($users, fn($u) => $u['role'] === 'user')) ?>
                    </p>
                </div>
                <div class="text-3xl">👤</div>
            </div>
        </div>
    </div>
</div>

<script>
// CSRF token
const csrfToken = '<?= $_SESSION['csrf_token'] ?? '' ?>';

function toggleUserStatus(userId) {
    if (!confirm('Kullanıcı durumunu değiştirmek istediğinize emin misiniz?')) {
        return;
    }

    fetch('/admin/users/toggle-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'user_id=' + userId + '&csrf_token=' + encodeURIComponent(csrfToken)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'İşlem başarısız');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Bir hata oluştu');
    });
}

function deleteUser(userId, username) {
    if (!confirm(`"${username}" kullanıcısını silmek istediğinize emin misiniz? Bu işlem geri alınamaz.`)) {
        return;
    }

    fetch('/admin/users/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'user_id=' + userId + '&csrf_token=' + encodeURIComponent(csrfToken)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove row with animation
            const row = document.getElementById('user-row-' + userId);
            row.style.transition = 'opacity 0.3s';
            row.style.opacity = '0';
            setTimeout(() => {
                row.remove();
            }, 300);
        } else {
            alert(data.message || 'Silme işlemi başarısız');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Bir hata oluştu');
    });
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>