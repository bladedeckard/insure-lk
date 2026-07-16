{{-- [4] Поле выбора field_code с выпадающим списком --}}
@php
    $allFieldOptions = [];
    
    // Поля продукта
    foreach ($allFields ?? [] as $f) {
        $allFieldOptions[] = [
            'value' => $f['code'] ?? '',
            'label' => ($f['name'] ?? '') . ' — ' . ($f['code'] ?? ''),
        ];
    }
    
    // Покрытия продукта
    foreach ($allCoverages ?? [] as $c) {
        if (!empty($c['code'])) {
            $allFieldOptions[] = [
                'value' => $c['code'],
                'label' => ($c['name'] ?? '') . ' — ' . $c['code'],
            ];
        }
    }
    
    // Вычисляемые поля
    $allFieldOptions[] = ['value' => 'age', 'label' => 'Возраст (вычисляемый) — age'];
    $allFieldOptions[] = ['value' => 'sum_insured', 'label' => 'Общая страховая сумма — sum_insured'];
    
    $selectId = 'fc_' . substr(md5($inputName . microtime()), 0, 8);
    
    // Текущее значение
    $currentValue = '';
    // Пытаемся получить текущее значение из wire:model
    $parts = explode('.', $inputName);
    // Не пытаемся резолвить — просто покажем все options
@endphp

<div class="relative">
    <select
        wire:model.defer="{{ $inputName }}"
        class="{{ $inputClass ?? 'w-40 border border-gray-300 rounded px-2 py-1 text-sm' }}"
        id="{{ $selectId }}">
        <option value="">— Выберите поле —</option>
        @if(!empty($allFieldOptions))
            <optgroup label="Поля формы ({{ count($allFieldOptions) }})">
                @foreach($allFieldOptions as $opt)
                    <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                @endforeach
            </optgroup>
        @endif
    </select>
    @if(empty($allFieldOptions))
        <p class="text-xs text-red-400 mt-1">Нет доступных полей — добавьте поля на вкладке «Настройка полей»</p>
    @endif
</div>
