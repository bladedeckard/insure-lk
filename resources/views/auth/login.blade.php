<!DOCTYPE html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Вход – Insure LK</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">
<form method="POST" action="{{ route('login') }}" class="bg-white p-8 rounded shadow w-full max-w-sm">
@csrf
<h1 class="text-xl font-semibold mb-4">Вход в ЛК</h1>
@if($errors->any())<div class="text-rose-600 text-sm mb-2">{{ $errors->first() }}</div>@endif
<input name="email" type="email" placeholder="Email" value="{{ old('email','admin@thuricum.ru') }}" class="border rounded px-3 py-2 w-full mb-3">
<input name="password" type="password" placeholder="Пароль" value="password" class="border rounded px-3 py-2 w-full mb-3">
<label class="text-sm flex items-center gap-2 mb-3"><input type="checkbox" name="remember"> Запомнить</label>
<button class="bg-slate-900 text-white px-4 py-2 rounded w-full">Войти</button>
</form>
</body></html>
