@php
    $visMap = $visibilityMap ?? [];
    $fieldVis = $visMap[$field->code ?? $field['code'] ?? ''] ?? null;
    $hasVisibility = !empty($fieldVis);
@endphp
<div
    @if($hasVisibility)
        x-data="fieldVisibility('{{ $field->code ?? $field['code'] }}', {!! json_encode($fieldVis, JSON_UNESCAPED_UNICODE) !!})"
        x-show="visible"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    @endif
    wire:key="field-{{ $field->code ?? $field['code'] }}"
></div>

@php
    $isStartDateField = ($field['code'] === 'start_date');
    $dateMin = null;
    $dateDisabled = false;

    if ($isStartDateField && isset($product)) {
        $dateMin = now()->addDays($product->period_start_days ?? 0)->format('Y-m-d');
        if (!$product->allow_edit_start_date) {
            $dateDisabled = true;
        }
    }
@endphp

<div class="{{ in_array($field['type'], ['textarea', 'address']) ? 'md:col-span-2' : '' }}">
    @switch($field['type'])
        @case('text')
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field['name'] }} @if($field['required'])<span class="text-red-500">*</span>@endif
                </label>
                <input type="text"
                    wire:model.defer="data.{{ $field['code'] }}"
                    @if(!empty($field['mask'])) data-mask="{{ $field['mask'] }}" @endif
                    placeholder="{{ $field['hint'] ?? '' }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            </div>
            @break

        @case('number')
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field['name'] }} @if($field['required'])<span class="text-red-500">*</span>@endif
                </label>
                <div class="relative">
                    <input type="number"
                        wire:model.live.debounce.500ms="data.{{ $field['code'] }}"
                        placeholder="{{ $field['hint'] ?? '0' }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>
            </div>
            @break

        @case('date')
            @if($dateDisabled)
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">
                        {{ $field['name'] }} @if($field['required'])<span class="text-red-500">*</span>@endif
                    </label>
                    <input type="text" disabled
                        value="{{ now()->addDays($product->period_start_days ?? 0)->format('d.m.Y') }}"
                        class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-slate-500">
                    <p class="text-xs text-orange-600">Дата устанавливается автоматически</p>
                </div>
            @else
                <x-custom-datepicker
                    name="data.{{ $field['code'] }}"
                    label="{{ $field['name'] }}"
                    value="{{ $this->data[$field['code']] ?? '' }}"
                    minDate="{{ $dateMin }}"
                    :required="$field['required']"
                />
            @endif
            @break

        @case('select')
            @php
                $selectOptions = $field['options'] ?? [];
                if(($field['code'] ?? '') === 'bank' && isset($banks) && $banks->isNotEmpty()) {
                    $selectOptions = $banks->map(fn($b) => ['value' => $b->code, 'label' => $b->name])->toArray();
                }
            @endphp
            <x-custom-select
                name="data.{{ $field['code'] }}"
                label="{{ $field['name'] }}"
                :options="$selectOptions"
                placeholder="— выберите —"
                :required="$field['required']"
            />
            @break

        @case('checkbox')
            <x-custom-checkbox
                name="data.{{ $field['code'] }}"
                label="{{ $field['name'] }}"
                description="Включить в расчёт"
            />
            @break

        @case('phone')
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field['name'] }} @if($field['required'])<span class="text-red-500">*</span>@endif
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <input type="tel"
                        wire:model.defer="data.{{ $field['code'] }}"
                        placeholder="+7 (___) ___-__-__"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>
            </div>
            @break

        @case('email')
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field['name'] }} @if($field['required'])<span class="text-red-500">*</span>@endif
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input type="email"
                        wire:model.defer="data.{{ $field['code'] }}"
                        placeholder="name@example.com"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>
            </div>
            @break

        @case('passport_series')
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field['name'] }} @if($field['required'])<span class="text-red-500">*</span>@endif
                </label>
                <input type="text"
                    wire:model.defer="data.{{ $field['code'] }}"
                    maxlength="5"
                    placeholder="XX XX"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                <p class="text-xs text-slate-400 pl-1">Только цифры</p>
            </div>
            @break

        @case('passport_number')
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field['name'] }} @if($field['required'])<span class="text-red-500">*</span>@endif
                </label>
                <input type="text"
                    wire:model.defer="data.{{ $field['code'] }}"
                    maxlength="6"
                    placeholder="XXXXXX"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            </div>
            @break

        @case('birthdate')
            <x-custom-datepicker
                name="data.{{ $field['code'] }}"
                label="{{ $field['name'] }}"
                value="{{ $this->data[$field['code']] ?? '' }}"
                maxDate="{{ now()->subYears(18)->format('Y-m-d') }}"
                :required="$field['required']"
            />
            @break

        @case('address')
            <div class="md:col-span-2 space-y-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field['name'] }} @if($field['required'])<span class="text-red-500">*</span>@endif
                </label>
                <div class="relative" wire:ignore.self>
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <input type="text"
                        wire:model.live.debounce.300ms="data.{{ $field['code'] }}"
                        placeholder="г. Москва, ул. ..., д. ..., кв. ..."
                        autocomplete="off"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>
                @if(!empty($addressSuggestions) && $field['code'] === 'property_address')
                    <div class="absolute z-10 w-full bg-white border border-slate-200 rounded-xl shadow-xl mt-1 max-h-60 overflow-y-auto">
                        @foreach($addressSuggestions as $suggestion)
                            <button type="button"
                                wire:click="selectAddress({{ json_encode($suggestion) }})"
                                class="w-full text-left px-4 py-3 hover:bg-indigo-50 transition-colors border-b border-slate-50 last:border-0">
                                <span class="font-medium text-slate-800">{{ $suggestion['value'] ?? '' }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
            @break

        @case('textarea')
            <div class="space-y-2 md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field['name'] }} @if($field['required'])<span class="text-red-500">*</span>@endif
                </label>
                <textarea
                    wire:model.defer="data.{{ $field['code'] }}"
                    rows="3"
                    placeholder="{{ $field['hint'] ?? '' }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none"></textarea>
            </div>
            @break

        @case('file')
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field['name'] }} @if($field['required'])<span class="text-red-500">*</span>@endif
                </label>
                <input type="file"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-500">
            </div>
            @break

        @case('linked_field')
            <div class="space-y-2">
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input type="checkbox"
                        class="el-checkbox linked-field-toggle"
                        data-source="{{ $field['linked_to'] }}"
                        data-target="{{ $field['code'] }}">
                    <span class="text-slate-700">Совпадает с «{{ $field['linked_to'] }}»</span>
                </label>
                <input type="text"
                    wire:model.defer="data.{{ $field['code'] }}"
                    placeholder="{{ $field['hint'] ?? '' }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            </div>
            @break

        @default
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field['name'] }} @if($field['required'])<span class="text-red-500">*</span>@endif
                </label>
                <input type="text"
                    wire:model.defer="data.{{ $field['code'] }}"
                    placeholder="{{ $field['hint'] ?? '' }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            </div>
    @endswitch

    @error('data.'.$field['code'])
        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>
</div>