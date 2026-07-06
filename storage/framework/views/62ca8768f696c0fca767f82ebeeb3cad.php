<div>
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Роли и права доступа</h1>
        <a href="<?php echo e(route('roles.create')); ?>" class="bg-slate-900 text-white px-4 py-2 rounded hover:bg-slate-800">
            + Создать роль
        </a>
    </div>

    <div class="bg-white rounded border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="text-left p-3 w-48">Техническое имя</th>
                    <th class="text-left p-3">Название (RU)</th>
                    <th class="text-left p-3">Описание</th>
                    <th class="text-center p-3 w-24">Прав</th>
                    <th class="text-right p-3 w-40">Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-t hover:bg-slate-50">
                    <td class="p-3 font-mono text-xs text-slate-600"><?php echo e($r->name); ?></td>
                    <td class="p-3">
                        <a href="<?php echo e(route('roles.edit', $r)); ?>" class="font-semibold text-slate-900 hover:text-blue-600">
                            <?php echo e($r->title_ru ?: $r->name); ?>

                        </a>
                    </td>
                    <td class="p-3 text-slate-600 text-xs"><?php echo e(\Illuminate\Support\Str::limit($r->description, 80)); ?></td>
                    <td class="p-3 text-center"><?php echo e($r->permissions_count); ?></td>
                    <td class="p-3 text-right space-x-3">
                        <a href="<?php echo e(route('roles.edit', $r)); ?>" class="text-blue-600 hover:underline">Открыть</a>
                        <button 
                            wire:click="delete(<?php echo e($r->id); ?>)"
                            wire:confirm="Точно удалить роль «<?php echo e($r->title_ru ?: $r->name); ?>»?"
                            class="text-rose-600 hover:underline"
                        >Удалить</button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="p-6 text-center text-slate-500">Ролей пока нет</td></tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-xs text-slate-500">
        Системные роли <code>admin / chief_manager / manager / agent</code> защищены от удаления.
    </div>
</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/roles/index.blade.php ENDPATH**/ ?>