<?php
/**
 * Admin Header Component
 * 
 * Sayfa başlığı ve üst bar
 * 
 * @param string $title Sayfa başlığı
 * @param string $subtitle Alt başlık (opsiyonel)
 * @param string $icon SVG icon path (opsiyonel)
 * @param array $actions Aksiyon butonları (opsiyonel)
 */

$title = $title ?? 'Sayfa';
$subtitle = $subtitle ?? '';
$icon = $icon ?? 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6';
$iconBg = $iconBg ?? 'bg-blue-600';
$actions = $actions ?? [];
?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
    <div class="flex items-center justify-between px-6 py-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 <?= $iconBg ?> rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $icon ?>"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900"><?= htmlspecialchars($title) ?></h2>
                <?php if (!empty($subtitle)): ?>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($subtitle) ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (!empty($actions)): ?>
            <div class="flex items-center gap-2">
                <?php foreach ($actions as $action): ?>
                    <?php if ($action['type'] === 'link'): ?>
                        <a href="<?= $action['href'] ?>" 
                           class="flex items-center gap-2 px-4 py-2 <?= $action['class'] ?? 'bg-blue-600 hover:bg-blue-700 text-white' ?> rounded-xl transition-colors font-semibold">
                            <?php if (!empty($action['icon'])): ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $action['icon'] ?>"/>
                                </svg>
                            <?php endif; ?>
                            <span><?= htmlspecialchars($action['label']) ?></span>
                        </a>
                    <?php elseif ($action['type'] === 'button'): ?>
                        <button onclick="<?= $action['onclick'] ?? '' ?>" 
                                class="flex items-center gap-2 px-4 py-2 <?= $action['class'] ?? 'bg-gray-100 hover:bg-gray-200 text-gray-700' ?> rounded-xl transition-colors font-semibold">
                            <?php if (!empty($action['icon'])): ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $action['icon'] ?>"/>
                                </svg>
                            <?php endif; ?>
                            <span><?= htmlspecialchars($action['label']) ?></span>
                        </button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

