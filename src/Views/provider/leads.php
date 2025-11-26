<?php ob_start(); ?>

<!-- İstatistik Kartları -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <!-- Toplam Lead Hakkı -->
    <div class="bg-white rounded-xl p-4 shadow-sm border border-blue-200">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-blue-600"><?= $stats['total_rights'] ?? 0 ?></p>
                <p class="text-sm text-gray-600">إجمالي حقوق الطلبات</p>
            </div>
        </div>
    </div>
    
    <!-- Teslim Edilen Lead -->
    <div class="bg-white rounded-xl p-4 shadow-sm border border-green-200">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-green-600"><?= $stats['delivered'] ?? 0 ?></p>
                <p class="text-sm text-gray-600">تم التسليم</p>
            </div>
        </div>
    </div>
    
    <!-- Kalan Lead Hakkı -->
    <div class="bg-white rounded-xl p-4 shadow-sm border border-orange-200">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-orange-600"><?= $stats['remaining'] ?? 0 ?></p>
                <p class="text-sm text-gray-600">المتبقي</p>
            </div>
        </div>
    </div>
</div>

<!-- Başlık -->
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
        
        <a href="/provider/browse-packages" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            شراء حزمة جديدة
        </a>
    </div>
</div>

<!-- Bekleme Uyarısı -->
<?php if (!empty($lastRequestInfo) && !$lastRequestInfo['canRequest']): ?>
<?php 
    $isPending = ($lastRequestInfo['waitReason'] ?? '') === 'pending';
    $remainingHours = $lastRequestInfo['remainingHours'] ?? 0;
    $remainingMinutes = $lastRequestInfo['remainingMinutes'] ?? 0;
    $remainingMins = $remainingMinutes % 60;
?>
<div class="<?= $isPending ? 'bg-yellow-50 border-yellow-300' : 'bg-orange-50 border-orange-300' ?> border rounded-2xl p-4 mb-6">
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 <?= $isPending ? 'bg-yellow-100' : 'bg-orange-100' ?> rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 <?= $isPending ? 'text-yellow-600' : 'text-orange-600' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="flex-1">
            <?php if ($isPending): ?>
                <p class="font-bold text-yellow-800">⏳ طلبك السابق قيد الانتظار</p>
                <p class="text-sm text-yellow-700">
                    سيتم إرسال بيانات العميل قريباً. يمكنك طلب عميل جديد بعد 
                    <strong><?= $remainingHours ?></strong> ساعة و <strong><?= $remainingMins ?></strong> دقيقة
                </p>
            <?php else: ?>
                <p class="font-bold text-orange-800">⏳ يرجى الانتظار قبل طلب عميل جديد</p>
                <p class="text-sm text-orange-700">
                    يمكنك طلب عميل جديد بعد <strong><?= $remainingMinutes ?></strong> دقيقة
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Satın Alınan Paketler - Kompakt -->
<?php if (!empty($purchases)): ?>
<?php 
    $canRequest = $lastRequestInfo['canRequest'] ?? true;
    $totalRemainingLeads = array_sum(array_column($purchases, 'remaining_leads'));
?>
<div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl border border-green-200 p-4 mb-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-600">طلبات متبقية للطلب</p>
                <p class="text-2xl font-bold text-green-600"><?= $totalRemainingLeads ?></p>
            </div>
        </div>
        
        <?php if ($totalRemainingLeads > 0 && $canRequest): ?>
            <button onclick="requestLead(<?= $purchases[0]['id'] ?>)" 
                    class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-colors flex items-center gap-2 shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                اطلب عميل الآن
            </button>
        <?php elseif ($totalRemainingLeads > 0 && !$canRequest): ?>
            <button disabled class="px-6 py-3 bg-gray-300 text-gray-500 font-bold rounded-xl cursor-not-allowed flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                انتظر...
            </button>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Lead Listesi - Tablo Formatı -->
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
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Tablo Başlığı -->
        <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-gray-900">قائمة الطلبات (<?= count($leads) ?>)</h2>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                        جديد
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 bg-gray-300 rounded-full"></span>
                        تم العرض
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Tablo -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">رقم الطلب</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">نوع الخدمة</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">المدينة</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">التاريخ</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">الإجراء</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($leads as $lead): ?>
                        <?php 
                        $isViewed = !empty($lead['viewed_at']);
                        $serviceTypes = getServiceTypes();
                        $serviceLabel = $serviceTypes[$lead['service_type']]['ar'] ?? $lead['service_type'];
                        $cities = getCities();
                        $cityLabel = $cities[$lead['city']]['ar'] ?? $lead['city'];
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors <?= !$isViewed ? 'bg-green-50' : '' ?>">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <?php if (!$isViewed): ?>
                                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                    <?php endif; ?>
                                    <span class="font-bold text-gray-900">#<?= str_pad($lead['id'], 5, '0', STR_PAD_LEFT) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-medium bg-blue-100 text-blue-800">
                                    <?= htmlspecialchars($serviceLabel) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-gray-700"><?= htmlspecialchars($cityLabel) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">
                                    <?= date('Y-m-d', strtotime($lead['delivered_at'] ?? $lead['created_at'])) ?>
                                </div>
                                <div class="text-xs text-gray-400">
                                    <?= date('H:i', strtotime($lead['delivered_at'] ?? $lead['created_at'])) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if (!$isViewed): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                        جديد
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span>
                                        تم العرض
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button onclick="showLeadDetail(<?= htmlspecialchars(json_encode([
                                    'id' => $lead['id'],
                                    'service_type' => $serviceLabel,
                                    'city' => $cityLabel,
                                    'phone' => $lead['phone'] ?? '',
                                    'description' => $lead['description'] ?? '',
                                    'created_at' => date('Y-m-d H:i', strtotime($lead['delivered_at'] ?? $lead['created_at'])),
                                    'is_viewed' => $isViewed
                                ])) ?>)" 
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    التفاصيل
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
        <div class="flex justify-center mt-8">
            <nav class="inline-flex rounded-lg shadow-sm">
                <?php if (($page ?? 1) > 1): ?>
                    <a href="/provider/leads?page=<?= ($page ?? 1) - 1 ?>" 
                       class="px-4 py-2 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-50 text-sm">
                        السابق
                    </a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                    <a href="/provider/leads?page=<?= $i ?>" 
                       class="px-4 py-2 border-t border-b border-gray-300 text-sm <?= $i === ($page ?? 1) ? 'bg-green-600 text-white border-green-600' : 'bg-white hover:bg-gray-50' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                
                <?php if (($page ?? 1) < ($totalPages ?? 1)): ?>
                    <a href="/provider/leads?page=<?= ($page ?? 1) + 1 ?>" 
                       class="px-4 py-2 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-50 text-sm">
                        التالي
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    <?php endif; ?>
<?php endif; ?>

<!-- Lead Detay Modal -->
<div id="leadDetailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 text-white rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold">تفاصيل الطلب</h3>
                        <p class="text-green-100 text-sm" id="modal-lead-id">#00000</p>
                    </div>
                </div>
                <button onclick="closeLeadModal()" class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 space-y-4">
            <!-- Hizmet ve Şehir -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-blue-50 rounded-xl p-4">
                    <p class="text-xs text-blue-600 font-semibold mb-1">نوع الخدمة</p>
                    <p class="text-lg font-bold text-blue-900" id="modal-service-type">-</p>
                </div>
                <div class="bg-orange-50 rounded-xl p-4">
                    <p class="text-xs text-orange-600 font-semibold mb-1">المدينة</p>
                    <p class="text-lg font-bold text-orange-900" id="modal-city">-</p>
                </div>
            </div>
            
            <!-- Tarih -->
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-600 font-semibold mb-1">تاريخ الطلب</p>
                <p class="text-md font-bold text-gray-900" id="modal-date">-</p>
            </div>
            
            <!-- İletişim Bilgileri -->
            <div class="bg-green-50 border-2 border-green-200 rounded-xl p-4">
                <p class="text-xs text-green-600 font-semibold mb-2">📞 معلومات الاتصال</p>
                <p class="text-2xl font-bold text-green-800 mb-3" id="modal-phone" dir="ltr">-</p>
                <div class="flex gap-2">
                    <a href="#" id="modal-whatsapp-btn" target="_blank" 
                       class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                        </svg>
                        واتساب
                    </a>
                    <a href="#" id="modal-call-btn" 
                       class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        اتصال
                    </a>
                </div>
            </div>
            
            <!-- Mesaj/Açıklama -->
            <div id="modal-description-container" class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                <p class="text-xs text-yellow-700 font-semibold mb-2">📝 وصف الطلب</p>
                <p class="text-gray-800 leading-relaxed" id="modal-description">-</p>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 rounded-b-2xl border-t border-gray-200">
            <button onclick="closeLeadModal()" class="w-full py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition-colors">
                إغلاق
            </button>
        </div>
    </div>
</div>

<script>
let currentLeadId = null;

function showLeadDetail(lead) {
    currentLeadId = lead.id;
    
    // Modal'ı doldur
    document.getElementById('modal-lead-id').textContent = '#' + String(lead.id).padStart(5, '0');
    document.getElementById('modal-service-type').textContent = lead.service_type;
    document.getElementById('modal-city').textContent = lead.city;
    document.getElementById('modal-phone').textContent = lead.phone || '-';
    document.getElementById('modal-date').textContent = lead.created_at;
    
    // Açıklama
    const descContainer = document.getElementById('modal-description-container');
    const descText = document.getElementById('modal-description');
    if (lead.description && lead.description.trim()) {
        descContainer.classList.remove('hidden');
        descText.textContent = lead.description;
    } else {
        descContainer.classList.add('hidden');
    }
    
    // Telefon linkleri
    const phoneClean = (lead.phone || '').replace(/[^0-9]/g, '');
    document.getElementById('modal-whatsapp-btn').href = 'https://wa.me/' + phoneClean;
    document.getElementById('modal-call-btn').href = 'tel:' + phoneClean;
    
    // Modal'ı göster
    document.getElementById('leadDetailModal').classList.remove('hidden');
    
    // Lead görüntülendi olarak işaretle (eğer yeni ise)
    if (!lead.is_viewed) {
        markAsViewed(lead.id);
    }
}

function closeLeadModal() {
    document.getElementById('leadDetailModal').classList.add('hidden');
    currentLeadId = null;
}

// ESC ile modal kapatma
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLeadModal();
    }
});

// Modal dışına tıklayınca kapatma
document.getElementById('leadDetailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLeadModal();
    }
});

async function requestLead(purchaseId) {
    if (!confirm('هل تريد طلب عميل جديد؟ سيتم إرسال بيانات العميل من قبل الإدارة.')) {
        return;
    }
    
    try {
        const formData = new FormData();
        formData.append('purchase_id', purchaseId);
        formData.append('csrf_token', getCsrfToken());
        
        const response = await fetch('/provider/request-lead', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('✅ ' + (result.message || 'تم إرسال طلبك بنجاح! سيتم إرسال بيانات العميل قريباً.'));
            window.location.reload();
        } else {
            alert('❌ ' + (result.message || 'حدث خطأ'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('حدث خطأ أثناء إرسال الطلب');
    }
}

async function markAsViewed(leadId) {
    try {
        const formData = new FormData();
        formData.append('lead_id', leadId);
        formData.append('csrf_token', getCsrfToken());
        
        const response = await fetch('/provider/mark-lead-viewed', {
            method: 'POST',
            body: formData
        });
        
        // Sessiz güncelleme - sayfa yenileme yok
        const result = await response.json();
        if (result.success) {
            // Tablodaki satırı güncelle
            const row = document.querySelector(`tr:has(button[onclick*='"id":${leadId}'])`);
            if (row) {
                row.classList.remove('bg-green-50');
                // Status badge'i güncelle
                const statusCell = row.querySelector('td:nth-child(5)');
                if (statusCell) {
                    statusCell.innerHTML = `
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span>
                            تم العرض
                        </span>
                    `;
                }
                // Yeşil nokta'yı kaldır
                const idCell = row.querySelector('td:first-child .w-2');
                if (idCell) {
                    idCell.remove();
                }
            }
        }
    } catch (error) {
        console.error('Error marking as viewed:', error);
    }
}
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'الطلبات المستلمة';
$currentPage = 'leads';
require __DIR__ . '/layout.php';
?>
