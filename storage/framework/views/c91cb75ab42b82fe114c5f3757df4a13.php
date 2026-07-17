<div class="space-y-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Настройка документов</h2>

    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
        <h3 class="text-sm font-semibold text-yellow-800 mb-2">📋 Доступные переменные для шаблонов</h3>
        <p class="text-xs text-yellow-700 mb-3">Используйте <code>${variable}</code> в DOCX-шаблонах. Нажмите на переменную — скопируется в буфер.</p>
        
        <?php $vars = $this->getAvailableVariables(); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $vars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $categoryVars): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($categoryVars)): ?>
                <div class="mb-3">
                    <h4 class="text-xs font-semibold text-yellow-800 mb-1"><?php echo e($category); ?></h4>
                    <div class="flex flex-wrap gap-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categoryVars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $varCode => $varLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button type="button"
                                x-data="{ copied: false }"
                                x-on:click="
                                    let text = '$' + '{' + '<?php echo e($varCode); ?>' + '}';
                                    let el = $el;
                                    if (navigator.clipboard && window.isSecureContext) {
                                        navigator.clipboard.writeText(text).then(() => {
                                            el.dataset.copied = '1';
                                            setTimeout(() => { el.dataset.copied = '0'; }, 1500);
                                        });
                                    } else {
                                        let ta = document.createElement('textarea');
                                        ta.value = text;
                                        ta.style.cssText = 'position:fixed;left:-9999px';
                                        document.body.appendChild(ta);
                                        ta.select();
                                        document.execCommand('copy');
                                        document.body.removeChild(ta);
                                        el.dataset.copied = '1';
                                        setTimeout(() => { el.dataset.copied = '0'; }, 1500);
                                    }
                                "
                                :class="dataset.copied === '1' ? 'bg-green-50 border-green-400' : 'bg-white border-yellow-200 hover:bg-yellow-100 hover:border-yellow-400'"
                                class="inline-flex items-center gap-1 px-2 py-1 rounded border text-xs cursor-pointer transition-colors"
                                title="Нажмите чтобы скопировать">
                                <code class="font-semibold" :class="dataset.copied === '1' ? 'text-green-600' : 'text-blue-600'">
                                    <span x-show="dataset.copied !== '1'"><?php echo e('$'); ?><?php echo e('{' . $varCode . '}'); ?></span>
                                    <span x-show="dataset.copied === '1'" x-cloak>✅ Скопировано!</span>
                                </code>
                                <span class="text-gray-400">— <?php echo e($varLabel); ?></span>
                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
        ['key' => 'policy', 'label' => 'Полис', 'prop' => 'policy_template', 'toggle' => 'use_policy'],
        ['key' => 'kid', 'label' => 'КИД (Ключевой Информационный Документ)', 'prop' => 'kid_template', 'toggle' => 'use_kid'],
        ['key' => 'application', 'label' => 'Заявление', 'prop' => 'application_template', 'toggle' => 'use_application'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $docType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex justify-between items-center mb-3">
                <div class="flex items-center gap-3">
                    <h3 class="font-medium text-gray-800"><?php echo e($docType['label']); ?></h3>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="<?php echo e($docType['toggle']); ?>"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-600">Использовать</span>
                    </label>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(${$docType['toggle']}): ?>
                
                <div class="mb-3">
                    <input type="file" wire:model="<?php echo e($docType['prop']); ?>" accept=".docx"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-400 mt-1">Формат: .docx с переменными ${variable}</p>
                </div>

                
                <?php $typeDocs = collect($documents)->filter(fn($d) => $d['type'] === $docType['key'])->values(); ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $typeDocs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $realIndex = collect($documents)->search(fn($d) => $d === $doc); ?>
                    <div class="border border-gray-100 bg-gray-50 rounded p-3 mb-2">
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center gap-2">
                                <span class="text-green-600 text-sm">✅</span>
                                <span class="text-sm font-medium"><?php echo e($doc['name']); ?></span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($doc['apply_conditions'])): ?>
                                    <span class="text-xs px-2 py-0.5 bg-green-100 text-green-700 rounded">По умолчанию</span>
                                <?php else: ?>
                                    <span class="text-xs px-2 py-0.5 bg-orange-100 text-orange-700 rounded">С условиями</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <button wire:click="removeDocument(<?php echo e($realIndex); ?>)"
                                class="text-red-400 hover:text-red-600 text-sm">Удалить</button>
                        </div>

                        
                        <div class="mt-2">
                            <details class="text-sm">
                                <summary class="cursor-pointer text-blue-600 hover:text-blue-800 text-xs font-medium">
                                    ⚙️ Условия применения шаблона
                                    <span class="text-gray-400 font-normal">(если пусто — применяется по умолчанию)</span>
                                </summary>
                                <div class="mt-2 space-y-2">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($doc['apply_conditions'])): ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $doc['apply_conditions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cIdx => $condition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex items-center gap-2 bg-white rounded p-2">
                                                <?php echo $__env->make('livewire.products.partials.field-code-select', [
                                                    'inputName' => 'documents.' . $realIndex . '.apply_conditions.' . $cIdx . '.field_code',
                                                    'allFields' => $fields ?? [],
                                                    'allCoverages' => $coverages ?? [],
                                                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                                <select wire:model.defer="documents.<?php echo e($realIndex); ?>.apply_conditions.<?php echo e($cIdx); ?>.operator"
                                                    class="w-28 border border-gray-300 rounded px-2 py-1 text-sm">
                                                    <option value="=">Равно</option>
                                                    <option value="!=">Не равно</option>
                                                    <option value="in">В списке</option>
                                                    <option value="not_in">Не в списке</option>
                                                    <option value="contains">Содержит</option>
                                                </select>
                                                <input type="text"
                                                    wire:model.defer="documents.<?php echo e($realIndex); ?>.apply_conditions.<?php echo e($cIdx); ?>.value"
                                                    class="flex-1 border border-gray-300 rounded px-2 py-1 text-sm"
                                                    placeholder="Значение (через запятую для «в списке»)">
                                                <button wire:click="removeDocumentCondition(<?php echo e($realIndex); ?>, <?php echo e($cIdx); ?>)"
                                                    class="text-red-400 hover:text-red-600">✕</button>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <button wire:click="addDocumentCondition(<?php echo e($realIndex); ?>)"
                                        class="text-xs text-blue-600 hover:text-blue-800">+ Добавить условие</button>
                                    <p class="text-xs text-gray-400">
                                        Пример: <code>bank</code> = <code>sber</code> → шаблон применяется только для Сбербанка.
                                        Все условия работают как «И» (должны выполняться все).
                                    </p>
                                </div>
                            </details>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/products/tabs/documents.blade.php ENDPATH**/ ?>