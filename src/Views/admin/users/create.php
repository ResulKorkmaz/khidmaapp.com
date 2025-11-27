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
                <div class="relative">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required 
                           minlength="8"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-10"
                           placeholder="Güçlü şifre girin">
                    <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <svg id="password-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-sm font-medium text-gray-700 mb-2">Şifre gereksinimleri:</p>
                    <ul class="text-xs space-y-1">
                        <li id="rule-length" class="flex items-center gap-2 text-gray-500">
                            <span class="rule-icon">○</span> En az 8 karakter
                        </li>
                        <li id="rule-upper" class="flex items-center gap-2 text-gray-500">
                            <span class="rule-icon">○</span> En az 1 büyük harf (A-Z)
                        </li>
                        <li id="rule-lower" class="flex items-center gap-2 text-gray-500">
                            <span class="rule-icon">○</span> En az 1 küçük harf (a-z)
                        </li>
                        <li id="rule-number" class="flex items-center gap-2 text-gray-500">
                            <span class="rule-icon">○</span> En az 1 rakam (0-9)
                        </li>
                        <li id="rule-special" class="flex items-center gap-2 text-gray-500">
                            <span class="rule-icon">○</span> En az 1 özel karakter (!@#$%...)
                        </li>
                        <li id="rule-repeat" class="flex items-center gap-2 text-gray-500">
                            <span class="rule-icon">○</span> Art arda 3 aynı karakter yok (111, aaa)
                        </li>
                        <li id="rule-sequence" class="flex items-center gap-2 text-gray-500">
                            <span class="rule-icon">○</span> Art arda 3 ardışık sayı/harf yok (123, abc)
                        </li>
                    </ul>
                </div>
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
            <li>• Şifre güçlü olmalı ve tüm gereksinimleri karşılamalıdır</li>
            <?php if ($currentRole === 'admin'): ?>
                <li>• Admin kullanıcıları sadece "User" rolü atayabilir</li>
            <?php endif; ?>
            <li>• Kullanıcı oluşturulduktan sonra aktif olacaktır</li>
        </ul>
    </div>
</div>

<script>
// Şifre göster/gizle
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const eye = document.getElementById(inputId + '-eye');
    if (input.type === 'password') {
        input.type = 'text';
        eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
    } else {
        input.type = 'password';
        eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
    }
}

// Şifre validasyonu
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthDiv = document.getElementById('password-strength');
    
    if (password.length > 0) {
        strengthDiv.classList.remove('hidden');
    } else {
        strengthDiv.classList.add('hidden');
        return;
    }
    
    // Kuralları kontrol et
    const rules = {
        length: password.length >= 8,
        upper: /[A-Z]/.test(password),
        lower: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~`]/.test(password),
        repeat: !/(.)\\1{2,}/.test(password),
        sequence: !/(012|123|234|345|456|567|678|789|890|987|876|765|654|543|432|321|210|abc|bcd|cde|def|efg|fgh|ghi|hij|ijk|jkl|klm|lmn|mno|nop|opq|pqr|qrs|rst|stu|tuv|uvw|vwx|wxy|xyz|cba|dcb|edc|fed|gfe|hgf|ihg|jih|kji|lkj|mlk|nml|onm|pon|qpo|rqp|srq|tsr|uts|vut|wvu|xwv|yxw|zyx)/i.test(password)
    };
    
    // Kural göstergelerini güncelle
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
    
    // Güç hesapla
    const passedRules = Object.values(rules).filter(Boolean).length;
    const strength = Math.round((passedRules / 7) * 100);
    
    // Güç çubuklarını güncelle
    const bars = ['strength-bar-1', 'strength-bar-2', 'strength-bar-3', 'strength-bar-4'];
    const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500'];
    
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
    
    // Güç metnini güncelle
    const strengthText = document.getElementById('strength-text');
    if (strength < 50) {
        strengthText.textContent = '🔴 Zayıf şifre';
        strengthText.className = 'text-xs text-red-500';
    } else if (strength < 75) {
        strengthText.textContent = '🟠 Orta güçte şifre';
        strengthText.className = 'text-xs text-orange-500';
    } else if (strength < 100) {
        strengthText.textContent = '🟡 İyi şifre';
        strengthText.className = 'text-xs text-yellow-600';
    } else {
        strengthText.textContent = '🟢 Güçlü şifre!';
        strengthText.className = 'text-xs text-green-600';
    }
});

// Form submit kontrolü
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    
    // Tüm kuralları kontrol et
    const errors = [];
    if (password.length < 8) errors.push('En az 8 karakter');
    if (!/[A-Z]/.test(password)) errors.push('En az 1 büyük harf');
    if (!/[a-z]/.test(password)) errors.push('En az 1 küçük harf');
    if (!/[0-9]/.test(password)) errors.push('En az 1 rakam');
    if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~`]/.test(password)) errors.push('En az 1 özel karakter');
    if (/(.)\\1{2,}/.test(password)) errors.push('Art arda 3 aynı karakter kullanılamaz');
    if (/(012|123|234|345|456|567|678|789|890|987|876|765|654|543|432|321|210|abc|bcd|cde|def|efg|fgh|ghi|hij|ijk|jkl|klm|lmn|mno|nop|opq|pqr|qrs|rst|stu|tuv|uvw|vwx|wxy|xyz|cba|dcb|edc|fed|gfe|hgf|ihg|jih|kji|lkj|mlk|nml|onm|pon|qpo|rqp|srq|tsr|uts|vut|wvu|xwv|yxw|zyx)/i.test(password)) errors.push('Art arda 3 ardışık sayı/harf kullanılamaz');
    
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

