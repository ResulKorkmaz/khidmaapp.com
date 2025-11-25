<?php
/**
 * Admin Stats Card Component
 * 
 * İstatistik kartı
 * 
 * @param string $title Kart başlığı
 * @param mixed $value Değer
 * @param string $icon SVG icon path
 * @param string $color Renk (blue, green, yellow, red, purple)
 * @param string $change Değişim yüzdesi (opsiyonel)
 * @param string $changeType Değişim tipi (up, down, neutral)
 */

$title = $title ?? 'İstatistik';
$value = $value ?? 0;
$icon = $icon ?? 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z';
$color = $color ?? 'blue';
$change = $change ?? null;
$changeType = $changeType ?? 'neutral';
$href = $href ?? null;

$colorClasses = [
    'blue' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'icon' => 'text-blue-500'],
    'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'icon' => 'text-green-500'],
    'yellow' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600', 'icon' => 'text-yellow-500'],
    'red' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'icon' => 'text-red-500'],
    'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'icon' => 'text-purple-500'],
    'orange' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600', 'icon' => 'text-orange-500'],
];

$colors = $colorClasses[$color] ?? $colorClasses['blue'];
?>

<?php if ($href): ?>
<a href="<?= $href ?>" class="block">
<?php endif; ?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 <?= $href ? 'hover:shadow-md transition-shadow cursor-pointer' : '' ?>">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1"><?= htmlspecialchars($title) ?></p>
            <p class="text-3xl font-bold text-gray-900"><?= is_numeric($value) ? number_format($value) : $value ?></p>
            
            <?php if ($change !== null): ?>
                <div class="flex items-center gap-1 mt-2">
                    <?php if ($changeType === 'up'): ?>
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                        <span class="text-sm font-medium text-green-600"><?= $change ?></span>
                    <?php elseif ($changeType === 'down'): ?>
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                        <span class="text-sm font-medium text-red-600"><?= $change ?></span>
                    <?php else: ?>
                        <span class="text-sm font-medium text-gray-500"><?= $change ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="w-14 h-14 <?= $colors['bg'] ?> rounded-2xl flex items-center justify-center">
            <svg class="w-7 h-7 <?= $colors['icon'] ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $icon ?>"/>
            </svg>
        </div>
    </div>
</div>

<?php if ($href): ?>
</a>
<?php endif; ?>

