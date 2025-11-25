<?php
/**
 * Admin Table Component
 * 
 * Responsive tablo
 * 
 * @param array $columns Sütun tanımları [['key' => 'id', 'label' => 'ID', 'class' => ''], ...]
 * @param array $rows Veri satırları
 * @param array $actions Satır aksiyonları (opsiyonel)
 * @param bool $selectable Seçilebilir satırlar (checkbox)
 * @param string $emptyMessage Boş tablo mesajı
 */

$columns = $columns ?? [];
$rows = $rows ?? [];
$actions = $actions ?? [];
$selectable = $selectable ?? false;
$emptyMessage = $emptyMessage ?? 'Kayıt bulunamadı';
$rowIdKey = $rowIdKey ?? 'id';
?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <?php if ($selectable): ?>
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                    <?php endif; ?>
                    
                    <?php foreach ($columns as $column): ?>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider <?= $column['class'] ?? '' ?>">
                            <?= htmlspecialchars($column['label']) ?>
                        </th>
                    <?php endforeach; ?>
                    
                    <?php if (!empty($actions)): ?>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            İşlemler
                        </th>
                    <?php endif; ?>
                </tr>
            </thead>
            
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="<?= count($columns) + ($selectable ? 1 : 0) + (!empty($actions) ? 1 : 0) ?>" 
                            class="px-4 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-lg font-medium"><?= htmlspecialchars($emptyMessage) ?></p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <?php if ($selectable): ?>
                                <td class="px-4 py-3">
                                    <input type="checkbox" name="selected[]" value="<?= $row[$rowIdKey] ?>" 
                                           class="row-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                            <?php endif; ?>
                            
                            <?php foreach ($columns as $column): ?>
                                <td class="px-4 py-3 <?= $column['tdClass'] ?? '' ?>">
                                    <?php 
                                    $value = $row[$column['key']] ?? '';
                                    
                                    // Özel render fonksiyonu varsa kullan
                                    if (isset($column['render']) && is_callable($column['render'])) {
                                        echo $column['render']($value, $row);
                                    } else {
                                        echo htmlspecialchars($value);
                                    }
                                    ?>
                                </td>
                            <?php endforeach; ?>
                            
                            <?php if (!empty($actions)): ?>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <?php foreach ($actions as $action): ?>
                                            <?php 
                                            // Action URL'ini oluştur
                                            $actionUrl = str_replace('{id}', $row[$rowIdKey], $action['href'] ?? '#');
                                            ?>
                                            <a href="<?= $actionUrl ?>" 
                                               class="p-2 <?= $action['class'] ?? 'text-gray-400 hover:text-blue-600 hover:bg-blue-50' ?> rounded-lg transition-colors"
                                               title="<?= $action['title'] ?? '' ?>">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $action['icon'] ?>"/>
                                                </svg>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($selectable): ?>
<script>
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
});
</script>
<?php endif; ?>

