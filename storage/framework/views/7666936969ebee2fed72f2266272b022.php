<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo e($title ?? 'Insure LK'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@@300;400;500;600;700&display=swap" rel="stylesheet">
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
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link.active { background: rgba(99,102,241,0.15); color: #818cf8; }
        .sidebar-link.active svg { color: #818cf8; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        body.sidebar-collapsed .sidebar-text { display: none; }
        body.sidebar-collapsed aside { width: 5rem !important; }
        body.sidebar-collapsed #main-content { margin-left: 5rem !important; }
        body.sidebar-collapsed .sidebar-chevron { display: none; }
        .dropdown-menu { display: none; }
        .dropdown-menu.show { display: block; }
        .submenu { display: none; }
        .submenu.open { display: block; }
        .submenu-toggle .chevron { transition: transform 0.2s; }
        .submenu-toggle.open .chevron { transform: rotate(180deg); }
        .flash-msg { transition: opacity 0.3s, transform 0.3s; }
        .flash-msg.hidden { opacity: 0; transform: translateY(-8px); pointer-events: none; }

        /* Element Plus-inspired form system */
        :root {
            --el-color-primary: #4f46e5;
            --el-color-primary-light-3: rgba(79, 70, 229, 0.3);
            --el-color-primary-light-5: rgba(79, 70, 229, 0.15);
            --el-color-success: #67c23a;
            --el-color-warning: #e6a23c;
            --el-color-danger: #f56c6c;
            --el-border-color: #dcdfe6;
            --el-border-color-hover: #c0c4cc;
            --el-text-color-primary: #303133;
            --el-text-color-regular: #606266;
            --el-text-color-placeholder: #a8abb2;
            --el-bg-color: #ffffff;
            --el-bg-color-page: #f5f7fa;
            --el-font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --el-border-radius-base: 6px;
        }

        /* Font override for form elements */
        input, select, textarea, label { font-family: var(--el-font-family); }

        /* Uniform checkbox styling */
        input[type="checkbox"], .el-checkbox {
            -webkit-appearance: none; -moz-appearance: none; appearance: none;
            width: 18px; height: 18px;
            border: 2px solid #d1d5db; border-radius: 5px;
            background-color: #fff; cursor: pointer;
            transition: all 0.15s ease; position: relative; flex-shrink: 0;
        }
        input[type="checkbox"]:hover, .el-checkbox:hover { border-color: #818cf8; }
        input[type="checkbox"]:checked, .el-checkbox:checked { background-color: #4f46e5; border-color: #4f46e5; }
        input[type="checkbox"]:checked::after, .el-checkbox:checked::after { content: '✓'; position: absolute; left: 2px; top: -2px; color: #fff; font-size: 14px; font-weight: bold; }
        input[type="checkbox"]:focus, .el-checkbox:focus { outline: none; box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2); }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased">
<div class="flex min-h-screen">
    <aside class="fixed inset-y-0 left-0 z-30 w-64 flex flex-col transition-all duration-300"
           style="background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);">
        <div class="flex items-center gap-3 px-5 h-16 border-b border-white/10 flex-shrink-0">
            <div class="w-9 h-9 rounded-lg bg-primary-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <span class="sidebar-text text-white font-semibold text-lg tracking-tight whitespace-nowrap">Insure LK</span>
        </div>
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <a href="<?php echo e(route('dashboard')); ?>"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      <?php echo e(request()->routeIs('dashboard') ? 'active' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?>">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                </svg>
                <span class="sidebar-text">Дашборд</span>
            </a>
            <a href="<?php echo e(route('policies.index')); ?>"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      <?php echo e(request()->routeIs('policies.*') ? 'active' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?>">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
                <span class="sidebar-text">Полисы</span>
            </a>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.view')): ?>
            <a href="<?php echo e(route('products.index')); ?>"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      <?php echo e(request()->routeIs('products.*') ? 'active' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?>">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-2.25-1.313M21 7.5v2.25m0-2.25l-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3l2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75l2.25-1.313M12 21.75V19.5m0 2.25l-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25"/>
                </svg>
                <span class="sidebar-text">Продукты</span>
            </a>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('users.view')): ?>
            <div class="mt-2">
                <button onclick="toggleSubmenu(this)" class="submenu-toggle flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors w-full
                       <?php echo e(request()->routeIs('users.*','intermediaries.*') ? 'open text-slate-300' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?>">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                    <span class="sidebar-text flex-1 text-left">Люди</span>
                    <svg class="chevron sidebar-text w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>
                <div class="submenu <?php echo e(request()->routeIs('users.*','intermediaries.*') ? 'open' : ''); ?> ml-5 mt-1 space-y-0.5 border-l border-white/10 pl-3">
                    <a href="<?php echo e(route('users.index')); ?>" class="block px-3 py-2 rounded-lg text-sm transition-colors <?php echo e(request()->routeIs('users.*') ? 'text-primary-400 bg-primary-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?>">Пользователи</a>
                    <a href="<?php echo e(route('intermediaries.index')); ?>" class="block px-3 py-2 rounded-lg text-sm transition-colors <?php echo e(request()->routeIs('intermediaries.*') ? 'text-primary-400 bg-primary-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?>">Посредники</a>
                </div>
            </div>
            <?php endif; ?>
            <div class="pt-4 pb-2 px-3">
                <p class="sidebar-text text-[11px] font-semibold uppercase tracking-wider text-slate-500">Настройки</p>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('roles.view')): ?>
            <a href="<?php echo e(route('roles.index')); ?>"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      <?php echo e(request()->routeIs('roles.*') ? 'active' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?>">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                </svg>
                <span class="sidebar-text">Роли и права</span>
            </a>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('numerators.view')): ?>
            <a href="<?php echo e(route('numerators.index')); ?>"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      <?php echo e(request()->routeIs('numerators.*') ? 'active' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?>">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/>
                </svg>
                <span class="sidebar-text">Нумераторы</span>
            </a>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dictionaries.view')): ?>
            <a href="<?php echo e(route('dictionaries.index')); ?>"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      <?php echo e(request()->routeIs('dictionaries.*') ? 'active' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?>">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
                <span class="sidebar-text">Словари</span>
            </a>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('products.view')): ?>
            <div class="pt-4 pb-2 px-3">
                <p class="sidebar-text text-[11px] font-semibold uppercase tracking-wider text-slate-500">Страхование</p>
            </div>
            <a href="<?php echo e(route('product-types.index')); ?>"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      <?php echo e(request()->routeIs('product-types.*') ? 'active' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?>">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                </svg>
                <span class="sidebar-text">Типы продуктов</span>
            </a>
            <a href="<?php echo e(route('banks.index')); ?>"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      <?php echo e(request()->routeIs('banks.*') ? 'active' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?>">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"/>
                </svg>
                <span class="sidebar-text">Банки</span>
            </a>
            <a href="<?php echo e(route('promocodes.index')); ?>"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      <?php echo e(request()->routeIs('promocodes.*') ? 'active' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?>">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                </svg>
                <span class="sidebar-text">Промокоды</span>
            </a>
            <?php endif; ?>
        </nav>
        <div class="border-t border-white/10 p-3 flex-shrink-0">
            <div class="flex items-center gap-3 px-2 py-2">
                <div class="w-9 h-9 rounded-full bg-primary-500/20 flex items-center justify-center flex-shrink-0">
                    <span class="text-primary-400 font-semibold text-sm"><?php echo e(strtoupper(substr(auth()->user()->name,0,1))); ?></span>
                </div>
                <div class="sidebar-text flex-1 min-w-0">
                    <div class="text-sm font-medium text-white truncate"><?php echo e(auth()->user()->name); ?></div>
                    <div class="text-xs text-slate-400 truncate"><?php echo e(auth()->user()->roles->pluck('title_ru')->join(', ') ?: auth()->user()->roles->pluck('name')->join(', ')); ?></div>
                </div>
            </div>
        </div>
    </aside>
    <div id="main-content" class="flex-1 ml-64 transition-all duration-300">
        <header class="sticky top-0 z-20 bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 shadow-sm">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors text-gray-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
            </div>
            <div class="flex items-center gap-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('ok')): ?>
                    <div id="flash-ok" class="flash-msg fixed top-20 right-6 z-50 bg-green-50 border border-green-200 rounded-xl shadow-lg p-4 flex items-center gap-3 max-w-sm">
                        <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </div>
                        <span class="text-sm text-green-700"><?php echo e(session('ok')); ?></span>
                        <button onclick="document.getElementById('flash-ok').classList.add('hidden')" class="text-green-400 hover:text-green-600 ml-auto"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('err')): ?>
                    <div id="flash-err" class="flash-msg fixed top-20 right-6 z-50 bg-red-50 border border-red-200 rounded-xl shadow-lg p-4 flex items-center gap-3 max-w-sm">
                        <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                        </div>
                        <span class="text-sm text-red-700"><?php echo e(session('err')); ?></span>
                        <button onclick="document.getElementById('flash-err').classList.add('hidden')" class="text-red-400 hover:text-red-600 ml-auto"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="relative">
                    <button onclick="toggleDropdown(this)" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors text-sm text-gray-600">
                        <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center">
                            <span class="text-primary-600 font-semibold text-sm"><?php echo e(strtoupper(substr(auth()->user()->name,0,1))); ?></span>
                        </div>
                        <span class="hidden sm:block"><?php echo e(auth()->user()->name); ?></span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                        <a href="<?php echo e(route('profile.show')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Профиль</a>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Выйти</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        <main class="p-6">
            <?php echo e($slot); ?>

        </main>
    </div>
</div>
<?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

<script>
function toggleSidebar() {
    document.body.classList.toggle('sidebar-collapsed');
    const aside = document.querySelector('aside');
    if (document.body.classList.contains('sidebar-collapsed')) {
        aside.style.width = '5rem';
        document.getElementById('main-content').style.marginLeft = '5rem';
    } else {
        aside.style.width = '16rem';
        document.getElementById('main-content').style.marginLeft = '16rem';
    }
}
function toggleSubmenu(btn) {
    btn.classList.toggle('open');
    const sub = btn.nextElementSibling;
    if (sub) sub.classList.toggle('open');
}
function toggleDropdown(btn) {
    const menu = btn.nextElementSibling;
    if (menu) menu.classList.toggle('show');
}
document.addEventListener('click', function(e) {
    document.querySelectorAll('.dropdown-menu.show').forEach(function(m) {
        if (!m.parentElement.contains(e.target)) m.classList.remove('show');
    });
});
setTimeout(function() {
    document.querySelectorAll('.flash-msg').forEach(function(el) { el.classList.add('hidden'); });
}, 5000);

// Custom Select
function toggleCustomSelect(btn) {
    const wrapper = btn.closest('.custom-select-wrapper');
    const dropdown = wrapper.querySelector('.custom-select-dropdown');
    const chevron = btn.querySelector('.chevron');
    const isOpen = !dropdown.classList.contains('hidden');
    // Close all other dropdowns
    document.querySelectorAll('.custom-select-dropdown').forEach(d => d.classList.add('hidden'));
    document.querySelectorAll('.chevron').forEach(c => c.style.transform = '');
    if (!isOpen) {
        dropdown.classList.remove('hidden');
        if (chevron) chevron.style.transform = 'rotate(180deg)';
    }
}

function selectCustomOption(el, value, label) {
    const wrapper = el.closest('.custom-select-wrapper');
    wrapper.querySelector('.custom-select-label').textContent = label;
    wrapper.querySelector('.custom-select-label').classList.remove('text-slate-400');
    wrapper.querySelector('.custom-select-label').classList.add('text-slate-800', 'font-medium');
    wrapper.querySelector('.custom-select-dropdown').classList.add('hidden');
    wrapper.querySelector('.chevron').style.transform = '';
    // Trigger Livewire update via hidden input
    const hidden = wrapper.querySelector('.custom-select-hidden');
    hidden.value = value;
    hidden.dispatchEvent(new Event('input', {bubbles: true}));
}

function filterCustomSelect(input) {
    const wrapper = input.closest('.custom-select-wrapper');
    const query = input.value.toLowerCase();
    wrapper.querySelectorAll('.custom-select-option').forEach(opt => {
        const text = opt.textContent.toLowerCase();
        opt.style.display = text.includes(query) ? '' : 'none';
    });
}

// Custom Datepicker
var datepickers = {};

function toggleDatepicker(btn) {
    const wrapper = btn.closest('.custom-datepicker-wrapper');
    const popup = wrapper.querySelector('.datepicker-popup');
    const isOpen = !popup.classList.contains('hidden');
    // Close all other datepickers
    document.querySelectorAll('.datepicker-popup').forEach(p => p.classList.add('hidden'));
    if (!isOpen) {
        popup.classList.remove('hidden');
        initDatepicker(wrapper);
    }
}

function initDatepicker(wrapper) {
    const name = wrapper.dataset.name;
    const hidden = wrapper.querySelector('.datepicker-hidden');

    if (!datepickers[name]) {
        datepickers[name] = {
            date: hidden.value ? new Date(hidden.value) : null,
            viewDate: new Date(),
            months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь']
        };
    }
    // Reset to month view
    wrapper.querySelector('.datepicker-monthpicker').classList.add('hidden');
    wrapper.querySelector('.datepicker-yearpicker').classList.add('hidden');
    wrapper.querySelector('.datepicker-days').style.display = '';
    renderDays(wrapper);
}

function datepickerPrevYear(btn) {
    const wrapper = btn.closest('.custom-datepicker-wrapper');
    const dp = datepickers[wrapper.dataset.name];
    dp.viewDate = new Date(dp.viewDate.getFullYear() - 1, dp.viewDate.getMonth(), 1);
    renderDays(wrapper);
}

function datepickerNextYear(btn) {
    const wrapper = btn.closest('.custom-datepicker-wrapper');
    const dp = datepickers[wrapper.dataset.name];
    dp.viewDate = new Date(dp.viewDate.getFullYear() + 1, dp.viewDate.getMonth(), 1);
    renderDays(wrapper);
}

function datepickerPrevMonth(btn) {
    const wrapper = btn.closest('.custom-datepicker-wrapper');
    const dp = datepickers[wrapper.dataset.name];
    dp.viewDate = new Date(dp.viewDate.getFullYear(), dp.viewDate.getMonth() - 1, 1);
    renderDays(wrapper);
}

function datepickerNextMonth(btn) {
    const wrapper = btn.closest('.custom-datepicker-wrapper');
    const dp = datepickers[wrapper.dataset.name];
    dp.viewDate = new Date(dp.viewDate.getFullYear(), dp.viewDate.getMonth() + 1, 1);
    renderDays(wrapper);
}

function datepickerToggleMonthPicker(btn) {
    const wrapper = btn.closest('.custom-datepicker-wrapper');
    const monthPicker = wrapper.querySelector('.datepicker-monthpicker');
    const yearPicker = wrapper.querySelector('.datepicker-yearpicker');
    const daysGrid = wrapper.querySelector('.datepicker-days');
    const dp = datepickers[wrapper.dataset.name];

    if (!monthPicker.classList.contains('hidden')) {
        // Show year picker
        monthPicker.classList.add('hidden');
        yearPicker.classList.remove('hidden');
        daysGrid.style.display = 'none';
        renderYearPicker(wrapper);
    } else if (!yearPicker.classList.contains('hidden')) {
        // Show days
        yearPicker.classList.add('hidden');
        daysGrid.style.display = '';
        renderDays(wrapper);
    } else {
        // Show month picker
        monthPicker.classList.remove('hidden');
        daysGrid.style.display = 'none';
        // Highlight current month
        monthPicker.querySelectorAll('button').forEach((b, i) => {
            b.classList.toggle('bg-indigo-600', i === dp.viewDate.getMonth());
            b.classList.toggle('text-white', i === dp.viewDate.getMonth());
        });
    }
}

function datepickerSelectMonth(btn, month) {
    const wrapper = btn.closest('.custom-datepicker-wrapper');
    const dp = datepickers[wrapper.dataset.name];
    dp.viewDate = new Date(dp.viewDate.getFullYear(), month, 1);
    wrapper.querySelector('.datepicker-monthpicker').classList.add('hidden');
    wrapper.querySelector('.datepicker-days').style.display = '';
    renderDays(wrapper);
}

function renderYearPicker(wrapper) {
    const dp = datepickers[wrapper.dataset.name];
    const yearPicker = wrapper.querySelector('.datepicker-yearpicker');
    const currentYear = dp.viewDate.getFullYear();
    let html = '';
    for (let y = currentYear - 50; y <= currentYear + 10; y++) {
        const isActive = y === currentYear;
        html += '<button type="button" onclick="datepickerSelectYear(this, ' + y + ')" class="px-3 py-2 text-sm rounded-lg transition-colors ' +
            (isActive ? 'bg-indigo-600 text-white' : 'hover:bg-indigo-50 text-slate-700') + '">' + y + '</button>';
    }
    yearPicker.innerHTML = html;
    // Scroll to current year
    const activeBtn = yearPicker.querySelector('.bg-indigo-600');
    if (activeBtn) activeBtn.scrollIntoView({ block: 'center' });
}

function datepickerSelectYear(btn, year) {
    const wrapper = btn.closest('.custom-datepicker-wrapper');
    const dp = datepickers[wrapper.dataset.name];
    dp.viewDate = new Date(year, dp.viewDate.getMonth(), 1);
    wrapper.querySelector('.datepicker-yearpicker').classList.add('hidden');
    wrapper.querySelector('.datepicker-monthpicker').classList.remove('hidden');
    wrapper.querySelector('.datepicker-days').style.display = 'none';
    wrapper.querySelector('.datepicker-monthpicker').querySelectorAll('button').forEach((b, i) => {
        b.classList.toggle('bg-indigo-600', i === dp.viewDate.getMonth());
        b.classList.toggle('text-white', i === dp.viewDate.getMonth());
    });
}

function renderDays(wrapper) {
    const name = wrapper.dataset.name;
    const dp = datepickers[name];
    const minDate = wrapper.dataset.min ? new Date(wrapper.dataset.min) : null;
    const maxDate = wrapper.dataset.max ? new Date(wrapper.dataset.max) : null;
    const monthLabel = wrapper.querySelector('.datepicker-month');
    const daysContainer = wrapper.querySelector('.datepicker-days');

    monthLabel.textContent = dp.months[dp.viewDate.getMonth()] + ' ' + dp.viewDate.getFullYear();

    const year = dp.viewDate.getFullYear();
    const month = dp.viewDate.getMonth();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    let firstDay = new Date(year, month, 1).getDay();
    firstDay = firstDay === 0 ? 6 : firstDay - 1;

    let html = '';
    for (let i = 0; i < firstDay; i++) {
        html += '<div></div>';
    }
    for (let day = 1; day <= daysInMonth; day++) {
        const d = new Date(year, month, day);
        const isSelected = dp.date && dp.date.getTime() === d.getTime();
        const isDisabled = (minDate && d < minDate) || (maxDate && d > maxDate);
        let cls = 'h-9 w-9 rounded-full flex items-center justify-center text-sm font-medium transition-all cursor-pointer ';
        if (isSelected) cls += 'bg-indigo-600 text-white shadow-md';
        else if (isDisabled) cls += 'text-slate-300 cursor-not-allowed';
        else cls += 'text-slate-800 hover:bg-indigo-50';
        const disabled = isDisabled ? 'disabled' : '';
        html += '<button type="button" onclick="selectDate(this, ' + day + ')" ' + disabled + ' class="' + cls + '">' + day + '</button>';
    }
    daysContainer.innerHTML = html;
}

function selectDate(btn, day) {
    const wrapper = btn.closest('.custom-datepicker-wrapper');
    const name = wrapper.dataset.name;
    const dp = datepickers[name];
    const minDate = wrapper.dataset.min ? new Date(wrapper.dataset.min) : null;
    const maxDate = wrapper.dataset.max ? new Date(wrapper.dataset.max) : null;
    const d = new Date(dp.viewDate.getFullYear(), dp.viewDate.getMonth(), day);
    if (minDate && d < minDate) return;
    if (maxDate && d > maxDate) return;

    dp.date = d;
    const formatted = d.toISOString().split('T')[0];
    const hidden = wrapper.querySelector('.datepicker-hidden');
    hidden.value = formatted;
    // Update input field
    const input = wrapper.querySelector('.datepicker-input');
    if (input) {
        const dayStr = String(d.getDate()).padStart(2, '0');
        const monthStr = String(d.getMonth() + 1).padStart(2, '0');
        input.value = dayStr + '.' + monthStr + '.' + d.getFullYear();
    }
    wrapper.querySelector('.datepicker-popup').classList.add('hidden');
    // Trigger Livewire update
    hidden.dispatchEvent(new Event('input', {bubbles: true}));
}

function handleDateInput(input) {
    const wrapper = input.closest('.custom-datepicker-wrapper');
    if (!wrapper) return;
    const hidden = wrapper.querySelector('.datepicker-hidden');
    const val = input.value.trim();

    // Try to parse dd.mm.yyyy or d.m.yyyy
    const match = val.match(/^(\d{1,2})\.(\d{1,2})\.(\d{4})$/);
    if (match) {
        const day = parseInt(match[1], 10);
        const month = parseInt(match[2], 10);
        const year = parseInt(match[3], 10);
        if (month >= 1 && month <= 12 && day >= 1 && day <= 31) {
            const d = new Date(year, month - 1, day);
            if (d.getDate() === day && d.getMonth() === month - 1) {
                const formatted = d.toISOString().split('T')[0];
                hidden.value = formatted;
                // Trigger Livewire update
                hidden.dispatchEvent(new Event('input', {bubbles: true}));
            }
        }
    }
}

// Close dropdowns on outside click
document.addEventListener('click', function(e) {
    if (!e.target.closest('.custom-select-wrapper')) {
        document.querySelectorAll('.custom-select-dropdown').forEach(d => d.classList.add('hidden'));
        document.querySelectorAll('.chevron').forEach(c => c.style.transform = '');
    }
    if (!e.target.closest('.custom-datepicker-wrapper')) {
        document.querySelectorAll('.datepicker-popup').forEach(p => p.classList.add('hidden'));
    }
});

// Drag-and-drop for product fields
var dragState = { type: '', fromIndex: -1, fromGroup: 0 };

function dragStartSection(e, index) {
    dragState = { type: 'section', fromIndex: index };
    e.dataTransfer.effectAllowed = 'move';
    e.target.style.opacity = '0.5';
}

function dragOverSection(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    var el = e.target.closest('[draggable]');
    if (el) el.style.borderColor = '#4f46e5';
}

function dropSection(e, toIndex) {
    e.preventDefault();
    var el = e.target.closest('[draggable]');
    if (el) el.style.borderColor = '';
    if (dragState.type === 'section' && dragState.fromIndex !== toIndex) {
        document.getElementById('dragAction').value = JSON.stringify({action:'moveSection', from: dragState.fromIndex, to: toIndex});
        document.getElementById('dragAction').dispatchEvent(new Event('input', {bubbles: true}));
    }
    dragState = { type: '', fromIndex: -1 };
}

function dragEndSection(e) {
    e.target.style.opacity = '1';
    document.querySelectorAll('[draggable]').forEach(function(el) { el.style.borderColor = ''; });
}

function dragStartField(e, index, groupId) {
    dragState = { type: 'field', fromIndex: index, fromGroup: groupId };
    e.dataTransfer.effectAllowed = 'move';
    e.target.style.opacity = '0.5';
}

function dragOverField(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    var el = e.target.closest('[draggable]');
    if (el) el.style.borderColor = '#4f46e5';
}

function dropField(e, toIndex, toGroupId) {
    e.preventDefault();
    var el = e.target.closest('[draggable]');
    if (el) el.style.borderColor = '';
    if (dragState.type === 'field') {
        document.getElementById('dragAction').value = JSON.stringify({action:'moveField', from: dragState.fromIndex, to: toIndex, fromGroup: dragState.fromGroup, toGroup: toGroupId});
        document.getElementById('dragAction').dispatchEvent(new Event('input', {bubbles: true}));
    }
    dragState = { type: '', fromIndex: -1, fromGroup: 0 };
}

function dragEndField(e) {
    e.target.style.opacity = '1';
    document.querySelectorAll('[draggable]').forEach(function(el) { el.style.borderColor = ''; });
}

function dragOverGroup(e) {
    e.preventDefault();
    e.target.closest('div').style.backgroundColor = 'rgba(79, 70, 229, 0.1)';
}

function dropToGroup(e, groupId) {
    e.preventDefault();
    e.target.closest('div').style.backgroundColor = '';
    if (dragState.type === 'field') {
        document.getElementById('dragAction').value = JSON.stringify({action:'moveFieldToGroup', from: dragState.fromIndex, toGroup: groupId});
        document.getElementById('dragAction').dispatchEvent(new Event('input', {bubbles: true}));
    }
    dragState = { type: '', fromIndex: -1 };
}

function dropToRow(e, groupId, rowId) {
    e.preventDefault();
    e.target.closest('div').style.backgroundColor = '';
    if (dragState.type === 'field') {
        document.getElementById('dragAction').value = JSON.stringify({action:'dropToRow', from: dragState.fromIndex, toGroup: groupId, rowId: rowId});
        document.getElementById('dragAction').dispatchEvent(new Event('input', {bubbles: true}));
    }
    dragState = { type: '', fromIndex: -1 };
}

// Coverage drag-and-drop
function dragStartCoverage(e, index) {
    dragState = { type: 'coverage', fromIndex: index };
    e.dataTransfer.effectAllowed = 'move';
    e.target.style.opacity = '0.5';
}

function dragEndCoverage(e) {
    e.target.style.opacity = '1';
    document.querySelectorAll('[draggable]').forEach(function(el) { el.style.borderColor = ''; });
}

function dropCoverageToRow(e, rowId) {
    e.preventDefault();
    var el = e.target.closest('[draggable]');
    if (el) el.style.borderColor = '';
    if (dragState.type === 'coverage') {
        document.getElementById('dragAction').value = JSON.stringify({action:'dropCoverageToRow', from: dragState.fromIndex, rowId: rowId});
        document.getElementById('dragAction').dispatchEvent(new Event('input', {bubbles: true}));
    }
    dragState = { type: '', fromIndex: -1 };
}

function dragOverCoverageRow(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    var el = e.target.closest('[draggable]');
    if (el) el.style.borderColor = '#6366f1';
}
</script>
</body>
</html>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/components/layouts/app.blade.php ENDPATH**/ ?>