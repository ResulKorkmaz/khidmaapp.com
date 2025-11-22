<?php
// Admin layout'u başlat - içeriği ob_start ile yakala
ob_start();
?>

<!-- Tab Navigation - Mobile Optimized -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
    <div class="flex overflow-x-auto scrollbar-hide">
        <a href="/admin/providers" 
           class="flex-1 min-w-fit text-center px-3 sm:px-6 py-3 sm:py-4 font-semibold border-b-2 transition-colors <?= empty($statusFilter) ? 'border-blue-600 text-blue-600 bg-blue-50' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' ?>">
            <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="text-sm sm:text-base">Tümü</span>
                <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-gray-200 text-gray-700 text-xs font-bold rounded-full"><?= $stats['total'] ?></span>
            </div>
        </a>
        
        <a href="/admin/providers?status=active" 
           class="flex-1 min-w-fit text-center px-3 sm:px-6 py-3 sm:py-4 font-semibold border-b-2 transition-colors <?= ($statusFilter ?? '') === 'active' ? 'border-green-600 text-green-600 bg-green-50' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' ?>">
            <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm sm:text-base">Aktif</span>
                <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-green-200 text-green-800 text-xs font-bold rounded-full"><?= $stats['active'] ?></span>
            </div>
        </a>
        
        <a href="/admin/providers?status=pending" 
           class="flex-1 min-w-fit text-center px-3 sm:px-6 py-3 sm:py-4 font-semibold border-b-2 transition-colors <?= ($statusFilter ?? '') === 'pending' ? 'border-yellow-600 text-yellow-600 bg-yellow-50' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' ?>">
            <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm sm:text-base">Beklemede</span>
                <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-yellow-200 text-yellow-800 text-xs font-bold rounded-full"><?= $stats['pending'] ?></span>
            </div>
        </a>
        
        <a href="/admin/providers?status=suspended" 
           class="flex-1 min-w-fit text-center px-3 sm:px-6 py-3 sm:py-4 font-semibold border-b-2 transition-colors <?= ($statusFilter ?? '') === 'suspended' ? 'border-red-600 text-red-600 bg-red-50' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' ?>">
            <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                <span class="text-sm sm:text-base">Askıda</span>
                <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-red-200 text-red-800 text-xs font-bold rounded-full"><?= $stats['suspended'] ?></span>
            </div>
        </a>
        
        <a href="/admin/providers?status=rejected" 
           class="flex-1 min-w-fit text-center px-3 sm:px-6 py-3 sm:py-4 font-semibold border-b-2 transition-colors <?= ($statusFilter ?? '') === 'rejected' ? 'border-gray-600 text-gray-600 bg-gray-50' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' ?>">
            <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm sm:text-base">Reddedilen</span>
                <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-gray-200 text-gray-700 text-xs font-bold rounded-full"><?= $stats['rejected'] ?></span>
            </div>
        </a>
    </div>
</div>

<!-- Filtreleme Paneli (Collapsible) -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
    <!-- Header -->
    <button type="button" onclick="toggleFilterPanel()" 
            class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors rounded-t-2xl">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
            </div>
            <div class="text-left">
                <h3 class="text-base font-bold text-gray-900">Filtrele & Ara</h3>
                <p class="text-sm text-gray-500"><?= $totalProviders ?> usta bulundu</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-semibold text-gray-500 hidden sm:block">Filtreleri Göster/Gizle</span>
            <svg id="filter-chevron" class="w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </button>
    
    <!-- Collapsible Content -->
    <div id="filter-panel" class="hidden border-t border-gray-100">
        <form method="GET" action="/admin/providers" class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Arama -->
            <div class="lg:col-span-2">
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Arama
                </label>
                <input type="text" name="search" value="<?= htmlspecialchars($searchQuery ?? '') ?>" 
                       placeholder="İsim, email veya telefon..."
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
            
            <!-- Hizmet Türü -->
            <div>
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Hizmet Türü
                </label>
                <select name="service_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Tümü</option>
                    <?php foreach (getServiceTypes() as $key => $service): ?>
                        <option value="<?= htmlspecialchars($key) ?>" <?= ($serviceTypeFilter ?? '') === $key ? 'selected' : '' ?>>
                            <?= htmlspecialchars($service['tr']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Durum -->
            <div>
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Durum
                </label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Tümü</option>
                    <option value="active" <?= ($statusFilter ?? '') === 'active' ? 'selected' : '' ?>>✅ Aktif</option>
                    <option value="pending" <?= ($statusFilter ?? '') === 'pending' ? 'selected' : '' ?>>⏳ Beklemede</option>
                    <option value="suspended" <?= ($statusFilter ?? '') === 'suspended' ? 'selected' : '' ?>>🚫 Askıda</option>
                    <option value="rejected" <?= ($statusFilter ?? '') === 'rejected' ? 'selected' : '' ?>>❌ Reddedilen</option>
                </select>
            </div>
            
            <!-- Şehir -->
            <div>
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Şehir
                </label>
                <select name="city" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Tümü</option>
                    <?php foreach (getCities() as $key => $city): ?>
                        <option value="<?= htmlspecialchars($key) ?>" <?= ($cityFilter ?? '') === $key ? 'selected' : '' ?>>
                            <?= htmlspecialchars($city['tr']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <!-- Butonlar -->
        <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-100">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filtrele
            </button>
            <a href="/admin/providers" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Temizle
            </a>
        </div>
        </form>
    </div>
    <!-- End Collapsible Content -->
</div>
<!-- End Filter Panel -->

<!-- Ustalar Tablosu -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Usta</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">İletişim</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Hizmet</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Şehir</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Puan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Durum</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tarih</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($providers)): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-gray-500 font-medium text-lg">Usta bulunamadı</p>
                                <p class="text-gray-400 text-sm mt-1">Filtreleri temizleyip tekrar deneyin</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $serviceTypes = getServiceTypes();
                    $cities = getCities();
                    foreach ($providers as $provider): 
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
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Usta -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-indigo-600 font-bold text-sm">
                                            <?= mb_substr($provider['name'], 0, 2, 'UTF-8') ?>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900"><?= htmlspecialchars($provider['name']) ?></p>
                                        <p class="text-xs text-gray-500">ID: #<?= str_pad($provider['id'], 4, '0', STR_PAD_LEFT) ?></p>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- İletişim -->
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900"><?= htmlspecialchars($provider['email']) ?></p>
                                <p class="text-sm text-gray-600"><?= htmlspecialchars($provider['phone']) ?></p>
                            </td>
                            
                            <!-- Hizmet -->
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                    <?= htmlspecialchars($serviceName) ?>
                                </span>
                            </td>
                            
                            <!-- Şehir -->
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-900"><?= htmlspecialchars($cityName) ?></span>
                            </td>
                            
                            <!-- Puan -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-900"><?= number_format($provider['rating'], 1) ?></span>
                                    <span class="text-xs text-gray-500">(<?= $provider['total_jobs'] ?>)</span>
                                </div>
                            </td>
                            
                            <!-- Durum -->
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 <?= $statusClasses[$provider['status']] ?> text-xs font-semibold rounded-full">
                                    <?= $statusLabels[$provider['status']] ?>
                                </span>
                            </td>
                            
                            <!-- Tarih -->
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900"><?= date('d.m.Y', strtotime($provider['created_at'])) ?></p>
                                <p class="text-xs text-gray-500"><?= date('H:i', strtotime($provider['created_at'])) ?></p>
                            </td>
                            
                            <!-- İşlemler -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="/admin/providers/<?= $provider['id'] ?>" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 rounded-lg text-xs font-semibold transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detay
                                    </a>
                                    
                                    <?php if ($provider['status'] === 'pending'): ?>
                                        <!-- Kanal Kontrolü Butonu -->
                                        <button onclick="checkWhatsAppChannel(<?= $provider['id'] ?>, '<?= htmlspecialchars($provider['phone']) ?>', '<?= htmlspecialchars($provider['name']) ?>')"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 rounded-lg text-xs font-semibold transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                                            </svg>
                                            Kanal Kontrolü
                                        </button>
                                    <?php elseif ($provider['status'] === 'active'): ?>
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-100 text-green-800 rounded-lg text-xs font-semibold">
                                            ✅ Onaylı
                                        </span>
                                    <?php endif; ?>
                                    
                                    <!-- Silme Butonu -->
                                    <button onclick="deleteProvider(<?= $provider['id'] ?>, '<?= htmlspecialchars($provider['name']) ?>')"
                                            id="delete-provider-btn-<?= $provider['id'] ?>"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 rounded-lg text-xs font-semibold transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Sil
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

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
    <div class="mt-6 flex items-center justify-between">
        <div class="text-sm text-gray-600">
            Sayfa <?= $page ?> / <?= $totalPages ?> (Toplam <?= $totalProviders ?> usta)
        </div>
        <div class="flex gap-2">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?><?= !empty($searchQuery) ? '&search=' . urlencode($searchQuery) : '' ?><?= !empty($serviceTypeFilter) ? '&service_type=' . urlencode($serviceTypeFilter) : '' ?><?= !empty($statusFilter) ? '&status=' . urlencode($statusFilter) : '' ?><?= !empty($cityFilter) ? '&city=' . urlencode($cityFilter) : '' ?>" 
                   class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                    Önceki
                </a>
            <?php endif; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?><?= !empty($searchQuery) ? '&search=' . urlencode($searchQuery) : '' ?><?= !empty($serviceTypeFilter) ? '&service_type=' . urlencode($serviceTypeFilter) : '' ?><?= !empty($statusFilter) ? '&status=' . urlencode($statusFilter) : '' ?><?= !empty($cityFilter) ? '&city=' . urlencode($cityFilter) : '' ?>" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    Sonraki
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<script>
// CSRF Token
const csrfToken = '<?= generateCsrfToken() ?>';

/**
 * Toggle Filter Panel
 */
function toggleFilterPanel() {
    const panel = document.getElementById('filter-panel');
    const chevron = document.getElementById('filter-chevron');
    
    if (panel.classList.contains('hidden')) {
        panel.classList.remove('hidden');
        chevron.style.transform = 'rotate(180deg)';
    } else {
        panel.classList.add('hidden');
        chevron.style.transform = 'rotate(0deg)';
    }
}

/**
 * WhatsApp kanal kontrolü yap
 */
function checkWhatsAppChannel(providerId, phone, name) {
    // WhatsApp kanalını yeni sekmede aç
    window.open('https://whatsapp.com/channel/0029VbCCqZoI1rcjIn9IWV2l', '_blank');
    
    // Confirm dialog göster
    setTimeout(() => {
        const confirmed = confirm(
            `Usta Bilgileri:\n` +
            `📛 İsim: ${name}\n` +
            `📞 Telefon: ${phone}\n\n` +
            `WhatsApp kanalında bu telefon numarası var mı?\n\n` +
            `✅ EVET = Usta onaylanacak (Status: Active)\n` +
            `❌ HAYIR = İptal`
        );
        
        if (confirmed) {
            approveProvider(providerId, phone, name);
        }
    }, 1000);
}

/**
 * Ustayı onayla
 */
function approveProvider(providerId, phone, name) {
    // Loading indicator
    const loadingMsg = `Usta onaylanıyor: ${name}...`;
    console.log(loadingMsg);
    
    fetch('/admin/providers/approve', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            provider_id: providerId,
            csrf_token: csrfToken
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`✅ Başarılı!\n\n${name} onaylandı.\nStatus: Active olarak güncellendi.`);
            location.reload();
        } else {
            alert(`❌ Hata!\n\n${data.error || 'Usta onaylanamadı'}`);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Beklenmeyen bir hata oluştu.');
    });
}

/**
 * Provider silme fonksiyonu (2 aşamalı onay)
 */
function deleteProvider(providerId, providerName) {
    // İlk onay
    if (!confirm(`⚠️ DİKKAT! Provider'ı silmek istediğinize emin misiniz?\n\nProvider: ${providerName}\n\n• Tüm lead deliveries kayıtları silinecek\n• Tüm satın alma kayıtları silinecek\n• Provider hesabı kalıcı olarak silinecek\n• Bu işlem GERİ ALINAMAZ!\n\nDevam etmek için "Tamam" butonuna basın.`)) {
        return;
    }
    
    // İkinci onay: "SIL" yazması gerekiyor
    const confirmation = prompt(
        `Son onay adımı:\n\n` +
        `"${providerName}" adlı provider'ı kalıcı olarak silmek için\n` +
        `aşağıya büyük harflerle SIL yazın:`
    );
    
    if (confirmation !== 'SIL') {
        if (confirmation !== null) { // Kullanıcı iptal etmediyse
            alert('❌ İşlem iptal edildi.\n\nDoğru onay metni girilmedi. "SIL" yazmalısınız.');
        }
        return;
    }
    
    // Butonu disable et ve loading state
    const btn = document.getElementById(`delete-provider-btn-${providerId}`);
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin h-4 w-4 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    }
    
    // AJAX request
    fetch('/admin/providers/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            csrf_token: csrfToken,
            provider_id: providerId,
            confirmation: 'SIL'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`✅ ${data.message}\n\nSilinen Provider: ${data.deleted_provider}`);
            // Satırı fade out ile kaldır
            const row = btn.closest('tr');
            if (row) {
                row.style.transition = 'opacity 0.5s';
                row.style.opacity = '0';
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                location.reload();
            }
        } else {
            alert('❌ Hata: ' + (data.error || 'Provider silinemedi'));
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg> Sil';
            }
        }
    })
    .catch(error => {
        console.error('Delete provider error:', error);
        alert('❌ Bağlantı hatası oluştu');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg> Sil';
        }
    });
}
</script>

<?php
// İçeriği al
$content = ob_get_clean();

// Layout'u yükle
require __DIR__ . '/layout.php';
?>


ob_start();
?>

<!-- Tab Navigation - Mobile Optimized -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
    <div class="flex overflow-x-auto scrollbar-hide">
        <a href="/admin/providers" 
           class="flex-1 min-w-fit text-center px-3 sm:px-6 py-3 sm:py-4 font-semibold border-b-2 transition-colors <?= empty($statusFilter) ? 'border-blue-600 text-blue-600 bg-blue-50' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' ?>">
            <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="text-sm sm:text-base">Tümü</span>
                <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-gray-200 text-gray-700 text-xs font-bold rounded-full"><?= $stats['total'] ?></span>
            </div>
        </a>
        
        <a href="/admin/providers?status=active" 
           class="flex-1 min-w-fit text-center px-3 sm:px-6 py-3 sm:py-4 font-semibold border-b-2 transition-colors <?= ($statusFilter ?? '') === 'active' ? 'border-green-600 text-green-600 bg-green-50' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' ?>">
            <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm sm:text-base">Aktif</span>
                <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-green-200 text-green-800 text-xs font-bold rounded-full"><?= $stats['active'] ?></span>
            </div>
        </a>
        
        <a href="/admin/providers?status=pending" 
           class="flex-1 min-w-fit text-center px-3 sm:px-6 py-3 sm:py-4 font-semibold border-b-2 transition-colors <?= ($statusFilter ?? '') === 'pending' ? 'border-yellow-600 text-yellow-600 bg-yellow-50' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' ?>">
            <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm sm:text-base">Beklemede</span>
                <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-yellow-200 text-yellow-800 text-xs font-bold rounded-full"><?= $stats['pending'] ?></span>
            </div>
        </a>
        
        <a href="/admin/providers?status=suspended" 
           class="flex-1 min-w-fit text-center px-3 sm:px-6 py-3 sm:py-4 font-semibold border-b-2 transition-colors <?= ($statusFilter ?? '') === 'suspended' ? 'border-red-600 text-red-600 bg-red-50' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' ?>">
            <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                <span class="text-sm sm:text-base">Askıda</span>
                <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-red-200 text-red-800 text-xs font-bold rounded-full"><?= $stats['suspended'] ?></span>
            </div>
        </a>
        
        <a href="/admin/providers?status=rejected" 
           class="flex-1 min-w-fit text-center px-3 sm:px-6 py-3 sm:py-4 font-semibold border-b-2 transition-colors <?= ($statusFilter ?? '') === 'rejected' ? 'border-gray-600 text-gray-600 bg-gray-50' : 'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-50' ?>">
            <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm sm:text-base">Reddedilen</span>
                <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-gray-200 text-gray-700 text-xs font-bold rounded-full"><?= $stats['rejected'] ?></span>
            </div>
        </a>
    </div>
</div>

<!-- Filtreleme Paneli (Collapsible) -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
    <!-- Header -->
    <button type="button" onclick="toggleFilterPanel()" 
            class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors rounded-t-2xl">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
            </div>
            <div class="text-left">
                <h3 class="text-base font-bold text-gray-900">Filtrele & Ara</h3>
                <p class="text-sm text-gray-500"><?= $totalProviders ?> usta bulundu</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-semibold text-gray-500 hidden sm:block">Filtreleri Göster/Gizle</span>
            <svg id="filter-chevron" class="w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </button>
    
    <!-- Collapsible Content -->
    <div id="filter-panel" class="hidden border-t border-gray-100">
        <form method="GET" action="/admin/providers" class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Arama -->
            <div class="lg:col-span-2">
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Arama
                </label>
                <input type="text" name="search" value="<?= htmlspecialchars($searchQuery ?? '') ?>" 
                       placeholder="İsim, email veya telefon..."
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
            
            <!-- Hizmet Türü -->
            <div>
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Hizmet Türü
                </label>
                <select name="service_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Tümü</option>
                    <?php foreach (getServiceTypes() as $key => $service): ?>
                        <option value="<?= htmlspecialchars($key) ?>" <?= ($serviceTypeFilter ?? '') === $key ? 'selected' : '' ?>>
                            <?= htmlspecialchars($service['tr']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Durum -->
            <div>
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Durum
                </label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Tümü</option>
                    <option value="active" <?= ($statusFilter ?? '') === 'active' ? 'selected' : '' ?>>✅ Aktif</option>
                    <option value="pending" <?= ($statusFilter ?? '') === 'pending' ? 'selected' : '' ?>>⏳ Beklemede</option>
                    <option value="suspended" <?= ($statusFilter ?? '') === 'suspended' ? 'selected' : '' ?>>🚫 Askıda</option>
                    <option value="rejected" <?= ($statusFilter ?? '') === 'rejected' ? 'selected' : '' ?>>❌ Reddedilen</option>
                </select>
            </div>
            
            <!-- Şehir -->
            <div>
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Şehir
                </label>
                <select name="city" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Tümü</option>
                    <?php foreach (getCities() as $key => $city): ?>
                        <option value="<?= htmlspecialchars($key) ?>" <?= ($cityFilter ?? '') === $key ? 'selected' : '' ?>>
                            <?= htmlspecialchars($city['tr']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <!-- Butonlar -->
        <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-100">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filtrele
            </button>
            <a href="/admin/providers" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Temizle
            </a>
        </div>
        </form>
    </div>
    <!-- End Collapsible Content -->
</div>
<!-- End Filter Panel -->

<!-- Ustalar Tablosu -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Usta</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">İletişim</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Hizmet</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Şehir</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Puan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Durum</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tarih</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($providers)): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-gray-500 font-medium text-lg">Usta bulunamadı</p>
                                <p class="text-gray-400 text-sm mt-1">Filtreleri temizleyip tekrar deneyin</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $serviceTypes = getServiceTypes();
                    $cities = getCities();
                    foreach ($providers as $provider): 
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
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Usta -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-indigo-600 font-bold text-sm">
                                            <?= mb_substr($provider['name'], 0, 2, 'UTF-8') ?>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900"><?= htmlspecialchars($provider['name']) ?></p>
                                        <p class="text-xs text-gray-500">ID: #<?= str_pad($provider['id'], 4, '0', STR_PAD_LEFT) ?></p>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- İletişim -->
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900"><?= htmlspecialchars($provider['email']) ?></p>
                                <p class="text-sm text-gray-600"><?= htmlspecialchars($provider['phone']) ?></p>
                            </td>
                            
                            <!-- Hizmet -->
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                    <?= htmlspecialchars($serviceName) ?>
                                </span>
                            </td>
                            
                            <!-- Şehir -->
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-900"><?= htmlspecialchars($cityName) ?></span>
                            </td>
                            
                            <!-- Puan -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-900"><?= number_format($provider['rating'], 1) ?></span>
                                    <span class="text-xs text-gray-500">(<?= $provider['total_jobs'] ?>)</span>
                                </div>
                            </td>
                            
                            <!-- Durum -->
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 <?= $statusClasses[$provider['status']] ?> text-xs font-semibold rounded-full">
                                    <?= $statusLabels[$provider['status']] ?>
                                </span>
                            </td>
                            
                            <!-- Tarih -->
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900"><?= date('d.m.Y', strtotime($provider['created_at'])) ?></p>
                                <p class="text-xs text-gray-500"><?= date('H:i', strtotime($provider['created_at'])) ?></p>
                            </td>
                            
                            <!-- İşlemler -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="/admin/providers/<?= $provider['id'] ?>" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 rounded-lg text-xs font-semibold transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detay
                                    </a>
                                    
                                    <?php if ($provider['status'] === 'pending'): ?>
                                        <!-- Kanal Kontrolü Butonu -->
                                        <button onclick="checkWhatsAppChannel(<?= $provider['id'] ?>, '<?= htmlspecialchars($provider['phone']) ?>', '<?= htmlspecialchars($provider['name']) ?>')"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 rounded-lg text-xs font-semibold transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                                            </svg>
                                            Kanal Kontrolü
                                        </button>
                                    <?php elseif ($provider['status'] === 'active'): ?>
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-100 text-green-800 rounded-lg text-xs font-semibold">
                                            ✅ Onaylı
                                        </span>
                                    <?php endif; ?>
                                    
                                    <!-- Silme Butonu -->
                                    <button onclick="deleteProvider(<?= $provider['id'] ?>, '<?= htmlspecialchars($provider['name']) ?>')"
                                            id="delete-provider-btn-<?= $provider['id'] ?>"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 rounded-lg text-xs font-semibold transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Sil
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

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
    <div class="mt-6 flex items-center justify-between">
        <div class="text-sm text-gray-600">
            Sayfa <?= $page ?> / <?= $totalPages ?> (Toplam <?= $totalProviders ?> usta)
        </div>
        <div class="flex gap-2">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?><?= !empty($searchQuery) ? '&search=' . urlencode($searchQuery) : '' ?><?= !empty($serviceTypeFilter) ? '&service_type=' . urlencode($serviceTypeFilter) : '' ?><?= !empty($statusFilter) ? '&status=' . urlencode($statusFilter) : '' ?><?= !empty($cityFilter) ? '&city=' . urlencode($cityFilter) : '' ?>" 
                   class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                    Önceki
                </a>
            <?php endif; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?><?= !empty($searchQuery) ? '&search=' . urlencode($searchQuery) : '' ?><?= !empty($serviceTypeFilter) ? '&service_type=' . urlencode($serviceTypeFilter) : '' ?><?= !empty($statusFilter) ? '&status=' . urlencode($statusFilter) : '' ?><?= !empty($cityFilter) ? '&city=' . urlencode($cityFilter) : '' ?>" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    Sonraki
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<script>
// CSRF Token
const csrfToken = '<?= generateCsrfToken() ?>';

/**
 * Toggle Filter Panel
 */
function toggleFilterPanel() {
    const panel = document.getElementById('filter-panel');
    const chevron = document.getElementById('filter-chevron');
    
    if (panel.classList.contains('hidden')) {
        panel.classList.remove('hidden');
        chevron.style.transform = 'rotate(180deg)';
    } else {
        panel.classList.add('hidden');
        chevron.style.transform = 'rotate(0deg)';
    }
}

/**
 * WhatsApp kanal kontrolü yap
 */
function checkWhatsAppChannel(providerId, phone, name) {
    // WhatsApp kanalını yeni sekmede aç
    window.open('https://whatsapp.com/channel/0029VbCCqZoI1rcjIn9IWV2l', '_blank');
    
    // Confirm dialog göster
    setTimeout(() => {
        const confirmed = confirm(
            `Usta Bilgileri:\n` +
            `📛 İsim: ${name}\n` +
            `📞 Telefon: ${phone}\n\n` +
            `WhatsApp kanalında bu telefon numarası var mı?\n\n` +
            `✅ EVET = Usta onaylanacak (Status: Active)\n` +
            `❌ HAYIR = İptal`
        );
        
        if (confirmed) {
            approveProvider(providerId, phone, name);
        }
    }, 1000);
}

/**
 * Ustayı onayla
 */
function approveProvider(providerId, phone, name) {
    // Loading indicator
    const loadingMsg = `Usta onaylanıyor: ${name}...`;
    console.log(loadingMsg);
    
    fetch('/admin/providers/approve', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            provider_id: providerId,
            csrf_token: csrfToken
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`✅ Başarılı!\n\n${name} onaylandı.\nStatus: Active olarak güncellendi.`);
            location.reload();
        } else {
            alert(`❌ Hata!\n\n${data.error || 'Usta onaylanamadı'}`);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Beklenmeyen bir hata oluştu.');
    });
}

/**
 * Provider silme fonksiyonu (2 aşamalı onay)
 */
function deleteProvider(providerId, providerName) {
    // İlk onay
    if (!confirm(`⚠️ DİKKAT! Provider'ı silmek istediğinize emin misiniz?\n\nProvider: ${providerName}\n\n• Tüm lead deliveries kayıtları silinecek\n• Tüm satın alma kayıtları silinecek\n• Provider hesabı kalıcı olarak silinecek\n• Bu işlem GERİ ALINAMAZ!\n\nDevam etmek için "Tamam" butonuna basın.`)) {
        return;
    }
    
    // İkinci onay: "SIL" yazması gerekiyor
    const confirmation = prompt(
        `Son onay adımı:\n\n` +
        `"${providerName}" adlı provider'ı kalıcı olarak silmek için\n` +
        `aşağıya büyük harflerle SIL yazın:`
    );
    
    if (confirmation !== 'SIL') {
        if (confirmation !== null) { // Kullanıcı iptal etmediyse
            alert('❌ İşlem iptal edildi.\n\nDoğru onay metni girilmedi. "SIL" yazmalısınız.');
        }
        return;
    }
    
    // Butonu disable et ve loading state
    const btn = document.getElementById(`delete-provider-btn-${providerId}`);
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin h-4 w-4 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    }
    
    // AJAX request
    fetch('/admin/providers/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            csrf_token: csrfToken,
            provider_id: providerId,
            confirmation: 'SIL'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`✅ ${data.message}\n\nSilinen Provider: ${data.deleted_provider}`);
            // Satırı fade out ile kaldır
            const row = btn.closest('tr');
            if (row) {
                row.style.transition = 'opacity 0.5s';
                row.style.opacity = '0';
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                location.reload();
            }
        } else {
            alert('❌ Hata: ' + (data.error || 'Provider silinemedi'));
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg> Sil';
            }
        }
    })
    .catch(error => {
        console.error('Delete provider error:', error);
        alert('❌ Bağlantı hatası oluştu');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg> Sil';
        }
    });
}
</script>

<?php
// İçeriği al
$content = ob_get_clean();

// Layout'u yükle
require __DIR__ . '/layout.php';
?>



