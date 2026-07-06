<div>
    <div class="flex items-center gap-3 mb-4">
        <a href="<?php echo e(route('roles.index')); ?>" class="text-slate-500 hover:text-slate-800">← Назад к ролям</a>
        <h1 class="text-2xl font-semibold">
            <?php echo e($role ? 'Редактирование роли: '.($role->title_ru ?: $role->name) : 'Новая роль'); ?>

        </h1>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <div class="col-span-2 bg-white rounded border p-5 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-slate-600">Техническое имя (англ., латиница) *</label>
                    <input type="text" wire:model="name" 
                        <?php if($role): ?> readonly <?php endif; ?>
                        class="border rounded px-3 py-2 w-full font-mono text-sm <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="manager">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-rose-600 text-xs mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <p class="text-xs text-slate-500 mt-1">Используется в коде: <code>hasRole('manager')</code>. После создания менять не рекомендуется.</p>
                </div>
                <div>
                    <label class="text-sm text-slate-600">Название на русском *</label>
                    <input type="text" wire:model="title_ru" class="border rounded px-3 py-2 w-full <?php $__errorArgs = ['title_ru'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Менеджер страховой компании">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title_ru'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-rose-600 text-xs mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div>
                <label class="text-sm text-slate-600">Описание</label>
                <textarea wire:model="description" rows="2" class="border rounded px-3 py-2 w-full" placeholder="Краткое описание что разрешено этой роли..."></textarea>
            </div>

            <hr>

            <div>
                <div class="font-semibold mb-2">Разрешения</div>
                <div class="space-y-4 max-h-[520px] overflow-auto pr-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $allPerms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $perms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border rounded p-3 bg-slate-50">
                        <div class="font-medium text-sm text-slate-700 mb-2"><?php echo e($group ?: 'Без группы'); ?></div>
                        <div class="grid grid-cols-2 gap-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $perms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-start gap-2 text-sm bg-white border rounded px-2 py-1.5 hover:bg-slate-50 cursor-pointer">
                                <input type="checkbox" wire:model="perms" value="<?php echo e($p->name); ?>" class="mt-0.5">
                                <span>
                                    <span class="font-medium"><?php echo e($p->title_ru ?: $p->name); ?></span><br>
                                    <span class="text-xs text-slate-500 font-mono"><?php echo e($p->name); ?></span>
                                </span>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="flex gap-2 pt-2">
                <button wire:click="save" class="bg-slate-900 text-white px-5 py-2 rounded hover:bg-slate-800">Сохранить</button>
                <a href="<?php echo e(route('roles.index')); ?>" class="px-4 py-2 border rounded hover:bg-slate-50">Отмена</a>
            </div>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded p-4 text-sm text-amber-900 h-fit">
            <b>Как работают роли:</b>
            <ul class="list-disc pl-4 mt-2 space-y-1 text-xs">
                <li><b>name</b> — техническое имя, латиница, используется в коде <code>hasRole()</code></li>
                <li><b>Название RU</b> — то что видят пользователи в интерфейсе</li>
                <li>Разрешения сгруппированы по разделам</li>
                <li>Можно создавать свои роли, например "Бухгалтер" с доступом только к полисам на чтение</li>
            </ul>
        </div>
    </div>
</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/roles/form.blade.php ENDPATH**/ ?>