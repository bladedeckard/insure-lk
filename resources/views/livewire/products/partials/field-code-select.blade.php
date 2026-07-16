{{-- [4] Поле выбора field_code с datalist для автодополнения --}}
@php
    // Собираем все доступные поля для выбора
    $allFieldOptions = [];
    
    // Поля продукта
    foreach ($allFields ?? [] as $f) {
        $label = $f['name'] . ' (' . $f['code'] . ')';
        $allFieldOptions[] = ['value' => $f['code'], 'label' => $label];
    }
    
    // Покрытия продукта
    foreach ($allCoverages ?? [] as $c) {
        if (!empty($c['code'])) {
            $allFieldOptions[] = ['value' => $c['code'], 'label' => $c['name'] . ' (' . $c['code'] . ')'];
        }
    }
    
    // Вычисляемые поля
    $allFieldOptions[] = ['value' => 'age', 'label' => 'Возраст (вычисляемый)'];
    $allFieldOptions[] = ['value' => 'sum_insured', 'label' => 'Общая страховая сумма'];
    
    $datalistId = 'field_codes_' . ($inputName ?? uniqid());
@endphp

<input type="text"
    list="{{ $datalistId }}"
    wire:model.defer="{{ $inputName }}"
    class="{{ $inputClass ?? 'w-36 border border-gray-300 rounded px-2 py-1 text-sm' }}"
    placeholder="Поле..."
    autocomplete="off">
<datalist id="{{ $datalistId }}">
    <optgroup label="Поля формы">
        @foreach($allFieldOptions as $opt)
            <option value="{{ $opt['value'] }}" label="{{ $opt['label'] }}"></option>
        @endforeach
    </optgroup>
</datalist>
