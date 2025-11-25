<?php
/**
 * Admin Alert Component
 * 
 * Bildirim/uyarı mesajı
 * 
 * @param string $type Alert tipi (success, error, warning, info)
 * @param string $message Mesaj
 * @param bool $dismissible Kapatılabilir mi
 * @param string $title Başlık (opsiyonel)
 */

$type = $type ?? 'info';
$message = $message ?? '';
$dismissible = $dismissible ?? true;
$title = $title ?? null;
$id = $id ?? 'alert-' . uniqid();

if (empty($message)) return;

$typeClasses = [
    'success' => [
        'bg' => 'bg-green-50',
        'border' => 'border-green-200',
        'icon' => 'text-green-500',
        'title' => 'text-green-800',
        'text' => 'text-green-700',
    ],
    'error' => [
        'bg' => 'bg-red-50',
        'border' => 'border-red-200',
        'icon' => 'text-red-500',
        'title' => 'text-red-800',
        'text' => 'text-red-700',
    ],
    'warning' => [
        'bg' => 'bg-yellow-50',
        'border' => 'border-yellow-200',
        'icon' => 'text-yellow-500',
        'title' => 'text-yellow-800',
        'text' => 'text-yellow-700',
    ],
    'info' => [
        'bg' => 'bg-blue-50',
        'border' => 'border-blue-200',
        'icon' => 'text-blue-500',
        'title' => 'text-blue-800',
        'text' => 'text-blue-700',
    ],
];

$icons = [
    'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    'error' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
    'warning' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
    'info' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
];

$classes = $typeClasses[$type] ?? $typeClasses['info'];
$icon = $icons[$type] ?? $icons['info'];
?>

<div id="<?= $id ?>" class="<?= $classes['bg'] ?> <?= $classes['border'] ?> border rounded-xl p-4 mb-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="w-5 h-5 <?= $classes['icon'] ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $icon ?>"/>
            </svg>
        </div>
        <div class="ml-3 flex-1">
            <?php if ($title): ?>
                <h3 class="text-sm font-semibold <?= $classes['title'] ?>"><?= htmlspecialchars($title) ?></h3>
            <?php endif; ?>
            <p class="text-sm <?= $classes['text'] ?> <?= $title ? 'mt-1' : '' ?>"><?= $message ?></p>
        </div>
        <?php if ($dismissible): ?>
            <div class="ml-auto pl-3">
                <button onclick="document.getElementById('<?= $id ?>').remove()" 
                        class="inline-flex <?= $classes['icon'] ?> hover:opacity-75 transition-opacity">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

