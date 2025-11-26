<?php
$pageTitle = 'Satın Alma Detayı';
ob_start();

$serviceTypes = getServiceTypes();
$cities = getCities();
$serviceLabel = $serviceTypes[$purchase['service_type']]['tr'] ?? $purchase['service_type'];
$cityLabel = $cities[$purchase['city']]['tr'] ?? $purchase['city'];

$paidAmount = floatval($purchase['price_paid']);
$refundedAmount = floatval($purchase['refunded_amount'] ?? 0);
$remainingAmount = $paidAmount - $refundedAmount;
$canRefund = $remainingAmount > 0 && $purchase['payment_status'] === 'completed';

// İade sebepleri
$refundReasons = [
    'customer_request' => 'Müşteri Talebi',
    'invalid_lead' => 'Geçersiz Lead',
    'duplicate' => 'Mükerrer Ödeme',
    'service_issue' => 'Hizmet Sorunu',
    'other' => 'Diğer'
];
?>

<div class="container mx-auto">
    <!-- Başlık -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="/admin/purchases" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Satın Alma #<?= $purchase['id'] ?></h1>
                <p class="text-sm text-gray-600"><?= htmlspecialchars($purchase['provider_name']) ?></p>
            </div>
        </div>
        
        <?php if ($purchase['refund_status'] === 'full'): ?>
            <span class="px-4 py-2 bg-red-100 text-red-800 rounded-lg font-semibold">
                🔴 Tamamen İade Edildi
            </span>
        <?php elseif ($purchase['refund_status'] === 'partial'): ?>
            <span class="px-4 py-2 bg-orange-100 text-orange-800 rounded-lg font-semibold">
                🟠 Kısmi İade
            </span>
        <?php elseif ($purchase['payment_status'] === 'completed'): ?>
            <span class="px-4 py-2 bg-green-100 text-green-800 rounded-lg font-semibold">
                ✅ Ödeme Tamamlandı
            </span>
        <?php endif; ?>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sol: Satın Alma Bilgileri -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Genel Bilgiler -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Satın Alma Bilgileri
                </h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-500 mb-1">Paket</p>
                        <p class="font-bold text-gray-900"><?= htmlspecialchars($purchase['package_name'] ?? $purchase['package_name_tr'] ?? '-') ?></p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-500 mb-1">Lead Sayısı</p>
                        <p class="font-bold text-gray-900"><?= $purchase['leads_count'] ?? $purchase['package_lead_count'] ?? 0 ?> adet</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-500 mb-1">Satın Alma Tarihi</p>
                        <p class="font-bold text-gray-900"><?= date('d.m.Y H:i', strtotime($purchase['purchased_at'] ?? $purchase['created_at'])) ?></p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-500 mb-1">Kalan Lead</p>
                        <p class="font-bold text-gray-900"><?= $purchase['remaining_leads'] ?? 0 ?> adet</p>
                    </div>
                </div>
            </div>
            
            <!-- Ödeme Bilgileri -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Ödeme Bilgileri
                </h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600">Ödenen Tutar</span>
                        <span class="font-bold text-gray-900 text-lg"><?= number_format($paidAmount, 2) ?> SAR</span>
                    </div>
                    
                    <?php if ($refundedAmount > 0): ?>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-red-600">İade Edilen</span>
                        <span class="font-bold text-red-600 text-lg">-<?= number_format($refundedAmount, 2) ?> SAR</span>
                    </div>
                    <div class="flex justify-between items-center py-3 bg-blue-50 rounded-lg px-4">
                        <span class="text-blue-800 font-semibold">Kalan Tutar</span>
                        <span class="font-bold text-blue-800 text-xl"><?= number_format($remainingAmount, 2) ?> SAR</span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="pt-4 text-sm text-gray-500">
                        <p><strong>Stripe Session:</strong> <?= htmlspecialchars($purchase['stripe_session_id'] ?? '-') ?></p>
                        <p><strong>Payment Intent:</strong> <?= htmlspecialchars($purchase['stripe_payment_intent_id'] ?? 'Yok (Manuel veya eski kayıt)') ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Teslim Edilen Lead'ler -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Teslim Edilen Lead'ler (<?= count($deliveries) ?>)
                </h2>
                
                <?php if (empty($deliveries)): ?>
                    <p class="text-gray-500 text-center py-4">Henüz lead teslim edilmemiş</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">Lead ID</th>
                                    <th class="px-4 py-2 text-left">Hizmet</th>
                                    <th class="px-4 py-2 text-left">Şehir</th>
                                    <th class="px-4 py-2 text-left">Teslim Tarihi</th>
                                    <th class="px-4 py-2 text-left">Durum</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($deliveries as $d): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 font-mono">#<?= $d['lead_id'] ?></td>
                                        <td class="px-4 py-2"><?= $serviceTypes[$d['service_type']]['tr'] ?? $d['service_type'] ?></td>
                                        <td class="px-4 py-2"><?= $cities[$d['city']]['tr'] ?? $d['city'] ?></td>
                                        <td class="px-4 py-2"><?= date('d.m.Y H:i', strtotime($d['delivered_at'])) ?></td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 rounded text-xs <?= $d['viewed_at'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                                <?= $d['viewed_at'] ? 'Görüntülendi' : 'Bekliyor' ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- İade Geçmişi -->
            <?php if (!empty($refundHistory)): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                    İade Geçmişi
                </h2>
                
                <div class="space-y-3">
                    <?php foreach ($refundHistory as $r): ?>
                        <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-red-800"><?= number_format($r['amount'], 2) ?> SAR</p>
                                    <p class="text-sm text-red-600"><?= $refundReasons[$r['reason']] ?? $r['reason'] ?></p>
                                    <?php if ($r['reason_details']): ?>
                                        <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($r['reason_details']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="text-right text-sm">
                                    <p class="text-gray-600"><?= date('d.m.Y H:i', strtotime($r['processed_at'])) ?></p>
                                    <p class="text-gray-500"><?= htmlspecialchars($r['refunded_by_name'] ?? 'Sistem') ?></p>
                                </div>
                            </div>
                            <?php if ($r['stripe_refund_id']): ?>
                                <p class="text-xs text-gray-400 mt-2">Stripe: <?= htmlspecialchars($r['stripe_refund_id']) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Sağ: Provider Bilgileri ve İade Formu -->
        <div class="space-y-6">
            <!-- Provider Bilgileri -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Usta Bilgileri
                </h2>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500">İsim</p>
                        <p class="font-semibold text-gray-900"><?= htmlspecialchars($purchase['provider_name']) ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">E-posta</p>
                        <p class="text-gray-700"><?= htmlspecialchars($purchase['provider_email']) ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Telefon</p>
                        <p class="text-gray-700"><?= htmlspecialchars($purchase['provider_phone']) ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Hizmet / Şehir</p>
                        <p class="text-gray-700"><?= $serviceLabel ?> / <?= $cityLabel ?></p>
                    </div>
                </div>
            </div>
            
            <!-- İade Formu -->
            <?php if ($canRefund): ?>
            <div class="bg-white rounded-xl shadow-sm border border-red-200 p-6">
                <h2 class="text-lg font-bold text-red-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                    İade Yap
                </h2>
                
                <form id="refundForm" class="space-y-4">
                    <input type="hidden" name="purchase_id" value="<?= $purchase['id'] ?>">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">İade Türü</label>
                        <select name="refund_type" id="refundType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500" onchange="toggleRefundAmount()">
                            <option value="full">Tam İade (<?= number_format($remainingAmount, 2) ?> SAR)</option>
                            <option value="partial">Kısmi İade</option>
                        </select>
                    </div>
                    
                    <div id="partialAmountDiv" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">İade Tutarı (SAR)</label>
                        <input type="number" name="refund_amount" step="0.01" min="0.01" max="<?= $remainingAmount ?>" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"
                               placeholder="Maksimum: <?= number_format($remainingAmount, 2) ?>">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">İade Sebebi</label>
                        <select name="reason" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                            <?php foreach ($refundReasons as $key => $label): ?>
                                <option value="<?= $key ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Detay (Opsiyonel)</label>
                        <textarea name="reason_details" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500" placeholder="İade sebebi hakkında detay..."></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notlar (Opsiyonel)</label>
                        <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500" placeholder="İç notlar..."></textarea>
                    </div>
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                        <p class="text-sm text-yellow-800">
                            <strong>⚠️ Dikkat:</strong> İade işlemi geri alınamaz. Stripe üzerinden müşterinin kartına iade yapılacaktır.
                        </p>
                    </div>
                    
                    <button type="submit" id="refundBtn" class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        İade Yap
                    </button>
                </form>
            </div>
            <?php else: ?>
            <div class="bg-gray-100 rounded-xl p-6 text-center">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                <p class="text-gray-600 font-semibold">İade Yapılamaz</p>
                <p class="text-sm text-gray-500 mt-1">
                    <?php if ($purchase['refund_status'] === 'full'): ?>
                        Bu satın alma tamamen iade edilmiş.
                    <?php elseif ($purchase['payment_status'] !== 'completed'): ?>
                        Ödeme henüz tamamlanmamış.
                    <?php endif; ?>
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function toggleRefundAmount() {
    const refundType = document.getElementById('refundType').value;
    const partialDiv = document.getElementById('partialAmountDiv');
    
    if (refundType === 'partial') {
        partialDiv.classList.remove('hidden');
    } else {
        partialDiv.classList.add('hidden');
    }
}

document.getElementById('refundForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!confirm('⚠️ İade işlemi geri alınamaz!\n\nDevam etmek istiyor musunuz?')) {
        return;
    }
    
    const btn = document.getElementById('refundBtn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> İşleniyor...';
    
    try {
        const formData = new FormData(this);
        formData.append('csrf_token', '<?= generateCsrfToken() ?>');
        
        const response = await fetch('/admin/refunds/create', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('✅ ' + result.message);
            window.location.reload();
        } else {
            alert('❌ ' + (result.message || 'Hata oluştu'));
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Bir hata oluştu');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>

