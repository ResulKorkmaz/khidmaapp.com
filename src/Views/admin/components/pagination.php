<?php
/**
 * Admin Pagination Component
 * 
 * Sayfalama
 * 
 * @param int $currentPage Mevcut sayfa
 * @param int $totalPages Toplam sayfa sayısı
 * @param string $baseUrl Temel URL (query string olmadan)
 * @param array $queryParams Ek query parametreleri
 */

$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$baseUrl = $baseUrl ?? '';
$queryParams = $queryParams ?? [];

if ($totalPages <= 1) return;

// URL oluşturma fonksiyonu
function buildPaginationUrl($baseUrl, $page, $queryParams) {
    $queryParams['page'] = $page;
    return $baseUrl . '?' . http_build_query($queryParams);
}

// Sayfa aralığını hesapla
$range = 2; // Her iki yanda gösterilecek sayfa sayısı
$start = max(1, $currentPage - $range);
$end = min($totalPages, $currentPage + $range);
?>

<div class="flex items-center justify-between mt-6">
    <div class="text-sm text-gray-500">
        Sayfa <span class="font-semibold"><?= $currentPage ?></span> / <span class="font-semibold"><?= $totalPages ?></span>
    </div>
    
    <div class="flex items-center gap-1">
        <!-- İlk Sayfa -->
        <?php if ($currentPage > 1): ?>
            <a href="<?= buildPaginationUrl($baseUrl, 1, $queryParams) ?>" 
               class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
               title="İlk Sayfa">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </a>
        <?php endif; ?>
        
        <!-- Önceki Sayfa -->
        <?php if ($currentPage > 1): ?>
            <a href="<?= buildPaginationUrl($baseUrl, $currentPage - 1, $queryParams) ?>" 
               class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
               title="Önceki Sayfa">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        <?php endif; ?>
        
        <!-- Sayfa Numaraları -->
        <?php if ($start > 1): ?>
            <span class="px-2 text-gray-400">...</span>
        <?php endif; ?>
        
        <?php for ($i = $start; $i <= $end; $i++): ?>
            <?php if ($i === $currentPage): ?>
                <span class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg">
                    <?= $i ?>
                </span>
            <?php else: ?>
                <a href="<?= buildPaginationUrl($baseUrl, $i, $queryParams) ?>" 
                   class="px-4 py-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 font-medium rounded-lg transition-colors">
                    <?= $i ?>
                </a>
            <?php endif; ?>
        <?php endfor; ?>
        
        <?php if ($end < $totalPages): ?>
            <span class="px-2 text-gray-400">...</span>
        <?php endif; ?>
        
        <!-- Sonraki Sayfa -->
        <?php if ($currentPage < $totalPages): ?>
            <a href="<?= buildPaginationUrl($baseUrl, $currentPage + 1, $queryParams) ?>" 
               class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
               title="Sonraki Sayfa">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        <?php endif; ?>
        
        <!-- Son Sayfa -->
        <?php if ($currentPage < $totalPages): ?>
            <a href="<?= buildPaginationUrl($baseUrl, $totalPages, $queryParams) ?>" 
               class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
               title="Son Sayfa">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                </svg>
            </a>
        <?php endif; ?>
    </div>
</div>

