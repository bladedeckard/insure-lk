<div>
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Нумераторы полисов</h1>
        <a href="{{ route('numerators.create') }}" class="bg-slate-900 text-white px-4 py-2 rounded hover:bg-slate-800">
            + Создать нумератор
        </a>
    </div>

    <div class="mb-3">
        <input wire:model.live="search" placeholder="Поиск по названию..." class="border rounded px-3 py-2 bg-white w-64">
    </div>

    <div class="bg-white rounded border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="text-left p-3">Название</th>
                    <th class="text-left p-3">Шаблон номера</th>
                    <th class="text-center p-3">Длина счётчика</th>
                    <th class="text-center p-3">Сброс</th>
                    <th class="text-right p-3 w-40">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $n)
                @php
                    $year = $n->include_year ? ($n->year_digits == 2 ? date('y') : date('Y')) : '';
                    $sample = ($n->prefix ?? '') . $year . str_repeat('0', max(0,$n->counter_length-1)) . '1';
                @endphp
                <tr class="border-t hover:bg-slate-50">
                    <td class="p-3">
                        <a href="{{ route('numerators.edit', $n) }}" class="font-semibold text-slate-900 hover:text-blue-600">
                            {{ $n->name }}
                        </a>
                        <div class="text-xs text-slate-500">ID #{{ $n->id }}</div>
                    </td>
                    <td class="p-3 font-mono text-slate-700">{{ $sample }}</td>
                    <td class="p-3 text-center">{{ $n->counter_length }}</td>
                    <td class="p-3 text-center text-xs">
                        {{ $n->reset_period === 'yearly' ? 'Каждый год' : 'Никогда' }}
                    </td>
                    <td class="p-3 text-right space-x-3">
                        <a href="{{ route('numerators.edit', $n) }}" class="text-blue-600 hover:underline">Открыть</a>
                        <button wire:click="delete({{ $n->id }})" wire:confirm="Точно удалить нумератор «{{ $n->name }}»?" class="text-rose-600 hover:underline">Удалить</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-6 text-center text-slate-500">Нумераторов пока нет. Создайте первый.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $items->links() }}</div>
</div>
