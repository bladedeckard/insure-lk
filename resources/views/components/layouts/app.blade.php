<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $title ?? 'Insure LK' }}</title>
<script src="https://cdn.tailwindcss.com"></script>
@livewireStyles
</head>
<body class="bg-slate-50 text-slate-800">
<div class="min-h-screen flex">
<aside class="w-64 bg-white border-r p-4">
<div class="text-xl font-bold mb-6">СК Турикум</div>
<nav class="space-y-1 text-sm">
<a class="block px-3 py-2 rounded hover:bg-slate-100" href="{{ route('dashboard') }}">Дашборд</a>
<a class="block px-3 py-2 rounded hover:bg-slate-100" href="{{ route('policies.index') }}">Полисы</a>
@can('products.view')
<a class="block px-3 py-2 rounded hover:bg-slate-100" href="{{ route('products.index') }}">Страховые продукты</a>
@endcan
@can('users.view')
<a class="block px-3 py-2 rounded hover:bg-slate-100" href="{{ route('users.index') }}">Пользователи</a>
<a class="block px-3 py-2 rounded hover:bg-slate-100" href="{{ route('intermediaries.index') }}">Посредники</a>
@endcan
<div class="pt-3 text-xs uppercase text-slate-400">Настройки</div>
@can('roles.view')<a class="block px-3 py-2 rounded hover:bg-slate-100" href="{{ route('roles.index') }}">Роли и права</a>@endcan
@can('numerators.view')<a class="block px-3 py-2 rounded hover:bg-slate-100" href="{{ route('numerators.index') }}">Нумераторы</a>@endcan
@can('dictionaries.view')<a class="block px-3 py-2 rounded hover:bg-slate-100" href="{{ route('dictionaries.index') }}">Словари</a>@endcan
</nav>
<div class="mt-6 text-xs text-slate-500">
{{ auth()->user()->name }}<br>
{{ auth()->user()->roles->pluck('name')->join(', ') }}
<form method="POST" action="{{ route('logout') }}">@csrf<button class="text-rose-600 mt-2">Выйти</button></form>
</div>
</aside>
<main class="flex-1 p-8">
@if(session('ok'))<div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-2 rounded mb-4">{{ session('ok') }}</div>@endif
@if(session('err'))<div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-2 rounded mb-4">{{ session('err') }}</div>@endif
@if(session('password_plain'))<div class="bg-amber-50 border border-amber-200 px-4 py-2 rounded mb-4">Пароль пользователя: <b>{{ session('password_plain') }}</b></div>@endif
{{ $slot }}
</main>
</div>
@livewireScripts
</body>
</html>
