<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => '',
    'label' => '',
    'value' => '',
    'minDate' => null,
    'maxDate' => null,
    'required' => false,
    'hint' => '',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'name' => '',
    'label' => '',
    'value' => '',
    'minDate' => null,
    'maxDate' => null,
    'required' => false,
    'hint' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="space-y-2 custom-datepicker-wrapper" data-name="<?php echo e($name); ?>" data-min="<?php echo e($minDate); ?>" data-max="<?php echo e($maxDate); ?>">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($label): ?>
        <label class="block text-sm font-semibold text-slate-700">
            <?php echo e($label); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($required): ?><span class="text-red-500">*</span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </label>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                
                <div class="flex items-center justify-between mb-4">
                    <button type="button" onclick="datepickerPrevYear(this)" class="px-2 py-1 hover:bg-slate-100 rounded text-slate-500 text-xs font-bold">«</button>
                    <button type="button" onclick="datepickerPrevMonth(this)" class="px-2 py-1 hover:bg-slate-100 rounded text-slate-500 text-sm font-bold">‹</button>
                    <button type="button" onclick="datepickerToggleMonthPicker(this)" class="datepicker-month font-bold text-slate-800 hover:bg-slate-100 px-2 py-1 rounded-lg text-sm"></button>
                    <button type="button" onclick="datepickerNextMonth(this)" class="px-2 py-1 hover:bg-slate-100 rounded text-slate-500 text-sm font-bold">›</button>
                    <button type="button" onclick="datepickerNextYear(this)" class="px-2 py-1 hover:bg-slate-100 rounded text-slate-500 text-xs font-bold">»</button>
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
        <input type="hidden" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>" class="datepicker-hidden">
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hint): ?>
        <p class="text-xs text-indigo-600 bg-indigo-50 inline-block px-2 py-1 rounded mt-1 font-medium"><?php echo e($hint); ?></p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/components/custom-datepicker.blade.php ENDPATH**/ ?>