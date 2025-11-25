<?php
/**
 * Admin Badge Component
 * 
 * Durum etiketi
 * 
 * @param string $text Badge metni
 * @param string $color Renk (blue, green, yellow, red, gray, purple, orange)
 * @param string $size Boyut (sm, md, lg)
 * @param bool $dot Nokta göster
 */

$text = $text ?? '';
$color = $color ?? 'gray';
$size = $size ?? 'md';
$dot = $dot ?? false;

$colorClasses = [
    'blue' => 'bg-blue-100 text-blue-800',
    'green' => 'bg-green-100 text-green-800',
    'yellow' => 'bg-yellow-100 text-yellow-800',
    'red' => 'bg-red-100 text-red-800',
    'gray' => 'bg-gray-100 text-gray-800',
    'purple' => 'bg-purple-100 text-purple-800',
    'orange' => 'bg-orange-100 text-orange-800',
];

$dotColors = [
    'blue' => 'bg-blue-500',
    'green' => 'bg-green-500',
    'yellow' => 'bg-yellow-500',
    'red' => 'bg-red-500',
    'gray' => 'bg-gray-500',
    'purple' => 'bg-purple-500',
    'orange' => 'bg-orange-500',
];

$sizeClasses = [
    'sm' => 'px-2 py-0.5 text-xs',
    'md' => 'px-2.5 py-1 text-xs',
    'lg' => 'px-3 py-1.5 text-sm',
];

$colorClass = $colorClasses[$color] ?? $colorClasses['gray'];
$dotColor = $dotColors[$color] ?? $dotColors['gray'];
$sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
?>

<span class="inline-flex items-center gap-1.5 <?= $colorClass ?> <?= $sizeClass ?> font-semibold rounded-full">
    <?php if ($dot): ?>
        <span class="w-1.5 h-1.5 <?= $dotColor ?> rounded-full"></span>
    <?php endif; ?>
    <?= htmlspecialchars($text) ?>
</span>

