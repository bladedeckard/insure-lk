<div>
<h1 class="text-2xl font-semibold mb-4"><?php echo e($user ? 'Пользователь #'.$user->id : 'Новый пользователь'); ?></h1>
<div class="bg-white rounded border p-5 max-w-2xl space-y-3">
<div><label class="text-sm text-slate-600">Имя</label><input wire:model="name" class="border rounded px-3 py-2 w-full"></div>
<div><label class="text-sm text-slate-600">Email</label><input wire:model="email" type="email" class="border rounded px-3 py-2 w-full"></div>
<div><label class="text-sm text-slate-600">Посредник</label>
<select wire:model="intermediary_id" class="border rounded px-3 py-2 w-full"><option value="">— нет, сотрудник СК —</option><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $intermediaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($i->id); ?>"><?php echo e($i->name); ?> (ИНН <?php echo e($i->inn); ?>)</option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></select>
<p class="text-xs text-slate-500">Если выбран — пользователь является сотрудником этого посредника и видит только его полисы.</p>
</div>
<div><label class="flex items-center gap-2"><input type="checkbox" wire:model="is_active"> Активен</label></div>
<div><label class="text-sm text-slate-600">Роли</label>
<div class="space-y-2 mt-1">
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $allRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<label class="flex items-center gap-2 text-sm border rounded px-3 py-2 hover:bg-slate-50 cursor-pointer">
<input type="checkbox" value="<?php echo e($r->name); ?>" wire:model="roles">
<span><b><?php echo e($r->title_ru ?: $r->name); ?></b> <span class="text-xs text-slate-500">(<?php echo e($r->name); ?>)</span></span>
</label>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
</div>
<div class="flex gap-2 pt-2">
<button wire:click="save" class="bg-slate-900 text-white px-4 py-2 rounded">Сохранить</button>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user): ?>
<button wire:click="resetPassword" wire:confirm="Сбросить пароль? Новый пароль будет показан один раз." class="border px-4 py-2 rounded">Сбросить пароль</button>
<button wire:click="deleteUser" wire:confirm="Точно удалить пользователя <?php echo e($user->name); ?>?" class="text-rose-600 px-4 py-2">Удалить</button>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<a href="<?php echo e(route('users.index')); ?>" class="px-4 py-2">Отмена</a>
</div>
<p class="text-xs text-slate-500">При создании пароль генерируется автоматически. При сбросе — тоже.</p>
</div>
</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/users/form.blade.php ENDPATH**/ ?>