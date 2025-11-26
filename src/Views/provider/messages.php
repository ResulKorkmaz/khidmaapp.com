<?php ob_start(); 

// Mesajları grupla: okunmamış ve okunmuş
$unreadMessages = array_filter($messages ?? [], fn($m) => !$m['is_read']);
$readMessages = array_filter($messages ?? [], fn($m) => $m['is_read']);
$totalUnread = count($unreadMessages);

// Mesaj türü ikonları ve renkleri
$messageTypeConfig = [
    'info' => ['icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'bg' => 'bg-blue-500', 'light' => 'bg-blue-50', 'text' => 'text-blue-600'],
    'notification' => ['icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'bg' => 'bg-green-500', 'light' => 'bg-green-50', 'text' => 'text-green-600'],
    'announcement' => ['icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z', 'bg' => 'bg-yellow-500', 'light' => 'bg-yellow-50', 'text' => 'text-yellow-600'],
    'lead' => ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'bg' => 'bg-purple-500', 'light' => 'bg-purple-50', 'text' => 'text-purple-600'],
];

function getMessageConfig($type, $config) {
    $type = $type ?? 'info';
    return $config[$type] ?? $config['info'];
}

function formatMessageDate($date) {
    $timestamp = strtotime($date);
    $now = time();
    $diff = $now - $timestamp;
    
    if ($diff < 60) return 'الآن';
    if ($diff < 3600) return floor($diff / 60) . ' دقيقة';
    if ($diff < 86400) return floor($diff / 3600) . ' ساعة';
    if ($diff < 604800) return floor($diff / 86400) . ' يوم';
    return date('Y-m-d', $timestamp);
}
?>

<!-- Header -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">صندوق الرسائل</h1>
                <p class="text-sm text-gray-600">رسائل من إدارة KhidmaApp</p>
            </div>
        </div>
        
        <?php if ($totalUnread > 0): ?>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 bg-red-100 text-red-700 rounded-full text-sm font-bold flex items-center gap-2">
                <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                <?= $totalUnread ?> رسالة جديدة
            </span>
            <button onclick="markAllAsRead()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors">
                تحديد الكل كمقروء
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if (empty($messages)): ?>
    <!-- Empty State -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">لا توجد رسائل</h3>
        <p class="text-gray-500 max-w-md mx-auto">صندوق الرسائل فارغ حالياً. ستظهر هنا الرسائل من إدارة KhidmaApp</p>
    </div>
<?php else: ?>
    <!-- Messages List -->
    <div class="space-y-4">
        <?php if (!empty($unreadMessages)): ?>
        <!-- Unread Messages Section -->
        <div class="mb-6">
            <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                رسائل جديدة (<?= count($unreadMessages) ?>)
            </h2>
            <div class="space-y-3">
                <?php foreach ($unreadMessages as $message): 
                    $config = getMessageConfig($message['message_type'] ?? 'info', $messageTypeConfig);
                    $priority = $message['priority'] ?? 'normal';
                ?>
                <div class="message-card bg-white rounded-xl border-2 border-blue-200 shadow-md hover:shadow-lg transition-all overflow-hidden" 
                     data-message-id="<?= $message['id'] ?>">
                    <!-- Priority Bar -->
                    <?php if ($priority === 'high'): ?>
                    <div class="h-1 bg-red-500"></div>
                    <?php elseif ($priority === 'urgent'): ?>
                    <div class="h-1 bg-red-600 animate-pulse"></div>
                    <?php endif; ?>
                    
                    <div class="p-4">
                        <div class="flex items-start gap-4">
                            <!-- Icon -->
                            <div class="w-12 h-12 <?= $config['bg'] ?> rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $config['icon'] ?>"/>
                                </svg>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-1 <?= $config['light'] ?> <?= $config['text'] ?> rounded-lg text-xs font-bold">
                                            <?php 
                                            $typeLabels = ['info' => 'معلومة', 'notification' => 'إشعار', 'announcement' => 'إعلان', 'lead' => 'طلب جديد'];
                                            echo $typeLabels[$message['message_type'] ?? 'info'] ?? 'إشعار';
                                            ?>
                                        </span>
                                        <?php if ($priority === 'high' || $priority === 'urgent'): ?>
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-bold">
                                            <?= $priority === 'urgent' ? '🔴 عاجل' : '⚠️ مهم' ?>
                                        </span>
                                        <?php endif; ?>
                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                    </div>
                                    <span class="text-xs text-gray-400"><?= formatMessageDate($message['created_at']) ?></span>
                                </div>
                                
                                <h3 class="font-bold text-gray-900 mb-1"><?= htmlspecialchars($message['subject'] ?? 'رسالة جديدة') ?></h3>
                                <p class="text-gray-600 text-sm leading-relaxed line-clamp-2"><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                                
                                <!-- Actions -->
                                <div class="flex items-center gap-2 mt-3">
                                    <button onclick="openMessageModal(<?= htmlspecialchars(json_encode($message)) ?>)" 
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        قراءة الرسالة
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($readMessages)): ?>
        <!-- Read Messages Section -->
        <div>
            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-3">
                الرسائل السابقة (<?= count($readMessages) ?>)
            </h2>
            <div class="space-y-2">
                <?php foreach ($readMessages as $message): 
                    $config = getMessageConfig($message['message_type'] ?? 'info', $messageTypeConfig);
                ?>
                <div class="message-card bg-white rounded-xl border border-gray-200 hover:border-gray-300 transition-all overflow-hidden opacity-75 hover:opacity-100" 
                     data-message-id="<?= $message['id'] ?>">
                    <div class="p-4">
                        <div class="flex items-start gap-4">
                            <!-- Icon -->
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $config['icon'] ?>"/>
                                </svg>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <h3 class="font-semibold text-gray-700 text-sm"><?= htmlspecialchars($message['subject'] ?? 'رسالة') ?></h3>
                                    <span class="text-xs text-gray-400"><?= formatMessageDate($message['created_at']) ?></span>
                                </div>
                                <p class="text-gray-500 text-sm line-clamp-1"><?= htmlspecialchars(substr($message['message'], 0, 100)) ?>...</p>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex items-center gap-1 flex-shrink-0">
                                <button onclick="openMessageModal(<?= htmlspecialchars(json_encode($message)) ?>)" 
                                        class="p-2 hover:bg-gray-100 rounded-lg transition-colors" title="عرض">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <button onclick="deleteMessage(<?= $message['id'] ?>)" 
                                        class="p-2 hover:bg-red-50 rounded-lg transition-colors" title="حذف">
                                    <svg class="w-4 h-4 text-gray-400 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- Message Detail Modal -->
<div id="messageModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div id="modal-header" class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div id="modal-icon" class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="modal-subject" class="text-xl font-bold">رسالة</h3>
                        <p id="modal-date" class="text-blue-100 text-sm">-</p>
                    </div>
                </div>
                <button onclick="closeMessageModal()" class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div id="modal-badges" class="flex items-center gap-2 mb-4">
                <!-- Badges will be inserted here -->
            </div>
            
            <div id="modal-message" class="text-gray-700 leading-relaxed whitespace-pre-wrap">
                -
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 rounded-b-2xl border-t border-gray-200">
            <div class="flex gap-3">
                <button onclick="closeMessageModal()" class="flex-1 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition-colors">
                    إغلاق
                </button>
                <button onclick="deleteCurrentMessage()" class="py-3 px-6 bg-red-100 hover:bg-red-200 text-red-700 font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    حذف
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentMessageId = null;

const typeConfig = {
    info: { bg: 'from-blue-500 to-blue-600', label: 'معلومة', labelBg: 'bg-blue-100', labelText: 'text-blue-700' },
    notification: { bg: 'from-green-500 to-green-600', label: 'إشعار', labelBg: 'bg-green-100', labelText: 'text-green-700' },
    announcement: { bg: 'from-yellow-500 to-yellow-600', label: 'إعلان', labelBg: 'bg-yellow-100', labelText: 'text-yellow-700' },
    lead: { bg: 'from-purple-500 to-purple-600', label: 'طلب جديد', labelBg: 'bg-purple-100', labelText: 'text-purple-700' }
};

function openMessageModal(message) {
    currentMessageId = message.id;
    const config = typeConfig[message.message_type] || typeConfig.info;
    
    // Update header
    document.getElementById('modal-header').className = `bg-gradient-to-r ${config.bg} p-6 text-white rounded-t-2xl`;
    document.getElementById('modal-subject').textContent = message.subject || 'رسالة';
    document.getElementById('modal-date').textContent = new Date(message.created_at).toLocaleDateString('ar-SA', {
        year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
    });
    
    // Update badges
    let badgesHtml = `<span class="px-3 py-1 ${config.labelBg} ${config.labelText} rounded-lg text-sm font-bold">${config.label}</span>`;
    if (message.priority === 'high') {
        badgesHtml += '<span class="px-3 py-1 bg-red-100 text-red-700 rounded-lg text-sm font-bold">⚠️ مهم</span>';
    } else if (message.priority === 'urgent') {
        badgesHtml += '<span class="px-3 py-1 bg-red-100 text-red-700 rounded-lg text-sm font-bold animate-pulse">🔴 عاجل</span>';
    }
    document.getElementById('modal-badges').innerHTML = badgesHtml;
    
    // Update message
    document.getElementById('modal-message').textContent = message.message;
    
    // Show modal
    document.getElementById('messageModal').classList.remove('hidden');
    
    // Mark as read if unread
    if (!message.is_read) {
        markAsRead(message.id);
    }
}

function closeMessageModal() {
    document.getElementById('messageModal').classList.add('hidden');
    currentMessageId = null;
}

async function markAsRead(messageId) {
    try {
        const formData = new FormData();
        formData.append('message_id', messageId);
        formData.append('csrf_token', getCsrfToken());
        
        const response = await fetch('/provider/messages/mark-read', {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            // Update UI - remove unread indicator
            const card = document.querySelector(`[data-message-id="${messageId}"]`);
            if (card) {
                card.classList.remove('border-blue-200', 'shadow-md');
                card.classList.add('border-gray-200', 'opacity-75');
            }
        }
    } catch (error) {
        console.error('Error marking as read:', error);
    }
}

async function markAllAsRead() {
    try {
        const formData = new FormData();
        formData.append('csrf_token', getCsrfToken());
        
        const response = await fetch('/provider/messages/mark-all-read', {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            window.location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function deleteMessage(messageId) {
    if (!confirm('هل أنت متأكد من حذف هذه الرسالة؟')) return;
    
    try {
        const formData = new FormData();
        formData.append('message_id', messageId);
        formData.append('csrf_token', getCsrfToken());
        
        const response = await fetch('/provider/messages/delete', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            const card = document.querySelector(`[data-message-id="${messageId}"]`);
            if (card) {
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '0';
                card.style.transform = 'translateX(-20px)';
                setTimeout(() => card.remove(), 300);
            }
        } else {
            alert(result.message || 'حدث خطأ');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('حدث خطأ أثناء الحذف');
    }
}

function deleteCurrentMessage() {
    if (currentMessageId) {
        deleteMessage(currentMessageId);
        closeMessageModal();
    }
}

// ESC to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeMessageModal();
});

// Click outside to close
document.getElementById('messageModal').addEventListener('click', function(e) {
    if (e.target === this) closeMessageModal();
});
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'صندوق الرسائل';
$currentPage = 'messages';
require __DIR__ . '/layout.php';
?>

