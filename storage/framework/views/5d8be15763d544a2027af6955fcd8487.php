<div>
<h1 class="text-2xl font-semibold mb-4">Страховые продукты</h1>
<a href="<?php echo e(route('products.create')); ?>" class="bg-slate-900 text-white px-4 py-2 rounded">+ Создать продукт</a>
<table class="w-full bg-white rounded border mt-3 text-sm">
<thead class="bg-slate-100"><tr><th class="p-2 text-left">Код</th><th class="p-2 text-left">Название</th><th class="p-2">Калькулятор</th><th></th></tr></thead>
<tbody>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr class="border-t"><td class="p-2"><?php echo e($p->code); ?></td><td class="p-2"><?php echo e($p->name); ?></td><td class="p-2"><?php echo e(class_basename($p->calculator_class)); ?></td><td class="p-2"><a class="text-blue-600" href="<?php echo e(route('products.edit',$p)); ?>">Открыть</a></td></tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</tbody>
</table>
<?php echo e($items->links()); ?>

</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/products/index.blade.php ENDPATH**/ ?>