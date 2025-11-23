<?php
/**
 * Admin Services Management Page
 * Modern ve profesyonel hizmet yönetimi arayüzü
 */

// Admin login kontrolü
if (!isset($_SESSION['admin_id'])) {
    header('Location: /admin/login');
    exit;
}

// Page data
$services = $pageData['services'] ?? [];
$totalServices = $pageData['totalServices'] ?? 0;
$activeCount = $pageData['activeCount'] ?? 0;
$inactiveCount = $pageData['inactiveCount'] ?? 0;
$pageTitle = $pageData['pageTitle'] ?? 'Hizmet Yönetimi';

// Start output buffering
ob_start();
?>

<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
                    <span class="text-3xl">📋</span>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white">Hizmet Yönetimi</h1>
                    <p class="text-blue-100 text-sm mt-1">Sistemdeki hizmetleri yönetin, ekleyin ve düzenleyin</p>
                </div>
            </div>
            
            <!-- Add New Service Button -->
            <button onclick="openCreateModal()" class="bg-white hover:bg-blue-50 text-blue-600 font-bold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Yeni Hizmet Ekle</span>
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Total Services -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-2xl">📦</span>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium">Toplam Hizmet</p>
                    <p class="text-3xl font-bold text-gray-900"><?= $totalServices ?></p>
                </div>
            </div>
        </div>

        <!-- Active Services -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-2xl">✅</span>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium">Aktif Hizmet</p>
                    <p class="text-3xl font-bold text-green-600"><?= $activeCount ?></p>
                </div>
            </div>
        </div>

        <!-- Inactive Services -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-2xl">⏸️</span>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pasif Hizmet</p>
                    <p class="text-3xl font-bold text-gray-600"><?= $inactiveCount ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Services List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Table Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Hizmet Listesi</h2>
                <p class="text-sm text-gray-500"><?= count($services) ?> hizmet</p>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sıra</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Icon</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Service Key</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Arapça Ad</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Türkçe Ad</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Durum</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($services)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <span class="text-5xl">📭</span>
                                    <p class="text-gray-500 font-medium">Henüz hizmet eklenmemiş</p>
                                    <button onclick="openCreateModal()" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                                        + İlk Hizmeti Ekle
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($services as $service): ?>
                            <?php 
                            $isActive = $service['is_active'];
                            $rowClass = $isActive ? '' : 'bg-gray-50 opacity-60';
                            ?>
                            <tr class="hover:bg-gray-50 transition-colors <?= $rowClass ?>" id="service-row-<?= $service['id'] ?>">
                                <!-- Display Order -->
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-gray-700">#<?= $service['display_order'] ?></span>
                                </td>

                                <!-- Icon -->
                                <td class="px-6 py-4">
                                    <span class="text-3xl"><?= htmlspecialchars($service['icon'] ?? '📦') ?></span>
                                </td>

                                <!-- Service Key -->
                                <td class="px-6 py-4">
                                    <span class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">
                                        <?= htmlspecialchars($service['service_key']) ?>
                                    </span>
                                </td>

                                <!-- Arabic Name -->
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-gray-900" dir="rtl">
                                        <?= htmlspecialchars($service['name_ar']) ?>
                                    </span>
                                </td>

                                <!-- Turkish Name -->
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($service['name_tr']) ?>
                                    </span>
                                </td>

                                <!-- Status Toggle -->
                                <td class="px-6 py-4 text-center">
                                    <button onclick="toggleStatus(<?= $service['id'] ?>)" 
                                            id="status-btn-<?= $service['id'] ?>"
                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 <?= $isActive ? 'bg-green-500' : 'bg-gray-300' ?>">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform <?= $isActive ? 'translate-x-6' : 'translate-x-1' ?>"></span>
                                    </button>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Edit Button -->
                                        <button onclick='openEditModal(<?= json_encode($service) ?>)' 
                                                class="group flex items-center gap-1.5 px-3 py-2 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-200 hover:border-blue-600 rounded-lg transition-all shadow-sm hover:shadow-md"
                                                title="Düzenle">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            <span class="text-xs font-semibold">Düzenle</span>
                                        </button>

                                        <!-- Delete Button -->
                                        <button onclick="deleteService(<?= $service['id'] ?>, '<?= htmlspecialchars($service['name_tr']) ?>')" 
                                                class="group flex items-center gap-1.5 px-3 py-2 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border border-red-200 hover:border-red-600 rounded-lg transition-all shadow-sm hover:shadow-md"
                                                title="Sil">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span class="text-xs font-semibold">Sil</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create/Edit Service Modal -->
<div id="serviceModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <h3 id="modalTitle" class="text-xl font-bold text-white">Yeni Hizmet Ekle</h3>
                <button onclick="closeModal()" class="text-white hover:bg-white/20 rounded-lg p-1 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <form id="serviceForm" class="p-6 space-y-4">
            <input type="hidden" id="serviceId" name="service_id">
            <input type="hidden" id="isEdit" value="0">

            <!-- Service Key -->
            <div id="serviceKeyContainer">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Service Key <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="serviceKey" 
                       name="service_key" 
                       placeholder="electric, plumbing, painting..."
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                       required>
                <p class="text-xs text-gray-500 mt-1">Küçük harf, rakam ve alt çizgi (_) kullanın</p>
            </div>

            <!-- Arabic Name -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Arapça Ad <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="nameAr" 
                       name="name_ar" 
                       placeholder="كهرباء"
                       dir="rtl"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                       required>
            </div>

            <!-- Turkish Name -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Türkçe Ad <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="nameTr" 
                       name="name_tr" 
                       placeholder="Elektrik"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                       required>
            </div>

            <!-- Icon -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Icon (Emoji)
                </label>
                <input type="text" 
                       id="icon" 
                       name="icon" 
                       placeholder="⚡"
                       maxlength="10"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-2xl text-center"
                       >
                <p class="text-xs text-gray-500 mt-1">Emoji kopyala-yapıştır yapabilirsiniz</p>
            </div>

            <!-- Display Order (only for edit) -->
            <div id="displayOrderContainer" class="hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Sıralama
                </label>
                <input type="number" 
                       id="displayOrder" 
                       name="display_order" 
                       min="0"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
            </div>

            <!-- Active Status -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Durum</label>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_active" value="1" checked class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Aktif</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_active" value="0" class="w-4 h-4 text-gray-600 focus:ring-gray-500">
                        <span class="text-sm font-medium text-gray-700">Pasif</span>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                <button type="button" 
                        onclick="closeModal()" 
                        class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors">
                    İptal
                </button>
                <button type="submit" 
                        id="submitBtn"
                        class="flex-1 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl">
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
    document.getElementById('modalTitle').textContent = 'Yeni Hizmet Ekle';
    document.getElementById('serviceForm').reset();
    document.getElementById('isEdit').value = '0';
    document.getElementById('serviceKeyContainer').classList.remove('hidden');
    document.getElementById('displayOrderContainer').classList.add('hidden');
    document.getElementById('serviceModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function openEditModal(service) {
    document.getElementById('modalTitle').textContent = 'Hizmet Düzenle';
    document.getElementById('isEdit').value = '1';
    document.getElementById('serviceId').value = service.id;
    document.getElementById('serviceKey').value = service.service_key;
    document.getElementById('nameAr').value = service.name_ar;
    document.getElementById('nameTr').value = service.name_tr;
    document.getElementById('icon').value = service.icon || '';
    document.getElementById('displayOrder').value = service.display_order;
    
    // Set active status
    const radios = document.getElementsByName('is_active');
    radios.forEach(radio => {
        radio.checked = (radio.value == service.is_active);
    });
    
    // Hide service_key field (can't edit), show display_order
    document.getElementById('serviceKeyContainer').classList.add('hidden');
    document.getElementById('displayOrderContainer').classList.remove('hidden');
    
    document.getElementById('serviceModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('serviceModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Form Submit
document.getElementById('serviceForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const isEdit = document.getElementById('isEdit').value === '1';
    const url = isEdit ? '/admin/services/update' : '/admin/services/create';
    const formData = new FormData(this);
    
    // Add CSRF token
    formData.append('csrf_token', csrfToken);
    
    // Show loading
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
async function toggleStatus(serviceId) {
    const formData = new FormData();
    formData.append('service_id', serviceId);
    formData.append('csrf_token', csrfToken);
    
    try {
        const response = await fetch('/admin/services/toggle', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast(result.message, 'success');
            
            // Update button UI
            const btn = document.getElementById(`status-btn-${serviceId}`);
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
            
            // Update row opacity
            const row = document.getElementById(`service-row-${serviceId}`);
            if (isActive) {
                row.classList.remove('bg-gray-50', 'opacity-60');
            } else {
                row.classList.add('bg-gray-50', 'opacity-60');
            }
        } else {
            showToast(result.error || 'Durum değiştirilemedi', 'error');
        }
    } catch (error) {
        console.error('Toggle status error:', error);
        showToast('Bağlantı hatası oluştu', 'error');
    }
}

// Delete Service
async function deleteService(serviceId, serviceName) {
    if (!confirm(`"${serviceName}" hizmetini silmek istediğinize emin misiniz?\n\nBu hizmet usta veya lead tarafından kullanılıyorsa silinemez.`)) {
        return;
    }
    
    const formData = new FormData();
    formData.append('service_id', serviceId);
    formData.append('csrf_token', csrfToken);
    
    try {
        const response = await fetch('/admin/services/delete', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast(result.message, 'success');
            
            // Fade out and remove row
            const row = document.getElementById(`service-row-${serviceId}`);
            row.style.transition = 'opacity 0.5s';
            row.style.opacity = '0';
            setTimeout(() => {
                row.remove();
                // Reload if no more services
                if (document.querySelectorAll('tbody tr').length === 0) {
                    location.reload();
                }
            }, 500);
        } else {
            showToast(result.error || 'Hizmet silinemedi', 'error');
        }
    } catch (error) {
        console.error('Delete service error:', error);
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

include __DIR__ . '/layout.php';
