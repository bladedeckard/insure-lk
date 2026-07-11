
<div class="<?php echo e(in_array($field->type, ['textarea', 'address']) ? 'md:col-span-2' : ''); ?>">
    <label class="block text-sm font-medium text-gray-700 mb-1">
        <?php echo e($field->name); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($field->required): ?>
            <span class="text-red-500">*</span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($field->hint): ?>
            <span class="text-xs text-gray-400 font-normal" title="<?php echo e($field->hint); ?>">💬</span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </label>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($field->description): ?>
        <p class="text-xs text-gray-500 mb-1"><?php echo e($field->description); ?></p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($field->type):
        case ('text'): ?>
            <input type="text"
                wire:model.defer="data.<?php echo e($field->code); ?>"
                <?php if($field->mask): ?> data-mask="<?php echo e($field->mask); ?>" <?php endif; ?>
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="<?php echo e($field->hint ?? ''); ?>">
            <?php break; ?>

        <?php case ('number'): ?>
            <input type="number"
                wire:model.defer="data.<?php echo e($field->code); ?>"
                wire:change="calculate"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="<?php echo e($field->hint ?? '0'); ?>">
            <?php break; ?>

        <?php case ('date'): ?>
            <input type="date"
                wire:model.defer="data.<?php echo e($field->code); ?>"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            <?php break; ?>

        <?php case ('select'): ?>
            <select wire:model.defer="data.<?php echo e($field->code); ?>"
                wire:change="calculate"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                <option value="">— выберите —</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $field->options ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($opt['value']); ?>"><?php echo e($opt['label']); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </select>
            <?php break; ?>

        <?php case ('checkbox'): ?>
            <label class="flex items-center gap-2 cursor-pointer mt-1">
                <input type="checkbox"
                    wire:model.defer="data.<?php echo e($field->code); ?>"
                    wire:change="calculate"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="text-sm text-gray-700">Да</span>
            </label>
            <?php break; ?>

        <?php case ('phone'): ?>
            <input type="tel"
                wire:model.defer="data.<?php echo e($field->code); ?>"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="+7 (___) ___-__-__">
            <?php break; ?>

        <?php case ('email'): ?>
            <input type="email"
                wire:model.defer="data.<?php echo e($field->code); ?>"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="email@example.com">
            <?php break; ?>

        <?php case ('passport_series'): ?>
            <input type="text"
                wire:model.defer="data.<?php echo e($field->code); ?>"
                maxlength="5"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="XX XX">
            <p class="text-xs text-gray-400 mt-1">Формат: XX XX (последние 2 цифры ≤ <?php echo e(date('y')); ?>)</p>
            <?php break; ?>

        <?php case ('passport_number'): ?>
            <input type="text"
                wire:model.defer="data.<?php echo e($field->code); ?>"
                maxlength="6"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="XXXXXX">
            <?php break; ?>

        <?php case ('birthdate'): ?>
            <input type="date"
                wire:model.defer="data.<?php echo e($field->code); ?>"
                wire:change="calculate"
                max="<?php echo e(now()->subYears(18)->format('Y-m-d')); ?>"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            <?php break; ?>

        <?php case ('address'): ?>
            <input type="text"
                wire:model.defer="data.<?php echo e($field->code); ?>"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="г. Москва, ул. ..., д. ..., кв. ...">
            <?php break; ?>

        <?php case ('textarea'): ?>
            <textarea wire:model.defer="data.<?php echo e($field->code); ?>"
                rows="3"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="<?php echo e($field->hint ?? ''); ?>"></textarea>
            <?php break; ?>

        <?php case ('file'): ?>
            <input type="file"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-500">
            <?php break; ?>

        <?php case ('linked_field'): ?>
            <div class="space-y-2">
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input type="checkbox"
                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                        data-linked="<?php echo e($field->linked_to); ?>"
                        data-target="<?php echo e($field->code); ?>">
                    <span class="text-purple-700">↔ Совпадает с «<?php echo e($field->linked_to); ?>»</span>
                </label>
                <input type="text"
                    wire:model.defer="data.<?php echo e($field->code); ?>"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="<?php echo e($field->hint ?? ''); ?>">
            </div>
            <?php break; ?>

        <?php default: ?>
            <input type="text"
                wire:model.defer="data.<?php echo e($field->code); ?>"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="<?php echo e($field->hint ?? ''); ?>">
    <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($field->error_message): ?>
        <p class="text-xs text-gray-400 mt-1"><?php echo e($field->error_message); ?></p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/policies/partials/field-render.blade.php ENDPATH**/ ?>