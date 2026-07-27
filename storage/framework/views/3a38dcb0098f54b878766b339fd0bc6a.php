<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => '',
    'label' => '',
    'options' => [],
    'placeholder' => 'Выберите...',
    'value' => '',
    'required' => false,
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
    'options' => [],
    'placeholder' => 'Выберите...',
    'value' => '',
    'required' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="space-y-2 custom-select-wrapper" data-name="<?php echo e($name); ?>">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($label): ?>
        <label class="block text-sm font-semibold text-slate-700">
            <?php echo e($label); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($required): ?><span class="text-red-500">*</span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </label>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <div class="relative">
        <button type="button" onclick="toggleCustomSelect(this)"
            class="w-full bg-slate-50 hover:bg-white border border-slate-200 hover:border-indigo-400 text-left rounded-xl px-4 py-3 flex items-center justify-between transition-all focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <span class="custom-select-label text-slate-400"><?php echo e($placeholder); ?></span>
            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div class="custom-select-dropdown hidden absolute z-50 w-full mt-2 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($options) > 5): ?>
                <div class="p-2 border-b border-slate-100">
                    <input type="text" oninput="filterCustomSelect(this)" placeholder="Поиск..." class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="max-h-60 overflow-y-auto custom-select-options">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div onclick="selectCustomOption(this, '<?php echo e($opt['value']); ?>', '<?php echo e(addslashes($opt['label'])); ?>')"
                        data-value="<?php echo e($opt['value']); ?>"
                        class="custom-select-option px-4 py-3 cursor-pointer hover:bg-indigo-50 transition-colors flex flex-col border-b border-slate-50 last:border-0">
                        <span class="font-medium text-slate-800"><?php echo e($opt['label']); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <input type="hidden" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>" class="custom-select-hidden">
    </div>
</div>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/components/custom-select.blade.php ENDPATH**/ ?>