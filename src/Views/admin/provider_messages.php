<?php
/**
 * Admin - Provider Messages
 * Modern, minimal ve mobil uyumlu tasarım
 */

$pageTitle = 'Provider Mesajları';
$currentPage = 'provider-messages';
ob_start();

$serviceTypes = getServiceTypes();
$cities = getCities();

$warningMessage = $_SESSION['warning_message'] ?? null;
if ($warningMessage) {
    unset($_SESSION['warning_message']);
}

$filters = $filters ?? ['city' => '', 'service' => '', 'status' => '', 'search' => ''];
$cityCounts = $cityCounts ?? [];
$serviceCounts = $serviceCounts ?? [];
$activeProviderCount = $activeProviderCount ?? 0;
$availableLeads = $availableLeads ?? [];
?>

<div class="container mx-auto px-4 py-4">
    <?php if ($warningMessage): ?>
    <div class="bg-amber-50 border border-amber-300 text-amber-800 px-4 py-3 rounded-lg mb-4 flex items-center gap-2 text-sm">
        <span>⚠️</span>
        <span><?= htmlspecialchars($warningMessage) ?></span>
    </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="bg-purple-600 rounded-xl p-4 mb-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="text-2xl">💬</span>
                <div>
                    <h1 class="text-lg font-bold text-white">Provider Mesajları</h1>
                    <p class="text-purple-200 text-xs">Toplu veya tekli mesaj gönder</p>
                </div>
            </div>
            <div class="flex gap-2 flex-wrap">
                <button onclick="openBulkMessageModal('all')" class="bg-white text-purple-600 hover:bg-purple-50 font-semibold px-3 py-1.5 rounded-lg text-xs flex items-center gap-1.5 transition-colors">
                    <span>📢</span><span>Tümüne</span>
                </button>
                <button onclick="openBulkMessageModal('city')" class="bg-white text-purple-600 hover:bg-purple-50 font-semibold px-3 py-1.5 rounded-lg text-xs flex items-center gap-1.5 transition-colors">
                    <span>🏙️</span><span>Şehir</span>
                </button>
                <button onclick="openBulkMessageModal('service')" class="bg-white text-purple-600 hover:bg-purple-50 font-semibold px-3 py-1.5 rounded-lg text-xs flex items-center gap-1.5 transition-colors">
                    <span>🔧</span><span>Sektör</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats - Tek satır -->
    <div class="bg-white rounded-lg border border-gray-200 p-3 mb-4 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="text-lg font-bold text-gray-900"><?= $totalProviders ?? 0 ?></span>
            <span class="text-xs text-gray-500">Usta</span>
        </div>
        <div class="w-px h-6 bg-gray-200"></div>
        <div class="flex items-center gap-2">
            <span class="text-lg font-bold text-green-600"><?= $activeProviderCount ?></span>
            <span class="text-xs text-gray-500">Aktif</span>
        </div>
        <div class="w-px h-6 bg-gray-200"></div>
        <div class="flex items-center gap-2">
            <span class="text-lg font-bold text-blue-600"><?= $stats['total_messages'] ?? 0 ?></span>
            <span class="text-xs text-gray-500">Mesaj</span>
        </div>
        <div class="w-px h-6 bg-gray-200"></div>
        <div class="flex items-center gap-2">
            <span class="text-lg font-bold text-red-600"><?= $stats['unread_messages'] ?? 0 ?></span>
            <span class="text-xs text-gray-500">Okunmamış</span>
        </div>
    </div>

    <!-- Compact Filters -->
    <div class="bg-white rounded-lg border border-gray-100 p-3 mb-4">
        <form method="GET" id="filterForm" class="flex flex-col sm:flex-row gap-2">
            <input type="text" name="search" id="searchInput" value="<?= htmlspecialchars($filters['search']) ?>" 
                   class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                   placeholder="🔍 İsim, email, telefon..." autocomplete="off">
            <select name="city" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500">
                <option value="">Tüm Şehirler</option>
                <?php foreach ($cities as $key => $city): ?>
                    <option value="<?= $key ?>" <?= $filters['city'] === $key ? 'selected' : '' ?>><?= htmlspecialchars($city['tr']) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="service" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500">
                <option value="">Tüm Hizmetler</option>
                <?php foreach ($serviceTypes as $key => $service): ?>
                    <option value="<?= $key ?>" <?= $filters['service'] === $key ? 'selected' : '' ?>><?= $service['icon'] ?? '🔧' ?> <?= htmlspecialchars($service['tr']) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500">
                <option value="">Tüm Durumlar</option>
                <option value="active" <?= $filters['status'] === 'active' ? 'selected' : '' ?>>Aktif</option>
                <option value="pending" <?= $filters['status'] === 'pending' ? 'selected' : '' ?>>Beklemede</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg text-sm transition-colors">
                Filtrele
            </button>
        </form>
    </div>

    <!-- Provider List -->
    <?php if (empty($providers)): ?>
        <div class="bg-white rounded-lg border border-gray-100 p-8 text-center">
            <span class="text-4xl mb-2 block">📭</span>
            <p class="text-gray-500 text-sm">Provider bulunamadı</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg border border-gray-100 overflow-hidden mb-4">
            <!-- List Items -->
            <div class="divide-y divide-gray-100">
                <?php foreach ($providers as $provider): ?>
                    <?php 
                    $serviceInfo = $serviceTypes[$provider['service_type']] ?? ['tr' => $provider['service_type'], 'icon' => '🔧'];
                    $cityInfo = $cities[$provider['city']] ?? ['tr' => $provider['city']];
                    ?>
                    <div class="p-3 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <!-- Avatar -->
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                <?= strtoupper(substr($provider['name'], 0, 2)) ?>
                            </div>
                            
                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="font-semibold text-gray-900 text-sm"><?= htmlspecialchars($provider['name']) ?></h3>
                                    <span class="px-1.5 py-0.5 rounded text-xs font-medium <?= $provider['status'] === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                                        <?= $provider['status'] === 'active' ? 'Aktif' : 'Beklemede' ?>
                                    </span>
                                    <?php if ($provider['unread_count'] > 0): ?>
                                        <span class="px-1.5 py-0.5 bg-red-500 text-white rounded text-xs font-medium"><?= $provider['unread_count'] ?> yeni</span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center gap-3 text-xs text-gray-500 mt-0.5">
                                    <span><?= htmlspecialchars($provider['email']) ?></span>
                                    <span>•</span>
                                    <span><?= htmlspecialchars($provider['phone']) ?></span>
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-1.5 py-0.5 bg-blue-50 text-blue-700 rounded text-xs"><?= $serviceInfo['icon'] ?? '🔧' ?> <?= htmlspecialchars($serviceInfo['tr']) ?></span>
                                    <span class="px-1.5 py-0.5 bg-orange-50 text-orange-700 rounded text-xs">📍 <?= htmlspecialchars($cityInfo['tr']) ?></span>
                                    <span class="text-xs text-gray-400"><?= $provider['message_count'] ?> mesaj</span>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex items-center gap-1 flex-shrink-0">
                                <button onclick="openSendMessageModal(<?= $provider['id'] ?>, '<?= htmlspecialchars($provider['name'], ENT_QUOTES) ?>')" 
                                        class="p-2 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg transition-colors" title="Mesaj Gönder">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                </button>
                                <button onclick="openSendLeadModal(<?= $provider['id'] ?>, '<?= htmlspecialchars($provider['name'], ENT_QUOTES) ?>', '<?= $provider['service_type'] ?>', '<?= $provider['city'] ?>')" 
                                        class="p-2 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition-colors" title="Lead Gönder">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    </svg>
                                </button>
                                <button onclick="viewMessageHistory(<?= $provider['id'] ?>, '<?= htmlspecialchars($provider['name'], ENT_QUOTES) ?>')" 
                                        class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors" title="Mesaj Geçmişi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Pagination -->
        <?php if (isset($totalPages) && $totalPages > 1): ?>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500"><?= $totalProviders ?> provider | Sayfa <?= $page ?>/<?= $totalPages ?></span>
                <div class="flex gap-2">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>&<?= http_build_query($filters) ?>" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-lg text-gray-700 font-medium">← Önceki</a>
                    <?php endif; ?>
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?= $page + 1 ?>&<?= http_build_query($filters) ?>" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 rounded-lg text-white font-medium">Sonraki →</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Single Message Modal -->
<div id="sendMessageModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">📤</span>
                <span>Mesaj Gönder</span>
            </h3>
            <button onclick="closeModal('sendMessageModal')" class="p-1 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="sendMessageForm" class="p-4 space-y-3">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="provider_id" id="modal_provider_id">
            
            <div class="bg-purple-50 rounded-lg p-2.5">
                <p class="text-sm text-purple-700"><strong>Alıcı:</strong> <span id="modal_provider_name"></span></p>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Mesaj Tipi</label>
                    <select name="message_type" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                        <option value="info">Bilgilendirme</option>
                        <option value="notification">Bildirim</option>
                        <option value="announcement">Duyuru</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Öncelik</label>
                    <select name="priority" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                        <option value="normal">Normal</option>
                        <option value="high">Yüksek</option>
                        <option value="urgent">Acil</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Konu</label>
                <input type="text" name="subject" required placeholder="Mesaj konusu"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Mesaj</label>
                <textarea name="message" rows="4" required placeholder="Mesajınızı yazın..."
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm"></textarea>
            </div>
            
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeModal('sendMessageModal')" 
                        class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg text-sm">
                    İptal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg text-sm">
                    Gönder
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Message Modal -->
<div id="bulkMessageModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">📢</span>
                <span id="bulkModalTitle">Toplu Mesaj</span>
            </h3>
            <button onclick="closeModal('bulkMessageModal')" class="p-1 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="bulkMessageForm" class="p-4 space-y-3">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="target_type" id="bulk_target_type" value="all">
            
            <div id="targetSelectionContainer" class="hidden">
                <label class="block text-xs font-medium text-gray-600 mb-1" id="targetSelectionLabel">Hedef Seçin</label>
                <select name="target_value" id="bulk_target_value" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    <option value="">Seçiniz...</option>
                </select>
            </div>
            
            <div class="bg-green-50 rounded-lg p-2.5" id="bulkTargetInfo">
                <p class="text-sm text-green-700"><strong>Hedef:</strong> <span id="bulk_target_text">Tüm Ustalar</span></p>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Mesaj Tipi</label>
                    <select name="message_type" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                        <option value="announcement">Duyuru</option>
                        <option value="notification">Bildirim</option>
                        <option value="info">Bilgilendirme</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Öncelik</label>
                    <select name="priority" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                        <option value="normal">Normal</option>
                        <option value="high">Yüksek</option>
                        <option value="urgent">Acil</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Konu</label>
                <input type="text" name="subject" required placeholder="Duyuru konusu"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Mesaj</label>
                <textarea name="message" rows="4" required placeholder="Duyuru mesajınızı yazın..."
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm"></textarea>
            </div>
            
            <div class="bg-amber-50 rounded-lg p-2.5">
                <p class="text-xs text-amber-700">⚠️ Bu mesaj seçilen tüm ustalara gönderilecektir.</p>
            </div>
            
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeModal('bulkMessageModal')" 
                        class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg text-sm">
                    İptal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg text-sm">
                    Gönder
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Send Lead Modal -->
<div id="sendLeadModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">🎯</span>
                <span>Lead Gönder</span>
            </h3>
            <button onclick="closeModal('sendLeadModal')" class="p-1 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="sendLeadForm" class="p-4 space-y-3">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="provider_id" id="lead_provider_id">
            
            <div class="bg-emerald-50 rounded-lg p-2.5">
                <p class="text-sm text-emerald-700"><strong>Alıcı:</strong> <span id="lead_provider_name"></span></p>
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Uygun Lead Seç</label>
                <select name="lead_id" id="lead_select" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    <option value="">Lead seçiniz...</option>
                </select>
                <p class="text-xs text-gray-400 mt-1" id="lead_count_info"></p>
            </div>
            
            <div id="leadDetails" class="hidden bg-gray-50 rounded-lg p-3 space-y-1 text-sm">
                <div id="leadDetailsContent"></div>
            </div>
            
            <div class="bg-blue-50 rounded-lg p-2.5">
                <p class="text-xs text-blue-700">💡 Lead seçtiğinizde ustaya otomatik Arapça mesaj gönderilir.</p>
            </div>
            
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeModal('sendLeadModal')" 
                        class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg text-sm">
                    İptal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg text-sm">
                    Gönder
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Message History Modal -->
<div id="messageHistoryModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <span class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">📜</span>
                <span>Mesaj Geçmişi</span>
            </h3>
            <button onclick="closeModal('messageHistoryModal')" class="p-1 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-4">
            <div class="bg-gray-50 rounded-lg p-2.5 mb-3">
                <p class="text-sm text-gray-700"><strong>Provider:</strong> <span id="history_provider_name"></span></p>
            </div>
            <div id="messageHistoryContent">
                <div class="text-center py-6">
                    <div class="animate-spin w-6 h-6 border-2 border-purple-600 border-t-transparent rounded-full mx-auto"></div>
                    <p class="text-gray-500 text-sm mt-2">Yükleniyor...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const csrfToken = '<?= generateCsrfToken() ?>';
const serviceTypes = <?= json_encode($serviceTypes) ?>;
const cities = <?= json_encode($cities) ?>;
const availableLeads = <?= json_encode($availableLeads) ?>;

// Debounce
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func(...args), wait);
    };
}

// Auto-search
const searchInput = document.getElementById('searchInput');
const filterForm = document.getElementById('filterForm');

searchInput.addEventListener('keypress', e => { if (e.key === 'Enter') { e.preventDefault(); filterForm.submit(); }});
const autoSearch = debounce(() => { if (searchInput.value.length >= 2 || searchInput.value.length === 0) filterForm.submit(); }, 800);
searchInput.addEventListener('input', autoSearch);
document.querySelectorAll('#filterForm select').forEach(s => s.addEventListener('change', () => filterForm.submit()));

// Modal functions
function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.body.style.overflow = 'auto'; }
function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.body.style.overflow = 'hidden'; }

// Single Message
function openSendMessageModal(providerId, providerName) {
    document.getElementById('modal_provider_id').value = providerId;
    document.getElementById('modal_provider_name').textContent = providerName;
    document.getElementById('sendMessageForm').reset();
    document.getElementById('modal_provider_id').value = providerId;
    openModal('sendMessageModal');
}

document.getElementById('sendMessageForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true; btn.textContent = 'Gönderiliyor...';
    try {
        const res = await fetch('/admin/provider-messages/send', { method: 'POST', body: new FormData(this) });
        const data = await res.json();
        if (data.success) { alert('✅ ' + data.message); closeModal('sendMessageModal'); location.reload(); }
        else alert('❌ ' + data.message);
    } catch (e) { alert('❌ Hata: ' + e.message); }
    finally { btn.disabled = false; btn.textContent = 'Gönder'; }
});

// Bulk Message
function openBulkMessageModal(targetType) {
    document.getElementById('bulk_target_type').value = targetType;
    document.getElementById('bulkMessageForm').reset();
    document.getElementById('bulk_target_type').value = targetType;
    
    const container = document.getElementById('targetSelectionContainer');
    const select = document.getElementById('bulk_target_value');
    const label = document.getElementById('targetSelectionLabel');
    const targetText = document.getElementById('bulk_target_text');
    
    select.innerHTML = '<option value="">Seçiniz...</option>';
    
    if (targetType === 'all') {
        container.classList.add('hidden');
        document.getElementById('bulkModalTitle').textContent = 'Tüm Ustalara';
        targetText.textContent = 'Tüm Ustalar (<?= $activeProviderCount ?> kişi)';
    } else if (targetType === 'city') {
        container.classList.remove('hidden');
        document.getElementById('bulkModalTitle').textContent = 'Şehre Göre';
        label.textContent = 'Şehir Seçin';
        targetText.textContent = 'Şehir seçilmedi';
        Object.keys(cities).forEach(k => { const o = document.createElement('option'); o.value = k; o.textContent = cities[k].tr; select.appendChild(o); });
    } else if (targetType === 'service') {
        container.classList.remove('hidden');
        document.getElementById('bulkModalTitle').textContent = 'Sektöre Göre';
        label.textContent = 'Hizmet Seçin';
        targetText.textContent = 'Hizmet seçilmedi';
        Object.keys(serviceTypes).forEach(k => { const o = document.createElement('option'); o.value = k; o.textContent = (serviceTypes[k].icon || '🔧') + ' ' + serviceTypes[k].tr; select.appendChild(o); });
    }
    openModal('bulkMessageModal');
}

document.getElementById('bulk_target_value').addEventListener('change', function() {
    const type = document.getElementById('bulk_target_type').value;
    const text = document.getElementById('bulk_target_text');
    if (this.value) text.textContent = type === 'city' ? cities[this.value]?.tr : (serviceTypes[this.value]?.icon || '🔧') + ' ' + serviceTypes[this.value]?.tr;
});

document.getElementById('bulkMessageForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const type = document.getElementById('bulk_target_type').value;
    const value = document.getElementById('bulk_target_value').value;
    if (type !== 'all' && !value) { alert('❌ Hedef seçin'); return; }
    if (!confirm('Bu mesaj seçilen tüm ustalara gönderilecek. Devam?')) return;
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true; btn.textContent = 'Gönderiliyor...';
    try {
        const res = await fetch('/admin/provider-messages/send-bulk', { method: 'POST', body: new FormData(this) });
        const data = await res.json();
        if (data.success) { alert('✅ ' + data.message); closeModal('bulkMessageModal'); location.reload(); }
        else alert('❌ ' + data.message);
    } catch (e) { alert('❌ Hata: ' + e.message); }
    finally { btn.disabled = false; btn.textContent = 'Gönder'; }
});

// Send Lead
function openSendLeadModal(providerId, providerName, serviceType, city) {
    document.getElementById('lead_provider_id').value = providerId;
    document.getElementById('lead_provider_name').textContent = providerName;
    document.getElementById('sendLeadForm').reset();
    document.getElementById('lead_provider_id').value = providerId;
    document.getElementById('leadDetails').classList.add('hidden');
    
    const select = document.getElementById('lead_select');
    select.innerHTML = '<option value="">Lead seçiniz...</option>';
    
    const matching = availableLeads.filter(l => l.service_type === serviceType && l.city === city);
    const others = availableLeads.filter(l => l.service_type !== serviceType || l.city !== city);
    
    if (matching.length > 0) {
        const g = document.createElement('optgroup'); g.label = 'Eşleşen (' + matching.length + ')';
        matching.forEach(l => { const o = document.createElement('option'); o.value = l.id; o.textContent = '#' + l.id + ' - ' + (serviceTypes[l.service_type]?.tr || l.service_type) + ' - ' + (cities[l.city]?.tr || l.city); o.dataset.lead = JSON.stringify(l); g.appendChild(o); });
        select.appendChild(g);
    }
    if (others.length > 0) {
        const g = document.createElement('optgroup'); g.label = 'Diğer (' + others.length + ')';
        others.forEach(l => { const o = document.createElement('option'); o.value = l.id; o.textContent = '#' + l.id + ' - ' + (serviceTypes[l.service_type]?.tr || l.service_type) + ' - ' + (cities[l.city]?.tr || l.city); o.dataset.lead = JSON.stringify(l); g.appendChild(o); });
        select.appendChild(g);
    }
    document.getElementById('lead_count_info').textContent = matching.length + ' eşleşen, ' + others.length + ' diğer';
    openModal('sendLeadModal');
}

document.getElementById('lead_select').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const div = document.getElementById('leadDetails');
    if (opt.dataset.lead) {
        const l = JSON.parse(opt.dataset.lead);
        document.getElementById('leadDetailsContent').innerHTML = '<p><strong>Hizmet:</strong> ' + (serviceTypes[l.service_type]?.tr || l.service_type) + '</p><p><strong>Şehir:</strong> ' + (cities[l.city]?.tr || l.city) + '</p><p><strong>Tel:</strong> ' + l.phone + '</p>' + (l.description ? '<p><strong>Açıklama:</strong> ' + l.description + '</p>' : '');
        div.classList.remove('hidden');
    } else div.classList.add('hidden');
});

document.getElementById('sendLeadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    if (!document.getElementById('lead_select').value) { alert('❌ Lead seçin'); return; }
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true; btn.textContent = 'Gönderiliyor...';
    try {
        const res = await fetch('/admin/provider-messages/send-lead', { method: 'POST', body: new FormData(this) });
        const data = await res.json();
        if (data.success) { alert('✅ ' + data.message); closeModal('sendLeadModal'); location.reload(); }
        else alert('❌ ' + data.message);
    } catch (e) { alert('❌ Hata: ' + e.message); }
    finally { btn.disabled = false; btn.textContent = 'Gönder'; }
});

// Message History
async function viewMessageHistory(providerId, providerName) {
    document.getElementById('history_provider_name').textContent = providerName;
    openModal('messageHistoryModal');
    const content = document.getElementById('messageHistoryContent');
    content.innerHTML = '<div class="text-center py-6"><div class="animate-spin w-6 h-6 border-2 border-purple-600 border-t-transparent rounded-full mx-auto"></div></div>';
    try {
        const res = await fetch('/admin/provider-messages/history?provider_id=' + providerId);
        const data = await res.json();
        if (data.success && data.messages.length > 0) {
            let html = '<div class="space-y-2">';
            data.messages.forEach(m => {
                const read = m.is_read == 1;
                html += '<div class="border ' + (read ? 'border-gray-200' : 'border-purple-300 bg-purple-50') + ' rounded-lg p-3">';
                html += '<div class="flex gap-2 mb-1 flex-wrap">';
                html += '<span class="px-1.5 py-0.5 bg-gray-100 text-gray-700 rounded text-xs">' + m.message_type + '</span>';
                if (!read) html += '<span class="px-1.5 py-0.5 bg-purple-600 text-white rounded text-xs">Okunmadı</span>';
                html += '</div>';
                html += '<h4 class="font-semibold text-gray-900 text-sm">' + escapeHtml(m.subject) + '</h4>';
                html += '<p class="text-xs text-gray-600 mt-1 whitespace-pre-wrap">' + escapeHtml(m.message) + '</p>';
                html += '<p class="text-xs text-gray-400 mt-2">' + new Date(m.created_at).toLocaleString('tr-TR') + '</p>';
                html += '</div>';
            });
            html += '</div>';
            content.innerHTML = html;
        } else content.innerHTML = '<div class="text-center py-6"><span class="text-3xl">📭</span><p class="text-gray-500 text-sm mt-2">Mesaj yok</p></div>';
    } catch (e) { content.innerHTML = '<div class="text-center py-6 text-red-500 text-sm">Yüklenemedi</div>'; }
}

function escapeHtml(t) { const d = document.createElement('div'); d.textContent = t; return d.innerHTML; }
document.addEventListener('keydown', e => { if (e.key === 'Escape') document.querySelectorAll('[id$="Modal"]').forEach(m => { if (!m.classList.contains('hidden')) { m.classList.add('hidden'); document.body.style.overflow = 'auto'; }}); });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
