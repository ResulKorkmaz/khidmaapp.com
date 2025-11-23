<?php
/**
 * Admin - Provider Messages
 * Modern, profesyonel ve mobil uyumlu tasarım
 */

$pageTitle = 'Provider Mesajları';
$currentPage = 'provider-messages';
ob_start();

$serviceTypes = getServiceTypes();
$cities = getCities();

// Check for warning message
$warningMessage = $_SESSION['warning_message'] ?? null;
if ($warningMessage) {
    unset($_SESSION['warning_message']);
}
?>

<style>
/* Modern Provider Messages Styles */
.provider-messages-container {
    padding: 1.5rem;
    background: #f8f9fa;
    min-height: calc(100vh - 100px);
}

/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
}

.stat-card .stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 1rem;
}

.stat-card.primary .stat-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-card.success .stat-icon { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stat-card.warning .stat-icon { background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%); }

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.stat-label {
    color: #718096;
    font-size: 0.875rem;
    margin: 0;
}

/* Search & Filter Bar */
.search-filter-bar {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.search-input {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.2s;
    width: 100%;
}

.search-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
}

/* Provider Cards */
.providers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
}

.provider-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}

.provider-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    transform: scaleX(0);
    transition: transform 0.3s;
}

.provider-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

.provider-card:hover::before {
    transform: scaleX(1);
}

.provider-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 1rem;
}

.provider-avatar {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    font-weight: 700;
    flex-shrink: 0;
}

.provider-info {
    flex: 1;
    margin-left: 1rem;
}

.provider-name {
    font-size: 1.125rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0 0 0.25rem 0;
}

.provider-id {
    font-size: 0.75rem;
    color: #a0aec0;
    font-weight: 600;
}

.provider-status {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    gap: 0.25rem;
}

.provider-status.active {
    background: #d4edda;
    color: #155724;
}

.provider-status.pending {
    background: #fff3cd;
    color: #856404;
}

.provider-details {
    margin: 1rem 0;
}

.detail-row {
    display: flex;
    align-items: center;
    padding: 0.5rem 0;
    font-size: 0.875rem;
    color: #4a5568;
    gap: 0.5rem;
}

.detail-icon {
    width: 20px;
    text-align: center;
}

.provider-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin: 1rem 0;
}

.tag {
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.tag.service {
    background: #e6f7ff;
    color: #0050b3;
}

.tag.city {
    background: #f0f5ff;
    color: #2f54eb;
}

.message-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    margin: 1rem 0;
    padding: 1rem;
    background: #f7fafc;
    border-radius: 8px;
}

.message-stat {
    text-align: center;
}

.message-stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
}

.message-stat-label {
    font-size: 0.75rem;
    color: #718096;
    margin-top: 0.25rem;
}

.message-stat.unread .message-stat-value {
    color: #e53e3e;
}

.provider-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}

.action-btn {
    padding: 0.75rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.action-btn.primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.action-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.action-btn.secondary {
    background: #edf2f7;
    color: #4a5568;
}

.action-btn.secondary:hover {
    background: #e2e8f0;
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 16px 16px 0 0;
    padding: 1.5rem;
    border: none;
}

.modal-title {
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-body {
    padding: 2rem;
}

.form-label {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-control, .form-select {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.75rem;
    font-size: 0.95rem;
    transition: all 0.2s;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
}

.info-alert {
    background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
    border: 2px solid #667eea;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.info-alert strong {
    color: #667eea;
}

/* Message History */
.message-item {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border: 2px solid #e2e8f0;
    transition: all 0.2s;
}

.message-item:hover {
    border-color: #667eea;
    transform: translateX(4px);
}

.message-item.unread {
    background: linear-gradient(135deg, #667eea05 0%, #764ba205 100%);
    border-color: #667eea;
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 1rem;
}

.message-badges {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.badge {
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge.type-lead { background: #d4edda; color: #155724; }
.badge.type-notification { background: #cfe2ff; color: #084298; }
.badge.type-announcement { background: #fff3cd; color: #856404; }
.badge.type-info { background: #e2e3e5; color: #41464b; }
.badge.priority-urgent { background: #f8d7da; color: #842029; }
.badge.priority-high { background: #fff3cd; color: #856404; }
.badge.unread-badge { background: #667eea; color: white; }

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.empty-state-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.3;
}

.empty-state-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.empty-state-text {
    color: #718096;
}

/* Loading State */
.loading-spinner {
    display: inline-block;
    width: 40px;
    height: 40px;
    border: 4px solid #e2e8f0;
    border-top-color: #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 768px) {
    .provider-messages-container {
        padding: 1rem;
    }
    
    .providers-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .provider-actions {
        grid-template-columns: 1fr;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
}
</style>

<div class="provider-messages-container">
    <?php if ($warningMessage): ?>
    <div class="alert alert-warning" role="alert" style="margin-bottom: 1.5rem; padding: 1rem; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; color: #856404;">
        <strong>⚠️ Uyarı:</strong> <?= htmlspecialchars($warningMessage) ?>
    </div>
    <?php endif; ?>
    
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-icon">📨</div>
            <h3 class="stat-value"><?= $stats['total_messages'] ?? 0 ?></h3>
            <p class="stat-label">Toplam Mesaj</p>
        </div>
        <div class="stat-card success">
            <div class="stat-icon">✅</div>
            <h3 class="stat-value"><?= $stats['providers_with_messages'] ?? 0 ?></h3>
            <p class="stat-label">Mesajlaşılan Provider</p>
        </div>
        <div class="stat-card warning">
            <div class="stat-icon">🔔</div>
            <h3 class="stat-value"><?= $stats['unread_messages'] ?? 0 ?></h3>
            <p class="stat-label">Okunmamış Mesaj</p>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="search-filter-bar">
        <div class="row align-items-center">
            <div class="col-md-8">
                <input type="text" id="searchProviders" class="search-input" placeholder="🔍 Provider ara... (isim, email, telefon)">
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <select id="filterStatus" class="form-select">
                    <option value="">Tüm Durumlar</option>
                    <option value="active">Aktif</option>
                    <option value="pending">Beklemede</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Providers Grid -->
    <?php if (empty($providers)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <h3 class="empty-state-title">Henüz Provider Yok</h3>
            <p class="empty-state-text">Sistemde kayıtlı hizmet sağlayıcı bulunmuyor</p>
        </div>
    <?php else: ?>
        <div class="providers-grid" id="providersGrid">
            <?php foreach ($providers as $provider): ?>
                <div class="provider-card" 
                     data-provider-id="<?= $provider['id'] ?>"
                     data-status="<?= $provider['status'] ?>"
                     data-search="<?= htmlspecialchars(strtolower($provider['name'] . ' ' . $provider['email'] . ' ' . $provider['phone'])) ?>">
                    
                    <div class="provider-header">
                        <div style="display: flex; align-items: start; flex: 1;">
                            <div class="provider-avatar">
                                <?= strtoupper(substr($provider['name'], 0, 2)) ?>
                            </div>
                            <div class="provider-info">
                                <h3 class="provider-name"><?= htmlspecialchars($provider['name']) ?></h3>
                                <span class="provider-id">#<?= $provider['id'] ?></span>
                            </div>
                        </div>
                        <span class="provider-status <?= $provider['status'] ?>">
                            <?php if ($provider['status'] === 'active'): ?>
                                ✓ Aktif
                            <?php else: ?>
                                ⏳ Beklemede
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="provider-details">
                        <div class="detail-row">
                            <span class="detail-icon">📧</span>
                            <span><?= htmlspecialchars($provider['email']) ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-icon">📱</span>
                            <span><?= htmlspecialchars($provider['phone']) ?></span>
                        </div>
                    </div>

                    <div class="provider-tags">
                        <span class="tag service">
                            <?= $serviceTypes[$provider['service_type']]['icon'] ?? '🔧' ?>
                            <?= $serviceTypes[$provider['service_type']]['tr'] ?? $provider['service_type'] ?>
                        </span>
                        <span class="tag city">
                            📍 <?= $cities[$provider['city']]['tr'] ?? $provider['city'] ?>
                        </span>
                    </div>

                    <div class="message-stats">
                        <div class="message-stat">
                            <div class="message-stat-value"><?= $provider['message_count'] ?></div>
                            <div class="message-stat-label">Toplam Mesaj</div>
                        </div>
                        <div class="message-stat unread">
                            <div class="message-stat-value"><?= $provider['unread_count'] ?></div>
                            <div class="message-stat-label">Okunmamış</div>
                        </div>
                    </div>

                    <div class="provider-actions">
                        <button class="action-btn primary" onclick="openSendMessageModal(<?= $provider['id'] ?>, '<?= htmlspecialchars($provider['name'], ENT_QUOTES) ?>')">
                            <span>📤</span>
                            <span>Mesaj Gönder</span>
                        </button>
                        <button class="action-btn secondary" onclick="viewMessageHistory(<?= $provider['id'] ?>, '<?= htmlspecialchars($provider['name'], ENT_QUOTES) ?>')">
                            <span>📜</span>
                            <span>Geçmiş</span>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if (isset($totalPages) && $totalPages > 1): ?>
            <div class="pagination-container" style="margin-top: 2rem; padding: 1.5rem; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div style="display: flex; flex-direction: column; sm:flex-direction: row; align-items: center; justify-content: space-between; gap: 1rem;">
                    <!-- Pagination Info -->
                    <div style="font-size: 0.875rem; color: #718096;">
                        <strong>Toplam:</strong> <?= $totalProviders ?? 0 ?> provider | 
                        <strong>Sayfa:</strong> <?= $page ?? 1 ?> / <?= $totalPages ?>
                    </div>
                    
                    <!-- Pagination Buttons -->
                    <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; justify-content: center;">
                        <?php 
                        $currentPaginationPage = $page ?? 1;
                        $totalPaginationPages = $totalPages;
                        ?>
                        
                        <!-- First Page -->
                        <?php if ($currentPaginationPage > 1): ?>
                            <a href="?page=1" class="pagination-btn" style="padding: 0.5rem 1rem; background: #e2e8f0; color: #2d3748; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.2s; display: none;">
                                İlk
                            </a>
                            <a href="?page=<?= $currentPaginationPage - 1 ?>" class="pagination-btn" style="padding: 0.5rem 1rem; background: #e2e8f0; color: #2d3748; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;">
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                <span class="hidden-mobile">Önceki</span>
                            </a>
                        <?php endif; ?>
                        
                        <!-- Page Numbers -->
                        <?php
                        $startPage = max(1, $currentPaginationPage - 2);
                        $endPage = min($totalPaginationPages, $currentPaginationPage + 2);
                        for ($i = $startPage; $i <= $endPage; $i++):
                        ?>
                            <a href="?page=<?= $i ?>" 
                               class="pagination-btn <?= $i === $currentPaginationPage ? 'active' : '' ?>"
                               style="padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.2s; min-width: 40px; text-align: center; <?= $i === $currentPaginationPage ? 'background: #667eea; color: white; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);' : 'background: #e2e8f0; color: #2d3748;' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        
                        <!-- Next Page -->
                        <?php if ($currentPaginationPage < $totalPaginationPages): ?>
                            <a href="?page=<?= $currentPaginationPage + 1 ?>" class="pagination-btn" style="padding: 0.5rem 1rem; background: #e2e8f0; color: #2d3748; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;">
                                <span class="hidden-mobile">Sonraki</span>
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            <a href="?page=<?= $totalPaginationPages ?>" class="pagination-btn" style="padding: 0.5rem 1rem; background: #e2e8f0; color: #2d3748; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.2s; display: none;">
                                Son
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <style>
                .pagination-btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
                }
                .pagination-btn.active {
                    pointer-events: none;
                }
                @media (min-width: 640px) {
                    .pagination-btn:first-child,
                    .pagination-btn:last-child {
                        display: flex !important;
                    }
                    .hidden-mobile {
                        display: inline !important;
                    }
                }
                @media (max-width: 639px) {
                    .hidden-mobile {
                        display: none;
                    }
                }
            </style>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Send Message Modal -->
<div class="modal fade" id="sendMessageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span>📤</span>
                    <span>Yeni Mesaj Gönder</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="sendMessageForm">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="hidden" name="provider_id" id="modal_provider_id">
                
                <div class="modal-body">
                    <div class="info-alert">
                        <strong>🎯 Alıcı:</strong> <span id="modal_provider_name"></span>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">📋 Mesaj Tipi</label>
                            <select name="message_type" class="form-select" required>
                                <option value="info">ℹ️ Bilgilendirme</option>
                                <option value="lead">📋 Lead Bilgisi</option>
                                <option value="notification">🔔 Bildirim</option>
                                <option value="announcement">📢 Duyuru</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">🎚️ Öncelik</label>
                            <select name="priority" class="form-select" required>
                                <option value="normal">Normal</option>
                                <option value="high">🟠 Yüksek</option>
                                <option value="urgent">🔴 Acil</option>
                                <option value="low">⚪ Düşük</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">📝 Konu</label>
                        <input type="text" name="subject" class="form-control" required placeholder="Mesaj konusu (Arapça önerilir)">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">💬 Mesaj İçeriği</label>
                        <textarea name="message" class="form-control" rows="6" required placeholder="Mesajınızı buraya yazın... (Arapça önerilir)

Örnek:
عميل جديد: أحمد
الخدمة: سباكة
المدينة: الرياض"></textarea>
                        <small class="text-muted">💡 Provider'lar için Arapça mesaj göndermeyi unutmayın</small>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ İptal</button>
                    <button type="submit" class="btn btn-primary">📤 Mesajı Gönder</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Message History Modal -->
<div class="modal fade" id="messageHistoryModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span>📜</span>
                    <span>Mesaj Geçmişi</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="info-alert">
                    <strong>👤 Provider:</strong> <span id="history_provider_name"></span>
                </div>
                <div id="messageHistoryContent">
                    <div class="text-center py-5">
                        <div class="loading-spinner"></div>
                        <p class="mt-3 text-muted">Mesajlar yükleniyor...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Send Message Modal
function openSendMessageModal(providerId, providerName) {
    document.getElementById('modal_provider_id').value = providerId;
    document.getElementById('modal_provider_name').textContent = providerName;
    document.getElementById('sendMessageForm').reset();
    document.getElementById('modal_provider_id').value = providerId; // Reset後に再設定
    new bootstrap.Modal(document.getElementById('sendMessageModal')).show();
}

// Send Message Form
document.getElementById('sendMessageForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="loading-spinner" style="width:16px;height:16px;border-width:2px"></span> Gönderiliyor...';
    
    try {
        const response = await fetch('/admin/provider-messages/send', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('✅ ' + result.message);
            bootstrap.Modal.getInstance(document.getElementById('sendMessageModal')).hide();
            setTimeout(() => location.reload(), 500);
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('❌ Bir hata oluştu: ' + error.message);
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// View Message History
async function viewMessageHistory(providerId, providerName) {
    console.log('viewMessageHistory called:', providerId, providerName);
    document.getElementById('history_provider_name').textContent = providerName;
    const modal = new bootstrap.Modal(document.getElementById('messageHistoryModal'));
    modal.show();
    
    const content = document.getElementById('messageHistoryContent');
    content.innerHTML = '<div class="text-center py-5"><div class="loading-spinner"></div><p class="mt-3 text-muted">Mesajlar yükleniyor...</p></div>';
    
    try {
        console.log('Fetching:', `/admin/provider-messages/history?provider_id=${providerId}`);
        const response = await fetch(`/admin/provider-messages/history?provider_id=${providerId}`);
        console.log('Response status:', response.status);
        
        // Check for unauthorized (not logged in)
        if (response.status === 401) {
            content.innerHTML = '<div class="alert alert-warning">⚠️ Oturum süreniz dolmuş. Sayfayı yenileyin.</div>';
            setTimeout(() => {
                window.location.reload();
            }, 2000);
            return;
        }
        
        const result = await response.json();
        console.log('Result:', result);
        
        if (result.success && result.messages.length > 0) {
            let html = '';
            result.messages.forEach(msg => {
                const isRead = msg.is_read == 1;
                const typeClasses = {
                    'lead': 'type-lead',
                    'notification': 'type-notification',
                    'announcement': 'type-announcement',
                    'info': 'type-info'
                };
                const typeClass = typeClasses[msg.message_type] || 'type-info';
                
                html += `
                    <div class="message-item ${!isRead ? 'unread' : ''}">
                        <div class="message-header">
                            <div class="message-badges">
                                <span class="badge ${typeClass}">${msg.message_type}</span>
                                ${msg.priority === 'urgent' ? '<span class="badge priority-urgent">🔴 Acil</span>' : ''}
                                ${msg.priority === 'high' ? '<span class="badge priority-high">🟠 Yüksek</span>' : ''}
                                ${!isRead ? '<span class="badge unread-badge">Okunmadı</span>' : ''}
                            </div>
                        </div>
                        <h6 style="font-weight:700; color:#2d3748; margin:1rem 0 0.5rem 0;">${escapeHtml(msg.subject)}</h6>
                        <p style="color:#4a5568; white-space:pre-wrap; margin-bottom:1rem;">${escapeHtml(msg.message)}</p>
                        <small style="color:#a0aec0;">
                            📅 ${new Date(msg.created_at).toLocaleString('tr-TR')}
                            ${msg.read_at ? ' | 👁️ Okundu: ' + new Date(msg.read_at).toLocaleString('tr-TR') : ''}
                        </small>
                    </div>
                `;
            });
            content.innerHTML = html;
        } else {
            content.innerHTML = '<div class="empty-state"><div class="empty-state-icon">📭</div><h3 class="empty-state-title">Henüz Mesaj Yok</h3><p class="empty-state-text">Bu provider\'a henüz mesaj gönderilmemiş</p></div>';
        }
    } catch (error) {
        console.error('Error loading message history:', error);
        content.innerHTML = '<div class="alert alert-danger">❌ Mesajlar yüklenemedi: ' + error.message + '</div>';
    }
}

// Search & Filter
document.getElementById('searchProviders').addEventListener('input', filterProviders);
document.getElementById('filterStatus').addEventListener('change', filterProviders);

function filterProviders() {
    const search = document.getElementById('searchProviders').value.toLowerCase();
    const status = document.getElementById('filterStatus').value;
    const cards = document.querySelectorAll('.provider-card');
    
    cards.forEach(card => {
        const searchText = card.dataset.search;
        const cardStatus = card.dataset.status;
        
        const matchesSearch = !search || searchText.includes(search);
        const matchesStatus = !status || cardStatus === status;
        
        card.style.display = (matchesSearch && matchesStatus) ? 'block' : 'none';
    });
}

// HTML escape helper
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
