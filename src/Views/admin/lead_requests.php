<?php
/**
 * Admin Lead İstekleri Sayfası
 * Ustalardan gelen lead istekleri - Manuel gönderim
 */

$pageTitle = $pageTitle ?? 'Lead İstekleri';
$currentPage = 'lead-requests';
$requests = $requests ?? [];
$stats = $stats ?? ['pending' => 0, 'completed' => 0, 'cancelled' => 0, 'total' => 0];
$statusFilter = $statusFilter ?? 'all';

// Bekleyen ve tamamlanmış istekleri ayır
$pendingRequests = array_filter($requests, fn($r) => ($r['request_status'] ?? '') === 'pending');
$completedRequests = array_filter($requests, fn($r) => ($r['request_status'] ?? '') === 'completed');

ob_start();
?>

<!-- Header -->
<div class="bg-orange-500 rounded-2xl shadow-lg p-6 mb-6" style="background-color: #f97316 !important;">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background-color: rgba(255,255,255,0.2);">
                <span class="text-3xl">⏰</span>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold" style="color: #ffffff !important;">Lead İstekleri</h1>
                <p class="text-sm mt-1" style="color: #ffffff !important;">Ustalardan gelen lead talepleri - Manuel gönderim</p>
            </div>
        </div>
        <div class="flex gap-3">
            <div class="rounded-xl px-4 py-2 text-center" style="background-color: rgba(255,255,255,0.2);">
                <div class="text-2xl font-bold" style="color: #ffffff !important;"><?= $stats['pending'] ?? 0 ?></div>
                <div class="text-xs" style="color: #ffffff !important;">Bekleyen</div>
            </div>
            <div class="rounded-xl px-4 py-2 text-center" style="background-color: rgba(255,255,255,0.2);">
                <div class="text-2xl font-bold" style="color: #ffffff !important;"><?= $stats['completed'] ?? 0 ?></div>
                <div class="text-xs" style="color: #ffffff !important;">Tamamlanan</div>
            </div>
        </div>
    </div>
</div>

<!-- Filtreler -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    <div class="flex items-center gap-3 flex-wrap">
        <span class="text-sm font-medium text-gray-700">Durum:</span>
        <a href="/admin/lead-requests?status=all" class="px-4 py-2 rounded-lg text-sm font-medium <?= $statusFilter === 'all' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Tümü (<?= $stats['total'] ?? 0 ?>)
        </a>
        <a href="/admin/lead-requests?status=pending" class="px-4 py-2 rounded-lg text-sm font-medium <?= $statusFilter === 'pending' ? 'bg-yellow-500 text-white' : 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' ?>">
            Bekleyen (<?= $stats['pending'] ?? 0 ?>)
        </a>
        <a href="/admin/lead-requests?status=completed" class="px-4 py-2 rounded-lg text-sm font-medium <?= $statusFilter === 'completed' ? 'bg-green-500 text-white' : 'bg-green-100 text-green-700 hover:bg-green-200' ?>">
            Tamamlanan (<?= $stats['completed'] ?? 0 ?>)
        </a>
    </div>
</div>

<!-- İstekler Listesi -->
<?php if (empty($requests)): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Lead İsteği Yok</h3>
        <p class="text-gray-600">Henüz bekleyen lead isteği bulunmuyor</p>
    </div>
<?php else: ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">İstek #</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Usta Bilgileri</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Paket</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Durum</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tarih</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">İşlem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($requests as $request): 
                        $serviceTypes = getServiceTypes();
                        $cities = getCities();
                        $serviceName = $serviceTypes[$request['provider_service_type'] ?? '']['tr'] ?? ($request['provider_service_type'] ?? '-');
                        $cityName = $cities[$request['provider_city'] ?? '']['tr'] ?? ($request['provider_city'] ?? '-');
                        $isPending = ($request['request_status'] ?? '') === 'pending';
                    ?>
                        <tr class="hover:bg-gray-50 transition-colors <?= $isPending ? 'bg-yellow-50' : '' ?>">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-900">#<?= $request['id'] ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div class="text-base font-bold text-gray-900"><?= htmlspecialchars($request['provider_name'] ?? '-') ?></div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">
                                            <?= $serviceName ?>
                                        </span>
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded">
                                            <?= $cityName ?>
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500">📞 <?= htmlspecialchars($request['provider_phone'] ?? '-') ?></div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($request['package_name'] ?? 'Paket bilgisi yok') ?></div>
                                <?php if (isset($request['remaining_leads'])): ?>
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-orange-100 text-orange-700 rounded font-semibold">
                                        Kalan: <?= $request['remaining_leads'] ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($isPending): ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                        ⏳ Bekliyor
                                    </span>
                                <?php elseif (($request['request_status'] ?? '') === 'completed'): ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                        ✅ Tamamlandı
                                    </span>
                                    <?php if (!empty($request['lead_id'])): ?>
                                    <div class="text-xs text-gray-500 mt-1">Lead #<?= str_pad($request['lead_id'], 6, '0', STR_PAD_LEFT) ?></div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">
                                        <?= ucfirst($request['request_status'] ?? 'Bilinmiyor') ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700"><?= date('d/m/Y', strtotime($request['requested_at'] ?? 'now')) ?></div>
                                <div class="text-xs text-gray-500"><?= date('H:i', strtotime($request['requested_at'] ?? 'now')) ?></div>
                                <?php if (!empty($request['notes'])): ?>
                                <div class="text-xs text-blue-600 mt-1" title="<?= htmlspecialchars($request['notes']) ?>">📝 Not var</div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($isPending): ?>
                                    <button 
                                        onclick="openSendLeadModal(<?= $request['id'] ?>, '<?= htmlspecialchars($request['provider_name'] ?? '', ENT_QUOTES) ?>', '<?= $request['provider_service_type'] ?? '' ?>', '<?= $request['provider_city'] ?? '' ?>', <?= $request['purchase_id'] ?? 0 ?>)"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors shadow-md hover:shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                        Lead Gönder
                                    </button>
                                <?php else: ?>
                                    <span class="text-gray-400 text-sm">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Sayfalama -->
    <?php if (($totalPages ?? 1) > 1): ?>
    <div class="flex justify-center mt-6">
        <nav class="inline-flex rounded-lg shadow-sm">
            <?php if (($page ?? 1) > 1): ?>
                <a href="/admin/lead-requests?page=<?= ($page ?? 1) - 1 ?>&status=<?= $statusFilter ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-50 text-sm">
                    ← Önceki
                </a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                <a href="/admin/lead-requests?page=<?= $i ?>&status=<?= $statusFilter ?>" 
                   class="px-4 py-2 border-t border-b border-gray-300 text-sm <?= $i === ($page ?? 1) ? 'bg-orange-500 text-white border-orange-500' : 'bg-white hover:bg-gray-50' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            
            <?php if (($page ?? 1) < ($totalPages ?? 1)): ?>
                <a href="/admin/lead-requests?page=<?= ($page ?? 1) + 1 ?>&status=<?= $statusFilter ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-50 text-sm">
                    Sonraki →
                </a>
            <?php endif; ?>
        </nav>
    </div>
    <?php endif; ?>
<?php endif; ?>

<!-- Modal: Lead Gönder -->
<div id="sendLeadModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 bg-green-50">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-gray-900">📤 Lead Gönder</h3>
                <button onclick="closeSendLeadModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <p class="text-sm text-gray-600 mt-2">Usta: <span id="modal-provider-name" class="font-bold text-green-700"></span></p>
            <p class="text-xs text-gray-500 mt-1">
                Hizmet: <span id="modal-service-type" class="font-semibold text-blue-600"></span> | 
                Şehir: <span id="modal-city" class="font-semibold text-purple-600"></span>
            </p>
        </div>
        
        <div class="p-6">
            <!-- Uygun Lead'ler Listesi -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-lg font-bold text-gray-900">🎯 Uygun Lead'ler</h4>
                    <span id="leads-count-badge" class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                        Yükleniyor...
                    </span>
                </div>
                <p class="text-xs text-gray-500 mb-3">Ustanın hizmet türü ve şehriyle eşleşen lead'ler (en eskiden en yeniye)</p>
                
                <div id="matching-leads-container" class="border border-gray-200 rounded-xl overflow-hidden">
                    <div id="matching-leads-loading" class="p-8 text-center">
                        <svg class="animate-spin h-8 w-8 mx-auto text-green-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-gray-500">Lead'ler yükleniyor...</p>
                    </div>
                    <div id="matching-leads-list" class="hidden max-h-64 overflow-y-auto">
                        <!-- Lead'ler buraya yüklenecek -->
                    </div>
                    <div id="matching-leads-empty" class="hidden p-8 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">Uygun lead bulunamadı</p>
                        <p class="text-gray-400 text-sm mt-1">Bu şehir ve hizmet türünde gönderilmemiş lead yok</p>
                    </div>
                </div>
            </div>
            
            <form id="sendLeadForm">
                <input type="hidden" name="request_id" id="form-request-id">
                <input type="hidden" name="purchase_id" id="form-purchase-id">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Seçilen Lead ID:</label>
                    <input type="number" name="lead_id" id="form-lead-id" required min="1"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-green-500 focus:ring-green-500 text-lg font-bold"
                           placeholder="Yukarıdan bir lead seçin veya ID girin...">
                    <p class="text-xs text-gray-500 mt-2">Yukarıdaki listeden tıklayarak seçebilir veya manuel ID girebilirsiniz.</p>
                </div>
                
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeSendLeadModal()" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition-colors">
                        İptal
                    </button>
                    <button type="submit" id="confirm-send-btn" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Lead'i Gönder
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Hizmet türleri ve şehir isimleri
const serviceTypeNames = <?= json_encode(array_map(fn($s) => $s['tr'], getServiceTypes())) ?>;
const cityNames = <?= json_encode(array_map(fn($c) => $c['tr'], getCities())) ?>;

async function openSendLeadModal(requestId, providerName, serviceType, city, purchaseId) {
    document.getElementById('modal-provider-name').textContent = providerName;
    document.getElementById('modal-service-type').textContent = serviceTypeNames[serviceType] || serviceType;
    document.getElementById('modal-city').textContent = cityNames[city] || city;
    document.getElementById('form-request-id').value = requestId;
    document.getElementById('form-purchase-id').value = purchaseId;
    document.getElementById('form-lead-id').value = '';
    document.getElementById('sendLeadModal').classList.remove('hidden');
    
    // Lead'leri yükle
    await loadMatchingLeads(serviceType, city);
}

async function loadMatchingLeads(serviceType, city) {
    const loadingEl = document.getElementById('matching-leads-loading');
    const listEl = document.getElementById('matching-leads-list');
    const emptyEl = document.getElementById('matching-leads-empty');
    const countBadge = document.getElementById('leads-count-badge');
    
    // Durumu sıfırla
    loadingEl.classList.remove('hidden');
    listEl.classList.add('hidden');
    emptyEl.classList.add('hidden');
    countBadge.textContent = 'Yükleniyor...';
    countBadge.className = 'px-3 py-1 bg-gray-100 text-gray-600 text-sm font-semibold rounded-full';
    
    try {
        const response = await fetch(`/admin/api/available-leads?service_type=${encodeURIComponent(serviceType)}&city=${encodeURIComponent(city)}`);
        const result = await response.json();
        
        loadingEl.classList.add('hidden');
        
        if (result.success && result.leads && result.leads.length > 0) {
            const leads = result.leads;
            countBadge.textContent = `${leads.length} Lead`;
            countBadge.className = 'px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full';
            
            // Lead listesini oluştur
            listEl.innerHTML = leads.map((lead, index) => {
                const statusBadge = getStatusBadge(lead.status);
                const date = new Date(lead.created_at);
                const formattedDate = date.toLocaleDateString('tr-TR') + ' ' + date.toLocaleTimeString('tr-TR', {hour: '2-digit', minute: '2-digit'});
                
                return `
                    <div onclick="selectLead(${lead.id})" 
                         class="lead-item p-4 border-b border-gray-100 hover:bg-green-50 cursor-pointer transition-colors flex items-center justify-between gap-4"
                         data-lead-id="${lead.id}">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center font-bold text-blue-600">
                                #${lead.id}
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-semibold text-gray-900">📞 ${lead.phone}</span>
                                    ${statusBadge}
                                </div>
                                <p class="text-xs text-gray-500 mt-1">${lead.description ? lead.description.substring(0, 50) + '...' : 'Açıklama yok'}</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="text-xs text-gray-400">${formattedDate}</div>
                            <div class="text-xs text-orange-600 font-medium mt-1">${index === 0 ? '🔥 En eski' : ''}</div>
                        </div>
                    </div>
                `;
            }).join('');
            
            listEl.classList.remove('hidden');
        } else {
            countBadge.textContent = '0 Lead';
            countBadge.className = 'px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full';
            emptyEl.classList.remove('hidden');
        }
    } catch (error) {
        console.error('Load leads error:', error);
        loadingEl.classList.add('hidden');
        countBadge.textContent = 'Hata';
        countBadge.className = 'px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full';
        emptyEl.classList.remove('hidden');
        emptyEl.querySelector('p.text-gray-500').textContent = 'Lead\'ler yüklenirken hata oluştu';
    }
}

function getStatusBadge(status) {
    const badges = {
        'new': '<span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded">Yeni</span>',
        'verified': '<span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded">Doğrulanmış</span>',
        'pending': '<span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded">Beklemede</span>'
    };
    return badges[status] || `<span class="px-2 py-0.5 bg-gray-100 text-gray-700 text-xs font-semibold rounded">${status}</span>`;
}

function selectLead(leadId) {
    document.getElementById('form-lead-id').value = leadId;
    
    // Seçili lead'i vurgula
    document.querySelectorAll('.lead-item').forEach(item => {
        item.classList.remove('bg-green-100', 'border-l-4', 'border-green-500');
    });
    
    const selectedItem = document.querySelector(`.lead-item[data-lead-id="${leadId}"]`);
    if (selectedItem) {
        selectedItem.classList.add('bg-green-100', 'border-l-4', 'border-green-500');
    }
}

function closeSendLeadModal() {
    document.getElementById('sendLeadModal').classList.add('hidden');
}

document.getElementById('sendLeadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('confirm-send-btn');
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    
    try {
        const formData = new FormData(this);
        
        const response = await fetch('/admin/lead-requests/send', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('success', result.message || '✅ Lead başarıyla gönderildi!');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showToast('error', result.message || 'Bir hata oluştu');
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }
    } catch (error) {
        console.error('Send error:', error);
        showToast('error', 'Bir hata oluştu');
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }
});

function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-[9999] px-6 py-4 rounded-xl shadow-2xl border-2 transition-all ${
        type === 'success' 
            ? 'bg-green-50 border-green-500 text-green-800' 
            : 'bg-red-50 border-red-500 text-red-800'
    }`;
    
    toast.innerHTML = `
        <div class="flex items-center gap-3">
            ${type === 'success' 
                ? '<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                : '<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            }
            <span class="font-semibold">${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
