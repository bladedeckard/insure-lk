<div class="space-y-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Основная информация</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Название продукта *</label>
            <input type="text" wire:model.defer="name"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Страхование квартиры «Страху.Нет»">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Маркетинговое название</label>
            <input type="text" wire:model.defer="marketing_name"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Страху.Нет">
        </div>

        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Код продукта *</label>
            <input type="text" wire:model.defer="code"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="strahu_net">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Валюта расчётов *</label>
            <select wire:model="currency"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($code); ?>"><?php echo e($label); ?> (<?php echo e($code); ?>)</option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </select>
        </div>
    </div>

    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Описание продукта</label>
        <textarea wire:model.defer="description" rows="3"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Краткое описание продукта..."></textarea>
    </div>

    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Посредники
            <span class="text-gray-400 text-sm font-normal">(если пусто — продукт для прямых продаж)</span>
        </label>
        <div class="border border-gray-300 rounded-lg p-4 max-h-48 overflow-y-auto">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $intermediaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $intermediary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <label class="flex items-center gap-2 py-1 cursor-pointer hover:bg-gray-50 px-2 rounded">
                    <input type="checkbox"
                        value="<?php echo e($intermediary->id); ?>"
                        wire:model.defer="selectedIntermediaries"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm"><?php echo e($intermediary->name); ?></span>
                    <span class="text-xs text-gray-400">(<?php echo e($intermediary->inn ?? 'без ИНН'); ?>)</span>
                </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-400 text-sm">Нет активных посредников</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <p class="text-xs text-gray-500 mt-1">
            Даже если выбран посредник, прямые продажи также доступны
        </p>
    </div>
</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/products/tabs/basic.blade.php ENDPATH**/ ?>