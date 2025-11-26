<?php
$pageTitle = 'Dashboard';
$currentPage = 'dashboard';
ob_start();
?>

<style>
    .stat-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    .stat-card.blue { border-left-color: #3b82f6; }
    .stat-card.green { border-left-color: #10b981; }
    .stat-card.purple { border-left-color: #8b5cf6; }
    .stat-card.orange { border-left-color: #f59e0b; }
    .stat-card.red { border-left-color: #ef4444; }
    .stat-card.cyan { border-left-color: #06b6d4; }
    
    .quick-action {
        transition: all 0.2s ease;
    }
    .quick-action:hover {
        transform: scale(1.02);
    }
    
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .pulse-dot {
        animation: pulse-dot 2s infinite;
    }
</style>

<!-- Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-500 text-sm mt-1"><?= date('l, d F Y') ?></p>
        </div>
        <a href="/admin/leads/create" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Yeni Lead
        </a>
    </div>
</div>

<!-- Acil Bildirimler -->
<?php if (($stats['pending_requests'] ?? 0) > 0): ?>
<div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 flex items-center gap-4">
    <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
        <span class="text-amber-600 text-xl">⚠️</span>
    </div>
    <div class="flex-1">
        <p class="font-semibold text-amber-800"><?= $stats['pending_requests'] ?> bekleyen lead talebi var</p>
        <p class="text-sm text-amber-600">Ustalar lead bekliyor, hemen gönderim yapın.</p>
    </div>
    <a href="/admin/lead-requests" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition-colors">
        Taleplere Git →
    </a>
</div>
<?php endif; ?>

<!-- Ana İstatistikler -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    <!-- Toplam Lead -->
    <div class="stat-card blue bg-white rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Toplam</span>
            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['total_leads']) ?></p>
        <a href="/admin/leads" class="text-xs text-blue-600 hover:underline">Tümünü gör →</a>
    </div>
    
    <!-- Yeni Lead -->
    <div class="stat-card green bg-white rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Yeni</span>
            <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['new_leads']) ?></p>
        <a href="/admin/leads?status=new" class="text-xs text-green-600 hover:underline">Görüntüle →</a>
    </div>
    
    <!-- Doğrulanmış -->
    <div class="stat-card purple bg-white rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Doğrulanmış</span>
            <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['verified_leads']) ?></p>
        <a href="/admin/leads?status=verified" class="text-xs text-purple-600 hover:underline">Görüntüle →</a>
    </div>
    
    <!-- Satılan -->
    <div class="stat-card cyan bg-white rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Satılan</span>
            <div class="w-8 h-8 bg-cyan-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['sold_leads']) ?></p>
        <a href="/admin/leads?status=sold" class="text-xs text-cyan-600 hover:underline">Görüntüle →</a>
    </div>
    
    <!-- Bugün -->
    <div class="stat-card orange bg-white rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Bugün</span>
            <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['today_leads']) ?></p>
        <span class="text-xs text-gray-400">Son 24 saat</span>
    </div>
    
    <!-- Bu Hafta -->
    <div class="stat-card red bg-white rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Bu Hafta</span>
            <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['week_leads']) ?></p>
        <span class="text-xs text-gray-400">7 günlük</span>
    </div>
</div>

<!-- İkinci Sıra: Ustalar ve Gelir -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Aktif Ustalar -->
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Aktif Ustalar</p>
                <p class="text-xl font-bold text-gray-900"><?= $stats['active_providers'] ?> <span class="text-sm font-normal text-gray-400">/ <?= $stats['total_providers'] ?></span></p>
            </div>
        </div>
        <a href="/admin/providers" class="text-xs text-emerald-600 hover:underline">Tümünü gör →</a>
    </div>
    
    <!-- Onay Bekleyen -->
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Onay Bekleyen</p>
                <p class="text-xl font-bold text-gray-900"><?= $stats['pending_providers'] ?></p>
            </div>
        </div>
        <a href="/admin/providers?status=pending" class="text-xs text-yellow-600 hover:underline">Görüntüle →</a>
    </div>
    
    <!-- Toplam Gelir -->
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Toplam Gelir</p>
                <p class="text-xl font-bold text-gray-900"><?= number_format($stats['total_revenue'], 0) ?> <span class="text-sm font-normal">SAR</span></p>
            </div>
        </div>
        <a href="/admin/purchases" class="text-xs text-indigo-600 hover:underline">Detaylar →</a>
    </div>
    
    <!-- Satın Almalar -->
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-pink-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Satın Almalar</p>
                <p class="text-xl font-bold text-gray-900"><?= $stats['total_purchases'] ?></p>
            </div>
        </div>
        <a href="/admin/purchases" class="text-xs text-pink-600 hover:underline">Görüntüle →</a>
    </div>
</div>

<!-- Alt Bölüm: Bekleyen Talepler ve Son Leadler -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    
    <!-- Bekleyen Lead Talepleri -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-amber-500 rounded-full pulse-dot"></div>
                <h3 class="font-semibold text-gray-900">Bekleyen Talepler</h3>
                <?php if (($stats['pending_requests'] ?? 0) > 0): ?>
                <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-medium rounded-full"><?= $stats['pending_requests'] ?></span>
                <?php endif; ?>
            </div>
            <a href="/admin/lead-requests" class="text-sm text-blue-600 hover:underline">Tümü →</a>
        </div>
        <div class="divide-y divide-gray-50">
            <?php if (empty($pendingRequests)): ?>
            <div class="p-8 text-center">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-gray-500 text-sm">Bekleyen talep yok</p>
            </div>
            <?php else: ?>
            <?php foreach ($pendingRequests as $req): ?>
            <div class="px-5 py-3 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900 text-sm"><?= htmlspecialchars($req['business_name'] ?? 'Usta') ?></p>
                        <p class="text-xs text-gray-500"><?= htmlspecialchars($req['package_name'] ?? 'Paket') ?> • <?= $req['remaining_leads'] ?? 0 ?> kalan</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400"><?= date('H:i', strtotime($req['requested_at'])) ?></p>
                        <a href="/admin/lead-requests" class="text-xs text-blue-600 hover:underline">Gönder</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Son Leadler -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Son Leadler</h3>
            <a href="/admin/leads" class="text-sm text-blue-600 hover:underline">Tümü →</a>
        </div>
        <div class="divide-y divide-gray-50">
            <?php if (empty($recentLeads)): ?>
            <div class="p-8 text-center">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-gray-500 text-sm">Henüz lead yok</p>
            </div>
            <?php else: ?>
            <?php foreach ($recentLeads as $lead): ?>
            <?php
            $statusColors = [
                'new' => 'bg-blue-100 text-blue-700',
                'verified' => 'bg-purple-100 text-purple-700',
                'sold' => 'bg-green-100 text-green-700',
                'pending' => 'bg-orange-100 text-orange-700',
                'invalid' => 'bg-red-100 text-red-700'
            ];
            $statusLabels = [
                'new' => 'Yeni',
                'verified' => 'Doğrulanmış',
                'sold' => 'Satılan',
                'pending' => 'Beklemede',
                'invalid' => 'Geçersiz'
            ];
            $status = $lead['status'] ?? 'new';
            ?>
            <a href="/admin/leads/<?= $lead['id'] ?>" class="block px-5 py-3 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-sm">
                            🔧
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm"><?= htmlspecialchars(getServiceTypes()[$lead['service_type']]['tr'] ?? $lead['service_type']) ?></p>
                            <p class="text-xs text-gray-500"><?= htmlspecialchars(getCities()[$lead['city']]['tr'] ?? $lead['city']) ?> • <?= htmlspecialchars($lead['phone']) ?></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full <?= $statusColors[$status] ?? 'bg-gray-100 text-gray-700' ?>">
                            <?= $statusLabels[$status] ?? $status ?>
                        </span>
                        <p class="text-xs text-gray-400 mt-1"><?= date('d.m H:i', strtotime($lead['created_at'])) ?></p>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Hızlı Erişim -->
<div class="mt-8">
    <h3 class="font-semibold text-gray-900 mb-4">Hızlı Erişim</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
        <a href="/admin/leads?status=new" class="quick-action bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center hover:border-blue-200 hover:bg-blue-50">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-700">Yeni Leadler</p>
        </a>
        
        <a href="/admin/lead-requests" class="quick-action bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center hover:border-amber-200 hover:bg-amber-50">
            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-700">Lead Talepleri</p>
        </a>
        
        <a href="/admin/providers?status=pending" class="quick-action bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center hover:border-yellow-200 hover:bg-yellow-50">
            <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-700">Onay Bekleyen</p>
        </a>
        
        <a href="/admin/lead-packages" class="quick-action bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center hover:border-purple-200 hover:bg-purple-50">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-700">Paketler</p>
        </a>
        
        <a href="/admin/services" class="quick-action bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center hover:border-green-200 hover:bg-green-50">
            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-700">Hizmetler</p>
        </a>
        
        <a href="/admin/purchases" class="quick-action bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center hover:border-indigo-200 hover:bg-indigo-50">
            <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-700">Satışlar</p>
        </a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
