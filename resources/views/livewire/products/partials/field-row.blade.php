<div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
    <div class="flex items-center gap-3 flex-1">
        <div class="flex flex-col gap-0.5">
            <button wire:click="moveFieldUp({{ $index }})" class="text-gray-300 hover:text-gray-500 text-xs">▲</button>
            <button wire:click="moveFieldDown({{ $index }})" class="text-gray-300 hover:text-gray-500 text-xs">▼</button>
        </div>
        <div class="flex-1">
            <div class="flex items-center gap-2">
                <span class="font-medium text-sm text-gray-800">{{ $field['name'] }}</span>
                <span class="text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-600">
                    {{ $fieldTypes[$field['type']] ?? $field['type'] }}
                </span>
                @if($field['required'])
                    <span class="text-xs px-2 py-0.5 rounded bg-red-100 text-red-600">Обязательно</span>
                @endif
                @if(!empty($field['linked_to']))
                    <span class="text-xs px-2 py-0.5 rounded bg-purple-100 text-purple-600">↔ {{ $field['linked_to'] }}</span>
                @endif
            </div>
            <div class="text-xs text-gray-400 mt-0.5">
                <code>{{ $field['code'] }}</code>
                @if(!empty($field['mask'])) · Маска: {{ $field['mask'] }}@endif
                @if(!empty($field['regex'])) · Regex: {{ $field['regex'] }}@endif
                @if(!empty($field['hint'])) · 💬 {{ $field['hint'] }}@endif
            </div>
        </div>
    </div>
    <div class="flex gap-1 ml-3">
        <button wire:click="editField({{ $index }})" class="px-2 py-1 text-xs text-blue-600 hover:text-blue-800">Изменить</button>
        <button wire:click="removeField({{ $index }})" onclick="return confirm('Удалить поле?')"
            class="px-2 py-1 text-xs text-red-600 hover:text-red-800">Удалить</button>
    </div>
</div>
