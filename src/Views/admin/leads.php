<?php
$pageTitle = 'Lead\'ler';
$currentPage = 'leads';
ob_start();
?>

<style>
/* Mobil Responsive Fixes */
@media (max-width: 1023px) {
    /* Lead cards mobilde wrap yaparak düzenli görünsün */
    .lead-card-mobile {
        flex-wrap: wrap !important;
        gap: 8px !important;
    }
    
    /* Checkbox ve ID: Üstte yan yana, tam genişlik */
    .lead-card-mobile > .lead-checkbox {
        width: auto !important;
        flex: 0 0 auto !important;
    }
    
    .lead-card-mobile > span:nth-child(2) {
        flex: 1 1 auto !important;
    }
    
    /* Service ve Status: 2. satır, yarı genişlik */
    .lead-card-mobile > span:nth-child(4),
    .lead-card-mobile > select {
        flex: 0 1 calc(50% - 4px) !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
    
    /* City & Phone: 3. satır, tam genişlik */
    .lead-card-mobile > div {
        flex: 1 1 100% !important;
    }
    
    /* Description: tek satır */
    .lead-card-mobile .description-mobile {
        flex: 1 1 100% !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        max-width: 100% !important;
    }
    
    /* Spacer gizle */
    .lead-card-mobile > .flex-1 {
        display: none !important;
    }
    
    /* Service time badge */
    .lead-card-mobile > span[class*="bg-red-100"],
    .lead-card-mobile > span[class*="bg-orange-100"],
    .lead-card-mobile > span[class*="bg-green-100"] {
        flex: 0 1 auto !important;
    }
    
    /* Tarih */
    .lead-card-mobile > span[class*="text-\[10px\]"]:last-of-type {
        flex: 1 1 100% !important;
        text-align: left !important;
    }
    
    /* Butonlar mobilde 2x2 grid */
    .lead-actions-mobile {
        width: 100% !important;
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 8px !important;
    }
    
    .lead-actions-mobile > button,
    .lead-actions-mobile > a {
        justify-content: center !important;
    }
    
    /* Checkbox daha büyük */
    .lead-checkbox {
        width: 20px !important;
        height: 20px !important;
    }
    
    /* Truncate uzun metinleri */
    .truncate-mobile {
        max-width: 100% !important;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Export buton küçült */
    #exportDropdownBtn span {
        display: none;
    }
    
    /* Bulk action button küçült */
    #assignToProviderBtn span:last-child {
        font-size: 14px;
    }
}
</style>

<!-- Header with Tabs & Filter Toggle -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
    <!-- Top Bar: Title + Filter Button -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        <div>
                <h2 class="text-xl font-bold text-gray-900">Lead Yönetimi</h2>
                <p class="text-sm text-gray-500"><?= number_format($totalLeads) ?> kayıt bulundu</p>
            </div>
        </div>
        
        <!-- Filter Toggle Button -->
        <button onclick="toggleFilters()" 
                class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors">
            <svg id="filterIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            <span class="font-semibold">Filtrele</span>
            <?php 
            $hasActiveFilters = ($serviceType !== 'all' || $city !== 'all' || $sentFilter !== 'all' || $serviceTimeType !== 'all' || !empty($dateFrom) || !empty($dateTo));
            if ($hasActiveFilters): 
            ?>
                <span class="px-2 py-0.5 bg-blue-600 text-white text-xs font-bold rounded-full">!</span>
            <?php endif; ?>
        </button>
    </div>
    
    <!-- Minimal Tabs -->
    <div class="flex overflow-x-auto border-b border-gray-100">
        <a href="/admin/leads" 
           class="group flex-1 min-w-[100px] text-center px-4 py-3 border-b-2 transition-all <?= empty($statusFilter) || $statusFilter === 'all' ? 'border-blue-600 bg-blue-50' : 'border-transparent hover:bg-gray-50' ?>">
            <div class="flex flex-col items-center gap-1">
                <span class="text-2xl font-bold <?= empty($statusFilter) || $statusFilter === 'all' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' ?>"><?= $stats['all'] ?></span>
                <span class="text-xs font-semibold <?= empty($statusFilter) || $statusFilter === 'all' ? 'text-blue-600' : 'text-gray-500 group-hover:text-gray-700' ?>">Tümü</span>
            </div>
        </a>
        
        <a href="/admin/leads?status=new" 
           class="group flex-1 min-w-[100px] text-center px-4 py-3 border-b-2 transition-all <?= ($statusFilter ?? '') === 'new' ? 'border-blue-600 bg-blue-50' : 'border-transparent hover:bg-gray-50' ?>">
            <div class="flex flex-col items-center gap-1">
                <span class="text-2xl font-bold <?= ($statusFilter ?? '') === 'new' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' ?>"><?= $stats['new'] ?></span>
                <span class="text-xs font-semibold <?= ($statusFilter ?? '') === 'new' ? 'text-blue-600' : 'text-gray-500 group-hover:text-gray-700' ?>">🆕 Yeni</span>
            </div>
        </a>
        
        <a href="/admin/leads?status=pending" 
           class="group flex-1 min-w-[100px] text-center px-4 py-3 border-b-2 transition-all <?= ($statusFilter ?? '') === 'pending' ? 'border-yellow-600 bg-yellow-50' : 'border-transparent hover:bg-gray-50' ?>">
            <div class="flex flex-col items-center gap-1">
                <span class="text-2xl font-bold <?= ($statusFilter ?? '') === 'pending' ? 'text-yellow-600' : 'text-gray-400 group-hover:text-gray-600' ?>"><?= $stats['pending'] ?></span>
                <span class="text-xs font-semibold <?= ($statusFilter ?? '') === 'pending' ? 'text-yellow-700' : 'text-gray-500 group-hover:text-gray-700' ?>">⏰ Beklemede</span>
            </div>
        </a>
        
        <a href="/admin/leads?status=verified" 
           class="group flex-1 min-w-[100px] text-center px-4 py-3 border-b-2 transition-all <?= ($statusFilter ?? '') === 'verified' ? 'border-green-600 bg-green-50' : 'border-transparent hover:bg-gray-50' ?>">
            <div class="flex flex-col items-center gap-1">
                <span class="text-2xl font-bold <?= ($statusFilter ?? '') === 'verified' ? 'text-green-600' : 'text-gray-400 group-hover:text-gray-600' ?>"><?= $stats['verified'] ?></span>
                <span class="text-xs font-semibold <?= ($statusFilter ?? '') === 'verified' ? 'text-green-600' : 'text-gray-500 group-hover:text-gray-700' ?>">✅ Doğrulanmış</span>
            </div>
        </a>
        
        <a href="/admin/leads?status=sold" 
           class="group flex-1 min-w-[100px] text-center px-4 py-3 border-b-2 transition-all <?= ($statusFilter ?? '') === 'sold' ? 'border-purple-600 bg-purple-50' : 'border-transparent hover:bg-gray-50' ?>">
            <div class="flex flex-col items-center gap-1">
                <span class="text-2xl font-bold <?= ($statusFilter ?? '') === 'sold' ? 'text-purple-600' : 'text-gray-400 group-hover:text-gray-600' ?>"><?= $stats['sold'] ?></span>
                <span class="text-xs font-semibold <?= ($statusFilter ?? '') === 'sold' ? 'text-purple-600' : 'text-gray-500 group-hover:text-gray-700' ?>">💰 Satılan</span>
            </div>
        </a>
        
        <a href="/admin/leads?status=invalid" 
           class="group flex-1 min-w-[100px] text-center px-4 py-3 border-b-2 transition-all <?= ($statusFilter ?? '') === 'invalid' ? 'border-red-600 bg-red-50' : 'border-transparent hover:bg-gray-50' ?>">
            <div class="flex flex-col items-center gap-1">
                <span class="text-2xl font-bold <?= ($statusFilter ?? '') === 'invalid' ? 'text-red-600' : 'text-gray-400 group-hover:text-gray-600' ?>"><?= $stats['invalid'] ?></span>
                <span class="text-xs font-semibold <?= ($statusFilter ?? '') === 'invalid' ? 'text-red-600' : 'text-gray-500 group-hover:text-gray-700' ?>">❌ Geçersiz</span>
            </div>
        </a>
        
        <a href="/admin/leads?status=deleted" 
           class="group flex-1 min-w-[100px] text-center px-4 py-3 border-b-2 transition-all <?= ($statusFilter ?? '') === 'deleted' ? 'border-gray-600 bg-gray-50' : 'border-transparent hover:bg-gray-50' ?>">
            <div class="flex flex-col items-center gap-1">
                <span class="text-2xl font-bold <?= ($statusFilter ?? '') === 'deleted' ? 'text-gray-600' : 'text-gray-400 group-hover:text-gray-600' ?>"><?= $stats['deleted'] ?></span>
                <span class="text-xs font-semibold <?= ($statusFilter ?? '') === 'deleted' ? 'text-gray-700' : 'text-gray-500 group-hover:text-gray-700' ?>">🗑️ Silinmiş</span>
            </div>
        </a>
    </div>
</div>

<!-- Filters (Collapsible) -->
<div id="filterPanel" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6 hidden">
    <!-- Compact Filter Header -->
    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <span class="text-sm font-semibold text-gray-700">Gelişmiş Filtreleme</span>
            <button onclick="toggleFilters()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
    
    <!-- Filter Form -->
    <form method="GET" action="/admin/leads" class="p-4">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 mb-4">
            <!-- Gönderilme Durumu -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Gönderilme</label>
                <select name="sent_filter" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="all" <?= ($sentFilter ?? 'all') === 'all' ? 'selected' : '' ?>>Tümü</option>
                    <option value="not_sent" <?= ($sentFilter ?? 'all') === 'not_sent' ? 'selected' : '' ?>>Gönderilmeyenler</option>
                    <option value="sent" <?= ($sentFilter ?? 'all') === 'sent' ? 'selected' : '' ?>>Gönderilenler</option>
            </select>
        </div>
        
            <!-- Lead Durumu -->
        <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Durum</label>
                <select name="status" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="all" <?= ($statusFilter ?? 'all') === 'all' ? 'selected' : '' ?>>Tüm Durumlar</option>
                    <option value="new" <?= ($statusFilter ?? '') === 'new' ? 'selected' : '' ?>>🆕 Yeni</option>
                    <option value="verified" <?= ($statusFilter ?? '') === 'verified' ? 'selected' : '' ?>>✅ Doğrulanmış</option>
                    <option value="pending" <?= ($statusFilter ?? '') === 'pending' ? 'selected' : '' ?>>⏰ Beklemede</option>
                    <option value="sold" <?= ($statusFilter ?? '') === 'sold' ? 'selected' : '' ?>>💰 Satılan</option>
                    <option value="invalid" <?= ($statusFilter ?? '') === 'invalid' ? 'selected' : '' ?>>❌ Geçersiz</option>
                </select>
            </div>
            
            <!-- Hizmet Türü -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Hizmet</label>
                <select name="service_type" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="all" <?= $serviceType === 'all' ? 'selected' : '' ?>>Tüm Hizmetler</option>
                <?php foreach (getServiceTypes() as $key => $service): ?>
                    <option value="<?= htmlspecialchars($key) ?>" <?= $serviceType === $key ? 'selected' : '' ?>>
                        <?= htmlspecialchars($service['tr']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
            <!-- Şehir -->
        <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Şehir</label>
                <select name="city" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="all" <?= $city === 'all' ? 'selected' : '' ?>>Tüm Şehirler</option>
                <?php foreach (getCities() as $key => $cityData): ?>
                    <option value="<?= htmlspecialchars($key) ?>" <?= $city === $key ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cityData['tr']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
            <!-- Hizmet Zamanı -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Zaman</label>
                <select name="service_time_type" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="all" <?= ($serviceTimeType ?? 'all') === 'all' ? 'selected' : '' ?>>Tümü</option>
                    <option value="urgent" <?= ($serviceTimeType ?? 'all') === 'urgent' ? 'selected' : '' ?>>⚡ Acil</option>
                    <option value="within_24h" <?= ($serviceTimeType ?? 'all') === 'within_24h' ? 'selected' : '' ?>>⏰ 24 Saat</option>
                    <option value="scheduled" <?= ($serviceTimeType ?? 'all') === 'scheduled' ? 'selected' : '' ?>>📅 Planlı</option>
                </select>
            </div>
        </div>
        
        <!-- Tarih Filtreleme -->
        <div class="grid grid-cols-2 gap-3 mb-4 pt-3 border-t border-gray-200">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Başlangıç Tarihi</label>
                <input type="date" name="date_from" value="<?= htmlspecialchars($dateFrom ?? '') ?>" 
                       class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Bitiş Tarihi</label>
                <input type="date" name="date_to" value="<?= htmlspecialchars($dateTo ?? '') ?>" 
                       class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors text-sm">
                Uygula
            </button>
            <a href="/admin/leads" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg transition-colors text-sm text-center">
                Temizle
            </a>
        </div>
    </form>
</div>

<!-- Leads Table -->
<div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
        <h2 class="text-2xl font-bold text-gray-900">Lead'ler (<?= number_format($totalLeads) ?>)</h2>
            <!-- Bulk Actions -->
            <button id="assignToProviderBtn" onclick="openAssignModal()" disabled
                    class="hidden items-center gap-3 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-lg"><span id="selectedCount" class="font-black">0</span> Lead'i Ustaya Gönder</span>
            </button>
        </div>
        
        <!-- Export Dropdown -->
        <div class="relative">
            <button id="exportDropdownBtn" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-colors shadow-md hover:shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Dışarı Aktar</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            
            <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl border border-gray-200 z-50">
                <div class="py-2">
                    <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Export Formatı Seçin</p>
                    <a href="javascript:void(0)" onclick="exportLeads('pdf')" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors text-gray-700 hover:text-gray-900">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="font-semibold">PDF</p>
                            <p class="text-xs text-gray-500">Varsayılan - Arapça</p>
                        </div>
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-semibold">Önerilen</span>
                    </a>
                    <a href="javascript:void(0)" onclick="exportLeads('excel')" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors text-gray-700 hover:text-gray-900">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="font-semibold">Excel (XLSX)</p>
                            <p class="text-xs text-gray-500">Hesap tablosu</p>
                        </div>
                    </a>
                    <a href="javascript:void(0)" onclick="exportLeads('csv')" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors text-gray-700 hover:text-gray-900">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="font-semibold">CSV</p>
                            <p class="text-xs text-gray-500">Virgülle ayrılmış</p>
                        </div>
                    </a>
                    <a href="javascript:void(0)" onclick="exportLeads('docx')" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors text-gray-700 hover:text-gray-900">
                        <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="font-semibold">Word (DOCX)</p>
                            <p class="text-xs text-gray-500">Microsoft Word</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (empty($leads)): ?>
        <div class="flex flex-col items-center justify-center py-20">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
        </div>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Lead Bulunamadı</h3>
            <p class="text-gray-500 text-center max-w-md">
                <?php if (!empty($statusFilter) && $statusFilter !== 'all'): ?>
                    Bu durumda henüz lead bulunmuyor. Filtreleri değiştirerek tekrar deneyebilirsiniz.
    <?php else: ?>
                    Henüz sistemde hiç lead bulunmuyor. Yeni lead'ler eklendiğinde burada görünecektir.
                <?php endif; ?>
            </p>
        </div>
    <?php else: ?>
        <?php if (($statusFilter ?? '') === 'deleted'): ?>
            <!-- Kompakt Liste Görünümü (Silinmiş Lead'ler için) -->
            <div class="space-y-2">
                    <?php foreach ($leads as $lead): ?>
                    <div class="bg-white border border-gray-200 rounded-lg p-3 hover:shadow-sm transition-shadow" id="lead-row-<?= $lead['id'] ?>">
                        <div class="flex items-start justify-between gap-3">
                            <!-- Sol Taraf: Lead Bilgileri -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    <!-- ID -->
                                    <span class="text-xs font-bold text-gray-500">#<?= str_pad($lead['id'], 6, '0', STR_PAD_LEFT) ?></span>
                                    
                                    <!-- Hizmet -->
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded">
                                        <?= htmlspecialchars(getServiceTypes()[$lead['service_type']]['tr'] ?? $lead['service_type']) ?>
                                    </span>
                                    
                                    <!-- Şehir -->
                                    <span class="text-xs text-gray-600">
                                        📍 <?= htmlspecialchars(getCities()[$lead['city']]['tr'] ?? $lead['city']) ?>
                                    </span>
                                    
                                    <!-- Telefon -->
                                    <span class="text-xs text-gray-600">
                                        📞 <?= htmlspecialchars($lead['phone']) ?>
                                    </span>
                                    
                                    <!-- Eski Durum -->
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-semibold rounded">
                                <?php
                                        $statusLabels = [
                                            'new' => '🆕 Yeni',
                                            'pending' => '⏰ Beklemede',
                                            'verified' => '✅ Doğrulanmış',
                                            'sold' => '💰 Satılmış',
                                            'invalid' => '❌ Geçersiz'
                                        ];
                                        echo $statusLabels[$lead['status']] ?? $lead['status'];
                                        ?>
                                    </span>
                                </div>
                                
                                <!-- Açıklama -->
                                <?php if (!empty($lead['description'])): ?>
                                    <p class="text-xs text-gray-600 truncate"><?= htmlspecialchars($lead['description']) ?></p>
                                <?php endif; ?>
                                
                                <!-- Alt Bilgiler -->
                                <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                    <span>🕒 Silindi: <?= date('d.m.Y H:i', strtotime($lead['deleted_at'])) ?></span>
                                    <?php if (!empty($lead['days_left'])): ?>
                                        <span class="text-red-600 font-semibold">
                                            ⚠️ <?= $lead['days_left'] > 0 ? $lead['days_left'] . ' gün sonra kalıcı silinecek' : 'Yarın kalıcı silinecek' ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Sağ Taraf: İşlem Butonları -->
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <button onclick="showLeadDetail(<?= htmlspecialchars(json_encode($lead)) ?>)" 
                                   class="px-3 py-1.5 bg-blue-100 text-blue-700 hover:bg-blue-200 text-xs font-semibold rounded-lg transition-colors whitespace-nowrap">
                                    👁️ Detay
                                </button>
                                
                                <button onclick="restoreLead(<?= $lead['id'] ?>)" 
                                   id="restore-btn-<?= $lead['id'] ?>"
                                   class="px-3 py-1.5 bg-green-600 text-white hover:bg-green-700 text-xs font-semibold rounded-lg transition-colors whitespace-nowrap">
                                    ↩️ Geri Yükle
                                </button>
                                
                                <button onclick="permanentlyDeleteLead(<?= $lead['id'] ?>)" 
                                   id="permanent-delete-btn-<?= $lead['id'] ?>"
                                   class="px-3 py-1.5 bg-red-600 text-white hover:bg-red-700 text-xs font-semibold rounded-lg transition-colors whitespace-nowrap">
                                    ⚠️ Kalıcı Sil
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination for Deleted Leads -->
            <?php if ($totalPages > 1): ?>
                <div class="mt-4 flex items-center justify-center gap-2">
                    <?php 
                    $paginationParams = http_build_query([
                        'status' => 'deleted',
                        'service_type' => $serviceType,
                        'city' => $city,
                        'sent_filter' => $sentFilter ?? 'all',
                        'service_time_type' => $serviceTimeType ?? 'all',
                        'date_from' => $dateFrom ?? '',
                        'date_to' => $dateTo ?? ''
                    ]);
                    ?>
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>&<?= $paginationParams ?>" 
                           class="px-4 py-2 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors text-sm">
                            Önceki
                        </a>
                    <?php endif; ?>
                    
                    <span class="px-4 py-2 text-gray-600 text-sm">
                        Sayfa <?= $page ?> / <?= $totalPages ?>
                    </span>
                    
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?= $page + 1 ?>&<?= $paginationParams ?>" 
                           class="px-4 py-2 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors text-sm">
                            Sonraki
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- Modern Card-Based List View -->
            <div class="space-y-3">
                <?php foreach ($leads as $lead): ?>
                    <?php 
                    $isSent = isset($lead['is_sent_to_provider']) ? $lead['is_sent_to_provider'] : false;
                                $statusColors = [
                                    'new' => 'bg-blue-100 text-blue-700',
                        'verified' => 'bg-green-100 text-green-700',
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        'sold' => 'bg-purple-100 text-purple-700',
                        'invalid' => 'bg-red-100 text-red-700',
                        'withdrawn' => 'bg-orange-100 text-orange-700'
                                ];
                                $currentStatus = $lead['status'] ?? 'new';
                                ?>
                    
                    <!-- Compact Lead Card -->
                    <?php 
                    $isWithdrawn = $currentStatus === 'withdrawn';
                    $cardBorder = $isWithdrawn ? 'border-orange-400 border-2 bg-orange-50' : 'border-gray-200';
                    ?>
                    <div class="bg-white border <?= $cardBorder ?> rounded-xl p-4 lg:p-3 hover:shadow-md transition-all <?= $isSent ? 'opacity-70' : '' ?>" id="lead-row-<?= $lead['id'] ?>">
                        <div class="flex items-center gap-3 lead-card-mobile">
                            <!-- Checkbox -->
                            <input type="checkbox" class="lead-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500" 
                                   value="<?= $lead['id'] ?>" 
                                   data-service="<?= htmlspecialchars($lead['service_type']) ?>"
                                   onchange="updateSelectedCount()">
                            
                            <!-- ID -->
                            <span class="text-xs font-bold text-gray-600 min-w-[60px]">
                                #<?= str_pad($lead['id'], 6, '0', STR_PAD_LEFT) ?>
                            </span>
                            
                            <!-- Withdrawn Warning Badge -->
                            <?php if ($isWithdrawn): ?>
                                <span class="px-2 py-1 bg-orange-600 text-white text-[10px] font-bold rounded whitespace-nowrap animate-pulse" 
                                      title="⚠️ Bu lead daha önce bir ustaya verilip geri çekilmiş! Potansiyel sorun olabilir.">
                                    ⚠️ GERİ ÇEKİLDİ
                                </span>
                            <?php endif; ?>
                            
                            <!-- Service Type -->
                            <span class="px-2 py-1 bg-blue-600 text-white text-xs font-semibold rounded whitespace-nowrap">
                                <?= htmlspecialchars(getServiceTypes()[$lead['service_type']]['tr'] ?? $lead['service_type']) ?>
                            </span>
                            
                            <!-- Status Dropdown -->
                                <select onchange="updateStatus(<?= $lead['id'] ?>, this.value)" 
                                    class="px-2 py-1 rounded text-xs font-semibold border-0 cursor-pointer <?= $statusColors[$currentStatus] ?? $statusColors['new'] ?>">
                                <option value="new" <?= $currentStatus === 'new' ? 'selected' : '' ?>>🆕 Yeni</option>
                                <option value="verified" <?= $currentStatus === 'verified' ? 'selected' : '' ?>>✅ Doğrulanmış</option>
                                <option value="pending" <?= $currentStatus === 'pending' ? 'selected' : '' ?>>⏰ Beklemede</option>
                                <option value="sold" <?= $currentStatus === 'sold' ? 'selected' : '' ?>>💰 Satılan</option>
                                <option value="withdrawn" <?= $currentStatus === 'withdrawn' ? 'selected' : '' ?>>🔄 Geri Çekildi</option>
                                <option value="invalid" <?= $currentStatus === 'invalid' ? 'selected' : '' ?>>❌ Geçersiz</option>
                                </select>
                            
                            <!-- City & Phone -->
                            <div class="flex items-center gap-2 text-xs min-w-0">
                                <span class="text-gray-500">📍</span>
                                <span class="text-gray-700 font-medium truncate"><?= htmlspecialchars(getCities()[$lead['city']]['tr'] ?? $lead['city']) ?></span>
                                <span class="text-gray-300">|</span>
                                <span class="text-gray-500">📞</span>
                                <span class="text-blue-600 font-semibold"><?= htmlspecialchars($lead['phone']) ?></span>
                            </div>
                            
                            <!-- Provider Info (if sold) -->
                            <?php if (!empty($lead['provider_name'])): ?>
                                <div class="flex items-center gap-1 px-2 py-1 bg-purple-50 rounded">
                                    <div class="w-5 h-5 bg-blue-600 rounded flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-bold text-[10px]"><?= mb_substr($lead['provider_name'], 0, 1) ?></span>
                                    </div>
                                    <span class="text-xs font-medium text-gray-700 truncate max-w-[100px]"><?= htmlspecialchars($lead['provider_name']) ?></span>
                                </div>
                                
                                <!-- Görüntülenme Durumu -->
                                <?php if (!empty($lead['viewed_at'])): ?>
                                    <span class="px-2 py-1 bg-orange-100 text-orange-700 text-[10px] font-bold rounded whitespace-nowrap" title="İlk görüntüleme: <?= date('d/m/Y H:i', strtotime($lead['viewed_at'])) ?>\nToplam görüntüleme: <?= $lead['viewed_count'] ?? 1 ?>">
                                        👁️ Görüldü
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded whitespace-nowrap">
                                        👁️ Görülmedi
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <!-- Description Preview -->
                            <?php if (!empty($lead['description'])): ?>
                                <span class="description-mobile text-xs text-gray-500 truncate max-w-[200px]" title="<?= htmlspecialchars($lead['description']) ?>">
                                    💬 <?= htmlspecialchars($lead['description']) ?>
                                </span>
                            <?php endif; ?>
                            
                            <!-- Spacer -->
                            <div class="flex-1"></div>
                            
                            <!-- Service Time Badge (if exists) -->
                            <?php
                            $serviceTime = $lead['service_time_type'] ?? null;
                            if ($serviceTime === 'urgent'): ?>
                                <span class="px-2 py-1 bg-red-100 text-red-700 text-[10px] font-bold rounded whitespace-nowrap">⚡ Acil</span>
                            <?php elseif ($serviceTime === 'within_24h'): ?>
                                <span class="px-2 py-1 bg-orange-100 text-orange-700 text-[10px] font-bold rounded whitespace-nowrap">⏰ 24h</span>
                            <?php elseif ($serviceTime === 'scheduled' && !empty($lead['scheduled_date'])): ?>
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded whitespace-nowrap">📅 <?= date('d/m', strtotime($lead['scheduled_date'])) ?></span>
                            <?php endif; ?>
                            
                            <!-- Date -->
                            <span class="text-[10px] text-gray-400 whitespace-nowrap">
                                <?= date('d.m.Y H:i', strtotime($lead['created_at'])) ?>
                            </span>
                            
                            <!-- Action Buttons - Sade & Okunabilir -->
                            <div class="flex items-center gap-2 lead-actions-mobile">
                                <!-- Detay -->
                                <button onclick="showLeadDetail(<?= htmlspecialchars(json_encode($lead)) ?>)" 
                                   class="group flex items-center gap-1.5 px-3 py-2 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-200 hover:border-blue-600 rounded-lg transition-all shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span class="text-xs font-semibold">Detay</span>
                                </button>
                                
                                <?php if (($statusFilter ?? '') === 'sold' && !empty($lead['provider_name'])): ?>
                                    <!-- Geri Çek - Görüldüyse uyarı ile -->
                                    <?php 
                                    $isViewed = !empty($lead['viewed_at']);
                                    $viewedClass = $isViewed ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' : 'bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border-red-200 hover:border-red-600';
                                    $viewedTitle = $isViewed ? '⚠️ Bu lead usta tarafından görüldü! Geri çekme önerilmez.' : 'Lead\'i ustadan geri çek';
                                    ?>
                                    <button onclick="withdrawLead(<?= $lead['id'] ?>, <?= $isViewed ? 'true' : 'false' ?>)" 
                                       id="withdraw-btn-<?= $lead['id'] ?>"
                                       title="<?= $viewedTitle ?>"
                                       class="group flex items-center gap-1.5 px-3 py-2 <?= $viewedClass ?> rounded-lg transition-all shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                                        </svg>
                                        <span class="text-xs font-semibold">Geri Çek</span>
                                        <?php if ($isViewed): ?>
                                            <span class="text-[10px]">⚠️</span>
                                        <?php endif; ?>
                                    </button>
                                <?php else: ?>
                                    <!-- Gönderim Durumu -->
                                    <button onclick="toggleSentStatus(<?= $lead['id'] ?>, <?= $isSent ? 'true' : 'false' ?>)" 
                                       id="toggle-btn-<?= $lead['id'] ?>"
                                       class="group flex items-center gap-1.5 px-3 py-2 rounded-lg transition-all shadow-sm hover:shadow-md <?= $isSent ? 'bg-green-50 hover:bg-green-600 text-green-600 hover:text-white border border-green-200 hover:border-green-600' : 'bg-gray-50 hover:bg-gray-600 text-gray-600 hover:text-white border border-gray-200 hover:border-gray-600' ?>">
                                        <?php if ($isSent): ?>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="text-xs font-semibold">Gönderildi</span>
                                        <?php else: ?>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-xs font-semibold">Gönderilmedi</span>
                                        <?php endif; ?>
                                    </button>
                                <?php endif; ?>
                                
                                <!-- Sil -->
                                <button onclick="deleteLead(<?= $lead['id'] ?>)" 
                                   id="delete-btn-<?= $lead['id'] ?>"
                                   class="group flex items-center gap-1.5 px-3 py-2 bg-gray-50 hover:bg-red-600 text-gray-600 hover:text-white border border-gray-200 hover:border-red-600 rounded-lg transition-all shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    <span class="text-xs font-semibold">Sil</span>
                                </button>
                                
                                <!-- WhatsApp Doğrula -->
                                <?php
                                // Telefon numarasını WhatsApp formatına çevir
                                $waPhone = preg_replace('/[^0-9]/', '', $lead['phone']);
                                if (substr($waPhone, 0, 1) === '0') {
                                    $waPhone = '966' . substr($waPhone, 1);
                                }
                                // Hizmet adını al
                                $serviceNameAr = $services[$lead['service_type']]['ar'] ?? $lead['service_type'];
                                // Doğrulama mesajı
                                $verifyMsg = "السلام عليكم 👋

تم استلام طلب خدمة *{$serviceNameAr}* الخاص بك.

سنقوم بتوجيه أحد مقدمي الخدمة المعتمدين إليك في أقرب وقت.

📋 *للتأكيد:*
✅ إذا أنت طلبت هذه الخدمة، أرسل: *1*
❌ إذا لم تطلب هذه الخدمة، أرسل: *0*

شكراً لثقتك بمنصة خدمة 🙏";
                                $waUrl = "https://wa.me/{$waPhone}?text=" . urlencode($verifyMsg);
                                ?>
                                <a href="<?= $waUrl ?>" target="_blank"
                                   class="group flex items-center gap-1.5 px-3 py-2 bg-green-50 hover:bg-green-600 text-green-600 hover:text-white border border-green-200 hover:border-green-600 rounded-lg transition-all shadow-sm hover:shadow-md"
                                   title="WhatsApp ile doğrulama mesajı gönder">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                                    </svg>
                                    <span class="text-xs font-semibold">Doğrula</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
                <div class="mt-6 flex items-center justify-center gap-3">
                    <?php 
                    $paginationParams = http_build_query([
                        'status' => $statusFilter ?? 'all',
                        'service_type' => $serviceType,
                        'city' => $city,
                        'sent_filter' => $sentFilter ?? 'all',
                        'service_time_type' => $serviceTimeType ?? 'all',
                        'date_from' => $dateFrom ?? '',
                        'date_to' => $dateTo ?? ''
                    ]);
                    ?>
                <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>&<?= $paginationParams ?>" 
                           class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                            ← Önceki
                    </a>
                <?php endif; ?>
                
                    <span class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl">
                    Sayfa <?= $page ?> / <?= $totalPages ?>
                </span>
                
                <?php if ($page < $totalPages): ?>
                        <a href="?page=<?= $page + 1 ?>&<?= $paginationParams ?>" 
                           class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                            Sonraki →
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php endif; ?> <!-- End of normal card view -->
    <?php endif; ?> <!-- End of leads check -->
</div>

<!-- Lead Detail Modal (Arabic Format) -->
<div id="leadDetailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-2xl font-bold text-gray-900">Müşteri Talebi Detayları</h3>
            <button onclick="closeLeadDetail()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <div class="p-6">
            <!-- Arabic Format Display -->
            <div id="leadDetailContent" class="bg-gray-50 rounded-xl p-6 mb-4 font-mono text-right" dir="rtl">
                <!-- Content will be inserted by JavaScript -->
            </div>
            
            <!-- Copy Button -->
            <div class="flex justify-end gap-3">
                <button onclick="closeLeadDetail()" 
                        class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-xl transition-colors">
                    Kapat
                </button>
                <button onclick="copyLeadDetail()" 
                        class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span id="copyButtonText">Kopyala (WhatsApp için)</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Assign to Provider Modal -->
<div id="assignProviderModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-blue-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Ustaya Lead Gönder</h3>
                        <p class="text-blue-100 text-sm"><span id="modal-selected-count">0</span> lead seçildi</p>
                    </div>
                </div>
                <button onclick="closeAssignModal()" class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-200px)]">
            <!-- Search Box -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Usta Ara (Telefon veya ID)
                </label>
                <div class="relative">
                    <input type="text" id="providerSearchInput" 
                           placeholder="örn: 0555 123 4567 veya #12" 
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           oninput="searchProviders(this.value)">
                    <div id="searchSpinner" class="hidden absolute right-3 top-3">
                        <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Provider Results -->
            <div id="providerResults" class="space-y-3">
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <p class="font-medium">Usta aramaya başlayın</p>
                    <p class="text-sm mt-1">Telefon numarası veya ID ile arama yapabilirsiniz</p>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex gap-3">
                <button onclick="closeAssignModal()" 
                        class="flex-1 px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition-colors">
                    İptal
                </button>
                <button id="confirmAssignBtn" disabled
                        class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    Lead'leri Gönder
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Service Types and Cities in Arabic
const serviceTypesAr = <?= json_encode(array_map(function($service) { return $service['ar']; }, getServiceTypes())) ?>;
const citiesAr = <?= json_encode(array_map(function($city) { return $city['ar']; }, getCities())) ?>;

// CSRF Token
const csrfToken = '<?= generateCsrfToken() ?>';

let currentLeadText = '';
let currentLeadId = null;

function showLeadDetail(lead) {
    currentLeadId = lead.id;
    const serviceNameAr = serviceTypesAr[lead.service_type] || lead.service_type;
    const cityNameAr = citiesAr[lead.city] || lead.city;
    
    // Service time in Arabic
    let serviceTimeAr = '';
    if (lead.service_time_type === 'urgent') {
        serviceTimeAr = '⚡ عاجل - في أقرب وقت';
    } else if (lead.service_time_type === 'within_24h') {
        serviceTimeAr = '⏰ خلال 24 ساعة';
    } else if (lead.service_time_type === 'scheduled' && lead.scheduled_date) {
        // Format date to DD/MM/YYYY
        const dateObj = new Date(lead.scheduled_date);
        const day = String(dateObj.getDate()).padStart(2, '0');
        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
        const year = dateObj.getFullYear();
        serviceTimeAr = `📅 التاريخ المحدد: ${day}/${month}/${year}`;
    }
    
    // Build Arabic format text
    const leadText = `🔧 الخدمة: ${serviceNameAr}
📍 المدينة: ${cityNameAr}
📞 الهاتف: ${lead.phone}
${lead.whatsapp_phone ? `📱 واتساب: ${lead.whatsapp_phone}\n` : ''}${serviceTimeAr ? `${serviceTimeAr}\n` : ''}${lead.description ? `📝 الوصف:\n${lead.description}\n` : ''}
${lead.budget_min || lead.budget_max ? `💰 الميزانية: ${lead.budget_min || '---'} - ${lead.budget_max || '---'} ريال\n` : ''}
⏰ تاريخ الطلب: ${lead.created_at}`;
    
    currentLeadText = leadText;
    
    // Display in modal
    document.getElementById('leadDetailContent').innerHTML = leadText.replace(/\n/g, '<br>');
    document.getElementById('leadDetailModal').classList.remove('hidden');
}

function closeLeadDetail() {
    document.getElementById('leadDetailModal').classList.add('hidden');
}

function copyLeadDetail() {
    navigator.clipboard.writeText(currentLeadText).then(() => {
        const btn = document.getElementById('copyButtonText');
        const originalText = btn.textContent;
        btn.textContent = '✓ Kopyalandı!';
        btn.parentElement.classList.add('bg-green-800');
        
        // Mark as sent to provider
        markAsSent(currentLeadId);
        
        setTimeout(() => {
            // Show success message and reload page
            showNotification('Lead ustaya gönderildi olarak işaretlendi. Sayfa yenileniyor...');
            
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }, 1000);
    }).catch(err => {
        alert('Kopyalama başarısız oldu. Lütfen manuel olarak kopyalayın.');
    });
}

function markAsSent(leadId) {
    const formData = new FormData();
    formData.append('lead_id', leadId);
    formData.append('csrf_token', csrfToken);
    
    console.log('Marking lead as sent:', leadId);
    
    fetch('/admin/leads/mark-as-sent', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            console.log('Successfully marked as sent');
            
            // Update row appearance
            const row = document.getElementById('lead-row-' + leadId);
            if (row) {
                row.classList.add('bg-gray-100', 'opacity-60');
            }
            
            // Update toggle button
            const toggleBtn = document.getElementById('toggle-btn-' + leadId);
            if (toggleBtn) {
                toggleBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-xs font-semibold">Gönderildi</span>';
                toggleBtn.className = 'group flex items-center gap-1.5 px-3 py-2 bg-green-50 hover:bg-green-600 text-green-600 hover:text-white border border-green-200 hover:border-green-600 rounded-lg transition-all shadow-sm hover:shadow-md';
                toggleBtn.onclick = function() { toggleSentStatus(leadId, true); };
            }
        } else {
            console.error('Failed to mark as sent:', data.message);
            alert('Hata: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error marking as sent:', error);
        alert('Bağlantı hatası: ' + error.message);
    });
}

function toggleSentStatus(leadId, currentStatus) {
    const formData = new FormData();
    formData.append('lead_id', leadId);
    formData.append('csrf_token', csrfToken);
    
    fetch('/admin/leads/toggle-sent', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const row = document.getElementById('lead-row-' + leadId);
            const toggleBtn = document.getElementById('toggle-btn-' + leadId);
            
            if (data.is_sent) {
                // Mark as sent
                if (row) {
                    row.classList.add('bg-gray-100', 'opacity-60');
                }
                if (toggleBtn) {
                    toggleBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-xs font-semibold">Gönderildi</span>';
                    toggleBtn.className = 'group flex items-center gap-1.5 px-3 py-2 bg-green-50 hover:bg-green-600 text-green-600 hover:text-white border border-green-200 hover:border-green-600 rounded-lg transition-all shadow-sm hover:shadow-md';
                    toggleBtn.onclick = function() { toggleSentStatus(leadId, true); };
                }
            } else {
                // Mark as not sent
                if (row) {
                    row.classList.remove('bg-gray-100', 'opacity-60');
                }
                if (toggleBtn) {
                    toggleBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span class="text-xs font-semibold">Gönderilmedi</span>';
                    toggleBtn.className = 'group flex items-center gap-1.5 px-3 py-2 bg-gray-50 hover:bg-gray-600 text-gray-600 hover:text-white border border-gray-200 hover:border-gray-600 rounded-lg transition-all shadow-sm hover:shadow-md';
                    toggleBtn.onclick = function() { toggleSentStatus(leadId, false); };
                }
            }
            
            // Show notification
            showNotification(data.message);
        }
    })
    .catch(error => {
        console.error('Error toggling sent status:', error);
        alert('İşlem başarısız oldu');
    });
}

function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-xl shadow-lg z-50';
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Close modal on outside click
document.getElementById('leadDetailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLeadDetail();
    }
});

// Export Dropdown Toggle
const exportDropdownBtn = document.getElementById('exportDropdownBtn');
const exportDropdown = document.getElementById('exportDropdown');

if (exportDropdownBtn && exportDropdown) {
    exportDropdownBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        exportDropdown.classList.toggle('hidden');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!exportDropdown.contains(e.target) && !exportDropdownBtn.contains(e.target)) {
            exportDropdown.classList.add('hidden');
        }
    });
}

// Export Leads Function
function exportLeads(format) {
    // Get current filter parameters from URL
    const urlParams = new URLSearchParams(window.location.search);
    
    // Build export URL with all filter parameters
    const exportParams = new URLSearchParams();
    exportParams.set('format', format);
    
    // Add all current filters
    ['status', 'service_type', 'city', 'sent_filter', 'service_time_type', 'date_from', 'date_to'].forEach(param => {
        const value = urlParams.get(param);
        if (value && value !== 'all' && value !== '') {
            exportParams.set(param, value);
        }
    });
    
    // Close dropdown
    exportDropdown.classList.add('hidden');
    
    // Show loading notification
    showNotification('Export hazırlanıyor... Lütfen bekleyin.');
    
    // Redirect to export URL
    window.location.href = `/admin/leads/export?${exportParams.toString()}`;
}

function updateStatus(leadId, status) {
    const formData = new FormData();
    formData.append('lead_id', leadId);
    formData.append('status', status);
    formData.append('csrf_token', csrfToken);
    
    fetch('/admin/leads/update-status', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Hata: ' + data.message);
        }
    })
    .catch(error => {
        alert('Bağlantı hatası oluştu');
    });
}

// Toggle Filter Panel
function toggleFilters() {
    const panel = document.getElementById('filterPanel');
    const icon = document.getElementById('filterIcon');
    
    if (panel.classList.contains('hidden')) {
        // Show panel
        panel.classList.remove('hidden');
        // Smooth animation
        setTimeout(() => {
            panel.style.opacity = '0';
            panel.style.transform = 'translateY(-10px)';
            panel.style.transition = 'all 0.3s ease';
            setTimeout(() => {
                panel.style.opacity = '1';
                panel.style.transform = 'translateY(0)';
            }, 10);
        }, 10);
    } else {
        // Hide panel
        panel.style.opacity = '0';
        panel.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            panel.classList.add('hidden');
            panel.style.opacity = '';
            panel.style.transform = '';
        }, 300);
    }
}

// Lead Selection & Provider Assignment Functions
let selectedProvider = null;
let searchTimeout = null;

function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.lead-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
    updateSelectedCount();
}

function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.lead-checkbox:checked');
    const count = checkboxes.length;
    const btn = document.getElementById('assignToProviderBtn');
    const countSpan = document.getElementById('selectedCount');
    
    countSpan.textContent = count;
    
    if (count > 0) {
        btn.classList.remove('hidden');
        btn.classList.add('flex');
        btn.disabled = false;
    } else {
        btn.classList.add('hidden');
        btn.classList.remove('flex');
        btn.disabled = true;
    }
    
    // Update "select all" checkbox
    const allCheckboxes = document.querySelectorAll('.lead-checkbox');
    const selectAllCheckbox = document.getElementById('selectAll');
    selectAllCheckbox.checked = count > 0 && count === allCheckboxes.length;
    selectAllCheckbox.indeterminate = count > 0 && count < allCheckboxes.length;
}

function openAssignModal() {
    const checkboxes = document.querySelectorAll('.lead-checkbox:checked');
    const count = checkboxes.length;
    
    if (count === 0) return;
    
    document.getElementById('modal-selected-count').textContent = count;
    document.getElementById('assignProviderModal').classList.remove('hidden');
    document.getElementById('providerSearchInput').value = '';
    document.getElementById('providerSearchInput').focus();
    selectedProvider = null;
    document.getElementById('confirmAssignBtn').disabled = true;
    
    // Uyarıyı gizle
    const warningContainer = document.getElementById('serviceTypeWarning');
    if (warningContainer) {
        warningContainer.classList.add('hidden');
    }
}

function closeAssignModal() {
    document.getElementById('assignProviderModal').classList.add('hidden');
    selectedProvider = null;
}

function searchProviders(query) {
    clearTimeout(searchTimeout);
    
    if (query.length < 2) {
        document.getElementById('providerResults').innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <p class="font-medium">En az 2 karakter girin</p>
            </div>
        `;
        return;
    }
    
    // Show spinner
    document.getElementById('searchSpinner').classList.remove('hidden');
    
    searchTimeout = setTimeout(() => {
        fetch(`/admin/providers/search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('searchSpinner').classList.add('hidden');
                
                if (data.success && data.providers.length > 0) {
                    displayProviders(data.providers);
                } else {
                    document.getElementById('providerResults').innerHTML = `
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="font-medium">Usta bulunamadı</p>
                            <p class="text-sm mt-1">Başka bir telefon veya ID deneyin</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('searchSpinner').classList.add('hidden');
                console.error('Search error:', error);
                alert('Arama sırasında hata oluştu');
            });
    }, 500);
}

function displayProviders(providers) {
    const html = providers.map(provider => {
        const statusColor = {
            'active': 'bg-green-100 text-green-700',
            'pending': 'bg-yellow-100 text-yellow-700',
            'suspended': 'bg-red-100 text-red-700'
        }[provider.status] || 'bg-gray-100 text-gray-700';
        
        const statusText = {
            'active': 'Aktif',
            'pending': 'Beklemede',
            'suspended': 'Askıda'
        }[provider.status] || provider.status;
        
        return `
            <div onclick="selectProvider(${provider.id}, '${provider.name}', '${provider.service_type}')" 
                 class="provider-card cursor-pointer border-2 border-gray-200 rounded-xl p-4 hover:border-blue-500 hover:bg-blue-50 transition-all" 
                 data-provider-id="${provider.id}">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-lg">${provider.name.charAt(0)}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <h4 class="font-bold text-gray-900 truncate">${provider.name}</h4>
                            <span class="px-2 py-0.5 ${statusColor} text-xs font-semibold rounded-full">${statusText}</span>
                        </div>
                        <p class="text-sm text-gray-600">📞 ${provider.phone}</p>
                        <p class="text-sm text-gray-500">🔧 ${provider.service_type_tr}</p>
                        <p class="text-xs text-gray-400">ID: #${provider.id}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </div>
        `;
    }).join('');
    
    document.getElementById('providerResults').innerHTML = html;
}

function selectProvider(id, name, serviceType) {
    selectedProvider = { id, name, serviceType };
    
    // Highlight selected
    document.querySelectorAll('.provider-card').forEach(card => {
        card.classList.remove('border-blue-600', 'bg-blue-50');
        card.classList.add('border-gray-200');
    });
    
    const selectedCard = document.querySelector(`[data-provider-id="${id}"]`);
    if (selectedCard) {
        selectedCard.classList.remove('border-gray-200');
        selectedCard.classList.add('border-blue-600', 'bg-blue-50');
    }
    
    // Seçili lead'lerin hizmet türlerini kontrol et
    checkServiceTypeMatch(serviceType, name);
}

function checkServiceTypeMatch(providerServiceType, providerName) {
    const checkboxes = document.querySelectorAll('.lead-checkbox:checked');
    const leadServiceTypes = Array.from(checkboxes).map(cb => cb.dataset.service);
    
    // Uyumsuz lead'leri bul
    const mismatchedLeads = leadServiceTypes.filter(type => type !== providerServiceType);
    const matchedCount = leadServiceTypes.length - mismatchedLeads.length;
    const mismatchedCount = mismatchedLeads.length;
    
    const confirmBtn = document.getElementById('confirmAssignBtn');
    const warningContainer = document.getElementById('serviceTypeWarning') || createWarningContainer();
    
    if (mismatchedCount === 0) {
        // ✅ Tümü uyumlu - yeşil
        warningContainer.classList.add('hidden');
        confirmBtn.disabled = false;
        confirmBtn.className = 'flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors';
        confirmBtn.textContent = `${providerName}'a Gönder`;
        
    } else if (mismatchedCount < leadServiceTypes.length) {
        // ⚠️ Bazıları uyumsuz - sarı uyarı
        warningContainer.classList.remove('hidden');
        warningContainer.innerHTML = `
            <div class="bg-yellow-50 border-2 border-yellow-400 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-yellow-900 mb-1">⚠️ Uyarı: Hizmet Türü Uyumsuzluğu!</h4>
                        <p class="text-sm text-yellow-800 mb-2">
                            <span class="font-semibold">${providerName}</span> bir <span class="font-semibold">${getServiceTypeTR(providerServiceType)}</span> ustasıdır.
                        </p>
                        <div class="space-y-1 text-xs">
                            <p class="text-yellow-700">✅ <strong>${matchedCount}</strong> lead uyumlu</p>
                            <p class="text-red-600">❌ <strong>${mismatchedCount}</strong> lead UYUMSUZ!</p>
                        </div>
                        <p class="text-xs text-yellow-700 mt-2 font-medium">
                            Uyumsuz lead'leri göndermek istediğinize emin misiniz?
                        </p>
                    </div>
                </div>
            </div>
        `;
        
        confirmBtn.disabled = false;
        confirmBtn.className = 'flex-1 px-4 py-2.5 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-xl transition-colors';
        confirmBtn.innerHTML = `
            <div class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>Yine de Gönder</span>
            </div>
        `;
        
    } else {
        // ❌ Tümü uyumsuz - kırmızı engelle
        warningContainer.classList.remove('hidden');
        warningContainer.innerHTML = `
            <div class="bg-red-50 border-2 border-red-400 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-red-900 mb-1">❌ HATA: Hizmet Türü Uyumsuz!</h4>
                        <p class="text-sm text-red-800 mb-2">
                            <span class="font-semibold">${providerName}</span> bir <span class="font-semibold">${getServiceTypeTR(providerServiceType)}</span> ustasıdır.
                        </p>
                        <p class="text-sm text-red-700 font-semibold">
                            Seçili ${leadServiceTypes.length} lead'in HİÇBİRİ ${getServiceTypeTR(providerServiceType)} hizmeti değil!
                        </p>
                        <p class="text-xs text-red-600 mt-2">
                            Lütfen doğru ustayı seçin veya farklı lead'ler seçin.
                        </p>
                    </div>
                </div>
            </div>
        `;
        
        confirmBtn.disabled = true;
        confirmBtn.className = 'flex-1 px-4 py-2.5 bg-red-400 cursor-not-allowed text-white font-semibold rounded-xl opacity-50';
        confirmBtn.innerHTML = `
            <div class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                <span>Gönderilemiyor (Uyumsuz)</span>
            </div>
        `;
    }
}

function createWarningContainer() {
    const container = document.createElement('div');
    container.id = 'serviceTypeWarning';
    container.className = 'mb-4';
    
    const modalBody = document.querySelector('#assignProviderModal .p-6');
    const resultsDiv = document.getElementById('providerResults');
    
    if (modalBody && resultsDiv) {
        modalBody.insertBefore(container, resultsDiv);
    }
    
    return container;
}

function getServiceTypeTR(serviceKey) {
    const types = {
        'paint': 'Boya Badana',
        'renovation': 'Tadilat',
        'cleaning': 'Temizlik',
        'ac': 'Klima',
        'plumbing': 'Sıhhi Tesisat',
        'electric': 'Elektrik',
        'carpentry': 'Marangoz',
        'security': 'Güvenlik',
        'satellite': 'Uydu'
    };
    return types[serviceKey] || serviceKey;
}

function confirmAssignLeads() {
    if (!selectedProvider) return;
    
    const checkboxes = document.querySelectorAll('.lead-checkbox:checked');
    const leadIds = Array.from(checkboxes).map(cb => parseInt(cb.value));
    
    if (leadIds.length === 0) {
        alert('Lead seçilmedi');
        return;
    }
    
    // Disable button
    const btn = document.getElementById('confirmAssignBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    
    fetch('/admin/leads/assign-to-provider', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            csrf_token: csrfToken,
            provider_id: selectedProvider.id,
            lead_ids: leadIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`✅ ${data.assigned_count} lead başarıyla ${selectedProvider.name}'a gönderildi!`);
            closeAssignModal();
            location.reload(); // Refresh page
        } else {
            alert('❌ Hata: ' + (data.error || 'Bilinmeyen hata'));
            btn.disabled = false;
            btn.textContent = `${selectedProvider.name}'a Gönder`;
        }
    })
    .catch(error => {
        console.error('Assign error:', error);
        alert('❌ Bağlantı hatası oluştu');
        btn.disabled = false;
        btn.textContent = `${selectedProvider.name}'a Gönder`;
    });
}

// Bind confirm button
document.getElementById('confirmAssignBtn').addEventListener('click', confirmAssignLeads);

// Close modal on outside click
document.getElementById('assignProviderModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAssignModal();
    }
});

// Withdraw Lead Function
function withdrawLead(leadId, isViewed) {
    // Eğer görüldüyse ekstra uyarı göster
    if (isViewed) {
        if (!confirm('⚠️ DİKKAT! Bu lead usta tarafından GÖRÜLDÜ!\n\n❌ UYARI:\n• Usta müşteri bilgilerini zaten gördü\n• Usta müşteriyle iletişime geçmiş olabilir\n• Geri çekmenin anlamı olmayabilir\n\n🤔 Yine de geri çekmek istiyor musunuz?')) {
            return;
        }
        
        // İkinci onay
        if (!confirm('SON ONAY:\n\nUsta bu lead\'i görüp müşteri ile iletişime geçmiş olabilir.\nGeri çekmek istediğinizden EMİN MİSİNİZ?')) {
            return;
        }
    } else {
        // Normal uyarı
        if (!confirm('Bu lead\'i ustadan geri çekmek istediğinize emin misiniz?\n\n• Lead\'in durumu önceki haline döner\n• Provider\'ın paketine lead hakkı iade edilir\n• Provider dashboard\'undan kaldırılır')) {
            return;
        }
    }
    
    const btn = document.getElementById(`withdraw-btn-${leadId}`);
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin h-3 w-3 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    }
    
    fetch('/admin/leads/withdraw-from-provider', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            csrf_token: csrfToken,
            lead_id: leadId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`✅ ${data.message}\n\nYeni durum: ${data.new_status}`);
            location.reload(); // Sayfayı yenile
        } else {
            alert('❌ Hata: ' + (data.error || 'Bilinmeyen hata'));
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '🔙 Geri Çek';
            }
        }
    })
    .catch(error => {
        console.error('Withdraw error:', error);
        alert('❌ Bağlantı hatası oluştu');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '🔙 Geri Çek';
        }
    });
}

// Delete Lead Function (Soft Delete)
function deleteLead(leadId) {
    if (!confirm('Bu lead\'i çöp kutusuna taşımak istediğinize emin misiniz?\n\n• Lead 30 gün boyunca çöp kutusunda kalacak\n• İstediğiniz zaman geri yükleyebilirsiniz\n• 30 gün sonra otomatik olarak kalıcı silinecek')) {
        return;
    }
    
    const btn = document.getElementById(`delete-btn-${leadId}`);
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin h-3 w-3 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    }
    
    fetch('/admin/leads/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            csrf_token: csrfToken,
            lead_id: leadId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message);
            // Satırı yumuşak bir şekilde kaldır
            const row = document.getElementById(`lead-row-${leadId}`);
            if (row) {
                row.style.transition = 'opacity 0.3s';
                row.style.opacity = '0';
                setTimeout(() => row.remove(), 300);
            }
        } else {
            alert('❌ Hata: ' + (data.error || 'Bilinmeyen hata'));
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '🗑️ Sil';
            }
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        alert('❌ Bağlantı hatası oluştu');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '🗑️ Sil';
        }
    });
}

// Restore Lead Function (Geri Yükle)
function restoreLead(leadId) {
    if (!confirm('Bu lead\'i geri yüklemek istediğinize emin misiniz?\n\n• Lead önceki durumuna geri dönecek\n• Normal lead listesinde görünecek')) {
        return;
    }
    
    const btn = document.getElementById(`restore-btn-${leadId}`);
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin h-3 w-3 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    }
    
    fetch('/admin/leads/restore', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            csrf_token: csrfToken,
            lead_id: leadId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`✅ ${data.message}\n\nGeri yüklenen durum: ${data.restored_status}`);
            location.reload(); // Sayfayı yenile
        } else {
            alert('❌ Hata: ' + (data.error || 'Bilinmeyen hata'));
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '↩️ Geri Yükle';
            }
        }
    })
    .catch(error => {
        console.error('Restore error:', error);
        alert('❌ Bağlantı hatası oluştu');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '↩️ Geri Yükle';
        }
    });
}

// Permanently Delete Lead Function (Kalıcı Sil)
function permanentlyDeleteLead(leadId) {
    if (!confirm('⚠️ DİKKAT! Bu işlem GERİ ALINAMAZ!\n\nBu lead\'i kalıcı olarak silmek istediğinize EMİN MİSİNİZ?\n\n• Lead veritabanından tamamen silinecek\n• Bu işlem geri alınamaz\n• Tüm lead verileri kaybolacak\n\nDevam etmek için "Tamam" butonuna basın.')) {
        return;
    }
    
    // İkinci onay
    const confirmation = prompt('Kalıcı silme işlemini onaylamak için "SIL" yazın:');
    if (confirmation !== 'SIL') {
        alert('İşlem iptal edildi.');
        return;
    }
    
    const btn = document.getElementById(`permanent-delete-btn-${leadId}`);
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin h-3 w-3 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    }
    
    fetch('/admin/leads/permanently-delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            csrf_token: csrfToken,
            lead_id: leadId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`✅ ${data.message}`);
            // Satırı yumuşak bir şekilde kaldır
            const row = document.getElementById(`lead-row-${leadId}`);
            if (row) {
                row.style.transition = 'opacity 0.3s';
                row.style.opacity = '0';
                setTimeout(() => row.remove(), 300);
            }
        } else {
            alert('❌ Hata: ' + (data.error || 'Bilinmeyen hata'));
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '⚠️ Kalıcı Sil';
            }
        }
    })
    .catch(error => {
        console.error('Permanently delete error:', error);
        alert('❌ Bağlantı hatası oluştu');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '⚠️ Kalıcı Sil';
        }
    });
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
