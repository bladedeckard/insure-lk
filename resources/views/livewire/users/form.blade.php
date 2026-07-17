<div>
    {{-- Page header --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
            <a href="{{ route('users.index') }}" class="hover:text-primary-600 transition-colors">Пользователи</a>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            <span class="text-gray-900">{{ $user ? $user->name : 'Новый пользователь' }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $user ? 'Редактирование пользователя' : 'Новый пользователь' }}</h1>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6 space-y-5">
                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Имя</label>
                    <input wire:model="name"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50 focus:bg-white transition-colors"
                           placeholder="Введите имя">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input wire:model="email" type="email"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50 focus:bg-white transition-colors"
                           placeholder="user@example.com">
                </div>

                {{-- Intermediary --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Посредник</label>
                    <select wire:model="intermediary_id"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50 focus:bg-white transition-colors">
                        <option value="">— нет, сотрудник СК —</option>
                        @foreach($intermediaries as $i)
                            <option value="{{ $i->id }}">{{ $i->name }} (ИНН {{ $i->inn }})</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1.5">Если выбран — пользователь видит только полисы этого посредника.</p>
                </div>

                {{-- Active --}}
                <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <input type="checkbox" wire:model="is_active" id="is_active"
                           class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Активен</label>
                </div>

                {{-- Roles --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Роли</label>
                    <div class="space-y-2">
                        @foreach($allRoles as $r)
                            @php
                                $roleColors = [
                                    'admin' => 'border-purple-200 hover:bg-purple-50 has-[:checked]:bg-purple-50 has-[:checked]:border-purple-300',
                                    'chief_manager' => 'border-blue-200 hover:bg-blue-50 has-[:checked]:bg-blue-50 has-[:checked]:border-blue-300',
                                    'manager' => 'border-cyan-200 hover:bg-cyan-50 has-[:checked]:bg-cyan-50 has-[:checked]:border-cyan-300',
                                    'agent' => 'border-emerald-200 hover:bg-emerald-50 has-[:checked]:bg-emerald-50 has-[:checked]:border-emerald-300',
                                ];
                            @endphp
                            <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-colors {{ $roleColors[$r->name] ?? 'border-gray-200 hover:bg-gray-50' }}">
                                <input type="checkbox" value="{{ $r->name }}" wire:model="roles"
                                       class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                <div>
                                    <span class="text-sm font-medium text-gray-900">{{ $r->title_ru ?: $r->name }}</span>
                                    <span class="text-xs text-gray-500 ml-1">({{ $r->name }})</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center gap-3">
                <button wire:click="save"
                        class="inline-flex items-center gap-2 bg-primary-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold
                               hover:bg-primary-700 transition-colors shadow-sm shadow-primary-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    Сохранить
                </button>

                @if($user)
                    <button onclick="confirmAction({
                                    type: 'warning',
                                    title: 'Сброс пароля',
                                    message: 'Сбросить пароль? Новый пароль будет показан один раз.',
                                    confirmText: 'Сбросить',
                                    onConfirm: function() { @this.call('resetPassword'); }
                                })"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-700 border border-gray-200 hover:bg-white transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                        Сбросить пароль
                    </button>

                    <button onclick="confirmAction({
                                    type: 'danger',
                                    title: 'Удаление пользователя',
                                    message: 'Точно удалить пользователя {{ $user->name }}? Это действие нельзя отменить.',
                                    confirmText: 'Удалить',
                                    onConfirm: function() { @this.call('deleteUser'); }
                                })"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 transition-colors ml-auto">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                        </svg>
                        Удалить
                    </button>
                @endif

                <a href="{{ route('users.index') }}" class="px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">Отмена</a>
            </div>
        </div>

        @if(!$user)
            <p class="text-xs text-gray-400 mt-3 px-1">При создании пароль генерируется автоматически и будет показан один раз.</p>
        @endif
    </div>
</div>
