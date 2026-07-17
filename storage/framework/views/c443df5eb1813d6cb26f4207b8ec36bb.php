<div class="min-h-screen bg-gray-50">
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('ok')): ?>
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <?php echo e(session('ok')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('err')): ?>
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 max-w-lg">
            <?php echo e(session('err')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="max-w-6xl mx-auto px-4 py-6">
        
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">
                    Полис <?php echo e($policy?->number ? '№ '.$policy->number : '(новый)'); ?>

                </h1>
                <a href="<?php echo e(route('policies.index')); ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                    ← Назад к списку
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 space-y-6">
                
                
                <div class="bg-white rounded-lg shadow p-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Страховой продукт *</label>
                    <select wire:model.live="product_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">— выберите —</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($pr->id); ?>">
                                <?php echo e($pr->name); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pr->marketing_name): ?> (<?php echo e($pr->marketing_name); ?>) <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product): ?>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($declarations->isNotEmpty() && !$policyId): ?>
                        <div class="bg-purple-50 border border-purple-200 rounded-lg shadow p-6">
                            <h2 class="text-lg font-semibold text-purple-800 mb-3">📜 Декларации</h2>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $declarations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dIdx => $declaration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="mb-4 pb-4 <?php echo e(!$loop->last ? 'border-b border-purple-200' : ''); ?>">
                                    <h3 class="font-medium text-purple-900 mb-2"><?php echo e($declaration->name); ?></h3>
                                    <div class="text-sm text-purple-700 bg-white rounded p-3 mb-2 max-h-48 overflow-y-auto">
                                        <?php echo e($declaration->text); ?>

                                    </div>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model.defer="declarationAgreements.<?php echo e($declaration->id); ?>"
                                            class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                        <span class="text-sm font-medium <?php echo e($declaration->required ? 'text-red-700' : 'text-gray-600'); ?>">
                                            Подтверждаю <?php echo e($declaration->required ? '(обязательно)' : '(необязательно)'); ?>

                                        </span>
                                    </label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <?php
                        $cfgJson = $product->config_json ?? [];
                        $rawOrder = $cfgJson['section_order'] ?? null;

                        // Строим нормализованный sectionOrder
                        $sectionOrder = [];
                        if (is_array($rawOrder) && count($rawOrder) > 0) {
                            foreach ($rawOrder as $s) {
                                if ($s === 'coverages') {
                                    $sectionOrder[] = ['type' => 'coverages'];
                                } else {
                                    // Ищем группу по id (loose comparison)
                                    $found = null;
                                    foreach ($fieldGroups as $fg) {
                                        if ($fg->id == $s) {
                                            $found = $fg;
                                            break;
                                        }
                                    }
                                    if ($found) {
                                        $sectionOrder[] = ['type' => 'group', 'group' => $found];
                                    }
                                }
                            }
                        }

                        // Если sectionOrder пустой — дефолтный: группы + покрытия в конце
                        if (empty($sectionOrder)) {
                            foreach ($fieldGroups as $fg) {
                                $sectionOrder[] = ['type' => 'group', 'group' => $fg];
                            }
                            $sectionOrder[] = ['type' => 'coverages'];
                        }

                        // Проверяем что все группы учтены (на случай если sectionOrder устарел)
                        $renderedGroupIds = [];
                        foreach ($sectionOrder as $sec) {
                            if ($sec['type'] === 'group') {
                                $renderedGroupIds[] = $sec['group']->id;
                            }
                        }
                        foreach ($fieldGroups as $fg) {
                            if (!in_array($fg->id, $renderedGroupIds)) {
                                $sectionOrder[] = ['type' => 'group', 'group' => $fg];
                            }
                        }
                    ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $sectionOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($section['type'] === 'coverages'): ?>
                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($coverages->isNotEmpty()): ?>
                                <div class="bg-white rounded-lg shadow p-6">
                                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Покрытия и страховые суммы</h2>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $coverages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    <?php echo e($cov->name); ?>

                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cov->required_for_calc): ?>
                                                        <span class="text-red-500">*</span>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($cov->risks)): ?>
                                                        <span class="text-xs text-gray-400">(<?php echo e(count($cov->risks)); ?> рисков)</span>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </label>

                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cov->type === 'range'): ?>
                                                    <input type="number"
                                                        wire:model.live="data.<?php echo e($cov->code); ?>"
                                                        min="<?php echo e($cov->min_value ?? 0); ?>"
                                                        max="<?php echo e($cov->max_value); ?>"
                                                        class="w-full border border-gray-300 rounded-lg px-3 py-2"
                                                        placeholder="от <?php echo e(number_format($cov->min_value ?? 0)); ?> до <?php echo e(number_format($cov->max_value ?? 0)); ?>">
                                                    <p class="text-xs text-gray-400 mt-1">
                                                        <?php echo e(number_format($cov->min_value ?? 0)); ?> — <?php echo e(number_format($cov->max_value ?? 0)); ?> ₽
                                                        · По умолч.: <?php echo e(number_format($cov->default_value ?? 0)); ?> ₽
                                                    </p>

                                                <?php elseif($cov->type === 'constant'): ?>
                                                    <div class="px-3 py-2 bg-gray-100 rounded-lg text-sm">
                                                        <?php echo e(number_format($cov->default_value ?? 0)); ?> ₽ (фиксировано)
                                                    </div>

                                                <?php elseif($cov->type === 'set'): ?>
                                                    <select wire:model.live="data.<?php echo e($cov->code); ?>"
                                                        class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $cov->set_values ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($val); ?>"><?php echo e(number_format($val)); ?> ₽</option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </select>

                                                <?php elseif($cov->type === 'flag'): ?>
                                                    <label class="flex items-center gap-2 cursor-pointer">
                                                        <input type="checkbox"
                                                            wire:model.live="data.<?php echo e($cov->code); ?>"
                                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                        <span class="text-sm text-gray-700">Да</span>
                                                    </label>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php elseif($section['type'] === 'group'): ?>
                            
                            <?php $group = $section['group']; ?>
                            <div class="bg-white rounded-lg shadow p-6">
                                <h2 class="text-lg font-semibold text-gray-800 mb-4"><?php echo e($group->name); ?></h2>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($group->description): ?>
                                    <p class="text-sm text-gray-500 mb-4"><?php echo e($group->description); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $fields->where('group_id', $group->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo $__env->make('livewire.policies.partials.field-render', ['field' => $field, 'product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <?php $ungroupedFields = $fields->whereNull('group_id'); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ungroupedFields->isNotEmpty()): ?>
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Дополнительные поля</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $ungroupedFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo $__env->make('livewire.policies.partials.field-render', ['field' => $field, 'product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($agreements->isNotEmpty()): ?>
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Соглашения</h2>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $agreements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aIdx => $agreement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-start gap-3 mb-3 cursor-pointer">
                                    <input type="checkbox"
                                        wire:model.defer="agreementChecks.<?php echo e($aIdx); ?>"
                                        class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">
                                        <?php echo e($agreement->text); ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($agreement->required): ?>
                                            <span class="text-red-500 font-semibold">* обязательно</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Комментарий</label>
                        <textarea wire:model.defer="comment" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="Дополнительная информация..."></textarea>
                    </div>

                    
                    <div class="flex gap-3">
                        <button wire:click="saveDraft"
                            class="px-6 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-medium">
                            💾 Сохранить черновик
                        </button>
                        <button wire:click="issue"
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                            ✅ Выпустить полис
                        </button>
                    </div>

                <?php else: ?>
                    <div class="bg-white rounded-lg shadow p-12 text-center text-gray-400">
                        <p class="text-lg">Выберите продукт для начала оформления</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Расчёт премии</h3>
                    
                    <div class="text-3xl font-bold text-blue-600 mb-4">
                        <?php echo e(number_format($premium, 2, ',', ' ')); ?> ₽
                    </div>

                    <button wire:click="calculate"
                        class="w-full px-4 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-sm mb-4">
                        🔄 Пересчитать
                    </button>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($calculation['breakdown'])): ?>
                        <div class="border-t pt-4">
                            <h4 class="text-xs font-semibold text-gray-500 mb-2">ДЕТАЛИЗАЦИЯ</h4>
                            <pre class="text-xs text-gray-600 bg-gray-50 rounded p-3 overflow-x-auto"><?php echo e(json_encode($calculation['breakdown'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)); ?></pre>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($calculation['errors'])): ?>
                        <div class="mt-4 bg-red-50 border border-red-200 rounded p-3">
                            <h4 class="text-xs font-semibold text-red-700 mb-2">ОШИБКИ</h4>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $calculation['errors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f => $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p class="text-sm text-red-600"><?php echo e($f); ?>: <?php echo e($m); ?></p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($calculation['needs_approval'])): ?>
                        <div class="mt-4 bg-orange-50 border border-orange-200 rounded p-3">
                            <p class="text-sm text-orange-700 font-medium">⚠️ Требуется согласование</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/policies/form.blade.php ENDPATH**/ ?>