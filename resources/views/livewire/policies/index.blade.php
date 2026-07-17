<div>
    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Полисы</h1>
            <p class="text-sm text-gray-500 mt-1">Управление страховыми полисами</p>
        </div>
        <a href="{{ route('policies.create') }}"
           class="inline-flex items-center gap-2 bg-primary-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold
                  hover:bg-primary-700 transition-colors shadow-sm shadow-primary-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Новый полис
        </a>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-6">
        <div class="p-4">
            <div class="relative max-w-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input wire:model.live="search" placeholder="Поиск по номеру полиса..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50 focus:bg-white transition-colors">
            </div>
        </div>
    </div>

    {{-- Policies table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Номер</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Продукт</th>
                    <th class="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Премия</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Статус</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Дата</th>
                    <th class="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($items as $p)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="text-sm font-medium text-gray-900 font-mono">{{ $p->number ?? '—' }}</span>
                        @if(!$p->number)
                            <div class="text-xs text-gray-400">Черновик</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-700">{{ $p->product->name }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="text-sm font-semibold text-gray-900">{{ number_format($p->premium, 2, ',', ' ') }} ₽</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $statusConfig = [
                                'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'border' => 'border-gray-200', 'dot' => 'bg-gray-400', 'label' => 'Черновик'],
                                'pending_approval' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'dot' => 'bg-amber-500', 'label' => 'На согласовании'],
                                'issued' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500', 'label' => 'Выдан'],
                                'cancelled' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-200', 'dot' => 'bg-red-500', 'label' => 'Аннулирован'],
                            ];
                            $s = $statusConfig[$p->status] ?? $statusConfig['draft'];
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $s['bg'] }} {{ $s['text'] }} border {{ $s['border'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $s['dot'] }}"></span>
                            {{ $s['label'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-500">{{ $p->created_at->format('d.m.Y') }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-primary-600 hover:bg-primary-50 transition-colors"
                               href="{{ route('policies.edit', $p) }}">
                                Открыть
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                                </svg>
                            </a>
                            @can('policies.delete')
                                <button onclick="confirmAction({
                                    type: 'danger',
                                    title: 'Удаление полиса',
                                    message: 'Вы уверены, что хотите удалить полис {{ $p->number ?? '#' . $p->id }}? Это действие нельзя отменить.',
                                    confirmText: 'Удалить',
                                    onConfirm: function() { @this.call('delete', {{ $p->id }}); }
                                })"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                    </svg>
                                    Удалить
                                </button>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        <p class="text-sm text-gray-500">Полисов пока нет</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
</div>
