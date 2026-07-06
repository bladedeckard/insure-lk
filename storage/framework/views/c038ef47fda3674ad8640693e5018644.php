<div>
<h1 class="text-2xl font-semibold mb-4">Посредники</h1>
<div class="flex gap-2 mb-3">
<input wire:model.live="search" placeholder="Поиск" class="border rounded px-3 py-2 bg-white w-64">
<a href="<?php echo e(route('intermediaries.create')); ?>" class="ml-auto bg-slate-900 text-white px-4 py-2 rounded">+ Создать</a>
</div>
<table class="w-full bg-white rounded border text-sm">
<thead class="bg-slate-100"><tr><th class="p-2 text-left">Наименование</th><th class="p-2">ИНН</th><th class="p-2">Договор</th><th class="p-2">Тип</th><th class="p-2">Активен</th><th></th></tr></thead>
<tbody>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr class="border-t"><td class="p-2"><?php echo e($i->name); ?></td><td class="p-2"><?php echo e($i->inn); ?></td><td class="p-2"><?php echo e($i->contract_number); ?></td><td class="p-2"><?php echo e($i->type); ?></td><td class="p-2"><?php echo e($i->is_active ? 'Да':'Нет'); ?></td><td class="p-2"><a class="text-blue-600" href="<?php echo e(route('intermediaries.edit',$i)); ?>">Открыть</a></td></tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</tbody>
</table>
<?php echo e($items->links()); ?>

</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/intermediaries/index.blade.php ENDPATH**/ ?>