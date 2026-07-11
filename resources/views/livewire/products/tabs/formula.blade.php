<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Формула расчёта премии</h2>
    </div>

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
</div>
