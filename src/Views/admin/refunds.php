<?php
$pageTitle = 'İadeler';
ob_start();

$refundReasons = [
    'customer_request' => 'Müşteri Talebi',
    'invalid_lead' => 'Geçersiz Lead',
    'duplicate' => 'Mükerrer Ödeme',
    'service_issue' => 'Hizmet Sorunu',
    'other' => 'Diğer'
];

$statusLabels = [
    'pending' => ['label' => 'Bekliyor', 'class' => 'bg-yellow-100 text-yellow-800'],
    'processing' => ['label' => 'İşleniyor', 'class' => 'bg-blue-100 text-blue-800'],
    'completed' => ['label' => 'Tamamlandı', 'class' => 'bg-green-100 text-green-800'],
    'failed' => ['label' => 'Başarısız', 'class' => 'bg-red-100 text-red-800']
];
?>

<div class="container mx-auto">
    <!-- Başlık -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">İadeler</h1>
            <p class="text-sm text-gray-600">Tüm iade işlemleri</p>
        </div>
        <a href="/admin/purchases" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Satın Almalar
        </a>
    </div>
    
    <!-- İstatistik Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-600"><?= $stats['total_refunds'] ?? 0 ?></p>
                    <p class="text-sm text-gray-600">Toplam İade</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-orange-600"><?= number_format($stats['total_refunded_amount'] ?? 0, 2) ?></p>
                    <p class="text-sm text-gray-600">Toplam Tutar (SAR)</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-yellow-600"><?= $stats['pending_refunds'] ?? 0 ?></p>
                    <p class="text-sm text-gray-600">Bekleyen</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-purple-600"><?= number_format($stats['this_month_refunds'] ?? 0, 2) ?></p>
                    <p class="text-sm text-gray-600">Bu Ay (SAR)</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- İade Listesi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
            <h2 class="font-bold text-gray-900">İade Geçmişi</h2>
        </div>
        
        <?php if (empty($refunds)): ?>
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-500 font-semibold">Henüz iade yok</p>
                <p class="text-sm text-gray-400">Tüm ödemeler sorunsuz</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Usta</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Paket</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tutar</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Sebep</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Durum</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tarih</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($refunds as $refund): ?>
                            <?php 
                            $statusInfo = $statusLabels[$refund['status']] ?? $statusLabels['pending'];
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-mono text-sm">#<?= $refund['id'] ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-gray-900"><?= htmlspecialchars($refund['provider_name']) ?></p>
                                        <p class="text-xs text-gray-500"><?= htmlspecialchars($refund['provider_email']) ?></p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm"><?= htmlspecialchars($refund['package_name'] ?? '-') ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-bold text-red-600"><?= number_format($refund['amount'], 2) ?> SAR</span>
                                    <span class="text-xs text-gray-500 block"><?= $refund['refund_type'] === 'full' ? 'Tam' : 'Kısmi' ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm"><?= $refundReasons[$refund['reason']] ?? $refund['reason'] ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold <?= $statusInfo['class'] ?>">
                                        <?= $statusInfo['label'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600"><?= date('d.m.Y', strtotime($refund['processed_at'] ?? $refund['requested_at'])) ?></div>
                                    <div class="text-xs text-gray-400"><?= date('H:i', strtotime($refund['processed_at'] ?? $refund['requested_at'])) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="/admin/purchases/detail?id=<?= $refund['purchase_id'] ?>" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Detay →
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>

