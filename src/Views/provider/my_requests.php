<?php ob_start(); ?>

<!-- Başlık -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                طلباتي
            </h1>
            <p class="text-sm text-gray-600 mt-1">جميع طلبات العملاء التي قدمتها</p>
        </div>
        <a href="/provider/leads" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            العودة للطلبات
        </a>
    </div>
</div>

<!-- Talep Listesi -->
<?php if (empty($requests)): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
        <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">لا توجد طلبات</h3>
        <p class="text-gray-500">لم تقدم أي طلبات بعد</p>
    </div>
<?php else: ?>
    <div class="space-y-4">
        <?php foreach ($requests as $request): ?>
            <?php 
            $serviceTypes = getServiceTypes();
            $serviceLabel = $serviceTypes[$request['service_type']]['ar'] ?? $request['service_type'];
            $statusColors = [
                'pending' => 'bg-yellow-100 text-yellow-800',
                'approved' => 'bg-green-100 text-green-800',
                'rejected' => 'bg-red-100 text-red-800',
            ];
            $statusLabels = [
                'pending' => 'قيد الانتظار',
                'approved' => 'تمت الموافقة',
                'rejected' => 'مرفوض',
            ];
            $statusColor = $statusColors[$request['status']] ?? 'bg-gray-100 text-gray-800';
            $statusLabel = $statusLabels[$request['status']] ?? $request['status'];
            ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900"><?= htmlspecialchars($serviceLabel) ?></h3>
                                <p class="text-sm text-gray-500"><?= htmlspecialchars($request['city'] ?? '-') ?></p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $statusColor ?>">
                                <?= $statusLabel ?>
                            </span>
                        </div>
                        <?php if (!empty($request['description'])): ?>
                            <p class="text-sm text-gray-600 mt-2"><?= htmlspecialchars($request['description']) ?></p>
                        <?php endif; ?>
                        <div class="flex items-center gap-4 mt-3 text-xs text-gray-400">
                            <span>تاريخ الطلب: <?= date('Y-m-d H:i', strtotime($request['created_at'])) ?></span>
                            <span>تاريخ العميل: <?= date('Y-m-d', strtotime($request['lead_created_at'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
$pageTitle = 'طلباتي';
$currentPage = 'my-requests';
require __DIR__ . '/layout.php';
?>

