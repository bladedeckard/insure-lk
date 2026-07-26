<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Расчёт премии</h2>
    </div>

    @if($isMortgage)
        {{-- ═══ ИПОТЕЧНЫЙ ПРОДУКТ: Тарифы и коэффициенты ═══ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Базовые тарифы --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 text-xs">1</span>
                    Базовые тарифы
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Несчастный случай (Жизнь), %</label>
                        <input type="number" wire:model="tariff_life" step="0.01" min="0" max="100"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500">
                        <p class="text-xs text-gray-400 mt-1">Базовый тариф по НС</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Имущество (с конструктивом), %</label>
                        <input type="number" wire:model="tariff_property_constructive" step="0.01" min="0" max="100"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Имущество (без конструктива), %</label>
                        <input type="number" wire:model="tariff_property_no_constructive" step="0.01" min="0" max="100"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Титул, %</label>
                        <input type="number" wire:model="tariff_title" step="0.01" min="0" max="100"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>
            </div>

            {{-- Перестрахование РНПК --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600 text-xs">2</span>
                    Перестрахование РНПК (имущество), %
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Квартира</label>
                        <input type="number" wire:model="reinsurance_apartment" step="0.0001" min="0" max="1"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Каменное перекрытие</label>
                        <input type="number" wire:model="reinsurance_stone" step="0.0001" min="0" max="1"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Смешанное перекрытие</label>
                        <input type="number" wire:model="reinsurance_mixed" step="0.0001" min="0" max="1"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Деревянное перекрытие</label>
                        <input type="number" wire:model="reinsurance_wood" step="0.0001" min="0" max="1"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Титул (перестрахование)</label>
                        <input type="number" wire:model="reinsurance_title" step="0.0001" min="0" max="1"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>
            </div>

            {{-- Максимальная нагрузка --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-green-100 flex items-center justify-center text-green-600 text-xs">3</span>
                    Настройки расчёта
                </h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Максимальная нагрузка, %</label>
                    <input type="number" wire:model="max_load_percent" step="0.1" min="0" max="100"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500">
                    <p class="text-xs text-gray-400 mt-1">Максимальная суммарная нагрузка (РВД + КВ + прибыль)</p>
                </div>
            </div>

            {{-- Справка --}}
            <div class="bg-blue-50 rounded-2xl border border-blue-200 p-6">
                <h3 class="text-sm font-semibold text-blue-800 mb-3">Как работает расчёт</h3>
                <div class="text-sm text-blue-700 space-y-2">
                    <p><strong>Имущество:</strong> MAX(стандартный, перестрахование) × Страховая сумма / 100</p>
                    <p><strong>Жизнь (НС):</strong> MAX(стандартный, перестрахование) × Страховая сумма / 100</p>
                    <p><strong>Титул:</strong> MAX(стандартный, перестрахование) × Страховая сумма / 100</p>
                    <p class="text-xs text-blue-600 mt-3">Итого = Сумма имущ + Сумма жизнь + Сумма титул</p>
                </div>
            </div>
        </div>

    @else
        {{-- ═══ СТАНДАРТНЫЙ ПРОДУКТ: Формула ═══ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Формула (2/3) --}}
            <div class="lg:col-span-2 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Expression (Symfony Expression Language)
                    </label>
                    <textarea wire:model.defer="formula_expression" rows="12"
                        class="w-full font-mono text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="(sum_construct * 0.1504 + sum_finish * 0.3478) / 100 * k_rent"></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        Поддерживаются: +, -, *, /, %, тернарный оператор (cond ? a : b), функции max(), min(), round(), abs(), if()
                    </p>
                </div>

                {{-- Тестовый калькулятор --}}
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">🧪 Тестовый калькулятор</h3>

                    @php $vars = $this->getFormulaVariables(); @endphp
                    @if(!empty($vars['used']))
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-3">
                            @foreach($vars['used'] as $varName)
                                <div>
                                    <label class="text-xs text-gray-500">{{ $varName }}</label>
                                    <input type="number" wire:model.defer="formula_test_values.{{ $varName }}"
                                        class="w-full border border-gray-300 rounded px-2 py-1 text-sm"
                                        placeholder="1000">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="flex items-center gap-3">
                        <button wire:click="testFormula" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                            Рассчитать
                        </button>
                        @if($formula_test_result)
                            <span class="text-sm {{ str_starts_with($formula_test_result, '✅') ? 'text-green-700' : 'text-red-700' }}">
                                {{ $formula_test_result }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Панель переменных (1/3) --}}
            <div class="space-y-4">
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">📋 Доступные переменные</h3>

                    @php $vars = $this->getFormulaVariables(); @endphp

                    @if(!empty($vars['available']))
                        <div class="space-y-2">
                            @foreach($vars['available'] as $var)
                                <div class="flex items-center justify-between text-xs">
                                    <code class="text-blue-600 cursor-pointer hover:text-blue-800"
                                        onclick="navigator.clipboard.writeText('{{ $var['code'] }}')"
                                        title="Нажмите чтобы скопировать">
                                        {{ $var['code'] }}
                                    </code>
                                    <span class="text-gray-400 truncate ml-2">{{ $var['name'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-gray-400">Сначала добавьте покрытия на вкладке «Покрытия и риски»</p>
                    @endif
                </div>

                <div class="border border-yellow-200 bg-yellow-50 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-yellow-800 mb-2">💡 Пример формулы</h3>
                    <pre class="text-xs text-yellow-700 whitespace-pre-wrap">(
  sum_construct * 0.1504 +
  sum_finish * 0.3478 +
  sum_movable * 0.752 +
  sum_go * 0.7 +
  (electricity ? max(sum_construct, sum_finish, sum_movable) : 0) * 0.03
) / 100 * k_rent +
  exp_keys * 0.42 / 100 +
  exp_rent * 0.56 / 100 +
  exp_transport * 0.28 / 100 +
  exp_return * 0.2 / 100</pre>
                </div>
            </div>
        </div>
    @endif
</div>
