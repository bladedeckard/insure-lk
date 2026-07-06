<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo e($title ?? 'Insure LK'); ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body class="bg-slate-50 text-slate-800">
<div class="min-h-screen flex">
<aside class="w-64 bg-white border-r p-4">
<div class="text-xl font-bold mb-6">СК Турикум</div>
<nav class="space-y-1 text-sm">
<a class="block px-3 py-2 rounded hover:bg-slate-100" href="<?php echo e(route('dashboard')); ?>">Дашборд</a>
<a class="block px-3 py-2 rounded hover:bg-slate-100" href="<?php echo e(route('policies.index')); ?>">Полисы</a>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.view')): ?>
<a class="block px-3 py-2 rounded hover:bg-slate-100" href="<?php echo e(route('products.index')); ?>">Страховые продукты</a>
<?php endif; ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('users.view')): ?>
<a class="block px-3 py-2 rounded hover:bg-slate-100" href="<?php echo e(route('users.index')); ?>">Пользователи</a>
<a class="block px-3 py-2 rounded hover:bg-slate-100" href="<?php echo e(route('intermediaries.index')); ?>">Посредники</a>
<?php endif; ?>
<div class="pt-3 text-xs uppercase text-slate-400">Настройки</div>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.view')): ?><a class="block px-3 py-2 rounded hover:bg-slate-100" href="<?php echo e(route('roles.index')); ?>">Роли и права</a><?php endif; ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('numerators.view')): ?><a class="block px-3 py-2 rounded hover:bg-slate-100" href="<?php echo e(route('numerators.index')); ?>">Нумераторы</a><?php endif; ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dictionaries.view')): ?><a class="block px-3 py-2 rounded hover:bg-slate-100" href="<?php echo e(route('dictionaries.index')); ?>">Словари</a><?php endif; ?>
</nav>
<div class="mt-6 text-xs text-slate-500">
<?php echo e(auth()->user()->name); ?><br>
<?php echo e(auth()->user()->roles->pluck('name')->join(', ')); ?>

<form method="POST" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?><button class="text-rose-600 mt-2">Выйти</button></form>
</div>
</aside>
<main class="flex-1 p-8">
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('ok')): ?><div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-2 rounded mb-4"><?php echo e(session('ok')); ?></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('err')): ?><div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-2 rounded mb-4"><?php echo e(session('err')); ?></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('password_plain')): ?><div class="bg-amber-50 border border-amber-200 px-4 py-2 rounded mb-4">Пароль пользователя: <b><?php echo e(session('password_plain')); ?></b></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php echo e($slot); ?>

</main>
</div>
<?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/components/layouts/app.blade.php ENDPATH**/ ?>