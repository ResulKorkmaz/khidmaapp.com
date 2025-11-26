<?php
$pageTitle = 'Dashboard';
$currentPage = 'dashboard';
ob_start();
?>

<style>
:root {
    --primary: #2563eb;
    --success: #059669;
    --warning: #d97706;
    --danger: #dc2626;
    --purple: #7c3aed;
    --cyan: #0891b2;
}

.dashboard-card {
    background: white;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
}

.dashboard-card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transform: translateY(-1px);
}

.metric-value {
    font-size: 2.5rem;
    font-weight: 700;
    line-height: 1;
    background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.metric-card {
    position: relative;
    overflow: hidden;
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    border-radius: 16px 16px 0 0;
}

.metric-card.blue::before { background: var(--primary); }
.metric-card.green::before { background: var(--success); }
.metric-card.purple::before { background: var(--purple); }
.metric-card.orange::before { background: var(--warning); }
.metric-card.cyan::before { background: var(--cyan); }
.metric-card.red::before { background: var(--danger); }

.icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-box.blue { background: #dbeafe; color: var(--primary); }
.icon-box.green { background: #d1fae5; color: var(--success); }
.icon-box.purple { background: #ede9fe; color: var(--purple); }
.icon-box.orange { background: #fef3c7; color: var(--warning); }
.icon-box.cyan { background: #cffafe; color: var(--cyan); }
.icon-box.red { background: #fee2e2; color: var(--danger); }
.icon-box.pink { background: #fce7f3; color: #db2777; }
.icon-box.indigo { background: #e0e7ff; color: #4f46e5; }

.alert-banner {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border: 1px solid #fcd34d;
    border-radius: 16px;
    padding: 16px 20px;
}

.list-item {
    padding: 14px 20px;
    border-bottom: 1px solid #f3f4f6;
    transition: background 0.15s ease;
}

.list-item:last-child {
    border-bottom: none;
}

.list-item:hover {
    background: #f9fafb;
}

.status-badge {
    font-size: 11px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.quick-btn {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 16px;
    text-align: center;
    transition: all 0.2s ease;
    text-decoration: none;
    display: block;
}

.quick-btn:hover {
    border-color: #3b82f6;
    background: #eff6ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

@keyframes pulse-ring {
    0% { transform: scale(1); opacity: 1; }
    100% { transform: scale(1.5); opacity: 0; }
}

.pulse-indicator {
    position: relative;
}

.pulse-indicator::after {
    content: '';
    position: absolute;
    width: 8px;
    height: 8px;
    background: #f59e0b;
    border-radius: 50%;
    animation: pulse-ring 1.5s infinite;
}
</style>

<!-- Header Section -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Dashboard</h1>
        <p class="text-gray-500"><?= date('l, d F Y') ?></p>
    </div>
    <a href="/admin/leads/create" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all shadow-sm hover:shadow-md">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Yeni Lead Ekle
    </a>
</div>

<!-- Alert Banner -->
<?php if (($stats['pending_requests'] ?? 0) > 0): ?>
<div class="alert-banner flex items-center gap-4 mb-8">
    <div class="w-12 h-12 bg-amber-200 rounded-full flex items-center justify-center flex-shrink-0">
        <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
    </div>
    <div class="flex-1">
        <p class="font-bold text-amber-900"><?= $stats['pending_requests'] ?> Bekleyen Lead Talebi</p>
        <p class="text-sm text-amber-700">Ustalar müşteri bilgisi bekliyor. Hemen işlem yapın.</p>
    </div>
    <a href="/admin/lead-requests" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-xl transition-colors flex-shrink-0">
        Talepleri Gör →
    </a>
</div>
<?php endif; ?>

<!-- Main Stats Grid -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    <!-- Toplam Lead -->
    <div class="dashboard-card metric-card blue p-5">
        <div class="flex items-start justify-between mb-4">
            <div class="icon-box blue">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
        <div class="metric-value mb-1"><?= number_format($stats['total_leads'] ?? 0) ?></div>
        <p class="text-sm text-gray-500 font-medium">Toplam Lead</p>
        <a href="/admin/leads" class="text-xs text-blue-600 hover:text-blue-700 font-medium mt-2 inline-block">Tümünü gör →</a>
    </div>

    <!-- Yeni -->
    <div class="dashboard-card metric-card green p-5">
        <div class="flex items-start justify-between mb-4">
            <div class="icon-box green">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
        </div>
        <div class="metric-value mb-1"><?= number_format($stats['new_leads'] ?? 0) ?></div>
        <p class="text-sm text-gray-500 font-medium">Yeni Lead</p>
        <a href="/admin/leads?status=new" class="text-xs text-emerald-600 hover:text-emerald-700 font-medium mt-2 inline-block">Görüntüle →</a>
    </div>

    <!-- Doğrulanmış -->
    <div class="dashboard-card metric-card purple p-5">
        <div class="flex items-start justify-between mb-4">
            <div class="icon-box purple">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div class="metric-value mb-1"><?= number_format($stats['verified_leads'] ?? 0) ?></div>
        <p class="text-sm text-gray-500 font-medium">Doğrulanmış</p>
        <a href="/admin/leads?status=verified" class="text-xs text-purple-600 hover:text-purple-700 font-medium mt-2 inline-block">Görüntüle →</a>
    </div>

    <!-- Satılan -->
    <div class="dashboard-card metric-card cyan p-5">
        <div class="flex items-start justify-between mb-4">
            <div class="icon-box cyan">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>
        <div class="metric-value mb-1"><?= number_format($stats['sold_leads'] ?? 0) ?></div>
        <p class="text-sm text-gray-500 font-medium">Satılan</p>
        <a href="/admin/leads?status=sold" class="text-xs text-cyan-600 hover:text-cyan-700 font-medium mt-2 inline-block">Görüntüle →</a>
    </div>

    <!-- Bugün -->
    <div class="dashboard-card metric-card orange p-5">
        <div class="flex items-start justify-between mb-4">
            <div class="icon-box orange">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div class="metric-value mb-1"><?= number_format($stats['today_leads'] ?? 0) ?></div>
        <p class="text-sm text-gray-500 font-medium">Bugün</p>
        <span class="text-xs text-gray-400 mt-2 inline-block">Son 24 saat</span>
    </div>

    <!-- Bu Hafta -->
    <div class="dashboard-card metric-card red p-5">
        <div class="flex items-start justify-between mb-4">
            <div class="icon-box red">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <div class="metric-value mb-1"><?= number_format($stats['week_leads'] ?? 0) ?></div>
        <p class="text-sm text-gray-500 font-medium">Bu Hafta</p>
        <span class="text-xs text-gray-400 mt-2 inline-block">7 günlük</span>
    </div>
</div>

<!-- Secondary Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Aktif Ustalar -->
    <div class="dashboard-card p-5">
        <div class="flex items-center gap-4">
            <div class="icon-box green">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm text-gray-500">Aktif Ustalar</p>
                <p class="text-2xl font-bold text-gray-900"><?= $stats['active_providers'] ?? 0 ?> <span class="text-sm font-normal text-gray-400">/ <?= $stats['total_providers'] ?? 0 ?></span></p>
            </div>
        </div>
        <a href="/admin/providers" class="text-xs text-emerald-600 hover:text-emerald-700 font-medium mt-3 inline-block">Tümünü gör →</a>
    </div>

    <!-- Onay Bekleyen -->
    <div class="dashboard-card p-5">
        <div class="flex items-center gap-4">
            <div class="icon-box orange">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm text-gray-500">Onay Bekleyen</p>
                <p class="text-2xl font-bold text-gray-900"><?= $stats['pending_providers'] ?? 0 ?></p>
            </div>
        </div>
        <a href="/admin/providers?status=pending" class="text-xs text-amber-600 hover:text-amber-700 font-medium mt-3 inline-block">Görüntüle →</a>
    </div>

    <!-- Toplam Gelir -->
    <div class="dashboard-card p-5">
        <div class="flex items-center gap-4">
            <div class="icon-box indigo">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm text-gray-500">Toplam Gelir</p>
                <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['total_revenue'] ?? 0, 0) ?> <span class="text-sm font-normal text-gray-400">SAR</span></p>
            </div>
        </div>
        <a href="/admin/purchases" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium mt-3 inline-block">Detaylar →</a>
    </div>

    <!-- Satın Almalar -->
    <div class="dashboard-card p-5">
        <div class="flex items-center gap-4">
            <div class="icon-box pink">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm text-gray-500">Satın Almalar</p>
                <p class="text-2xl font-bold text-gray-900"><?= $stats['total_purchases'] ?? 0 ?></p>
            </div>
        </div>
        <a href="/admin/purchases" class="text-xs text-pink-600 hover:text-pink-700 font-medium mt-3 inline-block">Görüntüle →</a>
    </div>
</div>

<!-- Two Column Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Bekleyen Talepler -->
    <div class="dashboard-card overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h3 class="font-bold text-gray-900">Bekleyen Talepler</h3>
                <?php if (($stats['pending_requests'] ?? 0) > 0): ?>
                <span class="px-2.5 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full"><?= $stats['pending_requests'] ?></span>
                <?php endif; ?>
            </div>
            <a href="/admin/lead-requests" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Tümü →</a>
        </div>
        
        <?php if (empty($pendingRequests)): ?>
        <div class="p-10 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-gray-500 font-medium">Bekleyen talep yok</p>
            <p class="text-sm text-gray-400 mt-1">Tüm talepler işlendi</p>
        </div>
        <?php else: ?>
        <div>
            <?php foreach ($pendingRequests as $req): ?>
            <div class="list-item flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                        <span class="text-amber-600 font-bold text-sm"><?= strtoupper(substr($req['business_name'] ?? 'U', 0, 1)) ?></span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900"><?= htmlspecialchars($req['business_name'] ?? 'Usta') ?></p>
                        <p class="text-xs text-gray-500"><?= htmlspecialchars($req['package_name'] ?? 'Paket') ?> • <?= $req['remaining_leads'] ?? 0 ?> kalan hak</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400 mb-1"><?= date('H:i', strtotime($req['requested_at'])) ?></p>
                    <a href="/admin/lead-requests" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Lead Gönder →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Son Leadler -->
    <div class="dashboard-card overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-900">Son Leadler</h3>
            <a href="/admin/leads" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Tümü →</a>
        </div>
        
        <?php if (empty($recentLeads)): ?>
        <div class="p-10 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-gray-500 font-medium">Henüz lead yok</p>
            <p class="text-sm text-gray-400 mt-1">Yeni lead ekleyin</p>
        </div>
        <?php else: ?>
        <?php 
        $statusColors = [
            'new' => 'bg-blue-100 text-blue-700',
            'verified' => 'bg-purple-100 text-purple-700',
            'sold' => 'bg-emerald-100 text-emerald-700',
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
        ?>
        <div>
            <?php foreach ($recentLeads as $lead): ?>
            <?php $status = $lead['status'] ?? 'new'; ?>
            <a href="/admin/leads/<?= $lead['id'] ?>" class="list-item flex items-center justify-between block">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                        <span class="text-lg">🔧</span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900"><?= htmlspecialchars(getServiceTypes()[$lead['service_type']]['tr'] ?? $lead['service_type']) ?></p>
                        <p class="text-xs text-gray-500"><?= htmlspecialchars(getCities()[$lead['city']]['tr'] ?? $lead['city']) ?> • <?= htmlspecialchars($lead['phone']) ?></p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="status-badge <?= $statusColors[$status] ?? 'bg-gray-100 text-gray-700' ?>"><?= $statusLabels[$status] ?? $status ?></span>
                    <p class="text-xs text-gray-400 mt-1"><?= date('d.m H:i', strtotime($lead['created_at'])) ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Quick Actions -->
<div class="dashboard-card p-5">
    <h3 class="font-bold text-gray-900 mb-4">Hızlı İşlemler</h3>
    <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
        <a href="/admin/leads?status=new" class="quick-btn">
            <div class="icon-box blue mx-auto mb-2" style="width:40px;height:40px;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <p class="text-xs font-medium text-gray-700">Yeni Leadler</p>
        </a>
        
        <a href="/admin/lead-requests" class="quick-btn">
            <div class="icon-box orange mx-auto mb-2" style="width:40px;height:40px;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <p class="text-xs font-medium text-gray-700">Lead Talepleri</p>
        </a>
        
        <a href="/admin/providers?status=pending" class="quick-btn">
            <div class="icon-box purple mx-auto mb-2" style="width:40px;height:40px;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <p class="text-xs font-medium text-gray-700">Onay Bekleyen</p>
        </a>
        
        <a href="/admin/lead-packages" class="quick-btn">
            <div class="icon-box cyan mx-auto mb-2" style="width:40px;height:40px;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <p class="text-xs font-medium text-gray-700">Paketler</p>
        </a>
        
        <a href="/admin/services" class="quick-btn">
            <div class="icon-box green mx-auto mb-2" style="width:40px;height:40px;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-xs font-medium text-gray-700">Hizmetler</p>
        </a>
        
        <a href="/admin/purchases" class="quick-btn">
            <div class="icon-box indigo mx-auto mb-2" style="width:40px;height:40px;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <p class="text-xs font-medium text-gray-700">Satışlar</p>
        </a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
