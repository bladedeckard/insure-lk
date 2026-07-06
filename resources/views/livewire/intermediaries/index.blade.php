<div>
<h1 class="text-2xl font-semibold mb-4">Посредники</h1>
<div class="flex gap-2 mb-3">
<input wire:model.live="search" placeholder="Поиск" class="border rounded px-3 py-2 bg-white w-64">
<a href="{{ route('intermediaries.create') }}" class="ml-auto bg-slate-900 text-white px-4 py-2 rounded">+ Создать</a>
</div>
<table class="w-full bg-white rounded border text-sm">
<thead class="bg-slate-100"><tr><th class="p-2 text-left">Наименование</th><th class="p-2">ИНН</th><th class="p-2">Договор</th><th class="p-2">Тип</th><th class="p-2">Активен</th><th></th></tr></thead>
<tbody>
@foreach($items as $i)
<tr class="border-t"><td class="p-2">{{ $i->name }}</td><td class="p-2">{{ $i->inn }}</td><td class="p-2">{{ $i->contract_number }}</td><td class="p-2">{{ $i->type }}</td><td class="p-2">{{ $i->is_active ? 'Да':'Нет' }}</td><td class="p-2"><a class="text-blue-600" href="{{ route('intermediaries.edit',$i) }}">Открыть</a></td></tr>
@endforeach
</tbody>
</table>
{{ $items->links() }}
</div>
