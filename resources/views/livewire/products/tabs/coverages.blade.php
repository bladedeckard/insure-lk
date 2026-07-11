<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Покрытия и риски</h2>
        <button wire:click="addCoverage" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
            + Добавить покрытие
        </button>
    </div>

    @if (empty($coverages))
        <div class="text-center py-12 text-gray-400">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="mt-2">Нет покрытий. Добавьте первое покрытие.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($coverages as $index => $coverage)
                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <h3 class="font-semibold text-gray-800">{{ $coverage['name'] }}</h3>
                                <span class="px-2 py-0.5 text-xs rounded-full
                                    {{ $coverage['type'] === 'range' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $coverage['type'] === 'constant' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $coverage['type'] === 'set' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $coverage['type'] === 'flag' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                ">
                                    @if($coverage['type'] === 'range') Диапазон
                                    @elseif($coverage['type'] === 'constant') Константа
                                    @elseif($coverage['type'] === 'set') Множество
                                    @elseif($coverage['type'] === 'flag') Флаг
                                    @endif
                                </span>
                                @if($coverage['required_for_calc'])
                                    <span class="px-2 py-0.5 text-xs bg-red-100 text-red-700 rounded-full">Обязательно</span>
                                @endif
                            </div>
                            <div class="mt-1 text-sm text-gray-500">
                                Код: <code>{{ $coverage['code'] ?? '—' }}</code>
                                @if($coverage['type'] === 'range')
                                    · {{ number_format($coverage['min_value'] ?? 0) }} — {{ number_format($coverage['max_value'] ?? 0) }} ₽
                                    · По умолч.: {{ number_format($coverage['default_value'] ?? 0) }} ₽
                                @elseif($coverage['type'] === 'set')
                                    · Значения: {{ implode(', ', $coverage['set_values'] ?? []) }}
                                @elseif($coverage['type'] === 'constant')
                                    · {{ number_format($coverage['default_value'] ?? 0) }} ₽
                                @endif
                            </div>
                            @if(!empty($coverage['risks']))
                                <div class="mt-2 flex flex-wrap gap-1">
                                    @foreach($coverage['risks'] as $risk)
                                        <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-600 rounded">{{ $risk }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center gap-1 ml-4">
                            <button wire:click="moveCoverageUp({{ $index }})" class="p-1 text-gray-400 hover:text-gray-600" title="Вверх">↑</button>
                            <button wire:click="moveCoverageDown({{ $index }})" class="p-1 text-gray-400 hover:text-gray-600" title="Вниз">↓</button>
                            <button wire:click="editCoverage({{ $index }})" class="px-3 py-1 text-sm text-blue-600 hover:text-blue-800">Редактировать</button>
                            <button wire:click="removeCoverage({{ $index }})" 
                                onclick="return confirm('Удалить покрытие?')"
                                class="px-3 py-1 text-sm text-red-600 hover:text-red-800">Удалить</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
