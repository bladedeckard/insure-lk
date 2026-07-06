<div>
<h1 class="text-2xl font-semibold mb-4">Полисы</h1>
<div class="flex gap-2 mb-3">
<input wire:model.live="search" placeholder="Номер полиса" class="border rounded px-3 py-2">
<a href="{{ route('policies.create') }}" class="ml-auto bg-slate-900 text-white px-4 py-2 rounded">+ Новый полис</a>
</div>
<table class="w-full bg-white rounded border text-sm">
<thead class="bg-slate-100"><tr><th class="p-2 text-left">Номер</th><th class="p-2 text-left">Продукт</th><th class="p-2">Премия</th><th class="p-2">Статус</th><th class="p-2">Дата</th><th></th></tr></thead>
<tbody>
@foreach($items as $p)
<tr class="border-t"><td class="p-2">{{ $p->number ?? 'черновик' }}</td><td class="p-2">{{ $p->product->name }}</td><td class="p-2">{{ number_format($p->premium,2,',',' ') }}</td><td class="p-2">{{ $p->status }}</td><td class="p-2">{{ $p->created_at->format('d.m.Y') }}</td><td class="p-2"><a class="text-blue-600" href="{{ route('policies.edit',$p) }}">Открыть</a></td></tr>
@endforeach
</tbody>
</table>
{{ $items->links() }}
</div>
