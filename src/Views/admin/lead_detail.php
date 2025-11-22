<?php
$pageTitle = 'Lead Detayları';
$currentPage = 'leads';

// Lead Model'i kullan
require_once __DIR__ . '/../../Models/Lead.php';
$leadModel = new Lead(getDatabase());

// Lead ID al
$leadId = $_GET['id'] ?? 0;

// Lead detaylarını getir
$lead = $leadModel->getDetails($leadId);

if (!$lead) {
    header('Location: /admin/leads');
    exit;
}

// Admin paneli için Türkçe isimleri kullan
$services = getServiceTypes();
$cities = getCities();
$lead['service_name'] = $services[$lead['service_type']]['tr'] ?? $lead['service_type'];
$lead['city_name'] = $cities[$lead['city']]['tr'] ?? $lead['city'];

ob_start();
?>

<!-- Lead Detail Container -->
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <a href="/admin/leads" class="text-blue-600 hover:text-blue-700 text-sm font-medium mb-2 inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Geri Dön
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Lead #<?= str_pad($lead['id'], 8, '0', STR_PAD_LEFT) ?></h1>
                <p class="text-gray-600 mt-1"><?= $lead['created_at_human'] ?></p>
            </div>
            
            <!-- Status Badge -->
            <div>
                <?php
                $statusColors = [
                    'new' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'verified' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                    'sold' => 'bg-green-100 text-green-700 border-green-200',
                    'invalid' => 'bg-red-100 text-red-700 border-red-200'
                ];
                $statusLabels = [
                    'new' => 'Yeni',
                    'verified' => 'Doğrulanmış',
                    'sold' => 'Satılan',
                    'invalid' => 'Geçersiz'
                ];
                $status = $lead['status'] ?? 'new';
                ?>
                <span class="px-4 py-2 rounded-xl text-sm font-bold border-2 <?= $statusColors[$status] ?? $statusColors['new'] ?>">
                    <?= $statusLabels[$status] ?? 'Yeni' ?>
                </span>
            </div>
        </div>
    </div>
    
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Lead Information Card -->
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Lead Bilgileri</h2>
                
                <div class="space-y-4">
                    <!-- Service Type -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Hizmet Türü</p>
                            <p class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($lead['service_name']) ?></p>
                        </div>
                    </div>
                    
                    <!-- City -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Şehir</p>
                            <p class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($lead['city_name']) ?></p>
                        </div>
                    </div>
                    
                    <!-- Phone -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Telefon Numarası</p>
                            <p class="text-lg font-semibold text-gray-900 font-mono" dir="ltr"><?= htmlspecialchars($lead['phone']) ?></p>
                            <a href="tel:<?= htmlspecialchars($lead['phone']) ?>" class="text-sm text-blue-600 hover:text-blue-700 mt-1 inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                                Ara
                            </a>
                        </div>
                    </div>
                    
                    <!-- WhatsApp -->
                    <?php if (!empty($lead['whatsapp_phone'])): ?>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">WhatsApp</p>
                            <p class="text-lg font-semibold text-gray-900 font-mono" dir="ltr"><?= htmlspecialchars($lead['whatsapp_phone']) ?></p>
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $lead['whatsapp_phone']) ?>" target="_blank" class="text-sm text-green-600 hover:text-green-700 mt-1 inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                                </svg>
                                WhatsApp Gönder
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Description -->
                    <?php if (!empty($lead['description'])): ?>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Açıklama</p>
                            <p class="text-base text-gray-700 mt-2 leading-relaxed"><?= nl2br(htmlspecialchars($lead['description'])) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Status Update Card -->
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Durum Güncelle</h2>
                
                <form id="status-update-form" method="POST" action="/admin/leads/update-status">
                    <input type="hidden" name="lead_id" value="<?= $lead['id'] ?>">
                    
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Yeni Durum</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer hover:border-blue-300 transition-all <?= $lead['status'] === 'new' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' ?>">
                                <input type="radio" name="status" value="new" <?= $lead['status'] === 'new' ? 'checked' : '' ?> class="sr-only">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900">Yeni</div>
                                    <div class="text-sm text-gray-500">Henüz işleme alınmadı</div>
                                </div>
                            </label>
                            
                            <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer hover:border-yellow-300 transition-all <?= $lead['status'] === 'verified' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200' ?>">
                                <input type="radio" name="status" value="verified" <?= $lead['status'] === 'verified' ? 'checked' : '' ?> class="sr-only">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900">Doğrulanmış</div>
                                    <div class="text-sm text-gray-500">Müşteri doğrulandı</div>
                                </div>
                            </label>
                            
                            <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer hover:border-green-300 transition-all <?= $lead['status'] === 'sold' ? 'border-green-500 bg-green-50' : 'border-gray-200' ?>">
                                <input type="radio" name="status" value="sold" <?= $lead['status'] === 'sold' ? 'checked' : '' ?> class="sr-only">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900">Satılan</div>
                                    <div class="text-sm text-gray-500">Ustaya verildi</div>
                                </div>
                            </label>
                            
                            <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer hover:border-red-300 transition-all <?= $lead['status'] === 'invalid' ? 'border-red-500 bg-red-50' : 'border-gray-200' ?>">
                                <input type="radio" name="status" value="invalid" <?= $lead['status'] === 'invalid' ? 'checked' : '' ?> class="sr-only">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900">Geçersiz</div>
                                    <div class="text-sm text-gray-500">İptal veya geçersiz</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition-all duration-300 transform hover:scale-105">
                        Durumu Güncelle
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Meta Information -->
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Meta Bilgiler</h3>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Lead ID</p>
                        <p class="text-base font-mono font-semibold text-gray-900">#<?= str_pad($lead['id'], 8, '0', STR_PAD_LEFT) ?></p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Kaynak</p>
                        <p class="text-base font-semibold text-gray-900"><?= htmlspecialchars($lead['source']) ?></p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Oluşturulma</p>
                        <p class="text-base font-semibold text-gray-900"><?= $lead['created_at_formatted'] ?></p>
                    </div>
                    
                    <?php if ($lead['updated_at']): ?>
                    <div>
                        <p class="text-sm text-gray-500">Son Güncelleme</p>
                        <p class="text-base font-semibold text-gray-900"><?= date('Y-m-d H:i:s', strtotime($lead['updated_at'])) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">İşlemler</h3>
                
                <div class="space-y-3">
                    <a href="tel:<?= htmlspecialchars($lead['phone']) ?>" class="w-full inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                        Müşteriyi Ara
                    </a>
                    
                    <?php if (!empty($lead['whatsapp_phone'])): ?>
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $lead['whatsapp_phone']) ?>" target="_blank" class="w-full inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                        </svg>
                        WhatsApp Gönder
                    </a>
                    <?php endif; ?>
                    
                    <button onclick="if(confirm('Bu lead\'i silmek istediğinizden emin misiniz?')) { /* DELETE */ }" class="w-full inline-flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-700 font-semibold py-3 rounded-xl transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Sil
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Status update form handler
document.getElementById('status-update-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('/admin/leads/update-status', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('✅ Durum başarıyla güncellendi', 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showAlert('❌ ' + (result.message || 'Bir hata oluştu'), 'error');
        }
    } catch (error) {
        showAlert('❌ Bağlantı hatası', 'error');
    }
});

// Radio button styling
document.querySelectorAll('input[name="status"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('label').forEach(label => {
            label.classList.remove('border-blue-500', 'bg-blue-50', 'border-yellow-500', 'bg-yellow-50', 'border-green-500', 'bg-green-50', 'border-red-500', 'bg-red-50');
            label.classList.add('border-gray-200');
        });
        
        const parent = this.closest('label');
        parent.classList.remove('border-gray-200');
        
        if (this.value === 'new') {
            parent.classList.add('border-blue-500', 'bg-blue-50');
        } else if (this.value === 'verified') {
            parent.classList.add('border-yellow-500', 'bg-yellow-50');
        } else if (this.value === 'sold') {
            parent.classList.add('border-green-500', 'bg-green-50');
        } else if (this.value === 'invalid') {
            parent.classList.add('border-red-500', 'bg-red-50');
        }
    });
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>


$currentPage = 'leads';

// Lead Model'i kullan
require_once __DIR__ . '/../../Models/Lead.php';
$leadModel = new Lead(getDatabase());

// Lead ID al
$leadId = $_GET['id'] ?? 0;

// Lead detaylarını getir
$lead = $leadModel->getDetails($leadId);

if (!$lead) {
    header('Location: /admin/leads');
    exit;
}

// Admin paneli için Türkçe isimleri kullan
$services = getServiceTypes();
$cities = getCities();
$lead['service_name'] = $services[$lead['service_type']]['tr'] ?? $lead['service_type'];
$lead['city_name'] = $cities[$lead['city']]['tr'] ?? $lead['city'];

ob_start();
?>

<!-- Lead Detail Container -->
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <a href="/admin/leads" class="text-blue-600 hover:text-blue-700 text-sm font-medium mb-2 inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Geri Dön
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Lead #<?= str_pad($lead['id'], 8, '0', STR_PAD_LEFT) ?></h1>
                <p class="text-gray-600 mt-1"><?= $lead['created_at_human'] ?></p>
            </div>
            
            <!-- Status Badge -->
            <div>
                <?php
                $statusColors = [
                    'new' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'verified' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                    'sold' => 'bg-green-100 text-green-700 border-green-200',
                    'invalid' => 'bg-red-100 text-red-700 border-red-200'
                ];
                $statusLabels = [
                    'new' => 'Yeni',
                    'verified' => 'Doğrulanmış',
                    'sold' => 'Satılan',
                    'invalid' => 'Geçersiz'
                ];
                $status = $lead['status'] ?? 'new';
                ?>
                <span class="px-4 py-2 rounded-xl text-sm font-bold border-2 <?= $statusColors[$status] ?? $statusColors['new'] ?>">
                    <?= $statusLabels[$status] ?? 'Yeni' ?>
                </span>
            </div>
        </div>
    </div>
    
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Lead Information Card -->
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Lead Bilgileri</h2>
                
                <div class="space-y-4">
                    <!-- Service Type -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Hizmet Türü</p>
                            <p class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($lead['service_name']) ?></p>
                        </div>
                    </div>
                    
                    <!-- City -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Şehir</p>
                            <p class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($lead['city_name']) ?></p>
                        </div>
                    </div>
                    
                    <!-- Phone -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Telefon Numarası</p>
                            <p class="text-lg font-semibold text-gray-900 font-mono" dir="ltr"><?= htmlspecialchars($lead['phone']) ?></p>
                            <a href="tel:<?= htmlspecialchars($lead['phone']) ?>" class="text-sm text-blue-600 hover:text-blue-700 mt-1 inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                                Ara
                            </a>
                        </div>
                    </div>
                    
                    <!-- WhatsApp -->
                    <?php if (!empty($lead['whatsapp_phone'])): ?>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">WhatsApp</p>
                            <p class="text-lg font-semibold text-gray-900 font-mono" dir="ltr"><?= htmlspecialchars($lead['whatsapp_phone']) ?></p>
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $lead['whatsapp_phone']) ?>" target="_blank" class="text-sm text-green-600 hover:text-green-700 mt-1 inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                                </svg>
                                WhatsApp Gönder
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Description -->
                    <?php if (!empty($lead['description'])): ?>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Açıklama</p>
                            <p class="text-base text-gray-700 mt-2 leading-relaxed"><?= nl2br(htmlspecialchars($lead['description'])) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Status Update Card -->
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Durum Güncelle</h2>
                
                <form id="status-update-form" method="POST" action="/admin/leads/update-status">
                    <input type="hidden" name="lead_id" value="<?= $lead['id'] ?>">
                    
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Yeni Durum</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer hover:border-blue-300 transition-all <?= $lead['status'] === 'new' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' ?>">
                                <input type="radio" name="status" value="new" <?= $lead['status'] === 'new' ? 'checked' : '' ?> class="sr-only">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900">Yeni</div>
                                    <div class="text-sm text-gray-500">Henüz işleme alınmadı</div>
                                </div>
                            </label>
                            
                            <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer hover:border-yellow-300 transition-all <?= $lead['status'] === 'verified' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200' ?>">
                                <input type="radio" name="status" value="verified" <?= $lead['status'] === 'verified' ? 'checked' : '' ?> class="sr-only">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900">Doğrulanmış</div>
                                    <div class="text-sm text-gray-500">Müşteri doğrulandı</div>
                                </div>
                            </label>
                            
                            <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer hover:border-green-300 transition-all <?= $lead['status'] === 'sold' ? 'border-green-500 bg-green-50' : 'border-gray-200' ?>">
                                <input type="radio" name="status" value="sold" <?= $lead['status'] === 'sold' ? 'checked' : '' ?> class="sr-only">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900">Satılan</div>
                                    <div class="text-sm text-gray-500">Ustaya verildi</div>
                                </div>
                            </label>
                            
                            <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer hover:border-red-300 transition-all <?= $lead['status'] === 'invalid' ? 'border-red-500 bg-red-50' : 'border-gray-200' ?>">
                                <input type="radio" name="status" value="invalid" <?= $lead['status'] === 'invalid' ? 'checked' : '' ?> class="sr-only">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900">Geçersiz</div>
                                    <div class="text-sm text-gray-500">İptal veya geçersiz</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition-all duration-300 transform hover:scale-105">
                        Durumu Güncelle
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Meta Information -->
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Meta Bilgiler</h3>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Lead ID</p>
                        <p class="text-base font-mono font-semibold text-gray-900">#<?= str_pad($lead['id'], 8, '0', STR_PAD_LEFT) ?></p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Kaynak</p>
                        <p class="text-base font-semibold text-gray-900"><?= htmlspecialchars($lead['source']) ?></p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Oluşturulma</p>
                        <p class="text-base font-semibold text-gray-900"><?= $lead['created_at_formatted'] ?></p>
                    </div>
                    
                    <?php if ($lead['updated_at']): ?>
                    <div>
                        <p class="text-sm text-gray-500">Son Güncelleme</p>
                        <p class="text-base font-semibold text-gray-900"><?= date('Y-m-d H:i:s', strtotime($lead['updated_at'])) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">İşlemler</h3>
                
                <div class="space-y-3">
                    <a href="tel:<?= htmlspecialchars($lead['phone']) ?>" class="w-full inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                        Müşteriyi Ara
                    </a>
                    
                    <?php if (!empty($lead['whatsapp_phone'])): ?>
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $lead['whatsapp_phone']) ?>" target="_blank" class="w-full inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                        </svg>
                        WhatsApp Gönder
                    </a>
                    <?php endif; ?>
                    
                    <button onclick="if(confirm('Bu lead\'i silmek istediğinizden emin misiniz?')) { /* DELETE */ }" class="w-full inline-flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-700 font-semibold py-3 rounded-xl transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Sil
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Status update form handler
document.getElementById('status-update-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('/admin/leads/update-status', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('✅ Durum başarıyla güncellendi', 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showAlert('❌ ' + (result.message || 'Bir hata oluştu'), 'error');
        }
    } catch (error) {
        showAlert('❌ Bağlantı hatası', 'error');
    }
});

// Radio button styling
document.querySelectorAll('input[name="status"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('label').forEach(label => {
            label.classList.remove('border-blue-500', 'bg-blue-50', 'border-yellow-500', 'bg-yellow-50', 'border-green-500', 'bg-green-50', 'border-red-500', 'bg-red-50');
            label.classList.add('border-gray-200');
        });
        
        const parent = this.closest('label');
        parent.classList.remove('border-gray-200');
        
        if (this.value === 'new') {
            parent.classList.add('border-blue-500', 'bg-blue-50');
        } else if (this.value === 'verified') {
            parent.classList.add('border-yellow-500', 'bg-yellow-50');
        } else if (this.value === 'sold') {
            parent.classList.add('border-green-500', 'bg-green-50');
        } else if (this.value === 'invalid') {
            parent.classList.add('border-red-500', 'bg-red-50');
        }
    });
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>



