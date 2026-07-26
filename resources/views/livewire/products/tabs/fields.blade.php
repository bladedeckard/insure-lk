<div class="space-y-6">
    <input type="hidden" wire:model.live="dragAction" id="dragAction">

    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Настройка полей формы полиса</h2>
        <div class="flex gap-2">
            <button wire:click="addFieldGroup" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">
                + Группа
            </button>
            <button wire:click="addField" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm">
                + Поле
            </button>
        </div>
    </div>

    @if(!empty($sectionOrder))
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 mb-4">
            <h3 class="text-sm font-semibold text-slate-700 mb-2">Порядок секций в форме полиса</h3>
            <p class="text-xs text-slate-500 mb-3">Перетаскивайте для изменения порядка</p>
            <div class="space-y-1">
                @foreach($sectionOrder as $sIdx => $section)
                    <div class="flex items-center gap-2 bg-white rounded-xl p-3 border border-slate-200 hover:border-primary/30 cursor-move transition-all"
                         draggable="true"
                         ondragstart="dragStartSection(event, {{ $sIdx }})"
                         ondragover="dragOverSection(event)"
                         ondrop="dropSection(event, {{ $sIdx }})"
                         ondragend="dragEndSection(event)">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                        </svg>
                        <span class="text-sm font-medium text-slate-700">
                            @if($section === 'coverages')
                                <span class="text-indigo-600">📦</span> Покрытия и страховые суммы
                            @else
                                @php
                                    $matchedGroup = null;
                                    foreach ($fieldGroups as $fg) {
                                        if (($fg['id'] ?? null) != null && (string)($fg['id']) === (string)$section) {
                                            $matchedGroup = $fg;
                                            break;
                                        }
                                    }
                                @endphp
                                @if($matchedGroup)
                                    <span class="text-emerald-600">📁</span> {{ $matchedGroup['name'] }}
                                @else
                                    <span class="text-orange-500 italic">📁 Группа — сохраните для обновления</span>
                                @endif
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if(!empty($coverages))
        <div class="border border-indigo-200 bg-indigo-50/50 rounded-2xl p-4">
            <div class="flex justify-between items-center mb-3">
                <div>
                    <h3 class="text-sm font-semibold text-indigo-700">📦 Покрытия и страховые суммы</h3>
                    <p class="text-xs text-indigo-500 mt-1">Эти поля автоматически добавляются в форму полиса. Перетаскивайте покрытия в ряды.</p>
                </div>
                <button wire:click="openRowModal(0, 'coverages')" class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 text-xs font-medium">
                    + Ряд
                </button>
            </div>

            @php $unrowedCoverages = collect($coverages)->filter(fn($c) => empty($c['row_id'])); @endphp
            @if($unrowedCoverages->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                    @foreach($unrowedCoverages as $covIndex => $cov)
                        @php $realIndex = collect($coverages)->search(fn($c) => $c === $cov); @endphp
                        <div class="flex items-center gap-2 bg-white rounded-xl p-3 border border-indigo-200 hover:border-indigo-400 cursor-move transition-all"
                             draggable="true"
                             ondragstart="dragStartCoverage(event, {{ $realIndex }})"
                             ondragend="dragEndCoverage(event)">
                            <svg class="w-4 h-4 text-indigo-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                            </svg>
                            <div class="w-2 h-2 rounded-full bg-indigo-400 flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <span class="text-sm font-medium text-slate-800 block truncate">{{ $cov["name"] }}</span>
                                <span class="text-xs text-slate-400">{{ $cov["type"] === 'range' ? 'Сумма' : ($cov["type"] === 'flag' ? 'Чекбокс' : $cov["type"]) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @foreach($coverageRows as $rIndex => $row)
                @php $rowCoverages = collect($coverages)->filter(fn($c) => ($c['row_id'] ?? null) === $row['id']); @endphp
                <div class="mb-3 bg-white rounded-xl border border-indigo-200 p-3"
                     ondragover="dragOverCoverageRow(event)"
                     ondrop="dropCoverageToRow(event, '{{ $row['id'] }}')">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-indigo-500 uppercase tracking-wider">Ряд {{ $rIndex + 1 }}</span>
                        <button wire:click="removeRow(0, {{ $rIndex }})"
                            onclick="return confirm('Удалить ряд?')"
                            class="text-red-400 hover:text-red-600 text-xs">Удалить ряд</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-{{ $row['cols'] ?? 2 }} gap-3 min-h-[40px]">
                        @if($rowCoverages->isEmpty())
                            <div class="text-center py-4 text-xs text-indigo-300 border-2 border-dashed border-indigo-200 rounded-lg col-span-full">
                                Перетащите покрытия сюда
                            </div>
                        @endif
                        @foreach($rowCoverages as $cov)
                            @php $realIndex = collect($coverages)->search(fn($c) => $c === $cov); @endphp
                            <div class="flex items-center gap-2 bg-indigo-50 rounded-lg p-2 border border-indigo-200 hover:border-indigo-400 cursor-move transition-all"
                                 draggable="true"
                                 ondragstart="dragStartCoverage(event, {{ $realIndex }})"
                                 ondragend="dragEndCoverage(event)">
                                <svg class="w-3 h-3 text-indigo-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                </svg>
                                <div class="w-2 h-2 rounded-full bg-indigo-400 flex-shrink-0"></div>
                                <div class="flex-1 min-w-0">
                                    <span class="text-sm font-medium text-slate-800 block truncate">{{ $cov["name"] }}</span>
                                    <span class="text-xs text-slate-400">{{ $cov["type"] === 'range' ? 'Сумма' : ($cov["type"] === 'flag' ? 'Чекбокс' : $cov["type"]) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
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
        <div class="space-y-6" id="fields-list">
            @php $ungroupedFields = collect($fields)->filter(fn($f) => empty($f['group_id']))->values(); @endphp
            @if($ungroupedFields->isNotEmpty())
                <div class="border border-slate-200 rounded-2xl p-4">
                    <h3 class="text-sm font-semibold text-slate-500 mb-3">Без группы</h3>
                    @foreach($ungroupedFields as $idx => $field)
                        @php $realIndex = collect($fields)->search(fn($f) => $f === $field); @endphp
                        <div class="flex items-center gap-2 bg-white rounded-xl p-3 border border-slate-200 hover:border-primary/30 cursor-move transition-all mb-2"
                             draggable="true"
                             ondragstart="dragStartField(event, {{ $realIndex }}, 0)"
                             ondragover="dragOverField(event)"
                             ondrop="dropField(event, {{ $realIndex }}, 0)"
                             ondragend="dragEndField(event)">
                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                            </svg>
                            @include('livewire.products.partials.field-row', ['field' => $field, 'index' => $realIndex, 'fieldTypes' => $fieldTypes])
                        </div>
                    @endforeach
                </div>
            @endif

            @foreach($fieldGroups as $gIndex => $group)
                <div class="border border-indigo-200 bg-indigo-50/50 rounded-2xl p-4"
                     ondragover="dragOverGroup(event)"
                     ondrop="dropToGroup(event, {{ $group['id'] ?? 0 }})">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-3">
                            <div class="flex flex-col gap-0.5">
                                <button wire:click="moveGroupUp({{ $gIndex }})" class="text-gray-300 hover:text-gray-600 text-xs" title="Вверх">▲</button>
                                <button wire:click="moveGroupDown({{ $gIndex }})" class="text-gray-300 hover:text-gray-600 text-xs" title="Вниз">▼</button>
                            </div>
                            <input type="text" wire:model.defer="fieldGroups.{{ $gIndex }}.name"
                                class="font-semibold text-gray-800 border-none bg-transparent focus:ring-0 text-base"
                                placeholder="Название группы">
                            <span class="text-xs text-gray-400">{{ $group['code'] ?? '' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click="openRowModal({{ $gIndex }})" class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 text-xs font-medium">
                                + Ряд
                            </button>
                            <button wire:click="removeFieldGroup({{ $gIndex }})"
                                onclick="return confirm('Удалить группу со всеми полями?')"
                                class="text-red-500 hover:text-red-700 text-sm">Удалить группу</button>
                        </div>
                    </div>

                    @if(!empty($group['description']))
                        <input type="text" wire:model.defer="fieldGroups.{{ $gIndex }}.description"
                            class="text-sm text-gray-500 border-none bg-transparent w-full mb-2"
                            placeholder="Описание группы...">
                    @endif

                    @php
                        $groupRows = $group['rows'] ?? [];
                        $groupFields = collect($fields)->filter(fn($f) => $f['group_id'] == $group['id']);
                    @endphp

                    @php $unrowedFields = $groupFields->filter(fn($f) => empty($f['row_id']))->values(); @endphp
                    @if($unrowedFields->isNotEmpty())
                        <div class="mb-3">
                            <div class="text-xs text-slate-400 mb-2">Поля без ряда</div>
                            @foreach($unrowedFields as $field)
                                @php $realIndex = collect($fields)->search(fn($f) => $f === $field); @endphp
                                <div class="flex items-center gap-2 bg-white rounded-xl p-3 border border-slate-200 hover:border-primary/30 cursor-move transition-all mb-2"
                                     draggable="true"
                                     ondragstart="dragStartField(event, {{ $realIndex }}, {{ $group['id'] ?? 0 }})"
                                     ondragover="dragOverField(event)"
                                     ondrop="dropField(event, {{ $realIndex }}, {{ $group['id'] ?? 0 }})"
                                     ondragend="dragEndField(event)">
                                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                    </svg>
                                    @include('livewire.products.partials.field-row', ['field' => $field, 'index' => $realIndex, 'fieldTypes' => $fieldTypes])
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @foreach($groupRows as $rIndex => $row)
                        @php $rowFields = $groupFields->filter(fn($f) => ($f['row_id'] ?? null) == ($row['id'] ?? null))->values(); @endphp
                        <div class="mb-3 bg-white rounded-xl border border-slate-200 p-3"
                             ondragover="dragOverField(event)"
                             ondrop="dropToRow(event, {{ $group['id'] ?? 0 }}, '{{ $row['id'] ?? '' }}')">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Ряд {{ $rIndex + 1 }}</span>
                                <button wire:click="removeRow({{ $gIndex }}, {{ $rIndex }})"
                                    class="text-red-400 hover:text-red-600 text-xs">Удалить ряд</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-{{ $row['cols'] ?? 2 }} gap-3">
                                @foreach($rowFields as $field)
                                    @php $realIndex = collect($fields)->search(fn($f) => $f === $field); @endphp
                                    <div class="flex items-center gap-2 bg-slate-50 rounded-lg p-2 border border-slate-200 hover:border-primary/30 cursor-move transition-all"
                                         draggable="true"
                                         ondragstart="dragStartField(event, {{ $realIndex }}, {{ $group['id'] ?? 0 }})"
                                         ondragover="dragOverField(event)"
                                         ondrop="dropField(event, {{ $realIndex }}, {{ $group['id'] ?? 0 }})"
                                         ondragend="dragEndField(event)">
                                        <svg class="w-3 h-3 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                        </svg>
                                        @include('livewire.products.partials.field-row', ['field' => $field, 'index' => $realIndex, 'fieldTypes' => $fieldTypes, 'compact' => true])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    @if($unrowedFields->isEmpty() && empty($groupRows))
                        <div class="text-center py-6 text-sm text-slate-400 border-2 border-dashed border-slate-200 rounded-xl">
                            Перетащите поля сюда
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- Модальное окно создания ряда --}}
    @if($showRowModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center" style="backdrop-filter: blur(4px);"
             x-data
             @keydown.escape.window="$wire.set('showRowModal', false)">
            <div class="absolute inset-0 bg-black/50" wire:click="$set('showRowModal', false)"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 mx-4">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-semibold text-slate-800">Новый ряд</h3>
                    <button wire:click="$set('showRowModal', false)" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-slate-700 mb-3">Количество столбцов</label>
                    <div class="flex gap-3">
                        @for($i = 1; $i <= 4; $i++)
                            <button wire:click="$set('rowColsCount', {{ $i }})"
                                class="flex-1 py-3 px-2 rounded-xl border-2 text-center transition-all
                                {{ $rowColsCount === $i
                                    ? 'border-emerald-500 bg-emerald-50 text-emerald-700 shadow-sm'
                                    : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300' }}">
                                <div class="text-2xl font-bold mb-1">{{ $i }}</div>
                                <div class="text-xs">столб{{ $i === 1 ? '' : ($i < 5 ? 'а' : 'ов') }}</div>
                            </button>
                        @endfor
                    </div>
                </div>

                {{-- Превью --}}
                <div class="mb-5 p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="text-xs text-slate-500 mb-2">Превью:</div>
                    <div class="grid grid-cols-{{ $rowColsCount }} gap-2">
                        @for($i = 1; $i <= $rowColsCount; $i++)
                            <div class="h-10 bg-white border-2 border-dashed border-slate-300 rounded-lg flex items-center justify-center text-xs text-slate-400">
                                Столбец {{ $i }}
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="flex gap-3">
                    <button wire:click="$set('showRowModal', false)"
                        class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 text-sm font-medium transition-colors">
                        Отмена
                    </button>
                    <button wire:click="confirmAddRow"
                        class="flex-1 px-4 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 text-sm font-medium transition-colors">
                        Создать ряд
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
