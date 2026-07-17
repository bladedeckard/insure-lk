
<?php
    $allFieldOptions = [];
    
    // Поля продукта
    foreach ($allFields ?? [] as $f) {
        $allFieldOptions[] = [
            'value' => $f['code'] ?? '',
            'label' => ($f['name'] ?? '') . ' — ' . ($f['code'] ?? ''),
        ];
    }
    
    // Покрытия продукта
    foreach ($allCoverages ?? [] as $c) {
        if (!empty($c['code'])) {
            $allFieldOptions[] = [
                'value' => $c['code'],
                'label' => ($c['name'] ?? '') . ' — ' . $c['code'],
            ];
        }
    }
    
    // Вычисляемые поля
    $allFieldOptions[] = ['value' => 'age', 'label' => 'Возраст (вычисляемый) — age'];
    $allFieldOptions[] = ['value' => 'sum_insured', 'label' => 'Общая страховая сумма — sum_insured'];
    
    $selectId = 'fc_' . substr(md5($inputName . microtime()), 0, 8);
    
    // Текущее значение
    $currentValue = '';
    // Пытаемся получить текущее значение из wire:model
    $parts = explode('.', $inputName);
    // Не пытаемся резолвить — просто покажем все options
?>

<div class="relative">
    <select
        wire:model.defer="<?php echo e($inputName); ?>"
        class="<?php echo e($inputClass ?? 'w-40 border border-gray-300 rounded px-2 py-1 text-sm'); ?>"
        id="<?php echo e($selectId); ?>">
        <option value="">— Выберите поле —</option>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($allFieldOptions)): ?>
            <optgroup label="Поля формы (<?php echo e(count($allFieldOptions)); ?>)">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $allFieldOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($opt['value']); ?>"><?php echo e($opt['label']); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </optgroup>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </select>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($allFieldOptions)): ?>
        <p class="text-xs text-red-400 mt-1">Нет доступных полей — добавьте поля на вкладке «Настройка полей»</p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/products/partials/field-code-select.blade.php ENDPATH**/ ?>