@props([
    'name' => '',
    'label' => '',
    'value' => '',
    'minDate' => null,
    'maxDate' => null,
    'required' => false,
    'hint' => '',
])

<div class="space-y-2 custom-datepicker-wrapper" data-name="{{ $name }}" data-min="{{ $minDate }}" data-max="{{ $maxDate }}">
    @if($label)
        <label class="block text-sm font-semibold text-slate-700">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <div class="relative">
        <div class="relative">
            <button type="button" onclick="toggleDatepicker(this)"
                class="w-full bg-slate-50 hover:bg-white border border-slate-200 hover:border-indigo-400 text-left rounded-xl px-4 py-3 flex items-center justify-between transition-all focus:outline-none focus:ring-2 focus:ring-indigo-200">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="datepicker-label text-slate-400">Выберите дату</span>
                </div>
            </button>
            <div class="datepicker-popup hidden absolute z-50 mt-2 bg-white rounded-2xl shadow-xl border border-slate-100 p-4 w-[320px]">
                {{-- Header with month/year navigation --}}
                <div class="flex items-center justify-between mb-4">
                    <button type="button" onclick="datepickerPrevYear(this)" class="px-2 py-1 hover:bg-slate-100 rounded text-slate-500 text-xs font-bold">«</button>
                    <button type="button" onclick="datepickerPrevMonth(this)" class="px-2 py-1 hover:bg-slate-100 rounded text-slate-500 text-sm font-bold">‹</button>
                    <button type="button" onclick="datepickerToggleMonthPicker(this)" class="datepicker-month font-bold text-slate-800 hover:bg-slate-100 px-2 py-1 rounded-lg text-sm"></button>
                    <button type="button" onclick="datepickerNextMonth(this)" class="px-2 py-1 hover:bg-slate-100 rounded text-slate-500 text-sm font-bold">›</button>
                    <button type="button" onclick="datepickerNextYear(this)" class="px-2 py-1 hover:bg-slate-100 rounded text-slate-500 text-xs font-bold">»</button>
                </div>
                {{-- Month picker (hidden by default) --}}
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
                {{-- Year picker (hidden by default) --}}
                <div class="datepicker-yearpicker hidden grid grid-cols-3 gap-2 mb-4 max-h-48 overflow-y-auto"></div>
                {{-- Days grid --}}
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
        <input type="hidden" name="{{ $name }}" value="{{ $value }}" class="datepicker-hidden">
    </div>
    @if($hint)
        <p class="text-xs text-indigo-600 bg-indigo-50 inline-block px-2 py-1 rounded mt-1 font-medium">{{ $hint }}</p>
    @endif
</div>
