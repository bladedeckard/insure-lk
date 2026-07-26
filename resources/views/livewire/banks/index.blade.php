<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Банки</h1>
            <p class="text-sm text-gray-500 mt-1">Управление банками-партнёрами</p>
        </div>
        <a href="{{ route('banks.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-xl hover:bg-primary-700 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Добавить банк
        </a>
    </div>

    @if(session('ok'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm">{{ session('ok') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <input type="text" wire:model.live="search" placeholder="Поиск по названию или коду..."
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50 focus:bg-white transition-colors">
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-left">
                <tr>
                    <th class="px-6 py-4 font-medium cursor-pointer" wire:click="sortBy('name')">
                        Название @if($sortField==='name')@if($sortDirection==='asc')↑@else↓@endif@endif
                    </th>
                    <th class="px-6 py-4 font-medium">Код</th>
                    <th class="px-6 py-4 font-medium">Баз.коэфф.</th>
                    <th class="px-6 py-4 font-medium">Констр.</th>
                    <th class="px-6 py-4 font-medium">Тариф</th>
                    <th class="px-6 py-4 font-medium">Коэфф.банка(ИМ)</th>
                    <th class="px-6 py-4 font-medium">Титул</th>
                    <th class="px-6 py-4 font-medium">Статус</th>
                    <th class="px-6 py-4 font-medium text-right">Действия</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($banks as $bank)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-900">{{ $bank->name }}</td>
                        <td class="px-6 py-3 text-gray-500"><code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">{{ $bank->code }}</code></td>
                        <td class="px-6 py-3 text-gray-500">{{ $bank->base_coefficient }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $bank->constructive_coefficient }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ number_format($bank->tariff_bank * 100, 2) }}%</td>
                        <td class="px-6 py-3 text-gray-500">{{ number_format($bank->bank_coefficient_property, 2) }}</td>
                        <td class="px-6 py-3">
                            @if($bank->title_disabled)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Недоступен</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Да</span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <button wire:click="toggleActive({{ $bank->id }})"
                                class="px-2 py-0.5 rounded-full text-xs font-medium {{ $bank->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $bank->is_active ? 'Да' : 'Нет' }}
                            </button>
                        </td>
                        <td class="px-6 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('banks.edit', $bank->id) }}" class="text-primary-600 hover:text-primary-800 text-xs">Ред.</a>
                                <button wire:click="delete({{ $bank->id }})" wire:confirm="Удалить банк?"
                                    class="text-red-500 hover:text-red-700 text-xs">Удал.</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="px-6 py-12 text-center text-gray-400">Банки не найдены</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100">{{ $banks->links() }}</div>
    </div>
</div>
