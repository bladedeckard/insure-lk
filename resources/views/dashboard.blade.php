<x-layouts.app>
    {{-- Page header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Дашборд</h1>
        <p class="text-sm text-gray-500 mt-1">Обзор системы страхования</p>
    </div>

    {{-- Stat cards --}}
    @php
        $policiesTotal = \App\Models\Policy::count();
        $policiesIssued = \App\Models\Policy::where('status','issued')->count();
        $policiesPending = \App\Models\Policy::where('status','pending_approval')->count();
        $usersCount = \App\Models\User::count();
        $intermediariesCount = \App\Models\Intermediary::where('is_active', true)->count();
        $productsCount = \App\Models\Product::where('status','published')->count();

        $premiumTotal = \App\Models\Policy::where('status','issued')->sum('premium');
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        {{-- Total policies --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $policiesTotal }}</div>
            <div class="text-sm text-gray-500 mt-1">Всего полисов</div>
        </div>

        {{-- Issued --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $policiesIssued }}</div>
            <div class="text-sm text-gray-500 mt-1">Выдано полисов</div>
        </div>

        {{-- Pending --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $policiesPending }}</div>
            <div class="text-sm text-gray-500 mt-1">На согласовании</div>
        </div>

        {{-- Premium --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ number_format($premiumTotal, 0, ',', ' ') }}</div>
            <div class="text-sm text-gray-500 mt-1">Премий (₽)</div>
        </div>
    </div>

    {{-- Secondary stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-xl font-bold text-gray-900">{{ $usersCount }}</div>
                <div class="text-xs text-gray-500">Пользователей</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-cyan-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                </svg>
            </div>
            <div>
                <div class="text-xl font-bold text-gray-900">{{ $intermediariesCount }}</div>
                <div class="text-xs text-gray-500">Посредников</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-rose-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-2.25-1.313M21 7.5v2.25m0-2.25l-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3l2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75l2.25-1.313M12 21.75V19.5m0 2.25l-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25"/>
                </svg>
            </div>
            <div>
                <div class="text-xl font-bold text-gray-900">{{ $productsCount }}</div>
                <div class="text-xs text-gray-500">Активных продуктов</div>
            </div>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-900">Быстрые действия</h2>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('policies.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl border border-gray-200 hover:border-primary-300 hover:bg-primary-50 transition-all group">
                <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center group-hover:bg-primary-100 transition-colors">
                    <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-900">Новый полис</div>
                    <div class="text-xs text-gray-500">Оформить полис</div>
                </div>
            </a>

            @can('products.view')
            <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl border border-gray-200 hover:border-emerald-300 hover:bg-emerald-50 transition-all group">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center group-hover:bg-emerald-100 transition-colors">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-2.25-1.313M21 7.5v2.25m0-2.25l-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3l2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75l2.25-1.313M12 21.75V19.5m0 2.25l-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-900">Продукты</div>
                    <div class="text-xs text-gray-500">Управление продуктами</div>
                </div>
            </a>
            @endcan

            @can('users.view')
            <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl border border-gray-200 hover:border-amber-300 hover:bg-amber-50 transition-all group">
                <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center group-hover:bg-amber-100 transition-colors">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-900">Пользователи</div>
                    <div class="text-xs text-gray-500">Управление доступом</div>
                </div>
            </a>
            @endcan

            <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl border border-gray-200 hover:border-gray-400 hover:bg-gray-50 transition-all group">
                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center group-hover:bg-gray-200 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-900">Мой профиль</div>
                    <div class="text-xs text-gray-500">Настройки аккаунта</div>
                </div>
            </a>
        </div>
    </div>
</x-layouts.app>
