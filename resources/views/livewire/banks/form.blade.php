<div>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
        <a href="{{ route('banks.index') }}" class="hover:text-primary-600">Банки</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        <span class="text-gray-900">{{ $bank ? 'Редактирование' : 'Новый банк' }}</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ $bank ? 'Редактирование банка' : 'Новый банк' }}</h1>

    <form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Основная информация</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Название *</label>
                            <input type="text" wire:model="name" placeholder="Сбербанк">
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Код *</label>
                            <input type="text" wire:model="code" placeholder="sber">
                            @error('code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Параметры</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">КВ банка (%)</label>
                            <input type="number" wire:model="commission" min="0" max="100" step="0.1">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Коэфф. ОСЗ</label>
                            <input type="number" wire:model="osg_coeff" min="0" max="10" step="0.01">
                        </div>
                        <div class="flex flex-col gap-3 pt-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="constructive" class="rounded">
                                <span class="text-sm text-gray-700">Конструктивные элементы</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="title_disabled" class="rounded">
                                <span class="text-sm text-gray-700">Титул недоступен</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="rounded">
                                <span class="text-sm text-gray-700">Активен</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Коэффициенты банка</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Базовый коэффициент банка *</label>
                            <input type="number" wire:model="base_coefficient" min="0" max="10" step="0.001">
                            @error('base_coefficient') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Коэффициент конструктив *</label>
                            <input type="number" wire:model="constructive_coefficient" min="0" max="1" step="0.0001">
                            @error('constructive_coefficient') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Тариф банка</label>
                            <input type="text" value="{{ number_format($tariff_bank * 100, 2) }}%" disabled class="bg-gray-50">
                            <p class="text-xs text-gray-400 mt-1">Автоматически = Базовый × Конструктив</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Коэффициент банка (имущество)</label>
                            <input type="text" value="{{ number_format($bank_coefficient_property, 2) }}" disabled class="bg-gray-50">
                            <p class="text-xs text-gray-400 mt-1">Автоматически = 1/(0.17% / Тариф)</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <button type="submit" class="w-full bg-primary-600 text-white py-2.5 rounded-xl text-sm font-medium hover:bg-primary-700 transition-colors">
                        {{ $bank ? 'Сохранить' : 'Создать' }}
                    </button>
                    <a href="{{ route('banks.index') }}" class="block text-center mt-3 text-sm text-gray-500 hover:text-gray-700">Отмена</a>
                </div>
            </div>
        </div>
    </form>
</div>
