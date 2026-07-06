<div>
<h1 class="text-2xl font-semibold mb-4">Пользователи</h1>
<div class="flex gap-2 mb-3">
<input wire:model.live="search" placeholder="Поиск имя/email" class="border rounded px-3 py-2 bg-white w-64">
<select wire:model.live="roleFilter" class="border rounded px-3 py-2 bg-white"><option value="">Все роли</option><option value="admin">admin</option><option value="chief_manager">chief_manager</option><option value="manager">manager</option><option value="agent">agent</option></select>
<a href="<?php echo e(route('users.create')); ?>" class="ml-auto bg-slate-900 text-white px-4 py-2 rounded">+ Создать</a>
</div>
<table class="w-full bg-white rounded border">
<thead class="bg-slate-100 text-left text-sm"><tr><th class="p-2">ID</th><th class="p-2">Имя</th><th class="p-2">Email</th><th class="p-2">Посредник</th><th class="p-2">Роли</th><th class="p-2">Активен</th><th></th></tr></thead>
<tbody>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr class="border-t">
<td class="p-2"><?php echo e($u->id); ?></td>
<td class="p-2"><?php echo e($u->name); ?></td>
<td class="p-2"><?php echo e($u->email); ?></td>
<td class="p-2 text-sm"><?php echo e($u->intermediary?->name); ?></td>
<td class="p-2 text-sm"><?php echo e($u->roles->pluck('name')->join(', ')); ?></td>
<td class="p-2"><?php echo e($u->is_active ? 'Да':'Нет'); ?></td>
<td class="p-2"><a href="<?php echo e(route('users.edit', $u)); ?>" class="text-blue-600">Открыть</a></td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</tbody>
</table>
<div class="mt-3"><?php echo e($users->links()); ?></div>
</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/users/index.blade.php ENDPATH**/ ?>