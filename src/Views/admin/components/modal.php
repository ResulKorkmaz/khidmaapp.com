<?php
/**
 * Admin Modal Component
 * 
 * Modal dialog
 * 
 * @param string $id Modal ID
 * @param string $title Modal başlığı
 * @param string $size Modal boyutu (sm, md, lg, xl, full)
 * @param string $content Modal içeriği (slot)
 * @param array $footer Footer butonları (opsiyonel)
 */

$id = $id ?? 'modal';
$title = $title ?? 'Modal';
$size = $size ?? 'md';
$footer = $footer ?? [];

$sizeClasses = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    'full' => 'max-w-full mx-4',
];

$sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
?>

<!-- Modal Backdrop -->
<div id="<?= $id ?>" class="fixed inset-0 z-50 hidden">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeModal('<?= $id ?>')"></div>
    
    <!-- Modal Content -->
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-xl <?= $sizeClass ?> w-full transform transition-all">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($title) ?></h3>
                    <button onclick="closeModal('<?= $id ?>')" 
                            class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Body -->
                <div class="px-6 py-4" id="<?= $id ?>-body">
                    <?= $content ?? '' ?>
                </div>
                
                <!-- Footer -->
                <?php if (!empty($footer)): ?>
                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                        <?php foreach ($footer as $button): ?>
                            <button type="<?= $button['type'] ?? 'button' ?>"
                                    onclick="<?= $button['onclick'] ?? '' ?>"
                                    class="px-4 py-2 <?= $button['class'] ?? 'bg-gray-100 hover:bg-gray-200 text-gray-700' ?> rounded-xl font-semibold transition-colors">
                                <?= htmlspecialchars($button['label']) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.body.style.overflow = '';
}

// ESC tuşu ile kapatma
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id^="modal"]').forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                closeModal(modal.id);
            }
        });
    }
});
</script>

