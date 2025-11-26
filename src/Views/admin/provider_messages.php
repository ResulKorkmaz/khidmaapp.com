<?php
/**
 * Admin - Provider Messages
 * Modern, profesyonel mesajlaşma sistemi
 * Toplu mesaj, şehir/sektör bazlı filtreleme, lead gönderimi
 */

$pageTitle = 'Provider Mesajları';
$currentPage = 'provider-messages';
ob_start();

$serviceTypes = getServiceTypes();
$cities = getCities();

// Check for warning message
$warningMessage = $_SESSION['warning_message'] ?? null;
if ($warningMessage) {
    unset($_SESSION['warning_message']);
}

// Filtreler
$filters = $filters ?? ['city' => '', 'service' => '', 'status' => '', 'search' => ''];
$cityCounts = $cityCounts ?? [];
$serviceCounts = $serviceCounts ?? [];
$activeProviderCount = $activeProviderCount ?? 0;
$availableLeads = $availableLeads ?? [];
?>

<div class="container mx-auto px-4 py-6">
    <?php if ($warningMessage): ?>
    <div class="bg-amber-50 border-2 border-amber-300 text-amber-800 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
        <span class="text-2xl">⚠️</span>
        <span class="font-medium"><?= htmlspecialchars($warningMessage) ?></span>
    </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
                    <span class="text-3xl">💬</span>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white">Provider Mesajları</h1>
                    <p class="text-white/90 text-sm mt-1">Toplu veya tekli mesaj gönder, lead paylaş</p>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex gap-2 flex-wrap">
                <button onclick="openBulkMessageModal('all')" class="bg-white hover:bg-green-50 text-green-600 font-bold px-4 py-2.5 rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2 text-sm">
                    <span>📢</span>
                    <span>Tüm Ustalara</span>
                </button>
                <button onclick="openBulkMessageModal('city')" class="bg-white hover:bg-blue-50 text-blue-600 font-bold px-4 py-2.5 rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2 text-sm">
                    <span>🏙️</span>
                    <span>Şehre Göre</span>
                </button>
                <button onclick="openBulkMessageModal('service')" class="bg-white hover:bg-orange-50 text-orange-600 font-bold px-4 py-2.5 rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2 text-sm">
                    <span>🔧</span>
                    <span>Sektöre Göre</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-2xl">👥</div>
                <div>
                    <p class="text-gray-500 text-xs font-medium">Toplam Usta</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $totalProviders ?? 0 ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-2xl">✅</div>
                <div>
                    <p class="text-gray-500 text-xs font-medium">Aktif Usta</p>
                    <p class="text-2xl font-bold text-green-600"><?= $activeProviderCount ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-2xl">📨</div>
                <div>
                    <p class="text-gray-500 text-xs font-medium">Toplam Mesaj</p>
                    <p class="text-2xl font-bold text-blue-600"><?= $stats['total_messages'] ?? 0 ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center text-2xl">🔔</div>
                <div>
                    <p class="text-gray-500 text-xs font-medium">Okunmamış</p>
                    <p class="text-2xl font-bold text-red-600"><?= $stats['unread_messages'] ?? 0 ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">🔍 Ara</label>
                <input type="text" name="search" value="<?= htmlspecialchars($filters['search']) ?>" 
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                       placeholder="İsim, email, telefon...">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">🏙️ Şehir</label>
                <select name="city" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">Tüm Şehirler</option>
                    <?php foreach ($cities as $key => $city): ?>
                        <option value="<?= $key ?>" <?= $filters['city'] === $key ? 'selected' : '' ?>>
                            <?= htmlspecialchars($city['tr']) ?> (<?= $cityCounts[$key] ?? 0 ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">🔧 Hizmet</label>
                <select name="service" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">Tüm Hizmetler</option>
                    <?php foreach ($serviceTypes as $key => $service): ?>
                        <option value="<?= $key ?>" <?= $filters['service'] === $key ? 'selected' : '' ?>>
                            <?= $service['icon'] ?? '🔧' ?> <?= htmlspecialchars($service['tr']) ?> (<?= $serviceCounts[$key] ?? 0 ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">📊 Durum</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">Tüm Durumlar</option>
                    <option value="active" <?= $filters['status'] === 'active' ? 'selected' : '' ?>>✅ Aktif</option>
                    <option value="pending" <?= $filters['status'] === 'pending' ? 'selected' : '' ?>>⏳ Beklemede</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors text-sm">
                    Filtrele
                </button>
                <a href="/admin/provider-messages" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors text-sm">
                    Temizle
                </a>
            </div>
        </form>
    </div>

    <!-- Providers Grid -->
    <?php if (empty($providers)): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <span class="text-6xl mb-4 block">📭</span>
            <p class="text-gray-500 font-medium mb-2">Provider bulunamadı</p>
            <p class="text-gray-400 text-sm">Filtrelerinizi değiştirmeyi deneyin</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <?php foreach ($providers as $provider): ?>
                <?php 
                $serviceInfo = $serviceTypes[$provider['service_type']] ?? ['tr' => $provider['service_type'], 'icon' => '🔧'];
                $cityInfo = $cities[$provider['city']] ?? ['tr' => $provider['city']];
                ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-lg hover:border-purple-200 transition-all">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                                <?= strtoupper(substr($provider['name'], 0, 2)) ?>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900"><?= htmlspecialchars($provider['name']) ?></h3>
                                <p class="text-xs text-gray-400">#<?= $provider['id'] ?></p>
                            </div>
                        </div>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold <?= $provider['status'] === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                            <?= $provider['status'] === 'active' ? '✓ Aktif' : '⏳ Beklemede' ?>
                        </span>
                    </div>

                    <!-- Contact Info -->
                    <div class="space-y-2 mb-4 text-sm">
                        <div class="flex items-center gap-2 text-gray-600">
                            <span>📧</span>
                            <span class="truncate"><?= htmlspecialchars($provider['email']) ?></span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <span>📱</span>
                            <span><?= htmlspecialchars($provider['phone']) ?></span>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-semibold">
                            <?= $serviceInfo['icon'] ?? '🔧' ?> <?= htmlspecialchars($serviceInfo['tr']) ?>
                        </span>
                        <span class="px-2 py-1 bg-orange-50 text-orange-700 rounded-lg text-xs font-semibold">
                            📍 <?= htmlspecialchars($cityInfo['tr']) ?>
                        </span>
                    </div>

                    <!-- Message Stats -->
                    <div class="grid grid-cols-2 gap-2 mb-4 p-3 bg-gray-50 rounded-lg">
                        <div class="text-center">
                            <p class="text-lg font-bold text-gray-900"><?= $provider['message_count'] ?></p>
                            <p class="text-xs text-gray-500">Mesaj</p>
                        </div>
                        <div class="text-center">
                            <p class="text-lg font-bold <?= $provider['unread_count'] > 0 ? 'text-red-600' : 'text-gray-400' ?>"><?= $provider['unread_count'] ?></p>
                            <p class="text-xs text-gray-500">Okunmamış</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="grid grid-cols-3 gap-2">
                        <button onclick="openSendMessageModal(<?= $provider['id'] ?>, '<?= htmlspecialchars($provider['name'], ENT_QUOTES) ?>')" 
                                class="px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded-lg transition-colors flex items-center justify-center gap-1">
                            <span>📤</span>
                            <span>Mesaj</span>
                        </button>
                        <button onclick="openSendLeadModal(<?= $provider['id'] ?>, '<?= htmlspecialchars($provider['name'], ENT_QUOTES) ?>', '<?= $provider['service_type'] ?>', '<?= $provider['city'] ?>')" 
                                class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition-colors flex items-center justify-center gap-1">
                            <span>🎯</span>
                            <span>Lead</span>
                        </button>
                        <button onclick="viewMessageHistory(<?= $provider['id'] ?>, '<?= htmlspecialchars($provider['name'], ENT_QUOTES) ?>')" 
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition-colors flex items-center justify-center gap-1">
                            <span>📜</span>
                            <span>Geçmiş</span>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if (isset($totalPages) && $totalPages > 1): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <p class="text-sm text-gray-600">
                        <strong><?= $totalProviders ?></strong> provider | Sayfa <strong><?= $page ?></strong> / <?= $totalPages ?>
                    </p>
                    <div class="flex gap-2">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>&<?= http_build_query($filters) ?>" 
                               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold transition-colors">
                                ← Önceki
                            </a>
                        <?php endif; ?>
                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?>&<?= http_build_query($filters) ?>" 
                               class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-semibold transition-colors">
                                Sonraki →
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Single Message Modal -->
<div id="sendMessageModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-5 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <span>📤</span>
                    <span>Mesaj Gönder</span>
                </h3>
                <button onclick="closeModal('sendMessageModal')" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <form id="sendMessageForm" class="p-5 space-y-4">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="provider_id" id="modal_provider_id">
            
            <div class="bg-purple-50 border border-purple-200 rounded-xl p-3">
                <p class="text-sm text-purple-700"><strong>🎯 Alıcı:</strong> <span id="modal_provider_name"></span></p>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">📋 Mesaj Tipi</label>
                    <select name="message_type" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500">
                        <option value="info">ℹ️ Bilgilendirme</option>
                        <option value="notification">🔔 Bildirim</option>
                        <option value="announcement">📢 Duyuru</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">🎚️ Öncelik</label>
                    <select name="priority" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500">
                        <option value="normal">Normal</option>
                        <option value="high">🟠 Yüksek</option>
                        <option value="urgent">🔴 Acil</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">📝 Konu</label>
                <input type="text" name="subject" required placeholder="Mesaj konusu (Arapça önerilir)"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">💬 Mesaj</label>
                <textarea name="message" rows="5" required placeholder="Mesajınızı yazın... (Arapça önerilir)"
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500"></textarea>
            </div>
            
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('sendMessageModal')" 
                        class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors">
                    İptal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                    📤 Gönder
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Message Modal -->
<div id="bulkMessageModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-5 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <span>📢</span>
                    <span id="bulkModalTitle">Toplu Mesaj Gönder</span>
                </h3>
                <button onclick="closeModal('bulkMessageModal')" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <form id="bulkMessageForm" class="p-5 space-y-4">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="target_type" id="bulk_target_type" value="all">
            
            <!-- Target Selection (shown for city/service) -->
            <div id="targetSelectionContainer" class="hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5" id="targetSelectionLabel">Hedef Seçin</label>
                <select name="target_value" id="bulk_target_value" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                    <option value="">Seçiniz...</option>
                </select>
            </div>
            
            <div class="bg-green-50 border border-green-200 rounded-xl p-3" id="bulkTargetInfo">
                <p class="text-sm text-green-700"><strong>🎯 Hedef:</strong> <span id="bulk_target_text">Tüm Ustalar</span></p>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">📋 Mesaj Tipi</label>
                    <select name="message_type" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                        <option value="announcement">📢 Duyuru</option>
                        <option value="notification">🔔 Bildirim</option>
                        <option value="info">ℹ️ Bilgilendirme</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">🎚️ Öncelik</label>
                    <select name="priority" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                        <option value="normal">Normal</option>
                        <option value="high">🟠 Yüksek</option>
                        <option value="urgent">🔴 Acil</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">📝 Konu</label>
                <input type="text" name="subject" required placeholder="Duyuru konusu (Arapça önerilir)"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">💬 Mesaj</label>
                <textarea name="message" rows="5" required placeholder="Duyuru mesajınızı yazın... (Arapça önerilir)"
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500"></textarea>
            </div>
            
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3">
                <p class="text-sm text-amber-700">⚠️ <strong>Dikkat:</strong> Bu mesaj seçilen tüm ustalara gönderilecektir.</p>
            </div>
            
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('bulkMessageModal')" 
                        class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors">
                    İptal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                    📢 Toplu Gönder
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Send Lead Modal -->
<div id="sendLeadModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-5 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <span>🎯</span>
                    <span>Lead Gönder</span>
                </h3>
                <button onclick="closeModal('sendLeadModal')" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <form id="sendLeadForm" class="p-5 space-y-4">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="provider_id" id="lead_provider_id">
            
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3">
                <p class="text-sm text-emerald-700"><strong>🎯 Alıcı:</strong> <span id="lead_provider_name"></span></p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">📋 Uygun Lead Seç</label>
                <select name="lead_id" id="lead_select" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500">
                    <option value="">Lead seçiniz...</option>
                </select>
                <p class="text-xs text-gray-500 mt-1" id="lead_count_info">Yükleniyor...</p>
            </div>
            
            <div id="leadDetails" class="hidden bg-gray-50 rounded-xl p-4 space-y-2">
                <h4 class="font-semibold text-gray-900 text-sm">Lead Detayları:</h4>
                <div id="leadDetailsContent"></div>
            </div>
            
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-3">
                <p class="text-sm text-blue-700">💡 Lead seçtiğinizde, ustaya otomatik olarak Arapça formatlı bir mesaj gönderilecektir.</p>
            </div>
            
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('sendLeadModal')" 
                        class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors">
                    İptal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors">
                    🎯 Lead Gönder
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Message History Modal -->
<div id="messageHistoryModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-gray-700 to-gray-800 p-5 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <span>📜</span>
                    <span>Mesaj Geçmişi</span>
                </h3>
                <button onclick="closeModal('messageHistoryModal')" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="p-5">
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-3 mb-4">
                <p class="text-sm text-gray-700"><strong>👤 Provider:</strong> <span id="history_provider_name"></span></p>
            </div>
            <div id="messageHistoryContent">
                <div class="text-center py-8">
                    <div class="animate-spin w-8 h-8 border-4 border-purple-600 border-t-transparent rounded-full mx-auto"></div>
                    <p class="text-gray-500 mt-3">Mesajlar yükleniyor...</p>
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

// Modal Functions
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

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
    const formData = new FormData(this);
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '⏳ Gönderiliyor...';
    
    try {
        const response = await fetch('/admin/provider-messages/send', { method: 'POST', body: formData });
        const result = await response.json();
        
        if (result.success) {
            alert('✅ ' + result.message);
            closeModal('sendMessageModal');
            location.reload();
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('❌ Hata: ' + error.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '📤 Gönder';
    }
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
        document.getElementById('bulkModalTitle').textContent = 'Tüm Ustalara Mesaj';
        targetText.textContent = 'Tüm Ustalar (<?= $activeProviderCount ?> kişi)';
    } else if (targetType === 'city') {
        container.classList.remove('hidden');
        document.getElementById('bulkModalTitle').textContent = 'Şehre Göre Mesaj';
        label.textContent = '🏙️ Şehir Seçin';
        targetText.textContent = 'Şehir seçilmedi';
        
        Object.keys(cities).forEach(key => {
            const opt = document.createElement('option');
            opt.value = key;
            opt.textContent = cities[key].tr + ' (<?= json_encode($cityCounts) ?>'[key] || 0 + ' kişi)';
            select.appendChild(opt);
        });
    } else if (targetType === 'service') {
        container.classList.remove('hidden');
        document.getElementById('bulkModalTitle').textContent = 'Sektöre Göre Mesaj';
        label.textContent = '🔧 Hizmet Seçin';
        targetText.textContent = 'Hizmet seçilmedi';
        
        Object.keys(serviceTypes).forEach(key => {
            const opt = document.createElement('option');
            opt.value = key;
            opt.textContent = (serviceTypes[key].icon || '🔧') + ' ' + serviceTypes[key].tr;
            select.appendChild(opt);
        });
    }
    
    openModal('bulkMessageModal');
}

document.getElementById('bulk_target_value').addEventListener('change', function() {
    const targetType = document.getElementById('bulk_target_type').value;
    const value = this.value;
    const targetText = document.getElementById('bulk_target_text');
    
    if (value) {
        if (targetType === 'city') {
            targetText.textContent = cities[value]?.tr || value;
        } else if (targetType === 'service') {
            targetText.textContent = (serviceTypes[value]?.icon || '🔧') + ' ' + (serviceTypes[value]?.tr || value);
        }
    }
});

document.getElementById('bulkMessageForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const targetType = document.getElementById('bulk_target_type').value;
    const targetValue = document.getElementById('bulk_target_value').value;
    
    if (targetType !== 'all' && !targetValue) {
        alert('❌ Lütfen hedef seçin');
        return;
    }
    
    if (!confirm('⚠️ Bu mesaj seçilen tüm ustalara gönderilecek. Devam etmek istiyor musunuz?')) {
        return;
    }
    
    const formData = new FormData(this);
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '⏳ Gönderiliyor...';
    
    try {
        const response = await fetch('/admin/provider-messages/send-bulk', { method: 'POST', body: formData });
        const result = await response.json();
        
        if (result.success) {
            alert('✅ ' + result.message);
            closeModal('bulkMessageModal');
            location.reload();
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('❌ Hata: ' + error.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '📢 Toplu Gönder';
    }
});

// Send Lead
function openSendLeadModal(providerId, providerName, serviceType, city) {
    document.getElementById('lead_provider_id').value = providerId;
    document.getElementById('lead_provider_name').textContent = providerName;
    document.getElementById('sendLeadForm').reset();
    document.getElementById('lead_provider_id').value = providerId;
    document.getElementById('leadDetails').classList.add('hidden');
    
    // Filter leads by service type and city
    const select = document.getElementById('lead_select');
    select.innerHTML = '<option value="">Lead seçiniz...</option>';
    
    const matchingLeads = availableLeads.filter(lead => 
        lead.service_type === serviceType && lead.city === city
    );
    
    const otherLeads = availableLeads.filter(lead => 
        lead.service_type !== serviceType || lead.city !== city
    );
    
    if (matchingLeads.length > 0) {
        const optGroup1 = document.createElement('optgroup');
        optGroup1.label = '✅ Eşleşen Lead\'ler (' + matchingLeads.length + ')';
        matchingLeads.forEach(lead => {
            const opt = document.createElement('option');
            opt.value = lead.id;
            opt.textContent = '#' + lead.id + ' - ' + (serviceTypes[lead.service_type]?.tr || lead.service_type) + ' - ' + (cities[lead.city]?.tr || lead.city);
            opt.dataset.lead = JSON.stringify(lead);
            optGroup1.appendChild(opt);
        });
        select.appendChild(optGroup1);
    }
    
    if (otherLeads.length > 0) {
        const optGroup2 = document.createElement('optgroup');
        optGroup2.label = '📋 Diğer Lead\'ler (' + otherLeads.length + ')';
        otherLeads.forEach(lead => {
            const opt = document.createElement('option');
            opt.value = lead.id;
            opt.textContent = '#' + lead.id + ' - ' + (serviceTypes[lead.service_type]?.tr || lead.service_type) + ' - ' + (cities[lead.city]?.tr || lead.city);
            opt.dataset.lead = JSON.stringify(lead);
            optGroup2.appendChild(opt);
        });
        select.appendChild(optGroup2);
    }
    
    document.getElementById('lead_count_info').textContent = 
        matchingLeads.length + ' eşleşen, ' + otherLeads.length + ' diğer lead mevcut';
    
    openModal('sendLeadModal');
}

document.getElementById('lead_select').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const detailsDiv = document.getElementById('leadDetails');
    const contentDiv = document.getElementById('leadDetailsContent');
    
    if (selected.dataset.lead) {
        const lead = JSON.parse(selected.dataset.lead);
        contentDiv.innerHTML = `
            <p class="text-sm"><strong>Hizmet:</strong> ${serviceTypes[lead.service_type]?.tr || lead.service_type}</p>
            <p class="text-sm"><strong>Şehir:</strong> ${cities[lead.city]?.tr || lead.city}</p>
            <p class="text-sm"><strong>Telefon:</strong> ${lead.phone}</p>
            ${lead.description ? '<p class="text-sm"><strong>Açıklama:</strong> ' + lead.description + '</p>' : ''}
            <p class="text-sm text-gray-500"><strong>Tarih:</strong> ${lead.created_at}</p>
        `;
        detailsDiv.classList.remove('hidden');
    } else {
        detailsDiv.classList.add('hidden');
    }
});

document.getElementById('sendLeadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const leadId = document.getElementById('lead_select').value;
    if (!leadId) {
        alert('❌ Lütfen bir lead seçin');
        return;
    }
    
    const formData = new FormData(this);
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '⏳ Gönderiliyor...';
    
    try {
        const response = await fetch('/admin/provider-messages/send-lead', { method: 'POST', body: formData });
        const result = await response.json();
        
        if (result.success) {
            alert('✅ ' + result.message);
            closeModal('sendLeadModal');
            location.reload();
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('❌ Hata: ' + error.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '🎯 Lead Gönder';
    }
});

// Message History
async function viewMessageHistory(providerId, providerName) {
    document.getElementById('history_provider_name').textContent = providerName;
    openModal('messageHistoryModal');
    
    const content = document.getElementById('messageHistoryContent');
    content.innerHTML = '<div class="text-center py-8"><div class="animate-spin w-8 h-8 border-4 border-purple-600 border-t-transparent rounded-full mx-auto"></div><p class="text-gray-500 mt-3">Mesajlar yükleniyor...</p></div>';
    
    try {
        const response = await fetch(`/admin/provider-messages/history?provider_id=${providerId}`);
        const result = await response.json();
        
        if (result.success && result.messages.length > 0) {
            let html = '<div class="space-y-3">';
            result.messages.forEach(msg => {
                const isRead = msg.is_read == 1;
                const typeColors = {
                    'lead': 'bg-green-100 text-green-700',
                    'notification': 'bg-blue-100 text-blue-700',
                    'announcement': 'bg-amber-100 text-amber-700',
                    'info': 'bg-gray-100 text-gray-700'
                };
                const typeColor = typeColors[msg.message_type] || typeColors.info;
                
                html += `
                    <div class="border ${isRead ? 'border-gray-200' : 'border-purple-300 bg-purple-50'} rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                            <span class="px-2 py-0.5 rounded text-xs font-semibold ${typeColor}">${msg.message_type}</span>
                            ${msg.priority === 'urgent' ? '<span class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs font-semibold">🔴 Acil</span>' : ''}
                            ${msg.priority === 'high' ? '<span class="px-2 py-0.5 bg-orange-100 text-orange-700 rounded text-xs font-semibold">🟠 Yüksek</span>' : ''}
                            ${!isRead ? '<span class="px-2 py-0.5 bg-purple-600 text-white rounded text-xs font-semibold">Okunmadı</span>' : ''}
                        </div>
                        <h4 class="font-bold text-gray-900 mb-1">${escapeHtml(msg.subject)}</h4>
                        <p class="text-sm text-gray-600 whitespace-pre-wrap mb-2">${escapeHtml(msg.message)}</p>
                        <p class="text-xs text-gray-400">📅 ${new Date(msg.created_at).toLocaleString('tr-TR')}</p>
                    </div>
                `;
            });
            html += '</div>';
            content.innerHTML = html;
        } else {
            content.innerHTML = '<div class="text-center py-8"><span class="text-4xl mb-3 block">📭</span><p class="text-gray-500">Henüz mesaj yok</p></div>';
        }
    } catch (error) {
        content.innerHTML = '<div class="text-center py-8 text-red-500">❌ Mesajlar yüklenemedi</div>';
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Close modal on ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id$="Modal"]').forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
