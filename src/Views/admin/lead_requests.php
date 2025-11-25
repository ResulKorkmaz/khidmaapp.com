<?php
/**
 * Admin Lead İstekleri Sayfası
 * Bekleyen lead isteklerini görüntüleme ve manuel gönderim
 */

$pageTitle = $pageTitle ?? 'Lead İstekleri';
$currentPage = 'lead-requests';
$pendingRequests = $pageData['pendingRequests'] ?? [];
$completedRequests = $pageData['completedRequests'] ?? [];
$availableLeads = $pageData['availableLeads'] ?? [];

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
        <div class="rounded-xl px-4 py-2" style="background-color: rgba(255,255,255,0.2);">
            <div class="text-2xl font-bold" style="color: #ffffff !important;"><?= count($pendingRequests) ?></div>
            <div class="text-xs" style="color: #ffffff !important;">Bekleyen</div>
        </div>
    </div>
</div>

<!-- Bekleyen İstekler -->
<?php if (empty($pendingRequests)): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center mb-6">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">✅ Tüm İstekler İşlendi!</h3>
        <p class="text-gray-600">Şu anda bekleyen lead isteği yok</p>
    </div>
<?php else: ?>
    <!-- Desktop: Tablo Görünümü -->
    <div class="hidden lg:block bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-200 bg-yellow-50">
            <h2 class="text-xl font-bold text-gray-900">⏳ Bekleyen İstekler (<?= count($pendingRequests) ?>)</h2>
            <p class="text-sm text-gray-600 mt-1">Ustalara lead göndermek için listeden seçin</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">İstek #</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Usta Bilgileri</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Paket</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">İstek Tarihi</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">İşlem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($pendingRequests as $request): 
                        $serviceTypes = getServiceTypes();
                        $cities = getCities();
                        $serviceName = $serviceTypes[$request['service_type']]['tr'] ?? $request['service_type'];
                        $cityName = $cities[$request['provider_city']]['tr'] ?? $request['provider_city'];
                    ?>
                        <tr class="hover:bg-yellow-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-900">#<?= $request['id'] ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div class="text-base font-bold text-gray-900"><?= htmlspecialchars($request['provider_name']) ?></div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-100 text-blue-800 text-sm font-bold rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            <?= $serviceName ?>
                                        </span>
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-100 text-purple-800 text-sm font-bold rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <?= $cityName ?>
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($request['provider_email']) ?></div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($request['package_name']) ?></div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-orange-100 text-orange-700 rounded font-semibold">
                                        Kalan: <?= $request['remaining_leads'] ?>
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700"><?= date('d/m/Y', strtotime($request['requested_at'])) ?></div>
                                <div class="text-xs text-gray-500"><?= date('H:i', strtotime($request['requested_at'])) ?></div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button 
                                    onclick="openSendLeadModal(<?= $request['id'] ?>, '<?= htmlspecialchars($request['provider_name'], ENT_QUOTES) ?>', '<?= $request['service_type'] ?>', '<?= $request['provider_city'] ?>')"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    Lead Gönder
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile: Card Görünümü -->
    <div class="lg:hidden space-y-4 mb-6">
        <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-4 mb-4">
            <h2 class="text-lg font-bold text-gray-900">⏳ Bekleyen İstekler (<?= count($pendingRequests) ?>)</h2>
            <p class="text-sm text-gray-600 mt-1">Ustalara lead göndermek için listeden seçin</p>
        </div>
        
        <?php foreach ($pendingRequests as $request): 
            $serviceTypes = getServiceTypes();
            $cities = getCities();
            $serviceName = $serviceTypes[$request['service_type']]['tr'] ?? $request['service_type'];
            $cityName = $cities[$request['provider_city']]['tr'] ?? $request['provider_city'];
        ?>
            <div class="bg-white rounded-xl shadow-md border-2 border-yellow-200 p-4">
                <!-- Header -->
                <div class="flex items-center justify-between mb-3 pb-3 border-b border-gray-200">
                    <span class="text-sm font-bold text-gray-500">İstek #<?= $request['id'] ?></span>
                    <span class="text-xs text-gray-400"><?= date('d.m.Y H:i', strtotime($request['requested_at'])) ?></span>
                </div>
                
                <!-- Usta Bilgileri -->
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-2"><?= htmlspecialchars($request['provider_name']) ?></h3>
                    <div class="flex flex-wrap gap-2 mb-2">
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-100 text-blue-800 text-sm font-bold rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <?= $serviceName ?>
                        </span>
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-100 text-purple-800 text-sm font-bold rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <?= $cityName ?>
                        </span>
                    </div>
                    <div class="text-sm text-gray-500"><?= htmlspecialchars($request['provider_email']) ?></div>
                </div>
                
                <!-- Paket Bilgisi -->
                <div class="bg-gray-50 rounded-lg p-3 mb-4">
                    <div class="text-sm font-medium text-gray-900 mb-1"><?= htmlspecialchars($request['package_name']) ?></div>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-orange-100 text-orange-700 rounded font-semibold">
                            Kalan: <?= $request['remaining_leads'] ?? 0 ?>
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded font-semibold">
                            Bekleyen: <?= $request['pending_requests'] ?? 0 ?>
                        </span>
                    </div>
                </div>
                
                <!-- Lead Gönder Butonu - Tam Genişlik -->
                <button 
                    onclick="openSendLeadModal(<?= $request['id'] ?>, '<?= htmlspecialchars($request['provider_name'], ENT_QUOTES) ?>', '<?= $request['service_type'] ?>', '<?= $request['provider_city'] ?>')"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-colors shadow-lg hover:shadow-xl text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    📤 Lead Gönder
                </button>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Tamamlanmış İstekler -->
<?php if (!empty($completedRequests)): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-green-50">
            <h2 class="text-xl font-bold text-gray-900">✅ Son Tamamlanan İstekler</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">İstek #</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Usta</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Lead #</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Gönderen</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Gönderim Tarihi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach (array_slice($completedRequests, 0, 20) as $request): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">#<?= $request['id'] ?></td>
                            <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($request['provider_name']) ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-extrabold rounded-lg shadow-md" style="background-color: #f97316 !important; color: #ffffff !important;">
                                    🆔 #<?= str_pad($request['lead_id'], 6, '0', STR_PAD_LEFT) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($request['admin_name']) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-700"><?= date('d/m/Y H:i', strtotime($request['completed_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<!-- Modal: Lead Gönder -->
<div id="sendLeadModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-gray-900">📤 Lead Gönder</h3>
                <button onclick="closeSendLeadModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <p class="text-sm text-gray-600 mt-2">Usta: <span id="modal-provider-name" class="font-semibold"></span></p>
        </div>
        
        <div class="p-6">
            <h4 class="text-lg font-bold text-gray-900 mb-4">Uygun Lead'ler:</h4>
            
            <div id="leads-list" class="space-y-3 max-h-96 overflow-y-auto">
                <?php foreach ($availableLeads as $lead): 
                    $serviceTypes = getServiceTypes();
                    $cities = getCities();
                    $serviceName = $serviceTypes[$lead['service_type']]['tr'] ?? $lead['service_type'];
                    $cityName = $cities[$lead['city']]['tr'] ?? $lead['city'];
                ?>
                    <div 
                        data-lead-id="<?= $lead['id'] ?>"
                        data-service-type="<?= $lead['service_type'] ?>"
                        data-city="<?= $lead['city'] ?>"
                        class="lead-item border-2 border-gray-200 rounded-xl p-4 hover:border-green-500 hover:bg-green-50 cursor-pointer transition-all"
                        onclick="selectLead(<?= $lead['id'] ?>)">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 text-base font-extrabold rounded-lg shadow-md" style="background-color: #f97316 !important; color: #ffffff !important;">
                                        🆔 #<?= str_pad($lead['id'], 6, '0', STR_PAD_LEFT) ?>
                                    </span>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded"><?= $serviceName ?></span>
                                    <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded"><?= $cityName ?></span>
                                </div>
                                <p class="text-sm text-gray-700 mb-2"><?= htmlspecialchars(substr($lead['description'], 0, 100)) ?>...</p>
                                <div class="flex items-center gap-4 text-xs text-gray-500">
                                    <span>📱 <?= htmlspecialchars($lead['phone']) ?></span>
                                    <span>📅 <?= date('d/m/Y', strtotime($lead['created_at'])) ?></span>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 border-2 border-gray-300 rounded-full flex items-center justify-center lead-checkbox">
                                    <svg class="w-5 h-5 text-green-600 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (empty($availableLeads)): ?>
                <div class="text-center py-8 text-gray-500">
                    <p>Henüz gönderilmeye hazır lead yok</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="p-6 border-t border-gray-200 bg-gray-50">
            <div class="flex gap-3 justify-end">
                <button onclick="closeSendLeadModal()" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition-colors">
                    İptal
                </button>
                <button id="confirm-send-btn" onclick="confirmSendLead()" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Lead'i Gönder
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let selectedRequestId = null;
let selectedLeadId = null;
let filterServiceType = null;
let filterCity = null;

function openSendLeadModal(requestId, providerName, serviceType, city) {
    selectedRequestId = requestId;
    filterServiceType = serviceType;
    filterCity = city;
    selectedLeadId = null;
    
    document.getElementById('modal-provider-name').textContent = providerName;
    document.getElementById('sendLeadModal').classList.remove('hidden');
    
    // Lead'leri filtrele
    filterLeads();
}

function closeSendLeadModal() {
    document.getElementById('sendLeadModal').classList.add('hidden');
    selectedRequestId = null;
    selectedLeadId = null;
    
    // Seçimleri temizle
    document.querySelectorAll('.lead-item').forEach(item => {
        item.classList.remove('border-green-500', 'bg-green-50', 'hidden');
        item.querySelector('.lead-checkbox svg').classList.add('hidden');
    });
    
    // "Lead yok" mesajını kaldır
    const noLeadsMsg = document.getElementById('no-leads-message');
    if (noLeadsMsg) {
        noLeadsMsg.remove();
    }
}

function filterLeads() {
    let visibleCount = 0;
    
    document.querySelectorAll('.lead-item').forEach(item => {
        const itemService = item.getAttribute('data-service-type');
        const itemCity = item.getAttribute('data-city');
        
        // Case-insensitive comparison (büyük/küçük harf duyarsız)
        const serviceMatch = itemService.toLowerCase() === filterServiceType.toLowerCase();
        const cityMatch = itemCity.toLowerCase() === filterCity.toLowerCase();
        
        if (serviceMatch && cityMatch) {
            item.classList.remove('hidden', 'opacity-50');
            visibleCount++;
        } else {
            item.classList.add('hidden');
        }
    });
    
    // Eğer hiç eşleşen lead yoksa uyarı göster
    const leadsList = document.getElementById('leads-list');
    let noLeadsMsg = document.getElementById('no-leads-message');
    
    if (visibleCount === 0) {
        if (!noLeadsMsg) {
            noLeadsMsg = document.createElement('div');
            noLeadsMsg.id = 'no-leads-message';
            noLeadsMsg.className = 'text-center py-12 bg-yellow-50 border-2 border-yellow-200 rounded-xl';
            noLeadsMsg.innerHTML = `
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-yellow-900 mb-2">⚠️ Uygun Lead Bulunamadı</h4>
                <p class="text-sm text-yellow-700">Bu usta için (${filterServiceType} / ${filterCity}) henüz gönderilmeye hazır lead yok.</p>
                <p class="text-xs text-yellow-600 mt-2">Yeni lead geldiğinde burada listelenecektir.</p>
            `;
            leadsList.appendChild(noLeadsMsg);
        }
    } else {
        if (noLeadsMsg) {
            noLeadsMsg.remove();
        }
    }
}

function selectLead(leadId) {
    selectedLeadId = leadId;
    
    // Tüm seçimleri temizle
    document.querySelectorAll('.lead-item').forEach(item => {
        item.classList.remove('border-green-500', 'bg-green-50');
        item.querySelector('.lead-checkbox svg').classList.add('hidden');
    });
    
    // Seçili olanı işaretle
    const selectedItem = document.querySelector(`[data-lead-id="${leadId}"]`);
    if (selectedItem) {
        selectedItem.classList.add('border-green-500', 'bg-green-50');
        selectedItem.querySelector('.lead-checkbox svg').classList.remove('hidden');
    }
    
    // Butonu aktif et
    document.getElementById('confirm-send-btn').disabled = false;
}

async function confirmSendLead() {
    if (!selectedRequestId || !selectedLeadId) return;
    
    const btn = document.getElementById('confirm-send-btn');
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    
    try {
        const formData = new FormData();
        formData.append('request_id', selectedRequestId);
        formData.append('lead_id', selectedLeadId);
        formData.append('csrf_token', '<?= generateCsrfToken() ?>');
        
        const response = await fetch('/admin/lead-requests/send', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Lead ID'yi göster
            const leadIdPadded = String(selectedLeadId).padStart(6, '0');
            showToast('success', `✅ Lead #${leadIdPadded} başarıyla gönderildi!`);
            
            // Badge'i güncelle (window.updatePendingRequestsBadge layout.php'de tanımlı)
            if (typeof window.updatePendingRequestsBadge === 'function') {
                // Mevcut badge sayısını al ve 1 azalt
                const badge = document.getElementById('pending-requests-badge');
                if (badge && !badge.classList.contains('hidden')) {
                    const currentCount = parseInt(badge.textContent) || 0;
                    const newCount = Math.max(0, currentCount - 1);
                    window.updatePendingRequestsBadge(newCount);
                }
            }
            
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showToast('error', result.message);
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }
    } catch (error) {
        console.error('Send error:', error);
        showToast('error', 'Bir hata oluştu');
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }
}

function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-2xl border-2 transition-all ${
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
?>


 * Admin Lead İstekleri Sayfası
 * Bekleyen lead isteklerini görüntüleme ve manuel gönderim
 */

$pageTitle = $pageTitle ?? 'Lead İstekleri';
$currentPage = 'lead-requests';
$pendingRequests = $pageData['pendingRequests'] ?? [];
$completedRequests = $pageData['completedRequests'] ?? [];
$availableLeads = $pageData['availableLeads'] ?? [];

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
        <div class="rounded-xl px-4 py-2" style="background-color: rgba(255,255,255,0.2);">
            <div class="text-2xl font-bold" style="color: #ffffff !important;"><?= count($pendingRequests) ?></div>
            <div class="text-xs" style="color: #ffffff !important;">Bekleyen</div>
        </div>
    </div>
</div>

<!-- Bekleyen İstekler -->
<?php if (empty($pendingRequests)): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center mb-6">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">✅ Tüm İstekler İşlendi!</h3>
        <p class="text-gray-600">Şu anda bekleyen lead isteği yok</p>
    </div>
<?php else: ?>
    <!-- Desktop: Tablo Görünümü -->
    <div class="hidden lg:block bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-200 bg-yellow-50">
            <h2 class="text-xl font-bold text-gray-900">⏳ Bekleyen İstekler (<?= count($pendingRequests) ?>)</h2>
            <p class="text-sm text-gray-600 mt-1">Ustalara lead göndermek için listeden seçin</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">İstek #</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Usta Bilgileri</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Paket</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">İstek Tarihi</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">İşlem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($pendingRequests as $request): 
                        $serviceTypes = getServiceTypes();
                        $cities = getCities();
                        $serviceName = $serviceTypes[$request['service_type']]['tr'] ?? $request['service_type'];
                        $cityName = $cities[$request['provider_city']]['tr'] ?? $request['provider_city'];
                    ?>
                        <tr class="hover:bg-yellow-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-900">#<?= $request['id'] ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div class="text-base font-bold text-gray-900"><?= htmlspecialchars($request['provider_name']) ?></div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-100 text-blue-800 text-sm font-bold rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            <?= $serviceName ?>
                                        </span>
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-100 text-purple-800 text-sm font-bold rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <?= $cityName ?>
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($request['provider_email']) ?></div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($request['package_name']) ?></div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-orange-100 text-orange-700 rounded font-semibold">
                                        Kalan: <?= $request['remaining_leads'] ?>
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700"><?= date('d/m/Y', strtotime($request['requested_at'])) ?></div>
                                <div class="text-xs text-gray-500"><?= date('H:i', strtotime($request['requested_at'])) ?></div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button 
                                    onclick="openSendLeadModal(<?= $request['id'] ?>, '<?= htmlspecialchars($request['provider_name'], ENT_QUOTES) ?>', '<?= $request['service_type'] ?>', '<?= $request['provider_city'] ?>')"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    Lead Gönder
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile: Card Görünümü -->
    <div class="lg:hidden space-y-4 mb-6">
        <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-4 mb-4">
            <h2 class="text-lg font-bold text-gray-900">⏳ Bekleyen İstekler (<?= count($pendingRequests) ?>)</h2>
            <p class="text-sm text-gray-600 mt-1">Ustalara lead göndermek için listeden seçin</p>
        </div>
        
        <?php foreach ($pendingRequests as $request): 
            $serviceTypes = getServiceTypes();
            $cities = getCities();
            $serviceName = $serviceTypes[$request['service_type']]['tr'] ?? $request['service_type'];
            $cityName = $cities[$request['provider_city']]['tr'] ?? $request['provider_city'];
        ?>
            <div class="bg-white rounded-xl shadow-md border-2 border-yellow-200 p-4">
                <!-- Header -->
                <div class="flex items-center justify-between mb-3 pb-3 border-b border-gray-200">
                    <span class="text-sm font-bold text-gray-500">İstek #<?= $request['id'] ?></span>
                    <span class="text-xs text-gray-400"><?= date('d.m.Y H:i', strtotime($request['requested_at'])) ?></span>
                </div>
                
                <!-- Usta Bilgileri -->
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-2"><?= htmlspecialchars($request['provider_name']) ?></h3>
                    <div class="flex flex-wrap gap-2 mb-2">
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-100 text-blue-800 text-sm font-bold rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <?= $serviceName ?>
                        </span>
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-100 text-purple-800 text-sm font-bold rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <?= $cityName ?>
                        </span>
                    </div>
                    <div class="text-sm text-gray-500"><?= htmlspecialchars($request['provider_email']) ?></div>
                </div>
                
                <!-- Paket Bilgisi -->
                <div class="bg-gray-50 rounded-lg p-3 mb-4">
                    <div class="text-sm font-medium text-gray-900 mb-1"><?= htmlspecialchars($request['package_name']) ?></div>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-orange-100 text-orange-700 rounded font-semibold">
                            Kalan: <?= $request['remaining_leads'] ?? 0 ?>
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded font-semibold">
                            Bekleyen: <?= $request['pending_requests'] ?? 0 ?>
                        </span>
                    </div>
                </div>
                
                <!-- Lead Gönder Butonu - Tam Genişlik -->
                <button 
                    onclick="openSendLeadModal(<?= $request['id'] ?>, '<?= htmlspecialchars($request['provider_name'], ENT_QUOTES) ?>', '<?= $request['service_type'] ?>', '<?= $request['provider_city'] ?>')"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-colors shadow-lg hover:shadow-xl text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    📤 Lead Gönder
                </button>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Tamamlanmış İstekler -->
<?php if (!empty($completedRequests)): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-green-50">
            <h2 class="text-xl font-bold text-gray-900">✅ Son Tamamlanan İstekler</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">İstek #</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Usta</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Lead #</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Gönderen</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Gönderim Tarihi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach (array_slice($completedRequests, 0, 20) as $request): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">#<?= $request['id'] ?></td>
                            <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($request['provider_name']) ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-extrabold rounded-lg shadow-md" style="background-color: #f97316 !important; color: #ffffff !important;">
                                    🆔 #<?= str_pad($request['lead_id'], 6, '0', STR_PAD_LEFT) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($request['admin_name']) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-700"><?= date('d/m/Y H:i', strtotime($request['completed_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<!-- Modal: Lead Gönder -->
<div id="sendLeadModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-gray-900">📤 Lead Gönder</h3>
                <button onclick="closeSendLeadModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <p class="text-sm text-gray-600 mt-2">Usta: <span id="modal-provider-name" class="font-semibold"></span></p>
        </div>
        
        <div class="p-6">
            <h4 class="text-lg font-bold text-gray-900 mb-4">Uygun Lead'ler:</h4>
            
            <div id="leads-list" class="space-y-3 max-h-96 overflow-y-auto">
                <?php foreach ($availableLeads as $lead): 
                    $serviceTypes = getServiceTypes();
                    $cities = getCities();
                    $serviceName = $serviceTypes[$lead['service_type']]['tr'] ?? $lead['service_type'];
                    $cityName = $cities[$lead['city']]['tr'] ?? $lead['city'];
                ?>
                    <div 
                        data-lead-id="<?= $lead['id'] ?>"
                        data-service-type="<?= $lead['service_type'] ?>"
                        data-city="<?= $lead['city'] ?>"
                        class="lead-item border-2 border-gray-200 rounded-xl p-4 hover:border-green-500 hover:bg-green-50 cursor-pointer transition-all"
                        onclick="selectLead(<?= $lead['id'] ?>)">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 text-base font-extrabold rounded-lg shadow-md" style="background-color: #f97316 !important; color: #ffffff !important;">
                                        🆔 #<?= str_pad($lead['id'], 6, '0', STR_PAD_LEFT) ?>
                                    </span>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded"><?= $serviceName ?></span>
                                    <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded"><?= $cityName ?></span>
                                </div>
                                <p class="text-sm text-gray-700 mb-2"><?= htmlspecialchars(substr($lead['description'], 0, 100)) ?>...</p>
                                <div class="flex items-center gap-4 text-xs text-gray-500">
                                    <span>📱 <?= htmlspecialchars($lead['phone']) ?></span>
                                    <span>📅 <?= date('d/m/Y', strtotime($lead['created_at'])) ?></span>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 border-2 border-gray-300 rounded-full flex items-center justify-center lead-checkbox">
                                    <svg class="w-5 h-5 text-green-600 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (empty($availableLeads)): ?>
                <div class="text-center py-8 text-gray-500">
                    <p>Henüz gönderilmeye hazır lead yok</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="p-6 border-t border-gray-200 bg-gray-50">
            <div class="flex gap-3 justify-end">
                <button onclick="closeSendLeadModal()" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition-colors">
                    İptal
                </button>
                <button id="confirm-send-btn" onclick="confirmSendLead()" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Lead'i Gönder
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let selectedRequestId = null;
let selectedLeadId = null;
let filterServiceType = null;
let filterCity = null;

function openSendLeadModal(requestId, providerName, serviceType, city) {
    selectedRequestId = requestId;
    filterServiceType = serviceType;
    filterCity = city;
    selectedLeadId = null;
    
    document.getElementById('modal-provider-name').textContent = providerName;
    document.getElementById('sendLeadModal').classList.remove('hidden');
    
    // Lead'leri filtrele
    filterLeads();
}

function closeSendLeadModal() {
    document.getElementById('sendLeadModal').classList.add('hidden');
    selectedRequestId = null;
    selectedLeadId = null;
    
    // Seçimleri temizle
    document.querySelectorAll('.lead-item').forEach(item => {
        item.classList.remove('border-green-500', 'bg-green-50', 'hidden');
        item.querySelector('.lead-checkbox svg').classList.add('hidden');
    });
    
    // "Lead yok" mesajını kaldır
    const noLeadsMsg = document.getElementById('no-leads-message');
    if (noLeadsMsg) {
        noLeadsMsg.remove();
    }
}

function filterLeads() {
    let visibleCount = 0;
    
    document.querySelectorAll('.lead-item').forEach(item => {
        const itemService = item.getAttribute('data-service-type');
        const itemCity = item.getAttribute('data-city');
        
        // Case-insensitive comparison (büyük/küçük harf duyarsız)
        const serviceMatch = itemService.toLowerCase() === filterServiceType.toLowerCase();
        const cityMatch = itemCity.toLowerCase() === filterCity.toLowerCase();
        
        if (serviceMatch && cityMatch) {
            item.classList.remove('hidden', 'opacity-50');
            visibleCount++;
        } else {
            item.classList.add('hidden');
        }
    });
    
    // Eğer hiç eşleşen lead yoksa uyarı göster
    const leadsList = document.getElementById('leads-list');
    let noLeadsMsg = document.getElementById('no-leads-message');
    
    if (visibleCount === 0) {
        if (!noLeadsMsg) {
            noLeadsMsg = document.createElement('div');
            noLeadsMsg.id = 'no-leads-message';
            noLeadsMsg.className = 'text-center py-12 bg-yellow-50 border-2 border-yellow-200 rounded-xl';
            noLeadsMsg.innerHTML = `
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-yellow-900 mb-2">⚠️ Uygun Lead Bulunamadı</h4>
                <p class="text-sm text-yellow-700">Bu usta için (${filterServiceType} / ${filterCity}) henüz gönderilmeye hazır lead yok.</p>
                <p class="text-xs text-yellow-600 mt-2">Yeni lead geldiğinde burada listelenecektir.</p>
            `;
            leadsList.appendChild(noLeadsMsg);
        }
    } else {
        if (noLeadsMsg) {
            noLeadsMsg.remove();
        }
    }
}

function selectLead(leadId) {
    selectedLeadId = leadId;
    
    // Tüm seçimleri temizle
    document.querySelectorAll('.lead-item').forEach(item => {
        item.classList.remove('border-green-500', 'bg-green-50');
        item.querySelector('.lead-checkbox svg').classList.add('hidden');
    });
    
    // Seçili olanı işaretle
    const selectedItem = document.querySelector(`[data-lead-id="${leadId}"]`);
    if (selectedItem) {
        selectedItem.classList.add('border-green-500', 'bg-green-50');
        selectedItem.querySelector('.lead-checkbox svg').classList.remove('hidden');
    }
    
    // Butonu aktif et
    document.getElementById('confirm-send-btn').disabled = false;
}

async function confirmSendLead() {
    if (!selectedRequestId || !selectedLeadId) return;
    
    const btn = document.getElementById('confirm-send-btn');
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    
    try {
        const formData = new FormData();
        formData.append('request_id', selectedRequestId);
        formData.append('lead_id', selectedLeadId);
        formData.append('csrf_token', '<?= generateCsrfToken() ?>');
        
        const response = await fetch('/admin/lead-requests/send', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Lead ID'yi göster
            const leadIdPadded = String(selectedLeadId).padStart(6, '0');
            showToast('success', `✅ Lead #${leadIdPadded} başarıyla gönderildi!`);
            
            // Badge'i güncelle (window.updatePendingRequestsBadge layout.php'de tanımlı)
            if (typeof window.updatePendingRequestsBadge === 'function') {
                // Mevcut badge sayısını al ve 1 azalt
                const badge = document.getElementById('pending-requests-badge');
                if (badge && !badge.classList.contains('hidden')) {
                    const currentCount = parseInt(badge.textContent) || 0;
                    const newCount = Math.max(0, currentCount - 1);
                    window.updatePendingRequestsBadge(newCount);
                }
            }
            
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showToast('error', result.message);
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }
    } catch (error) {
        console.error('Send error:', error);
        showToast('error', 'Bir hata oluştu');
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }
}

function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-2xl border-2 transition-all ${
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
?>



