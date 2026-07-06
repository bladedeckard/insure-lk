<div>
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Роли и права доступа</h1>
        <a href="{{ route('roles.create') }}" class="bg-slate-900 text-white px-4 py-2 rounded hover:bg-slate-800">
            + Создать роль
        </a>
    </div>

    <div class="bg-white rounded border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="text-left p-3 w-48">Техническое имя</th>
                    <th class="text-left p-3">Название (RU)</th>
                    <th class="text-left p-3">Описание</th>
                    <th class="text-center p-3 w-24">Прав</th>
                    <th class="text-right p-3 w-40">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $r)
                <tr class="border-t hover:bg-slate-50">
                    <td class="p-3 font-mono text-xs text-slate-600">{{ $r->name }}</td>
                    <td class="p-3">
                        <a href="{{ route('roles.edit', $r) }}" class="font-semibold text-slate-900 hover:text-blue-600">
                            {{ $r->title_ru ?: $r->name }}
                        </a>
                    </td>
                    <td class="p-3 text-slate-600 text-xs">{{ \Illuminate\Support\Str::limit($r->description, 80) }}</td>
                    <td class="p-3 text-center">{{ $r->permissions_count }}</td>
                    <td class="p-3 text-right space-x-3">
                        <a href="{{ route('roles.edit', $r) }}" class="text-blue-600 hover:underline">Открыть</a>
                        <button 
                            wire:click="delete({{ $r->id }})"
                            wire:confirm="Точно удалить роль «{{ $r->title_ru ?: $r->name }}»?"
                            class="text-rose-600 hover:underline"
                        >Удалить</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-6 text-center text-slate-500">Ролей пока нет</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-xs text-slate-500">
        Системные роли <code>admin / chief_manager / manager / agent</code> защищены от удаления.
    </div>
</div>
