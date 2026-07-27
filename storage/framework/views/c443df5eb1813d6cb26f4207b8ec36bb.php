
<div>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($restrictionErrors)): ?>
        <div class="fixed top-20 right-6 z-40 w-96" id="restriction-alert">
            <div class="bg-red-50 border border-red-200 rounded-xl shadow-lg p-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-semibold text-red-800 mb-1">Ошибки при выпуске полиса</h4>
                    <ul class="space-y-0.5">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $restrictionErrors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="text-xs text-red-700"><?php echo e($err); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
                <button onclick="document.getElementById('restriction-alert').remove()" class="text-red-400 hover:text-red-600 ml-auto">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="<?php echo e(route('policies.index')); ?>" class="hover:text-primary-600">Полисы</a>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                <span class="text-gray-900"><?php echo e($policy?->number ?? 'Новый'); ?></span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">
                Полис <?php echo e($policy?->number ? '№ '.$policy->number : '(новый)'); ?>

            </h1>
        </div>
        <a href="<?php echo e(route('policies.index')); ?>"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Назад к списку
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">

            
            <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-6">
                <label class="block text-sm font-bold text-slate-800 mb-3 uppercase tracking-wider text-xs">Страховой продукт <span class="text-red-500">*</span></label>
                <select wire:model.live="product_id"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
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
                    <div class="bg-purple-50 border border-purple-200 rounded-2xl shadow-soft p-6">
                        <h2 class="text-lg font-semibold text-purple-800 mb-3">Декларации</h2>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $declarations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dIdx => $declaration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mb-4 pb-4 <?php echo e(!$loop->last ? 'border-b border-purple-200' : ''); ?>">
                                <h3 class="font-medium text-purple-900 mb-2"><?php echo e($declaration->name); ?></h3>
                                <div class="text-sm text-purple-700 bg-white rounded-xl p-3 mb-2 max-h-48 overflow-y-auto">
                                    <?php echo e($declaration->text); ?>

                                </div>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model.defer="declarationAgreements.<?php echo e($declaration->id); ?>" class="el-checkbox">
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
                    $sectionOrder = [];
                    if (is_array($rawOrder) && count($rawOrder) > 0) {
                        foreach ($rawOrder as $s) {
                            if ($s === 'coverages') {
                                $sectionOrder[] = ['type' => 'coverages'];
                            } else {
                                $found = null;
                                foreach ($fieldGroups as $fg) {
                                    if ($fg->id == $s) { $found = $fg; break; }
                                }
                                if ($found) $sectionOrder[] = ['type' => 'group', 'group' => $found];
                            }
                        }
                    }
                    if (empty($sectionOrder)) {
                        foreach ($fieldGroups as $fg) $sectionOrder[] = ['type' => 'group', 'group' => $fg];
                        $sectionOrder[] = ['type' => 'coverages'];
                    }
                    $renderedGroupIds = [];
                    foreach ($sectionOrder as $sec) { if ($sec['type'] === 'group') $renderedGroupIds[] = $sec['group']->id; }
                    foreach ($fieldGroups as $fg) {
                        if (!in_array($fg->id, $renderedGroupIds)) $sectionOrder[] = ['type' => 'group', 'group' => $fg];
                    }
                ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $sectionOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($section['type'] === 'coverages'): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($coverages->isNotEmpty()): ?>
                            <?php
                                $coverageRowsList = $product->config_json['coverageRows'] ?? [];
                                $unrowedCoverages = $coverages->filter(fn($c) => empty($c->row_id));
                            ?>
                            <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-6">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    </div>
                                    <h2 class="text-xl font-bold text-slate-900">Покрытия и суммы</h2>
                                </div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unrowedCoverages->isNotEmpty()): ?>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $unrowedCoverages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cov->type === 'flag'): ?>
                                                <div class="space-y-2">
                                                    <label class="block text-sm font-semibold text-slate-700">
                                                        <?php echo e($cov->name); ?>

                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cov->required_for_calc): ?><span class="text-red-500">*</span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($cov->risks)): ?>
                                                            <span class="relative group inline-block ml-1">
                                                                <svg class="w-4 h-4 text-slate-400 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-slate-800 text-white text-xs rounded-lg whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                                                                    Риски: <?php echo e(implode(', ', $cov->risks)); ?>

                                                                </span>
                                                            </span>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </label>
                                                    <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:border-indigo-300 hover:bg-indigo-50/30 transition-all">
                                                        <input type="checkbox" wire:model.live="data.<?php echo e($cov->code); ?>" class="el-checkbox">
                                                        <span class="text-sm text-slate-600"><?php echo e($cov->description ?: 'Включить в расчёт'); ?></span>
                                                    </label>
                                                </div>
                                            <?php else: ?>
                                                <div class="space-y-2">
                                                    <label class="block text-sm font-semibold text-slate-700">
                                                        <?php echo e($cov->name); ?>

                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cov->required_for_calc): ?><span class="text-red-500">*</span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </label>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cov->type === 'range'): ?>
                                                        <div class="relative">
                                                            <input type="number"
                                                                wire:model.live="data.<?php echo e($cov->code); ?>"
                                                                min="<?php echo e($cov->min_value ?? 0); ?>"
                                                                max="<?php echo e($cov->max_value); ?>"
                                                                placeholder="0"
                                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold pointer-events-none">₽</div>
                                                        </div>
                                                        <div class="flex justify-between mt-1 px-1">
                                                            <span class="text-xs text-slate-400"><?php echo e(number_format($cov->min_value ?? 0)); ?> ₽</span>
                                                            <span class="text-xs text-slate-400"><?php echo e(number_format($cov->max_value ?? 0)); ?> ₽</span>
                                                        </div>
                                                    <?php elseif($cov->type === 'set'): ?>
                                                        <select wire:model.live="data.<?php echo e($cov->code); ?>"
                                                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $cov->set_values ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($val); ?>"><?php echo e(number_format($val)); ?> ₽</option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </select>
                                                    <?php elseif($cov->type === 'constant'): ?>
                                                        <div class="px-4 py-3 bg-slate-100 rounded-xl text-sm font-medium text-slate-600">
                                                            <?php echo e(number_format($cov->default_value ?? 0)); ?> ₽ (фиксировано)
                                                        </div>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $coverageRowsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $rowCoverages = $coverages->filter(fn($c) => ($c->row_id ?? null) === $row['id']); ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rowCoverages->isNotEmpty()): ?>
                                        <div class="grid grid-cols-1 md:grid-cols-<?php echo e($row['cols'] ?? 2); ?> gap-6 <?php echo e(!$loop->last ? 'mb-4' : ''); ?>">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $rowCoverages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cov->type === 'flag'): ?>
                                                    <div class="space-y-2">
                                                        <label class="block text-sm font-semibold text-slate-700">
                                                            <?php echo e($cov->name); ?>

                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cov->required_for_calc): ?><span class="text-red-500">*</span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($cov->risks)): ?>
                                                                <span class="relative group inline-block ml-1">
                                                                    <svg class="w-4 h-4 text-slate-400 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                                    <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-slate-800 text-white text-xs rounded-lg whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                                                                        Риски: <?php echo e(implode(', ', $cov->risks)); ?>

                                                                    </span>
                                                                </span>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </label>
                                                        <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:border-indigo-300 hover:bg-indigo-50/30 transition-all">
                                                            <input type="checkbox" wire:model.live="data.<?php echo e($cov->code); ?>" class="el-checkbox">
                                                            <span class="text-sm text-slate-600"><?php echo e($cov->description ?: 'Включить в расчёт'); ?></span>
                                                        </label>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="space-y-2">
                                                        <label class="block text-sm font-semibold text-slate-700">
                                                            <?php echo e($cov->name); ?>

                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cov->required_for_calc): ?><span class="text-red-500">*</span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </label>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cov->type === 'range'): ?>
                                                            <div class="relative">
                                                                <input type="number"
                                                                    wire:model.live="data.<?php echo e($cov->code); ?>"
                                                                    min="<?php echo e($cov->min_value ?? 0); ?>"
                                                                    max="<?php echo e($cov->max_value); ?>"
                                                                    placeholder="0"
                                                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold pointer-events-none">₽</div>
                                                            </div>
                                                            <div class="flex justify-between mt-1 px-1">
                                                                <span class="text-xs text-slate-400"><?php echo e(number_format($cov->min_value ?? 0)); ?> ₽</span>
                                                                <span class="text-xs text-slate-400"><?php echo e(number_format($cov->max_value ?? 0)); ?> ₽</span>
                                                            </div>
                                                        <?php elseif($cov->type === 'set'): ?>
                                                            <select wire:model.live="data.<?php echo e($cov->code); ?>"
                                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $cov->set_values ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($val); ?>"><?php echo e(number_format($val)); ?> ₽</option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            </select>
                                                        <?php elseif($cov->type === 'constant'): ?>
                                                            <div class="px-4 py-3 bg-slate-100 rounded-xl text-sm font-medium text-slate-600">
                                                                <?php echo e(number_format($cov->default_value ?? 0)); ?> ₽ (фиксировано)
                                                            </div>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php elseif($section['type'] === 'group'): ?>
                        <?php $group = $section['group']; ?>
                        <?php
                            $groupFields = $fields->where('group_id', $group->id);
                            $groupRows = $rows[(string)$group->id] ?? [];
                        ?>
                        <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-6">
                            <h2 class="text-xl font-bold text-slate-900 mb-4"><?php echo e($group->name); ?></h2>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($group->description): ?>
                                <p class="text-sm text-slate-500 mb-4"><?php echo e($group->description); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php $unrowedFields = $groupFields->filter(fn($f) => empty($f->row_id)); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unrowedFields->isNotEmpty()): ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $unrowedFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo $__env->make('livewire.policies.partials.field-render', ['field' => $field, 'product' => $product, 'data' => $data, 'visibilityMap' => $visibilityMap ?? []], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $groupRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $rowFields = $groupFields->filter(fn($f) => ($f->row_id ?? null) === $row['id']); ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rowFields->isNotEmpty()): ?>
                                    <div class="grid grid-cols-1 md:grid-cols-<?php echo e($row['cols'] ?? 2); ?> gap-6 <?php echo e(!$loop->last ? 'mb-4' : ''); ?>">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $rowFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo $__env->make('livewire.policies.partials.field-render', ['field' => $field, 'product' => $product, 'data' => $data, 'visibilityMap' => $visibilityMap ?? []], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <?php $ungroupedFields = $fields->whereNull('group_id'); ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ungroupedFields->isNotEmpty()): ?>
                    <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-6">
                        <h2 class="text-xl font-bold text-slate-900 mb-4">Дополнительные поля</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $ungroupedFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo $__env->make('livewire.policies.partials.field-render', ['field' => $field, 'product' => $product, 'data' => $data, 'visibilityMap' => $visibilityMap ?? []], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($agreements->isNotEmpty()): ?>
                    <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-6">
                        <h2 class="text-xl font-bold text-slate-900 mb-4">Соглашения</h2>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $agreements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aIdx => $agreement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-start gap-3 mb-3 cursor-pointer p-3 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                                <input type="checkbox"
                                    wire:model.defer="agreementChecks.<?php echo e($aIdx); ?>"
                                    class="el-checkbox mt-1">
                                <span class="text-sm text-slate-700">
                                    <?php echo e($agreement->text); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($agreement->required): ?>
                                        <span class="text-red-500 font-semibold">* обязательно</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </span>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Комментарий</label>
                    <textarea wire:model.defer="comment" rows="3"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none"
                        placeholder="Дополнительная информация..."></textarea>
                </div>

            <?php else: ?>
                <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-12 text-center text-slate-400">
                    <p class="text-lg">Выберите продукт для начала оформления</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-6 sticky top-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Расчёт премии</h3>
                </div>

                <div class="text-4xl font-bold text-primary-600 mb-4">
                    <?php echo e(number_format($premium, 2, ',', ' ')); ?> <span class="text-lg font-medium text-slate-400">₽</span>
                </div>

                <button wire:click="calculate"
                    class="w-full px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 text-sm font-medium transition-colors mb-4">
                    Пересчитать
                </button>

                
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-wider">Посредник</label>
                    <select wire:model.live="intermediary_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 transition-all">
                        <option value="">Без посредника</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $intermediaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($inter->id); ?>"><?php echo e($inter->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($intermediary_id)): ?>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-wider">КВ посредника (%)</label>
                        <input type="number" wire:model.live="kv_percent" min="0" max="70" step="5" placeholder="0"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 transition-all">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kv_percent > 0): ?>
                            <p class="text-xs text-slate-400 mt-1">Коэфф. посредника: <?php echo e(number_format(1 - ($kv_percent / 100), 2)); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-wider">Промокод</label>
                    <input type="text" wire:model.live="promocode" placeholder="Введите промокод"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 transition-all"
                        <?php echo e($markup_percent > 0 ? 'disabled' : ''); ?>>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($promocode) && isset($calculation['breakdown']['promo_coeff']) && $calculation['breakdown']['promo_coeff'] < 1): ?>
                        <p class="text-xs text-emerald-600 mt-1">Скидка: <?php echo e(round((1 - $calculation['breakdown']['promo_coeff']) * 100)); ?>%</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-wider">Надбавка (%)</label>
                    <input type="number" wire:model.live="markup_percent" min="0" max="100" step="1" placeholder="0"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 transition-all"
                        <?php echo e(!empty($promocode) ? 'disabled' : ''); ?>>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($markup_percent > 0): ?>
                        <p class="text-xs text-amber-600 mt-1">+<?php echo e($markup_percent); ?>% к премии</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="space-y-3 mb-4">
                    <button wire:click="issue"
                        class="w-full px-4 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 font-semibold text-sm transition-colors shadow-sm">
                        Выпустить полис
                    </button>
                    <button wire:click="saveDraft"
                        class="w-full px-4 py-2.5 bg-amber-500 text-white rounded-xl hover:bg-amber-600 font-medium text-sm transition-colors">
                        Сохранить черновик
                    </button>
                </div>

                <div class="border-t pt-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($calculation['breakdown'])): ?>
                        <div class="mb-3 text-xs space-y-1">
                            <div class="flex justify-between">
                                <span class="text-slate-500">ОСЗ:</span>
                                <span class="text-slate-700"><?php echo e(number_format($calculation['breakdown']['osg'] ?? 0, 0, '', ' ')); ?> ₽</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Коэфф. ОСЗ:</span>
                                <span class="text-slate-700"><?php echo e($calculation['breakdown']['osg_coeff'] ?? '—'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Страховая сумма:</span>
                                <span class="font-medium text-emerald-600"><?php echo e(number_format($calculation['breakdown']['insurance_sum'] ?? 0, 0, '', ' ')); ?> ₽</span>
                            </div>
                        </div>

                        <button wire:click="$set('showCalcDetail', true)" class="w-full text-left group">
                            <h4 class="text-xs font-semibold text-slate-500 mb-3 uppercase tracking-wider group-hover:text-primary-600 transition-colors flex items-center gap-1">
                                Детализация расчёта
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </h4>
                        </button>
                        <div class="space-y-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($calculation['breakdown']['life'])): ?>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-slate-600">Несчастный случай (жизнь)</span>
                                    <span class="font-medium text-slate-800"><?php echo e(number_format($calculation['breakdown']['life'], 2, ',', ' ')); ?> ₽</span>
                                </div>
                                <div class="text-xs -mt-1 ml-2 flex items-center gap-2">
                                    <span class="text-slate-400">тариф: <?php echo e($calculation['breakdown']['life_tariff'] ?? 0); ?>%</span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($calculation['breakdown']['life_eff_tariff']) && $calculation['breakdown']['life_eff_tariff'] != ($calculation['breakdown']['life_tariff'] ?? 0)): ?>
                                        <span class="text-amber-500">→ тариф итого: <?php echo e($calculation['breakdown']['life_eff_tariff']); ?>%</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($calculation['breakdown']['property'])): ?>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-slate-600">Имущество (конструктив)</span>
                                    <span class="font-medium text-slate-800"><?php echo e(number_format($calculation['breakdown']['property'], 2, ',', ' ')); ?> ₽</span>
                                </div>
                                <div class="text-xs -mt-1 ml-2 flex items-center gap-2">
                                    <span class="text-slate-400">тариф: <?php echo e($calculation['breakdown']['property_tariff'] ?? 0); ?>%</span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($calculation['breakdown']['property_eff_tariff']) && $calculation['breakdown']['property_eff_tariff'] != ($calculation['breakdown']['property_tariff'] ?? 0)): ?>
                                        <span class="text-amber-500">→ тариф итого: <?php echo e($calculation['breakdown']['property_eff_tariff']); ?>%</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($calculation['breakdown']['title'])): ?>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-slate-600">Титул</span>
                                    <span class="font-medium text-slate-800"><?php echo e(number_format($calculation['breakdown']['title'], 2, ',', ' ')); ?> ₽</span>
                                </div>
                                <div class="text-xs -mt-1 ml-2 flex items-center gap-2">
                                    <span class="text-slate-400">тариф: <?php echo e($calculation['breakdown']['title_tariff'] ?? 0); ?>%</span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($calculation['breakdown']['title_eff_tariff']) && $calculation['breakdown']['title_eff_tariff'] != ($calculation['breakdown']['title_tariff'] ?? 0)): ?>
                                        <span class="text-amber-500">→ тариф итого: <?php echo e($calculation['breakdown']['title_eff_tariff']); ?>%</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="border-t border-slate-200 pt-2 mt-2">
                                <div class="flex justify-between items-center text-sm font-semibold">
                                    <span class="text-slate-700">Итого премия</span>
                                    <span class="text-primary-600"><?php echo e(number_format($premium, 2, ',', ' ')); ?> ₽</span>
                                </div>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($calculation['breakdown']['promo_coeff']) && $calculation['breakdown']['promo_coeff'] < 1): ?>
                                <div class="text-xs text-emerald-600">
                                    Промокод: скидка <?php echo e(round((1 - $calculation['breakdown']['promo_coeff']) * 100)); ?>%
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($calculation['breakdown']['markup_coeff']) && $calculation['breakdown']['markup_coeff'] > 1): ?>
                                <div class="text-xs text-amber-600">
                                    Надбавка: +<?php echo e(round(($calculation['breakdown']['markup_coeff'] - 1) * 100)); ?>%
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($calculation['breakdown']['intermediary_coeff']) && $calculation['breakdown']['intermediary_coeff'] < 1): ?>
                                <div class="text-xs text-slate-400">
                                    Посредник: <?php echo e(round((1 - $calculation['breakdown']['intermediary_coeff']) * 100)); ?>% КВ
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($calculation['errors'])): ?>
                        <div class="mt-4 bg-red-50 border border-red-200 rounded-xl p-3">
                            <h4 class="text-xs font-semibold text-red-700 mb-2">Ошибки</h4>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $calculation['errors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f => $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p class="text-sm text-red-600"><?php echo e($f); ?>: <?php echo e($m); ?></p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <?php echo $__env->make('livewire.policies.partials.calc-detail-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/policies/form.blade.php ENDPATH**/ ?>