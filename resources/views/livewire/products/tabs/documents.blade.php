<div class="space-y-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Настройка документов</h2>

    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
        <h3 class="text-sm font-semibold text-yellow-800 mb-2">📋 Доступные переменные для шаблонов</h3>
        <p class="text-xs text-yellow-700 mb-3">Используйте <code>${variable}</code> в DOCX-шаблонах. Нажмите на переменную — скопируется в буфер.</p>
        
        @php $vars = $this->getAvailableVariables(); @endphp
        @foreach($vars as $category => $categoryVars)
            @if(!empty($categoryVars))
                <div class="mb-3">
                    <h4 class="text-xs font-semibold text-yellow-800 mb-1">{{ $category }}</h4>
                    <div class="flex flex-wrap gap-1">
                        @foreach($categoryVars as $varCode => $varLabel)
                            <button type="button"
                                onclick="copyVar(this, '{{ $varCode }}')"
                                class="copy-btn inline-flex items-center gap-1 px-2 py-1 rounded border text-xs cursor-pointer transition-colors bg-white border-yellow-200 hover:bg-yellow-100 hover:border-yellow-400"
                                title="Нажмите чтобы скопировать">
                                <code class="font-semibold text-blue-600 copy-label">{{ '$' }}{{ '{' . $varCode . '}' }}</code>
                                <span class="text-green-600 copy-ok hidden">Скопировано!</span>
                                <span class="text-gray-400">— {{ $varLabel }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    {{-- Типы шаблонов --}}
    @foreach([
        ['key' => 'policy', 'label' => 'Полис', 'prop' => 'policy_template', 'toggle' => 'use_policy'],
        ['key' => 'kid', 'label' => 'КИД (Ключевой Информационный Документ)', 'prop' => 'kid_template', 'toggle' => 'use_kid'],
        ['key' => 'application', 'label' => 'Заявление', 'prop' => 'application_template', 'toggle' => 'use_application'],
    ] as $docType)
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex justify-between items-center mb-3">
                <div class="flex items-center gap-3">
                    <h3 class="font-medium text-gray-800">{{ $docType['label'] }}</h3>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="{{ $docType['toggle'] }}"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-600">Использовать</span>
                    </label>
                </div>
            </div>

            @if(${$docType['toggle']})
                {{-- Загрузка файла --}}
                <div class="mb-3">
                    <input type="file" wire:model="{{ $docType['prop'] }}" accept=".docx"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-400 mt-1">Формат: .docx с переменными ${variable}</p>
                </div>

                {{-- Существующие шаблоны --}}
                @php $typeDocs = collect($documents)->filter(fn($d) => $d['type'] === $docType['key'])->values(); @endphp
                @foreach($typeDocs as $doc)
                    @php $realIndex = collect($documents)->search(fn($d) => $d === $doc); @endphp
                    <div class="border border-gray-100 bg-gray-50 rounded p-3 mb-2">
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center gap-2">
                                <span class="text-green-600 text-sm">✅</span>
                                <span class="text-sm font-medium">{{ $doc['name'] }}</span>
                                @if(empty($doc['apply_conditions']))
                                    <span class="text-xs px-2 py-0.5 bg-green-100 text-green-700 rounded">По умолчанию</span>
                                @else
                                    <span class="text-xs px-2 py-0.5 bg-orange-100 text-orange-700 rounded">С условиями</span>
                                @endif
                            </div>
                            <button wire:click="removeDocument({{ $realIndex }})"
                                class="text-red-400 hover:text-red-600 text-sm">Удалить</button>
                        </div>

                        {{-- [5] Условия применения шаблона --}}
                        <div class="mt-2">
                            <details class="text-sm">
                                <summary class="cursor-pointer text-blue-600 hover:text-blue-800 text-xs font-medium">
                                    ⚙️ Условия применения шаблона
                                    <span class="text-gray-400 font-normal">(если пусто — применяется по умолчанию)</span>
                                </summary>
                                <div class="mt-2 space-y-2">
                                    @if(!empty($doc['apply_conditions']))
                                        @foreach($doc['apply_conditions'] as $cIdx => $condition)
                                            <div class="flex items-center gap-2 bg-white rounded p-2">
                                                @include('livewire.products.partials.field-code-select', [
                                                    'inputName' => 'documents.' . $realIndex . '.apply_conditions.' . $cIdx . '.field_code',
                                                    'allFields' => $fields ?? [],
                                                    'allCoverages' => $coverages ?? [],
                                                ])
                                                <select wire:model.defer="documents.{{ $realIndex }}.apply_conditions.{{ $cIdx }}.operator"
                                                    class="w-28 border border-gray-300 rounded px-2 py-1 text-sm">
                                                    <option value="=">Равно</option>
                                                    <option value="!=">Не равно</option>
                                                    <option value="in">В списке</option>
                                                    <option value="not_in">Не в списке</option>
                                                    <option value="contains">Содержит</option>
                                                </select>
                                                <input type="text"
                                                    wire:model.defer="documents.{{ $realIndex }}.apply_conditions.{{ $cIdx }}.value"
                                                    class="flex-1 border border-gray-300 rounded px-2 py-1 text-sm"
                                                    placeholder="Значение (через запятую для «в списке»)">
                                                <button wire:click="removeDocumentCondition({{ $realIndex }}, {{ $cIdx }})"
                                                    class="text-red-400 hover:text-red-600">✕</button>
                                            </div>
                                        @endforeach
                                    @endif
                                    <button wire:click="addDocumentCondition({{ $realIndex }})"
                                        class="text-xs text-blue-600 hover:text-blue-800">+ Добавить условие</button>
                                    <p class="text-xs text-gray-400">
                                        Пример: <code>bank</code> = <code>sber</code> → шаблон применяется только для Сбербанка.
                                        Все условия работают как «И» (должны выполняться все).
                                    </p>
                                </div>
                            </details>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    @endforeach
</div>
