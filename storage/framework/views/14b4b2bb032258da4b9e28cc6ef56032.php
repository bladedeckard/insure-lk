<div>
<h1 class="text-2xl font-semibold mb-4">Полисы</h1>
<div class="flex gap-2 mb-3">
<input wire:model.live="search" placeholder="Номер полиса" class="border rounded px-3 py-2">
<a href="<?php echo e(route('policies.create')); ?>" class="ml-auto bg-slate-900 text-white px-4 py-2 rounded">+ Новый полис</a>
</div>
<table class="w-full bg-white rounded border text-sm">
<thead class="bg-slate-100"><tr><th class="p-2 text-left">Номер</th><th class="p-2 text-left">Продукт</th><th class="p-2">Премия</th><th class="p-2">Статус</th><th class="p-2">Дата</th><th></th></tr></thead>
<tbody>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr class="border-t"><td class="p-2"><?php echo e($p->number ?? 'черновик'); ?></td><td class="p-2"><?php echo e($p->product->name); ?></td><td class="p-2"><?php echo e(number_format($p->premium,2,',',' ')); ?></td><td class="p-2"><?php echo e($p->status); ?></td><td class="p-2"><?php echo e($p->created_at->format('d.m.Y')); ?></td><td class="p-2"><a class="text-blue-600" href="<?php echo e(route('policies.edit',$p)); ?>">Открыть</a></td></tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</tbody>
</table>
<?php echo e($items->links()); ?>

</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/policies/index.blade.php ENDPATH**/ ?>