<div>
    {{-- Floating restriction errors --}}
    @if(!empty($restrictionErrors))
        <div class="fixed top-20 right-6 z-40 w-96" id="restriction-alert">
            <div class="bg-red-50 border border-red-200 rounded-xl shadow-lg p-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-red-800 mb-1">Ошибки при выпуске полиса</h4>
                        <ul class="space-y-0.5">
                            @foreach($restrictionErrors as $err)
                                <li class="text-xs text-red-700">{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button onclick="this.closest('#restriction-alert').remove()" class="text-red-400 hover:text-red-600 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('policies.index') }}" class="hover:text-primary-600 transition-colors">Полисы</a>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                <span class="text-gray-900">{{ $policy?->number ?? 'Новый' }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">
                Полис {{ $policy?->number ? '№ '.$policy->number : '(новый)' }}
            </h1>
        </div>
        <a href="{{ route('policies.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Назад к списку
        </a>
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
                        <h2 class="text-lg font-semibold text-purple-800 mb-3">Декларации</h2>
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

                {{-- [1] Рендеринг секций по sectionOrder --}}
                @php
                    $cfgJson = $product->config_json ?? [];
                    $rawOrder = $cfgJson['section_order'] ?? null;

                    $sectionOrder = [];
                    if (is_array($rawOrder) && count($rawOrder) > 0) {
                        foreach ($rawOrder as $s) {
                            if ($s === 'coverages') {
                                $sectionOrder[] = ['type' => 'coverages'];
                            } else {
                                $found = null;
                                foreach ($fieldGroups as $fg) {
                                    if ($fg->id == $s) {
                                        $found = $fg;
                                        break;
                                    }
                                }
                                if ($found) {
                                    $sectionOrder[] = ['type' => 'group', 'group' => $found];
                                }
                            }
                        }
                    }

                    if (empty($sectionOrder)) {
                        foreach ($fieldGroups as $fg) {
                            $sectionOrder[] = ['type' => 'group', 'group' => $fg];
                        }
                        $sectionOrder[] = ['type' => 'coverages'];
                    }

                    $renderedGroupIds = [];
                    foreach ($sectionOrder as $sec) {
                        if ($sec['type'] === 'group') {
                            $renderedGroupIds[] = $sec['group']->id;
                        }
                    }
                    foreach ($fieldGroups as $fg) {
                        if (!in_array($fg->id, $renderedGroupIds)) {
                            $sectionOrder[] = ['type' => 'group', 'group' => $fg];
                        }
                    }
                @endphp

                @foreach($sectionOrder as $section)
                    @if($section['type'] === 'coverages')
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

                    @elseif($section['type'] === 'group')
                        @php $group = $section['group']; @endphp
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $group->name }}</h2>
                            @if($group->description)
                                <p class="text-sm text-gray-500 mb-4">{{ $group->description }}</p>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($fields->where('group_id', $group->id) as $field)
                                    @include('livewire.policies.partials.field-render', ['field' => $field, 'product' => $product])
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach

                {{-- Поля без группы --}}
                @php $ungroupedFields = $fields->whereNull('group_id'); @endphp
                @if($ungroupedFields->isNotEmpty())
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Дополнительные поля</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($ungroupedFields as $field)
                                @include('livewire.policies.partials.field-render', ['field' => $field, 'product' => $product])
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
                                        <span class="text-red-500 font-semibold">* обязательно</span>
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

            @else
                <div class="bg-white rounded-lg shadow p-12 text-center text-gray-400">
                    <p class="text-lg">Выберите продукт для начала оформления</p>
                </div>
            @endif
        </div>

        {{-- Боковая панель: расчёт + действия (1/3) --}}
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Расчёт премии</h3>

                <div class="text-3xl font-bold text-blue-600 mb-4">
                    {{ number_format($premium, 2, ',', ' ') }} ₽
                </div>

                <button wire:click="calculate"
                    class="w-full px-4 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-sm mb-4">
                    Пересчитать
                </button>

                {{-- Действия --}}
                <div class="space-y-2 mb-4">
                    <button wire:click="issue"
                        class="w-full px-4 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-semibold text-sm transition-colors">
                        Выпустить полис
                    </button>
                    <button wire:click="saveDraft"
                        class="w-full px-4 py-2.5 bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-medium text-sm transition-colors">
                        Сохранить черновик
                    </button>
                </div>

                <div class="border-t pt-4">
                    @if(!empty($calculation['breakdown']))
                        <h4 class="text-xs font-semibold text-gray-500 mb-2">ДЕТАЛИЗАЦИЯ</h4>
                        <pre class="text-xs text-gray-600 bg-gray-50 rounded p-3 overflow-x-auto">{{ json_encode($calculation['breakdown'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
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
                            <p class="text-sm text-orange-700 font-medium">Требуется согласование</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
