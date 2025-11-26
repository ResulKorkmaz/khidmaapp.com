<?php
/**
 * Kullanıcı Yönetimi - Liste
 * Rol bazlı görünürlük
 */
$pageTitle = 'Kullanıcı Yönetimi';
$currentPage = 'users';
ob_start();

$currentRole = $currentRole ?? 'user';
$superAdminUsername = $superAdminUsername ?? 'rslkrkmz';
?>

<div class="container mx-auto px-4 py-4">
    <!-- Header -->
    <div class="bg-blue-600 rounded-xl p-4 mb-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="text-2xl">👥</span>
                <div>
                    <h1 class="text-lg font-bold text-white">Kullanıcı Yönetimi</h1>
                    <p class="text-blue-200 text-xs">
                        <?php if ($currentRole === 'super_admin'): ?>
                            Tüm kullanıcıları yönetin
                        <?php elseif ($currentRole === 'admin'): ?>
                            User kullanıcılarını yönetin
                        <?php else: ?>
                            Kullanıcıları görüntüleyin
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            <?php if ($currentRole === 'super_admin' || $currentRole === 'admin'): ?>
            <a href="/admin/users/create" class="bg-white text-blue-600 hover:bg-blue-50 font-semibold px-4 py-2 rounded-lg text-sm flex items-center gap-2 transition-colors">
                <span>➕</span>
                <span>Yeni Kullanıcı</span>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <span>✅</span>
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <span>❌</span>
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Stats -->
    <div class="bg-white rounded-lg border border-gray-200 p-3 mb-4 flex items-center justify-between">
        <?php if ($currentRole === 'super_admin'): ?>
        <div class="flex items-center gap-2">
            <span class="text-lg font-bold text-purple-600"><?= count(array_filter($users, fn($u) => $u['role'] === 'super_admin')) ?></span>
            <span class="text-xs text-gray-500">Super Admin</span>
        </div>
        <div class="w-px h-6 bg-gray-200"></div>
        <div class="flex items-center gap-2">
            <span class="text-lg font-bold text-blue-600"><?= count(array_filter($users, fn($u) => $u['role'] === 'admin')) ?></span>
            <span class="text-xs text-gray-500">Admin</span>
        </div>
        <div class="w-px h-6 bg-gray-200"></div>
        <?php endif; ?>
        <div class="flex items-center gap-2">
            <span class="text-lg font-bold text-gray-600"><?= count(array_filter($users, fn($u) => $u['role'] === 'user')) ?></span>
            <span class="text-xs text-gray-500">User</span>
        </div>
        <div class="w-px h-6 bg-gray-200"></div>
        <div class="flex items-center gap-2">
            <span class="text-lg font-bold text-green-600"><?= count(array_filter($users, fn($u) => $u['is_active'])) ?></span>
            <span class="text-xs text-gray-500">Aktif</span>
        </div>
        <div class="w-px h-6 bg-gray-200"></div>
        <div class="flex items-center gap-2">
            <span class="text-lg font-bold text-gray-900"><?= count($users) ?></span>
            <span class="text-xs text-gray-500">Toplam</span>
        </div>
    </div>

    <!-- User List -->
    <?php if (empty($users)): ?>
        <div class="bg-white rounded-lg border border-gray-100 p-8 text-center">
            <span class="text-4xl mb-2 block">📭</span>
            <p class="text-gray-500 text-sm">Kullanıcı bulunamadı</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg border border-gray-100 overflow-hidden">
            <div class="divide-y divide-gray-100">
                <?php foreach ($users as $user): ?>
                    <?php
                    $roleColors = [
                        'super_admin' => 'bg-purple-100 text-purple-700',
                        'admin' => 'bg-blue-100 text-blue-700',
                        'user' => 'bg-gray-100 text-gray-700'
                    ];
                    $roleNames = [
                        'super_admin' => '👑 Super Admin',
                        'admin' => '🔑 Admin',
                        'user' => '👤 User'
                    ];
                    $roleColor = $roleColors[$user['role']] ?? 'bg-gray-100 text-gray-700';
                    $roleName = $roleNames[$user['role']] ?? $user['role'];
                    $isSuperAdmin = $user['username'] === $superAdminUsername;
                    $canEdit = ($currentRole === 'super_admin') || 
                               ($currentRole === 'admin' && $user['role'] === 'user') ||
                               ($user['id'] == $_SESSION['admin_id']);
                    $canDelete = ($currentRole === 'super_admin' && !$isSuperAdmin && $user['id'] != $_SESSION['admin_id']) ||
                                 ($currentRole === 'admin' && $user['role'] === 'user' && $user['id'] != $_SESSION['admin_id']);
                    ?>
                    <div class="p-3 hover:bg-gray-50 transition-colors" id="user-row-<?= $user['id'] ?>">
                        <div class="flex items-center gap-3">
                            <!-- Avatar -->
                            <div class="w-10 h-10 <?= $user['role'] === 'super_admin' ? 'bg-purple-500' : ($user['role'] === 'admin' ? 'bg-blue-500' : 'bg-gray-400') ?> rounded-lg flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                <?= strtoupper(substr($user['username'], 0, 2)) ?>
                            </div>
                            
                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="font-semibold text-gray-900 text-sm"><?= htmlspecialchars($user['username']) ?></h3>
                                    <span class="px-1.5 py-0.5 rounded text-xs font-medium <?= $roleColor ?>">
                                        <?= $roleName ?>
                                    </span>
                                    <span class="px-1.5 py-0.5 rounded text-xs font-medium <?= $user['is_active'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                        <?= $user['is_active'] ? 'Aktif' : 'Pasif' ?>
                                    </span>
                                    <?php if ($isSuperAdmin): ?>
                                        <span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded text-xs font-medium">🔒 Korumalı</span>
                                    <?php endif; ?>
                                    <?php if ($user['id'] == $_SESSION['admin_id']): ?>
                                        <span class="px-1.5 py-0.5 bg-indigo-100 text-indigo-700 rounded text-xs font-medium">Ben</span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center gap-3 text-xs text-gray-500 mt-0.5">
                                    <span><?= htmlspecialchars($user['email']) ?></span>
                                    <span>•</span>
                                    <span>Son giriş: <?= $user['last_login'] ? date('d.m.Y H:i', strtotime($user['last_login'])) : 'Hiç' ?></span>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex items-center gap-1 flex-shrink-0">
                                <?php if ($canEdit): ?>
                                <a href="/admin/users/edit?id=<?= $user['id'] ?>" 
                                   class="p-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors" title="Düzenle">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <?php endif; ?>
                                
                                <?php if ($canEdit && !$isSuperAdmin && $user['id'] != $_SESSION['admin_id']): ?>
                                <button onclick="toggleUserStatus(<?= $user['id'] ?>)" 
                                        class="p-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-lg transition-colors" title="Durum Değiştir">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>
                                <?php endif; ?>
                                
                                <?php if ($canDelete): ?>
                                <button onclick="deleteUser(<?= $user['id'] ?>, '<?= htmlspecialchars($user['username'], ENT_QUOTES) ?>')" 
                                        class="p-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors" title="Sil">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
const csrfToken = '<?= $_SESSION['csrf_token'] ?? '' ?>';

function toggleUserStatus(userId) {
    if (!confirm('Kullanıcı durumunu değiştirmek istediğinize emin misiniz?')) return;
    
    fetch('/admin/users/toggle-status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'user_id=' + userId + '&csrf_token=' + encodeURIComponent(csrfToken)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
        else alert(data.message || 'İşlem başarısız');
    })
    .catch(e => alert('Bir hata oluştu'));
}

function deleteUser(userId, username) {
    if (!confirm(`"${username}" kullanıcısını silmek istediğinize emin misiniz?`)) return;
    
    fetch('/admin/users/delete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'user_id=' + userId + '&csrf_token=' + encodeURIComponent(csrfToken)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const row = document.getElementById('user-row-' + userId);
            row.style.opacity = '0';
            setTimeout(() => row.remove(), 300);
        } else alert(data.message || 'Silme işlemi başarısız');
    })
    .catch(e => alert('Bir hata oluştu'));
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
