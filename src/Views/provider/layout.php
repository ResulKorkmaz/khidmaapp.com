<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'لوحة التحكم' ?> - KhidmaApp</title>
    <link href="/assets/css/app.css" rel="stylesheet">
    <style>
        /* RTL (Sağdan Sola) Desteği */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Sidebar overlay için stiller */
        #sidebarOverlay {
            display: none;
        }
        #sidebarOverlay.show {
            display: block;
        }
        
        /* Mobil: Sidebar gizli */
        @media (max-width: 1023px) {
            #sidebar {
                transform: translateX(100%);
                height: 100vh;
            }
            #sidebar.show {
                transform: translateX(0);
            }
        }
        
        /* Desktop: Yapışkan sidebar */
        @media (min-width: 1024px) {
            #sidebar {
                position: sticky !important;
                top: 0;
                height: 100vh;
                transform: translateX(0) !important;
                overflow-y: auto;
            }
            
            main {
                min-height: 100vh;
            }
        }
        
        /* Scrollbar gizle */
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        /* Notification Badge Animation - Extra Dikkat Çekici! */
        @keyframes pulse-ring {
            0% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }
            50% {
                box-shadow: 0 0 0 8px rgba(239, 68, 68, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }
        
        @keyframes pulse-scale {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.15);
            }
        }
        
        .notification-badge {
            animation: pulse-scale 1.5s ease-in-out infinite, pulse-ring 2s ease-out infinite;
            box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.5), 0 2px 4px -1px rgba(239, 68, 68, 0.3);
        }
    </style>
</head>
<body class="bg-gray-50">
    <?php
    // Get unviewed leads count for notification badge
    $unviewedLeadsCount = 0;
    if (isset($_SESSION['provider_id'])) {
        try {
            $pdo = getDatabase();
            if ($pdo) {
                $stmt = $pdo->prepare("
                    SELECT COUNT(DISTINCT pld.lead_id) as unviewed_count
                    FROM provider_lead_deliveries pld
                    INNER JOIN leads l ON pld.lead_id = l.id
                    WHERE pld.provider_id = ?
                    AND (pld.viewed_at IS NULL OR pld.viewed_count = 0)
                    AND pld.deleted_at IS NULL
                    AND (pld.hidden_by_provider IS NULL OR pld.hidden_by_provider = 0)
                ");
                $stmt->execute([$_SESSION['provider_id']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $unviewedLeadsCount = intval($result['unviewed_count'] ?? 0);
            }
        } catch (Exception $e) {
            error_log("Error fetching unviewed leads count: " . $e->getMessage());
        }
    }
    ?>
    <!-- Mobil Başlık -->
    <header class="bg-white border-b border-gray-200 shadow-sm lg:hidden sticky top-0 z-50">
        <div class="flex items-center justify-between px-4 py-4">
            <div class="flex items-center gap-3">
                <a href="/provider/dashboard" class="text-xl font-bold text-green-600">KhidmaApp</a>
            </div>
            <div class="flex items-center gap-3">
                <button id="mobileMenuToggle" class="text-gray-600 hover:text-green-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Sidebar Overlay (Mobil) -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>

    <div class="flex min-h-screen" dir="rtl">
        <!-- Yan Menü (Sidebar) -->
        <aside id="sidebar" class="fixed lg:sticky top-0 right-0 h-full lg:h-screen w-64 flex-shrink-0 bg-white border-l border-gray-200 shadow-xl transition-transform duration-300 ease-in-out z-50 flex flex-col">
            <!-- Desktop Başlık -->
            <div class="hidden lg:flex flex-shrink-0 items-center gap-3 px-6 py-6 border-b border-gray-200">
                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">KhidmaApp</h2>
                    <p class="text-xs text-gray-500">لوحة التحكم</p>
                </div>
            </div>

            <!-- Kullanıcı Bilgisi (Mobil) -->
            <div class="lg:hidden flex-shrink-0 px-6 py-4 border-b border-gray-200 bg-green-50">
                <p class="text-sm font-semibold text-gray-900">مرحباً</p>
                <p class="text-xs text-gray-600"><?= htmlspecialchars($_SESSION['provider_name'] ?? 'مقدم الخدمة') ?></p>
            </div>

            <!-- Navigasyon Menüsü -->
            <nav class="flex-1 py-4 overflow-y-auto min-h-0">
                <ul class="space-y-1 px-3">
                    <!-- Ana Sayfa -->
                    <li>
                        <a href="/provider/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($currentPage ?? '') === 'dashboard' ? 'bg-green-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span class="font-semibold">الرئيسية</span>
                        </a>
                    </li>

                    <!-- طلباتي المستلمة -->
                    <li>
                        <a href="/provider/leads" class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all <?= ($currentPage ?? '') === 'leads' ? 'bg-green-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' ?>">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span class="font-semibold">الطلبات المستلمة</span>
                            </div>
                            <?php if ($unviewedLeadsCount > 0): ?>
                                <span id="unviewed-leads-badge" class="notification-badge flex items-center justify-center min-w-[24px] h-6 px-2 bg-red-500 text-white text-xs font-bold rounded-full shadow-lg animate-pulse">
                                    <?= $unviewedLeadsCount ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>

                    <!-- Paket Satın Al -->
                    <li>
                        <a href="/provider/browse-packages" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($currentPage ?? '') === 'browse-packages' ? 'bg-green-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span class="font-semibold">شراء حزمة</span>
                        </a>
                    </li>

                    <!-- طلب عميل - Quick Action -->
                    <li>
                        <a href="/provider/dashboard#request-lead" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all bg-green-600 text-white shadow-lg hover:bg-green-700 hover:shadow-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="font-bold">طلب عميل</span>
                        </a>
                    </li>

                    <!-- سلة المهملات - Çöp Kutusu -->
                    <li>
                        <a href="/provider/hidden-leads" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($currentPage ?? '') === 'hidden-leads' ? 'bg-green-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            <span class="font-semibold">سلة المهملات</span>
                        </a>
                    </li>

                    <!-- Ayırıcı -->
                    <li class="pt-4">
                        <div class="px-4 mb-2">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">الحساب</p>
                        </div>
                    </li>

                    <!-- Profil -->
                    <li>
                        <a href="/provider/profile" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($currentPage ?? '') === 'profile' ? 'bg-green-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="font-semibold">الملف الشخصي</span>
                        </a>
                    </li>

                    <!-- Mesajlar -->
                    <li>
                        <a href="/provider/messages" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($currentPage ?? '') === 'messages' ? 'bg-green-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-semibold">الرسائل</span>
                            <?php
                            // Okunmamış mesaj sayısını göster (global değişkenden)
                            if (isset($unreadMessagesCount) && $unreadMessagesCount > 0):
                            ?>
                                <span class="px-2 py-1 bg-red-600 text-white text-xs font-bold rounded-full animate-pulse">
                                    <?= $unreadMessagesCount ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    
                    <!-- Ayarlar -->
                    <li>
                        <a href="/provider/settings" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($currentPage ?? '') === 'settings' ? 'bg-green-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="font-semibold">الإعدادات</span>
                        </a>
                    </li>

                    <!-- Ana Site -->
                    <li>
                        <a href="/" target="_blank" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-100 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            <span class="font-medium">الموقع الرئيسي</span>
                        </a>
                    </li>

                    <!-- Çıkış (Desktop) -->
                    <li class="hidden lg:block">
                        <a href="/provider/logout" class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="font-semibold">تسجيل الخروج</span>
                        </a>
                    </li>

                    <!-- Çıkış (Mobil) -->
                    <li class="lg:hidden">
                        <a href="/provider/logout" class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="font-semibold">تسجيل الخروج</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Kullanıcı Bilgisi (Desktop) -->
            <div class="hidden lg:block flex-shrink-0 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate"><?= htmlspecialchars($_SESSION['provider_name'] ?? 'مقدم الخدمة') ?></p>
                        <p class="text-xs text-gray-500">مقدم الخدمة</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Ana İçerik Alanı -->
        <main class="flex-1 bg-gray-50 w-full lg:w-auto">
            <!-- Mobil header için boşluk -->
            <div class="lg:hidden h-4"></div>
            
            <!-- İçerik Konteyneri -->
            <div class="w-full max-w-full px-4 py-6 lg:px-8 lg:py-8">
                <?= $content ?? '' ?>
            </div>
        </main>
    </div>

    <script>
        // Mobil menü işlevselliği
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

        // Sidebar aç/kapat toggle
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

        // Overlay'e tıklandığında menüyü kapat
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }

        // Mobilde link tıklandığında menüyü kapat
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

        // Ekran boyutu değiştiğinde desktop'ta menüyü kapat
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });
        
        // CSRF Token Helper Function
        function getCsrfToken() {
            return '<?= generateCsrfToken() ?>';
        }
    </script>
</body>
</html>
