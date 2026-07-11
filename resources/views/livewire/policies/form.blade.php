<div class="min-h-screen bg-gray-50">
    {{-- Flash messages --}}
    @if (session()->has('ok'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('ok') }}
        </div>
    @endif
    @if (session()->has('err'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('err') }}
        </div>
    @endif

    <div class="max-w-6xl mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">
                    Полис {{ $policy?->number ? '№ '.$policy->number : '(новый)' }}
                </h1>
                <a href="{{ route('policies.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                    ← Назад к списку
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Основная форма (2/3) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Выбор продукта --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Страховой продукт *</label>
                    <select wire:model.live="product_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">— выберите —</option>
                        @foreach($products as $pr)
                            <option value="{{ $pr->id }}">
                                {{ $pr->name }}
                                @if($pr->marketing_name) ({{ $pr->marketing_name }}) @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($product)

                    {{-- Декларации (pop-up при создании) --}}
                    @if($declarations->isNotEmpty() && !$policyId)
                        <div class="bg-purple-50 border border-purple-200 rounded-lg shadow p-6">
                            <h2 class="text-lg font-semibold text-purple-800 mb-3">📜 Декларации</h2>
                            @foreach($declarations as $dIdx => $declaration)
                                <div class="mb-4 pb-4 {{ !$loop->last ? 'border-b border-purple-200' : '' }}">
                                    <h3 class="font-medium text-purple-900 mb-2">{{ $declaration->name }}</h3>
                                    <div class="text-sm text-purple-700 bg-white rounded p-3 mb-2 max-h-48 overflow-y-auto">
                                        {{ $declaration->text }}
                                    </div>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model.defer="declarationAgreements.{{ $declaration->id }}"
                                            class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                        <span class="text-sm font-medium {{ $declaration->required ? 'text-red-700' : 'text-gray-600' }}">
                                            Подтверждаю {{ $declaration->required ? '(обязательно)' : '(необязательно)' }}
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Динамическая форма из полей продукта --}}
                    @foreach($fieldGroups as $group)
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $group->name }}</h2>
                            @if($group->description)
                                <p class="text-sm text-gray-500 mb-4">{{ $group->description }}</p>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($fields->where('group_id', $group->id) as $field)
                                    @include('livewire.policies.partials.field-render', ['field' => $field])
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    {{-- Поля без группы --}}
                    @php $ungroupedFields = $fields->whereNull('group_id'); @endphp
                    @if($ungroupedFields->isNotEmpty())
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Дополнительные поля</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($ungroupedFields as $field)
                                    @include('livewire.policies.partials.field-render', ['field' => $field])
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Покрытия (если нет в полях) --}}
                    @if($coverages->isNotEmpty())
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Покрытия и страховые суммы</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($coverages as $cov)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            {{ $cov->name }}
                                            @if($cov->required_for_calc)
                                                <span class="text-red-500">*</span>
                                            @endif
                                            @if(!empty($cov->risks))
                                                <span class="text-xs text-gray-400">({{ count($cov->risks) }} рисков)</span>
                                            @endif
                                        </label>

                                        @if($cov->type === 'range')
                                            <input type="number"
                                                wire:model.live="data.{{ $cov->code }}"
                                                min="{{ $cov->min_value ?? 0 }}"
                                                max="{{ $cov->max_value }}"
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2"
                                                placeholder="от {{ number_format($cov->min_value ?? 0) }} до {{ number_format($cov->max_value ?? 0) }}">
                                            <p class="text-xs text-gray-400 mt-1">
                                                {{ number_format($cov->min_value ?? 0) }} — {{ number_format($cov->max_value ?? 0) }} ₽
                                                · По умолч.: {{ number_format($cov->default_value ?? 0) }} ₽
                                            </p>

                                        @elseif($cov->type === 'constant')
                                            <div class="px-3 py-2 bg-gray-100 rounded-lg text-sm">
                                                {{ number_format($cov->default_value ?? 0) }} ₽ (фиксировано)
                                            </div>

                                        @elseif($cov->type === 'set')
                                            <select wire:model.live="data.{{ $cov->code }}"
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                                @foreach($cov->set_values ?? [] as $val)
                                                    <option value="{{ $val }}">{{ number_format($val) }} ₽</option>
                                                @endforeach
                                            </select>

                                        @elseif($cov->type === 'flag')
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox"
                                                    wire:model.live="data.{{ $cov->code }}"
                                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <span class="text-sm text-gray-700">Да</span>
                                            </label>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Пользовательские соглашения --}}
                    @if($agreements->isNotEmpty())
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Соглашения</h2>
                            @foreach($agreements as $aIdx => $agreement)
                                <label class="flex items-start gap-3 mb-3 cursor-pointer">
                                    <input type="checkbox"
                                        wire:model.defer="agreementChecks.{{ $aIdx }}"
                                        class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">
                                        {{ $agreement->text }}
                                        @if($agreement->required)
                                            <span class="text-red-500 font-semibold">*</span>
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    @endif

                    {{-- Комментарий --}}
                    <div class="bg-white rounded-lg shadow p-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Комментарий</label>
                        <textarea wire:model.defer="comment" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="Дополнительная информация..."></textarea>
                    </div>

                    {{-- Действия --}}
                    <div class="flex gap-3">
                        <button wire:click="saveDraft"
                            class="px-6 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-medium">
                            💾 Сохранить черновик
                        </button>
                        <button wire:click="issue"
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                            ✅ Выпустить полис
                        </button>
                    </div>

                @else
                    <div class="bg-white rounded-lg shadow p-12 text-center text-gray-400">
                        <p class="text-lg">Выберите продукт для начала оформления</p>
                    </div>
                @endif
            </div>

            {{-- Боковая панель: расчёт (1/3) --}}
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Расчёт премии</h3>
                    
                    <div class="text-3xl font-bold text-blue-600 mb-4">
                        {{ number_format($premium, 2, ',', ' ') }} ₽
                    </div>

                    <button wire:click="calculate"
                        class="w-full px-4 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-sm mb-4">
                        🔄 Пересчитать
                    </button>

                    @if(!empty($calculation['breakdown']))
                        <div class="border-t pt-4">
                            <h4 class="text-xs font-semibold text-gray-500 mb-2">ДЕТАЛИЗАЦИЯ</h4>
                            <pre class="text-xs text-gray-600 bg-gray-50 rounded p-3 overflow-x-auto">{{ json_encode($calculation['breakdown'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    @endif

                    @if(!empty($calculation['errors']))
                        <div class="mt-4 bg-red-50 border border-red-200 rounded p-3">
                            <h4 class="text-xs font-semibold text-red-700 mb-2">ОШИБКИ</h4>
                            @foreach($calculation['errors'] as $f => $m)
                                <p class="text-sm text-red-600">{{ $f }}: {{ $m }}</p>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($calculation['needs_approval']))
                        <div class="mt-4 bg-orange-50 border border-orange-200 rounded p-3">
                            <p class="text-sm text-orange-700 font-medium">⚠️ Требуется согласование</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
