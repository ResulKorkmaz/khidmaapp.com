<?php
/**
 * Admin Sidebar Component
 * 
 * Sol menü
 * 
 * @param string $currentPage Aktif sayfa
 * @param string $currentUserRole Kullanıcı rolü
 * @param int $newLeadsCount Yeni lead sayısı
 * @param int $pendingProvidersCount Bekleyen usta sayısı
 * @param int $newPurchasesCount Yeni satın alma sayısı
 * @param int $pendingLeadRequestsCount Bekleyen lead talep sayısı
 */

$currentPage = $currentPage ?? '';
$currentUserRole = $currentUserRole ?? 'user';
$newLeadsCount = $newLeadsCount ?? 0;
$pendingProvidersCount = $pendingProvidersCount ?? 0;
$newPurchasesCount = $newPurchasesCount ?? 0;
$pendingLeadRequestsCount = $pendingLeadRequestsCount ?? 0;

// Menü öğeleri
$menuItems = [
    [
        'href' => '/admin',
        'label' => 'Dashboard',
        'page' => 'dashboard',
        'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        'badge' => 0,
        'highlight' => false,
    ],
    [
        'href' => '/admin/lead-requests',
        'label' => 'Lead İstekleri',
        'page' => 'lead-requests',
        'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
        'badge' => $pendingLeadRequestsCount,
        'highlight' => true,
        'highlightClass' => 'bg-orange-50 text-orange-700 hover:bg-orange-100 border-2 border-orange-200',
        'activeClass' => 'bg-orange-600 text-white shadow-lg',
    ],
    [
        'href' => '/admin/leads',
        'label' => 'Lead\'ler',
        'page' => 'leads',
        'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
        'badge' => $newLeadsCount,
    ],
    [
        'href' => '/admin/providers',
        'label' => 'Ustalar',
        'page' => 'providers',
        'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
        'badge' => $pendingProvidersCount,
    ],
    [
        'href' => '/admin/purchases',
        'label' => 'Satın Alımlar',
        'page' => 'purchases',
        'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
        'badge' => $newPurchasesCount,
    ],
    [
        'href' => '/admin/refunds',
        'label' => 'İadeler',
        'page' => 'refunds',
        'icon' => 'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6',
        'badge' => 0,
    ],
];

$settingsItems = [
    [
        'href' => '/admin/services',
        'label' => 'Hizmetler',
        'page' => 'services',
        'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    ],
    [
        'href' => '/admin/lead-packages',
        'label' => 'Lead Paketleri',
        'page' => 'lead-packages',
        'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
    ],
    [
        'href' => '/admin/provider-messages',
        'label' => 'Provider Mesajları',
        'page' => 'provider-messages',
        'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    ],
];
?>

<!-- Desktop Header -->
<div class="hidden lg:flex flex-shrink-0 items-center gap-3 px-6 py-6 border-b border-gray-200">
    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center shadow-lg">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <div>
        <h2 class="text-lg font-bold text-gray-900">KhidmaApp</h2>
        <p class="text-xs text-gray-500">Admin Paneli</p>
    </div>
</div>

<!-- User Info (Mobile) -->
<div class="lg:hidden flex-shrink-0 px-6 py-4 border-b border-gray-200 bg-blue-50">
    <p class="text-sm font-semibold text-gray-900">Hoş geldiniz</p>
    <p class="text-xs text-gray-600"><?= htmlspecialchars($_SESSION['admin_username'] ?? '') ?></p>
</div>

<!-- Navigation Menu -->
<nav class="flex-1 py-4 overflow-y-auto min-h-0">
    <ul class="space-y-1 px-3">
        <?php foreach ($menuItems as $item): ?>
            <?php 
            $isActive = $currentPage === $item['page'];
            $hasHighlight = $item['highlight'] ?? false;
            
            if ($isActive) {
                $linkClass = $item['activeClass'] ?? 'bg-blue-600 text-white shadow-lg';
            } elseif ($hasHighlight) {
                $linkClass = $item['highlightClass'] ?? 'text-gray-700 hover:bg-gray-100';
            } else {
                $linkClass = 'text-gray-700 hover:bg-gray-100';
            }
            ?>
            <li>
                <a href="<?= $item['href'] ?>" 
                   class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all <?= $linkClass ?>">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $item['icon'] ?>"/>
                        </svg>
                        <span class="font-semibold"><?= $item['label'] ?></span>
                    </div>
                    <?php if (($item['badge'] ?? 0) > 0): ?>
                        <span class="ml-auto bg-red-600 text-white text-sm font-bold min-w-[24px] h-6 px-2 flex items-center justify-center rounded-full flex-shrink-0">
                            <?= $item['badge'] ?>
                        </span>
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; ?>
        
        <!-- Divider -->
        <li class="pt-4">
            <div class="px-4 mb-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Ayarlar</p>
            </div>
        </li>
        
        <?php foreach ($settingsItems as $item): ?>
            <?php $isActive = $currentPage === $item['page']; ?>
            <li>
                <a href="<?= $item['href'] ?>" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= $isActive ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $item['icon'] ?>"/>
                    </svg>
                    <span class="font-semibold"><?= $item['label'] ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<!-- User Info (Desktop) -->
<div class="hidden lg:block flex-shrink-0 px-6 py-4 border-t border-gray-200 relative">
    <button id="profileMenuToggle" class="w-full flex items-center gap-3 hover:bg-gray-50 p-2 rounded-lg transition-colors group">
        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center group-hover:bg-blue-100 transition-colors">
            <svg class="w-6 h-6 text-gray-600 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0 text-left">
            <p class="text-sm font-semibold text-gray-900 truncate"><?= htmlspecialchars($_SESSION['admin_username'] ?? '') ?></p>
            <p class="text-xs text-gray-500">Yönetici</p>
        </div>
        <svg class="w-4 h-4 text-gray-400 transition-transform" id="profileMenuChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    
    <!-- Dropdown Menu -->
    <div id="profileDropdown" class="hidden absolute bottom-full left-0 right-0 mb-2 mx-4 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden z-50">
        <?php if ($currentUserRole === 'super_admin'): ?>
        <a href="/admin/users" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span class="text-sm font-medium text-gray-700">Kullanıcı Yönetimi</span>
        </a>
        <div class="border-t border-gray-100"></div>
        <?php endif; ?>
        <a href="/admin/logout" class="flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition-colors">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            <span class="text-sm font-medium text-red-600">Çıkış Yap</span>
        </a>
    </div>
</div>

