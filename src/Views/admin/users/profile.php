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
                <div class="relative">
                    <input type="password" 
                           id="new_password" 
                           name="new_password" 
                           minlength="8"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-10"
                           placeholder="Güçlü şifre girin">
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <svg id="password-eye" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Şifre Gücü Göstergesi -->
                <div id="password-strength" class="mt-2 hidden">
                    <div class="flex gap-1 mb-1">
                        <div id="strength-bar-1" class="h-1 flex-1 rounded bg-gray-200"></div>
                        <div id="strength-bar-2" class="h-1 flex-1 rounded bg-gray-200"></div>
                        <div id="strength-bar-3" class="h-1 flex-1 rounded bg-gray-200"></div>
                        <div id="strength-bar-4" class="h-1 flex-1 rounded bg-gray-200"></div>
                    </div>
                    <p id="strength-text" class="text-xs text-gray-500"></p>
                </div>
                
                <!-- Şifre Kuralları -->
                <div id="password-rules" class="mt-2 p-2 bg-gray-50 rounded-lg border border-gray-200 hidden">
                    <p class="text-xs font-medium text-gray-700 mb-1">Şifre gereksinimleri:</p>
                    <ul class="text-xs space-y-0.5">
                        <li id="rule-length" class="flex items-center gap-1 text-gray-500"><span class="rule-icon">○</span> En az 8 karakter</li>
                        <li id="rule-upper" class="flex items-center gap-1 text-gray-500"><span class="rule-icon">○</span> En az 1 büyük harf</li>
                        <li id="rule-lower" class="flex items-center gap-1 text-gray-500"><span class="rule-icon">○</span> En az 1 küçük harf</li>
                        <li id="rule-number" class="flex items-center gap-1 text-gray-500"><span class="rule-icon">○</span> En az 1 rakam</li>
                        <li id="rule-special" class="flex items-center gap-1 text-gray-500"><span class="rule-icon">○</span> En az 1 özel karakter</li>
                        <li id="rule-repeat" class="flex items-center gap-1 text-gray-500"><span class="rule-icon">○</span> Art arda 3 aynı karakter yok</li>
                        <li id="rule-sequence" class="flex items-center gap-1 text-gray-500"><span class="rule-icon">○</span> Art arda 3 ardışık sayı/harf yok</li>
                    </ul>
                </div>
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

<script>
function togglePassword() {
    const input = document.getElementById('new_password');
    const eye = document.getElementById('password-eye');
    if (input.type === 'password') {
        input.type = 'text';
        eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
    } else {
        input.type = 'password';
        eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
    }
}

document.getElementById('new_password').addEventListener('input', function() {
    const password = this.value;
    const strengthDiv = document.getElementById('password-strength');
    const rulesDiv = document.getElementById('password-rules');
    
    if (password.length > 0) {
        strengthDiv.classList.remove('hidden');
        rulesDiv.classList.remove('hidden');
    } else {
        strengthDiv.classList.add('hidden');
        rulesDiv.classList.add('hidden');
        return;
    }
    
    const rules = {
        length: password.length >= 8,
        upper: /[A-Z]/.test(password),
        lower: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~`]/.test(password),
        repeat: !/(.)\\1{2,}/.test(password),
        sequence: !/(012|123|234|345|456|567|678|789|890|987|876|765|654|543|432|321|210|abc|bcd|cde|def|efg|fgh|ghi|hij|ijk|jkl|klm|lmn|mno|nop|opq|pqr|qrs|rst|stu|tuv|uvw|vwx|wxy|xyz|cba|dcb|edc|fed|gfe|hgf|ihg|jih|kji|lkj|mlk|nml|onm|pon|qpo|rqp|srq|tsr|uts|vut|wvu|xwv|yxw|zyx)/i.test(password)
    };
    
    Object.keys(rules).forEach(rule => {
        const el = document.getElementById('rule-' + rule);
        const icon = el.querySelector('.rule-icon');
        if (rules[rule]) {
            el.classList.remove('text-gray-500', 'text-red-500');
            el.classList.add('text-green-600');
            icon.textContent = '✓';
        } else {
            el.classList.remove('text-gray-500', 'text-green-600');
            el.classList.add('text-red-500');
            icon.textContent = '✗';
        }
    });
    
    const passedRules = Object.values(rules).filter(Boolean).length;
    const strength = Math.round((passedRules / 7) * 100);
    
    const bars = ['strength-bar-1', 'strength-bar-2', 'strength-bar-3', 'strength-bar-4'];
    bars.forEach((bar, i) => {
        const el = document.getElementById(bar);
        el.classList.remove('bg-gray-200', 'bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500');
        if (strength >= (i + 1) * 25) {
            if (strength < 50) el.classList.add('bg-red-500');
            else if (strength < 75) el.classList.add('bg-orange-500');
            else if (strength < 100) el.classList.add('bg-yellow-500');
            else el.classList.add('bg-green-500');
        } else {
            el.classList.add('bg-gray-200');
        }
    });
    
    const strengthText = document.getElementById('strength-text');
    if (strength < 50) { strengthText.textContent = '🔴 Zayıf'; strengthText.className = 'text-xs text-red-500'; }
    else if (strength < 75) { strengthText.textContent = '🟠 Orta'; strengthText.className = 'text-xs text-orange-500'; }
    else if (strength < 100) { strengthText.textContent = '🟡 İyi'; strengthText.className = 'text-xs text-yellow-600'; }
    else { strengthText.textContent = '🟢 Güçlü!'; strengthText.className = 'text-xs text-green-600'; }
});

document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('new_password').value;
    if (password.length === 0) return;
    
    const errors = [];
    if (password.length < 8) errors.push('En az 8 karakter');
    if (!/[A-Z]/.test(password)) errors.push('En az 1 büyük harf');
    if (!/[a-z]/.test(password)) errors.push('En az 1 küçük harf');
    if (!/[0-9]/.test(password)) errors.push('En az 1 rakam');
    if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~`]/.test(password)) errors.push('En az 1 özel karakter');
    if (/(.)\\1{2,}/.test(password)) errors.push('Art arda 3 aynı karakter yok');
    if (/(012|123|234|345|456|567|678|789|890|987|876|765|654|543|432|321|210|abc|bcd|cde|def|efg|fgh|ghi|hij|ijk|jkl|klm|lmn|mno|nop|opq|pqr|qrs|rst|stu|tuv|uvw|vwx|wxy|xyz|cba|dcb|edc|fed|gfe|hgf|ihg|jih|kji|lkj|mlk|nml|onm|pon|qpo|rqp|srq|tsr|uts|vut|wvu|xwv|yxw|zyx)/i.test(password)) errors.push('Art arda 3 ardışık sayı/harf yok');
    
    if (errors.length > 0) {
        e.preventDefault();
        alert('Şifre gereksinimleri karşılanmıyor:\\n• ' + errors.join('\\n• '));
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>

