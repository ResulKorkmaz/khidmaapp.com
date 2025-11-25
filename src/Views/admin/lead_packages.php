<?php
/**
 * Admin Lead Packages Management Page
 * Stripe entegrasyonlu paket yönetimi
 */

if (!isset($_SESSION['admin_id'])) {
    header('Location: /admin/login');
    exit;
}

// Page data
$packages = $pageData['packages'] ?? [];
$packagesByService = $pageData['packagesByService'] ?? [];
$totalPackages = $pageData['totalPackages'] ?? 0;
$activeCount = $pageData['activeCount'] ?? 0;
$inactiveCount = $pageData['inactiveCount'] ?? 0;
$services = $pageData['services'] ?? [];
$pageTitle = $pageData['pageTitle'] ?? 'Lead Paket Yönetimi';

// Start output buffering
ob_start();
?>

<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-lg p-6 mb-6" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
                    <span class="text-3xl">📦</span>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">Lead Paket Yönetimi</h1>
                    <p class="text-white/95 text-sm mt-1 font-medium" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">Stripe entegrasyonlu paket oluştur, düzenle ve yönet</p>
                </div>
            </div>
            
            <!-- Add New Package Button -->
            <button onclick="openCreateModal()" class="bg-white hover:bg-blue-50 text-blue-600 font-bold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Yeni Paket Oluştur</span>
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-2xl">📦</span>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium">Toplam Paket</p>
                    <p class="text-3xl font-bold text-gray-900"><?= $totalPackages ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-2xl">✅</span>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium">Aktif Paket</p>
                    <p class="text-3xl font-bold text-green-600"><?= $activeCount ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-2xl">⏸️</span>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pasif Paket</p>
                    <p class="text-3xl font-bold text-gray-600"><?= $inactiveCount ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Packages by Service Type -->
    <?php foreach ($packagesByService as $serviceType => $servicePackages): ?>
        <?php $serviceInfo = $services[$serviceType] ?? ['tr' => $serviceType, 'icon' => '📦']; ?>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
            <!-- Service Header -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <span class="text-2xl"><?= $serviceInfo['icon'] ?? '📦' ?></span>
                    <h2 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($serviceInfo['tr']) ?></h2>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full"><?= count($servicePackages) ?> paket</span>
                </div>
            </div>

            <!-- Packages Grid -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($servicePackages as $package): ?>
                    <?php 
                    $isActive = $package['is_active'];
                    $cardClass = $isActive ? 'border-blue-200 bg-blue-50/30' : 'border-gray-200 bg-gray-50 opacity-60';
                    ?>
                    <div class="border-2 <?= $cardClass ?> rounded-xl p-4 hover:shadow-md transition-all" id="package-card-<?= $package['id'] ?>">
                        <!-- Package Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="font-bold text-lg text-gray-900"><?= htmlspecialchars($package['name_tr']) ?></h3>
                                <p class="text-sm text-gray-500 mt-1" dir="rtl"><?= htmlspecialchars($package['name_ar']) ?></p>
                            </div>
                            <!-- Toggle Switch -->
                            <button onclick="toggleStatus(<?= $package['id'] ?>)" 
                                    id="status-btn-<?= $package['id'] ?>"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none <?= $isActive ? 'bg-green-500' : 'bg-gray-300' ?>">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform <?= $isActive ? 'translate-x-6' : 'translate-x-1' ?>"></span>
                            </button>
                        </div>

                        <!-- Package Details -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Lead Sayısı:</span>
                                <span class="font-bold text-blue-600"><?= $package['lead_count'] ?> lead</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Toplam Fiyat:</span>
                                <span class="font-bold text-gray-900"><?= number_format($package['price_sar'], 2) ?> SAR</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Lead Başına:</span>
                                <span class="font-semibold text-gray-700"><?= number_format($package['price_per_lead'], 2) ?> SAR</span>
                            </div>
                            <?php if ($package['discount_percentage'] > 0): ?>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">İndirim:</span>
                                    <span class="font-bold text-green-600">%<?= number_format($package['discount_percentage'], 1) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Stripe Info -->
                        <?php if ($package['stripe_product_id']): ?>
                            <div class="mb-4 p-2 bg-purple-50 rounded-lg">
                                <p class="text-xs text-purple-700 font-mono truncate" title="<?= htmlspecialchars($package['stripe_product_id']) ?>">
                                    🔗 <?= htmlspecialchars(substr($package['stripe_product_id'], 0, 20)) ?>...
                                </p>
                            </div>
                        <?php endif; ?>

                        <!-- Actions -->
                        <div class="flex items-center gap-2 pt-3 border-t border-gray-200">
                            <button onclick='openEditModal(<?= json_encode($package) ?>)' 
                                    class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                ✏️ Düzenle
                            </button>
                            <button onclick="deletePackage(<?= $package['id'] ?>, '<?= htmlspecialchars($package['name_tr']) ?>')" 
                                    class="px-3 py-2 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white text-sm font-semibold rounded-lg transition-colors">
                                🗑️
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (empty($packages)): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <span class="text-6xl mb-4 block">📭</span>
            <p class="text-gray-500 font-medium mb-4">Henüz paket eklenmemiş</p>
            <button onclick="openCreateModal()" class="text-blue-600 hover:text-blue-700 font-semibold">
                + İlk Paketi Oluştur
            </button>
        </div>
    <?php endif; ?>
</div>

<!-- Create/Edit Modal -->
<div id="packageModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-4 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <h3 id="modalTitle" class="text-xl font-bold text-white">Yeni Paket Oluştur</h3>
                <button onclick="closeModal()" class="text-white hover:bg-white/20 rounded-lg p-1 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <form id="packageForm" class="p-6 space-y-4">
            <input type="hidden" id="packageId" name="package_id">
            <input type="hidden" id="isEdit" value="0">

            <!-- Service Type (only for create) -->
            <div id="serviceTypeContainer">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Hizmet Türü <span class="text-red-500">*</span>
                </label>
                <select id="serviceType" name="service_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    <option value="">Seçiniz...</option>
                    <?php foreach ($services as $key => $service): ?>
                        <option value="<?= $key ?>"><?= $service['icon'] ?? '' ?> <?= htmlspecialchars($service['tr']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Lead Count (only for create) -->
            <div id="leadCountContainer">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Lead Sayısı <span class="text-red-500">*</span>
                </label>
                <input type="number" id="leadCount" name="lead_count" min="1" placeholder="Örn: 3" 
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
            </div>

            <!-- Price -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Fiyat (SAR) <span class="text-red-500">*</span>
                </label>
                <input type="number" id="priceSar" name="price_sar" min="0" step="0.01" placeholder="Örn: 324.00" 
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
            </div>

            <!-- Turkish Name -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Türkçe Ad <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nameTr" name="name_tr" placeholder="Örn: Elektrik - 3 Lead" 
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
            </div>

            <!-- Arabic Name -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Arapça Ad <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nameAr" name="name_ar" placeholder="حزمة 3 طلبات" dir="rtl"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
            </div>

            <!-- Turkish Description -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Türkçe Açıklama</label>
                <textarea id="descriptionTr" name="description_tr" rows="2" placeholder="3 yeni müşteri adayı kazanın"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
            </div>

            <!-- Arabic Description -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Arapça Açıklama</label>
                <textarea id="descriptionAr" name="description_ar" rows="2" placeholder="احصل على 3 عملاء محتملين جدد" dir="rtl"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
            </div>

            <!-- Active Status -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Durum</label>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_active" value="1" checked class="w-4 h-4 text-blue-600">
                        <span class="text-sm font-medium">Aktif</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_active" value="0" class="w-4 h-4 text-gray-600">
                        <span class="text-sm font-medium">Pasif</span>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModal()" 
                        class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors">
                    İptal
                </button>
                <button type="submit" id="submitBtn"
                        class="flex-1 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl">
                    Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// CSRF Token
const csrfToken = '<?= generateCsrfToken() ?>';

// Modal Functions
function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Yeni Paket Oluştur';
    document.getElementById('packageForm').reset();
    document.getElementById('isEdit').value = '0';
    document.getElementById('serviceTypeContainer').classList.remove('hidden');
    document.getElementById('leadCountContainer').classList.remove('hidden');
    document.getElementById('packageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function openEditModal(package) {
    document.getElementById('modalTitle').textContent = 'Paket Düzenle';
    document.getElementById('isEdit').value = '1';
    document.getElementById('packageId').value = package.id;
    document.getElementById('priceSar').value = package.price_sar;
    document.getElementById('nameTr').value = package.name_tr;
    document.getElementById('nameAr').value = package.name_ar;
    document.getElementById('descriptionTr').value = package.description_tr || '';
    document.getElementById('descriptionAr').value = package.description_ar || '';
    
    const radios = document.getElementsByName('is_active');
    radios.forEach(radio => {
        radio.checked = (radio.value == package.is_active);
    });
    
    document.getElementById('serviceTypeContainer').classList.add('hidden');
    document.getElementById('leadCountContainer').classList.add('hidden');
    document.getElementById('packageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('packageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Form Submit
document.getElementById('packageForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const isEdit = document.getElementById('isEdit').value === '1';
    const url = isEdit ? '/admin/lead-packages/update' : '/admin/lead-packages/create';
    const formData = new FormData(this);
    formData.append('csrf_token', csrfToken);
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Kaydediliyor...';
    
    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast(result.message, 'success');
            closeModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(result.error || 'Bir hata oluştu', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Kaydet';
        }
    } catch (error) {
        console.error('Form submit error:', error);
        showToast('Bağlantı hatası oluştu', 'error');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Kaydet';
    }
});

// Toggle Status
async function toggleStatus(packageId) {
    const formData = new FormData();
    formData.append('package_id', packageId);
    formData.append('csrf_token', csrfToken);
    
    try {
        const response = await fetch('/admin/lead-packages/toggle', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast(result.message, 'success');
            
            const btn = document.getElementById(`status-btn-${packageId}`);
            const isActive = result.new_status === 1;
            
            if (isActive) {
                btn.classList.remove('bg-gray-300');
                btn.classList.add('bg-green-500');
                btn.querySelector('span').classList.remove('translate-x-1');
                btn.querySelector('span').classList.add('translate-x-6');
            } else {
                btn.classList.remove('bg-green-500');
                btn.classList.add('bg-gray-300');
                btn.querySelector('span').classList.remove('translate-x-6');
                btn.querySelector('span').classList.add('translate-x-1');
            }
            
            const card = document.getElementById(`package-card-${packageId}`);
            if (isActive) {
                card.classList.remove('border-gray-200', 'bg-gray-50', 'opacity-60');
                card.classList.add('border-blue-200', 'bg-blue-50/30');
            } else {
                card.classList.remove('border-blue-200', 'bg-blue-50/30');
                card.classList.add('border-gray-200', 'bg-gray-50', 'opacity-60');
            }
        } else {
            showToast(result.error || 'Durum değiştirilemedi', 'error');
        }
    } catch (error) {
        console.error('Toggle status error:', error);
        showToast('Bağlantı hatası oluştu', 'error');
    }
}

// Delete Package
async function deletePackage(packageId, packageName) {
    if (!confirm(`"${packageName}" paketini silmek istediğinize emin misiniz?\n\nBu paket satın alımlarda kullanılıyorsa silinemez.\nStripe'da arşivlenecektir.`)) {
        return;
    }
    
    const formData = new FormData();
    formData.append('package_id', packageId);
    formData.append('csrf_token', csrfToken);
    
    try {
        const response = await fetch('/admin/lead-packages/delete', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast(result.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(result.error || 'Paket silinemedi', 'error');
        }
    } catch (error) {
        console.error('Delete package error:', error);
        showToast('Bağlantı hatası oluştu', 'error');
    }
}

// Toast Notification
function showToast(message, type = 'info') {
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in-right`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transition = 'opacity 0.5s';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>

<style>
@keyframes slide-in-right {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.animate-slide-in-right {
    animation: slide-in-right 0.3s ease-out;
}
</style>

<?php
// Get buffered content
$content = ob_get_clean();

// Load layout
require __DIR__ . '/layout.php';
