<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<h1 class="text-2xl font-semibold mb-4">Дашборд</h1>
<div class="grid grid-cols-4 gap-4">
<div class="bg-white p-4 rounded border">Полисов всего<br><b class="text-2xl"><?php echo e(\App\Models\Policy::count()); ?></b></div>
<div class="bg-white p-4 rounded border">Выпущено<br><b class="text-2xl"><?php echo e(\App\Models\Policy::where('status','issued')->count()); ?></b></div>
<div class="bg-white p-4 rounded border">Пользователей<br><b class="text-2xl"><?php echo e(\App\Models\User::count()); ?></b></div>
<div class="bg-white p-4 rounded border">Посредников<br><b class="text-2xl"><?php echo e(\App\Models\Intermediary::count()); ?></b></div>
</div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/dashboard.blade.php ENDPATH**/ ?>