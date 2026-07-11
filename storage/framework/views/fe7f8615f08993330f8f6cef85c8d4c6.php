
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">
                    <?php echo e($editingFieldIndex >= 0 ? 'Редактировать поле' : 'Новое поле'); ?>

                </h3>
                <button wire:click="$set('showFieldModal', false)" class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Название поля *</label>
                        <input type="text" wire:model.defer="fld_name"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="Фамилия страхователя">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Код *</label>
                        <input type="text" wire:model.defer="fld_code"
                            class="w-full font-mono border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="policyholder_last_name">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Тип поля</label>
                        <select wire:model="fld_type"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $fieldTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type); ?>"><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Группа</label>
                        <select wire:model.defer="fld_group_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="">— Без группы —</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $fieldGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($group['id']); ?>"><?php echo e($group['name']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fld_type === 'select'): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Варианты (JSON или "ключ=значение" по строке)
                        </label>
                        <textarea wire:model.defer="fld_options" rows="4"
                            class="w-full font-mono text-sm border border-gray-300 rounded-lg px-3 py-2"
                            placeholder='[{"value":"kv","label":"Квартира"},{"value":"dom","label":"Дом"}]
или:
kv=Квартира
dom=Дом'></textarea>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fld_type === 'linked_field'): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Связано с полем (код)
                        </label>
                        <input type="text" wire:model.defer="fld_linked_to"
                            class="w-full font-mono border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="policyholder_address">
                        <p class="text-xs text-gray-400 mt-1">
                            Будет показана галочка «Совпадает с...», при активации значение копируется
                        </p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Описание</label>
                    <input type="text" wire:model.defer="fld_description"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2"
                        placeholder="Введите фамилию кириллицей">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Подсказка (tooltip)</label>
                    <input type="text" wire:model.defer="fld_hint"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2"
                        placeholder="Например: как в паспорте">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Маска ввода</label>
                        <input type="text" wire:model.defer="fld_mask"
                            class="w-full font-mono border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="99 99 или +7 (999) 999-99-99">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Regex-валидация</label>
                        <input type="text" wire:model.defer="fld_regex"
                            class="w-full font-mono border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="/^[А-Яа-яЁё\s]+$/">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Сообщение при ошибке валидации</label>
                    <input type="text" wire:model.defer="fld_error_message"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2"
                        placeholder="Введите корректное значение">
                </div>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model.defer="fld_required"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700">Обязательно для заполнения</span>
                </label>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                <button wire:click="$set('showFieldModal', false)"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Отмена</button>
                <button wire:click="saveField"
                    class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Сохранить</button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/products/modals/field-modal.blade.php ENDPATH**/ ?>