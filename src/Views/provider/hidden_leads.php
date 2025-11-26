<?php ob_start(); ?>

<!-- Başlık -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                سلة المهملات
            </h1>
            <p class="text-sm text-gray-600 mt-1">الطلبات المخفية</p>
        </div>
        <a href="/provider/leads" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            العودة للطلبات
        </a>
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
        <h3 class="text-lg font-semibold text-gray-900 mb-2">سلة المهملات فارغة</h3>
        <p class="text-gray-500">لا توجد طلبات مخفية</p>
    </div>
<?php else: ?>
    <div class="space-y-4">
        <?php foreach ($leads as $lead): ?>
            <?php 
            $serviceTypes = getServiceTypes();
            $serviceLabel = $serviceTypes[$lead['service_type']]['ar'] ?? $lead['service_type'];
            ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 opacity-75 hover:opacity-100 transition-opacity">
                <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900"><?= htmlspecialchars($lead['customer_name'] ?? 'عميل') ?></h3>
                                <p class="text-sm text-gray-500"><?= htmlspecialchars($serviceLabel) ?> - <?= htmlspecialchars($lead['city'] ?? '-') ?></p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400">تم الإخفاء: <?= date('Y-m-d H:i', strtotime($lead['hidden_at'])) ?></p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="restoreLead(<?= $lead['id'] ?>)" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            استعادة
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
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
$pageTitle = 'سلة المهملات';
$currentPage = 'hidden-leads';
require __DIR__ . '/layout.php';
?>

