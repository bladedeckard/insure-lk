<div>
    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Роли и права доступа</h1>
            <p class="text-sm text-gray-500 mt-1">Управление ролями пользователей</p>
        </div>
        <a href="{{ route('roles.create') }}"
           class="inline-flex items-center gap-2 bg-primary-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold
                  hover:bg-primary-700 transition-colors shadow-sm shadow-primary-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Создать роль
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Техническое имя</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Название</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Описание</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Прав</th>
                    <th class="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Действия</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($roles as $r)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-mono font-medium bg-gray-100 text-gray-700">
                            {{ $r->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('roles.edit', $r) }}" class="text-sm font-semibold text-gray-900 hover:text-primary-600 transition-colors">
                            {{ $r->title_ru ?: $r->name }}
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-500">{{ \Illuminate\Support\Str::limit($r->description, 60) }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary-50 text-primary-700 text-sm font-semibold">
                            {{ $r->permissions_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('roles.edit', $r) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-primary-600 hover:bg-primary-50 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                </svg>
                                Редактировать
                            </a>
                            <button onclick="confirmAction({
                                    type: 'danger',
                                    title: 'Удаление роли',
                                    message: 'Точно удалить роль «{{ $r->title_ru ?: $r->name }}»?',
                                    confirmText: 'Удалить',
                                    onConfirm: function() { @this.call('delete', {{ $r->id }}); }
                                })"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                                Удалить
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <p class="text-sm text-gray-500">Ролей пока нет</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 px-1">
        <p class="text-xs text-gray-400">
            Системные роли <code class="bg-gray-100 px-1.5 py-0.5 rounded">admin</code>
            <code class="bg-gray-100 px-1.5 py-0.5 rounded">chief_manager</code>
            <code class="bg-gray-100 px-1.5 py-0.5 rounded">manager</code>
            <code class="bg-gray-100 px-1.5 py-0.5 rounded">agent</code> защищены от удаления.
        </p>
    </div>
</div>
