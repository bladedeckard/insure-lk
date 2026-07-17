<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $title ?? 'Insure LK' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Montserrat', 'sans-serif'] },
                    colors: {
                        primary: { 50:'#eef2ff',100:'#e0e7ff',200:'#c7d2fe',300:'#a5b4fc',400:'#818cf8',500:'#6366f1',600:'#4f46e5',700:'#4338ca',800:'#3730a3',900:'#312e81' },
                    }
                }
            }
        }
    </script>
    @livewireStyles
    <style>
        .sidebar-link.active { background: rgba(99,102,241,0.15); color: #818cf8; }
        .sidebar-link.active svg { color: #818cf8; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        /* Sidebar collapsed */
        body.sidebar-collapsed .sidebar-text { display: none; }
        body.sidebar-collapsed aside { width: 5rem !important; }
        body.sidebar-collapsed #main-content { margin-left: 5rem !important; }
        body.sidebar-collapsed .sidebar-chevron { display: none; }
        /* Dropdown */
        .dropdown-menu { display: none; }
        .dropdown-menu.show { display: block; }
        /* Submenu */
        .submenu { display: none; }
        .submenu.open { display: block; }
        .submenu-toggle .chevron { transition: transform 0.2s; }
        .submenu-toggle.open .chevron { transform: rotate(180deg); }
        /* Flash */
        .flash-msg { transition: opacity 0.3s, transform 0.3s; }
        .flash-msg.hidden { opacity: 0; transform: translateY(-8px); pointer-events: none; }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased">
<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-30 w-64 flex flex-col transition-all duration-300"
           style="background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 h-16 border-b border-white/10 flex-shrink-0">
            <div class="w-9 h-9 rounded-lg bg-primary-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <span class="sidebar-text text-white font-semibold text-lg tracking-tight whitespace-nowrap">Insure LK</span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('dashboard') ? 'active' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                </svg>
                <span class="sidebar-text">Дашборд</span>
            </a>

            <a href="{{ route('policies.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('policies.*') ? 'active' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
                <span class="sidebar-text">Полисы</span>
            </a>

            @can('products.view')
            <a href="{{ route('products.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('products.*') ? 'active' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-2.25-1.313M21 7.5v2.25m0-2.25l-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3l2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75l2.25-1.313M12 21.75V19.5m0 2.25l-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25"/>
                </svg>
                <span class="sidebar-text">Продукты</span>
            </a>
            @endcan

            @can('users.view')
            <div class="mt-2">
                <button onclick="toggleSubmenu(this)" class="submenu-toggle flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors w-full
                       {{ request()->routeIs('users.*','intermediaries.*') ? 'open text-slate-300' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                    <span class="sidebar-text flex-1 text-left">Люди</span>
                    <svg class="chevron sidebar-text w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>
                <div class="submenu {{ request()->routeIs('users.*','intermediaries.*') ? 'open' : '' }} ml-5 mt-1 space-y-0.5 border-l border-white/10 pl-3">
                    <a href="{{ route('users.index') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('users.*') ? 'text-primary-400 bg-primary-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">Пользователи</a>
                    <a href="{{ route('intermediaries.index') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('intermediaries.*') ? 'text-primary-400 bg-primary-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">Посредники</a>
                </div>
            </div>
            @endcan

            <div class="pt-4 pb-2 px-3">
                <p class="sidebar-text text-[11px] font-semibold uppercase tracking-wider text-slate-500">Настройки</p>
            </div>

            @can('roles.view')
            <a href="{{ route('roles.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('roles.*') ? 'active' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                </svg>
                <span class="sidebar-text">Роли и права</span>
            </a>
            @endcan

            @can('numerators.view')
            <a href="{{ route('numerators.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('numerators.*') ? 'active' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/>
                </svg>
                <span class="sidebar-text">Нумераторы</span>
            </a>
            @endcan

            @can('dictionaries.view')
            <a href="{{ route('dictionaries.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('dictionaries.*') ? 'active' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
                <span class="sidebar-text">Словари</span>
            </a>
            @endcan
        </nav>

        {{-- User block --}}
        <div class="border-t border-white/10 p-3 flex-shrink-0">
            <div class="flex items-center gap-3 px-2 py-2">
                <div class="w-9 h-9 rounded-full bg-primary-500/20 flex items-center justify-center flex-shrink-0">
                    <span class="text-primary-400 font-semibold text-sm">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</span>
                </div>
                <div class="sidebar-text flex-1 min-w-0">
                    <div class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-slate-400 truncate">{{ auth()->user()->roles->pluck('title_ru')->join(', ') ?: auth()->user()->roles->pluck('name')->join(', ') }}</div>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main content --}}
    <div id="main-content" class="flex-1 ml-64 transition-all duration-300">

        {{-- Top header --}}
        <header class="sticky top-0 z-20 bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 shadow-sm">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors text-gray-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
            </div>

            <div class="flex items-center gap-3">
                {{-- Notifications bell --}}
                <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors text-gray-500 relative">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                </button>

                {{-- User dropdown --}}
                <div class="relative">
                    <button onclick="toggleDropdown('profile-dropdown')" class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-primary-500 flex items-center justify-center">
                            <span class="text-white font-semibold text-xs">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</span>
                        </div>
                        <span class="text-sm font-medium text-gray-700 hidden sm:block">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </button>

                    <div id="profile-dropdown" class="dropdown-menu absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <div class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                        </div>
                        <a href="{{ route('profile.show') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                            Мой профиль
                        </a>
                        <div class="border-t border-gray-100 mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                                    </svg>
                                    Выйти
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash messages (toast) --}}
        <div id="toast-container" class="fixed top-20 right-6 z-50 space-y-3 w-96">
            @if(session('ok'))
                <div class="toast-item flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-lg text-sm">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="flex-1">{{ session('ok') }}</span>
                    <button onclick="this.closest('.toast-item').remove()" class="text-emerald-400 hover:text-emerald-600 flex-shrink-0">&times;</button>
                </div>
            @endif
            @if(session('err'))
                <div class="toast-item flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-lg text-sm">
                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <span class="flex-1">{{ session('err') }}</span>
                    <button onclick="this.closest('.toast-item').remove()" class="text-red-400 hover:text-red-600 flex-shrink-0">&times;</button>
                </div>
            @endif
            @if(session('password_plain'))
                <div class="toast-item flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl shadow-lg text-sm">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                    </div>
                    <span class="flex-1">Пароль: <b class="font-mono">{{ session('password_plain') }}</b></span>
                    <button onclick="this.closest('.toast-item').remove()" class="text-amber-400 hover:text-amber-600 flex-shrink-0">&times;</button>
                </div>
            @endif
        </div>

        {{-- Page content --}}
        <main class="p-6">
            {{ $slot }}
        </main>
    </div>
</div>

{{-- Confirmation modal --}}
<div id="confirm-modal" class="fixed inset-0 z-[100] hidden" style="background: rgba(0,0,0,0.4); backdrop-filter: blur(2px);">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" id="confirm-modal-box">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0" id="confirm-icon-wrap">
                        <svg id="confirm-icon" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-gray-900" id="confirm-title">Подтверждение</h3>
                        <p class="text-sm text-gray-600 mt-1" id="confirm-message">Вы уверены?</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                <button onclick="closeConfirmModal()" class="px-4 py-2.5 text-sm font-medium text-gray-600 border border-gray-200 rounded-xl hover:bg-white transition-colors">
                    Отмена
                </button>
                <button id="confirm-btn" class="px-5 py-2.5 text-sm font-semibold text-white rounded-xl transition-colors">
                    Подтвердить
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Sidebar toggle
    function toggleSidebar() {
        document.body.classList.toggle('sidebar-collapsed');
    }

    // Dropdown toggle
    function toggleDropdown(id) {
        var el = document.getElementById(id);
        el.classList.toggle('show');
    }

    // Submenu toggle
    function toggleSubmenu(btn) {
        btn.classList.toggle('open');
        var submenu = btn.nextElementSibling;
        submenu.classList.toggle('open');
    }

    // Close dropdowns on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(function(d) {
                d.classList.remove('show');
            });
        }
    });

    // Auto-hide toast messages
    document.querySelectorAll('.toast-item').forEach(function(el) {
        setTimeout(function() {
            el.style.transition = 'opacity 0.3s, transform 0.3s';
            el.style.opacity = '0';
            el.style.transform = 'translateX(20px)';
            setTimeout(function() { el.remove(); }, 300);
        }, 5000);
    });

    // ─── Confirmation Modal ──────────────────────────────────────────────
    var _confirmCallback = null;

    function confirmAction(opts) {
        var modal = document.getElementById('confirm-modal');
        var title = document.getElementById('confirm-title');
        var message = document.getElementById('confirm-message');
        var btn = document.getElementById('confirm-btn');
        var iconWrap = document.getElementById('confirm-icon-wrap');
        var icon = document.getElementById('confirm-icon');

        title.textContent = opts.title || 'Подтверждение';
        message.textContent = opts.message || 'Вы уверены?';

        // Style by type
        if (opts.type === 'danger') {
            iconWrap.className = 'w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 bg-red-100';
            icon.className = 'w-6 h-6 text-red-600';
            btn.className = 'px-5 py-2.5 text-sm font-semibold text-white rounded-xl bg-red-600 hover:bg-red-700 transition-colors';
            btn.textContent = opts.confirmText || 'Удалить';
        } else if (opts.type === 'warning') {
            iconWrap.className = 'w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 bg-amber-100';
            icon.className = 'w-6 h-6 text-amber-600';
            btn.className = 'px-5 py-2.5 text-sm font-semibold text-white rounded-xl bg-amber-600 hover:bg-amber-700 transition-colors';
            btn.textContent = opts.confirmText || 'Подтвердить';
        } else {
            iconWrap.className = 'w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 bg-primary-100';
            icon.className = 'w-6 h-6 text-primary-600';
            btn.className = 'px-5 py-2.5 text-sm font-semibold text-white rounded-xl bg-primary-600 hover:bg-primary-700 transition-colors';
            btn.textContent = opts.confirmText || 'Подтвердить';
        }

        _confirmCallback = opts.onConfirm || null;
        modal.classList.remove('hidden');
    }

    function closeConfirmModal() {
        document.getElementById('confirm-modal').classList.add('hidden');
        _confirmCallback = null;
    }

    document.getElementById('confirm-btn').addEventListener('click', function() {
        if (_confirmCallback) _confirmCallback();
        closeConfirmModal();
    });

    document.getElementById('confirm-modal').addEventListener('click', function(e) {
        if (e.target === this) closeConfirmModal();
    });

    // ─── Copy variable to clipboard ──────────────────────────────────────
    function copyVar(btn, code) {
        var text = '${' + code + '}';
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text);
        } else {
            var ta = document.createElement('textarea');
            ta.value = text;
            ta.style.cssText = 'position:fixed;left:-9999px';
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
        }
        var label = btn.querySelector('.copy-label');
        var ok = btn.querySelector('.copy-ok');
        if (label) label.classList.add('hidden');
        if (ok) ok.classList.remove('hidden');
        btn.classList.add('bg-green-50', 'border-green-400');
        btn.classList.remove('bg-white', 'border-yellow-200');
        setTimeout(function() {
            if (label) label.classList.remove('hidden');
            if (ok) ok.classList.add('hidden');
            btn.classList.remove('bg-green-50', 'border-green-400');
            btn.classList.add('bg-white', 'border-yellow-200');
        }, 1500);
    }
</script>

@livewireScripts
</body>
</html>
