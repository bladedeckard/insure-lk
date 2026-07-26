<div>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
        <a href="{{ route('promocodes.index') }}" class="hover:text-primary-600">Промокоды</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        <span class="text-gray-900">{{ $promo ? 'Редактирование' : 'Новый промокод' }}</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ $promo ? 'Редактирование промокода' : 'Новый промокод' }}</h1>

    <form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Параметры промокода</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Код промокода *</label>
                            <input type="text" wire:model="code" placeholder="NEW10">
                            @error('code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Скидка (%) *</label>
                            <input type="number" wire:model="discount_percent" min="0" max="100" step="0.1">
                            @error('discount_percent') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Продукт *</label>
                            <select wire:model="product_id">
                                <option value="">— выберите —</option>
                                @foreach($products as $pr)
                                    <option value="{{ $pr->id }}">{{ $pr->name }}</option>
                                @endforeach
                            </select>
                            @error('product_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex items-center gap-2 pt-6">
                            <input type="checkbox" wire:model="is_active" class="rounded">
                            <label class="text-sm text-gray-700">Активен</label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Действует с</label>
                            <input type="date" wire:model="valid_from">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Действует до</label>
                            <input type="date" wire:model="valid_to">
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <button type="submit" class="w-full bg-primary-600 text-white py-2.5 rounded-xl text-sm font-medium hover:bg-primary-700 transition-colors">
                        {{ $promo ? 'Сохранить' : 'Создать' }}
                    </button>
                    <a href="{{ route('promocodes.index') }}" class="block text-center mt-3 text-sm text-gray-500 hover:text-gray-700">Отмена</a>
                </div>
            </div>
        </div>
    </form>
</div>
