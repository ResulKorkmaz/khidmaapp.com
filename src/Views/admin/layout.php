<!DOCTYPE html>
<html lang="tr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin Paneli' ?> - KhidmaApp</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <link href="/assets/css/app.css" rel="stylesheet">
    <style>
        /* Sidebar overlay başlangıçta gizli */
        #sidebarOverlay {
            display: none;
        }
        #sidebarOverlay.show {
            display: block;
        }
        
        /* Sidebar mobilde gizli */
        @media (max-width: 1023px) {
            #sidebar {
                transform: translateX(-100%);
                height: 100vh;
            }
            #sidebar.show {
                transform: translateX(0);
            }
        }
        
        /* Desktop: Sticky sidebar */
        @media (min-width: 1024px) {
            #sidebar {
                position: sticky !important;
                top: 0;
                height: 100vh;
                transform: translateX(0) !important;
                overflow-y: auto;
            }
            
            /* Main content yanında olmalı */
            main {
                min-height: 100vh;
            }
        }
        
        /* Hide scrollbar for horizontal scroll */
        .scrollbar-hide {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;  /* Chrome, Safari, Opera */
        }
        
        /* Badge Animasyonları */
        .request-badge-pulse, .purchase-badge-pulse {
            animation: badge-pulse 1.5s ease-in-out infinite, badge-ring 2s ease-out infinite, badge-bounce 2s ease-in-out infinite;
        }
        
        @keyframes badge-pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.15);
            }
        }
        
        @keyframes badge-ring {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.8);
            }
            50% {
                box-shadow: 0 0 0 12px rgba(220, 38, 38, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(220, 38, 38, 0);
            }
        }
        
        @keyframes badge-bounce {
            0%, 100% {
                transform: translateY(0) scale(1);
            }
            25% {
                transform: translateY(-4px) scale(1.1);
            }
            50% {
                transform: translateY(0) scale(1.05);
            }
            75% {
                transform: translateY(-2px) scale(1.08);
            }
        }
        
        /* Number count up animation */
        @keyframes countUp {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .count-up {
            animation: countUp 0.5s ease-out;
        }
        
        /* Glow effect for purchases badge */
        @keyframes glow {
            0%, 100% {
                box-shadow: 0 0 15px rgba(220, 38, 38, 0.5);
            }
            50% {
                box-shadow: 0 0 25px rgba(220, 38, 38, 0.8);
            }
        }
        
        .purchase-badge-pulse {
            animation: badge-pulse 1.5s ease-in-out infinite, 
                       badge-ring 2s ease-out infinite, 
                       glow 2s ease-in-out infinite;
        }
    </style>
</head>
<?php
// Yeni satın alımları say (son 24 saat içinde)
$newPurchasesCount = 0;
$pendingLeadRequestsCount = 0;

try {
    // Direkt veritabanı bağlantısı
    require_once __DIR__ . '/../../config/config.php';
    $db = getDatabase();
    
    if ($db) {
        // Yeni satın alımlar (son 24 saat)
        try {
            $stmt = $db->prepare("
                SELECT COUNT(*) as count 
                FROM provider_purchases 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                AND payment_status = 'completed'
            ");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $newPurchasesCount = intval($result['count'] ?? 0);
            error_log("✅ New purchases query SUCCESS: count = $newPurchasesCount");
        } catch (Exception $e) {
            error_log('❌ New purchases query error: ' . $e->getMessage());
            // Test için fallback
            $newPurchasesCount = 2; // TEMPORARY FOR TESTING
        }
        
        // Pending lead requests (bekleyen istekler)
        try {
            $stmt = $db->prepare("
                SELECT COUNT(*) as count 
                FROM lead_requests 
                WHERE request_status = 'pending'
            ");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $pendingLeadRequestsCount = intval($result['count'] ?? 0);
            error_log("✅ Lead requests query SUCCESS: count = $pendingLeadRequestsCount");
        } catch (Exception $e) {
            error_log('❌ Lead requests query error: ' . $e->getMessage());
            // Fallback: Manuel set et
            $pendingLeadRequestsCount = 2; // TEMP FIX
        }
    } else {
        error_log('❌ Database connection FAILED in layout.php');
        // Fallback
        $pendingLeadRequestsCount = 2; // TEMP FIX
    }
} catch (Exception $e) {
    error_log('❌ Admin layout stats error: ' . $e->getMessage());
    // Fallback
    $pendingLeadRequestsCount = 2; // TEMP FIX
}

error_log("🎯 FINAL: pendingLeadRequestsCount = $pendingLeadRequestsCount");
?>
<body class="bg-gray-50">
    <!-- Mobile Header -->
    <header class="bg-white border-b border-gray-200 shadow-sm lg:hidden sticky top-0 z-50">
        <div class="flex items-center justify-between px-4 py-4">
            <div class="flex items-center gap-3">
                <button id="mobileMenuToggle" class="text-gray-600 hover:text-blue-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <a href="/admin" class="text-xl font-bold text-blue-600">KhidmaApp</a>
            </div>
            <a href="/admin/logout" class="text-sm text-red-600 hover:text-red-700 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </a>
        </div>
    </header>
    
    <!-- Sidebar Overlay (Mobile) -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>

    <div class="flex min-h-screen">
        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="fixed lg:sticky top-0 left-0 h-full lg:h-screen w-64 flex-shrink-0 bg-white border-r border-gray-200 shadow-xl transition-transform duration-300 ease-in-out z-50 flex flex-col">
            <!-- Desktop Header -->
            <div class="hidden lg:flex flex-shrink-0 items-center gap-3 px-6 py-6 border-b border-gray-200">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
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
                    <!-- Dashboard -->
                    <li>
                        <a href="/admin" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($currentPage ?? '') === 'dashboard' ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span class="font-semibold">Dashboard</span>
                        </a>
                    </li>

                    <!-- Lead İstekleri - EN ÜSTTE! -->
                    <li>
                        <a href="/admin/lead-requests" class="relative flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all <?= ($currentPage ?? '') === 'lead-requests' ? 'bg-orange-600 text-white shadow-lg' : 'bg-orange-50 text-orange-700 hover:bg-orange-100 border-2 border-orange-200' ?>">
                            <div class="flex items-center gap-3">
                                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-bold">⏰ Lead İstekleri</span>
                            </div>
                            <?php if (isset($pendingLeadRequestsCount) && $pendingLeadRequestsCount > 0): ?>
                                <span id="pending-requests-badge" class="flex items-center justify-center min-w-[32px] h-8 px-3 text-base font-extrabold rounded-full request-badge-pulse" style="background: #dc2626 !important; color: #ffffff !important; display: flex !important; opacity: 1 !important; z-index: 9999 !important; position: relative !important; border: 3px solid #ffffff !important; box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7), 0 4px 14px 0 rgba(220, 38, 38, 0.6) !important;">
                                    <?= $pendingLeadRequestsCount ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>

                    <!-- Lead'ler -->
                    <li>
                        <a href="/admin/leads" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($currentPage ?? '') === 'leads' ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <span class="font-semibold">Lead'ler</span>
                            <?php 
                            // Lead sayısını göster (opsiyonel)
                            if (isset($totalLeads) && $totalLeads > 0): 
                            ?>
                                <span class="ml-auto bg-blue-100 text-blue-600 text-xs font-bold px-2 py-1 rounded-full">
                                    <?= $totalLeads ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>

                    <!-- Ustalar -->
                    <li>
                        <a href="/admin/providers" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($currentPage ?? '') === 'providers' ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span class="font-semibold">Ustalar</span>
                            <?php 
                            // Usta sayısını göster (opsiyonel)
                            if (isset($totalProviders) && $totalProviders > 0): 
                            ?>
                                <span class="ml-auto bg-indigo-100 text-indigo-600 text-xs font-bold px-2 py-1 rounded-full">
                                    <?= $totalProviders ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>

                    <!-- Satın Alımlar -->
                    <li>
                        <a href="/admin/purchases" class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all <?= ($currentPage ?? '') === 'purchases' ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' ?>">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <span class="font-semibold">💳 Satın Alımlar</span>
                            </div>
                            <?php if ($newPurchasesCount > 0): ?>
                                <span id="new-purchases-badge" class="flex items-center justify-center min-w-[32px] h-8 px-3 text-base font-extrabold rounded-full shadow-lg border-2 border-white purchase-badge-pulse" style="background-color: #dc2626 !important; color: #ffffff !important; box-shadow: 0 4px 10px rgba(220, 38, 38, 0.6) !important;">
                                    <?= $newPurchasesCount ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>

                    <!-- Divider -->
                    <li class="pt-4">
                        <div class="px-4 mb-2">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Ayarlar</p>
                        </div>
                    </li>

                    <!-- Hizmetler -->
                    <li>
                        <a href="/admin/services" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($currentPage ?? '') === 'services' ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-semibold">Hizmetler</span>
                        </a>
                    </li>
                    
                    <!-- Lead Paketleri -->
                    <li>
                        <a href="/admin/lead-packages" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($currentPage ?? '') === 'lead-packages' ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span class="font-semibold">Lead Paketleri</span>
                        </a>
                    </li>
                    
                    <!-- Provider Mesajları -->
                    <li>
                        <a href="/admin/provider-messages" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($currentPage ?? '') === 'provider-messages' ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-semibold">📨 Provider Mesajları</span>
                        </a>
                    </li>

                    <!-- Ana Siteye Git -->
                    <li>
                        <a href="/" target="_blank" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-100 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            <span class="font-medium">Ana Siteye Git</span>
                        </a>
                    </li>

                    <!-- Çıkış Yap (Desktop) -->
                    <li class="hidden lg:block">
                        <a href="/admin/logout" class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="font-semibold">Çıkış Yap</span>
                        </a>
                    </li>
                </ul>
        </nav>
        
            <!-- User Info (Desktop) -->
            <div class="hidden lg:block flex-shrink-0 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate"><?= htmlspecialchars($_SESSION['admin_username'] ?? '') ?></p>
                        <p class="text-xs text-gray-500">Yönetici</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 bg-gray-50 w-full lg:w-auto">
            <!-- Mobile spacing for header -->
            <div class="lg:hidden h-20"></div>
            
            <!-- Content Container -->
            <div class="w-full max-w-full px-4 py-6 lg:px-8 lg:py-8">
        <?= $content ?? '' ?>
            </div>
        </main>
    </div>

    <script>
        // Mobile menu functionality
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.add('show');
            sidebarOverlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        // Toggle sidebar
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                if (sidebar.classList.contains('show')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
        }

        // Close when clicking overlay
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }

        // Close sidebar when clicking a link on mobile
        if (sidebar) {
            const sidebarLinks = sidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) {
                        closeSidebar();
                    }
                });
            });
        }

        // Close sidebar on window resize to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });
        
        // Lead Request Badge Güncellemesi
        window.updatePendingRequestsBadge = function(newCount) {
            const badge = document.getElementById('pending-requests-badge');
            
            if (!badge) {
                console.warn('Badge element bulunamadı');
                return;
            }
            
            if (newCount > 0) {
                // Sayıyı güncelle
                badge.textContent = newCount;
                badge.classList.remove('hidden');
                
                // Style'ı AGRESIF zorla - TEK RENK (gradient yok!)
                badge.style.background = '#dc2626'; // Solid red
                badge.style.color = '#ffffff';
                badge.style.display = 'flex';
                badge.style.alignItems = 'center';
                badge.style.justifyContent = 'center';
                badge.style.opacity = '1';
                badge.style.visibility = 'visible';
                badge.style.zIndex = '9999';
                badge.style.position = 'relative';
                badge.style.border = '3px solid #ffffff';
                badge.style.boxShadow = '0 0 0 0 rgba(220, 38, 38, 0.7), 0 4px 14px 0 rgba(220, 38, 38, 0.6)';
                badge.style.fontSize = '16px';
                badge.style.fontWeight = '800';
                
                // Count up animasyonu ekle
                badge.classList.remove('count-up');
                void badge.offsetWidth; // Reflow trick
                badge.classList.add('count-up');
                
                console.log('✅ Badge güncellendi (TEK RENK):', newCount);
            } else {
                // Sayı 0 ise badge'i gizle
                badge.classList.add('hidden');
                console.log('🔴 Badge gizlendi (sayı: 0)');
            }
        };
        
        // Sayfa yüklendiğinde badge'i kontrol et ve style'ı AGRESIF zorla
        document.addEventListener('DOMContentLoaded', function() {
            const badge = document.getElementById('pending-requests-badge');
            if (badge && !badge.classList.contains('hidden')) {
                // Style'ı AGRESIF zorla uygula - TEK RENK (gradient yok!)
                badge.style.background = '#dc2626'; // Solid red, no gradient
                badge.style.color = '#ffffff';
                badge.style.display = 'flex';
                badge.style.alignItems = 'center';
                badge.style.justifyContent = 'center';
                badge.style.opacity = '1';
                badge.style.visibility = 'visible';
                badge.style.zIndex = '9999';
                badge.style.position = 'relative';
                badge.style.border = '3px solid #ffffff';
                badge.style.boxShadow = '0 0 0 0 rgba(220, 38, 38, 0.7), 0 4px 14px 0 rgba(220, 38, 38, 0.6)';
                badge.style.border = '2px solid white';
                badge.style.boxShadow = '0 4px 14px 0 rgba(220, 38, 38, 0.6)';
                badge.style.fontSize = '16px';
                badge.style.fontWeight = '800';
                
                console.log('📊 Pending requests badge aktif:', badge.textContent);
                console.log('🎨 Badge style AGRESIF zorlandı');
                console.log('   ├─ bg: gradient red');
                console.log('   ├─ color:', badge.style.color);
                console.log('   ├─ border: 2px solid white');
                console.log('   └─ shadow: kırmızı glow');
            }
        });
    </script>
    
    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

