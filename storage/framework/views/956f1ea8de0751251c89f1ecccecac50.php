<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Настройка полей формы полиса</h2>
        <div class="flex gap-2">
            <button wire:click="addFieldGroup" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">
                + Группа
            </button>
            <button wire:click="addField" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                + Поле
            </button>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($fieldGroups) && empty($fields)): ?>
        <div class="text-center py-12 text-gray-400">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
            </svg>
            <p class="mt-2">Нет полей. Создайте группу и добавьте поля.</p>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            
            <?php $ungroupedFields = collect($fields)->filter(fn($f) => empty($f['group_id']))->values(); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ungroupedFields->isNotEmpty()): ?>
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-500 mb-3">Без группы</h3>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $ungroupedFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $realIndex = collect($fields)->search(fn($f) => $f === $field); ?>
                        <?php echo $__env->make('livewire.products.partials.field-row', ['field' => $field, 'index' => $realIndex, 'fieldTypes' => $fieldTypes], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $fieldGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gIndex => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border border-blue-200 bg-blue-50 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-3">
                            <input type="text" wire:model.defer="fieldGroups.<?php echo e($gIndex); ?>.name"
                                class="font-semibold text-gray-800 border-none bg-transparent focus:ring-0 text-base">
                            <span class="text-xs text-gray-400"><?php echo e($group['code'] ?? ''); ?></span>
                        </div>
                        <button wire:click="removeFieldGroup(<?php echo e($gIndex); ?>)"
                            onclick="return confirm('Удалить группу со всеми полями?')"
                            class="text-red-500 hover:text-red-700 text-sm">Удалить группу</button>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($group['description'])): ?>
                        <input type="text" wire:model.defer="fieldGroups.<?php echo e($gIndex); ?>.description"
                            class="text-sm text-gray-500 border-none bg-transparent w-full mb-2"
                            placeholder="Описание группы...">
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php $groupFields = collect($fields)->filter(fn($f) => $f['group_id'] == $group['id'])->values(); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $groupFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $realIndex = collect($fields)->search(fn($f) => $f === $field); ?>
                        <?php echo $__env->make('livewire.products.partials.field-row', ['field' => $field, 'index' => $realIndex, 'fieldTypes' => $fieldTypes], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/products/tabs/fields.blade.php ENDPATH**/ ?>