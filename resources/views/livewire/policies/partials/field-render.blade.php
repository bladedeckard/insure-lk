{{-- 
    field-render.blade.php — с условной видимостью (Гибрид A + B)
    
    Принимает: $field, $product, $data, $visibilityMap (опционально)
    
    $visibilityMap — массив: [field_code => {coverage_codes: [...], coverage_types: {...}, condition: {...}}]
--}}
@php
    $isStartDateField = ($field["code"] === 'start_date');
    $dateMin = null;
    $dateDisabled = false;

    if ($isStartDateField && isset($product)) {
        $dateMin = now()->addDays($product->period_start_days ?? 0)->format('Y-m-d');
        if (!$product->allow_edit_start_date) {
            $dateDisabled = true;
        }
    }

    // Visibility check
    $visMap = $visibilityMap ?? [];
    $fieldVis = $visMap[$field['code']] ?? null;
    $hasVisibility = !empty($fieldVis);
@endphp

{{-- Обёртка с условной видимостью --}}
<div
    @if($hasVisibility)
        x-data="fieldVisibility('{{ $field['code'] }}', {{ Js::from($fieldVis) }})"
        x-show="visible"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-1"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    @endif
    class="{{ in_array($field['type'], ['textarea', 'address']) ? 'md:col-span-2' : '' }}"
    wire:key="field-{{ $field['code'] }}"
>

@switch($field["type"])
    @case('text')
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
        </label>
        <input type="text" wire:model.defer="data.{{ $field['code'] }}"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
            @if($field["mask"] ?? false) data-mask="{{ $field['mask'] }}" @endif
            placeholder="{{ $field['hint'] ?? '' }}">
        @break

    @case('number')
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
        </label>
        <input type="number" wire:model.defer="data.{{ $field['code'] }}"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="{{ $field['hint'] ?? '0' }}">
        @break

    @case('date')
        @if($dateDisabled)
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
            </label>
            <input type="date" wire:model.defer="data.{{ $field['code'] }}" disabled
                class="w-full border-gray-300 rounded-lg bg-gray-100 shadow-sm">
            <p class="text-xs text-orange-600 mt-1">Дата устанавливается автоматически</p>
        @else
            @php $dpValue = $data[$field["code"]] ?? ''; @endphp
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
            </label>
            <input type="date" wire:model.defer="data.{{ $field['code'] }}"
                @if($dateMin) min="{{ $dateMin }}" @endif
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @endif
        @break

    @case('select')
        @php
            $selectOptions = $field["options"] ?? [];
            if(($field["code"] ?? '') === 'bank' && isset($banks) && $banks->isNotEmpty()) {
                $selectOptions = $banks->map(fn($b) => ['value' => $b->code, 'label' => $b->name])->toArray();
            }
        @endphp
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
        </label>
        <select wire:model.defer="data.{{ $field['code'] }}"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">— выберите —</option>
            @foreach($selectOptions as $opt)
                <option value="{{ $opt['value'] ?? '' }}">{{ $opt['label'] ?? '' }}</option>
            @endforeach
        </select>
        @break

    @case('checkbox')
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" wire:model.defer="data.{{ $field['code'] }}"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            <span class="text-sm font-medium text-gray-700">
                {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
            </span>
        </label>
        @break

    @case('phone')
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
        </label>
        <input type="tel" wire:model.defer="data.{{ $field['code'] }}"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="+7 (___) ___-__-__">
        @break

    @case('email')
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
        </label>
        <input type="email" wire:model.defer="data.{{ $field['code'] }}"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="email@example.com">
        @break

    @case('passport_series')
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
        </label>
        <input type="text" wire:model.defer="data.{{ $field['code'] }}" maxlength="5"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="XX XX">
        <p class="text-xs text-gray-400 mt-1">Только цифры</p>
        @break

    @case('passport_number')
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
        </label>
        <input type="text" wire:model.defer="data.{{ $field['code'] }}" maxlength="6"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="XXXXXX">
        @break

    @case('birthdate')
        @php $bdValue = $data[$field["code"]] ?? ''; @endphp
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
        </label>
        <input type="date" wire:model.defer="data.{{ $field['code'] }}"
            max="{{ now()->subYears(18)->format('Y-m-d') }}"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @break

    @case('address')
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
        </label>
        <input type="text" wire:model.defer="data.{{ $field['code'] }}"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="г. Москва, ул. ..., д. ..., кв. ...">
        @break

    @case('textarea')
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
        </label>
        <textarea wire:model.defer="data.{{ $field['code'] }}" rows="3"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="{{ $field['hint'] ?? '' }}"></textarea>
        @break

    @case('file')
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
        </label>
        <input type="file" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        @break

    @case('linked_field')
        <label class="flex items-center gap-2 cursor-pointer text-sm mb-2">
            <input type="checkbox"
                class="rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                x-data
                x-on:change="
                    let src = document.querySelector('[wire\\:model\\.defer=&quot;data.{{ $field['linked_to'] }}&quot;]');
                    let tgt = document.querySelector('[wire\\:model\\.defer=&quot;data.{{ $field['code'] }}&quot;]');
                    if ($event.target.checked && src && tgt) {
                        tgt.value = src.value;
                        tgt.dispatchEvent(new Event('input'));
                    }
                ">
            <span class="text-purple-700 font-medium">Совпадает с «{{ $field["linked_to"] }}»</span>
        </label>
        <input type="text" wire:model.defer="data.{{ $field['code'] }}"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="{{ $field['hint'] ?? '' }}">
        @break

    @default
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
        </label>
        <input type="text" wire:model.defer="data.{{ $field['code'] }}"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="{{ $field['hint'] ?? '' }}">
@endswitch

@error('data.'.$field["code"])
    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
@enderror

</div>
