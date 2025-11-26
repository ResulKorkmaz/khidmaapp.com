<?php
// Admin layout'u başlat - içeriği ob_start ile yakala
ob_start();

$serviceTypes = getServiceTypes();
?>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <?php
    $totalPurchases = count($purchases);
    $totalRevenue = array_sum(array_column($purchases, 'price_paid'));
    $totalLeadsSold = array_sum(array_column($purchases, 'leads_count'));
    $totalDelivered = array_sum(array_column($purchases, 'delivered_count'));
    ?>
    
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Toplam Satış</p>
                <p class="text-2xl font-bold text-gray-900 mt-1"><?= $totalPurchases ?></p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Toplam Gelir</p>
                <p class="text-2xl font-bold text-green-600 mt-1"><?= number_format($totalRevenue, 0) ?> SR</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Satılan Lead</p>
                <p class="text-2xl font-bold text-gray-900 mt-1"><?= $totalLeadsSold ?></p>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Teslim Edilen</p>
                <p class="text-2xl font-bold text-green-600 mt-1"><?= $totalDelivered ?></p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Purchases Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-xl font-bold text-gray-900">Satın Alımlar</h2>
        <p class="text-sm text-gray-500 mt-1">Tüm usta satın alımları ve lead teslim durumları</p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Usta</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Paket</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Toplam</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Teslim</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Bekleyen</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Fiyat</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tarih</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($purchases)): ?>
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <p class="text-gray-500 font-medium text-lg">Henüz satın alım yok</p>
                                <p class="text-gray-400 text-sm mt-1">Ustalar paket satın aldığında burada görünecek</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($purchases as $purchase): 
                        $deliveredCount = intval($purchase['delivered_count']);
                        $totalLeads = intval($purchase['leads_count']);
                        $pendingCount = $totalLeads - $deliveredCount;
                        $percentage = $totalLeads > 0 ? round(($deliveredCount / $totalLeads) * 100) : 0;
                        
                        $serviceName = $serviceTypes[$purchase['service_type']]['tr'] ?? $purchase['service_type'];
                    ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-bold text-gray-900">#<?= str_pad($purchase['id'], 4, '0', STR_PAD_LEFT) ?></span>
                            </td>
                            
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-gray-900"><?= htmlspecialchars($purchase['provider_name']) ?></p>
                                    <p class="text-xs text-gray-500"><?= htmlspecialchars($purchase['provider_phone']) ?></p>
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-semibold rounded">
                                        <?= htmlspecialchars($serviceName) ?>
                                    </span>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-900"><?= htmlspecialchars($purchase['package_name_tr']) ?></span>
                            </td>
                            
                            <td class="px-6 py-4">
                                <span class="text-lg font-bold text-gray-900"><?= $totalLeads ?></span>
                            </td>
                            
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-100 text-green-800 text-sm font-bold rounded-full">
                                    ✓ <?= $deliveredCount ?>
                                </span>
                            </td>
                            
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-yellow-100 text-yellow-800 text-sm font-bold rounded-full">
                                    ⏳ <?= $pendingCount ?>
                                </span>
                            </td>
                            
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-900"><?= number_format($purchase['price_paid'], 0) ?> SR</span>
                            </td>
                            
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900"><?= date('d.m.Y', strtotime($purchase['purchased_at'])) ?></p>
                                <p class="text-xs text-gray-500"><?= date('H:i', strtotime($purchase['purchased_at'])) ?></p>
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <!-- Detay Butonu -->
                                    <a href="/admin/purchases/detail?id=<?= $purchase['id'] ?>" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-50 text-gray-700 hover:bg-gray-100 border border-gray-200 rounded-lg text-xs font-semibold transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detay
                                    </a>
                                    
                                    <?php if ($pendingCount > 0): ?>
                                        <button onclick="openSendLeadModal(<?= $purchase['id'] ?>, <?= $purchase['provider_id'] ?>, '<?= htmlspecialchars($purchase['provider_name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($purchase['service_type']) ?>', '<?= htmlspecialchars($purchase['city'] ?? '') ?>', <?= $pendingCount ?>)" 
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 rounded-lg text-xs font-semibold transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                            </svg>
                                            Gönder (<?= $pendingCount ?>)
                                        </button>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-100 text-green-800 rounded-lg text-xs font-semibold">
                                            ✅ Tamamlandı
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php 
                                    $refundStatus = $purchase['refund_status'] ?? 'none';
                                    if ($refundStatus === 'full'): 
                                    ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">
                                            İade Edildi
                                        </span>
                                    <?php elseif ($refundStatus === 'partial'): ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-orange-100 text-orange-700 rounded text-xs font-semibold">
                                            Kısmi İade
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Send Lead Modal -->
<div id="sendLeadModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden animate-scale-in">
        <!-- Modal Header -->
        <div class="bg-blue-600 px-6 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Lead Gönder</h3>
                        <p class="text-blue-100 text-sm" id="modalProviderName">Usta: -</p>
                    </div>
                </div>
                <button onclick="closeSendLeadModal()" class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
            <!-- Loading State -->
            <div id="leadsLoading" class="flex items-center justify-center py-12">
                <div class="text-center">
                    <svg class="animate-spin h-12 w-12 text-blue-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-600 font-medium">Lead'ler yükleniyor...</p>
                </div>
            </div>

            <!-- Leads List -->
            <div id="leadsList" class="hidden space-y-3">
                <!-- Leads will be dynamically inserted here -->
            </div>

            <!-- Empty State -->
            <div id="leadsEmpty" class="hidden text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500 font-medium text-lg">Uygun lead bulunamadı</p>
                <p class="text-gray-400 text-sm mt-1">Bu hizmet türü için gönderilecek lead yok</p>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <button onclick="closeSendLeadModal()" 
                    class="w-full px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition-colors">
                Kapat
            </button>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 left-4 z-[100] space-y-3"></div>

<style>
@keyframes scale-in {
    from {
        transform: scale(0.95);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

.animate-scale-in {
    animation: scale-in 0.2s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
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
        transform: translateX(100%);
        opacity: 0;
    }
}
</style>

<script>
const csrfToken = '<?= generateCsrfToken() ?>';
const serviceTypes = <?= json_encode(array_map(fn($s) => $s['tr'], getServiceTypes())) ?>;
const citiesAr = <?= json_encode(array_map(fn($c) => $c['ar'], getCities())) ?>;

let currentPurchaseId = null;
let currentProviderId = null;
let currentServiceType = null;
let currentCity = null;

function openSendLeadModal(purchaseId, providerId, providerName, serviceType, city, pendingCount) {
    currentPurchaseId = purchaseId;
    currentProviderId = providerId;
    currentServiceType = serviceType;
    currentCity = city;
    
    console.log('🔍 Opening modal with:', {
        purchaseId,
        providerId,
        providerName,
        serviceType,
        city,
        pendingCount
    });
    
    document.getElementById('modalProviderName').textContent = `Usta: ${providerName} (${city}) - (${pendingCount} lead bekliyor)`;
    document.getElementById('sendLeadModal').classList.remove('hidden');
    document.getElementById('leadsLoading').classList.remove('hidden');
    document.getElementById('leadsList').classList.add('hidden');
    document.getElementById('leadsEmpty').classList.add('hidden');
    
    // Fetch available leads for this service type and city
    fetchAvailableLeads(serviceType, city);
}

function closeSendLeadModal() {
    document.getElementById('sendLeadModal').classList.add('hidden');
    currentPurchaseId = null;
    currentProviderId = null;
    currentServiceType = null;
    currentCity = null;
}

async function fetchAvailableLeads(serviceType, city) {
    try {
        console.log('Fetching leads for service type:', serviceType, 'City:', city);
        const response = await fetch(`/admin/leads/get-available-providers?service_type=${serviceType}&city=${encodeURIComponent(city)}`);
        const data = await response.json();
        
        console.log('Response data:', data);
        
        if (data.success && data.leads && data.leads.length > 0) {
            console.log('Found leads:', data.leads.length);
            displayLeads(data.leads);
        } else {
            console.log('No leads found or error');
            showEmptyState();
        }
    } catch (error) {
        console.error('Error fetching leads:', error);
        showToast('Lead\'ler yüklenirken hata oluştu', 'error');
        showEmptyState();
    }
}

function displayLeads(leads) {
    document.getElementById('leadsLoading').classList.add('hidden');
    document.getElementById('leadsList').classList.remove('hidden');
    
    const leadsList = document.getElementById('leadsList');
    leadsList.innerHTML = leads.map(lead => `
        <div class="bg-white border-2 border-gray-200 rounded-xl p-4 hover:border-blue-300 hover:shadow-md transition-all">
            <div class="flex items-start justify-between gap-4">
                <!-- Lead Info -->
                <div class="flex-1 grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Hizmet</p>
                        <p class="font-semibold text-gray-900">${serviceTypes[lead.service_type] || lead.service_type}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Şehir</p>
                        <p class="font-semibold text-gray-900">${citiesAr[lead.city] || lead.city}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Telefon</p>
                        <p class="font-semibold text-gray-900 direction-ltr">${lead.phone}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tarih</p>
                        <p class="font-semibold text-gray-900">${new Date(lead.created_at).toLocaleDateString('tr-TR')}</p>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col gap-2">
                    <button onclick="sendLeadViaSystem(${lead.id})" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-sm transition-colors whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Sistem ile Gönder
                    </button>
                    <button onclick="markAsSentViaWhatsApp(${lead.id})" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg text-sm transition-colors whitespace-nowrap">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                        </svg>
                        WhatsApp'tan Gönderdim
                    </button>
                </div>
            </div>
            
            ${lead.description ? `
            <div class="mt-3 pt-3 border-t border-gray-200">
                <p class="text-xs text-gray-500 mb-1">Açıklama</p>
                <p class="text-sm text-gray-700 line-clamp-2">${lead.description}</p>
            </div>
            ` : ''}
        </div>
    `).join('');
}

function showEmptyState() {
    document.getElementById('leadsLoading').classList.add('hidden');
    document.getElementById('leadsEmpty').classList.remove('hidden');
}

async function sendLeadViaSystem(leadId) {
    if (!confirm('Bu lead\'i sistem üzerinden gönder?\n\nUsta panosunda otomatik olarak görünecek.')) {
        return;
    }
    
    const toastId = showToast('Lead gönderiliyor...', 'info', 10000);
    
    try {
        const response = await fetch('/admin/leads/send-to-provider', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                csrf_token: csrfToken,
                purchase_id: currentPurchaseId,
                provider_id: currentProviderId,
                lead_id: leadId
            })
        });
        
        const data = await response.json();
        
        removeToast(toastId);
        
        if (data.success) {
            showToast('✅ Lead başarıyla gönderildi!', 'success');
            
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
            
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showToast('❌ ' + (data.error || 'Lead gönderilemedi'), 'error');
        }
    } catch (error) {
        removeToast(toastId);
        console.error('Error sending lead:', error);
        showToast('❌ Hata oluştu', 'error');
    }
}

async function markAsSentViaWhatsApp(leadId) {
    if (!confirm('Bu lead\'i WhatsApp\'tan gönderdin mi?\n\nManuel olarak işaretlenecek.')) {
        return;
    }
    
    const toastId = showToast('Kaydediliyor...', 'info', 10000);
    
    try {
        const response = await fetch('/admin/leads/mark-sent-via-whatsapp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                csrf_token: csrfToken,
                purchase_id: currentPurchaseId,
                provider_id: currentProviderId,
                lead_id: leadId
            })
        });
        
        const data = await response.json();
        
        removeToast(toastId);
        
        if (data.success) {
            showToast('✅ WhatsApp gönderimi kaydedildi!', 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showToast('❌ ' + (data.error || 'Kaydedilemedi'), 'error');
        }
    } catch (error) {
        removeToast(toastId);
        console.error('Error marking as sent:', error);
        showToast('❌ Hata oluştu', 'error');
    }
}

// Toast Notification System
let toastCounter = 0;

function showToast(message, type = 'success', duration = 4000) {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    const toastId = ++toastCounter;
    
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
    
    toast.id = `toast-${toastId}`;
    toast.className = `${colors[type]} border-l-4 rounded-lg shadow-lg p-4 flex items-start gap-3 min-w-[320px] max-w-md`;
    toast.style.animation = 'slideInRight 0.3s ease-out';
    toast.innerHTML = `
        <div class="flex-shrink-0">${icons[type]}</div>
        <div class="flex-1">
            <p class="text-sm font-semibold text-gray-900">${message}</p>
        </div>
        <button onclick="removeToast(${toastId})" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    `;
    
    container.appendChild(toast);
    
    if (duration > 0) {
        setTimeout(() => {
            removeToast(toastId);
        }, duration);
    }
    
    return toastId;
}

function removeToast(toastId) {
    const toast = document.getElementById(`toast-${toastId}`);
    if (toast) {
        toast.style.animation = 'slideOutRight 0.3s ease-in';
        setTimeout(() => toast.remove(), 300);
    }
}
</script>

<?php
// İçeriği al
$content = ob_get_clean();

// Layout'u yükle
require __DIR__ . '/layout.php';
