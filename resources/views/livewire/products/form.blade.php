<div>
<h1 class="text-2xl font-semibold mb-4">Страховой продукт</h1>
<div class="bg-white rounded border p-5 max-w-4xl space-y-3">
<div class="grid grid-cols-2 gap-3">
<div><label class="text-sm">Код</label><input wire:model="code" class="border rounded px-3 py-2 w-full"></div>
<div><label class="text-sm">Название</label><input wire:model="name" class="border rounded px-3 py-2 w-full"></div>
</div>
<div><label class="text-sm">Описание</label><textarea wire:model="description" class="border rounded px-3 py-2 w-full"></textarea></div>
<div class="grid grid-cols-2 gap-3">
<div><label class="text-sm">Нумератор</label>
<select wire:model="numerator_id" class="border rounded px-3 py-2 w-full"><option value="">—</option>@foreach($numerators as $n)<option value="{{ $n->id }}">{{ $n->name }}</option>@endforeach</select></div>
<div><label class="text-sm">Класс-калькулятор</label><input wire:model="calculator_class" class="border rounded px-3 py-2 w-full font-mono text-sm"></div>
</div>
<div><label class="text-sm">Config JSON (схема полей, риски, шаблоны)</label>
<textarea wire:model="config_json" rows="18" class="border rounded px-3 py-2 w-full font-mono text-xs"></textarea>
<p class="text-xs text-slate-500">Здесь хранится конструктор: fields / risks / validation / template. Для простых продуктов можно оставить {}</p>
</div>
<div><button wire:click="save" class="bg-slate-900 text-white px-4 py-2 rounded">Сохранить</button>
<a href="{{ route('products.index') }}" class="px-4 py-2">Отмена</a></div>
</div>
</div>
