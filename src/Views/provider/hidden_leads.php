<?php ob_start(); ?>

<!-- Başlık -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                سلة المحذوفات
            </h1>
            <p class="text-sm text-gray-600 mt-1">الطلبات المحذوفة تُحفظ لمدة <?= $retentionDays ?? 180 ?> يوم</p>
        </div>
        <a href="/provider/leads" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            العودة للطلبات
        </a>
    </div>
</div>

<!-- İstatistik Kartları -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <!-- Toplam Silinen (Tüm Zamanlar) -->
    <div class="bg-white rounded-xl p-4 shadow-sm border border-red-200">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-red-600"><?= $totalDeletedAllTime ?? 0 ?></p>
                <p class="text-sm text-gray-600">إجمالي المحذوفات</p>
                <p class="text-xs text-gray-400">منذ البداية</p>
            </div>
        </div>
    </div>
    
    <!-- Görünür Lead'ler (180 gün içinde) -->
    <div class="bg-white rounded-xl p-4 shadow-sm border border-orange-200">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-orange-600"><?= $totalVisibleLeads ?? 0 ?></p>
                <p class="text-sm text-gray-600">قابل للاستعادة</p>
                <p class="text-xs text-gray-400">خلال <?= $retentionDays ?? 180 ?> يوم</p>
            </div>
        </div>
    </div>
    
    <!-- Süresi Dolmuş Lead'ler -->
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-500"><?= $expiredLeads ?? 0 ?></p>
                <p class="text-sm text-gray-600">منتهي الصلاحية</p>
                <p class="text-xs text-gray-400">تم إخفاؤها نهائياً</p>
            </div>
        </div>
    </div>
</div>

<!-- Bilgi Notu -->
<div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
    <div class="flex items-start gap-3">
        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm text-yellow-800 font-semibold">⚠️ ملاحظة مهمة</p>
            <p class="text-sm text-yellow-700 mt-1">
                الطلبات المحذوفة تُحفظ لمدة <strong><?= $retentionDays ?? 180 ?> يوم</strong> فقط. 
                بعد انتهاء هذه المدة، لن تتمكن من استعادتها.
            </p>
        </div>
    </div>
</div>

<!-- Lead Listesi -->
<?php if (empty($leads)): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">سلة المحذوفات فارغة</h3>
        <p class="text-gray-500">لا توجد طلبات محذوفة قابلة للاستعادة</p>
        <?php if (($expiredLeads ?? 0) > 0): ?>
            <p class="text-sm text-gray-400 mt-2">
                (<?= $expiredLeads ?> طلب منتهي الصلاحية - تم إخفاؤها نهائياً)
            </p>
        <?php endif; ?>
    </div>
<?php else: ?>
    <!-- Tablo -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-gray-900">الطلبات المحذوفة (<?= $totalVisibleLeads ?>)</h2>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">رقم الطلب</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">نوع الخدمة</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">المدينة</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">تاريخ الحذف</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">المتبقي</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">الإجراء</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($leads as $lead): ?>
                        <?php 
                        $serviceTypes = getServiceTypes();
                        $serviceLabel = $serviceTypes[$lead['service_type']]['ar'] ?? $lead['service_type'];
                        $cities = getCities();
                        $cityLabel = $cities[$lead['city']]['ar'] ?? $lead['city'];
                        $daysRemaining = max(0, (int)$lead['days_remaining']);
                        
                        // Renk belirleme
                        if ($daysRemaining <= 7) {
                            $badgeClass = 'bg-red-100 text-red-800';
                            $badgeIcon = '🔴';
                        } elseif ($daysRemaining <= 30) {
                            $badgeClass = 'bg-orange-100 text-orange-800';
                            $badgeIcon = '🟠';
                        } else {
                            $badgeClass = 'bg-green-100 text-green-800';
                            $badgeIcon = '🟢';
                        }
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors opacity-75 hover:opacity-100">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-gray-900">#<?= str_pad($lead['id'], 5, '0', STR_PAD_LEFT) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-medium bg-gray-100 text-gray-700">
                                    <?= htmlspecialchars($serviceLabel) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-gray-700"><?= htmlspecialchars($cityLabel) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">
                                    <?= date('Y-m-d', strtotime($lead['hidden_at'])) ?>
                                </div>
                                <div class="text-xs text-gray-400">
                                    <?= date('H:i', strtotime($lead['hidden_at'])) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold <?= $badgeClass ?>">
                                    <?= $badgeIcon ?> <?= $daysRemaining ?> يوم
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button onclick="restoreLead(<?= $lead['id'] ?>)" 
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    استعادة
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Sayfalama -->
    <?php if (($totalPages ?? 1) > 1): ?>
        <?php 
        $currentPage = $page ?? 1;
        $total = $totalPages ?? 1;
        $range = 2;
        ?>
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6 bg-white rounded-xl p-4 border border-gray-200">
            <div class="text-sm text-gray-600">
                صفحة <span class="font-bold text-gray-900"><?= $currentPage ?></span> من <span class="font-bold text-gray-900"><?= $total ?></span>
                <span class="text-gray-400 mx-2">|</span>
                إجمالي: <span class="font-bold text-orange-600"><?= $totalVisibleLeads ?? 0 ?></span> طلب
            </div>
            
            <nav class="inline-flex items-center gap-1">
                <?php if ($currentPage > 1): ?>
                    <a href="/provider/hidden-leads?page=<?= $currentPage - 1 ?>" 
                       class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium text-gray-700 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        السابق
                    </a>
                <?php endif; ?>
                
                <?php if ($currentPage > $range + 1): ?>
                    <a href="/provider/hidden-leads?page=1" class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium">1</a>
                    <?php if ($currentPage > $range + 2): ?>
                        <span class="px-2 text-gray-400">...</span>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php 
                $start = max(1, $currentPage - $range);
                $end = min($total, $currentPage + $range);
                for ($i = $start; $i <= $end; $i++): 
                ?>
                    <a href="/provider/hidden-leads?page=<?= $i ?>" 
                       class="px-3 py-2 rounded-lg text-sm font-medium <?= $i === $currentPage ? 'bg-orange-600 text-white border border-orange-600' : 'bg-white border border-gray-300 hover:bg-gray-50 text-gray-700' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($currentPage < $total - $range): ?>
                    <?php if ($currentPage < $total - $range - 1): ?>
                        <span class="px-2 text-gray-400">...</span>
                    <?php endif; ?>
                    <a href="/provider/hidden-leads?page=<?= $total ?>" class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium"><?= $total ?></a>
                <?php endif; ?>
                
                <?php if ($currentPage < $total): ?>
                    <a href="/provider/hidden-leads?page=<?= $currentPage + 1 ?>" 
                       class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium text-gray-700 flex items-center gap-1">
                        التالي
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    <?php endif; ?>
<?php endif; ?>

<script>
async function restoreLead(leadId) {
    if (!confirm('هل تريد استعادة هذا الطلب؟')) {
        return;
    }
    
    try {
        const formData = new FormData();
        formData.append('lead_id', leadId);
        formData.append('csrf_token', getCsrfToken());
        
        const response = await fetch('/provider/restore-lead', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Sayfayı yenile
            window.location.reload();
        } else {
            alert(result.message || 'حدث خطأ');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('حدث خطأ أثناء الاستعادة');
    }
}
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'سلة المحذوفات';
$currentPage = 'hidden-leads';
require __DIR__ . '/layout.php';
?>
