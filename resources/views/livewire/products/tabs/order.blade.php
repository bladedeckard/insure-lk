<div class="space-y-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Настройка заказа</h2>

    {{-- Нумератор --}}
    <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Нумератор полисов</h3>
        <select wire:model.defer="numerator_id"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            <option value="">— Выберите нумератор —</option>
            @foreach ($numerators as $num)
                <option value="{{ $num->id }}">{{ $num->name }} ({{ $num->prefix }}...)</option>
            @endforeach
        </select>
    </div>

    {{-- Период действия --}}
    <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Период действия договора</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Дата начала: Сегодня +</label>
                <div class="flex items-center gap-2">
                    <input type="number" wire:model.defer="period_start_days" min="0"
                        class="w-24 border border-gray-300 rounded px-3 py-2 text-sm">
                    <span class="text-sm text-gray-600">дней</span>
                </div>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Дата окончания: Дата начала +</label>
                <div class="flex items-center gap-2">
                    <input type="number" wire:model.defer="period_end_value" min="1"
                        class="w-24 border border-gray-300 rounded px-3 py-2 text-sm">
                    <select wire:model="period_end_unit"
                        class="border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="days">дней</option>
                        <option value="years">лет</option>
                    </select>
                </div>
            </div>
            <div class="flex items-end">
                <div class="text-sm text-gray-500 bg-white px-3 py-2 rounded border">
                    Пример: сегодня + {{ $period_start_days }} дн. → + {{ $period_end_value }} {{ $period_end_unit === 'years' ? 'год(а)/лет' : 'дн.' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Ограничения на заказ --}}
    <div>
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-sm font-semibold text-gray-700">Ограничения на заказ</h3>
            <button wire:click="addOrderRestriction" class="px-3 py-1 bg-red-100 text-red-700 rounded text-sm hover:bg-red-200">
                + Добавить ограничение
            </button>
        </div>

        <p class="text-xs text-gray-500 mb-4">
            Условия, при которых оформление полиса запрещено. Например: регион не Москва, возраст меньше 18 лет.
        </p>

        @if(empty($orderRestrictions))
            <p class="text-gray-400 text-sm text-center py-6">Нет ограничений на заказ</p>
        @else
            <div class="space-y-4">
                @foreach($orderRestrictions as $rIndex => $restriction)
                    <div class="border border-red-200 bg-red-50 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1 space-y-2">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-xs text-gray-500">Сообщение об ошибке</label>
                                        <input type="text" 
                                            wire:model.defer="orderRestrictions.{{ $rIndex }}.error_message"
                                            class="w-full border border-gray-300 rounded px-2 py-1 text-sm"
                                            placeholder="Оформление запрещено: возраст менее 18 лет">
                                    </div>
                                    <div class="flex gap-3">
                                        <div>
                                            <label class="text-xs text-gray-500">Действие</label>
                                            <select wire:model.defer="orderRestrictions.{{ $rIndex }}.action"
                                                class="border border-gray-300 rounded px-2 py-1 text-sm">
                                                <option value="block">Блокировать</option>
                                                <option value="approval">На согласование</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Логика</label>
                                            <select wire:model.defer="orderRestrictions.{{ $rIndex }}.logic"
                                                class="border border-gray-300 rounded px-2 py-1 text-sm">
                                                <option value="and">И (все условия)</option>
                                                <option value="or">ИЛИ (любое условие)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button wire:click="removeOrderRestriction({{ $rIndex }})"
                                class="ml-3 text-red-500 hover:text-red-700 text-sm">Удалить</button>
                        </div>

                        {{-- Условия --}}
                        <div class="space-y-2">
                            @foreach($restriction['conditions'] as $cIndex => $condition)
                                <div class="flex items-center gap-2 bg-white rounded p-2">
                                    @include('livewire.products.partials.field-code-select', [
                                        'inputName' => 'orderRestrictions.' . $rIndex . '.conditions.' . $cIndex . '.field_code',
                                        'allFields' => $fields ?? [],
                                        'allCoverages' => $coverages ?? [],
                                    ])
                                    <select wire:model.defer="orderRestrictions.{{ $rIndex }}.conditions.{{ $cIndex }}.operator"
                                        class="w-32 border border-gray-300 rounded px-2 py-1 text-sm">
                                        @foreach($operators as $op => $label)
                                            <option value="{{ $op }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text"
                                        wire:model.defer="orderRestrictions.{{ $rIndex }}.conditions.{{ $cIndex }}.value"
                                        class="flex-1 border border-gray-300 rounded px-2 py-1 text-sm"
                                        placeholder="Значение">
                                    <button wire:click="removeOrderCondition({{ $rIndex }}, {{ $cIndex }})"
                                        class="text-red-400 hover:text-red-600">✕</button>
                                </div>
                            @endforeach
                            <button wire:click="addOrderCondition({{ $rIndex }})"
                                class="text-sm text-blue-600 hover:text-blue-800">+ Добавить условие</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
