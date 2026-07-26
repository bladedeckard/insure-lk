<div class="space-y-6">
    <input type="hidden" wire:model.live="dragAction" id="dragAction">

    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Настройка полей формы полиса</h2>
        <div class="flex gap-2">
            <button wire:click="addFieldGroup" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">
                + Группа
            </button>
            <button wire:click="addField" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm">
                + Поле
            </button>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($sectionOrder)): ?>
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 mb-4">
            <h3 class="text-sm font-semibold text-slate-700 mb-2">Порядок секций в форме полиса</h3>
            <p class="text-xs text-slate-500 mb-3">Перетаскивайте для изменения порядка</p>
            <div class="space-y-1">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $sectionOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sIdx => $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center gap-2 bg-white rounded-xl p-3 border border-slate-200 hover:border-primary/30 cursor-move transition-all"
                         draggable="true"
                         ondragstart="dragStartSection(event, <?php echo e($sIdx); ?>)"
                         ondragover="dragOverSection(event)"
                         ondrop="dropSection(event, <?php echo e($sIdx); ?>)"
                         ondragend="dragEndSection(event)">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                        </svg>
                        <span class="text-sm font-medium text-slate-700">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($section === 'coverages'): ?>
                                <span class="text-indigo-600">📦</span> Покрытия и страховые суммы
                            <?php else: ?>
                                <?php
                                    $matchedGroup = null;
                                    foreach ($fieldGroups as $fg) {
                                        if (($fg['id'] ?? null) != null && (string)($fg['id']) === (string)$section) {
                                            $matchedGroup = $fg;
                                            break;
                                        }
                                    }
                                ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($matchedGroup): ?>
                                    <span class="text-emerald-600">📁</span> <?php echo e($matchedGroup['name']); ?>

                                <?php else: ?>
                                    <span class="text-orange-500 italic">📁 Группа — сохраните для обновления</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($coverages)): ?>
        <div class="border border-indigo-200 bg-indigo-50/50 rounded-2xl p-4">
            <div class="flex justify-between items-center mb-3">
                <div>
                    <h3 class="text-sm font-semibold text-indigo-700">📦 Покрытия и страховые суммы</h3>
                    <p class="text-xs text-indigo-500 mt-1">Эти поля автоматически добавляются в форму полиса. Перетаскивайте покрытия в ряды.</p>
                </div>
                <button wire:click="openRowModal(0, 'coverages')" class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 text-xs font-medium">
                    + Ряд
                </button>
            </div>

            <?php $unrowedCoverages = collect($coverages)->filter(fn($c) => empty($c['row_id'])); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unrowedCoverages->isNotEmpty()): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $unrowedCoverages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $covIndex => $cov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $realIndex = collect($coverages)->search(fn($c) => $c === $cov); ?>
                        <div class="flex items-center gap-2 bg-white rounded-xl p-3 border border-indigo-200 hover:border-indigo-400 cursor-move transition-all"
                             draggable="true"
                             ondragstart="dragStartCoverage(event, <?php echo e($realIndex); ?>)"
                             ondragend="dragEndCoverage(event)">
                            <svg class="w-4 h-4 text-indigo-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                            </svg>
                            <div class="w-2 h-2 rounded-full bg-indigo-400 flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <span class="text-sm font-medium text-slate-800 block truncate"><?php echo e($cov["name"]); ?></span>
                                <span class="text-xs text-slate-400"><?php echo e($cov["type"] === 'range' ? 'Сумма' : ($cov["type"] === 'flag' ? 'Чекбокс' : $cov["type"])); ?></span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $coverageRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rIndex => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $rowCoverages = collect($coverages)->filter(fn($c) => ($c['row_id'] ?? null) === $row['id']); ?>
                <div class="mb-3 bg-white rounded-xl border border-indigo-200 p-3"
                     ondragover="dragOverCoverageRow(event)"
                     ondrop="dropCoverageToRow(event, '<?php echo e($row['id']); ?>')">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-indigo-500 uppercase tracking-wider">Ряд <?php echo e($rIndex + 1); ?></span>
                        <button wire:click="removeRow(0, <?php echo e($rIndex); ?>)"
                            onclick="return confirm('Удалить ряд?')"
                            class="text-red-400 hover:text-red-600 text-xs">Удалить ряд</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-<?php echo e($row['cols'] ?? 2); ?> gap-3 min-h-[40px]">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rowCoverages->isEmpty()): ?>
                            <div class="text-center py-4 text-xs text-indigo-300 border-2 border-dashed border-indigo-200 rounded-lg col-span-full">
                                Перетащите покрытия сюда
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $rowCoverages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $realIndex = collect($coverages)->search(fn($c) => $c === $cov); ?>
                            <div class="flex items-center gap-2 bg-indigo-50 rounded-lg p-2 border border-indigo-200 hover:border-indigo-400 cursor-move transition-all"
                                 draggable="true"
                                 ondragstart="dragStartCoverage(event, <?php echo e($realIndex); ?>)"
                                 ondragend="dragEndCoverage(event)">
                                <svg class="w-3 h-3 text-indigo-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                </svg>
                                <div class="w-2 h-2 rounded-full bg-indigo-400 flex-shrink-0"></div>
                                <div class="flex-1 min-w-0">
                                    <span class="text-sm font-medium text-slate-800 block truncate"><?php echo e($cov["name"]); ?></span>
                                    <span class="text-xs text-slate-400"><?php echo e($cov["type"] === 'range' ? 'Сумма' : ($cov["type"] === 'flag' ? 'Чекбокс' : $cov["type"])); ?></span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($fieldGroups) && empty($fields)): ?>
        <div class="text-center py-12 text-gray-400">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
            </svg>
            <p class="mt-2">Нет полей. Создайте группу и добавьте поля.</p>
        </div>
    <?php else: ?>
        <div class="space-y-6" id="fields-list">
            <?php $ungroupedFields = collect($fields)->filter(fn($f) => empty($f['group_id']))->values(); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ungroupedFields->isNotEmpty()): ?>
                <div class="border border-slate-200 rounded-2xl p-4">
                    <h3 class="text-sm font-semibold text-slate-500 mb-3">Без группы</h3>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $ungroupedFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $realIndex = collect($fields)->search(fn($f) => $f === $field); ?>
                        <div class="flex items-center gap-2 bg-white rounded-xl p-3 border border-slate-200 hover:border-primary/30 cursor-move transition-all mb-2"
                             draggable="true"
                             ondragstart="dragStartField(event, <?php echo e($realIndex); ?>, 0)"
                             ondragover="dragOverField(event)"
                             ondrop="dropField(event, <?php echo e($realIndex); ?>, 0)"
                             ondragend="dragEndField(event)">
                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                            </svg>
                            <?php echo $__env->make('livewire.products.partials.field-row', ['field' => $field, 'index' => $realIndex, 'fieldTypes' => $fieldTypes], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $fieldGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gIndex => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border border-indigo-200 bg-indigo-50/50 rounded-2xl p-4"
                     ondragover="dragOverGroup(event)"
                     ondrop="dropToGroup(event, <?php echo e($group['id'] ?? 0); ?>)">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-3">
                            <div class="flex flex-col gap-0.5">
                                <button wire:click="moveGroupUp(<?php echo e($gIndex); ?>)" class="text-gray-300 hover:text-gray-600 text-xs" title="Вверх">▲</button>
                                <button wire:click="moveGroupDown(<?php echo e($gIndex); ?>)" class="text-gray-300 hover:text-gray-600 text-xs" title="Вниз">▼</button>
                            </div>
                            <input type="text" wire:model.defer="fieldGroups.<?php echo e($gIndex); ?>.name"
                                class="font-semibold text-gray-800 border-none bg-transparent focus:ring-0 text-base"
                                placeholder="Название группы">
                            <span class="text-xs text-gray-400"><?php echo e($group['code'] ?? ''); ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click="openRowModal(<?php echo e($gIndex); ?>)" class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 text-xs font-medium">
                                + Ряд
                            </button>
                            <button wire:click="removeFieldGroup(<?php echo e($gIndex); ?>)"
                                onclick="return confirm('Удалить группу со всеми полями?')"
                                class="text-red-500 hover:text-red-700 text-sm">Удалить группу</button>
                        </div>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($group['description'])): ?>
                        <input type="text" wire:model.defer="fieldGroups.<?php echo e($gIndex); ?>.description"
                            class="text-sm text-gray-500 border-none bg-transparent w-full mb-2"
                            placeholder="Описание группы...">
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php
                        $groupRows = $group['rows'] ?? [];
                        $groupFields = collect($fields)->filter(fn($f) => $f['group_id'] == $group['id']);
                    ?>

                    <?php $unrowedFields = $groupFields->filter(fn($f) => empty($f['row_id']))->values(); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unrowedFields->isNotEmpty()): ?>
                        <div class="mb-3">
                            <div class="text-xs text-slate-400 mb-2">Поля без ряда</div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $unrowedFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $realIndex = collect($fields)->search(fn($f) => $f === $field); ?>
                                <div class="flex items-center gap-2 bg-white rounded-xl p-3 border border-slate-200 hover:border-primary/30 cursor-move transition-all mb-2"
                                     draggable="true"
                                     ondragstart="dragStartField(event, <?php echo e($realIndex); ?>, <?php echo e($group['id'] ?? 0); ?>)"
                                     ondragover="dragOverField(event)"
                                     ondrop="dropField(event, <?php echo e($realIndex); ?>, <?php echo e($group['id'] ?? 0); ?>)"
                                     ondragend="dragEndField(event)">
                                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                    </svg>
                                    <?php echo $__env->make('livewire.products.partials.field-row', ['field' => $field, 'index' => $realIndex, 'fieldTypes' => $fieldTypes], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $groupRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rIndex => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $rowFields = $groupFields->filter(fn($f) => ($f['row_id'] ?? null) == ($row['id'] ?? null))->values(); ?>
                        <div class="mb-3 bg-white rounded-xl border border-slate-200 p-3"
                             ondragover="dragOverField(event)"
                             ondrop="dropToRow(event, <?php echo e($group['id'] ?? 0); ?>, '<?php echo e($row['id'] ?? ''); ?>')">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Ряд <?php echo e($rIndex + 1); ?></span>
                                <button wire:click="removeRow(<?php echo e($gIndex); ?>, <?php echo e($rIndex); ?>)"
                                    class="text-red-400 hover:text-red-600 text-xs">Удалить ряд</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-<?php echo e($row['cols'] ?? 2); ?> gap-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $rowFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $realIndex = collect($fields)->search(fn($f) => $f === $field); ?>
                                    <div class="flex items-center gap-2 bg-slate-50 rounded-lg p-2 border border-slate-200 hover:border-primary/30 cursor-move transition-all"
                                         draggable="true"
                                         ondragstart="dragStartField(event, <?php echo e($realIndex); ?>, <?php echo e($group['id'] ?? 0); ?>)"
                                         ondragover="dragOverField(event)"
                                         ondrop="dropField(event, <?php echo e($realIndex); ?>, <?php echo e($group['id'] ?? 0); ?>)"
                                         ondragend="dragEndField(event)">
                                        <svg class="w-3 h-3 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                        </svg>
                                        <?php echo $__env->make('livewire.products.partials.field-row', ['field' => $field, 'index' => $realIndex, 'fieldTypes' => $fieldTypes, 'compact' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unrowedFields->isEmpty() && empty($groupRows)): ?>
                        <div class="text-center py-6 text-sm text-slate-400 border-2 border-dashed border-slate-200 rounded-xl">
                            Перетащите поля сюда
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showRowModal): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center" style="backdrop-filter: blur(4px);"
             x-data
             @keydown.escape.window="$wire.set('showRowModal', false)">
            <div class="absolute inset-0 bg-black/50" wire:click="$set('showRowModal', false)"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 mx-4">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-semibold text-slate-800">Новый ряд</h3>
                    <button wire:click="$set('showRowModal', false)" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-slate-700 mb-3">Количество столбцов</label>
                    <div class="flex gap-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 4; $i++): ?>
                            <button wire:click="$set('rowColsCount', <?php echo e($i); ?>)"
                                class="flex-1 py-3 px-2 rounded-xl border-2 text-center transition-all
                                <?php echo e($rowColsCount === $i
                                    ? 'border-emerald-500 bg-emerald-50 text-emerald-700 shadow-sm'
                                    : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300'); ?>">
                                <div class="text-2xl font-bold mb-1"><?php echo e($i); ?></div>
                                <div class="text-xs">столб<?php echo e($i === 1 ? '' : ($i < 5 ? 'а' : 'ов')); ?></div>
                            </button>
                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                
                <div class="mb-5 p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="text-xs text-slate-500 mb-2">Превью:</div>
                    <div class="grid grid-cols-<?php echo e($rowColsCount); ?> gap-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= $rowColsCount; $i++): ?>
                            <div class="h-10 bg-white border-2 border-dashed border-slate-300 rounded-lg flex items-center justify-center text-xs text-slate-400">
                                Столбец <?php echo e($i); ?>

                            </div>
                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button wire:click="$set('showRowModal', false)"
                        class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 text-sm font-medium transition-colors">
                        Отмена
                    </button>
                    <button wire:click="confirmAddRow"
                        class="flex-1 px-4 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 text-sm font-medium transition-colors">
                        Создать ряд
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/products/tabs/fields.blade.php ENDPATH**/ ?>