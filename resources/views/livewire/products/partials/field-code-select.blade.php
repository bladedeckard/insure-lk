{{-- [4] Поле выбора field_code с datalist для автодополнения --}}
@php
    $allFieldOptions = [];
    
    // Поля продукта
    foreach ($allFields ?? [] as $f) {
        $allFieldOptions[] = [
            'value' => $f['code'],
            'label' => $f['name'] . ' [' . $f['code'] . ']',
        ];
    }
    
    // Покрытия продукта
    foreach ($allCoverages ?? [] as $c) {
        if (!empty($c['code'])) {
            $allFieldOptions[] = [
                'value' => $c['code'],
                'label' => $c['name'] . ' [' . $c['code'] . ']',
            ];
        }
    }
    
    // Вычисляемые поля
    $allFieldOptions[] = ['value' => 'age', 'label' => 'Возраст (вычисляемый) [age]'];
    $allFieldOptions[] = ['value' => 'sum_insured', 'label' => 'Общая страховая сумма [sum_insured]'];
    
    $datalistId = 'fc_' . md5($inputName . rand(1000, 9999));
@endphp

<input type="text"
    list="{{ $datalistId }}"
    wire:model.defer="{{ $inputName }}"
    class="{{ $inputClass ?? 'w-36 border border-gray-300 rounded px-2 py-1 text-sm' }}"
    placeholder="Начните вводить..."
    autocomplete="off">
<datalist id="{{ $datalistId }}">
    @foreach($allFieldOptions as $opt)
        <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
    @endforeach
</datalist>
