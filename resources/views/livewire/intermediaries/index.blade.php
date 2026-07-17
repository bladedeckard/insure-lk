<div>
    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Посредники</h1>
            <p class="text-sm text-gray-500 mt-1">Страховые агенты и брокеры</p>
        </div>
        <a href="{{ route('intermediaries.create') }}"
           class="inline-flex items-center gap-2 bg-primary-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold
                  hover:bg-primary-700 transition-colors shadow-sm shadow-primary-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Создать
        </a>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-6">
        <div class="p-4">
            <div class="relative max-w-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input wire:model.live="search" placeholder="Поиск по наименованию, ИНН..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50 focus:bg-white transition-colors">
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Наименование</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">ИНН</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Договор</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Тип</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Статус</th>
                    <th class="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($items as $i)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-cyan-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $i->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-mono text-gray-600">{{ $i->inn }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600">{{ $i->contract_number }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $typeLabels = ['legal' => 'Юр. лицо', 'ip' => 'ИП', 'individual' => 'Физ. лицо'];
                        @endphp
                        <span class="text-sm text-gray-600">{{ $typeLabels[$i->type] ?? $i->type }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($i->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Активен
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                Неактивен
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-primary-600 hover:bg-primary-50 transition-colors"
                           href="{{ route('intermediaries.edit', $i) }}">
                            Открыть
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                        </svg>
                        <p class="text-sm text-gray-500">Посредников пока нет</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
</div>
