<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'لوحة التحكم' ?> - KhidmaApp</title>
    <link href="/assets/css/app.css" rel="stylesheet">
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; }
        
        /* Desktop: Sidebar sabit, içerik kaydırılabilir */
        @media (min-width: 1024px) {
            #sidebar {
                position: fixed;
                top: 0;
                right: 0;
                height: 100vh;
            }
            #main-content {
                margin-right: 14rem; /* w-56 = 14rem */
            }
        }
        
        /* Mobile: Sidebar gizli, açılabilir */
        @media (max-width: 1023px) {
            #sidebar {
                position: fixed;
                top: 0;
                right: 0;
                height: 100vh;
                transform: translateX(100%);
                transition: transform 0.3s ease;
            }
            #sidebar.open {
                transform: translateX(0);
            }
        }
        
        .nav-item { transition: all 0.15s; }
        .nav-item:hover { background: #f3f4f6; }
        .nav-item.active { background: #059669; color: white; }
        .nav-item.active:hover { background: #047857; }
    </style>
</head>
<body class="bg-gray-100">
<?php
$unviewedCount = 0;
$unreadMessageCount = 0;
if (isset($_SESSION['provider_id'])) {
    try {
        $pdo = getDatabase();
        // Görülmemiş lead sayısı
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM provider_lead_deliveries WHERE provider_id = ? AND viewed_at IS NULL AND deleted_at IS NULL");
        $stmt->execute([$_SESSION['provider_id']]);
        $unviewedCount = (int)$stmt->fetchColumn();
        
        // Okunmamış mesaj sayısı
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM provider_messages WHERE provider_id = ? AND is_read = 0 AND deleted_at IS NULL");
        $stmt->execute([$_SESSION['provider_id']]);
        $unreadMessageCount = (int)$stmt->fetchColumn();
    } catch (Exception $e) {}
}
?>

<!-- Mobile Header -->
<header class="lg:hidden fixed top-0 left-0 right-0 h-14 bg-white border-b border-gray-200 flex items-center justify-between px-4 z-30">
    <a href="/provider/dashboard" class="font-bold text-green-600">KhidmaApp</a>
    <button id="menuBtn" class="p-2 text-gray-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
</header>

<!-- Overlay -->
<div id="overlay" class="fixed inset-0 bg-black/50 z-40 hidden"></div>

<!-- Sidebar -->
<aside id="sidebar" class="w-56 bg-white border-l border-gray-200 z-50 flex flex-col">
    <!-- Logo -->
    <div class="hidden lg:flex items-center gap-2 h-14 px-4 border-b border-gray-200">
        <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <span class="font-bold text-gray-900">KhidmaApp</span>
    </div>

    <!-- Mobile Close -->
    <div class="lg:hidden flex items-center justify-between h-14 px-4 border-b border-gray-200">
        <span class="font-bold text-gray-900">القائمة</span>
        <button id="closeBtn" class="p-2 text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 py-2 overflow-y-auto">
        <a href="/provider/dashboard" class="nav-item flex items-center gap-3 mx-2 px-3 py-2.5 rounded-lg text-sm font-medium <?= ($currentPage ?? '') === 'dashboard' ? 'active' : 'text-gray-700' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            الرئيسية
        </a>

        <a href="/provider/leads" class="nav-item flex items-center justify-between mx-2 px-3 py-2.5 rounded-lg text-sm font-medium <?= ($currentPage ?? '') === 'leads' ? 'active' : 'text-gray-700' ?>">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                طلباتي
            </div>
            <?php if ($unviewedCount > 0): ?>
            <span class="px-1.5 py-0.5 bg-red-500 text-white text-xs font-bold rounded-full min-w-[20px] text-center"><?= $unviewedCount ?></span>
            <?php endif; ?>
        </a>

        <a href="/provider/browse-packages" class="nav-item flex items-center gap-3 mx-2 px-3 py-2.5 rounded-lg text-sm font-medium <?= ($currentPage ?? '') === 'browse-packages' ? 'active' : 'text-gray-700' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            شراء حزمة
        </a>

        <div class="my-2 mx-4 border-t border-gray-200"></div>

        <a href="/provider/profile" class="nav-item flex items-center gap-3 mx-2 px-3 py-2.5 rounded-lg text-sm font-medium <?= ($currentPage ?? '') === 'profile' ? 'active' : 'text-gray-700' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            الملف الشخصي
        </a>

        <a href="/provider/messages" class="nav-item flex items-center justify-between mx-2 px-3 py-2.5 rounded-lg text-sm font-medium <?= ($currentPage ?? '') === 'messages' ? 'active' : 'text-gray-700' ?>">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                الرسائل
            </div>
            <?php if ($unreadMessageCount > 0): ?>
            <span class="px-1.5 py-0.5 bg-red-500 text-white text-xs font-bold rounded-full min-w-[20px] text-center animate-pulse"><?= $unreadMessageCount ?></span>
            <?php endif; ?>
        </a>

        <a href="/provider/hidden-leads" class="nav-item flex items-center gap-3 mx-2 px-3 py-2.5 rounded-lg text-sm font-medium <?= ($currentPage ?? '') === 'hidden-leads' ? 'active' : 'text-gray-700' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            المحذوفات
        </a>

        <a href="/" target="_blank" class="nav-item flex items-center gap-3 mx-2 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            الموقع الرئيسي
        </a>
    </nav>

    <!-- User & Logout -->
    <div class="p-3 border-t border-gray-200">
        <div class="flex items-center gap-2 px-2 py-2 mb-2">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-900 truncate"><?= htmlspecialchars($_SESSION['provider_name'] ?? '') ?></div>
                <div class="text-xs text-gray-500">مقدم خدمة</div>
            </div>
        </div>
        <a href="/provider/logout" class="flex items-center justify-center gap-2 w-full px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            تسجيل الخروج
        </a>
    </div>
</aside>

<!-- Main Content -->
<main id="main-content" class="min-h-screen pt-16 lg:pt-0 pb-4">
    <div class="p-4 lg:p-6">
        <?= $content ?? '' ?>
    </div>
</main>

<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const menuBtn = document.getElementById('menuBtn');
const closeBtn = document.getElementById('closeBtn');

function openMenu() {
    sidebar.classList.add('open');
    overlay.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeMenu() {
    sidebar.classList.remove('open');
    overlay.classList.add('hidden');
    document.body.style.overflow = '';
}

menuBtn?.addEventListener('click', openMenu);
closeBtn?.addEventListener('click', closeMenu);
overlay?.addEventListener('click', closeMenu);

sidebar?.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth < 1024) closeMenu();
    });
});

function getCsrfToken() {
    return '<?= generateCsrfToken() ?>';
}
</script>
</body>
</html>
