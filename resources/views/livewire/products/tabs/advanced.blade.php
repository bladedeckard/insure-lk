<div class="space-y-8">
    <h2 class="text-xl font-semibold text-gray-800">Дополнительные настройки</h2>

    {{-- ═══ Блок: Настройки ═══ --}}
    <div class="bg-gray-50 rounded-lg p-4 space-y-4">
        <h3 class="text-sm font-semibold text-gray-700">⚙️ Настройки</h3>

        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" wire:model="send_email"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            <span class="text-sm text-gray-700">Отправлять договор на e-mail после выпуска</span>
        </label>

        @if($send_email)
            <div class="ml-8">
                <label class="text-xs text-gray-500">Из какого поля брать e-mail</label>
                <input type="text" wire:model.defer="email_field"
                    class="w-64 border border-gray-300 rounded px-2 py-1 text-sm"
                    placeholder="policyholder_email">
            </div>
        @endif

        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" wire:model="allow_edit_start_date"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            <span class="text-sm text-gray-700">Разрешить редактировать дату начала договора</span>
        </label>
    </div>

    {{-- ═══ Блок: Андеррайтинг ═══ --}}
    <div class="bg-orange-50 rounded-lg p-4 space-y-4">
        <div class="flex justify-between items-center">
            <h3 class="text-sm font-semibold text-orange-800">🛡️ Андеррайтинг</h3>
            <button wire:click="addUnderwritingRestriction" class="px-3 py-1 bg-orange-200 text-orange-800 rounded text-sm hover:bg-orange-300">
                + Добавить ограничение
            </button>
        </div>

        <p class="text-xs text-orange-700">
            Ограничения андеррайтинга блокируют оформление или отправляют полис на согласование.
        </p>

        <div>
            <label class="text-xs text-gray-500">Email-адреса для уведомлений о согласовании (через запятую)</label>
            <input type="text" wire:model.defer="approval_emails"
                class="w-full border border-gray-300 rounded px-2 py-1 text-sm"
                placeholder="underwriting@company.ru, chief@company.ru">
        </div>

        @foreach($underwritingRestrictions as $rIndex => $restriction)
            <div class="border border-orange-200 bg-white rounded-lg p-3">
                <div class="flex justify-between items-start mb-2">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 flex-1">
                        <div>
                            <label class="text-xs text-gray-500">Сообщение</label>
                            <input type="text" wire:model.defer="underwritingRestrictions.{{ $rIndex }}.error_message"
                                class="w-full border border-gray-300 rounded px-2 py-1 text-sm"
                                placeholder="Требуется согласование андеррайтера">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Действие</label>
                            <select wire:model.defer="underwritingRestrictions.{{ $rIndex }}.action"
                                class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                                <option value="block">Блокировать</option>
                                <option value="approval">На согласование</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Логика</label>
                            <select wire:model.defer="underwritingRestrictions.{{ $rIndex }}.logic"
                                class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                                <option value="and">И (все условия)</option>
                                <option value="or">ИЛИ (любое)</option>
                            </select>
                        </div>
                    </div>
                    <button wire:click="removeUnderwritingRestriction({{ $rIndex }})"
                        class="ml-2 text-red-400 hover:text-red-600 text-sm">Удалить</button>
                </div>
                <div class="space-y-2">
                    @foreach($restriction['conditions'] as $cIndex => $condition)
                        <div class="flex items-center gap-2 bg-gray-50 rounded p-2">
                            @include('livewire.products.partials.field-code-select', [
                                'inputName' => 'underwritingRestrictions.' . $rIndex . '.conditions.' . $cIndex . '.field_code',
                                'allFields' => $fields ?? [],
                                'allCoverages' => $coverages ?? [],
                            ])
                            <select wire:model.defer="underwritingRestrictions.{{ $rIndex }}.conditions.{{ $cIndex }}.operator"
                                class="w-28 border border-gray-300 rounded px-2 py-1 text-sm">
                                @foreach($operators as $op => $label)
                                    <option value="{{ $op }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <input type="text" wire:model.defer="underwritingRestrictions.{{ $rIndex }}.conditions.{{ $cIndex }}.value"
                                class="flex-1 border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Значение">
                            <button wire:click="removeUnderwritingCondition({{ $rIndex }}, {{ $cIndex }})"
                                class="text-red-400 hover:text-red-600">✕</button>
                        </div>
                    @endforeach
                    <button wire:click="addUnderwritingCondition({{ $rIndex }})"
                        class="text-xs text-blue-600 hover:text-blue-800">+ Условие</button>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ═══ Блок: Пользовательские соглашения ═══ --}}
    <div class="bg-green-50 rounded-lg p-4 space-y-4">
        <div class="flex justify-between items-center">
            <h3 class="text-sm font-semibold text-green-800">✅ Пользовательские соглашения</h3>
            <button wire:click="addAgreement" class="px-3 py-1 bg-green-200 text-green-800 rounded text-sm hover:bg-green-300">
                + Добавить
            </button>
        </div>

        @foreach($agreements as $aIndex => $agreement)
            <div class="border border-green-200 bg-white rounded-lg p-3">
                <div class="flex justify-between items-start mb-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model.defer="agreements.{{ $aIndex }}.required"
                            class="rounded border-gray-300 text-green-600">
                        <span class="text-sm">{{ $agreement['required'] ? 'Обязательно' : 'Необязательно' }}</span>
                    </label>
                    <button wire:click="removeAgreement({{ $aIndex }})" class="text-red-400 hover:text-red-600 text-sm">Удалить</button>
                </div>
                <textarea wire:model.defer="agreements.{{ $aIndex }}.text" rows="2"
                    class="w-full border border-gray-300 rounded px-2 py-1 text-sm"
                    placeholder="Настоящим подтверждаю, что ознакомлен с правилами страхования..."></textarea>
            </div>
        @endforeach
    </div>

    {{-- ═══ Блок: Декларации ═══ --}}
    <div class="bg-purple-50 rounded-lg p-4 space-y-4">
        <div class="flex justify-between items-center">
            <h3 class="text-sm font-semibold text-purple-800">📜 Декларации</h3>
            <button wire:click="addDeclaration" class="px-3 py-1 bg-purple-200 text-purple-800 rounded text-sm hover:bg-purple-300">
                + Добавить
            </button>
        </div>
        <p class="text-xs text-purple-700">Декларации показываются как pop-up при нажатии «Создать полис».</p>

        @foreach($declarations as $dIndex => $declaration)
            <div class="border border-purple-200 bg-white rounded-lg p-3">
                <div class="flex justify-between items-center mb-2">
                    <input type="text" wire:model.defer="declarations.{{ $dIndex }}.name"
                        class="font-medium border-none bg-transparent text-sm" placeholder="Название декларации">
                    <button wire:click="removeDeclaration({{ $dIndex }})" class="text-red-400 hover:text-red-600 text-sm">Удалить</button>
                </div>
                <div class="flex gap-4 mb-2">
                    <label class="flex items-center gap-1 text-xs cursor-pointer">
                        <input type="checkbox" wire:model.defer="declarations.{{ $dIndex }}.is_active"
                            class="rounded border-gray-300 text-purple-600">
                        Активна
                    </label>
                    <label class="flex items-center gap-1 text-xs cursor-pointer">
                        <input type="checkbox" wire:model.defer="declarations.{{ $dIndex }}.required"
                            class="rounded border-gray-300 text-purple-600">
                        Обязательно
                    </label>
                </div>
                <textarea wire:model.defer="declarations.{{ $dIndex }}.text" rows="4"
                    class="w-full border border-gray-300 rounded px-2 py-1 text-sm"
                    placeholder="Текст декларации..."></textarea>
            </div>
        @endforeach
    </div>
</div>
