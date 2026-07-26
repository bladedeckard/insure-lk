@props([
    'name' => '',
    'label' => '',
    'options' => [],
    'placeholder' => 'Выберите...',
    'value' => '',
    'required' => false,
])

<div class="space-y-2 custom-select-wrapper" data-name="{{ $name }}">
    @if($label)
        <label class="block text-sm font-semibold text-slate-700">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <div class="relative">
        <button type="button" onclick="toggleCustomSelect(this)"
            class="w-full bg-slate-50 hover:bg-white border border-slate-200 hover:border-indigo-400 text-left rounded-xl px-4 py-3 flex items-center justify-between transition-all focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <span class="custom-select-label text-slate-400">{{ $placeholder }}</span>
            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div class="custom-select-dropdown hidden absolute z-50 w-full mt-2 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden">
            @if(count($options) > 5)
                <div class="p-2 border-b border-slate-100">
                    <input type="text" oninput="filterCustomSelect(this)" placeholder="Поиск..." class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>
            @endif
            <div class="max-h-60 overflow-y-auto custom-select-options">
                @foreach($options as $opt)
                    <div onclick="selectCustomOption(this, '{{ $opt['value'] }}', '{{ addslashes($opt['label']) }}')"
                        data-value="{{ $opt['value'] }}"
                        class="custom-select-option px-4 py-3 cursor-pointer hover:bg-indigo-50 transition-colors flex flex-col border-b border-slate-50 last:border-0">
                        <span class="font-medium text-slate-800">{{ $opt['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        <input type="hidden" name="{{ $name }}" value="{{ $value }}" class="custom-select-hidden">
    </div>
</div>
