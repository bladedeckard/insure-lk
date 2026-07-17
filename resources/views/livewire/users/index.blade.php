<div>
    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Пользователи</h1>
            <p class="text-sm text-gray-500 mt-1">Управление пользователями системы</p>
        </div>
        <a href="{{ route('users.create') }}"
           class="inline-flex items-center gap-2 bg-primary-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold
                  hover:bg-primary-700 transition-colors shadow-sm shadow-primary-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Создать
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-6">
        <div class="p-4 flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px] max-w-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input wire:model.live="search" placeholder="Поиск по имени или email..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50 focus:bg-white transition-colors">
            </div>
            <select wire:model.live="roleFilter"
                    class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                <option value="">Все роли</option>
                <option value="admin">Администратор</option>
                <option value="chief_manager">Главный менеджер</option>
                <option value="manager">Менеджер</option>
                <option value="agent">Агент</option>
            </select>
        </div>
    </div>

    {{-- Users table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Пользователь</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Посредник</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Роли</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Статус</th>
                    <th class="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Действия</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $u)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary-50 flex items-center justify-center flex-shrink-0">
                                <span class="text-primary-600 font-semibold text-sm">{{ strtoupper(substr($u->name, 0, 2)) }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">{{ $u->name }}</div>
                                <div class="text-xs text-gray-500">{{ $u->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($u->intermediary)
                            <span class="text-sm text-gray-700">{{ $u->intermediary->name }}</span>
                        @else
                            <span class="text-xs text-gray-400">Сотрудник СК</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1">
                            @foreach($u->roles as $role)
                                @php
                                    $roleColors = [
                                        'admin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'chief_manager' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'manager' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                        'agent' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    ];
                                @endphp
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-medium border {{ $roleColors[$role->name] ?? 'bg-gray-100 text-gray-600 border-gray-200' }}">
                                    {{ $role->title_ru ?: $role->name }}
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($u->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Активен
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                Неактивен
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('profile.user', $u) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-100 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                </svg>
                                Профиль
                            </a>
                            <a href="{{ route('users.edit', $u) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-primary-600 hover:bg-primary-50 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                </svg>
                                Редактировать
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                        </svg>
                        <p class="text-sm text-gray-500">Пользователи не найдены</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
</div>
