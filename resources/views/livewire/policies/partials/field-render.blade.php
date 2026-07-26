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
@endphp

<div class="{{ in_array($field["type"], ['textarea', 'address']) ? 'md:col-span-2' : '' }}">
    @switch($field["type"])
        @case('text')
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
                </label>
                <input type="text"
                    wire:model.defer="data.{{ $field["code"] }}"
                    @if(!empty($field["mask"])) data-mask="{{ $field["mask"] }}" @endif
                    placeholder="{{ $field["hint"] ?? '' }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            </div>
            @break

        @case('number')
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
                </label>
                <div class="relative">
                    <input type="number"
                        wire:model.live.debounce.500ms="data.{{ $field["code"] }}"
                        placeholder="{{ $field["hint"] ?? '0' }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>
            </div>
            @break

        @case('date')
            @if($dateDisabled)
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">
                        {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
                    </label>
                    <input type="text" disabled
                        value="{{ now()->addDays($product->period_start_days ?? 0)->format('d.m.Y') }}"
                        class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-slate-500">
                    <p class="text-xs text-orange-600">Дата устанавливается автоматически</p>
                </div>
            @else
                @php $dpValue = $data[$field["code"]] ?? ''; @endphp
                <div class="space-y-2 custom-datepicker-wrapper" data-name="data.{{ $field["code"] }}" data-min="{{ $dateMin }}" data-max="">
                    <label class="block text-sm font-semibold text-slate-700">
                        {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
                    </label>
                    <div class="relative">
                        <input type="text"
                            value="{{ $dpValue ? \Carbon\Carbon::parse($dpValue)->format('d.m.Y') : '' }}"
                            placeholder="дд.мм.гггг"
                            class="datepicker-input w-full bg-slate-50 border border-slate-200 hover:border-indigo-400 rounded-xl px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-200 transition-all"
                            oninput="handleDateInput(this)"
                            onkeydown="if(event.key==='Enter'){toggleDatepicker(this.parentElement.querySelector('button'));event.preventDefault();}">
                        <button type="button" onclick="toggleDatepicker(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </button>
                        <div class="datepicker-popup hidden absolute z-50 mt-2 bg-white rounded-2xl shadow-xl border border-slate-100 p-4 w-80">
                            <div class="flex items-center justify-between mb-4">
                                <button type="button" onclick="datepickerPrevYear(this)" class="px-2 py-1 hover:bg-slate-100 rounded text-slate-500 text-xs font-bold">&laquo;</button>
                                <button type="button" onclick="datepickerPrevMonth(this)" class="px-2 py-1 hover:bg-slate-100 rounded text-slate-500 text-sm font-bold">&lsaquo;</button>
                                <button type="button" onclick="datepickerToggleMonthPicker(this)" class="datepicker-month font-bold text-slate-800 hover:bg-slate-100 px-2 py-1 rounded-lg text-sm"></button>
                                <button type="button" onclick="datepickerNextMonth(this)" class="px-2 py-1 hover:bg-slate-100 rounded text-slate-500 text-sm font-bold">&rsaquo;</button>
                                <button type="button" onclick="datepickerNextYear(this)" class="px-2 py-1 hover:bg-slate-100 rounded text-slate-500 text-xs font-bold">&raquo;</button>
                            </div>
                            <div class="datepicker-monthpicker hidden grid grid-cols-3 gap-2 mb-4">
                                <button type="button" onclick="datepickerSelectMonth(this, 0)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Янв</button>
                                <button type="button" onclick="datepickerSelectMonth(this, 1)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Фев</button>
                                <button type="button" onclick="datepickerSelectMonth(this, 2)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Мар</button>
                                <button type="button" onclick="datepickerSelectMonth(this, 3)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Апр</button>
                                <button type="button" onclick="datepickerSelectMonth(this, 4)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Май</button>
                                <button type="button" onclick="datepickerSelectMonth(this, 5)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Июн</button>
                                <button type="button" onclick="datepickerSelectMonth(this, 6)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Июл</button>
                                <button type="button" onclick="datepickerSelectMonth(this, 7)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Авг</button>
                                <button type="button" onclick="datepickerSelectMonth(this, 8)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Сен</button>
                                <button type="button" onclick="datepickerSelectMonth(this, 9)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Окт</button>
                                <button type="button" onclick="datepickerSelectMonth(this, 10)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Ноя</button>
                                <button type="button" onclick="datepickerSelectMonth(this, 11)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Дек</button>
                            </div>
                            <div class="datepicker-yearpicker hidden grid grid-cols-3 gap-2 mb-4 max-h-48 overflow-y-auto"></div>
                            <div class="grid grid-cols-7 gap-1 mb-2">
                                <div class="text-center text-xs font-bold text-slate-400 py-1">Пн</div>
                                <div class="text-center text-xs font-bold text-slate-400 py-1">Вт</div>
                                <div class="text-center text-xs font-bold text-slate-400 py-1">Ср</div>
                                <div class="text-center text-xs font-bold text-slate-400 py-1">Чт</div>
                                <div class="text-center text-xs font-bold text-slate-400 py-1">Пт</div>
                                <div class="text-center text-xs font-bold text-slate-400 py-1">Сб</div>
                                <div class="text-center text-xs font-bold text-slate-400 py-1">Вс</div>
                            </div>
                            <div class="datepicker-days grid grid-cols-7 gap-1"></div>
                        </div>
                    </div>
                    <input type="hidden" wire:model.live="data.{{ $field["code"] }}" class="datepicker-hidden">
                </div>
            @endif
            @break

        @case('select')
            @php
                $selectOptions = $field["options"] ?? [];
                if(($field["code"] ?? '') === 'bank' && isset($banks) && $banks->isNotEmpty()) {
                    $selectOptions = $banks->map(fn($b) => ['value' => $b->code, 'label' => $b->name])->toArray();
                }
                $currentValue = $data[$field["code"]] ?? '';
                $selectedLabel = '';
                foreach($selectOptions as $opt) {
                    if(($opt['value'] ?? '') === $currentValue) { $selectedLabel = $opt['label'] ?? ''; break; }
                }
            @endphp
            <div class="space-y-2 custom-select-wrapper" data-name="data.{{ $field["code"] }}">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
                </label>
                <div class="relative">
                    <button type="button" onclick="toggleCustomSelect(this)" class="w-full bg-slate-50 hover:bg-white border border-slate-200 hover:border-indigo-400 text-left rounded-xl px-4 py-3 flex items-center justify-between transition-all focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <span class="custom-select-label {{ $selectedLabel ? 'text-slate-800 font-medium' : 'text-slate-400' }}">{{ $selectedLabel ?: '— выберите —' }}</span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="custom-select-dropdown hidden absolute z-50 w-full mt-2 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden">
                        @if(count($selectOptions) > 5)
                            <div class="p-2 border-b border-slate-100">
                                <input type="text" oninput="filterCustomSelect(this)" placeholder="Поиск..." class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-200">
                            </div>
                        @endif
                        <div class="max-h-60 overflow-y-auto custom-select-options">
                            @foreach($selectOptions as $opt)
                                <div onclick="selectCustomOption(this, '{{ $opt['value'] }}', '{{ addslashes($opt['label'] ?? '') }}')" data-value="{{ $opt['value'] }}" class="custom-select-option px-4 py-3 cursor-pointer hover:bg-indigo-50 transition-colors flex flex-col border-b border-slate-50 last:border-0">
                                    <span class="font-medium text-slate-800">{{ $opt['label'] ?? '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" wire:model.live="data.{{ $field["code"] }}" class="custom-select-hidden">
                </div>
            </div>
            @break

        @case('checkbox')
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
                </label>
                <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:border-indigo-300 hover:bg-indigo-50/30 transition-all">
                    <input type="checkbox" wire:model.live="data.{{ $field["code"] }}" class="el-checkbox">
                    <span class="text-sm text-slate-600">Включить в расчёт</span>
                </label>
            </div>
            @break

        @case('phone')
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <input type="tel"
                        wire:model.defer="data.{{ $field["code"] }}"
                        placeholder="+7 (___) ___-__-__"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>
            </div>
            @break

        @case('email')
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input type="email"
                        wire:model.defer="data.{{ $field["code"] }}"
                        placeholder="name@example.com"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>
            </div>
            @break

        @case('passport_series')
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
                </label>
                <input type="text"
                    wire:model.defer="data.{{ $field["code"] }}"
                    maxlength="5"
                    placeholder="XX XX"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                <p class="text-xs text-slate-400 pl-1">Только цифры</p>
            </div>
            @break

        @case('passport_number')
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
                </label>
                <input type="text"
                    wire:model.defer="data.{{ $field["code"] }}"
                    maxlength="6"
                    placeholder="XXXXXX"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            </div>
            @break

        @case('birthdate')
            @php $bdValue = $data[$field["code"]] ?? ''; @endphp
            <div class="space-y-2 custom-datepicker-wrapper" data-name="data.{{ $field["code"] }}" data-min="" data-max="{{ now()->subYears(18)->format('Y-m-d') }}">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
                </label>
                <div class="relative">
                    <input type="text"
                        value="{{ $bdValue ? \Carbon\Carbon::parse($bdValue)->format('d.m.Y') : '' }}"
                        placeholder="дд.мм.гггг"
                        class="datepicker-input w-full bg-slate-50 border border-slate-200 hover:border-indigo-400 rounded-xl px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-200 transition-all"
                        oninput="handleDateInput(this)"
                        onkeydown="if(event.key==='Enter'){toggleDatepicker(this.parentElement.querySelector('button'));event.preventDefault();}">
                    <button type="button" onclick="toggleDatepicker(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </button>
                    <div class="datepicker-popup hidden absolute z-50 mt-2 bg-white rounded-2xl shadow-xl border border-slate-100 p-4 w-80">
                        <div class="flex items-center justify-between mb-4">
                            <button type="button" onclick="datepickerPrevYear(this)" class="px-2 py-1 hover:bg-slate-100 rounded text-slate-500 text-xs font-bold">&laquo;</button>
                            <button type="button" onclick="datepickerPrevMonth(this)" class="px-2 py-1 hover:bg-slate-100 rounded text-slate-500 text-sm font-bold">&lsaquo;</button>
                            <button type="button" onclick="datepickerToggleMonthPicker(this)" class="datepicker-month font-bold text-slate-800 hover:bg-slate-100 px-2 py-1 rounded-lg text-sm"></button>
                            <button type="button" onclick="datepickerNextMonth(this)" class="px-2 py-1 hover:bg-slate-100 rounded text-slate-500 text-sm font-bold">&rsaquo;</button>
                            <button type="button" onclick="datepickerNextYear(this)" class="px-2 py-1 hover:bg-slate-100 rounded text-slate-500 text-xs font-bold">&raquo;</button>
                        </div>
                        <div class="datepicker-monthpicker hidden grid grid-cols-3 gap-2 mb-4">
                            <button type="button" onclick="datepickerSelectMonth(this, 0)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Янв</button>
                            <button type="button" onclick="datepickerSelectMonth(this, 1)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Фев</button>
                            <button type="button" onclick="datepickerSelectMonth(this, 2)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Мар</button>
                            <button type="button" onclick="datepickerSelectMonth(this, 3)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Апр</button>
                            <button type="button" onclick="datepickerSelectMonth(this, 4)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Май</button>
                            <button type="button" onclick="datepickerSelectMonth(this, 5)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Июн</button>
                            <button type="button" onclick="datepickerSelectMonth(this, 6)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Июл</button>
                            <button type="button" onclick="datepickerSelectMonth(this, 7)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Авг</button>
                            <button type="button" onclick="datepickerSelectMonth(this, 8)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Сен</button>
                            <button type="button" onclick="datepickerSelectMonth(this, 9)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Окт</button>
                            <button type="button" onclick="datepickerSelectMonth(this, 10)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Ноя</button>
                            <button type="button" onclick="datepickerSelectMonth(this, 11)" class="px-3 py-2 text-sm rounded-lg hover:bg-indigo-50 text-slate-700">Дек</button>
                        </div>
                        <div class="datepicker-yearpicker hidden grid grid-cols-3 gap-2 mb-4 max-h-48 overflow-y-auto"></div>
                        <div class="grid grid-cols-7 gap-1 mb-2">
                            <div class="text-center text-xs font-bold text-slate-400 py-1">Пн</div>
                            <div class="text-center text-xs font-bold text-slate-400 py-1">Вт</div>
                            <div class="text-center text-xs font-bold text-slate-400 py-1">Ср</div>
                            <div class="text-center text-xs font-bold text-slate-400 py-1">Чт</div>
                            <div class="text-center text-xs font-bold text-slate-400 py-1">Пт</div>
                            <div class="text-center text-xs font-bold text-slate-400 py-1">Сб</div>
                            <div class="text-center text-xs font-bold text-slate-400 py-1">Вс</div>
                        </div>
                        <div class="datepicker-days grid grid-cols-7 gap-1"></div>
                    </div>
                </div>
                <input type="hidden" wire:model.live="data.{{ $field["code"] }}" class="datepicker-hidden">
            </div>
            @break

        @case('address')
            <div class="md:col-span-2 space-y-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
                </label>
                <div class="relative" wire:ignore.self>
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <input type="text"
                        wire:model.live.debounce.300ms="data.{{ $field["code"] }}"
                        placeholder="г. Москва, ул. ..., д. ..., кв. ..."
                        autocomplete="off"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>
                @if(!empty($addressSuggestions) && $field["code"] === 'property_address')
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
                    {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
                </label>
                <textarea
                    wire:model.defer="data.{{ $field["code"] }}"
                    rows="3"
                    placeholder="{{ $field["hint"] ?? '' }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none"></textarea>
            </div>
            @break

        @case('file')
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
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
                        data-source="{{ $field["linked_to"] }}"
                        data-target="{{ $field["code"] }}">
                    <span class="text-slate-700">Совпадает с «{{ $field["linked_to"] }}»</span>
                </label>
                <input type="text"
                    wire:model.defer="data.{{ $field["code"] }}"
                    placeholder="{{ $field["hint"] ?? '' }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            </div>
            @break

        @default
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">
                    {{ $field["name"] }} @if($field["required"])<span class="text-red-500">*</span>@endif
                </label>
                <input type="text"
                    wire:model.defer="data.{{ $field["code"] }}"
                    placeholder="{{ $field["hint"] ?? '' }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            </div>
    @endswitch

    @error('data.'.$field["code"])
        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>
