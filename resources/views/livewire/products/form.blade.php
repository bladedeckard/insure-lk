<div>
    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('products.index') }}" class="hover:text-primary-600 transition-colors">Продукты</a>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                <span class="text-gray-900">{{ $productId ? $name : 'Новый продукт' }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $productId ? 'Редактирование продукта' : 'Новый продукт' }}
            </h1>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('products.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                Отмена
            </a>
            <button wire:click="saveDraft"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 text-white rounded-xl text-sm font-semibold hover:bg-amber-600 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                Черновик
            </button>
            <button wire:click="saveAndPublish"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition-colors shadow-sm shadow-emerald-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Опубликовать
            </button>
        </div>
    </div>

        {{-- Tabs Navigation --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
            <div class="border-b border-gray-100">
                <nav class="flex overflow-x-auto -mb-px">
                    @foreach ($tabs as $key => $label)
                        <button
                            wire:click="setTab('{{ $key }}')"
                            class="px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 transition-colors
                                {{ $activeTab === $key
                                    ? 'border-primary-500 text-primary-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                        >
                            {{ $label }}
                        </button>
                    @endforeach
                </nav>
            </div>
        </div>

        {{-- Tab Content --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            
            {{-- TAB 1: Basic Information --}}
            @if ($activeTab === 'basic')
                @include('livewire.products.tabs.basic', [
                    'currencies' => $currencies,
                    'intermediaries' => $intermediaries
                ])
            @endif

            {{-- TAB 2: Coverages --}}
            @if ($activeTab === 'coverages')
                @include('livewire.products.tabs.coverages')
            @endif

            {{-- TAB 3: Formula --}}
            @if ($activeTab === 'formula')
                @include('livewire.products.tabs.formula')
            @endif

            {{-- TAB 4: Order Settings --}}
            @if ($activeTab === 'order')
                @include('livewire.products.tabs.order', ['numerators' => $numerators])
            @endif

            {{-- TAB 5: Fields --}}
            @if ($activeTab === 'fields')
                @include('livewire.products.tabs.fields', ['fieldTypes' => $fieldTypes])
            @endif

            {{-- TAB 6: Documents --}}
            @if ($activeTab === 'documents')
                @include('livewire.products.tabs.documents')
            @endif

            {{-- TAB 7: Advanced --}}
            @if ($activeTab === 'advanced')
                @include('livewire.products.tabs.advanced', ['operators' => $operators])
            @endif

            {{-- TAB 8: Log --}}
            @if ($activeTab === 'log')
                @include('livewire.products.tabs.log')
            @endif

        </div>

    {{-- Coverage Modal --}}
    @if ($showCoverageModal)
        @include('livewire.products.modals.coverage-modal')
    @endif

    {{-- Field Modal --}}
    @if ($showFieldModal)
        @include('livewire.products.modals.field-modal', ['fieldTypes' => $fieldTypes])
    @endif
</div>
