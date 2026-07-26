{{-- Field Modal — с условиями видимости (Гибрид A + B) --}}
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" wire:key="field-modal">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">
                    {{ $editingFieldIndex >= 0 ? 'Редактировать поле' : 'Новое поле' }}
                </h3>
                <button wire:click="$set('showFieldModal', false)" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>

            <div class="space-y-4">
                {{-- Основные свойства --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Название поля *</label>
                        <input type="text" wire:model.defer="fld_name"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                            placeholder="Фамилия страхователя">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Код *</label>
                        <input type="text" wire:model.defer="fld_code"
                            class="w-full font-mono border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="policyholder_last_name">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Тип поля</label>
                        <select wire:model="fld_type"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            @foreach($fieldTypes as $type => $label)
                                <option value="{{ $type }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Группа</label>
                        <select wire:model.defer="fld_group_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="">— Без группы —</option>
                            @foreach($fieldGroups as $group)
                                <option value="{{ $group['id'] }}">{{ $group['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if($fld_type === 'select')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Варианты (JSON или "ключ=значение")</label>
                        <textarea wire:model.defer="fld_options" rows="4"
                            class="w-full font-mono text-sm border border-gray-300 rounded-lg px-3 py-2"
                            placeholder='[{"value":"kv","label":"Квартира"}]
или:
kv=Квартира
dom=Дом'></textarea>
                    </div>
                @endif

                @if($fld_type === 'linked_field')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Связано с полем (код)</label>
                        <input type="text" wire:model.defer="fld_linked_to"
                            class="w-full font-mono border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="policyholder_address">
                        <p class="text-xs text-gray-400 mt-1">Будет показана галочка «Совпадает с...», при активации значение копируется</p>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Описание</label>
                        <input type="text" wire:model.defer="fld_description"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="Введите фамилию кириллицей">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Подсказка (tooltip)</label>
                        <input type="text" wire:model.defer="fld_hint"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="Например: как в паспорте">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Маска ввода</label>
                        <input type="text" wire:model.defer="fld_mask"
                            class="w-full font-mono border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="99 99 или +7 (999) 999-99-99">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Regex-валидация</label>
                        <input type="text" wire:model.defer="fld_regex"
                            class="w-full font-mono border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="/^[А-Яа-яЁё\s]+$/">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Сообщение при ошибке валидации</label>
                    <input type="text" wire:model.defer="fld_error_message"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2"
                        placeholder="Введите корректное значение">
                </div>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model.defer="fld_required"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700">Обязательно для заполнения</span>
                </label>

                {{-- ═══════════════════════════════════════════════════════════ --}}
                {{-- УСЛОВИЯ ВИДИМОСТИ (Гибрид A + B) --}}
                {{-- ═══════════════════════════════════════════════════════════ --}}
                <div class="border-t pt-4 mt-4">
                    <h4 class="text-base font-semibold text-gray-800 mb-3">👁️ Условия видимости поля</h4>
                    <p class="text-xs text-gray-500 mb-4">
                        Если не заданы — поле показывается всегда. Можно комбинировать оба уровня.
                    </p>

                    {{-- Уровень A: Привязка к покрытиям --}}
                    @if(!empty($coverages))
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <h5 class="text-sm font-semibold text-blue-800 mb-2">
                                Уровень A: Показывать при покрытиях
                            </h5>
                            <p class="text-xs text-blue-600 mb-3">
                                Поле появится только когда хотя бы одно из выбранных покрытий активно.
                                Не выбирайте ничего — поле будет показываться всегда.
                            </p>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($coverages as $covIdx => $cov)
                                    @if(!empty($cov['code']))
                                        <label class="flex items-center gap-2 cursor-pointer bg-white rounded px-3 py-2 border border-blue-100 hover:border-blue-300">
                                            <input type="checkbox"
                                                value="{{ $cov['id'] ?? $covIdx }}"
                                                wire:model.defer="fld_coverage_ids"
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <div>
                                                <span class="text-sm font-medium text-gray-700">{{ $cov['name'] }}</span>
                                                <span class="text-xs text-gray-400 ml-1">({{ $cov['code'] }})</span>
                                            </div>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                            @if(!empty($fld_coverage_ids))
                                <p class="text-xs text-blue-700 mt-2">
                                    ✅ Поле будет видно при:
                                    @foreach($fld_coverage_ids as $cId)
                                        @php $c = collect($coverages)->firstWhere(fn($c) => ($c['id'] ?? '') == $cId); @endphp
                                        @if($c)
                                            <span class="inline-block px-1.5 py-0.5 bg-blue-100 rounded text-xs mr-1">{{ $c['name'] }}</span>
                                        @endif
                                    @endforeach
                                </p>
                            @endif
                        </div>
                    @endif

                    {{-- Уровень B: Расширенные условия --}}
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <h5 class="text-sm font-semibold text-purple-800 mb-2">
                            Уровень B: Расширенные условия
                        </h5>
                        <p class="text-xs text-purple-600 mb-3">
                            Тонкая настройка: показывать когда [Поле] [Оператор] [Значение].
                            Работает с любыми полями формы и покрытиями.
                        </p>

                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-sm text-gray-600">Логика между условиями:</span>
                            <select wire:model.defer="fld_visibility_logic"
                                class="border border-gray-300 rounded px-2 py-1 text-sm">
                                <option value="and">И (все условия)</option>
                                <option value="or">ИЛИ (любое условие)</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            @foreach($fld_visibility_conditions as $vcIdx => $vc)
                                <div class="flex items-center gap-2 bg-white rounded p-2 border border-purple-100">
                                    <select wire:model.defer="fld_visibility_conditions.{{ $vcIdx }}.field_code"
                                        class="w-40 border border-gray-300 rounded px-2 py-1 text-sm">
                                        <option value="">— Поле —</option>
                                        @foreach($coverages ?? [] as $cov)
                                            @if(!empty($cov['code']))
                                                <option value="{{ $cov['code'] }}">{{ $cov['name'] }} ({{ $cov['code'] }})</option>
                                            @endif
                                        @endforeach
                                        @foreach($fields ?? [] as $f)
                                            <option value="{{ $f['code'] }}">{{ $f['name'] }} ({{ $f['code'] }})</option>
                                        @endforeach
                                    </select>
                                    <select wire:model.defer="fld_visibility_conditions.{{ $vcIdx }}.operator"
                                        class="w-28 border border-gray-300 rounded px-2 py-1 text-sm">
                                        <option value="=">= равно</option>
                                        <option value="!=">!= не равно</option>
                                        <option value=">">> больше</option>
                                        <option value="<">< меньше</option>
                                        <option value="in">в списке</option>
                                        <option value="not_empty">не пустое</option>
                                        <option value="empty">пустое</option>
                                    </select>
                                    <input type="text"
                                        wire:model.defer="fld_visibility_conditions.{{ $vcIdx }}.value"
                                        class="flex-1 border border-gray-300 rounded px-2 py-1 text-sm"
                                        placeholder="Значение">
                                    <button wire:click="removeVisibilityCondition({{ $vcIdx }})"
                                        class="text-red-400 hover:text-red-600 text-lg leading-none">&times;</button>
                                </div>
                            @endforeach
                        </div>

                        <button wire:click="addVisibilityCondition"
                            class="mt-2 text-sm text-purple-600 hover:text-purple-800 font-medium">
                            + Добавить условие
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                <button wire:click="$set('showFieldModal', false)"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Отмена</button>
                <button wire:click="saveField"
                    class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Сохранить</button>
            </div>
        </div>
    </div>
</div>
