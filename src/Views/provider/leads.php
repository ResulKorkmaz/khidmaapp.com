<?php ob_start(); ?>

<!-- İstatistik Kartları -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900"><?= $stats['total'] ?? 0 ?></p>
                <p class="text-sm text-gray-500">إجمالي الطلبات</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900"><?= $stats['viewed'] ?? 0 ?></p>
                <p class="text-sm text-gray-500">تم عرضها</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900"><?= $stats['not_viewed'] ?? 0 ?></p>
                <p class="text-sm text-gray-500">لم يتم عرضها</p>
            </div>
        </div>
    </div>
</div>

<!-- Başlık ve Filtreler -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                الطلبات المستلمة
            </h1>
            <p class="text-sm text-gray-600 mt-1">جميع طلبات العملاء المرسلة إليك</p>
        </div>
        
        <div class="flex items-center gap-2">
            <select onchange="window.location.href='/provider/leads?status=' + this.value" 
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <option value="all" <?= ($statusFilter ?? 'all') === 'all' ? 'selected' : '' ?>>جميع الحالات</option>
                <option value="new" <?= ($statusFilter ?? '') === 'new' ? 'selected' : '' ?>>جديد</option>
                <option value="verified" <?= ($statusFilter ?? '') === 'verified' ? 'selected' : '' ?>>تم التحقق</option>
                <option value="contacted" <?= ($statusFilter ?? '') === 'contacted' ? 'selected' : '' ?>>تم التواصل</option>
                <option value="completed" <?= ($statusFilter ?? '') === 'completed' ? 'selected' : '' ?>>مكتمل</option>
            </select>
        </div>
    </div>
</div>

<!-- Lead Listesi -->
<?php if (empty($leads)): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">لا توجد طلبات</h3>
        <p class="text-gray-500 mb-6">لم يتم إرسال أي طلبات إليك بعد</p>
        <a href="/provider/browse-packages" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            شراء حزمة للحصول على طلبات
        </a>
    </div>
<?php else: ?>
    <div class="space-y-4">
        <?php foreach ($leads as $lead): ?>
            <?php 
            $isViewed = !empty($lead['viewed_at']);
            $serviceTypes = getServiceTypes();
            $serviceLabel = $serviceTypes[$lead['service_type']]['ar'] ?? $lead['service_type'];
            ?>
            <div class="bg-white rounded-2xl shadow-sm border <?= $isViewed ? 'border-gray-200' : 'border-green-300 ring-2 ring-green-100' ?> p-6 hover:shadow-md transition-shadow">
                <div class="flex flex-col lg:flex-row lg:items-start gap-4">
                    <!-- Sol: Lead Bilgileri -->
                    <div class="flex-1">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-2">
                                    <h3 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($lead['customer_name'] ?? 'عميل') ?></h3>
                                    <?php if (!$isViewed): ?>
                                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">جديد</span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span><?= htmlspecialchars($serviceLabel) ?></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span><?= htmlspecialchars($lead['city'] ?? '-') ?></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        <a href="tel:<?= htmlspecialchars($lead['phone'] ?? '') ?>" class="text-green-600 hover:underline font-medium">
                                            <?= htmlspecialchars($lead['phone'] ?? '-') ?>
                                        </a>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span><?= date('Y-m-d H:i', strtotime($lead['delivered_at'] ?? $lead['created_at'])) ?></span>
                                    </div>
                                </div>
                                
                                <?php if (!empty($lead['description'])): ?>
                                    <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                        <p class="text-sm text-gray-700"><?= nl2br(htmlspecialchars($lead['description'])) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sağ: Aksiyonlar -->
                    <div class="flex flex-row lg:flex-col gap-2 lg:w-auto">
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $lead['phone'] ?? '') ?>" 
                           target="_blank"
                           class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            واتساب
                        </a>
                        <a href="tel:<?= htmlspecialchars($lead['phone'] ?? '') ?>" 
                           class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            اتصال
                        </a>
                        <?php if (!$isViewed): ?>
                            <button onclick="markAsViewed(<?= $lead['id'] ?>)" 
                                    class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                تم العرض
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Sayfalama -->
    <?php if (($totalPages ?? 1) > 1): ?>
        <div class="flex justify-center mt-8">
            <nav class="inline-flex rounded-lg shadow-sm">
                <?php if (($page ?? 1) > 1): ?>
                    <a href="/provider/leads?page=<?= ($page ?? 1) - 1 ?>&status=<?= $statusFilter ?? 'all' ?>" 
                       class="px-4 py-2 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-50 text-sm">
                        السابق
                    </a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                    <a href="/provider/leads?page=<?= $i ?>&status=<?= $statusFilter ?? 'all' ?>" 
                       class="px-4 py-2 border-t border-b border-gray-300 text-sm <?= $i === ($page ?? 1) ? 'bg-green-600 text-white border-green-600' : 'bg-white hover:bg-gray-50' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                
                <?php if (($page ?? 1) < ($totalPages ?? 1)): ?>
                    <a href="/provider/leads?page=<?= ($page ?? 1) + 1 ?>&status=<?= $statusFilter ?? 'all' ?>" 
                       class="px-4 py-2 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-50 text-sm">
                        التالي
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    <?php endif; ?>
<?php endif; ?>

<script>
async function markAsViewed(leadId) {
    try {
        const formData = new FormData();
        formData.append('lead_id', leadId);
        formData.append('csrf_token', getCsrfToken());
        
        const response = await fetch('/provider/mark-lead-viewed', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            window.location.reload();
        } else {
            alert(result.message || 'حدث خطأ');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('حدث خطأ أثناء تحديث الحالة');
    }
}
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'الطلبات المستلمة';
$currentPage = 'leads';
require __DIR__ . '/layout.php';
?>

