<div>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
        <a href="{{ route('product-types.index') }}" class="hover:text-primary-600">Типы продуктов</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        <span class="text-gray-900">{{ $type ? 'Редактирование' : 'Новый тип' }}</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ $type ? 'Редактирование типа продукта' : 'Новый тип продукта' }}</h1>

    <form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Основная информация</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Код *</label>
                            <input type="text" wire:model="code" placeholder="mortgage">
                            @error('code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Название *</label>
                            <input type="text" wire:model="name" placeholder="Ипотечное страхование">
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Описание</label>
                            <textarea wire:model="description" rows="2"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Калькулятор *</label>
                            <select wire:model="calculator_class">
                                <option value="App\Services\ProductCalculators\FormulaBasedCalculator">Формульный</option>
                                <option value="App\Services\ProductCalculators\MortgageCalculator">Ипотечный</option>
                                <option value="App\Services\ProductCalculators\PropertyCalculator">Имущество</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2 pt-6">
                            <input type="checkbox" wire:model="is_active" class="rounded">
                            <label class="text-sm text-gray-700">Активен</label>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Настройки расчёта</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Макс. нагрузка (%)</label>
                            <input type="number" wire:model="max_load_percent" min="0" max="100" step="0.1">
                        </div>
                        <div class="flex items-center gap-2 pt-6">
                            <input type="checkbox" wire:model="requires_bank" class="rounded">
                            <label class="text-sm text-gray-700">Требуется выбор банка</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" wire:model="title_requires_property" class="rounded">
                            <label class="text-sm text-gray-700">Титул только с имуществом</label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Банки без титула (через запятую)</label>
                            <input type="text" wire:model="title_disabled_banks" placeholder="sber">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Порог согласования Жизнь (₽)</label>
                            <input type="number" wire:model="approval_life_threshold">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Порог согласования Имущество (₽)</label>
                            <input type="number" wire:model="approval_property_threshold">
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <button type="submit" class="w-full bg-primary-600 text-white py-2.5 rounded-xl text-sm font-medium hover:bg-primary-700 transition-colors">
                        {{ $type ? 'Сохранить' : 'Создать' }}
                    </button>
                    <a href="{{ route('product-types.index') }}" class="block text-center mt-3 text-sm text-gray-500 hover:text-gray-700">Отмена</a>
                </div>
            </div>
        </div>
    </form>
</div>
