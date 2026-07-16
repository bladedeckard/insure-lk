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

    {{-- [4] Порядок секций: группы + покрытия --}}
    @if(!empty($sectionOrder))
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
            <h3 class="text-sm font-semibold text-yellow-800 mb-2">📋 Порядок секций в форме полиса</h3>
            <p class="text-xs text-yellow-700 mb-3">Перемещайте секции стрелками. Этот порядок будет в форме создания полиса.</p>
            <div class="space-y-1">
                @foreach($sectionOrder as $sIdx => $section)
                    <div class="flex items-center gap-2 bg-white rounded p-2 border border-yellow-100">
                        <div class="flex flex-col gap-0.5">
                            <button wire:click="moveSectionUp({{ $sIdx }})" class="text-gray-300 hover:text-gray-600 text-xs">▲</button>
                            <button wire:click="moveSectionDown({{ $sIdx }})" class="text-gray-300 hover:text-gray-600 text-xs">▼</button>
                        </div>
                        <span class="text-sm font-medium text-gray-700">
                            @if($section === 'coverages')
                                📦 Покрытия и страховые суммы
                            @else
                                @php
                                    $matchedGroup = null;
                                    $sectionStr = (string)$section;
                                    $sectionInt = is_numeric($section) ? intval($section) : null;
                                    foreach ($fieldGroups as $fg) {
                                        $fgId = $fg['id'] ?? null;
                                        if ($fgId !== null && ((string)$fgId === $sectionStr || $fgId === $sectionInt)) {
                                            $matchedGroup = $fg;
                                            break;
                                        }
                                    }
                                @endphp
                                @if($matchedGroup)
                                    📁 {{ $matchedGroup['name'] }}
                                @else
                                    📁 <span class="text-orange-500 italic">Группа — сохраните продукт для обновления</span>
                                @endif
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

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

            {{-- Группы с полями (с сортировкой) --}}
            @foreach($fieldGroups as $gIndex => $group)
                <div class="border border-blue-200 bg-blue-50 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-3">
                            {{-- [4] Кнопки сортировки групп --}}
                            <div class="flex flex-col gap-0.5">
                                <button wire:click="moveGroupUp({{ $gIndex }})" class="text-gray-300 hover:text-gray-600 text-xs" title="Вверх">▲</button>
                                <button wire:click="moveGroupDown({{ $gIndex }})" class="text-gray-300 hover:text-gray-600 text-xs" title="Вниз">▼</button>
                            </div>
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
