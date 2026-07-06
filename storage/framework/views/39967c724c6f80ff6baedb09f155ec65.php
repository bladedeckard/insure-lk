<!DOCTYPE html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Вход – Insure LK</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">
<form method="POST" action="<?php echo e(route('login')); ?>" class="bg-white p-8 rounded shadow w-full max-w-sm">
<?php echo csrf_field(); ?>
<h1 class="text-xl font-semibold mb-4">Вход в ЛК</h1>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?><div class="text-rose-600 text-sm mb-2"><?php echo e($errors->first()); ?></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<input name="email" type="email" placeholder="Email" value="<?php echo e(old('email','admin@thuricum.ru')); ?>" class="border rounded px-3 py-2 w-full mb-3">
<input name="password" type="password" placeholder="Пароль" value="password" class="border rounded px-3 py-2 w-full mb-3">
<label class="text-sm flex items-center gap-2 mb-3"><input type="checkbox" name="remember"> Запомнить</label>
<button class="bg-slate-900 text-white px-4 py-2 rounded w-full">Войти</button>
</form>
</body></html>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/auth/login.blade.php ENDPATH**/ ?>