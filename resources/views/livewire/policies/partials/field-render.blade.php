{{-- Динамический рендер поля на основе его типа --}}
<div class="{{ in_array($field->type, ['textarea', 'address']) ? 'md:col-span-2' : '' }}">
    <label class="block text-sm font-medium text-gray-700 mb-1">
        {{ $field->name }}
        @if($field->required)
            <span class="text-red-500">*</span>
        @endif
        @if($field->hint)
            <span class="text-xs text-gray-400 font-normal" title="{{ $field->hint }}">💬</span>
        @endif
    </label>

    @if($field->description)
        <p class="text-xs text-gray-500 mb-1">{{ $field->description }}</p>
    @endif

    @switch($field->type)
        @case('text')
            <input type="text"
                wire:model.defer="data.{{ $field->code }}"
                @if($field->mask) data-mask="{{ $field->mask }}" @endif
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="{{ $field->hint ?? '' }}">
            @break

        @case('number')
            <input type="number"
                wire:model.live.debounce.500ms="data.{{ $field->code }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="{{ $field->hint ?? '0' }}">
            @break

        @case('date')
            <input type="date"
                wire:model.live="data.{{ $field->code }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            @break

        @case('select')
            <select wire:model.live="data.{{ $field->code }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                <option value="">— выберите —</option>
                @foreach($field->options ?? [] as $opt)
                    <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                @endforeach
            </select>
            @break

        @case('checkbox')
            <label class="flex items-center gap-2 cursor-pointer mt-1">
                <input type="checkbox"
                    wire:model.live="data.{{ $field->code }}"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="text-sm text-gray-700">Да</span>
            </label>
            @break

        @case('phone')
            <input type="tel"
                wire:model.defer="data.{{ $field->code }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="+7 (___) ___-__-__">
            @break

        @case('email')
            <input type="email"
                wire:model.defer="data.{{ $field->code }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="email@example.com">
            @break

        @case('passport_series')
            <input type="text"
                wire:model.defer="data.{{ $field->code }}"
                maxlength="5"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="XX XX">
            <p class="text-xs text-gray-400 mt-1">Формат: XX XX (последние 2 цифры ≤ {{ date('y') }})</p>
            @break

        @case('passport_number')
            <input type="text"
                wire:model.defer="data.{{ $field->code }}"
                maxlength="6"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="XXXXXX">
            @break

        @case('birthdate')
            <input type="date"
                wire:model.live="data.{{ $field->code }}"
                max="{{ now()->subYears(18)->format('Y-m-d') }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            @break

        @case('address')
            <input type="text"
                wire:model.defer="data.{{ $field->code }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="г. Москва, ул. ..., д. ..., кв. ...">
            @break

        @case('textarea')
            <textarea wire:model.defer="data.{{ $field->code }}"
                rows="3"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="{{ $field->hint ?? '' }}"></textarea>
            @break

        @case('file')
            <input type="file"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-500">
            @break

        @case('linked_field')
            <div class="space-y-2">
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input type="checkbox"
                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                        data-linked="{{ $field->linked_to }}"
                        data-target="{{ $field->code }}"
                        onchange="if(this.checked){ document.querySelector('[wire\\:model\\.defer=&quot;data.{{ $field->code }}&quot;]').value = document.querySelector('[wire\\:model\\.defer=&quot;data.{{ $field->linked_to }}&quot;]')?.value || ''; document.querySelector('[wire\\:model\\.defer=&quot;data.{{ $field->code }}&quot;]').dispatchEvent(new Event('input')); }">
                    <span class="text-purple-700">↔ Совпадает с «{{ $field->linked_to }}»</span>
                </label>
                <input type="text"
                    wire:model.defer="data.{{ $field->code }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="{{ $field->hint ?? '' }}">
            </div>
            @break

        @default
            <input type="text"
                wire:model.defer="data.{{ $field->code }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="{{ $field->hint ?? '' }}">
    @endswitch

    @if($field->error_message)
        <p class="text-xs text-gray-400 mt-1">{{ $field->error_message }}</p>
    @endif
</div>
