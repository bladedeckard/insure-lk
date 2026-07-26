@props([
    'name' => '',
    'label' => '',
    'description' => '',
    'checked' => false,
])

<label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:border-indigo-300 hover:bg-indigo-50/30 transition-all">
    <input type="checkbox" wire:model.live="{{ $name }}" {{ $checked ? 'checked' : '' }}
        class="el-checkbox">
    <div class="flex-1">
        <span class="font-semibold text-sm text-slate-800">{{ $label }}</span>
        @if($description)
            <span class="text-xs text-slate-400 ml-2">{{ $description }}</span>
        @endif
    </div>
</label>
