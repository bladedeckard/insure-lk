<div class="space-y-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Основная информация</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Название --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Название продукта *</label>
            <input type="text" wire:model.defer="name"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Страхование квартиры «Страху.Нет»">
            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Маркетинговое название --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Маркетинговое название</label>
            <input type="text" wire:model.defer="marketing_name"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Страху.Нет">
        </div>

        {{-- Код --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Код продукта *</label>
            <input type="text" wire:model.defer="code"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="strahu_net">
            @error('code') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Валюта --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Валюта расчётов *</label>
            <select wire:model="currency"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @foreach ($currencies as $code => $label)
                    <option value="{{ $code }}">{{ $label }} ({{ $code }})</option>
                @endforeach
            </select>
        </div>

        {{-- Тип продукта --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Тип продукта</label>
            <select wire:model="product_type_id"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">— не выбран —</option>
                @foreach ($productTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
            <p class="text-xs text-gray-400 mt-1">Определяет калькулятор и настройки расчёта</p>
        </div>
    </div>

    {{-- Описание --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Описание продукта</label>
        <textarea wire:model.defer="description" rows="3"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Краткое описание продукта..."></textarea>
    </div>

    {{-- Посредники --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Посредники
            <span class="text-gray-400 text-sm font-normal">(если пусто — продукт для прямых продаж)</span>
        </label>
        <div class="border border-gray-300 rounded-lg p-4 max-h-48 overflow-y-auto">
            @forelse ($intermediaries as $intermediary)
                <label class="flex items-center gap-2 py-1 cursor-pointer hover:bg-gray-50 px-2 rounded">
                    <input type="checkbox"
                        value="{{ $intermediary->id }}"
                        wire:model.defer="selectedIntermediaries"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm">{{ $intermediary->name }}</span>
                    <span class="text-xs text-gray-400">({{ $intermediary->inn ?? 'без ИНН' }})</span>
                </label>
            @empty
                <p class="text-gray-400 text-sm">Нет активных посредников</p>
            @endforelse
        </div>
        <p class="text-xs text-gray-500 mt-1">
            Даже если выбран посредник, прямые продажи также доступны
        </p>
    </div>
</div>
