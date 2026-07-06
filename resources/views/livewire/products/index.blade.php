<div>
<h1 class="text-2xl font-semibold mb-4">Страховые продукты</h1>
<a href="{{ route('products.create') }}" class="bg-slate-900 text-white px-4 py-2 rounded">+ Создать продукт</a>
<table class="w-full bg-white rounded border mt-3 text-sm">
<thead class="bg-slate-100"><tr><th class="p-2 text-left">Код</th><th class="p-2 text-left">Название</th><th class="p-2">Калькулятор</th><th></th></tr></thead>
<tbody>
@foreach($items as $p)
<tr class="border-t"><td class="p-2">{{ $p->code }}</td><td class="p-2">{{ $p->name }}</td><td class="p-2">{{ class_basename($p->calculator_class) }}</td><td class="p-2"><a class="text-blue-600" href="{{ route('products.edit',$p) }}">Открыть</a></td></tr>
@endforeach
</tbody>
</table>
{{ $items->links() }}
</div>
