<?php
// Admin layout'u başlat - içeriği ob_start ile yakala
ob_start();

$serviceTypes = getServiceTypes();
$cities = getCities();
$serviceName = $serviceTypes[$provider['service_type']]['tr'] ?? $provider['service_type'];
$cityName = isset($provider['city']) && isset($cities[$provider['city']]) ? $cities[$provider['city']]['tr'] : ($provider['city'] ?? '-');

// Status badge classes
$statusClasses = [
    'active' => 'bg-green-100 text-green-800',
    'pending' => 'bg-yellow-100 text-yellow-800',
    'suspended' => 'bg-red-100 text-red-800',
    'rejected' => 'bg-gray-100 text-gray-800'
];
$statusLabels = [
    'active' => '✅ Aktif',
    'pending' => '⏳ Beklemede',
    'suspended' => '🚫 Askıda',
    'rejected' => '❌ Reddedilen'
];
?>

<!-- Back Button -->
<div class="mb-6">
    <a href="/admin/providers" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Ustalara Geri Dön
    </a>
</div>

<!-- Provider Header Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex items-start justify-between">
        <div class="flex items-start gap-4">
            <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-indigo-600 font-bold text-2xl">
                    <?= mb_substr($provider['name'], 0, 2, 'UTF-8') ?>
                </span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($provider['name']) ?></h1>
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <?= htmlspecialchars($provider['email']) ?>
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <?= htmlspecialchars($provider['phone']) ?>
                    </span>
                </div>
            </div>
        </div>
        
        <div>
            <span class="px-4 py-2 <?= $statusClasses[$provider['status']] ?> text-sm font-semibold rounded-full">
                <?= $statusLabels[$provider['status']] ?>
            </span>
        </div>
    </div>
</div>

<!-- Info Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Provider Info -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Usta Bilgileri
        </h2>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-semibold text-gray-500">ID</label>
                <p class="text-gray-900">#<?= str_pad($provider['id'], 4, '0', STR_PAD_LEFT) ?></p>
            </div>
            
            <div>
                <label class="text-sm font-semibold text-gray-500">Hizmet Türü</label>
                <p class="text-gray-900">
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                        <?= htmlspecialchars($serviceName) ?>
                    </span>
                </p>
            </div>
            
            <div>
                <label class="text-sm font-semibold text-gray-500">Şehir</label>
                <p class="text-gray-900"><?= htmlspecialchars($cityName) ?></p>
            </div>
            
            <div>
                <label class="text-sm font-semibold text-gray-500">Email Doğrulandı</label>
                <p class="text-gray-900">
                    <?= $provider['is_verified'] ? '✅ Evet' : '❌ Hayır' ?>
                </p>
            </div>
            
            <div>
                <label class="text-sm font-semibold text-gray-500">Puan</label>
                <p class="text-gray-900 flex items-center gap-1">
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <?= number_format($provider['rating'], 1) ?> (<?= $provider['total_jobs'] ?> iş)
                </p>
            </div>
            
            <div>
                <label class="text-sm font-semibold text-gray-500">Tecrübe</label>
                <p class="text-gray-900"><?= $provider['experience_years'] ? $provider['experience_years'] . ' yıl' : '-' ?></p>
            </div>
            
            <div class="col-span-2">
                <label class="text-sm font-semibold text-gray-500">Biyografi</label>
                <p class="text-gray-900"><?= htmlspecialchars($provider['bio'] ?? 'Henüz eklenmemiş') ?></p>
            </div>
        </div>
    </div>
    
    <!-- Statistics -->
    <div class="space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Tarihler</h2>
            
            <div class="space-y-3 text-sm">
                <div>
                    <label class="font-semibold text-gray-500">Kayıt Tarihi</label>
                    <p class="text-gray-900"><?= date('d.m.Y H:i', strtotime($provider['created_at'])) ?></p>
                </div>
                
                <div>
                    <label class="font-semibold text-gray-500">Son Güncelleme</label>
                    <p class="text-gray-900"><?= date('d.m.Y H:i', strtotime($provider['updated_at'])) ?></p>
                </div>
                
                <?php if ($provider['last_login_at']): ?>
                <div>
                    <label class="font-semibold text-gray-500">Son Giriş</label>
                    <p class="text-gray-900"><?= date('d.m.Y H:i', strtotime($provider['last_login_at'])) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
        </svg>
        Durum İşlemleri
    </h2>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <?php if ($provider['status'] !== 'active'): ?>
        <!-- Onayla Button -->
        <button onclick="changeProviderStatus(<?= $provider['id'] ?>, 'active', '<?= htmlspecialchars($provider['name']) ?>')"
                class="flex items-center justify-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            ✅ Onayla
        </button>
        <?php endif; ?>
        
        <?php if ($provider['status'] !== 'pending'): ?>
        <!-- Bekletme Button -->
        <button onclick="changeProviderStatus(<?= $provider['id'] ?>, 'pending', '<?= htmlspecialchars($provider['name']) ?>')"
                class="flex items-center justify-center gap-2 px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            ⏳ Beklet
        </button>
        <?php endif; ?>
        
        <?php if ($provider['status'] !== 'suspended'): ?>
        <!-- Askıya Al Button -->
        <button onclick="changeProviderStatus(<?= $provider['id'] ?>, 'suspended', '<?= htmlspecialchars($provider['name']) ?>')"
                class="flex items-center justify-center gap-2 px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
            🚫 Askıya Al
        </button>
        <?php endif; ?>
        
        <?php if ($provider['status'] !== 'rejected'): ?>
        <!-- Reddet Button -->
        <button onclick="changeProviderStatus(<?= $provider['id'] ?>, 'rejected', '<?= htmlspecialchars($provider['name']) ?>')"
                class="flex items-center justify-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            ❌ Reddet
        </button>
        <?php endif; ?>
    </div>
    
    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-xl text-sm text-blue-800">
        <strong>💡 İpucu:</strong> Durum değişiklikleri anında etkilidir. Usta bir sonraki girişinde yeni durumunu görecektir.
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-3" style="max-width: 400px;"></div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" style="backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all" id="confirmModalContent">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div id="modalIcon" class="w-12 h-12 rounded-full flex items-center justify-center">
                    <!-- Icon will be inserted here -->
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900" id="modalTitle">Durum Değiştir</h3>
                    <p class="text-sm text-gray-500" id="modalSubtitle"></p>
                </div>
            </div>
        </div>
        
        <!-- Modal Body -->
        <div class="px-6 py-4">
            <p class="text-gray-700 leading-relaxed" id="modalDescription"></p>
        </div>
        
        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex gap-3">
            <button onclick="closeConfirmModal()" 
                    class="flex-1 px-4 py-2.5 bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 rounded-xl font-semibold transition-colors">
                İptal
            </button>
            <button id="confirmButton" onclick="executeStatusChange()" 
                    class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-colors">
                Onayla
            </button>
        </div>
    </div>
</div>

<script>
const csrfToken = '<?= generateCsrfToken() ?>';
let pendingStatusChange = null;

// Toast Notification System
function showToast(message, type = 'success', duration = 4000) {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    
    const icons = {
        success: '<svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
        error: '<svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
        warning: '<svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>',
        info: '<svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>'
    };
    
    const colors = {
        success: 'bg-green-50 border-green-500',
        error: 'bg-red-50 border-red-500',
        warning: 'bg-yellow-50 border-yellow-500',
        info: 'bg-blue-50 border-blue-500'
    };
    
    toast.className = `${colors[type]} border-l-4 rounded-lg shadow-lg p-4 flex items-start gap-3 transform transition-all duration-300 ease-out`;
    toast.style.animation = 'slideInRight 0.3s ease-out';
    toast.innerHTML = `
        <div class="flex-shrink-0">${icons[type]}</div>
        <div class="flex-1">
            <p class="text-sm font-semibold text-gray-900">${message}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease-in';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// Modal Functions
function showConfirmModal(config) {
    const modal = document.getElementById('confirmModal');
    const modalIcon = document.getElementById('modalIcon');
    const modalTitle = document.getElementById('modalTitle');
    const modalSubtitle = document.getElementById('modalSubtitle');
    const modalDescription = document.getElementById('modalDescription');
    const confirmButton = document.getElementById('confirmButton');
    
    modalIcon.innerHTML = config.icon;
    modalIcon.className = `w-12 h-12 rounded-full flex items-center justify-center ${config.iconBg}`;
    modalTitle.textContent = config.title;
    modalSubtitle.textContent = config.subtitle;
    modalDescription.innerHTML = config.description;
    confirmButton.className = `flex-1 px-4 py-2.5 ${config.buttonClass} rounded-xl font-semibold transition-colors`;
    confirmButton.textContent = config.buttonText;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(() => {
        document.getElementById('confirmModalContent').style.transform = 'scale(1)';
    }, 10);
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    const modalContent = document.getElementById('confirmModalContent');
    
    modalContent.style.transform = 'scale(0.95)';
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        pendingStatusChange = null;
    }, 200);
}

function changeProviderStatus(providerId, newStatus, providerName) {
    const statusConfigs = {
        'active': {
            icon: '<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            iconBg: 'bg-green-100',
            title: '✅ Ustayı Onayla',
            subtitle: providerName,
            description: '<strong>Usta aktif duruma geçecek:</strong><br>• Lead paketleri satın alabilir<br>• Sisteme tam erişim sağlar<br>• Dashboard\'ını kullanabilir',
            buttonClass: 'bg-green-600 hover:bg-green-700 text-white',
            buttonText: 'Onayla ve Aktif Et',
            successMessage: 'Usta başarıyla onaylandı ve aktif edildi!'
        },
        'pending': {
            icon: '<svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            iconBg: 'bg-yellow-100',
            title: '⏳ Ustayı Beklet',
            subtitle: providerName,
            description: '<strong>Usta bekleme durumuna alınacak:</strong><br>• Lead satın alamaz<br>• Onay sürecinde bekler<br>• WhatsApp kanal kontrolü yapılabilir',
            buttonClass: 'bg-yellow-600 hover:bg-yellow-700 text-white',
            buttonText: 'Bekletme Durumuna Al',
            successMessage: 'Usta bekleme durumuna alındı.'
        },
        'suspended': {
            icon: '<svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>',
            iconBg: 'bg-orange-100',
            title: '🚫 Ustayı Askıya Al',
            subtitle: providerName,
            description: '<strong>Usta askıya alınacak:</strong><br>• Sisteme giremez<br>• Tüm aktivitesi durdurulur<br>• Geçici bir önlem olarak uygulanır',
            buttonClass: 'bg-orange-600 hover:bg-orange-700 text-white',
            buttonText: 'Askıya Al',
            successMessage: 'Usta askıya alındı.'
        },
        'rejected': {
            icon: '<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            iconBg: 'bg-red-100',
            title: '❌ Ustayı Reddet',
            subtitle: providerName,
            description: '<strong>Usta tamamen reddedilecek:</strong><br>• Kalıcı olarak engellenecek<br>• Sisteme hiç giremez<br>• Bu işlem geri alınamaz!',
            buttonClass: 'bg-red-600 hover:bg-red-700 text-white',
            buttonText: 'Reddet ve Engelle',
            successMessage: 'Usta reddedildi ve engellendi.'
        }
    };
    
    const config = statusConfigs[newStatus];
    pendingStatusChange = { providerId, newStatus, providerName, config };
    showConfirmModal(config);
}

function executeStatusChange() {
    if (!pendingStatusChange) return;
    
    const { providerId, newStatus, providerName, config } = pendingStatusChange;
    
    closeConfirmModal();
    showToast('İşlem yapılıyor...', 'info', 2000);
    
    fetch('/admin/providers/change-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            provider_id: providerId,
            new_status: newStatus,
            csrf_token: csrfToken
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(config.successMessage, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.error || 'Durum güncellenemedi', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Beklenmeyen bir hata oluştu', 'error');
    });
}

// Animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    #confirmModalContent {
        transform: scale(0.95);
        transition: transform 0.2s ease-out;
    }
`;
document.head.appendChild(style);
</script>

<?php
// İçeriği al
$content = ob_get_clean();

// Layout'u yükle
require __DIR__ . '/layout.php';
?>


ob_start();

$serviceTypes = getServiceTypes();
$cities = getCities();
$serviceName = $serviceTypes[$provider['service_type']]['tr'] ?? $provider['service_type'];
$cityName = isset($provider['city']) && isset($cities[$provider['city']]) ? $cities[$provider['city']]['tr'] : ($provider['city'] ?? '-');

// Status badge classes
$statusClasses = [
    'active' => 'bg-green-100 text-green-800',
    'pending' => 'bg-yellow-100 text-yellow-800',
    'suspended' => 'bg-red-100 text-red-800',
    'rejected' => 'bg-gray-100 text-gray-800'
];
$statusLabels = [
    'active' => '✅ Aktif',
    'pending' => '⏳ Beklemede',
    'suspended' => '🚫 Askıda',
    'rejected' => '❌ Reddedilen'
];
?>

<!-- Back Button -->
<div class="mb-6">
    <a href="/admin/providers" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Ustalara Geri Dön
    </a>
</div>

<!-- Provider Header Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex items-start justify-between">
        <div class="flex items-start gap-4">
            <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-indigo-600 font-bold text-2xl">
                    <?= mb_substr($provider['name'], 0, 2, 'UTF-8') ?>
                </span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($provider['name']) ?></h1>
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <?= htmlspecialchars($provider['email']) ?>
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <?= htmlspecialchars($provider['phone']) ?>
                    </span>
                </div>
            </div>
        </div>
        
        <div>
            <span class="px-4 py-2 <?= $statusClasses[$provider['status']] ?> text-sm font-semibold rounded-full">
                <?= $statusLabels[$provider['status']] ?>
            </span>
        </div>
    </div>
</div>

<!-- Info Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Provider Info -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Usta Bilgileri
        </h2>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-semibold text-gray-500">ID</label>
                <p class="text-gray-900">#<?= str_pad($provider['id'], 4, '0', STR_PAD_LEFT) ?></p>
            </div>
            
            <div>
                <label class="text-sm font-semibold text-gray-500">Hizmet Türü</label>
                <p class="text-gray-900">
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                        <?= htmlspecialchars($serviceName) ?>
                    </span>
                </p>
            </div>
            
            <div>
                <label class="text-sm font-semibold text-gray-500">Şehir</label>
                <p class="text-gray-900"><?= htmlspecialchars($cityName) ?></p>
            </div>
            
            <div>
                <label class="text-sm font-semibold text-gray-500">Email Doğrulandı</label>
                <p class="text-gray-900">
                    <?= $provider['is_verified'] ? '✅ Evet' : '❌ Hayır' ?>
                </p>
            </div>
            
            <div>
                <label class="text-sm font-semibold text-gray-500">Puan</label>
                <p class="text-gray-900 flex items-center gap-1">
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <?= number_format($provider['rating'], 1) ?> (<?= $provider['total_jobs'] ?> iş)
                </p>
            </div>
            
            <div>
                <label class="text-sm font-semibold text-gray-500">Tecrübe</label>
                <p class="text-gray-900"><?= $provider['experience_years'] ? $provider['experience_years'] . ' yıl' : '-' ?></p>
            </div>
            
            <div class="col-span-2">
                <label class="text-sm font-semibold text-gray-500">Biyografi</label>
                <p class="text-gray-900"><?= htmlspecialchars($provider['bio'] ?? 'Henüz eklenmemiş') ?></p>
            </div>
        </div>
    </div>
    
    <!-- Statistics -->
    <div class="space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Tarihler</h2>
            
            <div class="space-y-3 text-sm">
                <div>
                    <label class="font-semibold text-gray-500">Kayıt Tarihi</label>
                    <p class="text-gray-900"><?= date('d.m.Y H:i', strtotime($provider['created_at'])) ?></p>
                </div>
                
                <div>
                    <label class="font-semibold text-gray-500">Son Güncelleme</label>
                    <p class="text-gray-900"><?= date('d.m.Y H:i', strtotime($provider['updated_at'])) ?></p>
                </div>
                
                <?php if ($provider['last_login_at']): ?>
                <div>
                    <label class="font-semibold text-gray-500">Son Giriş</label>
                    <p class="text-gray-900"><?= date('d.m.Y H:i', strtotime($provider['last_login_at'])) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
        </svg>
        Durum İşlemleri
    </h2>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <?php if ($provider['status'] !== 'active'): ?>
        <!-- Onayla Button -->
        <button onclick="changeProviderStatus(<?= $provider['id'] ?>, 'active', '<?= htmlspecialchars($provider['name']) ?>')"
                class="flex items-center justify-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            ✅ Onayla
        </button>
        <?php endif; ?>
        
        <?php if ($provider['status'] !== 'pending'): ?>
        <!-- Bekletme Button -->
        <button onclick="changeProviderStatus(<?= $provider['id'] ?>, 'pending', '<?= htmlspecialchars($provider['name']) ?>')"
                class="flex items-center justify-center gap-2 px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            ⏳ Beklet
        </button>
        <?php endif; ?>
        
        <?php if ($provider['status'] !== 'suspended'): ?>
        <!-- Askıya Al Button -->
        <button onclick="changeProviderStatus(<?= $provider['id'] ?>, 'suspended', '<?= htmlspecialchars($provider['name']) ?>')"
                class="flex items-center justify-center gap-2 px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
            🚫 Askıya Al
        </button>
        <?php endif; ?>
        
        <?php if ($provider['status'] !== 'rejected'): ?>
        <!-- Reddet Button -->
        <button onclick="changeProviderStatus(<?= $provider['id'] ?>, 'rejected', '<?= htmlspecialchars($provider['name']) ?>')"
                class="flex items-center justify-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            ❌ Reddet
        </button>
        <?php endif; ?>
    </div>
    
    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-xl text-sm text-blue-800">
        <strong>💡 İpucu:</strong> Durum değişiklikleri anında etkilidir. Usta bir sonraki girişinde yeni durumunu görecektir.
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-3" style="max-width: 400px;"></div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" style="backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all" id="confirmModalContent">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div id="modalIcon" class="w-12 h-12 rounded-full flex items-center justify-center">
                    <!-- Icon will be inserted here -->
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900" id="modalTitle">Durum Değiştir</h3>
                    <p class="text-sm text-gray-500" id="modalSubtitle"></p>
                </div>
            </div>
        </div>
        
        <!-- Modal Body -->
        <div class="px-6 py-4">
            <p class="text-gray-700 leading-relaxed" id="modalDescription"></p>
        </div>
        
        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex gap-3">
            <button onclick="closeConfirmModal()" 
                    class="flex-1 px-4 py-2.5 bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 rounded-xl font-semibold transition-colors">
                İptal
            </button>
            <button id="confirmButton" onclick="executeStatusChange()" 
                    class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-colors">
                Onayla
            </button>
        </div>
    </div>
</div>

<script>
const csrfToken = '<?= generateCsrfToken() ?>';
let pendingStatusChange = null;

// Toast Notification System
function showToast(message, type = 'success', duration = 4000) {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    
    const icons = {
        success: '<svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
        error: '<svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
        warning: '<svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>',
        info: '<svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>'
    };
    
    const colors = {
        success: 'bg-green-50 border-green-500',
        error: 'bg-red-50 border-red-500',
        warning: 'bg-yellow-50 border-yellow-500',
        info: 'bg-blue-50 border-blue-500'
    };
    
    toast.className = `${colors[type]} border-l-4 rounded-lg shadow-lg p-4 flex items-start gap-3 transform transition-all duration-300 ease-out`;
    toast.style.animation = 'slideInRight 0.3s ease-out';
    toast.innerHTML = `
        <div class="flex-shrink-0">${icons[type]}</div>
        <div class="flex-1">
            <p class="text-sm font-semibold text-gray-900">${message}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease-in';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// Modal Functions
function showConfirmModal(config) {
    const modal = document.getElementById('confirmModal');
    const modalIcon = document.getElementById('modalIcon');
    const modalTitle = document.getElementById('modalTitle');
    const modalSubtitle = document.getElementById('modalSubtitle');
    const modalDescription = document.getElementById('modalDescription');
    const confirmButton = document.getElementById('confirmButton');
    
    modalIcon.innerHTML = config.icon;
    modalIcon.className = `w-12 h-12 rounded-full flex items-center justify-center ${config.iconBg}`;
    modalTitle.textContent = config.title;
    modalSubtitle.textContent = config.subtitle;
    modalDescription.innerHTML = config.description;
    confirmButton.className = `flex-1 px-4 py-2.5 ${config.buttonClass} rounded-xl font-semibold transition-colors`;
    confirmButton.textContent = config.buttonText;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(() => {
        document.getElementById('confirmModalContent').style.transform = 'scale(1)';
    }, 10);
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    const modalContent = document.getElementById('confirmModalContent');
    
    modalContent.style.transform = 'scale(0.95)';
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        pendingStatusChange = null;
    }, 200);
}

function changeProviderStatus(providerId, newStatus, providerName) {
    const statusConfigs = {
        'active': {
            icon: '<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            iconBg: 'bg-green-100',
            title: '✅ Ustayı Onayla',
            subtitle: providerName,
            description: '<strong>Usta aktif duruma geçecek:</strong><br>• Lead paketleri satın alabilir<br>• Sisteme tam erişim sağlar<br>• Dashboard\'ını kullanabilir',
            buttonClass: 'bg-green-600 hover:bg-green-700 text-white',
            buttonText: 'Onayla ve Aktif Et',
            successMessage: 'Usta başarıyla onaylandı ve aktif edildi!'
        },
        'pending': {
            icon: '<svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            iconBg: 'bg-yellow-100',
            title: '⏳ Ustayı Beklet',
            subtitle: providerName,
            description: '<strong>Usta bekleme durumuna alınacak:</strong><br>• Lead satın alamaz<br>• Onay sürecinde bekler<br>• WhatsApp kanal kontrolü yapılabilir',
            buttonClass: 'bg-yellow-600 hover:bg-yellow-700 text-white',
            buttonText: 'Bekletme Durumuna Al',
            successMessage: 'Usta bekleme durumuna alındı.'
        },
        'suspended': {
            icon: '<svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>',
            iconBg: 'bg-orange-100',
            title: '🚫 Ustayı Askıya Al',
            subtitle: providerName,
            description: '<strong>Usta askıya alınacak:</strong><br>• Sisteme giremez<br>• Tüm aktivitesi durdurulur<br>• Geçici bir önlem olarak uygulanır',
            buttonClass: 'bg-orange-600 hover:bg-orange-700 text-white',
            buttonText: 'Askıya Al',
            successMessage: 'Usta askıya alındı.'
        },
        'rejected': {
            icon: '<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            iconBg: 'bg-red-100',
            title: '❌ Ustayı Reddet',
            subtitle: providerName,
            description: '<strong>Usta tamamen reddedilecek:</strong><br>• Kalıcı olarak engellenecek<br>• Sisteme hiç giremez<br>• Bu işlem geri alınamaz!',
            buttonClass: 'bg-red-600 hover:bg-red-700 text-white',
            buttonText: 'Reddet ve Engelle',
            successMessage: 'Usta reddedildi ve engellendi.'
        }
    };
    
    const config = statusConfigs[newStatus];
    pendingStatusChange = { providerId, newStatus, providerName, config };
    showConfirmModal(config);
}

function executeStatusChange() {
    if (!pendingStatusChange) return;
    
    const { providerId, newStatus, providerName, config } = pendingStatusChange;
    
    closeConfirmModal();
    showToast('İşlem yapılıyor...', 'info', 2000);
    
    fetch('/admin/providers/change-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            provider_id: providerId,
            new_status: newStatus,
            csrf_token: csrfToken
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(config.successMessage, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.error || 'Durum güncellenemedi', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Beklenmeyen bir hata oluştu', 'error');
    });
}

// Animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    #confirmModalContent {
        transform: scale(0.95);
        transition: transform 0.2s ease-out;
    }
`;
document.head.appendChild(style);
</script>

<?php
// İçeriği al
$content = ob_get_clean();

// Layout'u yükle
require __DIR__ . '/layout.php';
?>



