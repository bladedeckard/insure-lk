<div>
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Словари</h1>
        <a href="{{ route('dictionaries.create') }}" class="bg-slate-900 text-white px-4 py-2 rounded hover:bg-slate-800">
            + Создать словарь
        </a>
    </div>

    <div class="mb-3">
        <input wire:model.live="search" placeholder="Поиск по коду / названию..." class="border rounded px-3 py-2 bg-white w-80">
    </div>

    <div class="bg-white rounded border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="text-left p-3">Код</th>
                    <th class="text-left p-3">Название</th>
                    <th class="text-center p-3">Элементов</th>
                    <th class="text-right p-3 w-40">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dicts as $d)
                <tr class="border-t hover:bg-slate-50">
                    <td class="p-3 font-mono text-xs">{{ $d->code }}</td>
                    <td class="p-3">
                        <a href="{{ route('dictionaries.edit', $d) }}" class="font-semibold text-slate-900 hover:text-blue-600">
                            {{ $d->name }}
                        </a>
                    </td>
                    <td class="p-3 text-center">{{ $d->items_count }}</td>
                    <td class="p-3 text-right space-x-3">
                        <a href="{{ route('dictionaries.edit', $d) }}" class="text-blue-600 hover:underline">Открыть</a>
                        <button wire:click="delete({{ $d->id }})" wire:confirm="Точно удалить словарь «{{ $d->name }}»? Все элементы будут удалены безвозвратно." class="text-rose-600 hover:underline">Удалить</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="p-6 text-center text-slate-500">Словарей пока нет</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $dicts->links() }}</div>
</div>
