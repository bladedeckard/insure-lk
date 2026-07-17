<div class="space-y-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Настройка заказа</h2>

    
    <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Нумератор полисов</h3>
        <select wire:model.defer="numerator_id"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            <option value="">— Выберите нумератор —</option>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $numerators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($num->id); ?>"><?php echo e($num->name); ?> (<?php echo e($num->prefix); ?>...)</option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </select>
    </div>

    
    <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Период действия договора</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Дата начала: Сегодня +</label>
                <div class="flex items-center gap-2">
                    <input type="number" wire:model.defer="period_start_days" min="0"
                        class="w-24 border border-gray-300 rounded px-3 py-2 text-sm">
                    <span class="text-sm text-gray-600">дней</span>
                </div>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Дата окончания: Дата начала +</label>
                <div class="flex items-center gap-2">
                    <input type="number" wire:model.defer="period_end_value" min="1"
                        class="w-24 border border-gray-300 rounded px-3 py-2 text-sm">
                    <select wire:model="period_end_unit"
                        class="border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="days">дней</option>
                        <option value="years">лет</option>
                    </select>
                </div>
            </div>
            <div class="flex items-end">
                <div class="text-sm text-gray-500 bg-white px-3 py-2 rounded border">
                    Пример: сегодня + <?php echo e($period_start_days); ?> дн. → + <?php echo e($period_end_value); ?> <?php echo e($period_end_unit === 'years' ? 'год(а)/лет' : 'дн.'); ?>

                </div>
            </div>
        </div>
    </div>

    
    <div>
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-sm font-semibold text-gray-700">Ограничения на заказ</h3>
            <button wire:click="addOrderRestriction" class="px-3 py-1 bg-red-100 text-red-700 rounded text-sm hover:bg-red-200">
                + Добавить ограничение
            </button>
        </div>

        <p class="text-xs text-gray-500 mb-4">
            Условия, при которых оформление полиса запрещено. Например: регион не Москва, возраст меньше 18 лет.
        </p>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($orderRestrictions)): ?>
            <p class="text-gray-400 text-sm text-center py-6">Нет ограничений на заказ</p>
        <?php else: ?>
            <div class="space-y-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $orderRestrictions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rIndex => $restriction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-red-200 bg-red-50 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1 space-y-2">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-xs text-gray-500">Сообщение об ошибке</label>
                                        <input type="text" 
                                            wire:model.defer="orderRestrictions.<?php echo e($rIndex); ?>.error_message"
                                            class="w-full border border-gray-300 rounded px-2 py-1 text-sm"
                                            placeholder="Оформление запрещено: возраст менее 18 лет">
                                    </div>
                                    <div class="flex gap-3">
                                        <div>
                                            <label class="text-xs text-gray-500">Действие</label>
                                            <select wire:model.defer="orderRestrictions.<?php echo e($rIndex); ?>.action"
                                                class="border border-gray-300 rounded px-2 py-1 text-sm">
                                                <option value="block">Блокировать</option>
                                                <option value="approval">На согласование</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Логика</label>
                                            <select wire:model.defer="orderRestrictions.<?php echo e($rIndex); ?>.logic"
                                                class="border border-gray-300 rounded px-2 py-1 text-sm">
                                                <option value="and">И (все условия)</option>
                                                <option value="or">ИЛИ (любое условие)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button wire:click="removeOrderRestriction(<?php echo e($rIndex); ?>)"
                                class="ml-3 text-red-500 hover:text-red-700 text-sm">Удалить</button>
                        </div>

                        
                        <div class="space-y-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $restriction['conditions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cIndex => $condition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center gap-2 bg-white rounded p-2">
                                    <?php echo $__env->make('livewire.products.partials.field-code-select', [
                                        'inputName' => 'orderRestrictions.' . $rIndex . '.conditions.' . $cIndex . '.field_code',
                                        'allFields' => $fields ?? [],
                                        'allCoverages' => $coverages ?? [],
                                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <select wire:model.defer="orderRestrictions.<?php echo e($rIndex); ?>.conditions.<?php echo e($cIndex); ?>.operator"
                                        class="w-32 border border-gray-300 rounded px-2 py-1 text-sm">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $operators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($op); ?>"><?php echo e($label); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>
                                    <input type="text"
                                        wire:model.defer="orderRestrictions.<?php echo e($rIndex); ?>.conditions.<?php echo e($cIndex); ?>.value"
                                        class="flex-1 border border-gray-300 rounded px-2 py-1 text-sm"
                                        placeholder="Значение">
                                    <button wire:click="removeOrderCondition(<?php echo e($rIndex); ?>, <?php echo e($cIndex); ?>)"
                                        class="text-red-400 hover:text-red-600">✕</button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <button wire:click="addOrderCondition(<?php echo e($rIndex); ?>)"
                                class="text-sm text-blue-600 hover:text-blue-800">+ Добавить условие</button>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/products/tabs/order.blade.php ENDPATH**/ ?>