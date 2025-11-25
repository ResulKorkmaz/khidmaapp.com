<?php
/**
 * Admin Layout
 * 
 * Admin paneli ana layout dosyası
 */

// Veritabanı istatistiklerini al
$currentUserRole = 'user';
$newLeadsCount = 0;
$pendingProvidersCount = 0;
$newPurchasesCount = 0;
$pendingLeadRequestsCount = 0;

try {
    $pdo = getDatabase();
    if ($pdo) {
        // Kullanıcı rolünü al
        $stmt = $pdo->prepare("SELECT role FROM admins WHERE id = ?");
        $stmt->execute([$_SESSION['admin_id'] ?? 0]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $currentUserRole = $result['role'] ?? 'user';
        
        // Yeni lead sayısı
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM leads WHERE status = 'new' AND deleted_at IS NULL");
        $newLeadsCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
        
        // Bekleyen usta sayısı
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM service_providers WHERE status = 'pending'");
        $pendingProvidersCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
        
        // Yeni satın almalar (son 24 saat)
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM provider_purchases WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR) AND payment_status = 'completed'");
        $newPurchasesCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
        
        // Bekleyen lead talepleri
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM lead_requests WHERE status = 'pending'");
        $pendingLeadRequestsCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
    }
} catch (PDOException $e) {
    error_log("Admin layout stats error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="tr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin Paneli') ?> - KhidmaApp</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <link href="/assets/css/app.css" rel="stylesheet">
    
    <style>
        /* Sidebar responsive */
        #sidebarOverlay { display: none; }
        #sidebarOverlay.show { display: block; }
        
        @media (max-width: 1023px) {
            #sidebar {
                transform: translateX(-100%);
                height: 100vh;
            }
            #sidebar.show {
                transform: translateX(0);
            }
        }
        
        @media (min-width: 1024px) {
            #sidebar {
                position: sticky !important;
                top: 0;
                height: 100vh;
                transform: translateX(0) !important;
                overflow-y: auto;
            }
            main { min-height: 100vh; }
        }
        
        /* Scrollbar gizle */
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        
        /* Badge animasyonları */
        .badge-pulse {
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
    </style>
</head>
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
            <?php include __DIR__ . '/components/sidebar.php'; ?>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 bg-gray-50 w-full lg:w-auto">
            <!-- Content Container -->
            <div class="w-full max-w-full px-4 py-6 lg:px-8 lg:py-8">
                <?php 
                // Session mesajlarını göster
                if (!empty($_SESSION['success'])): ?>
                    <?php 
                    $message = $_SESSION['success'];
                    unset($_SESSION['success']);
                    include __DIR__ . '/components/alert.php';
                    $type = 'success';
                    ?>
                <?php endif; ?>
                
                <?php if (!empty($_SESSION['error'])): ?>
                    <?php 
                    $message = $_SESSION['error'];
                    unset($_SESSION['error']);
                    $type = 'error';
                    include __DIR__ . '/components/alert.php';
                    ?>
                <?php endif; ?>
                
                <?= $content ?? '' ?>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script>
        // Mobile menu
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
        
        // Profile dropdown
        const profileMenuToggle = document.getElementById('profileMenuToggle');
        const profileDropdown = document.getElementById('profileDropdown');
        const profileMenuChevron = document.getElementById('profileMenuChevron');
        
        if (profileMenuToggle && profileDropdown) {
            profileMenuToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                profileDropdown.classList.toggle('hidden');
                profileMenuChevron?.classList.toggle('rotate-180');
            });
            
            document.addEventListener('click', (e) => {
                if (!profileMenuToggle.contains(e.target) && !profileDropdown.contains(e.target)) {
                    profileDropdown.classList.add('hidden');
                    profileMenuChevron?.classList.remove('rotate-180');
                }
            });
        }

        // Toggle sidebar
        mobileMenuToggle?.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.contains('show') ? closeSidebar() : openSidebar();
        });

        // Close on overlay click
        sidebarOverlay?.addEventListener('click', closeSidebar);

        // Close on link click (mobile)
        sidebar?.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) closeSidebar();
            });
        });

        // Close on resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) closeSidebar();
        });
    </script>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
