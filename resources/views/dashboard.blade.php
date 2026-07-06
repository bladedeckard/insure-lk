<x-layouts.app>
<h1 class="text-2xl font-semibold mb-4">Дашборд</h1>
<div class="grid grid-cols-4 gap-4">
<div class="bg-white p-4 rounded border">Полисов всего<br><b class="text-2xl">{{ \App\Models\Policy::count() }}</b></div>
<div class="bg-white p-4 rounded border">Выпущено<br><b class="text-2xl">{{ \App\Models\Policy::where('status','issued')->count() }}</b></div>
<div class="bg-white p-4 rounded border">Пользователей<br><b class="text-2xl">{{ \App\Models\User::count() }}</b></div>
<div class="bg-white p-4 rounded border">Посредников<br><b class="text-2xl">{{ \App\Models\Intermediary::count() }}</b></div>
</div>
</x-layouts.app>
