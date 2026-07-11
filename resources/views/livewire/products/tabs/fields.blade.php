<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Настройка полей формы полиса</h2>
        <div class="flex gap-2">
            <button wire:click="addFieldGroup" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">
                + Группа
            </button>
            <button wire:click="addField" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                + Поле
            </button>
        </div>
    </div>

    @if(empty($fieldGroups) && empty($fields))
        <div class="text-center py-12 text-gray-400">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
            </svg>
            <p class="mt-2">Нет полей. Создайте группу и добавьте поля.</p>
        </div>
    @else
        <div class="space-y-6">
            {{-- Поля без группы --}}
            @php $ungroupedFields = collect($fields)->filter(fn($f) => empty($f['group_id']))->values(); @endphp
            @if($ungroupedFields->isNotEmpty())
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-500 mb-3">Без группы</h3>
                    @foreach($ungroupedFields as $idx => $field)
                        @php $realIndex = collect($fields)->search(fn($f) => $f === $field); @endphp
                        @include('livewire.products.partials.field-row', ['field' => $field, 'index' => $realIndex, 'fieldTypes' => $fieldTypes])
                    @endforeach
                </div>
            @endif

            {{-- Группы с полями --}}
            @foreach($fieldGroups as $gIndex => $group)
                <div class="border border-blue-200 bg-blue-50 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-3">
                            <input type="text" wire:model.defer="fieldGroups.{{ $gIndex }}.name"
                                class="font-semibold text-gray-800 border-none bg-transparent focus:ring-0 text-base">
                            <span class="text-xs text-gray-400">{{ $group['code'] ?? '' }}</span>
                        </div>
                        <button wire:click="removeFieldGroup({{ $gIndex }})"
                            onclick="return confirm('Удалить группу со всеми полями?')"
                            class="text-red-500 hover:text-red-700 text-sm">Удалить группу</button>
                    </div>

                    @if(!empty($group['description']))
                        <input type="text" wire:model.defer="fieldGroups.{{ $gIndex }}.description"
                            class="text-sm text-gray-500 border-none bg-transparent w-full mb-2"
                            placeholder="Описание группы...">
                    @endif

                    @php $groupFields = collect($fields)->filter(fn($f) => $f['group_id'] == $group['id'])->values(); @endphp
                    @foreach($groupFields as $field)
                        @php $realIndex = collect($fields)->search(fn($f) => $f === $field); @endphp
                        @include('livewire.products.partials.field-row', ['field' => $field, 'index' => $realIndex, 'fieldTypes' => $fieldTypes])
                    @endforeach
                </div>
            @endforeach
        </div>
    @endif
</div>
