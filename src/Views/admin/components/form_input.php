<?php
/**
 * Admin Form Input Component
 * 
 * Form input alanı
 * 
 * @param string $name Input adı
 * @param string $label Label metni
 * @param string $type Input tipi (text, email, password, number, textarea, select)
 * @param mixed $value Değer
 * @param array $options Select için seçenekler
 * @param string $placeholder Placeholder
 * @param bool $required Zorunlu alan
 * @param string $error Hata mesajı
 * @param string $help Yardım metni
 * @param array $attributes Ek HTML attributes
 */

$name = $name ?? '';
$label = $label ?? '';
$type = $type ?? 'text';
$value = $value ?? '';
$options = $options ?? [];
$placeholder = $placeholder ?? '';
$required = $required ?? false;
$error = $error ?? '';
$help = $help ?? '';
$attributes = $attributes ?? [];
$id = $id ?? $name;
$class = $class ?? '';

// Attribute string oluştur
$attrString = '';
foreach ($attributes as $key => $val) {
    $attrString .= " {$key}=\"" . htmlspecialchars($val) . "\"";
}

$inputClass = "w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors " . 
              ($error ? 'border-red-300 bg-red-50' : 'border-gray-200') . " " . $class;
?>

<div class="mb-4">
    <?php if ($label): ?>
        <label for="<?= $id ?>" class="block text-sm font-semibold text-gray-700 mb-2">
            <?= htmlspecialchars($label) ?>
            <?php if ($required): ?>
                <span class="text-red-500">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>
    
    <?php if ($type === 'textarea'): ?>
        <textarea 
            name="<?= $name ?>" 
            id="<?= $id ?>" 
            placeholder="<?= htmlspecialchars($placeholder) ?>"
            class="<?= $inputClass ?>"
            <?= $required ? 'required' : '' ?>
            <?= $attrString ?>
        ><?= htmlspecialchars($value) ?></textarea>
        
    <?php elseif ($type === 'select'): ?>
        <select 
            name="<?= $name ?>" 
            id="<?= $id ?>" 
            class="<?= $inputClass ?>"
            <?= $required ? 'required' : '' ?>
            <?= $attrString ?>
        >
            <?php if ($placeholder): ?>
                <option value=""><?= htmlspecialchars($placeholder) ?></option>
            <?php endif; ?>
            <?php foreach ($options as $optValue => $optLabel): ?>
                <option value="<?= htmlspecialchars($optValue) ?>" <?= $value == $optValue ? 'selected' : '' ?>>
                    <?= htmlspecialchars($optLabel) ?>
                </option>
            <?php endforeach; ?>
        </select>
        
    <?php else: ?>
        <input 
            type="<?= $type ?>" 
            name="<?= $name ?>" 
            id="<?= $id ?>" 
            value="<?= htmlspecialchars($value) ?>"
            placeholder="<?= htmlspecialchars($placeholder) ?>"
            class="<?= $inputClass ?>"
            <?= $required ? 'required' : '' ?>
            <?= $attrString ?>
        >
    <?php endif; ?>
    
    <?php if ($error): ?>
        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    
    <?php if ($help && !$error): ?>
        <p class="mt-1 text-sm text-gray-500"><?= htmlspecialchars($help) ?></p>
    <?php endif; ?>
</div>

