<?php
/**
 * Provider Dashboard Görünüm Sayfası
 * Usta paneli ana sayfa - istatistikler, lead'ler ve hızlı erişim
 */

// Layout için içerik yakalama başlat
ob_start();

// Sayfa verilerini al
$provider = $pageData['provider'] ?? [];
$deliveredLeads = $pageData['deliveredLeads'] ?? [];
$purchases = $pageData['purchases'] ?? [];
$stats = $pageData['stats'] ?? [];
?>

<!-- Hoş Geldin Kartı -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                مرحباً <?= htmlspecialchars($provider['name']) ?> 👋
            </h1>
            <p class="text-gray-600">نتمنى لك يوماً موفقاً في العمل</p>
        </div>
        <div class="flex gap-3">
            <a href="/provider/browse-packages" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-xl shadow-sm transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                شراء حزمة
            </a>
        </div>
    </div>
</div>

<!-- İstatistik Kartları -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Hesap Durumu -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <?php
            $statusConfig = [
                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => '⏳ قيد المراجعة'],
                'active' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => '✅ نشط'],
                'suspended' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => '🚫 معلق'],
                'rejected' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => '❌ مرفوض']
            ];
            $status = $provider['status'];
            $config = $statusConfig[$status] ?? $statusConfig['pending'];
            ?>
            <span class="px-3 py-1.5 rounded-lg text-xs font-bold <?= $config['bg'] ?> <?= $config['text'] ?>">
                <?= $config['label'] ?>
            </span>
        </div>
        <h3 class="text-sm font-semibold text-gray-600 mb-1">حالة الحساب</h3>
        <p class="text-xs text-gray-500">
            <?php if ($status === 'pending'): ?>
                حسابك قيد المراجعة
            <?php elseif ($status === 'active'): ?>
                حسابك نشط ويمكنك استقبال الطلبات
            <?php elseif ($status === 'suspended'): ?>
                حسابك معلق مؤقتاً
            <?php else: ?>
                تم رفض الحساب
            <?php endif; ?>
        </p>
    </div>

    <!-- Lead'ler -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <a href="/provider/leads" class="text-xs text-purple-600 hover:text-purple-700 font-medium">عرض الكل →</a>
        </div>
        <div class="text-4xl font-bold text-gray-900 mb-1"><?= number_format($stats['total_leads'] ?? 0) ?></div>
        <h3 class="text-sm font-semibold text-gray-600">إجمالي الطلبات</h3>
        <div class="mt-3 flex items-center gap-2">
            <span class="text-xs text-green-600 font-semibold">+<?= $stats['today_leads'] ?? 0 ?></span>
            <span class="text-xs text-gray-500">اليوم</span>
        </div>
    </div>

    <!-- Bakiye -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>
        <div class="text-4xl font-bold text-gray-900 mb-1"><?= number_format($stats['available_leads'] ?? 0) ?></div>
        <h3 class="text-sm font-semibold text-gray-600">طلبات متاحة</h3>
        <p class="text-xs text-gray-500 mt-2">من أصل <?= number_format($stats['purchased_leads'] ?? 0) ?> مشترى</p>
    </div>

    <!-- Toplam Harcama -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div class="text-4xl font-bold text-gray-900 mb-1"><?= number_format($stats['total_spent'] ?? 0, 2) ?></div>
        <h3 class="text-sm font-semibold text-gray-600">إجمالي الإنفاق (ريال)</h3>
        <p class="text-xs text-gray-500 mt-2">على <?= $stats['total_purchases'] ?? 0 ?> حزمة</p>
    </div>
</div>

<!-- Satın Alınan Paketler ve Lead İste Bölümü - EN ÜSTTE! -->
<?php 
// Aktif ve tamamlanmış paketleri ayır
$activePurchases = array_filter($purchases, function($p) {
    return $p['payment_status'] === 'completed' && $p['remaining_leads'] > 0;
});
$completedPurchases = array_filter($purchases, function($p) {
    return $p['payment_status'] === 'completed' && $p['remaining_leads'] == 0;
});
?>

<?php if (!empty($activePurchases)): ?>
<div class="mb-8" id="request-lead">
    <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="text-3xl">📦</span>
                    حزمي النشطة
                </h2>
                <p class="text-sm text-gray-700 mt-1 font-medium">الحزم التي لديها طلبات متبقية - قم بطلب الطلبات عندما تكون جاهزاً</p>
            </div>
        </div>
        
        <div class="space-y-4">
            <?php 
            foreach ($activePurchases as $purchase): 
                $totalLeads = $purchase['leads_count'];
                $deliveredCount = $purchase['delivered_count'];
                $remainingLeads = $purchase['remaining_leads'];
                $pendingRequests = $purchase['pending_requests'] ?? 0;
                $canRequest = $remainingLeads > $pendingRequests;
            ?>
                <div class="border-2 <?= $canRequest ? 'border-green-300 bg-white shadow-md' : 'border-gray-200 bg-gray-50' ?> rounded-xl p-5">
                    <div class="flex items-start justify-between gap-4 flex-wrap">
                        <!-- Paket Bilgisi -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-sm">
                                    <span class="text-2xl">📦</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($purchase['package_name']) ?></h3>
                                    <p class="text-sm text-gray-600">تاريخ الشراء: <?= date('d/m/Y', strtotime($purchase['purchased_at'])) ?></p>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">التقدم</span>
                                    <span class="text-sm font-bold text-gray-900"><?= $deliveredCount ?> / <?= $totalLeads ?></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <?php $percentage = $totalLeads > 0 ? ($deliveredCount / $totalLeads) * 100 : 0; ?>
                                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-2.5 rounded-full transition-all" style="width: <?= $percentage ?>%"></div>
                                </div>
                            </div>
                            
                            <!-- İstatistikler -->
                            <div class="grid grid-cols-3 gap-3">
                                <div class="bg-blue-50 rounded-lg p-3 text-center border border-blue-200">
                                    <div class="text-lg font-bold text-blue-600"><?= $totalLeads ?></div>
                                    <div class="text-xs text-gray-600">الإجمالي</div>
                                </div>
                                <div class="bg-green-50 rounded-lg p-3 text-center border border-green-200">
                                    <div class="text-lg font-bold text-green-600"><?= $deliveredCount ?></div>
                                    <div class="text-xs text-gray-600">مستلم</div>
                                </div>
                                <div class="bg-orange-50 rounded-lg p-3 text-center border border-orange-200">
                                    <div class="text-lg font-bold text-orange-600"><?= $remainingLeads ?></div>
                                    <div class="text-xs text-gray-600">متبقي</div>
                                </div>
                            </div>
                            
                            <!-- Bekleyen İstekler -->
                            <?php if ($pendingRequests > 0): ?>
                                <div class="mt-3 flex items-center gap-2 bg-yellow-50 border-2 border-yellow-400 rounded-lg px-3 py-2">
                                    <svg class="w-4 h-4 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-sm font-bold text-yellow-900">
                                        <?= $pendingRequests ?> طلب في قائمة الانتظار
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Lead İste Butonu -->
                        <div class="flex flex-col gap-2">
                            <?php if ($canRequest): ?>
                                <!-- Buton + Tooltip Container -->
                                <div class="relative request-lead-wrapper">
                                    <button 
                                        onclick="requestLead(<?= $purchase['id'] ?>)"
                                        id="request-btn-<?= $purchase['id'] ?>"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2 text-lg relative z-10">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        <span>طلب عميل</span>
                                    </button>
                                    
                                    <!-- Dikkat Çekici Tooltip Balonu -->
                                    <div class="tooltip-reminder absolute -top-24 right-0 left-0 z-20 pointer-events-none opacity-0">
                                        <!-- Balon -->
                                        <div class="bg-gradient-to-br from-orange-500 to-red-500 text-white px-5 py-3 rounded-2xl shadow-2xl relative mx-4">
                                            <!-- Parlayan Nokta (Sol Üst) -->
                                            <div class="absolute -top-1 -left-1 w-3 h-3 bg-yellow-300 rounded-full animate-ping"></div>
                                            <div class="absolute -top-1 -left-1 w-3 h-3 bg-yellow-400 rounded-full"></div>
                                            
                                            <!-- Parlayan Nokta (Sağ Üst) -->
                                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-yellow-300 rounded-full animate-ping" style="animation-delay: 0.3s;"></div>
                                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-yellow-400 rounded-full"></div>
                                            
                                            <!-- İçerik -->
                                            <div class="flex items-start gap-3">
                                                <div class="text-2xl animate-bounce">👆</div>
                                                <div class="flex-1">
                                                    <div class="font-bold text-sm mb-1">⚠️ تذكير مهم!</div>
                                                    <div class="text-xs leading-relaxed">
                                                        يجب عليك طلب العميل التالي<br>
                                                        <span class="font-bold">اضغط على الزر أدناه!</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Alt Ok -->
                                            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-4 h-4 bg-gradient-to-br from-orange-500 to-red-500 rotate-45"></div>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-center text-gray-600 font-medium">
                                    يمكنك طلب <?= $remainingLeads - $pendingRequests ?> المزيد
                                </p>
                            <?php elseif ($remainingLeads > 0): ?>
                                <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl px-4 py-3 text-center">
                                    <div class="text-yellow-600 font-bold text-sm mb-1">جميع الطلبات المتبقية في الانتظار</div>
                                    <div class="text-yellow-600 text-xs">سيرسل المسؤول الطلبات قريباً</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- İki Sütunlu Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Son Lead'ler (Sol - Geniş) -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">أحدث الطلبات</h2>
                    <p class="text-sm text-gray-600 mt-1">آخر الطلبات المستلمة</p>
                </div>
                <a href="/provider/leads" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold px-5 py-2.5 rounded-xl transition-all text-sm flex items-center gap-2">
                    عرض الكل
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            </div>
            
            <?php if (empty($deliveredLeads)): ?>
                <!-- Boş Durum -->
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-lg font-medium">لا توجد طلبات بعد</p>
                    <p class="text-gray-400 text-sm mt-2">اشترِ حزمة للحصول على طلبات جديدة</p>
                    <a href="/provider/browse-packages" class="inline-flex items-center gap-2 mt-4 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        شراء حزمة الآن
                    </a>
                </div>
            <?php else: ?>
                <!-- Lead Listesi -->
                <div class="space-y-3">
                    <?php
                    $leadLimit = 5;
                    $displayLeads = array_slice($deliveredLeads, 0, $leadLimit);
                    foreach ($displayLeads as $lead):
                        $serviceTypes = getServiceTypes();
                        $serviceName = $serviceTypes[$lead['service_type']]['ar'] ?? $lead['service_type'];
                        $cities = getCities();
                        $cityName = $cities[$lead['city']]['ar'] ?? $lead['city'];
                    ?>
                        <div class="border border-gray-200 rounded-xl p-4 hover:border-purple-300 hover:shadow-md transition-all cursor-pointer" onclick="viewLeadDetail(<?= $lead['id'] ?>)">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-3 flex-1">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <span class="text-lg">🔧</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-bold text-gray-900 mb-1"><?= htmlspecialchars($serviceName) ?></h4>
                                        <p class="text-xs text-gray-600 mb-2 line-clamp-2"><?= htmlspecialchars($lead['description']) ?></p>
                                        <div class="flex items-center gap-3 flex-wrap">
                                            <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                <?= htmlspecialchars($cityName) ?>
                                            </span>
                                            <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <?= date('Y/m/d', strtotime($lead['delivered_at'])) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <button class="flex-shrink-0 text-purple-600 hover:text-purple-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Hızlı Erişim ve Bilgiler (Sağ - Dar) -->
    <div class="space-y-6">
        <!-- Hızlı Erişim -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">روابط سريعة</h3>
            <div class="space-y-2">
                <a href="/provider/browse-packages" class="flex items-center gap-3 p-3 bg-green-50 hover:bg-green-100 rounded-xl transition-colors group">
                    <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-gray-900 group-hover:text-green-700">شراء حزمة</div>
                        <div class="text-xs text-gray-500">احصل على طلبات جديدة</div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>

                <a href="/provider/leads" class="flex items-center gap-3 p-3 bg-purple-50 hover:bg-purple-100 rounded-xl transition-colors group">
                    <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-gray-900 group-hover:text-purple-700">طلباتي</div>
                        <div class="text-xs text-gray-500">عرض جميع الطلبات</div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>

                <a href="/provider/profile" class="flex items-center gap-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors group">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-gray-900 group-hover:text-blue-700">ملفي الشخصي</div>
                        <div class="text-xs text-gray-500">تحديث المعلومات</div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Hesap Bilgileri -->
        <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-6">
            <div class="flex items-start gap-3 mb-4">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-indigo-900 mb-1">نصيحة</h4>
                    <p class="text-xs text-indigo-700 leading-relaxed">
                        اشترِ حزمة للحصول على طلبات عملاء جدد. كل حزمة تحتوي على عملاء محتملين مع معلومات اتصال كاملة.
                    </p>
                </div>
            </div>
        </div>

        <!-- Hizmet Türü -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-600 mb-3">خدمتك</h3>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">🔧</span>
                </div>
                <div>
                    <div class="text-lg font-bold text-gray-900">
                        <?php
                        $serviceTypes = getServiceTypes();
                        echo htmlspecialchars($serviceTypes[$provider['service_type']]['ar'] ?? $provider['service_type']);
                        ?>
                    </div>
                    <div class="text-xs text-gray-500">نوع الخدمة</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tamamlanmış Paketler - Satın Alma Geçmişi -->
<?php if (!empty($completedPurchases)): ?>
<div class="mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    سجل الحزم المكتملة
                </h2>
                <p class="text-sm text-gray-600 mt-1">الحزم التي تم استلام جميع طلباتها</p>
            </div>
            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-sm font-semibold">
                <?= count($completedPurchases) ?> حزمة
            </span>
        </div>
        
        <div class="space-y-3">
            <?php foreach ($completedPurchases as $purchase): ?>
                <div class="border border-gray-200 bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors">
                    <div class="flex items-center justify-between gap-4 flex-wrap">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-xl">✅</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-bold text-gray-900"><?= htmlspecialchars($purchase['package_name']) ?></h3>
                                <div class="flex items-center gap-3 mt-1 flex-wrap">
                                    <span class="text-xs text-gray-600">
                                        <span class="font-medium">تاريخ الشراء:</span> <?= date('d/m/Y', strtotime($purchase['purchased_at'])) ?>
                                    </span>
                                    <span class="text-xs text-green-600 font-semibold">
                                        <?= $purchase['leads_count'] ?> طلب مستلم
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-lg text-xs font-bold">
                                مكتملة 100%
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
// Lead detay görüntüleme fonksiyonu
function viewLeadDetail(leadId) {
    window.location.href = '/provider/leads?id=' + leadId;
}

// Lead İste Fonksiyonu - AJAX
async function requestLead(purchaseId) {
    const btn = document.getElementById(`request-btn-${purchaseId}`);
    if (!btn) return;
    
    // Buton durumunu değiştir
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    
    try {
        const formData = new FormData();
        formData.append('purchase_id', purchaseId);
        formData.append('csrf_token', '<?= generateCsrfToken() ?>');
        
        const response = await fetch('/provider/request-lead', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Başarılı - Toast göster
            showToast('success', result.message);
            
            // Sayfayı 2 saniye sonra yenile
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            // Hata - Toast göster
            showToast('error', result.message);
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }
    } catch (error) {
        console.error('Request error:', error);
        showToast('error', 'حدث خطأ أثناء إرسال الطلب');
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }
}

// Toast Bildirimi
function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 left-1/2 transform -translate-x-1/2 z-50 px-6 py-4 rounded-xl shadow-2xl border-2 transition-all ${
        type === 'success' 
            ? 'bg-green-50 border-green-500 text-green-800' 
            : 'bg-red-50 border-red-500 text-red-800'
    }`;
    
    toast.innerHTML = `
        <div class="flex items-center gap-3">
            ${type === 'success' 
                ? '<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                : '<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            }
            <span class="font-semibold">${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // 5 saniye sonra kaldır
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

// Tooltip Reminder Animasyonu
document.addEventListener('DOMContentLoaded', function() {
    const tooltips = document.querySelectorAll('.tooltip-reminder');
    
    if (tooltips.length > 0) {
        // 2 saniye bekle, sonra tooltip'i göster
        setTimeout(() => {
            tooltips.forEach((tooltip, index) => {
                setTimeout(() => {
                    // Fade in + slide down animasyonu
                    tooltip.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                    tooltip.style.transform = 'translateY(0)';
                    tooltip.style.opacity = '1';
                    
                    // Her tooltip için farklı süre (ilk paket daha önce)
                }, index * 500); // Her biri 0.5 saniye arayla
            });
            
            // 8 saniye sonra tooltip'leri gizle
            setTimeout(() => {
                tooltips.forEach(tooltip => {
                    tooltip.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                    tooltip.style.transform = 'translateY(-10px)';
                    tooltip.style.opacity = '0';
                });
            }, 8000);
            
            // 12 saniye sonra tekrar göster (döngü)
            setInterval(() => {
                tooltips.forEach((tooltip, index) => {
                    setTimeout(() => {
                        tooltip.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                        tooltip.style.transform = 'translateY(0)';
                        tooltip.style.opacity = '1';
                        
                        // 8 saniye sonra tekrar gizle
                        setTimeout(() => {
                            tooltip.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                            tooltip.style.transform = 'translateY(-10px)';
                            tooltip.style.opacity = '0';
                        }, 8000);
                    }, index * 500);
                });
            }, 15000); // Her 15 saniyede bir tekrar
        }, 2000); // Sayfa yüklendikten 2 saniye sonra başla
    }
    
    // Butona tıklandığında tooltip'i kaldır
    document.querySelectorAll('.request-lead-wrapper button').forEach(button => {
        button.addEventListener('click', function() {
            const wrapper = this.closest('.request-lead-wrapper');
            const tooltip = wrapper.querySelector('.tooltip-reminder');
            if (tooltip) {
                tooltip.style.transition = 'opacity 0.3s ease-out';
                tooltip.style.opacity = '0';
                // Artık gösterme
                setTimeout(() => {
                    tooltip.remove();
                }, 300);
            }
        });
    });
});
</script>

<style>
/* Tooltip Reminder Animasyonları */
.tooltip-reminder {
    transform: translateY(-10px);
    animation: tooltip-float 3s ease-in-out infinite;
}

@keyframes tooltip-float {
    0%, 100% {
        transform: translateY(-10px);
    }
    50% {
        transform: translateY(-5px);
    }
}

/* Parlayan nokta animasyonu */
@keyframes ping {
    75%, 100% {
        transform: scale(2);
        opacity: 0;
    }
}

.animate-ping {
    animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
}

/* El işareti bounce */
@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
}

.animate-bounce {
    animation: bounce 1s infinite;
}

/* Gradient glow efekti */
.tooltip-reminder > div {
    animation: glow 2s ease-in-out infinite;
}

@keyframes glow {
    0%, 100% {
        box-shadow: 0 0 20px rgba(249, 115, 22, 0.5), 0 0 40px rgba(239, 68, 68, 0.3);
    }
    50% {
        box-shadow: 0 0 30px rgba(249, 115, 22, 0.7), 0 0 60px rgba(239, 68, 68, 0.5);
    }
}
</style>

<?php
// İçeriği al
$content = ob_get_clean();

// Sayfa ayarları
$pageTitle = 'لوحة التحكم';
$currentPage = 'dashboard';

// Layout'u yükle
require __DIR__ . '/layout.php';
?>

 * Provider Dashboard Görünüm Sayfası
 * Usta paneli ana sayfa - istatistikler, lead'ler ve hızlı erişim
 */

// Layout için içerik yakalama başlat
ob_start();

// Sayfa verilerini al
$provider = $pageData['provider'] ?? [];
$deliveredLeads = $pageData['deliveredLeads'] ?? [];
$purchases = $pageData['purchases'] ?? [];
$stats = $pageData['stats'] ?? [];
?>

<!-- Hoş Geldin Kartı -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                مرحباً <?= htmlspecialchars($provider['name']) ?> 👋
            </h1>
            <p class="text-gray-600">نتمنى لك يوماً موفقاً في العمل</p>
        </div>
        <div class="flex gap-3">
            <a href="/provider/browse-packages" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-xl shadow-sm transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                شراء حزمة
            </a>
        </div>
    </div>
</div>

<!-- İstatistik Kartları -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Hesap Durumu -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <?php
            $statusConfig = [
                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => '⏳ قيد المراجعة'],
                'active' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => '✅ نشط'],
                'suspended' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => '🚫 معلق'],
                'rejected' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => '❌ مرفوض']
            ];
            $status = $provider['status'];
            $config = $statusConfig[$status] ?? $statusConfig['pending'];
            ?>
            <span class="px-3 py-1.5 rounded-lg text-xs font-bold <?= $config['bg'] ?> <?= $config['text'] ?>">
                <?= $config['label'] ?>
            </span>
        </div>
        <h3 class="text-sm font-semibold text-gray-600 mb-1">حالة الحساب</h3>
        <p class="text-xs text-gray-500">
            <?php if ($status === 'pending'): ?>
                حسابك قيد المراجعة
            <?php elseif ($status === 'active'): ?>
                حسابك نشط ويمكنك استقبال الطلبات
            <?php elseif ($status === 'suspended'): ?>
                حسابك معلق مؤقتاً
            <?php else: ?>
                تم رفض الحساب
            <?php endif; ?>
        </p>
    </div>

    <!-- Lead'ler -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <a href="/provider/leads" class="text-xs text-purple-600 hover:text-purple-700 font-medium">عرض الكل →</a>
        </div>
        <div class="text-4xl font-bold text-gray-900 mb-1"><?= number_format($stats['total_leads'] ?? 0) ?></div>
        <h3 class="text-sm font-semibold text-gray-600">إجمالي الطلبات</h3>
        <div class="mt-3 flex items-center gap-2">
            <span class="text-xs text-green-600 font-semibold">+<?= $stats['today_leads'] ?? 0 ?></span>
            <span class="text-xs text-gray-500">اليوم</span>
        </div>
    </div>

    <!-- Bakiye -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>
        <div class="text-4xl font-bold text-gray-900 mb-1"><?= number_format($stats['available_leads'] ?? 0) ?></div>
        <h3 class="text-sm font-semibold text-gray-600">طلبات متاحة</h3>
        <p class="text-xs text-gray-500 mt-2">من أصل <?= number_format($stats['purchased_leads'] ?? 0) ?> مشترى</p>
    </div>

    <!-- Toplam Harcama -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div class="text-4xl font-bold text-gray-900 mb-1"><?= number_format($stats['total_spent'] ?? 0, 2) ?></div>
        <h3 class="text-sm font-semibold text-gray-600">إجمالي الإنفاق (ريال)</h3>
        <p class="text-xs text-gray-500 mt-2">على <?= $stats['total_purchases'] ?? 0 ?> حزمة</p>
    </div>
</div>

<!-- Satın Alınan Paketler ve Lead İste Bölümü - EN ÜSTTE! -->
<?php 
// Aktif ve tamamlanmış paketleri ayır
$activePurchases = array_filter($purchases, function($p) {
    return $p['payment_status'] === 'completed' && $p['remaining_leads'] > 0;
});
$completedPurchases = array_filter($purchases, function($p) {
    return $p['payment_status'] === 'completed' && $p['remaining_leads'] == 0;
});
?>

<?php if (!empty($activePurchases)): ?>
<div class="mb-8" id="request-lead">
    <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="text-3xl">📦</span>
                    حزمي النشطة
                </h2>
                <p class="text-sm text-gray-700 mt-1 font-medium">الحزم التي لديها طلبات متبقية - قم بطلب الطلبات عندما تكون جاهزاً</p>
            </div>
        </div>
        
        <div class="space-y-4">
            <?php 
            foreach ($activePurchases as $purchase): 
                $totalLeads = $purchase['leads_count'];
                $deliveredCount = $purchase['delivered_count'];
                $remainingLeads = $purchase['remaining_leads'];
                $pendingRequests = $purchase['pending_requests'] ?? 0;
                $canRequest = $remainingLeads > $pendingRequests;
            ?>
                <div class="border-2 <?= $canRequest ? 'border-green-300 bg-white shadow-md' : 'border-gray-200 bg-gray-50' ?> rounded-xl p-5">
                    <div class="flex items-start justify-between gap-4 flex-wrap">
                        <!-- Paket Bilgisi -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-sm">
                                    <span class="text-2xl">📦</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($purchase['package_name']) ?></h3>
                                    <p class="text-sm text-gray-600">تاريخ الشراء: <?= date('d/m/Y', strtotime($purchase['purchased_at'])) ?></p>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">التقدم</span>
                                    <span class="text-sm font-bold text-gray-900"><?= $deliveredCount ?> / <?= $totalLeads ?></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <?php $percentage = $totalLeads > 0 ? ($deliveredCount / $totalLeads) * 100 : 0; ?>
                                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-2.5 rounded-full transition-all" style="width: <?= $percentage ?>%"></div>
                                </div>
                            </div>
                            
                            <!-- İstatistikler -->
                            <div class="grid grid-cols-3 gap-3">
                                <div class="bg-blue-50 rounded-lg p-3 text-center border border-blue-200">
                                    <div class="text-lg font-bold text-blue-600"><?= $totalLeads ?></div>
                                    <div class="text-xs text-gray-600">الإجمالي</div>
                                </div>
                                <div class="bg-green-50 rounded-lg p-3 text-center border border-green-200">
                                    <div class="text-lg font-bold text-green-600"><?= $deliveredCount ?></div>
                                    <div class="text-xs text-gray-600">مستلم</div>
                                </div>
                                <div class="bg-orange-50 rounded-lg p-3 text-center border border-orange-200">
                                    <div class="text-lg font-bold text-orange-600"><?= $remainingLeads ?></div>
                                    <div class="text-xs text-gray-600">متبقي</div>
                                </div>
                            </div>
                            
                            <!-- Bekleyen İstekler -->
                            <?php if ($pendingRequests > 0): ?>
                                <div class="mt-3 flex items-center gap-2 bg-yellow-50 border-2 border-yellow-400 rounded-lg px-3 py-2">
                                    <svg class="w-4 h-4 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-sm font-bold text-yellow-900">
                                        <?= $pendingRequests ?> طلب في قائمة الانتظار
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Lead İste Butonu -->
                        <div class="flex flex-col gap-2">
                            <?php if ($canRequest): ?>
                                <!-- Buton + Tooltip Container -->
                                <div class="relative request-lead-wrapper">
                                    <button 
                                        onclick="requestLead(<?= $purchase['id'] ?>)"
                                        id="request-btn-<?= $purchase['id'] ?>"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2 text-lg relative z-10">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        <span>طلب عميل</span>
                                    </button>
                                    
                                    <!-- Dikkat Çekici Tooltip Balonu -->
                                    <div class="tooltip-reminder absolute -top-24 right-0 left-0 z-20 pointer-events-none opacity-0">
                                        <!-- Balon -->
                                        <div class="bg-gradient-to-br from-orange-500 to-red-500 text-white px-5 py-3 rounded-2xl shadow-2xl relative mx-4">
                                            <!-- Parlayan Nokta (Sol Üst) -->
                                            <div class="absolute -top-1 -left-1 w-3 h-3 bg-yellow-300 rounded-full animate-ping"></div>
                                            <div class="absolute -top-1 -left-1 w-3 h-3 bg-yellow-400 rounded-full"></div>
                                            
                                            <!-- Parlayan Nokta (Sağ Üst) -->
                                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-yellow-300 rounded-full animate-ping" style="animation-delay: 0.3s;"></div>
                                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-yellow-400 rounded-full"></div>
                                            
                                            <!-- İçerik -->
                                            <div class="flex items-start gap-3">
                                                <div class="text-2xl animate-bounce">👆</div>
                                                <div class="flex-1">
                                                    <div class="font-bold text-sm mb-1">⚠️ تذكير مهم!</div>
                                                    <div class="text-xs leading-relaxed">
                                                        يجب عليك طلب العميل التالي<br>
                                                        <span class="font-bold">اضغط على الزر أدناه!</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Alt Ok -->
                                            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-4 h-4 bg-gradient-to-br from-orange-500 to-red-500 rotate-45"></div>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-center text-gray-600 font-medium">
                                    يمكنك طلب <?= $remainingLeads - $pendingRequests ?> المزيد
                                </p>
                            <?php elseif ($remainingLeads > 0): ?>
                                <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl px-4 py-3 text-center">
                                    <div class="text-yellow-600 font-bold text-sm mb-1">جميع الطلبات المتبقية في الانتظار</div>
                                    <div class="text-yellow-600 text-xs">سيرسل المسؤول الطلبات قريباً</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- İki Sütunlu Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Son Lead'ler (Sol - Geniş) -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">أحدث الطلبات</h2>
                    <p class="text-sm text-gray-600 mt-1">آخر الطلبات المستلمة</p>
                </div>
                <a href="/provider/leads" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold px-5 py-2.5 rounded-xl transition-all text-sm flex items-center gap-2">
                    عرض الكل
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            </div>
            
            <?php if (empty($deliveredLeads)): ?>
                <!-- Boş Durum -->
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-lg font-medium">لا توجد طلبات بعد</p>
                    <p class="text-gray-400 text-sm mt-2">اشترِ حزمة للحصول على طلبات جديدة</p>
                    <a href="/provider/browse-packages" class="inline-flex items-center gap-2 mt-4 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        شراء حزمة الآن
                    </a>
                </div>
            <?php else: ?>
                <!-- Lead Listesi -->
                <div class="space-y-3">
                    <?php
                    $leadLimit = 5;
                    $displayLeads = array_slice($deliveredLeads, 0, $leadLimit);
                    foreach ($displayLeads as $lead):
                        $serviceTypes = getServiceTypes();
                        $serviceName = $serviceTypes[$lead['service_type']]['ar'] ?? $lead['service_type'];
                        $cities = getCities();
                        $cityName = $cities[$lead['city']]['ar'] ?? $lead['city'];
                    ?>
                        <div class="border border-gray-200 rounded-xl p-4 hover:border-purple-300 hover:shadow-md transition-all cursor-pointer" onclick="viewLeadDetail(<?= $lead['id'] ?>)">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-3 flex-1">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <span class="text-lg">🔧</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-bold text-gray-900 mb-1"><?= htmlspecialchars($serviceName) ?></h4>
                                        <p class="text-xs text-gray-600 mb-2 line-clamp-2"><?= htmlspecialchars($lead['description']) ?></p>
                                        <div class="flex items-center gap-3 flex-wrap">
                                            <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                <?= htmlspecialchars($cityName) ?>
                                            </span>
                                            <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <?= date('Y/m/d', strtotime($lead['delivered_at'])) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <button class="flex-shrink-0 text-purple-600 hover:text-purple-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Hızlı Erişim ve Bilgiler (Sağ - Dar) -->
    <div class="space-y-6">
        <!-- Hızlı Erişim -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">روابط سريعة</h3>
            <div class="space-y-2">
                <a href="/provider/browse-packages" class="flex items-center gap-3 p-3 bg-green-50 hover:bg-green-100 rounded-xl transition-colors group">
                    <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-gray-900 group-hover:text-green-700">شراء حزمة</div>
                        <div class="text-xs text-gray-500">احصل على طلبات جديدة</div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>

                <a href="/provider/leads" class="flex items-center gap-3 p-3 bg-purple-50 hover:bg-purple-100 rounded-xl transition-colors group">
                    <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-gray-900 group-hover:text-purple-700">طلباتي</div>
                        <div class="text-xs text-gray-500">عرض جميع الطلبات</div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>

                <a href="/provider/profile" class="flex items-center gap-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors group">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-gray-900 group-hover:text-blue-700">ملفي الشخصي</div>
                        <div class="text-xs text-gray-500">تحديث المعلومات</div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Hesap Bilgileri -->
        <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-6">
            <div class="flex items-start gap-3 mb-4">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-indigo-900 mb-1">نصيحة</h4>
                    <p class="text-xs text-indigo-700 leading-relaxed">
                        اشترِ حزمة للحصول على طلبات عملاء جدد. كل حزمة تحتوي على عملاء محتملين مع معلومات اتصال كاملة.
                    </p>
                </div>
            </div>
        </div>

        <!-- Hizmet Türü -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-600 mb-3">خدمتك</h3>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">🔧</span>
                </div>
                <div>
                    <div class="text-lg font-bold text-gray-900">
                        <?php
                        $serviceTypes = getServiceTypes();
                        echo htmlspecialchars($serviceTypes[$provider['service_type']]['ar'] ?? $provider['service_type']);
                        ?>
                    </div>
                    <div class="text-xs text-gray-500">نوع الخدمة</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tamamlanmış Paketler - Satın Alma Geçmişi -->
<?php if (!empty($completedPurchases)): ?>
<div class="mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    سجل الحزم المكتملة
                </h2>
                <p class="text-sm text-gray-600 mt-1">الحزم التي تم استلام جميع طلباتها</p>
            </div>
            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-sm font-semibold">
                <?= count($completedPurchases) ?> حزمة
            </span>
        </div>
        
        <div class="space-y-3">
            <?php foreach ($completedPurchases as $purchase): ?>
                <div class="border border-gray-200 bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors">
                    <div class="flex items-center justify-between gap-4 flex-wrap">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-xl">✅</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-bold text-gray-900"><?= htmlspecialchars($purchase['package_name']) ?></h3>
                                <div class="flex items-center gap-3 mt-1 flex-wrap">
                                    <span class="text-xs text-gray-600">
                                        <span class="font-medium">تاريخ الشراء:</span> <?= date('d/m/Y', strtotime($purchase['purchased_at'])) ?>
                                    </span>
                                    <span class="text-xs text-green-600 font-semibold">
                                        <?= $purchase['leads_count'] ?> طلب مستلم
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-lg text-xs font-bold">
                                مكتملة 100%
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
// Lead detay görüntüleme fonksiyonu
function viewLeadDetail(leadId) {
    window.location.href = '/provider/leads?id=' + leadId;
}

// Lead İste Fonksiyonu - AJAX
async function requestLead(purchaseId) {
    const btn = document.getElementById(`request-btn-${purchaseId}`);
    if (!btn) return;
    
    // Buton durumunu değiştir
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    
    try {
        const formData = new FormData();
        formData.append('purchase_id', purchaseId);
        formData.append('csrf_token', '<?= generateCsrfToken() ?>');
        
        const response = await fetch('/provider/request-lead', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Başarılı - Toast göster
            showToast('success', result.message);
            
            // Sayfayı 2 saniye sonra yenile
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            // Hata - Toast göster
            showToast('error', result.message);
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }
    } catch (error) {
        console.error('Request error:', error);
        showToast('error', 'حدث خطأ أثناء إرسال الطلب');
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }
}

// Toast Bildirimi
function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 left-1/2 transform -translate-x-1/2 z-50 px-6 py-4 rounded-xl shadow-2xl border-2 transition-all ${
        type === 'success' 
            ? 'bg-green-50 border-green-500 text-green-800' 
            : 'bg-red-50 border-red-500 text-red-800'
    }`;
    
    toast.innerHTML = `
        <div class="flex items-center gap-3">
            ${type === 'success' 
                ? '<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                : '<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            }
            <span class="font-semibold">${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // 5 saniye sonra kaldır
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

// Tooltip Reminder Animasyonu
document.addEventListener('DOMContentLoaded', function() {
    const tooltips = document.querySelectorAll('.tooltip-reminder');
    
    if (tooltips.length > 0) {
        // 2 saniye bekle, sonra tooltip'i göster
        setTimeout(() => {
            tooltips.forEach((tooltip, index) => {
                setTimeout(() => {
                    // Fade in + slide down animasyonu
                    tooltip.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                    tooltip.style.transform = 'translateY(0)';
                    tooltip.style.opacity = '1';
                    
                    // Her tooltip için farklı süre (ilk paket daha önce)
                }, index * 500); // Her biri 0.5 saniye arayla
            });
            
            // 8 saniye sonra tooltip'leri gizle
            setTimeout(() => {
                tooltips.forEach(tooltip => {
                    tooltip.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                    tooltip.style.transform = 'translateY(-10px)';
                    tooltip.style.opacity = '0';
                });
            }, 8000);
            
            // 12 saniye sonra tekrar göster (döngü)
            setInterval(() => {
                tooltips.forEach((tooltip, index) => {
                    setTimeout(() => {
                        tooltip.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                        tooltip.style.transform = 'translateY(0)';
                        tooltip.style.opacity = '1';
                        
                        // 8 saniye sonra tekrar gizle
                        setTimeout(() => {
                            tooltip.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                            tooltip.style.transform = 'translateY(-10px)';
                            tooltip.style.opacity = '0';
                        }, 8000);
                    }, index * 500);
                });
            }, 15000); // Her 15 saniyede bir tekrar
        }, 2000); // Sayfa yüklendikten 2 saniye sonra başla
    }
    
    // Butona tıklandığında tooltip'i kaldır
    document.querySelectorAll('.request-lead-wrapper button').forEach(button => {
        button.addEventListener('click', function() {
            const wrapper = this.closest('.request-lead-wrapper');
            const tooltip = wrapper.querySelector('.tooltip-reminder');
            if (tooltip) {
                tooltip.style.transition = 'opacity 0.3s ease-out';
                tooltip.style.opacity = '0';
                // Artık gösterme
                setTimeout(() => {
                    tooltip.remove();
                }, 300);
            }
        });
    });
});
</script>

<style>
/* Tooltip Reminder Animasyonları */
.tooltip-reminder {
    transform: translateY(-10px);
    animation: tooltip-float 3s ease-in-out infinite;
}

@keyframes tooltip-float {
    0%, 100% {
        transform: translateY(-10px);
    }
    50% {
        transform: translateY(-5px);
    }
}

/* Parlayan nokta animasyonu */
@keyframes ping {
    75%, 100% {
        transform: scale(2);
        opacity: 0;
    }
}

.animate-ping {
    animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
}

/* El işareti bounce */
@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
}

.animate-bounce {
    animation: bounce 1s infinite;
}

/* Gradient glow efekti */
.tooltip-reminder > div {
    animation: glow 2s ease-in-out infinite;
}

@keyframes glow {
    0%, 100% {
        box-shadow: 0 0 20px rgba(249, 115, 22, 0.5), 0 0 40px rgba(239, 68, 68, 0.3);
    }
    50% {
        box-shadow: 0 0 30px rgba(249, 115, 22, 0.7), 0 0 60px rgba(239, 68, 68, 0.5);
    }
}
</style>

<?php
// İçeriği al
$content = ob_get_clean();

// Sayfa ayarları
$pageTitle = 'لوحة التحكم';
$currentPage = 'dashboard';

// Layout'u yükle
require __DIR__ . '/layout.php';
?>


