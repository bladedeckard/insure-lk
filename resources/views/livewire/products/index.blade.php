<div>
    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Страховые продукты</h1>
            <p class="text-sm text-gray-500 mt-1">Управление страховыми продуктами и конструктор</p>
        </div>
        <a href="{{ route('products.create') }}"
           class="inline-flex items-center gap-2 bg-primary-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold
                  hover:bg-primary-700 transition-colors shadow-sm shadow-primary-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Создать продукт
        </a>
    </div>

    {{-- Products table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Код</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Название</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Калькулятор</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Статус</th>
                    <th class="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($items as $p)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-mono font-medium bg-gray-100 text-gray-700">
                            {{ $p->code }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-semibold text-gray-900">{{ $p->name }}</div>
                        @if($p->marketing_name)
                            <div class="text-xs text-gray-500">{{ $p->marketing_name }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600">{{ class_basename($p->calculator_class) }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $prodStatus = match($p->status ?? 'draft') {
                                'published' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'label' => 'Опубликован'],
                                'archived' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'border' => 'border-gray-200', 'label' => 'В архиве'],
                                default => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'label' => 'Черновик'],
                            };
                        @endphp
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $prodStatus['bg'] }} {{ $prodStatus['text'] }} border {{ $prodStatus['border'] }}">
                            {{ $prodStatus['label'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-primary-600 hover:bg-primary-50 transition-colors"
                           href="{{ route('products.edit', $p) }}">
                            Открыть
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-2.25-1.313M21 7.5v2.25m0-2.25l-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3l2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75l2.25-1.313M12 21.75V19.5m0 2.25l-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25"/>
                        </svg>
                        <p class="text-sm text-gray-500">Продуктов пока нет</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
</div>
