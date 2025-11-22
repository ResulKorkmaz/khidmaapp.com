<?php
$pageTitle = 'Dashboard';
$currentPage = 'dashboard';
ob_start();

// Ek istatistikler al
try {
    $providerStats = $pdo->query("
        SELECT 
            COUNT(*) as total_providers,
            SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_providers,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_providers
        FROM service_providers
    ")->fetch(PDO::FETCH_ASSOC);
    
    $purchaseStats = $pdo->query("
        SELECT 
            COUNT(*) as total_purchases,
            SUM(CASE WHEN payment_status = 'completed' THEN amount_paid ELSE 0 END) as total_revenue
        FROM provider_purchases
    ")->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $providerStats = ['total_providers' => 0, 'active_providers' => 0, 'pending_providers' => 0];
    $purchaseStats = ['total_purchases' => 0, 'total_revenue' => 0];
}
?>

<!-- Welcome Header -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                👋 Hoş geldiniz, <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?>
            </h1>
            <p class="text-gray-600">İşte bugünkü özet bilgileriniz</p>
        </div>
        <div class="flex gap-3">
            <a href="/admin/leads/create" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl shadow-sm transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Yeni Lead
            </a>
        </div>
    </div>
</div>

<!-- Ana İstatistik Kartları -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Toplam Lead -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Toplam</span>
        </div>
        <div class="text-4xl font-bold text-gray-900 mb-1"><?= number_format($stats['total_leads']) ?></div>
        <div class="text-sm text-gray-600 font-medium">Toplam Lead</div>
        <a href="/admin/leads" class="text-xs text-blue-600 hover:text-blue-700 font-medium mt-3 inline-block">Tümünü gör →</a>
    </div>

    <!-- Yeni Lead -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">Aktif</span>
        </div>
        <div class="text-4xl font-bold text-gray-900 mb-1"><?= number_format($stats['new_leads']) ?></div>
        <div class="text-sm text-gray-600 font-medium">Yeni Lead</div>
        <a href="/admin/leads?status=new" class="text-xs text-green-600 hover:text-green-700 font-medium mt-3 inline-block">Görüntüle →</a>
    </div>

    <!-- Satılan Lead -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-3 py-1 rounded-full">Tamamlanan</span>
        </div>
        <div class="text-4xl font-bold text-gray-900 mb-1"><?= number_format($stats['sold_leads']) ?></div>
        <div class="text-sm text-gray-600 font-medium">Satılan Lead</div>
        <a href="/admin/leads?status=sold" class="text-xs text-purple-600 hover:text-purple-700 font-medium mt-3 inline-block">Detaylar →</a>
    </div>

    <!-- Bugünkü Lead -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-orange-600 bg-orange-50 px-3 py-1 rounded-full">Bugün</span>
        </div>
        <div class="text-4xl font-bold text-gray-900 mb-1"><?= number_format($stats['today_leads']) ?></div>
        <div class="text-sm text-gray-600 font-medium">Bugünkü Lead</div>
        <div class="text-xs text-gray-500 mt-3">Son 24 saat</div>
    </div>
</div>

<!-- İkinci Sıra İstatistikler -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Ustalar -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Ustalar</h3>
            <a href="/admin/providers" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Tümü →</a>
        </div>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-green-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">Aktif Usta</div>
                        <div class="text-xs text-gray-500"><?= number_format(($providerStats['active_providers'] / max($providerStats['total_providers'], 1)) * 100, 1) ?>% toplam</div>
                    </div>
                </div>
                <div class="text-2xl font-bold text-green-600"><?= number_format($providerStats['active_providers']) ?></div>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">Beklemede</div>
                        <div class="text-xs text-gray-500">Onay bekliyor</div>
                    </div>
                </div>
                <div class="text-2xl font-bold text-yellow-600"><?= number_format($providerStats['pending_providers']) ?></div>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">Toplam Usta</div>
                        <div class="text-xs text-gray-500">Tüm kayıtlar</div>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-600"><?= number_format($providerStats['total_providers']) ?></div>
            </div>
        </div>
    </div>

    <!-- Gelir İstatistikleri -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Gelir</h3>
            <a href="/admin/purchases" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Detay →</a>
        </div>
        <div class="space-y-4">
            <div class="bg-indigo-50 rounded-xl p-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">Toplam Gelir</div>
                        <div class="text-xs text-gray-500">Tüm zamanlar</div>
                    </div>
                </div>
                <div class="text-3xl font-bold text-indigo-600"><?= number_format($purchaseStats['total_revenue'], 2) ?> <span class="text-lg">SAR</span></div>
            </div>
            
            <div class="bg-cyan-50 rounded-xl p-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">Toplam Satın Alma</div>
                        <div class="text-xs text-gray-500">Lead paketleri</div>
                    </div>
                </div>
                <div class="text-3xl font-bold text-cyan-600"><?= number_format($purchaseStats['total_purchases']) ?></div>
            </div>
            
            <div class="bg-teal-50 rounded-xl p-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">Ortalama Sepet</div>
                        <div class="text-xs text-gray-500">Per işlem</div>
                    </div>
                </div>
                <div class="text-2xl font-bold text-teal-600">
                    <?= $purchaseStats['total_purchases'] > 0 ? number_format($purchaseStats['total_revenue'] / $purchaseStats['total_purchases'], 2) : '0.00' ?> <span class="text-base">SAR</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Hızlı Erişim -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Hızlı Erişim</h3>
        <div class="space-y-2">
            <a href="/admin/leads?status=new" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-blue-50 rounded-xl transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Yeni Lead'ler</span>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            
            <a href="/admin/providers?status=pending" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-yellow-50 rounded-xl transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-yellow-100 group-hover:bg-yellow-200 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Onay Bekleyenler</span>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-yellow-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            
            <a href="/admin/lead-packages" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-purple-50 rounded-xl transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-purple-100 group-hover:bg-purple-200 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Lead Paketleri</span>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            
            <a href="/admin/services" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-green-50 rounded-xl transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Hizmet Türleri</span>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            
            <a href="/admin/purchases" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-indigo-50 rounded-xl transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-indigo-100 group-hover:bg-indigo-200 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Satın Almalar</span>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- Son Lead'ler Tablosu -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Son Lead'ler</h2>
            <p class="text-sm text-gray-600 mt-1">En son eklenen müşteri talepleri</p>
        </div>
        <a href="/admin/leads" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl transition-all text-sm flex items-center gap-2">
            Tümünü Gör
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
    
    <?php if (empty($recentLeads)): ?>
        <div class="text-center py-16">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="text-gray-500 text-lg font-medium">Henüz lead bulunmuyor</p>
            <p class="text-gray-400 text-sm mt-2">Yeni lead eklendiğinde burada görünecek</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-4 px-4 text-sm font-bold text-gray-700 rounded-tl-xl">ID</th>
                        <th class="text-left py-4 px-4 text-sm font-bold text-gray-700">Hizmet</th>
                        <th class="text-left py-4 px-4 text-sm font-bold text-gray-700">Şehir</th>
                        <th class="text-left py-4 px-4 text-sm font-bold text-gray-700">Telefon</th>
                        <th class="text-left py-4 px-4 text-sm font-bold text-gray-700">Durum</th>
                        <th class="text-left py-4 px-4 text-sm font-bold text-gray-700 rounded-tr-xl">Tarih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($recentLeads as $lead): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-4">
                                <span class="text-sm font-bold text-gray-900">#<?= str_pad($lead['id'], 6, '0', STR_PAD_LEFT) ?></span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <span class="text-sm">🔧</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars(getServiceTypes()[$lead['service_type']]['tr'] ?? $lead['service_type']) ?>
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-sm text-gray-700">
                                    <?= htmlspecialchars(getCities()[$lead['city']]['tr'] ?? $lead['city']) ?>
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-sm font-mono text-gray-600"><?= htmlspecialchars($lead['phone']) ?></span>
                            </td>
                            <td class="py-4 px-4">
                                <?php
                                $statusConfig = [
                                    'new' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Yeni', 'icon' => '⚡'],
                                    'verified' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Doğrulanmış', 'icon' => '✓'],
                                    'pending' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Beklemede', 'icon' => '⏱'],
                                    'sold' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Satılan', 'icon' => '✓'],
                                    'invalid' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Geçersiz', 'icon' => '✕']
                                ];
                                $status = $lead['status'] ?? 'new';
                                $config = $statusConfig[$status] ?? $statusConfig['new'];
                                ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold <?= $config['bg'] ?> <?= $config['text'] ?>">
                                    <span><?= $config['icon'] ?></span>
                                    <?= $config['label'] ?>
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-sm text-gray-600">
                                    <?= date('d.m.Y', strtotime($lead['created_at'])) ?>
                                </div>
                                <div class="text-xs text-gray-400">
                                    <?= date('H:i', strtotime($lead['created_at'])) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>

$currentPage = 'dashboard';
ob_start();

// Ek istatistikler al
try {
    $providerStats = $pdo->query("
        SELECT 
            COUNT(*) as total_providers,
            SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_providers,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_providers
        FROM service_providers
    ")->fetch(PDO::FETCH_ASSOC);
    
    $purchaseStats = $pdo->query("
        SELECT 
            COUNT(*) as total_purchases,
            SUM(CASE WHEN payment_status = 'completed' THEN amount_paid ELSE 0 END) as total_revenue
        FROM provider_purchases
    ")->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $providerStats = ['total_providers' => 0, 'active_providers' => 0, 'pending_providers' => 0];
    $purchaseStats = ['total_purchases' => 0, 'total_revenue' => 0];
}
?>

<!-- Welcome Header -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                👋 Hoş geldiniz, <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?>
            </h1>
            <p class="text-gray-600">İşte bugünkü özet bilgileriniz</p>
        </div>
        <div class="flex gap-3">
            <a href="/admin/leads/create" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl shadow-sm transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Yeni Lead
            </a>
        </div>
    </div>
</div>

<!-- Ana İstatistik Kartları -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Toplam Lead -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Toplam</span>
        </div>
        <div class="text-4xl font-bold text-gray-900 mb-1"><?= number_format($stats['total_leads']) ?></div>
        <div class="text-sm text-gray-600 font-medium">Toplam Lead</div>
        <a href="/admin/leads" class="text-xs text-blue-600 hover:text-blue-700 font-medium mt-3 inline-block">Tümünü gör →</a>
    </div>

    <!-- Yeni Lead -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">Aktif</span>
        </div>
        <div class="text-4xl font-bold text-gray-900 mb-1"><?= number_format($stats['new_leads']) ?></div>
        <div class="text-sm text-gray-600 font-medium">Yeni Lead</div>
        <a href="/admin/leads?status=new" class="text-xs text-green-600 hover:text-green-700 font-medium mt-3 inline-block">Görüntüle →</a>
    </div>

    <!-- Satılan Lead -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-3 py-1 rounded-full">Tamamlanan</span>
        </div>
        <div class="text-4xl font-bold text-gray-900 mb-1"><?= number_format($stats['sold_leads']) ?></div>
        <div class="text-sm text-gray-600 font-medium">Satılan Lead</div>
        <a href="/admin/leads?status=sold" class="text-xs text-purple-600 hover:text-purple-700 font-medium mt-3 inline-block">Detaylar →</a>
    </div>

    <!-- Bugünkü Lead -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-orange-600 bg-orange-50 px-3 py-1 rounded-full">Bugün</span>
        </div>
        <div class="text-4xl font-bold text-gray-900 mb-1"><?= number_format($stats['today_leads']) ?></div>
        <div class="text-sm text-gray-600 font-medium">Bugünkü Lead</div>
        <div class="text-xs text-gray-500 mt-3">Son 24 saat</div>
    </div>
</div>

<!-- İkinci Sıra İstatistikler -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Ustalar -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Ustalar</h3>
            <a href="/admin/providers" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Tümü →</a>
        </div>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-green-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">Aktif Usta</div>
                        <div class="text-xs text-gray-500"><?= number_format(($providerStats['active_providers'] / max($providerStats['total_providers'], 1)) * 100, 1) ?>% toplam</div>
                    </div>
                </div>
                <div class="text-2xl font-bold text-green-600"><?= number_format($providerStats['active_providers']) ?></div>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">Beklemede</div>
                        <div class="text-xs text-gray-500">Onay bekliyor</div>
                    </div>
                </div>
                <div class="text-2xl font-bold text-yellow-600"><?= number_format($providerStats['pending_providers']) ?></div>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">Toplam Usta</div>
                        <div class="text-xs text-gray-500">Tüm kayıtlar</div>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-600"><?= number_format($providerStats['total_providers']) ?></div>
            </div>
        </div>
    </div>

    <!-- Gelir İstatistikleri -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Gelir</h3>
            <a href="/admin/purchases" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Detay →</a>
        </div>
        <div class="space-y-4">
            <div class="bg-indigo-50 rounded-xl p-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">Toplam Gelir</div>
                        <div class="text-xs text-gray-500">Tüm zamanlar</div>
                    </div>
                </div>
                <div class="text-3xl font-bold text-indigo-600"><?= number_format($purchaseStats['total_revenue'], 2) ?> <span class="text-lg">SAR</span></div>
            </div>
            
            <div class="bg-cyan-50 rounded-xl p-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">Toplam Satın Alma</div>
                        <div class="text-xs text-gray-500">Lead paketleri</div>
                    </div>
                </div>
                <div class="text-3xl font-bold text-cyan-600"><?= number_format($purchaseStats['total_purchases']) ?></div>
            </div>
            
            <div class="bg-teal-50 rounded-xl p-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">Ortalama Sepet</div>
                        <div class="text-xs text-gray-500">Per işlem</div>
                    </div>
                </div>
                <div class="text-2xl font-bold text-teal-600">
                    <?= $purchaseStats['total_purchases'] > 0 ? number_format($purchaseStats['total_revenue'] / $purchaseStats['total_purchases'], 2) : '0.00' ?> <span class="text-base">SAR</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Hızlı Erişim -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Hızlı Erişim</h3>
        <div class="space-y-2">
            <a href="/admin/leads?status=new" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-blue-50 rounded-xl transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Yeni Lead'ler</span>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            
            <a href="/admin/providers?status=pending" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-yellow-50 rounded-xl transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-yellow-100 group-hover:bg-yellow-200 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Onay Bekleyenler</span>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-yellow-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            
            <a href="/admin/lead-packages" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-purple-50 rounded-xl transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-purple-100 group-hover:bg-purple-200 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Lead Paketleri</span>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            
            <a href="/admin/services" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-green-50 rounded-xl transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Hizmet Türleri</span>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            
            <a href="/admin/purchases" class="flex items-center justify-between p-3 bg-gray-50 hover:bg-indigo-50 rounded-xl transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-indigo-100 group-hover:bg-indigo-200 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Satın Almalar</span>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- Son Lead'ler Tablosu -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Son Lead'ler</h2>
            <p class="text-sm text-gray-600 mt-1">En son eklenen müşteri talepleri</p>
        </div>
        <a href="/admin/leads" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl transition-all text-sm flex items-center gap-2">
            Tümünü Gör
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
    
    <?php if (empty($recentLeads)): ?>
        <div class="text-center py-16">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="text-gray-500 text-lg font-medium">Henüz lead bulunmuyor</p>
            <p class="text-gray-400 text-sm mt-2">Yeni lead eklendiğinde burada görünecek</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-4 px-4 text-sm font-bold text-gray-700 rounded-tl-xl">ID</th>
                        <th class="text-left py-4 px-4 text-sm font-bold text-gray-700">Hizmet</th>
                        <th class="text-left py-4 px-4 text-sm font-bold text-gray-700">Şehir</th>
                        <th class="text-left py-4 px-4 text-sm font-bold text-gray-700">Telefon</th>
                        <th class="text-left py-4 px-4 text-sm font-bold text-gray-700">Durum</th>
                        <th class="text-left py-4 px-4 text-sm font-bold text-gray-700 rounded-tr-xl">Tarih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($recentLeads as $lead): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-4">
                                <span class="text-sm font-bold text-gray-900">#<?= str_pad($lead['id'], 6, '0', STR_PAD_LEFT) ?></span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <span class="text-sm">🔧</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars(getServiceTypes()[$lead['service_type']]['tr'] ?? $lead['service_type']) ?>
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-sm text-gray-700">
                                    <?= htmlspecialchars(getCities()[$lead['city']]['tr'] ?? $lead['city']) ?>
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-sm font-mono text-gray-600"><?= htmlspecialchars($lead['phone']) ?></span>
                            </td>
                            <td class="py-4 px-4">
                                <?php
                                $statusConfig = [
                                    'new' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Yeni', 'icon' => '⚡'],
                                    'verified' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Doğrulanmış', 'icon' => '✓'],
                                    'pending' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Beklemede', 'icon' => '⏱'],
                                    'sold' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Satılan', 'icon' => '✓'],
                                    'invalid' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Geçersiz', 'icon' => '✕']
                                ];
                                $status = $lead['status'] ?? 'new';
                                $config = $statusConfig[$status] ?? $statusConfig['new'];
                                ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold <?= $config['bg'] ?> <?= $config['text'] ?>">
                                    <span><?= $config['icon'] ?></span>
                                    <?= $config['label'] ?>
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-sm text-gray-600">
                                    <?= date('d.m.Y', strtotime($lead['created_at'])) ?>
                                </div>
                                <div class="text-xs text-gray-400">
                                    <?= date('H:i', strtotime($lead['created_at'])) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>


