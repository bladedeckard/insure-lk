<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Покрытия и риски</h2>
        <button wire:click="addCoverage" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
            + Добавить покрытие
        </button>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($coverages)): ?>
        <div class="text-center py-12 text-gray-400">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="mt-2">Нет покрытий. Добавьте первое покрытие.</p>
        </div>
    <?php else: ?>
        <div class="space-y-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $coverages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $coverage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <h3 class="font-semibold text-gray-800"><?php echo e($coverage['name']); ?></h3>
                                <span class="px-2 py-0.5 text-xs rounded-full
                                    <?php echo e($coverage['type'] === 'range' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                    <?php echo e($coverage['type'] === 'constant' ? 'bg-green-100 text-green-800' : ''); ?>

                                    <?php echo e($coverage['type'] === 'set' ? 'bg-purple-100 text-purple-800' : ''); ?>

                                    <?php echo e($coverage['type'] === 'flag' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                ">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($coverage['type'] === 'range'): ?> Диапазон
                                    <?php elseif($coverage['type'] === 'constant'): ?> Константа
                                    <?php elseif($coverage['type'] === 'set'): ?> Множество
                                    <?php elseif($coverage['type'] === 'flag'): ?> Флаг
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($coverage['required_for_calc']): ?>
                                    <span class="px-2 py-0.5 text-xs bg-red-100 text-red-700 rounded-full">Обязательно</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="mt-1 text-sm text-gray-500">
                                Код: <code><?php echo e($coverage['code'] ?? '—'); ?></code>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($coverage['type'] === 'range'): ?>
                                    · <?php echo e(number_format($coverage['min_value'] ?? 0)); ?> — <?php echo e(number_format($coverage['max_value'] ?? 0)); ?> ₽
                                    · По умолч.: <?php echo e(number_format($coverage['default_value'] ?? 0)); ?> ₽
                                <?php elseif($coverage['type'] === 'set'): ?>
                                    · Значения: <?php echo e(implode(', ', $coverage['set_values'] ?? [])); ?>

                                <?php elseif($coverage['type'] === 'constant'): ?>
                                    · <?php echo e(number_format($coverage['default_value'] ?? 0)); ?> ₽
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($coverage['risks'])): ?>
                                <div class="mt-2 flex flex-wrap gap-1">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $coverage['risks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $risk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-600 rounded"><?php echo e($risk); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="flex items-center gap-1 ml-4">
                            <button wire:click="moveCoverageUp(<?php echo e($index); ?>)" class="p-1 text-gray-400 hover:text-gray-600" title="Вверх">↑</button>
                            <button wire:click="moveCoverageDown(<?php echo e($index); ?>)" class="p-1 text-gray-400 hover:text-gray-600" title="Вниз">↓</button>
                            <button wire:click="editCoverage(<?php echo e($index); ?>)" class="px-3 py-1 text-sm text-blue-600 hover:text-blue-800">Редактировать</button>
                            <button wire:click="removeCoverage(<?php echo e($index); ?>)" 
                                onclick="return confirm('Удалить покрытие?')"
                                class="px-3 py-1 text-sm text-red-600 hover:text-red-800">Удалить</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/products/tabs/coverages.blade.php ENDPATH**/ ?>