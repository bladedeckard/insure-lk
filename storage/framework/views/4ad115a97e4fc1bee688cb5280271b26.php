<div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
    <div class="flex items-center gap-3 flex-1">
        <div class="flex flex-col gap-0.5">
            <button wire:click="moveFieldUp(<?php echo e($index); ?>)" class="text-gray-300 hover:text-gray-500 text-xs">▲</button>
            <button wire:click="moveFieldDown(<?php echo e($index); ?>)" class="text-gray-300 hover:text-gray-500 text-xs">▼</button>
        </div>
        <div class="flex-1">
            <div class="flex items-center gap-2">
                <span class="font-medium text-sm text-gray-800"><?php echo e($field['name']); ?></span>
                <span class="text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-600">
                    <?php echo e($fieldTypes[$field['type']] ?? $field['type']); ?>

                </span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($field['required']): ?>
                    <span class="text-xs px-2 py-0.5 rounded bg-red-100 text-red-600">Обязательно</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($field['linked_to'])): ?>
                    <span class="text-xs px-2 py-0.5 rounded bg-purple-100 text-purple-600">↔ <?php echo e($field['linked_to']); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="text-xs text-gray-400 mt-0.5">
                <code><?php echo e($field['code']); ?></code>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($field['mask'])): ?> · Маска: <?php echo e($field['mask']); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($field['regex'])): ?> · Regex: <?php echo e($field['regex']); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($field['hint'])): ?> · 💬 <?php echo e($field['hint']); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
    <div class="flex gap-1 ml-3">
        <button wire:click="editField(<?php echo e($index); ?>)" class="px-2 py-1 text-xs text-blue-600 hover:text-blue-800">Изменить</button>
        <button wire:click="removeField(<?php echo e($index); ?>)" onclick="return confirm('Удалить поле?')"
            class="px-2 py-1 text-xs text-red-600 hover:text-red-800">Удалить</button>
    </div>
</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/products/partials/field-row.blade.php ENDPATH**/ ?>