<div>
    {{-- Page header --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
            <a href="{{ route('users.index') }}" class="hover:text-primary-600 transition-colors">Пользователи</a>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            <span class="text-gray-900">{{ $user->name }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left column: User info card --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Profile card --}}
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="h-24 bg-gradient-to-r from-primary-500 to-primary-700"></div>
                <div class="px-6 pb-6">
                    <div class="-mt-12 flex items-end gap-4 mb-4">
                        <div class="w-24 h-24 rounded-2xl bg-white border-4 border-white shadow-lg flex items-center justify-center">
                            <span class="text-3xl font-bold text-primary-600">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                        </div>
                        <div class="pb-1">
                            <h1 class="text-xl font-bold text-gray-900">{{ $user->name }}</h1>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mb-4">
                        @if($user->is_active)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Активен
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                Неактивен
                            </span>
                        @endif
                        @foreach($user->roles as $role)
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-primary-50 text-primary-700 border border-primary-200">
                                {{ $role->title_ru ?: $role->name }}
                            </span>
                        @endforeach
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex items-center gap-3 text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                            <span>{{ $user->email }}</span>
                        </div>
                        @if($user->intermediary)
                        <div class="flex items-center gap-3 text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                            </svg>
                            <span>{{ $user->intermediary->name }}</span>
                        </div>
                        @endif
                        <div class="flex items-center gap-3 text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                            <span>Зарегистрирован {{ $user->created_at->format('d.m.Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick actions --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Действия</h3>
                <div class="space-y-2">
                    <a href="{{ route('users.edit', $user) }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                        </svg>
                        Редактировать
                    </a>
                    @if(auth()->id() !== $user->id)
                    <a href="{{ route('policies.create') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        Новый полис
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right column: Stats & activity --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Stats grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['policies_total'] }}</div>
                            <div class="text-xs text-gray-500">Всего полисов</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['policies_issued'] }}</div>
                            <div class="text-xs text-gray-500">Выдано</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['policies_pending'] }}</div>
                            <div class="text-xs text-gray-500">На согласовании</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['policies_draft'] }}</div>
                            <div class="text-xs text-gray-500">Черновиков</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent policies --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-900">Последние полисы</h2>
                    @if($stats['policies_total'] > 0)
                        <a href="{{ route('policies.index') }}" class="text-xs font-medium text-primary-600 hover:text-primary-700">Все полисы &rarr;</a>
                    @endif
                </div>
                @if($recentPolicies->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        <p class="text-sm text-gray-500">Полисов пока нет</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($recentPolicies as $policy)
                            <a href="{{ route('policies.edit', $policy) }}" class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $policy->number ?? 'Черновик #' . $policy->id }}</div>
                                        <div class="text-xs text-gray-500">{{ $policy->product->name }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-gray-900">{{ number_format($policy->premium, 2, ',', ' ') }} ₽</div>
                                        <div class="text-xs text-gray-500">{{ $policy->created_at->format('d.m.Y') }}</div>
                                    </div>
                                    @php
                                        $statusColors = [
                                            'draft' => 'bg-gray-100 text-gray-600',
                                            'pending_approval' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                            'issued' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                            'cancelled' => 'bg-red-50 text-red-600 border border-red-200',
                                        ];
                                        $statusLabels = [
                                            'draft' => 'Черновик',
                                            'pending_approval' => 'На согласовании',
                                            'issued' => 'Выдан',
                                            'cancelled' => 'Аннулирован',
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColors[$policy->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $statusLabels[$policy->status] ?? $policy->status }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Roles & permissions info --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-900">Роли и права доступа</h2>
                </div>
                <div class="p-6">
                    @forelse($user->roles as $role)
                        <div class="mb-4 last:mb-0">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-primary-50 text-primary-700 border border-primary-200">
                                    {{ $role->title_ru ?: $role->name }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $role->permissions_count ?? $role->permissions->count() }} прав</span>
                            </div>
                            <div class="flex flex-wrap gap-1.5 ml-1">
                                @foreach($role->permissions as $perm)
                                    <span class="inline-flex px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-600">
                                        {{ $perm->title_ru ?: $perm->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Роли не назначены</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
