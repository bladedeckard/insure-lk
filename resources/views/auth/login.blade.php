<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Вход – Insure LK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Montserrat', 'sans-serif'] } } }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans antialiased">
<div class="min-h-screen flex">

    {{-- Left side: Branding --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden items-center justify-center"
         style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 50%, #312e81 100%);">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <circle cx="20" cy="20" r="15" fill="white" opacity="0.05"/>
                <circle cx="80" cy="80" r="25" fill="white" opacity="0.03"/>
                <circle cx="60" cy="30" r="10" fill="white" opacity="0.04"/>
            </svg>
        </div>
        <div class="relative z-10 text-center px-12 max-w-md">
            <div class="w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center mx-auto mb-8">
                <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-3">Insure LK</h1>
            <p class="text-slate-300 text-lg leading-relaxed">
                Личный кабинет<br>страховой компании
            </p>
            <div class="mt-10 flex items-center justify-center gap-8 text-slate-400 text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                    </svg>
                    Безопасность
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605"/>
                    </svg>
                    Надёжность
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                    Команда
                </div>
            </div>
        </div>
    </div>

    {{-- Right side: Login form --}}
    <div class="flex-1 flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">
            {{-- Mobile logo --}}
            <div class="lg:hidden flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-primary-500 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-gray-900">Insure LK</span>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Добро пожаловать</h2>
                <p class="text-gray-500 mt-2">Войдите в свой аккаунт</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </div>
                        <input name="email" type="email" value="{{ old('email', 'admin@thuricum.ru') }}"
                               class="block w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl text-sm
                                      focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors
                                      placeholder-gray-400 bg-gray-50 focus:bg-white"
                               placeholder="admin@thuricum.ru">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Пароль</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                            </svg>
                        </div>
                        <input name="password" type="password" value="password"
                               class="block w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl text-sm
                                      focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors
                                      placeholder-gray-400 bg-gray-50 focus:bg-white"
                               placeholder="Введите пароль">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="text-sm text-gray-600">Запомнить меня</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-primary-600 text-white py-3 px-4 rounded-xl
                               text-sm font-semibold hover:bg-primary-700 focus:ring-4 focus:ring-primary-100
                               transition-all duration-200 shadow-sm shadow-primary-200">
                    Войти
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </button>
            </form>

            <p class="text-center text-xs text-gray-400 mt-8">
                АО СК «Турикум» &middot; Insure LK &middot; {{ date('Y') }}
            </p>
        </div>
    </div>
</div>
</body>
</html>
